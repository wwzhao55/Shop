<?php 
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Weixin\Account,App\Models\Customer\Customer,App\Models\Brand\Brand,App\Models\Shop\Shopinfo;
use Request,EasyWeChat\Foundation\Application,EasyWeChat\Message\Text,Session,DB;


class WeixinController extends Controller
{

	public function __construct()
	{
		# code...
	}
	const EARTH_RADIUS = 6378.137;

	private function rad($d){
	   return $d * M_PI / 180.0;
	}

	private function GetDistance($lat1, $lng1, $lat2, $lng2)
	{
	   $radLat1 = $this->rad($lat1);
	   $radLat2 = $this->rad($lat2);
	   $a = $radLat1 - $radLat2;
	   $b = $this->rad($lng1) - $this->rad($lng2);
	   $s = 2 * asin(sqrt(pow(sin($a/2),2) + cos($radLat1)*cos($radLat2)*pow(sin($b/2),2)));
	   $s = $s * self::EARTH_RADIUS;
	   $s = round($s * 10000) / 10000;
	   return $s;
	}

	public function anyWxapi() {
		$originalId = Request::input('originalId');
		$options = array();
		if($originalId){
			$public_info = Account::where('originalId',$originalId)->first();
		}
		if((!$public_info) || (!$originalId)){
			echo "";
			exit;
		}

		$options = [
		    'debug'  => true,
		    'app_id' => $public_info->appid,
		    'secret' => $public_info->appsecret,
		    'token'  => $public_info->token,
		    'aes_key' =>$public_info->encodingaeskey, // 可选
		    'log' => [
		        'level' => 'debug',
		        'file'  => storage_path('logs/easywechat.log'), // XXX: 绝对路径！！！！
		    ],
		];
		$customer = new Customer;
		$app = new Application($options);
		//从项目实例中得到服务端应用实例。
		$server = $app->server;
		//处理消息
		$server->setMessageHandler(function ($message) use($public_info,$app,$customer){
			$openid = $message->FromUserName;
			$brand_id = $public_info->brand_id;
			$brand = Brand::where('id',$brand_id)->first();
            $brand_name = $brand->brandname;
		    switch ($message->MsgType) {
		        case 'event':
		            switch($message->Event) {
		            	case 'subscribe':
		            		# 订阅事件
		            		$userService = $app->user;
		            		$userInfo = $userService->get($openid);
		            		//存储用户信息	
                			$old_customer = DB::table($brand_name.'_customers')->where('openid',$openid)->get();
                			if (count($old_customer)) {	
	                			DB::table($brand_name.'_customers')->where('openid',$openid)->update(['follow_weixin' => $public_info->name,'status' => 1,'nickname' => $userInfo['nickname'],'sex' => $userInfo['sex'],'headimgurl' => $userInfo['headimgurl'],'city' => $userInfo['city'],'province' => $userInfo['province'],'country' => $userInfo['country'],'openid' => $openid,'public_id' => $public_info->id,'updated_at' => time()]);
			                } else {
				                DB::table($brand_name.'_customers')->insert(['follow_weixin' => $public_info->name,'status' => 1,'nickname' => $userInfo['nickname'],'sex' => $userInfo['sex'],'headimgurl' => $userInfo['headimgurl'],'city' => $userInfo['city'],'province' => $userInfo['province'],'country' => $userInfo['country'],'openid' => $openid,'public_id' => $public_info->id,'created_at' => time(),'updated_at' => time()]);
                			}
		            		return new Text(['content'=>$public_info->subscribe_text]);
		            		break;
		            	case 'unsubscribe':
			                DB::table($brand_name.'_customers')->where('openid',$openid)->update(['status' => 0,'updated_at' => time()]);
                            break;
                        case 'LOCATION':
                        	$latitude = $message->Latitude;
                        	$longitude = $message->Longitude;
                        	$Customer = new Customer;
                        	$Customer->setTable($brand_name.'_customers');
                        	$customer = $Customer->where('openid',$openid)->first();
                        	$shop_list = Shopinfo::where('brand_id',$brand_id)->where('status',1)->where('open_weishop',1)->get();
                        	if(count($shop_list)){
                        		$shop_id = 0;
	                        	$min = 0;
	                        	foreach ($shop_list as $key => $shop) {
	                        		$temp = $this->GetDistance($latitude,$longitude,$shop->latitude,$shop->longitude);
	                        		if($key == 0){
	                        			$min = $temp;
	                        		}else{
	                        			if($temp<$min){
	                        				$min = $temp;
	                        				$shop_id = $shop->id;
	                        			}
	                        		}		
	                        	}
	                        	$customer->shop_id = $shop_id;
	                        	$customer->setTable($brand_name.'_customers')->save();
                        	}
                        	break;
		            }
		            break;		      
		        default:
		        	return new Text(['content' => '即将上线，敬请期待']);
		            break;
		    }
		});		
		$response = $server->serve();
		// 将响应输出
		return $response;
	}
}