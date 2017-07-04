<?php namespace App\Http\Controllers\Shopstaff;
use App\Http\Controllers\Shopstaff\CommonController;
/**
* 
*/

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Brand\Brand,App\Models\Shop\Shopaccount,App\Models\Shop\Shopinfo,App\Models\User,App\Models\Order\Order,App\Models\Customer\Customer;
use DB;
use View,Auth,Session,Response;
class WeborderController extends CommonController
{
	
	function __construct()
	{
		# code...
	}

	public function getIndex(){
		echo "WeborderController";
	}


	public function getPreordermanage(){
		// $brand_id = Auth::user()->brand_id;
		$brand_id=1;
		// $brandname='brandhead';
		$brandname = Brand::find($brand_id)->brandname;
		// $shop_id = Auth::user()->shop_id;
		$shop_id=1;
		$shopname = Shopinfo::find($shop_id)->shopname;


		//order all order///////////////////////////////////////////////////////////////////////////////
		$result=DB::table($brandname.'_order')
        ->where('id','>',0)
        ->get();
        $i=0;
        foreach ($result as $key => $value) {
        	$order[$i]['time']=date('Y-m-d H:i:s',$value->created_at);
        	$order[$i]['status']=$value->status;
        	$order[$i]['order_num']=$value->order_num;
        	$order[$i]['trade_num']=$value->trade_num;
        	$order[$i]['customer_id']=$value->customer_id;

        	$j=0;
        	$re=DB::table('users')->where('id','=',$value->customer_id)->get();
        	foreach ($re as $key1 => $value1) {
        		$users=array();
        		$users[$j]['name']=$value1->name;
        		$users[$j]['mobile']=$value1->mobile;
        		$j++;
        	}
        	$order[$i]['customer']=$users;

        	
        	$res=DB::table($brandname."_order")->where('id',$value->order_num)->get();
        	$order_id=$res[0]->id;
        	$resu=DB::table($brandname."_order_shopcart")->where('order_id',$order_id)->get();
        	$shopcart_id=$resu[0]->id;
        	$resul=DB::table($brandname."_shopcart")->where('id',$shopcart_id)->get();
        	$commodity_id=$resul[0]->commodity_id;
        	$sku_id=$resul[0]->sku_id;
        	$result=DB::table($brandname."_commodity")->where('id',$commodity_id)->get();
        	$commodity_name=$result[0]->commodity_name;
        	$results=DB::table($brandname."_skuname")->where('id',$sku_id)->get();
        	$commodity_sku_name=$result[0]->sku_name;

        	$order[$i]['commodity_id']=$commodity_id;
        	$order[$i]['commodity_name']=$commodity_name;
        	$order[$i]['commodity_sku_name']=$commodity_sku_name;
        	
        	$i++;		
        }
        //order1 status =1 need dispose ///////////////////////////////////////////////////////////////////////////////
        $result1=DB::table($brandname.'_order')
        ->where('status','=',1)
        ->get();
        $i=0;
        $order1=array();
        foreach ($result1 as $key => $value) {
        	$order1[$i]['time']=date('Y-m-d H:i:s',$value->created_at);
        	$order1[$i]['status']=$value->status;
        	$order1[$i]['order_num']=$value->order_num;
        	$order1[$i]['trade_num']=$value->trade_num;
        	$order1[$i]['customer_id']=$value->customer_id;

        	$j=0;
        	$re1=DB::table('users')->where('id','=',$value->customer_id)->get();
        	foreach ($re1 as $key1 => $value1) {
        		$users=array();
        		$users[$j]['name']=$value1->name;
        		$users[$j]['mobile']=$value1->mobile;
        		$j++;
        	}
        	$order1[$i]['customer']=$users;

        	$res1=DB::table($brandname."_order")->where('id',$value->order_num)->get();
        	$order_id=$res[0]->id;
        	$resu1=DB::table($brandname."_order_shopcart")->where('order_id',$order_id)->get();
        	$shopcart_id=$resu[0]->id;
        	$resul1=DB::table($brandname."_shopcart")->where('id',$shopcart_id)->get();
        	$commodity_id=$resul[0]->commodity_id;
        	$sku_id=$resul[0]->sku_id;
        	$result1=DB::table($brandname."_commodity")->where('id',$commodity_id)->get();
        	$commodity_name=$result[0]->commodity_name;
        	$results1=DB::table($brandname."_skuname")->where('id',$sku_id)->get();
        	$commodity_sku_name=$result[0]->sku_name;

        	$order1[$i]['commodity_id']=$commodity_id;
        	$order1[$i]['commodity_name']=$commodity_name;
        	$order1[$i]['commodity_sku_name']=$commodity_sku_name;

        	$i++;		
        }
       	//order2 status =2 have disposed ///////////////////////////////////////////////////////////////////////////////
       	$result2=DB::table($brandname.'_order')
       	->where('status','=',2)
        ->get();
        $i=0;
        $order2=array();
        foreach ($result2 as $key => $value) {
        	$order2[$i]['time']=date('Y-m-d H:i:s',$value->created_at);
        	$order2[$i]['status']=$value->status;
        	$order2[$i]['order_num']=$value->order_num;
        	$order2[$i]['trade_num']=$value->trade_num;
        	$order2[$i]['customer_id']=$value->customer_id;

        	$j=0;
        	$re2=DB::table('users')->where('id','=',$value->customer_id)->get();
        	foreach ($re2 as $key1 => $value1) {
        		$users=array();
        		$users[$j]['name']=$value1->name;
        		$users[$j]['mobile']=$value1->mobile;
        		$j++;
        	}
        	$order2[$i]['customer']=$users;


        	$res2=DB::table($brandname."_order")->where('id',$value->order_num)->get();
        	$order_id=$res[0]->id;
        	$resu2=DB::table($brandname."_order_shopcart")->where('order_id',$order_id)->get();
        	$shopcart_id=$resu[0]->id;
        	$resul2=DB::table($brandname."_shopcart")->where('id',$shopcart_id)->get();
        	$commodity_id=$resul[0]->commodity_id;
        	$sku_id=$resul[0]->sku_id;
        	$result2=DB::table($brandname."_commodity")->where('id',$commodity_id)->get();
        	$commodity_name=$result[0]->commodity_name;
        	$results2=DB::table($brandname."_skuname")->where('id',$sku_id)->get();
        	$commodity_sku_name=$result[0]->sku_name;

        	$order2[$i]['commodity_id']=$commodity_id;
        	$order2[$i]['commodity_name']=$commodity_name;
        	$order2[$i]['commodity_sku_name']=$commodity_sku_name;
        	
        	$i++;		
        }
       	//order3 status =3 have finished ///////////////////////////////////////////////////////////////////////////////
       	$result3=DB::table($brandname.'_order')
        ->where('status','=',3)
        ->get();
        $i=0;
        $order3=array();
        foreach ($result3 as $key => $value) {
        	$order3[$i]['time']=date('Y-m-d H:i:s',$value->created_at);
        	$order3[$i]['status']=$value->status;
        	$order3[$i]['order_num']=$value->order_num;
        	$order3[$i]['trade_num']=$value->trade_num;
        	$order3[$i]['customer_id']=$value->customer_id;

        	$j=0;
        	$re3=DB::table('users')->where('id','=',$value->customer_id)->get();
        	foreach ($re3 as $key1 => $value1) {
        		$users=array();
        		$users[$j]['name']=$value1->name;
        		$users[$j]['mobile']=$value1->mobile;
        		$j++;
        	}
        	$order3[$i]['customer']=$users;


        	$res3=DB::table($brandname."_order")->where('id',$value->order_num)->get();
        	$order_id=$res[0]->id;
        	$resu3=DB::table($brandname."_order_shopcart")->where('order_id',$order_id)->get();
        	$shopcart_id=$resu[0]->id;
        	$resul3=DB::table($brandname."_shopcart")->where('id',$shopcart_id)->get();
        	$commodity_id=$resul[0]->commodity_id;
        	$sku_id=$resul[0]->sku_id;
        	$result3=DB::table($brandname."_commodity")->where('id',$commodity_id)->get();
        	$commodity_name=$result[0]->commodity_name;
        	$results3=DB::table($brandname."_skuname")->where('id',$sku_id)->get();
        	$commodity_sku_name=$result[0]->sku_name;

        	$order3[$i]['commodity_id']=$commodity_id;
        	$order3[$i]['commodity_name']=$commodity_name;
        	$order3[$i]['commodity_sku_name']=$commodity_sku_name;
        	

        	$i++;		
        }
        var_dump($order);
        var_dump($order1);
        var_dump($order2);
        var_dump($order3);
		return View::make('shopstaff.weborder.index',array(
			'order'=>$order,
			'order1' => $order1,
			'order2' => $order2,
			'order3'=>$order3,
			));
	}

	//dispose order
	public function getDisposeorder($express_num,$order_num){
		// echo "WeborderController";
		// $brand_id = Auth::user()->brand_id;
		$brand_id=1;
		$brandname = Brand::find($brand_id)->brandname;
		// $shop_id = Auth::user()->shop_id;
		$shop_id=1;
		$shopname = Shopinfo::find($shop_id)->shopname;
		$re=DB::table($brandname.'_order')->where('order_num','=',$order_num)->update(array('express_num'=>$express_num,'status'=>2,'updated_at'=>time()));
		if($re){
			echo json_encode(array('status'=>'success','msg'=>'dispose order success!'));
		}else{
			echo json_encode(array('status'=>'error','msg'=>'dispose order failed!'));
		}
	}
	
}