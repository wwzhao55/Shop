<?php
namespace App\Models\Shop;
use Illuminate\Database\Eloquent\Model;
use App\Models\BaseModel;
class Shopinfo extends BaseModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'shopinfo';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    
    protected $fillable = [
         'shopname','brand_id','shoplogo','open_weishop','contacter_name','contacter_phone','contacter_email','contacter_QQ','shop_province','shop_city','shop_district','shop_address_detail','latitude','longitude','special','status','status_at','customer_service_phone','open_at','close_at'
    ];

    public function getShopCount($brand_id){
        return $this->where('brand_id',$brand_id)->count();
    }
    public function getNewShopCount($start_date,$end_date,$brand_id){
        $shopCount = $this->whereBetween('created_at',[$start_date,$end_date])->where('brand_id','=',$brand_id)->count();
        return $shopCount;
    }



}
