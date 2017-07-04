<?php namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\Category;
use View,Auth,Redirect,Session;

/**
* 品牌主营(用于管理主营列表) edit by xuxuxu
*/
class CategoryController extends Controller
{
    
    function __construct()
    {
        $this->middleware('admin');
    }

    //商品分类管理首页
    public function getIndex(){

        $category_lists = Category::all();
        $category_count = $category_lists->count();
        return View::make('admin.category.index',array(
            'category_lists'=>$category_lists,
            'category_count'=>$category_count,
            ));
    }

    public function getChangestatus($id){
        $category = Category::find($id);
        if(!$category){
            Session::flash('Message','参数错误');
            return Redirect::back();
        }
        if($category->status){
            $category->status = 0;
        }else{
            $category->status = 1;
        }
        $result = $category->save();
        if($result){
            Session::flash('Message','修改成功！');
        }else{
            Session::flash('Message','修改失败！');
        }
        return Redirect::back();
    }

    public function getDelete($id){
        $category = Category::find($id);
        if(!$category){
            Session::flash('Message','参数错误');
            return Redirect::back();
        }
        
        $result = $category->delete();
        if($result){
            Session::flash('Message','删除成功！');
        }else{
            Session::flash('Message','删除失败！');
        }
        return Redirect::back();
    }

    public function postAdd(Request $request){
        $this->validate($request,array(
            'name' => 'required|max:255',
            ));
      
        $category = new Category;
        $category->name = $request->input('name');
        $category->status = 1;
        $result = $category->save();

        if($result){
            Session::flash('Message','添加成功！');
        }else{
            Session::flash('Message','添加失败！');
        }
        return Redirect::back();


    }
}