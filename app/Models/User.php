<?php
namespace App\Models;
use App\Models\BaseModel;
class User extends BaseModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
         'account','password','role','brand_id','shop_id',
    ];

    protected $hidden = [
        'password','remember_token',
    ];


}