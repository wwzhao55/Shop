<?php
/***@author hetutu 
	**/
	namespace App\Http\Controllers\Shop;
	use App\Http\Controllers\Controller;
	use App\Models\Weixin\Account,App\Models\Brand\Brand;
	use EasyWeChat\Foundation\Application;
	use App\Models\Customer\Customer,App\Models\User,App\Models\Shop\Shopinfo,App\Models\Commodity\Shopcart;
	use View,Auth,Session,Redirect,Response,Cache,Carbon\Carbon,Message,Hash,DB,Request,Validator;

	class AuthController extends Controller{
		public function __construct(){
			$this->middleware('shop',['only'=>['getLogout']]);
			$this->middleware('wxentry',['except'=>['getWxerror']]);	
		}

		public function getGateway(){//菜单入口
			$code = Request::input('code');
			$brand_id = Request::input('brand_id');
			$brand_name = Brand::where('id',$brand_id)->first()->brandname;
			Session::put('brand_id', $brand_id);
	        Session::put('brand_name', $brand_name);
			$weixinInfo = Account::where('brand_id',$brand_id)->first();
			if($weixinInfo){
				$options = [
				    'debug'  => true,
				    'app_id' => $weixinInfo->appid,
				    'secret' => $weixinInfo->appsecret,
				    'token'  => $weixinInfo->token,
				    'aes_key' =>$weixinInfo->encodingaeskey, // 可选
				    'log' => [
				        'level' => 'debug',
				        'file'  => storage_path('logs/easywechat.log'), // XXX: 绝对路径！！！！
				    ],
				    'oauth' => [
					     'scopes'   => ['snsapi_base'],
					     'callback' => '/shop/auth/checkoauth/'.$brand_id,
					],
				];
	            $app = new Application($options);
		        $openid = $this->getOpenid($code,$weixinInfo->appid,$weixinInfo->appsecret);
	            $Customer=new Customer;
	            $Customer->setTable($brand_name.'_customers');
	            if($openid){
	            	Session::put('openid',$openid);
	            	//code参数正确，证明确实是微信发起的请求
	            	$user = $Customer->where('openid',$openid)->first();
	            	if((!$user) || ($user && (!$user->nickname))){
	            		//之前没有获取到用户信息->此处再获取一遍
	            		$userService = $app->user;
	            		$userInfo = $userService->get($openid);
	            		//存储用户信息
	            		$customer = new Customer;
                        $customer->setTable($brand_name.'_customers');
                        $old_customer = $customer->where('openid',$openid)->first();
                        if ($old_customer) {
                            $old_customer->setTable($brand_name.'_customers');
                        	$cus_arr = ['follow_weixin' => $weixinInfo->name,'status' => 1,'nickname' => $userInfo['nickname'],'sex' => $userInfo['sex'],'headimgurl' => $userInfo['headimgurl'],'city' => $userInfo['city'],'province' => $userInfo['province'],'country' => $userInfo['country'],'openid' => $openid,'public_id' => $weixinInfo->id];
                            $old_customer->fill($cus_arr);
                            $old_customer->save();
                        } else {
                             $new_customer = new Customer;
                             $new_customer->setTable($brand_name.'_customers');
                             $new_customer->follow_weixin = $weixinInfo->name;
                             $new_customer->nickname = isset($userInfo['nickname']) ? $userInfo['nickname'] :'';
                             $new_customer->sex = isset($userInfo['sex']) ? $userInfo['sex'] :'';
                             $new_customer->headimgurl = isset($userInfo['headimgurl']) ? $userInfo['headimgurl'] : '';
                             $new_customer->openid = $openid;
                          //   $new_customer->shop_id = 1;//shop_id临时为1，
                             $new_customer->public_id = $weixinInfo->id;
                             $new_customer->city = isset($userInfo['city']) ? $userInfo['city'] :'';
                             $new_customer->province = isset($userInfo['province']) ? $userInfo['province'] :'';
                             $new_customer->country = isset($userInfo['country']) ? $userInfo['country'] :'';
                             $new_customer->status = 1;
                             $new_customer->save();
                        }
	            		$user = $Customer->where('openid',$openid)->first();
	            	}
	            	if(($user->shop_id) && (Shopinfo::find($user->shop_id)->status == 1) && (Shopinfo::find($user->shop_id)->open_weishop == 1)){
	            		Session::put('shop_id',$user->shop_id);
	            	}else{
	            		//店铺歇业
	            		$hasShop = Shopinfo::where('brand_id',$brand_id)->where('status',1)->where('open_weishop',1)->first();
	            		if($hasShop){
	            			Session::put('shop_id',Shopinfo::where('brand_id',$brand_id)->where('open_weishop',1)->first()->id);

	            		}else{
	            			return Redirect::to('/shop/front/rest');
	            		}
	            	}
	            	
	            	//Session::put('openid',$openid);
	            	if($user && $user->phone){
	            		//用户已绑定过
	            		$localId = Auth::id();
	            		$userId = User::where('account',$user->phone)->where('role',4)->first()->id;
	            		if($localId != $userId){
	            			Auth::loginUsingId($userId);
	            		}
	            	}else{
	            		//未绑定
	            		//$state="";
	            	}
	            	/*switch ($state){
	            		case '21'://微商城
	            			return Redirect::to('shop/front/index');
	            		case '22'://会员卡
	            			return Redirect::to('shop/vip/index');
	            		default:
	            			return Redirect::to('/shop/register');
	            	}*/
	            	return Redirect::to('shop/front/index');
	            }else{
					return 'error';
				}
			}else{
				return '该公众号信息不存在';
			}
		}

		public function getLogin(){
			$account = Account::where('brand_id',Session::get('brand_id'))->first();
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
			$js = $app->js;
			$shop = Shopinfo::find(Session::get('shop_id'));
			$shopaddress = $shop->shop_province.$shop->shop_city.$shop->shop_district.$shop->shop_address_detail;
			$shopaddress = str_replace('市辖区','',$shopaddress);
			return View::make('shop.auth.login',array('js'=>$js,'shopaddress'=>$shopaddress));
		}

		public function postLogin(){
	        $phone = Request::input('phone');
	        $password = Request::input('password');
	        if (!$phone) {
	            Session::flash('logFailedMessage', "请填写手机号码");
	            return Redirect::back();
	        }
	        if (!$password) {
	            Session::flash('logFailedMessage', "请输入密码");
	            return Redirect::back();
	        }
	        $userInfo = User::where('account',$phone)->first();
	        if (!$userInfo) {
	            Session::flash('logFailedMessage',  "账号不存在" );
	            return Redirect::back();
	        }
	        /*if ($userInfo && $userInfo->Status == '3') {
	            Session::flash('logFailedMessage',  "用户不能登录" );
	            return Redirect::back();
	        }*/

	        //验证登陆
	        if (Auth::attempt(array('account' => $phone, 'password' => $password, 'brand_id'=>Session::get('brand_id'),'role' => 4)))
	        {
	            $user = Auth::user();
	            $user->save();
	            if(Session::has('brand_name')){
	            	
	            }else{
	            	Session::put('brand_id',$user->brand_id);
	            	Session::put('brand_name',Brand::find($user->brand_id)->brandname);
	            }
	            //如果用户登录，则获取openid
	            $Customer = new Customer;
	            $Customer->setTable(Session::get('brand_name').'_customers');
	            $customer = $Customer->where('uid',Auth::user()->id)->first();
            	Session::put('openid',$customer->openid);
	            //登录成功,将购物车cookie存储
	            if(Session::has('cart')){
	            	$cart_cookie = Session::get('cart');
	            	$shopArr = array();
	            	foreach ($cart_cookie as $key => $cart) {
	            		$Shopcart = new Shopcart;
	            		$Shopcart->setTable(Session::get('brand_name').'_shopcart');
	            		$shopcart = $Shopcart->where('customer_id',$customer->id)->where('sku_id',$cart['sku_id'])->where('status',1)->first();
	            		if($shopcart){
	            			$shopcart->count = $cart['count'];
	            			$shopcart->setTable(Session::get('brand_name').'_shopcart')->save();
	            			if(isset($shopArr[$cart['shop_id']])){
								array_push($shopArr[$cart['shop_id']], $shopcart->id);
							}else{
								$shopArr[$cart['shop_id']] = array();
								array_push($shopArr[$cart['shop_id']], $shopcart->id);
							}
	            		}else{
	            			$Shopcart->customer_id = $customer->id;
		            		$Shopcart->shop_id = $cart['shop_id'];
		            		$Shopcart->commodity_id = $cart['commodity_id'];
		            		$Shopcart->sku_id = $cart['sku_id'];
		            		$Shopcart->count = $cart['count'];
		            		$Shopcart->status = 1;
		            		$Shopcart->save();
		            		if(isset($shopArr[$cart['shop_id']])){
								array_push($shopArr[$cart['shop_id']], $Shopcart->id);
							}else{
								$shopArr[$cart['shop_id']] = array();
								array_push($shopArr[$cart['shop_id']], $Shopcart->id);
							}
	            		}  		
	            	}
	            	Session::put('cartArr',$shopArr);
	            } 
	            Session::forget('cart');           
	            return Redirect::intended('/');
	        } else {
	            Session::flash('logFailedMessage',  "帐号不存在或者密码错误" );
	            return Redirect::back();
	        }
		}

		public function getRegister(){
			//需在微信页面注册
			if(Session::has('openid')){
				return View::make('shop.auth.register');
			}else{
				return Redirect::to('/shop/error');
			}			
		}

		public function postRegister(){
            $rules = array(
	            'phone'=>'required',
	            'password'=>'required|min:6|max:16',
	            'repassword'=>'required|min:6|max:16',
	            'code'=>'required',
	        );
	        $message = array(
	            "required"=> ":attribute 不能为空",
	        );
	        $attributes = array(
	            'phone'=>'手机号码',
	            'password'=>'密码',
	            'repassword'=>'重复密码',
	            'code'=>'验证码',
	        );
	        $validator = Validator::make(
	            Request::all(), 
	            $rules,
	            $message,
	            $attributes
	        );
	        if ($validator->fails()) {
	            $warnings = $validator->messages();
	            $show_warning = $warnings->first();
	            Session::flash('regFailedMessage', $show_warning);
				return Redirect::back();
	        }
			$phone = Request::input('phone');
			$password = Request::input('password');
			$repassword = Request::input('repassword');
			$code = Request::input('code');
			$openid = Request::input('openid');
			$cache=Cache::get($phone);
			if(!$openid){
				Session::flash('regFailedMessage', "请在微信端注册");
				return Redirect::back();
			}else{
				Session::put('openid',$openid);
			}
			if(!($phone && $password && $repassword && $code)){
				Session::flash('regFailedMessage', "请填写完整表单");
				return Redirect::back();
			}
			if(!$this->isMobile($phone)){
				Session::flash('regFailedMessage', "手机号码格式错误");
				return Redirect::back();
			}
			if($password!=$repassword){
				Session::flash('regFailedMessage', "两次密码输入不一致");
				return Redirect::back();
			}
			if($code!=$cache['code']){
				Session::flash('regFailedMessage', "验证码输入错误");
				return Redirect::back();
			}
			$brand_name = Session::get('brand_name');
			$customer = new Customer;
			$customer->setTable($brand_name.'_customers');
			$customer_phone=$customer->where('phone',$phone)->where('is_vip',1)->first();
			if($customer_phone){
				Session::flash('regFailedMessage', "该手机已经注册！");
            	return Redirect::back();
			}

			$reg_customer = $customer->where('openid',$openid)->first();
			$result = DB::transaction(function () use($phone,$password,$reg_customer,$brand_name){
				if(User::where('account',$phone)->where('role',4)->count()){
				 	//用户存在
				 	$user = User::where('account',$phone)->where('role',4)->first();
				}else{
					$user = new User;
				}
				$user->account = $phone;
				$user->password = Hash::make($password);
				$user->brand_id = Session::get('brand_id');
				$user->shop_id = Session::get('shop_id');
				$user->role = 4;//顾客
        		$result = $user->save();
        		$user_id = $user->id;
        		//customers表
        		$reg_customer->setTable($brand_name.'_customers');
        		$reg_customer->phone = $phone;
        		$reg_customer->uid = $user_id;
        		$reg_customer->is_vip = 1;
        		$result2 = $reg_customer->save();
        		return $user->id;
			});
			if($result){
				Cache::forget($phone);
				Auth::loginUsingId($result);
            	return Redirect::to('shop/vip/index');
			}else{
				 Session::flash('regFailedMessage', "网络错误！");
            	 return Redirect::back();
			}
		}

		//获取验证码
		// @param 	phone
		public function postMessage(){
			$phone = Request::input('phone');
			if(!$phone){
				return Response::json(['status'=>'error','msg'=>'手机号码不能为空']);
			}
			if(!$this->isMobile($phone)){
				return Response::json(['status'=>'error','msg'=>'手机号码格式错误']);
			}
			$brand_name = Session::get('brand_name');
			$user = new Customer;
			$user->setTable($brand_name.'_customers');
			$user_phone=$user->where('phone',$phone)->first();

			if($user_phone){
				return Response::json(['status'=>'error','msg'=>'手机号码已注册']);
			}

			$code=array();

			if(Cache::has($phone)){
				$cache =Cache::get($phone);
				if(($cache['time'] + 60) > time()){
					$gap = $cache['time'] + 60 - time();
        			return Response::json(['status' => 'error','msg' => $gap.'秒后才能重新获取验证码','phone' => $phone]);
				}
				$code['repeat'] = $cache['repeat'] + 1;
				if($cache['repeat'] > 5){
        			return Response::json(['status' => 'error','msg' => '24小时内获取验证码的次数不能超过5次','phone' => $phone]);
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
        		return Response::json(['status' => 'success','msg' => '获取成功','code' => $code['code'],'phone' => $phone]);
        	}else{
        		return Response::json(['status' => 'error','msg' => '服务器忙，请稍后再试','phone' => $phone]);
        	}

		}

		public function getLogout(){
			if (Auth::check()) Auth::logout();
		        return Redirect::back();
		}

		public function getWxerror(){
			$brand_id = Session::get('brand_id');
			$brandname = Brand::find($brand_id)->brandname;
			$weixin_id = Account::where('brand_id',$brand_id)->first()->weixin_id;
			return View::make('errors.wxerror',array('account'=>$weixin_id,'brandname'=>$brandname));
		}


		public function isMobile($mobile) {
	        if (!is_numeric($mobile)) {
	            return false;
	        }
	        return preg_match('#^13[\d]{9}$|^14[5,7]{1}\d{8}$|^15[^4]{1}\d{8}$|^17[0,6,7,8]{1}\d{8}$|^18[\d]{9}$#', $mobile) ? true : false;
	    }

	    //获取微信登录用户信息
		private function getOpenid($code,$appid,$appsecret){
			$token_url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid='.$appid.'&secret='.$appsecret.'&code='.$code.'&grant_type=authorization_code';
			$token = json_decode(file_get_contents($token_url));
			if (isset($token->errcode)) {
	    		echo '<h1>错误：</h1>'.$token->errcode;
	    		echo '<br/><h2>错误信息：</h2>'.$token->errmsg;
	    		exit;
			}

			$access_token_url = 'https://api.weixin.qq.com/sns/oauth2/refresh_token?appid='.$appid.'&grant_type=refresh_token&refresh_token='.$token->refresh_token;
		
			$access_token = json_decode(file_get_contents($access_token_url));
			if (isset($access_token->errcode)) {
	    		echo '<h1>错误：</h1>'.$access_token->errcode;
	    		echo '<br/><h2>错误信息：</h2>'.$access_token->errmsg;
	    		exit;
			}
			$openid = $access_token->openid;	
			return $openid;
		}

		public function getCheckoauth($brand_id){
			$weixinInfo = Account::where('brand_id',$brand_id)->first();
			$options = [
			    'debug'  => true,
			    'app_id' => $weixinInfo->appid,
			    'secret' => $weixinInfo->appsecret,
			    'token'  => $weixinInfo->token,
			    'aes_key' =>$weixinInfo->encodingaeskey, // 可选
			    'log' => [
			        'level' => 'debug',
			        'file'  => storage_path('logs/easywechat.log'), // XXX: 绝对路径！！！！
			    ],
			    'oauth' => [
				     'scopes'   => ['snsapi_base'],
				     'callback' => '/shop/auth/checkoauth/'.$brand_id,
				],
			];
            $app = new Application($options);
            $oauth = $app->oauth;
			// 获取 OAuth 授权结果用户信息
			$user = $oauth->user();
			Session::put('openid',$user->getId());
			return redirect()->action('Shop\AuthController@getGateway',['brand_id'=>$brand_id]);
		}

	}
