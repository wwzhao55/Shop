<?php
/***@author hetutu 
	**/
	namespace App\Http\Controllers\Shop;
	use App\Http\Controllers\Controller;
	use View,Auth,Session,DB,Redirect;
	use App\Models\Weixin\Account,App\Models\Customer\Customer,App\Models\Brand\Brand,App\Models\Shop\Shopinfo;
	use App\Models\Order\Order,EasyWeChat\Foundation\Application;
	class VipController extends CommonController{
		
		public function getIndex(){
			$account = Account::where('brand_id',$this->brand_id)->first();
			$options = [
			    'debug'  => true,
			    'app_id' => $account->appid,
			    'secret' => $account->appsecret,
			    'token'  => $account->token,
			    'aes_key' =>$account->encodingaeskey, // 可选
			    'log' => [
			        'level' => 'debug',
			        'file'  => storage_path('logs/easywechat.log'), // XXX: 绝对路径！！！！
			    ],
			    'oauth' => [
				     'scopes'   => ['snsapi_userinfo'],
				     'callback' => '/shop/front/checkoauth/'.$this->brand_id.'/'.$this->shop_id.'/vip',
				],
			];
			$app = new Application($options);
			$oauth = $app->oauth;
			if(!$this->openid){
		        //return Redirect::guest('/shop/login');
		        return $oauth->redirect();
			}
			$Vip = new Customer;
			$Vip->setTable($this->brand_name.'_customers');
			$vip = $Vip->where('openid',$this->openid)->first();
			$order = new Order;
			$order->setTable($this->brand_name.'_order'); 
			$order = $order->where('customer_id',$vip->id)->get();
			$unpay = $order->where('status','1')->count();
			$unsend = $order->where('status','2')->count();
			$payed = $order->where('status','3')->count(); 
			$finished = $order->where('status','4')->count();
			
			
			$js = $app->js;
			$shop = Shopinfo::find($this->shop_id);
			$shopaddress = $shop->shop_province.$shop->shop_city.$shop->shop_district.$shop->shop_address_detail;
			$shopaddress = str_replace('市辖区','',$shopaddress);
			return View::make('shop.vip.index',array(
				'nickname' => $vip->nickname,
				'headimgurl' => $vip->headimgurl,
				'score' => $vip->score,//以下未获取
				'unpay' => $unpay,
				'unsend' => $unsend,
				'payed' => $payed,
				'finished' => $finished,
				'js'=>$js,
				'shopaddress'=>$shopaddress
				));
		}

	}
