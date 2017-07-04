@extends('layouts.app')
@section('siderbar')
@include('layouts.siderbar')
@endsection

@section('addCss')
<link rel="stylesheet" type="text/css" href="{{ URL::asset('shopstaff/apporder.css')}}">
@endsection

@section('content')

      <div class="navgation">
        <div class="btn-group btn-group-justified" role="group" aria-label="....">
            <div class="btn-group" id="button-startImg" role="group">
                <button type="button"  class="btn">下单管理</button>
            </div>
            <div class="btn-group btnBorder" id="button-advImg" role="group">
                <button type="button"  class="btn">结算管理</button>
            </div>
            <!-- <div class="btn-group btnBorder1" id="button-themeTemplate" role="group">
                <button type="button"  class="btn">已发货订单</button>
            </div>
            <div class="btn-group" id="button-themeTemplate1" role="group">
                <button type="button"  class="btn">已完成订单</button>
            </div> -->
        </div>
    </div>

    <div class="container-fluid themeTemplateContent themeTemplateContent_allOrder">
        <table>
            <thead>
                <td class="thead_name_1">待处理</td>
                <td class="thead_rendering"></td>
                    <td class="thead_time"></td>
                    <td class="thead_price"></td>
                    <td class="thead_describe"></td>
                <!-- <td class="thead_name">待处理</td> -->
            </thead>
            <tbody id="theme-list">
                <tr>
                    <td class="thead_name">桌号</td>
                    <td class="thead_rendering">店员名</td>
                    <td class="thead_time">商品名字</td>
                    <td class="thead_price">时间</td>
                    <td class="thead_describe">状态变更</td>
                    <!-- <td class="thead_operation">订单状态</td> -->
                </tr>
                @foreach($dopreorder as $list1)
                <tr class="">
                    <td class="tbody_name">{{$list1['table']}}</td>
                    <td class="tbody_rendering">{{$list1['clerk_name']}}</td>
                    <td class="tbody_time">{{$list1['commodity']['commotity_name']}}</td>
                    <td class="tbody_price">{{$list1['time']}}</td>
                    <td class="tbody_describe tbody_describe1"><img src="{{URL::asset('admin/img/web/icon-Already-processed.png')}}" alt="">&nbsp;已处理</td>
                    <!-- <td class="tbody_operation">已完成订单</td> -->
                </tr>
                @endforeach
                <!-- @foreach($list->        
                effect_img as $list_img) -->
                <!-- @endforeach --> </tbody>
        </table>
        <div class="space"></div>
        <table class="order_detail">
            <thead>
                <td class="thead_name_detail">订单详情</td>
                <td class="thead_rendering_detail"></td>
                    <!-- <td class="thead_time"></td>
                    <td class="thead_price"></td>
                    <td class="thead_describe"></td> -->
            </thead>
            <tbody id="theme-list">
                <tr>
                    <td class="tbody_name_detail">06</td>
                    <td class="tbody_rendering_detail">复古风单肩斜跨手提大包包<br />1<br />￥158</td>
                    <!-- <td class="tbody_time">2546932146</td>
                    <td class="tbody_price">2016-06-07</td>
                    <td class="tbody_describe">已处理</td> -->
                </tr>
            </tbody>
        </table>
        <!-- 已处理 -->
        <div class="space1"></div>
        <table>
            <thead>
                <td class="thead_name_1">已处理</td>
                <td class="thead_rendering"></td>
                    <td class="thead_time"></td>
                    <td class="thead_price"></td>
                    <td class="thead_describe"></td>
                <!-- <td class="thead_name">待处理</td> -->
            </thead>
            <tbody id="theme-list">
                <tr>
                    <td class="thead_name">桌号</td>
                    <td class="thead_rendering">店员名</td>
                    <td class="thead_time">商品名字</td>
                    <td class="thead_price">时间</td>
                    <td class="thead_describe">状态变更</td>
                    <!-- <td class="thead_operation">订单状态</td> -->
                </tr>
                @foreach($preorder as $list2)
                <tr>
                    <td class="tbody_name">{{$list2['table']}}</td>
                    <td class="tbody_rendering">{{$list2['clerk_name']}}</td>
                    <td class="tbody_time">{{$list2['commodity']['commotity_name']}}</td>
                    <td class="tbody_price">{{$list2['time']}}</td>
                    <td class="tbody_describe tbody_describe2"><img src="{{URL::asset('admin/img/web/icon-Reminder.png')}}" alt="">&nbsp;催单</td>
                    <!-- <td class="tbody_operation">已完成订单</td> -->
                </tr>
                @endforeach
                <!-- @foreach($list->        
                effect_img as $list_img) -->
                <!-- @endforeach --> </tbody>
        </table>
        <div class="space"></div>
        <table class="order_detail">
            <thead>
                <td class="thead_name_detail">订单详情</td>
                <td class="thead_rendering_detail"></td>
                    <!-- <td class="thead_time"></td>
                    <td class="thead_price"></td>
                    <td class="thead_describe"></td> -->
            </thead>
            <tbody id="theme-list">
                <tr>
                    <td class="tbody_name_detail">06</td>
                    <td class="tbody_rendering_detail">复古风单肩斜跨手提大包包<br />1<br />￥158</td>
                    <!-- <td class="tbody_time">2546932146</td>
                    <td class="tbody_price">2016-06-07</td>
                    <td class="tbody_describe">已处理</td> -->
                </tr>
            </tbody>
        </table>
    </div>

    <!-- 等待处理订单开始 -->
    <div class="container-fluid themeTemplateContent themeTemplateContent_wait">
        <table class="container-fluid-table">
            <thead>
                <td class="thead_name_1">待处理</td>
                <td class="thead_rendering"></td>
                    <td class="thead_time"></td>
                    <td class="thead_price"></td>
                    <td class="thead_describe"></td>
                <!-- <td class="thead_name">待处理</td> -->
            </thead>
            <tbody id="theme-list">
                <tr>
                    <td class="thead_name">桌号</td>
                    <td class="thead_rendering">店员名</td>
                    <td class="thead_time">单号</td>
                    <td class="thead_price">时间</td>
                    <td class="thead_describe">状态变更</td>
                    <!-- <td class="thead_operation">订单状态</td> -->
                </tr>
                @foreach($doorder as $list3)
                
                <tr class="current_tr">
                    <td class="tbody_name">{{$list3['table']}}</td>
                    <td class="tbody_rendering">{{$list3['clerk_name']}}</td>
                    <td class="tbody_time">{{$list3['order_num']}}</td>
                    <td class="tbody_price">{{$list3['time']}}</td>
                    <td class="tbody_describe tbody_describe1"><img src="{{URL::asset('admin/img/web/icon-Already-processed.png')}}" alt="">&nbsp;已处理</td>
                    <!-- <td class="tbody_operation">已完成订单</td> -->
                   <td style="display:none;"><input class="aaa" type="text" value="{{$list3['order_num']}}"></td>
                </tr>
                @endforeach
                <!-- @foreach($list->        
                effect_img as $list_img) -->
                <!-- @endforeach --> </tbody>
        </table>
        <a style="display:none;" href="" id="aaa"></a>
        <div class="space"></div>
         
        <table class="order_detail">
            <thead>
                <td class="thead_name_detail">订单详情</td>
                <td class="thead_rendering_detail"></td>
                    <!-- <td class="thead_time"></td>
                    <td class="thead_price"></td>
                    <td class="thead_describe"></td> -->
            </thead>
            <tbody id="theme-list">
                <tr>
                    <td class="tbody_name_detail">07</td>
                    <td class="tbody_rendering_detail">复古风单肩斜跨手提大包包<br />2<br />￥158</td>
                    <!-- <td class="tbody_time">2546932146</td>
                    <td class="tbody_price">2016-06-07</td>
                    <td class="tbody_describe">已处理</td> -->
                </tr>
            </tbody>
        </table>
        <!-- 已处理 -->
        <div class="space1"></div>
        <table>
            <thead>
                <td class="thead_name_1">待处理</td>
                <td class="thead_rendering"></td>
                    <td class="thead_time"></td>
                    <td class="thead_price"></td>
                    <td class="thead_describe"></td>
                <!-- <td class="thead_name">待处理</td> -->
            </thead>
            <tbody id="theme-list">
                <tr>
                    <td class="thead_name">桌号</td>
                    <td class="thead_rendering">店员名</td>
                    <td class="thead_time">单号</td>
                    <td class="thead_price">时间</td>
                    <td class="thead_describe">状态变更</td>
                    <!-- <td class="thead_operation">订单状态</td> -->
                </tr>
                @foreach($order as $list4)
                <tr>
                    <td class="tbody_name">{{$list4['table']}}</td>
                    <td class="tbody_rendering">{{$list4['clerk_name']}}</td>
                    <td class="tbody_time">{{$list4['order_num']}}</td>
                    <td class="tbody_price">{{$list4['time']}}</td>
                    <td class="tbody_describe tbody_describe2"><img src="{{URL::asset('admin/img/web/icon-Reminder.png')}}" alt="">&nbsp;催单</td>
                    <!-- <td class="tbody_operation">已完成订单</td> -->
                </tr>
                @endforeach
                <!-- @foreach($list->        
                effect_img as $list_img) -->
                <!-- @endforeach --> </tbody>
        </table>
        <div class="space"></div>
        <table class="order_detail">
            <thead>
                <td class="thead_name_detail">订单详情</td>
                <td class="thead_rendering_detail"></td>
                    <!-- <td class="thead_time"></td>
                    <td class="thead_price"></td>
                    <td class="thead_describe"></td> -->
            </thead>
            <tbody id="theme-list">
                <tr>
                    <td class="tbody_name_detail">07</td>
                    <td class="tbody_rendering_detail">复古风单肩斜跨手提大包包<br />2<br />￥158</td>
                    <!-- <td class="tbody_time">2546932146</td>
                    <td class="tbody_price">2016-06-07</td>
                    <td class="tbody_describe">已处理</td> -->
                </tr>
            </tbody>
        </table>
    </div>
    <!-- 等待处理订单结束 -->
    <script>
        $("#button-startImg").on("click",function(){
        // alert("你好");
        $(".themeTemplateContent_allOrder").css("display","block");
        $(".themeTemplateContent_wait").css("display","none");
        $(".themeTemplateContent_shipped").css("display","none");
        $(".themeTemplateContent_completed").css("display","none");
        $("#button-startImg button").css("background-color","#fff");
        $("#button-advImg button").css("background-color","#eee");
    });
    $("#button-advImg").on("click",function(){
        $(".themeTemplateContent_wait").css("display","block");
        $(".themeTemplateContent_allOrder").css("display","none");
        $(".themeTemplateContent_completed").css("display","none");
        $(".themeTemplateContent_shipped").css("display","none");
        // $("#button-advImg button").css("background-color","#fff");
        $("#button-advImg button").css("background-color","#fff");
        $("#button-startImg button").css("background-color","#eee");
    });
   // 操作悬停变色
   // $(".tbody_describe")
   $(".tbody_describe1").on("mouseover",function(){
        var src1="{{URL::asset('admin/img/web/icon-Already-processed-hover.png')}}"
        $(this).find("img").attr("src",src1);
        $(this).css("color","red");
   });
   $(".tbody_describe1").on("mouseout",function(){
              var src1="{{URL::asset('admin/img/web/icon-Already-processed.png')}}"
              $(this).find("img").attr("src",src1);
              $(this).css("color","");
    });
   // $(".tbody_describe2 img").on("mouseover",function(){
   //      var src1="{{URL::asset('admin/img/web/icon-Reminder-hover.png')}}"
   //      $(this).attr("src",src1);
   // });
   $(".tbody_describe2").on("mouseover",function(){
        var src1="{{URL::asset('admin/img/web/icon-Reminder-hover.png')}}"
        $(this).find("img").attr("src",src1);
        $(this).css("color","red");
   });
   $(".tbody_describe2").on("mouseout",function(){
              var src1="{{URL::asset('admin/img/web/icon-Reminder.png')}}"
              // $(this).attr("src",src1);
              $(this).find("img").attr("src",src1);
              $(this).css("color","");
    });

   // 点击行，显示该行的详情
   // $(".container-fluid-table").on("click",".current_tr",function(){

   //  alert("shihsi");
   //  var a=$(this).find("#aaa").attr("href");
   //      alert(a);
   //      $(this).find("#aaa").click();
   // });

   // $(".container-fluid-table").on("mouseover",".current_tr",function(){
   //       alert("shihsi");
   //      var a=$(this).find("#aaa").attr("href");
   //      alert(a);
   //      $(this).find("#aaa").click();
   // });

   $(".current_tr").click(function(event){

        // event.stopPropagation();
        // $(this).addclass("color_style");
    // alert("shihsi");
    $("#aaa").attr("href","");
    alert($("#aaa").attr("href"));
    var a=$(this).find(".aaa").val();
    //     alert(a);
    // var b=$("#aaa").attr("href");
    alert(a);
    // alert(b);
    var url_get="/Shopstaff/apporder/showcommodity/"+a;
    alert(url_get);
    // $("#aaa").attr("href","/Shopstaff/apporder/showcommodity/"+a);
    // document.getElementById("aaa").click(); 

    $.ajax({
             type: "get",
             url: url_get,
             // data: {username:$("#username").val(), content:$("#content").val()},
             // dataType: "json",
             success: function(data){
                         alert("请求成功");
                         alert(data.commodity[count]);

                      }
         });


        // $(".current_tr .tbody_describe1").on("click",function(){
        //     event.stopPropagation();
        //     alert("td");
        // })
   });

$(".current_tr .tbody_describe1").on("click",function(event){
    event.stopPropagation();
            alert("td");
        })

    </script>

    
@endsection