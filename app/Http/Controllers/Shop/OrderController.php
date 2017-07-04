<?php
	/*** @author hetutu
	 @modify 2016.08.05
	**/
	namespace App\Http\Controllers\Shop;
	use App\Http\Controllers\Controller,Illuminate\Http\Request;
	use View,DB,Response,Session,Redirect,Message,Cache,Carbon\Carbon,Auth,Log,Validator;
	use App\Models\Shop\Shopinfo,App\Models\Order\Order,App\Models\Order\Ordershopcart,App\Models\Brand\Brand;
	use App\Models\Commodity\Skulist,App\Models\Commodity\Shopcart,App\Models\Coupon\Couponlist,App\Models\Coupon\Coupon,App\Models\Commodity\Shopsku,App\Models\Commodity\Commodity,App\Models\Commodity\Shopcommodity,App\Models\Order\Orderrefund,App\Models\Customer\Customer;
	use App\libraries\Wechat,App\Models\Weixin\Account,EasyWeChat\Foundation\Application;
	use EasyWeChat\Payment\Order as WxOrder,EasyWeChat\Support\XML;
	use Symfony\Component\HttpFoundation\Request as SymRequest;

	class OrderController extends CommonController{

		/*获取我的订单*/
		public function getIndex($id=null){
			if($id){
				$this->brand_id = $id;
				Session::put('brand_id',$this->brand_id);
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
			    'oauth' => [
				     'scopes'   => ['snsapi_userinfo'],
				     'callback' => '/shop/front/checkoauth/'.$this->brand_id.'/'.$this->shop_id.'/order',
				],
			];
			$app = new Application($options);
			$oauth = $app->oauth;
			if(!$this->openid){
		      //  return Redirect::guest('/shop/login');
		        return $oauth->redirect();
			}
				
			$js = $app->js;
			$order_all = array();
			$customer_id = DB::table($this->brand_name.'_customers')->where('openid',$this->openid)->value('id');
			$all = DB::table($this->brand_name.'_order')
					->where($this->brand_name.'_order.customer_id',$customer_id)
					->where('status','>',0)
					->where('status','!=','5')
					->orderBy('created_at','desc')
					->select('id','shop_id','order_num','total','express_price','status')
					->get();		
			foreach ($all as $key => $value) {
				//$value->commodity = DB::table($this->brand_name.'_order_shopcart')->where('order_id',$value->id)->get();
				$value->commodity = DB::table($this->brand_name.'_order_shopcart')
					->join($this->brand_name.'_shopcart',$this->brand_name.'_order_shopcart.shopcart_id','=',$this->brand_name.'_shopcart.id')
					->join($this->brand_name.'_commodity',$this->brand_name.'_shopcart.commodity_id','=',$this->brand_name.'_commodity.id')
					->join($this->brand_name.'_skulist',$this->brand_name.'_shopcart.sku_id','=',$this->brand_name.'_skulist.id')
					->join('shopinfo',$this->brand_name.'_shopcart.shop_id','=','shopinfo.id')
					->where($this->brand_name.'_order_shopcart.order_id',$value->id)
					->select($this->brand_name.'_order_shopcart.shopcart_id',$this->brand_name.'_shopcart.count',$this->brand_name.'_shopcart.commodity_id',$this->brand_name.'_shopcart.sku_id',$this->brand_name.'_commodity.commodity_name',$this->brand_name.'_commodity.main_img',$this->brand_name.'_skulist.commodity_sku',$this->brand_name.'_skulist.price','shopinfo.shopname')
					->get();
				$value->count = 0;
				foreach ($value->commodity as $key => $sku) {
					$sku->commodity_sku = json_decode($sku->commodity_sku,true);
					$value->count += $sku->count;
				}
				if($value){
					$value->shopname = Shopinfo::find($value->shop_id)->shopname;
				}
			}

			//获取精选商品
			$more = DB::table($this->brand_name.'_commodity')
					->join($this->brand_name.'_shop_commodity',$this->brand_name.'_commodity.id','=',$this->brand_name.'_shop_commodity.commodity_id')
					->where($this->brand_name.'_shop_commodity.status',1)->where('is_recommend',1)->where($this->brand_name.'_shop_commodity.shop_id',$this->shop_id)
					->select($this->brand_name.'_commodity.id','commodity_name','main_img','group_id','base_price as price')
					->get();
			if(count($more)>12){
				$more = collect($more)->random(12)->all();//随机选择12个商品
			}
			foreach ($more as $key => $value) {
				//多规格时首页显示商品最低价格
				$more[$key]->price = number_format($value->price,2,'.','');
			}
			$shopcart = DB::table($this->brand_name.'_shopcart')->where('customer_id',$customer_id)->where('status',1)->count();
			$shopcart = ($shopcart>0) ? true:false;

			$shop = Shopinfo::find($this->shop_id);
			$shopaddress = $shop->shop_province.$shop->shop_city.$shop->shop_district.$shop->shop_address_detail;
			$shopaddress = str_replace('市辖区','',$shopaddress);
			return View::make('shop.order.index',array(
				'all' => $all,
				'more' => $more,
				'shopcart' => $shopcart,
				'js' => $js,
				'shopaddress'=>$shopaddress
				));
		}

		/*提交订单*/
		public function getSubmit(){
			if(Session::has('address_from')){
				Session::forget('address_from');
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
			    'oauth' => [
				     'scopes'   => ['snsapi_userinfo'],
				     'callback' => '/shop/front/checkoauth/'.$this->brand_id.'/'.$this->shop_id.'/submit',
				],
			];
			$app = new Application($options);
			$js = $app->js;
			$oauth = $app->oauth;
			if($this->openid){
				if(Session::has('cartArr')){
					$cartArr = Session::get('cartArr');
				}else{
					return Redirect::to('/shop/shopcart/index');
				}
				$commoditys = array();
				$express_price = array();
				$customer_id = DB::table($this->brand_name.'_customers')->where('openid',$this->openid)->value('id');
				$address = DB::table($this->brand_name.'_receiver_address')->where('customer_id',$customer_id)->where('is_default',1)->where('status',1)->first();
				if(!$address){
					$address = DB::table($this->brand_name.'_receiver_address')->where('customer_id',$customer_id)->where('status',1)->first();
				}
				$total = 0;

				foreach ($cartArr as $shop => $cart) {
					//更改了店铺id！！！
					$shopname = Shopinfo::find($shop)->shopname;
					$temp = 0;
					foreach ($cart as $key => $value) {
						$cart = DB::table($this->brand_name.'_shopcart')
							->join($this->brand_name.'_commodity',$this->brand_name.'_shopcart.commodity_id','=',$this->brand_name.'_commodity.id')
							->join($this->brand_name.'_skulist',$this->brand_name.'_shopcart.sku_id','=',$this->brand_name.'_skulist.id')
							->where($this->brand_name.'_shopcart.id',$value)
							->select($this->brand_name.'_shopcart.id',$this->brand_name.'_shopcart.shop_id',$this->brand_name.'_shopcart.count',$this->brand_name.'_shopcart.commodity_id',$this->brand_name.'_commodity.commodity_name',$this->brand_name.'_commodity.main_img',$this->brand_name.'_shopcart.sku_id','category_id','category_name',$this->brand_name.'_commodity.express_price',$this->brand_name.'_skulist.commodity_sku',$this->brand_name.'_skulist.price')
							->first();
						$Shopsku = new Shopsku;
						$Shopsku->setTable($this->brand_name.'_shop_sku');
						$cart->quantity = $Shopsku->where('shop_id',$shop)->where('sku_id',$cart->sku_id)->first()->quantity;
						$cart->commodity_sku = json_decode($cart->commodity_sku,true);
						if(!isset($commoditys[$shopname])){
							$commoditys[$shopname] = array();
						}
						if(!isset($commoditys[$shopname]['commodity'])){
							$commoditys[$shopname]['commodity'] = array();
						}	
						array_push($commoditys[$shopname]['commodity'], $cart);

						if(!isset($express_price[$shopname])){
							$express_price[$shopname] = array();
						}	
						array_push($express_price[$shopname], $cart->express_price);
						$temp += $cart->price * $cart->count;
					}

					$commoditys[$shopname] = (object)$commoditys[$shopname];
					$commoditys[$shopname]->total = $temp;
					$express_price[$shopname] = max($express_price[$shopname]);
					$total += $commoditys[$shopname]->total;
					//var_dump($commoditys[$shopname]);				
				}
				$express = array_sum($express_price);
				//$coupons = $this->chooseCoupon();
				
				Session::put('order_commoditys',$commoditys);
				Session::put('order_express',$express);
				Session::put('order_shopexpress',$express_price);
			}else{
				//return Redirect::guest('/shop/login');
				return $oauth->redirect();
			}
			
			$shop = Shopinfo::find($this->shop_id);
			$shopaddress = $shop->shop_province.$shop->shop_city.$shop->shop_district.$shop->shop_address_detail;
			$shopaddress = str_replace('市辖区','',$shopaddress);
			return View::make('shop.order.submit',array(
				'address' => $address,
				'commoditys' => $commoditys,
				'express' => $express,
				'shopexpress' => $express_price,
				'total' => $total,
				'js' => $js,
				'shopaddress' => $shopaddress
				//'coupons' => $coupons
				));
		}

		public function postSubmit(Request $request){
			$address_id = $request->input('address_id');
			$coupon_id = json_decode($request->input('coupon_id'),true);
			$message = json_decode($request->input('message'),true);
			$pay_money = $request->input('pay_money');

			$customer = DB::table($this->brand_name.'_customers')->where('openid',$this->openid)->first();
			$customer_id = $customer->id;
			$order_total = json_decode($request->input('total'),true);//加邮费加优惠的总额
			$order_address = DB::table($this->brand_name.'_receiver_address')->find($address_id);
			if(!$order_address){
				return Response::json(['status' => 'error','msg' => '地址信息有误']);
			}
			$order_commoditys = Session::get('order_commoditys');
			$order_express = Session::get('order_express');
			$order_shopexpress = Session::get('order_shopexpress');
			$trade_num = $this->build_order_no();//合并付款的临时单号
			$order_num_arr = array();//合并付款的单笔订单号
			$goodstr_arr = array();
			$ordernumstr = array();
			foreach($order_commoditys as $shop => $detail){
				//循环店铺
				$shop_id = Shopinfo::where('shopname',$shop)->first()->id;
				$order_num = $this->build_order_no();
				array_push($order_num_arr, $order_num);
				$shopcart_id = array();
				array_push($ordernumstr,$order_num);
				//循环商品检查库存
				foreach($detail->commodity as $key => $value){
					array_push($shopcart_id,$value->id);
					array_push($goodstr_arr,$value->commodity_name);
					$sku_id = DB::table($this->brand_name.'_shopcart')->find($value->id)->sku_id;
					$shopsku = DB::table($this->brand_name.'_shop_sku')->where('shop_id',$shop_id)->where('sku_id',$sku_id)->first();
					$quantity = $shopsku->quantity;
					if($shopsku->status !=1){
						return Response::json(['status' => 'error','msg' => '您所选择的商品 '.$value->commodity_name.'已失效']);
					}
					if($quantity < $value->count){
						return Response::json(['status' => 'error','msg' => '您所选择的商品 '.$value->commodity_name.' 已经被抢光啦~']);
					}

					//检查限购
					$Check_com = new Commodity;
					$Check_com->setTable($this->brand_name.'_commodity');
					$check_com = $Check_com->find($value->commodity_id);
					if($check_com->limit_count > 0){
						//表示限购
						if($value->count > $check_com->limit_count){
							return Response::json(['status' => 'error','msg' => '您所选择的商品 '.$value->commodity_name.' 限购'.$check_com->limit_count.'件，请返回购物车重新选择']);
						}
					}
				}
				DB::beginTransaction();
				try{
					$order = new Order;
					$order->setTable($this->brand_name.'_order');
					$order_temp = array(
						'shop_id' => $shop_id,
						'order_num' => $order_num,
						//'trade_num' => $trade_num,
						'total' => $order_total[$shop],
						//'express_price' => $order_shopexpress[$shop],
						'coupon_id' => $coupon_id[$shop],
						'status' => 1,//待付款
						'customer_id' => $customer_id,
						'address_id' => $address_id,
						'message' => $message[$shop],
						'order_at' => time(),
						'deal' => 0);
					$order->fill($order_temp)->save();
					$order_id = $order->id;

					foreach($shopcart_id as $value){
						$order_cart = new Ordershopcart;
						$order_cart->setTable($this->brand_name.'_order_shopcart');
						$order_cart->order_id = $order_id;
						$order_cart->shopcart_id = $value;
						$order_cart->save();
					}
					
        			//减库存，购物车失效
					foreach($detail->commodity as $key => $value){
						$cart = new Shopcart;
						$cart->setTable($this->brand_name.'_shopcart');
						$cart = $cart->find($value->id);
						$cart->setTable($this->brand_name.'_shopcart');
						$cart->status = 0;
						$cart->save();
					}

					//订单号暂放入缓存
					$expiresAt = Carbon::now()->addMinutes(60);
					Cache::put($trade_num, json_encode($order_num_arr), $expiresAt); 

					if(count($goodstr_arr)<=1){
						$goodstr = implode($goodstr_arr, ',');
					}else{
						$goodstr = $goodstr_arr[0].'等商品';
					}	
				    DB::commit();
        		}catch (Exception $e){
		            DB::rollback();
		            return Response::json(['status' => 'error','msg' => '提交出错，请稍后再试']);
		        }
			}
			//清除session
			Session::forget('order_address');
			Session::forget('order_coupon');
			Session::forget('cartArr');
			Session::forget('order_commoditys');
			Session::forget('order_express');
			Session::forget('order_shopexpress');
			
			//发送信息 订单提交成功
			if($customer->status){
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
				$notice = $app->notice;
				$userId = $this->openid;
				$templateId = $account->msg_order;
				$url = 'http://shop.dataguiding.com/shop/order/index/'.$this->brand_id.'?unpay';
				$data = array(
				    "first"    => "您的订单已经提交成功，请尽快支付货款~",
				    "orderID" => $order_num_arr[0].'等订单',
				    "orderMoneySum" => $pay_money,
				    "backupFieldName" => '商品信息：',
				    "backupFieldData" => $goodstr,
				    "remark"   => "如有问题，请及时联系商家！", 
				);
				$messageId = $notice->uses($templateId)->withUrl($url)->andData($data)->andReceiver($userId)->send();
			}
			

			//socket
			$url = 'http://121.42.136.52:2999/pushInfo?type=order';
			$html = file_get_contents($url);

			return Response::json(['status' => 'success','msg' => $trade_num]);
		}
		//立即购买
		public function getBuynow($commodity_id,$count,$skulist){
			Session::forget('order_coupon');
			Session::forget('order_address');
			$add = $this->addCart($commodity_id,$count,$skulist);
			if($add){
				$cartArr = array();
				$shop_id = $this->shop_id;
				$cartArr[$shop_id] = array($add);

				Session::put('cartArr',$cartArr);
				return Redirect::to('shop/order/submit');
			}else{
				echo "出错啦";
			}
			
		}

		/*订单详情*/
		public function getDetail($order_id){
			if(!$this->openid){
		        return Redirect::guest('/shop/login');
			}
			$customer_id = DB::table($this->brand_name.'_customers')->where('openid',$this->openid)->value('id');
			$order = DB::table($this->brand_name.'_order')
					->join($this->brand_name.'_order_shopcart',$this->brand_name.'_order.id','=',$this->brand_name.'_order_shopcart.order_id')
					->where($this->brand_name.'_order.id',$order_id)
					->select($this->brand_name.'_order.id',$this->brand_name.'_order.shop_id',$this->brand_name.'_order.order_num',$this->brand_name.'_order.total',$this->brand_name.'_order.express_price',$this->brand_name.'_order.status',$this->brand_name.'_order.address_id',$this->brand_name.'_order.coupon_id',$this->brand_name.'_order.message',$this->brand_name.'_order_shopcart.shopcart_id','trade_num','express_num','refund_money')
					->first();
			$coupon = DB::table($this->brand_name.'_coupon')->where('id',$order->coupon_id)->select('name','sum','use_condition')->first();
			if($coupon){
				$order->couponname = $coupon->name;
				$order->couponsum = $coupon->sum;
				$order->couponcondition = $coupon->use_condition;
			}else{
				$order->couponname = "";
				$order->couponsum = "";
				$order->couponcondition = "";
			}
			//$order->commodity = DB::table($this->brand_name.'_order_shopcart')->where('order_id',$order->id)->get();
			$order->commodity = DB::table($this->brand_name.'_order_shopcart')
				->join($this->brand_name.'_shopcart',$this->brand_name.'_order_shopcart.shopcart_id','=',$this->brand_name.'_shopcart.id')
				->join($this->brand_name.'_commodity',$this->brand_name.'_shopcart.commodity_id','=',$this->brand_name.'_commodity.id')
				->join($this->brand_name.'_skulist',$this->brand_name.'_shopcart.sku_id','=',$this->brand_name.'_skulist.id')
				->join('shopinfo',$this->brand_name.'_shopcart.shop_id','=','shopinfo.id')
				->where($this->brand_name.'_order_shopcart.order_id',$order->id)
				->select($this->brand_name.'_order_shopcart.shopcart_id',$this->brand_name.'_shopcart.count',$this->brand_name.'_commodity.id as commodity_id',$this->brand_name.'_commodity.commodity_name',$this->brand_name.'_commodity.main_img',$this->brand_name.'_skulist.commodity_sku',$this->brand_name.'_skulist.price','shopinfo.shopname')
				->get();
			$order->count = 0;
			foreach ($order->commodity as $key => $sku) {
				$sku->commodity_sku = json_decode($sku->commodity_sku,true);
				$order->count += $sku->count;
			}
			$order->shopname = $order->commodity[0]->shopname;

			$address  =  DB::table($this->brand_name.'_receiver_address')->find($order->address_id);
			$contact = Shopinfo::find($order->shop_id)->customer_service_phone;
			if($order->status == 6 || $order->status==7){
				$order->refund_shopinfo = Shopinfo::find($order->shop_id);
			}
			// var_dump(array('address' => $address,
			// 	'order' => $order,
			// 	'contact' => $contact));
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
			return View::make('shop.order.detail',array(
				'address' => $address,
				'order' => $order,
				'contact' => $contact,
				'js' => $js,
				'shopaddress' => $shopaddress
				));
		}

		public function postCancel(Request $request){
			$order_id = $request->input('order_id');
			$cancel = DB::table($this->brand_name.'_order')->find($order_id);
			if($cancel){
				$shop = Shopinfo::find($cancel->shop_id);
		        if($shop->status == 0 || $shop->open_weishop == 0){
		        	return Response::json(['status' => 'error','msg' => '店铺已打烊，请稍后操作']);
		        }
				//改变订单状态
				DB::beginTransaction();
				try{
					$Order = new Order;
					$Order->setTable($this->brand_name.'_order');
					$order = $Order->find($order_id);
					$order->status = 5;
					$order->close_type = 2;
					$order->setTable($this->brand_name.'_order')->save();
					//恢复商品库存
					$cart_Arr = DB::table($this->brand_name.'_order_shopcart')->where('order_id',$order_id)->select('shopcart_id')->get();
					foreach ($cart_Arr as $key => $value) {
						$temp = DB::table($this->brand_name.'_shopcart')->find($value->shopcart_id);
						$Skulist = new Shopsku;
						$Skulist->setTable($this->brand_name.'_shop_sku');
						$skulist = $Skulist->where('sku_id',$temp->sku_id)->where('shop_id',$order->shop_id)->first();
						$skulist->quantity +=$temp->count;
						if($skulist->status == 0){
							//因售罄下架的恢复上架
							$skulist->status = 1;
							$Shopcom = new Shopcommodity;
							$Shopcom->setTable($this->brand_name.'_shop_commodity');
							$shopcom = $Shopcom->where('commodity_id',$temp->commodity_id)->where('shop_id',$order->shop_id)->first();
							$shopcom->status = 1;
							$shopcom->setTable($this->brand_name.'_shop_commodity')->save();
						}
						$skulist->setTable($this->brand_name.'_shop_sku')->save();
					}
					DB::commit();
				}catch (Exception $e){
		            DB::rollback();
		            return Response::json(['status' => 'error','msg' => '取消出错，请稍后再试']);
		        }
				
				//socket
				$url = 'http://121.42.136.52:2999/pushInfo?type=change';
				$html = file_get_contents($url);
				return Response::json(['status' => 'success','msg' => '订单已取消']);
			}else{
				return Response::json(['status' => 'error','msg' => '订单不存在']);
			}
		}

		//删除订单
		public function postDelete(Request $request){
			$order_id = $request->input('order_id');
			$del_order = new Order;
			$del_order->setTable($this->brand_name.'_order');
			$del_order = $del_order->find($order_id);
			if($del_order){
				$shop = Shopinfo::find($del_order->shop_id);
		        if($shop->status == 0 || $shop->open_weishop == 0){
		        	return Response::json(['status' => 'error','msg' => '店铺已打烊，请稍后操作']);
		        }
				$del_order->setTable($this->brand_name.'_order');
				$del_order->status = 0;
				$del_order->save();
				return Response::json(['status' => 'success','msg' => '删除成功']);
			}else{
				return Response::json(['status' => 'error','msg' => '订单异常']);
			}
		}

		//支付
		public function anyPay($trade_num,$pay_money,$type){
			$pay_money = (float)$pay_money;
			if($type==0){
				//多笔订单一起付款
				$order_num_arr = json_decode(Cache::get($trade_num),true);
				$order_num = 0;
				$goods = array();
				foreach ($order_num_arr as $key => $value) {
					$a = DB::table($this->brand_name.'_order')
							->join($this->brand_name.'_order_shopcart',$this->brand_name.'_order.id','=',$this->brand_name.'_order_shopcart.order_id')
							->join($this->brand_name.'_shopcart',$this->brand_name.'_order_shopcart.shopcart_id','=',$this->brand_name.'_shopcart.id')
							->join($this->brand_name.'_commodity',$this->brand_name.'_shopcart.commodity_id','=',$this->brand_name.'_commodity.id')
							->where('order_num',$value)
							->select($this->brand_name.'_order.id as order_id',$this->brand_name.'_shopcart.commodity_id','commodity_name','sku_id',$this->brand_name.'_order.shop_id','count','limit_count')
							->get();
					array_push($goods,$a[0]->commodity_name);
					//循环商品检查库存
					foreach($a as $item){
						$shopsku = DB::table($this->brand_name.'_shop_sku')->where('shop_id',$item->shop_id)->where('sku_id',$item->sku_id)->first();
						$quantity = $shopsku->quantity;
						if($shopsku->status !=1){
							return Response::json(['status' => 'error','msg' => '您所选择的商品 '.$item->commodity_name.'已失效']);
						}
						if($quantity < $item->count){
							return Response::json(['status' => 'error','msg' => '您所选择的商品 '.$item->commodity_name.' 已经被抢光啦~']);
						}
					}
				}
				$order_count = count($order_num_arr);
			}else if($type==1){
				//我的订单中单笔付款,参数trade_num为订单id
				$pay_order = DB::table($this->brand_name.'_order')->find($trade_num);
				if($pay_order){
					$shop = Shopinfo::find($pay_order->shop_id);
			        if($shop->status == 0 || $shop->open_weishop == 0){
			        	return Response::json(['status' => 'error','msg' => '店铺已打烊，请稍后操作']);
			        }
			        $order_num = $pay_order->order_num;
					$trade_num = $order_num;
				}
				$goods = array();
				$a = DB::table($this->brand_name.'_order')
							->join($this->brand_name.'_order_shopcart',$this->brand_name.'_order.id','=',$this->brand_name.'_order_shopcart.order_id')
							->join($this->brand_name.'_shopcart',$this->brand_name.'_order_shopcart.shopcart_id','=',$this->brand_name.'_shopcart.id')
							->join($this->brand_name.'_commodity',$this->brand_name.'_shopcart.commodity_id','=',$this->brand_name.'_commodity.id')
							->where('order_num',$order_num)
							->select($this->brand_name.'_order.id as order_id',$this->brand_name.'_shopcart.commodity_id','commodity_name','sku_id',$this->brand_name.'_order.shop_id','count','limit_count')
							->get();
				//循环商品检查库存
				foreach($a as $item){
					array_push($goods,$item->commodity_name);
					$shopsku = DB::table($this->brand_name.'_shop_sku')->where('shop_id',$item->shop_id)->where('sku_id',$item->sku_id)->first();
					$quantity = $shopsku->quantity;
					if($shopsku->status !=1){
						return Response::json(['status' => 'error','msg' => '您所选择的商品 '.$item->commodity_name.'已失效']);
					}
					if($quantity < $item->count){
						return Response::json(['status' => 'error','msg' => '您所选择的商品 '.$item->commodity_name.' 已经被抢光啦~']);
					}
				}
				$order_count = 1;
			}
			$goodstr = $order_count.'笔订单，请戳详情查看';
			
			$account = Account::where('brand_id',$this->brand_id)->first();
			$brand = Brand::find($this->brand_id);
			$cert_path = 'uploads/'.$this->brand_id.'/apiclient/apiclient_cert.pem';
			$key_path = 'uploads/'.$this->brand_id.'/apiclient/apiclient_key.pem';
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
			    // payment
			    'payment' => [
			        'merchant_id'        => $brand->weixin_shop_num,
			        'key'                => $brand->weixin_api_key,
			        'cert_path'          => $cert_path, // XXX: 绝对路径！！！！
			        'key_path'           => $key_path,      // XXX: 绝对路径！！！！
			        'notify_url'         => 'http://shop.dataguiding.com/shop/order/notify/'.$brand->id,       // 你也可以在下单时单独设置来想覆盖它
			    ],
			];
			$app = new Application($options);
			$payment = $app->payment;
			//创建订单
			$attributes = [
			    'trade_type'       => 'JSAPI', // JSAPI，NATIVE，APP...
			    'body'             => $goods[0].'等商品',
			    'attach'           => json_encode(array('order_num'=>$order_num,'order_name' => mb_substr($goods[0],0,10,'utf-8').'等')),
			    'out_trade_no'     => $trade_num,
			    'total_fee'        => $pay_money*100,
			    'openid'		   => $this->openid,
			    'time_start'	   => date("YmdHis"),
			    'time_expire'      => date("YmdHis", time() + 3600),//一小时
			];
			$order = new WxOrder($attributes);
			$result = $payment->prepare($order);
			if ($result->return_code == 'SUCCESS' && $result->result_code == 'SUCCESS'){
			    $prepayId = $result->prepay_id;
			    $config = $payment->configForJSSDKPayment($prepayId);
				return Response::json(['status' => 'success','jsApiParameters' => $config]);
			}else{
				if($result->return_code == 'SUCCESS'){
					return Response::json(['status' => 'error','msg' => $result->err_code_des]);
				}else{
					return Response::json(['status' => 'error','msg' => $result->return_msg]);
				}
			}	
		}

		//合并支付
		public function anyPaymany(Request $request){
			$order_id = $request->input('order_id');
			//$order_id = [13,14];
			$pay_money = $request->input('pay_money');
			$pay_money = (float)$pay_money;
			//$pay_money = (float)0.02;
			$trade_num = $this->build_order_no();
			$goods = array();
			$order_num = 0;
			$order_num_arr = array();
			foreach($order_id as $value){
				$pay_order = DB::table($this->brand_name.'_order')->find($value);
				$shop = Shopinfo::find($pay_order->shop_id);
		        if($shop->status == 0 || $shop->open_weishop == 0){
		        	return Response::json(['status' => 'error','msg' => $shop->shopname.'已打烊，请稍后操作']);
		        }
				array_push($order_num_arr,DB::table($this->brand_name.'_order')->find($value)->order_num);
				// DB::table($this->brand_name.'_order')->where('id',$value)->update(['trade_num'=>$trade_num,'updated_at'=>time()]);
			}
			$expireTime = Carbon::now()->addMinutes(60);
        	Cache::put($trade_num,json_encode($order_num_arr),$expireTime);

			$order_count = count($order_id);
			foreach ($order_id as $a => $b) {
				$temp = DB::table($this->brand_name.'_order')
							->join($this->brand_name.'_order_shopcart',$this->brand_name.'_order.id','=',$this->brand_name.'_order_shopcart.order_id')
							->join($this->brand_name.'_shopcart',$this->brand_name.'_order_shopcart.shopcart_id','=',$this->brand_name.'_shopcart.id')
							->join($this->brand_name.'_commodity',$this->brand_name.'_shopcart.commodity_id','=',$this->brand_name.'_commodity.id')
							->where($this->brand_name.'_order.id',$b)->first()
							->select($this->brand_name.'_order.id as order_id',$this->brand_name.'_shopcart.commodity_id','commodity_name','sku_id',$this->brand_name.'_order.shop_id','count','limit_count')
							->get();
				array_push($goods,$temp[0]->commodity_name);
				//循环商品检查库存
				foreach($temp as $item){
					$quantity = DB::table($this->brand_name.'_shop_sku')->where('shop_id',$item->shop_id)->where('sku_id',$item->sku_id)->first()->quantity;
					if($quantity < $item->count){
						return Response::json(['status' => 'error','msg' => '您所选择的商品 '.$item->commodity_name.' 库存不足，只余'.$quantity.'件，已不支持购买']);
					}
				}
			}		
			//$goods = $goods[0].'等商品';
			$goodstr = $order_count.'笔订单，请戳详情查看';
			
			$account = Account::where('brand_id',$this->brand_id)->first();
			$brand = Brand::find($this->brand_id);
			$cert_path = 'uploads/'.$this->brand_id.'/apiclient/apiclient_cert.pem';
			$key_path = 'uploads/'.$this->brand_id.'/apiclient/apiclient_key.pem';
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
			    'payment' => [
			        'merchant_id'        => $brand->weixin_shop_num,
			        'key'                => $brand->weixin_api_key,
			        'cert_path'          => $cert_path, // XXX: 绝对路径！！！！
			        'key_path'           => $key_path,      // XXX: 绝对路径！！！！
			        'notify_url'         => 'http://shop.dataguiding.com/shop/order/notify/'.$brand->id,       // 你也可以在下单时单独设置来想覆盖它 
			    ],
			];
			$app = new Application($options);
			$payment = $app->payment;
			//创建订单
			$attributes = [
			    'trade_type'       => 'JSAPI', // JSAPI，NATIVE，APP...
			    'body'             => $goods[0],
			    'attach'           => json_encode(array('order_num'=>$order_num,'order_name' => mb_substr($goods[0],0,10,'utf-8').'等')),
			    'out_trade_no'     => $trade_num,
			    'total_fee'        => $pay_money*100,
			    'openid'		   => $this->openid,
			    'time_start'	   => date("YmdHis"),
			    'time_expire'      => date("YmdHis", time() + 3600),
			];
			$order = new WxOrder($attributes);
			$result = $payment->prepare($order);
			if ($result->return_code == 'SUCCESS' && $result->result_code == 'SUCCESS'){
			    $prepayId = $result->prepay_id;
			    $config = $payment->configForJSSDKPayment($prepayId);
				return Response::json(['status' => 'success','jsApiParameters' => $config]);
			}else{
				return Response::json(['status' => 'error','msg' => $order_count]);
			}	
		}

		public function anyNotify($id){
			$account = Account::where('brand_id',$id)->first();
			$brand = Brand::find($account->brand_id);
			$brand_name = $brand->brandname;
			$cert_path = 'uploads/'.$account->brand_id.'/apiclient/apiclient_cert.pem';
			$key_path = 'uploads/'.$account->brand_id.'/apiclient/apiclient_key.pem';

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
			    // payment
			    'payment' => [
			        'merchant_id'        => $brand->weixin_shop_num,
			        'key'                => $brand->weixin_api_key,
			        'cert_path'          => $cert_path, // XXX: 绝对路径！！！！
			        'key_path'           => $key_path,      // XXX: 绝对路径！！！！
			        'notify_url'         => 'http://shop.dataguiding.com/shop/order/notify/'.$brand->id,       // 你也可以在下单时单独设置来想覆盖它
			    ],
			];
			$app = new Application($options);
			$payment = $app->payment;			    
			$response = $app->payment->handleNotify(function($notify, $successful) use($account,$app,$payment,$brand_name){
				$attach = json_decode($notify->attach,true);
				if($attach['order_num']==0){
					//多笔一起支付的
					//使用通知里的 "微信支付订单号" 或者 "商户订单号" 去自己的数据库找到订单
					if (Cache::has($notify->out_trade_no)){
					   $order_num_arr = json_decode(Cache::get($notify->out_trade_no),true);
		               $order_arr = array();
		               foreach ($order_num_arr as $a => $b) {
		                  $c = DB::table($brand_name.'_order')->where('order_num',$b)->first();
		                  if ($c->trade_at) { // 假设订单字段“支付时间”不为空代表已经支付
						     return true; // 已经支付成功了就不再更新了
						  }
		                  array_push($order_arr,$c->id);
		               }

		               if ($successful){
		               		 DB::beginTransaction();
			                try{
			                    foreach ( $order_arr as $value) {
			                        //更新订单
			                        $Order = new Order;
			                        $Order->setTable($brand_name.'_order');
			                        $order = $Order->find($value);
			                        $order->status = 2;
			                        $order->trade_num = $notify->transaction_id;
			                        $order->trade_at = time();
			                        $order->deal = 0;
			                        $order->setTable($brand_name.'_order')->save();
			                        //更新销量
			                        $Ordershopcart = new Ordershopcart;
			                        $Ordershopcart->setTable($brand_name.'_order_shopcart');
			                        $shopcart_arr = $Ordershopcart->where('order_id',$value)->select('shopcart_id')->get();
			                       // Log::info('shopcart',['shopcart'=>$shopcart_arr]);
			                        foreach ($shopcart_arr as $k => $cart) {
			                        	$Shopcart = new Shopcart;
			                        	$Shopcart->setTable($brand_name.'_shopcart');
			                        	$shopcart = $Shopcart->find($cart->shopcart_id);

			                        	$Shopcommodity = new Shopcommodity;
			                        	$Shopcommodity->setTable($brand_name.'_shop_commodity');
			                        	$shopcommodity = $Shopcommodity->where('shop_id',$shopcart->shop_id)->where('commodity_id',$shopcart->commodity_id)->first();
			                        	$shopcommodity->saled_count += $shopcart->count;
			                        	
			                        	//Log::info('shopcommodity',['shopcommodity'=>$shopcommodity->saled_count]);

			                        	$Shopsku = new Shopsku;
			                        	$Shopsku->setTable($brand_name.'_shop_sku');
			                        	$shopsku = $Shopsku->where('shop_id',$shopcart->shop_id)->where('sku_id',$shopcart->sku_id)->first();
			                        	$shopsku->saled_count += $shopcart->count;
			                        	//减库存
			                        	$old_quantity = $shopsku->quantity;
										if($old_quantity-$shopcart->count >= 0){
											$shopsku->quantity = $old_quantity-$shopcart->count;
											//级联失效
											if($old_quantity - $shopcart->count == 0){
												//库存已为0 商品失效
												$sku_info = DB::table($brand_name.'_commodity')->where('id',$shopcart->commodity_id)->first()->sku_info;
												if($sku_info == 0){
													//单一规格
													$shopcommodity->status = 0;
													$shopsku->status = 0;
												}else if($sku_info == 1){
													//多规格
													$shopsku->status = 0;
													$sum = DB::table($brand_name.'_shop_sku')->where('commodity_id',$shopcart->commodity_id)->where('shop_id',$shopcart->shop_id)->where('status',1)->sum('quantity');
													if($sum == 0){
														//全部在售规格都没有库存
														$shopcommodity->status = 0;
													}
												}
											}
										}else{
											return Response::json(['status' => 'error','msg' => '库存数据出错，请稍后再试']);
										}
										$shopsku->setTable($brand_name.'_shop_sku')->save();
			                        	$shopcommodity->setTable($brand_name.'_shop_commodity')->save();

			                        	$Commodity = new Commodity;
			                        	$Commodity->setTable($brand_name.'_commodity');
			                        	$commodity = $Commodity->find($shopcart->commodity_id);
			                        	$commodity->saled_count += $shopcart->count;
			                        	$commodity->setTable($brand_name.'_commodity')->save();
			                        }

			                        //优惠券使用
			                        if($order->coupon_id){
			                        	$Coupon = new Coupon;
										$Coupon->setTable($brand_name.'_coupon');
										$coupon = $Coupon->find($order->coupon_id);
										$coupon->used_num+=1;
										$coupon->setTable($brand_name.'_coupon')->save();

										$couponlist = new Couponlist;
										$couponlist->setTable($brand_name.'_coupon_list');
										$customer = new Customer;
										$customer->setTable($brand_name.'_customers');
										$customer_id = $customer->where('openid',$notify->openid)->first()->id;
										$update = $couponlist->where('coupon_id',$order->coupon_id)->where('customer_id',$customer_id)->first();
										if($update){
											$update->setTable($brand_name.'_coupon_list');
											$update->used +=1;
											$update->save();
										}
			                        }
			                       
			                    }
			                    
			                    DB::commit();
			                    Cache::forget($notify->out_trade_no);
			                }catch (Exception $e){
			                    DB::rollback();
			                    return false;
			                } 
		               }else{
		               		return false;
		               }
					}else{
				        return 'Order not exist.'; // 告诉微信，我已经处理完了，订单没找到，别再通知我了
					} 
				}else{

					$isOrder = DB::table($brand_name.'_order')->where('order_num',$notify->out_trade_no)->first();
					if(count($isOrder)==0){
				        return 'Order not exist.'; // 告诉微信，我已经处理完了，订单没找到，别再通知我了
					}
					// 如果订单存在
					// 检查订单是否已经更新过支付状态
				    if ($isOrder->trade_at) { // 假设订单字段“支付时间”不为空代表已经支付
				        return true; // 已经支付成功了就不再更新了
				    }

				    if ($successful) {
				    	DB::beginTransaction();
				    	try{
				    		//更新订单
	                        $Order = new Order;
	                        $Order->setTable($brand_name.'_order');
	                        $order = $Order->where('order_num',$attach['order_num'])->first();
	                        $order->status = 2;
	                        $order->trade_num = $notify->transaction_id;
	                        $order->trade_at = time();
	                        $order->deal = 0;
	                        $order->setTable($brand_name.'_order')->save();
	                        //更新销量
	                        $Ordershopcart = new Ordershopcart;
	                        $Ordershopcart->setTable($brand_name.'_order_shopcart');
	                        $shopcart_arr = $Ordershopcart->where('order_id',$order->id)->select('shopcart_id')->get();

	                        foreach ($shopcart_arr as $key => $cart) {
	                        	$Shopcart = new Shopcart;
	                        	$Shopcart->setTable($brand_name.'_shopcart');
	                        	$shopcart = $Shopcart->find($cart->shopcart_id);

	                        	$Shopcommodity = new Shopcommodity;
	                        	$Shopcommodity->setTable($brand_name.'_shop_commodity');
	                        	$shopcommodity = $Shopcommodity->where('shop_id',$shopcart->shop_id)->where('commodity_id',$shopcart->commodity_id)->first();
	                        	$shopcommodity->saled_count += $shopcart->count;

	                        	$Shopsku = new Shopsku;
	                        	$Shopsku->setTable($brand_name.'_shop_sku');
	                        	$shopsku = $Shopsku->where('shop_id',$shopcart->shop_id)->where('sku_id',$shopcart->sku_id)->first();
	                        	$shopsku->saled_count += $shopcart->count;
	                        	//减库存
	                        	$old_quantity = $shopsku->quantity;
								if($old_quantity-$shopcart->count >= 0){
									$shopsku->quantity = $old_quantity-$shopcart->count;
									//级联失效
									if($old_quantity - $shopcart->count == 0){
										//库存已为0 商品失效
										$sku_info = DB::table($brand_name.'_commodity')->where('id',$shopcart->commodity_id)->first()->sku_info;
										if($sku_info == 0){
											//单一规格
											$shopcommodity->status = 0;
											$shopsku->status = 0;
										}else if($sku_info == 1){
											//多规格
											$shopsku->status = 0;
											$sum = DB::table($brand_name.'_shop_sku')->where('commodity_id',$shopcart->commodity_id)->where('shop_id',$shopcart->shop_id)->where('status',1)->sum('quantity');
											if($sum == 0){
												//全部在售规格都没有库存
												$shopcommodity->status = 0;
											}
										}
									}
								}else{
									return Response::json(['status' => 'error','msg' => '库存数据出错，请稍后再试']);
								}
								$shopsku->setTable($brand_name.'_shop_sku')->save();
	                        	$shopcommodity->setTable($brand_name.'_shop_commodity')->save();

	                        	$Commodity = new Commodity;
	                        	$Commodity->setTable($brand_name.'_commodity');
	                        	$commodity = $Commodity->find($shopcart->commodity_id);
	                        	$commodity->saled_count += $shopcart->count;
	                        	$commodity->setTable($brand_name.'_commodity')->save();
	                        }
	                        if($order->coupon_id){
	                        	$Coupon = new Coupon;
								$Coupon->setTable($brand_name.'_coupon');
								$coupon = $Coupon->find($order->coupon_id);
								$coupon->used_num+=1;
								$coupon->setTable($brand_name.'_coupon')->save();

								$couponlist = new Couponlist;
								$couponlist->setTable($brand_name.'_coupon_list');
								$customer = new Customer;
								$customer->setTable($brand_name.'_customers');
								$customer_id = $customer->where('openid',$notify->openid)->first()->id;
								$update = $couponlist->where('coupon_id',$order->coupon_id)->where('customer_id',$customer_id)->first();
								if($update){
									$update->setTable($brand_name.'_coupon_list');
									$update->used +=1;
									$update->save();
								}
	                        }
	                        DB::commit();
				    	}catch (Exception $e){
			                    DB::rollback();
			                    return false;
			                } 
				    }else{
				    	return false;
				    }
				}

				//向用户发送消息
				$customer = new Customer;
				$customer->setTable($brand_name.'_customers');
				$follow_weixin = $customer->where('openid',$notify->openid)->first()->status;
				if($follow_weixin){
					$notice = $app->notice;
					$userId = $notify->openid;
					$templateId = $account->msg_pay;
					$url = 'http://shop.dataguiding.com/shop/order/index/'.$id.'?unsend';
					$color = '#FF0000';
					$data = array(
					         "first"  => "我们已收到您的货款，开始为您打包商品，请耐心等待",
					         "orderMoneySum"   => ($notify->total_fee/100)."元",
					         "orderProductName"  =>$attach['order_name'],
					         "Remark" =>"欢迎您再次购买！",
					        );	
					$messageId = $notice->uses($templateId)->withUrl($url)->andData($data)->andReceiver($userId)->send();
				}		
				return true; // 返回处理完成	
			});

			//socket
			$url = 'http://121.42.136.52:2999/pushInfo?type=pay';
			$html = file_get_contents($url);

			return $response;
		}

		//生成订单编号
		public function build_order_no()
		{
		    return date('Ymd').substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
		}

		//立即购买先加入购物车,代码有做修改！！
		public function addCart($commodity_id,$count,$sku_lists){
			if($this->openid){
				$customer_id = DB::table($this->brand_name.'_customers')->where('openid',$this->openid)->value('id');
				if($sku_lists == "{}"){
					$sku_id = DB::table($this->brand_name.'_skulist')->where('commodity_id',$commodity_id)->where('status','!=',9)->value('id');
				}else{
					$sku_id = DB::table($this->brand_name.'_skulist')->where('commodity_id',$commodity_id)->where('commodity_sku',$sku_lists)->where('status','!=',9)->value('id');
				}

				if($sku_id){
					//存储商品信息
					//$in_cart = DB::table($this->brand_name.'_shopcart')->where('customer_id',$customer_id)->where('commodity_id',$commodity_id)->where('sku_id',$sku_id)->where('status',1)->count();
					
					/*if($in_cart){
						//购物车中已经存在
						$cart = new Shopcart;
						$cart->setTable($this->brand_name.'_shopcart');
						$cart->where('customer_id',$customer_id)->where('commodity_id',$commodity_id)->where('sku_id',$sku_id)->where('status',1)->increment('count', $count);
						$result = $cart->where('customer_id',$customer_id)->where('commodity_id',$commodity_id)->where('sku_id',$sku_id)->where('status',1)->first()->id;
					}else{*/
						//购物车中不存在
						//不判断是否已在购物车，
						$cart = new Shopcart;
						$cart->setTable($this->brand_name.'_shopcart');
						$cart->customer_id = $customer_id;
						$cart->shop_id = $this->shop_id;
						$cart->commodity_id = $commodity_id;
						$cart->sku_id = $sku_id;
						$cart->count = $count;
						$cart->status = 2;
						$cart->save();
						$result = $cart->id;
					//}
					return $result;
				}else{
					return false;
				}
			}else{
				return false;
			}
			
		}

		//提醒发货
		public function postHurry(Request $request){
			$order_id = $request->input('order_id');
			//判断24小时内是否提醒过
			$order = DB::table($this->brand_name.'_order')->find($order_id);
			$shop = Shopinfo::find($order->shop_id);
	        if($shop->status == 0 || $shop->open_weishop == 0){
	        	return Response::json(['status' => 'error','msg' => '店铺已打烊，请稍后操作']);
	        }
			$hurry_time = $order->hurry_at;
			if($hurry_time && ($hurry_time + 24*60*60 > time())){
				//24小时内已经提醒过
				return Response::json(['status'=>'error','msg'=>'您在24小时内已经提醒过发货了，请耐心等待']);
			}else{
				//发送短信提醒发货
				$phone = Shopinfo::find($order->shop_id)->contacter_phone;
				$customer = DB::table($this->brand_name.'_customers')->find($order->customer_id)->nickname;
				$message = "提醒发货通知：店主大人，会员#".$customer."#在小店购买的编号#".$order->order_num."#的商品还未发货，请尽快安排打包嗷~！";
	        	$res = Message::sendHurry($phone,$message);
				//更新数据库记录时间
				$New = new Order;
				$New->setTable($this->brand_name.'_order');
				$new = $New->find($order_id);
				$new->setTable($this->brand_name.'_order');
				$new->hurry_at = time();
				$new->hurry_times+=1;
				$new->save();

				if(strstr($res,'success')){
					//socket
					$url = 'http://121.42.136.52:2999/pushInfo?type=quick';
					$html = file_get_contents($url);
					return Response::json(['status' => 'success','msg' => '已提醒卖家发货，请耐心等待']);
	        	}else{
	        		return Response::json(['status' => 'error','msg' => '服务器忙，请稍后再试']);
	        	}
			}
			
		}

		public function chooseCoupon(){
			//使用优惠券,未使用 未过期
			$coupons = array();
			$customer_id = DB::table($this->brand_name.'_customers')->where('openid',$this->openid)->value('id');
			$idArr = DB::table($this->brand_name.'_coupon_list')->where('customer_id',$customer_id)->where('is_used',0)->select('coupon_id')->get();
			if(count($idArr)){
				foreach ($idArr as $id) {
					$a = DB::table($this->brand_name.'_coupon')->where('id',$id->coupon_id)->first();
					if($a->status == 1){
						if($a->validity_end >= date("Y-m-d")){
							array_push($coupons,$a); 
						}
					}
				}
			}
			//return $coupons;
			return View::make('shop.coupon.choose',array('coupons' => $coupons,'total' => $total,'shopname' => $shopname));
		}


		public function getRefund($order_id){
			if(!$this->openid){
		        return Redirect::guest('/shop/login');
			}
			$customer_id = DB::table($this->brand_name.'_customers')->where('openid',$this->openid)->value('id');
			$Order = new Order;
			$Order->setTable($this->brand_name.'_order');
			$order = $Order->find($order_id);
			if(!$order){
				return Redirect::back();
			}
			if($order->customer_id!=$customer_id){
				return "越权浏览";
			}
			$ordershopcart = new Ordershopcart;
			$ordershopcart->setTable($this->brand_name.'_order_shopcart');
			$order->shopcart_id = $ordershopcart->where('order_id',$order_id)->select('shopcart_id')->get();
			$order->commoditys = collect(array());
			foreach ($order->shopcart_id as $cart) {
				$Shopcart = new Shopcart;
				$Shopcart->setTable($this->brand_name.'_shopcart');
				$shopcart = $Shopcart->where('id',$cart->shopcart_id)->select('commodity_id','sku_id','count')->first();
				$order->commoditys->push($shopcart);	
			}
			foreach ($order->commoditys as $item) {
				$Commodity = new Commodity;
				$Commodity->setTable($this->brand_name.'_commodity');
				$commodity = $Commodity->find($item->commodity_id);
				$item->commodity_name = $commodity->commodity_name;
				$item->main_img = $commodity->main_img;
				$Skulist = new Skulist;
				$Skulist->setTable($this->brand_name.'_skulist');
				$skulist = $Skulist->find($item->sku_id);
				$item->commodity_sku = json_decode($skulist->commodity_sku,true);
				$item->price = number_format($skulist->price,2,'.','');
			}
			if($order->coupon_id>0){
				$Coupon = new Coupon;
				$Coupon->setTable($this->brand_name.'_coupon');
				$coupon = $Coupon->find($order->coupon_id);
				$order->coupon_sum = $coupon->sum;
				$order->coupon_use_condition = $coupon->use_condition;
				$order->coupon_description = $coupon->description;
			}
			if($order->status == 6){
				//已经申请了退款
				$Orderrefund = new Orderrefund;
				$Orderrefund->setTable($this->brand_name.'_order_refund');
				$orderrefund = $Orderrefund->where('order_id',$order->id)->where('status',0)->first();
				$order->refund_info = $orderrefund->description;	
				$order->refund_imgs = explode(',',$orderrefund->img_src);
			}
			$contacter = Shopinfo::find($order->shop_id)->customer_service_phone;

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
			//var_dump($order->toArray());
			//var_dump($order->commoditys->toArray());
			return View::make('shop.order.refund',array('order' => $order,'contacter' => $contacter,'js'=>$js,'shopaddress'=>$shopaddress));
		}

		public function postRefund(Request $request){
			$order_id = $request->input('order_id');
			if(!$order_id){
				Session::flash('message','无效的订单参数');
				return Redirect::back();
			}

			DB::beginTransaction();
			try{
				$Order = new Order;
				$Order->setTable($this->brand_name.'_order');
				$order = $Order->find($order_id);
				if(!$order){
					Session::flash('message','无效的订单参数');
					return Redirect::back();
				}
				if($order->status != 4){
					Session::flash('message','无效的订单参数');
					return Redirect::back();
				}
				$shop = Shopinfo::find($order->shop_id);
		        if($shop->status == 0 || $shop->open_weishop == 0){
		        	Session::flash('message','店铺已打烊，请稍后操作');
					return Redirect::back();
		        }
				$description = $request->input('description');
				$Refund = new Orderrefund;
				$Refund->setTable($this->brand_name.'_order_refund');
				if($Refund->where('order_id',$order_id)->count()>0){
					Session::flash('message','该订单已申请过退款');
					return Redirect::back();
				}
				$Refund->order_id = $order_id;
				$Refund->description = $description;
				$img_src = "";
				if($request->hasFile('img')){
					$imgs = $request->file('img');
					if(count($imgs)>3){
						Session::flash('message','最多上传3张图片');
						return Redirect::back();
					}
					foreach ($imgs as $img) {
						if (!$img->isValid()) {
							Session::flash('message','无效的文件');
							return Redirect::back();
						}
					}
					$publicPath = public_path();
					$path = $publicPath.'/uploads/'.$this->brand_id.'/refund';
					$destinationPath = 'uploads/'.$this->brand_id.'/refund';
					$result = $this->createdir($path);
					if(!$result){
						Session::flash('message','Create directory failed.');
						return Redirect::back();
					}
					foreach ($imgs as $k => $img) {
						//上传图片
						$imgname = md5( date('ymdhis').substr(uniqid(),7,13) );
				        $extension = $img->getClientOriginalExtension();
				        $img->move($destinationPath,$imgname.'.'.$extension);
				        if($k == 0){
				        	$img_src =$img_src.$destinationPath.'/'.$imgname.'.'.$extension;
				        }else{
				        	$img_src =$img_src.','.$destinationPath.'/'.$imgname.'.'.$extension;
				        }
					}	
				}
				$Refund->img_src = $img_src;
				$Refund->status = 0;
				$Refund->save();

				$order->status = 6;
				$order->deal = 0;
				$order->setTable($this->brand_name.'_order')->save();

				DB::commit();
			}catch (Exception $e){
                DB::rollback();
                Session::flash('message',$e->getMessage());
				return Redirect::back();
            }
			//socket
			$url = 'http://121.42.136.52:2999/pushInfo?type=change';
			$html = file_get_contents($url);
			return Redirect::to('/shop/order/index');
		}

		//取消退款
		public function postCancelrefund(Request $request){
			$validator = Validator::make($request->all(), [
	            'order_id' => 'required',
	        ]);
	        if ($validator->fails()) {
	            return Response::json(['status' => 'error','msg' => '无效的订单参数']);
	        }
	        $Order = new Order;
	        $Order->setTable($this->brand_name.'_order');
			$order = $Order->find($request->order_id);
			if(!$order){
				return Response::json(['status' => 'error','msg' => '订单不存在']);
			}
			if($order->status != 6){
				return Response::json(['status' => 'error','msg' => '订单未申请退款']);
			}
			$shop = Shopinfo::find($order->shop_id);
	        if($shop->status == 0 || $shop->open_weishop == 0){
	        	return Response::json(['status' => 'error','msg' => '店铺已打烊，请稍后操作']);
	        }
			$Orderrefund = new Orderrefund;
			$Orderrefund->setTable($this->brand_name.'_order_refund');
			$refund = $Orderrefund->where('order_id',$order->id)->where('status',0)->first();
			if(!$refund){
				return Response::json(['status' => 'error','msg' => '订单未申请退款']);
			}
			DB::beginTransaction();
			try{
				$refund->setTable($this->brand_name.'_order_refund')->delete();
				$order->status = 4;
				$order->setTable($this->brand_name.'_order')->save();
				DB::commit();
			}catch (Exception $e){
               DB::rollback();
               return Response::json(array(
                'status' => 'error',
                'msg'=>$e->getMessage(),
                ));
            }
			return Response::json(['status' => 'success','msg' => '退款处理成功']);	
		}

		//再次购买
		public function postBuyagain(Request $request){
			$validator = Validator::make($request->all(), [
				'order_id'=>'required',
	            'shop_id' => 'required',
	            'commodity' => 'required'
	        ]);
	        if ($validator->fails()) {
	            return Response::json(['status' => 'error','msg' => '参数无效']);
	        }
	        $shop = Shopinfo::find($request->shop_id);
	        if($shop->status == 0 || $shop->open_weishop == 0){
	        	return Response::json(['status' => 'error','msg' => '店铺已打烊，请稍后操作']);
	        }
	        $commoditys = json_decode($request->commodity);
	        $invalid = 0;
	        $customer_id = DB::table($this->brand_name.'_customers')->where('openid',$this->openid)->value('id');
	        foreach ($commoditys as $key => $item) {
	        	$Shopsku = new Shopsku;
	        	$Shopsku->setTable($this->brand_name.'_shop_sku');
	        	$shopsku = $Shopsku->where('shop_id',$request->shop_id)->where('commodity_id',$item->commodity_id)->where('sku_id',$item->sku_id)->where('status',1)->first();
	        	if(!$shopsku || $shopsku->quantity==0){
	        		$invalid++;
	        	}else{
	        		$Shopcart = new Shopcart;
	        		$Shopcart->setTable($this->brand_name.'_shopcart');
	        		$has_cart = $Shopcart->where('order_id',$request->order_id)->where('status',3)->where('sku_id',$item->sku_id)->count();
	        		if(!$has_cart){
	        			$Shopcart->shop_id = $request->shop_id;
		        		$Shopcart->customer_id = $customer_id;
		        		$Shopcart->commodity_id = $item->commodity_id;
		        		$Shopcart->sku_id = $item->sku_id;
		        		$Shopcart->status = 3;
		        		$Shopcart->count = 1;
		        		$Shopcart->order_id = $request->order_id;
		        		$Shopcart->save();
	        		}	
	        	}   	
	        } 
	        if($invalid == count($commoditys)){
	        	return Response::json(['status' => 'error','msg' => '该笔订单的商品已下架']);
	        } else{
	        	return Response::json(['status' => 'success']);
	        }  
		}

		#-------确认收货
		public function postReceive(Request $request){
			$validator = Validator::make($request->all(), [
	            'order_id' => 'required',
	        ]);
	        if ($validator->fails()) {
	            return Response::json(['status' => 'error','msg' => '订单参数无效']);
	        }
	        $id = $request->order_id;
	        $Order = new Order;
			$Order->setTable($this->brand_name."_order");
			$order = $Order->find($id);
			if($order){
				 $shop = Shopinfo::find($order->shop_id);
			     if($shop->status == 0 || $shop->open_weishop == 0){
			       return Response::json(['status' => 'error','msg' => '店铺已打烊，请稍后操作']);
			     }
				DB::beginTransaction();
				try{
					$order->status = 4;
					$order->deal = 1;
					$order->setTable($this->brand_name."_order")->save();

					//更新会员积分,按1元1分
					$Customer = new Customer;
					$Customer->setTable($this->brand_name.'_customers');
					$customer = $Customer->find($order->customer_id);
					$customer->score = floor($order->total);
					$customer->setTable($this->brand_name.'_customers')->save();
					DB::commit();
				}catch (Exception $e){
	                DB::rollback();
	                return Response::json(['status' => 'error','msg' => '操作失败']);
	            }
				
				return Response::json(['status' => 'success','msg' => '该订单已完成！']);
			}else{
				return Response::json(['status' => 'error','msg' => '订单参数无效']);
			}
		}

		#-------定时取消待付款 1小时
		public function postCancelunpay(){
			$brand_list = Brand::where('status',1)->select('id','brandname')->get();
			foreach ($brand_list as $brand) {
				//遍历品牌
				$Order = new Order;
				$Order->setTable($brand->brandname.'_order');
				$Order->where('status',1)->where('order_at','<',time()-60*60)->update(['status'=>5,'close_type'=>1]);
			}
		}

		private function createdir($path){
	        if (is_dir($path)){  //判断目录存在否，存在不创建
	            return true;
	        }else{ //不存在创建
	            $re=mkdir($path,0777,true); //第三个参数为true即可以创建多极目录
	            if ($re){
	                return true;
	            }else{
	                return false;
	            }
	        }
	    }

	}
