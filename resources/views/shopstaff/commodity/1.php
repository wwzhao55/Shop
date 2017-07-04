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
     <!-- 选择商品分类模块 -->
        <div class="choose-commodity">
             <div class="commodity-class">
              @foreach($category_lists as $list)
                <div class="commodity-list">{{$list->name}}</div>

              @endforeach
               <div class="commodity-list">鞋帽</div>
               <div class="commodity-list">上衣</div>
               <div class="commodity-list">家居</div>
              <div class="commodity-list">鞋帽</div>
               <div class="commodity-list">上衣</div>
               <div class="commodity-list">家居</div>
              </div> 
             <div class="next">
                <img src="{{URL::asset('shopstaff/img/btn-Next-step.png')}}" id="choose-next" />
             </div>
        </div>
   <!-- 编辑基本信息 -->
        <div class="commodity-msg-edit">
            <div class="msg-edit">
               <!--  基本信息 -->
                <div class="edit-title">基本信息</div>
                <div class="edit-commodity">
                    <div class="commodity-class-list">
                       <p>商品分类</p>
                       <input type="text" class="class-commodity" id="category_name" />
                    </div>
                   <div class="commodity-class-list">
                     <p>商品分组</p>
                       <input type="text" class="class-commodity" />
                   </div>
                   <div class="commodity-class-list">
                     <p>商品类型</p>
                       <input type="radio" name="commodity-class" /> 实物商品
                       <input type="radio" name="commodity-class" /> 虚拟商品
                   </div>
                    <div class="commodity-class-list">
                     <p>预售设置</p>
                       <input type="radio" name="class-commodity" /> 预售商品
                   </div>
                </div>
           <!--  库存规格 -->
                <div class="edit-title">库存规格</div>
                <div class="edit-commodity">
                    <div class="commodity-class-list">
                       <p>商品规格</p>
                       <input type="text" class="class-commodity" />
                    </div>
                   <div class="commodity-class-list">
                     <p>总库存</p>
                       <input type="text" class="class-commodity" />
                   </div>
                   <div class="commodity-class-list">
                     <p>商家编码</p>
                       <input type="radio" name="commodity-class" /> 实物商品
                       <input type="radio" name="commodity-class" /> 虚拟商品
                   </div>
                   
                </div>
         <!--  商品信息 -->
                 <div class="edit-title">商品信息</div>
                <div class="edit-commodity">
                    <div class="commodity-class-list">
                       <p>商品名</p>
                       <input type="text" class="class-commodity" id="commodity_name" />
                    </div>
                   <div class="commodity-class-list">
                     <p>价格</p>
                       <span class="money-mark">￥</span><input type="text" class="money-input" />
                       <input type="text" class="money-input"  placeholder="原价："/>
                   </div>
                   <div class="commodity-class-img">
                     <p>商品主图</p>
                       <form  action='/Shopstaff/commodity/uploadimg' id="rendering_form" enctype="multipart/form-data">
                         <input type="file" id="upload-img" name="main"/>
                       </form>
                       <div class="commodity-show"></div>
                       <img src="{{URL::asset('shopstaff/img/add.png')}}" id="add-img" class="img-show"/>
                   </div>

                  <div class="commodity-class-images">
                     <p>商品展示图</p>
                       <form  action='/Shopstaff/commodity/uploadimg' id="show_form" enctype="multipart/form-data">
                         <input type="file" id="upload-images" name="images"/>
                       </form>
                       <div class="commodity-show-image"></div>
                       <img src="{{URL::asset('shopstaff/img/add.png')}}" id="add-image" class="img-show"/>
                        <img src="{{URL::asset('shopstaff/img/icon-delete.png')}}" id="dele-image" class="img-show"/>
                   </div>
                    
                </div>
              
            <!--   物流其他 -->
                <div class="edit-title">物流其他</div>
                <div class="edit-commodity">
                    <div class="commodity-class-list">
                       <p>运费</p>
                       <input type="radio" name="use_express_template" value="1" checked /> 统一运费<input type="text" class="class-commodity" placeholder="￥：" id="express_price" /><br/><br/>
                       <input type="radio" name="use_express_template" value="0" /> 运费模板<input type="text" class="class-commodity" id="express_price" />
                    </div>
                   <div class="commodity-class-list">
                     <p>开售时间</p>
                       <input type="radio" name="start-time"/> 立即开售<br/><br/>
                       <input type="radio" name="start-time"/> 定时开售<input type="text" class="class-commodity" />
                   </div>
                   <div class="commodity-class-list">
                     <p>会员折扣</p>
                       <input type="checkbox" name="commodity-class" /> 参加会员折扣价
                   </div>
                </div>
    
               <div class="next">
                <img src="{{URL::asset('shopstaff/img/btn-Last-step-hover.png')}}" class="last-step"/>
                <img src="{{URL::asset('shopstaff/img/btn-Next-step-hover.png')}}" class="next-step"/>
             </div>
            </div>
        </div>
   
      <!--  编辑商品详情 -->
      <div class="edit-commodity-detail">
          <div class="edit-commodity-detail-container">

              <div class="commodity-mode">
                  <span >商品页模板</span>
                  <select class="choose-mode">
                     <option>普通版</option>
                      <option>简洁流畅版</option>
                  </select>
              </div>

              <div class="commodity-introduce">
                  <span class="introduce-title">商品简介(选填，微信分享给好友会显示这里的文章)</span>
                  <textarea type="text" class="introduce-msg" id="brief_introduction" ></textarea>
              </div>


               <div class="commodity-detail-edit">
                  <span class="introduce-title">编辑商品详情</span>
                  <textarea class="introduce-msg-detail"  id="description"></textarea>   
              </div>

          </div>
          <div  class="next">
             <img src="{{URL::asset('shopstaff/img/btn-Last-step.png')}}" class="last-step-edit"/>
             <img src="{{URL::asset('shopstaff/img/btn-The-shelves.png')}}" class="on-shelf"/>
          </div>

        
      </div>


      </div>
      <script>
          $(".commodity-status").on("click",function(){
             $(".commodity-status").css("background-color","#eee");
              if($(this).html()=="选择商品分类")  {
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
             if($(this).attr("src")=="http://localhost:8000/shopstaff/img/add.png") {
                $('#upload-img').click();
             }
             else{
                  $("#main-img").remove();
                  $(this).attr("src","http://localhost:8000/shopstaff/img/add.png")
             }
           });
           $("#upload-img").on("change",function(){
               $("#rendering_form").ajaxSubmit({
                  type: "POST",
                  url: "/Shopstaff/commodity/uploadimg",
                  data:{
                    type:'add',
                  },
                  dataType:"json",
                        success: function(data) {
                         var  img_src="http://localhost:8000/"+data.path;
                         main_img=data.path;
                          var commodity_img=$("<img src="+img_src+" class='img-show' id='main-img'>");
                          $(".commodity-show").append(commodity_img);
                         var   del_src="{{URL::asset('shopstaff/img/icon-delete.png')}}";
                          $("#add-img").attr("src",del_src)
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
             $("#add-image").on("click",function(){
                $('#upload-images').click(); 
           });
             var img=new Array();
           $("#upload-images").on("change",function(){
               $("#show_form").ajaxSubmit({
                  type: "POST",
                  url: "/Shopstaff/commodity/uploadimg",
                  data:{
                    type:'add',
                  },
                  dataType:"json",
                        success: function(data) {
                          img_src="http://localhost:8000/"+data.path;
                          img.push(data.path);
                          var commodity_img=$("<img src="+img_src+" class='img-show images-length'>");
                          $(".commodity-show-image").append(commodity_img);
                          //    var   del_src="{{URL::asset('shopstaff/img/icon-delete.png')}}";
                          //  $("#add-image").attr("src",del_src);
                          // var dele_img=$("<img src=")
                          // $("#add-image").appendAfter();
                                },
                         error: function(XMLHttpRequest, textStatus, errorThrown) {
                                    alert("上传失败，请检查网络后重试");
                           }
                    });
                 
           });
          //点击上架 提交编辑数据
           $(".on-shelf").on("click",function(){
                  //商品名  
                 var commodity_name=$("#commodity_name").val();
                 //商品简介
                 var brief_introduction=$("#brief_introduction").val();
                 //商品类别id
                 var category_id=$("#category_id").val();
                 //商品名
                 var category_name=$("#category_name").val();
                 //商品描述
                 var description=$("#description").val();
                 // img    image  已经有了
                 //
                $.ajax({
                type:'POST',
                url:'/Shopstaff/commodity/add',  
                data:{
                   commodity_name: commodity_name,
                   brief_introduction: brief_introduction,
                   category_id: category_id,
                   category_name: category_name,
                   description: description,
                   express_price: express_price,
                   express_template_id:express_template_id,
                   img: img,
                   is_recommond:is_recommond,
                   main_img:main_img,
                   price:price,
                   produce_area1:produce_area1,
                   produce_area2:produce_area2,
                   quantity:quantity,
                   skuinfo:skuinfo,
                   use_express_template
                } ,                       
                dataType:"json",
                success:function(result){
                    if(result.status=="success"){
                        alert("删除成功！");
                    }else{
                        alert("删除失败！");
                    }
                }               
                });

           })

      </script>
@endsection