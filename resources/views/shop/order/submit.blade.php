<!-- auth:zww
	 date:2016.07.20 
-->
<!-- 继承的模板 -->
@extends('layouts.shop')
<!-- 标题 -->
@section('title')
<title>提交订单</title>
@stop
<!-- 自己额外要加的css -->
@section('addCss')
<!-- <link rel="stylesheet" type="text/css" href="http://cache.dataguiding.com/css/shop/wechat2.css"> -->
<link href="{{asset('shop/css/wechat2.css')}}" rel="stylesheet">
@stop
<!-- 内容 -->
@section('content')
	<div id="submitOrder">
		<!-- 收货地址 -->
			@if (Session::has('order_address'))
			<a href="/shop/address/manage/?type=order">
				<div class="submit-address">
					<label class="address-id" hidden >{{Session::get('order_address.id')}}</label>
					<span class='img-gps' ></span>
					<h3>收件人：{{Session::get('order_address.receiver')}}</h3>
					<label id="contact">{{Session::get('order_address.phone')}} 
							<span class="detail-em"><img src="{{asset('shop/images/shopcat/em-detail.png')}}"></span>
					</label>
					<span>{{Session::get('order_address.address')}}</span>			
				</div>
			</a>
			@elseif(count($address) == 0)
			<a href="/shop/address/manage/?type=order">
				<div class="submit-address">
					<label class="address-id" hidden></label>
					<span class='img-gps' ></span>
					<h3></h3>
					<label id="contact"> 
						<span class="detail-em"><img src="{{asset('shop/images/shopcat/em-detail.png')}}"></span>	
					</label>
					<span>暂无收货地址</span>				
				</div>
			</a>
			@else
			<a href="/shop/address/manage/?type=order">
				<div class="submit-address">
					<label class="address-id" hidden>{{$address->id}}</label>
					<span class='img-gps' ></span>
					<h3>收件人：{{$address->receiver_name}}</h3>
					<label id="contact">{{$address->receiver_phone}} 
						<span class="detail-em"><img src="{{asset('shop/images/shopcat/em-detail.png')}}"></span>						
					</label>
					<span>{{$address->province}}{{$address->city}}{{$address->district}}{{$address->street}}{{$address->address_details}}</span>				
				</div>
			</a>
			@endif
		<!-- 遍历店铺 -->
			@foreach ($commoditys as $key=>$list)
			
				<div class="submitshop_list">	
					<div id="shop_list">
						<div class="shop_list_title">
							<span class='store-big'><img src="{{asset('shop/images/myorder/store.png')}}"></span>
							<a href='/shop/front/index?s={{$list->commodity[0]->shop_id}}'>
								<label class="shopname">{{$key}}</label>
							</a>
							<!-- <span class="express" hidden>{{$shopexpress[$key]}}</span> --><!--获得数组类的-->								    
						</div>
						<!-- 遍历一个店铺里的多类商品 -->
						@foreach ($list->commodity as $value)
							<div class="commodity-id" style="display:none;">{{$value->id}}</div>
							<div class="shop_list_content">					
								<a href='/shop/front/detail?commodity_id={{$value->commodity_id}}'>
									<div class="content-box"><img src="{{asset($value->main_img)}}"></div>
								</a>
								<div id="content_detail">
								@if($value->count > $value->quantity)
									<span class='sku-info'>[库存不足，宝贝已失效]</span><br>
								@endif	
									<span>{{$value->commodity_name}}</span><br>
									<!--<span>Chateau La Branne MEDOC CRU BOURGEIOS</span><br><br>-->
									@if(count($value->commodity_sku)>0)
			                            @foreach($value->commodity_sku as $k=>$info)
			                               <div class="order-list-content-sku">{{$k}}:{{$info}}</div>
			                            @endforeach
			                        @endif
			                        <br>
									<span style="color:#ff6a52;">￥{{$value->price}}</span>
									<label class="commodity-count">×{{$value->count}}</label>		
								</div>
							</div>
						@endforeach
					</div>
					<!-- <div id="fare">
							<label>邮费</label>
							<span>{{$shopexpress[$key]}}</span>
					</div> -->
					<div id="information">
							<label class="message-left">买家留言：</label>
							<input type="hidden" name="_token" value="{{ csrf_token() }}">
							<input type="text" placeholder="选填，可填写您和卖家达成一致......" name="message" id="commodity-message" value="">							
							<hr class="line">
							<div id="object">								
								<span class="total">{{$list->total}}</span>
								<span class="doller">￥</span>
								<h4 class="shop-count">共1件商品 合计：</h4>
							</div>
					</div>
					@if (Session::has('order_coupon') && (Session::has('order_coupon.'.$key)))
						<a href="/shop/coupon/choose/{{$key}}/{{$list->total}}">
							<div id="usecoupon">
								<label>优惠</label>
								<span class="return-choose"><img src="{{asset('shop/images/shopcat/em-detail.png')}}"></span>
								<span class="coupon-sum">{{Session::get('order_coupon.'.$key.'.sum')}}</span>
								<c>- </c>
								<span class="coupon-id" hidden >{{Session::get('order_coupon.'.$key.'.id')}}</span>
							</div>
						</a>
					@else
						<a href="/shop/coupon/choose/{{$key}}/{{$list->total}}">
							<div id="usecoupon">
								<label>优惠</label>
								<span>使用优惠券 
									<span class="return-choose"><img src="{{asset('shop/images/shopcat/em-detail.png')}}"></span>
								</span>
								<span class="coupon-id" hidden >0</span>
							</div>
						</a>
					@endif
				</div>
			@endforeach
			<div class="express_way">
				<span class="express_way_tip-1">配送方：闪送快递，运费为货到付款，不在本店支付范围内</span>
				<span class="express_way_tip-2">运费预估￥10，具体金额以实际到付为准</span>
			</div>
			<div class='clearfix'></div>
	</div>	
	<div id="bottom-Btn">
		<label class="money-detail">￥{{$total}}—￥<span class="pay_coupon">0.00</span>(优惠券)</label>
		<label class="need-pay">需付：
			<span class="doller-1">￥</span><span class="express-total">{{$total}}</span></label><br>
		<button id="check">支付</button>
	</div>	
	<div id="coupon-collet-cover">
		<div class="weui_actionsheet_menu">
			<div class="weui_actionsheet_cell " id="case-on-weixin">微信支付</div>
			<!-- <div class="weui_actionsheet_cell " id="cash-on-delivery">货到付款</div> -->
			<div class="weui_actionsheet_cell " id="cancle-on">取消</div>
		</div>
	</div>
	<div class='clearfix'></div>
@stop
@section('addJs')
<script src="{{asset('shop/js/jweixin-1.0.0.js')}}"></script>
<script src="{{asset('shop/js/orderSubmit.js')}}"></script> 
<script src="{{URL::asset('shop/js/coupon.js')}}"></script>
<script type="text/javascript">
	 //微信支付
    wx.config(<?php echo $js->config(array('chooseWXPay'), false) ?>);
</script>
@stop
