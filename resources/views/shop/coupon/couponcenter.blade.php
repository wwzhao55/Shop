<!-- auth:zww
	 date:2016.07.18 
-->
<!-- 继承的模板 -->
@extends('layouts.shop')
<!-- 标题 -->
@section('title')
<title>优惠券</title>
@stop
<!-- 自己额外要加的css -->
@section('addCss')
<link rel="stylesheet" type="text/css" href="{{asset('shop/css/coupon.css')}}">
@stop
<!-- 内容 -->
@section('content')
<div id="coupon-choose">
			<ol id="coupon-lists">
			  	<div class="coupon-block">
			  		@foreach ($coupons as $list)
						<li class="coupon-list">
							<span class="coupon-id" style="display:none;">{{$list->id}}</span>
							<a href="/shop/coupon/detail/{{$list->id}}">
								<div class="coupon-name">{{$list->name}}</div><span class="coupon-dollar">¥</span><span class="coupon-value">{{$list->sum}}</span><span class="coupon-use-condition">(订单满{{$list->use_condition}}元使用)</span><div class="coupon-deadline">使用期限 {{$list->validity_start}}-{{$list->validity_end}}</div>
							</a>
							<!-- 无限次领取 -->
							@if($list->gettimes == 0)
								<span class="coupon-take"><img src="{{asset('shop/images/coupon/coupon-take.png')}}"></span>
							<!-- 领取一次 -->
							@elseif($list->gettimes == 1)
								@if($list->collected == 0)
									<span class="coupon-take"><img src="{{asset('shop/images/coupon/coupon-take.png')}}"></span>
								@else
									<span  class="coupon-finish">已领取</span>
								@endif
							<!-- 领取多次 -->
							@else
								@if($list->collected == 0)
									<span class="coupon-take"><img src="{{asset('shop/images/coupon/coupon-take.png')}}"></span>
								@else
									@if($list->rest == 0)
									<span  class="coupon-finish">已领取</span>
									@else
									<span class="coupon-take"><img src="{{asset('shop/images/coupon/coupon-take.png')}}"></span>
									<span class="coupon-times">剩余{{$list->rest}}次</span>
									@endif
								@endif
							@endif
						</li>
					@endforeach
			    </div>
			</ol>
	</div>
@stop
@section('addJs')
<script src="{{URL::asset('shop/js/coupon.js')}}"></script>
 @stop