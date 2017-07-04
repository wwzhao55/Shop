<?php 
#----update by hetutu
namespace App\Http\Controllers\Brand;
use Illuminate\Http\Request;
use App\Models\Weixin\Account,EasyWeChat\Foundation\Application;
use View,Auth,Session,Redirect,Validator,Response;
class PublicmanageController extends CommonController{

	public function getIndex(){
		$brand_id = Auth::user()->brand_id;
		$public_info = Account::where('brand_id',$brand_id)->first();
		$welcome = $public_info->subscribe_text;
		$menu = json_decode($public_info->menu);
		return View::make('brand.publicmanage.index',array(
			'subscribe_text'=>$welcome,
			'menu' => $menu,
			));
	}


	public function postEdit(Request $request){
		$rules = array(
            //'subscribe_text'=>'required',
            'menu' =>'required',
            'menu_changed'=>'required',
        );
        $message = array(
            "required"=> ":attribute 不能为空",
        );

        $attributes = array(
            'subscribe_text'=>'欢迎语',
            'menu' =>'自定义菜单',
            'menu_changed'=>'菜单改变项',
        );
        $validator = Validator::make(
            $request->all(), 
            $rules,
            $message,
            $attributes
        );
        if ($validator->fails()) {
            $warnings = $validator->messages();
            $show_warning = $warnings->first();
            return Response::Json(['status'=>'error','message'=>$show_warning]);
        }
        $account = Account::where('brand_id',Auth::user()->brand_id)->first();
        $account->subscribe_text = $request->subscribe_text;
        if($request->menu_changed){
        	$buttons = json_decode($request->menu,true);
        	foreach ($buttons as $key => $value) {
        		if(array_key_exists('sub_button', $value)){
        			foreach ($value['sub_button'] as $k => $btn) {
        				if(!filter_var($btn['url'], FILTER_VALIDATE_URL)){
	        				//url验证失败
	        				return Response::Json(['status'=>'error','message'=>'存在非法的url']);
	        			}
        			}
        		}else{
        			if(!filter_var($value['url'], FILTER_VALIDATE_URL)){
        				//url验证失败
        				return Response::Json(['status'=>'error','message'=>'url格式不正确，请确保']);
        			}
        		}
        	}
        	$account->menu = $request->menu;
        	//设置菜单
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
			];
			$app = new Application($options);
			$menu = $app->menu;
			//$result = $menu->add($buttons);
			//var_dump($result);
        }
        $account->save();
        return Response::Json(['status'=>'success','message'=>'修改成功']);
	}


}