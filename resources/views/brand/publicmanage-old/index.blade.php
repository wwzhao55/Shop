<!-- auth:wuwenjia -->
@extends('layouts.app')
@section('siderbar')
@include('layouts.siderbar')
@endsection
@section('addCss')
<link rel="stylesheet" type="text/css" href="{{ URL::asset('shop/css/publicmanage.css')}}">
@endsection
@section('content')
        <div class="publicmanage">
            <div class="publicheading">公众号信息<button class="modify">确定</button></div>
            <div class="publicbody">
            	@if($has_public_number)
                <form action="/Brand/publicmanage/edit/{{$public_info['id']}}" method="post" class="form" enctype="multipart/form-data">
                    <div>
                        {{Session::get('Message')}}
                    </div>
                    {!! csrf_field() !!}
                    <div class="publicgroup{{ $errors->has('name') ? ' has-error' : '' }}">
                        <span>公众号名称：</span>
                        <input type="text" name="name" class="publicinput" value="{{$public_info['name']}}" placeholder="公众号名称">
                        @if ($errors->has('name'))
                            <span class="help-block">
                                <strong>{{ $errors->first('name') }}</strong>
                            </span>
                        @endif
                        <div class="mention">微信公众平台=>设置=>公众号设置=>账号详情=>公开信息</div>
                        <div class="clearfix" style="clear:both;"></div>
                    </div>
                    
                    <div class="publicgroup{{ $errors->has('weixin_id') ? ' has-error' : '' }}">
                        <span>微信号：</span>
                        <input type="text" name="weixin_id" class="publicinput" value="{{$public_info['weixin_id']}}" placeholder="微信号">
                        @if ($errors->has('weixin_id'))
                            <span class="help-block">
                                <strong>{{ $errors->first('weixin_id') }}</strong>
                            </span>
                        @endif
                        <div class="mention">微信公众平台=>设置=>公众号设置=>账号详情=>公开信息</div>
                        <div class="clearfix" style="clear:both;"></div>
                    </div>

                    <div class="publicgroup{{ $errors->has('originalid') ? ' has-error' : '' }}">
                        <span>公众号原始ID：</span>
                        <input type="text" name="originalid" class="publicinput" value="{{$public_info['originalid']}}" placeholder="公众号原始ID">
                        @if ($errors->has('originalid'))
                            <span class="help-block">
                                <strong>{{ $errors->first('originalid') }}</strong>
                            </span>
                        @endif
                        <div class="mention">微信公众平台=>设置=>公众号设置=>账号详情=>注册信息</div>
                        <div class="clearfix" style="clear:both;"></div>
                    </div>
                    

                    <div class="publicgroup{{ $errors->has('appid') ? ' has-error' : '' }}">
                        <span>公众号AppID（应用ID）：</span>
                        <input type="text" name="appid" class="publicinput" value="{{$public_info['appid']}}" placeholder="公众号AppID">
                        @if ($errors->has('appid'))
                            <span class="help-block">
                                <strong>{{ $errors->first('appid') }}</strong>
                            </span>
                        @endif
                        <div class="mention">微信公众平台=>开发=>基本配置=>开发者ID</div>
                        <div class="clearfix" style="clear:both;"></div>
                    </div>
                    

                    <div class="publicgroup{{ $errors->has('appsecret') ? ' has-error' : '' }}">
                        <span>公众号AppSecret：</span>
                        <input type="text" name="appsecret" class="publicinput" value="{{$public_info['appsecret']}}" placeholder="公众号AppSecret">
                        @if ($errors->has('appsecret'))
                            <span class="help-block">
                                <strong>{{ $errors->first('appsecret') }}</strong>
                            </span>
                        @endif
                        <div class="mention">微信公众平台=>开发=>基本配置=>开发者ID</div>
                        <div class="clearfix" style="clear:both;"></div>
                    </div>
                    

                    <div class="tip">您必须启动服务器配置才能获取到以下信息（微信公众平台=>开发=>基本配置=>服务器配置-启用）</div>

                    <div class="publicgroup{{ $errors->has('token') ? ' has-error' : '' }}">
                        <span>公众号Token(令牌)：</span>
                        <input type="text" name="token" class="publicinput" value="{{$public_info['token']}}" placeholder="公众号Token">
                        @if ($errors->has('token'))
                            <span class="help-block">
                                <strong>{{ $errors->first('token') }}</strong>
                            </span>
                        @endif
                        <div class="mention">微信公众平台=>开发=>基本配置=>服务器配置</div>
                        <div class="clearfix" style="clear:both;"></div>
                    </div>
                    

                    <div class="publicgroup{{ $errors->has('encodingaeskey') ? ' has-error' : '' }}">
                        <span>公众号EncodingAESKey(消息加解密密钥)：</span>
                        <input type="text" name="encodingaeskey" class="publicinput" value="{{$public_info['encodingaeskey']}}" placeholder="公众号EncodingAESKey">
                        @if ($errors->has('encodingaeskey'))
                            <span class="help-block">
                                <strong>{{ $errors->first('encodingaeskey') }}</strong>
                            </span>
                        @endif
                        <div class="mention">微信公众平台=>开发=>基本配置=>服务器配置</div>
                        <div class="clearfix" style="clear:both;"></div>
                    </div>
                    <button type="submit" class="btn" hidden></button>
                     
                </form>
            	           	
				@else   
				<p class="bg-success" style="padding:15px">咦，还没有数据哎,<a href="/Brand/publicmanage/add/{{$brand_id}}">添加公众号</a></p>             
            	@endif
            </div>
        </div>
        <script type="text/javascript">
            $('.side-list').find('.in').removeClass('in');
            $('#wexin-manage').addClass('in');
            $('.side-list').find('.onsidebar').removeClass('onsidebar');
            $('.weixinmanage').addClass('onsidebar');
            $('.side-list').find('.onsidebarlist').removeClass('onsidebarlist');
            $('.publicmanagelist').addClass('onsidebarlist');
            $('.modify').on('click',function(){
                $('.btn').click();
            })
        </script>

@endsection