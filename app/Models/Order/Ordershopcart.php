<?php
namespace App\Models\Order;
use App\Models\BaseModel;
class Ordershopcart extends BaseModel
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
         'shopcart_id','order_id'
    ];

}