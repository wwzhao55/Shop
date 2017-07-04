@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{URL::asset('admin/css/register.css')}}">
  <div class="register-box">
          <div class="register-box-logo">
               <img src="{{URL::asset('admin/img/logo.png')}}" class="logo-login" />
               <span class="register-box-word">注册</span>
           </div>
            <div class="register-box-msg">
                <form class="form-horizontal" role="form" method="POST" action="{{ url('/auth/register') }}">
                        {!! csrf_field() !!}
                 <div class="form-group{{ $errors->has('account') ? ' has-error' : '' }}">
                    <div class="msg-detail">
                    <label for="phoneNumber" class="register-msg">手机号码&nbsp:</label><input type="text" id="phoneNumber" name="account" value="{{ old('account') }}"/>
                    </div>
                    </div>

                 <div class="form-group{{ $errors->has('code') ? ' has-error' : '' }}">
                    <div class="msg-detail">
                    <label for="loginKey" class="register-msg msg-code">短信校验码&nbsp:</label><input type="text" id="loginKey" name="code" value="{{ old('code') }}"/><input type="button" value="获取" class="getMsg" id="getcode" onclick="getCode()"  />
                      @if ($errors->has('code'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('code') }}</strong>
                                    </span>
                      @endif
                    </div>
                    </div>

                <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}"> 
                    <div class="msg-detail">
                    <label for="verification_code" class="register-msg ">设置密码&nbsp:</label><input type="password" id="setPassword" name="password" placeholder="8~20个字符" />

                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                    <p class="suggestion">8~20个字符,包含字母和数字，建议字母包含大小写组合</p>
                    </div>
                    </div>

                 <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                    <div class="msg-detail">
                    <label for="phoneNumber" class="register-msg">确认密码&nbsp:</label><input type="password" id="confirm-key" placeholder="重复您设置的密码" name="password_confirmation">
                      @if ($errors->has('password_confirmation'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password_confirmation') }}</strong>
                                    </span>
                                @endif

                    </div>
                    </div>
                
                    <div class="msg-detail">
                    <img src="{{URL::asset('admin/img/btn-register.png')}}" id="btn_register">
                     <button type="submit" id="btn_submit" hidden> 
                      </button>
                       <span class="help-block">
                                    <strong>{{ Session::get('Message') }}</strong>
                                </span>
                    </div>
                </form>
                </div>
               

        </div>
        <div class="count">
            <span class="autoLogin">已有账户？</span>
            <a href="{{ url('/auth/login') }}" class="forget_key">登录</a>
        </div>

<script>
    $.ajaxSetup({
          headers: { 'X-CSRF-TOKEN' : '{{ csrf_token() }}' }
    });
         var flag=true;
    function getCode(){
       
        if(flag){
            $.ajax({
            type:'POST',
            url:'/auth/message',
            data:{
                "phone":$("input[name='account']").val()
           },
            dataType:'json',
            success:function(data){
                
                console.log(data);
            },
            error:function(){
                $.alert("网络异常，请稍后刷新重试")
            }
        });
           flag=false;

        }
        
       else{
        layer.tips("不要连续获取，请刷新后重试！", $("#getcode"), {
           tips: [1, '#3595CC'],
            time: 2000
         });

       } 

    }
           $("#btn_register").on('click',function(){
       $("#btn_submit").click();

   })
</script>
@endsection
