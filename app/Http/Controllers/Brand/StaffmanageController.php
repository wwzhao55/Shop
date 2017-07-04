<?php namespace App\Http\Controllers\Brand;
//edit by xuxuxu
use Illuminate\Http\Request;
use App\Models\Brand\Brand,App\Models\Shop\Shopstaff,App\Models\Shop\Shopinfo,App\Models\User,App\Models\Order\Order,App\Models\Customer\Customer,App\Models\Shop\Shopadmin;
use Hash,Schema,View,Session,Redirect,DB,Response,Auth,Validator;

class StaffmanageController extends CommonController{
    #------------小店员工管理首页
    #--------hetutu update
    public function getIndex($id=null){
        $brand_id = Auth::user()->brand_id;
        $shoplists = Shopinfo::where('brand_id',$brand_id)->get();
        if($id == null){
            $shopstaff = Shopstaff::getShopstaff($brand_id);
            $shopadmin = Shopadmin::where('brand_id',$brand_id)->get();
            $shopstaff = $shopadmin->merge($shopstaff);
            $shopstaff_count = $shopstaff->count();   
        }else if($id == 0){
            $shopstaff = Shopadmin::where('brand_id',$brand_id)->get();
            $shopstaff_count = $shopstaff->count();
        }else{
            $shopstaff = Shopstaff::where('shop_id',$id)->get();
            $shopstaff_count = $shopstaff->count();
        }
        if($shopstaff_count > 0){
            foreach($shopstaff as $list){
                if(isset($list->brand_id)){
                    $list->shop_id = 0;
                    $list->shopname = $this->brandname.'总店';
                }else{
                    $list->shopname = Shopinfo::find($list->shop_id)->shopname;
                }
            }
        }

        return View::make('brand.staffmanage.index',array(
            'shopstaff_lists'=>$shopstaff->sortBy('shop_id'),
            'shopstaff_count'=>$shopstaff_count,
            'shoplists'=>$shoplists,
            ));
    }
    #---------修改小店员工信息
    public function postChangeinfo(Request $request){
        $rules = array(
            'shopstaff_id'=>'required',
            'name' =>'required|max:255',
            'phone'=>'required|regex:/^1[34578][0-9]{9}$/',
            'shop_id' =>'required',
            'old_shop' => 'required',
        );
        $message = array(
            "required"=> ":attribute 不能为空",
        );

        $attributes = array(
            'shopstaff_id'=>'员工id',
            'name' =>'姓名',
            'phone'=>'手机号码',
            'shop_id' =>'店铺id',
            'old_shop' => '店铺id',
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
        DB::beginTransaction();
        try {
            if($request->shop_id == 0){
                if($request->old_shop == 0){
                    //总店-》总店
                    $shopadmin = Shopadmin::find($request->shopstaff_id);
                    if(!$shopadmin){
                        return Response::json(array(
                        'status'=>'fail',
                        'message'=>'店员不存在',
                        ));
                    }
                    if($shopadmin->uid == Auth::user()->id){
                        return Response::json(array(
                        'status'=>'fail',
                        'message'=>'不能修改自己的账号',
                        ));
                    }
                    //电话做修改
                    if($shopadmin->phone!= $request->phone){
                        if(User::where('account',$request->phone)->where('role',1)->count()){
                            return Response::json(array(
                            'status'=>'fail',
                            'message'=>'电话号码已注册',
                            ));
                        }
                    }
                    $contacter = Brand::find(Auth::user()->brand_id);
                    if($contacter->contacter_phone == $shopadmin->phone){
                        //品牌负责人
                        $contacter->contacter_name = $request->name;
                        $contacter->contacter_phone = $request->phone;
                        $contacter->save();
                    }
                    $shopadmin->name = $request->name;
                    $shopadmin->phone = $request->phone;
                    $shopadmin->brand_id = Auth::user()->brand_id;
                    $shopadmin->status = 1;
                    $shopadmin->save();

                    $user = User::find($shopadmin->uid);
                    $user->account = $request->phone;
                    $user->save();

                }else{
                    //分店->总店员工
                    $shopstaff = Shopstaff::find($request->shopstaff_id);
                    if(!$shopstaff){
                        return Response::json(array(
                        'status'=>'fail',
                        'message'=>'店员不存在',
                        ));
                    }else{
                        $uid = $shopstaff->uid;
                    }
                    if($shopstaff->uid == Auth::user()->id){
                        return Response::json(array(
                        'status'=>'fail',
                        'message'=>'不能修改自己的账号',
                        ));
                    }
                    $contacter = Shopinfo::find($request->old_shop);
                    if($contacter->contacter_phone == $shopstaff->phone){
                        //店铺负责人
                        return Response::json(array(
                        'status'=>'fail',
                        'message'=>$shopstaff->name.'为分店负责人，不能编辑所属分店,请联系超级管理员修改负责人',
                        ));
                    }
                    //电话做修改
                    if($shopstaff->phone != $request->phone){
                        if(User::where('account',$request->phone)->where('role',1)->count()){
                            return Response::json(array(
                                'status'=>'fail',
                                'message'=>'电话号码已注册',
                                ));
                        }
                    }
                    $shopadmin = new Shopadmin;
                    $shopadmin->uid = $uid;
                    $shopadmin->name = $request->name;
                    $shopadmin->phone = $request->phone;
                    $shopadmin->brand_id = Auth::user()->brand_id;
                    $shopadmin->status = 1;
                    $shopadmin->save();
                    
                    $user = User::find($uid);
                    $user->account = $request->phone;
                    $user->role = 1;
                    $user->shop_id = 0;
                    $user->save();
                    $shopstaff->delete(); 
                }
            }else{
                if($request->old_shop == 0){
                    //总店->分店员工
                    $shopadmin = Shopadmin::find($request->shopstaff_id);
                    if(!$shopadmin){
                        return Response::json(array(
                        'status'=>'fail',
                        'message'=>'店员不存在',
                        ));
                    }else{
                        $uid = $shopadmin->uid;
                    }
                    if($shopadmin->uid == Auth::user()->id){
                        return Response::json(array(
                        'status'=>'fail',
                        'message'=>'不能修改自己的账号',
                        ));
                    }
                    //电话做修改
                    if($shopadmin->phone != $request->phone){
                        if(User::where('account',$request->phone)->where('role',3)->count()){
                            return Response::json(array(
                                'status'=>'fail',
                                'message'=>'电话号码已注册',
                                ));
                        }
                    }
                    $contacter = Brand::find(Auth::user()->brand_id);
                    if($contacter->contacter_phone == $shopadmin->phone){
                        //店铺负责人
                        return Response::json(array(
                        'status'=>'fail',
                        'message'=>$shopadmin->name.'为总店负责人，不能编辑所属分店,请联系超级管理员修改负责人',
                        ));
                    }
                    $shopstaff = new Shopstaff;
                    $shopstaff->uid = $uid;
                    $shopstaff->name = $request->name;
                    $shopstaff->phone = $request->phone;
                    $shopstaff->shop_id = $request->shop_id;
                    $shopstaff->status = 1;
                    $shopstaff->save();

                    $user = User::find($uid);
                    $user->account = $request->phone;
                    $user->role = 3;
                    $user->shop_id = $request->shop_id;
                    $user->save();
                    $shopadmin->delete();                          
                }else{
                    //分店-》分店
                    $shopstaff = Shopstaff::find($request->shopstaff_id);
                    if(!$shopstaff){
                        return Response::json(array(
                        'status'=>'fail',
                        'message'=>'店员不存在',
                        ));
                    }
                    if($shopstaff->uid == Auth::user()->id){
                        return Response::json(array(
                        'status'=>'fail',
                        'message'=>'不能修改自己的账号',
                        ));
                    }
                    //电话做修改
                    if($shopstaff->phone != $request->phone){
                        if(User::where('account',$request->phone)->where('role',3)->count()){
                            return Response::json(array(
                            'status'=>'fail',
                            'message'=>'电话号码已注册',
                            ));
                        }
                     }
                    $contacter = Shopinfo::find($request->old_shop); 
                    if($contacter->contacter_phone == $shopstaff->phone){
                        if($request->old_shop != $request->shop_id){
                            //分店负责人
                            return Response::json(array(
                            'status'=>'fail',
                            'message'=>$shopstaff->name.'为分店负责人，不能编辑所属分店,请联系超级管理员修改负责人',
                            )); 
                        }
                        $contacter->contacter_name = $request->name;
                        $contacter->contacter_phone = $request->phone;
                        $contacter->save();
                    }
 
                    $shopstaff->name = $request->name;
                    $shopstaff->phone = $request->phone;
                    $shopstaff->shop_id = $request->shop_id;
                    $shopstaff->status = 1;
                    $shopstaff->save();

                    $user = User::find($shopstaff->uid);
                    $user->account = $request->phone;
                    $user->shop_id = $request->shop_id;
                    $user->save();
                }
            }
            //提交事务
            DB::commit();
        } catch (Exception $e){
           DB::rollback();
           
           return Response::json(array(
                'status'=>'fail',
                'message'=>'修改失败',
                ));
        }
        
        return Response::json(array(
            'status'=>'success',
            'message'=>'修改成功',
            ));  
    }
    #--------添加小店员工
    public function postAdd(Request $request){
        $rules = array(
            'shop_id' =>'required',
            'name' =>'required|max:255',
            'phone'=>'required|regex:/^1[34578][0-9]{9}$/',
        );
        $message = array(
            "required"=> ":attribute 不能为空",
        );

        $attributes = array(
            'shop_id' =>'店铺id',
            'name' =>'姓名',
            'phone'=>'手机号码',
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
        if($request->shop_id == 0){
            if(User::where('account',$request->phone)->where('role',1)->count()>0){
                return Response::Json(['status'=>'fail','message'=>'添加失败，号码已存在']);
            }
        }else{
            if(!Shopinfo::find($request->shop_id)){
                return Response::Json(['status'=>'fail','message'=>'添加失败，店铺不存在']);
            }
            if(User::where('account',$request->phone)->where('role',3)->count()>0){
                return Response::Json(['status'=>'fail','message'=>'添加失败，号码已存在']);
            }
        }
       
        //开启事务
        DB::beginTransaction();
        try {

            $user = new User;
            $user->brand_id = Auth::user()->brand_id;
            $user->account = $request->phone;
            $user->password = Hash::make('123456');
            if($request->shop_id == 0){
                $user->shop_id = 0;
                $user->role = 1;
            }else{
                $user->shop_id = $request->shop_id;
                $user->role = 3;
            }
            $user->save();
            if($request->shop_id == 0){
                $shopadmin = new Shopadmin;
                $shopadmin->uid = $user->id;
                $shopadmin->brand_id = Auth::user()->brand_id;
                $shopadmin->name = $request->name;
                $shopadmin->phone = $request->phone;
                $shopadmin->status = 1;
                $shopadmin->save();
            }else{
                $shopstaff = new Shopstaff;
                $shopstaff->uid = $user->id;
                $shopstaff->name = $request->name;
                $shopstaff->phone = $request->phone;
                $shopstaff->shop_id = $request->shop_id;
                $shopstaff->status = 1;
                $shopstaff->save();
            }
            
            //提交事务
            DB::commit();
        } catch (Exception $e){
           DB::rollback();
           return Response::Json(['status'=>'fail','message'=>'添加失败，'.$e->getMessage()]);
        }
        
        return Response::Json(['status'=>'success','message'=>'添加成功']);
    }
    #-------update over


    #--------修改小店员工状态
    public function postChangestatus(Request $request){
        $rules = array(
            'shop_id'=>'required',
            'shopstaff_id'=>'required',
            'password'=>'required|min:6|max:16',
        );
        $message = array(
            "required"=> ":attribute 不能为空",
        );

        $attributes = array(
            'shop_id'=>'店铺id',
            'shopstaff_id'=>'员工id',
            'password'=>'密码',
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
        $shopstaff_id = $request->shopstaff_id;
        $account = Auth::user()->account;
        $password = $request->password;
        if ( !Auth::validate( array('account'=>$account,'password'=>$password) ) ){
            return Response::json(['status'=>'fail','message'=>'密码错误']);    
        }
        if($request->shop_id == 0){
            //总店
            $shopadmin = Shopadmin::find($shopstaff_id);
            if(!$shopadmin){
                return Response::json(array(
                    'status'=>'fail',
                    'message'=>'店员参数错误',
                    )); 
            }
           if($shopadmin->uid == Auth::user()->id){
                    return Response::json(array(
                    'status'=>'fail',
                    'message'=>'不能修改自己的账号',
                    ));
                }
            if($shopadmin->status){
                $shopadmin->status = 0;
            }else{
                $shopadmin->status = 1;
            }   
            $result = $shopadmin->save();
        }else{
            //分店
            $shopstaff = Shopstaff::find($shopstaff_id);
            if(!$shopstaff){
                return Response::json(array(
                    'status'=>'fail',
                    'message'=>'店员参数错误',
                    ));
            }
            if($shopstaff->uid == Auth::user()->id){
                        return Response::json(array(
                        'status'=>'fail',
                        'message'=>'不能修改自己的账号',
                        ));
                    }
            if( !($this->authCheck($shopstaff->shop_id)) ){
                return Response::json(array(
                    'status'=>'fail',
                    'message'=>'越权操作！！',
                    ));
            }
            if($shopstaff->status){
                $shopstaff->status = 0;
            }else{
                $shopstaff->status = 1;
            }   
            $result = $shopstaff->save();
        }  
        if(!$result){           
            return Response::json(array(
                'status'=>'fail',
                'message'=>'操作失败！！',
                ));
        }else{
            return Response::json(array(
                'status'=>'success',
                'message'=>'操作成功！！',
                ));
        }
    }
    #-------------删除小店员工
    public function postDelete(Request $request){
        $rules = array(
            'shop_id'=>'required',
            'shopstaff_id'=>'required',
            'password'=>'required|min:6|max:16',
        );
        $message = array(
            "required"=> ":attribute 不能为空",
        );

        $attributes = array(
            'shop_id'=>'店铺id',
            'shopstaff_id'=>'员工id',
            'password'=>'密码',
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
        $shopstaff_id = $request->shopstaff_id;
        $account = Auth::user()->account;
        $password = $request->password;
        if ( !Auth::validate( array('account'=>$account,'password'=>$password) ) ){
            return Response::json(['status'=>'fail','message'=>'密码错误']);    
        } 
        if($request->shop_id == 0){
            $shopadmin = Shopadmin::find($shopstaff_id);
            if(!$shopadmin){
                return Response::json(array(
                    'status'=>'fail',
                    'message'=>'店员参数错误',
                    ));
            }
            if($shopadmin->uid == Auth::user()->id){
                        return Response::json(array(
                        'status'=>'fail',
                        'message'=>'不能修改自己的账号',
                        ));
                    }
            $contacter = Shopinfo::find($request->shop_id);
            if($contacter->contacter_phone == $shopadmin->phone){
                //店铺负责人
                return Response::json(array(
                'status'=>'fail',
                'message'=>$shopadmin->name.'为分店负责人，不能删除,请联系超级管理员修改负责人',
                ));
            }
            $user = User::find($shopadmin->uid);
            $user->delete();
            $result = $shopadmin->delete();
        }else{
            $shopstaff = Shopstaff::find($shopstaff_id);
            if(!$shopstaff){
                return Response::json(array(
                    'status'=>'fail',
                    'message'=>'店员参数错误',
                    ));
            }
             if($shopstaff->uid == Auth::user()->id){
                        return Response::json(array(
                        'status'=>'fail',
                        'message'=>'不能修改自己的账号',
                        ));
                    }
            if( !($this->authCheck($shopstaff->shop_id)) ){
                return Response::json(array(
                    'status'=>'fail',
                    'message'=>'越权操作！！',
                    ));
            }
            $contacter = Shopinfo::find($request->shop_id);
            if($contacter->contacter_phone == $shopstaff->phone){
                //店铺负责人
                return Response::json(array(
                'status'=>'fail',
                'message'=>$shopstaff->name.'为分店负责人，不能删除,请联系超级管理员修改负责人',
                ));
            }
            $user = User::find($shopstaff->uid);
            $user->delete();
            $result = $shopstaff->delete();
        } 
        
        if(!$result){           
            return Response::json(array(
                'status'=>'fail',
                'message'=>'操作失败！！',
                ));
        }else{
            return Response::json(array(
                'status'=>'success',
                'message'=>'操作成功！！',
                ));
        }
    }
    #------操作权限检查
    private function authCheck($shop_id){
        if($shop_id == 0){

        }
        $brand_id = Shopinfo::find($shop_id)->brand_id;
        if(Auth::user()->brand_id == $brand_id){
            return true;
        }else{
            return false;
        }        
    }

}