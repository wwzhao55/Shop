<!-- auth:zww
	 date:2016.07.20 
-->
<!-- 继承的模板 -->
@extends('layouts.shop')
<!-- 标题 -->
@section('title')
<title>选择优惠券</title>
@stop
<!-- 自己额外要加的css -->
@section('addCss')
<link rel="stylesheet" type="text/css" href="{{asset('shop/css/wechat2.css')}}">
<!-- 内容 -->
@section('content')
<div id="coupon-choose">
		@if($coupons)

			<ol id="coupon-lists">
			  	<div class="coupon-block">
			  		@foreach ($coupons as $list)

						<li class="coupon-list">
							<span class="coupon-id" style="display:none;">{{$list->id}}</span>
							<span class="coupon-value">{{$list->sum}}元</span><br/>
							<span class="coupon-use-condition">订单满<span>{{$list->use_condition}}元使用</span></span><br/>
							<span class="coupon-deadline">使用期限 {{$list->validity_start}}-{{$list->validity_end}}</span>
							@if($total>=$list->use_condition)
							<span class="coupon-use"><img src="{{asset('shop/images/myorder/使用@2x.png')}}"></span>
							@else
							<span class="coupon-use disabled"><img src="{{asset('shop/images/myorder/使用@2x.png')}}"></span>
							@endif
							<span class="shopname" style="display:none">{{$shopname}}</span>
							<span class="total" style="display:none">{{$total}}</span>
						</li>

					@endforeach
			     </div>
			</ol>
				@if (Session::has('coupon_error'))
					<div class="coupon_error" hidden>{{Session::get('coupon_error')}}
					</div>
				@endif
		@else
			<div class="coupon-empty">暂无优惠券</div>
		@endif
			<form action='/shop/coupon/choose' method='post' id="form1">
			{!! csrf_field() !!}
				<input type='text' hidden value="" id='input_coupon' name='coupon_id'>
				<input type='text' hidden value="{{ $total}}" name='total'>
				<input type='text' hidden value="{{$shopname}}"  name='shopname'>
			</form>
</div>
@stop
@section('addJs')
<script src="{{URL::asset('shop/js/coupon.js')}}"></script>
@stop