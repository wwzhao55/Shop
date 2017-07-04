<?php namespace App\Http\Controllers\Admin\App;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\app\Theme,App\Models\app\Material,App\Models\app\Advertisement;
use View,Response,Session,Redirect,DB;
/*-----
----app广告图片 edit by xuxuxu
-------*/
class AdvertisementController extends Controller{
    public function __construct(){
        $this->middleware('admin');
        #-----数据表初始化----------#
        #-----advertisement数据表只有一条记录，包含四张广告图-------#
        if(Advertisement::all()->count() == 0){
            $advertisement = new Advertisement;
            $advertisement->image_src1 = '';
            $advertisement->image_src2 = '';
            $advertisement->image_src3 = '';
            $advertisement->image_src4 = '';
            $advertisement->status = 1;
            $advertisement->save();
        }
    }
    #------------首页-------#
    public function getIndex(){
        $advertisement = Advertisement::first();
        return View::make('admin.app.advertisement.index',array(
                'advertisement'=>$advertisement,
            ));
    }
    #------------添加、修改----------#
    public function postAdd(Request $request){
        $advertisement_name_array = [
            'image_src1',
            'image_src2',
            'image_src3',
            'image_src4',
        ];

        $advertisement_name = '';
        //获取advertisement_name
        foreach ($advertisement_name_array as $button) {
            if($request->hasFile($button)){
                $advertisement_name = $button;
                break;
            }
        }
        if (!$advertisement_name) {
            return Response::json(['status'=>'fail','message'=>'上传数据出错']);
        }

        $file = $request->file($advertisement_name);
        if(!$file->isValid()){
            return Response::json(['status'=>'fail','message'=>$file->getErrorMessage()]);
        }

        $publicPath = public_path();//项目public路径
        $result = $this->createdir($publicPath.'/uploads/app/advertisement',0777);//不存在相应路径则建立
        if(!$result){
            return Response::json(['status'=>'fail','message'=>'建立图片存储目录出错']);
        }
        //目标存储路径
        $destinationPath = 'uploads/app/advertisement';
        $filename = md5(date('ymdhis'));
        $extension = $file->getClientOriginalExtension();
        $file->move($destinationPath,$filename.'.'.$extension);

        $advertisement = Advertisement::first();
        $old_advertisement = $advertisement->$advertisement_name;
        $advertisement->$advertisement_name = 'uploads/app/advertisement/'.$filename.'.'.$extension;
        $result = $advertisement->save();
        if($result){
            unlink($publicPath.'/'.$old_advertisement);
            return Response::json(array(
                'status'=>'success',
                'message'=>'上传成功',
                'path'=>'uploads/app/advertisement/'.$filename.'.'.$extension,
                )); 
        }else{
            return Response::json(array(
                'status'=>'fail',
                'message'=>'图片入库失败',
                
                ));
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

    #---------------删除-------------#
    #--------------参数$name 
    #--------------'image_src1',
    #--------------'image_src2',
    #--------------'image_src3',
    #--------------'image_src4',
    public function getDelete($name){
        $advertisement = Advertisement::first();
        $advertisement_name = $advertisement->$name;
        $advertisement->$name = '';
        $result = $advertisement->save();
        if(!$result){
            Session::flash('Message','删除失败');
            return Redirect::back();
        }
        $publicPath = public_path();
        unlink($publicPath.'/'.$advertisement_name);
        Session::flash('Message','删除成功');
        return Redirect::back();
    }
    
}