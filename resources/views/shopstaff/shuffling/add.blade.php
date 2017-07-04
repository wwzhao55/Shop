@extends('layouts.app')
@section('siderbar')
@include('layouts.siderbar')
@endsection
@section('content')
    <link rel="stylesheet" type="text/css" href="{{URL::asset('shop/css/fileinput.min.css')}}">
    <script src="{{URL::asset('shop/js/fileinput.min.js')}}"></script>
    <div class="col-md-9 ">
        <div class="panel panel-default">
            <div class="panel-heading">添加</div>
            <div class="panel-body">
                <form action="/Shopstaff/shuffling/add" method="post" class="form" enctype="multipart/form-data">
                    {!! csrf_field() !!}
                    <div class="col-md-12">
                        <div class="box box-info">
                            <div class="box-header with-border">
                                <h3 class="box-title">添加轮播图片</h3>
                                <div class="box-tools pull-right">
                                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                                </div>
                            </div>
                            <div class="box-body">
                                <div class="form-group{{ $errors->has('http_src') ? ' has-error' : '' }}">
                                    <label for="http_src">轮播图跳转地址</label>
                                    <input type="text" name="http_src" class="form-control" id="http_src" value="{{old('http_src')}}" placeholder="轮播图跳转地址">
                                    @if ($errors->has('http_src'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('http_src') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group{{ $errors->has('img_src') ? ' has-error' : '' }}">
                                    <label for="img_src">轮播图</label>
                                    <input id="images" class="file" name="img_src" type="file" data-preview-file-type="any" value="{{old('img_src')}}">
                                    @if ($errors->has('img_src'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('img_src') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <button type="submit" class="btn btn-info">添加轮播图</button>
                                {{Session::get('Message')}}
                            </div><!-- /.box-body -->
                        </div><!-- /.box -->
                    </div>
                    
                </form>    
            </div>
        </div>
    </div>
    <script>
        $("#images").fileinput({
            showUpload:false,
            //uploadUrl: '/Shopstaff/shuffling/uploadimg', // you must set a valid URL here else you will get an error
        });
    </script>
@endsection