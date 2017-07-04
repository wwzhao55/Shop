<?php
/* *
 * Ping++ Server SDK
 * 说明：
 * 以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己网站的需要，按照技术文档编写, 并非一定要使用该代码。
 * 该代码仅供学习和研究 Ping++ SDK 使用，只是提供一个参考。
*/

require dirname(__FILE__) . '/../init.php';

// api_key、app_id 请从 [Dashboard](https://dashboard.pingxx.com) 获取
///////////////////////////////////////////////////////////////////////////////////////////////////////////
//
//
//                             pay
//
//
//////////////////////////////////////////////////////////////////////////////////////////////////////////
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
         
           return $ch; 
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

///////////////////////////////////////////////////////////////////////////////////////////////////////////
//
//
//                             callback
//
//
//////////////////////////////////////////////////////////////////////////////////////////////////////////
    public function start(){
        $raw_data = file_get_contents('php://input');
        $data1 = json_decode($raw_data,true);
        // echo $raw_data;
        // return $raw_data;
        // exit;
// 示例
// $raw_data = '{"id":"evt_eYa58Wd44Glerl8AgfYfd1sL","created":1434368075,"livemode":true,"type":"charge.succeeded","data":{"object":{"id":"ch_bq9IHKnn6GnLzsS0swOujr4x","object":"charge","created":1434368069,"livemode":true,"paid":true,"refunded":false,"app":"app_vcPcqDeS88ixrPlu","channel":"wx","order_no":"2015d019f7cf6c0d","client_ip":"140.227.22.72","amount":100,"amount_settle":0,"currency":"cny","subject":"An Apple","body":"A Big Red Apple","extra":{},"time_paid":1434368074,"time_expire":1434455469,"time_settle":null,"transaction_no":"1014400031201506150354653857","refunds":{"object":"list","url":"/v1/charges/ch_bq9IHKnn6GnLzsS0swOujr4x/refunds","has_more":false,"data":[]},"amount_refunded":0,"failure_code":null,"failure_msg":null,"metadata":{},"credential":{},"description":null}},"object":"event","pending_webhooks":0,"request":"iar_Xc2SGjrbdmT0eeKWeCsvLhbL"}';

    $headers = \Pingpp\Util\Util::getRequestHeaders();
// 签名在头部信息的 x-pingplusplus-signature 字段
    $signature = isset($headers['X-Pingplusplus-Signature']) ? $headers['X-Pingplusplus-Signature'] : NULL;
// 示例
// $signature = 'BX5sToHUzPSJvAfXqhtJicsuPjt3yvq804PguzLnMruCSvZ4C7xYS4trdg1blJPh26eeK/P2QfCCHpWKedsRS3bPKkjAvugnMKs+3Zs1k+PshAiZsET4sWPGNnf1E89Kh7/2XMa1mgbXtHt7zPNC4kamTqUL/QmEVI8LJNq7C9P3LR03kK2szJDhPzkWPgRyY2YpD2eq1aCJm0bkX9mBWTZdSYFhKt3vuM1Qjp5PWXk0tN5h9dNFqpisihK7XboB81poER2SmnZ8PIslzWu2iULM7VWxmEDA70JKBJFweqLCFBHRszA8Nt3AXF0z5qe61oH1oSUmtPwNhdQQ2G5X3g==';

// 请从 https://dashboard.pingxx.com 获取「Ping++ 公钥」
    $pub_key_path = __DIR__ . "/pingpp_rsa_public_key.pem";

    $result = $this->verify_signature($raw_data, $signature, $pub_key_path);
    
    // if ($result === 1) {
    // 验证通过
        $event = json_decode($raw_data, true);
        if ($event['type'] == 'charge.succeeded') {
            $charge = $event['data']['object'];
            echo json_encode(array('status'=>'success','msg'=>'charge.succeeded','data'=>$charge));
            $msg='charge.succeeded';
            $data=array('msg'=>$msg,'raw_data'=>$data1);
            return $data;
        // http_response_code(200); // PHP 5.4 or greater
        } elseif ($event['type'] == 'refund.succeeded') {
            $refund = $event['data']['object'];
            echo json_encode(array('status'=>'success','msg'=>'refund.succeeded','data'=>$refund));
            $data=array('msg'=>$msg,'raw_data'=>$data1);
            return $data;
        // http_response_code(200); // PHP 5.4 or greater
        } else {

        }
    // } elseif ($result === 0) {
    // // http_response_code(400);
    //     echo json_encode(array('status'=>'error','msg'=>'verification failed'));
    //     $msg='verification failed';
    //     return $msg;
    //     exit;
    // } else {
    // // http_response_code(400);
    //     echo json_encode(array('status'=>'error','msg'=>'verification error'));
    //     $msg='verification error';
    //     return $msg;
    //     exit;
    // }
    
    }


    
    public function verify_signature($raw_data, $signature, $pub_key_path) {
        $pub_key_contents = file_get_contents($pub_key_path);
        // php 5.4.8 以上，第四个参数可用常量 OPENSSL_ALGO_SHA256
        return openssl_verify($raw_data, base64_decode($signature), $pub_key_contents, 'sha256');
}


}
