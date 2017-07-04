<?php
namespace App\Models\Commodity;
use App\Models\BaseModel;
class Skulist extends BaseModel
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
         'commodity_id','commodity_sku','price','old_price','quantity','skulist'
    ];



}