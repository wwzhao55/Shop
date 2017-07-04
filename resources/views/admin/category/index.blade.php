@extends('layouts.app')
@section('siderbar')
@include('layouts.siderbar')
@endsection
@section('content')

    <div class="col-md-9 ">
        <div class="panel panel-default">
            <div class="panel-heading">商品分类列表</div>
            <div class="panel-body">
                @if(!$category_count)
                    <p class="bg-success" style="padding:15px">咦，还没有数据哎</p>
                @else
                    <table class="table">
                        <tr>
                            <th>分类id</th>
                            <th>分类名称</th>
                            <th>分类状态</th>
                            <th>操作</th>
                        </tr>
                        @foreach($category_lists as $list)
                            <tr>
                                <td>{{$list->id}}</td>
                                <td>{{$list->name}}</td>
                                <td>{{$list->status}}</td>
                                <td>
                                    <a href="/Admin/category/changestatus/{{$list->id}}">
                                        @if($list->status)禁用@else启用@endif
                                    </a>
                                    <a href="/Admin/category/delete/{{$list->id}}">删除</a>
                                </td>
                            </tr>
                        @endforeach    
                    </table> 
                @endif       
            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading">添加新分类</div>
            <div class="panel-body">
                 <form action="/Admin/category/add" method="post" class="form">
                    {!! csrf_field() !!}
                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label for="name">分类名称</label>
                            <input type="text" name="name" class="form-control" id="name" value="{{old('name')}}" placeholder="分类名称">
                            @if ($errors->has('name'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('name') }}</strong>
                                </span>
                            @endif
                        </div>                                                       
                     <button type="submit" class="btn btn-info"> 添加分类</button>
                     {{Session::get('Message')}}
                </form> 
            </div>
        </div>
    </div>
@endsection