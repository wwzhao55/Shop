<?php
namespace App\Http\Controllers\app;
use View,App\Http\Controllers\Controller,Route;
use App\Models\app\User;
use Illuminate\Http\Request;
use App\SmsApi;
use DB;
use App\Pay;
use Redirect,Validation,Session,Auth,Hash;
class ClerkController extends Controller {
    public function __construct() { 
    }

    /**
	 * Setup the layout used by the controller.
	 *
	 * @return void
	 */
	protected function setupLayout()
	{
		if ( ! is_null($this->layout))
		{
			$this->layout = View::make($this->layout);
		}
	}

	public function getIndex(){
		echo "app/Clerk index";
	} 

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//
//                                choose table id
//
//
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////    

	//获取桌号
	public function postGettableid(Request $request){
		$json_data = $request->getContent();
        $data = json_decode($json_data,true);
        $table_id=$data['table_id'];
        Session::put('table_id',$table_id);
        if(session('table_id')){
        	echo json_encode(array('status'=>'success','msg'=>'get tabel id success!','table_id'=>session('table_id')));

        }else{
            echo json_encode(array('status'=>'error','msg'=>'get tabel id failed!'));
         }
	}



//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//
//                                test
//
//
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////     
	public function postTest(Request $request){
		$json_data = $request->getContent();
        $data = json_decode($json_data,true);
        var_dump(time());
        var_dump(date('Y-m-d H:i:s',time()));
        var_dump(date('Y-m-d H:i:s','1464839306'));
        
	}
}

