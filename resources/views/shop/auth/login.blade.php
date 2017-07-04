<!-- auth:zww
	 date:2016.08.01
	 content:update 
-->
@extends('layouts.shop')
@section('title')
<title>微信小店</title>
@endsection
@section('addCss')
<link rel="stylesheet" type="text/css" href="{{asset('shop/css/wechat.css')}}">
@stop
@section('header')
<header>
    <div class='center-box'>
    	登&nbsp录
    </div>
    <div class="right-box">
        <a href="/shop/register" style="color:#fff">注册</a>
    </div>
</header>
@endsection


@section('content')
<article>
	<form method='post' action='/shop/login'>
		{!! csrf_field() !!}
		<div class="weui_cells weui_cells_form" id="login-box">
			<div class="weui_cell">
		    	<div class="weui_cell_hd"><label class="weui_label">手机号</label></div>
		    	<div class="weui_cell_bd weui_cell_primary">
		      		<input class="weui_input" type="tel" name='phone' placeholder="请输入手机号">
		    	</div>
		  	</div>
		  	<div class='weui_cell'>
		  		<div class="weui_cell_hd"><label class="weui_label">密码</label></div>
		    	<div class="weui_cell_bd weui_cell_primary">
		      		<input class="weui_input" type="password" name='password' placeholder="请输入密码">
		    	</div>
		  	</div>
		</div>
		@if (Session::has('logFailedMessage'))
		<div class='errorTip'><i class="weui_icon_warn"></i><span class="error-tips-code">{{Session::get('logFailedMessage')}}</span></div>
		@endif
		<button class="weui_btn weui_btn_primary" type='submit'>登录</button>
	</form>
</article>
@endsection

