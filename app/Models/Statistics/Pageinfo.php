<?php
namespace App\Models\Statistics;
use Illuminate\Database\Eloquent\Model;
use App\Models\BaseModel;
class Pageinfo extends BaseModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'pageinfo';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
         'page_name','page_url'
    ];



}
