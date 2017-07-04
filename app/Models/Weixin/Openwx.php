<?php
namespace App\Models\Weixin;
use App\Models\BaseModel;
use App\libraries\Wechat;
use Config;
use DB;
class Openwx extends BaseModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'open_weixin';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = array('appid','type');

}



