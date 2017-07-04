<!-- auth:zww
	 date:2016.07.20 
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
<div id="coupon-detail">
			<ol id="coupon-detail-lists">
			  	<div class="coupon-detail-block">
							<div class="coupon-name">{{$coupon->name}}</div>
							<div><span class="coupon-dollar">￥</span><span class="coupon-value">{{$coupon->sum}}元</span><span class="coupon-use-condition">(订单满{{$coupon->use_condition}}元使用)</span></div>
							@if($coupon->gettimes == 0)
								<span class="coupon-introduction">每人可领取无限张</span>
							@else
							<div class="coupon-introduction">每人可领取{{$coupon->gettimes}}张</div>
							@endif
							<div class="coupon-deadline">使用期限{{$coupon->validity_start}}-{{$coupon->validity_end}}</div>
			    </div>
			    <div class="coupon-detail-condition">
			    	<span class="coupon-condition-introduction">使用说明：{{$coupon->description}}</span>	
			    </div>
			</ol>
	</div>
@stop
@section('addJs')

@stop
@section("jsSDK")
<script type="text/javascript">
	//  //微信分享
    wx.config(<?php echo $js->config(array('onMenuShareTimeline','onMenuShareAppMessage'), false) ?>);
 	
	document.addEventListener('WeixinJSBridgeReady', function onBridgeReady() {
                // 发送给好友
                WeixinJSBridge.on('menu:share:appmessage', function (argv) {
                    WeixinJSBridge.invoke('sendAppMessage', {
                        "appid": "123",
                        "img_url": "http://shop.dataguiding.com/shop/images/coupon/coupon.png",
                        "img_width": "160",
                        "img_height": "160",
                        "link": "http://shop.dataguiding.com/shop/coupon/share/{{Session::get('brand_id')}}/{{$coupon->id}}",
                        "desc":  "德灵微店发放优惠券了~",
                        "title": "我抢到优惠券了！召唤小伙伴一起来抢，手慢无！"
                    }, function (res) {
                        console.log(res);
                    })
                });

                // 分享到朋友圈
                WeixinJSBridge.on('menu:share:timeline', function (argv) {
                    WeixinJSBridge.invoke('shareTimeline', {
                        "img_url": "http://shop.dataguiding.com/shop/images/coupon/coupon.png",
                        "img_width": "160",
                        "img_height": "160",
                        "link": "http://shop.dataguiding.com/shop/coupon/share/{{Session::get('brand_id')}}/{{$coupon->id}}",
                        "desc":  "德灵微店发放优惠券了~",
                        "title": "我抢到优惠券了！召唤小伙伴一起来抢，手慢无！"
                    }, function (res) {
                        console.log(res);
                    });
                });
            }, false)
</script>
@stop