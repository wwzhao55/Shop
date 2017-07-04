@extends('layouts.app')
@section('siderbar')
@include('layouts.siderbar')
@endsection

@section('addCss')
<link rel="stylesheet" type="text/css" href="{{ URL::asset('shopstaff/weborder.css')}}">
@endsection

@section('content')

    <div class="navgation">
    	<div class="btn-group btn-group-justified" role="group" aria-label="....">
    		<div class="btn-group" id="button-startImg" role="group">
    			<button type="button"  class="btn">全部订单</button>
    		</div>
    		<div class="btn-group btnBorder" id="button-advImg" role="group">
    			<button type="button"  class="btn">等待处理订单</button>
    		</div>
    		<div class="btn-group btnBorder1" id="button-themeTemplate" role="group">
    			<button type="button"  class="btn">已发货订单</button>
    		</div>
    		<div class="btn-group" id="button-themeTemplate1" role="group">
    			<button type="button"  class="btn">已完成订单</button>
    		</div>
    	</div>
    </div>

    <div class="container-fluid themeTemplateContent themeTemplateContent_allOrder">
    	<table>
    		<thead>
    			<!-- <td class="thead_name">名称</td>
    			<td class="thead_rendering">效果图</td>
    			<td class="thead_time">添加时间</td>
    			<td class="thead_price">价格</td>
    			<td class="thead_describe">描述</td>
    			<td class="thead_operation">操作</td>
    			<td class="thead_detail">详情</td> -->
                <td class="thead_name">订单号</td>
                <td class="thead_rendering">时间</td>
                <td class="thead_time">商品列表</td>
                <td class="thead_price">用户信息</td>
                <td class="thead_describe">快递单号</td>
                <td class="thead_operation">订单状态</td>
    		</thead>
    		<tbody id="theme-list">
            <!-- {{$list->time}} -->
                @foreach($order as $list)
                <tr class="tr_evn">
                    <td class="tbody_name">{{$list['order_num']}}</td>
                    <td class="tbody_rendering">{{$list['time']}}</td>
                    <td class="tbody_time">{{$list['customer_id']}}</td>
                    <td class="tbody_price">{{$list['customer']['name']}}</td>
                    <td class="tbody_describe">{{$list['trade_num']}}</td>
                    <td class="tbody_operation">{{$list['status']}}</td>
                    
                </tr>
                @endforeach
    	<!-- @foreach($list->    
    	effect_img as $list_img) -->
    	<!-- @endforeach -->
        </tbody>
    </table>
    <div class="detail_div">
        <table>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
        </table>
    </div>
    </div>

    <!-- 等待处理订单开始 -->
    <div class="container-fluid themeTemplateContent themeTemplateContent_wait">
        <table>
            <thead>
                <td class="thead_name">订单号</td>
                <td class="thead_rendering">时间</td>
                <td class="thead_time">商品列表</td>
                <td class="thead_price">用户信息</td>
                <td class="thead_describe">快递单号</td>
                <td class="thead_operation">订单状态</td>
            </thead>
            <tbody id="theme-list">
                @foreach($order1 as $list1)
                <tr class="tr_evn1">
                    <td class="tbody_name">{{$list1['order_num']}}</td>
                    <td class="tbody_rendering">{{$list1['time']}}</td>
                    <td class="tbody_time">{{$list1['customer_id']}}</td>
                    <td class="tbody_price">{{$list1['customer']['name']}}</td>
                    <td class="tbody_describe">{{$list1['trade_num']}}</td>
                    <!-- <td class="tbody_operation">{{$list1['status']}}</td> -->
                    <td id="deliver" class="tbody_operation tbody_operation1"><a href="/Shopstaff/weborder/disposeorder/{{$list1['trade_num']}}/{{$list1['order_num']}}"><img src="{{URL::asset('admin/img/web/icon-Already-processed.png')}}" alt="">&nbsp;发货</a></td>
                </tr>
                @endforeach
        </tbody>
    </table>
    <div class="detail_div1">
        <table>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
        </table>
    </div>
    </div>
    <!-- 等待处理订单结束 -->
    <!-- 已发货开始 -->
    <div class="container-fluid themeTemplateContent themeTemplateContent_shipped">
        <table>
            <thead>
                <td class="thead_name">订单号</td>
                <td class="thead_rendering">时间</td>
                <td class="thead_time">商品列表</td>
                <td class="thead_price">用户信息</td>
                <td class="thead_describe">快递单号</td>
                <td class="thead_operation">订单状态</td>
            </thead>
            <tbody id="theme-list">
                @foreach($order2 as $list)
                <tr class="tr_evn2">
                    <td class="tbody_name">{{$list['order_num']}}</td>
                    <td class="tbody_rendering">{{$list['time']}}</td>
                    <td class="tbody_time">{{$list['customer_id']}}</td>
                    <td class="tbody_price">{{$list['customer']->name}}</td>
                    <td class="tbody_describe">{{$list['trade_num']}}</td>
                    <td class="tbody_operation">{{$list['status']}}</td>
                </tr>
                @endforeach
        </tbody>
    </table>
    <div class="detail_div2">
        <table>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
        </table>
    </div>
    </div>
    <!-- 已发货结束 -->
    <!-- 已完成开始 -->
    <div class="container-fluid themeTemplateContent themeTemplateContent_completed">
        <table>
            <thead>
                <td class="thead_name">订单号</td>
                <td class="thead_rendering">时间</td>
                <td class="thead_time">商品列表</td>
                <td class="thead_price">用户信息</td>
                <td class="thead_describe">快递单号</td>
                <td class="thead_operation">订单状态</td>
            </thead>
            <tbody id="theme-list">
                @foreach($order3 as $list)
                <tr class="tr_evn3">
                    <td class="tbody_name">{{$list['order_num']}}</td>
                    <td class="tbody_rendering">{{$list['time']}}</td>
                    <td class="tbody_time">{{$list['customer_id']}}</td>
                    <td class="tbody_price">{{$list['customer']->name}}</td>
                    <td class="tbody_describe">{{$list['trade_num']}}</td>
                    <td class="tbody_operation">{{$list['status']}}</td>
                </tr>
                @endforeach
        </tbody>
    </table>
    <div class="detail_div3">
        <table>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
        </table>
    </div>
    </div>
    <!-- 已完成结束 -->
    <script>
        $("#button-startImg").on("click",function(){
        // alert("你好");
        $(".themeTemplateContent_allOrder").css("display","block");
        $(".themeTemplateContent_wait").css("display","none");
        $(".themeTemplateContent_shipped").css("display","none");
        $(".themeTemplateContent_completed").css("display","none");
        $("#button-startImg button").css("background-color","#fff");
        $("#button-advImg button").css("background-color","#eee");
        $("#button-themeTemplate button").css("background-color","#eee");
        $("#button-themeTemplate1 button").css("background-color","#eee");
    });
    $("#button-advImg").on("click",function(){
        $(".themeTemplateContent_wait").css("display","block");
        $(".themeTemplateContent_allOrder").css("display","none");
        $(".themeTemplateContent_completed").css("display","none");
        $(".themeTemplateContent_shipped").css("display","none");
        // $("#button-advImg button").css("background-color","#fff");
        $("#button-advImg button").css("background-color","#fff");
        $("#button-startImg button").css("background-color","#eee");
        $("#button-themeTemplate button").css("background-color","#eee");
        $("#button-themeTemplate1 button").css("background-color","#eee");
    });
    $("#button-themeTemplate").on("click",function(){
        $(".themeTemplateContent_shipped").css("display","block");
        $(".themeTemplateContent_allOrder").css("display","none");
        $(".themeTemplateContent_wait").css("display","none");
        $(".themeTemplateContent_completed").css("display","none");
        // $("#button-themeTemplate button").css("background-color","#fff");
        $("#button-themeTemplate button").css("background-color","#fff");
        $("#button-advImg button").css("background-color","#eee");
        $("#button-startImg button").css("background-color","#eee");
        $("#button-themeTemplate1 button").css("background-color","#eee");
    });
    $("#button-themeTemplate1").on("click",function(){
        $(".themeTemplateContent_completed").css("display","block");
        $(".themeTemplateContent_allOrder").css("display","none");
        $(".themeTemplateContent_wait").css("display","none");
        $(".themeTemplateContent_shipped").css("display","none");
        // $("#button-themeTemplate button").css("background-color","#fff");
        $("#button-themeTemplate1 button").css("background-color","#fff");
        $("#button-advImg button").css("background-color","#eee");
        $("#button-startImg button").css("background-color","#eee");
        $("#button-themeTemplate button").css("background-color","#eee");
    });
   
   // 待处理悬停变色
   $(".tbody_operation1").on("mouseover",function(){
        var src1="{{URL::asset('admin/img/web/icon-Already-processed-hover.png')}}"
        $(this).find("img").attr("src",src1);
        $(this).css("color","red");
        // alert("bian");
   });
   $(".tbody_operation1").on("mouseout",function(){
              var src1="{{URL::asset('admin/img/web/icon-Already-processed.png')}}"
              $(this).find("img").attr("src",src1);
              $(this).css("color","");
    });

   // 用户信息详情
// var a=1;
// alert(a);
   $(".tr_evn").on("mouseover",function(){
    var X = $(this).position().top; 
        $(this).css("background-color","#ddd");
        $(".detail_div").css({
            "display":"block",
            "top": (X+60)
        });
   });
   $(".tr_evn").on("mouseout",function(){
        $(this).css("background-color","");
        $(".detail_div").css({
            "display":"none",
            
        });
    });
   // 1
   $(".tr_evn1").on("mouseover",function(){
    var X = $(this).position().top; 
        $(this).css("background-color","#ddd");
        $(".detail_div1").css({
            "display":"block",
            "top": (X+60)
        });
   });
   $(".tr_evn1").on("mouseout",function(){
        $(this).css("background-color","");
        $(".detail_div1").css({
            "display":"none",
            
        });
    });
   // 2
   $(".tr_evn2").on("mouseover",function(){
    var X = $(this).position().top; 
        $(this).css("background-color","#ddd");
        $(".detail_div2").css({
            "display":"block",
            "top": (X+60)
        });
   });
   $(".tr_evn2").on("mouseout",function(){
        $(this).css("background-color","");
        $(".detail_div2").css({
            "display":"none",
            
        });
    });
   // 3
   $(".tr_evn3").on("mouseover",function(){
    var X = $(this).position().top; 
        $(this).css("background-color","#ddd");
        $(".detail_div3").css({
            "display":"block",
            "top": (X+60)
        });
   });
   $(".tr_evn3").on("mouseout",function(){
        $(this).css("background-color","");
        $(".detail_div3").css({
            "display":"none",
            
        });
    });
   // 发货
   // $("#deliver").on("click",function(){

   // });

   
    </script>

@endsection
