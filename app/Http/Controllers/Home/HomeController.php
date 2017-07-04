<?php namespace App\Http\Controllers\Home;
//edit by xuxuxu
use App\Http\Controllers\Controller;
use App\Models\User,App\Models\Brand\Brand,App\Models\Shop\Shopstaff,App\Models\Shop\Shopadmin,App\Models\Shop\Shopinfo;
use Redirect,Auth,Session;		
/**
* 
*/
class HomeController extends Controller
{
	
	function __construct()
	{
		//登录后可进行下一步操作
		$this->middleware('auth');
	}
	//根据用户的不同角色分配到不同首页
	public function getIndex(){
		//获取用户角色
		$role = Auth::user()->role;
		switch ($role) {
			case 0://超级管理员
				return Redirect::to('/Admin/datacenter');
				
			case 1://品牌管理员
				$brandname = Brand::find(Auth::user()->brand_id)->brandname;
				Session::put('brand_id',Auth::user()->brand_id);
				Session::put('brandname',$brandname);
				return Redirect::to('/Brand/datacenter');
			/*	
			case 2://小店超级管理员
				return Redirect::to('/Shopadmin/datacenter');
			*/	
			case 3://小店员工
				//brandname shop_id 写入session 方便使用
				$brandname = Brand::find(Auth::user()->brand_id)->brandname;
				$shop_id= Auth::user()->shop_id;
				Session::put('brand_id',Auth::user()->brand_id);
				Session::put('shop_id',$shop_id);
				Session::put('shopname',Shopinfo::find($shop_id)->shopname);
				return Redirect::to('/Shopstaff/weborder');
					
			default:
				return Redirect::to('/shop');
		}
	}
}