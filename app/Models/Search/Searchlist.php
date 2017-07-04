<?php
namespace App\Models\Search;
use Illuminate\Database\Eloquent\Model;
use App\Models\BaseModel;
class Searchlist extends BaseModel
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
         'search_id','customer_id'
    ];



}
