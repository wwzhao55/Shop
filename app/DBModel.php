<?php
namespace App;
use View,App\Http\Controllers\Controller,Route,Auth;
use App\Models\app\User;
use Illuminate\Http\Request;
use App\SmsApi;
use DB;
use Redirect,Validation,Session,Auth,Hash;
class DBModel {
	public function dbdo($table='',$operate='',$field='',$condition='',$value=''){
		if($table && $operate && $field){
			switch ($operate) {
				case 'add':
					# code...
					break;
				
				case 'delete':
					# code...
					break;
				
				case 'find':
					$result=DB::table($table)->where($field, $condition,$value)->get();
					break;

				case 'update':
					# code...
					break;
				default:
					# code...
					break;
			}
		}
		// echo "app/Public index";
	}
}