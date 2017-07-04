<?php
namespace App\Models\Commodity;
use App\Models\BaseModel;
class Commodityimg extends BaseModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table ;

    protected $fillable = ['shop_id','commodity_id','img_src','status','order'];
}