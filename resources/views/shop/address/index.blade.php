<!-- auth:zww
	 date:2016.05.11 
-->
<!--选择收货地址-->
<!-- 继承的模板 -->
@extends('layouts.shop')
<!-- 标题 -->
@section('title')
<title>选择收货地址</title>
@stop
<!-- 自己额外要加的css -->
@section('addCss')
<link rel="stylesheet" type="text/css" href="{{ URL::asset('shop/css/wechat2.css')}}">
@stop
<!-- 内容 -->
@section('content')
	<div class="prompt" hidden>暂无收货地址</div>
	<div id="addressChoose">
		@foreach ($lists as $list)
		
			@if ($list->is_default == 1)
				<div class="receive">
					<div class="address-id" style="display:none;">{{$list->id}}</div>
					<p>收件人：{{$list->receiver_name}}</p>
					<label id="contact">{{$list->receiver_phone}}</label>
					<h4>[默认地址]</h4>
					<p> {{$list->province}}{{$list->city}}{{$list->district}}{{$list->street}}{{$list->address_details}}</p>
				</div>
			
			@else
			<div class="receive">
				<div class="address-id" style="display:none;">{{$list->id}}</div>
				<h3>收件人：{{$list->receiver_name}}</h3>
				<label>{{$list->receiver_phone}}</label>
				<h3> {{$list->province}}{{$list->city}}{{$list->district}}{{$list->street}}{{$list->address_details}}</h3>
			</div>
			@endif

		@endforeach
		<form action='/shop/address/choose' method='post'>
		{!! csrf_field() !!}
			<input type='text' hidden value id='input_address' name='address_id'>
		</form>
	</div>
<a href="/shop/address/manage/1"><button class="manage-address">管理</button><a>
	
@stop
@section('addJs')
	<script src="{{URL::asset('shop/js/address.js')}}"></script>
@stop