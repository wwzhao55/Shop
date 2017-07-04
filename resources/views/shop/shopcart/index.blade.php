<!-- auth:zww
	 date:2016.07.30 
-->
<!--购物车-->
<!-- 继承的模板 -->
@extends('layouts.shop')
<!-- 标题 -->
@section('title')
<title>购物车</title>
@stop
<!-- 自己额外要加的css -->
@section('addCss')
<!-- <link href="http://cache.dataguiding.com/css/shop/wechat2.css" rel="stylesheet"> -->
<link href="//cdn.bootcss.com/iCheck/1.0.2/skins/minimal/orange.css" rel="stylesheet">
<link href="{{asset('shop/css/wechat2.css')}}" rel="stylesheet">
@stop 
<!-- 内容 -->
@section('content')
	<div class='loading'><img src="{{asset('shop/images/loading.gif')}}"></div>
	<div class="shopCat">
		<!-- 遍历商品 -->
		@foreach ($commoditys as $shop=> $list)
			<div class="shopCat_list">
					<div class="shopCat_title">
						<span class="shopcart-logo"><input type="checkbox" ></span>
						<span class='img-storesmall'><img class='storeImg' src="{{asset('shop/images/shopcat/store.png')}}"></span>
						<label>
							<a href="/shop/front/index?s={{$list[0]->shop_id}}">
								<c class="shop-name">{{$shop}}</c>
								<span class='img-arrowright'>
									<img src="{{asset('shop/images/shopcat/arrow-right.png')}}">
								</span>
							</a>
						</label>					
						<div id="storeDet">

							<span class='shop_id' hidden>{{$list[0]->shop_id}}</span>
							@if($coupons)
							<button class="coupon">领劵</button>
							@endif					
							<button class="edit">编辑</button>
						</div>
					</div>
					@foreach ($list as $value)	
					
					<div class="shopCat_content">
						<div class="commodity-name" style="display:none;">{{$value->shopname}}</div>
						<div class="commodity-id" style="display:none;">{{$value->id}}</div>
						<div class="commodity-price" style="display:none;">{{$value->price}}</div>				
						<span class="shopcart-content"><input type="checkbox" ></span>	
						<div class="content-box "><a href="/shop/front/detail?commodity_id={{$value->commodity_id}}"><img src="{{asset($value->main_img)}}"></a></div>
						<div id="content_detail">
							<div data-trigger="spinner" class='spinner pull-right' hidden style="margin-bottom: 20px;">
							@if($value->count == 1)
								<a href="javascript:;" data-spin="down" class='spinner-minus disabled'>-</a>
							@else
								<a href="javascript:;" data-spin="down" class='spinner-minus '>-</a>
							@endif
								<input type="text" id="count" value="{{$value->count}}" readonly data-rule="quantity" data-min="1">
								<a href="javascript:;" data-spin="up" class='spinner-plus'>+</a>
							</div>
							<span class="commodity_name_shop">{{$value->commodity_name}}</span>
                                @foreach($value->commodity_sku as $key=>$val)
                                    <span class="shopcart-skuvalue">{{$val}}</span>
                                @endforeach
							<br>
							<span  class="price" style="color:#ff6a52;">￥{{$value->price}}</span>
							<label class="number">x{{$value->count}}</label>
							<label class="quantity" style="display:none;">{{$value->quantity}}</label>                  
						</div>
						<div class="edit-delete" hidden><span class="shop-edit-delete">删除</span></div>
					</div>
					@endforeach	
					<div class='clearfix'></div>
			</div>			
		@endforeach
		
	</div>
	<div id="prompt-message" hidden>购物车空空如也~
		<a href="/shop/front/index">
			<span class="gotoshopcart">
				<img src="{{asset('shop/images/shopcat/btn@2x.png')}}">
			</span>
		</a>

		<div class="you-like">
		<div class='line-text1'>
			<hr>
			<div>猜你喜欢</div>
		</div>
		<div class='commodity-lists-detail'>
			@foreach($likes as $list)
				<a href="/shop/front/detail?commodity_id={{$list->id}}">
	          		<div class='commodity-list'>
	            		<div class='list-img'>
			                <img src="{{ asset($list->main_img) }}" class="commodity-image">
			                <div class='list-value'>￥{{$list->price}}</div>
			            </div>
			            <div class='list-title'>
			                 {{$list->commodity_name}}
			            </div>
			        </div>
	        	</a>
			@endforeach				
		</div>
		</div>
	</div>
	<!-- 底部固定栏 -->
	<div id="shopcart-btn">
			<!-- <img class="shopcart-all" src="http://cache.dataguiding.com/img/shop/shopcat/dot-big.png"> -->
			<span class='shopcart-all'><input type='checkbox'></span>
			<span class="choose_all">全选</span>
			<button class="check-money disabled ">结算(0)</button>
			<label class="sum"><p>￥<c class="total-money">0.00<c/></p>合计：</label><br>
			<label class="describe">不含运费和进口税</label>
			<div class="delete-all" hidden>删除</div>
	</div>
	<!-- 领取优惠券弹窗 -->
	<div id="coupon-collet-cover"></div>
	<div id="coupon-collet">
		<div class="coupon-shopcart">	     
			<span class="shopcart-store-coupon">领取优惠券</span>
		</div>
		<ol id="coupon-lists">
			<div class="coupon-block">
				@foreach ($coupons as $list)
					<li class="coupon-list">
						<span class="coupon-shop-id" style="display:none;">{{$list->shop_id}}</span>
						<span class="coupon-id" style="display:none;">{{$list->id}}</span>
						<span class="coupon-value">{{$list->sum}}元</span><br/>
						<span class="coupon-use-condition">订单满{{$list->use_condition}}元使用</span><br/>
						<span class="coupon-deadline">使用期限 {{$list->validity_start}}-{{$list->validity_end}}</span>
						<span class="coupon-deal">领取</span>
					</li>
				@endforeach
			 </div>
		</ol>
		<div class="coupon-bottom">	<span class="coupon-button">关闭</span></div>
	</div>
	<div class='clearfix'></div>
@stop
@section('addJs')
<script src="{{URL::asset('shop/weui/js/swiper.min.js')}}"></script>
<script src="//cdn.bootcss.com/iCheck/1.0.2/icheck.min.js"></script>
<script src="{{asset('shop/js/shopcart.js')}}"></script>
<script src="{{asset('shop/js/spinner.min.js')}}"></script>
 @stop