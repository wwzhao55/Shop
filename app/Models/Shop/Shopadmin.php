<?php
namespace App\Models\Shop;
use App\Models\BaseModel;
class Shopadmin extends BaseModel
{
    
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'shopadmin';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
         'uid','name','brand_id','status','phone','email'
    ];

    

}