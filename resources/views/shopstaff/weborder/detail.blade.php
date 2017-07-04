<!-- auth:xuedan -->
@extends('layouts.app')
@section('siderbar')
@include('layouts.siderbar')
@endsection

@section('addCss')
<link rel="stylesheet" type="text/css" href="{{ URL::asset('shop/css/jquery.toastmessage.css')}}"/>
<link rel="stylesheet" type="text/css" href="{{ URL::asset('shopstaff/weborderDetail.css')}}">
@endsection

@section('content')

<!--待付款-->
@if($order->status==1)
  <div id="wait-pay">
          <div class="process">
                  <div class="top">
                         <p class="process-words" id="bayer-order">买家下单</p>
                         <p class="process-date">{{$order->order_at}}</p>
                  </div>
                  <div class="pay top">
                         <p class="process-words">买家已付款</p>
                         
                  </div>
                  <div class="goods top">
                         <p class="process-words">已发货</p>
                         
                  </div>
                  <div class="done top">
                         <p class="process-done">完成</p>
                  </div>
          </div>
          <div class="clearfix"></div>
          <div class="order-info">
               <span class="information">订单信息</span>
               <a href="/Shopstaff/weborder"><input class="back" type="button" value="返回"/></a>
          </div>
          <div class="info-content">
               <div class="left-content">
                     <p id="order-num">订单号: {{$order->order_num}}</p>
                     <p>买家：{{$order->receiver_name}}</p>
                     <p>配送方式: 闪送</p>
               </div>
               <div class="right-content">
                     <p class="right-title">订单状态</p>
                     <p class="right-content-last">待付款</p>
               </div>
          </div>
  </div>
  

<!--待发货-->
@elseif($order->status==2)
  <div id="wait-send" style="">
        <div class="process">
                  <div class="top">
                         <p class="process-words">买家下单</p>
                         <p class="process-date">{{$order->order_at}}</p>
                  </div>
                  <div class="pay top">
                         <p class="process-words" id="bayer-paid">买家已付款</p>
                         <p class="process-date">{{$order->trade_at}}</p>
                  </div>
                  <div class="goods top">
                         <p class="process-words">已发货</p>
                         
                  </div>
                  <div class="done top">
                         <p class="process-done">完成</p>
                  </div>
        </div>
        <div class="clearfix"></div>
        <div class="order-info">
               <span class="information">订单信息</span>
               <a href="/Shopstaff/weborder"><input class="back" type="button" value="返回"/></a>
        </div>
        <div class="order-info-detail">
               <div class="left-detail">
			                 <p class="left-info" id="order-num">订单号：{{$order->order_num}} </p>
					             <p class="left-info">买家：{{$order->receiver_name}} </p>
                       <p class="left-info">配送方式：闪送</p>
                       <p class="left-info">快递单号：<span class="now-none">暂无</span></p>
                       <p class="left-info">收货信息：<span class="now-none buyer_detail_address">{{$order->receiver_province}}{{$order->receiver_city}}{{$order->receiver_district}}{{$order->receiver_address_details}}</span></p>
                       <p class="left-info">支付方式：<span class="now-none">微信支付</span></p>
                       <p class="left-info">支付流水号：<span class="now-none">{{$order->trade_num}}</span></p>
               </div>
               <div class="right-detail" style="text-align:center;">
                       <p class="right-detail-title">订单状态</p>
                       <p class="right-detail-status">待发货</p>
                        
                       <input type="button" value="发货" class="right-button send-btn Send1" />
                       <span class='order_id_value' hidden>{{$order->id}}</span>
                       <span class='order_buyer_name' hidden>{{$order->receiver_name}}</span>
                       <span class='order_buyer_phone' hidden>{{$order->receiver_phone}}</span>
                       @if($order->hurry_times==0)
                       <p class="btn-bottom hurryTimes">买家未催货</p>
                       @else
                       <p class="btn-bottom hurryTimes">催货{{$order->hurry_times}}次</p>
                       @endif                   
               </div>
        </div>
        
  </div>

<!--已发货-->
@elseif($order->status==3)
  <div id="had-send" style="">
          <div class="process">
                      <div class="top">
                             <p class="process-words">买家下单</p>
                             <p class="process-date">{{$order->order_at}}</p>
                      </div>
                      <div class="pay top">
                             <p class="process-words">买家已付款</p>
                             <p class="process-date">{{$order->trade_at}}</p>
                      </div>
                      <div class="goods top">
                             <p class="process-words" id="sent-goods">已发货</p>
                             <p class="process-date">{{$order->send_at}}</p>
                      </div>
                      <div class="done top">
                             <p class="process-done">完成</p>
                      </div>
          </div>
          <div class="clearfix"></div>
          <div class="order-info">
               <span class="information">订单信息</span>
               <a href="/Shopstaff/weborder"><input class="back" type="button" value="返回"/></a>
          </div>
          <div class="order-info-detail">
               <div class="left-detail">
			                 <p class="left-info" id="order-num">订单号：{{$order->order_num}}</p>
					             <p class="left-info">买家：{{$order->receiver_name}}</p>
                       <p class="left-info">配送方式：闪送</p>
                       <p class="left-info">快递单号：<span class="now-none">{{$order->express_num}}</span></p>
                       <p class="left-info">收货信息：<span class="now-none">{{$order->receiver_province}}{{$order->receiver_city}}{{$order->receiver_district}}{{$order->receiver_address_details}}</span></p>
                       <p class="left-info">支付方式：<span class="now-none">微信支付</span></p>
                       <p class="left-info">支付流水号: <span class="now-none">{{$order->trade_num}}</span></p>
               </div>
               <div class="right-detail" style="text-align:center;">
                       <p class="right-detail-title">订单状态</p>
                       <p class="right-detail-status">已发货</p>
                       <span class='order_id_value' hidden>{{$order->id}}</span>
                       <span class='order_buyer-Name' hidden>{{$order->receiver_name}}</span>
                       <span class='order_buyer-Phone' hidden>{{$order->receiver_phone}}</span>
                     <!--   <input type="button" value="发货" class="send-goods" />        -->    
                       <input type="button" value="确认收货" class="right-button comfirm confirm-btn Receive1" /><br>
                                               
                       <span class="btn-bottom send-express ">快递单号：{{$order->express_num}}</span>
               </div>
          </div>
          
  </div>

<!--已完成-->
@elseif($order->status==4)
<div id="had-done" style="">
        <div class="process">
                  <div class="top">
                         <p class="process-words">买家下单</p>
                         <p class="process-date">{{$order->order_at}}</p>
                  </div>
                  <div class="pay top">
                         <p class="process-words">买家已付款</p>
                         <p class="process-date">{{$order->trade_at}}</p>
                  </div>
                  <div class="goods top">
                         <p class="process-words">已发货</p>
                         <p class="process-date">{{$order->send_at}}</p>
                  </div>
                  <div class="done top">
                         <p class="process-done" id="trade-done">完成</p>
                  </div>
        </div>
        <div class="clearfix"></div>
        <div class="order-info">
               <span class="information">订单信息</span>
               <a href="/Shopstaff/weborder"><input class="back" type="button" value="返回"/></a>
        </div>
        <div class="order-info-detail">
               <div class="left-detail">
                       <p class="left-info" id="order-num">订单号：{{$order->order_num}} </p>
                       <p class="left-info">买家：{{$order->receiver_name}} </p>
                       <p class="left-info">配送方式：闪送</p>
                       <p class="left-info">快递单号：<span class="now-none">{{$order->express_num}}</span></p>
                       <p class="left-info">收货信息：<span class="now-none">{{$order->receiver_province}}{{$order->receiver_city}}{{$order->receiver_district}}{{$order->receiver_address_details}}</span></p>
                       <p class="left-info">支付方式：<span class="now-none">微信支付</span></p>
                       <p class="left-info">支付流水号：<span class="now-none">{{$order->trade_num}}</span></p>
               </div>
               <div class="right-detail" style="text-align:center;">
                       <p class="right-detail-title">订单状态</p>
                       <p class="right-detail-status">已完成</p>
                       <!-- <div class="three-button">  --> 
                      <!--  <input type="button" value="发货" class="right-button" />           
                       <input type="button" value="确认收货" class="right-button" />
                       <input type="button" value="退款" class="right-button" />   -->
                       <!-- </div>  -->                   
                       <!-- <p class="btn-bottom">买家未催单/催货{{$order->hurry_times}}次</p>
                       <p class="btn-bottom">快递单号：{{$order->express_num}}</p> -->
               </div>
        </div>
        
</div>

<!--已关闭-->
@elseif($order->status==5)
  <div id="had-close" style="">
        <div class="order-info">
               <span class="information">订单信息</span>
               <a href="/Shopstaff/weborder"><input class="back" type="button" value="返回"/></a>
          </div>
          <div class="info-content">
               <div class="left-content">
                     <p id="order-num">订单号: {{$order->order_num}}</p>
                     <p>买家：{{$order->receiver_name}}</p>
                     <p>配送方式: 闪送</p>
               </div>
               <div class="right-content">
                     <p class="right-title">订单状态</p>
                     <div id="right-last" style="text-align:center;">
                     <span id="right-content-second">已关闭</span><br>
                     @if($order->close_type==1)
                       <span id="right-content-first">有效时间未付款</span>
                     @elseif($order->close_type==2)
                       <span id="right-content-first">用户取消订单</span>
                     @elseif($order->close_type==3)
                       <span id="right-content-first">已退款(￥{{$order->refund_money}})</span>
                     @endif
                     <!-- <span id="right-content-first">有效时间未付款</span> -->
                     
                    </div>
               </div>
          </div>
  </div>

<!--退款中-->
@elseif($order->status==6)
  <div id="had-refund" style="">
        <div class="process">
                  <div class="top">
                         <p class="process-words">买家下单</p>
                         <p class="process-date">{{$order->order_at}}</p>
                  </div>
                  <div class="pay top">
                         <p class="process-words">买家已付款</p>
                         <p class="process-date">{{$order->trade_at}}</p>
                  </div>
                  <div class="goods top">
                         <p class="process-words">已发货</p>
                         <p class="process-date">{{$order->send_at}}</p>
                  </div>
                  <div class="done top">
                         <p class="process-done">完成</p>
                  </div>
        </div>
        <div class="clearfix"></div>
        <div class="order-info">
               <span class="information">订单信息</span>
               <a href="/Shopstaff/weborder"><input class="back" type="button" value="返回"/></a>
        </div>
        <div class="order-info-detail">
               <div class="left-detail">
                       <p class="left-info" id="order-num">订单号：{{$order->order_num}} </p>
                       <p class="left-info">买家：{{$order->receiver_name}} </p>
                       <p class="left-info">配送方式：闪送</p>
                       <p class="left-info">快递单号：<span class="now-none">{{$order->express_num}}</span></p>
                       <p class="left-info">收货信息：<span class="now-none">{{$order->receiver_province}}{{$order->receiver_city}}{{$order->receiver_district}}{{$order->receiver_address_details}}</span></p>
                       <p class="left-info">支付方式：<span class="now-none">微信支付</span></p>
                       <p class="left-info">支付流水号：<span class="now-none">{{$order->trade_num}}</span></p>
               </div>
               <div class="right-detail" style="text-align:center;">
                       <p class="right-detail-title">订单状态</p>   
                       <p class="right-detail-status">退款中</p>
                        <span class='order_id_value' hidden>{{$order->id}}</span>
                         <input type="button" value="退款" class="right-button refundMoney Refund1" />  
                       
               </div>
        </div>
       
  </div>

<!--已退款-->
@elseif($order->status==7)
          <div class="order-info">
               <span class="information">订单信息</span>
               <a href="/Shopstaff/weborder"><input class="back" type="button" value="返回"/></a>
          </div>
           <div class="order-info-detail">
               <div class="left-detail">
                       <p class="left-info" id="order-num">订单号：{{$order->order_num}} </p>
                       <p class="left-info">买家：{{$order->receiver_name}} </p>
                       <p class="left-info">配送方式：闪送</p>
                       <p class="left-info">快递单号：<span class="now-none">{{$order->express_num}}</span></p>
                       <p class="left-info">收货信息：<span class="now-none">{{$order->receiver_province}}{{$order->receiver_city}}{{$order->receiver_district}}{{$order->receiver_address_details}}</span></p>
                       <p class="left-info">支付方式：<span class="now-none">微信支付</span></p>
                       <p class="left-info">支付流水号：<span class="now-none">{{$order->trade_num}}</span></p>
               </div>
               <div class="right-detail" style="text-align:center;">
                       <p class="right-detail-title">订单状态</p>   
                       <p class="right-detail-status">已退款</p>
                       
               </div>
          </div>
          
@endif
@if($order->status!=5)
          <div class="detail-bottom">
               <table class="lists-bottom">
                    <tbody align="center" >  
                         <tr>
                          <th>商品</th>
                          <th>单价/数量</th>
                          <th>优惠金额</th>
                          <th>实付金额</th>
                          <th>订单状态</th>
                        </tr>            
                        <tr>
                             <td id="order-num" class="list-title1">
                              @foreach($order->commoditys as $commodity)
                              <img src="{{asset($commodity->main_img)}}" class="commodity-img" style="float:left; width:60px; height:60px; margin-left:10px;">
                               <div style="" class="commoditydetail">
                                    <span class="commodity_name">{{$commodity->commodity_name}}</span><br>
                                    @foreach($commodity->commodity_sku as $key=>$value)
                                        <span>{{$key}}:{{$value}}</span>
                                    @endforeach
                               </div>
                              @endforeach
                             </td>
                             <td>
                             @foreach($order->commoditys as $commodity) 
                                <div class="commodity_price">￥{{$order->commoditys[0]->price}} <div>({{$order->commoditys[0]->count}}件)</div></div>
                             @endforeach
                             </td>
                             <td>￥{{$order->discount}}</td> 
                             <td><p class="orderMoney">￥{{$order->total}}</p></td>
                             @if($order->status==0)
                                       <td class="status" name="status">已关闭</td>
                             @elseif($order->status==1)
                                       <td class="status" name="status">待付款</td>
                             @elseif($order->status==2)
                                       <td class="status" name="status">待发货<br><input type="button" value="发货" class="detail-btn send-btn Send2"/></td>
                             @elseif($order->status==3)
                                       <td class="status" name="status">已发货<br><input type="button" value="确认收货" class="detail-btn confirm-btn Receive2" /></td>
                             @elseif($order->status==4)
                                       <td class="status" name="status">已完成</td>
                             @elseif($order->status==5)
                                       <td class="status" name="status">已关闭</td>
                             @elseif($order->status==6)
                                       <td class="status" name="status">退款中<br><input type="button" value="退款" class="detail-btn refund-btn Refund2"  /></td>
                             @elseif($order->status==7)
                                      <td class="status" name="status"><p>已关闭</p><p>已退款(￥{{$order->refund_money}})</p></td>
                             @endif
                
                            
                        </tr> 
                    </tbody>
               </table>
          </div>
@endif

<!-- 弹窗类 -->
<!--发货弹窗-->
  <div class="send-layer" style="">
      <div class="send-info"> 
           <p><span>快递公司：闪送</span></p>
           <p><span>客服电话：<span class="service-phone"></span>8888</span></p>
           <p><span>送货地址：<span class="send_address address"></span></span></p>
           <p><span>收货人：<span class="buyer-name send_buyer-name"></span></span></p>
           <p><span>联系电话：<span class="buyer-phone send_buyer-phone"></span></span></p>
           <p><span>快递单号：</span><input type="text" class="input-order-num"/></p>
           <span class="send_id" hidden></span>
      </div>
      <div class="choose button-style">
     <input type="submit" name="" class="choose-btn cancle-overdue" value="返回">
      <input type="submit" name="" class="choose-btn confirm-send" value="确定">
      </div>
  </div>
<!--确认收货弹窗-->
  <div class="confirm-layer" style="">
      <div class="remind">
        <p class="remind1">您是否已和买家沟通并确认货品已送到？</p>
        <p class="remind2">(建议确认过程中全程录音)</p>
        <p class="remind3"><span class="buyer-name receive_buyer-name"></span></p>
        <p class="remind4"><span class="buyer-phone receive_buyer-phone"></span></p>
        <span class="receive_id" hidden></span>
      </div>
      <div class="choose button-style">
      <input type="submit" name="" class="choose-btn receive-back" value="返回">
      <input type="submit" name="" class="choose-btn confirm-receive" value="确定">
      </div>
  </div>
<!--退款弹窗-->
  <div class="refund-layer" style="">
        <div class="refund-info">
           <div class="refund-title">退款信息</div>
           <div class="question">
               <span>问题描述:</span>
               <textarea rows="5" cols="30" style="resize:none" id="questionContent" class="refund-description" disabled="disabled"></textarea>
           </div>
           <div class="refund-content">
             <div class="picture">
                 <div class='picture-title'>图片：</div>
                 <span class="no-picture-mention" style='display:none;'>无图片展示</span>
                 <div class="refund-imgs"></div>
             </div>
             <p><span class="buyer-name"></span></p>
             <p><span class="buyer-phone"></span></p>
             <p>订单金额：<span class="order-money"></span></p>
             <p><span>实际金额：</span><input type="text" class="input-money"/></p>
             <span class="id" style='display:none;'></span>
           </div>
        </div>
        <div class="choose">
          <input type="submit" name="" class="choose-btn refund-back" value="返回">
          <input type="submit" name="" class="choose-btn confirm-refund" value="确定">
        </div>
  </div>
<script src="{{asset('shop/js/jquery.toastmessage.js')}}"></script> 
<script type="text/javascript">
//发货点击事件
  $(".Send1").on("click",function(){
         // var buyerName=$(this).closest('tr').find('.buyerName').html();
         // var buyerPhnoe=$(this).closest('tr').find('.buyerPhnoe').html();
         // var addressdetail=$(this).closest('tr').find('.receiver_province').html();
         // var servicePhone=$(this).closest('tr').find('.servicePhone').html();
         // $('.address').html(addressdetail);
         // $('.service-phone').html(servicePhone);
         // $('.buyer-name').html(buyerName);
         // $('.buyer-phone').html(buyerPhnoe);
         // var id=$(this).parents('tr').children('.orderID').html();
         var buyerPhnoe=$(this).siblings('.order_buyer_phone').html();
         var addressdetail=$(this).parent('.right-detail').siblings('.left-detail').children().find('.buyer_detail_address').html();
         var buyerName=$(this).siblings('.order_buyer_name').html();
         var id = $(this).siblings('.order_id_value').html();
         $('.send_address').html(addressdetail);
         // $('.service-phone').html(servicePhone);
         $('.send_buyer-name').html(buyerName);
         $('.send_buyer-phone').html(buyerPhnoe);
         $('.send_id').html(id);
            //自定页
             cancel_index1= layer.open({
                title: false, //不显示标题
                type: 1,
                skin: 'layui-layer-demo', //样式类名
                closeBtn: 0, //不显示关闭按钮
                shift: 2,
                area: ['730px', '420px'],
                 shadeClose: true, //开启遮罩关闭
                shade: [0.3,'#3d0708'],
                content: $('.send-layer')
              });
            $(".cancle-overdue").on("click",function(){
                $(".send-layer").css("display","none");
                layer.close(cancel_index1);
            });
  });
  $('.Send2').on('click',function(){
    $('.Send1').click();
  });
  //发货post
    $('.confirm-send').on('click',function(){
        var orderID=$('.send_id').html();
        var express_num=$('.input-order-num').val();
        $.ajax({
            type: 'POST',
            url: '/Shopstaff/weborder/send',
            data:{
                express_num:express_num,
                order_id:orderID
            },
            dataType: 'json',
            success:function(result){
                      if(result.status=="success"){     
                           $().toastmessage('showSuccessToast', "发货成功！");
                          window.location.href="/Shopstaff/weborder";
                      }else{
                          alert(result.msg);
                      }
                  }
        });
    });
//确认收货弹窗
  $(".Receive1").on("click",function(){
      //自定页
              // var buyerName=$(this).closest('tr').find('.buyerName').html();
              // var buyerPhnoe=$(this).closest('tr').find('.buyerPhnoe').html();
              // $('.buyer-name').html(buyerName);
              // $('.buyer-phone').html(buyerPhnoe);
              //var id=$(this).parents('tr').children('.orderID').html();
              var id = $(this).siblings('.order_id_value').html();
              var buyerName=$(this).siblings('.order_buyer-Name').html();
              var buyerPhone=$(this).siblings('.order_buyer-Phone').html();
              $('.receive_buyer-name').html(buyerName);
              $('.receive_buyer-phone').html(buyerPhone);
              $('.receive_id').html(id);
             cancel_index2= layer.open({
                title: false, //不显示标题
                type: 1,
                skin: 'layui-layer-demo', //样式类名
                closeBtn: 0, //不显示关闭按钮
                shift: 2,
                area: ['780px', '480px'],
                 shadeClose: true, //开启遮罩关闭
                shade: [0.3,'#3d0708'],
                content: $('.confirm-layer')
              });
            $(".receive-back").on("click",function(){
            $(".confirm-layer").css("display","none");
            layer.close(cancel_index2);
        });
  });
  $('.Receive2').on('click',function(){
    $(".Receive1").click();
  });
  //确认收货post
  $('.confirm-receive').on('click',function(){
        var orderID=$('.receive_id').html();
        $.ajax({
            type: 'POST',
            url: '/Shopstaff/weborder/receive',
            data:{
                order_id:orderID
            },
            dataType: 'json',
            success:function(result){
                      if(result.status=="success"){     
                           $().toastmessage('showSuccessToast', "确认收货成功！");
                          window.location.href="/Shopstaff/weborder";
                      }else{
                          alert(result.msg);
                      }
                  }
        });
    });
  //退货点击事件
  $('.refundMoney').on('click',function(){
    $(".refund-btn").click();
  });
  $(".refund-btn").on("click",function(){
      //自定页
               var buyerName=$(this).closest('tr').find('.buyerName').html();
               var buyerPhnoe=$(this).closest('tr').find('.buyerPhnoe').html();
               var orderMoney=$(this).closest('tr').find('.orderMoney').html();
               var refund_description=$(this).closest('tr').find('.refundDescription').html();
               var refund_img_list=$(this).closest('td').siblings('.refund-img-information');
               var i=0;
               $('.refund-imgs').empty();
               $.each(refund_img_list,function(key,val){
                  i++;
                  var img=$(this).children('.img_list');
                  $('.refund-imgs').append(img_list);
               });
               if(i==0){
                  $('.picture').children('.no-picture-mention').css('display','block');
               }
               if(refund_description){
                  $('.refund-description').html(refund_description);
               }else{
                  $('.refund-description').html('无描述信息');
               }
               $('.buyer-name').html(buyerName);
               $('.buyer-phone').html(buyerPhnoe);
               $('.order-money').html(orderMoney);
               // $('.refund-description').html(refund_description);
               var id=$(this).parents('tr').children('.orderID').html();
               $('.id').html(id);
               cancel_index3= layer.open({
                  title: false, //不显示标题
                  type: 1,
                  skin: 'layui-layer-demo', //样式类名
                  closeBtn: 0, //不显示关闭按钮
                  shift: 2,
                  area: ['760px', '620px'],
                   shadeClose: true, //开启遮罩关闭
                  shade: [0.3,'#3d0708'],
                  content: $('.refund-layer')
                });
              $(".refund-back").on("click",function(){
                  $(".refund-layer").css("display","none");
                  layer.close(cancel_index3);
               });
  });
//退款post
$('.confirm-refund').on('click',function(){
      var orderID=$('.id').html();
      var input_money=$('.input-money').val();
      $.ajax({
          type: 'POST',
          url: '/Shopstaff/weborder/refund',
          data:{
              order_id:orderID,
              money:input_money
          },
          dataType: 'json',
          success:function(result){
                    if(result.status=="success"){     
                         $().toastmessage('showSuccessToast', "退款成功！");
                        window.location.href="/Shopstaff/weborder";
                    }else{
                        alert(result.msg);
                    }
                }
      });
  });
</script>
@endsection