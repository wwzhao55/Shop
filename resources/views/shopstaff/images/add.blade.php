@extends('layouts.app')
@section('siderbar')
@include('layouts.siderbar')
@endsection
@section('content')
    <link rel="stylesheet" type="text/css" href="{{URL::asset('shop/css/fileinput.min.css')}}">
    <script src="{{URL::asset('shop/js/fileinput.min.js')}}"></script>
    <div class="col-md-9 ">
        <div class="panel panel-default">
            <div class="panel-heading">添加商品</div>
            <div class="panel-body">
                <form action="/Shopstaff/commodity/add" method="post" class="form" enctype="multipart/form-data">
                    {!! csrf_field() !!}
                    <div class="col-md-12">
                        <div class="box box-info">
                            <div class="box-header with-border">
                                <h3 class="box-title">添加商品图片</h3>
                                <div class="box-tools pull-right">
                                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                                </div>
                            </div>
                            <div class="box-body">
                                <div class="form-group">
                                    <input id="images" class="file" name="images" type="file" multiple data-preview-file-type="any" >
                                </div>
                                <hr>
                            </div><!-- /.box-body -->
                        </div><!-- /.box -->
                    </div>
                    
                </form>    
            </div>
        </div>
    </div>
    <script>
        $("#images").fileinput({
            uploadUrl: '/Shopstaff/images/uploadimg', // you must set a valid URL here else you will get an error
        });
    </script>
@endsection