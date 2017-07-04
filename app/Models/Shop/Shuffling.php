<?php
namespace App\Models\Shop;
use Illuminate\Database\Eloquent\Model;
use App\Models\BaseModel;
class Shuffling extends BaseModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'shuffling';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
         'brand_id','shop_id','img_src','http_src','status','order'
    ];



}
