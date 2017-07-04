<!-- auth:zww
	 date:2016.07.20 
-->
@extends('layouts.shop')

@section('title')
<title>{{Session::get('brand_name')}}</title>
@stop
@section('addCss')
<link href="{{ URL::asset('shop/css/wechat.css')}}" rel="stylesheet">
@stop
@section('content')
<div id="focus">
	<img src="http://open.weixin.qq.com/qr/code/?username={{$account}}" class="focus-image"><br/>
	<span class="tip">
		长按二维码关注[{{$name}}]微信公众号
	</span>
</div>
@stop
@section('addJs')

@stop
