<!-- auth:wuwenjia -->
@extends('layouts.app')
@section('siderbar')
@include('layouts.siderbar')
@endsection

@section('addCss')
<link rel="stylesheet" href="{{ URL::asset('shop/css/jquery.toastmessage.css')}}"/>
<link rel="stylesheet" type="text/css" href="{{ URL::asset('shopstaff/shuffling.css')}}">
@endsection

@section('content')
    <!-- 轮播图管理 -->
    <div class="contentManage">
            <div class="contentManage-title">
                        <span>广告图列表</span>
                        <select class="shufflinglists" value="">
                          <option value="0" @if($shop_id==0)selected="selected"@endif>全部</option>
                          @foreach($shop_lists as $list)
                          <option value="{{$list->id}}" @if($shop_id==$list->id)selected="selected"@endif>{{$list->shopname}}</option>
                          @endforeach
                        </select>                    
                        
                        <a href="/Brand/shuffling/add"><button id="account-btn">添加</button></a>                       
            </div>
              @if($shuffling_count)
            <table>
                <thead>
                    <tr>
                        <th width="9.64%"><div class="checkall btn-check"></div>序号</th>
                        <th width="18.07%">名称</th>
                        <th width="18.07%">图片</th>
                        <th width="18.07%">所属分店</th>
                        <th width="18.07%">跳转</th>
                        <th width="18.08%">操作</th>
                    </tr>
                </thead>
              
                <tbody class="tab-lists">
                    @foreach($shuffling_lists as $list)
                    <tr> 
                        <td class="commodity_id" hidden>{{$list->id}}</td>
                        <td><div class="check btn-check"></div><span class='order'>{{$list->order}}</span></td>
                        <td>{{$list->name}}</td>
                        <td><img class="main-img" src="{{asset($list->img_src)}}"></td>
                        <td>{{$list->shopname}}</td>
                        <td>{{$list->http_src}}</td>
                        <td>
                            <a href="/Brand/shuffling/edit/{{$list->id}}">
                                <img class="edit" src="{{asset('shopstaff/images/edit.png')}}">
                            </a>
                            <a href="/Brand/shuffling/delete/{{$list->id}}">
                                <img class="delete" src="{{asset('shopstaff/images/delete.png')}}">
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                </table>
                <button class="btn-up">上移</button>
                <button class="btn-down">下移</button>
                <button class="btn-del">删除</button>
                <div class="clearfix" style="clear:both;"></div>
                <div class="divide-page">
                        <?php echo $shuffling_lists->render(); ?>
                </div>
                @else   
                    <div class="error-mention"> 咦，还没有数据哎...</div>             
                @endif  
            
    </div>
    <div class="clearfix" style="clear:both;"></div>
<script src="{{asset('shop/js/jquery.toastmessage.js')}}"></script> 
<Script type="text/javascript">
            $('.side-list').find('.in').removeClass('in');
            $('#wexin-manage').addClass('in');
            $('.side-list').find('.onsidebar').removeClass('onsidebar');
            $('.weixinmanage').addClass('onsidebar');
            $('.side-list').find('.onsidebarlist').removeClass('onsidebarlist');
            $('.shuffingmanage').addClass('onsidebarlist');
        //悬浮效果
            $(".delete").on("mouseover",function(){
              $(this).attr("src","{{asset('shopstaff/images/delete1.png')}}");
            });
            $(".delete").on("mouseout",function(){
              $(this).attr("src","{{asset('shopstaff/images/delete.png')}}");
            });
            $("#account-btn").on("mouseover",function(){
              $(this).attr("src","{{asset('shopstaff/images/btn-add-to-hover')}}");
            });
            $("#account-btn").on("mouseout",function(){
              $(this).attr("src","{{asset('shopstaff/images/btn-add-to.png')}}");
            });
            $(".edit").on("mouseover",function(){
              $(this).attr("src","{{asset('shopstaff/images/edit1.png')}}");
            });
            $(".edit").on("mouseout",function(){
              $(this).attr("src","{{asset('shopstaff/images/edit.png')}}");
            });
        //选中check
            $('.tab-lists').on('click','.check',function(){
                  if($(this).css('border-style')!="none"){
                    $(this).css('border','none');
                    $(this).css('background-image',"url('/shopstaff/images/check.png')");  
                  }else{
                    $(this).css('border','1px solid');
                    $(this).css('border-color','#d6d6d6');
                    $(this).css('background-image',"");
                  }
                  
              });
              $('.checkall').on('click',function(){
                  if($(this).css('border-style')!="none"){
                    $('.btn-check').css('border','none');
                    $('.btn-check').css('background-image',"url('/shopstaff/images/check.png')");  
                  }else{
                    $('.btn-check').css('border','1px solid');
                    $('.btn-check').css('border-color','#d6d6d6');
                    $('.btn-check').css('background-image',"");
                  }
              });
//筛选店铺
  $('.shufflinglists').change(function(){
      var shop_id=$('.shufflinglists option:selected').val();
      window.location.href = '/Brand/shuffling/index/'+shop_id;
  });
//批量删除
  $('.btn-del').on('click',function(){
      var i=$('.tab-lists .check').size();
      $('.tab-lists .check').each(function(){
          if($(this).css('border-style')=="none"){
              return false;
          }
          i--;
          if(i==0){
              alert("请选择商品！");
          }
      });
      var commodity=new Array();
      $('.tab-lists .check').each(function(){
          if($(this).css('border-style')=="none"){
            var id=$(this).parents('tr').children('.commodity_id').html();
            commodity.unshift(id);
          }
      });            
      $.ajax({
          type: 'POST',
          url: '/Brand/shuffling/deletemulti',
          data:{
              array:commodity
          },
          dataType: 'json',
          success: function(result){
              if(result.status=="success"){
                $().toastmessage('showSuccessToast', "删除成功!");
                window.location.href="/Brand/shuffling/";
              }else{
                alert(result.msg);
              }
          }
      });
  });
$(function(){ 
  //上移  
  $(".btn-up").on('click',function() { 
      var m=0;
      $('.tab-lists .check').each(function(){
          if($(this).css('border-style')=="none"){
              var id=$(this).parents('tr').children('.commodity_id').html();
              var tr=$(this).parents('tr');
              var order= $(this).parents('tr').children().children('.order').html();            
              if (tr.index() != 0) {
                var order1= $(this).parents('tr').prev().children().children('.order').html();
                var o=order;
                $.ajax({
                  type:'post',
                  url:'/Brand/shuffling/order',
                  data:{                            
                    id:id,
                    method:'up'
                  },
                  dataType:"json",
                  success:function(result){
                      if(result.status=="success"){
                          tr.children().children('.order').html(order1);
                          tr.prev().children().children('.order').html(o);                           
                          tr.fadeOut().fadeIn(); 
                          tr.prev().before(tr);
                          
                      }else{
                          alert(result.msg);
                      }                    
                  }
                });                       
              } 
              m++;
              return false;
          }                    
      });
      if(m==0){
          alert("请选择商品！");
      } 
  }); 
  //下移 
  $(".btn-down").on('click',function() {
      var m=0;
      var len = $('.tab-lists .check');
      $('.tab-lists .check').each(function(){
          if($(this).css('border-style')=="none"){
              var id=$(this).parents('tr').children('.commodity_id').html();
              var tr=$(this).parents('tr');
              var order= $(this).parents('tr').children().children('.order').html();
              if (tr.index() != (len-1)) {
                var order1= $(this).parents('tr').next().children().children('.order').html();
                var o=order;                
                $.ajax({
                  type:'post',
                  url:'/Brand/shuffling/order',
                  data:{                            
                    id:id,
                    method:'down'
                  },
                  dataType:"json",
                  success:function(result){
                      if(result.status=="success"){
                          tr.children().children('.order').html(order1);
                          tr.next().children().children('.order').html(o);
                          tr.fadeOut().fadeIn(); 
                          tr.next().after(tr);
                      }else{
                          alert(result.msg);
                      }                    
                  }
                });    
                                   
              } 
              m++;
              return false;
          }                    
      });
      if(m==0){
          alert("请选择商品！");
      } 
  }); 
});
   
                  
            
 </script>
@endsection