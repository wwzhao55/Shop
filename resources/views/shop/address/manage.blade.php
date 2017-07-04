<!-- auth:zww
	 date:2016.05.12 
-->
<!--管理收货地址-->
<!-- 继承的模板 -->
@extends('layouts.shop')
<!-- 标题 -->
@section('title')
<title>{{$title}}</title>
@stop
<!-- 自己额外要加的css -->
@section('addCss')
<!-- <link rel="stylesheet" type="text/css" href="http://cache.dataguiding.com/css/shop/wechat2.css">  -->
<link rel="stylesheet" type="text/css" href="{{URL::asset('shop/css/wechat2.css')}}">
@stop
<!-- 内容 -->
@section('content')
	<div class='loading'><img src="{{asset('shop/images/loading.gif')}}"></div>
	<div class="prompt" hidden>暂无收货地址</div>
	<div class="addressManage">
		@foreach ($lists as $list)

			@if ($list->is_default == 1)
			<div class="address-list">
				<div class="address-id" style="display:none;">{{$list->id}}</div>
				<div class="receive">
					<p>收件人：{{$list->receiver_name}}</p>
					<label>{{$list->receiver_phone}}</label>
					<h4>[默认地址]</h4>
					<p>@if($list->province == $list->city) 
					{{$list->province}}{{$list->district}}{{$list->street}}{{$list->address_details}}
					@else
					{{$list->province}}{{$list->city}}{{$list->district}}{{$list->street}}{{$list->address_details}}
					@endif</p>
				</div>
				<div class="editAddress">
					<img class="choose-default" src="http://cache.dataguiding.com/img/shop/shopcat/check-small.png"> 
					<label>默认地址</label>
					<div id="manage-btn">
						<a href="/shop/address/edit?type=edit&address_id={{$list->id}}">
							<button id="btn-edit"><img src="http://cache.dataguiding.com/img/shop/shopcat/edit.png">编辑</button>
						</a>
						<button class="btn-del">
							<img src="http://cache.dataguiding.com/img/shop/shopcat/delete.png">删除</button>
					</div>
				</div>
			</div>

			@else
			<div class="address-list">
					<div class="address-id" style="display:none">{{$list->id}}</div>
					<div class="receive">
						<p>收件人：{{$list->receiver_name}}</p>
						<label>{{$list->receiver_phone}}</label>
						<p> @if($list->province == $list->city) 
					{{$list->province}}{{$list->district}}{{$list->street}}{{$list->address_details}}
					@else
					{{$list->province}}{{$list->city}}{{$list->district}}{{$list->street}}{{$list->address_details}}
					@endif</p>
					</div>
					<div class="editAddress">
						<img class="choose-default" src="http://cache.dataguiding.com/img/shop/shopcat/dot-small.png" > 
						<label>设为默认</label>
						<div id="manage-btn">
							<a href="/shop/address/edit?type=edit&address_id={{$list->id}}"><button id="btn-edit">
								<img src="http://cache.dataguiding.com/img/shop/shopcat/edit.png">编辑</button>
							</a>
							<button class="btn-del">
								<img src="http://cache.dataguiding.com/img/shop/shopcat/delete.png">删除</button>
						</div>
					</div>
			</div>
			@endif

		@endforeach
		@if(count($lists) > 0)
		<div class='clearfix'></div>
		@endif			
			<a href="/shop/address/edit?type=new" >
				<div id="buttom"><button id="newAdress">添加新地址</button></div>
			</a>
		<form action='/shop/address/choose' method='post'id="form1">
		{!! csrf_field() !!}
			<input type='text' hidden value id='input_address' name='address_id'>
		</form>			
	</div>
@stop
@section('addJs')
	<script src="{{URL::asset('shop/js/address.js')}}"></script>
@stop