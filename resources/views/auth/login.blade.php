@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{URL::asset('admin/css/login.css')}}">
<div class="login-box">
          <div class="login-box-logo">
           <form class="form-horizontal" role="form" method="POST" action="{{ url('/login') }}">
                        {!! csrf_field() !!}
               <img src="{{URL::asset('admin/img/logo.png')}}" class="logo-login" />
               <span class="login-box-word">登录</span>
           </div>
            <div class="login-box-msg">
                <div class="login-box-msg-left">
                  
              <div class="form-group{{ $errors->has('account') ? ' has-error' : '' }}">
                    <div class="msg-detail">
                    <label for="phoneNumber" class="loginer-msg">手机号码:</label><input type="text" id="phoneNumber" name="account" value="{{ old('account') }}" />
                    @if ($errors->has('account'))
                           <span class="help-block">
                                  <strong>{{ $errors->first('account') }}</strong>
                             </span>
                                @endif
                    </div>
                    </div>

               <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                    <div class="msg-detail">
                    <label for="loginKey" class="loginer-msg">登录密码:</label><input type="password" id="loginKey" name="password"/>
                      @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                     @endif
                    </div>
                    </div>
                     <div class="form-group{{ $errors->has('captcha') ? ' has-error' : '' }}">
                    <div class="msg-detail">
                    <label for="verification_code" class="loginer-msg login_code">验证码:</label>
                    <input type="text" id="verification_code" name="captcha"  />
                    <a href="javascript:none" onclick=recaptcha() class="picChange"><img src="{{captcha_src()}}" id="captcha" style="width:27.5%;height:46px;"></a>
                    @if ($errors->has('captcha'))
                         <span class="help-block">
                          <strong>{{ $errors->first('captcha') }}</strong>
                          </span>
                     @endif
                   </div>
                    </div>
                    <div class="msg-detail">
                    <input type="checkbox"  name="remember"  id="checkBox" hidden />
                    <img src="{{URL::asset('admin/img/1dot.png')}}" id="checkBox1"/>
                     <span class="autoLogin">三天以内自动登录</span>
                     <a href="{{ url('auth/repassword') }}" class="forget_key">忘记密码?</a>
                     <span class="help-block">
                      <strong>{{ Session::get('Message') }}</strong>
                    </span>
                    </div>
                    <div class="msg-detail">
                    <!-- <img src="{{URL::asset('admin/img/btn-Login.png')}}" id="btn_login"> -->
                    <input type="button" name="" value="登录" id="btn_login"> 
                     <button type="submit" id="btn_submit" hidden> 
                      </button>
                    </div>
               
                </div>
                <!-- <div class="login-box-msg-right">
                    <div id="code_box">
                        <div id="code_img"></div>
                        <div id="app_introduce">
                            <span class="someDetail">扫码下载手机客户端<br/></span>
                            <span class="someDetail">随时随地管理你的店铺</span>
                        </div>
                        
                    </div>
                </div> -->

           </div> 
           </form>    
        </div>
        <!-- <div class="count">
            <span class="autoLogin">没有账户？</span>
            <a href="{{ url('/auth/register') }}"  class="forget_key">注册</a>
        </div> -->
  

  <script>
    function recaptcha(){
        $.get('/auth/captcha',function(data){
            $('#captcha').attr("src",data.url);
        }) ;
        
    }
   $("#btn_login").on('click',function(){
       $("#btn_submit").click();
   });
    var src1="{{URL::asset('admin/img/1dot.png')}}";
    var src2="{{URL::asset('admin/img/dot.png')}}";
    // if($('#checkBox').attr("src")==src1){
    //     return 0;
    // }else{
    //     return 1;
    // }
   $('#checkBox1').on('click',function(){
      var src1="{{URL::asset('admin/img/1dot.png')}}";
      var src2="{{URL::asset('admin/img/dot.png')}}";
      if($(this).attr("src")==src1){
        $(this).attr('src',src2);
        // return 1;
      }else{
        $(this).attr('src',src1);        
        // return 0;
      }
      $('#checkbox').click();
   });
</script>       
@endsection
