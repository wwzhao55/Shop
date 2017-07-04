<?php namespace App\Http\Controllers\Home;
//edit by xuxuxu
use App\Http\Controllers\Controller;
use App\Models\User,App\Models\Shop\Shopadmin,App\Models\Shop\Shopstaff;
use Illuminate\Http\Request;
use View,Redirect,Validation,Session,Cache,Auth,Hash,Response,Carbon\Carbon,Message,Cookie;
/**
* 登录 注册 注销 短信验证码 忘记密码
*/
class AuthController extends Controller
{
	
	function __construct()
	{
		//登录验证，仅用于登出操作
		$this->middleware('auth',['only'=>'getLogout']);
	}

	/*登录页面模板加载*/
	public function getLogin(){
		if (Auth::check()) {//如果登录直接跳转首页
			return Redirect::to('/');
		}       
		return View::make('auth.login');//否则进入登录页
	}

	/*登录操作*/
	public function postLogin(Request $request){
		//参数验证，规则可加，验证码暂未启用
		$this->validate($request, [
		    'account' => 'required|max:255',
		    'password' => 'required|min:6',
		    //'captcha' => 'required|captcha',
		]);

		$data = $request->except('remember');
		$remember = $request->input('remember')=="on"?true:false;//用于记住我选项
		for($role=0;$role<4;$role++){
			//尝试四种角色登录，其他角色（顾客）不能登录后台
			if (Auth::attempt(['account'=>$data['account'],'password'=>$data['password'],'role'=>$role],$remember)) {
            	switch (Auth::user()->role) {
            		case '0':
            			break;
            		case '1':
            			$shopadmin = new Shopadmin;
            			$_shopadmin = $shopadmin->where('uid',Auth::user()->id)->first();
            			if(!$_shopadmin->status){
            				Auth::logout();
            				Session::flash('Message','账号冻结中');
        					return Redirect::back();
            			}
            			break;
            			
            		case '3':
            			$shopstaff = new Shopstaff;
                     	$_shopstaff = $shopstaff->where('uid',Auth::user()->id)->first();
            			if(!$_shopstaff->status){
            				Auth::logout();
            				Session::flash('Message','账号冻结中');
        					return Redirect::back();
            			}
            			break;
            		default:
            			# code...
            			break;
            	}
            	return Redirect::to('/');//跳转至HomeController（入口）

			}            
        }
        Session::flash('Message','错误的账号或密码');
        return Redirect::back();


	}

	//注册页面
	public function getRegister(){
		return View::make('auth.register');
	}
	//注册操作（超级管理员注册，只能注册一个）
	public function postRegister(Request $request){
		//参数验证，规则可加，
		$this->validate($request, [
		    'account' => 'required|max:255|unique:users',
		    'password' => 'required|min:6',
		    'password_confirmation' => 'required|min:6|same:password',
		    'code'=>'required',
		]);

		$phone = $request->input('account');
		$code = $request->input('code');
		$cache=Cache::get($phone);
		if($code != $cache['code']){
			Session::flash('Message', "验证码输入错误");
			return Redirect::back();
		}
		
		if(User::where('role',0)->count() > 0){
			Session::flash('Message','超级管理员账号只能注册一个！！');
			return Redirect::back();
		}else{
			$user = new User;
			$user->fill(['account'=>$request->account,'password'=>Hash::make($request->password)]);
			$result = $user->save();
			if($result){
				Session::flash('Message','注册成功！！');
			}else{
				Session::flash('Message','网络错误！！');
			}			
			return Redirect::back();
		}
		
	}

	//忘记密码
	public function getRepassword(){
		return View::make('auth.repassword');
	}
	//后台用户密码修改操作
	public function postRepassword(Request $request){
		$this->validate($request, [
		    'account' => 'required|max:255',
		    'password' => 'required|min:6',
		    'password_confirmation' => 'required|min:6|same:password',
		    'code'=>'required',
		]);

		$phone = $request->input('account');
		$code = $request->input('code');
		$cache=Cache::get($phone);
		if($code != $cache['code']){
			Session::flash('Message', "验证码输入错误");
			return Redirect::back();
		}

		$admin = User::where('account',$phone)->first();
		$admin->password = Hash::make($request->password);
		$result = $admin->save();
		if($result){
			Session::flash('Message','密码修改成功');
		}else{
			Session::flash('Message','密码修改失败');
		}
		return Redirect::back();

	}
	//获取验证码
	// @param 	phone
	public function postMessage(Request $request){
		$phone = $request->input('phone');
		if(!$phone){
			return Response::json(['status'=>'fail','message'=>'手机号码不能为空']);
		}
		if(!$this->isMobile($phone)){
			return Response::json(['status'=>'fail','message'=>'手机号码格式错误']);
		}

		$code=array();

		if(Cache::has($phone)){
			$cache =Cache::get($phone);
			if(($cache['time'] + 60) > time()){
				$gap = $cache['time'] + 60 - time();
    			return Response::json(['status' => 'fail','message' => $gap.'秒后才能重新获取验证码','phone' => $phone]);
			}
			$code['repeat'] = $cache['repeat'] + 1;
			if($cache['repeat'] > 5){
    			return Response::json(['status' => 'fail','message' => '24小时内获取验证码的次数不能超过5次','phone' => $phone]);
			}
		}else{
			//获取验证码的次数
			$code['repeat']=1;
		}
		$code['code'] = substr(str_shuffle("1234567890"),2,6);
		$code['time'] = time();
		//发送短信验证码
    	$res = Message::sendCode($phone,$code['code']);
    	if(strstr($res,'success')){
    		$expireTime = Carbon::now()->addMinutes(1440);
    		Cache::put($phone,$code,$expireTime);
    		return Response::json(['status' => 'success','message' => '获取成功','code' => $code['code'],'phone' => $phone]);
    	}else{
    		return Response::json(['status' => 'fail','message' => '服务器忙，请稍后再试','phone' => $phone]);
    	}

	}


	public function isMobile($mobile) {
        if (!is_numeric($mobile)) {
            return false;
        }
        return preg_match('#^13[\d]{9}$|^14[5,7]{1}\d{8}$|^15[^4]{1}\d{8}$|^17[0,6,7,8]{1}\d{8}$|^18[\d]{9}$#', $mobile) ? true : false;
    }
    //字符验证码
	public function getCaptcha(){
		$url = captcha_src();
		return Response::json(['url'=>$url]);
	}
	//注销
	public function getLogout(){
		if (Auth::check()) Auth::logout();
        return Redirect::to('/');
	}
}