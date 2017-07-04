<?php namespace App\Http\Controllers\Admin\App;
//edit by xuxuxu
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\app\Theme,App\Models\app\Themeeffect;
use View,Response,Session,Redirect,DB;
class ThemeController extends Controller{
	public function __construct(){
		$this->middleware('admin');
	}
	#----------首页------------#
	public function getIndex(){
        $theme_lists = Theme::all();
        foreach ($theme_lists as $key => $value) {
            $value->effect_img;
        }
        $theme_count = $theme_lists->count();
        var_dump($theme_lists->toArray());
        return View::make('admin.app.theme.index',array(
            'theme_lists'=>$theme_lists,
            'theme_count'=>$theme_count,
            ));       
    }

    #------------添加页面---------#
    public function getAdd(){
        return View::make('admin.app.theme.add');
    }
    #------------主题详情（修改页面）-----------#
    public function getDetail($id){
        $theme = Theme::find($id);
        $theme_effect = Themeeffect::where('theme_id',$id)->get();
        $theme->effect_img = $theme_effect;
        return View::make('admin.app.theme.detail',array(
            'theme'=>$theme,
            ));
    }
    #------------主题5个文本字段修改-----------#
    public function postEdit(Request $request,$id){
        $this->validate($request, [
            'name'=>'required|unique:app_theme',
            'background_color'=>'required',
            'font'=>'required',
            'price'=>'required',
            'description'=>'required',
        ]);
        $theme = Theme::find($id);
        $result = $theme->fill($request->only('name','background_color','font','price','description'))->save();
        if($result){
            Session::flash('Message','修改成功');
        }else{
            Session::flash('Message','修改失败');
        }
        return Redirect::back();
    }
    #--------------主题添加------------#
    #--------------按钮组路径规则 ：
    #---------如果未上传成功，则保存在buttongroup文件夹下，
    #--------一旦上传成功，该文件夹改名为主题id
    #--------效果图(多张)路径需要入库：
    #----------提交的字段为字符串格式，以“，”隔开
    #----------name="effect_img"
    public function postAdd(Request $request){
        $this->validate($request, [
            'name'=>'required|unique:app_theme',
            'effect_img'=>'required',
            'background_color'=>'required',
            'font'=>'required',
            'price'=>'required',
            'description'=>'required',
        ]);
        $publicPath = public_path();//项目public路径
        if(is_dir(!$publicPath.'/uploads/app/theme/buttongroup')){
            return Response::json(['status'=>'fail','message'=>'按钮组图片上传出错']);
        }
        
        if( scandir($publicPath.'/uploads/app/theme/buttongroup') < 30 ){
            return Response::json(['status'=>'fail','message'=>'按钮组图片上传不完整']);
        }


        $data = $request->only('name','background_color','font','price','description');
        $theme_effect = $request->input('effect_img');

        DB::beginTransaction();
        try {
            $theme = new Theme;
            $theme->status = 1;
            $theme->save();//预生成主题id

            $effect_img_array = explode(',',$theme_effect);
            foreach ($effect_img_array as $value) {
                $effect = new Themeeffect;
                $effect->theme_id = $theme->id;
                $effect->img_src = $value;
                $effect->status = 1;
                $effect->save();
            }

            $theme->url =  'uploads/app/theme/'.$theme->id;
            $theme->name = $data['name'];
            $theme->background_color = $data['background_color'];
            $theme->font = $data['font'];
            $theme->price = $data['price'];
            $theme->description = $data['description'];
            $theme->status = 1;

            $theme->save();
            $result = rename($publicPath.'/uploads/app/theme/buttongroup',$publicPath.'/uploads/app/theme/'.$theme->id);
            if(!$result){
                DB::rollback();
                return Response::json(['status'=>'fail','message'=>'按钮组图片路径修改出错']);
            }
            DB::commit();
        } catch (Exception $e){
            DB::rollback();
            return Response::json(['status'=>'fail','message'=>$e->getMessage()]);
        }

        return Response::json(['status'=>'success','message'=>'添加主题模板成功']);
    }
    #----------按钮组上传、编辑
    #--------参数button_name 既输入框的name值
    #----------type ‘add’添加 ‘edit’修改
    public function postButtongroup(Request $request){
        $this->validate($request,array(
            'type' => 'required',
            'button_name'=>'required',
            ));
        $button_name = $request->button_name;
        $type = $request->type;
        
        if($type == 'edit'){
            $theme_id = isset($request->theme_id)?$request->theme_id:'';
            if($theme_id){
                return Response::json(array(
                    'status'=>'fail',
                    'message'=>'无效的主题id',
                    ));
            }
            $button_dir = $theme_id;
        }elseif($type == 'add'){
            $button_dir = 'buttongroup';
        }else{
            return Response::json(array(
                'status'=>'fail',
                'message'=>'操作类型错误',
                ));
        }

        if(!$request->hasFile($button_name)){
            return Response::json(['status'=>'fail','message'=>'上传数据出错']);
        }
        $file = $request->file($button_name);
        if(!$file->isValid()){
            return Response::json(['status'=>'fail','message'=>$file->getErrorMessage()]);
        }

        $publicPath = public_path();//项目public路径
        $result = $this->createdir($publicPath.'/uploads/app/theme/'.$button_dir,0777);//不存在相应路径则建立
        if(!$result){
            return Response::json(['status'=>'fail','message'=>'建立图片存储目录出错']);
        }
        //目标存储路径
        $destinationPath = 'uploads/app/theme/'.$button_dir;
        $extension = $file->getClientOriginalExtension();
        if($extension != 'png'){
            return Response::json(['status'=>'fail','message'=>'图片只能为png格式']);
        }
        $file->move($destinationPath,$button_name.'.png');

        return Response::json(array(
            'status'=>'success',
            'message'=>'操作成功',
            'path'=>'uploads/app/theme/'.$button_dir.'/'.$button.'.png',
            )); 
    }
    #----------------效果图上传、编辑
    #-------参数type 
    public function postEffectimg(Request $request){
        $this->validate($request,array(
            'type'=>'required',
            ));

        if (!$request->hasFile('effect_img')) {
            return Response::json(['status'=>'fail','message'=>'上传数据出错']);
        }

        $file = $request->file('effect_img');
        if(!$file->isValid()){
            return Response::json(['status'=>'fail','message'=>$file->getErrorMessage()]);
        }

        $publicPath = public_path();//项目public路径
        $result = $this->createdir($publicPath.'/uploads/app/theme/effect',0777);//不存在相应路径则建立
        if(!$result){
            return Response::json(['status'=>'fail','message'=>'建立图片存储目录出错']);
        }
        //目标存储路径
        $destinationPath = 'uploads/app/theme/effect';
        $filename = md5( date('ymdhis') );
        $extension = $file->getClientOriginalExtension();
        $file->move($destinationPath,$filename.'.'.$extension);//图片上传

        $type = $request->type;
        switch ($type) {
            case 'add':
                # code...
                break;
            case 'edit':
                $effect_img_id = $request->effect_img_id;
                $effect_img = Effecetimg::find($effect_img_id);
                $old_img_src = $effect_img->img_src;
                $effect_img->img_src = 'uploads/app/theme/effect/'.$filename.'.'.$extension;
                $result = $effect_img->save();
                if($result){
                    unlink($publicPath.'/'.$old_img_src);
                }else{
                    unlink($publicPath.'/uploads/app/theme/effect/'.$filename.'.'.$extension);
                    return Response::json(array(
                        'status'=>'fail',
                        'message'=>'修改失败，图片地址入库失败',
                        ));
                }
                break;
            default:
                return Response::json(array(
                    'status'=>'fail',
                    'message'=>'操作类型错误',
                    ));
                break;
        }


        return Response::json(array(
            'status'=>'success',
            'message'=>'上传成功',
            'path'=>'uploads/app/theme/effect/'.$filename.'.'.$extension,
            )); 
    }
    #-----------效果图删除-------------#
    public function postDelete($id){
        DB::beginTransaction();
        try {
            $theme = Theme::find($id);
            $theme_effects = Themeeffect::where('theme_id',$id)->get();
            $theme->delete();
            foreach ($theme_effects as $effect) {
                $effect->delete();
            }

            if(is_dir($publicPath.'/uploads/app/theme/'.$id)){
                $result = $this->deldir($publicPath.'/uploads/app/theme/'.$id);
                if(!$result){
                    Session::flash('Message','删除按钮组图片失败');
                    DB::rollback();
                    return Redirect::back();
                }
            }
            
            DB::commit();
        } catch (Exception $e){
            Session::flash('Message','删除按钮组图片失败');
            DB::rollback();
            return Redirect::back();
        }
        Session::flash('Message','删除成功');
        return Redirect::back();
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

    private function deldir($dir) {
      //先删除目录下的文件：
        $dh=opendir($dir);
        while ($file=readdir($dh)) {
            if($file!="." && $file!="..") {
                $fullpath=$dir."/".$file;
                if(!is_dir($fullpath)) {
                    unlink($fullpath);
                } else {
                    deldir($fullpath);
                }
            }
        }
     
        closedir($dh);
        //删除当前文件夹：
        if(rmdir($dir)) {
            return true;
        } else {
            return false;
        }
    }
	
}