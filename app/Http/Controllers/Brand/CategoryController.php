<?php namespace App\Http\Controllers\Brand;
use Illuminate\Http\Request;
use App\Models\Commodity\Category,App\Models\Commodity\Commodity,App\Models\Shop\Shopinfo,App\Models\Shop\Shopstaff,App\Models\Brand\Brand;
use Auth,View,Session,Redirect;
/**
* 商品管理模块 //edit by xuxuxu
*/
class CategoryController extends CommonController
{
	
	//商品分类管理首页
	public function getIndex(){

		$category = new Category;
		$category->setTable($this->brandname.'_category');
		$category_lists = $category->where('id','>',0)->get();
		foreach ($category_lists as $key => $list) {
			$commodity = new Commodity;
			$commodity->setTable($this->brandname.'_commodity');
			$list->commodity_count = $commodity->where('category_id',$list->id)->count();
		}
		$category_count = $category_lists->count();
		return View::make('brand.category.index',array(
			'category_lists'=>$category_lists,
			'category_count'=>$category_count,
			));
	}

	public function postAdd(Request $request){
		$this->validate($request,array(
			'name' => 'required|max:255',
			));

		$data = $request->all();
		$data['status'] = 1;
		
		$category = new Category;
		$category->setTable($this->brandname.'_category');
		$result = $category->fill($data)->save();

		if($result){
			Session::flash('Message','添加成功！');
		}else{
			Session::flash('Message','添加失败！');
		}
		return Redirect::back();
	}

	public function postEdit($id,Request $request){

	}

	public function getChangestatus($id){
		$category = new Category;
		$category->setTable($this->brandname.'_category');
		$category->where('id',$id)->first();
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
		$_commodity = new Commodity;
		$_commodity->setTable($this->brandname.'_commodity');
		$commodity_count = $_commodity->where('category_id',$id)->count();
		if($commodity_count){
			Session::flash("Message",'该商品分类下存在商品，暂时不能删除！！');
			return Redirect::back();
		}
		$category = new Category;
		$category->setTable($this->brandname.'_category');
		$list = $category->where('id',$id)->first();
		$result = $list->setTable($this->brandname.'_category')->delete();
		if($result){
			Session::flash('Message','删除成功！');
		}else{
			Session::flash('Message','删除失败！');
		}
		return Redirect::back();
	}

	

}