<!-- auth:zww
	 date:2016.07.30
	 content:update 
-->
<!-- 继承的模板 -->
@extends('layouts.shop')
<!-- 标题 -->
@section('title')
<title>我的优惠券</title>
@stop
<!-- 自己额外要加的css -->
@section('addCss')
<link rel="stylesheet" type="text/css" href="{{ URL::asset('shop/css/wechat.css')}}">
@stop
<!-- 内容 -->
@section('content')
<div id="coupon">
@if(count($coupons)!=0)
	<div class="coupon-shop">	     
	    <img src="http://cache.dataguiding.com/img/shop/myorder/store.png" class="order-store-img"> 
	    <span class="order-store-name">{{$brandname}}</span>
	    <img src="http://cache.dataguiding.com/img/shop/myorder/turn.png" class="order-store-turn"> 
	</div>
	<ol id="coupon-lists">
	    <div class="coupon-block">
	        @foreach ($coupons as $list)
		        <li class="coupon-list">
		            <span class="coupon-id" hidden>{{$list->id}}</span>
			        <span class="coupon-value">{{$list->sum}}元</span><br/>
			        <span class="coupon-use-condition">订单满{{$list->use_condition}}元使用</span><br/>
			        <span class="coupon-deadline">使用期限 {{$list->validity_start}}-{{$list->validity_end}}</span>
		        </li>
	        @endforeach
        </div>
	</ol>
	@else
    <div class="tips">暂无优惠券</div>
	@endif
</div>
@stop
@section('addJs')
@stop