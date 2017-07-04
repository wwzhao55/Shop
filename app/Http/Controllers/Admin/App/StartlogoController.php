<?php namespace App\Http\Controllers\Admin\App;
//edit by xuxuxu
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\app\Theme,App\Models\app\Material,App\Models\app\Startlogo;
use View,Response,Session,Redirect,DB;
class StartlogoController extends Controller{
	public function __construct(){
		$this->middleware('admin');
        #-----数据表初始化----------#
        #-----start_logo数据表只有一条记录，包含默认启动图和当前启动图-------#
        #-------当前启动图不为空应用当前启动图，否则应用默认启动图--------#
        if(Startlogo::all()->count() == 0){
            $startlogo = new Startlogo;
            $startlogo->default_src = '';
            $startlogo->logo_src='';
            $startlogo->status = 1;
            $startlogo->save();
        }
	}
	#-------------首页-----------#
	public function getIndex(){
        $startlogo = Startlogo::first();
		return View::make('admin.app.startlogo.index',array(
            'startlogo'=>$startlogo,
			));
    }
    #------------添加、修改------------#
    public function postAdd(Request $request){
        $logo_name_array = [
            'default_src',
            'logo_src',
        ];

        $logo_name = '';
        //获取logo_name
        foreach ($logo_name_array as $logo) {
            if($request->hasFile($logo)){
                $logo_name = $logo;
                break;
            }
        }
        if (!$logo_name) {
            return Response::json(['status'=>'fail','message'=>'上传数据出错']);
        }

        $file = $request->file($logo_name);
        if(!$file->isValid()){
            return Response::json(['status'=>'fail','message'=>$file->getErrorMessage()]);
        }

        $publicPath = public_path();//项目public路径
        $result = $this->createdir($publicPath.'/uploads/app/startlogo',0777);//不存在相应路径则建立
        if(!$result){
            return Response::json(['status'=>'fail','message'=>'建立图片存储目录出错']);
        }
        //目标存储路径
        $destinationPath = 'uploads/app/startlogo';
        $filename = md5(date('ymdhis'));
        $extension = $file->getClientOriginalExtension();
        $file->move($destinationPath,$filename.'.'.$extension);

        $startlogo = Startlogo::first();
        $old_logo = $startlogo->$logo_name;
        $startlogo->$logo_name = 'uploads/app/startlogo/'.$filename.'.'.$extension;
        $result = $startlogo->save();
        if($result){
            unlink($publicPath.'/'.$old_logo);
            return Response::json(array(
                'status'=>'success',
                'message'=>'上传成功',
                'path'=>'uploads/app/startlogo/'.$filename.'.'.$extension,
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
    #-------------删除-----------#
    #-------只能删除当前启动图，默认启动图不能删除---------#
    public function getDelete(){
        $startlogo = Startlogo::first();
        $logo_src = $startlogo->logo_src;
        $startlogo->logo_src = '';
        $result = $startlogo->save();
        if(!$result){
            Session::flash('Message','删除失败');
            return Redirect::back();
        }
        $publicPath = public_path();
        unlink($publicPath.'/'.$logo_src);
        Session::flash('Message','删除成功');
        return Redirect::back();
    }
	
}