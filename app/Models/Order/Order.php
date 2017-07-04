<?php
namespace App\Models\Order;
use App\Models\BaseModel;
class Order extends BaseModel
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
         'shop_id','order_num','trade_num','refund_num','total','express_price','coupon_id','status','customer_id','address_id','message','order_at','trade_at','send_at','refund_at','hurry_times','hurry_at','close_type','deal','refund_money'
    ];

    public function getOrderCount(){
        return $this->where('id','>',0)->count();
    }

    public function getTotal(){
        $orders = $this->where('status','=',4)->get()->toArray();
        $total = 0;
        foreach($orders as $order){
            $total += $order['total'];
        }
        return $total;

    }

    public function getNewOrder($start_date,$end_date){
        $orderCount = $this->whereBetween('created_at',[$start_date,$end_date])->count();
        return $orderCount;
    }

    public function getNewTotal($start_date,$end_date){
        $orders = $this->where('status','=',4)->whereBetween('created_at',[$start_date,$end_date])->get()->toArray();
        $total = 0;
        foreach($orders as $order){
            $total += $order['total'];
        }
        return $total;
    }

    public function getShopTotal($shop_id){
        $orders = $this->where('status','=',4)->where('shop_id','=',$shop_id)->get()->toArray();
        $total = 0;
        foreach($orders as $order){
            $total += $order['total'];
        }
        return $total;
    }

    public function getShopCount($shop_id){
        return $this->where('shop_id',$shop_id)->count();
    }

    public function getShopNewOrder($start_date,$end_date,$shop_id){
        $orderCount = $this->whereBetween('created_at',[$start_date,$end_date])->where('shop_id','=',$shop_id)->count();
        return $orderCount;
    }

    public function getShopNewTotal($start_date,$end_date,$shop_id){
        $orders = $this->where('status','=',4)->whereBetween('created_at',[$start_date,$end_date])->where('shop_id','=',$shop_id)->get()->toArray();
        $total = 0;
        foreach($orders as $order){
            $total += $order['total'];
        }
        return $total;
    }


}