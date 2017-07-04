<?php
namespace App\Models\Commodity;
use App\Models\BaseModel;
class Skuname extends BaseModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table  = 'skuname';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
         'category_id','skuname'
    ];



}