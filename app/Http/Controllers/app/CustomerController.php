<?php
namespace App\Http\Controllers\app;
use View,App\Http\Controllers\Controller,Route;
use App\Models\app\User;
use Illuminate\Http\Request;
use App\SmsApi;
use DB;
use App\Pay;
use Redirect,Validation,Session,Auth,Hash;
require_once(dirname(dirname(dirname(dirname(dirname(__FILE__))))).'/vendor'.'/pingpp-php-master/example/transfer.php');
class CustomerController extends Controller {
    public function __construct() { 
    }

    /**
	 * Setup the layout used by the controller.
	 *
	 * @return void
	 */
	protected function setupLayout()
	{
		if ( ! is_null($this->layout))
		{
			$this->layout = View::make($this->layout);
		}
	}

	public function getIndex(){
		echo "app/Customer index";
	}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//
//
//                                     shopcart
//
//
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////         
	//获取某顾客的购物车信息
    public function postGetshopcart(Request $request){
        
		$json_data = $request->getContent();
        $data = json_decode($json_data,true);
        $customer_id=$data['customer_id'];
        if(!$customer_id){
            echo json_encode(array('status'=>'error','msg'=>'please login first!'));
            exit;
        }
        $result=DB::table($data['brand_name'].'_shopcart')
        ->join($data['brand_name'].'_commodity',$data['brand_name'].'_shopcart.commodity_id','=',$data['brand_name'].'_commodity.id')
        ->where('customer_id',$customer_id)
        ->select($data['brand_name'].'_shopcart.commodity_id',$data['brand_name'].'_commodity.commodity_name',$data['brand_name'].'_commodity.category_name',$data['brand_name'].'_commodity.main_img',$data['brand_name'].'_commodity.express_price',$data['brand_name'].'_shopcart.count',$data['brand_name'].'_shopcart.status',$data['brand_name'].'_shopcart.id')
        ->get();
        $i=0;
        foreach ($result as $key => $value) {
            $re=DB::table($data['brand_name'].'_commodity_img')->where('commodity_id',$value->commodity_id)->get();
            $temp='';
            foreach ($re as $key => $value) {
                
                $temp=$temp.$value->img_src.',';
            }
            $result[$i]->img=$temp;
            $i++;
        }
       
        if($result) {
               echo json_encode(array('status'=>'success','msg'=>'get shopcart info success','data'=>$result));
           }
         else{
            echo json_encode(array('status'=>'success','msg'=>'get shopcart info success,but info is null','data'=>null));
         }

	}

    //获取某件商品的所有规格
	public function postGetsku(Request $request){
		$json_data=$request->getContent();
		$data=json_decode($json_data,true);
		$result=DB::table($data['brand_name'].'_skulist')
		->where('commodity_id','=',$data['commodity_id'])
		->select('commodity_sku','price','quantity')
		->get();
		if($result) {
               echo json_encode(array('status'=>'success','msg'=>'get commodity_sku info success','data'=>$result));
           }
         else{
            echo json_encode(array('status'=>'error','msg'=>'get commodity_sku info failed!'));
         }

	}


    //将某件商品加入购物车
	public function postAddshopcart(Request $request){
		// if(!session('uid')){
		// 	echo json_encode(array('status'=>'error','msg'=>'please login first'));
		// 	exit;
		// }
		$json_data = $request->getContent();
        $data = json_decode($json_data,true);
        $customer_id=$data['customer_id'];
        $commodity_id=$data['commodity_id'];
        $sku_id=$data['sku_id'];
        $count=$data['count'];
        if(!$customer_id){
            echo json_encode(array('status'=>'error','msg'=>'please login first!'));
            exit;
        }
        $re=DB::table($data['brand_name'].'_shopcart')
        ->where('customer_id',$customer_id)
        ->where('commodity_id',$commodity_id)
        ->where('sku_id',$sku_id)
        ->get();
        //如果商品存在，增加数量，如果不存在，增加新商品数据
        if($re){
            $pre_count=$re[0]->count;
            $result1=DB::table($data['brand_name'].'_shopcart')
            ->where('commodity_id', $commodity_id)
            ->where('sku_id',$sku_id)
            ->where('customer_id',$customer_id)
            ->update(array('count'=>$count+$pre_count));
            if($result1) {
                echo json_encode(array('status'=>'success','msg'=>'add shopcart info success(case :have thing in shopcart)','data'=>$result1));
            }
            else{
                echo json_encode(array('status'=>'error','msg'=>'add shopcart info failed!'));
            }
        }
        else{
            $result=DB::table($data['brand_name'].'_shopcart')
            ->insert(array('customer_id'=>$customer_id,'commodity_id' => $data['commodity_id'],'count' => $data['count'],'sku_id' => $data['sku_id'],'created_at'=>time(),'updated_at'=>time()));
            if($result) {
               echo json_encode(array('status'=>'success','msg'=>'add shopcart info success (case:nothing in shopcart)','data'=>$result,'customer_id'=>$customer_id));
            }
            else{
            echo json_encode(array('status'=>'error','msg'=>'add shopcart info failed!'));
            }
        }
        

	}
    //将商品删除出购物车
	public function postDelshopcart(Request $request){
		
		$json_data = $request->getContent();
        $data = json_decode($json_data,true);
        $shopcart_id=$data['shopcart_id'];
        // var_dump($shopcart_id);
        // var_dump(count($shopcart_id));
        // var_dump($shopcart_id[1]);
        for ($i=0; $i<count($shopcart_id);$i++) {
            
            $result=DB::table($data['brand_name'].'_shopcart')
            ->where('id', $shopcart_id[$i])
            ->delete();
            if($result) {
               echo json_encode(array('status'=>'success','msg'=>'del commodity from shopcart  success','data'=>$result));
            }
            else{
            echo json_encode(array('status'=>'error','msg'=>'del commodity from shopcart failed!'));
            }
        }
        

	}

    //更新购物车中某件商品的数量
    public function postUpdatecount(Request $request){
        
        $json_data = $request->getContent();
        $data = json_decode($json_data,true);
        $customer_id=$data['customer_id'];
        if(!$customer_id){
            echo json_encode(array('status'=>'error','msg'=>'please login first!'));
            exit;
        }
        $brand_name=$data['brand_name'];
        $commodity_id=$data['commodity_id'];
        $sku_id=$data['sku_id'];
        $count=$data['count'];
        $result=DB::table($brand_name.'_shopcart')
        ->where('commodity_id', $commodity_id)
        ->where('sku_id',$sku_id)
        ->where('customer_id',$customer_id)
        ->update(array('count'=>$count));
        if($result) {
               echo json_encode(array('status'=>'success','msg'=>'update count success','data'=>$result));
           }
         else{
            echo json_encode(array('status'=>'error','msg'=>'update count failed!'));
         }

    }
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//
//
//                                     order
//
//
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //测试用，暂时没有被使用
  	public function postAddorder(Request $request){
		if(!session('uid')){
			echo json_encode(array('status'=>'error','msg'=>'please login first'));
			exit;
		}
		$json_data = $request->getContent();
        $data = json_decode($json_data,true);
        $result=DB::table($data['brand_name'].'_order')
        ->insert(array('shop_id' => $data['shop_id'],'customer_id' => session(),'sku_id' => $data['sku_id']));
        if($result) {
               echo json_encode(array('status'=>'success','msg'=>'get shopcart info success','data'=>$result));
           }
         else{
            echo json_encode(array('status'=>'error','msg'=>'get shopcart info failed!'));
         }

	}

///////////////////////////////////////////////////////////////////////////////////////////////////////////
//
//
//                             search commodity
//
//
//////////////////////////////////////////////////////////////////////////////////////////////////////////
	//搜索商品，输入搜索字段，返回搜索商品信息数组
    public function postSearch(Request $request){
		$json_data = $request->getContent();
        $data = json_decode($json_data,true);
        // $shop_id=session('shop_id');
        $shop_id=3;
        // $brand_name=session('brand_name')
        $brand_name='幸菓';
        $re=DB::table($brand_name.'_commodity')
        ->where('shop_id','=',$shop_id)
        ->where('commodity_name', 'like', '%'.$data['search'].'%')->get();
        $commodity_array=array();
        $i=0;
        foreach ($re as $key => $value) {
        	$commodity_array[$i]['commodity_name']=$re[$i]->commodity_name;
        	$commodity_array[$i]['category_name']=$re[$i]->category_name;
        	$commodity_array[$i]['main_img']=$re[$i]->main_img;
        	$commodity_array[$i]['img']=$re[$i]->img;
        	$commodity_array[$i]['produce_area1']=$re[$i]->produce_area1;
        	$commodity_array[$i]['sku_info']=$re[$i]->sku_info;
        	$commodity_array[$i]['express_price']=$re[$i]->express_price;
        }
        if($re){
        	 echo json_encode(array('status'=>'success','msg'=>'get search result success','data'=>$commodity_array));
        }
        else{
            echo json_encode(array('status'=>'error','msg'=>'get search result failed!'));
         }
	}


	//商品接口
    public function postCommodity(Request $request){
		$json_data = $request->getContent();
        $data = json_decode($json_data,true);
        // $shop_id=session('shop_id');
        $shop_id=$data['shop_id'];
        // $brand_name=session('brand_name')
        $brand_name=$data['brand_name'];
        // $brand_name='幸菓';
        $re=DB::table($brand_name.'_commodity')
        ->where('shop_id','=',$shop_id)
        ->get();
        $commodity_array=array();
        $i=0;
        foreach ($re as $key => $value) {
        	$commodity_array[$i]['id']=$re[$i]->id;
        	$commodity_array[$i]['shop_id']=$re[$i]->shop_id;
        	$commodity_array[$i]['commodity_name']=$re[$i]->commodity_name;
        	$commodity_array[$i]['category_id']=$re[$i]->category_id;
            $commodity_array[$i]['intro']=$re[$i]->brief_introduction;
            $commodity_array[$i]['description']=$re[$i]->description;
        	$commodity_array[$i]['category_name']=$re[$i]->category_name;
        	$commodity_array[$i]['tag_id']=$re[$i]->tag_id;
        	$commodity_array[$i]['main_img']=$re[$i]->main_img;
            $temp="";
            $re1=DB::table($brand_name.'_commodity_img')
            ->where('commodity_id','=',$re[$i]->id)
            ->get();
            foreach ($re1 as $key => $value) {
                $temp=$temp.$value->img_src.',';

            }
        	$commodity_array[$i]['img']=$temp;
        	$commodity_array[$i]['sku_info']=$re[$i]->sku_info;
        	$commodity_array[$i]['produce_area1']=$re[$i]->produce_area1;
        	$commodity_array[$i]['produce_area2']=$re[$i]->produce_area2;
        	$commodity_array[$i]['status']=$re[$i]->status;
        	$commodity_array[$i]['express_price']=$re[$i]->express_price;
        	$commodity_array[$i]['created_at']=$re[$i]->created_at;
        	$commodity_array[$i]['updated_at']=$re[$i]->updated_at;
            $i++;
        }
        if($re){
        	 echo json_encode(array('status'=>'success','msg'=>'get search result success','data'=>$commodity_array));
        }
        else{
            echo json_encode(array('status'=>'error','msg'=>'get search result failed!'));
         }
	}	
///////////////////////////////////////////////////////////////////////////////////////////////////////////
//
//
//                             place an order 下单
//
//
//////////////////////////////////////////////////////////////////////////////////////////////////////////

    //下单接口，下单成功后，删除购物车中的这个数据
	public function postPreorder(Request $request){
		$json_data = $request->getContent();
        $data = json_decode($json_data,true);
        $clerk_id=$data['clerk_id'];
        $table_id=$data['table_id'];
        $brand_id=$data['brand_id'];
        $shopcart_id=$data['shopcart_id'];

        $re1=DB::table('brand')->where('id','=',$brand_id)->get();
        if(!$re1){
            echo json_encode(array('status'=>'error','msg'=>'can not find this brand_id'));
            exit;
        }
        $brand_name=$re1[0]->brandname;
        $bb=0;
        for ($i=0; $i<count($shopcart_id); $i++) {
            $re2=DB::table($brand_name.'_shopcart')->where('id','=',$shopcart_id[$i])->get();
            if(!$re2){
                echo json_encode(array('status'=>'error','msg'=>'can not find this data shopcart'));
                exit;
            }
            $commodity_id=$re2[0]->commodity_id;
            $count=$re2[0]->count;
            $customer_id=$re2[0]->customer_id;
            $re=DB::table($brand_name.'_app_order')
            ->where('identifer','=',$data['table_id'])
            ->where('customer_id','=',$customer_id)
            ->where('commodity_id','=',$commodity_id)
            ->where('status','=',0)
            ->get();
            if($re){
                $result=DB::table($brand_name.'_app_order')
                ->where('identifer','=',$data['table_id'])
                ->where('customer_id','=',$customer_id)
                ->where('commodity_id','=',$commodity_id)
                ->update(array('count' => $count+$re[0]->count,'staff_id' => $clerk_id,'status'=>0,'created_at'=>time()));
                if($result){
                    $aa=0;
                    }
                else{
                    $aa=1;
                    }
                $bb=$bb+$aa;
                }
            else{
                $result=DB::table($brand_name.'_app_order')
                ->insert(array('identifer'=>$data['table_id'],'clerk_id'=>$clerk_id,'customer_id'=>$customer_id,'commodity_id'=>$commodity_id,'count' => $count,'staff_id' => $clerk_id,'status'=>0,'created_at'=>time()));
                if($result){
                    $aa=0;
                }
                else{
                    $aa=1;
                }
                $bb=$bb+$aa;
            }
            
        }
        if(!$bb){
            //////////////////////del commodity from shopcart
            for($j=0; $j<count($shopcart_id); $j++){
                $result1=DB::table($brand_name.'_shopcart')
                ->where('id', $shopcart_id[$j])
                ->delete();
                if(!$result1){
                    echo json_encode(array('status'=>'error','msg'=>'del shopcart failed!'));
                    exit;
                }
            }
            // app下单后,通知后台
             $type=' app下单后,通知后台';
             $a="http://121.42.136.52:2999/pushInfo?type=".$type."hzq&age=".$table_id;
             $res = file_get_contents($a);
             echo json_encode(array('status'=>'success','msg'=>'place an order  success'));
            }
        else{
            echo json_encode(array('status'=>'error','msg'=>'place an order  failed!'));
            }
        
    }


    //获取某个顾客的下单数据
    public function postGetpreorder(Request $request){
		$json_data = $request->getContent();
        $data = json_decode($json_data,true);
        $clerk_id=$data['clerk_id'];
        $brand_id=$data['brand_id'];
        // $clerk_id=session('clerk_id');
        // $brand_id=session('brand_id');
        $re1=DB::table('brand')->where('id','=',$brand_id)->get();
        if(!$re1){
            echo json_encode(array('status'=>'error','msg'=>'can not find this brand_id'));
        }
        $brand_name=$re1[0]->brandname;

        $result=DB::table($brand_name.'_app_order')
        ->where('status','=',0)
        ->where('identifer','=',$data['table_id'])->get();
        $preorder_data=array();
        $i=0;
        $temp=0;
        //联合查询
        foreach ($result as $key => $value) {
            $commodity_id=$value->commodity_id;
        	$preorder_data[$i]['commodity_id']=$value->commodity_id;
        	$re1=DB::table($brand_name.'_commodity')->where('id','=',$value->commodity_id)->get();

        	$preorder_data[$i]['count']=$value->count;
        	$preorder_data[$i]['status']=$value->status;
            
            if(floor(($value->created_at-$temp)%86400/60) > 15){
                $preorder_data[$i]['time']=date('Y-m-d H:i:s',$value->created_at);
                $temp=$value->created_at;
            }else{
                $preorder_data[$i]['time']=date('Y-m-d H:i:s',$temp);
            }
        	$preorder_data[$i]['commodity_name']=$re1[0]->commodity_name;
        	$preorder_data[$i]['main_img']=$re1[0]->main_img;
            $temp_img="";
            $re2=DB::table($brand_name.'_commodity_img')
            ->where('commodity_id','=',$commodity_id)
            ->get();
            foreach ($re2 as $key => $value) {
                $temp_img=$temp_img.$value->img_src.',';

            }
        	$preorder_data[$i]['img']=$temp_img;
        	$preorder_data[$i]['express_price']=$re1[0]->express_price;
        	$i++;

        }
        if($result){
        	 echo json_encode(array('status'=>'success','msg'=>'get preorder  success','data'=>$preorder_data));
        }
        else{
            echo json_encode(array('status'=>'error','msg'=>'get preorder  failed!'));
         }
    }
///////////////////////////////////////////////////////////////////////////////////////////////////////////
//
//
//                             place an order  结算生成付款二维码
//
//
//////////////////////////////////////////////////////////////////////////////////////////////////////////
    //结算，生成付款二维码，返回二维码数组，app端使用工具，显示为二维码图片
    public function postApppay(Request $request){
        $json_data = $request->getContent();
        $data = json_decode($json_data,true);
        $pay = new Pay;
        $shop_id=$data['shop_id'];
        $clerk_id=$data['clerk_id'];
        if(!$clerk_id){
            echo json_encode(array('status'=>'error','msg'=>'find clerk_id failed!'));
            exit;
        }
        $customer_id=$data['customer_id'];
        if(!$customer_id){
            echo json_encode(array('status'=>'error','msg'=>'find customer_id failed!'));
            exit;
        }
        $table_id=$data['table_id'];
        if(!$table_id){
            echo json_encode(array('status'=>'error','msg'=>'find table_id failed!'));
            exit;
        }

        $re1=DB::table('shopinfo')->where('id','=',$shop_id)->get();
        if(!$re1){
            echo json_encode(array('status'=>'error','msg'=>'no exist shop !'));
            exit;
        }
        $brand_id=$re1[0]->brand_id;
        $shop_name=$re1[0]->shopname;
        $re2=DB::table('brand')->where('id','=',$brand_id)->get();
        $brand_name=$re2[0]->brandname;

        $identifer = $clerk_id.$table_id;
        $re3=DB::table($brand_name.'_app_order')->where('identifer','=',$identifer)->get();
        if(!$re3){
            echo json_encode(array('status'=>'error','msg'=>'order abnormal!!!!'));
            exit;
        }

        $api_key='sk_live_OuH040ivDyjD4Wf1044Ce9uD';
        // $api_key=$re1[0]->api_key;
        if(!$api_key){
            echo json_encode(array('status'=>'error','msg'=>'find api_key failed!'));
            exit;
        }
        $app_id='app_9m14S8HKqTeLOyzL';
        // $app_id=$re1[0]->app_id;
        if(!$app_id){
            echo json_encode(array('status'=>'error','msg'=>'find app_id failed!'));
            exit;
        }
        $channel=$data['channel'];
        if(!$channel){
            echo json_encode(array('status'=>'error','msg'=>'$channel cannot be null!'));
            exit;
        }
        $amount=$data['amount'];
        if(!$amount){
            echo json_encode(array('status'=>'error','msg'=>'$amount cannot be null!'));
            exit;
        }
        $subject=$shop_name;
        if(!$subject){
            echo json_encode(array('status'=>'error','msg'=>'$subject cannot be null!'));
            exit;
        }
        $body='body';
        //调用结算类
        $pay = new \DoPay;
        $res = $pay->createOrder($api_key,$app_id,$channel,$amount,$subject,$body);
        $res_data = json_decode($res,true);
        if($res_data){
        
            $result=DB::table($brand_name.'_order')
            ->insert(array('shop_id'=>$shop_id,'identifer'=>$identifer,'order_num'=>$res_data['order_no'],'total'=>$amount/100,'status'=>0,'customer_id'=>$customer_id,'created_at'=>time()));
            
            if($result){
                echo json_encode(array('status'=>'success','msg'=>'get two d code success!','order_no'=>$res_data['order_no'],'credential'=>$res_data['credential'][$channel]));
            }
            else{
                 echo json_encode(array('status'=>'error','msg'=>'get two d code success.but insert table failed!','order_no'=>$res_data['order_no'],'credential'=>$res_data['credential'][$channel]));
            }
        }
        else{
            echo json_encode(array('status'=>'error','msg'=>'get two d code failed!','order_no'=>$res_data['order_no'],'credential'=>$res_data['credential'][$channel]));
        }
    }

    //测试用
    public function postTestpay(Request $request){
        
        $json_data = $request->getContent();
        $data = json_decode($json_data,true);
        $channel=$data['channel'];

        $pay = new \DoPay;
        $res = $pay->createOrder('','',$channel,1,'subject','body');

        $res_data = json_decode($res,true);
        echo json_encode(array('status'=>'success','msg'=>'get two d code success!','order_no'=>$res_data['order_no'],'credential'=>$res_data['credential'][$channel]));
    }
    //测试用
    public function getTestpay(){
        $pay = new Pay;
        $res = $pay->pay2code('','','wx_pub_qr',1,'subject','body');
    }




///////////////////////////////////////////////////////////////////////////////////////////////////////////
//
//
//                             history order
//
//
//////////////////////////////////////////////////////////////////////////////////////////////////////////
    //查看某用户的历史订单
    public function postHistoryorder(Request $request){
        $json_data = $request->getContent();
        $data = json_decode($json_data,true);
        $customer_id=$data['customer_id'];
        $brand_id=$data['brand_id'];
        // $brand_id=$data['brand_id'];
        // $clerk_id=session('clerk_id');
        // $brand_id=session('brand_id');
        $re1=DB::table('brand')->where('id','=',$brand_id)->get();
        if(!$re1){
            echo json_encode(array('status'=>'error','msg'=>'can not find this brand_id'));
        }
        $brand_name=$re1[0]->brandname;

        $result=DB::table($brand_name.'_order')
        ->where('customer_id','=',$customer_id)
        ->where('status','=',1)->get();
        $order_data=array();
        $i=0;
        //联表查询
        foreach ($result as $key => $value) {
            $order_data[$i]['order_num']=$value->order_num;
            $re1=DB::table($brand_name.'_app_order')
            ->where('identifer','=',$value->identifer)
            ->where('customer_id','=',$customer_id)
            ->where('order_num','=',$value->order_num)->get();
            $j=0;
            $preorder=array();
            $count=count($re1);
            foreach ($re1 as $key => $value) {
               $preorder[$j]['commodity_id']=$value->commodity_id;
               $commodity_id=$value->commodity_id;
               $re2=DB::table($brand_name.'_commodity')->where('id','=',$value->commodity_id)->get();

               foreach ($re2 as $key => $value) {
                   $preorder[$j]['commodity_name']=$value->commodity_name;
                   $preorder[$j]['main_img']=$value->main_img;
                   $preorder[$j]['category_name']=$value->category_name;
                   $re3=DB::table($brand_name.'_commodity_img')->where('id','=',$commodity_id)->get();
                   $temp="";
                   foreach ($re3 as $key => $value1) {
                       $temp=$temp.$value1->img_src.",";
                   }
                   $preorder[$j]['img']=$temp;
                   $preorder[$j]['express_price']=$value->express_price;
                   $preorder[$j]['count']=$re1[$j]->count;

               }
               $j++;
            }
            $order_data[$i]['commodity']=$preorder;
            $order_data[$i]['time']=date('Y-m-d H:i:s',$result[$i]->created_at);
            $order_data[$i]['status']=$result[$i]->status;
            $order_data[$i]['total']=$result[$i]->total;
            
            $i++;

        }
        if($result){
             echo json_encode(array('status'=>'success','msg'=>'get historyorder  success','data'=>$order_data));
        }
        else{
            echo json_encode(array('status'=>'error','msg'=>'get historyorder  failed!'));
         }
    }
///////////////////////////////////////////////////////////////////////////////////////////////////////////
//
//
//                             if payed for order
//
//
//////////////////////////////////////////////////////////////////////////////////////////////////////////
        //app端 轮询访问接口，每段时间访问，是否结算成功，结算成功，app端停止轮询
        public function postIspay(Request $request){
        $json_data = $request->getContent();
        $data = json_decode($json_data,true);
        $brand_name=$data['brand_name'];
        $order_no=$data['order_no'];
        $re=DB::table($brand_name.'_order')->where('order_num','=',$order_no)->get();
        if($re){
            if($re[0]->status == 1){
                echo json_encode(array('status'=>'success','msg'=>'pay success'));
            }else{
                echo json_encode(array('status'=>'error','msg'=>'pay failed!'));
            }
        }else{
            echo json_encode(array('status'=>'error','msg'=>'cannot find this order_num'));
        }
        
    }


///////////////////////////////////////////////////////////////////////////////////////////////////////////
//
//
//                             hist order
//
//
//////////////////////////////////////////////////////////////////////////////////////////////////////////
}