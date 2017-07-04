<?php namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\libraries\Wechat,App\Models\Weixin\Account,App\Models\Customer\Customer,App\Models\Brand\Brand;
use Request;

/**
*
*/
class WeixinController extends Controller
{

	public function __construct()
	{
		# code...
	}

	public function anyWxapi() {
     	 $httpget_info = Request::input("originalId");
     	 $public_info = '';
     	 $options = array();
     	 if ($httpget_info) {
     	 	$public_info = Account::where('originalid',$httpget_info)->first();
     	 }

     	 if ($public_info) {
     	 	$options = array(
     	 			'token'=>$public_info->token, //填写你设定的key
     	 			'encodingaeskey'=>$public_info->encodingaeskey,//填写加密用的EncodingAESKey，如接口为明文模式可忽略
     	 	);
     	 } else {
     	     echo "";
     	     exit;
     	 }
        //          $options = array(
        //                  'token'=>'BrandHead', //填写你设定的key
        //                  'encodingaeskey'=>'VxxxxRyP0S2R3IgdHDx29vmH4ihKuSEcp29YAEgWykq' //填写加密用的EncodingAESKey，如接口为明文模式可忽略
        //          );

         $weObj = new Wechat($options);
         $weObj->valid();//明文或兼容模式可以在接口验证通过后注释此句，但加密模式一定不能注释，否则会验证失败
         $type = $weObj->getRev()->getRevType();
         switch($type) {
            case Wechat::MSGTYPE_TEXT:
                /*$access_token = Account::getAccessToken($public_info->id);
                $temp = $weObj->checkAuth('','',$access_token);*/
                $weObj->text('稍后回复！')->reply();

            case Wechat::MSGTYPE_EVENT:
                 $eventArray = $weObj->getRev()->getRevEvent();
                 if (isset($eventArray['event'])) {
                     $fromUser = $weObj->getRevFrom();
                     $toUser = $weObj->getRevTo();
                     //获取品牌名
                     $brand_id = $public_info->brand_id;
                     $brand_name = Brand::where('id',$brand_id)->first()->brandname;
                     switch ($eventArray['event']) {
                         case 'subscribe':
                             //创建用户
                             //获取用户信息
                             $access_token = Account::getAccessToken($public_info->id);
                             token:
                             $weObj->checkAuth('','',$access_token);
                             $userInfo = $weObj->getUserInfo($fromUser);
                             //$weObj->text(json_encode($userInfo))->reply();
                             if ($userInfo) {
                                if(isset($userInfo['errcode'])){                                 
                                    if($userInfo['errcode'] == '40001'){
                                        //可能access_token过期，重新获取
                                        $access_token = $weObj->checkAuth($public_info->appid,$public_info->appsecret);
                                        //$weObj->text($userInfo['errcode'])->reply();
                                         if ($access_token) {
                                            $public_info->update(['access_token' => $access_token]);
                                            goto token;
                                        }
                                    }
                                }else{
                                    $weObj->text($userInfo['nickname'].' 您已经关注了我们！')->reply();
                                    $customer = new Customer;
                                     $customer->setTable($brand_name.'_customers');
                                     $old_customer = $customer->where('openid',$fromUser)->first();
                                     if ($old_customer) {
                                        $old_customer->setTable($brand_name.'_customers');
                                        $old_customer->update(['follow_weixin' => $public_info->name,'status' => 1,'nickname' => $userInfo['nickname'],'sex' => $userInfo['sex'],'headimgurl' => $userInfo['headimgurl'],'city' => $userInfo['city'],'province' => $userInfo['province'],'country' => $userInfo['country'],'openid' => $fromUser,'shop_id' => 1,'public_id' => $public_info->id,]);
                                     } else {
                                         $user = new Customer;
                                         $user->setTable($brand_name.'_customers');
                                         $user->follow_weixin = $public_info->name;
                                         $user->nickname = isset($userInfo['nickname']) ? $userInfo['nickname'] :'';
                                         $user->sex = isset($userInfo['sex']) ? $userInfo['sex'] :'';
                                         $user->headimgurl = isset($userInfo['headimgurl']) ? $userInfo['headimgurl'] : '';
                                         $user->openid = $fromUser;
                                         $user->shop_id = 1;//shop_id临时为1，
                                         $user->public_id = $public_info->id;
                                         $user->city = isset($userInfo['city']) ? $userInfo['city'] :'';
                                         $user->province = isset($userInfo['province']) ? $userInfo['province'] :'';
                                         $user->country = isset($userInfo['country']) ? $userInfo['country'] :'';
                                         $user->status = 1;
                                         $user->save();
                                     }
                                }
                             }
                             break;
                         case 'unsubscribe':
                             $leave_customer = new Customer;
                             $leave_customer->setTable($brand_name.'_customers');
                             $leave_user = $leave_customer->where('openid',$fromUser)->first();
                             if($leave_user) {
                                $leave_user->setTable($brand_name.'_customers');
                                $leave_user->update(['status' => 0]);
                             }
                             break;
                     }
                 }
                 break;

         }
    }
}
