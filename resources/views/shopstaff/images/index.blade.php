@extends('layouts.app')
@section('siderbar')
@include('layouts.siderbar')
@endsection
@section('content')
    <script>
        $(function(){
            $.post('/Shopstaff/express/add',{
                name:'shuaidfgf',
                first_num:14,
                first_price:15,
                second_num:13,
                second_price:14,
                express_province:['beijing','shandong'],
            },function(data){
                console.log(data);
            })
        })
    </script>
    <div class="col-md-9 ">
        <div class="panel panel-default">
            <div class="panel-heading">商品图片首页</div>
            <div class="panel-body">
                @if($image_count)
                <a href="/Shopstaff/images/add" class="pull-right">添加图片</a>
                <table class="table">
                    <tr>
                        <th>图片id</th>
                        <th>图片展示</th>
                        <th>图片状态</th>
                        <th>操作</th>
                    </tr>
                    @foreach($image_lists as $list)
                        <tr>
                            <td>{{$list->id}}</td>
                            <td>
                                <div>
                                    <img src="{{asset($list->url)}}" width='50px' height='50px'>
                                </div>
                            </td>
                            <td>{{$list->status}}</td>
                            <td>
                                <a href="">删除</a>
                                <a href="">禁用</a>
                            </td>
                        </tr>
                    @endforeach    
                </table>
                @else
                <p>咦，还没有图片哎，<a href="/Shopstaff/images/add">添加图片</a></p>
                @endif      
            </div>
        </div>
    </div>
@endsection