<?php namespace App\Http\Controllers\Admin;
//edit by xuxuxu
use App\Http\Controllers\Controller;
use App\Models\Brand\Brand,App\Models\Shop\Shopaccount,App\Models\Shop\Shopinfo,App\Models\User,App\Models\Order\Order,App\Models\Customer\Customer;
use View,Request,Response;
class DatacenterController extends Controller{
	public function __construct(){
		$this->middleware('admin');
	}
	#---------超级管理员数据中心首页
	public function getIndex(){
        $brand_count = Brand::all()->count();
        $customer_count = 0;
        $total = 0;
        $order_count = 0;

        $order = new Order;
        $customer = new Customer;
        $brand = new Brand;

        $brands = $brand->getAllBrandName();
        foreach($brands as $brand){
        	$customer->setTable($brand.'_customers');
        	$customer_count += $customer->getCustomerCount();

            $order->setTable($brand.'_order');
            $order_count += $order->getOrderCount();
            $total += $order->getTotal();

        }

		return View::make('admin.datacenter.index',array(
            'brand_count' => $brand_count,
            'total' => $total,
            'order_count' => $order_count,
            'customer_count' => $customer_count,
            ));
	}
	#----------表格数据---------
	#----------参数 start_time---------
	#---------------end_time
	#---------------unit(day week month)
	#----------返回 status---------
	#---------order_array---------
	#---------customer_array---------
	#--------total_array---------
	#----------brand_array---------
	public function postData(){
		$start = Request::input('start_time');
		$end = Request::input('end_time');
		$unit = Request::input('unit');
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
		$brand_array = array();
		$order_array = array();
		$customer_array = array();
		$total_array = array();
		$order = new Order;
		$customer = new Customer;

		$brandNames = Brand::getAllBrandName();

		for($i=$start;$i<$end;$i+=$seconds){

			$order_count_base = 0;
			$customer_count_base = 0;
			$total_base = 0;
			$brand_base = 0;

			foreach($brandNames as $brand){
				$customer->setTable($brand.'_customers');
				$customer_count_base += $customer->getNewCustomerCount($i,$i+$seconds);

				$order->setTable($brand.'_order');
				$order_count_base += $order->getNewOrder($i,$i+$seconds);
				$total_base += $order->getNewTotal($i,$i+$seconds);

			}
			array_push($brand_array,Brand::getNewBrand($i,$i+$seconds));
			array_push($total_array,$total_base);
			array_push($order_array,$order_count_base);
			array_push($customer_array,$customer_count_base);

		}
		return Response::json(array(
			'status' => 'success',
			'order_array' => $order_array,
			'customer_array' => $customer_array,
			'total_array' => $total_array,
			'brand_array'=>$brand_array,
			));
	}

	
}