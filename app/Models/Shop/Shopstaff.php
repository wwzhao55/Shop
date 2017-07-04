<?php
namespace App\Models\Shop;
use App\Models\BaseModel,App\Models\Shop\Shopinfo;
class Shopstaff extends BaseModel
{
    
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'shopstaff';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
         'uid','shop_id','name','status','phone',
    ];

    public static function getShopstaff($brand_id){
        $shops = Shopinfo::where('brand_id',$brand_id)->lists('id');
        $shopstaff = self::whereIn('shop_id',$shops)->get();
        return $shopstaff;
    }
}