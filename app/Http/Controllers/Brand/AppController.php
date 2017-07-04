<?php namespace App\Http\Controllers\Brand;
//edit by xuxuxu
use Illuminate\Http\Request;
use App\Models\Brand\Brand,App\Models\Shop\Shopinfo,App\Models\Brand\Brandtheme,App\Models\app\Theme;
use Hash,Schema,View,Session,Redirect,DB,Response,Auth;

class AppController extends CommonController{
    #----------app管理首页--------
    public function getIndex(){
        $brand_id = Auth::user()->brand_id;
        $brand_logo = Brand::find($brand_id)->logo;//品牌logo

        $theme_lists = Theme::all();
        $theme_count = $theme_lists;

        $brand_theme_lists = Brandtheme::all();
        $brand_theme_count = $brand_theme_lists->count();
        return View::make('brand.app.index',array(
            'brand_logo'=>$brand_logo,//品牌logo
            'theme_lists'=>$theme_lists,//主题列表
            'theme_count'=>$theme_count,//主题数量
            'brand_theme_lists'=>$brand_theme_lists,//品牌已购买的主题列表
            'brand_theme_count'=>$brand_theme_count,//品牌已购买的主题数量
            ));
    }
    #------------brand logo 上传、修改
    #----------name="brandlogo"
    #---------------存储路径 'uploads/$brand_id/brandlogo/filename'
    public function postChangelogo(Request $request){
        $brand_id = Auth::user()->brand_id;
        if (!$request->hasFile('brandlogo')) {
            return Response::json(['status'=>'fail','message'=>'上传数据出错']);
        }

        $file = $request->file('brandlogo');
        if(!$file->isValid()){
            return Response::json(['status'=>'fail','message'=>$file->getErrorMessage()]);
        }

        $publicPath = public_path();//项目public路径
        $result = $this->createdir($publicPath.'/uploads/'.$brand_id.'/brandlogo',0777);//不存在相应路径则建立
        if(!$result){
            return Response::json(['status'=>'fail','message'=>'建立图片存储目录出错']);
        }
        //目标存储路径
        $destinationPath = 'uploads/'.$brand_id.'/brandlogo';
        $filename = md5( date('ymdhis') );
        $extension = $file->getClientOriginalExtension();
        $file->move($destinationPath,$filename.'.'.$extension);

        $brand = Brand::find($brand_id);
        $old_brandlogo = $brand->brandlogo;
        $brand->brandlogo = 'uploads/'.$brand_id.'/brandlogo/'.$filename.'.'.$extension;
        $result = $brand->save();
        if($result){
           //成功上传
            unlink($publicPath.'/'.$old_brandlogo);
            return Response::json(array(
                'status'=>'success',
                'message'=>'上传成功',
                'path'=>'uploads/'.$brand_id.'/brandlogo/'.$filename.'.'.$extension)); 
        }else{
            return Response::json(['status'=>'fail','message'=>'图片入库失败']);
        }
    } 
    #------------brand logo 删除
    public function postDeletelogo(){
        $publicPath = public_path();//项目public路径
        $brand_id = Auth::user()->brand_id;
        $brand = Brand::find($brand_id);
        $old_brandlogo = $brand->brandlogo;
        $brand->brandlogo = "";
        $result = $brand->save();
        if($result){
            unlink($publicPath.'/'.$old_brandlogo);
            return Response::json(array(
                'status'=>'success',
                'message'=>'删除成功',));
        }else{
            return Response::json(['status'=>'fail','message'=>'删除失败']);
        }

    }
    #--------------主题详情---------
    public function getThemedetail($id){
        $theme = Theme::find($id);
        return View::make('brand.app.themedetail',array(
            'theme'=>$theme,
            ));
    }
    #-------------更换品牌app主题
    #-------------路由参数brand_theme_id（品牌已购买的主题id）
    public function postChangetheme($id){
        $brand_id = Auth::user()->brand_id;
        $brand_theme = Brandtheme::find($id);
        if(!$brand_theme){
            return Response::json(['status'=>'fail','message'=>'无效主题id']);
        }
        if($brand_id != $brand_theme->brand_id){
            return Response::json(['status'=>'fail','message'=>'越权操作！']);
        }
        $brandtheme_lists = Brandtheme::all();
        foreach ($brandtheme_lists as $key => $list) {
            if($list->status == 1){
                $list->status = 0;
                $list->save();
            }
        }

        $brand_theme->status = 1;
        $result = $brand_theme->save();
        if($result){
           //成功上传
            return Response::json(array(
                'status'=>'success',
                'message'=>'修改主题成功',));
        }else{
            return Response::json(['status'=>'fail','message'=>'修改主题失败']);
        }
    }
    #------------删除品牌已购买的主题
    #-----------路由参数brand_theme_id
    public function postDeletetheme($id){
        $brand_id = Auth::user()->brand_id;
        $brand_theme = Brandtheme::find($id);
        if(!$brand_theme){
            return Response::json(['status'=>'fail','message'=>'无效主题id']);
        }
        if($brand_id != $brand_theme->brand_id){
            return Response::json(['status'=>'fail','message'=>'越权操作！']);
        }
        $result = $brand_theme->delete();
        if($result){
           //成功上传
            return Response::json(array(
                'status'=>'success',
                'message'=>'删除主题成功',));
        }else{
            return Response::json(['status'=>'fail','message'=>'修改主题失败']);
        }
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

    public function postPurchase($brand_theme_id){
        $notify = new NativePay();
        $theme_code = $notify->GetPrePayUrl($brand_theme_id);
    }


}