@extends('layouts.app')
@section('siderbar')
@include('layouts.siderbar')
@endsection
@section('content')

    <div class="col-md-9 ">
        <div class="panel panel-default">
            <div class="panel-heading">商品标签列表</div>
            <div class="panel-body">
                @if(!$tag_count)
                    <p>咦，还没有标签哎</p>
                @else
                    <table class="table">
                        <tr>
                            <th>标签id</th>
                            <th>标签名称</th>
                            <th>标签状态</th>
                            <th>操作</th>
                        </tr>
                        @foreach($tag_lists as $list)
                            <tr>
                                <td>{{$list->id}}</td>
                                <td>{{$list->name}}</td>
                                <td>{{$list->status}}</td>
                                <td>
                                    <a href="">禁用</a>
                                    <a href="">删除</a>
                                </td>
                            </tr>
                        @endforeach    
                    </table> 
                @endif       
            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading">添加新标签</div>
            <div class="panel-body">
                 <form action="/Shopstaff/tag/add" method="post" class="form">
                    {!! csrf_field() !!}


                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label for="name">标签名称</label>
                            <input type="text" name="name" class="form-control" id="name" value="{{old('name')}}" placeholder="标签名称">
                            @if ($errors->has('name'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('name') }}</strong>
                                </span>
                            @endif
                        </div>

                                              
                    
                     <button type="submit" class="btn btn-info"> 添加标签</button>
                     {{Session::get('Message')}}
                </form> 
            </div>
        </div>
    </div>
@endsection