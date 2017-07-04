<?php namespace App\Http\Controllers\Brand;
//edit by xuxuxu
use App\Models\Brand\Brand,App\Models\Shop\Shopaccount,App\Models\Shop\Shopinfo,App\Models\User,App\Models\Order\Order,App\Models\Customer\Customer;
use View,Request,Auth,Response;
class DatacenterController extends CommonController{
	#---------首页-------------
	public function getIndex(){
		$brand_id = Auth::user()->brand_id;
		$brandname = Brand::find($brand_id)->brandname;

		$shop_count = Shopinfo::where('brand_id',$brand_id)->count();
		$order = new Order;
		$order->setTable($brandname.'_order');
		$order_count = $order->getOrderCount();
		$total = $order->getTotal();

		$customer = new Customer;
		$customer->setTable($brandname.'_customers');
		$customer_count = $customer->getCustomerCount();
		$fans_count = $customer->getFansCount();
		return View::make('brand.datacenter.index',array(
			'shop_count' => $shop_count,
			'brandname' => $brandname,
			'brand_id' => $brand_id,
			'order_count'=>$order_count,
			'total'=>$total,
			'customer_count'=>$customer_count,
			'fans_count'=>$fans_count,
			));
	}
	#-----------图表数据-----------
	#----------参数 start_time---------
	#---------------end_time
	#---------------unit(day week month)
	#---------粉丝与顾客的区别：
	#---------粉丝只关注了微信公众号但未注册小店
	public function postData(){
		$start = Request::input('start_time');
		$end = Request::input('end_time');
		$unit = Request::input('unit');
		$brand_id = Auth::user()->brand_id;
		$brand = Brand::find($brand_id)->brandname;

		$seconds = 60*60*6 ;//6小时
		switch ($unit) {
            case 'day': 
                break;
            case 'week':
                $seconds = $seconds*4;//一天
                break;
            case 'month':
                $seconds = $seconds*20;//5天
                break;
			default:
				return Response::json(array(
					'status' => 'fail',
					'message' => '单位不正确',
					));
		}
		$fans_array = array();
		$order_array = array();
		$customer_array = array();
		$total_array = array();
		$order = new Order;
		$customer = new Customer;

		for($i=$start;$i<$end;$i+=$seconds){
			$customer_count_base = 0;
			$fans_count_base = 0;
			$order_count_base = 0;
			$total_base = 0;
			
			$customer->setTable($brand.'_customers');
			$customer_count_base += $customer->getNewCustomerCount($i,$i+$seconds);
			$fans_count_base += $customer->getNewFansCount($i,$i+$seconds);

			$order->setTable($brand.'_order');
			$order_count_base += $order->getNewOrder($i,$i+$seconds);
			$total_base += $order->getNewTotal($i,$i+$seconds);

			array_push($total_array,$total_base);
			array_push($order_array,$order_count_base);
			array_push($customer_array,$customer_count_base);
			array_push($fans_array,$fans_count_base);

		}
		
		return Response::json(array(
			'status' => 'success',
			'message'=>'获取数据成功',
			'order_array' => $order_array,
			'customer_array' => $customer_array,
			'total_array' => $total_array,
			'fans_array'=>$fans_array,
			));
	}


}