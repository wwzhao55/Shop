<?php namespace App\Http\Controllers\Brand;
use Illuminate\Http\Request;
use App\Models\Commodity\Group,App\Models\Commodity\Commodity,App\Models\Shop\Shopinfo,App\Models\Shop\Shopstaff,App\Models\Brand\Brand;
use Auth,View,Session,Redirect,Response,DB,Validator;
/**
* 商品管理模块 //edit by xuxuxu
*/
class GroupController extends CommonController
{
	
	//商品分类管理首页
	public function getIndex(){

		$group = new Group;
		$group->setTable($this->brandname.'_group');
		$group_lists = $group->where('id','>',0)->get();
		foreach ($group_lists as $key => $list) {
			$commodity = new Commodity;
			$commodity->setTable($this->brandname.'_commodity');
			$list->commodity_count = $commodity->where('group_id',$list->id)->where('status','!=',9)->count();
		}
		$group_count = $group_lists->count();
		return View::make('brand.group.index',array(
			'group_lists'=>$group_lists,
			'group_count'=>$group_count,
			));
	}

	public function postAdd(Request $request){
		$this->validate($request,array(
			'name' => 'required|max:255',
			));

		$data = $request->all();
		$data['status'] = 1;
		
		$group = new Group;
		$group->setTable($this->brandname.'_group');
		$result = $group->fill($data)->save();

		if($result){
			Session::flash('Message','添加成功！');
		}else{
			Session::flash('Message','添加失败！');
		}
		return Redirect::back();
	}

	public function postEdit(Request $request,$id){
		$validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
        ]);
        if ($validator->fails()) {
            return Response::json(['status' => 'error','msg' => '名称不符合要求']);
        }
		$_commodity = new Commodity;
		$_commodity->setTable($this->brandname.'_commodity');
		$commodity_count = $_commodity->where('group_id',$id)->count();
		/*if($commodity_count){
			return Response::json(array(
				'status'=>'fail',
				'message'=>'修改失败', 
				));
		}*/
		DB::beginTransaction();
		try{
			$group = new Group;
			$group->setTable($this->brandname.'_group');
			$list = $group->where('id',$id)->first();
			$list->name = $request->name;
			$list->setTable($this->brandname.'_group')->save();

			$Commodity = new Commodity;
			$Commodity->setTable($this->brandname.'_commodity');
			$commodity_list = $Commodity->where('group_id',$id)->get();
			foreach ($commodity_list as $key => $c) {
				$c->group_id = $id;
				$c->group_name = $request->name;
				$c->setTable($this->brandname.'_commodity')->save();
			}
			DB::commit();
		}catch (Exception $e){
           DB::rollback();
           return Response::json(array(
            'status' => 'fail',
            'message'=>$e->getMessage(),
            ));
        }
		
		return Response::json(array(
				'status'=>'success',
				'message'=>'修改成功',
				));
	}

	public function getChangestatus($id){
		$group = new Group;
		$group->setTable($this->brandname.'_group');
		$group->where('id',$id)->first();
		if($group->status){
			$group->status = 0;
		}else{
			$group->status = 1;
		}
		$result = $group->save();
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
		$commodity_count = $_commodity->where('group_id',$id)->where('status','!=',9)->count();
		if($commodity_count){
			Session::flash("Message",'该商品分类下存在商品，暂时不能删除！！');
			return Redirect::back();
		}
		$group = new Group;
		$group->setTable($this->brandname.'_group');
		$list = $group->where('id',$id)->first();
		$result = $list->setTable($this->brandname.'_group')->delete();
		if($result){
			Session::flash('Message','删除成功！');
		}else{
			Session::flash('Message','删除失败！');
		}
		return Redirect::back();
	}

	

}