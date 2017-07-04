<?php
/***@author hetutu 
	**/
	namespace App\Http\Controllers\Shop;
	use App\Http\Controllers\Controller;
	use View,Session,Request,DB,Response,Redirect;
	use App\Models\Commodity\Shopcart,App\Models\Brand\Brand,App\Models\Shop\Shopinfo,App\Models\Coupon\Couponlist,App\Models\Coupon\Coupon,App\Models\Weixin\Account;
	use App\Models\Commodity\Shopsku,App\Models\Commodity\Skulist,EasyWeChat\Foundation\Application;

	class ShopcartController extends CommonController{

		public function getIndex($order_id=0){
			Session::forget('cartArr');
			Session::forget('order_coupon');
			Session::forget('order_address');
			if($this->openid){
				//对于可以标识的用户
				$customer_id = DB::table($this->brand_name.'_customers')->where('openid',$this->openid)->value('id');
				if($order_id>0){
					//再次购买
					$cart = array();
					$cart = DB::table($this->brand_name.'_shopcart')
								->join($this->brand_name.'_commodity',$this->brand_name.'_shopcart.commodity_id','=',$this->brand_name.'_commodity.id')
								->join($this->brand_name.'_skulist',$this->brand_name.'_shopcart.sku_id','=',$this->brand_name.'_skulist.id')
								//->join('shopinfo',$this->brand_name.'_commodity.shop_id','=','shopinfo.id')
								->where('order_id',$order_id)
								->where($this->brand_name.'_shopcart.status',3)
								->where($this->brand_name.'_commodity.status',1)
								->where($this->brand_name.'_skulist.status','!=',9)
								->select($this->brand_name.'_shopcart.id',$this->brand_name.'_shopcart.commodity_id',$this->brand_name.'_shopcart.count',$this->brand_name.'_shopcart.shop_id',$this->brand_name.'_commodity.commodity_name',$this->brand_name.'_commodity.main_img',$this->brand_name.'_skulist.id as sku_id',$this->brand_name.'_skulist.commodity_sku',$this->brand_name.'_skulist.price')
								->get();
					foreach ($cart as $key => $value) {
						if($value->commodity_sku == ""){
							$value->commodity_sku = [];
						}else{
							$value->commodity_sku = json_decode($value->commodity_sku,true);
						}
						$Shopsku = new Shopsku;
						$Shopsku->setTable($this->brand_name.'_shop_sku');
						$value->quantity = $Shopsku->where('shop_id',$value->shop_id)->where('sku_id',$value->sku_id)->first()->quantity;
						$value->shopname = Shopinfo::find($value->shop_id)->shopname;
					}
					$cartCollection = collect($cart);
					$carts = $cartCollection->groupBy('shopname')->toArray();
				}else{
					$cart = array();
					$cart = DB::table($this->brand_name.'_shopcart')
								->join($this->brand_name.'_commodity',$this->brand_name.'_shopcart.commodity_id','=',$this->brand_name.'_commodity.id')
								->join($this->brand_name.'_skulist',$this->brand_name.'_shopcart.sku_id','=',$this->brand_name.'_skulist.id')
								//->join('shopinfo',$this->brand_name.'_commodity.shop_id','=','shopinfo.id')
								->where('customer_id',$customer_id)
								->where($this->brand_name.'_shopcart.status',1)
								->where($this->brand_name.'_commodity.status',1)
								->where($this->brand_name.'_skulist.status','!=',9)
								->select($this->brand_name.'_shopcart.id',$this->brand_name.'_shopcart.commodity_id',$this->brand_name.'_shopcart.count',$this->brand_name.'_shopcart.shop_id',$this->brand_name.'_commodity.commodity_name',$this->brand_name.'_commodity.main_img',$this->brand_name.'_skulist.id as sku_id',$this->brand_name.'_skulist.commodity_sku',$this->brand_name.'_skulist.price')
								->get();
					foreach ($cart as $key => $value) {
						if($value->commodity_sku == ""){
							$value->commodity_sku = [];
						}else{
							$value->commodity_sku = json_decode($value->commodity_sku,true);
						}
						$Shopsku = new Shopsku;
						$Shopsku->setTable($this->brand_name.'_shop_sku');
						$value->quantity = $Shopsku->where('shop_id',$value->shop_id)->where('sku_id',$value->sku_id)->first()->quantity;
						$value->shopname = Shopinfo::find($value->shop_id)->shopname;
					}
					$cartCollection = collect($cart);
					$carts = $cartCollection->groupBy('shopname')->toArray();
				}
				//猜你喜欢
					$likes = DB::table($this->brand_name.'_commodity_customer')
								->join($this->brand_name.'_shop_commodity',$this->brand_name.'_shop_commodity.commodity_id','=',$this->brand_name.'_commodity_customer.commodity_id')
								->join($this->brand_name.'_commodity',$this->brand_name.'_commodity.id','=',$this->brand_name.'_commodity_customer.commodity_id')
								->where('customer_id',$customer_id)
								->where($this->brand_name.'_shop_commodity.status',1)->where($this->brand_name.'_shop_commodity.shop_id',$this->shop_id)
								->select($this->brand_name.'_commodity.id',$this->brand_name.'_commodity.commodity_name',$this->brand_name.'_commodity.main_img',$this->brand_name.'_commodity.sku_info',$this->brand_name.'_commodity.base_price as price')					
								->orderBy('count','desc')->take(10)->get();
					if(count($likes)==0){
						//没有访问记录？发送推荐商品
						$likes =DB::table($this->brand_name.'_commodity')
							->join($this->brand_name.'_shop_commodity',$this->brand_name.'_commodity.id','=',$this->brand_name.'_shop_commodity.commodity_id')
							->where($this->brand_name.'_shop_commodity.status',1)->where('is_recommend',1)->where($this->brand_name.'_shop_commodity.shop_id',$this->shop_id)
							->orderBy($this->brand_name.'_shop_commodity.saled_count','desc')
							->select($this->brand_name.'_commodity.id','commodity_name','main_img','group_id','base_price as price')
							->take(10)->get();
					}
					foreach ($likes as $k => $item) {
						$likes[$k]->price = number_format($item->price,2,'.','');
					}
			}else{
				//未登录用户用cookie记录购物车
				$carts = array();
				//setcookie('cart','',time()-3600);

				if(Session::has('cart')){
					$cart_cookie = Session::get('cart');

					foreach ($cart_cookie as $key => $cart) {
						$commodity = DB::table($this->brand_name.'_commodity')->where('id',$cart['commodity_id'])->select('id as commodity_id','commodity_name','main_img')->first();
						$Skulist = new Skulist;
						$Skulist->setTable($this->brand_name.'_skulist');
						$skulist = $Skulist->find($cart['sku_id']);
						$commodity->price = number_format($skulist->price,2,'.','');
						if($skulist->commodity_sku==""){
							$commodity->commodity_sku = [];
						}else{
							$commodity->commodity_sku = json_decode($skulist->commodity_sku,true);			
						}
						$Shopsku = new Shopsku;
						$Shopsku->setTable($this->brand_name.'_shop_sku');
						$commodity->quantity = $Shopsku->where('shop_id',$cart['shop_id'])->where('sku_id',$cart['sku_id'])->first()->quantity;
						$commodity->id = $key+1;
						$commodity->shop_id = $cart['shop_id'];
						$commodity->shopname = Shopinfo::find($cart['shop_id'])->shopname;
						$commodity->count = $cart['count'];
						array_push($carts,$commodity);
					}
					$carts = collect($carts)->groupBy('shopname')->toArray();
				}
				$likes =DB::table($this->brand_name.'_commodity')
						->join($this->brand_name.'_shop_commodity',$this->brand_name.'_commodity.id','=',$this->brand_name.'_shop_commodity.commodity_id')
						->where($this->brand_name.'_shop_commodity.status',1)->where('is_recommend',1)->where($this->brand_name.'_shop_commodity.shop_id',$this->shop_id)
						->orderBy($this->brand_name.'_shop_commodity.saled_count','desc')
						->select($this->brand_name.'_commodity.id','commodity_name','main_img','group_id','base_price as price')
						->take(10)->get();
				foreach ($likes as $k => $item) {
					$likes[$k]->price = number_format($item->price,2,'.','');
				}
			}
			

			//优惠券
			/*$coupons = DB::table($this->brand_name."_coupon")->where('status',1)->where('validity_end','>=',time())->get();
			foreach ($coupons as $key => $c) {
				$c->validity_start = date('Y.m.d',$c->validity_start);
				$c->validity_end = date('Y.m.d',$c->validity_end);
			}*/
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
			return View::make('shop.shopcart.index',array(
				'commoditys' => $carts,//商品信息
				'coupons' => $coupons,//优惠券
				'likes' => $likes,
				'js' => $js,
				'shopaddress' => $shopaddress
				));
		}

		public function postAdd(){
			// if(!$this->openid){
			// 	return Response::json(['status' => 'error','msg' => 'login']);
			// }
			$commodity_id = Request::input('commodity_id');
			$count = Request::input('count');
			$sku_lists = Request::input('sku_lists');
			//$sku_lists = str_replace("[","{",$sku_lists);
			//$sku_lists = str_replace("]","}",$sku_lists);
			if($sku_lists == "{}"){
				$sku_id = DB::table($this->brand_name.'_skulist')->where('commodity_id',$commodity_id)->where('status','!=',9)->value('id');
			}else{
				$sku_id = DB::table($this->brand_name.'_skulist')->where('commodity_id',$commodity_id)->where('commodity_sku',$sku_lists)->where('status','!=',9)->value('id');
			}

			if($sku_id){
				if($this->openid){
					$customer_id = DB::table($this->brand_name.'_customers')->where('openid',$this->openid)->value('id');
					//存储商品信息
					$in_cart = DB::table($this->brand_name.'_shopcart')->where('customer_id',$customer_id)->where('commodity_id',$commodity_id)->where('sku_id',$sku_id)->where('shop_id',$this->shop_id)->where('status',1)->count();
					if($in_cart){
						//购物车中已经存在
						$cart = new Shopcart;
						$cart->setTable($this->brand_name.'_shopcart');
						$cart->where('customer_id',$customer_id)->where('commodity_id',$commodity_id)->where('sku_id',$sku_id)->where('shop_id',$this->shop_id)->where('status',1)->increment('count', $count);
					}else{
						//购物车中不存在
						$cart = new Shopcart;
						$cart->setTable($this->brand_name.'_shopcart');
						$cart->customer_id = $customer_id;
						$cart->shop_id = $this->shop_id;
						$cart->commodity_id = $commodity_id;
						$cart->sku_id = $sku_id;
						$cart->count = $count;
						$cart->status = 1;
						$cart->save();
					}
				}else{
					$cart_cookie = Session::has('cart')? Session::get('cart'):array();
					$is_new = true;
					foreach($cart_cookie as $cart){
						if(($cart['commodity_id']==$commodity_id) &&($cart['shop_id']==$this->shop_id) && ($cart['sku_id'] == $sku_id)){
							$cart['count']+=$count;
							$is_new = false;
							break;
						}
					}
					if($is_new){
						$content = array('commodity_id'=>$commodity_id,'shop_id'=>$this->shop_id,'sku_id'=>$sku_id,'count'=>$count);
						array_push($cart_cookie,$content);
					}
					Session::put('cart',$cart_cookie);
				}
				
				return Response::json(['status' => 'success','msg' => '添加购物车成功']);
			}else{
				return Response::json(['status' => 'error','msg' => '商品规格出错']);
			}
		}

		public function postSubmit(){
			//结算
			$cartArr = Request::input('cart_array');
			if($this->openid){
				$shopArr = array();
				foreach($cartArr as $key => $value){
					$cart = DB::table($this->brand_name."_shopcart")->find($value);
					if($cart){

						//先判断库存
						$rest = DB::table($this->brand_name.'_shop_sku')->where('shop_id',$cart->shop_id)->where('sku_id',$cart->sku_id)->first()->quantity;
						if($rest - $cart->count >= 0){

						}else{
							//库存不足
							$name = DB::table($this->brand_name.'_commodity')->where('id',$cart->commodity_id)->first()->commodity_name;
							$sku = DB::table($this->brand_name.'_skulist')->where('id',$cart->sku_id)->first()->commodity_sku;
							$sku = json_decode($sku,true);
							list($sku_keys, $sku_values) = array_divide($sku);
							$sku_values = implode(',', $sku_values);
							return Response::json(['status' => 'error','msg' => '您所选择的商品 '.$name.' '.$sku_values.' 库存不足，只余'.$rest.'件，请重新选择']);
						}
						//按店铺分组
						$shop = $cart->shop_id;
						if(isset($shopArr[$shop])){
							array_push($shopArr[$shop], $value);
						}else{
							$shopArr[$shop] = array();
							array_push($shopArr[$shop], $value);
						}
					}else{
						return Response::json(['status' => 'error','msg' => '提交了无效的购物车条目，请刷新购物车']);
					}
				}
				Session::put('cartArr',$shopArr);
			}
			return Response::json(['status' => 'success','msg' => '/shop/order/submit']);
		}

		public function postDelete(){
			$cart_array = Request::input('cart_id');

			//删除
			if($this->openid){
				$cart = new Shopcart;
				$cart->setTable($this->brand_name.'_shopcart');
				foreach ($cart_array as $key => $cart_id) {
					$case = $cart->find($cart_id);
					if($case){
						$cart->where('id',$cart_id)->delete();
						//return Response::json(['status' => 'success','msg' => '删除成功']);
						continue;
					}else{
						return Response::json(['status' => 'error','msg' => '商品还未加入购物车']);
					}
				}
				return Response::json(['status' => 'success','msg' => '删除成功']);
			}else{
				if(Session::has('cart')){
					$cart_cookie = Session::get('cart');
					foreach ($cart_array as $a => $b) {
						unset($cart_cookie[$b-1]);
					}
					if(count($cart_cookie)==0){
						Session::forget('cart');
					}else{
						Session::put('cart',$cart_cookie);
					}
				return Response::json(['status' => 'success','msg' => '删除成功']);

				}else{
					return Response::json(['status' => 'error','msg' => '修改失败']);	
				}
			}
			
		}

		public function postChangecount(){
			//加减
			$cart_arr = Request::input('cart');
			if($this->openid){
				$num = 0;
				foreach ($cart_arr as $key => $value) {
				 	$cart = new Shopcart;
					$cart->setTable($this->brand_name.'_shopcart');
					$case = $cart->find($value['id']);
					if($case){
						if($cart->where('id',$value['id'])->first()->count == $value['count']){
							$num++;
						}else{
							$num = $num + $cart->where('id',$value['id'])->update(['count' => $value['count']]);
						}
					}else{
						return Response::json(['status' => 'error','msg' => '商品还未加入购物车']);
					}
				}
				if($num == count($cart_arr)){
					return Response::json(['status' => 'success','msg' => '修改成功']);	
				}else{
					return Response::json(['status' => 'error','msg' => '修改失败']);	
				}
			}else{
				if(Session::has('cart')){
					$cart_cookie = Session::get('cart');
					foreach ($cart_arr as $key => $cart) {
						$cart_cookie[$cart['id']-1]['count'] = $cart['count'];
					}
					Session::put('cart',$cart_cookie);

				}else{
					return Response::json(['status' => 'error','msg' => '修改失败']);	
				}
				return Response::json(['status' => 'success','msg' => '修改成功']);	

			}		
			
		}

		public function postEditall(){
			//编辑全部
		}

	}
