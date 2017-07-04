  <!-- auth:zww
	 date:2016.08.01 
-->
@extends('layouts.shop')

@section('title')
<title>{{Session::get('brand_name')}}</title>
@stop
@section('addCss')
<link href="{{ URL::asset('shop/css/wechat.css')}}" rel="stylesheet">
@stop
@section('content')
	<div id="orderdetail">
		<div class="order-detail-express">
			<div class="order-number">订单号：{{$order->order_num}}</div>
				@if($order->status==1)
	                <span class="status" name="status">等待买家付款</span>
	            @elseif($order->status==2)
                    <span class="status" name="status">等待卖家发货</span>
                @elseif($order->status==3)
                	<div class="express-number">快递单号：{{$order->express_num}}(闪送)</div>
                    <span class="status" name="status">卖家已发货</span>
                @elseif($order->status==4)
                	<div class="express-number">快递单号：{{$order->express_num}}(闪送)</div>
                    <span class="status" name="status">交易完成</span>
                @elseif($order->status==5)
                	<div class="express-number">快递单号：{{$order->express_num}}(闪送)</div>
                    <span class="status" name="status">交易关闭</span>
                @elseif($order->status==6)
                	<div class="express-number">
                		<a href="/shop/order/refund/{{$order->id}}">
                			<span class="refund-information">描述信息</span>
                		</a>
                	</div>
                    <span class="status" name="status">退款中</span>
                 @elseif($order->status==7)
                	<div class="express-number">
                		<img src="{{asset('/shop/images/myorder/contacter.png')}}" class="contacter-phone">
                	</div>
                    <span class="status" name="status">已退款</span>
                @endif
		</div>
		<!-- 收货信息 -->
			@if($order->status==6||$order->status==7)
			<div class="orderdetail-top">
				<img src="http://cache.dataguiding.com/img/shop/vieworder/orderdetailAddress.png" class="orderdetail-Address">		
				<div class="buyerName">收件人：{{$order->refund_shopinfo->contacter_name}}</div>
				<div class="buyerNumber">{{$order->refund_shopinfo->contacter_phone}}</div>
				@if($order->refund_shopinfo->shop_city=='市辖区')
				<div class="buyerAddress">{{$order->refund_shopinfo->shop_province}}{{$order->refund_shopinfo->shop_district}}{{$order->refund_shopinfo->shop_street}}{{$order->refund_shopinfo->shop_address_detail}}</div>				
				@else
				<div class="buyerAddress">{{$order->refund_shopinfo->shop_province}}{{$order->refund_shopinfo->shop_city}}{{$order->refund_shopinfo->shop_district}}{{$order->refund_shopinfo->shop_street}}{{$order->refund_shopinfo->shop_address_detail}}</div>	
				@endif
			</div>
			@else
			<div class="orderdetail-top">
				<img src="http://cache.dataguiding.com/img/shop/vieworder/orderdetailAddress.png" class="orderdetail-Address">		
				<div class="buyerName">收件人：{{$address->receiver_name}}</div>
				<div class="buyerNumber">{{$address->receiver_phone}}</div>
				<div class="buyerAddress">{{$address->province}}{{$address->city}}{{$address->district}}{{$address->street}}{{$address->address_details}}</div>				
			</div>
			@endif
		<!-- 商品信息 -->
			<div class="order-list">
		        <div class="order-list-store">
					<img src="http://cache.dataguiding.com/img/shop/vieworder/orderdetailStore.png" class="order-store-img"> 
		            <span class="order-store-name">{{$order->shopname}}</span>
		            <img src="http://cache.dataguiding.com/img/shop/vieworder/orderdetailTurn.png" class="order-store-turn">
		            <!-- <span class="ordernum">订单号：{{$order->order_num}}</span>	  -->          
				</div>
				@foreach($order->commodity as $content)
	            <a href="/shop/front/detail?commodity_id={{$content->commodity_id}}&shop_id={{$order->shop_id}}">
					<div class="order-list-content">
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
				@if($order->message!='')
					<div class="order-list-value message">
				    	<div class="message-left">买家留言：<span class="buymessage">{{$order->message}}</span></div>
				    </div>
				@endif
				<div class="order-list-value" id="value-blank">
					<div class="order-list-value-summary" id="line-top">
						<span class="money-left">合计</span>
						<span class="money-right">￥<span class="total-money"></span></span>
						<!-- <span class="money-right">￥{{$order->total}}</span> -->
						<!-- 共{{$order->count}}件商品 合计：￥<span>{{$order->total}}</span>（含运费￥{{$order->express_price}}） -->
					</div>				
				</div>
				@if($order->couponname!=''&& $order->status!=1)
					<div class="order-list-value">
						<div class="order-list-value-summary">
							<span class="money-coupon">优惠:使用优惠券满￥{{$order->couponcondition}}减￥{{$order->couponsum}}</span>
						</div>
				    </div>
				@endif
				<!-- <div class="order-list-action">
					<img src="http://cache.dataguiding.com/img/shop/vieworder/contactSeller.png" class="order-list-action-image1">				
				</div> -->			
			</div>
		<!-- 支付信息 -->
			@if($order->status!=1&&$order->status!=7)
				<div class="express-way">
					<div class="express-way-tips">支付方式：微信支付</div>
					<div class="express-way-number">支付流水号：{{$order->trade_num}}</div>
					@if($order->couponname!='')
						<div class="express-way-moneydetail">￥<span class="total-money"></span>—￥{{$order->couponsum}}(优惠)
							<div class="real-pay">实付：￥<span class="real-paymoney">{{$order->total}}</span></div>
						</div>
					@else
						<div class="express-way-moneydetail">￥<span class="total-money"></span>-￥0.00(优惠)
							<div class="real-pay">实付：￥<span class="real-paymoney">{{$order->total}}</span></div>
						</div>
					@endif		
				</div>
			@elseif($order->status==7)
				<div class="express-way">
					<div class="express-way-tips">支付方式：微信支付</div>
					<div class="express-way-number">支付流水号：{{$order->trade_num}}</div>
					@if($order->couponname!='')
						<div class="express-way-moneydetail">￥<span class="total-money"></span>—￥{{$order->couponsum}}(优惠)
							<div class="real-pay">￥<span class="real-paymoney">{{$order->refund_money}}（实退金额）</span></div>
						</div>
					@else
						<div class="express-way-moneydetail">￥<span class="total-money"></span>-￥0.00(优惠)
							<div class="real-pay">￥<span class="real-paymoney">{{$order->refund_money}}（实退金额）</span></div>
						</div>
					@endif		
				</div>
			@endif
	</div>
@stop
@section('addJs')
<script src="{{asset('shop/js/orderRefund.js')}}"></script>
<script type="text/javascript">
//联系卖家
        $("#orderdetail").on("click", ".order-list-action-image1", function() {
                    $.confirm("确定拨打电话{{$contact}}吗？", "拨打电话！", function() {
    					window.location.href="tel:{{$contact}}";
                    }, function() {
                      //取消操作
                    });
        });
        $("#orderdetail").on("click", ".contacter-phone", function() {
            $.confirm("确定拨打电话{{$contact}}吗？", "拨打电话！", function() {
            	window.location.href="tel:{{$contact}}";
            }, function() {
                  //取消操作
            });
        });
</script>
@stop