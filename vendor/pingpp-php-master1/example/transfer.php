<?php
/* *
 * Ping++ Server SDK
 * 说明：
 * 以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己网站的需要，按照技术文档编写, 并非一定要使用该代码。
 * 该代码仅供学习和研究 Ping++ SDK 使用，只是提供一个参考。
*/

require dirname(__FILE__) . '/../init.php';

// api_key、app_id 请从 [Dashboard](https://dashboard.pingxx.com) 获取
class Dopay{
    public function createOrder($api_key,$app_id,$channel1,$amount,$subject,$body){
        if($api_key == '' || $api_key == 0){
            $api_key = 'sk_live_OuH040ivDyjD4Wf1044Ce9uD';
        }
        if($app_id == '' || $app_id == 0){
            $app_id = 'app_9m14S8HKqTeLOyzL';
        }
        if($channel1 == ''){
            $channel1='wx_pub_qr';
        }
        $channel = strtolower($channel1);
        $orderNo = substr(md5(time()),0,12);
        $extra = array();
        switch ($channel) {
            case 'alipay_wap':
                $extra = array(
                    'success_url' => 'http://127.0.0.1/youba/index.php/Home/Index/success.html',
                    'cancel_url' => 'http://127.0.0.1/youba/index.php/Home/Index/buy.html'
                );
                break;
            case 'upmp_wap':
                $extra = array(
                    'result_url' => 'http://www.yourdomain.com/result?code='
                );
                break;
            case 'bfb_wap':
                $extra = array(
                    'result_url' => 'http://www.yourdomain.com/result?code=',
                    'bfb_login' => true
                );
                break;
            case 'upacp_wap':
                $extra = array(
                    'result_url' => 'http://www.yourdomain.com/result?code='
                );
                break;
            case 'wx_pub':
                $extra = array(
                    'open_id' => 'Openid'
                );
                break;
            case 'wx_pub_qr':
                $extra = array(
                    'product_id' => 'Productid'
                );
                break;
            case 'yeepay_wap':
                 $extra = array(
                    'product_category' => '1',
                    'identity_id'=> 'your identity_id',
                    'identity_type' => 1,
                    'terminal_type' => 1,
                    'terminal_id'=>'your terminal_id',
                    'user_ua'=>'your user_ua',
                    'result_url'=>'http://www.yourdomain.com/result'
                );
                break;
            case 'jdpay_wap':
                $extra = array(
                    'success_url' => 'http://www.yourdomain.com',
                    'fail_url'=> 'http://www.yourdomain.com',
                    'token' => 'dsafadsfasdfadsjuyhfnhujkijunhaf'
                );
                break;
        }
  
        \Pingpp\Pingpp::setApiKey($api_key);
          header('Content-Type: application/json'); 
        try{
            $ch = \Pingpp\Charge::create(
                array(
                    'subject'   => $subject,
                    'body'      => $body,
                    'amount'    => $amount,
                    'order_no'  => $orderNo,
                    'currency'  => 'cny',
                    'extra'     => $extra,
                    'channel'   => $channel,
                    'client_ip' => '127.0.0.1',
                    'app'       => array('id' => $app_id),
                )
            );
            // $_SESSION['charge_id'] = $ch['id'];
         
           echo $ch; 
          // echo json_encode( array('status'=>'success','msg'=>'get success','ch'=>$ch));
        }catch(\Pingpp\Error\Base $e){
           
            header('Status: '.$e->getHttpStatus());
            echo $e->getHttpBody();
            //echo json_encode( array('status'=>'error','msg'=>$e->getHttpBody()));
            
        }
        
    }
    public function dopay1(){
    $api_key = 'sk_live_OuH040ivDyjD4Wf1044Ce9uD';
    $app_id = 'app_9m14S8HKqTeLOyzL';
        \Pingpp\Pingpp::setApiKey($api_key);
        try {
         $tr = \Pingpp\Transfer::create(
            array(
            'amount'    => 100,
            'order_no'  => date('YmdHis') . (microtime(true) % 1) * 1000 . mt_rand(0, 9999),
            'currency'  => 'cny',
            'channel'   => 'alipay_qr',
            'app'       => array('id' => $app_id),
            'type'      => 'b2c',
            'recipient' => 'o9zpMs9jIaLynQY9N6yxcZ',
            'description' => 'testing',
            'extra' => array('user_name' => 'User Name', 'force_check' => false)
        )
        );
            echo $tr;
    }   catch (\Pingpp\Error\Base $e) {
        header('Status: ' . $e->getHttpStatus());
        echo($e->getHttpBody());
        }
    }
}
