<!-- auth:zww
	 date:2016.07.14 
-->
@extends('layouts.shop')

@section('title')
<title>{{Session::get('brand_name')}}</title>
@stop
@section('addCss')
<link href="{{ URL::asset('shop/css/wechat.css')}}" rel="stylesheet">
@stop
@section('content')
<div id="shoporder">
	<div id="shoporder-select">
		<div class="shoporder-type"><span class="shoporder-type-name onorder">全部</span></div>
        <div class="shoporder-type"><span class="shoporder-type-name">待付款</span></div>
        <div class="shoporder-type"><span class="shoporder-type-name">待发货</span></div>
        <div class="shoporder-type"><span class="shoporder-type-name">已发货</span></div>
        <div class="shoporder-type"><span class="shoporder-type-name">已完成</span></div>
	</div>
	<div id="no-order">
	    <div class="order-tip1">您还没有相关的订单</div>
	    <div class="order-tip2">可以先逛逛有哪些想买的</div>
	</div>
	<div id="y-order">
    	@foreach($all as $list)
    		<div class="order-list" name="order-list">
                <span class="order_id" hidden>{{$list->id}}</span>
                <span class="shop_id" hidden>{{$list->shop_id}}</span>
                <span class="total" hidden>{{$list->total}}</span>
    			<div class="order-list-store">			    
    				<img src="http://cache.dataguiding.com/img/shop/myorder/dot.png" class="order-select-img1" name="order-select-img1">
    				<img src="http://cache.dataguiding.com/img/shop/myorder/dot2.png" class="order-select-img2" name="order-select-img2">
    				<img src="http://cache.dataguiding.com/img/shop/myorder/store.png" class="order-store-img"> 
    	            <span class="order-store-name">{{$list->shopname}}</span>
    	            <img src="http://cache.dataguiding.com/img/shop/myorder/turn.png" class="order-store-turn">
    	            
    	            @if($list->status==1)
    	                <span class="status" name="status">等待买家付款</span>
    	            @elseif($list->status==2)
                        <span class="status" name="status">等待卖家发货</span>
                    @elseif($list->status==3)
                        <span class="status" name="status">卖家已发货</span>
                    @elseif($list->status==4)
                        <span class="status" name="status">交易完成</span>
                    @elseif($list->status==5)
                        <span class="status" name="status">交易关闭</span>
                    @elseif($list->status==6)
                        <span class="status" name="status">退款中</span>
                    @elseif($list->status==7)
                        <span class="status" name="status">已退款</span>
                    @endif  
    			</div>
    			@foreach($list->commodity as $content)
                <a href="/shop/order/detail/{{$list->id}}">
    				<div class="order-list-content">
                        <span class='order-list-commodity-id' hidden>{{$content->commodity_id}}</span>
                        <span class='order-list-sku-id' hidden>{{$content->sku_id}}</span>
                        <span class="commodity_id" hidden></span>
    					<img src="{{ asset($content->main_img) }}" class="order-list-content-image">
    					<div class="order-list-content-title">
    					    <div class="order-list-content-name">{{$content->commodity_name}}</div>                       
                            @if($content->commodity_sku!=null)
                                @foreach($content->commodity_sku as $key=>$value)
                                   <div class="order-list-content-sku">{{$key}}:{{$value}}</div>
                                @endforeach
                            @endif
                            <div class="order-list-content-value">￥{{$content->price}}</div>
    					</div>
    					<div class="order-list-amount">x{{$content->count}}</div>
    				</div>
                </a>
    			@endforeach
    			<div class="order-list-value">
    				<div class="order-list-value-summary" id="order-list-value-summary">共{{$list->count}}件商品 合计：￥<span class='total-money'>{{$list->total}}</span>（含运费￥{{$list->express_price}}）</div>
    			</div>
    			<div class="order-list-action">
    			    @if($list->status==1)
    	                <img src="http://cache.dataguiding.com/img/shop/myorder/pay.png" class="order-list-action-image1 pay">
    				    <img src="http://cache.dataguiding.com/img/shop/myorder/conselorder.png" class="order-list-action-image2 conselorder">
    	            @elseif($list->status==2)
                        <img src="http://cache.dataguiding.com/img/shop/myorder/reminderdelivery.png" class="order-list-action-image2 reminderdelivery" name="reminderdelivery">
    				    <a href="/shop/order/detail/{{$list->id}}"><img src="http://cache.dataguiding.com/img/shop/myorder/vieworder.png" class="order-list-action-image1"></a>
                    @elseif($list->status==3)
                        <span class="order-list-action-image2 confirm-receive">确认收货</span>
    				    <a href="/shop/order/detail/{{$list->id}}"><img src="http://cache.dataguiding.com/img/shop/myorder/vieworder.png" class="order-list-action-image1"></a>
                    @elseif($list->status==4)
                        <img src="{{asset('shop/images/myorder/icon6@2x.png')}}" class="order-list-action-image2 buy-next" name="deleteorder">
    				    <a href="/shop/order/detail/{{$list->id}}"><img src="http://cache.dataguiding.com/img/shop/myorder/vieworder.png" class="order-list-action-image1"></a>
                        <a href="/shop/order/refund/{{$list->id}}"><img src="{{asset('shop/images/myorder/refund.png')}}" class="order-list-action-image1"></a>
                    @elseif($list->status==5)
                        <img src="http://cache.dataguiding.com/img/shop/myorder/deleteorder.png" class="order-list-action-image2 deleteorder" name="deleteorder">
                    @elseif($list->status==6)
                        <a href="/shop/order/detail/{{$list->id}}"><img src="http://cache.dataguiding.com/img/shop/myorder/vieworder.png" class="order-list-action-image1"></a>
                    	<span class="order-list-action-image1 cancle-refund">取消退货</span>
                    @elseif($list->status==7)
                        <a href="/shop/order/detail/{{$list->id}}"><img src="http://cache.dataguiding.com/img/shop/myorder/vieworder.png" class="order-list-action-image1"></a>
                    @endif				
    			</div>
    		</div>
    	@endforeach
	   <!-- 合并付款	 -->
		<div class="allpay" name="allpay">
			<img src="http://cache.dataguiding.com/img/shop/myorder/allpay.png" class="order-list-action-image3">
		</div>
	</div>
	<div class='line-text' name="line-text">
			<hr>
			<div>更多精选商品</div>
	</div>
    @if (count($more) >0)
	<div class='commodity-lists' name="commodity-lists">
    			
			@foreach($more as $list)
                <a href="/shop/front/detail?commodity_id={{$list->id}}">
                  <div class='commodity-list'>
                    <div class='list-img'>
                        <img src="{{ asset($list->main_img) }}" class="commodity-image">
                        <div class='list-value'>￥{{$list->price}}</div>
                    </div>
                    <div class='list-title'>
                        {{$list->commodity_name}}
                    </div>
                  </div>
                </a>
            @endforeach 
    
			<div class='clearfix'></div>
	</div>
</div>
    @endif
</div>
@stop

@section('addJs')
<script src="{{asset('shop/js/jweixin-1.0.0.js')}}"></script>
<script src="{{asset('shop/js/orderIndex.js')}}"></script>
<script type="text/javascript">
     //微信支付
    wx.config(<?php echo $js->config(array('chooseWXPay'), false) ?>);

    var Commodity=new Array();
    var Sku=new Array();
    var commodity_id=new Array();
    var sku_id=new Array();
    var commodity=new Array();
    $('.buy-next').on('click',function(){
        commodity_id.length=0;
        sku_id.length=0;
        commodity.length=0;
        Sku.length=0;
        var shop_id = $(this).parents('.order-list').children('.shop_id').html();
        var order_id = $(this).parents('.order-list').children('.order_id').html();
        var object = $(this).parents('.order-list-action').siblings('a').children('.order-list-content');

        $.each(object,function(key1,val1){
            var commodityID=$(this).children('.order-list-commodity-id').html();
            var skuID=$(this).children('.order-list-sku-id').html();
            var commodity_middle={"commodity_id":commodityID,"sku_id":skuID};
            commodity.push(commodity_middle);
        });
        console.log(JSON.stringify(commodity)); 
            $.ajax({
                    type:'POST',
                    url:'/shop/order/buyagain',                          
                    dataType:"json",
                    data:{
                        shop_id:shop_id,
                        commodity:JSON.stringify(commodity),
                        order_id:order_id,
                        },
                    success:function(data){
                        if(data.status=="success"){
                            window.location = '/shop/shopcart/index/'+order_id;                                          
                        }else{
                            $.alert(data.msg);
                        }
                    }
            });
    });
$('.confirm-receive').on('click',function(){
    var order_id = $(this).parents('.order-list').children('.order_id').html();
        $.ajax({
                    type:'POST',
                    url:'/shop/order/receive',                          
                    dataType:"json",
                    data:{
                        order_id:order_id,
                        },
                    success:function(data){
                        if(data.status=="success"){
                            window.location.reload();                                          
                        }else{
                            $.alert(data.msg);
                        }
                    }
        });   
});
</script>
@stop