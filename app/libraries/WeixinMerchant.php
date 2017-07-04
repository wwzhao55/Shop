<?php namespace App\libraries;
use App\Models\weixin\Account;
use Auth;
class WeixinMerchant{
    public static function getToken() {
        return Account::getAccessToken(Auth::user()->new_public_id);
    }
    public static function addGoods($data=[]){

        $catelist = json_decode(Account::https_request("https://api.weixin.qq.com/merchant/create?access_token=".self::getToken(), $data),true);
        if($catelist['errcode']){
            return ['error'=>true,'msg'=>$catelist['errmsg']];
        }else{
            return ['error'=>false,'data'=>$catelist['product_id']];
        }
    }
    public static function deleteGoods($product_id){

        $catelist = json_decode(Account::https_request("https://api.weixin.qq.com/merchant/del?access_token=".self::getToken(), json_encode(["product_id"=>$product_id],JSON_UNESCAPED_UNICODE)),true);
        if($catelist['errcode']){
            return ['error'=>true,'msg'=>$catelist['errmsg']];
        }else{
            return ['error'=>false,'data'=>""];
        }
    }
    public static function updateGoods($data){

        $catelist = json_decode(Account::https_request("https://api.weixin.qq.com/merchant/update?access_token=".self::getToken(), $data),true);
        if($catelist['errcode']){
            return ['error'=>true,'msg'=>$catelist['errmsg']];
        }else{
            return ['error'=>false,'data'=>""];
        }
    }
    public static function getGoodsForId($product_id,$username=''){
        
        if($username){
            $public = Account::where('original_id','=',$username)->first();//dd($public->access_token);
            $token = $public->access_token;
        }else{
            $token = self::getToken();
        }
        $catelist = json_decode(Account::https_request("https://api.weixin.qq.com/merchant/get?access_token=".$token, json_encode(["product_id" =>$product_id],JSON_UNESCAPED_UNICODE)),true);
        if($catelist['errcode']){
            return ['error'=>true,'msg'=>$catelist['errmsg']];
        }else{
            return ['error'=>false,'data'=>$catelist['product_info']];
        }
    }
    public static function getGoodsForStatus($status,$username=''){  // 0 全部 1.上架  2.下架
        
        if($username){
            $public = Account::where('original_id','=',$username)->first();//dd($public->access_token);
            $token = $public->access_token;
        }else{
            $token = self::getToken();
        }
        $catelist = json_decode(Account::https_request("https://api.weixin.qq.com/merchant/getbystatus?access_token=".$token, json_encode(["status" =>$status],JSON_UNESCAPED_UNICODE)),true);
        if($catelist['errcode']){
            return ['error'=>true,'msg'=>$catelist['errmsg']];
        }else{
            return ['error'=>false,'data'=>$catelist['products_info']];
        }
    }
    public static function modProductStatus($product_id,$status){  //修改上下架状态  1.上架  0.下架
        $data = [
            "product_id" => $product_id,
            "status" => $status
        ];
        $catelist = json_decode(Account::https_request("https://api.weixin.qq.com/merchant/modproductstatus?access_token=".self::getToken(), json_encode($data,JSON_UNESCAPED_UNICODE)),true);
        if($catelist['errcode']){
            return ['error'=>true,'msg'=>$catelist['errmsg']];
        }else{
            return ['error'=>false,'data'=>""];
        }
    }

    public static function getCate($cateid=0,$username=""){
        
        if($username){
            $public = Account::where('original_id','=',$username)->first();//dd($public->access_token);
            $token = $public->access_token;
        }else{
            $token = self::getToken();
        }
        $catelist = json_decode(Account::https_request("https://api.weixin.qq.com/merchant/category/getsub?access_token=".$token, json_encode(["cate_id"=>$cateid],JSON_UNESCAPED_UNICODE)),true);
        if($catelist['errcode']){
            return ['error'=>true,'msg'=>$catelist['errmsg']];
        }else{
            return ['error'=>FALSE,'data'=>$catelist['cate_list']];
        }
    }

    public static function addStock($product_id,$skuinfo,$quantity){
        $data = [
            "product_id" => $product_id,
            "sku_info" => $skuinfo,
            "quantity" => $quantity
        ];
        $catelist = json_decode(Account::https_request("https://api.weixin.qq.com/merchant/stock/add?access_token=".self::getToken(), json_encode($data,JSON_UNESCAPED_UNICODE)),true);
        if($catelist['errcode']){
            return ['error'=>true,'msg'=>$catelist['errmsg']];
        }else{
            return ['error'=>FALSE,'data'=>""];
        }
    }
    public static function reduceStock($product_id,$skuinfo,$quantity){
        $data = [
            "product_id" => $product_id,
            "sku_info" => $skuinfo,
            "quantity" => $quantity
        ];
        $catelist = json_decode(Account::https_request("https://api.weixin.qq.com/merchant/stock/add?access_token=".self::getToken(), json_encode($data,JSON_UNESCAPED_UNICODE)),true);
        if($catelist['errcode']){
            return ['error'=>true,'msg'=>$catelist['errmsg']];
        }else{
            return ['error'=>FALSE,'data'=>""];
        }
    }

    public static function addTemplate($data){
        $catelist = json_decode(Account::https_request("https://api.weixin.qq.com/merchant/express/add?access_token=".self::getToken(), json_encode($data,JSON_UNESCAPED_UNICODE)),true);
        if($catelist['errcode']){
            return ['error'=>true,'msg'=>$catelist['errmsg']];
        }else{
            return ['error'=>FALSE,'data'=>$catelist['template_id']];
        }
    }

    public static function delTemplate($template_id){
        $catelist = json_decode(Account::https_request("https://api.weixin.qq.com/merchant/express/del?access_token=".self::getToken(), json_encode(["template_id" => $template_id],JSON_UNESCAPED_UNICODE)),true);
        if($catelist['errcode']){
            return ['error'=>true,'msg'=>$catelist['errmsg']];
        }else{
            return ['error'=>FALSE,'data'=>""];
        }
    }

    public static function editTemplate($data){
        $catelist = json_decode(Account::https_request("https://api.weixin.qq.com/merchant/express/update?access_token=".self::getToken(), json_encode($data,JSON_UNESCAPED_UNICODE)),true);
        if($catelist['errcode']){
            return ['error'=>true,'msg'=>$catelist['errmsg']];
        }else{
            return ['error'=>FALSE,'data'=>""];
        }
    }
    public static function getTemplate($template_id){
        $catelist = json_decode(Account::https_request("https://api.weixin.qq.com/merchant/express/getbyid?access_token=".self::getToken(), json_encode(["template_id" => $template_id],JSON_UNESCAPED_UNICODE)),true);
        if($catelist['errcode']){
            return ['error'=>true,'msg'=>$catelist['errmsg']];
        }else{
            return ['error'=>FALSE,'data'=>$catelist['template_info']];
        }
    }
    public static function getAllTemplate(){
        $catelist = json_decode(Account::https_request("https://api.weixin.qq.com/merchant/express/getall?access_token=".self::getToken()),true);
        if($catelist['errcode']){
            return ['error'=>true,'msg'=>$catelist['errmsg']];
        }else{
            return ['error'=>FALSE,'data'=>$catelist['templates_info']];
        }
    }
    public static function addGroup($group_name,$product_list=[]){

        $data = [
            "group_detail" => [
                "group_name" => $group_name,
                "product_list" => $product_list,
                ]
        ];
        $catelist = json_decode(Account::https_request("https://api.weixin.qq.com/merchant/group/add?access_token=".self::getToken(),json_encode($data,JSON_UNESCAPED_UNICODE)),true);
        if($catelist['errcode']){
            return ['error'=>true,'msg'=>$catelist['errmsg']];
        }else{
            return ['error'=>FALSE,'data'=>$catelist['group_id']];
        }
    }
    public static function editGroup($group_id,$group_name){

        $data = [
            "group_name" => $group_name,
            "group_id" => $group_id,
        ];
        $catelist = json_decode(Account::https_request("https://api.weixin.qq.com/merchant/group/propertymod?access_token=".self::getToken(),json_encode($data,JSON_UNESCAPED_UNICODE)),true);
        if($catelist['errcode']){
            return ['error'=>true,'msg'=>$catelist['errmsg']];
        }else{
            return ['error'=>FALSE,'data'=>""];
        }
    }
    public static function editGroupProduct($group_id,$product){

        $data = [
            "product" => $product,
            // [
            //      "product_id"=>"pDF3iY-CgqlAL3k8Ilz-6sj0UYpk",
            //      "mod_action"=>1     修改操作(0-删除, 1-增加)
            // ],
            // [
            //      "product_id"=>"pDF3iY-CgqlAL3k8Ilz-6sj0UYpk",
            //      "mod_action"=>0
            // ]
            "group_id" => $group_id,
        ];
        $catelist = json_decode(Account::https_request("https://api.weixin.qq.com/merchant/group/productmod?access_token=".self::getToken(),json_encode($data,JSON_UNESCAPED_UNICODE)),true);
        if($catelist['errcode']){
            return ['error'=>true,'msg'=>$catelist['errmsg']];
        }else{
            return ['error'=>FALSE,'data'=>""];
        }
    }
    public static function delGroup($group_id){

        $data = [
            "group_id" => $group_id,
        ];
        $catelist = json_decode(Account::https_request("https://api.weixin.qq.com/merchant/group/del?access_token=".self::getToken(),json_encode($data,JSON_UNESCAPED_UNICODE)),true);
        if($catelist['errcode']){
            return ['error'=>true,'msg'=>$catelist['errmsg']];
        }else{
            return ['error'=>FALSE,'data'=>""];
        }
    }
    public static function getAllGroup(){

        $catelist = json_decode(Account::https_request("https://api.weixin.qq.com/merchant/group/getall?access_token=".self::getToken()),true);
        if($catelist['errcode']){
            return ['error'=>true,'msg'=>$catelist['errmsg']];
        }else{
            return ['error'=>FALSE,'data'=>$catelist['groups_detail']];
        }
    }
    public static function getGroupById($groupid){

        $catelist = json_decode(Account::https_request("https://api.weixin.qq.com/merchant/group/getbyid?access_token=".self::getToken(), json_encode(["group_id"=>$groupid],JSON_UNESCAPED_UNICODE)),true);
        if($catelist['errcode']){
            return ['error'=>true,'msg'=>$catelist['errmsg']];
        }else{
            return ['error'=>FALSE,'data'=>$catelist['group_detail']];
        }
    }

    public static function getProperties($cateid=0){

        $catelist = json_decode(Account::https_request("https://api.weixin.qq.com/merchant/category/getproperty?access_token=".self::getToken(), json_encode(["cate_id"=>intval($cateid)],JSON_UNESCAPED_UNICODE)),true);
        if($catelist['errcode']){
            return ['error'=>true,'msg'=>$catelist['errmsg']];
        }else{
            return ['error'=>FALSE,'data'=>$catelist['properties']];
        }
    }
    public static function getSku($cateid=0){

        $catelist = json_decode(Account::https_request("https://api.weixin.qq.com/merchant/category/getsku?access_token=".self::getToken(), json_encode(["cate_id"=>intval($cateid)],JSON_UNESCAPED_UNICODE)),true);
        if($catelist['errcode']){
            return ['error'=>true,'msg'=>$catelist['errmsg']];
        }else{
            return ['error'=>FALSE,'data'=>$catelist['sku_table']];
        }
    }
    public static function addShelf($shelf_name,$shelfdata,$shelf_banner=""){
        $data = [
            "shelf_name" => $shelf_name,
            "shelf_data" => $shelfdata,
            "shelf_banner" => $shelf_banner,
        ];
        $catelist = json_decode(Account::https_request("https://api.weixin.qq.com/merchant/shelf/add?access_token=".self::getToken(), json_encode($data,JSON_UNESCAPED_UNICODE)),true);
        if($catelist['errcode']){
            return ['error'=>true,'msg'=>$catelist['errmsg']];
        }else{
            return ['error'=>FALSE,'data'=>$catelist['shelf_id']];
        }
    }
    public static function editShelf($shelf_id,$shelf_name,$shelfdata,$shelf_banner=""){
        $data = [
            "shelf_id" => $shelf_id,
            "shelf_name" => $shelf_name,
            "shelf_data" => $shelfdata,
            "shelf_banner" => $shelf_banner,
        ];
        $catelist = json_decode(Account::https_request("https://api.weixin.qq.com/merchant/shelf/mod?access_token=".self::getToken(), json_encode($data,JSON_UNESCAPED_UNICODE)),true);
        if($catelist['errcode']){
            return ['error'=>true,'msg'=>$catelist['errmsg']];
        }else{
            return ['error'=>FALSE,'data'=>""];
        }
    }
    public static function delShelf($shelf_id){
        $data = [
            "shelf_id" => $shelf_id,
        ];
        $catelist = json_decode(Account::https_request("https://api.weixin.qq.com/merchant/shelf/del?access_token=".self::getToken(), json_encode($data,JSON_UNESCAPED_UNICODE)),true);
        if($catelist['errcode']){
            return ['error'=>true,'msg'=>$catelist['errmsg']];
        }else{
            return ['error'=>FALSE,'data'=>""];
        }
    }
    public static function getAllShelf(){
        $catelist = json_decode(Account::https_request("https://api.weixin.qq.com/merchant/shelf/getall?access_token=".self::getToken()),true);
        if($catelist['errcode']){
            return ['error'=>true,'msg'=>$catelist['errmsg']];
        }else{
            return ['error'=>FALSE,'data'=>$catelist['shelves']];
        }
    }
    public static function getShelfById($shelf_id){
        $data = [
            "shelf_id" => $shelf_id,
        ];
        $catelist = json_decode(Account::https_request("https://api.weixin.qq.com/merchant/shelf/getbyid?access_token=".self::getToken(), json_encode($data,JSON_UNESCAPED_UNICODE)),true);
        if($catelist['errcode']){
            return ['error'=>true,'msg'=>$catelist['errmsg']];
        }else{
            return ['error'=>FALSE,'data'=>$catelist['shelf_info']];
        }
    }
    public static function getOrderById($order_id,$username=''){

        $data = [
            "order_id" => $order_id,
        ];
        if($username){
            $public = Account::where('original_id','=',$username)->first();
            $token = $public->access_token;
        }else{
            $token = self::getToken();
        }
        $catelist = json_decode(Account::https_request("https://api.weixin.qq.com/merchant/order/getbyid?access_token=".$token, json_encode($data,JSON_UNESCAPED_UNICODE)),true);
        if($catelist['errcode']){
            return ['error'=>true,'msg'=>$catelist['errmsg']];
        }else{
            return ['error'=>FALSE,'data'=>$catelist['order']];
        }
    }
    public static function getOrderByFilter($status=null,$begintime=null,$endtime=null){
        $data = [];
        if($status){
            $data['status'] = $status;
        }
        if($begintime && $endtime){
            $data['begintime'] = $begintime;
            $data['endtime'] = $endtime;
        }
        $catelist = json_decode(Account::https_request("https://api.weixin.qq.com/merchant/order/getbyfilter?access_token=".self::getToken(), json_encode($data,JSON_UNESCAPED_UNICODE)),true);
        if($catelist['errcode']){
            return ['error'=>true,'msg'=>$catelist['errmsg']];
        }else{
            return ['error'=>FALSE,'data'=>$catelist['order_list']];
        }
    }
    public static function setOrderDelivery($data){
        $catelist = json_decode(Account::https_request("https://api.weixin.qq.com/merchant/order/setdelivery?access_token=".self::getToken(), json_encode($data,JSON_UNESCAPED_UNICODE)),true);
        if($catelist['errcode']){
            return ['error'=>true,'msg'=>$catelist['errmsg']];
        }else{
            return ['error'=>FALSE,'data'=>''];
        }
    }
    public static function orderClose($order_id){
        $data = [
            "order_id" => $order_id,
        ];
        $catelist = json_decode(Account::https_request("https://api.weixin.qq.com/merchant/order/close?access_token=".self::getToken(), json_encode($data,JSON_UNESCAPED_UNICODE)),true);
        if($catelist['errcode']){
            return ['error'=>true,'msg'=>$catelist['errmsg']];
        }else{
            return ['error'=>FALSE,'data'=>''];
        }
    }

    public static function uploadImg($filename){
        if($filename){
            $data = file_get_contents(storage_path()."/uploads/". $filename);
            $catelist = json_decode(Account::https_request("https://api.weixin.qq.com/merchant/common/upload_img?access_token=".self::getToken()."&filename=$filename",$data),true);
            if($catelist['errcode']){
                return ['error'=>true,'msg'=>$catelist['errmsg']];
            }else{
                return $catelist['image_url'];
            }
        }
    }
}
?>
