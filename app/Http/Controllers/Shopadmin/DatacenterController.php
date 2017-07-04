<?php namespace App\Http\Controllers\Shopadmin;
//edit by xuxuxu
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Brand\Brand,App\Models\Shop\Shopaccount,App\Models\Shop\Shopinfo,App\Models\User,App\Models\Order\Order,App\Models\Customer\Customer;
use View,Auth,Session,Response;
class DatacenterController extends Controller{
    public function __construct(){
        $this->middleware('shopadmin');
    }
    #-------------小店超级管理员数据中心首页
	public function getIndex(){
		$brand_id = Auth::user()->brand_id;
		$brandname = Brand::find($brand_id)->brandname;
		$shop_id = Auth::user()->shop_id;
		$shopname = Shopinfo::find($shop_id)->shopname;

		$order = new Order;
		$order->setTable($brandname.'_order');
		$order_count = $order->getShopCount($shop_id);
		$total = $order->getShopTotal($shop_id);

		$customer = new Customer;
		$customer->setTable($brandname.'_customers');
		$customer_count = $customer->getShopCustomerCount($shop_id);
		return View::make('shopadmin.datacenter.index',array(
			'shopname'=>$shopname,
			'brandname' => $brandname,
			'brand_id' => $brand_id,
			'order_count'=>$order_count,
			'total'=>$total,
			'customer_count'=>$customer_count,
			));
	}
    #------------图表数据----------
	public function postData(Request $request){
        $start = $request->input('start_time');
        $end = $request->input('end_time');
        $brand_id = Auth::user()->brand_id;
        $shop_id = Auth::user()->shop_id;
        $unit = $request->input('unit');
        $seconds = 60*60*6 ;//6小时
        $brandname = Brand::find($brand_id)->brandname;
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
                    'order_array' => [],
                    'customer_array' => [],
                    'total_array' => [],
                    ));
        }

        $order_array = array();
        $customer_array = array();
        $total_array = array();
        
        $order = new Order;
        $order->setTable($brandname.'_order');
        $customer = new Customer;
        $customer->setTable($brandname.'_customers');

        for($i=$start;$i<$end;$i+=$seconds){

            $order_count_base = 0;
            $customer_count_base = 0;
            $total_base = 0;
           
            $customer_count_base += $customer->getNewShopCustomerCount($i,$i+$seconds,$shop_id);
            $order_count_base += $order->getShopNewOrder($i,$i+$seconds,$shop_id);
            $total_base += $order->getShopNewTotal($i,$i+$seconds,$shop_id);

            array_push($total_array,$total_base);
            array_push($order_array,$order_count_base);
            array_push($customer_array,$customer_count_base);

        }
        return Response::json(array(
            'status' => 'success',
            'order_array' => $order_array,
            'customer_array' => $customer_array,
            'total_array' => $total_array,
            'message'=>'获取成功',
            ));
    }



}