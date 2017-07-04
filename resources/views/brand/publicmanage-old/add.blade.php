@extends('layouts.app')
@section('siderbar')
@include('layouts.siderbar')
@endsection
@section('content')

    <div class="col-md-9 ">
        <div class="panel panel-default">
            <div class="panel-heading">添加公众号</div>
            <div class="panel-body">
            	<form action="/Brand/publicmanage/add" method="post" class="form">
                    {!! csrf_field() !!}
                    <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                        <label for="name">公众号名称</label>
                        <input type="text" name="name" class="form-control" id="name" value="{{old('name')}}" placeholder="公众号名称">
                        @if ($errors->has('name'))
                            <span class="help-block">
                                <strong>{{ $errors->first('name') }}</strong>
                            </span>
                        @endif
                    </div>

                    <div class="form-group{{ $errors->has('appid') ? ' has-error' : '' }}">
                        <label for="appid">公众号appid</label>
                        <input type="text" name="appid" class="form-control" id="appid" value="{{old('appid')}}" placeholder="公众号appid">
                        @if ($errors->has('appid'))
                            <span class="help-block">
                                <strong>{{ $errors->first('appid') }}</strong>
                            </span>
                        @endif
                    </div>

                    <div class="form-group{{ $errors->has('appsecret') ? ' has-error' : '' }}">
                        <label for="appsecret">公众号appsecret</label>
                        <input type="text" name="appsecret" class="form-control" id="appsecret" value="{{old('appsecret')}}" placeholder="公众号appsecret">
                        @if ($errors->has('appsecret'))
                            <span class="help-block">
                                <strong>{{ $errors->first('appsecret') }}</strong>
                            </span>
                        @endif
                    </div>

                    <div class="form-group{{ $errors->has('token') ? ' has-error' : '' }}">
                        <label for="token">公众号token</label>
                        <input type="text" name="token" class="form-control" id="token" value="{{old('token')}}" placeholder="公众号token">
                        @if ($errors->has('token'))
                            <span class="help-block">
                                <strong>{{ $errors->first('token') }}</strong>
                            </span>
                        @endif
                    </div>

                    <div class="form-group{{ $errors->has('encodingaeskey') ? ' has-error' : '' }}">
                        <label for="encodingaeskey">公众号encodingaeskey</label>
                        <input type="text" name="encodingaeskey" class="form-control" id="encodingaeskey" value="{{old('encodingaeskey')}}" placeholder="公众号encodingaeskey">
                        @if ($errors->has('encodingaeskey'))
                            <span class="help-block">
                                <strong>{{ $errors->first('encodingaeskey') }}</strong>
                            </span>
                        @endif
                    </div>

                    <div class="form-group{{ $errors->has('originalid') ? ' has-error' : '' }}">
                        <label for="originalid">公众号originalid</label>
                        <input type="text" name="originalid" class="form-control" id="originalid" value="{{old('originalid')}}" placeholder="公众号originalid">
                        @if ($errors->has('originalid'))
                            <span class="help-block">
                                <strong>{{ $errors->first('originalid') }}</strong>
                            </span>
                        @endif
                    </div>

                    
                     <button type="submit" class="btn btn-info "> 添加公众号</button>
                     {{Session::get('Message')}}
                </form>
            </div>
        </div>
    </div>

@endsection