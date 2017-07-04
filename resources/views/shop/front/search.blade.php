@extends('layouts.shop')

@section('title')
<title>{{Session::get('brand_name')}}</title>
@stop
@section('addCss')
<link href="{{ URL::asset('shop/css/wechat.css')}}" rel="stylesheet">
@stop
@section('content')
<div id="sea">
<div id="mysearch">
    <div id="search-top">
        <input name="s" type="search" class="search-content" aria-label="请输入搜索文字" placeholder="搜索本店所有商品" autofocus="autofocus" autocomplete="off" style="color:black" />
        <img src="{{asset('shop/images/fistPage/Search.png')}}" class="search-image">
        <span id="cancel">取消</span>
    </div>
    <div id="search_view">
        <div class="search-title1">
            <span>
                    最近搜索
            </span>
        </div>
        <img src="http://cache.dataguiding.com/img/shop/search/delete.png" class="search-delete">       
            <span class="y-n-history" hidden>{{count($recentSearch)}}</span>
            <div id="no-search-history">
                暂无搜索历史
            </div>

            <div id="y-search-history">
                @foreach ( $recentSearch as $list )   
                    <div class="search-content-his-pop"><span class="search-popular-commodity">{{$list}}</span></div>
                @endforeach
            </div>
 
        <div id="search-popular">
            <span class="search-title2">热门搜索</span>
            <div class="search-popular-list">
            @foreach ( $hotSearch as $list)
                <div class="search-content-his-pop"><span class="search-popular-commodity">{{$list}}</span></div>
            @endforeach
            </div>
        </div>
    </div>
    <!-- 幽灵按钮 -->
    @if($shopcart)
    <a href="{{ URL::asset('shop/shopcart/index')}}">
      <div id="ghost">
        <img src="http://cache.dataguiding.com/img/shop/firstPage/ghostcart.png">
      </div>
    </a>
    @endif
    <div id="search_result">
        
    </div>
    <div class="clear" style="clear:both"></div>
</div>
</div>
@stop

@section('addJs')
<script src="{{asset('shop/js/frontSearch.js')}}"></script>
@stop