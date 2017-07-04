<?php namespace App\Http\Controllers\Admin;
//edit by xuxuxu
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Database\Schema\Blueprint;
use App\Models\Brand\Brand,App\Models\Shop\Shopadmin,App\Models\Shop\Shopstaff,App\Models\Shop\Shopinfo,App\Models\User,App\Models\Order\Order,App\Models\Customer\Customer,App\Models\Commodity\Shopcommodity,App\Models\Commodity\Shopsku;
use Hash,Schema,View,Session,Redirect,DB,Response;

class ShopmanageController extends Controller{
    public function __construct(){
        $this->middleware('admin');
    }
    #-----------小店状态控制
    #----------路由参数$shop_id
    #----------返回一次性存储json
    public function postChangestatus($id){        
        $list = Shopinfo::find($id);               
        if($list->status){
            $list->status = 0;       
        }else{
            $list->status = 1;
        }
        $list->status_at = time();     
        $result = $list->save();
        if($result){
            return Response::json(array(
                'status'=>'success',
                'message'=>'修改成功',
                ));
        }else{
            return Response::json(array(
                'status'=>'fail',
                'message'=>'修改失败',
                ));
        }    
    }
    
    public function getDetail($id){
        $shopinfo = Shopinfo::find($id);
        /*
        $total = 0;
        $customer_count = 0;
        $order_count = 0;
        $commodity_count = 0;

        $customer = new Customer;
        $customer->setTable($brandname.'_customers');
        $customer_count = $customer->getShopCustomerCount($shop_id);

        $order = new Order;
        $submit_order = $order->setTable($brandname.'_order');
        $order_count = $submit_order->where('shop_id',$shop_id)->count();
        $total = $order->getShopTotal($shop_id);
        */
        return View::make('admin.shopmanage.detail',array(
            'shopinfo'=>$shopinfo,
            ));
    }
    #-----------添加店铺
    #-----------路由参数$brand_id
    public function getAdd($id){
        return View::make('admin.shopmanage.add',array(
            'brand_id'=>$id,
            ));
    }
    #----------添加店铺
    #----------路由参数$brand_id
    #---------前台表单方式提交
    #----------返回闪存信息 Message
    public function postAdd(Request $request,$id){
        $this->validate($request, [
            'shopname' => 'required|unique:shopinfo|max:255',
            /*
            'staff_email'=> 'required|email',
            'staff_phone'=> 'required|unique:users,account',
            'admin_email'=> 'required|email',
            'admin_phone'=> 'required|unique:users,account',
            */
            'contacter_name' => 'required',
            'contacter_phone' => 'required|unique:users,account|regex:/^1[34578][0-9]{9}$/',
            'password' => 'required|min:6|max:16',
            'contacter_email' => 'required|email',
            'contacter_QQ' => 'required|regex:/^[1-9][0-9]{4,10}$/',
            'customer_service_phone'=> array('required','regex:/(^1[34578][0-9]{9}$)|(^([0-9]{3,4}-)?[0-9]{7,8}$)/'),

            'shop_district' => 'required',
            'shop_province' => 'required',
            'shop_city' => 'required',
            'shop_address_detail' => 'required|max:255',
            'latitude'=>'required|numeric',
            'longitude'=>"required|numeric"
            /*
            'weixin_shop_num' => 'required',
            'weixin_api_key'=> 'required',
            'weixin_staff_account'=> 'required',
            'weixin_apiclient_cert'=> 'required',
            'weixin_apiclient_key'=> 'required',
            'zhifubao_pid'=> 'required',
            'zhifubao_appid'=> 'required',
            'zhifubao_public_key'=> 'required',
            'zhifubao_private_key'=> 'required',
            */
        ]);
        $data = $request->all();
        $data['brand_id'] = $id;
        $data['status'] = 1;
        $data['open_weishop'] = 0;
        $data['status_at'] = time();
        //开启事务
        DB::beginTransaction();
        try {
            $shop = new Shopinfo;
            $shop->fill($data)->save();
            /*
            $user1 = new User;
            $user1->account = $request->input('admin_phone');
            $user1->password = Hash::make('123456');
            $user1->role = 2;
            $user1->shop_id = $shop->id;
            $user1->brand_id = $id;
            */
            $user2 = new User;
            $user2->account = $request->input('contacter_phone');
            $user2->password = Hash::make($data['password']);
            $user2->role = 3;
            $user2->shop_id = $shop->id;
            $user2->brand_id = $id;

            //$user1->save();
            $user2->save();
            /*
            $shopadmin = new Shopadmin;
            $shopadmin->uid = $user1->id;
            $shopadmin->shop_id = $shop->id;
            $shopadmin->phone = $request->input('admin_phone');
            $shopadmin->email = $request->input('admin_email');
            $shopadmin->status = 1;
            $shopadmin->save();
            */
            $shopstaff = new Shopstaff;
            $shopstaff->uid = $user2->id;
            $shopstaff->shop_id = $shop->id;
            $shopstaff->name = $request->input('contacter_name');
            $shopstaff->phone = $request->input('contacter_phone');
            $shopstaff->email = $request->input('contacter_email');
            $shopstaff->status = 1;
            $shopstaff->save();

            //商品入店
            $brandname = Brand::find($id)->brandname;
            $commoditys = DB::table($brandname.'_commodity')->select('id','status')->get();
            foreach ($commoditys as $key => $commodity) {
                $shopcom = new Shopcommodity;
                $shopcom->setTable($brandname.'_shop_commodity');
                $shopcom_data['shop_id'] = $shop->id;
                $shopcom_data['commodity_id'] = $commodity->id;
                $shopcom_data['quantity'] = 0;
                $shopcom_data['saled_count'] = 0;
                if($commodity->status == 0){
                    $shopcom_data['status'] = 3;
                }else{
                    $shopcom_data['status'] = 0;
                }
                $shopcom->fill($shopcom_data)->save();
                $skulist = DB::table($brandname.'_skulist')->where('commodity_id',$commodity->id)->get();
                foreach ($skulist as $key => $sku) {
                    $shopsku = new Shopsku;
                    $shopsku->setTable($brandname.'_shop_sku');
                    $shopsku_data['commodity_id'] = $commodity->id; 
                    $shopsku_data['shop_id'] = $shop->id; 
                    $shopsku_data['sku_id'] = $sku->id; 
                    $shopsku_data['quantity'] = 0;
                    $shopsku_data['saled_count'] = 0;
                    if($commodity->status == 0){
                        $shopsku_data['status'] = 3;
                    }else{
                        $shopsku_data['status'] = 0;
                    }
                    $shopsku->fill($shopsku_data)->save();
                }
            }
            
            //提交事务
            DB::commit();
        } catch (Exception $e){
           DB::rollback();
           Session::flash('Message','添加失败，'.$e->getMessage());
           return Redirect::back();
        }
        //Session::flash('Message','添加成功！');
        return Redirect::action('Admin\BrandmanageController@getDetail',[$id]);       
    }
    #----------修改店铺信息
    #-----------表单方式提交信息
    public function postChangeinfo(Request $request){
        $this->validate($request, [
            'shop_id'=>'required',
            'shopname' => 'required',
            'contacter_name' => 'required',
            'contacter_phone' => 'required|regex:/^1[34578][0-9]{9}$/',
            'contacter_email' => 'required|email',
            'contacter_QQ' => 'required|regex:/^[1-9][0-9]{4,10}$/',
            'customer_service_phone'=> array('required','regex:/(^1[34578][0-9]{9}$)|(^([0-9]{3,4}-)?[0-9]{7,8}$)/'),
            'shop_district' => 'required',
            'shop_province' => 'required',
            'shop_city' => 'required',
            'shop_address_detail' => 'required|max:255',
            'latitude'=>'required|numeric',
            'longitude'=>"required|numeric"
        ]);
        if(User::where('account',$request->contacter_phone)->get()->count() > 1){
            Session::flash('Message','添加失败，账号冲突');
            return Redirect::back();
        }
        //开启事务
        DB::beginTransaction();
        try {
            $shopinfo = Shopinfo::find($request->shop_id);
            $shopstaff = Shopstaff::where('shop_id',$shopinfo->id)
                                  ->where('name',$shopinfo->contacter_name)
                                  ->where('phone',$shopinfo->contacter_phone)
                                  ->first();
            $user = User::find($shopstaff->uid);
            $user->account = $request->contacter_phone;
            if($request->password){
                $user->password = Hash::make($request->password);
            }            
            $user->save();

            $shopstaff->name = $request->contacter_name;
            $shopstaff->phone = $request->contacter_phone;
            $shopstaff->email = $request->contacter_email;
            $shopstaff->save();

            $shopinfo->shopname = $request->shopname;
            $shopinfo->contacter_name = $request->contacter_name;
            $shopinfo->contacter_phone = $request->contacter_phone;
            $shopinfo->contacter_QQ = $request->contacter_QQ;
            $shopinfo->contacter_email = $request->contacter_email;
            $shopinfo->customer_service_phone = $request->customer_service_phone;
            $shopinfo->shop_district = $request->shop_district;
            $shopinfo->shop_province = $request->shop_province;
            $shopinfo->shop_city = $request->shop_city;
            $shopinfo->shop_address_detail = $request->shop_address_detail;
            $shopinfo->latitude = $request->latitude;
            $shopinfo->longitude = $request->longitude;
            $shopinfo->save();

            //提交事务
            DB::commit();
        } catch (Exception $e){
           DB::rollback();
           Session::flash('Message','添加失败，'.$e->getMessage());
           return Redirect::back();
        }
        Session::flash('Message','添加成功！');
        return Redirect::back(); 
    }
    #-------------表格数据
    #----------参数 start_time---------
    #---------------end_time
    #---------------unit(day week month)
    #-----------brand_id
    #-----------shop_id
    public function postData(Request $request){
        $start = $request->input('start_time');
        $end = $request->input('end_time');
        $brand_id = $request->input('brand_id');
        $shop_id = $request->input('shop_id');
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

        $brand_array = array();
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
           
            $customer_count_base += $customer->getShopNewCustomerCount($i,$i+$seconds,$shop_id);
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