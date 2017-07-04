<?php 
/*** @author hetutu
	 @modify 2016.08.05
	**/
namespace App\Http\Controllers\Shopstaff;
use App\Http\Controllers\Shopstaff\CommonController;
use View,DB,Session,Response,Validator,Illuminate\Http\Request,Carbon\Carbon,Redirect;
use App\Models\Order\Order,App\Models\Order\Ordershopcart,App\Models\Commodity\Commodity,App\Models\Commodity\Skulist,App\Models\Commodity\Shopcart,App\Models\Customer\Customer,App\Models\Express\Receiver,App\Models\Brand\Brand,App\Models\Shop\Shopinfo,App\Models\Coupon\Coupon,App\Models\Weixin\Account,App\Models\Order\Orderrefund;
use EasyWeChat\Foundation\Application;
class WeborderController extends CommonController{
	#-------订单管理
	public function getIndex(){
		$Order = new Order;
		$Order->setTable($this->brandname."_order");
		$order_list = $Order->where('shop_id',$this->shop_id)->orderBy('order_at','desc')->paginate(10);
		$hurry_times = $Order->where('shop_id',$this->shop_id)->where('status',2)->where('hurry_times','>',0)->orderBy('order_at','desc')->count();
		foreach ($order_list as $key => $order) {
			$order->order_at = date('Y-m-d H:i:s',$order->order_at);
			$order->total = number_format($order->total,2,'.','');
			$Ordershopcart = new Ordershopcart;
			$Ordershopcart->setTable($this->brandname.'_order_shopcart');
			$order->commoditys = $Ordershopcart->where('order_id',$order->id)->select('shopcart_id')->get();
			foreach ($order->commoditys as $key => $item) {
				$Shopcart = new Shopcart;
				$Shopcart->setTable($this->brandname.'_shopcart');
				$shopcart = $Shopcart->find($item->shopcart_id);
				$item->commodity_id = $shopcart->commodity_id;
				$item->sku_id = $shopcart->sku_id;
				$item->count = $shopcart->count;

				$Commodity = new Commodity;
				$Commodity->setTable($this->brandname.'_commodity');
				$commodity = $Commodity->find($item->commodity_id);
				$item->commodity_name = $commodity->commodity_name;
				$item->main_img = $commodity->main_img;

				$Skulist = new Skulist;
				$Skulist->setTable($this->brandname.'_skulist');
				$skulist = $Skulist->find($item->sku_id);
				$item->commodity_sku = json_decode($skulist->commodity_sku,true);
				$item->price = $skulist->price;
			}
			$Receiver = new Receiver;
			$Receiver->setTable($this->brandname.'_receiver_address');
			$receiver = $Receiver->find($order->address_id);
			$order->receiver_phone = $receiver->receiver_phone;
			$order->receiver_name = $receiver->receiver_name;
			$order->receiver_province = $receiver->province;
			$order->receiver_city = $receiver->city;
			$order->receiver_district = $receiver->district;
			$order->receiver_address_details = $receiver->address_details;


			$Customer = new Customer;
			$Customer->setTable($this->brandname.'_customers');
			$order->nickname = $Customer->find($order->customer_id)->nickname;

			$order->service_phone = Shopinfo::find($order->shop_id)->customer_service_phone;

			if($order->status == 6){
				//退款中
				$Refund = new Orderrefund;
				$Refund->setTable($this->brandname.'_order_refund');
				$refund = $Refund->where('order_id',$order->id)->where('status',0)->first();
				$order->refund_description = $refund->description;
				if($refund->img_src){
					$order->refund_imgs = explode(',',$refund->img_src);
				}else{
					$order->refund_imgs = array();
				}
			}
		}
		//var_dump(array('order_lists' => $order_list->toArray()['data'],'hurry_times' => $hurry_times));
		return View::make('shopstaff.weborder.index',array('order_lists' => $order_list,'hurry_times' => $hurry_times));
	}

	#-------搜索订单
	public function postSelect(Request $request){
		$condition = $request->all();
		$condition = array_filter($condition);//过滤空元素
		$Order = new Order;
		$Order->setTable($this->brandname."_order");

		$result = collect([]);
		if(array_key_exists('number', $condition)){	
			//订单号和支付号唯一搜索
			if($condition['num_type'] == 1){
				//订单号
				$result = $Order->where('order_num',$condition['number'])->paginate(10);
			}else if($condition['num_type'] == 2){
				//支付流水号
				$result = $Order->where('trade_num',$condition['number'])->paginate(10);
			}
		}else{
			$sql = $Order;
			if(array_key_exists('status', $condition)){
				if($condition['status']==5){
					$sql = $sql->whereIn('status',[5,7]);
				}else{
					$sql = $sql->where('status',$condition['status']);
				}
			}else{
				$sql = $sql->where('status','>',0);
			}
			if(array_key_exists('date_type', $condition)){
				switch($condition['date_type']){
					case 1:
						//自定义
					if(array_key_exists('date_start', $condition) && array_key_exists('date_end', $condition)){
						$sql = $sql->where('order_at','>=',strtotime($condition['date_start']))->where('order_at','<=',strtotime($condition['date_end']));
					}
						break;
					case 2:
						$sql = $sql->where('order_at','>=',Carbon::now()->subdays(7)->timestamp)->where('order_at','<=',time());
						break;
					case 3:
						$sql = $sql->where('order_at','>=',Carbon::now()->subdays(30)->timestamp)->where('order_at','<=',time());
						break;
					default:
						break;
				}
			}
			
			if(array_key_exists('name', $condition)){
				// $result = $result->filter(function($order) use($condition){
				// 		$Receiver = new Receiver;   
				// 		$Receiver->setTable($this->brandname.'_receiver_address');
				// 		$receiver = $Receiver->find($order->address_id);
				// 		return preg_match('/'.$condition['name'].'/', $receiver->receiver_name);
				// 	});
				$name_arr = array();
				$Receiver = new Receiver;   
				$Receiver->setTable($this->brandname.'_receiver_address');
				$receiver = $Receiver->where('receiver_name','like','%'.$condition['name'].'%')->get()->pluck('id');
				$sql = $sql->whereIn('address_id',$receiver);

				if(array_key_exists('phone', $condition)){
					$phone_arr = array();
					$Receiver = new Receiver;   
					$Receiver->setTable($this->brandname.'_receiver_address');
					$receiver = $Receiver->where('receiver_phone','like','%'.$condition['phone'].'%')->get()->pluck('id');
					$sql = $sql->whereIn('address_id',$receiver);
				}
			}else{
				if(array_key_exists('phone', $condition)){
					$phone_arr = array();
					$Receiver = new Receiver;   
					$Receiver->setTable($this->brandname.'_receiver_address');
					$receiver = $Receiver->where('receiver_phone','like','%'.$condition['phone'].'%')->get()->pluck('id');
					$sql = $sql->whereIn('address_id',$receiver);
				}
			}
			$result = $sql->orderBy('order_at','desc')->paginate(10);
		}

		foreach ($result as $key => $order) {
			$order->order_at = date('Y-m-d H:i:s',$order->order_at);
			$Ordershopcart = new Ordershopcart;
			$Ordershopcart->setTable($this->brandname.'_order_shopcart');
			$order->commoditys = $Ordershopcart->where('order_id',$order->id)->select('shopcart_id')->get();
			foreach ($order->commoditys as $key => $item) {
				$Shopcart = new Shopcart;
				$Shopcart->setTable($this->brandname.'_shopcart');
				$shopcart = $Shopcart->find($item->shopcart_id);
				$item->commodity_id = $shopcart->commodity_id;
				$item->sku_id = $shopcart->sku_id;
				$item->count = $shopcart->count;

				$Commodity = new Commodity;
				$Commodity->setTable($this->brandname.'_commodity');
				$commodity = $Commodity->find($item->commodity_id);
				$item->commodity_name = $commodity->commodity_name;
				$item->main_img = $commodity->main_img;

				$Skulist = new Skulist;
				$Skulist->setTable($this->brandname.'_skulist');
				$skulist = $Skulist->find($item->sku_id);
				$item->commodity_sku = json_decode($skulist->commodity_sku,true);
				$item->price = $skulist->price;
			}
			$Receiver = new Receiver;
			$Receiver->setTable($this->brandname.'_receiver_address');
			$receiver = $Receiver->find($order->address_id);
			$order->receiver_phone = $receiver->receiver_phone;
			$order->receiver_name = $receiver->receiver_name;
			$order->receiver_province = $receiver->province;
			$order->receiver_city = $receiver->city;
			$order->receiver_district = $receiver->district;
			$order->receiver_address_details = $receiver->address_details;

			$Customer = new Customer;
			$Customer->setTable($this->brandname.'_customers');
			$order->nickname = $Customer->find($order->customer_id)->nickname;
			if($order->status == 6){
				//退款中
				$Refund = new Orderrefund;
				$Refund->setTable($this->brandname.'_order_refund');
				$refund = $Refund->where('order_id',$order->id)->where('status',0)->first();
				$order->refund_description = $refund->description;
				if($refund->img_src){
					$order->refund_imgs = explode(',',$refund->img_src);
				}else{
					$order->refund_imgs = array();
				}
			}
		}
		return Response::json(View::make('shopstaff.weborder.content', ['order_lists'=>$result])->render());
		//return Response::json(['status' => 'success','order_lists' => $result]);
		
	}

	#-------切换状态
	public function getChangestatus(Request $request){
		$Order = new Order;
		$Order->setTable($this->brandname."_order");
		$status = $request->status;
		if($status == 0){
			$order_list = $Order->where('shop_id',$this->shop_id)->orderBy('order_at','desc')->paginate(10);
		}else if($status == 5){
			$order_list = $Order->where('shop_id',$this->shop_id)->whereIn('status',[5,7])->orderBy('order_at','desc')->paginate(10);
		}else{
			$order_list = $Order->where('shop_id',$this->shop_id)->where('status',$status)->orderBy('order_at','desc')->paginate(10);
		}
		$hurry_times = $Order->where('shop_id',$this->shop_id)->where('status',2)->where('hurry_times','>',0)->orderBy('order_at','desc')->count();
		foreach ($order_list as $key => $order) {
			$order->order_at = date('Y-m-d H:i:s',$order->order_at);
			$Ordershopcart = new Ordershopcart;
			$Ordershopcart->setTable($this->brandname.'_order_shopcart');
			$order->commoditys = $Ordershopcart->where('order_id',$order->id)->select('shopcart_id')->get();
			foreach ($order->commoditys as $key => $item) {
				$Shopcart = new Shopcart;
				$Shopcart->setTable($this->brandname.'_shopcart');
				$shopcart = $Shopcart->find($item->shopcart_id);
				$item->commodity_id = $shopcart->commodity_id;
				$item->sku_id = $shopcart->sku_id;
				$item->count = $shopcart->count;

				$Commodity = new Commodity;
				$Commodity->setTable($this->brandname.'_commodity');
				$commodity = $Commodity->find($item->commodity_id);
				$item->commodity_name = $commodity->commodity_name;
				$item->main_img = $commodity->main_img;

				$Skulist = new Skulist;
				$Skulist->setTable($this->brandname.'_skulist');
				$skulist = $Skulist->find($item->sku_id);
				$item->commodity_sku = json_decode($skulist->commodity_sku,true);
				$item->price = $skulist->price;
			}
			$Receiver = new Receiver;
			$Receiver->setTable($this->brandname.'_receiver_address');
			$receiver = $Receiver->find($order->address_id);
			$order->receiver_phone = $receiver->receiver_phone;
			$order->receiver_name = $receiver->receiver_name;
			$order->receiver_province = $receiver->province;
			$order->receiver_city = $receiver->city;
			$order->receiver_district = $receiver->district;
			$order->receiver_address_details = $receiver->address_details;

			$Customer = new Customer;
			$Customer->setTable($this->brandname.'_customers');
			$order->nickname = $Customer->find($order->customer_id)->nickname;
			if($order->status == 6){
				//退款中
				$Refund = new Orderrefund;
				$Refund->setTable($this->brandname.'_order_refund');
				$refund = $Refund->where('order_id',$order->id)->where('status',0)->first();
				$order->refund_description = $refund->description;
				if($refund->img_src){
					$order->refund_imgs = explode(',',$refund->img_src);
				}else{
					$order->refund_imgs = array();
				}
			}
		}
		//var_dump(array('order_lists' => $order_list,'hurry_times' => $hurry_times));
		//return View::make('shopstaff.weborder.index',array('order_lists' => $order_list,'hurry_times' => $hurry_times));
		return Response::json(View::make('shopstaff.weborder.content', ['order_lists'=>$order_list])->render());
	}
	#-------发货
	public function postSend(Request $request){
		$validator = Validator::make($request->all(), [
            'order_id' => 'required',
            'express_num' => 'required'
        ]);
        if ($validator->fails()) {
            return Response::json(['status' => 'error','msg' => '参数有误']);
        }
        $id = $request->order_id;
        $express_num = $request->express_num;
        $Order = new Order;
		$Order->setTable($this->brandname."_order");
		if($Order->where('express_num',$express_num)->count()){
			 return Response::json(['status' => 'error','msg' => '快递单号已经存在']);
		}
		$order = $Order->find($id);
		if($order){
			if($order->express_num){
				 return Response::json(['status' => 'error','msg' => '该笔订单快递单号已存在']);
			}
			$order->status = 3;
			$order->express_num = $express_num;
			$order->send_at = time();
			$order->deal = 1;
			$result = $order->setTable($this->brandname."_order")->save();
			if($result){
				return Response::json(['status' => 'success','msg' => '发货成功']);
			}else{
				return Response::json(['status' => 'error','msg' => '操作失败']);
			}
		}else{
			return Response::json(['status' => 'error','msg' => '订单不存在']);
		}
	}

	#-------确认收货
	public function postReceive(Request $request){
		$validator = Validator::make($request->all(), [
            'order_id' => 'required',
        ]);
        if ($validator->fails()) {
            return Response::json(['status' => 'error','msg' => '参数有误']);
        }
        $id = $request->order_id;
        $Order = new Order;
		$Order->setTable($this->brandname."_order");
		$order = $Order->find($id);
		if($order){
			DB::beginTransaction();
			try{
				$order->status = 4;
				$order->deal = 1;
				$order->setTable($this->brandname."_order")->save();

				//更新会员积分,按1元1分
				$Customer = new Customer;
				$Customer->setTable($this->brandname.'_customers');
				$customer = $Customer->find($order->customer_id);
				$customer->score = floor($order->total);
				$customer->setTable($this->brandname.'_customers')->save();
				DB::commit();
			}catch (Exception $e){
                DB::rollback();
                return Response::json(['status' => 'error','msg' => '操作失败']);
            }
			
			return Response::json(['status' => 'success','msg' => 'success.']);
		}else{
			return Response::json(['status' => 'error','msg' => '订单不存在']);
		}
	}

	#-------退款
	public function postRefund(Request $request){
		$validator = Validator::make($request->all(), [
            'order_id' => 'required',
            'money'=>'required|numeric'
        ]);
        if ($validator->fails()) {
            return Response::json(['status' => 'error','msg' => '参数有误']);
        }
	    $order_id =  $request->order_id;
	    $refund_money = $request->money;
		$Order = new Order;
		$Order->setTable($this->brandname."_order");
		$order = $Order->find($order_id);
		if(!$order){
			return Response::json(['status' => 'error','msg' => '订单不存在']);
		}
		if($order->status != 6){
			return Response::json(['status' => 'error','msg' => '订单未申请退款']);
		}
		if($refund_money>$order->total || $refund_money<=0){
			return Response::json(['status' => 'error','msg' => '无效的退款金额']);
		}
		//订单总金额
		$total_fee = $Order->where('trade_num',$order->trade_num)->sum('total');
		//生成退款单号
		$refund_num = $this->build_order_no();
		
		$account = Account::where('brand_id',$this->brand_id)->first();
		$brand = Brand::find($this->brand_id);
		$cert_path = 'uploads/'.$account->brand_id.'/apiclient/apiclient_cert.pem';
		$key_path = 'uploads/'.$account->brand_id.'/apiclient/apiclient_key.pem';
		//$cert_path = 'http://shop.dataguiding.com/uploads/1/apiclient/apiclient_cert.pem';
		//$key_path = 'http://shop.dataguiding.com/uploads/1/apiclient/apiclient_key.pem';

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
		        'cert_path'          => public_path($cert_path), // XXX: 绝对路径！！！！
		        'key_path'           => public_path($key_path),      // XXX: 绝对路径！！！！
		        'notify_url'         => 'http://shop.dataguiding.com/shop/order/notify',       // 你也可以在下单时单独设置来想覆盖它
		    ],
		];
		$app = new Application($options);
		$payment = $app->payment;		
		$result = $payment->refundByTransactionId($order->trade_num, $refund_num, $total_fee*100, $refund_money*100);
		if($result->toArray()['return_code']=='SUCCESS' && $result->toArray()['return_msg']=='OK'){
			$order->refund_num = $refund_num;
			$order->status = 7;
			$order->close_type = 3;
			$order->refund_at = time();
			$order->refund_money = $refund_money;
			$order->deal = 1;
			$order->setTable($this->brandname."_order")->save();

			$Refund = new Orderrefund;
			$Refund->setTable($this->brandname.'_order_refund');
			$refund = $Refund->where('order_id',$order->id)->first();
			$refund->status = 1;
			$refund->setTable($this->brandname.'_order_refund')->save();

			//向用户发送消息
			$Customer = new Customer;
			$Customer->setTable($this->brandname.'_customers');
			$customer = $Customer->find($order->customer_id);
			$userId = $customer->openid;
			if($customer->status){
				$templateId = $account->msg_refund;
				$url = 'http://shop.dataguiding.com/shop/order/index'.$this->brand_id;
				$color = '#FF0000';
				$data = array(
				         "first"  => "您好，您的订单".$order->order_num."商家已同意退款。",
				         "reason"   => '主动申请退款',
				         "refund"  =>$order->total."元",
				         "remark" =>"欢迎您再次购买！",
				        );	
				$notice = $app->notice;
				$messageId = $notice->uses($templateId)->withUrl($url)->andData($data)->andReceiver($userId)->send();
			}	
			return Response::json(['status' => 'success','msg' => 'success.']);
		}else{
			return Response::json(['status' => 'error','msg' => $result->toArray()['return_msg']]);
		}
		
	}

	#-------订单详情
	public function getDetail($id){
		$Order = new Order;
		$Order->setTable($this->brandname."_order");
		$order = $Order->find($id);
		if(!$order){
			return Redirect::back();
		}
		$order->order_at = date('Y-m-d H:i:s',$order->order_at);
		$order->trade_at = ($order->trade_at==0)?0:date('Y-m-d H:i:s',$order->trade_at);
		$order->send_at = ($order->send_at==0)?0:date('Y-m-d H:i:s',$order->send_at);
		$order->deal = 1;

		$Ordershopcart = new Ordershopcart;
		$Ordershopcart->setTable($this->brandname.'_order_shopcart');
		$order->commoditys = $Ordershopcart->where('order_id',$order->id)->select('shopcart_id')->get();
		foreach ($order->commoditys as $key => $item) {
			$Shopcart = new Shopcart;
			$Shopcart->setTable($this->brandname.'_shopcart');
			$shopcart = $Shopcart->find($item->shopcart_id);
			$item->commodity_id = $shopcart->commodity_id;
			$item->sku_id = $shopcart->sku_id;
			$item->count = $shopcart->count;

			$Commodity = new Commodity;
			$Commodity->setTable($this->brandname.'_commodity');
			$commodity = $Commodity->find($item->commodity_id);
			$item->commodity_name = $commodity->commodity_name;
			$item->main_img = $commodity->main_img;

			$Skulist = new Skulist;
			$Skulist->setTable($this->brandname.'_skulist');
			$skulist = $Skulist->find($item->sku_id);
			$item->commodity_sku = json_decode($skulist->commodity_sku);
			$item->price = $skulist->price;
		}
		$Receiver = new Receiver;
		$Receiver->setTable($this->brandname.'_receiver_address');
		$receiver = $Receiver->find($order->address_id);
		$order->receiver_phone = $receiver->receiver_phone;
		$order->receiver_name = $receiver->receiver_name;
		$order->receiver_province = $receiver->province;
		$order->receiver_city = $receiver->city;
		$order->receiver_district = $receiver->district;
		$order->receiver_address_details = $receiver->address_details;

		if($order->status == 6){
				//退款中
				$Refund = new Orderrefund;
				$Refund->setTable($this->brandname.'_order_refund');
				$refund = $Refund->where('order_id',$order->id)->where('status',0)->first();
				$order->refund_description = $refund->description;
				if($order->img_src){
					$order->refund_imgs = explode(',',$order->img_src);
				}else{
					$order->refund_imgs = array();
				}
			}

		$Coupon = new Coupon;
		$Coupon->setTable($this->brandname.'_coupon');
		$order->discount = ($order->coupon_id==0)?0:$Coupon->find($order->coupon_id)->sum;

		return View::make('shopstaff.weborder.detail',array('order' => $order));
	}

	//生成订单编号
	public function build_order_no()
	{
	    return date('Ymd').substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
	}
}


