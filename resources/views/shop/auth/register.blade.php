<!-- auth:zww
	 date:2016.07.30
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
    	注&nbsp册
    </div>
    <div class="right-box">
        <a href="/shop/login" style="color:#fff">登录</a>
    </div>
</header>
@endsection

@section('content')
<article>
	<form method='post' action='/shop/register'>
		{!! csrf_field() !!}
		<div class="weui_cells weui_cells_form" id="login-box">
			@if (Session::has('openid'))
			<div class="weui_cell" style="display: none;">
		    	<div class="weui_cell_hd"><label class="weui_label">openid</label></div>
		    	<div class="weui_cell_bd weui_cell_primary">
		      		<input class="weui_input" type="text" name='openid' value="{{Session::get('openid')}}">
		    	</div>
		  	</div>
		  	@endif
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
		  	<div class='weui_cell'>
		  		<div class="weui_cell_hd"><label class="weui_label">确认密码</label></div>
		    	<div class="weui_cell_bd weui_cell_primary">
		      		<input class="weui_input" type="password" name='repassword' placeholder="请再次输入密码">
		    	</div>
		  	</div>
		  	<div class="weui_cell weui_vcode">
			    <div class="weui_cell_hd"><label class="weui_label">验证码</label></div>
			    <div class="weui_cell_bd weui_cell_primary">
			      <input class="weui_input" type="number" name='code' placeholder="请输入验证码">
			    </div>
			    <div class="weui_cell_ft">
			      <button type='button' id='btn_getcode' class="weui_btn weui_btn_default" onclick='getCode()'>获取验证码</button>
			    </div>
			</div>
		</div>
		<div class='errorTip codeTip' style='display:none'><i class="weui_icon_warn"></i><span></span></div>
		@if (Session::has('regFailedMessage'))
		<div class='errorTip'><i class="weui_icon_warn"></i><span>{{Session::get('regFailedMessage')}}</span></div>
		@endif
		<button id='btn_submit' class="weui_btn weui_btn_disabled weui_btn_primary" type='submit' >注册</button>
	</form>
</article>
@endsection

@section('addJs')
<script type="text/javascript">
	//ajax设置csrf_token
	$.ajaxSetup({
          headers: { 'X-CSRF-TOKEN' : '{{ csrf_token() }}' }
    });
	function getCode(){
		$.ajax({
	        type:'POST',
	        url:'/shop/message',
	        data:{
	        	"phone":$("input[name='phone']").val()
	       },
	        dataType:'json',
	        success:function(data){
	            if(data.status == 'success'){
	            	$(".codeTip").hide();
	                var wait=60;
	                time($("#btn_getcode"));
	                $("#btn_submit").removeClass('weui_btn_disabled');
	                $("#btn_submit").attr("disabled",false);

	                function time(codebtn){
	                    if(wait == 0){
	                        codebtn.attr("disabled",false);
	                        codebtn.removeClass('weui_btn_disabled');
	                        codebtn.html("获取验证码");
	                        wait = 60;
	                    }else{
	                        codebtn.attr("disabled",true);
	                        codebtn.addClass('weui_btn_disabled');
	                        codebtn.html(wait+"秒后重新获取");
	                        wait--;
	                        setTimeout(function(){
	                            time(codebtn);
	                        },1000);
	                    }
	                }
	            }else{
	            	$(".codeTip span").html(data.msg);
	                $(".codeTip").css("display","block");
	                $("#btn_getcode").attr("disabled",false);
	            }
	        },
	        error:function(){
	        	$.alert("网络异常，请稍后刷新重试")
	        }
    	});
	}

</script>
@endsection

