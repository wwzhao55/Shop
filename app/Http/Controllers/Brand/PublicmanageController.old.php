<?php namespace App\Http\Controllers\Brand;
//edit by xuxuxu
use Illuminate\Http\Request;
use App\Models\Weixin\Account;
use View,Auth,Session,Redirect;
class PublicmanageController extends CommonController{

	public function getIndex(){
		$brand_id = Auth::user()->brand_id;
		$public_info = array();
		$has_public_number = Account::where('brand_id',$brand_id)->count();
		if($has_public_number){
			$public_info = Account::where('brand_id',$brand_id)->first();
		}
		
		return View::make('brand.publicmanage.index',array(
			'brand_id'=>$brand_id,
			'has_public_number'=>$has_public_number,
			'public_info'=>$public_info,
			));
	}


	public function getAdd(){
		return View::make('brand.publicmanage.add');
	}

	public function postAdd(Request $request){
		$brand_id = Auth::user()->brand_id;
		$has_public_number = Account::where('brand_id',$brand_id)->count();
		if($has_public_number){
			Session::flash('Message','只能添加一个公众号！！');
			return Redirect::back();
		}
		$this->validate($request,array(
			'name' => 'required|max:255',
			'weixin_id'=>'required',
			'appid' => 'required|unique:public_number',
			'appsecret' => 'required',
			'token' => 'required',
			'encodingaeskey' => 'required',
			'originalid' => 'required|unique:public_number',
			));
		$data = $request->all();
		$data['brand_id'] = Auth::user()->brand_id;
		$data['type'] = 0;
		$data['status']= 1;

		$account = new Account;
		$account->fill($data);
		$result = $account->save();
		if($result){
			Session::flash('Message','添加公众号成功！！');
			return Redirect::back();
		}else{
			Session::flash('Message','添加公众号失败！！');
			return Redirect::back();
		}
	}

	public function postEdit(Request $request,$id){
		$this->validate($request,array(
			'name' => 'required|max:255',
			'weixin_id'=>'required',
			'appid' => 'required',
			'appsecret' => 'required',
			'token' => 'required',
			'encodingaeskey' => 'required',
			'originalid' => 'required',
			));
		$data = $request->all();
		$account = Account::find($id);
		$account->name = $request->input('name');
		$account->appid = $request->input('appid');
		$account->appsecret = $request->input('appsecret');
		$account->token = $request->input('token');
		$account->encodingaeskey = $request->input('encodingaeskey');
		$account->originalid = $request->input('originalid');
		$account->weixin_id = $request->weixin_id;
		$result = $account->save();
		if($result){
			Session::flash('Message','修改公众号成功！！');
			return Redirect::back();
		}else{
			Session::flash('Message','修改公众号失败！！');
			return Redirect::back();
		}
	}


}