<?php
namespace App\Models\Statistics;
use Illuminate\Database\Eloquent\Model;
use App\Models\BaseModel;
class Pageview extends BaseModel
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
         'page_id','shopid','customer_id'
    ];



}
