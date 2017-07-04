<?php
namespace App;
//require_once 'vendor/autoload.php';
// require_once('vendor/autoload.php');
require_once(dirname(dirname(__FILE__)).'/vendor'.'/pingpp-php-master/example/transfer.php');
// require_once('/var/www/html/shop/vendor/autoload.php');
//require_once('/wwwroot/www/Dataguiding-MS/vendor/autoload.php');
// require_once('/var/www/html/shop/vendor/pingpp-php-master/example/transfer.php');
class Pay{
    //$msg = 'aaa';
    //系统消息推送
    public function pay2code($api_key=null,$app_id=null,$channel1=null,$amount=null,$subject=null,$body=null){
        $pay2 =new \Dopay();
        //createOrder($api_key,$app_id,$channel1,$amount)
        $pay2->createOrder($api_key,$app_id,$channel1,$amount,$subject,$body);
        // echo "ddd";
    	}
    	
   
}



    
 

