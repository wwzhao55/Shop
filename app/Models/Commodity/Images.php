<?php
namespace App\Models\Commodity;
use App\Models\BaseModel;
class Images extends BaseModel
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
         'shop_id','url','status'
    ];



}