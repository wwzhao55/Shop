<!-- auth:zww
	 date:2016.07.30 
-->
<!-- 继承的模板 -->
@extends('layouts.shop')
<!-- 标题 -->
@section('title')
<title>领取优惠券</title>
@stop
<!-- 自己额外要加的css -->
@section('addCss')
<link rel="stylesheet" type="text/css" href="{{asset('shop/css/coupon.css')}}">
@stop
<!-- 内容 -->
@section('content')
	<div id="coupon-share">
		<div class="share-body">
			<img class="share-background" src="{{asset('shop/images/coupon/coupon-logo.png')}}">
			<div class="share-word">
				<span class="share-name">{{$brandname}}</span></br>
				<span class="share-send">送你一张优惠券</span>
			</div>
		</div>
		<div class="take-btn">
			<a href="/shop/coupon/collectshare/{{$brand_id}}/{{$id}}">
				<!-- <a href="/shop/coupon/collectcoupon/{{$brandname}}/{{$id}}"> -->
					<img src="{{asset('shop/images/coupon/Receive-coupon.png')}}">
			</a>
		</div>
		<div class="use-description-image">
			<img src="{{asset('shop/images/coupon/icon_Prompt.png')}}">
			<span class="use-description-word">使用说明</span>
			<span class="use-coupon-description" hidden>{{$info}}</span>
		</div>	
	</div>
@stop
@section('addJs')
<script src="{{URL::asset('shop/js/coupon.js')}}"></script>
 @stop