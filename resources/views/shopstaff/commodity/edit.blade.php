@extends('layouts.app')
@section('siderbar')
@include('layouts.siderbar')
@endsection
@section('content')
    <link rel="stylesheet" type="text/css" href="{{URL::asset('shop/css/add-commodity.css')}}">
     <div class="container-commodity">
       <div class="commodity-edit">
            <span class="commodity-word"><span class="commodity-ban">商品库</span>/编辑商品</span>
            <span class="commodity-return"><img src="{{URL::asset('shopstaff/img/btn-return.png')}}"/></span>
        </div> 
        <div class="commodity-status">选择商品分类</div>
        <div class="commodity-status">编辑基本信息</div>
        <div class="commodity-status">编辑商品详情</div>
    
        <div class="choose-commodity">
             <div class="commodity-class">
              @foreach($category_lists as $list)
                <div class="commodity-list" category-id='{{$list->id}}' @if($commodity->category_id==$list->id)style='background-color:red' @endif>{{$list->name}}</div>
                <input type="text" name="category_name" id="category_name" value="{{$commodity->category_name}}" hidden>
                <input type="text" name="category_id" id="catetory_id" value="{{$commodity->category_id}}" hidden>
              @endforeach
              </div> 
             <div class="next">
                <img src="{{URL::asset('shopstaff/img/btn-Next-step.png')}}" id="choose-next" />
             </div>
        </div>
  <script type="text/javascript">
    $('.commodity-list').click(function(){
      $('.commodity-list').css('background-color','#f2f2f2');
      $(this).css('background-color','red');
      $('input[name="category_name"]').attr('value',$(this).html());
      $('input[name="category_id"]').attr('value',$(this).attr('category-id'));
    })
  </script>
        <div class="commodity-msg-edit">
            <div class="msg-edit">
                <div class="edit-title">基本信息</div>
                <div class="edit-commodity">
                    <div class="commodity-class-list">
                       <p>商品名</p>
                       <input type="text" class="class-commodity" name="commodity_name" value="{{$commodity->commodity_name}}" id="commodity_name" />
                    </div>
                   <div class="commodity-class-list">
                     <p>商品类型</p>
                     <label>
                        <input type="radio" name="type" value="1" @if($commodity->type) checked @endif/> 实物商品
                      </label>
                       <label>
                         <input type="radio" name="type" value="0" @if(!($commodity->type)) checked @endif/> 虚拟商品
                       </label>
                       
                   </div>
                   <div class="commodity-class-list">
                     <p>是否推荐</p>
                      <label>
                        <input type="radio" name="is_recommend" value="1" @if($commodity->is_recommend) checked @endif/> 推荐商品
                      </label>
                       <label>
                         <input type="radio" name="is_recommend" value="0" @if(!$commodity->is_recommend) checked @endif/> 暂不推荐
                       </label>
                       
                   </div>
                   <div class="commodity-class-list">
                       <p>商品一级产地</p>
                       <input type="text" class="class-commodity" value="{{$commodity->produce_area1}}" name="produce_area1" id="produce_area1" />
                    </div>
                    <div class="commodity-class-list">
                       <p>商品二级产地</p>
                       <input type="text" class="class-commodity" value="{{$commodity->produce_area2}}" name="produce_area2" id="produce_area2" />
                    </div>
                </div>
  
                <div class="edit-title" style="height:600px">库存规格</div>
                <div class="edit-commodity" style="min-height:600px">
                    <div class="commodity-class-list">
                     <p>规格选择</p>
                      <label>
                        <input type="radio" id="radio_single" name="sku_info" value="0" @if(!$commodity->sku_info) checked @endif>
                        统一规格
                      </label>
                      <label>
                        <input type="radio" id="radio_multiple" name="sku_info"  value="1" @if($commodity->sku_info) checked @endif>
                        多规格
                      </label>
                       <button type="button" style="margin-top:50px;margin-bottom:400px" id="skuinfo_reset" class="btn btn-success">重置库存信息</button>
                    </div>
                    <script>
                      $('#skuinfo_reset').click(function(){
                          $('input[name="price"]').attr('value','');
                          $('input[name="quantity"]').attr('value','');
                          $('#multiple .skulist').remove();
                          $('#multiple .addsku').css('display','block');
                      })
                    </script>
                    <div id="single" @if($commodity->sku_info) style="display: none;" @endif>
                        <div class="commodity-class-list">
                           <p>商品价格</p>
                           <input type="text" class="class-commodity" name="price" id="price" @if(!$commodity->sku_info) value="{{$commodity->skulist[0]->price}}" @endif />
                        </div>
                        <div class="commodity-class-list">
                           <p>商品库存</p>
                           <input type="text" class="class-commodity" name="quantity" id="quantity" @if(!$commodity->sku_info) value="{{$commodity->skulist[0]->quantity}}" @endif/>
                        </div>                 
                    </div>
                    <div id="multiple" @if(!$commodity->sku_info) style="display: none;margin-top:30px;float:left;width:70%" @endif>

                        @if($commodity->sku_info)
                        @foreach($commodity->skulist as $index => $sku)
                          <div class="form-group skulist">
                              <span> {{$sku->commodity_sku}}》》》 </span>
                              <input type="text" class="quantity" name="skulist{{$index}}" value="{{$sku->commodity_sku}}"  hidden>
                              <label for="skuname">价格</label>
                              <input type="text" class="price" name="price{{$index}}" value="{{$sku->price}}"   placeholder="价格">
                              <label for="skuname">库存</label>
                              <input type="text" class="quantity" name="quantity{{$index}}" value="{{$sku->quantity}}" placeholder="库存">                            
                          </div>
                          
                         @endforeach                          
                         @endif
                        <div class="form-group addsku" @if($commodity->sku_info) style="display:none;" @endif>
                            <label for="skuname">商品规格名称</label>
                            <input type="text"  id="skuname" placeholder="商品规格名称">
                            <input type="text" name="sku_length" hidden value="{{$commodity->sku_length}}">
                            <button id="addsku" type="button" class="btn btn-info btn-sm">添加一条</button>
                        </div>
                    </div>                   
                </div>
     
                 <div class="edit-title">商品图片</div>
                <div class="edit-commodity">
                    
                   <div class="commodity-class-img">
                    <p>商品主图</p>
                       <form  action='/Shopstaff/commodity/uploadimg' id="rendering_form" enctype="multipart/form-data">
                         <input type="file" id="upload-img" name="main"/>
                         <input type="text" name="main_img" hidden value="{{$commodity->main_img}}">
                         <input type='text' name='old_main_img' hidden value="{{$commodity->main_img}}">
                       </form>
                       <img src="{{URL::asset($commodity->main_img)}}" id="add-img" />
                   </div>

                  <div class="commodity-class-images">
                    <p>商品展示图</p>
                       <form  action='/Shopstaff/commodity/uploadimg' id="show_form" enctype="multipart/form-data">
                         <input type="file" id="upload-images" name="images"/>
                         <input type="text" name="img" value='{{$commodity->img_string}}' hidden>
                         <input type="text" name="old_img" value='{{$commodity->img_string}}' hidden>
                       </form>
                       @foreach($commodity->img as $img)
                          <img src="{{URL::asset($img->img_src)}}" class="img-show"/>
                       @endforeach
                       <img src="{{URL::asset('shopstaff/img/add.png')}}" id="add-images" />
                       <a id="images-reset" type="button">展示图重置</a>
                   </div>
                    
                </div>        
                <div class="edit-title">物流其他</div>
                <div class="edit-commodity">
                    <div class="commodity-class-list">
                       <p>运费</p>
                       <label>
                         <input type="radio" name="use_express_template" value="0" @if(!$commodity->use_express_template) checked @endif /> 统一运费<input type="text" class="class-commodity" value="{{$commodity->express_price}}" placeholder="￥：" name="express_price" />
                       </label>
                       <label>
                          <input type="radio" name="use_express_template" value="1" @if($commodity->use_express_template) checked @endif/>运费模板
                         <select name='express_template_id'>
                           <option value="0" >请选择</option>
                           @foreach($express_template_lists as $list)
                            <option value="{{$list->id}}" @if($commodity->express_template_id == $list->id) checked @endif >{{$list->name}}</option>
                           @endforeach 
                         </select>
                       </label>
                       
                    </div>
                   <div class="commodity-class-list" style="margin-bottom:30px">
                      <p>会员折扣</p>
                      <label>
                      
                        <input type="radio"  name="has_vip_discount" value="1" @if($commodity->has_vip_discount) checked @endif>
                        参与会员折扣
                      </label>
                      <label>
                      
                        <input type="radio"  name="has_vip_discount"  value="0" @if(!$commodity->has_vip_discount) checked @endif>
                        不参与会员折扣
                      </label>
                       
                    </div>
                </div>
    
               <div class="next">
                <img src="{{URL::asset('shopstaff/img/btn-Last-step-hover.png')}}" class="last-step"/>
                <img src="{{URL::asset('shopstaff/img/btn-Next-step-hover.png')}}" class="next-step"/>
             </div>
            </div>
        </div>
   
   
      <div class="edit-commodity-detail">
          <div class="edit-commodity-detail-container">

             

              <div class="commodity-introduce">
                  <span class="introduce-title">商品简介(选填，微信分享给好友会显示这里的文章)</span>
                  <textarea type="text" class="introduce-msg" name="brief_introduction" id="brief_introduction" >{{$commodity->brief_introduction}}</textarea>
              </div>


               <div class="commodity-detail-edit">
                  <span class="introduce-title">编辑商品详情</span>
                  <textarea class="introduce-msg-detail"  id="description" name="description">{{$commodity->description}}</textarea>   
              </div>

          </div>
          <div  class="next">
             <img src="{{URL::asset('shopstaff/img/btn-Last-step.png')}}" class="last-step-edit"/>
             <img src="{{URL::asset('shopstaff/img/btn-The-shelves.png')}}" class="on-shelf"/>
          </div>

        
      </div>


      </div>
      <script>

          $('#images-reset').click(function(){
              $('.img-show').remove();
              $('input[name="img"]').attr('value','');
          });
          $(".commodity-status").on("click",function(){
             $(".commodity-status").css("background-color","#eee");
              if($(this).html()=="选择商品分类"){
                  $(".choose-commodity").css("display","block");
                  $(".commodity-msg-edit").css("display","none");
                  $(".edit-commodity-detail").css("display","none");
              }
             if($(this).html()=="编辑基本信息")  {
                  $(".choose-commodity").css("display","none");
                  $(".commodity-msg-edit").css("display","block");
                  $(".edit-commodity-detail").css("display","none");
              }
              if($(this).html()=="编辑商品详情")  {
                  $(".choose-commodity").css("display","none");
                  $(".commodity-msg-edit").css("display","none");
                  $(".edit-commodity-detail").css("display","block");
              }
              $(this).css("background-color","#fff");
          });

           $("#choose-next").on("click",function(){
                  $(".choose-commodity").css("display","none");
                  $(".commodity-msg-edit").css("display","block");
          });
            $(".last-step").on("click",function(){
                  $(".choose-commodity").css("display","block");
                  $(".commodity-msg-edit").css("display","none");
          });
          $(".next-step").on("click",function(){
                  $(".edit-commodity-detail").css("display","block");
                  $(".commodity-msg-edit").css("display","none");
          });
          $(".last-step-edit").on("click",function(){
                  $(".edit-commodity-detail").css("display","none");
                  $(".commodity-msg-edit").css("display","block");
          });

           $("#add-img").on("click",function(){
                $('#upload-img').click();             
           });
           $("#upload-img").on("change",function(){
               $("#rendering_form").ajaxSubmit({
                  type: "POST",
                  url: "/Shopstaff/commodity/uploadimg",
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

            //点击商品展示图删除按钮，删除图片
            $("#dele-image").on("click",function(){
             var images_commodity=$(".images-length");
             // alert(images_commodity.length);
             $(".images-length").eq(images_commodity.length-1).remove();
            })

             // 点击商品展示图上传按钮，上传图片
             $("#add-images").on("click",function(){
                  $('#upload-images').click(); 
             });

            var img=new Array();
           $("#upload-images").on("change",function(){
               $("#show_form").ajaxSubmit({
                  type: "POST",
                  url: "/Shopstaff/commodity/uploadimg",
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
                      var element = '<img src='+img_src+' class="img-show" />'
                      $('.commodity-class-images').append(element);

                    },
                     error: function(XMLHttpRequest, textStatus, errorThrown) {
                                alert("上传失败，请检查网络后重试");
                       }
                    });
                 
           });
          //点击上架 提交编辑数据
           $(".on-shelf").on("click",function(){
                 var commodity_name = $('input[name="commodity_name"]').val();
                 var category_name = $('input[name="category_name"]').attr('value');
                 var category_id = $('input[name="category_id"]').attr('value');
                 var is_recommend = $('input[name="is_recommend"]:checked').val();
                 var type = $('input[name="type"]:checked').val();
                 var produce_area1 = $('input[name="produce_area1"]').val();
                 var produce_area2 = $('input[name="produce_area2"]').val();
                 var skuinfo = $('input[name="sku_info"]:checked').attr('value');
                 var price = $('input[name="price"]').val();
                 var quantity = $('input[name="quantity"]').val();
                 var img = $('input[name="img"]').attr('value');
                 var main_img = $('input[name="main_img"]').attr('value');
                 var use_express_template = $('input[name="use_express_template"]:checked').val();
                 var express_template_id = $('select[name="express_template_id"]').val();
                 var express_price = $('input[name="express_price"]').val();
                 var has_vip_discount = $('input[name="has_vip_discount"]:checked').val();
                 var description = $('textarea[name="description"]').val();
                 var brief_introduction = $('textarea[name="brief_introduction"]').val();
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
                 var data = {
                     type:type,
                     has_vip_discount:has_vip_discount,
                     commodity_name: commodity_name,
                     brief_introduction: brief_introduction,
                     category_id: category_id,
                     category_name: category_name,
                     description: description,
                     express_price: express_price,
                     express_template_id:express_template_id,
                     img: img,
                     is_recommend:is_recommend,
                     main_img:main_img,
                     price:price,
                     produce_area1:produce_area1,
                     produce_area2:produce_area2,
                     quantity:quantity,
                     sku_info:skuinfo,
                     use_express_template:use_express_template,
                     img_changed:img_changed,
                     main_img_changed:main_img_changed,
                  };
                  if(skuinfo=="1"){
                    var sku_length = $('input[name="sku_length"]').attr('value');
                    data['sku_length'] = sku_length;
                    for(var i=0;i<sku_length;i++){
                      data['skulist'+i] = $('input[name="skulist'+i+'"]').attr('value');
                      data['price'+i] = $('input[name="price'+i+'"]').val();
                      data['quantity'+i] = $('input[name="quantity'+i+'"]').val();
                    }
                  }
                  console.log(data);
                $.ajax({
                type:'POST',
                url:'/Shopstaff/commodity/edit/{{$commodity->id}}',  
                data: data,                      
                dataType:"json",
                success:function(result){
                    if(result.status=="success"){
                        alert(result.message);
                    }else{
                        alert(result.message);
                    }
                }               
                });

           })
      </script>
 
    
    <link rel="stylesheet" type="text/css" href="{{URL::asset('admin/css/AdminLTE.min.css')}}">
    <script>
        $(function(){
            $('#addsku').click(function(){
                var skuname = $('#skuname').val();//获取skuname名称
                $('#skuname').val('');//清空
                var element = '<div class="box box-success">'+
                    '<div class="box-header with-border">'+
                        '<h3 class="box-title skuname">'+skuname+'</h3>'+
                        '<div class="box-tools pull-right">'+
                            '<input type="text"  placeholder="商品规格值">'+
                            '<button type="button" class="btn btn-info btn-sm addvalue">添加一条</button>'+
                            '<button type="button" class="btn btn-warning btn-sm reset">重置</button>'+
                        '</div>'+
                    '</div>'+
                    '<div class="box-body">'+
                    '</div>'+
                '</div>';
                $('#multiple').append(element);
            });
            $('#radio_use_template').click(function(){
                $('#use_template').css('display','block');
                $('#no_use_template').css('display','none');
            })
            $('#radio_no_use_template').click(function(){
                $('#no_use_template').css('display','block');
                $('#use_template').css('display','none');
            })
            $('#radio_multiple').click(function(){
                $('#multiple').css('display','block');
                $('#single').css('display','none');
            })
            $('#radio_single').click(function(){
                $('#single').css('display','block');
                $('#multiple').css('display','none');
            });
            $('#multiple').on('click','.addvalue',function(){
                var skuvalue = $(this).prev().val();
                $(this).prev().val('');
                var element = '<label class="checkbox-inline">'+
                  '<input type="checkbox" class="select_value" value="'+skuvalue+'">'+skuvalue+
                '</label>';
                $(this).parents('.box-header').next().append(element);
            });

            $('#multiple').on('click','.reset',function(){
                $(this).parents('.box-header').next().empty();
            });

            $('#multiple').on('click','.select_value',function(){
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
                    
                }else{
                    var sku_array0 = sku_array[0];
                    var sku_init = [];
                    for(var k=0;k<sku_array0.length;k++){
                        var sku = {};
                        sku[sku_names[0]] = sku_array0[k];                        
                        sku_init.push(sku);
                    }
                    for(var z=1;z<sku_array.length;z++){
                        sku_init = getsku(sku_init,sku_array[z],sku_names[z]);
                    }
                    result = sku_init;
                }
                function getsku(arr1,arr2,name){
                    var result = [];
                    for(var i=0;i<arr1.length;i++){
                        var value = arr1[i];
                        for(j=0;j<arr2.length;j++){
                            value[name] = arr2[j];
                            result.push(value);
                        }
                    }
                    return result;
                }
                for(var m=0;m<result.length;m++){
                    result[m] = JSON.stringify(result[m]);
                    var element = '<div class="form-group skulist">'+
                        '<span>'+result[m]+' 》》》 </span>'+
                        '<input type="text" class="quantity" name="skulist'+m+'" value=\''+result[m]+'\'  hidden>'+
                        '<label for="skuname">价格</label>'+
                        '<input type="text" class="price" name="price'+m+'"   placeholder="价格">'+
                        '<label for="skuname">库存</label>'+
                        '<input type="text" class="quantity" name="quantity'+m+'"  placeholder="库存">'+
                    '</div>';
                    $('#multiple').append(element);
                }
                var sku_length = result.length;
                $('input[name="sku_length"]').attr('value',sku_length);
                
            });
        })
    </script>
@endsection