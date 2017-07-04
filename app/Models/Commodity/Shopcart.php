<?php
namespace App\Models\Commodity;
use App\Models\BaseModel;
class Shopcart extends BaseModel
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
         'customer_id','shop_id','commodity_id','sku_id','count','status','order_id'
    ];



}