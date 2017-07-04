<!-- auth:zww
	 date:2016.07.29 
-->
<!-- 店铺已打烊 -->
@extends('layouts.shop')

@section('title')
<title>店铺已打烊</title>
@stop
@section('addCss')
<!-- <link href="http://cache.dataguiding.com/css/shop/wechat.css" rel="stylesheet"> -->
<link href="{{ URL::asset('shop/css/wechat.css')}}" rel="stylesheet">
@stop
@section('content')
	<div class="store-rest">
		<div class="stroe-rest-img">
			<img src="{{asset('shop/images/offlinestore/close-store.png')}}">
		</div>
		<div class="store-rest-introduce">幸果子店铺已打烊</div>
		<div class="store-rest-tip">如需查看历史购买记录请查看会员主页</div>
		<div class="store-rest-govip">
			<a href="/shop/vip/index">
				<span class='goto-vip'>前往会员主页</span>
			</a>
		</div>
	</div>
@stop

@section('addJs')
@stop