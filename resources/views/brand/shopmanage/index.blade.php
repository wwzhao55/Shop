<!-- auth:wuwenjia -->
@extends('layouts.app')
@section('siderbar')
@include('layouts.siderbar')
@endsection

@section('addCss')
<link rel="stylesheet" type="text/css" href="{{ URL::asset('shop/css/brandmanage-shop.css')}}">
@endsection

@section('content')
<div class="shop-manage">
  <div class="shop">
    <span class="shop-title">已入驻分店</span>
      @if($open_weishop_count)
        {{Session::get('Message')}}
        <table class="shop-out">
            <thead>
                <tr>
                    <th width="15%">分店名</th>
                    <th width="15%">开通时间</th>
                    <th width="30%">地址</th>
                    <th width="10%">联系人</th>
                    <th width="10%">电话</th>
                    <th width="20%">操作</th>
                </tr>
            </thead>
            <tbody id="tab-contents">
                @foreach($open_weishop_lists as $list)
                <tr>
                    <td>{{$list->shopname}}</td>
                    <td>{{$list->created_at}}</td>
                    <td class='address-list'>{{$list->shop_province}}{{$list->shop_city}}{{$list->shop_district}}{{$list->shop_address_detail}}</td>
                    <td>{{$list->contacter_name}}</td>
                    <td>{{$list->contacter_phone}}</td>
                    <td><input type="hidden" value='{{$list->id}}' /><input type="button" value="移出微店" class="removed">
                    <a href='/Brand/shopmanage/edit/{{$list->id}}'><input type="button" value="编辑" class="Settled-edit"></a></td>
                </tr>
                @endforeach
            </tbody> 
        </table>
          @else
             <p class="bg-success" style="padding:15px">咦，还没有数据哎</p>
          @endif 
  </div>
  <div class="shop">
      <span class="shop-title">未入驻分店</span>
      @if($close_weishop_count)
        {{Session::get('Message')}}
        <table class="shop-out">
            <thead>
                <tr>
                    <th width="15%">分店名</th>
                    <th width="15%">开通时间</th>
                    <th width="30%">地址</th>
                    <th width="10%">联系人</th>
                    <th width="10%">电话</th>
                    <th width="20%">操作</th>
                </tr>
            </thead>
            <tbody id="tab-contents">
                @foreach($close_weishop_lists as $list)
                <tr>
                    <td>{{$list->shopname}}</td>
                    <td>{{$list->created_at}}</td>
                    <td>{{$list->shop_province}}{{$list->shop_city}}{{$list->shop_district}}{{$list->shop_address_detail}}</td>
                    <td>{{$list->contacter_name}}</td>
                    <td>{{$list->contacter_phone}}</td>
                    <td><input type="hidden" value='{{$list->id}}' /><input type="button" value="入驻微店" class="Settled">
                    </td>
                </tr>
                @endforeach
            </tbody> 
        </table>
          @else
             <p class="bg-success" style="padding:15px">咦，还没有数据哎</p>
          @endif 
  </div>
  <div class="confrimRemove">
          <span class="confrim-title">你确定要此分店移出微店吗？</span>
          <input placeholder="请输入登陆密码" type="password" class="shop-id"/> 
          <div class="btn-confrim">
            <button class="btn-cancel allhover">取消</button>
            <button class="btn-determine allhover">确定</button>
            <!-- <img src="{{URL::asset('admin/img/btn-cancel.png')}}" class="btn-cancel"/>
            <img src="{{URL::asset('admin/img/btn-determine.png')}}" class="btn-determine"/> -->
          </div>
  </div>
</div>
  
    


<script type="text/javascript">
$('.side-list').find('.onsidebar').removeClass('onsidebar');
$('.shopmanage').addClass('onsidebar');
  $(".removed,.Settled,.Settled-edit").on("mouseover",function(){
        $(this).css('color','#fff');
        $(this).css('background-color','#fb2d5c');
  });
    $(".removed,.Settled,.Settled-edit").on("mouseout",function(){
        $(this).css('color','#999');
        $(this).css('background-color','#fff');


  })
     
     var shop_id,cancel_index;
    $(".removed").on("click",function(){
       shop_id=$(this).prev().val(); //当前元素前一个兄弟节点
       $(".confrim-title").html("你确定要此分店移出微店吗？");
       cancel_index=layer.open({
              type: 1,
              title: false,
              closeBtn: 0,
              shadeClose: true,
              skin: 'yourclass',
              shade: 0.5,
              area : ['870px' , '300px'],
              content:$('.confrimRemove'),          
                      });
    });
    $(".Settled").on("click",function(){
      shop_id=$(this).prev().val(); //当前元素前一个兄弟节点
      $(".confrim-title").html("你确定要此分店入驻微店吗？");
      cancel_index=layer.open({
              type: 1,
              title: false,
              closeBtn: 0,
              shadeClose: true,
              skin: 'yourclass',
              shade: 0.5,
              area : ['870px' , '300px'],
              content:$('.confrimRemove'),          
                      });
    })
    $(".btn-cancel").on("click",function(){
         layer.close(cancel_index);
    })
    $(".btn-determine").on("click",function(){
      var password=$(".shop-id").val();
       if(password==""){
         $(".shop-id").addClass('shop-id-border');
       }
       else{
        if($(".shop-id").hasClass('shop-id-border')){
          $(".shop-id").removeClass('shop-id-border');
        }
        else{
           $.ajax({
             type: "post",
             url: "/Brand/shopmanage/openweishop",
             data: {
                password:password, 
                shop_id:shop_id,
              },
             dataType: "json",
             success: function(data){
               if(data.status=="success"){
                 window.location.reload();
               }
               else{
                 layer.tips("密码输入错误，请重新输入！", $(".shop-id"), {
                tips: [1, '#F92672'],
               time: 2000
         });
               }
              
              }
            });
        }
     }
    })
  
</script>
@endsection
