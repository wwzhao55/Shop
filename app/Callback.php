<?php
namespace App;
//require_once 'vendor/autoload.php';
// require_once('vendor/autoload.php');
require_once(dirname(dirname(__FILE__)).'/vendor'.'/pingpp-php-master/example/webhooks.php');
// require_once('/var/www/html/shop/vendor/autoload.php');
//require_once('/wwwroot/www/Dataguiding-MS/vendor/autoload.php');
// require_once('/var/www/html/shop/vendor/pingpp-php-master/example/transfer.php');
class Callback{
    //$msg = 'aaa';
    //系统消息推送
    public function hook($api_key=null,$app_id=null,$channel1=null,$amount=null,$subject=null,$body=null){
        // $pay2 =new \DoWebhooks();
        //createOrder($api_key,$app_id,$channel1,$amount)
        // $pay2->verify_signature();
        // $raw_data = file_get_contents('php://input');
        $call =new \Dowebhooks();
        //createOrder($api_key,$app_id,$channel1,$amount)
        $call->verify_signature();
        echo "ddd";
    	}
    	
   
}