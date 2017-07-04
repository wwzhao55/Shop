<?php
namespace App\Http\Controllers\app;
use View,App\Http\Controllers\Controller,Route;
use App\Models\app\User;
use Illuminate\Http\Request;
use App\SmsApi;
use DB;
use Cookie;
use App\Callback;
use Redirect,Validation,Session,Auth,Hash;
require_once(dirname(dirname(dirname(dirname(dirname(__FILE__))))).'/vendor'.'/pingpp-php-master/example/transfer.php');
class PublicController extends Controller {
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
    //公共接口类 
	public function getIndex(){
		echo "app/Public index";
	}
	//测试用
	public function postTest(Request $request){
		$user = new User;
		$json_data = $request->getContent();
        $data = json_decode($json_data,true);
        $uid=$data['uid'];
        $re=$user->where('id','=',$uid)->take(1)->get();
        echo json_encode(array('status'=>'success','msg'=>$re));  
	}
///////////////////////////////////////////////////////////////////////////////////////////////////////////
//
//
//                             login logout register
//
//
//////////////////////////////////////////////////////////////////////////////////////////////////////////

/////////////////////clerklogin/////////////////////////////////
    //员工登录，获取登录账户和密码，返回品牌图片，店铺名，左侧菜单
    public function postClerklogin(Request $request){
         $json_data = $request->getContent();
         $data = json_decode($json_data,true);
         if (Auth::attempt(['account'=>$data['account'],'password'=>$data['password']])) {
            $re=DB::table('users')->where('account','=',$data['account'])->get();
            Session::put('brand_id',$re['0']->brand_id);
            // Session::put('brand_name',$re['0']->brand_name);
            Session::put('shop_id',$re['0']->shop_id);
            Session::put('clerk_id',$re['0']->id);
            Session::put('account',$data['account']);
            $re1=DB::table('brand')->where('id','=',session('brand_id'))->get();
            Session::put('brand_name',$re1['0']->brandname);
            $re2=DB::table('shopinfo')->where('id','=',session('shop_id'))->get();
            $re3=DB::table($re1['0']->brandname.'_category')->where('shop_id',session('shop_id'))->get();
            $menu=array();
            //获取左侧的菜单
            $i=0;
            foreach ($re3 as $key => $value) {
                $menu[$i]=$value->name;
                $i++;
            }
            // echo json_encode(array('status'=>'success','msg'=>'clerklogin success','brand_img'=>$re1['0']->brandname,'shop_name'=>$re2['0']->shopname,'menu'=>$menu,'brand_id'=>session('brand_id'),'brand_name'=>session('brand_name'),'shop_id'=>session('shop_id'),'clerk_id'=>session('clerk_id')));
            echo json_encode(array('status'=>'success','msg'=>'clerklogin success','brand_img'=>$re1['0']->brandname,'shop_name'=>'','menu'=>$menu,'brand_id'=>session('brand_id'),'brand_name'=>session('brand_name'),'shop_id'=>session('shop_id'),'clerk_id'=>session('clerk_id')));
         }else{
            echo json_encode(array('status'=>'error','msg'=>'clerklogin failed'));
         }

    }

/////////////////////clerklogout/////////////////////////////////
    //员工退出登录，要求输入密码才可以退出
    public function postClerklogout(Request $request){
         $json_data = $request->getContent();
         $data = json_decode($json_data,true);
         $account=session('account');
         if (Auth::attempt(['account'=>$account,'password'=>$data['password']])) {
            Session::put('account','');
            if(!session('account')){
                echo json_encode(array('status'=>'success','msg'=>'clerklogout success'));
            }
            else{
                echo json_encode(array('status'=>'error','msg'=>'clerklogout failed!'));
            }
         }else{
            echo json_encode(array('status'=>'error','msg'=>'password is wrong!'));
         }

    }
/////////////////////customerlogin/////////////////////////////////
    //顾客登录
	public function postCustomerlogin(Request $request){
         // $brand_id=session('brand_id');
         // $shop_id=session('shop_id');
         // $brand_id=3;
         // $shop_id=3;
		 $json_data = $request->getContent();
         $data = json_decode($json_data,true);
         $temp_customer_id=$data['customer_id'];
         $brand_id=$data['brand_id'];
         $shop_id=$data['shop_id'];
         $re=DB::table('users')->where('account','=',$data['mobile'])->get();
         if ($re){
            //如果登录成功，将用户当前用的临时账号的购物信息移植到新登录的用户名下
            if($re['0']->password == md5($data['password'])){
               Session::put('mobile',$data['mobile']);
               Session::put('uid',$re['0']->id);


               $re1=DB::table('brand')->where('id','=',$brand_id)->get();
               $brand_name=$re1[0]->brandname;
               $re4=DB::table($brand_name.'_shopcart')->where('customer_id','=',$temp_customer_id)->get();
               if($re4){
                    $re2=DB::table($brand_name.'_shopcart')->where('customer_id','=',$temp_customer_id)->update(array('customer_id'=>$re[0]->id));
               }
               else{$re2=1;}
               $re5=DB::table($brand_name.'_app_order')->where('customer_id','=',$temp_customer_id)->get();
               if($re5){
                    $re3=DB::table($brand_name.'_app_order')->where('customer_id','=',$temp_customer_id)->update(array('customer_id'=>$re[0]->id));
               }
               else{$re3=1;}
               $re6=DB::table($brand_name.'_order')->where('customer_id','=',$temp_customer_id)->get();
               if($re6){
                    $re7=DB::table($brand_name.'_order')->where('customer_id','=',$temp_customer_id)->update(array('customer_id'=>$re[0]->id));
               }
               else{$re7=1;}
               if($re2 && $re3 && $re7){
                    echo json_encode(array('status'=>'success','msg'=>'login success','customer_id'=>$re[0]->id));
               }
               else{
                    echo json_encode(array('status'=>'error','msg'=>'login success,but change customer_id failed','customer_id'=>$re[0]->id));
               }
               
            }
            else{
               echo json_encode(array('status'=>'error','msg'=>'wrong password'));
            }
         }
         else{
            echo json_encode(array('status'=>'error','msg'=>'login failed,user not exist!'));
         }

	}

/////////////////////customerlogout/////////////////////////////////
    //顾客退出登录状态
    public function postCustomerlogout(Request $request){
        Session::put('mobile','');
         if(!session('mobile')) {
               echo json_encode(array('status'=>'success','msg'=>'logout success'));
           }
         else{
            echo json_encode(array('status'=>'error','msg'=>'logout failed'));
         }

    }

/////////////////////customerregister/////////////////////////////////
    //顾客注册接口，
    public function postCustomerregister(Request $request){
         // $brand_id=session('brand_id');
         // $shop_id=session('shop_id');
         $brand_id=3;
         $shop_id=3;
         $json_data = $request->getContent();
         $data = json_decode($json_data,true);
         if($data){
            if(!$data['mobile']){
                echo json_encode(array('status'=>'error','msg'=>'please input mobile number'));
                exit;
            }
            if(!$data['password']){
                echo json_encode(array('status'=>'error','msg'=>'please input password'));
                exit;
            }
            if(!$data['repassword']){
                echo json_encode(array('status'=>'error','msg'=>'please cheak your repassword'));
                exit;
            }
            // if($data['code'] != Session::get('code', 'default')){
            //     echo json_encode(array('status'=>'error','msg'=>'wrong code !','code'=> $data['code'],'session_code'=>Session::get('code', 'default')));
            //     exit;
            // }

            $result=DB::table('users')
            ->insert(array('account'=>$data['mobile'],'password'=>md5($data['password']),'shop_id' => $shop_id,'brand_id' => $brand_id,'role'=>5));

            if($result){
                echo json_encode(array('status'=>'success','msg'=>'clerk register success'));
            }
            else{
                echo json_encode(array('status'=>'error','msg'=>'clerk register failed'));
            }
         }
         else{
            echo json_encode(array('status'=>'error','msg'=>'without data'));
            exit;
         }
    }
    //顾客获取手机验证码，且规定24小时内获取验证码的次数不超过5次
    public function postGetcode(Request $request){
        
        $json_data = $request->getContent();
        $data = json_decode($json_data,true);
        $mobile=$data['mobile'];
        if(!$mobile){
            echo json_encode(array('status'=>'error','msg'=>'please input mobile first!'));
            exit;
        }
        if(!session('repeat')){
            $repeat=0;
        }
        else{
            $repeat = session('repeat');
        }
        if($repeat > 5){
            echo json_encode(array('status'=>'error','msg'=>'24小时内获取验证码的次数不能超过5次'));
            exit;
            }
        $code = substr(str_shuffle("1234567890"),2,6);
        Session::put('code',$code);
        $sms = new SmsApi;
        $res = $sms->sendCode($mobile,$code);
        if(strstr($res,'success')){
            $repeat++;
            Session::put('repeat',$repeat);

            echo json_encode(array('status'=>'success','msg'=>'get code success ,please cheak your message.获取验证码成功，请等待','mobile'=>$mobile,'code'=>Session::get('code', 'default'),'repeat'=>$repeat));   
        }else{
            echo json_encode(array('status'=>'error','msg'=>'get code failed ,please try later.获取验证码失败，未知错误'));
        }

    }
///////////////////////////////////////////////////////////////////////////////////////////////////////////
//
//
//                             find password
//
//
//////////////////////////////////////////////////////////////////////////////////////////////////////////
    //找回密码
    public function postFindpassword(Request $request){
         $json_data = $request->getContent();
         $data = json_decode($json_data,true);
         $mobile=$data['mobile'];
         $code=$data['code'];
         $password=$data['password'];
         if($data){
            if(!$data['mobile']){
                echo json_encode(array('status'=>'error','msg'=>'please input mobile number'));
                exit;
            }
            if(!$data['password']){
                echo json_encode(array('status'=>'error','msg'=>'please input password'));
                exit;
            }
            if(!$data['code']){
                echo json_encode(array('status'=>'error','msg'=>'please cheak your repassword'));
                exit;
            }
            if($data['code'] != Session::get('code', 'default')){
                echo json_encode(array('status'=>'error','msg'=>'wrong code !','code'=> $data['code'],'session_code'=>Session::get('code', 'default')));
                exit;
            }

            $result=DB::table('users')
            ->where('account','=',$mobile)
            ->update(array('password'=>md5($password),'created_at'=>time()));

            if($result){
                echo json_encode(array('status'=>'success','msg'=>'customer reset password success'));
            }
            else{
                echo json_encode(array('status'=>'error','msg'=>'customer reset password failed'));
            }
         }
         else{
            echo json_encode(array('status'=>'error','msg'=>'without data'));
            exit;
         }
    }

///////////////////////////////////////////////////////////////////////////////////////////////////////////
//
//
//                             get brand shop menu
//
//
//////////////////////////////////////////////////////////////////////////////////////////////////////////

///////////////////////////////getbrand///////////////////////////////////////////
    //获取所有的品牌
    public function postGetbrand(){
        $result=DB::table('brand')->select('id','brandname')->get();//->toArray();
        if($result){
            echo json_encode(array('status'=>'success','msg'=>'get brand success','brand'=>$result));
        }else{
            json_encode(array('status'=>'error','msg'=>'get brand failed'));
        }
    }
    //获取某品牌下的所有店铺
    public function postGetshop(Request $request){
        $json_data = $request->getContent();
        $data = json_decode($json_data,true);
        $result=DB::table('shopinfo')->where('brand_id',$data['brand_id'])->select('id','shopname')->get();
        if($result){
            echo json_encode(array('status'=>'success','msg'=>'get shop success','shop'=>$result));
        }else{
            echo json_encode(array('status'=>'error','msg'=>'get shop failed'));
        }
    }

    //点击菜单内的任意条目，获取对应该条目下的所有商品
    public function postGetgoods(Request $request){
        $json_data = $request->getContent();
        $data = json_decode($json_data,true);
        $brand_name=$data['brand_name'];
        $shop_id=$data['shop_id'];
        if(!$brand_name){
            echo json_encode(array('status'=>'error','msg'=>'session brand_name get failed.'));
            exit;
        }
        if(!$shop_id){
            echo json_encode(array('status'=>'error','msg'=>'session shop_id get failed.'));
            exit;
        }
        $result = DB::table($brand_name.'_commodity')
        ->where('shop_id',$shop_id)
        ->where('category_name',$data['name'])
        ->select('commodity_name','main_img','img','express_price')->get();
        if($result){
            echo json_encode(array('status'=>'success','msg'=>'get shop success','data'=>$result));
        }else{
            echo json_encode(array('status'=>'error','msg'=>'get shop failed'));
        }
    } 
///////////////////////////////////////////////////////////////////////////////////////////////////////////
//
//
//                             get order
//
//
//////////////////////////////////////////////////////////////////////////////////////////////////////////

    public function postGetorder(Request $request){

    }

///////////////////////////////////////////////////////////////////////////////////////////////////////////
//
//
//                            get app start img
//
//
//////////////////////////////////////////////////////////////////////////////////////////////////////////
    //app启动页加载，显示几张图片
    public function postGetstartimg(Request $request){
        $result = DB::table('app_start_logo')->where('id','>',0)->get();
        $img=array();
        $i=0;
        foreach ($result as $key => $value) {
            $img[$i]['img']=$value->logo_src;
            $i++;
        }
        if($result){
            echo json_encode(array('status'=>'success','msg'=>'get start_img  success','data'=>$img));
        }else{
            echo json_encode(array('status'=>'error','msg'=>'get start_img failed'));
        }

    }


///////////////////////////////////////////////////////////////////////////////////////////////////////////
//
//
//                             get theme
//
//
//////////////////////////////////////////////////////////////////////////////////////////////////////////
    //获取所有的主题，
    public function postGetalltheme(Request $request){
        $re=DB::table('app_theme')->where('id','>',0)->get();
        $theme_array=array();
        $i=0;
        foreach ($re as $key => $value) {
            $theme_array[$i]['id']=$value->id;
            $theme_array[$i]['name']=$value->name;
            $i++;
        }
        if($theme_array){
            echo json_encode(array('status'=>'success','msg'=>'get all theme success','data'=>$theme_array));
        }
        else{
            echo json_encode(array('status'=>'success','msg'=>'get all theme failed'));
        }

    }
    //选择一个主题，返回该主题的基本信息
    public function postChoosetheme(Request $request){
        $json_data = $request->getContent();
        $data = json_decode($json_data,true);
        $re=DB::table('app_theme')->where('id','=',$data['id'])->get();
        $theme_array=array();
        $theme_array['url']=$re[0]->url;
        $theme_array['font']=$re[0]->font;
        $theme_array['name']=$re[0]->name;
        
        if($re){
            echo json_encode(array('status'=>'success','msg'=>'get theme success','data'=>$theme_array));
        }
        else{
            echo json_encode(array('status'=>'success','msg'=>'get theme failed'));
        }

    }   

///////////////////////////////////////////////////////////////////////////////////////////////////////////
//
//
//                            pay callback
//
//
//////////////////////////////////////////////////////////////////////////////////////////////////////////
    //付款成功后，回调函数所执行的操作，在数据库中填写付款成功等信息
    public function postTtt(Request $request){
         // $brand_id=3;
         // $re=DB::table('幸菓_category')->where('shop_id',3)->get();

         // Session::put('ee',$brand_id);
         // $ee=Session::get('ee', 'default');
         // $ee1=session('ee');
         // var_dump($ee);
         // var_dump($ee1);
         // // var_dump($brand_name);
        $pay = new \DoPay;
        $res = $pay->start();
        // $json_data = $request->getContent();
        // $data = json_decode($json_data,true);
        $brand_name='幸菓';
        $order_no=$res['raw_data']['data']['object']['order_no'];
        // $order_no=$data['order_no'];
        if($res['msg'] =='charge.succeeded'){
            $re=DB::table($brand_name.'_order')->where('order_num','=',$order_no)->update(array('status'=>1));
            $re1=DB::table($brand_name.'_order')->where('order_num','=',$order_no)->get();
            $identifer=$re1[0]->identifer;
            $re2=DB::table($brand_name.'_app_order')->where('identifer','=',$identifer)
            ->where('status','=',0)->update(array('status'=>1,'order_num'=>$order_no));
            if($re && $re2){
                $result=DB::table('users')
                ->insert(array('account'=>'success','remember_token'=>$res['raw_data']['data']['object']['order_no'],'role'=>'9'));
            }
            else{
                $result=DB::table('users')
                ->insert(array('account'=>'failed','remember_token'=>$res['raw_data']['data']['object']['order_no'],'role'=>'8'));
            }
            
        }
        
    }   
    public function getTtt(Request $request){
        $pay = new \DoPay;
        $res = $pay->start();
        $result=DB::table('users')
        ->insert(array('account'=>$res,'role'=>'6'));
    }


///////////////////////////////////////////////////////////////////////////////////////////////////////////
//
//
//                            generate temporary acount
//
//
//////////////////////////////////////////////////////////////////////////////////////////////////////////

///////////////////////////////////////////////////////////////////////////////////////////////////////////
//
//
//                             temporary users
//
//
//////////////////////////////////////////////////////////////////////////////////////////////////////////
    //未登录的用户，开始操作时，使用零时账户
    public function postGettempuser(Request $request){
        $json_data = $request->getContent();
        $data = json_decode($json_data,true);
        $brand_id=$data['brand_id'];
        $re=DB::table('brand')->where('id','=',$brand_id)->get();
        $brand_name=$re[0]->brandname;
        $shop_id=$data['shop_id'];
        $table_id=$data['table_id'];
        $account=time().$table_id;
        $re1=DB::table($brand_name.'_app_customers')
        ->insert(array('account'=>$account,'brand_id'=>$brand_id,'shop_id' => $shop_id,'table_id' => $table_id,'created_at'=>time()));
        if($re1){
            echo json_encode(array('status'=>'success','msg'=>'get temp user success','customer_id'=>$account));
        }
        else{
            echo json_encode(array('status'=>'success','msg'=>'get temp failed'));
        }
    }
}
