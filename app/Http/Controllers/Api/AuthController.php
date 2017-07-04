<?php 
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Log;
use App\Models\Weixin\Openwx,App\Models\Weixin\Account;
include_once(app_path("libraries/openwx/wxBizMsgCrypt.php"));
//include_once("wxBizMsgCrypt.php");

class AuthController extends Controller{
	public function postMessage(Request $request){
		//接收预授权码
		Log::info('wxauth',['ticket'=>$request->all(),'input'=>file_get_contents('php://input')]);
		$timeStamp = $request->timestamp;
		$nonce = $request->nonce;
		$msg_signature = $request->msg_signature;
		
		$encryptMsg = file_get_contents('php://input');

		$xml_tree = new \DOMDocument();
		$xml_tree->loadXML($encryptMsg);
        $array_a = $xml_tree->getElementsByTagName('AppId');
		$array_e = $xml_tree->getElementsByTagName('Encrypt');
        $appid = $array_a->item(0)->nodeValue;
		$encrypt = $array_e->item(0)->nodeValue;

        $weixin = Openwx::where('type',0)->where('appid',$appid)->first();
        $encodingAesKey = $weixin->encodingAesKey;
        $token = $weixin->token;
        $pc = new \WXBizMsgCrypt($token, $encodingAesKey, $appid);

		$format = "<xml><ToUserName><![CDATA[toUser]]></ToUserName><Encrypt><![CDATA[%s]]></Encrypt></xml>";
		$from_xml = sprintf($format, $encrypt);

		// 第三方收到公众号平台发送的消息
        $msg = '';
        $errCode = $pc->decryptMsg($msg_signature, $timeStamp, $nonce, $from_xml, $msg);
        if($errCode == 0) {

            $xml = new \DOMDocument();
            $xml->loadXML($msg);
            $array_e = $xml->getElementsByTagName('ComponentVerifyTicket');
            $component_verify_ticket = $array_e->item(0)->nodeValue;
            $weixin->component_verify_ticket = $component_verify_ticket;

            $get_token_url = "https://api.weixin.qq.com/cgi-bin/component/api_component_token";  
            $ticket_data = array('component_appid' => $appid,'component_appsecret' => $weixin->appsecret,'component_verify_ticket' => $component_verify_ticket);  
            $jsonStr = json_encode($ticket_data);  
            $returnContent = $this->http_post_json($get_token_url, $jsonStr);  
            $component_access_token = json_decode($returnContent) -> component_access_token; 
            $weixin->component_access_token = $component_access_token;

            $url2 = 'https://api.weixin.qq.com/cgi-bin/component/api_create_preauthcode?component_access_token='.$component_access_token;  
            $pre_data = array('component_appid' => $appid);  
            $jsonStr2 = json_encode($pre_data);  
            $returnContent2 = $this->http_post_json($url2, $jsonStr2);  
            $pre_auth_code = json_decode($returnContent2) -> pre_auth_code;
            $weixin->pre_auth_code = $pre_auth_code;
            $weixin->save();
            echo 'success';        
        }else{
            //logResult('解密后失败：'.$errCode);
            //$res = M('weixin_account')->where(array('appId'=>$this->component_appid))->save(array('text'=>'fasle'));
            Log::info('wxauth_err',['wxauthmsg'=>$errCode]);
        }
	}

    private function http_post_json($url, $jsonStr) {  
      $ch = curl_init();  
      curl_setopt($ch, CURLOPT_POST, 1);  
      curl_setopt($ch, CURLOPT_URL, $url);  
      curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonStr);  
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); //不验证证书，这一点很重要因为是HTTPS请求，  
      curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false); //不验证证书  
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);  
      curl_setopt($ch, CURLOPT_HTTPHEADER, array(  
          'Content-Type: application/json; charset=utf-8',  
          'Content-Length: ' . strlen($jsonStr)  
        )  
      );  
      $response = curl_exec($ch);  
      $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);  
      return $response;  
    }  

    public function anyAuthback($id=0,Request $request){
        Log::info('wxauth_back',['auth'=>$request->all(),'input'=>file_get_contents('php://input')]);
        $auth_code = $request->auth_code;
        $weixin = Openwx::find($id);

        $get_token_url = "https://api.weixin.qq.com/cgi-bin/component/api_query_auth?component_access_token=".$weixin->component_access_token;  
        $post_data = array('component_appid' => $weixin->appid,'authorization_code' => $auth_code);  
        $jsonStr = json_encode($post_data);  
        $returnContent = $this->http_post_json($get_token_url, $jsonStr);  
        $returnContent = json_decode($returnContent)->authorization_info;
        if(Openwx::where('authorizer_appid',$returnContent->authorizer_appid)->count()){
            $auth_weixin = Openwx::where('authorizer_appid',$returnContent->authorizer_appid)->first();
        }else{
            $auth_weixin = new Openwx;
        }
        $auth_weixin->type=1;
        $auth_weixin->authorizer_appid = $returnContent->authorizer_appid;
        $auth_weixin->authorizer_access_token = $returnContent->authorizer_access_token;
        $auth_weixin->authorizer_refresh_token = $returnContent->authorizer_refresh_token;
        $auth_weixin->save();
        //获取公众号基本信息
        $get_account_url = "https://api.weixin.qq.com/cgi-bin/component/api_get_authorizer_info?component_access_token=".$weixin->component_access_token;
        $account_data = array('component_appid' => $weixin->appid,'authorizer_appid' => $auth_weixin->authorizer_appid);  
        $accountStr = json_encode($account_data);  
        $accountContent = $this->http_post_json($get_account_url, $accountStr); 
        $authorizer_info = json_decode($accountContent)->authorizer_info;
        $authorization_info = json_decode($accountContent)->authorization_info;
        if(Account::where('appid',$authorization_info->authorizer_appid)->count()){
            $new_account = Account::where('appid',$authorization_info->authorizer_appid)->first();
        }else{
            $new_account = new Account;
        }
        $new_account->name = $authorizer_info->nick_name;
        $new_account->head_img = $authorizer_info->head_img;
        $new_account->service_type_info = $authorizer_info->service_type_info->id;
        $new_account->func_info = $authorization_info->func_info;
        $new_account->originalid = $authorizer_info->user_name;
        $new_account->weixin_id = $authorizer_info->alias;
        $new_account->qrcode_url = $authorizer_info->qrcode_url;
        $new_account->save();
        //设置授权方的选项信息--开启地理位置上报
        $get_option_url = "https://api.weixin.qq.com/cgi-bin/component/ api_set_authorizer_option?component_access_token=".$weixin->component_access_token;
        $option_data = array('component_appid' => $weixin->appid,'authorizer_appid' => $auth_weixin->authorizer_appid,'option_name'=>'location_report','option_value'=>1);  
        $optionStr = json_encode($option_data);  
        $optionContent = $this->http_post_json($get_option_url, $optionStr); 
        $optionContent = json_decode($optionContent);
        if($optionContent->errCode != 0){
            echo "开启地理位置上报失败！";
        }
    }
}