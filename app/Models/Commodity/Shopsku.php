<?php
namespace App\Models\Commodity;
use App\Models\BaseModel;
class Shopsku extends BaseModel
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
         'commodity_id','shop_id','sku_id','quantity','saled_count','status'
    ];



}