<?php namespace App\Http\Controllers\Brand;
use App\Http\Controllers\Controller,App\Models\Brand\Brand;
use Session,Auth;
/**
* //edit by xuxuxu
*/
class CommonController extends Controller
{
	
	function __construct()
	{
		$this->middleware('brand');
		if(Session::has('brand_id')){
			$this->brandname = Brand::find(Session::get('brand_id'))->brandname;
		}
	}
}