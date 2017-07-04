<?php
namespace App\Models\Weixin;
use App\Models\BaseModel;
use App\libraries\Wechat;
use Config;
use DB;
class Account extends BaseModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'public_number';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = array('name','brand_id', 'appid', 'appsecret', 'access_token', 'token', 'encodingaeskey', 'service_type_info','func_info', 'originalid','head_img','qrcode_url','weixin_id', 'status','menu','msg_order','msg_pay','msg_refund','subscribe_text');

    public static function getAccessToken($id) {
        $weixinInfo = self::find($id);
        $accessToken = '';
        $accessTokenTime = 0;
        if ($weixinInfo->access_token) {
            //有access_token
            $accessToken = $weixinInfo->access_token;
            $accessTokenTime = strtotime($weixinInfo->updated_at);
            $accessTokenTime += 3600;
            if( $accessTokenTime < time() ){
                //过期
                $options = array(
                        'token'=>$weixinInfo->token, //填写你设定的key
                        'encodingaeskey'=>$weixinInfo->encodingaeskey,  //填写加密用的EncodingAESKey
                        'appid'=>$weixinInfo->appid, //填写高级调用功能的app id
                        'appsecret'=>$weixinInfo->appsecret,//填写高级调用功能的密钥
                        'partnerid'=>'', //财付通商户身份标识
                        'partnerkey'=>'', //财付通商户权限密钥Key
                        'paysignkey'=>'' //商户签名密钥Key
                );
                $weiObj = new Wechat($options);
                $accessToken = $weiObj->checkAuth($weixinInfo->appid,$weixinInfo->appsecret);
                if ($accessToken) {
                    $weixinInfo->update(['access_token' => $accessToken]);
                    return $accessToken;
                }
            }else{
                    return $accessToken;
            }
        }else{
            //没有access_token
            $options = array(
                    'token'=>$weixinInfo->token, //填写你设定的key
                    'encodingaeskey'=>$weixinInfo->encodingaeskey,  //填写加密用的EncodingAESKey
                    'appid'=>$weixinInfo->appid, //填写高级调用功能的app id
                    'appsecret'=>$weixinInfo->appsecret,//填写高级调用功能的密钥
                    'partnerid'=>'', //财付通商户身份标识
                    'partnerkey'=>'', //财付通商户权限密钥Key
                    'paysignkey'=>'' //商户签名密钥Key
            );
            $weiObj = new Wechat($options);
            $accessToken = $weiObj->checkAuth($weixinInfo->appid,$weixinInfo->appsecret);
            if ($accessToken) {
                $weixinInfo->update(['access_token' => $accessToken]);
                return $accessToken;
            }
        }
    }
    /**
     *
     */
    public function getMenu($id) {
        $weixinInfo = $this->find($id);
        if ($weixinInfo) {
            $accessToken = $this->getAccessToken($id);
            $weiObj = new Wechat(array());
            $weiObj->checkAuth($weixinInfo->appid, $weixinInfo->appsecret, $accessToken);
            $data = $weiObj->getMenu();

            dd($data);
            return $data;
        }
        return array();
    }

    /**
     *
     */
    public function deleteMenu($id='1') {
        $weixinInfo = $this->find($id);
        $reule = false;
        if ($weixinInfo) {
            $accessToken = $this->getAccessToken($id);
            $weiObj = new Wechat(array());
            $weiObj->checkAuth($weixinInfo->appid, $weixinInfo->appsecret, $accessToken);
            $reule = $weiObj->deleteMenu();

        }
        return $reule;
    }

    /**
     *
     */
    public function createMenu($data, $id='1') {
        $weixinInfo = $this->find($id);
        $reule = false;
        if ($weixinInfo) {
            $accessToken = $this->getAccessToken($id);
            $weiObj = new Wechat(array());
            $weiObj->checkAuth($weixinInfo->appid, $weixinInfo->appsecret, $accessToken);
            //         dd($weiObj::json_encode($data));
            $reule = $weiObj->createMenu($data);
        }
        return $reule;
    }

    /**
     *
     */
    public function uuid()
    {
        $chars = md5(uniqid(mt_rand(), true));//mt_rand:生成更好的随机数; uniqid:函数基于以微秒计的当前时间
        $uuid  = substr($chars,0,8) . '_';
        $uuid .= substr($chars,8,4) . '_';
        $uuid .= substr($chars,12,4) . '_';
        $uuid .= substr($chars,16,4) . '_';
        $uuid .= substr($chars,20,12);
        return $uuid;
    }

    public static function https_request($url, $data = null)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        if (!empty($header)) {
            curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        }
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        if (!empty($data)){
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($curl);
        curl_close($curl);
        return $output;
    }

    public function uploadImg_http_request($url, $data = null){
        $curl = curl_init();
        if (class_exists('\CURLFile')) {
            $field = array('media' => new \CURLFile(realpath($data['media'])));
        } else {
            $field = array('media' =>  '@' . realpath($data['media']));
        }
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        if (!empty($data)){
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $field);
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($curl);
        curl_close($curl);
        return $output;
    }

    //微信上图片
    public function uploadToWeixin($path, $publicId = 1) {
        $options = array();
        $weixin = new Wechat($options);
        $access_token = $this->getAccessToken($publicId);//获取token
        $weixin->checkAuth('','',$access_token); //设置token
        $data = array("media"=>"$path");
        // type 类型：图片:image 语音:voice 视频:video 缩略图:thumb
        //品装上传文件
        $type = 'image';
        //              $result = $weixin->uploadMedia($data,$type);
        $url = "http://file.api.weixin.qq.com/cgi-bin/media/upload?access_token=$access_token&type=$type";
        $result = $this->uploadImg_http_request($url, $data);
        if ($result) {
            $json = json_decode($result,true);
            if (!$json || !empty($json['errcode'])) {
                $this->errCode = $json['errcode'];
                $this->errMsg = $json['errmsg'];
                return false;
            }
            return $json;
        }
        return false;
    }

    //微信上图文
    public function uploadGraphic($thumb_media_id, $title, $content_source_url, $content, $digest, $show_cover_pic = '1', $publicId = 1){
        $data['articles'] = array();
        $data['articles']['thumb_media_id'] = $thumb_media_id;
        $data['articles']['author'] = '';
        $data['articles']['title'] = $title;
        $data['articles']['content_source_url'] = $content_source_url;
        $data['articles']['content'] = $content;
        $data['articles']['digest'] = $digest;
        $data['articles']['show_cover_pic'] = $show_cover_pic;
        $wechat = new Wechat(array());
        $accessToken = $this->getAccessToken($publicId);//获取token
        $wechat->checkAuth('','',$accessToken); //设置token
        $result = $wechat->uploadArticles($data);
        require $result;
    }

    /**
     * 图片自动上传功能 （公众号中快速回复图片3日过期）
     */

    public static function timingTask(){
        //获取配置
        $timingTask = Config::get('timingTask');
        $timingTask = empty($timingTask) ? array('UploadImg') : $timingTask;
        if ($timingTask) {
            foreach ($timingTask as $key=>$val){
                switch ($val) {
                    case 'UploadImg':
                        $account = new Account();
                        //获取图片文件
                        $time = date('Y-m-d H:i:s',time()-259200);
                        $where = '';
                        $where .= " DATE_FORMAT(updated_at,'%Y-%m-%d %H:%i:%s') <= '{$time}'";
                        $files = UploadFile::whereRaw($where)->get();
                        if ($files->count()) {
                            foreach ($files as $file_key=>$file_val) {
                                $resul = $account->uploadToWeixin(public_path().$file_val->image,$file_val->publicid);
                                $oldMedia_id = $file_val->mediaid;
                                if($resul){
                                    //更新自动回复中的图片mediaid
                                    Answer::where('mediaid',$oldMedia_id)->update(array('mediaid'=>$resul['media_id']));
                                    $fileObj = UploadFile::find($file_val->id);
                                    if ($fileObj) {
                                        $fileObj->mediaid = $resul['media_id'];
                                        $fileObj->save();
                                    }
                                }
                            }
                            return '更新成功';
                        } else {
                            return '未找到符合条件的数据';
                        }
                        break;
                }
            }
        }
        return '操作失败';
    }
}



