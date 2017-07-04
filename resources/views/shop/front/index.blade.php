<!-- auth:zww
   date:2016.07.27 
-->
@extends('layouts.shop')

@section('title')
<title>{{Session::get('brand_name')}}</title>
@stop
@section('addCss')
<!-- <link href="http://cache.dataguiding.com/css/shop/wechat.css" rel="stylesheet"> -->
<link href="{{asset('shop/css/wechat.css')}}" rel="stylesheet">
@stop
@section('content')
<div class='loading'><img src="{{asset('shop/images/loading.gif')}}"></div>
<div id="firstPage">
<!-- 顶部 -->
<div id="firstpageTop">
      <a href="{{ URL::asset('shop/front/branch')}}">
        <div class="Branch">
          <img src="{{asset('shop/images/firstPage/branch-location.png')}}" class="branch-location">
          <span class="branch-address">{{$shopaddress}}</span>
        </div>
      </a>
</div>
<!-- 轮播广告 -->
<div id="carousel" style="height:350px;">
  <div class="commodity-carousel swiper-container" data-pagination='.swiper-pagination'>
      <div class="swiper-wrapper">
      @foreach($shufflings as $list)
        <div class="swiper-slide"><a href="{{$list->http_src}}"><img src="{{asset($list->img_src)}}" alt="" width='100%' height='350px'></a></div>
      @endforeach
      </div>
       <!-- If we need pagination --> 
       <div class="swiper-pagination"></div>  
  </div>
</div>
<!-- 搜索框 -->
<div class="search">
    <div id="search-top">
      <!-- <input name="s" type="search" class="search-box" aria-label="请输入搜索文字" placeholder="请输入要搜索商品关键字" autofocus="autofocus" autocomplete="off" style="color:black" /> -->
        <img src="{{asset('shop/images/search/Search.png')}}" class="search-image-1" width='30px' height='30px'>
      <button type="search" class="search-box">请输入要搜索商品关键字</button>
    </div>
</div>
<!-- 地区 -->
<div id="selectCountry">
  <div class="area">
    @for($i=0;$i<count($tags);$i++)
      @for($j=0;$j<count($tags[$i]);$j++)
        @if($tags[$i][$j]->name!='')
          @if ( $i==0 && $j==0 )
            <div class="country"><span class="countryName on" id="{{$tags[$i][$j]->id}}">{{$tags[$i][$j]->name}}</span></div>
          @else
            <div class="country"><span class="countryName" id="{{$tags[$i][$j]->id}}">{{$tags[$i][$j]->name}}</span></div>
          @endif
        @endif
      @endfor
    @endfor    
    <span class="down"></span> 
  </div> 



  <!-- 更改地区 -->
  <div id="changeArea">
    <div class="change-title">
      <span>切换分类</span>
      <span class="up" ></span>
    </div>
    <table class="change-list">
      @for($i=0;$i<count($tags);$i++)
        <tr class="change-rows">
          @for($j=0;$j<count($tags[$i]);$j++)
            @if ( $i==0 && $j==0 )
              <td class="change-cols on" id="{{$tags[$i][$j]->id}}"><span class="changecountryName">{{$tags[$i][$j]->name}}</span></td>
            @else
              <td class="change-cols" id="{{$tags[$i][$j]->id}}"><span class="changecountryName">{{$tags[$i][$j]->name}}</span></td>
            @endif
          @endfor
        </tr>
      @endfor    
    </table>
  </div> 
</div>
<!-- 商品列表 -->
<div id="commodity">
  @if( $commoditys!=null)
    @foreach( $commoditys as $group => $list )
    <div id='{{$group}}'>
        @foreach( $list['commodity'] as $k => $item )
          @if( $k==0)
              <div class="list-title1 list-title2">{{$group}}</div>
                <a href="/shop/front/detail?commodity_id={{$item->id}}" class='commodity-block'>
                  <div  class='commodity-list'>
                    <div class='list-img'>
                        <img src="{{ asset($item->main_img) }}" class="commodity-image" width='330px' height='330px'>
                        <div class='list-value'>￥{{$item->base_price}}</div>
                    </div>
                    <div class='list-title'>
                        {{$item->commodity_name}}
                    </div>
                  </div>
                </a>
          @else
            <a href="/shop/front/detail?commodity_id={{$item->id}}" class='commodity-block'>
              <div class='commodity-list' id="{{$item->id}}">
                <div class='list-img'>
                    <img src="{{ asset($item->main_img) }}" class="commodity-image">
                    <div class='list-value'>￥{{$item->base_price}}</div>
                </div>
                <div class='list-title'>
                    {{$item->commodity_name}}
                </div>
              </div>
            </a>
          @endif
        @endforeach
        
        @if($list['finish']==false)
          <a href='/shop/front/product/{{$list['commodity'][0]->group_id}}'>
            <div class="commodity-more">查看更多</div>
          </a>
        @endif
        <div class='clear' style='clear:both'></div>
    </div>
    @endforeach
    <div class='clear' style='clear:both'></div>
    <div class='commodity-cover'></div>
  @else
    <div class="no-commoditys">暂无上架商品！</div>
  @endif 

  <!-- 底部更多显示 -->
  <div class="commodity-down">
     <a href="/shop/front/index"><span class="commodity-down-list">店铺首页</span></a>
     <a href="/shop/vip/index"><span class="commodity-down-list">会员中心</span><a>
     <a href="/shop/front/focus"><span class="commodity-down-list">关注我们</span></a>
  </div>
</div> 
<!-- 幽灵按钮 -->
  @if($coupons)
    <a href='/shop/coupon/couponcenter'>
      <div id="ghost"><img src="{{asset('shop/images/coupon/coupon.png')}}"></div>
    </a>
  @endif
</div> 
<!-- 底部固定按钮  -->
<div id="bottom-Btn">
      <a href="{{ URL::asset('shop/front/index')}}">
        <span class="home-img">
          <img src="{{asset('shop/images/firstPage/icon-home.png')}}" class="home">
        </span>
      </a>
      <a href="{{ URL::asset('shop/shopcart/index')}}">
        <span class="into-shopcart">购物车</span>
      </a>
      <a href="{{ URL::asset('shop/vip/index')}}">
        <span class="into-myorder">我的订单</span>
      </a>
</div> 
<!-- 搜索弹出框 -->
<div id="sea">
  <div id="mysearch">
      <div id="search-top">
          <form action="javascript:return true;">
            <input name="s" type="search" class="search-content" aria-label="请输入搜索文字" placeholder="搜索本店所有商品" autofocus="autofocus" autocomplete="off" style="color:black" />
          </form>
          <span class="search-image"><img src="{{asset('shop/images/search/Search.png')}}" width='30px' height='30px'></span>
          <span id="cancel">取消</span>
      </div>
      <div id="search_result"></div>
      <div id="search_view">
          <div class="search-title1">
              <span>
                      最近搜索
              </span>
          </div>
          <span class="search-delete"><img src="{{asset('shop/images/search/delete.png')}}"></span>       
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
      <div class="clear" style="clear:both"></div>
  </div>
</div>
@stop

@section('addJs')
<script src="{{asset('shop/weui/js/swiper.min.js')}}"></script>
<script src="{{asset('shop/js/frontIndex.js')}}"></script>
@stop