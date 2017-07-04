<?php

namespace Illuminate\Message;

class Message{
    //即时发送,充值短信
//    var $result = sendSMS($username,$password,$mobile,$content,$apikey);
//    echo $result;
    
    public function sendRecharge($mobile,$value,$account){
        $url = 'http://m.5c.com.cn/api/send/?';
        $username = 'deling';     //用户账号
        $password = 'asdf123';   //密码
        $apikey = 'aaf99bf81617b12ebab4323acae9ac49';   //密码
        $content = '【youba】充值提醒：充值'.$value.'元成功，账户余额为'.$account.'元。';        //内容
        $data = array
            (
            'username'=>$username,                  //用户账号
            'password'=>$password,              //密码
            'mobile'=>$mobile,                  //号码
            'content'=>$content,                //内容
            'apikey'=>$apikey,                  //apikey
            );
        $result= $this->curlSMS($url,$data);           //POST方式提交
        return $content;
    }
    //验证码短信
    public function sendCode($mobile,$code){
        $url = 'http://m.5c.com.cn/api/send/?';
        $username = 'dataguiding';     //用户账号
        $password = 'qwer1234';   //密码
        $apikey = 'b966f93d0035a4ec78b4d27118bc85b7';   //密码
        $content = '您的验证码是：'.$code.'。请不要把验证码泄露给其他人。';        //内容
        $data = array
            (
            'username'=>$username,                  //用户账号
            'password'=>$password,              //密码
            'mobile'=>$mobile,                  //号码
            'content'=>$content,                //内容
            'apikey'=>$apikey,                  //apikey
            );
        $result= $this->curlSMS($url,$data);           //POST方式提交
        return $result;
    }
     //提醒发货
    public function sendHurry($mobile,$code){
        $url = 'http://m.5c.com.cn/api/send/?';
        $username = 'dataguiding';     //用户账号
        $password = 'qwer1234';   //密码
        $apikey = 'b966f93d0035a4ec78b4d27118bc85b7';   //密码
        $content = $code;        //内容
        $data = array
            (
            'username'=>$username,                  //用户账号
            'password'=>$password,              //密码
            'mobile'=>$mobile,                  //号码
            'content'=>$content,                //内容
            'apikey'=>$apikey,                  //apikey
            );
        $result= $this->curlSMS($url,$data);           //POST方式提交
        return $result;
    }
    //取货通知
    public function sendFinish($mobile,$order,$time,$detail = null){
        $url = 'http://m.5c.com.cn/api/send/?';
        $username = 'deling';     //用户账号
        $password = 'asdf123';   //密码
        $apikey = 'aaf99bf81617b12ebab4323acae9ac49';   //密码
        if($detail){
            $content = '【youba】您的订单'.$order.'于'.$time.'完成。'.'（'.$detail.'）';        //内容
        }else{
            $content = '【youba】您的订单'.$order.'于'.$time.'完成。';        //内容
        }
        $data = array
            (
            'username'=>$username,                  //用户账号
            'password'=>$password,              //密码
            'mobile'=>$mobile,                  //号码
            'content'=>$content,                //内容
            'apikey'=>$apikey,                  //apikey
            );
        $result= $this->curlSMS($url,$data);           //POST方式提交
        return $result;
    }
    
    public function query(){
        $url = 'http://m.5c.com.cn/api/query/index.php';
        $username = 'deling';     //用户账号
        $password = 'asdf123';   //密码
        $apikey = 'aaf99bf81617b12ebab4323acae9ac49';   //密码
        $data = array
            (
            'username'=>$username,                  //用户账号
            'password'=>$password,              //密码
            'apikey'=>$apikey,                  //apikey
            );
        $result= $this->curlSMS($url,$data);           //POST方式提交
        return $result;
    }
    
    public function curlSMS($url,$post_fields=array()){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 3600); //60秒 
        curl_setopt($ch, CURLOPT_HEADER,1);
        curl_setopt($ch, CURLOPT_REFERER,'http://www.yourdomain.com');
        curl_setopt($ch,CURLOPT_POST,1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,$post_fields);
        $data = curl_exec($ch);
        curl_close($ch);
        $res = explode("\r\n\r\n",$data);
        return $res[2]; 
    }
}
