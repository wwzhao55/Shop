<?php
namespace App\Models\Brand;
use App\Models\BaseModel;
class Brand extends BaseModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'brand';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
         'uid','brandname','logo','main_business','contacter_name','contacter_phone','contacter_email','contacter_QQ','company_name','company_province','company_city','company_district','company_address_detail','status','weixin_shop_num','weixin_api_key','weixin_staff_account','weixin_apiclient_cert','weixin_apiclient_key','zhifubao_pid','zhifubao_appid','zhifubao_public_key','zhifubao_private_key',
    ];

    public static function getNewBrand($start_date,$end_date){
        $newBrands = Self::whereBetween('created_at',[$start_date,$end_date])->count();
        return $newBrands;
    }

    public static function getAllBrandName(){
        $brands = Self::all()->toArray();
        $brandNameArray = array();
        foreach($brands as $brand){
            array_push($brandNameArray,$brand['brandname']);
        }
        return $brandNameArray;
    }

}
