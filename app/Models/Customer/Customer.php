<?php
namespace App\Models\Customer;
use App\Models\BaseModel;
class Customer extends BaseModel
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table ;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
         'uid','shop_id','openid','status','email','phone','nickname','follow_weixin','sex','headimgurl','public_id','is_vip','city','province','country'
    ];

    public function getCustomerCount(){
        return $this->where('is_vip',1)->count();
    }

    public function getFansCount(){
        return $this->where('is_vip',0)->count();
    }

    public function getNewCustomerCount($start_date,$end_date){
        $customerCount = $this->where('is_vip',1)->whereBetween('created_at',[$start_date,$end_date])->count();
        return $customerCount;
    }

    public function getNewFansCount($start_date,$end_date){
        $customerCount = $this->where('is_vip',0)->whereBetween('created_at',[$start_date,$end_date])->count();
        return $customerCount;
    }

    public function getNewShopCustomerCount($start_date,$end_date,$shop_id){
        $customerCount = $this->where('is_vip',1)->whereBetween('created_at',[$start_date,$end_date])->where('shop_id',$shop_id)->count();
        return $customerCount;
    }

    public function getNewShopFansCount($start_date,$end_date,$shop_id){
        $customerCount = $this->where('is_vip',0)->whereBetween('created_at',[$start_date,$end_date])->where('shop_id',$shop_id)->count();
        return $customerCount;
    }

    public function getShopCustomerCount($shop_id){
        $customerCount = $this->where('is_vip',1)->where('shop_id',$shop_id)->count();
        return $customerCount;
    }
    public function getShopFansCount($shop_id){
        $customerCount = $this->where('is_vip',0)->where('shop_id',$shop_id)->count();
        return $customerCount;
    }
}
