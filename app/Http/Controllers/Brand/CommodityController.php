<?php 
    #---update by hetutu
namespace App\Http\Controllers\Brand;
use Illuminate\Http\Request;
use App\Models\Commodity\Commodity,App\Models\Commodity\Skulist,App\Models\Commodity\Group,App\Models\Commodity\Tag,App\Models\Shop\Shopinfo,App\Models\Shop\Shopstaff,App\Models\Brand\Brand,App\Models\Express\Express,App\Models\Order\Order,App\Models\Order\Ordershopcart,App\Models\Commodity\Shopcart,App\Models\Commodity\Commodityimg,App\Models\Commodity\Shopcommodity,App\Models\Commodity\Shopsku,App\Models\Commodity\Skuname;
use App\Models\Admin\Category as Mainbusiness,App\Models\Commodity\CommodityParam;
use Auth,View,Response,DB,Session,Redirect,Validator;
/**
* 商品管理模块 edit by xuxuxu
*/
class CommodityController extends CommonController
{  
    //商品管理首页
    public function getIndex(){
        $commodity = new Commodity;
        $commodity->setTable($this->brandname.'_commodity');
        $commodity_lists = $commodity->where('status',1)->paginate(10);
        $commodity_count = $commodity_lists->count();
        
        foreach ($commodity_lists as $key => $commodity) {          

            $skulist = new Shopsku;
            $skulist->setTable($this->brandname.'_shop_sku');
            $commodity->quantity = $skulist->where('commodity_id',$commodity->id)->where('status','>=',0)->where('status','<=',2)->sum('quantity');
            // $order = new Order;
            // $order->setTable($this->brandname.'_order');
            // $shop_finished_orders = $order->where('status',4)->get();//订单完成计算销量
            // $commodity_trade_count = 0;

            // foreach ($shop_finished_orders as $key => $shop_finished_order) {
            //     $order_shopcart = new Ordershopcart;
            //     $order_shopcart->setTable($this->brandname.'_order_shopcart');
            //     $ordershopcarts = $order_shopcart->where('order_id',$shop_finished_order->id)->get();
            //     foreach ($ordershopcarts as $key => $ordershopcart) {
            //         $shopcart = new Shopcart;
            //         $shopcart->setTable($this->brandname.'_shopcart');
            //         $list = $shopcart->where('id',$ordershopcart->shopcart_id)->first();
            //         if($list->commodity_id == $commodity->id){
            //             $commodity_trade_count += $list->count;
            //         }
            //     }

            // }
        }   
        $group = new Group;
        $group_lists = $group->setTable($this->brandname.'_group')
                              ->where('status',1)
                              ->get();
                            
        return View::make('brand.commodity.index',array(
            'commodity_lists'=>$commodity_lists,
            'commodity_count'=>$commodity_count,
            'group_lists'=>$group_lists
            ));
    }    
    public function postGroups(){
        $brandname = $this->brandname;
        $group = new Group;
        $group_lists = $group->setTable($brandname.'_group')
                              ->where('status',1)
                              ->get();
        $group_count = $group_lists->count();
        return Response::json(array(
            'status'=>'success',
            'group_lists'=>$group_lists,
            'group_count'=>$group_count,
            ));
    }

    #-----hetutu update
    public function postParam(Request $request){
        $validator = Validator::make($request->all(), [
            'category' => 'required',
        ]);
        if ($validator->fails()) {
            return Response::json(['status' => 'error','msg' => 'category is required.']);
        }
        $category = $request->category;
        $skuname = array();
        $skuname = Skuname::where(function($query) use($category){
            $query->where('category_id',$category)->orWhere('category_id',0);
        })->select('id','skuname')->get();
        $param = CommodityParam::where('category_id',$category)->select('id','name')->get();
        return Response::json(['status' => 'success','msg' => ['skuname' => $skuname,'param'=>$param]]);
    }
    #-----update over


    #---------------添加商品页面
    #-----------发送的数据：
    #----------------group_lists 分类列表
    #---------------express_template_lists 运费模板列表
    public function getAdd(){
        $main_business = Mainbusiness::where('status',1)->get();
        /*
        $category = new Category;
        $category->setTable($this->brandname.'_category');
        $category_lists = $category->where('status',1)->get();
        
        $express_template = new Express;
        $express_template->setTable($this->brandname.'_express_template');
        $express_template_lists = $express_template->where('shop_id',$this->shop_id)->get();
        */
        return View::make('brand.commodity.add',array(
            'main_business_lists'=>$main_business,
            //'category_lists'=>$category_lists,
            ));
    }
    #-------------商品编辑
    public function postEdit(Request $request,$id){
        $skuinfo = $request->input('sku_info');
        $rules = array(
            'commodity_name' => 'required|max:255',
            'category_name' => 'required',
            'category_id'=>'required',
            'main_img'=>'required',
            'img'=>'required',
            //'produce_area1'=>'required',
            //'produce_area2'=>'required',
            'sku_info'=>'required',
            'is_recommend'=>'required',
            'brief_introduction'=>'required|max:255',
            'description'=>'required',
           // 'has_vip_discount'=>'required',
            'limit_count'=>'required',
            'group_name'=>'required',
            'group_id'=>'required',
            'img_changed'=>'required',
            'main_img_changed'=>'required',
            'order'=>'required',
            );
        $commodity_data = $request->only('commodity_name','category_name','category_id','sku_info','type','is_recommend','brief_introduction','description','group_id','group_name','limit_count');
        $commodity_data['description'] = json_encode($commodity_data['description']);
        if($request->main_img_changed){
            $commodity_data['main_img'] = $request->main_img;
        }

        if($skuinfo=='0'){
            $rules['price'] = 'required|numeric';
            $rules['old_price'] = 'required|numeric';
            $rules['skuname'] = 'required';
            $rules['skuvalue'] = 'required';
            //$rules['quantity'] = 'required';
            $commodity_data['base_price'] = $request->price;
        }else{
            $rules['sku_length'] = 'required';
            $sku_length = $request->sku_length;
            for($i=0;$i<$sku_length;$i++){
                $rules['skulist'.$i] = 'required';
                $rules['price'.$i] = 'required';               
            }
        }
        $message = array(
            "required"             => ":attribute 不能为空",
        );

        $attributes = array(
            'commodity_name' => '商品名称',
            'category_name' => '商品分类',
            'category_id'=>'商品分类',
            'main_img'=>'商品主图',
            'img'=>'商品展示图',
            'sku_info'=>'商品规格',
            'brief_introduction'=>'商品简介',
            'description'=>'商品描述',
            'limit_count'=>'商品限购',
            'group_name'=>'商品分组',
            'price' => '商品价格',
            'old_price'=>'商品原价',
            'skuname'=>'商品规格',
            'skuvalue'=>'商品规格',
            'skulist'=>'商品规格'
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
            return Response::Json(['status'=>'fail','message'=>$show_warning]);
        }

        $imgs = explode(',', $request->input('img'));
        if(!is_array($imgs)){
            return Response::json(array(
                'status'=>'fail',
                'message'=>'展示图数据格式错误',
                ));
        }
        if(count($imgs)!=count($request->order)){
            return Response::json(array(
                'status'=>'fail',
                'message'=>'展示图次序有误',
                ));
        }
        
        $sku_data = $request->only('price','old_price');
        if($skuinfo == 1){
            $base_price = $request->input('price0');
            for($i=0;$i<$sku_length;$i++){
                if($request->input('price'.$i)<$base_price){
                    $base_price = $request->input('price'.$i);
                }
            }
            $commodity_data['base_price'] = $base_price;
        }
        DB::beginTransaction();
        try {
            $_commodity = new Commodity;
            $_commodity->setTable($this->brandname.'_commodity');
            $commodity = $_commodity->where('id',$id)->first();
            $commodity_img = new Commodityimg;
            $commodity_img->setTable($this->brandname.'_commodity_img');
            $old_commodity_imgs = $commodity_img->where('commodity_id',$id)->get();
            $old_main_img = $commodity->main_img;
            $commodity->setTable($this->brandname.'_commodity')->fill($commodity_data)->save();
                        
            /*展示图片入库*/
            if($request->img_changed){
                foreach ($imgs as $k => $img) {
                    $commodity_img = new Commodityimg;
                    $commodity_img->setTable($this->brandname.'_commodity_img');
                    $commodity_img->commodity_id = $commodity->id;
                   // $commodity_img->shop_id = $this->shop_id;
                    $commodity_img->img_src = $img;
                    $commodity_img->status = 1;
                    $commodity_img->order = array_search($k+1, $request->order)+1;
                    $commodity_img->save();
                }

                foreach($old_commodity_imgs as $old_commodity_img){
                    $old_commodity_img->setTable($this->brandname.'_commodity_img');
                    $old_commodity_img->delete();
                }
            }
            

            $_skulist = new Skulist;
            $_skulist->setTable($this->brandname.'_skulist');
            
            if(!$skuinfo){
                $skulist = $_skulist->where('commodity_id',$id)->where('status',0)->first();
                $skulist->price = $request->price;
                $skulist->old_price = $request->old_price;
                $skuname = $request->skuname;
                $skuvalue = $request->skuvalue;
                $skulist->commodity_sku= '{"'.$skuname.'":"'.$skuvalue.'"}';
                $skulist->setTable($this->brandname.'_skulist')->save();
            }else{
                $old_skulists = $_skulist->where('commodity_id',$id)->get();
                
                foreach($old_skulists as $old_skulist){
                    $old_skulist->status=9;
                    $old_skulist->setTable($this->brandname.'_skulist')->save();
                }
                

                $OldShopsku = new Shopsku;
                $OldShopsku->setTable($this->brandname.'_shop_sku');
                $oldshopskus = $OldShopsku->where('commodity_id',$id)->get();
                
                foreach($oldshopskus as $oldshopsku){
                    $oldshopsku->status = 9;
                    $oldshopsku->setTable($this->brandname.'_shop_sku')->save();
                }
                

                $shoplist = Shopinfo::where('brand_id',Auth::user()->brand_id)->select('id')->get();
                for($i=0;$i<$sku_length;$i++){
                    $commodity_sku = $request->input('skulist'.$i);
                    $price = $request->input('price'.$i);
                    $skulist = new Skulist;
                    $skulist->setTable($this->brandname.'_skulist');
                    $skulist->commodity_id = $commodity->id;
                    $skulist->commodity_sku = $commodity_sku;
                    $skulist->price = $price;
                    $skulist->save();
                    foreach ($shoplist as $shop) {
                        $Shopsku = new Shopsku;
                        $Shopsku->setTable($this->brandname.'_shop_sku');
                        $Shopsku->commodity_id = $commodity->id;
                        $Shopsku->shop_id = $shop->id;
                        $Shopsku->sku_id = $skulist->id;
                        $Shopsku->save();
                    }
                }
            }
            
            //提交事务
            DB::commit();
        } catch (Exception $e){
           DB::rollback();
           return Response::json(array(
            'status' => 'fail',
            'message'=>$e->getMessage(),
            ));
        }
        $publicPath = public_path();
        if($request->main_img_changed){
            if(file_exists($publicPath.'/'.$old_main_img)){
                unlink($publicPath.'/'.$old_main_img);
            }
            
        }
        if($request->img_changed){
            foreach ($old_commodity_imgs as $old_commodity_img) {
                unlink($publicPath.'/'.$old_commodity_img->img_src);
            }
        }
        
        return Response::json(array(
            'status' => 'success',
            'message'=>'修改商品成功！',
            ));
    }

    #------------商品编辑页面---------
    private function handleSkus($strings){
        //$strings = '{"name":"shuaixu"}';
        $first_pos = 1;
        $second_pos = strpos($strings,'"',$first_pos+1);
        $third_pos = strpos($strings,'"',$second_pos+1);
        $fourth_pos = strpos($strings,'"',$third_pos+1);
        $skuname_length = $second_pos-$first_pos;
        $skuvalue_length = $fourth_pos-$third_pos;
        $skuname = substr($strings,$first_pos+1,$skuname_length-1);
        $skuvalue = substr($strings,$third_pos+1,$skuvalue_length-1);
        return array(
            'skuname'=>$skuname,
            'skuvalue'=>$skuvalue,
            );
    }
    public function getEdit($id){
        $main_business = Mainbusiness::all();

        $commodity = new Commodity;
        $commodity->setTable($this->brandname.'_commodity');
        $list = $commodity->where('id',$id)->first();
        if($list->description){
            $list->description = json_decode($list->description,true);
        }else{
            $list->description = array();
        }
        var_dump($list->description);
        if(!$list){
            Session::flash('Message','无效商品id');
            return Redirect::back();
        }
        $skulist = new Skulist;
        $skulist->setTable($this->brandname.'_skulist');
        $skus = $skulist->where('commodity_id',$id)->where('status',0)->get();
        foreach($skus as $sku){
            if($sku->commodity_sku){
                $sku_array = $this->handleSkus($sku->commodity_sku);
                $sku->skuname = $sku_array['skuname'];
                $sku->skuvalue = $sku_array['skuvalue'];
            }
        }

        $commodity_img = new Commodityimg;
        $commodity_img->setTable($this->brandname.'_commodity_img');
        $imgs = $commodity_img->where('commodity_id',$id)->get();
        $list->sku_length = $skus->count();
        $list->img = $imgs;
        $img_string="";
        foreach ($imgs as $key => $img) {
            if($key==0){
                $img_string = $img->img_src;
            }else{
                $img_string = $img_string.','.$img->img_src;
            }
        }
        $list->img_string = $img_string;
        $list->skulist = $skus;
       // var_dump($list->img_string);
        return View::make('brand.commodity.edit',array(
            'commodity'=>$list,
            'skus'=>$skus,
            'main_business_lists'=>$main_business,
            ));
    }

    #-----------商品添加页面
    public function postAdd(Request $request){
        $skuinfo = $request->input('sku_info');
        $rules = array(
            'commodity_name' => 'required|max:255',
            'category_name' => 'required',
            'category_id'=>'required',
            'group_name' => 'required',
            'group_id' => 'required',
            'main_img'=>'required',
            'img'=>'required',
           // 'produce_area1'=>'required',
           // 'produce_area2'=>'required',
            'sku_info'=>'required',
            'is_recommend'=>'required',
            'brief_introduction'=>'required',
            'description'=>'required',
           // 'has_vip_discount'=>'required',
            'limit_count'=>'required',
            'order'=>'required',
        );
        $commodity_data = $request->only('commodity_name','category_name','category_id','group_id','group_name','main_img','sku_info','type','is_recommend','brief_introduction','description','limit_count');
        $commodity_data['description'] = json_encode($commodity_data['description']);
        if($skuinfo=='0'){
            $rules['price'] = 'required|numeric';
            $rules['old_price'] = 'required|numeric';
            $rules['skuname'] = 'required';
            $rules['skuvalue'] = 'required';
            //$rules['quantity'] = 'required';
            $commodity_data['base_price'] = $request->price;
        }else{
            $rules['sku_length'] = 'required';
            $sku_length = $request->sku_length;
            $base_price = $request->price0;
            for($i=0;$i<$sku_length;$i++){
                if($base_price > $request->input('price'.$i)){
                    $base_price = $request->input('price'.$i);
                }
                $rules['skulist'.$i] = 'required';
                $rules['price'.$i] = 'required';
                //$rules['quantity'.$i] = 'required';
            }
            $commodity_data['base_price'] = $base_price;
        }

        $message = array(
            "required"             => ":attribute 不能为空",
        );

        $attributes = array(
            'commodity_name' => '商品名称',
            'category_name' => '商品分类',
            'category_id'=>'商品分类',
            'main_img'=>'商品主图',
            'img'=>'商品展示图',
            'sku_info'=>'商品规格',
            'brief_introduction'=>'商品简介',
            'description'=>'商品描述',
            'limit_count'=>'商品限购',
            'group_name'=>'商品分组',
            'price' => '商品价格',
            'old_price'=>'商品原价',
            'skuname'=>'商品规格',
            'skuvalue'=>'商品规格',
            'skulist'=>'商品规格'
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
            return Response::Json(['status'=>'fail','message'=>$show_warning]);
        }

        $imgs = explode(',', $request->input('img'));
        if(!is_array($imgs)){
            return Response::json(array(
                'status'=>'fail',
                'message'=>'展示图数据格式错误',
                ));
        }
        if(count($imgs)!=count($request->order)){
            return Response::json(array(
                'status'=>'fail',
                'message'=>'展示图次序有误',
                ));
        }
        
        $sku_data = $request->only('price','old_price');

        DB::beginTransaction();
        try {
            $commodity = new Commodity;
            $commodity->setTable($this->brandname.'_commodity');
            $commodity_data['status'] = 1;
            $commodity_data['shop_id'] = 0;
            $commodity->fill($commodity_data)->save();

            $brand_id = Brand::where('brandname',$this->brandname)->first()->id;
            $shops = Shopinfo::where('brand_id',$brand_id)->get();
            //存入shop_commodity
            foreach ($shops as $item) {
                $shopcom = new Shopcommodity;
                $shopcom->setTable($this->brandname.'_shop_commodity');
                $shopcom_data['shop_id'] = $item->id;
                $shopcom_data['commodity_id'] = $commodity->id;
                $shopcom_data['quantity'] = 0;
                $shopcom_data['saled_count'] = 0;
                $shopcom_data['status'] = 0;
                $shopcom->fill($shopcom_data)->save();
            }
            /*展示图片入库*/
            foreach ($imgs as $k=>$img) {
                $commodity_img = new Commodityimg;
                $commodity_img->setTable($this->brandname.'_commodity_img');
                $commodity_img->commodity_id = $commodity->id;
                $commodity_img->shop_id = 0;
                $commodity_img->img_src = $img;
                $commodity_img->status = 1;
                $commodity_img->order = array_search($k+1, $request->order)+1;
                $commodity_img->save();
            }

            if(!$skuinfo){
                $skulist = new Skulist;
                $skulist->setTable($this->brandname.'_skulist');
                $sku_data['commodity_id'] = $commodity->id;
                $skuname = $request->skuname;
                $skuvalue = $request->skuvalue;
                $sku_data['commodity_sku'] = '{"'.$skuname.'":"'.$skuvalue.'"}';
                $skulist->fill($sku_data)->save();
                foreach ($shops as $item){
                    $shopsku = new Shopsku;
                    $shopsku->setTable($this->brandname.'_shop_sku');
                    $shopsku_data['commodity_id'] = $commodity->id; 
                    $shopsku_data['shop_id'] = $item->id; 
                    $shopsku_data['sku_id'] = $skulist->id; 
                    $shopsku_data['quantity'] = 0;
                    $shopsku_data['saled_count'] = 0;
                    $shopsku_data['status'] = 0;
                    $shopsku->fill($shopsku_data)->save();
                }
            }else{
                for($i=0;$i<$sku_length;$i++){
                    $commodity_sku = $request->input('skulist'.$i);
                    $price = $request->input('price'.$i);
                    //$quantity = $request->input('quantity'.$i);
                    $skulist = new Skulist;
                    $skulist->setTable($this->brandname.'_skulist');
                    $skulist->commodity_id = $commodity->id;
                    $skulist->commodity_sku = $commodity_sku;
                    $skulist->price = $price;
                    //$skulist->quantity = $quantity;
                    $skulist->save();
                    foreach ($shops as $item){
                        $shopsku = new Shopsku;
                        $shopsku->setTable($this->brandname.'_shop_sku');
                        $shopsku_data['commodity_id'] = $commodity->id; 
                        $shopsku_data['shop_id'] = $item->id; 
                        $shopsku_data['sku_id'] = $skulist->id; 
                        $shopsku_data['quantity'] = 0;
                        $shopsku_data['saled_count'] = 0;
                        $shopsku_data['status'] = 0;
                        $shopsku->fill($shopsku_data)->save();
                    }
                }
            }
            
            //提交事务
            DB::commit();
        } catch (Exception $e){
           DB::rollback();
           return Response::json(array(
            'status' => 'fail',
            'message'=>$e->getMessage(),
            ));
        }
        return Response::json(array(
            'status' => 'success',
            'message'=>'发布商品成功！',
            ));
    }
    #-----------更改商品状态
    public function getChangestatus($id){
        $commodity = new Commodity;
        $commodity->setTable($this->brandname.'_commodity');
        $list = $commodity->where('id',$id)->first();
        if(!$list){
            Session::flash('Message','无效商品id');
            return Redirect::back();
        }
        //改变品牌&分店&分店sku 商品状态
        $shopcom = new Shopcommodity;
        $shopcom->setTable($this->brandname.'_shop_commodity');
        $shopsku = new Shopsku;
        $shopsku->setTable($this->brandname.'_shop_sku');
        $shopcart = new Shopcart;
        $shopcart->setTable($this->brandname.'_shopcart');

        if($list->status){
            $list->status = 0;
            $shopcom_list = $shopcom->where('commodity_id',$id)->where('status',1)->get();
            $shopsku_list = $shopsku->where('commodity_id',$id)->where('status',1)->get();
            $shopcart_list = $shopcart->where('commodity_id',$id)->where('status',1)->get();
            foreach ($shopcom_list as $item) {
                $item->status = 3;
                $item->save();
            }
            foreach ($shopsku_list as $item) {
                $item->status = 3;
                $item->save();
            }
            foreach ($shopcart_list as $item) {
                $item->status = 0;
                $item->save();
            }
        }else{
            $list->status=1;
            $shopcom_list = $shopcom->where('commodity_id',$id)->where('status',3)->get();
            foreach ($shopcom_list as $item) {
                $item->status = 1;
                $item->save();
            }
            foreach ($shopsku_list as $item) {
                $item->status = 3;
                $item->save();
            }
        }
        $result = $list->setTable($this->brandname.'_commodity')->save();
        if($result){
            Session::flash('Message','操作成功');
        }else{
            Session::flash('Message','操作失败');
        }
        return Redirect::back();    
    }
    #----------删除商品
    public function getDelete($id){
        $commodity = new Commodity;
        $commodity->setTable($this->brandname.'_commodity');
        $list = $commodity->where('id',$id)->first();
        if(!$list){
            Session::flash('Message','无效商品id');
            return Redirect::back();
        }
        $has_quantity = DB::table($this->brandname.'_shop_sku')->where('commodity_id',$id)->where('status','!=',9)->sum('quantity');
        if($has_quantity>0){
            Session::flash('Message','该商品库存不为0，无法删除');
            return Redirect::back();
        }
        $skulist = new Skulist;
        $skulist->setTable($this->brandname.'_skulist');
        $skus = $skulist->where('commodity_id',$id)->get();

        $commodity_img = new Commodityimg;
        $commodity_img->setTable($this->brandname.'_commodity_img');
        $imgs = $commodity_img->where('commodity_id',$id)->get();

        $shopcom = new Shopcommodity;
        $shopcom->setTable($this->brandname.'_shop_commodity');
        $shopcommodity = $shopcom->where('commodity_id',$id)->get();

        $shopsku = new Shopsku;
        $shopsku->setTable($this->brandname.'_shop_sku');
        $shopskulist = $shopsku->where('commodity_id',$id)->get();

        $main_img_src = $list->main_img;
        $commodity_imgs = array();

        DB::beginTransaction();
        try {
            $list->status = 9;
            $list->setTable($this->brandname.'_commodity')->save();
           /* foreach ($skus as $key => $sku) {
                $sku->status = 9;
                $sku->setTable($this->brandname.'_skulist')->save();
            }*/
            foreach ($imgs as $key => $img) {
                $img->status = 9;
                $img->setTable($this->brandname.'_commodity_img')->save();
                array_push($commodity_imgs,$img->img_src);
            }
            foreach ($shopcommodity as $key => $com) {
                $com->status = 9;
                $com->setTable($this->brandname.'_shop_commodity')->save();
            }
            foreach ($shopskulist as $key => $shopsku) {
                $shopsku->status = 9;
                $shopsku->setTable($this->brandname.'_shop_sku')->save();
            }
            /*$list->setTable($this->brandname.'_commodity')->delete();
            foreach ($skus as $key => $sku) {
                $sku->setTable($this->brandname.'_skulist')->delete();
            }
            foreach ($imgs as $key => $img) {
                $img->setTable($this->brandname.'_commodity_img')->delete();
                array_push($commodity_imgs,$img->img_src);
            }*/
            //提交事务
            DB::commit();
        } catch (Exception $e){
           DB::rollback();
           Session::flash('Message','删除失败');
           return Redirect::back();
        }
        // $publicPath = public_path();
        // unlink($publicPath.'/'.$main_img_src);
        // foreach ($commodity_imgs as $img_src) {
        //     unlink($publicPath.'/'.$img_src);
        // }
        Session::flash('Message','删除成功');
        return Redirect::back();
    }
    #----------商品图片上传
    public function postUploadimg(Request $request){
        $publicPath = public_path();
         $rules = array(
            'type'=>'required',
        );
        $message = array(
            "required"=> ":attribute 不能为空",
        );

        $attributes = array(
            "type" => '操作类型',
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
            return Response::Json(['status'=>'fail','message'=>$show_warning]);
        }
        switch ($request->type) {
            case 'add':
                # code...
                break;
            case 'edit':
                # code...
                break;
            default:
                return Response::json(['status'=>'fail','message'=>'操作类型出错']);
                break;
        }
        $brand_id = Auth::user()->brand_id;
        if ($request->hasFile('images')) {
            $file = $request->file('images');
            $path = $publicPath.'/uploads/'.$brand_id.'/commodity';
            $destinationPath = 'uploads/'.$brand_id.'/commodity';
        }elseif($request->hasFile('main')){
            $file = $request->file('main');
            $path = $publicPath.'/uploads/'.$brand_id.'/commodity/main';
            $destinationPath = 'uploads/'.$brand_id.'/commodity/main';
        }else{
            return Response::json(['status'=>'fail','message'=>'上传数据出错']);
        }
        

        $result = $this->createdir($path);
        if(!$result){
            return Response::json(['status'=>'fail','message'=>'建立图片存储目录出错']);
        }
        $filename = md5( date('ymdhis') );
        $extension = $file->getClientOriginalExtension();
        $result = $file->move($destinationPath,$filename.'.'.$extension);
        if($result){
            return Response::json(array(
            'status'=>'success',
            'message'=>'图片上传成功',
            'path'=>$destinationPath.'/'.$filename.'.'.$extension,
            ));   
        }else{
            return Response::json(array(
            'status'=>'fail',
            'message'=>'图片上传失败',
           // 'path'=>$destinationPath.'/'.$filename.'.'.$extension,
            )); 
        }
            
    }

    #---------hetutu update
    public function postSearch(Request $request){
        $validator = Validator::make($request->all(), [
            'group' => 'required',
        ]);
        if ($validator->fails()) {
            return Response::json(['status' => 'error','msg' => '分组不能为空.']);
        }
        $group_id = $request->group;

        $brandname = Brand::find(Auth::user()->brand_id)->brandname;
        $commodity = new Commodity;
        $commodity->setTable($brandname.'_commodity');
        if($group_id == 0){
            if($request->keyword!=''){
                $key_arr = explode(" ",$request->keyword);
                $_commodity = $commodity->where('status',1);
                foreach ($key_arr as $word) {
                    $_commodity = $_commodity->where('commodity_name','like','%'.$word.'%');
                }
                $_commodity = $_commodity->paginate(10);
            }else{
                $_commodity = $commodity->where('status',1)->paginate(10);
            }
        }else{
            if($request->keyword!=''){
                $key_arr = explode(" ",$request->keyword);
                $_commodity = $commodity->where('status',1)->where('group_id',$group_id);
                foreach ($key_arr as $word) {
                    $_commodity = $_commodity->where('commodity_name','like','%'.$word.'%');
                }
                $_commodity = $_commodity->paginate(10);
             }else{
                 $_commodity = $commodity->where('status',1)->where('group_id',$group_id)->paginate(10);
             }
           
        }
        //修改
        foreach ($_commodity as $key => $item) {
           $shopsku = new Shopsku;
           $shopsku->setTable($brandname.'_shop_sku');
           $item->quantity = $shopsku->where('commodity_id',$item->id)->whereIn('status',[0,1,2])->sum('quantity');
        }
        $commodity_count = $_commodity->count();

        $group = new Group;
        $group_lists = $group->setTable($this->brandname.'_group')
                              ->where('status',1)
                              ->get();

        if($_commodity){
            return Response::json(View::make('brand.commodity.content', ['commodity_lists'=>$_commodity,
                 'group_lists'=>$group_lists])->render());
        }else{
           return Response::json(View::make('brand.commodity.content', ['commodity_lists'=>[],
                 'group_lists'=>$group_lists])->render());
        }
    }
    #---------update over


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

    #----------商品改分组
    public function postChangegroup(Request $request){
        $rules = array(
            'commodity' => 'required',
            'group' => 'required',
        );
        $message = array(
            "required"  => ":attribute 不能为空",
        );
         $attributes = array(
            'commodity' => '商品',
            'group' => '新分组',
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
            return Response::json(['status' => 'fail','message' => $show_warning]);
        }
        $commodity_arr = $request->commodity;
        $group_id = $request->group;
        $group = new Group;
        $group->setTable($this->brandname.'_group');
        $newgroup = $group->find($group_id);
        if($newgroup->status){
            DB::beginTransaction();
            try{
                foreach ($commodity_arr as $key => $item) {
                    $Commodity = new Commodity;
                    $Commodity->setTable($this->brandname.'_commodity');
                    $commodity = $Commodity->find($item);
                    if(!$commodity){
                        return Response::json(['status' => 'error','msg' => 'Invalid id.']);
                    }
                    $commodity->group_id = $group_id;
                    $commodity->group_name = $newgroup->name;
                    $commodity->setTable($this->brandname.'_commodity')->save();
                }
                DB::commit();
            } catch (Exception $e){
                   DB::rollback();
                   return Response::json(array(
                    'status' => 'error',
                    'message'=>$e->getMessage(),
                    ));
            }
            return Response::json(['status'=>'success','msg'=>'Modify success.']);
        }else{
            return Response::json(['status'=>'error','msg'=>'Invalid group.']);
        }
    }

    #----------hetutu update
    #----------删除多个商品
    public function postDeletemore(Request $request){
        $validator = Validator::make($request->all(), [
            'commodity' => 'required',
        ]);
        if ($validator->fails()) {
            return Response::json(['status' => 'error','msg' => '商品id不能为空']);
        }
        $commodity_arr = $request->commodity;
         DB::beginTransaction();
            try {
                foreach ($commodity_arr as $id) {
                    $commodity = new Commodity;
                    $commodity->setTable($this->brandname.'_commodity');
                    $list = $commodity->where('id',$id)->first();
                    if(!$list){
                        return Response::json(['status' => 'error','msg' => 'Invalid commodity.']);
                    }
                    $has_quantity = DB::table($this->brandname.'_shop_sku')->where('commodity_id',$id)->where('status','!=',9)->sum('quantity');
                    if($has_quantity>0){ 
                        return Response::json(['status' => 'error','msg' => '删除失败，您所选择的部分商品库存不为0']);
                    }
                    $skulist = new Skulist;
                    $skulist->setTable($this->brandname.'_skulist');
                    $skus = $skulist->where('commodity_id',$id)->get();

                    $commodity_img = new Commodityimg;
                    $commodity_img->setTable($this->brandname.'_commodity_img');
                    $imgs = $commodity_img->where('commodity_id',$id)->get();

                    $shopcom = new Shopcommodity;
                    $shopcom->setTable($this->brandname.'_shop_commodity');
                    $shopcommodity = $shopcom->where('commodity_id',$id)->get();

                    $shopsku = new Shopsku;
                    $shopsku->setTable($this->brandname.'_shop_sku');
                    $shopskulist = $shopsku->where('commodity_id',$id)->get();

                    $main_img_src = $list->main_img;
                    $commodity_imgs = array();

                    $list->status = 9;
                    $list->setTable($this->brandname.'_commodity')->save();
                    foreach ($imgs as $key => $img) {
                        $img->status = 9;
                        $img->setTable($this->brandname.'_commodity_img')->save();
                        array_push($commodity_imgs,$img->img_src);
                    }
                    foreach ($shopcommodity as $key => $com) {
                        $com->status = 9;
                        $com->setTable($this->brandname.'_shop_commodity')->save();
                    }
                    foreach ($shopskulist as $key => $shopsku) {
                        $shopsku->status = 9;
                        $shopsku->setTable($this->brandname.'_shop_sku')->save();
                    }
                    // $publicPath = public_path();
                    // unlink($publicPath.'/'.$main_img_src);
                    // foreach ($commodity_imgs as $img_src) {
                    //     unlink($publicPath.'/'.$img_src);
                    // }
                }
                //提交事务
                DB::commit();
            } catch (Exception $e){
               DB::rollback();
               return Response::json(['status' => 'error','msg' => 'Wrong,please try again later.']);
            }
        return Response::json(['status' => 'error','msg' => '删除成功']);

    }

    #---------数据库清理
    public function postCleardata(){
        
    }
}