<!-- auth:xuedan -->
@extends('layouts.app')
@section('siderbar')
@include('layouts.siderbar')
@endsection

@section('addCss')
<link rel="stylesheet" type="text/css" href="{{ URL::asset('shop/css/jquery.toastmessage.css')}}"/>
<link rel="stylesheet" type="text/css" href="{{ URL::asset('shopstaff/weborder.css')}}">

@endsection

@section('content')
<!--订单管理页-->
  <div class="container-order">
      <div class="condition">
            <p>查询条件</p>
      </div>
      <div class="weborder-title">
                <select class="weborder-select select-numType">
                    <option value="1">订单号</option>
                    <option value="2">支付流水号</option>
                </select>
                <input type="text" class="input-content number" />
                <span class="buyers-name weborder-words">买家姓名</span>
                <input type="text" class="input-content name" />
                <span class="buyers-number weborder-words">买家手机号</span>
                <input type="text" class="input-content phone" />
                 <div class="weborder-time" id="weborder-choose-time">
                <span class="weborder-words">下单时间</span>
                <input type="text"  class="order-time input-content start-time"   id="order-time-start" />
                <span class="order-time  weborder-words">至</span>
                <input type="text" class="order-time  input-content end-time" id="order-time-end"  />
                <span class="near-days days-fir sevenDays" id="near-seven" >近7天</span>
                <span class="near-days day-sec thirtyDays" id="near-thirty">近30天</span>
                </div>
                <div class="weborder-find">
                <span class="weborder-words">订单状态</span>
                <select class="weborder-select select-sec">
                      <option value="0">全部</option>
                      <option value="1">待付款</option>
                      <option value="2">待发货</option>
                      <option value="3">已发货</option>
                      <option value="4">已完成</option>
                      <option value="5">已关闭</option>
                      <option value="6">退款中</option>
                </select>
                <input type="button" value="查询" class="find"/>
                </div>
      </div>
    <div class="navgation" id="nav">
            <div class="lists">
                <span class="order-list">订单列表</span>
            </div>
            <div class="navgation-detial">
                <div class="btn-group"  role="group">
                      <button type="button"  class="active btn all-order">全部订单</button>
                </div>
                <div class="btn-group btnBorder" role="group">
                      <button type="button"  class="btn wait-pay">待付款</button>
                </div>
                <div class="btn-group btnBorder1" role="group">
                      <button type="button"  class="btn wait-send">
                      @if($hurry_times==0)
                      待发货
                      @else
                      待发货<div id="hurryTimes">{{$hurry_times}}</div>
                      @endif
                      </button>
                </div>
                <div class="btn-group"  role="group">
                      <button type="button"  class="btn sent">已发货</button>
                </div>
                <div class="btn-group done-btn"  role="group">
                      <button type="button"  class="btn done  last-three">已完成</button>
                </div>
                <div class="btn-group " role="group">
                      <button type="button"  class="btn closed  last-three">已关闭</button>
                </div>
                <div class="btn-group" role="group">
                      <button type="button"  class="btn refund  last-three">退款中</button>
                </div>
            </div>    
    </div>
    @include('shopstaff.weborder.content')
  </div>
      
      <!--确认收货弹窗-->

<div class="confirm-layer" style="">
    <div class="remind">
      <p class="remind1">您是否已和买家沟通并确认货品已送到？</p>
      <p class="remind2">(建议确认过程中全程录音)</p>
      <p class="remind3"><span class="buyer-name"></span></p>
      <p class="remind4"><span class="buyer-phone"></span></p>
      <span class="id" hidden></span>
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
               <span class='picture-title'>图片:</span>
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
<!--发货弹窗-->

<div class="send-layer" style="">
    <div class="send-info"> 
         <p><span>快递公司：闪送</span></p>
         <p><span>客服电话：<span class="service-phone">400 060 8585</span></span></p>
         <p><span>送货地址：<span class="address"></span></span></p>
         <p><span class="buyer-name"></span></p>
         <p><span class="buyer-phone"></span></p>
         <p><span>快递单号：</span><input type="text" class="input-order-num"/></p>
         <span class="id" hidden></span>
    </div>
    <div class="choose button-style">
      <input type="submit" name="" class="choose-btn cancle-overdue" value="返回">
      <input type="submit" name="" class="choose-btn confirm-send" value="确定">
    </div>
</div>

</div>
<!-- <audio id="audio1" src="{{URL::asset('shopstaff/music/order.mp3')}}"  controls="controls" hidden="true" ></audio>  -->
<audio id="audio2" src="{{URL::asset('shopstaff/music/pay.mp3')}}"  controls="controls" hidden="true" ></audio> 
<!-- <audio id="audio3" src="{{URL::asset('shopstaff/music/change.mp3')}}"  controls="controls" hidden="true" ></audio> -->
<audio id="audio4" src="{{URL::asset('shopstaff/music/be_quick.mp3')}}"  controls="controls" hidden="true" ></audio>  
<script src="{{URL::asset('shopstaff/socket.io.js')}}"></script>
<script src="{{asset('shop/js/jquery.toastmessage.js')}}"></script> 
<script>
var height=$('.content-right').height();

$('.siderbar').height(height);

//添加音乐
// var audio_order=document.getElementById('audio1');
var audio_pay=document.getElementById('audio2');
// var audio_change=document.getElementById('audio3');
var audio_quick=document.getElementById('audio4');
var socket = io('http://121.42.136.52:2999');
socket.on('new message',function(data){
  console.log(data);
  //alert(data.type);
  if(data.type=="order"){
    audio_order.play();
  }else if(data.type=="pay"){
    audio_pay.play();
  }else if(data.type=="change"){
    audio_change.play();
  }else if(data.type=="quick"){
    audio_quick.play();
  };

  socket.emit('my order',{my:'data'});
}); 
//选择自定义日期，近7天，近30天的date_type的获取。
var time_type; 
$(".start-time").on("click",function(){   
    time_type=1;
    }); 
$(".end-time").on("click",function(){   
    time_type=1;
    }); 
$(".sevenDays").on("click",function(){   
    time_type=2;
    }); 
$(".thirtyDays").on("click",function(){   
    time_type=3;
    }); 
//导航栏切换的全局变量
var choose_type;
$(".all-order").on("click",function(){   
    choose_type=0;
    }); 
$(".wait-pay").on("click",function(){   
    choose_type=1;
    }); 
$(".wait-send").on("click",function(){   
    choose_type=2;
    }); 
$(".sent").on("click",function(){   
    choose_type=3;
    }); 
$(".done").on("click",function(){   
    choose_type=4;
    });
$(".closed").on("click",function(){   
    choose_type=5;
    });
$(".refund").on("click",function(){   
    choose_type=6;
    });
//导航栏切换
window.onload=function()
  {
    var aBtn=document.getElementById('nav').getElementsByTagName('button');
    var i=0;
    for(i=0;i<aBtn.length;i++)
    {
  //给每个按钮加点击事件
         aBtn[i].onclick=function()
     {
       for(i=0;i<aBtn.length;i++)
       {   aBtn[i].index=i;  //把所有按钮加上索引值。
         aBtn[i].className='btn';  //把所有按钮的class都设为.btn的。
        
       } 
  //       alert('点击了第'+this.index+'个按钮')
  //this指的是当前发生事件的元素，此处指的是button按钮。
         this.className='active';  //当前点击的按钮的class设为active。  
        
       }
    }
  }

//日历
var start = {
      dateCell: '#order-time-start',
      format: 'YYYY-MM-DD hh:mm:ss',
      festival:true,
      isTime: true,
      }
var end = {
      dateCell: '#order-time-end',
      format: 'YYYY-MM-DD hh:mm:ss',
      festival:true,
      isTime: true,
      }
     jeDate(start);
     jeDate(end);

//筛选分组
  $('.find').on('click',function(){
      var num_type=$('.select-numType option:selected').val();
      var number=$('.number').val();
      var name=$('.name').val();
      var phone=$('.phone').val();
      var date_start=$('#order-time-start').val();
      var date_end=$('#order-time-end').val();
      var status=$('.select-sec option:selected').val();
      $.ajax({
          type: 'POST',
          url: '/Shopstaff/weborder/select',
          data:{
              num_type:num_type,
              number:number,
              name:name,
              phone:phone,
              date_start:date_start,
              date_end:date_end,
              status:status,
              date_type:time_type
          },
          dataType: 'json',
          success: function(result){
            // var btn_group = $('.btn-group');
            // $.each(btn_group,function(i,val){
            //   $(this).children().find('active').removeClass('active');
            // });
            $('.btn').removeClass('active');
            if(status==0){
              $('.all-order').addClass('active');
            }else if(status==1){
              $('.wait-pay').addClass('active');
            }else if(status==2){
              $('.wait-send').addClass('active');
            }else if(status==3){
              $('.send').addClass('active');
            }else if(status==4){
              $('.done').addClass('active');
            }else if(status==5){
              $('.closed').addClass('active');
            }else if(status==6){
              $('.refund').addClass('active');
            }
            $('.list-content').html(result);
            if($('.lists-detail-body').children('.detail-tr').length==0){
              $('.mention-tips').show();
            }
          }
      });
  });
//导航栏的切换
/* $('.btn').on('click',function(){
      $.ajax({
          type: 'POST',
          url: '/Shopstaff/weborder/select',
          data:{ 
              status:choose_type       
          },
          dataType: 'json',
          success: function(result){
            $('.lists-detail-body').html(result);
          }
      });
  });
*/
//导航栏切换

 $('.btn').on('click',function(){
      $.ajax({
          type: 'GET',
          url: '/Shopstaff/weborder/changestatus/'+choose_type,
          dataType: 'json',
           data:{  
              status:choose_type,  
          },
          success: function(result){
            $('.list-content').html(result);
            if($('.lists-detail-body').children('.detail-tr').length==0){
              $('.mention-tips').show();
            }
          }
      });
  });
//移除催货次数的气泡
$('.wait-send').on('click',function(){
       $("#hurryTimes").remove();
     });
//分页
$('.list-content').on('click', '.pagination a', function(e) {
    var url = $(this).attr('href');
    var num_type=$('.select-numType option:selected').val();
    var number=$('.number').val();
    var name=$('.name').val();
    var phone=$('.phone').val();
    var date_start=$('#order-time-start').val();
    var date_end=$('#order-time-end').val();
    var status=$('.select-sec option:selected').val();
    if(url.indexOf('select')>=0){
      e.preventDefault();
      $.post(url, {
              num_type:num_type,
              number:number,
              name:name,
              phone:phone,
              date_start:date_start,
              date_end:date_end,
              status:status,
              date_type:time_type
      }, function(data){
          $('.list-content').html(data); 
        });
    }else if(url.indexOf('changestatus')>=0){
      e.preventDefault();
        $.get(url, {
          status:choose_type, 
        }, function(data){
            $('.list-content').html(data); 
          });
      }
  });

//发货post
$('.confirm-send').on('click',function(){
      var orderID=$('.id').html();
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
//确认收货post
$('.confirm-receive').on('click',function(){
      var orderID=$('.id').html();
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
//近几天的选中状态
$(".near-days").on("click",function(){
        $(".near-days").removeClass("near-days-visited");
        $(this).addClass("near-days-visited");
      })
//确认收货弹窗
$(".container-order").on("click",'.confirm-btn',function(){
    //自定页
    console.log('确认收货');
            var buyerName=$(this).closest('tr').find('.buyerName').html();
            var buyerPhnoe=$(this).closest('tr').find('.buyerPhnoe').html();
            $('.buyer-name').html(buyerName);
            $('.buyer-phone').html(buyerPhnoe);
            var id=$(this).parents('tr').children('.orderID').html();
            $('.id').html(id);
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
//退货弹窗
$(".container-order").on("click",'.refund-btn',function(){
    //自定页
    console.log('退货');
             var buyerName=$(this).closest('tr').find('.buyerName').html();
             var buyerPhnoe=$(this).closest('tr').find('.buyerPhnoe').html();
             var orderMoney=$(this).closest('tr').find('.orderMoney').html();
             var refund_description=$(this).closest('tr').find('.refundDescription').html();
             var refund_img_list=$(this).closest('td').siblings('.refund-img-information');
             var i=0;
             $('.refund-imgs').empty();
             $.each(refund_img_list,function(key,val){
                i++;
                var img_list=$(this).children('.img_list');
                var img_src=$(this).children('.img_list').attr('src');
                console.log(img_src);
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
             $('.refund-description').html(refund_description);

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
//发货弹窗
$(".container-order").on("click",'.send-btn',function(){
  console.log('发货');
       var buyerName=$(this).closest('tr').find('.buyerName').html();
       var buyerPhnoe=$(this).closest('tr').find('.buyerPhnoe').html();
       var addressdetail=$(this).closest('tr').find('.receiver_province').html();
       var servicePhone=$(this).closest('tr').find('.servicePhone').html();
       $('.address').html(addressdetail);
       // $('.service-phone').html(servicePhone);
       $('.buyer-name').html(buyerName);
       $('.buyer-phone').html(buyerPhnoe);
       var id=$(this).parents('tr').children('.orderID').html();
       $('.id').html(id);
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
</script>


@endsection