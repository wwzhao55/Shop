<?php
namespace App\Models\Admin;
use App\Models\BaseModel;
class Category extends BaseModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'category' ;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name','img_src','status'
    ];



}