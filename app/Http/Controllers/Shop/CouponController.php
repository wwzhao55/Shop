<?php
/***@author hetutu 
	**/
	namespace App\Http\Controllers\Shop;
	use App\Http\Controllers\Controller;
	use View,DB,Request,Session,Response,Redirect,Log;
	use App\Models\Coupon\Couponlist,App\Models\Coupon\Coupon,App\Models\Brand\Brand,App\Models\Weixin\Account,App\Models\Shop\Shopinfo,App\Models\Customer\Customer,App\Models\Admin\Category;
	use EasyWeChat\Foundation\Application;

	class CouponController extends CommonController{

		public function getIndex(){
			if(!$this->openid){
		        return Redirect::guest('/shop/login');
			}

			$coupons = array();
			//我的优惠券
			$customer_id = DB::table($this->brand_name.'_customers')->where('openid',$this->openid)->value('id');
			$coupon = new Coupon;
			$coupon->setTable($this->brand_name.'_coupon');
			$coupon_list = $coupon->where('validity_end','>=',time())->orderBy('validity_start','asc')->get();//所有有效优惠券
			foreach ($coupon_list as $key => $item) {
				$item->validity_start = date('Y.m.d',$item->validity_start);
				$item->validity_end = date('Y.m.d',$item->validity_end);
				$mycoupon = new Couponlist;
				$mycoupon->setTable($this->brand_name.'_coupon_list');
				$my = $mycoupon->where('customer_id',$customer_id)->where('coupon_id',$item->id)->first();
				if($my){
					if($my->used < $my->number){
						//还有次数可用
						//减去待付款中的优惠券
						$is_unpay = DB::table($this->brand_name.'_order')->where('status',1)->where('customer_id',$customer_id)->where('coupon_id',$item->id)->count(); 
						if($my->used + $is_unpay < $my->number){
							for($i=0;$i<$my->number-$my->used-$is_unpay;$i++){
								array_push($coupons,$item);
							}
						}
							
					}		
				}
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
			return View::make('shop.coupon.index',array(
				'coupons' => $coupons,//优惠券
				'brandname' => $this->brand_name,//品牌名
				'js'=>$js,
				'shopaddress'=>$shopaddress
				));
		}

		public function postCollect(){
			$coupon_id = Request::input('coupon_id');
			$Coupon = new Coupon;
			$Coupon->setTable($this->brand_name.'_coupon');
			$coupon = $Coupon->find($coupon_id);
			if(!$this->openid){
				if(Request::has('coupon_from')){
					Session::put('url.intended', url('shop/coupon/couponcenter'));
				}else{
					Session::put('url.intended', url('shop/shopcart/index'));
				}	
				return Response::json(['status' => 'error','msg' => 'login']);
			}
			$customer_id = DB::table($this->brand_name.'_customers')->where('openid',$this->openid)->value('id');
			if($coupon->quantity == $coupon->number){
				return Response::json(['status' => 'error','msg' => '您来晚了，该优惠券已领完']);
			}
			if($coupon->status != 1){
				return Response::json(['status' => 'error','msg' => '您来晚了，该优惠券已发放结束']);
			}
			if($coupon->validity_end < time()){
				return Response::json(['status' => 'error','msg' => '优惠券已过期，不能领取']);
			}else{
				$has_collect = DB::table($this->brand_name.'_coupon_list')->where('coupon_id',$coupon_id)->where('customer_id',$customer_id)->first();
				if($coupon->gettimes == 1){
					//只能获取一次
					if( $has_collect && ($has_collect->number == 1) ){
						return Response::json(['status' => 'error','msg' => '该优惠券每人最多领取一次，您已领取过']);
					}else{
						DB::beginTransaction();
						try{
							$new = new Couponlist;
							$new->setTable($this->brand_name.'_coupon_list');
							$new->coupon_id = $coupon_id;
							$new->customer_id = $customer_id;
							$new->number = 1;
							$new->used = 0;
							$new->save();
							$coupon->quantity+=1;//领取数量
							$coupon->person_times+=1;//领取人次
							$coupon->setTable($this->brand_name.'_coupon')->save();
							DB::commit();
						}catch (Exception $e){
			                   DB::rollback();
			                   return Response::json(['status' => 'success','msg' => '操作失败']);
			            }
						return Response::json(['status' => 'success','msg' => '领取成功']);
					}
				}else if($coupon->gettimes == 0){
					//无限次领取
					DB::beginTransaction();
					try{
						if($has_collect){
							$old = new Couponlist;
							$old->setTable($this->brand_name.'_coupon_list');
							$oldlist = $old->where('customer_id',$customer_id)->where('coupon_id',$coupon_id)->first();
							$oldlist->number+=1;
							$oldlist->setTable($this->brand_name.'_coupon_list')->save();
						}else{
							$new = new Couponlist;
							$new->setTable($this->brand_name.'_coupon_list');
							$new->coupon_id = $coupon_id;
							$new->customer_id = $customer_id;
							$new->number = 1;
							$new->used = 0;
							$new->save();
							$coupon->person_times+=1;
						}
						$coupon->quantity+=1;
						$coupon->setTable($this->brand_name.'_coupon')->save();
						DB::commit();
					}catch (Exception $e){
		                   DB::rollback();
		                   return Response::json(['status' => 'success','msg' => '操作失败']);
		            }					
					return Response::json(['status' => 'success','msg' => '领取成功']);			
				}else{
					//领取n次
					DB::beginTransaction();
					try{
						if($has_collect){
							if($has_collect->number == $coupon->gettimes ){
								return Response::json(['status' => 'error','msg' => '领取次数已达上限']);
							}else{
								$old = new Couponlist;
								$old->setTable($this->brand_name.'_coupon_list');
								$oldlist = $old->where('customer_id',$customer_id)->where('coupon_id',$coupon_id)->first();
								$oldlist->number+=1;
								$result = $oldlist->setTable($this->brand_name.'_coupon_list')->save();
							}
						}else{
							$new = new Couponlist;
							$new->setTable($this->brand_name.'_coupon_list');
							$new->coupon_id = $coupon_id;
							$new->customer_id = $customer_id;
							$new->number = 1;
							$new->used = 0;
							$result = $new->save();
							$coupon->person_times+=1;
						}
						$coupon->quantity+=1;
						$coupon->setTable($this->brand_name.'_coupon')->save();
						DB::commit();
					}catch (Exception $e){
		                   DB::rollback();
		                   return Response::json(['status' => 'success','msg' => '操作失败']);
		            }					
					return Response::json(['status' => 'success','msg' => '领取成功']);	
				}
			}
			
		}

		public function getChoose($shopname,$total){
			if(!$this->openid){
		        return Redirect::guest('/shop/login');
			}
			$coupons = array();
			//我的优惠券
			$customer_id = DB::table($this->brand_name.'_customers')->where('openid',$this->openid)->value('id');
			$coupon = new Coupon;
			$coupon->setTable($this->brand_name.'_coupon');
			$coupon_list = $coupon->where('validity_end','>=',time())->orderBy('validity_start','asc')->get();//所有有效优惠券
			foreach ($coupon_list as $key => $item) {
				$item->validity_start = date('Y.m.d',$item->validity_start);
				$item->validity_end = date('Y.m.d',$item->validity_end);
				$mycoupon = new Couponlist;
				$mycoupon->setTable($this->brand_name.'_coupon_list');
				$my = $mycoupon->where('customer_id',$customer_id)->where('coupon_id',$item->id)->first();
				if($my){
					if($my->used < $my->number){
						//还有次数可用
						//减去待付款中的优惠券
						$is_unpay = DB::table($this->brand_name.'_order')->where('status',1)->where('customer_id',$customer_id)->where('coupon_id',$item->id)->count(); 
						if($my->used + $is_unpay < $my->number){
							for($i=0;$i<$my->number-$my->used-$is_unpay;$i++){
								array_push($coupons,$item);
							}
						}	
					}		
				}
			}
			//踢出待付款中的优惠券
			/*$coupons = array_where($coupons,function($key,$item){
				
			})*/
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
			return View::make('shop.coupon.choose',array('coupons' => $coupons,'total' => $total,'shopname' => $shopname,'js'=>$js,'shopaddress'=>$shopaddress));
		}

		public function postChoose(){
			if(!$this->openid){
				Session::put('url.intended', url('shop/order/submit'));
				return Response::json(['status' => 'error','msg' => 'login']);
			}else{
				//选择优惠券
				$coupon_id = Request::input('coupon_id'); 
				$total = Request::input('total'); 
				$shopname = Request::input('shopname');
				$coupon_choose = array();
				$coupon = DB::table($this->brand_name.'_coupon')->find($coupon_id);
				$coupon_choose['id'] = $coupon_id;
				$coupon_choose['name'] = $coupon->name;
				$coupon_choose['condition'] = $coupon->use_condition;
				$coupon_choose['sum'] = $coupon->sum;
				//验证优惠券使用条件
				$commoditys = Session::get('order_commoditys');
				$shop_id = Shopinfo::where('brand_id',$this->brand_id)->where('shopname',$shopname)->first()->id;

				//是否适用店铺
				if($coupon->shop_id > 0){
					if($coupon->shop_id != $shop_id){
						$coupon_shopname = Shopinfo::find($coupon->shop_id)->shopname;
						return Response::json(['status' => 'error','msg' => '该优惠券仅适用于'.$coupon_shopname]);
					}
				}
				//金额是否满足
				//种类是否满足
				if($coupon->commodity_category > 0){
					$category_total = 0;
					foreach ($commoditys[$shopname]->commodity as $key => $item) {
						if($item->category_id == $coupon->commodity_category){
							$category_total += $item->quantity * $item->price;
						}
					}
					if($category_total < $coupon->use_condition){
						return Response::json(['status' => 'error','msg' => '该优惠券仅适用于'.Category::find($coupon->commodity_category)->name.'类商品，您的订单金额不满足适用条件']);
					}
					if($coupon->use_condition == 0){
						if($category_total <= $coupon->sum){
							return Response::json(['status' => 'error','msg' => '您的订单金额需要大于优惠券面额']);
						}
					}
				}else{
					if($total < $coupon->use_condition){
						return Response::json(['status' => 'error','msg' => '您的订单金额不满足适用条件']);
					}
					if($coupon->use_condition == 0){
						if($total <= $coupon->sum){
							return Response::json(['status' => 'error','msg' => '您的订单金额需要大于优惠券面额']);
						}
					}
				}			
				//有效期
				if(($coupon->validity_start > time()) || ($coupon->validity_end < time())){
					return Response::json(['status' => 'error','msg' => '您的订单不在优惠券使用期限内']);
				}
				if(Session::has('order_coupon')){
					$temp = Session::pull('order_coupon');
				}else{
					$temp = array();
				}
				$temp[$shopname] = $coupon_choose;
				Session::put('order_coupon',$temp);
				return Response::json(['status' => 'success','msg' => '选择使用']);
				
			}
			
		}

		//店铺优惠券页
		public function getCouponcenter(){
			$Coupon = new Coupon;
			$Couponlist = new Couponlist;
			$Coupon->setTable($this->brand_name.'_coupon');
			$Couponlist->setTable($this->brand_name.'_coupon_list');
			$usercoupon = collect(array());
			if($this->openid){
				$customer_id = DB::table($this->brand_name.'_customers')->where('openid',$this->openid)->value('id');
				$usercoupon = $Couponlist->where('customer_id',$customer_id)->get()->pluck('coupon_id');//用户优惠券
			}
			$coupons = $Coupon->where('status',1)->where('validity_end','>=',time())->get();//所有店铺未过期 可领取的优惠券
			foreach ($coupons as $a => $b) {
				$b->use_condition =  number_format($b->use_condition,2,'.','');
				$c = $b->id;
				if( $usercoupon->contains(function ($key, $value) use($c) {
				    return $value == $c;
				}) ){
					//用户领过这个券
					if($b->gettimes > 1){
						//券限定次数领取
						$collectcount = $Couponlist->where('customer_id',$customer_id)->where('coupon_id',$c)->first()->number;//用户领取次数
						$b->rest = $b->gettimes - $collectcount;
					}
					$b->collected = 1;
				}else{
					$b->collected = 0;
				}
				$b->validity_start = date('Y.m.d',$b->validity_start);
				$b->validity_end = date('Y.m.d',$b->validity_end);
			}
			//var_dump($coupons->toArray());
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
			return View::make('shop.coupon.couponcenter',array('coupons' => $coupons,'js'=>$js,'shopaddress'=>$shopaddress));
		}

		//优惠券详情
		public function getDetail($coupon_id){
			$coupon = DB::table($this->brand_name.'_coupon')->find($coupon_id);
			$coupon->use_condition =  number_format($coupon->use_condition,2,'.','');
			$coupon->validity_start = date('Y.m.d',$coupon->validity_start);
			$coupon->validity_end = date('Y.m.d',$coupon->validity_end);
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
			return View::make('shop.coupon.detail',array('coupon' => $coupon,'js' => $js,'shopaddress'=>$shopaddress));
		}

		//优惠券分享页面
		public function getShare($brand_id,$coupon_id){
			$account = Account::where('brand_id',$brand_id)->first();
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
				     'callback' => '/shop/coupon/couponoauth/'.$brand_id.'/'.$coupon_id,
				],
			];
			$app = new Application($options);
			$oauth = $app->oauth;
			$js = $app->js;
			if(Session::has('coupon_user')){
				$brandname = Brand::find($brand_id)->brandname;
				$info = DB::table($brandname.'_coupon')->find($coupon_id);

				$shop = Shopinfo::find($this->shop_id);
				$shopaddress = $shop->shop_province.$shop->shop_city.$shop->shop_district.$shop->shop_address_detail;
				$shopaddress = str_replace('市辖区','',$shopaddress);
				return View::make('shop.coupon.share',array('brand_id'=>$brand_id,'brandname' => $brandname,'info' => $info->description,'id' => $info->id,'js'=>$js,'shopaddress'=>$shopaddress));
			}else{
				return $oauth->redirect();
			}			
		}

		//优惠券领取成功
		public function getCollectshare($brand_id,$coupon_id){
			$brandname = Brand::find($brand_id)->brandname;
			$info = DB::table($brandname.'_coupon')->find($coupon_id);
			$info->validity_start = date('Y.m.d',$info->validity_start);
			$info->validity_end = date('Y.m.d',$info->validity_end);

			$account = Account::where('brand_id',$brand_id)->first();
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
			$coupon_user = Session::get('coupon_user');
			//Session::forget('coupon_user');
			Session::put('openid',$coupon_user['openid']);
			$shop_id = Shopinfo::where('brand_id',$brand_id)->first()->id;
			
			DB::beginTransaction();
			try{
				//写入用户信息
				$Coupon = new Coupon;
				$Coupon->setTable($brandname.'_coupon');
				$coupon = $Coupon->find($coupon_id);
				$new_user = new Customer;
				$new_user->setTable($brandname.'_customers');
				if($new_user->where('openid',$coupon_user['openid'])->count()>0){
					//老用户领券
					$new_user = $new_user->where('openid',$coupon_user['openid'])->first();
					if($coupon->validity_end <= time()){
						//已过期
						$msg = '您领取的优惠券已过期，再逛逛吧';
					}else if($coupon->status == 0){
						$msg = '您领取的优惠券已停止发放，再逛逛吧';
					}else{
						$coupon_list = new Couponlist;
						$coupon_list->setTable($brandname.'_coupon_list');
						$mycoupon = $coupon_list->where('customer_id',$new_user->id)->where('coupon_id',$coupon_id)->first();
						if($mycoupon){
							if($mycoupon->number >= $coupon->gettimes){
								$msg = '您已经领取过'.$brandname.'优惠券，快去买买买吧';
							}else{
								$mycoupon->number+=1;
								$mycoupon->setTable($brandname.'_coupon_list')->save();
								$coupon->quantity+=1;
								$coupon->setTable($brandname.'_coupon')->save();
								$msg = '我领到'.$brandname.'优惠券啦';
							}
						}else{
							//老用户未领取过该券
							$newcoupon= new Couponlist;
							$newcoupon->setTable($brandname.'_coupon_list');
							$newcoupon->coupon_id = $coupon_id;
							$newcoupon->customer_id = $new_user->id;
							$newcoupon->number=1;
							$newcoupon->used=0;
							$newcoupon->save();
							$coupon->quantity+=1;
							$coupon->person_times+=1;
							$coupon->setTable($brandname.'_coupon')->save();
							//Log::info('ttt',['tt'=>$coupon->quantity]);
							$msg = '我领到'.$brandname.'优惠券啦';
						}	
					}
				}else{
					//新用户领券
					$new_user_data['openid'] = $coupon_user['openid'];
					$new_user_data['shop_id'] = $shop_id;
					$new_user_data['headimgurl'] = $coupon_user['headimgurl'];
					$new_user_data['nickname'] = $coupon_user['nickname'];
					$new_user_data['province'] = $coupon_user['province'];
					$new_user_data['city'] = $coupon_user['city'];
					$new_user_data['country'] = $coupon_user['country'];
					$new_user_data['sex'] = $coupon_user['sex'];
					$new_user->fill($new_user_data)->save();
					//记录领券
					$mycoupon= new Couponlist;
					$mycoupon->setTable($brandname.'_coupon_list');
					$mycoupon->coupon_id = $coupon_id;
					$mycoupon->customer_id = $new_user->id;
					$mycoupon->number=1;
					$mycoupon->used=0;
					$mycoupon->save();
					$coupon->quantity+=1;
					$coupon->person_times+=1;
					$coupon->setTable($brandname.'_coupon')->save();
					$msg = '我领到'.$brandname.'优惠券啦';
				} 
				DB::commit();
			}catch (Exception $e){
               DB::rollback();
               return $e->getMessage();
            }
			Session::put('openid',$coupon_user['openid']);
			$shop = Shopinfo::find($this->shop_id);
			$shopaddress = $shop->shop_province.$shop->shop_city.$shop->shop_district.$shop->shop_address_detail;
			$shopaddress = str_replace('市辖区','',$shopaddress);
			return View::make('shop.coupon.collectshare',array(
				'brand_id' => $brand_id,
				'brandname' => $brandname,
				'coupon' => $info,
				'headimgurl' => $coupon_user['headimgurl'],
				'shop_id' => $shop_id,
				'msg' => $msg,
				'js' => $js,
				'shopaddress'=>$shopaddress
				));
		}

		//分享认证入口
		public function getCouponoauth($brand_id,$coupon_id){
			$account = Account::where('brand_id',$brand_id)->first();
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
			$oauth = $app->oauth;
			// 获取 OAuth 授权结果用户信息
			$user = $oauth->user();
			Session::put('coupon_user',$user->getOriginal());
			return redirect()->action('Shop\CouponController@getShare',[$brand_id,$coupon_id]);
		}
	}
