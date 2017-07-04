<?php 
namespace App\Http\Controllers\Shopstaff;
//edit by xuxuxu
use App\Http\Controllers\Controller,App\Models\Brand\Brand;
use Session,Auth;
/**
* 
*/
class CommonController extends Controller
{
	
	function __construct()
	{
		$this->middleware('shopstaff');
		if(Session::has('brand_id')){
			$this->brandname = Brand::find(Session::get('brand_id'))->brandname;
			$this->shop_id = Session::get('shop_id');
			$this->brand_id = Session::get('brand_id');
			$this->shopname = Session::get('shopname');
		}
	}
}