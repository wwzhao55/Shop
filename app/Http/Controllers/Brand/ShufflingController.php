<?php namespace App\Http\Controllers\Brand;
//edit by xuxuxu
use Illuminate\Http\Request;
use App\Models\Shop\Shopstaff,App\Models\Shop\Shopinfo,App\Models\Brand\Brand,App\Models\Shop\Shuffling,App\Models\Commodity\Shopcommodity,App\Models\Commodity\Commodity;
use Auth,Response,View,Session,Redirect,DB,Validator;
/**
* 
*/
class ShufflingController extends CommonController
{
    public function __construct(){
        $this->brand_id = Session::get('brand_id');
        parent::__construct();
        $shop_arr = Shopinfo::where('brand_id',$this->brand_id)->where('open_weishop',1)->lists('id');//微店id
        $commodity = new Commodity;
        $commodity->setTable($this->brandname.'_commodity');
        $shop_commodity = new Shopcommodity;
        $shop_commodity->setTable($this->brandname.'_shop_commodity');
        $commoditys = $commodity->where('status',1)->get();
        $commodity_all_shop = array();
        foreach($commoditys as $_commodity){
            $count = $shop_commodity->where('commodity_id',$_commodity->id)
                           ->where('status',1)->whereIn('shop_id',$shop_arr)
                           ->count();
            if($count == count($shop_arr)){
                $_commodity->is_all_shop = 1;
                $_commodity->setTable($this->brandname.'_commodity')->save();
            }
        }
    }
    #------------轮播图排序维护，添加删除后调用
    private function order_protect(){
        $shufflings_asc = Shuffling::where('brand_id',$this->brand_id)->orderBy('order','ASC')->get();
        $count = $shufflings_asc->count();
        $mix_order = 1;
        foreach($shufflings_asc as $shuffling){
            $shuffling->order = $mix_order;
            $shuffling->save();
            $mix_order++;
        }
    }
    
    #------------轮播图首页-----------
    public function getIndex($shop_id=0){
        $shuffling = new Shuffling;
        if($shop_id==0){            
            $image_lists = $shuffling->where('brand_id',$this->brand_id)->orderBy('order','ASC')->paginate(10);           
        }else{
            $image_lists = $shuffling->where('brand_id',$this->brand_id)->orderBy('order','ASC')->where('shop_id',$shop_id)->paginate(10);
        }
        foreach($image_lists as $image){
            if($image->shop_id > 0){
                $image->shopname = Shopinfo::find($image->shop_id)->shopname;
            }else{
                $image->shopname = '全部';
            }          
        }
        $image_count = $image_lists->count(); 
        $shop_lists = Shopinfo::where('brand_id',Auth::user()->brand_id)->where('open_weishop',1)->get();
        return view('brand.shuffling.index',array(
            'shuffling_lists' => $image_lists,
            'shuffling_count' => $image_count,
            'shop_lists' => $shop_lists,
            'shop_id'=>$shop_id,
            ));
    }
    #----------------排序--------------
    public function postOrder(Request $request){
        $rules = array(
            'id'=>'required',
            'method'=>'required',
        );
        $message = array(
            "required"=> ":attribute 不能为空",
        );

        $attributes = array(
            "id" => 'id',
            'method' =>'method'
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
        $id = $request->id;
        $method = $request->method;
        DB::beginTransaction();
        try {
            switch ($method) {
                case 'up':
                    $shuffling = Shuffling::find($id);
                    if($shuffling->order==1){
                        //已经是最顶端了
                        return Response::json(array(
                        'status'=>'fail',
                        'message'=>'已经是最顶端了',
                        ));
                    }else{
                        $pre_shuffling = Shuffling::where('brand_id',$this->brand_id)->where('order',$shuffling->order-1)->first();
                        $pre_shuffling->order = $pre_shuffling->order+1;
                        $pre_shuffling->save();
                        $shuffling->order = $shuffling->order-1;
                        $shuffling->save();
                    }
                break;
                case 'down':
                    $shuffling = Shuffling::find($id);
                    if($shuffling->order==Shuffling::where('brand_id',$this->brand_id)->count()){
                        //已经是最顶端了
                        return Response::json(array(
                        'status'=>'fail',
                        'message'=>'已经是最低端了',
                        ));
                    }else{
                        $next_shuffling = Shuffling::where('brand_id',$this->brand_id)->where('order',$shuffling->order+1)->first();
                        $next_shuffling->order = $next_shuffling->order-1;
                        $next_shuffling->save();
                        $shuffling->order = $shuffling->order+1;
                        $shuffling->save();
                    }
                    break;
                default:
                    return Response::json(array(
                    'status'=>'fail',
                    'message'=>'参数错误',
                    ));
            }
            DB::commit();
        } catch (Exception $e){
           DB::rollback();
           return Response::json(array(
            'status'=>'fail',
            'message'=>'排序失败',
            ));
        }  
        return Response::json(array(
            'status'=>'success',
            'message'=>'排序成功',
            ));      
    }
    #-----------店铺商品搜索-----------
    public function postSearch(Request $request){
        $rules = array(
            'shop_id'=>'required',
            'keyword'=>'required',
        );
        $message = array(
            "required"=> ":attribute 不能为空",
        );

        $attributes = array(
            'shop_id'=>'店铺id',
            'keyword'=>'关键词',
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
        //验证通过
        $id = $request->shop_id;
        $keyword = $request->keyword;
        if($id==0){
            $commodity = new Commodity;
            $commodity->setTable($this->brandname.'_commodity');
            $commoditys = $commodity->where('is_all_shop',1)
                                    ->where('status',1)
                                    ->where('commodity_name','like','%'.$keyword.'%')
                                    ->get();
            if($commoditys){
                return Response::json(View::make('brand.shuffling.commodity', array(
                    'status'=>'success',
                    'commoditys'=>$commoditys,
                    ))->render());
            }else{
                return Response::json(View::make('brand.shuffling.commodity', array(
                    'status'=>'success',
                    'commoditys'=>[],
                    ))->render());
            }
        }else{
            $shop_commodity = new Shopcommodity;
            $shop_commodity->setTable($this->brandname.'_shop_commodity');
            $shop_commodity_onsale = $shop_commodity->where('shop_id',$id)
                                                    ->where('status',1)
                                                    ->get();
            if(!$shop_commodity_onsale){
                return Response::json(View::make('brand.shuffling.commodity', array(
                    'status'=>'success',
                    'commoditys'=>[],
                    ))->render());
            }
            $commodity = new Commodity;
            $commodity->setTable($this->brandname.'_commodity');
            $commoditys = array();
            foreach($shop_commodity_onsale as $commodity_onsale){
                $commodity_info = $commodity->where('id',$commodity_onsale->commodity_id)->first();
                if(strstr($commodity_info->name,$keyword)){
                    array_push($commoditys,$commodity_info);
                }            
            }
            return Response::json(View::make('brand.shuffling.commodity', array(
                    'status'=>'success',
                    'commoditys'=>$commoditys,
                    ))->render());
        
        }
        
    }
    #-----------店铺商品获取-----------
    public function postCommodity($id){
        if($id==0){
            $shop_arr = Shopinfo::where('brand_id',$this->brand_id)->where('open_weishop',1)->lists('id');//微店id
            $commodity = new Commodity;
            $commodity->setTable($this->brandname.'_commodity');
            $shop_commodity = new Shopcommodity;
            $shop_commodity->setTable($this->brandname.'_shop_commodity');
            $commoditys = $commodity->where('status',1)->get();
            $commodity_all_shop = array();
            foreach($commoditys as $_commodity){
                $count = $shop_commodity->where('commodity_id',$_commodity->id)
                               ->where('status',1)->whereIn('shop_id',$shop_arr)
                               ->count();
                if($count == count($shop_arr)){
                    array_push($commodity_all_shop, $_commodity);
                }
            }
            if(count($commodity_all_shop)){
                return Response::json(View::make('brand.shuffling.commodity', array(
                    'status'=>'success',
                    'commoditys'=>$commodity_all_shop,
                    ))->render());
            }else{
                return Response::json(View::make('brand.shuffling.commodity', array(
                    'status'=>'success',
                    'commoditys'=>[],
                    ))->render());
            }
        }else{
            $shop_commodity = new Shopcommodity;
            $shop_commodity->setTable($this->brandname.'_shop_commodity');
            $shop_commodity_onsale = $shop_commodity->where('shop_id',$id)
                                                    ->where('status',1)
                                                    ->get();
            if(!$shop_commodity_onsale){
                return Response::json(View::make('brand.shuffling.commodity', array(
                    'status'=>'success',
                    'commoditys'=>[],
                    ))->render());
                }
            $commodity = new Commodity;
            $commodity->setTable($this->brandname.'_commodity');
            $commoditys = array();
            foreach($shop_commodity_onsale as $commodity_onsale){
                $commodity_info = $commodity->where('id',$commodity_onsale->commodity_id)->first();
                array_push($commoditys,$commodity_info);
            }
        }        
        return Response::json(View::make('brand.shuffling.commodity', array(
                    'status'=>'success',
                    'commoditys'=>$commoditys,
                    ))->render());
    }
    #-----------添加轮播图页面
    public function getAdd(){
        $shop_lists = Shopinfo::where('brand_id',Auth::user()->brand_id)->where('open_weishop',1)->get();
        return view('brand.shuffling.add',array(
            'shop_lists' => $shop_lists,
            'commoditys'=>[],
            ));
    }
    #-------------添加轮播图
    #-----------表单方式提交
    #--------提交的字段：
    #--------------img_src
    #-------------http_src
    #------------------name
    public function postAdd(Request $request){
        $this->validate($request,array(
            'img_src'=>'required',
            'name'=>'required',
            'http_src'=>'required',
            'shop_id'=>'required',
            ));
        if (!$request->hasFile('img_src')) {
            Session::flash('Message','上传数据出错');
            return Redirect::back();
        }
        $file = $request->file('img_src');
        if(!$file->isValid()){
            Session::flash('Message',$file->getErrorMessage());
            return Redirect::back();
        }

        $brand_id = Auth::user()->brand_id;
        $shop_id = $request->shop_id;

        $publicPath = public_path();
        $result = $this->createdir($publicPath.'/uploads/'.$brand_id.'/shuffling',0777);
        if(!$result){
            Session::flash('Message','建立图片存储目录出错');
            return Redirect::back();
        }
        //目标存储路径
        $destinationPath = 'uploads/'.$brand_id.'/shuffling';
        $filename = md5( date('ymdhis') );
        $extension = $file->getClientOriginalExtension();
        $file->move($destinationPath,$filename.'.'.$extension);
        
        $shuffling = new Shuffling;
        $order = Shuffling::where('brand_id',$this->brand_id)->max('order');
        $shuffling->http_src = $request->input('http_src');
        $shuffling->name = $request->input('name');
        $shuffling->img_src = 'uploads/'.$brand_id.'/shuffling/'.$filename.'.'.$extension;
        $shuffling->shop_id = $shop_id;
        $shuffling->brand_id = $brand_id;
        $shuffling->status = 1;
        $shuffling->order = $order+1;
        $result = $shuffling->save();
        if(!$result){
            Session::flash('Message','图片地址入库失败');
            return Redirect::back();
        }
        $this->order_protect();
       // Session::flash('Message','添加成功');
        return Redirect::to('/Brand/shuffling/index/0');  
    }
    public function getEdit($id){
        $shop_lists = Shopinfo::where('brand_id',Auth::user()->brand_id)->where('open_weishop',1)->get();
        $shuffling = Shuffling::find($id);
        if($shuffling->shop_id){
            $shuffling->shopname = Shopinfo::find($shuffling->shop_id)->shopname;
        }else{
            $shuffling->shopname = '全部';
        }        
        return view('brand.shuffling.edit',array(
            'shop_lists' => $shop_lists,
            'shuffling'=>$shuffling,
            'commoditys'=>[],
            ));
    }
    public function postEdit(Request $request,$id){
        $this->validate($request,array(
            'name'=>'required',
            'http_src'=>'required',
            'shop_id'=>'required',
            ));
        $shuffling = Shuffling::find($id);
        if(!$shuffling){
            Session::flash('Message','参数错误');
            return Redirect::back();
        }
        
        $shop_id = $request->shop_id;

        if($request->img_src){//图片修改过
            if (!$request->hasFile('img_src')) {
                Session::flash('Message','上传数据出错');
                return Redirect::back();
            }
            $file = $request->file('img_src');
            if(!$file->isValid()){
                Session::flash('Message',$file->getErrorMessage());
                return Redirect::back();
            }
            $brand_id = Auth::user()->brand_id;
            $old_img = $shuffling->img_src;
            $publicPath = public_path();
            //目标存储路径
            $destinationPath = 'uploads/'.$brand_id.'/shuffling';
            $filename = md5( date('ymdhis') );
            $extension = $file->getClientOriginalExtension();
            $file->move($destinationPath,$filename.'.'.$extension);
            
            $shuffling->http_src = $request->input('http_src');
            $shuffling->name = $request->input('name');
            $shuffling->img_src = 'uploads/'.$brand_id.'/shuffling/'.$filename.'.'.$extension;
            $shuffling->shop_id = $shop_id;
            $result = $shuffling->save();
            if(!$result){
                Session::flash('Message','图片地址入库失败');
                return Redirect::back();
            }
            $this->order_protect();
            if(file_exists($publicPath.'/'.$old_img)){
                unlink($publicPath.'/'.$old_img);
            }  
           // Session::flash('Message','修改成功');
            return Redirect::to('/Brand/shuffling/index/0');      
        }else{//图片未更新
            $shuffling->http_src = $request->input('http_src');
            $shuffling->name = $request->input('name');
            $shuffling->shop_id = $shop_id;
            $result = $shuffling->save();
            if(!$result){
                Session::flash('Message','图片地址入库失败');
                return Redirect::back();
            }
            $this->order_protect(); 
           // Session::flash('Message','修改成功');
            return Redirect::to('/Brand/shuffling/index/0'); 
        }
    }

    #-------------状态操作
    #------------路由参数shuffling_id
    public function getChangestatus($id){
        $shuffling = Shuffling::find($id);
        
        if($shuffling->status){
            $shuffling->status = 0;
        }else{
            $shuffling->status = 1;
        }
        $result = $shuffling->save();
        if(!$result){
            Session::flash('Message','修改失败');
            return Redirect::back();
        }
        Session::flash('Message','修改成功');
        return Redirect::back();
    }
    #-------------轮播图删除
    #-----------路由参数shuffling_id
    public function getDelete($id){
        $shuffling = Shuffling::find($id);
        $brand_id = Auth::user()->brand_id;
        
        $shuffling_path = $shuffling->img_src;
        $result = $shuffling->delete();
        if(!$result){
            Session::flash('Message','删除失败');
            return Redirect::back();
        }
        $publicPath = public_path();
        if(file_exists($publicPath.'/'.$shuffling_path)){
            unlink($publicPath.'/'.$shuffling_path);
        }                
        $this->order_protect();
        Session::flash('Message','删除成功');
        return Redirect::back();
    }
    public function postDeletemulti(Request $request){
        $validator = Validator::make($request->all(), [
            'array'=>'required',
        ]);
        if ($validator->fails()) {
            return Response::json(['status' => 'error','msg' => '参数不能为空']);
        }
        $delete_array = $request->array; 
        if(!is_array($delete_array)){
            return Response::json(array(
                'status'=>'fail',
                'message'=>'参数错误',
                ));
        }
        DB::beginTransaction();
        try {
            for($i=0;$i<count($delete_array);$i++){
                $shuffling = Shuffling::find($delete_array[$i]);               
                $shuffling_path = $shuffling->img_src;
                $shuffling->delete();
                $publicPath = public_path();
                if(file_exists($publicPath.'/'.$shuffling_path)){
                    unlink($publicPath.'/'.$shuffling_path);
                } 
            }
            DB::commit();
        } catch (Exception $e){
           DB::rollback();
           return Response::json(array(
            'status'=>'fail',
            'message'=>'删除失败',
            ));
        }  
        $this->order_protect();
        return Response::json(array(
            'status'=>'success',
            'message'=>'删除成功',
            ));      
    }

    private function createdir($path,$mode){
        if (is_dir($path)){  //判断目录存在否，存在不创建
            return true;
        }else{ //不存在创建
            $re=mkdir($path,$mode,true); //第三个参数为true即可以创建多极目录
            if ($re){
                return true;
            }else{
                return false;
            }
        }
    }
}