<!-- auth:zww
	 date:2016.07.13 
-->
<!-- 继承的模板 -->
@extends('layouts.shop')
<!-- 标题 -->
@section('title')
<title>成功领到优惠券</title>
@stop
<!-- 自己额外要加的css -->
@section('addCss')
<link rel="stylesheet" type="text/css" href="{{asset('shop/css/coupon.css')}}">
@stop
<!-- 内容 -->
@section('content')
	<div id="coupon-success">
		<div class="coupon-success-title">
			<img class="share-logo" src="{{asset($headimgurl)}}">
			<span class="share-coupon-name">{{$msg}}</span></br>
		</div>
		<div class="coupon-success-body">
			<div class="coupon-name-1">{{$coupon->name}}</div><span class="coupon-dollar">￥</span><span class="coupon-value">{{$coupon->sum}}</span><span class="coupon-use-condition">(订单满{{$coupon->use_condition}}元使用)</span><div class="coupon-deadline">使用期限 {{$coupon->validity_start}}-{{$coupon->validity_end}}</div>
			<a href="/shop/front/index?b={{$brand_id}}&s={{$shop_id}}">
				<span class="coupon-goto">
					<img src="{{asset('shop/images/coupon/icon-gofirst.png')}}">
				</span>
			</a>
			
		</div>
		
	</div> 
@stop
@section('addJs')
@stop