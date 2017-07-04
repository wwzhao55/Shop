@extends('layouts.app')
@section('siderbar')
@include('layouts.siderbar')
@endsection
@section('content')
    <link rel="stylesheet" type="text/css" href="{{URL::asset('shop/css/fileinput.min.css')}}">
    <script src="{{URL::asset('shop/js/fileinput.min.js')}}"></script>
    <div class="col-md-9 ">
        <div class="panel panel-default">
            <div class="panel-heading">添加素材</div>
            <div class="panel-body">
                <form action="/Admin/App/material/add" method="post" class="form" enctype="multipart/form-data">
                    {!! csrf_field() !!}
                    <div class="col-md-12">
                        <div class="box box-info">
                            <div class="box-header with-border">
                                <h3 class="box-title">添加素材</h3>
                                <div class="box-tools pull-right">
                                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                                </div>
                            </div>
                            <div class="box-body">
                                <div class="form-group">
                                    <select name="type" class="form-control">
                                        <option value="0">app启动图片</option>
                                        <option value="2">app主题图片</option>
                                        <option value="1">app广告图片</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <input id="images" class="file" name="material" type="file" multiple data-preview-file-type="any" >
                                    <input name='img_src' id="image" type="text" hidden="">
                                </div>
                                <hr>
                                <button type="submit" class="btn btn-info">添加素材</button>
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
            //uploadAsync:false,
            uploadUrl: '/Admin/App/material/add/startlogo', // you must set a valid URL here else you will get an error
        }).on('fileuploaded', function(event, data, previewId, index, reader) {
            console.log(data);
            console.log(event);
            console.log(previewId);
            console.log(index);
            console.log(reader);
            files = data.files;
            var image = $('#image').attr('value');

            for(var i=0;i<files.length;i++){
                file = files[i];
                if(image){
                    image = image+(file.name)+',';
                }else{
                    image = (file.name)+',';
                }
            }

            $('#image').attr('value',image);
        }).on('fileloaded',function(event, file, previewId, index, reader){
            $(".kv-file-upload").remove();
        });
    </script>
@endsection