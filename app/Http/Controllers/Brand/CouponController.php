<?php namespace App\Http\Controllers\Brand;
//edit by xuxuxu
use Illuminate\Http\Request;
use App\Models\Brand\Brand,App\Models\Shop\Shopstaff,App\Models\Shop\Shopinfo,App\Models\User,App\Models\Order\Order,App\Models\Coupon\Coupon,App\Models\Commodity\Category;
use App\Models\Admin\Category as Mainbusiness;
use Hash,Schema,View,Session,Redirect,DB,Response,Auth;

class CouponController extends CommonController{
    #------------优惠劵首页
    #-------$id     2 全部
    #------------0已结束（发放）
    #------------1进行中
    #--------hetutu update
    public function getIndex(){
        $brandname = Brand::find(Auth::user()->brand_id)->brandname;
        $coupon = new Coupon;
        $coupon_listing = $coupon->setTable($brandname."_coupon")->where('status',1)->where('validity_end','>=',time())->paginate(10,['*'],'page1');
        $coupon_listed = $coupon->setTable($brandname."_coupon")->where(function($query){
            $query->where('status',0)->orWhere('validity_end','<',time());
        })->where('status','!=',9)->paginate(10,['*'],'page2');
        foreach ($coupon_listing as $key => $c) {
            $c->validity_start = date("Y-m-d H:i:s",$c->validity_start);
            $c->validity_end = date("Y-m-d H:i:s",$c->validity_end);
        }
        foreach ($coupon_listed as $key => $c) {
            $c->validity_start = date("Y-m-d H:i:s",$c->validity_start);
            $c->validity_end = date("Y-m-d H:i:s",$c->validity_end);
        }
        
        return view('brand.coupon.index',array(
            'brand_id' => Auth::user()->brand_id,
            'coupon_listing'=>$coupon_listing,
            'coupon_listed'=>$coupon_listed,
            ));
    }

    public function getCouponing(){
        $brandname = Brand::find(Auth::user()->brand_id)->brandname;
        $coupon = new Coupon;
        $coupon_listing = $coupon->setTable($brandname."_coupon")->where('status',1)->where('validity_end','>=',time())->paginate(10,['*'],'page1');
        foreach ($coupon_listing as $key => $c) {
            $c->validity_start = date("Y-m-d H:i:s",$c->validity_start);
            $c->validity_end = date("Y-m-d H:i:s",$c->validity_end);
        }
        return Response::json(View::make('brand.coupon.couponing', ['coupon_listing'=>$coupon_listing,'brand_id' => Auth::user()->brand_id,])->render());
    }

    public function getCouponed(){
        $brandname = Brand::find(Auth::user()->brand_id)->brandname;
        $coupon = new Coupon;
        $coupon_listed = $coupon->setTable($brandname."_coupon")->where(function($query){
            $query->where('status',0)->orWhere('validity_end','<',time());
        })->where('status','!=',9)->paginate(10,['*'],'page2');
        foreach ($coupon_listed as $key => $c) {
            $c->validity_start = date("Y-m-d H:i:s",$c->validity_start);
            $c->validity_end = date("Y-m-d H:i:s",$c->validity_end);
        }
        return Response::json(View::make('brand.coupon.couponed', ['coupon_listed'=>$coupon_listed,'brand_id' => Auth::user()->brand_id,])->render());
    }
    #---------update over

    public function getAdd(){ 
        $brandname = Brand::find(Auth::user()->brand_id)->brandname;      
        $shop_lists = Shopinfo::where('brand_id',Auth::user()->brand_id)->get();
        $category = new Mainbusiness;
        $category_lists = $category->all();
        return view('brand.coupon.add',array(
            'shop_lists'=>$shop_lists,
            'category_lists'=>$category_lists,
            ));
    }
    public function postAdd(Request $request){       
        $rule = array(
            'name'=>'required',
            'sum'=>'required',
            'number'=>'required',
            'shop_id'=>'required',
            'commodity_category'=>'required',
            'description'=>'required',
            'use_condition'=>'required',
            'validity_start'=>'required',
            'validity_end'=>'required',
            'gettimes'=>'required',
            'allow_share'=>'required',
            );
        if($request->allow_share){
            $rule['share_introduce'] = 'required';
        }
        $this->validate($request,$rule);
        $data = $request->all();
        $data['status'] = 1;
        $data['used_num'] = 0;       
        $brandname = Brand::find(Auth::user()->brand_id)->brandname;
        $coupon = new Coupon;
        $coupon->setTable($brandname.'_coupon');
        $result = $coupon->fill($data)->save();
        if($result){
            
            Session::flash('Message','添加成功');
        }else{
            
            Session::flash('Message','添加失败');
        }
        return Redirect::back();
    }

    #------hetutu update
    public function getSearchon(Request $request){
        $keyword = $request->keyword;
        $brandname = Brand::find(Auth::user()->brand_id)->brandname;
        $coupon = new Coupon;
        $coupon->setTable($brandname.'_coupon');
        if($keyword == ""){
            $_coupon = $coupon->where('status',1)->where('validity_end','>=',time())->paginate(10,['*'],'page1');
        }else{
            $key_arr = explode(" ",$keyword);
            $_coupon = $coupon->where('status',1)->where('validity_end','>=',time());
            foreach ($key_arr as $word) {
               $_coupon = $_coupon->where('name','like','%'.$word.'%');
            }
            $_coupon = $_coupon->paginate(10,['*'],'page1');
        }
        
        foreach ($_coupon as $key => $c) {
            $c->validity_start = date("Y-m-d H:i:s",$c->validity_start);
            $c->validity_end = date("Y-m-d H:i:s",$c->validity_end);
        }
        return Response::json(View::make('brand.coupon.couponing', ['coupon_listing'=>$_coupon,'brand_id' => Auth::user()->brand_id,])->render());
    }

     public function getSearchover(Request $request){
        $keyword = $request->keyword;
        $brandname = Brand::find(Auth::user()->brand_id)->brandname;
        $coupon = new Coupon;
        $coupon->setTable($brandname.'_coupon');
        if($keyword == ""){
            $_coupon = $coupon->where(function($query){
                $query->where('status',0)->orWhere('validity_end','<',time());
            })->where('status','!=',9)->paginate(10,['*'],'page2');
        }else{
             $key_arr = explode(" ",$keyword);
             $_coupon =$coupon->where(function($query){
                $query->where('status',0)->orWhere('validity_end','<',time());
            })->where('status','!=',9);
             foreach ($key_arr as $word) {
               $_coupon = $_coupon->where('name','like','%'.$keyword.'%');
            }
            $_coupon = $_coupon->paginate(10,['*'],'page2');
        }
        
        foreach ($_coupon as $key => $c) {
            $c->validity_start = date("Y-m-d H:i:s",$c->validity_start);
            $c->validity_end = date("Y-m-d H:i:s",$c->validity_end);
        }
        return Response::json(View::make('brand.coupon.couponed', ['coupon_listed'=>$_coupon,'brand_id' => Auth::user()->brand_id,])->render());
    }
    #-------update over

    
    public function getEdit($id){
        $brandname = Brand::find(Auth::user()->brand_id)->brandname;

        $shop_lists = Shopinfo::where('brand_id',Auth::user()->brand_id)->select('id','shopname')->get();
        $category = new Mainbusiness;
        $category_lists = $category->select('id','name')->get();
        
        $coupon = new Coupon;
        $coupon->setTable($brandname."_coupon");
        $_coupon = $coupon->where('id',$id)->first();
        $_coupon->validity_start = date("Y-m-d H:i:s",$_coupon->validity_start);
        $_coupon->validity_end = date("Y-m-d H:i:s",$_coupon->validity_end);
        /*var_dump(array(
            'coupon'=>$_coupon->toArray(),
            'shop_lists'=>$shop_lists->toArray(),
            'category_lists'=>$category_lists->toArray(),
            ));*/
        return view('brand.coupon.edit',array(
            'coupon'=>$_coupon,
            'shop_lists'=>$shop_lists,
            'category_lists'=>$category_lists,
            ));
    }
    public function postEdit(Request $request){
        $rule = array(
            'coupon_id'=>'required',
            'name'=>'required',
            'sum'=>'required',
            'number'=>'required',
            'shop_id'=>'required',
            'commodity_category'=>'required',
            'description'=>'required',
            'use_condition'=>'required',
            'validity_start'=>'required',
            'validity_end'=>'required',
            'gettimes'=>'required',
            'allow_share'=>'required',
            );
        if($request->allow_share){
            //$rule['share_introduce'] = 'required';
        }
        $this->validate($request,$rule);

        $brandname = Brand::find(Auth::user()->brand_id)->brandname;
        $coupon = new Coupon;
        $coupon->setTable($brandname."_coupon");
        $_coupon = $coupon->where('id',$request->coupon_id)->first();
        if(!$_coupon){
            Session::flash('Message','添加失败');
            return Redirect::back();
        }
        $_coupon->name = $request->name;
        $_coupon->sum = $request->sum;
        $_coupon->number = $request->number;
        $_coupon->shop_id = $request->shop_id;
        $_coupon->commodity_category = $request->commodity_category;
        $_coupon->description = $request->description;
        $_coupon->use_condition = $request->use_condition;
        $_coupon->validity_start = $request->validity_start;
        $_coupon->validity_end = $request->validity_end;
        $_coupon->gettimes = $request->gettimes;
        $_coupon->allow_share = $request->allow_share;
        $_coupon->status = 1;
        if($request->allow_share){
            $_coupon->share_introduce = $request->share_introduce;
        }
        $result = $_coupon->setTable($brandname."_coupon")->save();

        if($result){
            Session::flash('Message','修改成功');
        }else{
            Session::flash('Message','修改失败');
        }
        return Redirect::back();
    }

    public function getChangestatus($id){
        $brandname = Brand::find(Auth::user()->brand_id)->brandname;
        $coupon = new Coupon;
        $coupon->setTable($brandname.'_coupon');
        $_coupon = $coupon->where('id',$id)->where('status','!=',9)->first();
        if(!$_coupon){
            return Response::json(['status' => 'error','msg' => '参数错误']);
        }
        $_coupon->status = 0;
        $result = $_coupon->setTable($brandname.'_coupon')->save();
        if($result){
           
             return Response::json(['status' => 'success','msg' => '状态修改成功']);
        }else{
  
             return Response::json(['status' => 'error','msg' => '状态修改失败']);
        }
    }

    public function getDelete($id){
        $brandname = Brand::find(Auth::user()->brand_id)->brandname;
        $coupon = new Coupon;
        $coupon->setTable($brandname.'_coupon');
        $_coupon = $coupon->where('id',$id)->where('status','!=',9)->first();
        if(!$_coupon){
            return Response::json(['status' => 'error','msg' => '参数错误']);
        }
        $_coupon->status = 9;
        $result = $_coupon->setTable($brandname.'_coupon')->save();
        if($result){
           
             return Response::json(['status' => 'success','msg' => '删除成功']);
        }else{
  
             return Response::json(['status' => 'error','msg' => '删除失败']);
        }
    }
}