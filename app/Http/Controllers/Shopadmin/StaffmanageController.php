<?php namespace App\Http\Controllers\Shopadmin;
//edit by xuxuxu
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Brand\Brand,App\Models\Shop\Shopaccount,App\Models\Shop\Shopinfo,App\Models\User,App\Models\Order\Order,App\Models\Customer\Customer,App\Models\Shop\Staff;
use View,Auth,Redirect,Session,Hash,DB;
class StaffmanageController extends Controller{
    public function __construct(){
        $this->middleware('shopadmin');
    }
    #--------员工管理（实体店员工 app端登录）
    public function getIndex(){
        $staff = new Staff;
        $brand_id = Auth::user()->brand_id;
        $brandname = Brand::find($brand_id)->brandname;
        $staff->setTable($brandname.'_staff');
        $staff_lists = $staff->where('id','>',0)->get();
        $staff_count = $staff_lists->count();
        return View::make('shopadmin.staffmanage.index',array(
            'staff_lists'=>$staff_lists,
            'staff_count'=>$staff_count,
            ));
    }
    #-------------添加员工---------
    #-----------同时需要对两个表写入
    #------------user表用于登录登录时需要检查角色
    public function postAdd(Request $request){
        $brand_id = Auth::user()->brand_id;
        $shop_id = Auth::user()->shop_id;
        $brandname = Brand::find($brand_id)->brandname;
        $this->validate($request, [
            'staff_phone'=>'required|unique:'.$brandname.'_staff',
            'staff_name'=>'required',
        ]);
        //开启事务
        DB::beginTransaction();
        try {
            $user = new User;
            $user->account = $request->staff_phone;
            $user->password = Hash::make('123456');
            $user->role = 5;
            $user->brand_id = $brand_id;
            $user->shop_id = $shop_id;
            $user->save();

            $staff = new Staff;
            $staff->setTable($brandname.'_staff');
            $staff->uid = $user->id;
            $staff->staff_name = $request->staff_name;
            $staff->staff_phone = $request->staff_phone;
            $staff->status = 1;
            $staff->shop_id = $shop_id;
            $staff->save();
            
            //提交事务
            DB::commit();
        } catch (Exception $e){
           DB::rollback();
           Session::flash('Message','添加失败，'.$e->getMessage());
           return Redirect::back();
        }       
        Session::flash('Message','添加成功');       
        return Redirect::back();
    }
    #---------------员工账号删除
    #-------------路由参数staff_id
    #-------员工uid对应user表中的id
    public function getDelete($id){
        $shop_id = Auth::user()->shop_id;
        $brand_id = Auth::user()->brand_id;
        $brandname = Brand::find($brand_id)->brandname;

        DB::beginTransaction();
        try {
            $staff = new Staff;
            $staff->setTable($brandname.'_staff');
            $list = $staff->where('id',$id)->first();
            if(User::find($list->uid)){
                User::find($list->uid)->delete();
            }
            if( $list->shop_id != $shop_id ){
                Session::flash('Message','越权操作');
                return Redirect::back();
            }
            $result = $list->setTable($brandname.'_staff')->delete();
            
            //提交事务
            DB::commit();
        } catch (Exception $e){
           DB::rollback();
           Session::flash('Message','删除失败，'.$e->getMessage());
           return Redirect::back();
        }       
        Session::flash('Message','删除成功');       
        return Redirect::back();
    }
    #------------员工状态修改----------
    #----------路由参数staff_id
    public function getChangestatus($id){
        $shop_id = Auth::user()->shop_id;
        $brand_id = Auth::user()->brand_id;
        $brandname = Brand::find($brand_id)->brandname;
        $staff = new Staff;
        $staff->setTable($brandname.'_staff');
        $list = $staff->where('id',$id)->first();
        if( $list->shop_id != $shop_id ){
            Session::flash('Message','越权操作');
            return Redirect::back();
        }
        if($list->status){
            $list->status = 0;
        }else{
            $list->status = 1;
        }
        $result = $list->setTable($brandname.'_staff')->save();
        if($result){
        	Session::flash('Message','修改成功');
        }else{
        	Session::flash('Message','修改失败');
        }
        return Redirect::back();

    }


}