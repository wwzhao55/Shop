<?php
namespace App\Models\Coupon;
use App\Models\BaseModel;
class Couponlist extends BaseModel
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
         'coupon_id','customer_id','number','used'
    ];



}