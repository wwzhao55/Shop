<!-- auth:wuwenjia -->
@extends('layouts.app')
@section('siderbar')
@include('layouts.siderbar')
@endsection
@section('addCss')
<link rel="stylesheet" href="{{ URL::asset('shop/css/jquery.toastmessage.css')}}"/>
<link rel="stylesheet" type="text/css" href="{{ URL::asset('shop/css/commodity.css')}}">
@endsection
@section('content')
     <div class="container-commodity">
          <div id="commodity-saling">
            <div class="commodity-class">
              <span class="commodity-title">商品列表</span>
              <a href="/Brand/commodity/add" class="commodity-className commodity-pubilc">发布商品</a>
                  <select  class="commodity-select" value="">
                      <option value="0">所有分组</option>
                      @foreach($group_lists as $list)
                          <option value="{{$list->id}}">{{$list->name}}</option>
                      @endforeach
                  </select>
                  <div class="commodity-search">
                      <img class="img-search" src="{{asset('shopstaff/images/search.png')}}"><input type="text" class="search-commodity"/><span class="search">搜索</span>  
                  </div>
            </div>
            @if(Session::get('Message'))
            <div class="bg-info" style="padding:15px;color:#337ab7;text-align:center;font-size: 18px;">{{Session::get('Message')}}</div>
            @endif
            
            @include('brand.commodity.content')
        </div>
      </div>  
<script src="{{asset('shop/js/jquery.toastmessage.js')}}"></script>   
<script>
  $('.side-list').find('.in').removeClass('in');
  $('#commodity-manage').addClass('in');
  $('.side-list').find('.onsidebar').removeClass('onsidebar');
  $('.commoditymanage').addClass('onsidebar');
  $('.side-list').find('.onsidebarlist').removeClass('onsidebarlist');
  $('.maintenance').addClass('onsidebarlist');

  $('.commodity-status').click(function(){
      $('.commodity-status').removeClass('commodity-active');
      $(this).addClass('commodity-active');
      $('.commodity-tbody').addClass('element-hide');
      $('tbody[cellspacing='+$(this).attr('list-value')+']').removeClass('element-hide');
  });
  
//筛选分组
  $('.commodity-select').change(function(){
      var keyword=$('.search-commodity').val();
      var group_id=$('.commodity-select option:selected').val();
      $.ajax({
          type: 'POST',
          url: '/Brand/commodity/search',
          data:{
              group:group_id,
              keyword:keyword
          },
          dataType: 'json',
          success: function(result){
            $('.commodity-content').html(result);
          }
      });
  });
//搜索功能
  $('.search').on('click',function(){
      var keyword=$('.search-commodity').val();
      var group_id=$('.commodity-select option:selected').val(); 
      if(keyword!=''){
          $.ajax({
              type: 'POST',
              url: '/Brand/commodity/search',
              data:{
                keyword:keyword,
                group:group_id
              },
              dataType: 'json',
              success: function(result){
                  // if(result.status=="success"){
                  //       $('.commodity-tbody').children('tr').remove();
                  //       var search_result=$('.commodity-tbody');                        
                  //       if(result.commodity_count==0){                            
                  //           var tip=$("<div class='tips'>暂无搜索结果</div>");
                  //           search_result.append(tip);
                  //       }else{
                  //           for(var i=0;i<result.commodity_count;i++){
                  //               var a_tr=$("<tr class='commodity-tr'></tr>");
                  //               var td1=$("<td class='group_id' hidden>"+result.commodity_lists[i].group_id+"</td>");
                  //               var td2=$("<td class='commodity_list_td commodity-display'></td>");
                  //               var td3=$("<td class='commodity_list_td'></td>");
                  //               var td4=$("<td class='commodity_list_td'></td>");
                  //               var td5=$("<td class='commodity_list_td'></td>");
                  //               var td6=$("<td class='commodity_list_td commodity_id' hidden>"+result.commodity_lists[i].id+"</td>");
                  //               var td7=$("<td class='commodity_list_td'></td>");
                  //               var td8=$("<td class='commodity_list_td'></td>");
                  //               var td2_div1=$("<div class='check btn-check'></div>");
                  //               var td2_img=$("<img src='"+result.commodity_lists[i].main_img+"' class='commodity-img'>");
                  //               var td2_div2=$("<div class='commodity-name'></div>");
                  //               var td2_div2_p1=$("<p class='name'>"+result.commodity_lists[i].commodity_name+"</p>");
                  //               var td2_div2_p2=$("<p class='number'>1</p>");
                  //               var td2_div2_p3=$("<p class='value'>￥"+result.commodity_lists[i].base_price+"</p>");
                  //               var td3_p1=$("<p class='PV'>PV:"+result.commodity_lists[i].PV+"</p>");
                  //               var td3_p2=$("<p>UV:"+result.commodity_lists[i].UV+"</p>");
                  //               var td4_p=$("<p>"+result.commodity_lists[i].quantity+"</p>");
                  //               var td5_p=$("<p>"+result.commodity_lists[i].saled_count+"</p>");
                  //               var td7_p=$("<p>"+result.commodity_lists[i].created_at+"</p>");
                  //               var td8_a1=$("<a href='/Brand/commodity/edit/"+result.commodity_lists[i].id+"'></a>");
                  //               var td8_a1_img1=$("<img src='/shopstaff/img/icon-edit.png' class='someImg'>");
                  //               var td8_a1_img2=$("<img src='/shopstaff/img/icon-edit-hover.png' class='someImg_hover element-hide'>");
                  //               var td8_a2=$("<a href=//Brand/commodity/delete/"+result.commodity_lists[i].id+" style='margin-left:5px;'></a>");
                  //               var td8_a2_img1=$("<img src='/shopstaff/img/icon-delete.png' class='someImg'>");
                  //               var td8_a2_img2=$("<img src='/shopstaff/img/icon-delete-hover.png' class='someImg_hover element-hide' >");
                  //               td2_div2.append(td2_div2_p1).append(td2_div2_p2).append(td2_div2_p3);
                  //               td2.append(td2_div1).append(td2_img).append(td2_div2);
                  //               td3.append(td3_p1).append(td3_p2);
                  //               td4.append(td4_p);
                  //               td5.append(td5_p);
                  //               td7.append(td7_p);
                  //               td8_a1.append(td8_a1_img1).append(td8_a1_img2);
                  //               td8_a2.append(td8_a2_img1).append(td8_a2_img2);
                  //               td8.append(td8_a1).append(td8_a2);
                  //               a_tr.append(td1).append(td2).append(td3).append(td4).append(td5).append(td6).append(td7).append(td8);
                  //               search_result.append(a_tr);


                  //           }
                  //       }
                  //       $('#search_view').css('display','none');
                  //       $('#search_result').css('display','block');
                  // }else{
                  //     alert(result.msg);
                  // }       
                  $('.commodity-content').html(result);       
              }              
          });  
      }else{
          alert("搜索内容不能为空！");
      }
      
  });
//搜索结果分页
$('.commodity-content').on('click', '.pagination a', function(e) {
  var url = $(this).attr('href');
  if(url.indexOf('search')>=0){
      e.preventDefault();
      $.post(url, {
        keyword:$('.search-commodity').val(),
        group:$('.commodity-select option:selected').val()
      }, function(data){
          $('.commodity-content').html(data); 
      });
  }
   
});
//商品改分组
  $('.btn-down').on('click',function(){
      if($('.btn-down').html()=='商品改分组'){
        var i=$('.commodity-tbody .check').size();
        $('.commodity-tbody .check').each(function(){
            if($(this).css('border-style')=="none"){
              $('.group_modify').css('display','block');
              $('.btn-down').html('取消');
              return false;
            }
            i--;
            if(i==0){
              alert("请选择商品！");
            }
        });
      }else{
        $('.group_modify').css('display','none');
        $('.btn-down').html('商品改分组');
      }
      
  });
  $('.container-commodity').on('click','.group_modify li',function(){
      var group=$(this).val();
      var commodity=new Array();
      $('.commodity-tbody .check').each(function(){
          if($(this).css('border-style')=="none"){
            var id=$(this).parents('tr').children('.commodity_id').html();
            commodity.unshift(id);
          }
      });            
      $.ajax({
          type: 'POST',
          url: '/Brand/commodity/changegroup',
          data:{
              group:group,
              commodity:commodity
          },
          dataType: 'json',
          success: function(result){
              if(result.status=="success"){
                $().toastmessage('showSuccessToast', "该商品分组修改成功!");
                window.location.href="/Brand/commodity/";
              }else{
                alert(result.msg);
              }
          }
        });
  });
//批量删除
  $('.btn-del').on('click',function(){
      var i=$('.commodity-tbody .check').size();
      $('.commodity-tbody .check').each(function(){
          if($(this).css('border-style')=="none"){
              return false;
          }
          i--;
          if(i==0){
              alert("请选择商品！");
          }
      });
      var commodity=new Array();
      $('.commodity-tbody .check').each(function(){
          if($(this).css('border-style')=="none"){
            var id=$(this).parents('tr').children('.commodity_id').html();
            commodity.unshift(id);
          }
      });            
      $.ajax({
          type: 'POST',
          url: '/Brand/commodity/deletemore',
          data:{
              commodity:commodity
          },
          dataType: 'json',
          success: function(result){
              if(result.status=="success"){
                $().toastmessage('showSuccessToast', "删除成功!");
                window.location.href="/Brand/commodity/";
              }else{
                alert(result.msg);
              }
          }
      });
  });



            //选中check
              $('.table-commodity').on('click','.check',function(){
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
         
         function hoverChangeImgClass(img_class_info){
            for(var i=0;i<img_class_info.length;i++){
              (function(i){
                var img_class = img_class_info[i];
                $("."+img_class).hover(function(){
                    $(this).addClass("element-hide");
                    $(this).next().removeClass("element-hide");
                  });
                  $("."+img_class+"_hover").hover(function(){
                  },function(){
                    $(this).addClass("element-hide");
                    $(this).prev().removeClass("element-hide");

                  });
              })(i);      
            }
          }
          hoverChangeImgClass(['someImg']);


</script>
@endsection