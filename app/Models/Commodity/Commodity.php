<?php
namespace App\Models\Commodity;
use App\Models\BaseModel;
class Commodity extends BaseModel
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
         'shop_id','commodity_name','category_id','category_name','group_id','group_name','tag_id','PV','UV','express_price','produce_area1','produce_area2','main_img','sku_info','status','description','brief_introduction','type','is_recommend','use_express_template','has_vip_discount','limit_count','saled_count','base_price','is_all_shop'
    ];



}