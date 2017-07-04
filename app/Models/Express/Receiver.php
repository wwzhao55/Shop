<?php
namespace App\Models\Express;
use App\Models\BaseModel;
class Receiver extends BaseModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table ;

    protected $fillable = [
         'customer_id','receiver_name','receiver_phone','province','city','district','street','address_details','is_default','status'
    ];


}