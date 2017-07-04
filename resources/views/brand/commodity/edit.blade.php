<!-- auth:wuwenjia -->
@extends('layouts.app')
@section('siderbar')
@include('layouts.siderbar')
@endsection
@section('addCss')
<link rel="stylesheet" type="text/css" href="{{URL::asset('admin/css/AdminLTE.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{URL::asset('shop/css/add-commodity.css')}}">

@endsection
@section('content')
    <div class="container-commodity">
       <div class="commodity-edit">
            <span class="commodity-word">发布商品</span>
            <a href="/Brand/commodity/"><button class="commodity-return allhover">返回</button></a>
        </div>

        <div class="commodity-status on select">分类选择</div>
        <div class="commodity-status message">基本信息</div>
        <div class="commodity-status detail">商品详情</div>
    
        <div class="choose-commodity">
             <div class="commodity-class">
              @foreach($main_business_lists as $list)
                <div class="commodity-list" category-id='{{$list->id}}' @if($commodity->category_id==$list->id)style='background-color:red;color:#fff' @endif>{{$list->name}}</div>
                <input type="text" name="category_name" id="category_name" value="{{$commodity->category_name}}" hidden>
                <input type="text" name="category_id" id="catetory_id" value="{{$commodity->category_id}}" hidden>
              @endforeach
              </div> 
             <div class="next">
                <button id="choose-next" class="allhover">下一步</button>
             </div>
        </div>

        <div class="commodity-msg-edit">       
                <div class="edit-title">基本信息</div>
                <div class="edit-commodity">
                    <div class="commodity-class-list" style="margin-bottom:30px;">
                       <span>商品分类：</span>
                       <span style="width:150px;text-align:left;" class='category_name'></span>
                    </div>
                    <div class="commodity-class-list div_name">
                       <span>商品名称：</span>
                       <input type="text" class="class-commodity" value="{{$commodity->commodity_name}}" name="commodity_name" id="commodity_name" />
                    </div>
                    <div class="commodity-class-list div_category">
                       <span>商品分组：</span>
                       <select id="commodity_group" class="class-commodity" name="commodity_group">
                       </select>
                       <a href="javascript:void(0)" id="refresh_group">刷新</a>
                       <a href="/Brand/group" target="_blank" id="new_publish">新建</a>
                    </div>
                    <div class="commodity-class-list div_type">
                      <span>商品类型：</span>
                      <div style="display:inline-block;">
                        @if($commodity->type)
                          <img class="dot dot_type dot1" src="{{asset('shopstaff/images/dot.png')}}"><span class="dot_value span_words">实物</span>
                          <img class="dot dot_type" src="{{asset('shopstaff/images/dot1.png')}}"><span class="span_words">虚拟物品</span>
                          <input type="text" name="type" hidden />
                        @else
                          <img class="dot dot_type dot1" src="{{asset('shopstaff/images/dot1.png')}}"><span class="dot_value span_words">实物</span>
                          <img class="dot dot_type" src="{{asset('shopstaff/images/dot.png')}}"><span class="span_words">虚拟物品</span>
                          <input type="text" name="type" hidden />
                        @endif
                      </div>                     
                    </div>                    
                    <div class="commodity-class-list div_introduce">
                      <span>是否推荐：</span>
                      <div style="display:inline-block;">
                        @if($commodity->is_recommend)
                          <img class="dot dot_introduce dot2" src="{{asset('shopstaff/images/dot.png')}}"><span class="dot_value span_words">推荐</span>
                          <img class="dot dot_introduce" src="{{asset('shopstaff/images/dot1.png')}}"><span class="span_words">暂不推荐</span>
                          <input type="text" name="is_recommend" hidden/>
                        @else
                          <img class="dot dot_introduce dot2" src="{{asset('shopstaff/images/dot1.png')}}"><span class="dot_value span_words">推荐</span>
                          <img class="dot dot_introduce" src="{{asset('shopstaff/images/dot.png')}}"><span class="span_words">暂不推荐</span>
                          <input type="text" name="is_recommend" hidden/>
                        @endif
                      </div>                       
                    </div>
                    <div class="commodity-class-list div_place">
                      <span>一级产区：</span>
                      <input type="text" placeholder="国家" value="{{$commodity->produce_area1}}" class="class-commodity" name="produce_area1" id="produce_area1" />
                      <span>二级产区：</span>                  
                      <input type="text" placeholder="国家地区" value="{{$commodity->produce_area2}}" class="class-commodity" name="produce_area2" id="produce_area2" />
                    </div>
                    <div class="commodity-class-list div_image">
                        <span class="image_title">商品图：</span>
                        <div style="display:inline-block;" class="mainimage">
                            <span>主图</span>
                            <form  action='/Brand/commodity/uploadimg' id="rendering_form" enctype="multipart/form-data">
                                <input type="file" id="upload-img" name="main" hidden>
                                <input type="text" name="main_img" hidden value="{{$commodity->main_img}}">
                                <input type='text' name='old_main_img' hidden value="{{$commodity->main_img}}">
                            </form>
                            <img src="{{URL::asset($commodity->main_img)}}" id="add-img" />
                        </div>
                        <div style="display:inline-block;">
                            <span>其他展示图</span>                                              
                            <form  action='/Brand/commodity/uploadimg' id="show_form" enctype="multipart/form-data">
                                <input type="file" id="upload-images" name="images" hidden>
                                <input type="text" name="img" hidden value='{{$commodity->img_string}}'>
                                <input type="text" name="old_img" value='{{$commodity->img_string}}' hidden>
                                <input type="text" name="order" hidden>
                             </form>
                             <ul class="box-body" id="items">                             
                               @foreach($commodity->img as $img)
                                  <li><img src="{{URL::asset($img->img_src)}}" class="img_show"/></li>
                               @endforeach
                             </ul>
                             <img src="{{URL::asset('shopstaff/img/add.png')}}" id="add-images" />
                             <a id="images-reset" type="button">展示图重置</a>
                             <div class="tip">建议尺寸：640 x 640 像素；您可以拖拽图片调整图片顺序。</div>
                        </div>     
                    </div>
                </div>
                <div class="edit-title">规格价格</div>
                <div class="edit-commodity">
                    <div class="commodity-class-list info">
                        <span>规格选择：</span>
                        <div style="display:inline-block;" id="sku_info">
                          @if(!$commodity->sku_info)
                            <img class="dot dot4 dot_info" src="{{asset('shopstaff/images/dot.png')}}">
                            <span class="sku_info">统一规格</span>
                            <img class="dot dot4" src="{{asset('shopstaff/images/dot1.png')}}">
                            <span class="sku_info">多规格</span>
                            <input type="text" id="radio_multiple" name="sku_info" hidden>
                          @else
                            <img class="dot dot4 dot_info" src="{{asset('shopstaff/images/dot1.png')}}">
                            <span class="sku_info">统一规格</span>
                            <img class="dot dot4" src="{{asset('shopstaff/images/dot.png')}}">
                            <span class="sku_info">多规格</span>
                            <input type="text" id="radio_multiple" name="sku_info" hidden>
                          @endif
                        </div>
                        <button type="button" id="skuinfo_reset" class="btn btn-success">重置库存信息</button>
                    </div>
                    <div id="single" @if($commodity->sku_info) style="display: none;" @endif>
                        <div class="edit_sku">
                            <span>规格</span>
                            <select class="sku_lists" name="skuname" >
                                <option selected="selected"></option>
                            </select>
                            <span class="single_value">数值</span>
                            <input type="text" value="{{$skus[0]->skuvalue}}" name="skuvalue" class="sku_value">
                        </div>
                        <div>
                            <!-- <span class="sku_name">{{$skus[0]->skuname}}:</span>
                            <span class="sku_name_value">{{$skus[0]->skuvalue}}</span> -->
                        </div>
                        <div class="info">
                            <span>商品价格</span>
                            <input type="text" class="class-commodity commodity_value" placeholder="￥" name="price" id="price" @if(!$commodity->sku_info) value="{{$commodity->skulist[0]->price}}" @endif />
                            <span>原价</span>
                            <input type="text" class="class-commodity commodity_value" placeholder="￥" name="old_price" id="old_price" @if(!$commodity->sku_info) value="{{$commodity->skulist[0]->old_price}}" @endif />
                        </div>                                     
                    </div>                    
                    <div id="multiple" style="display: none;margin-top:30px" @if(!$commodity->sku_info) style="display: none;margin-top:30px;float:left;width:70%" @endif>
                        @if($commodity->sku_info)
                        @foreach($commodity->skulist as $index => $sku)
                          <div class="form-group skulist">
                              <span> {{$sku->commodity_sku}}》》》 </span>
                              <input type="text" class="quantity" name="skulist{{$index}}" value="{{$sku->commodity_sku}}"  hidden>
                              <label for="skuname">价格</label>
                              <input type="text" class="price" name="price{{$index}}" value="{{$sku->price}}"   placeholder="价格">                          
                          </div>
                          
                         @endforeach                          
                         @endif
                        <div class="form-group addsku" @if($commodity->sku_info) style="display:none;" @endif>
                            <span>规格</span>
                            <select class="sku_lists" value="">
                            </select>
                            <input type="text" name="sku_length" hidden value="{{$commodity->sku_length}}" >
                            <button id="addsku" type="button" class="btn_add">添加</button>
                        </div>
                    </div>
                </div>
                <div class="edit-title">其他</div>                
                <div class="edit-commodity">                
                    <div class="commodity-class-list buy_number">
                      <span>每人限购：</span>
                      <div style="display:inline-block;width:50%;">
                          <input type="text" name="limit_count" value='{{$commodity->limit_count}}' class="class-commodity limitbuy">
                          <span class="mention">0代表不限购</span>
                      </div>
                    </div>
                    <!-- <div class="commodity-class-list">
                      <span>会员折扣：</span>
                      <div style="display:inline-block;">
                        @if($commodity->has_vip_discount)
                          <img class="dot dot3" src="{{asset('shopstaff/images/dot.png')}}">
                          <span class="vip_value">参加会员折扣价</span>                     
                          <input type="text"  name="has_vip_discount" hidden class="vip_discount">
                        @else
                          <img class="dot dot3" src="{{asset('shopstaff/images/dot1.png')}}">
                          <span class="vip_value">参加会员折扣价</span>                     
                          <input type="text"  name="has_vip_discount" hidden class="vip_discount">
                        @endif
                      </div>                                              
                    </div> -->
                </div>
    
                <div class="next">
                  <button class="last-step allhover">上一步</button>
                  <button class="next-step allhover">下一步</button>
                </div>
                <div class="clearfix" style="clear:both;"></div>
        </div>
   
        <div class="clearfix" style="clear:both;"></div>

        <div class="edit-commodity-detail">
          <div class="edit-commodity-detail-container">            
              <div class="commodity-introduce">
                  <span class="introduce-title">商品简介：</span>
                  <textarea type="text" class="introduce-msg" name="brief_introduction" id="brief_introduction" >{{$commodity->brief_introduction}}</textarea>
              </div>
              <div class="commodity-detail-edit">
                  <span class="introduce-title">商品详情：</span>
                  <button class="custom">自定义</button>
                  <table>
                    <tr>
                        <th width="50px">选择</th>
                        <th width="100px">参数名称</th>
                        <th width="350px">描述(最多20个字符)</th>
                        <th width="150px">操作</th>
                    </tr>
                    @foreach($commodity->description as $key=>$value)
                      @foreach($value as $key1=>$value1)                        
                          @if($key1!='system')
                          <tr>
                              <td><input type='checkbox' class='checkbox' checked="checked"></td>                             
                              <td class='param_name'>{{$key1}}</td>
                              <td><input type='text' value="{{$value1}}" class='param_description'></td>                              
                              @if($value['system']==1)
                              <td>系统自带</td>
                              <td hidden class='system'>1</td>
                              @elseif($value['system']==0)
                              <td class='custom_delete'>删除</td>
                              <td hidden class='system'>0</td>
                              @endif
                          </tr>
                          @endif                        
                      @endforeach
                    @endforeach
                  </table>  
              </div>
          </div>
          <div  class="next">
             <button class="last-step1 allhover">上一步</button>
             <button class="on-shelf allhover">上架</button>
          </div>        
        </div>    
        <div class="custom_window" hidden>
          <span>参数名称</span><input class="custom_name" type="text" placeholder="最多10个汉字">
          <span>描述</span><textarea class="custom_description" type="text" placeholder="最多20个汉字"></textarea>
          <div>
            <button class="custom_cancle">取消</button>
            <button class="custom_confirm">确定</button>
          </div>
        </div>

    </div>


<script type="text/javascript" src="{{asset('shop/js/Sortable.js')}}"></script>
<script>
$(function(){
  var img=new Array();
  var order=new Array();
  var ordernum=0;
    $('#items li').each(function(){
      ordernum++;
      $(this).attr('class',ordernum);
    })

    $('.side-list').find('.in').removeClass('in');
    $('#commodity-manage').addClass('in');
    $('.side-list').find('.onsidebar').removeClass('onsidebar');
    $('.commoditymanage').addClass('onsidebar');
    $('.side-list').find('.onsidebarlist').removeClass('onsidebarlist');
    $('.maintenance').addClass('onsidebarlist');
//点击分类

    // $('.commodity-list').click(function(){
    //     $('.commodity-list').css('background-color','#f2f2f2');
    //     $('.commodity-list').css('color','#666');
    //     $(this).css('background-color','red');
    //     $(this).css('color','#fff');
    //     $('input[name="category_name"]').attr('value',$(this).html());
    //     $('.category_name').html($(this).html());
    //     $('input[name="category_id"]').attr('value',$(this).attr('category-id'));
    //     var category=$('input[name="category_id"]').attr('value');
    //     $('.sku_lists').empty();
    //     $.ajax({
    //         type: 'POST',
    //         url: '/Brand/commodity/param',
    //         data:{
    //             category:category
    //         },
    //         dataType: 'json',
    //         success: function(result){
    //           if(result.status=="success"){
    //               for(var i=0;i<result.msg.skuname.length;i++){
    //                 console.log('{{$skus[0]->skuname}}')
                  
    //                   var option="<option class='"+result.msg.skuname[i].id+"'>"+result.msg.skuname[i].skuname+"</option>";
                    
    //                 $('.sku_lists').append(option);
    //               }
    //               for(var i=0;i<result.msg.param.length;i++){
    //                 var tr=$("<tr>"+
    //                          "<td><input type='checkbox' class='checkbox'></td>"+
    //                          "<td class='param_name'>"+result.msg.param[i].name+"</td>"+
    //                          "<td><input type='text' class='param_description'></td>"+
    //                          "<td>系统自带</td>"+
    //                          "</tr>");
    //                 $('.commodity-detail-edit table').append(tr);
    //               }   
                
    //           }
              
    //         }
    //     });
    // });
        var category=$('input[name="category_id"]').attr('value');
        $('.category_name').html($('input[name="category_name"]').attr('value'));
        $('.sku_lists').empty();
        $.ajax({
            type: 'POST',
            url: '/Brand/commodity/param',
            data:{
                category:category
            },
            dataType: 'json',
            success: function(result){
              if(result.status=="success"){
                  for(var i=0;i<result.msg.skuname.length;i++){
                    if(result.msg.skuname[i].skuname == '{{$skus[0]->skuname}}'){
                      var option="<option selected='selected' class='"+result.msg.skuname[i].id+"'>"+result.msg.skuname[i].skuname+"</option>";
                    }else{
                      var option="<option class='"+result.msg.skuname[i].id+"'>"+result.msg.skuname[i].skuname+"</option>";
                    }
                    $('.sku_lists').append(option);
                  }  
                  var n=$('.param_name').length;                  
                  for(var i=0;i<result.msg.param.length;i++){
                    var m=0;
                    $('.param_name').each(function(){
                      m++;
                      if($(this).html()==result.msg.param[i].name){
                        return false;
                      } 
                      if(m==n){
                        var tr=$("<tr>"+
                             "<td><input type='checkbox' class='checkbox'></td>"+
                             "<td class='param_name'>"+result.msg.param[i].name+"</td>"+
                             "<td><input type='text' class='param_description'></td>"+
                             "<td>系统自带</td>"+
                             "<td hidden class='system'>1</td>"+
                             "</tr>");
                        $('.commodity-detail-edit table').append(tr);
                      }
                    });
                    
                  }                                   
              }
              
            }
        });
        
//自定义
    $('.custom').on('click',function(){
      cancel_index= layer.open({
                   type: 1,
                   title:false,
                   skin: 'layui-layer-demo', //样式类名
                   closeBtn: 0, //不显示关闭按钮
                   shift: 2,
                   shadeClose: true, //开启遮罩关闭
                   area : ["300px" , '280px'],
                   content:$('.custom_window'),
                });
    });
    $('.custom_cancle').on('click',function(){
      $(".custom_window").css("display","none");
      layer.close(cancel_index);
    });
    $('.custom_confirm').on('click',function(){
       var name=$('.custom_name').val();
       var description=$('.custom_description').val();
       // alert(name+" "+description);
       if(name!=''){
          var tr=$("<tr>"+
                   "<td><input type='checkbox' class='checkbox'></td>"+
                   "<td class='param_name'>"+name+"</td>"+
                   "<td><input type='text' value='"+description+"' class='param_description'></td>"+
                   "<td class='custom_delete'>删除</td>"+
                   "<td hidden class='system'>0</td>"+
                   "</tr>");
          $('.commodity-detail-edit table').append(tr);
       }else{
        alert("请输入参数名称！");
       }
       $(".custom_window").css("display","none");
       layer.close(cancel_index);
    });
    //删除自定义
    $('.commodity-detail-edit').on('click','.custom_delete',function(){
      $(this).parents('tr').remove();
    })
//点击上一步，下一步
    $("#choose-next").on("click",function(){
        $('.on').removeClass('on');
        $('.message').addClass('on');
        $(".choose-commodity").css("display","none");
        $(".commodity-msg-edit").css("display","block");
    });    
    $(".next-step").on("click",function(){
        $('.on').removeClass('on');
        $('.detail').addClass('on');
        $(".edit-commodity-detail").css("display","block");
        $(".commodity-msg-edit").css("display","none");
    }); 
    $(".last-step").on("click",function(){
        $('.on').removeClass('on');
        $('.select').addClass('on');
        $(".choose-commodity").css("display","block");
        $(".commodity-msg-edit").css("display","none");
    }); 
    $(".last-step1").on("click",function(){
        $('.on').removeClass('on');
        $('.message').addClass('on');
        $(".edit-commodity-detail").css("display","none");
        $(".commodity-msg-edit").css("display","block");
    });   
    $('#images-reset').click(function(){
        $('.img_show').remove();
        $('input[name="img"]').attr('value','');
    });
    $('#skuinfo_reset').click(function(){
        $('input[name="price"]').attr('value','');
        $('input[class="quantity"]').attr('value','');
        $('#multiple .skulist').remove();
        $('#multiple .addsku').css('display','block');
    });
//复选框是否被选中
    $('.dot_type').on('click',function(){
        if($(this).attr('src')=="{{asset('shopstaff/images/dot.png')}}"){
            $('.div_type .dot_type').attr('src',"{{asset('shopstaff/images/dot.png')}}");
            $(this).attr('src',"{{asset('shopstaff/images/dot1.png')}}");            
        }else{
            $('.div_type .dot_type').attr('src',"{{asset('shopstaff/images/dot1.png')}}");
            $(this).attr('src',"{{asset('shopstaff/images/dot.png')}}");
        }
        if($('.dot1').attr('src')=="{{asset('shopstaff/images/dot.png')}}"){
            $('.div_type input').val(1);
        }else{
            $('.div_type input').val(0);
        }
    });
    $('.dot_introduce').on('click',function(){
        if($(this).attr('src')=="{{asset('shopstaff/images/dot.png')}}"){
            $('.div_introduce .dot_introduce').attr('src',"{{asset('shopstaff/images/dot.png')}}");
            $(this).attr('src',"{{asset('shopstaff/images/dot1.png')}}");
        }else{
            $('.div_introduce .dot_introduce').attr('src',"{{asset('shopstaff/images/dot1.png')}}");
            $(this).attr('src',"{{asset('shopstaff/images/dot.png')}}");
        }
        if($('.dot2').attr('src')=="{{asset('shopstaff/images/dot.png')}}"){
            $('.div_introduce input').val(1);
        }else{
            $('.div_introduce input').val(0);
        }
    });
    $('.dot3').on('click',function(){
        if($(this).attr('src')=="{{asset('shopstaff/images/dot.png')}}"){
            $(this).attr('src',"{{asset('shopstaff/images/dot1.png')}}");
        }else{
            $(this).attr('src',"{{asset('shopstaff/images/dot.png')}}");
        }
        if($('.dot3').attr('src')=="{{asset('shopstaff/images/dot.png')}}"){
            $('.vip_discount').val(1);
        }else{
            $('.vip_discount').val(0);
        }
    });
    $('.dot4').on('click',function(){
        if($(this).attr('src')=="{{asset('shopstaff/images/dot.png')}}"){
            $('#sku_info .dot4').attr('src',"{{asset('shopstaff/images/dot.png')}}");
            $(this).attr('src',"{{asset('shopstaff/images/dot1.png')}}");
        }else{
            $('#sku_info .dot4').attr('src',"{{asset('shopstaff/images/dot1.png')}}");
            $(this).attr('src',"{{asset('shopstaff/images/dot.png')}}");
        }
        if($('.dot_info').attr('src')=="{{asset('shopstaff/images/dot.png')}}"){
            $('#sku_info input').val(0);
            $('#single').css('display','block');
            $('#multiple').css('display','none');
        }else{
            $('#sku_info input').val(1);
            $('#multiple').css('display','block');
            $('#single').css('display','none');
        }
    });
        if($('.dot_info').attr('src')=="{{asset('shopstaff/images/dot.png')}}"){
            $('#sku_info input').val(0);
            $('#single').css('display','block');
            $('#multiple').css('display','none');
        }else{
            $('#sku_info input').val(1);
            $('#multiple').css('display','block');
            $('#single').css('display','none');
        }
        if($('.dot3').attr('src')=="{{asset('shopstaff/images/dot.png')}}"){
            $('.vip_discount').val(1);
        }else{
            $('.vip_discount').val(0);
        }
        if($('.dot2').attr('src')=="{{asset('shopstaff/images/dot.png')}}"){
            $('.div_introduce input').val(1);
        }else{
            $('.div_introduce input').val(0);
        }
        if($('.dot1').attr('src')=="{{asset('shopstaff/images/dot.png')}}"){
            $('.div_type input').val(1);
        }else{
            $('.div_type input').val(0);
        }      
//重置展示图
    $('#images-reset').on('click',function(){
        $('#items li').remove();
        order=[];
        ordernum=0;

    });          
//数据绑定
    var value=$('.sku_value').val();
    $('.sku_name_value').html(value);
    var sku=$('#single .sku_lists option:selected').html();
    $('.sku_name').html(sku);
    $('.sku_value').on('change',function(){
      var value=$('.sku_value').val();
      $('.sku_name_value').html(value);
    });
    $('#single .sku_lists').change(function(){
      var sku=$('#single .sku_lists option:selected').html();
      $('.sku_name').html(sku);
    });            
                function commodityGroupInit(){
                      $.ajax({
                          type: 'POST',
                          url: '/Brand/commodity/groups' ,
                          success: function(data){
                              console.log(data);
                              var group_lists = data.group_lists;
                              var group_count = data.group_count;
                              for(var i=0;i<group_count;i++){
                                  var group = group_lists[i];
                                  if(group.id=='{{$commodity->group_id}}'){
                                    var element = '<option value='+group.id+' selected="selected">'+group.name+
                                                '</option>';
                                  }else{
                                    var element = '<option value='+group.id+'>'+group.name+
                                                '</option>';
                                  }
                                  

                                  $('#commodity_group').append(element);
                              }
                          } ,
                          dataType: 'json'
                      });
                }

                commodityGroupInit();
                $('#refresh_group').click(function(){
                    $('#commodity_group').empty();
                    commodityGroupInit();
                });
                
                $('#addsku').click(function(){
                    var skuname = $('#multiple .sku_lists option:selected').html();//获取skuname名称
                    $('#skuname').val('');//清空
                    // var element = '<div class="box box-success">'+
                    //         '<div class="box-header with-border">'+
                    //             '<h3 class="box-title skuname">'+skuname+'</h3>'+
                    //             '<div class="box-tools pull-right">'+
                    //                 '<input type="text"  class="scale_value" placeholder="商品规格值">'+
                    //                 '<button type="button" class="btn btn-info btn-sm addvalue">添加一条</button>'+
                    //                 '<button type="button" class="btn btn-warning btn-sm reset">重置</button>'+
                    //             '</div>'+
                    //         '</div>'+
                    //         '<div class="box_body">'+
                    //         '</div>'+
                    //         '</div>';
                    var element = '<div class="box box-success finally-1-css">'+
                    '<div class="box-header with-border finally-2-css">'+
                        '<span class="box-title skuname finally-4-css">'+skuname+'</span>'+
                        '<div class="box-tools pull-right-input finally-3-css">'+
                            '<input type="text"  class="scale_value" placeholder="商品规格值">'+
                            '<button type="button" class="btn btn-info btn-sm addvalue">添加一条</button>'+
                            
                        '</div>'+
                    '</div>'+
                    
                    '<div class="box_body">'+
                    '</div>'+
                    '<div class="block-list" style="display:none;">'+
                      '<span class="scale-list-name"></span>'+
                      '<span class="scale-list-price">价格(元)</span>'+
                    '</div>'+
                '</div>';
                    $('#multiple').append(element);
                    $('#addsku').attr('disabled','disabled');
                });
                var s=0;
                $('#multiple').on('click','.addvalue',function(){
                  var skuname = $('#multiple .sku_lists option:selected').html();
                  if($(this).parent('.box-tools').prev().html()!=skuname){
                    alert("规格不同，不能添加！");
                  }else{
                    s++;
                    var skuvalue = $(this).prev().val();
                    $(this).prev().val('');
                    // var element = '<label class="checkbox-inline">'+
                    //   '<input type="checkbox" class="select_value" value="'+skuvalue+'">'+skuvalue+
                    // '</label>';
                    // $(this).parents('.box-header').next().append(element);
                    var element = '<label class="checkbox-inline" style="display:none;">'+
                                    '<span class="skuvalue_number">'+s+'</span>'+
                                    '<input type="checkbox" class="select_value value'+s+'" value="'+skuvalue+'">'+skuvalue+
                                  '</label>';
                    $(this).parents('.box-header').next().append(element);
                    $('#multiple .value'+s+'').click();
                  }
                });

                // $('#multiple').on('click','.reset',function(){
                //     $(this).parents('.box-header').next().empty();
                // });

                $('#multiple').on('click','.select_value',function(){
                    var value_number = $(this).prev('.skuvalue_number').html();
                    var box_array = $('#multiple .box-success');
                    var box_count = box_array.length;
                    var sku_array = [];
                    var sku_names = [];
                    var result = [];

                    $('#multiple .skulist').remove();
                    for(var i=0;i<box_count;i++){
                        var skuvalue_array = [];
                        var box = $(box_array[i]);
                        var skuvalues = box.find('.select_value');
                        for(j=0;j<skuvalues.length;j++){

                            var skuvalue = $(skuvalues[j]);
                            if(skuvalue.is(':checked')){
                                skuvalue_array.push(skuvalue.attr('value'));
                            }                    
                        }
                        if(skuvalue_array.length){
                            sku_array.push(skuvalue_array);
                            sku_names.push(box.find('.skuname').html());
                        }
                    }

                    
                    if(sku_array.length == 1){
                        var sku_array0 = sku_array[0];
                        for(var k=0;k<sku_array0.length;k++){
                            var sku = {};
                            sku[sku_names[0]] = sku_array0[k];
                            result.push(sku);
                        }
                        
                    }
                    // else{
                    //     var sku_array0 = sku_array[0];
                    //     var sku_init = [];
                    //     for(var k=0;k<sku_array0.length;k++){
                    //         var sku = {};
                    //         sku[sku_names[0]] = sku_array0[k];                        
                    //         sku_init.push(sku);
                    //     }
                    //     for(var z=1;z<sku_array.length;z++){
                    //         sku_init = getsku(sku_init,sku_array[z],sku_names[z]);
                    //     }
                    //     result = sku_init;
                    // }
                    // function getsku(arr1,arr2,name){
                    //     var result = [];
                    //     for(var i=0;i<arr1.length;i++){
                    //         var value = arr1[i];
                    //         for(j=0;j<arr2.length;j++){
                    //             value[name] = arr2[j];
                    //             result.push(value);
                    //         }
                    //     }
                    //     return result;
                    // }
                    for(var m=0;m<result.length;m++){
                        result[m] = JSON.stringify(result[m]);
                       
                        var element = '<div class="form-group skulist">'+
                              '<span class="scale-Name scale-Name'+m+'">'+result[m]+' </span>'+
                              '<input type="text" class="quantity " name="skulist'+m+'" value=\''+result[m]+'\'  hidden>'+
                              // '<label for="skuname">价格</label>'+
                              '<span class="del_number" hidden>'+value_number+'</span>'+
                              '<span class="delete_sku delete_sku'+m+'" style="color:blue;cursor:pointer;float:right;">删除</span>'+
                              '<span class="index" hidden></span>'+
                              '<input type="text" class="price" name="price'+m+'"   placeholder="价格">'+
                          '</div>';
                        $('#multiple').append(element);
                        // var scale_name = $('.scale-Name'+m+'').html().replace(/[^0-9]/ig,"");
                        var scale_name = $('.scale-Name'+m+'').html().replace(/[~'!{}“”""<>@#$%^&*()-+_=:]/g, "");
                        var b = $('#multiple .sku_lists option:selected').html().length;
                        scale_name = scale_name.slice(b,scale_name.length);
                        var skuname = $('.skuname').html();
                        console.log(scale_name);
                        $('.scale-Name'+m+'').html(scale_name);
                        $('.scale-list-name').html(skuname);
                    }
                      var sku_length = result.length;
                      $('input[name="sku_length"]').attr('value',sku_length);
                      $('.block-list').css('display','block');
                      var index=0;
                      $('#multiple .delete_sku').each(function(){
                        index++;
                        $(this).next().html(index);
                        //console.log($(this).id);
                      }); 
                }); 

                $('#multiple').on('click','.delete_sku',function(){
                  var del=$(this).next().html();
                  var del_array=[];  
                  var del_length=$('#multiple .delete_sku').length;
                  $('#multiple .index').each(function(){
                      var i=$(this).html();
                      del_array.push(i);
                  });
                  for(var p=del;p<del_length;p++){
                      del_array[p]--;
                  }              
                  $(this).parent('.form-group').remove();
                  $('#multiple .value'+del+'').remove();
                  s--;
                  var q=0;
                  $('#multiple .select_value').each(function(){
                    q++;
                    $(this).attr("class","select_value");
                    $(this).addClass("value"+q);
                  }); 
                  del_array.splice(del-1,1);
                  var f=0;
                  $('#multiple .index').each(function(){
                      $(this).html(del_array[f]);
                      f++;
                  });
                });           

           $("#add-img").on("click",function(){
                $('#upload-img').click();             
           });
           $("#upload-img").on("change",function(){
               $("#rendering_form").ajaxSubmit({
                  type: "POST",
                  url: "/Brand/commodity/uploadimg",
                  data:{
                    type:'edit',
                  },
                  dataType:"json",
                    success: function(data) {
                      console.log(data);
                     var  img_src="/"+data.path;
                      $('#add-img').attr('src',img_src);
                      $('input[name="main_img"]').attr('value',data.path);
                            },
                     error: function(XMLHttpRequest, textStatus, errorThrown) {
                                alert("上传失败，请检查网络后重试");
                       }
                  });                
           });


             // 点击商品展示图上传按钮，上传图片
             $("#add-images").on("click",function(){
                  $('#upload-images').click(); 
             });


           $("#upload-images").on("change",function(){
               $("#show_form").ajaxSubmit({
                  type: "POST",
                  url: "/Brand/commodity/uploadimg",
                  data:{
                    type:'edit',
                  },
                  dataType:"json",
                    success: function(data) {
                      var oldValue = $('input[name="img"]').attr('value');
                      if(oldValue){
                        $('input[name="img"]').attr('value',oldValue+','+data.path);
                      }else{
                        $('input[name="img"]').attr('value',data.path);
                      }
                      img_src="/"+data.path;
                      img.push(data.path);
                      ordernum++;
                      var element = '<li class="'+ordernum+'"><img src='+img_src+' class="img_show" /></li>'
                      $('.box-body').append(element);

                    },
                     error: function(XMLHttpRequest, textStatus, errorThrown) {
                                alert("上传失败，请检查网络后重试");
                       }
                    });
                 
           });
           //拖拽
            var el = document.getElementById('items');
            new Sortable(el);//初始化
            new Sortable(el, {
                group: "name",
                store: null, // @see Store
                handle: ".my-handle", // 点击目标元素约束开始
                draggable: ".item",   // 指定那些选项需要排序
                ghostClass: "sortable-ghost",
             
                onStart: function (/**Event*/evt) { // 拖拽
                    var itemEl = evt.item;
                },
             
                onEnd: function (/**Event*/evt) { // 拖拽
                    var itemEl = evt.item;
                },
             
                onAdd: function (/**Event*/evt){
                    var itemEl = evt.item;
                },
             
                onUpdate: function (/**Event*/evt){
                    var itemEl = evt.item; // 当前拖拽的html元素
                },
             
                onRemove: function (/**Event*/evt){
                    var itemEl = evt.item;
                }
            });
            //保存与恢复排序
            new Sortable(el, {
                group: "localStorage-example",
                store: {

                    //Get the order of elements. Called once during initialization.

                    get: function (sortable) {
                        var order = localStorage.getItem(sortable.options.group);
                        return order ? order.split('|') : [];
                    },
             
                    // Save the order of elements. Called every time at the drag end.

                    set: function (sortable) {
                        var order = sortable.toArray();
                        localStorage.setItem(sortable.options.group, order.join('|'));
                        console.log(order);
                        alert();
                    }
                }
            });
          //点击上架 提交编辑数据
            $(".on-shelf").on("click",function(){
                 var commodity_name = $('input[name="commodity_name"]').val();
                 var category_name = $('input[name="category_name"]').attr('value');
                 var category_id = $('input[name="category_id"]').attr('value');
                 var is_recommend = $('input[name="is_recommend"]').val();
                 var type = $('input[name="type"]').val();
                 var produce_area1 = $('input[name="produce_area1"]').val();
                 var produce_area2 = $('input[name="produce_area2"]').val();
                 var skuinfo = $('input[name="sku_info"]').val();
                 var price = $('input[name="price"]').val();
                 var quantity = $('input[class="quantity"]').val();
                 var img = $('input[name="img"]').attr('value');
                 var main_img = $('input[name="main_img"]').attr('value');
                 var old_price = $('input[name="old_price"]').val();
                 var group_name = $('#commodity_group').find("option:selected").text();;
                 var group_id = $('#commodity_group').val(); 
                 var limit_count = $('input[name="limit_count"]').val();
                 var has_vip_discount = $('input[name="has_vip_discount"]').val();
                 var description = new Array();
                 var brief_introduction = $('textarea[name="brief_introduction"]').val();
                 var skuname = $('select[name="skuname"]').find("option:selected").text();
                 var skuvalue = $('input[name="skuvalue"]').val();
                 if($('input[name="old_img"]').attr('value') == $('input[name="img"]').attr('value')){
                    var img_changed = 0;
                 }else{
                  var img_changed = 1;
                 }
                 if($('input[name="old_main_img"]').attr('value') == $('input[name="main_img"]').attr('value')){
                    var main_img_changed = 0;
                 }else{
                  var main_img_changed = 1;
                 }
                 
                  $('#items li').each(function(){
                      var orderid=$(this).attr('class');
                      order.push(orderid);
                  }) ; 
                  $("input[name='order']").val(order); 
                  console.log(order);
                  $('.commodity-detail-edit .checkbox').each(function(){
                      if($(this).prop("checked")==true){
                        var m=$(this).parents('tr').children('.param_name').html();
                        var n=$(this).parents('tr').find('.param_description').val();
                        var s=$(this).parents('tr').find('.system').html();
                        var des={};
                        des[m]=n;
                        des['system']=s;
                        console.log(des);
                        // for(var key in des){
                        //   description[key]=des[key];
                        // } 
                        description.push(des); 
                      }
                  });
                   //var d=JSON.stringify(description);
                 var data = {
                     order:order,
                     type:type,
                     has_vip_discount:0,
                     commodity_name: commodity_name,
                     brief_introduction: brief_introduction,
                     category_id: category_id,
                     category_name: category_name,
                     description: description,
                     limit_count:limit_count,
                     group_id: group_id,
                     group_name: group_name,
                     old_price:old_price,
                     img: img,
                     is_recommend:is_recommend,
                     main_img:main_img,
                     price:price,
                     produce_area1:produce_area1,
                     produce_area2:produce_area2,
                     quantity:quantity,
                     skuname:skuname,
                     skuvalue:skuvalue,
                     sku_info:skuinfo,
                     img_changed:img_changed,
                     main_img_changed:main_img_changed,
                  };
                  if(skuinfo=="1"){
                    var sku_length = $('input[name="sku_length"]').attr('value');
                    data['sku_length'] = sku_length;
                    for(var i=0;i<sku_length;i++){
                      data['skulist'+i] = $('input[name="skulist'+i+'"]').attr('value');
                      data['price'+i] = $('input[name="price'+i+'"]').val();
                      //data['quantity'+i] = $('input[class="quantity'+i+'"]').val();
                    }
                  }
                  console.log(data);
                $.ajax({
                    type:'POST',
                    url:'/Brand/commodity/edit/{{$commodity->id}}',  
                    data: data,                      
                    dataType:"json",
                    success:function(result){
                        if(result.status=="success"){
                            alert(result.message);
                            window.location.href = '/Brand/commodity';
                        }else{
                            alert(result.message);
                        }
                    }               
                });

            });
});
</script>
       
@endsection