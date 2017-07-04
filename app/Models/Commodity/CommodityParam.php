<?php
namespace App\Models\Commodity;
use App\Models\BaseModel;
class CommodityParam extends BaseModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'commodity_param';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
         'category_id','name'
    ];



}