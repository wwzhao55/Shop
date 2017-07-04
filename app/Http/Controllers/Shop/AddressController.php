<?php
	/***@author hetutu 
	**/
	namespace App\Http\Controllers\Shop;
	use App\Http\Controllers\Controller;
	use EasyWeChat\Foundation\Application,App\Models\Weixin\Account,App\Models\Shop\Shopinfo;
	use View,DB,Session,Request,Response,Redirect,Validator;

	class AddressController extends CommonController{

		// public function getIndex(){
		// 	//这块不要了
		// 	$this->middleware('customer');
		// 	//选择收货地址
		// 	$lists = array();
		// 	$customer_id = DB::table($this->brand_name.'_customers')->where('openid',$this->openid)->value('id');
		// 	$lists = DB::table($this->brand_name.'_receiver_address')->where('customer_id',$customer_id)->where('status',1)->get();
		// 	Session::put('address_from',0);//表示从选择地址进入
		// 	return View::make('shop.address.index',array('lists' => $lists));
		// }

		public function postChoose(){
			//$is_reg = DB::table($this->brand_name.'_customers')->where('openid',$this->openid)->first();
			if(!$this->openid){
				Session::put('url.intended', url('shop/vip/index'));
				return Redirect::guest('/shop/login');//登录异常
			}else{
				//选择收货地址
				$address_id =Request::input('address_id');
				$order_address = array();
				$order_address['id'] = $address_id;
				$temp = DB::table($this->brand_name.'_receiver_address')->find($address_id); 
				$order_address['address'] = $temp->province.$temp->city.$temp->district.$temp->street.$temp->address_details;
				$order_address['receiver'] = $temp->receiver_name;
				$order_address['phone'] = $temp->receiver_phone;
				Session::put('order_address',$order_address);
				return Redirect::to('shop/order/submit');
			}	
		}

		//管理收货地址
		public function getManage(){
			$type = Request::input('type');
			if(!$this->openid){
				return Redirect::guest('/shop/login');
			}
			switch($type){
				case 'order'://从提交订单进入
					$title = "选择收货地址";
					Session::put('address_from','order');
					break;
				case 'vip':
					$title = "收货地址管理";
					Session::put('address_from','vip');
					break;
				default:
					$title = "收货地址管理";
					Session::put('address_from','vip');
					break;
			}	
			$lists = array();//地址列表
			$customer_id = DB::table($this->brand_name.'_customers')->where('openid',$this->openid)->value('id');
			$lists = DB::table($this->brand_name.'_receiver_address')->where('customer_id',$customer_id)->where('status',1)->get();
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
			];
			$app = new Application($options);
			$js = $app->js;

			$shop = Shopinfo::find($this->shop_id);
			$shopaddress = $shop->shop_province.$shop->shop_city.$shop->shop_district.$shop->shop_address_detail;
			$shopaddress = str_replace('市辖区','',$shopaddress);
			return View::make('shop.address.manage',array(
				'lists' => $lists,
				'title' => $title,
				'type' => $type,
				'js' => $js,
				'shopaddress' => $shopaddress 
				));
		}

		public function postSetdefault(){
		//	$is_reg = DB::table($this->brand_name.'_customers')->where('openid',$this->openid)->first();
			if(!$this->openid){
				return Response::json(['status'=>'error','msg'=>'登录异常']);//登录异常
			}else{
				$address_id = Request::input('address_id');
				$customer_id = DB::table($this->brand_name.'_customers')->where('openid',$this->openid)->value('id');
				$old = DB::table($this->brand_name.'_receiver_address')->where('customer_id',$customer_id)->where('is_default',1)->update(['is_default' => 0,'updated_at' => time()]);
				$new =  DB::table($this->brand_name.'_receiver_address')->where('id',$address_id)->update(['is_default' => 1,'updated_at' => time()]);
				if($new){
					return Response::json(['status'=>'success','msg'=>'设置成功']);
				}else{
					return Response::json(['status'=>'error','msg'=>'操作失败']);
				}
			}	
		}

		public function getEdit(){
			if(!$this->openid){
				return Redirect::guest('/shop/login');
			}
			$type = Request::input('type');
			$address = array();
			$result = array();
			if($type == 'new'){
				//新建地址
				$address = [
					"id" => "",
					"receiver_name" => "",
					"receiver_phone" => "",
					"province" => "",
					"city" => "",
					"district" => "",
					"street" => "",
					"address_details" => ""
				];
				$address = (object)$address;
				
			}else if($type == 'edit'){
				//编辑地址
				$address_id = Request::input('address_id'); 
				$address = DB::table($this->brand_name.'_receiver_address')->find($address_id);
			}
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
			];
			$app = new Application($options);
			$js = $app->js;

			$shop = Shopinfo::find($this->shop_id);
			$shopaddress = $shop->shop_province.$shop->shop_city.$shop->shop_district.$shop->shop_address_detail;
			$shopaddress = str_replace('市辖区','',$shopaddress);
			return View::make('shop.address.edit',array("type" => $type,"address" => $address,'js'=>$js,'shopaddress'=>$shopaddress));
		}

		public function postEdit(){
			//判断注册与否
			//$is_reg = DB::table($this->brand_name.'_customers')->where('openid',$this->openid)->first();
			if(!$this->openid){
				if(Request::has('address_from')){
					//立即购买
					 Session::put('url.intended', url('shop/front/detail?commodity_id='.Request::input('commodity_id')));
					return Response::json(['status' => 'error','msg' => 'login']);
				}else{
					return Redirect::guest('/shop/login');
				}
			}else{
				$rules = array(
		            'receiver_name' =>'required|max:255',
		            'receiver_phone'=>array('required','regex:/(^1[34578][0-9]{9}$)|(^([0-9]{3,4}-)?[0-9]{7,8}$)/'),
		            'district' =>'required',
		            'address_details' => 'required|max:255',
		        );
		        $message = array(
		            "required"=> ":attribute 不能为空",
		        );
		        $attributes = array(
		            'receiver_name' =>'收件人',
		            'receiver_phone'=>'联系电话',
		            'district' =>'地址',
		            'address_details' => '详细地址',
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
		            if(Request::has('address_from')){
		            	return Response::json(['status' => 'error','msg' => $show_warning]);
		            }else{
		            	Session::flash('address_error',$show_warning);
						return Redirect::back();
		            }
		            
		        }
		        //验证通过
				$address_id = Request::input('address_id');
				$receiver_name = Request::input('receiver_name');
				$receiver_phone = Request::input('receiver_phone');
				$district = explode(' ',Request::input('district'));
				$street = "";
				$address_detail = Request::input('address_details');
				if(!$receiver_phone || !$receiver_name || !$address_detail || !$district){
					Session::flash('address_error','地址不完整！');
					return Redirect::back();
				}
				$customer_id = DB::table($this->brand_name.'_customers')->where('openid',$this->openid)->value('id');
				if($address_id){
					//编辑
					$result = DB::table($this->brand_name.'_receiver_address')->where('id',$address_id)->update([
						'receiver_name' => $receiver_name,
						'receiver_phone' => $receiver_phone,
						'province' => $district[0],
						'city' => $district[1],
						'district' => $district[2],
						'street' => $street,
						'address_details' => $address_detail,
						'updated_at' => time()]);

					if($result){
						if(Session::has('address_from') && (Session::get('address_from') == 'order')){
							//跳回地址管理
							return Redirect::to('shop/address/manage?type=order');
						}else if(Session::has('address_from') && (Session::get('address_from') == 'vip')) {
							//跳回收货地址管理
							return Redirect::to('shop/address/manage?type=vip');
						}			
					}else{
						Session::flash('address_error','修改失败');
						return Redirect::back();
					}
				}else{
					//新建
					if(Request::has('address_from')){
						//说明是立即购买
						$is_default = 1;
						$old = DB::table($this->brand_name.'_receiver_address')->where('customer_id',$customer_id)->where('is_default',1)->update(['is_default' => 0,'updated_at' => time()]);
						$result = DB::table($this->brand_name.'_receiver_address')->insertGetId([
						 	'customer_id' => $customer_id,
						 	'receiver_name' => $receiver_name,
							'receiver_phone' => $receiver_phone,
							'province' => $district[0],
							'city' => $district[1],
							'district' => $district[2],
							'street' => $street,
							'address_details' => $address_detail,
							'is_default' => $is_default, 
							'status' => 1, 
							'created_at' => time(),
							'updated_at' => time()]);
						if($result){
							return  Response::json(['status' => 'success','msg' => '添加成功']);
						}else{
							return  Response::json(['status' => 'success','msg' => '添加失败']);
						}	 
					}else{
						//管理地址进入的新建地址
						$is_default = Request::input('is_default');
						if($is_default == 1){
						 	$old = DB::table($this->brand_name.'_receiver_address')->where('customer_id',$customer_id)->where('is_default',1)->update(['is_default' => 0,'updated_at' => time()]);
						 }
						$result = DB::table($this->brand_name.'_receiver_address')->insertGetId([
						 	'customer_id' => $customer_id,
						 	'receiver_name' => $receiver_name,
							'receiver_phone' => $receiver_phone,
							'province' => $district[0],
							'city' => $district[1],
							'district' => $district[2],
							'street' => $street,
							'address_details' => $address_detail,
							'is_default' => $is_default, 
							'status' => 1, 
							'created_at' => time(),
							'updated_at' => time()]);
						if($result){
							if(Session::has('address_from') && (Session::get('address_from') == 'order')){
								//跳回地址管理
								return Redirect::to('shop/address/manage?type=order');
							}else if(Session::has('address_from') && (Session::get('address_from') == 'vip')){
								//跳回收货地址管理
								return Redirect::to('shop/address/manage?type=vip');
							}			
						}else{
							Session::flash('address_error','修改失败');
							return Redirect::back();
						}
						
					}			 
				}
			}
			
		}

		public function postDelete(){
		//	$is_reg = DB::table($this->brand_name.'_customers')->where('openid',$this->openid)->first();
			if(!$this->openid){
				return Response::json(['status' => 'error','msg' => '登录异常']);
			}else{
				$address_id = Request::input('address_id');
				$a = DB::table($this->brand_name.'_receiver_address')->find($address_id);
				if(count($a)){
					if($a->is_default){
						return Response::json(['status'=>'error','msg'=>'默认地址不可删除']);
					}
					$b = DB::table($this->brand_name.'_receiver_address')->where('id',$address_id)->update(['status' => 0,'updated_at' => time()]);
					if($b){
						return Response::json(['status'=>'success','msg'=>'删除成功']);
					}else{
						return Response::json(['status'=>'error','msg'=>'删除失败']);
					}
				}else{
					return Response::json(['status'=>'error','msg'=>'地址id无效']);
				}
			}	
		}

		public function getCheckoauth($commodity_id){
			if(!$this->openid){
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
					     'callback' => '/shop/front/checkoauth/'.$this->brand_id.'/'.$this->shop_id.'/address'.'/'.$commodity_id,
					],
				];
				$app = new Application($options);
				$oauth = $app->oauth;
				return $oauth->redirect();
			}
		}
	}
