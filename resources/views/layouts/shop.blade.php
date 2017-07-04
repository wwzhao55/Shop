<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		@yield('title')
		<meta name="viewport" content="width=750, user-scalable=no">
		<meta name="csrf-token" content="{{ csrf_token() }}"/>
		<link href="{{ URL::asset('shop/weui/lib/weui.min.css')}}" rel="stylesheet">
		<link href="{{ URL::asset('shop/css/bootstrap.min.css')}}" rel="stylesheet">
		<link rel="stylesheet" type="text/css" href="{{ URL::asset('shop/weui/css/jquery-weui.min.css')}}">
		@yield('addCss')
	</head>
	<body>
		@yield('header')
		@yield('content')
		<script src="{{ URL::asset('shop/js/jquery.min.js')}}"></script>
		<script src="{{ URL::asset('shop/js/bootstrap.min.js')}}"></script>
		<script src="{{asset('shop/weui/js/jquery-weui.js')}}"></script>
		<script src="{{asset('shop/js/font-family.js')}}"></script>
		<script src="{{URL::asset('shop/js/jweixin-1.0.0.js')}}"></script>
		<!-- <script src="/min?f=cache.dataguiding.com/plugins/jquery/jquery.min.js,cache.dataguiding.com/plugins/bootstrap/js/bootstrap.min.js,cache.dataguiding.com/plugins/weui/js/jquery-weui.js"></script> -->
	    @yield('addJs')
	    @section('jsSDK')
	    	<script type="text/javascript">
			  wx.config(<?php echo $js->config(array('onMenuShareTimeline','onMenuShareAppMessage'), false) ?>);
			  document.addEventListener('WeixinJSBridgeReady', function onBridgeReady() {
			                // 发送给好友
			                WeixinJSBridge.on('menu:share:appmessage', function (argv) {
			                    WeixinJSBridge.invoke('sendAppMessage', {
			                        "appid": "123",
			                        "img_url": "http://shop.dataguiding.com/shop/images/firstPage/shop_logo.jpg",
			                        "img_width": "160",
			                        "img_height": "160",
			                        "link": "http://shop.dataguiding.com/shop/front/index?b={{Session::get('brand_id')}}&s={{Session::get('shop_id')}}&from=share",
			                        "desc":  "{{$shopaddress}}",
			                        "title": "{{Session::get('brand_name')}}"
			                    }, function (res) {
			                        console.log(res);
			                    })
			                });

			                // 分享到朋友圈
			                WeixinJSBridge.on('menu:share:timeline', function (argv) {
			                    WeixinJSBridge.invoke('shareTimeline', {
			                        "img_url": "http://shop.dataguiding.com/shop/images/firstPage/shop_logo.jpg",
			                        "img_width": "160",
			                        "img_height": "160",
			                        "link": "http://shop.dataguiding.com/shop/front/index?b={{Session::get('brand_id')}}&s={{Session::get('shop_id')}}&from=share",
			                        "desc":  "{{$shopaddress}}",
			                        "title": "{{Session::get('brand_name')}}"
			                    }, function (res) {
			                        console.log(res);
			                    });
			                });
			            }, false)
			</script>
	    @show
	</body>
</html>
