<?php 
namespace App\Http\Controllers\Shop;
use App\Http\Controllers\Controller,App\Models\Brand\Brand;
use Session,Auth;
/**
* //edit by xuxuxu
*/
class CommonController extends Controller
{
	public $openid;
	public $brand_id;
	function __construct()
	{
		$this->middleware('wxentry',['except'=>['anyNotify']]);	
		$this->middleware('shoprest',['except'=>['anyNotify']]);	
		if(Session::has('brand_id')){
			$this->brand_id = Session::get('brand_id');
			$this->brand_name = Brand::find($this->brand_id)->brandname;
			$this->openid = Session::get('openid');
			$this->shop_id = Session::get('shop_id');
		}	
	}
}