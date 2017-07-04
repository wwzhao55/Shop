<!-- auth:zww
	 date:2016.07.20 
-->
@extends('layouts.shop')

@section('title')
<title>{{Session::get('brand_name')}}</title>
@stop
@section('addCss')
<link href="{{ URL::asset('shop/css/wechat.css')}}" rel="stylesheet">
@stop
@section('content')
	<div id="offlinestore">
	    <div class="offlinestoreContent">
		    <div class="offlineName">店铺：{{$shopinfo->shopname}}</div>
		    <img src="{{asset($shopinfo->shoplogo)}}" class="offlinestoreImg">
		    <div class="offlinestoreDetail">
		    	@if($shopinfo->shop_city == "市辖区")
		    	<div class="offlinestoreAddress">地址：{{$shopinfo->shop_province}}{{$shopinfo->shop_district}}{{$shopinfo->shop_address_detail}}</div>
		    	@else
			    <div class="offlinestoreAddress">地址：{{$shopinfo->shop_province}}{{$shopinfo->shop_city}}{{$shopinfo->shop_district}}{{$shopinfo->shop_address_detail}}</div>
			    @endif
			    <div class="offlinestoreRecommendation">商家推荐：{{$shopinfo->special}}</div>
		    </div>
		</div>
		<span class="contactSellerImg"><img src="{{asset('shop/images/offlinestore/contactSeller.png')}}"></span>
		<div class='clear' style='clear:both'></div>
	</div>
@stop
@section('addJs')
<script type="text/javascript">
	$(document).on("click", ".contactSellerImg", function() {
        $.confirm("确定拨打电话{{$shopinfo->contacter_phone}}吗？", "拨打电话！", function() {
         window.location.href="tel:{{$shopinfo->contacter_phone}}";
        }, function() {
          //取消操作
        });
    });
</script>
@stop