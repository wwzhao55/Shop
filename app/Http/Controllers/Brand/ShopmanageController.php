<?php namespace App\Http\Controllers\Brand;
//edit by xuxuxu
use Illuminate\Http\Request;
use App\Models\Brand\Brand,App\Models\Shop\Shopadmin,App\Models\Shop\Shopstaff,App\Models\Shop\Shopinfo,App\Models\User,App\Models\Order\Order,App\Models\Customer\Customer;
use Hash,Schema,View,Session,Redirect,DB,Response,Auth;

class ShopmanageController extends CommonController{
    #------------小店管理首页
    public function getIndex(){
        $brand_id = Auth::user()->brand_id;
        $open_weishop_lists = Shopinfo::where('brand_id',$brand_id)->where('open_weishop',1)->get();
        $open_weishop_count = $open_weishop_lists->count(); 
        $close_weishop_lists = Shopinfo::where('brand_id',$brand_id)->where('open_weishop',0)->get();
        $close_weishop_count = $close_weishop_lists->count(); 
        return View::make('brand.shopmanage.index',array(
            'open_weishop_lists'=>$open_weishop_lists,//开启微店的小店列表
            'open_weishop_count'=>$open_weishop_count,//开启微店的小店数量
            'close_weishop_lists'=>$close_weishop_lists,
            'close_weishop_count'=>$close_weishop_count,
            ));
    }
    #--------------小店开启、关闭微店状态控制
    #-------------参数password(品牌管理员密码)
    #-----------------shop_id
    public function postOpenweishop(Request $request){
        $brand_id = Auth::user()->brand_id;
        $account = Auth::user()->account;
        $password =  $request->input('password');

        if ( !Auth::validate( array('account'=>$account,'password'=>$password) ) ){
            return Response::json(['status'=>'fail','message'=>'密码错误']);    
        }

        $shop_id = $request->input('shop_id');
        $shop = Shopinfo::find($shop_id);
        if($shop->brand_id != $brand_id){
            return Response::json(['status'=>'fail','message'=>'越权操作']);
        }
        if($shop->open_weishop){
            $shop->open_weishop = 0;
        }else{
            $shop->open_weishop = 1;
        }
        $result = $shop->save();
        if($result){
            return Response::json(['status'=>'success','message'=>'操作成功']);
        }else{
            return Response::json(['status'=>'fail','message'=>'操作失败']);
        }
    }

    public function getEdit($id){
        $shop = Shopinfo::find($id);
        return View::make('brand.shopmanage.edit',array('shop'=>$shop));
    }

    public function postEdit(Request $request){
        $this->validate($request,[
            'shop_id'=>'required',
            'shop_province'=>'required|max:30',
            'shop_city'=>'required|max:30',
            'shop_district'=>"required|max:30",
            'shop_address_detail'=>"required|max:255",
            'customer_service_phone'=>array('required','regex:/(^1[34578][0-9]{9}$)|(^([0-9]{3,4}-)?[0-9]{7,8}$)/'),
            'open_at'=>'required',
            'close_at'=>"required",
           // 'special'=>"required|max:255",
           // 'shoplogo'=>'required',
            'logo_changed'=>"required"
        ]);
        $shop = Shopinfo::find($request->shop_id);
        $data = $request->except(['shoplogo','shop_id','logo_changed']);

        if($request->logo_changed){
            $shoplogo = $request->file('shoplogo');
            if (!$shoplogo->isValid()) {
                Session::flash('message','图片出错');
                return Redirect::back();
            }
            $publicPath = public_path();
            $path = $publicPath.'/uploads/'.$shop->brand_id.'/shoplogo';
            $destinationPath = 'uploads/'.$shop->brand_id.'/shoplogo';
            $result = $this->createdir($path);
            if(!$result){
                Session::flash('message','Create directory failed.');
                return Redirect::back();
            }
            //上传图片
            $imgname = md5( date('ymdhis') );
            $extension = $shoplogo->getClientOriginalExtension();
            $shoplogo->move($destinationPath,$imgname.'.'.$extension);
            $img_src =$destinationPath.'/'.$imgname.'.'.$extension;   
            $data['shoplogo'] = $img_src;
            if($shop->shoplogo){
                if(file_exists($publicPath.'/'.$shop->shoplogo)){
                    unlink($publicPath.'/'.$shop->shoplogo);
                }
            }
            
        }
       // var_dump($img_src);
        $shop->fill($data)->save();
        Session::flash('message','修改成功');
        return Redirect::back();
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


}