<?php
namespace App\Models\Shop;
use App\Models\BaseModel;
class Staff extends BaseModel
{
    
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'shop_id','staff_name','staff_phone','status',
    ];

    

}