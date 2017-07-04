<?php 
/*** @author hetutu
     @modify 2016.08.05
    **/

namespace App\Http\Controllers\Shopstaff;
use App\Http\Controllers\Shopstaff\CommonController;
use Illuminate\Http\Request;
use App\Models\Brand\Brand,App\Models\Shop\Shopaccount,App\Models\Shop\Shopinfo,App\Models\User,App\Models\Customer\Customer;
use App\Models\Commodity\Commodity,App\Models\Commodity\Skulist,App\Models\Commodity\Shopcommodity,App\Models\Commodity\Shopsku;
use View,Auth,Session,Response,DB,Validator;

class CommodityController extends CommonController{

    public function getIndex(){
		//所有分组
		$group = DB::table($this->brandname.'_group')->where(function($query){
            $query->where('shop_id',$this->shop_id)->orWhere('shop_id',0);
        })->select('id','name')->get();
		//商品列表（名称，主图，规格，价格，库存，销量，状态）分页10
		$skulist = DB::table($this->brandname.'_skulist')->join($this->brandname.'_commodity',$this->brandname.'_skulist.commodity_id','=',$this->brandname.'_commodity.id')->where(function($query){
            $query->where('shop_id',$this->shop_id)->orWhere('shop_id',0);
        })->where($this->brandname.'_commodity.status',1)->where($this->brandname.'_skulist.status','!=',9)->select($this->brandname.'_commodity.id as commodity_id','commodity_name',$this->brandname.'_skulist.id as sku_id','main_img','sku_info','group_id','group_name','commodity_sku','price')->paginate(10);
		foreach ($skulist as $key => $item) {
            $item->price = number_format($item->price,2);
            $item->commodity_sku = json_decode($item->commodity_sku,true);
			$shopsku = new Shopsku;
			$shopsku->setTable($this->brandname.'_shop_sku');
			$shopsku_list = $shopsku->where('shop_id',$this->shop_id)->where('sku_id',$item->sku_id)->where('status','!=',9)->first();
			$item->quantity = $shopsku_list->quantity;
			$item->status = $shopsku_list->status;
			$item->saled_count = $shopsku_list->saled_count;
		}
      //   var_dump(array(
    		// 'group' => $group,
    		// 'commodity_lists' => $skulist->toArray()['data']
    		// ));
    	return View::make('shopstaff.commodity.index',array(
    		'group' => $group,
    		'commodity_lists' => $skulist
    		));
    }

    //点击分类
    public function postSelect(Request $request){
        $validator = Validator::make($request->all(), [
            'group' => 'required',
            'status' => 'required',
        ]);
        if ($validator->fails()) {
            return Response::json(['status' => 'error','msg' => '无效参数']);
        }
    	$group = $request->group;
    	$status = $request->status;
    	$skulist = array();
    	if($group == 0){
    		//所有分组
    		$sql0 = DB::table($this->brandname.'_shop_sku')->join($this->brandname.'_commodity',$this->brandname.'_shop_sku.commodity_id','=',$this->brandname.'_commodity.id')
					->where($this->brandname.'_shop_sku.shop_id',$this->shop_id)
                    ->where($this->brandname.'_commodity.status',1);
    	}else{
    		$sql0 = DB::table($this->brandname.'_shop_sku')->join($this->brandname.'_commodity',$this->brandname.'_shop_sku.commodity_id','=',$this->brandname.'_commodity.id')
					->where($this->brandname.'_shop_sku.shop_id',$this->shop_id)->where('group_id',$group)->where($this->brandname.'_commodity.status',1);
    	}

    	if(in_array($status,[0,1,2])){
    		$sql1 = $sql0;
			if($request->has('content')){
				$content = $request->content;
				$content_arr = explode(' ',$content);
				$content_len = count($content_arr);
				for($i=0;$i<$content_len;$i++) {
					$sql1 = $sql1->where('commodity_name','like','%'.$content_arr[$i].'%');
				}
			}
				
    		if($status == 0){
    			//商品列表（名称，主图，规格，价格，库存，销量，所有）分页10
				$skulist_sql =$sql1->select($this->brandname.'_commodity.id as commodity_id','commodity_name',$this->brandname.'_shop_sku.sku_id','main_img','sku_info','group_id','group_name','quantity',$this->brandname.'_shop_sku.saled_count',$this->brandname.'_shop_sku.status');
				$skulist = $skulist_sql->paginate(10);
    		}else if($status == 1){
    			//商品列表（名称，主图，规格，价格，库存，销量，出售中）分页10
				$skulist_sql = $sql1->where($this->brandname.'_shop_sku.status',1)
					->select($this->brandname.'_commodity.id as commodity_id','commodity_name',$this->brandname.'_shop_sku.sku_id','main_img','sku_info','group_id','group_name','quantity',$this->brandname.'_shop_sku.saled_count',$this->brandname.'_shop_sku.status');
				$skulist = $skulist_sql->paginate(10);
    		}else{
    			//商品列表（名称，主图，规格，价格，库存，销量，售罄）分页10
				$skulist_sql = $sql1->whereIn($this->brandname.'_shop_sku.status',[0,3])
					->select($this->brandname.'_commodity.id as commodity_id','commodity_name',$this->brandname.'_shop_sku.sku_id','main_img','sku_info','group_id','group_name','quantity',$this->brandname.'_shop_sku.saled_count',$this->brandname.'_shop_sku.status');
				$skulist = $skulist_sql->paginate(10);
    		}
    		foreach ($skulist as $key => $value) {
    			$Commodity_sku = new Skulist;
    			$Commodity_sku->setTable($this->brandname.'_skulist');
    			$commodity_sku = $Commodity_sku->find($value->sku_id);
    			$value->commodity_sku = json_decode($commodity_sku->commodity_sku,true);
    			$value->price = number_format($commodity_sku->price,2);
    		}
    	}else{
    		return Response::json(['status' => 'error','msg' => '无效状态']);
    	}
        //var_dump($skulist->toArray());
    	return Response::json(View::make('shopstaff.commodity.content', ['commodity_lists'=>$skulist])->render());
    }

    //更新库存
    public function postUpdatequantity(Request $request){
        $rules = array(
            'commodity_id'=>'required',
            'sku_id'=>'required',
            'quantity'=>'required|integer'
        );
        $message = array(
            "required"=> ":attribute 不能为空",
        );

        $attributes = array(
            'commodity_id'=>'商品id',
            'sku_id'=>'规格信息',
            'quantity'=>'库存'
        );

        $validator = Validator::make(
            $request->all(), 
            $rules,
            $message,
            $attributes
        );
        if ($validator->fails()) {
            $warnings = $validator->messages();
            $show_warning = $warnings->first();
            return Response::Json(['status'=>'error','msg'=>$show_warning]);
        }
        //验证通过
        $commodity_id = $request->commodity_id;
    	$sku_id = $request->sku_id;
    	$quantity = $request->quantity;
    	$shopsku = new Shopsku;
    	$shopsku->setTable($this->brandname.'_shop_sku');
    	$target = $shopsku->where('shop_id',$this->shop_id)->where('sku_id',$sku_id)->first();
        if(!$target){
            return Response::json(['status'=>'error','msg'=>'规格参数有误']);
        }
        if($target->status == 9){
            return Response::json(['status'=>'error','msg'=>'Wrong sku_id.']);
        }
        DB::beginTransaction();
        try{
            //更新库存
                $target->quantity = $quantity;
                $target->status = 1;
                $target->setTable($this->brandname.'_shop_sku')->save();
                //改变shop_commodity status
                $shopcom = new Shopcommodity;
                $shopcom->setTable($this->brandname.'_shop_commodity');
                $shopcom_item = $shopcom->where('shop_id',$this->shop_id)->where('commodity_id',$commodity_id)->first();
                if($shopcom_item->status !=1){
                    $shopcom_item->status = 1;
                }
                $shopcom_item->setTable($this->brandname.'_shop_commodity')->save();    
                DB::commit();
        }catch (Exception $e){
               DB::rollback();
               return Response::json(array(
                'status' => 'error',
                'msg'=>$e->getMessage(),
                ));
            }
    	return Response::json(['status'=>'success','msg'=>'success']);

    }

    //上架 
    public function postShelfon(Request $request){
    	$commodity_id = $request->commodity_id;
    	$sku_id = $request->sku_id;
    	$shopsku = new Shopsku;
    	$shopsku->setTable($this->brandname.'_shop_sku');
    	$target = $shopsku->where('shop_id',$this->shop_id)->where('sku_id',$sku_id)->first();
    	if(!$target){
    		return Response::json(['status'=>'error','msg'=>'规格参数有误']);
    	}else if($target->quantity == 0) {
            return Response::json(['status'=>'error','msg'=>'请更新库存']);
        }else{
    		DB::beginTransaction();
	    	try{
	    		//if($target->status == 0){
	    			$target->status = 1;
	    		//}
	    		$target->setTable($this->brandname.'_shop_sku')->save();
	    		//改变shop_commodity status
	    		$shopcom = new Shopcommodity;
				$shopcom->setTable($this->brandname.'_shop_commodity');
				$shopcom_item = $shopcom->where('shop_id',$this->shop_id)->where('commodity_id',$commodity_id)->first();
	    		if($shopcom_item->status !=1){
	    			$shopcom_item->status = 1;
	    		}
	    		$shopcom_item->setTable($this->brandname.'_shop_commodity')->save();   	
		    	DB::commit();
	        }catch (Exception $e){
	           DB::rollback();
	           return Response::json(array(
	            'status' => 'error',
	            'msg'=>$e->getMessage(),
	            ));
	        }
    	}	
	    return Response::json(['status'=>'success','msg'=>'success']); 	
    }

    //下架，可批量下架
    public function postShelfoff(Request $request){
    	$array = $request->commoditys;
    	$array = json_decode($array);
    	foreach ($array as $key => $value) {
    		$shopsku = new Shopsku;
	    	$shopsku->setTable($this->brandname.'_shop_sku');
	    	$target = $shopsku->where('shop_id',$this->shop_id)->where('sku_id',$value->sku_id)->first();
	    	if(!$target){
	    		return Response::json(['status'=>'error','msg'=>'无效的规格'.$value->sku_id]);
	    	}else{
	    		DB::beginTransaction();
		    	try{
		    		if($target->status == 1){
		    			$target->status = 0;
		    		}
		    		$target->setTable($this->brandname.'_shop_sku')->save();
		    		//改变shop_commodity status
		    		$shopsku = new Shopsku;
		    		$shopsku->setTable($this->brandname.'_shop_sku');
		    		$on_target = $shopsku->where('shop_id',$this->shop_id)->where('commodity_id',$value->commodity_id)->where('status',1)->count();
		    		$shopcom = new Shopcommodity;
					$shopcom->setTable($this->brandname.'_shop_commodity');
					$shopcom_item = $shopcom->where('shop_id',$this->shop_id)->where('commodity_id',$value->commodity_id)->first();
		    		if($on_target ==0){
		    			$shopcom_item->status = 0;
		    		}
		    		$shopcom_item->setTable($this->brandname.'_shop_commodity')->save();   	
			    	DB::commit();
		        }catch (Exception $e){
		           DB::rollback();
		           return Response::json(array(
		            'status' => 'error',
		            'msg'=>$e->getMessage(),
		            ));
		        }
	    	}
    	}
	    return Response::json(['status'=>'success','msg'=>'success']); 	
    }

    //
    public function postPause(Request $request){
        $sku_id = $request->sku_id;
        $status = $request->status;
        $shopsku = new Shopsku;
        $shopsku->setTable($this->brandname.'_shop_sku');
        $target = $shopsku->where('shop_id',$this->shop_id)->where('sku_id',$sku_id)->first();
        if($target->status == 2){
            //取消暂停
            $target->status = $status;
            $target->setTable($this->brandname.'_shop_sku')->save();
        }else if($target->status != 9){
            //暂停
            $target->status = 2;
            $target->setTable($this->brandname.'_shop_sku')->save();
        }else{
            return Response::json(['status'=>'error','msg'=>'商品不存在']);
        }
        return Response::json(['status'=>'success']);
    }
}