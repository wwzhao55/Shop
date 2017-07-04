<?php
namespace App\Models\Coupon;
use App\Models\BaseModel;
class Coupon extends BaseModel
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
         'name','sum','number','shop_id','commodity_category','status','description','use_condition','validity_start','validity_end','gettimes','allow_share','share_introduce','used_num','person_times','quantity'
    ];



}