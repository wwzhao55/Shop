@extends('layouts.app')
@section('siderbar')
@include('layouts.siderbar')
@endsection

@section('addCss')
<link rel="stylesheet" type="text/css" href="{{ URL::asset('shop/css/brandmanage-shop.css')}}">
@endsection

@section('content')
<div class="shop-manage">

   <div class="shop">
   <span class="shop-title">未入驻分店</span>
        <table class="shop-out">
            <thead>
                <tr>
                    <th width="15%">分店名</th>
                    <th width="15%">开通时间</th>
                    <th width="30%">地址</th>
                    <th width="15%">联系人</th>
                    <th width="15%">电话</th>
                    <th width="10%">操作</th>
                </tr>
            </thead>
            <tbody id="tab-contents">
                   @if($close_weishop_count)
                
                {{Session::get('Message')}}

                @foreach($close_weishop_lists as $list)
                <tr>
                    <td>{{$list->shopname}}</td>
                    <td>{{$list->created_at}}</td>
                    <td>{{$list->shop_province+shop}}</td>
                    <td>{{$list->contacter_name  }}</td>
                    <td>{{$list->contacter_phone}}</td>
                    <td><img class="more_detail" src="{{asset('shop/images/brandmanage/icon-more.png')}}"></td>
                </tr>
                @endforeach
                @endif
            </tbody> 
        </table>
    </div>
       @else
    <p class="bg-success" style="padding:15px">咦，还没有数据哎</p>
    @endif 
    </div>
  
    


<script type="text/javascript">
  
</script>
@endsection
