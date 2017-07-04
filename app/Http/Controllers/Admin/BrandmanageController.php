<?php namespace App\Http\Controllers\Admin;
//edit by xuxuxu
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Database\Schema\Blueprint;
use App\Models\Brand\Brand,App\Models\Shop\Shopadmin,App\Models\Shop\Shopstaff,App\Models\Shop\Shopinfo,App\Models\User,App\Models\Order\Order,App\Models\Customer\Customer,App\Models\Weixin\Account;
use App\Models\Admin\Category as Mainbusiness;
use Hash,Schema,View,Session,Redirect,DB,Response,Storage;

class BrandmanageController extends Controller{
    public function __construct(){
        //超级管理员登录可进行下一步操作
        $this->middleware('admin');
    }
    #------------超级管理员品牌管理首页
    public function getIndex(){
        $brand_lists = Brand::all();
        $brand_count = Brand::all()->count();
        return View::make('admin.brandmanage.index',array(
            'brand_count' => $brand_count,
            'brand_lists'=>$brand_lists,
            ));
    }

    public function postChangename(Request $request){
        //字段验证，可添加规则
        $this->validate($request, [
            'brand_id'=>'required',
            'brandname'=>'required|unique:brand|max:200',
            ]);
        $brand = Brand::find($request->brand_id);
        $old_brand_name = $brand->brandname;
        $new_brand_name = $request->brandname;
        $brand_table_names = [
            'app_customers',
            'app_order',
            'group',
            'commodity',
            'commodity_customer',
            'commodity_img',
            'coupon',
            'coupon_list',
            'customers',
            'express_province',
            'express_template',
            'order',
            'order_shopcart',
            'receiver_address',
            'search',
            'search_list',
            'skulist',
            'shopcart',
            'skuname',
            'skuvalue',
            'staff',
            'statistic',
            'shop_commodity',
            'shop_sku',
            'order_refund'
        ];
        DB::beginTransaction();
        try {            
            $brand->brandname = $new_brand_name;
            $brand->save();
            foreach ($brand_table_names as $name) {
                if(Schema::hasTable($old_brand_name.'_'.$name)){
                    Schema::rename($old_brand_name.'_'.$name, $new_brand_name.'_'.$name);
                }
                 
            } 
            DB::commit();
        } catch (Exception $e){
            DB::rollback();
            Session::flash('Message','修改失败，'.$e->getMessage());
            return Redirect::back();
        }
        Session::flash('Message','修改成功！');
        return Redirect::back();
    }

    public function postChangeinfo(Request $request){
        //字段验证，可添加规则
        $this->validate($request, [
            'brand_id'=>'required',
            'main_business' => 'required|max:255',
            'password' => 'min:6|max:16',
            'contacter_name' => 'required|max:60', 
            'contacter_phone' => 'required|regex:/^1[34578][0-9]{9}$/',
            'contacter_email' => 'required|email',
            'contacter_QQ' => 'required|regex:/^[1-9][0-9]{4,10}$/',

            'company_name' => 'required|max:100',
            'company_district' => 'required',
            'company_province' => 'required',
            'company_city' => 'required',
            'company_address_detail' => 'required|max:255',
            ]);
        if(User::where('account',$request->contacter_phone)->get()->count() > 1){
            Session::flash('Message','联系人账号出错');
            return Redirect::back();
        }
        DB::beginTransaction();
        try {
            $data = $request->except('brand_id','password');
            $brand = Brand::find($request->brand_id);
            #----------更新登录账户和密码
            $user = User::find($brand->uid);
            $user->account = $data['contacter_phone'];
            if($request->password){                
                $user->password = Hash::make($request->password);                
            }
            $user->save();
            #--------更新shopadmin
            $shopadmin = Shopadmin::where('uid',$brand->uid)->first();
            $shopadmin->name = $data['contacter_name'];
            $shopadmin->phone = $data['contacter_phone'];
            $shopadmin->email = $data['contacter_email'];
            $shopadmin->save();
            #---------更新品牌信息
            $brand->main_business = $data['main_business'];

            $brand->contacter_name = $data['contacter_name'];
            $brand->contacter_phone = $data['contacter_phone'];
            $brand->contacter_email = $data['contacter_email'];
            $brand->contacter_QQ = $data['contacter_QQ'];

            $brand->company_city = $data['company_city'];
            $brand->company_name = $data['company_name'];
            $brand->company_district = $data['company_district'];
            $brand->company_address_detail = $data['company_address_detail'];
            $brand->company_province = $data['company_province'];
            $brand->save();




            DB::commit();
        } catch (Exception $e){
            DB::rollback();
            Session::flash('Message','修改失败，'.$e->getMessage());
            return Redirect::back();
        }
        Session::flash('Message','修改成功！');
        return Redirect::back();        
    }

    public function postChangeweixin(Request $request){
        $this->validate($request, [
            'brand_id' => 'required',
            'name' => 'required|max:255',
            'weixin_id'=>'required',
            'appid' => 'required',
            'appsecret' => 'required',
            'token' => 'required',
            'encodingaeskey' => 'required',
            'originalid' => 'required',
            'subscribe_text'=>'required',

            'weixin_shop_num' => 'required',
            'weixin_api_key'=> 'required|min:32|max:32',
           // 'weixin_staff_account'=> 'required',
            'weixin_apiclient_cert'=> 'required',
            'weixin_apiclient_key'=> 'required',
            //'weixin_apiclient_key'=> 'required',
            /*
            'zhifubao_pid'=> 'required',
            'zhifubao_appid'=> 'required',
            'zhifubao_public_key'=> 'required',
            'zhifubao_private_key'=> 'required',
            */
        ]);
        DB::beginTransaction();
        try{
            $brand = Brand::find($request->brand_id);
            $brand->weixin_shop_num = $request->weixin_shop_num;
            $brand->weixin_api_key = $request->weixin_api_key;
           // $brand->weixin_staff_account = $request->weixin_staff_account;
            $brand->save();

            $path = public_path('uploads/'.$brand->id.'/apiclient');
            $result = $this->createdir($path);
            if(!$result){
                Session::flash('Message','Create directory failed.');
                return Redirect::back();
            }
            $file1 = public_path('uploads/'.$brand->id.'/'.'apiclient/apiclient_cert.pem');
            $file2 = public_path('uploads/'.$brand->id.'/'.'apiclient/apiclient_key.pem');

            $fp1 = fopen($file1, 'w+');
            fwrite($fp1, $request->weixin_apiclient_cert);
            fclose($fp1);
            $fp2 = fopen($file2, 'w+');
            fwrite($fp2, $request->weixin_apiclient_key);
            fclose($fp2);

            $account = Account::where('brand_id',$request->brand_id)->first();
            $account->name = $request->name;
            $account->appid = $request->appid;
            $account->appsecret = $request->appsecret;
            $account->token = $request->token;
            $account->encodingaeskey = $request->encodingaeskey;
            $account->originalid = $request->originalid;
            $account->weixin_id = $request->weixin_id;
            $account->subscribe_text = $request->subscribe_text;
            $account->save();

            DB::commit();
        } catch (Exception $e){
            DB::rollback();
            Session::flash('WeixinMessage','修改失败，'.$e->getMessage());
            return Redirect::back();
        }
        Session::flash('WeixinMessage','修改成功');
            return Redirect::back();
    }

    #-------------添加品牌页面
    public function getAdd(){
        $mainbusiness = Mainbusiness::all();
        return View::make('admin.brandmanage.add',array(
            'mainbusiness'=>$mainbusiness
            ));
    }
    #-----------品牌添加
    #---------表单提交
    public function postAdd(Request $request){
        //字段验证，可添加规则
        $this->validate($request, [
            'brandname' => 'required|unique:brand|min:1|max:255',
            'main_business' => 'required|max:255',

            'contacter_name' => 'required',
            'contacter_phone' => 'required|unique:users,account|regex:/^1[34578][0-9]{9}$/',
            'password' => 'required|min:6|max:16',
            'contacter_email' => 'required|email',
            'contacter_QQ' => 'required|regex:/^[1-9][0-9]{4,10}$/', 

            'company_name' => 'required',
            'company_district' => 'required',
            'company_province' => 'required',
            'company_city' => 'required',
            'company_address_detail' => 'required',

            'name' => 'required|max:255',
            'weixin_id'=>'required',
            'appid' => 'required|unique:public_number',
            'appsecret' => 'required',
            'token' => 'required',
            'encodingaeskey' => 'required',
            'originalid' => 'required|unique:public_number',
            'subscribe_text'=>'required',

            'weixin_shop_num' => 'required',
            'weixin_api_key'=> 'required|min:32|max:32',
            //'weixin_staff_account'=> 'required',
            'weixin_apiclient_cert'=> 'required',
            'weixin_apiclient_key'=> 'required',
            //'weixin_apiclient_key'=> 'required',
            /*
            'zhifubao_pid'=> 'required',
            'zhifubao_appid'=> 'required',
            'zhifubao_public_key'=> 'required',
            'zhifubao_private_key'=> 'required',
            */
        ]);

        $data = $request->except(['weixin_apiclient_cert','weixin_apiclient_key']);
        $data['status'] = 1;
        $brand_name = $data['brandname'];
        //事务处理
        DB::beginTransaction();
        try {
            $user = new User;
            $user->account = $request->input('contacter_phone');
            $user->password = Hash::make($request->input('password'));
            $user->role = 1;
            $user->save();

            $data['uid'] = $user->id;
            $brand = new Brand;
            $brand->fill($data)->save();

            $user->brand_id = $brand->id;
            $user->save();

            $path = public_path('uploads/'.$brand->id.'/apiclient');
            $result = $this->createdir($path);
            if(!$result){
                Session::flash('Message','Create directory failed.');
                return Redirect::back();
            }
            $file1 = public_path('uploads/'.$brand->id.'/'.'apiclient/apiclient_cert.pem');
            $file2 = public_path('uploads/'.$brand->id.'/'.'apiclient/apiclient_key.pem');

            $fp1 = fopen($file1, 'w+');
            fwrite($fp1, $request->weixin_apiclient_cert);
            fclose($fp1);
            $fp2 = fopen($file2, 'w+');
            fwrite($fp2, $request->weixin_apiclient_key);
            fclose($fp2);

            $account=new Account;
            $account->brand_id = $brand->id;
            $account->name = $request->name;
            $account->appid = $request->appid;
            $account->appsecret = $request->appsecret;
            $account->token = $request->token;
            $account->encodingaeskey = $request->encodingaeskey;
            $account->type = 0;
            $account->originalid = $request->originalid;
            $account->weixin_id = $request->weixin_id;
            $account->subscribe_text = $request->subscribe_text;
            $account->status = 1;
            $account->save();

            $shopadmin = new Shopadmin;
            $shopadmin->uid = $user->id;
            $shopadmin->brand_id = $brand->id;
            $shopadmin->name = $brand->contacter_name;
            $shopadmin->phone = $brand->contacter_phone;
            $shopadmin->email = $brand->contacter_email;
            $shopadmin->status = 1;
            $shopadmin->save();
            
            $this->create_all_brand_tables($brand_name);
            
            DB::commit();
        } catch (Exception $e){
            DB::rollback();
            Session::flash('Message','添加失败，'.$e->getMessage());
            return Redirect::back();
        }
        //Session::flash('Message','添加成功！');
        return Redirect::to('Admin/brandmanage/index');
        
    }
    #---------------状态修改参数 
    #------------路由参数brand_id
    public function postChangestatus($id){
        
        $list = Brand::find($id);       
        
        if($list->status){
            $list->status = 0;            
        }else{
            $list->status = 1;
        }
        $result = $list->save();
        if($result){
            //以闪存方式返回信息
            return Response::json(array(
                'status'=>'success',
                'message'=>'状态更新成功',
                ));
        }else{
            return Response::json(array(
                'status'=>'success',
                'message'=>'状态更新成功',
                ));
        }
    }
    #----------数据中心（表格）
    #----------参数 start_time---------
    #---------------end_time
    #---------------unit(day week month)
    #----------brand_id
    public function postData(Request $request){
        $start = $request->input('start_time');//起始时间
        $end = $request->input('end_time');//结束时间
        $brand_id = $request->input('brand_id');
        $unit = $request->input('unit');//时间单位 day week month 
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

        $order_array = array();
        $customer_array = array();
        $total_array = array();
        $shop_array = array();
        $order = new Order;
        $order->setTable($brandname.'_order');
        $customer = new Customer;
        $customer->setTable($brandname.'_customers');
        $shop = new Shopinfo;

        for($i=$start;$i<$end;$i+=$seconds){

            $order_count_base = 0;
            $customer_count_base = 0;
            $total_base = 0;
            $shop_count_base = 0;
           
            $customer_count_base += $customer->getNewCustomerCount($i,$i+$seconds);
            $order_count_base += $order->getNewOrder($i,$i+$seconds);
            $total_base += $order->getNewTotal($i,$i+$seconds);
            $shop_count_base += $shop->getNewShopCount($i,$i+$seconds,$brand_id);

            array_push($total_array,$total_base);
            array_push($order_array,$order_count_base);
            array_push($customer_array,$customer_count_base);
            array_push($shop_array,$shop_count_base);

        }

        return Response::json(array(
            'status' => 'success', 
            'order_array' => $order_array,
            'customer_array' => $customer_array,
            'total_array' => $total_array,
            'shop_array'=>$shop_array,
            'message' => '获取成功',
            ));
    }
    #---------------品牌详情
    #------------路由参数brand_id
    public function getDetail($id){
        $brand = Brand::find($id);
        $brandname = Brand::find($id)->brandname;
        $total = 0;
        $shop_count = 0;
        $customer_count = 0;
        $order_count = 0;

        $account = Account::where('brand_id',$id)->first(); 
        $savePath = 'uploads/'.$brand->id.'/'.'apiclient';
        if(file_exists(public_path($savePath.'/apiclient_cert.pem'))){
            $brand->weixin_apiclient_cert = file_get_contents(public_path($savePath.'/apiclient_cert.pem'));
        }else{
            $brand->weixin_apiclient_cert = "";
        }
        if(file_exists(public_path($savePath.'/apiclient_key.pem'))){
            $brand->weixin_apiclient_key = file_get_contents(public_path($savePath.'/apiclient_key.pem'));
        }else{
            $brand->weixin_apiclient_key = "";
        }

        $shop_lists = Shopinfo::where('brand_id',$id)->get();
        foreach ($shop_lists as $key => $value) {
            $value->status_at = date('Y-m-d H:i:s',$value->status_at);
        }
        $shop_count = Shopinfo::where('brand_id',$id)->count();//分店数

        $customer = new Customer;
        $brand_customers = $customer->setTable($brandname.'_customers');
        $customer_count = $brand_customers->getCustomerCount();

        $order = new Order;
        $submit_order = $order->setTable($brandname.'_order');
        $order_count = $submit_order->getOrderCount();
        $total = $order->getTotal();
        $mainbusiness = Mainbusiness::all();
        return View::make('admin.brandmanage.detail',array(
            'brand'=>$brand,
            'shop_lists'=>$shop_lists,
            'shop_count'=>$shop_count,
            'account'=>$account,
            'brand_id'=>$id,
            'brandname'=>$brandname,
            'order_count'=>$order_count,
            'total'=>$total,
            'customer_count'=>$customer_count,
            'mainbusiness'=>$mainbusiness
            ));
    }

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


    //品牌表创建，如果想要修改品牌下的表结构需要在此修改！！！
    private function create_all_brand_tables($brand_name){
        if(!Schema::hasTable($brand_name.'_express_template'))
        Schema::create($brand_name.'_express_template', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('shop_id');            
            $table->string('name');//员工姓名
            $table->tinyInteger('first_num');//首件个数
            $table->float('first_price');//首件运费
            $table->tinyInteger('second_num');//续件个数
            $table->float('second_price');//续件运费
            $table->tinyInteger('status');
            $table->integer('created_at');
            $table->integer('updated_at');
            //$table->timestamps();
        });

        if(!Schema::hasTable($brand_name.'_express_province'))
        Schema::create($brand_name.'_express_province', function (Blueprint $table) {
            $table->increments('id');            
            $table->integer('express_template_id');//模板id
            $table->string('province');//可配送省
            $table->tinyInteger('status');
            $table->integer('created_at');
            $table->integer('updated_at');
            //$table->timestamps();
        });

        if(!Schema::hasTable($brand_name.'_staff'))
        Schema::create($brand_name.'_staff', function (Blueprint $table) {
            $table->increments('id');            
            $table->integer('shop_id');//店铺id
            $table->string('staff_name');//员工姓名
            $table->string('staff_phone');//员工电话
            $table->tinyInteger('status');
            $table->integer('created_at');
            $table->integer('updated_at');
            //$table->timestamps();
        });

        Schema::create($brand_name.'_app_order', function (Blueprint $table) {
            $table->increments('id');
            $table->string('customer_id',11);
            $table->string('order_num',15);
            $table->string('identifer');//标识号            
            $table->integer('commodity_id');
            $table->integer('count');
            $table->integer('staff_id');
            $table->tinyInteger('clerk_id');
            $table->boolean('isdispose');//0未处理，1已处理
            $table->tinyInteger('status');//0未结算，1结算
            $table->integer('created_at');
            $table->integer('updated_at');
            //$table->timestamps();
        });

        //app临时用户表
        Schema::create($brand_name.'_app_customers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('account');//临时用户账号
            $table->integer('brand_id');
            $table->integer('shop_id');
            $table->integer('table_id');
            $table->integer('created_at');
            $table->integer('updated_at');
            //$table->timestamps();
        });

        Schema::create($brand_name.'_receiver_address', function (Blueprint $table) {
            $table->increments('id');            
            $table->string('customer_id',11);//顾客id
            $table->string('receiver_name');//收货人姓名
            $table->string('receiver_phone');//收货人电话
            $table->string('province');//收货人所在省
            $table->string('city');//收货人所在市
            $table->string('district');//收货人所在区
            $table->string('street');//收货人所在街道
            $table->string('address_details');//收货人详细地址
            $table->tinyInteger('is_default');
            $table->tinyInteger('status');//0已删除 1正常
            $table->integer('created_at');
            $table->integer('updated_at');
            //$table->timestamps();
        });
        
        if(!Schema::hasTable($brand_name.'_commodity'))
        //商品表
        Schema::create($brand_name.'_commodity', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('shop_id');//商品所属店铺id
            $table->string('commodity_name');//商品名称
            $table->integer('category_id');//商品分类id
            $table->string('category_name');//商品分类名称
            $table->integer('group_id');//商品分组id
            $table->string('group_name');//商品分组
            $table->integer('PV');//商品页面访问量
            $table->integer('UV');//商品顾客访问量            
            $table->string('brief_introduction');
            $table->text('description');
            $table->string('main_img');//商品主图
            $table->tinyInteger('sku_info');//商品库存信息
            $table->string('produce_area1');//一级产地
            $table->string('produce_area2');//二级产地
            $table->double('base_price');//商品基本价格
            $table->tinyInteger('use_express_template');//是否使用邮费模板
            $table->float('express_price');//统一规格下的邮费价格
            $table->integer('express_template_id');//邮费模板id
            $table->tinyInteger('is_recommend');//是否精选
            $table->tinyInteger('type');//0虚拟商品，1实物商品
            $table->tinyInteger('has_time_limit');
            $table->integer('start_sale_time');
            $table->integer('end_sale_time');
            $table->integer('has_vip_discount');//是否参与会员折扣
            $table->integer('limit_count');//限买次数
            $table->integer('saled_count');//销量
            $table->tinyInteger('is_all_shop');//是否全店都有
            $table->tinyInteger('status');//商品状态（是否上架销售）删除9
            $table->integer('created_at');
            $table->integer('updated_at');
            $table->softDeletes();
            //$table->timestamps();
        });
        //商店在售商品表
        Schema::create($brand_name.'_shop_commodity', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('shop_id');//店铺id
            $table->integer('commodity_id');
            $table->integer('quantity');//商品店铺总库存
            $table->integer('saled_count');//商品分店销量
            $table->tinyInteger('status');//商品状态,0下架 1上架 2暂停销售 3品牌下架导致下架
            $table->integer('created_at');
            $table->integer('updated_at');
            //$table->timestamps();
        });
        //商店在售商品库存表
        Schema::create($brand_name.'_shop_sku', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('commodity_id');
            $table->integer('shop_id');//店铺id
            $table->integer('sku_id');
            $table->integer('quantity');//商品库存
            $table->integer('saled_count');//某规格商品分店销量
            $table->tinyInteger('status');//商品库存状态
            $table->integer('created_at');
            $table->integer('updated_at');
            //$table->timestamps();
        });
    //商品表
        Schema::create($brand_name.'_commodity_img', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('shop_id');//商品图片所属店铺id
            $table->integer('commodity_id');
            $table->string('img_src');//商品图片地址
            $table->tinyInteger('status');//商品图状态
            $table->tinyInteger('order');//商品图次序
            $table->integer('created_at');
            $table->integer('updated_at');
            //$table->timestamps();
        });
     //顾客访问统计表,记录顾客访问时间
        Schema::create($brand_name.'_commodity_customer', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('commodity_id');
            $table->integer('customer_id');
            $table->integer('count');
            $table->integer('created_at');
            $table->integer('updated_at');
            //$table->timestamps();
        });
    /*
        if(!Schema::hasTable($brand_name.'_tag'))
        //商品标签表
        Schema::create($brand_name.'_tag', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('shop_id');//标签所属店铺id
            $table->string('name');//标签名
            $table->string('img');//标签logo
            $table->tinyInteger('status');//状态
            $table->integer('created_at');
            $table->integer('updated_at');
            //$table->timestamps();
        });

        if(!Schema::hasTable($brand_name.'_commodity_group'))
        //商品标签表
        Schema::create($brand_name.'_commodity_group', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('shop_id');//分组所属店铺id
            $table->string('name');//
            $table->string('img');//分组logo
            $table->tinyInteger('status');//状态
            $table->integer('created_at');
            $table->integer('updated_at');
            //$table->timestamps();
        });
*/
        


        if(!Schema::hasTable($brand_name.'_skuname'))
        //商品sku 名称
        Schema::create($brand_name.'_skuname', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('shop_id');//商品sku所属店铺id
            $table->string('sku_name');//商品sku名称
            $table->integer('created_at');
            $table->integer('updated_at');
            //$table->timestamps();
        });

        if(!Schema::hasTable($brand_name.'_skuvalue'))
        //商品sku 值
        Schema::create($brand_name.'_skuvalue', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('skuname_id');//商品sku名称id
            $table->string('sku_value');//商品sku 值
            $table->integer('created_at');
            $table->integer('updated_at');
                //$table->timestamps();
        });

        if(!Schema::hasTable($brand_name.'_skulist'))
        //商品sku列表 {name1:value1,name2:value2,.....}为一个最小库存单位
        Schema::create($brand_name.'_skulist', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('commodity_id');//商品id
            $table->text('commodity_sku');//商品sku信息
            $table->float('price');//商品价格，微信价
            $table->float('old_price');//原价
            $table->integer('quantity');//商品库存量
            $table->integer('status');
            $table->integer('created_at');
            $table->integer('updated_at');
            //$table->timestamps();
        });

        if(!Schema::hasTable($brand_name.'_statistic'))
        //数据统计（最小单位为店铺，每个店铺每天会产生一条数据）
        Schema::create($brand_name.'_statistic', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('shop_id');//数据统计的店铺id
            $table->integer('customer_count');//店铺某日顾客数
            $table->integer('fans_count');//店铺某日粉丝数
            $table->integer('order_count');//店铺某日订单数
            $table->integer('visiter_count');//店铺某日访问量
            $table->integer('created_at');
            $table->integer('updated_at');
            //$table->timestamps();
        });

        if(!Schema::hasTable($brand_name.'_order'))
        
        Schema::create($brand_name.'_order', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('shop_id');//订单所属店铺id
            $table->string('identifer');//app_order关联
            $table->string('order_num',16);//订单编号
            $table->string('trade_num',32);//订单编号
            $table->float('total');//总价
            $table->float('express_price');//订单运费
            $table->string('express_num');//快递单号
            $table->integer('coupon_id');//优惠劵
            $table->tinyInteger('status');//0用户已删除 1待付款 2待发货 3已发货 4已完成 5已关闭 6退款中 7已退款
            $table->string('customer_id',11);//顾客id
            $table->integer('address_id');//收货地址id
            $table->string('message');//买家留言
            $table->integer('order_at');
            $table->integer('trade_at');
            $table->integer('send_at');
            $table->integer('refund_at');
            $table->string('refund_num',32);
            $table->float('refund_money');//实际退款金额
            $table->integer('close_type');//关闭原因
            $table->integer('hurry_times');//催货次数
            $table->integer('hurry_at');//提醒发货时间
            $table->integer('deal');//是否处理过
            $table->integer('created_at');
            $table->integer('updated_at');
            //$table->timestamps();
        });

        Schema::create($brand_name.'_order_shopcart', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('shopcart_id');
            $table->integer('order_id');
            $table->integer('created_at');
            $table->integer('updated_at');
            //$table->timestamps();
        });
        
        if(!Schema::hasTable($brand_name.'_order_refund'))
        
        Schema::create($brand_name.'_order_refund', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('order_id');//订单id
            $table->string('description');
            $table->string('img_src');
            $table->integer('status');//是否处理
            $table->integer('created_at');
            $table->integer('updated_at');
            //$table->timestamps();
        });

        if(!Schema::hasTable($brand_name.'_customers'))
        //顾客表
        Schema::create($brand_name.'_customers', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('uid');//uid 对应users表中的id（认证时使用）
            $table->integer('shop_id');//顾客所注册的店铺id
            $table->string('openid');//顾客微信openid
            $table->string('email');//顾客邮箱
            $table->string('phone');//顾客电话
            $table->string('nickname');//顾客微信昵称
            $table->string('follow_weixin');//顾客所关注微信号
            $table->string('city');//顾客所关注微信号
            $table->string('province');//顾客所关注微信号
            $table->string('country');//顾客所关注微信号
            $table->tinyInteger('sex');//顾客性别1男 2女
            $table->string('headimgurl');//微信头像
            $table->integer('public_id');//关注公众号id
            $table->tinyInteger('is_vip');//是否为会员
            $table->tinyInteger('status');//状态
            $table->integer('score');//会员积分
            $table->integer('created_at');
            $table->integer('updated_at');
            //$table->timestamps();
        });
        if(!Schema::hasTable($brand_name.'_group'))
        //商品分组表
        Schema::create($brand_name.'_group', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('shop_id');//商品分类店铺id
            $table->string('name');//商品分类名称
            $table->string('img');//分类图片
            $table->tinyInteger('status');//分类状态
            $table->integer('created_at');
            $table->integer('updated_at');
                //$table->timestamps();
        });
        if(!Schema::hasTable($brand_name.'_shopcart'))
        //购物车
        Schema::create($brand_name.'_shopcart', function (Blueprint $table) {
            $table->increments('id');            
            $table->string('customer_id',11);//顾客id
            $table->integer('commodity_id');//商品id
            $table->integer('shop_id');
            $table->integer('sku_id');//商品sku id
            $table->integer('count');//商品数量
            $table->tinyInteger('status');//条目状态0 不活动，1活动,2立即购买时临时存入购物车，3再次购买加入
            $table->integer('order_id');//仅再次购买时存在 
            $table->integer('created_at');
            $table->integer('updated_at');
                //$table->timestamps();
        });
/*
        if(!Schema::hasTable($brand_name.'_pageview'))
        //页面访问记录
        Schema::create($brand_name.'_pageview', function (Blueprint $table) {
            $table->increments('id');            
            $table->integer('page_id');//页面url
            $table->integer('shopid');
            $table->string('customer_id',11);//顾客id
            $table->integer('created_at');
            $table->integer('updated_at');
                //$table->timestamps();
        });
*/
        if(!Schema::hasTable($brand_name.'_coupon'))
        //优惠劵
        Schema::create($brand_name.'_coupon', function (Blueprint $table) {
            $table->increments('id');            
            $table->string('name');//优惠劵名称
            $table->integer('person_times');//获取人次
            $table->integer('quantity');//领取数量
            $table->integer('number');//发放数量
            $table->float('sum',10,2);//优惠劵面额
            $table->integer('shop_id');//使用范围，0全店通用
            $table->integer('commodity_category');//使用范围，0所有种类商品通用
            $table->string('description');//优惠劵描述
            $table->float('use_condition',10,2);//优惠劵使用条件（消费最低限额）0无限制
            $table->integer('validity_start');//有效期开始日期
            $table->integer('validity_end');//有效期结束日期
            $table->tinyInteger('allow_share');//是否允许分享朋友圈
            $table->text('share_introduce');//分享说明
            $table->tinyInteger('status');//（领取）0失效 1有效
            $table->tinyInteger('gettimes');//限领次数 0无限制 默认为1次
            $table->integer('used_num');//已使用张数
            $table->integer('created_at');
            $table->integer('updated_at');
            //$table->timestamps();
        });
        if(!Schema::hasTable($brand_name.'_coupon_list'))
        //优惠劵列表
        Schema::create($brand_name.'_coupon_list', function (Blueprint $table) {
            $table->increments('id');            
            $table->integer('coupon_id');//优惠劵id
            $table->string('customer_id',11);//顾客id
            $table->integer('number');//数量
            $table->integer('used');//使用张数
            $table->integer('created_at');
            $table->integer('updated_at');
            //$table->timestamps();
        });
        if(!Schema::hasTable($brand_name.'_search'))
        //用户搜索
        Schema::create($brand_name.'_search', function (Blueprint $table) {
            $table->increments('id');            
            $table->string('content');//搜索内容
            $table->integer('times');//搜索次数
            $table->integer('created_at');
            $table->integer('updated_at');
                //$table->timestamps();
        });
        if(!Schema::hasTable($brand_name.'_search_list'))
        //用户搜索列表
        Schema::create($brand_name.'_search_list', function (Blueprint $table) {
            $table->increments('id');            
            $table->integer('search_id');//搜索内容id
            $table->string('customer_id',11);//顾客id
            $table->integer('created_at');
            $table->integer('updated_at');
                //$table->timestamps();
        });
    }



}