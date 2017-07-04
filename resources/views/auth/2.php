@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{URL::asset('admin/css/login.css')}}">

<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Login</div>
                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="POST" action="{{ url('/login') }}">
                        {!! csrf_field() !!}

                        <div class="form-group{{ $errors->has('account') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">手机号码：</label>

                            <div class="col-md-6">
                                <input class="form-control" name="account" value="{{ old('account') }}">

                                @if ($errors->has('account'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('account') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">登录密码：</label>

                            <div class="col-md-6">
                                <input type="password" class="form-control" name="password">

                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('captcha') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">验证码：</label>

                            <div class="col-md-6">
                                <input type="captcha" class="form-control" name="captcha" placeholder="未启用~~">

                                @if ($errors->has('captcha'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('captcha') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label" ></label>
                            <div class="col-md-6">
                                <img src="{{captcha_src()}}" id="captcha">
                                <a href="javascript:none" onclick=recaptcha()>看不清？换一张</a>
                            </div>
                            
                        </div>
                        
                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="remember"> Remember Me
                                    </label>
                                </div>
                            </div>
                        </div>
<script>
    function recaptcha(){
        $.get('/auth/captcha',function(data){
            $('#captcha').attr("src",data.url);
        }) ;
        
    }
</script>
                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-btn fa-sign-in"></i>Login
                                </button>

                                <a class="btn btn-link" href="{{ url('auth/repassword') }}">Forgot Your Password?</a>

                                <span class="help-block">
                                    <strong>{{ Session::get('Message') }}</strong>
                                </span>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
