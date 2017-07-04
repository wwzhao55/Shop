@extends('layouts.shop')

@section('title')
<title></title>
@stop
@section('addCss')
<link href="{{asset('shop/css/wechat.css')}}" rel="stylesheet">
@stop
@section('content')
<div class='more-commodity'>
	<!-- 商品列表 -->
	<div id="commodity">
		<div class="list-title1 list-title2"></div>
	    @foreach( $commodity as $list )
      <div class='kind-name' hidden>{{$list->group_name}}</div>
            <a href="/shop/front/detail?commodity_id={{$list->id}}" class='commodity-block'>
                <div  class='commodity-list'>
                    <div class='list-img'>
                        <img src="{{ asset($list->main_img) }}" class="commodity-image" width='330px' height='330px'>
                        <div class='list-value'>￥{{$list->price}}</div>
                    </div>
                    <div class='list-title'>
                        {{$list->commodity_name}}
                    </div>
                </div>
            </a>
	    @endforeach        	
	    <div class='clear' style='clear:both'></div>
	    <!-- 商品底部显示 -->
		<div class="commodity-down">
		    <a href="/shop/front/index"><span class="commodity-down-list">店铺首页</span></a>
		    <a href="/shop/vip/index"><span class="commodity-down-list">会员中心</span><a>
		    <a href="/shop/front/focus"><span class="commodity-down-list">关注我们</span></a>
		</div>
	</div>
	
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
@stop

@section('addJs')
<script type="text/javascript">
$('.list-title1').html($('.kind-name').html());
</script>
@stop