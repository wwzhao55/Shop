<?php namespace App\Http\Controllers\Shopstaff;
use App\Http\Controllers\Shopstaff\CommonController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Brand\Brand,App\Models\Shop\Shopaccount,App\Models\Shop\Shopinfo,App\Models\User,App\Models\Order\Order,App\Models\Customer\Customer;
use DB;
use View,Auth,Session,Response;
/**
* 
*/
class ApporderController extends CommonController
{
	
	function __construct()
	{
		# code...
	}

	//preorder manage 
	public function getPreordermanage(){
		// $brand_id = Auth::user()->brand_id;
		$brand_id=1;
		$brandname = Brand::find($brand_id)->brandname;
		// $brandname="幸菓";
		// $shop_id = Auth::user()->shop_id;
		$shop_id=1;
		$shopname = Shopinfo::find($shop_id)->shopname;

		//have disposed order
		$dopreorder=array();
		//need dispose order
		$preorder=array();
		//have disposed order
		$doorder=array();
		//need dispose order
		$order=array();
		//commodity
		var_dump($brandname);
/////////////////////////////////////////preordermanage/////////////////////////////////./////////////////
		//dopreorder 
		$result1=DB::table($brandname.'_app_order')
        ->where('status',0)
        ->where('isdispose',0)
        ->get();
        $i=0;
        foreach ($result1 as $key => $value) {
        	// $clerk_id=substr($value->identifer, 2);
        	$table_id=$value->identifer;
        	$dopreorder[$i]['clerk_id']=$clerk_id;
        	$dopreorder[$i]['clerk_name']="";
        	$dopreorder[$i]['table']=$table_id;
        	// $doorder[$i]['order_num']=$value->order_num;
        	$dopreorder[$i]['time']=date('Y-m-d H:i:s',$value->created_at);
        	$j=0;
        	$re=DB::table($brandname.'_app_order')->where('identifer','=',$value->identifer)->where('status','=',0)->where('isdispose',0)->get();
        	foreach ($re as $key1 => $value1) {
        		$commodity=array();
        		$commodity[$j]['commodity_id']=$value1->commodity_id;
        		$re1=DB::table($brandname.'_commodity')->where('id',$value1->commodity_id)->get();
        		$commodity[$j]['commodity_name']=$re1[0]->commodity_name;
        		$commodity[$j]['count']=$value1->count;
        		$j++;
        	}
        	$dopreorder[$i]['commodity']=$commodity;
        	$dopreorder[$i]['status']=$value->status;
        	$i++;		
        }
        //order
        $result2=DB::table($brandname.'_app_order')
        ->where('status',0)
        ->where('isdispose',1)
        ->get();
        $i=0;
        foreach ($result2 as $key => $value) {
        	$clerk_id=substr($value->identifer, 2);
        	$table_id=substr($value->identifer, -2);
        	$preorder[$i]['clerk_id']=$clerk_id;
        	$preorder[$i]['clerk_name']="";
        	$preorder[$i]['table']=$table_id;
        	// $doorder[$i]['order_num']=$value->order_num;
        	$preorder[$i]['time']=date('Y-m-d H:i:s',$value->created_at);
        	$j=0;
        	$re=DB::table($brandname.'_app_order')->where('identifer','=',$value->identifer)->where('status','=',0)->where('isdispose',1)->get();
        	foreach ($re as $key1 => $value2) {
        		$commodity=array();
        		$commodity[$j]['commodity_id']=$value2->commodity_id;
        		$re2=DB::table($brandname.'_commodity')->where('id',$value2->commodity_id)->get();
        		$commodity[$j]['commodity_name']=$re2[0]->commodity_name;
        		$commodity[$j]['count']=$value2->count;
        		$j++;
        	}
        	$preorder[$i]['commodity']=$commodity;
        	$preorder[$i]['status']=$value->status;
        	$i++;		
        }
/////////////////////////////////////////ordermanage/////////////////////////////////./////////////////
        //doorder 
		$result3=DB::table($brandname.'_order')
        ->where('status',0)
        ->get();

        $i=0;
        foreach ($result3 as $key => $value) {
        	$clerk_id=substr($value->identifer, 2);
        	$table_id=substr($value->identifer, -2);
        	$doorder[$i]['clerk_id']=$clerk_id;
        	$doorder[$i]['clerk_name']="";
        	$doorder[$i]['table']=$table_id;
        	$doorder[$i]['order_num']=$value->order_num;
        	$doorder[$i]['time']=date('Y-m-d H:i:s',$value->created_at);
        	$j=0;
        	$re3=DB::table($brandname.'_order')->where('order_num','=',$value->order_num)->where('status','=',0)->get();
        	foreach ($re3 as $key1 => $value1) {
        		$commodity=array();
        		$commodity[$j]['commodity_id']=$value1->commodity_id;
        		$re1=DB::table($brandname.'_commodity')->where('id',$value1->commodity_id)->get();
        		$commodity[$j]['commodity_name']=$re1[0]->commodity_name;
        		$commodity[$j]['count']=$value1->count;
        		$j++;
        	}
        	$doorder[$i]['commodity']=$commodity;
        	$doorder[$i]['status']=$value->status;
        	$i++;		
        }
        //order
        $result4=DB::table($brandname.'_order')
        ->where('status',1)
        ->get();
        $i=0;
        foreach ($result4 as $key => $value) {
        	// $clerk_id=substr($value->identifer, 2);
        	$table_id= $value->identifer;
        	$order[$i]['clerk_id']=$clerk_id;
        	$order[$i]['clerk_name']="";
        	$order[$i]['table']=$table_id;
        	$order[$i]['order_num']=$value->order_num;
        	$order[$i]['time']=date('Y-m-d H:i:s',$value->created_at);
        	$j=0;
        	$re4=DB::table($brandname.'_app_order')->where('order_num','=',$value->order_num)->where('status','=',1)->get();
        	foreach ($re4 as $key1 => $value2) {
        		$commodity=array();
        		$commodity[$j]['commodity_id']=$value2->commodity_id;
        		$re5=DB::table($brandname.'_commodity')->where('id',$value2->commodity_id)->get();
        		$commodity[$j]['commodity_name']=$re5[0]->commodity_name;
        		$commodity[$j]['count']=$value2->count;
        		$j++;
        	}
        	$order[$i]['commodity']=$commodity;
        	$order[$i]['status']=$value->status;
        	$i++;		
        }

        var_dump($dopreorder);
        var_dump($doorder);
        var_dump($preorder);
        var_dump($order);
		return View::make('shopstaff.apporder.index',array(
			'shopname'=>$shopname,
			'brandname' => $brandname,
			'brand_id' => $brand_id,
			'dopreorder'=>$dopreorder,
			'preorder'=>$preorder,
			'doorder'=>$doorder,
			'order'=>$order,
			));
	}
	//dispose the preorder
	public function getDisposeorder($table){
		$brand_id = Auth::user()->brand_id;
		// $brand_id=3;
		$brandname = Brand::find($brand_id)->brandname;
		$shop_id = Auth::user()->shop_id;
		// $shop_id=3;
		$shopname = Shopinfo::find($shop_id)->shopname;
		$re=DB::table($brandname.'_app_order')->where('identifer','=',$table)->update(array('status'=>1));
		if($re){
			echo json_encode(array('status'=>'success','msg'=>'dispose success!'));
		}else{
			echo json_encode(array('status'=>'error','msg'=>'dispose failed!'));
		}
	}
	// click order show the all commodity in this order 
	public function getShowcommodity($order_num){
		$brand_id = Auth::user()->brand_id;
		// $brand_id=1;
		$brandname = Brand::find($brand_id)->brandname;
		$shop_id = Auth::user()->shop_id;
		// $shop_id=1;
		$shopname = Shopinfo::find($shop_id)->shopname;
		$re=DB::table($brandname.'_app_order')->where('order_num','=',$order_num)->get();
		$commodity=array();
		$i=0;
		foreach ($re as $key => $value){
			$commodity[$i]['commodity_id']=$value->commodity_id;
			$commodity[$i]['count']=$value->count;
			$re1=DB::table($brandname.'_commodity')->where('id','=',$value->commodity)->get();

			foreach ($re1 as $key1 => $value1) {
				$commodity[$i]['commodity_name']=$value1->commodity_name;
				$commodity[$i]['img']=$value1->main_img;

			}
			$i++;
		}
		if($commodity){
			echo json_encode(array('status'=>'success','msg'=>'get commodity success!','commodity'=>$commodity));
		}else{
			echo json_encode(array('status'=>'error','msg'=>'get commodity failed!','commodity'=>$commodity));
		}
	}
	// order manage
	public function getOrdermanage(){
		// $brand_id = Auth::user()->brand_id;
		$brand_id=3;
		$brandname = Brand::find($brand_id)->brandname;
		// $shop_id = Auth::user()->shop_id;
		$shop_id=3;
		$shopname = Shopinfo::find($shop_id)->shopname;

		//have disposed order
		$doorder=array();
		//need dispose order
		$order=array();
		//commodity
		

		//doorder 
		$result1=DB::table($brandname.'_order')
        ->where('status',0)
        ->get();
        var_dump($result1);
        $i=0;
        foreach ($result1 as $key => $value) {
        	$clerk_id=substr($value->identifer, 2);
        	$table_id=substr($value->identifer, -2);
        	$doorder[$i]['clerk_id']=$clerk_id;
        	$doorder[$i]['clerk_name']="";
        	$doorder[$i]['table']=$table_id;
        	$doorder[$i]['order_num']=$value->order_num;
        	$doorder[$i]['time']=date('Y-m-d H:i:s',$value->created_at);
        	$j=0;
        	$re=DB::table($brandname.'_order')->where('order_num','=',$value->order_num)->where('status','=',0)->get();
        	foreach ($re as $key1 => $value1) {
        		$commodity=array();
        		$commodity[$j]['commodity_id']=$value1->commodity_id;
        		$re1=DB::table($brandname.'_commodity')->where('id',$value1->commodity_id)->get();
        		$commodity[$j]['commodity_name']=$re1[0]->commodity_name;
        		$commodity[$j]['count']=$value1->count;
        		$j++;
        	}
        	$doorder[$i]['commodity']=$commodity;
        	$doorder[$i]['status']=$value->status;
        	$i++;		
        }
        //order
        $result2=DB::table($brandname.'_order')
        ->where('status',0)
        ->get();
        $i=0;
        foreach ($result2 as $key => $value) {
        	$clerk_id=substr($value->identifer, 2);
        	$table_id=substr($value->identifer, -2);
        	$order[$i]['clerk_id']=$clerk_id;
        	$order[$i]['clerk_name']="";
        	$order[$i]['table']=$table_id;
        	$order[$i]['order_num']=$value->order_num;
        	$order[$i]['time']=date('Y-m-d H:i:s',$value->created_at);
        	$j=0;
        	$re=DB::table($brandname.'_order')->where('order_num','=',$value->order_num)->where('status','=',0)->get();
        	foreach ($re as $key1 => $value2) {
        		$commodity=array();
        		$commodity[$j]['commodity_id']=$value2->commodity_id;
        		$re2=DB::table($brandname.'_commodity')->where('id',$value2->commodity_id)->get();
        		$commodity[$j]['commodity_name']=$re2[0]->commodity_name;
        		$commodity[$j]['count']=$value2->count;
        		$j++;
        	}
        	$order[$i]['commodity']=$commodity;
        	$order[$i]['status']=$value->status;
        	$i++;		
        }
        var_dump($doorder);
        var_dump($order);
		return View::make('shopstaff.apporder.index',array(
			'shopname'=>$shopname,
			'brandname' => $brandname,
			'brand_id' => $brand_id,
			'doorder'=>$doorder,
			'order'=>$order,
			));
	}

	
}