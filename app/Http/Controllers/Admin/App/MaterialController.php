<?php namespace App\Http\Controllers\Admin\App;
//edit by xuxuxu
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\app\Theme,App\Models\app\Material;
use View,Response,Session,Redirect,DB,Hash;
class MaterialController extends Controller{
	public function __construct(){
		$this->middleware('admin');
	}

    public function getIndex(){
    	$startlogo_lists = Material::where('type',0)->get();
    	$startlogo_count = $startlogo_lists->count();

        $advertisement_lists = Material::where('type',1)->get();
        $advertisement_count = $advertisement_lists->count();

        $theme_lists = Theme::all();
        $theme_count = $theme_lists->count();

    	return View::make('admin.app.material.index',array(
    		'startlogo_lists'=>$startlogo_lists,
    		'startlogo_count'=>$startlogo_count,
            'advertisement_lists'=>$advertisement_lists,
            'advertisement_count'=>$advertisement_count,
            'theme_lists'=>$theme_lists,
            'theme_count'=>$theme_count,
    		));
    }

    public function getAdd(){
        return View::make('admin.app.material.add');
    }

    public function getDelete($id){
        $material = Material::find($id);
        $result = $material->delete();
        if($result){
            Session::flash('Message','操作成功');
        }else{
            Session::flase('Message','操作失败');
        } 
        return Redirect::back();   
    }
    
    public function postAdd($type,Request $request){

		if (!$request->hasFile('material')) {
			return Response::json(['status'=>'fail','message'=>'上传数据出错']);
		}

		$file = $request->file('material');
		if(!$file->isValid()){
			return Response::json(['status'=>'fail','message'=>$file->getErrorMessage()]);
		}

		$publicPath = public_path();//项目public路径
        if($type == 'startlogo'){
            $extraPath = 'startlogo';
            $material_type = 0;
        }elseif ($type=='advertisement') {
            $extraPath = 'advertisement';
            $material_type = 1;
        }else{
            return Response::json(['status'=>'fail','message'=>'无效的素材类型']);
        }

		$result = $this->createdir($publicPath.'/uploads/app/material/'.$extraPath,0777);//不存在相应路径则建立
		if(!$result){
			return Response::json(['status'=>'fail','message'=>'建立图片存储目录出错']);
		}
		//目标存储路径
	    $destinationPath = 'uploads/app/material/'.$extraPath;
	    $filename = md5( date('ymdhis') );
        $extension = $file->getClientOriginalExtension();
    	$file->move($destinationPath,$filename.'.'.$extension);

        $material = new Material;
        $material->type = $material_type;
        $material->img_src = 'uploads/app/material/'.$extraPath.'/'.$filename.'.'.$extension;
        $material->status = 1;
        $result = $material->save();
        if($result){
           //成功上传
            return Response::json(array(
                'status'=>'success',
                'message'=>'上传成功',
                'path'=>'uploads/app/material/'.$extraPath.'/'.$filename.'.'.$extension)); 
        }else{
            return Response::json(['status'=>'fail','message'=>'图片入库失败']);
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

	
}