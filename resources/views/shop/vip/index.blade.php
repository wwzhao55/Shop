<!-- auth:zww
	 date:2016.07.20 
-->
@extends('layouts.shop')

@section('title')
<title>{{Session::get('brand_name')}}</title>
@stop
@section('addCss')
<link href="{{asset('shop/css/wechat.css')}}" rel="stylesheet">
@stop
@section('content')
<div id="member-center">
<div class="owner-message">
	<a href="{{ URL::asset('shop/front/index')}}"><img src="http://cache.dataguiding.com/img/shop/memberCenter/shop.png" class="ownershop"></a>
	<div class="personal-information">
	    <img src="{{asset($headimgurl)}}" class="head1">
	    <div id="userName">{{$nickname}}</div>
	    <!-- <span class="credit">积分：{{$score}}</span> -->
	</div>
	<a href="{{ URL::asset('shop/shopcart/index')}}"><img src="http://cache.dataguiding.com/img/shop/memberCenter/cart.png" class="cart"></a>
</div>
<div class="order-review">
	<a href="/shop/order/index?unpay">
		<div class="num">
			@if($unpay)
			<span class="order-num">{{$unpay}}</span>
			@endif
		    <img src="{{asset('shop/images/myorder/Already-paid.png')}}" class="order-img">
		    <span class="order-name">待付款</span>  
		</div>
	</a>
	<a href="/shop/order/index?unsend">
		<div class="num">
			@if($unsend)
			<span class="order-num">{{$unsend}}</span>
			@endif
		    <img src="{{asset('shop/images/myorder/Pending-payment.png')}}" class="order-img">
		    <span class="order-name">待发货</span>  
		</div>
	</a>
	<a href="/shop/order/index?payed">
		<div class="num">
			@if($payed)
			<span class="order-num">{{$payed}}</span>
			@endif
		    <img src="{{asset('shop/images/myorder/Already-shipped.png')}}" class="order-img">
		    <span class="order-name">已发货</span>  
		</div>
	</a>
	<a href="/shop/order/index?finished">
		<div class="num">
			@if($finished)
			<span class="order-num">{{$finished}}</span>
			@endif
		    <img src="{{asset('shop/images/myorder/Has-been-completed.png')}}" class="order-img">
		    <span class="order-name">已完成</span>
		</div>
	</a>
</div>
<ol class="test-list">
<div class="tl">
<li class="list">
    <a href="{{ URL::asset('shop/order/index')}}" class="hyperlink">
    <div>
        <img src="http://cache.dataguiding.com/img/shop/memberCenter/order.png">
	    <span class="list-name">我的订单</span>
	    <img src="http://cache.dataguiding.com/img/shop/memberCenter/turn.png" class="list-turn">
	</div>
	</a>
</li>
<li class="list">
    <a href="{{ URL::asset('shop/coupon/index')}}" class="hyperlink">
    <div>
	    <img src="http://cache.dataguiding.com/img/shop/memberCenter/coupon.png">
	    <span class="list-name">我的优惠劵</span>
	    <img src="http://cache.dataguiding.com/img/shop/memberCenter/turn.png" class="list-turn">
	</div>
	</a>
</li>
<li class="list">
    <a href="{{ URL::asset('shop/address/manage/?type=vip')}}" class="hyperlink">
    <div>
	    <img src="http://cache.dataguiding.com/img/shop/memberCenter/account.png">
	    <span class="list-name">收货地址管理</span>
	    <img src="http://cache.dataguiding.com/img/shop/memberCenter/turn.png" class="list-turn">
	</div>
	</a>
</li>
</div>
</ol>

</div>
@stop
@section('addJs')
@stop
