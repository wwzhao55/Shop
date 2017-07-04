@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{URL::asset('admin/css/register.css')}}">
  <div class="register-box">
          <div class="register-box-logo">
               <img src="{{URL::asset('admin/img/logo.png')}}" class="logo-login" />
               <span class="register-box-word">找回密码</span>
           </div>
            <div class="register-box-msg">
               <form class="form-horizontal" id="register" role="form" method="POST" action="{{ url('/auth/repassword') }}" onsubmit="return check()">
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
                    <label for="verification_code" class="register-msg ">新密码&nbsp&nbsp&nbsp:</label><input type="password" id="setPassword" name="password" placeholder="8~20个字符" />

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
                    <!-- <img src="{{URL::asset('admin/img/btn-register.png')}}" id="btn_register"> -->
                    <input type="button" name="" value="确认" id="btn_register" >
                     <!-- <button type="submit" id="btn_submit" hidden> 
                      </button> -->
                       <span class="help-block">
                                    <strong>{{ Session::get('Message') }}</strong>
                                </span>
                    </div>
                </form>
                </div>
               

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
                if(data.status!='success'){
                  alert(data.message);
                  $("input[name='account']").val('');
                }
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
    
      $('#register').submit();

       

   })
      function check(){
        if ($("input[name='account']").parents(".form-group").has(".rewarning")) {
            $("input[name='account']").parents(".form-group").find(".rewarning").remove();
        }
        if ($("input[name='code']").parents(".form-group").has(".rewarning")) {
            $("input[name='code']").parents(".form-group").find(".rewarning").remove();
        }
        if ($("input[name='password']").parents(".form-group").has(".rewarning")) {
            $("input[name='password']").parents(".form-group").find(".rewarning").remove();
        }
        if ($("input[name='password_confirmation']").parents(".form-group").has(".rewarning")) {
            $("input[name='password_confirmation']").parents(".form-group").find(".rewarning").remove();
        }
        var phonenum=$("input[name='account']").val();
        if (phonenum == "" || phonenum == null) {
            var span = $("<div class='rewarning'>请您输入手机号！</div>");
            $("input[name='account']").parents(".form-group").append(span);
        }
        var coding=$("input[name='code']").val();
        if (coding == "" || coding == null) {
            var span = $("<div class='rewarning'>请您输入验证码！</div>");
            $("input[name='code']").parents(".form-group").append(span);
        }
        var pass_word=$("input[name='password']").val();
        if (pass_word == "" || pass_word == null) {
            var span = $("<div class='rewarning'>请您输入6到12位的数字或字母!！</div>");
            $("input[name='password']").parents(".form-group").append(span);
        }
        var re_pass_word=$("input[name='password_confirmation']").val();
        if (re_pass_word == "" || re_pass_word == null) {
            var span = $("<div class='rewarning'>请您再次输入密码！</div>");
            $("input[name='password_confirmation']").parents(".form-group").append(span);
        }
                if(phonenum&&coding&&pass_word&&re_pass_word){
                    return true;
                }else{
                    return false;
                }
      }
            $("input[name='account']").on("blur", function () {
                if ($("input[name='account']").parents(".form-group").has(".rewarning")) {
                    $("input[name='account']").parents(".form-group").find(".rewarning").remove();
                }
                var phonenum=$("input[name='account']").val();
                if (phonenum == "" || phonenum == null) {
                    var span = $("<div class='rewarning'>请您输入手机号！</div>");
                    $("input[name='account']").parents(".form-group").append(span);
                }
                else if ($("input[name='account']").parents(".form-group").has(".rewarning")) {
                    {
                        $("input[name='account']").parents(".form-group").find(".rewarning").remove();
                    }

                }
            });
            $("input[name='code']").on("blur", function () {
                if ($("input[name='code']").parents(".form-group").has(".rewarning")) {
                    $("input[name='code']").parents(".form-group").find(".rewarning").remove();
                }
                var coding=$("input[name='code']").val();
                if (coding == "" || coding == null) {
                    var span = $("<div class='rewarning'>请您输入验证码！</div>");
                    $("input[name='code']").parents(".form-group").append(span);
                }
                else if ($("input[name='code']").parents(".form-group").has(".rewarning")) {
                    {
                        $("input[name='code']").parents(".form-group").find(".rewarning").remove();
                    }

                }
            });
      var pass_word,re_pass_word;
      $("input[name='password']").on("blur", function () {
        if ($("input[name='password']").parents(".form-group").has(".rewarning")) {
            $("input[name='password']").parents(".form-group").find(".rewarning").remove();
        }
        pass_word=$("input[name='password']").val();
        if (pass_word == "" || pass_word == null) {
            var span = $("<div class='rewarning'>请您输入6到12位的数字或字母!！</div>");
            $("input[name='password']").parents(".form-group").append(span);
        }
        else if ($("input[name='password']").parents(".form-group").has(".rewarning")) {
            $("input[name='password']").parents(".form-group").children(".rewarning").remove();
        }
    });
    $("input[name='password_confirmation']").on("blur", function () {
        if ($("input[name='password_confirmation']").parents(".form-group").has(".rewarning")) {
            $("input[name='password_confirmation']").parents(".form-group").find(".rewarning").remove();
        }
        re_pass_word=$("input[name='password_confirmation']").val();
        if (re_pass_word == "" || re_pass_word == null) {
            var span = $("<div class='rewarning'>请您再次输入密码！</div>");
            $("input[name='password_confirmation']").parents(".form-group").append(span);
        }
        else if (pass_word !== re_pass_word) {
            var span = $("<span class='rewarning'>您输入确认密码和刚才输入的密码不一致，请重新输入!</span>");
            $("input[name='password_confirmation']").parents(".form-group").append(span);
        }
        else if ($("input[name='password_confirmation']").parents(".form-group").has(".rewarning")) {
            $("input[name='password_confirmation']").parents(".form-group").children(".rewarning").remove();
        }
    });
</script>
@endsection
