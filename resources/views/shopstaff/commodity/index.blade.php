<!-- auth：lijingchao -->
@extends('layouts.app')
@section('siderbar')
@include('layouts.siderbar')
@endsection

@section('addCss') 
<link rel="stylesheet" type="text/css" href="{{URL::asset('shopstaff/commoditystock.css')}}">
@endsection 

@section('content')
<!-- 内容引进 -->
<div class="container">
  <div class="title_bar">
    <span class="title">商品列表</span>
    <select class="all_group" >
        <option class="group1" value="0">所有分组</option>
        @foreach($group as $list)
          <option value="{{$list->id}}">{{$list->name}}</option>
        @endforeach 
    </select>  
    <select class="all_status" >
        <option value="0">所有状态</option>
        <option value="1">出售中</option>
        <option value="2">售罄</option>
    </select>
    <input type="text" class="search" placeholder="搜索">
  </div>
  <div class="list_head">
    <div class="list">
      <table class="list_head_content">
        <tr>
          <th><div class="checkbox1"></div>
              <div class="check_click1">
                 <img src="{{asset('shopstaff/images/check.png')}}" alt="">
              </div>
              <span>商品名称（价格）</span></th>
          <th >库存（总）</th>
          <th>总销量</th>
          <th class="status-head">状态</th>
          <th class="operate">操作</th>
          <th class="update_all">刷新</th>

        </tr>
      </table>
      <!-- <span class="update_all">刷新</span>  -->
    </div>
  </div>
  <div class='words-tips' hidden>咦，还没有数据哎...</div>
  
  @include('shopstaff.commodity.content')

</div>

<script type="text/javascript">
$('.siderbar').height($('.content-right').height());
//刷新
  $('.list .update_all').on('click',function(){
      var group_id = $(".all_group option:selected").val();
      var status_id = $(".all_status option:selected").val();
      var content = $(".search").val();
      $.ajax({
            type:'POST',
            url:'/Shopstaff/commodity/select',
            data:{
                  group:group_id,
                  status:status_id,
                  content:content
            },
            dataType:"json",
            success:function(result){
              $('.list-container').html(result);                  
                              },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                                    // alert("失败，请检查网络后重试");
                                     // alert(result.msg);
                                }
            });
  });

// 切换分类，状态，搜索
   $('.all_group').change(function(){
      var group_id = $(".all_group option:selected").val();
      var status_id = $(".all_status option:selected").val();
      var content = $(".search").val();
      $.ajax({
            type:'POST',
            url:'/Shopstaff/commodity/select',
            data:{
                  group:group_id,
                  status:status_id,
                  content:content
            },
            dataType:"json",
            success:function(result){
              $('.list-container').html(result);                  
                              },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                                    // alert("失败，请检查网络后重试");
                                     alert(result.msg);
                                }
            });
    });

   $('.all_status').change(function(){
      var group_id = $(".all_group option:selected").val();
      var status_id = $(".all_status option:selected").val();
      var content = $(".search").val();
      $.ajax({
            type:'POST',
            url:'/Shopstaff/commodity/select',
            data:{
                  group:group_id,
                  status:status_id,
                  content:content
            },
            dataType:"json",
            success:function(result){
              $('.list-container').html(result);                  
                              },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                                    // alert("失败，请检查网络后重试");
                                    alert(result.msg);
                                }
            });
    });

   $('.search').change(function(){
      var group_id = $(".all_group option:selected").val();
      var status_id = $(".all_status option:selected").val();
      var content = $(".search").val();
      $.ajax({
            type:'POST',
            url:'/Shopstaff/commodity/select',
            data:{
                  group:group_id,
                  status:status_id,
                  content:content
            },
            dataType:"json",
            success:function(result){
              $('.list-container').html(result);                  
                              },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                                    // alert("失败，请检查网络后重试");
                                    alert(result.msg);
                                }
            });
    });

//更新库存
    $('.container').on('click','.update-stock',function(){
      var status;
      var object=$(this).parent('.update').siblings('.status');
      var s = $(this).parent('.update').siblings('.status').html();
      if(s=='出售中'){
        status=1;
      }else if(s=='售罄'){
        status=0;
      }else{
        status=2;
      }
      $('.window-status').val(status);
      var quantity=$(this).parents('.list_content').children('.stock').children('.quantity').html();
      var a=$(this).parents('.list_content').children('.sku_id').html();
      $('.skuid').val(a);
      var b=$(this).parents('.list_content').children('.commodity_id').html();
      $('.commodityid').val(b);

      $('.layer1 .get-stock').html(quantity);
        cancel_index=layer.open({
        title: false, //不显示标题
        type: 1,
        skin: 'layui-layer-demo', //样式类名
        closeBtn: 0, //不显示关闭按钮
        shift: 2,
        area: ['400px', '250px'],
        //shadeClose: true, //开启遮罩关闭
        //shade: [0.3,'#3d0708'],
        content: $('.layer1'),
      });
        if(status!=2){
          $.ajax({
              type:'POST',
              url:'/Shopstaff/commodity/pause',
              data:{
                    sku_id:a,
                    status:status,
              },
              dataType:"json",
              success:function(result){
                    if(result.status=='success'){
                      // object.html('暂停');
                    }else{
                         alert(result.msg);
                    }                               
              }
          }); 
        }       
    });

    $('.container').on('click','.up-btn',function(){
          layer.closeAll('page');
          var quantity = $(this).parents('.layer1').children('.latest').children('.latest-number').val();
          var sku=$('.skuid').val();
          var commodity = $('.commodityid').val();
          $.ajax({
            type:'POST',
            url:'/Shopstaff/commodity/updatequantity',
            data:{
                  sku_id:sku,
                  quantity:quantity,
                  commodity_id:commodity
            },
            dataType:"json",
            success:function(result){
                  self.location.reload();          
                              },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                                    // alert("失败，请检查网络后重试");
                                   // alert(errorThrown);
                                }
            });
        });
    $('.container').on('click','.cancle',function(){
      var sku=$(this).siblings('.skuid').val();
      var status=$(this).siblings('.window-status').val();
      var obj=$(this).siblings('.window-status');
      layer.close(cancel_index);
      //$('.layer1').hide();
      if(status!=2){
          $.ajax({
              type:'POST',
              url:'/Shopstaff/commodity/pause',
              data:{
                    sku_id:sku,
                    status:status,
              },
              dataType:"json",
              success:function(result){
                    if(result.status=='success'){
                      
                    }else{
                         alert(result.msg);
                    }                               
              }
          });
        }          
    });
//上架
    $('.container').on('click','.up-stock',function(){
      var n=$(this).parents('.list_content').children('.stock').children('.quantity').html();
      $('.layer1 .get-stock').html(n);
      var sku_id=$(this).parents('.list_content').children('.sku_id').html();
      var commodity_id=$(this).parents('.list_content').children('.commodity_id').html();
      if (n==0) {       
        layer.open({
          title: false, //不显示标题
          type: 1,
          skin: 'layui-layer-demo', //样式类名
          closeBtn: 0, //不显示关闭按钮
          shift: 2,
          area: ['400px', '250px'],
          // shadeClose: true, //开启遮罩关闭
          //shade: [0.3,'#3d0708'],
          content: $('.layer1'),
        });
        var a=$(this).parents('.list_content').children('.sku_id').html();
        $('.skuid').val(a);
        var b=$(this).parents('.list_content').children('.commodity_id').html();
        $('.commodityid').val(b);
      }else{
        // alert('当前有库存！')
          $.ajax({
              type:'POST',
              url:'/Shopstaff/commodity/shelfon',
              data:{
                    sku_id:sku_id,
                    commodity_id:commodity_id,
              },
              dataType:"json",
              success:function(result){
                    if(result.status=='success'){
                      window.location.reload();
                    }else{
                         alert(result.msg);
                    }                               
              }
        });   
      }

    });

//下架
  $(".list-container").on("click",".down-stock",function(){
      var a=$(this).parents('.list_content').children('.sku_id').html();
      $('.skuid').val(a);
      var b=$(this).parents('.list_content').children('.commodity_id').html();
      $('.commodityid').val(b);
      //自定页
      layer.open({
        title: false, //不显示标题
        type: 1,
        skin: 'layui-layer-demo', //样式类名
        closeBtn: 0, //不显示关闭按钮
        shift: 2,
        area: ['700px', '380px'],
        shadeClose: true, //开启遮罩关闭
        // shade: [0.3,'#3d0708'],
        content: $('.layer2'),
      });
  });
  var m=new Array();
  $('.list-container').on('click','.choose .choose-sure',function(){
        layer.closeAll('page');
        var sku=$('.skuid').val();
        var commodity = $('.commodityid').val();
        var m='['+'{'+'"commodity_id":'+commodity+','+'"sku_id":'+sku+'}'+']';
        $.ajax({
            type:'POST',
            url:'/Shopstaff/commodity/shelfoff',
            data:{
                  commoditys:m
            },
            dataType:"json",
            success:function(result){
                  self.location.reload();
                                
                              },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                                    // alert("失败，请检查网络后重试");
                                    // alert(errorThrown);
                                }
            });
    });
    $('.list-container').on('click','.choose .choose-cancel',function(){
        layer.closeAll('page');
    });

//批量下架
   $('.list-container').on('click','.button',function(){
     //自定页
      layer.open({
        title: false, //不显示标题
        type: 1,
        skin: 'layui-layer-demo', //样式类名
        closeBtn: 0, //不显示关闭按钮
        shift: 2,
        area: ['700px', '380px'],
        shadeClose: true, //开启遮罩关闭
        // shade: [0.3,'#3d0708'],
        content: $('.layer2'),
      });
     var arr1=new Array();
     var arr2=new Array();
     $('.check_click').each(function(){
        if ($(this).css('display')=='block') {
          var sku=$(this).parents('.list_content').children('.sku_id').html();
          var commodity=$(this).parents('.list_content').children('.commodity_id').html();
           // alert(sku);
          var arr1='{'+' "commodity_id":'+commodity+','+' "sku_id":'+sku+'}';
          arr2.push(arr1);
          var down ='['+arr2+']';
          // var down = JSON.stringify(arr2);
          // alert(down);
          $('.list-container').on('click','.choose .choose-sure',function(){
            layer.closeAll('page');
            $.ajax({
              type:'POST',
              url:'/Shopstaff/commodity/shelfoff',
              data:{
                    commoditys:down
              },
              dataType:"json",
              success:function(result){
                    self.location.reload();
                                      }
            });
          });
        }
     });
   });

if($(".list_content").length==0){
  $('.list_head').css('display','none');
  $('.words-tips').css('display','block');
  $('.button').css('display','none');
}
</script>
@endsection