@extends('layouts.app')
@section('siderbar')
@include('layouts.siderbar')
@endsection

@section('addCss')
<link rel="stylesheet" href="{{URL::asset('admin/css/startImg.css')}}">
@endsection

@section('content')
    <div class="container-fluid startImg">
    	<div class="row" >
    		<div class="col-md-6 col-xs-6 col-sm-6 col-lg-6 autoImageArea"  >
    			<span class="areaTitle">默认启动图片</span>
    			<div class="autoImageArea_image" >
    				<!-- <img src="DG系统超级管理员效果图+标注图+切图/3.DG系统超级管理员切图/autoimage.png" />    
    				-->
                    <!-- <img src="{{$startlogo->default_src}}" />   -->
                    <img src="{{URL::asset($startlogo->default_src)}}" />  
    				<!-- <img src="{{URL::asset('admin/img/app_img/2.jpeg')}}" />   -->  
    			</div>
    			<div class="autoImageArea_image_action">
    				<div type="button" class="btnStyle_del" style="display:none;"></div>
    				<!-- <div type="button" class="btnStyle_sta"></div> -->
                    <form action="">
                        <div type="button" id="btnStyle_up_autoImage" class="btnStyle_up"></div>
                        <input type="file" id="btn_autoImage"  style="display:none;" name="default_src"/>
                    </form>
    			</div>
    		</div>
    		<div class="col-md-6 col-xs-6 col-sm-6 col-lg-6 currentImageArea" >
    			<span class="areaTitle">当前启动图片</span>
    			<div class="currentImageArea_image" >
    				<!-- <img src="{{URL::asset('admin/img/app_img/4.jpeg')}}" />     -->
                    <!-- <img src="{{$startlogo->logo_src}}" />   -->
                    <img src="{{URL::asset($startlogo->logo_src)}}" /> 
    			</div>
    			<div class="autoImageArea_image_action">
    				<div type="button" class="btnStyle_del"></div>
    				<!-- <div type="button" class="btnStyle_sta btnStyle_active"></div> -->
    				<!-- <div type="button" class="btnStyle_up"></div> -->
                    <form action="">
                        <div type="button" id="btnStyle_up_currentImage" class="btnStyle_up"></div>
                        <input type="file" id="btn_currentImage"  style="display:none;" name="logo_src"/>
                    </form>
    			</div>
    		</div>
    	</div>
    	<!-- <div class="btnArea">
    		<a href="/Admin/datacenter"><button type="button" class="btn btn-danger btnStyle_cancle">取消</button></a>
    		<button type="button" class="btn btn-danger btnStyle_sure">确定</button>
    	</div> -->
        <!-- {{$startlogo}} -->
      <!--   @foreach ( $startlogo as $list )
        <div >{{$list}}</div>
        @endforeach -->
        <!-- {{$startlogo_material}} -->
    </div>

    <div class="confrimDel">
       <div><span>你确定要删除所选广告图片吗？</span></div>
       <hr class="line" />
       <div>
           <img src="{{URL::asset('admin/img/app_img/btn-cancel.png')}}" id="cancel">       
           <a href="/Admin/App/startlogo/delete"><img src="{{URL::asset('admin/img/app_img/btn-determine-s.png')}}" id="confirm"></a></div>
   </div>

    <script type="text/javascript">
    var cancel_index;
        // for (var i = {{$startlogo}}.length - 1; i >= 0; i--) {
        //     alert({{$startlogo}});
        // }
        
        // console.log({{$startlogo}});

        $(".btnStyle_sta").on("click",function(){
            // _this=$(this);
            if($(this).hasClass("btnStyle_active")){

            }else{
                $(".btnStyle_sta").removeClass("btnStyle_active");
                $(this).addClass("btnStyle_active");
            }
        });

        $(".btnStyle_del").on("click",function(){
            _this=$(this);
                //页面层-自定义
                // layer.open({
                //   type: 1,
                //   title: false,
                //   closeBtn: 0,
                //   shadeClose: true,
                //   skin: 'yourclass',
                //   shade: 0.5,
                //   area : ['800px' , '622px'],
                //   content:$('#adduser_window2Modify'),           
                // });
                is_src_empty = _this.parent("div").parent("div").find("img").attr("src");
                if(is_src_empty=="http://localhost:8000/"){
                    
                }else{
                    cancel_index=layer.open({
                   type: 1,
                   title:false,
                   skin: 'layui-layer-demo', //样式类名
                   closeBtn: 0, //不显示关闭按钮
                   shift: 2,
                   shadeClose: true, //开启遮罩关闭
                   area : ["41.66%" , '300px'],
                   content:$('.confrimDel'),
                    }); 
                }
               
        });
        //点击取消按钮，选中按钮，弹窗消失
            $("#cancel").on("click",function(){
               layer.close(cancel_index);
            });
        //点击确定按钮，取消选中的按钮，弹窗消失
            // $("#confirm").on("click",function(){
            //     layer.close(cancel_index);
            //     _this.parent("div").parent("div").find("img").attr("src","");

            //     $.ajax({
            //     type:'POST',
            //     url:'/Admin/App/startlogo/delete',                          
            //     dataType:"json",
            //     success:function(result){
            //         if(result.status=="success"){
            //             alert("删除成功！");
            //         }else{
            //             alert("删除失败！");
            //         }
            //     }               
            //     });
            // });

            // 上传开始
            // 启动图片异步上传
                $("#btnStyle_up_autoImage").on("click",function(){
                    $("#btn_autoImage").click();
                });
                $("#btn_autoImage").bind("change", function(){
                        // alert($("#btn_autoImage").val());
                        $("#btn_autoImage").parent("form").ajaxSubmit({
                                type: "POST",
                                url: "/Admin/App/startlogo/add",
                                // data: $("#btn_autoImage").val(),
                                success: function(data) {
                                    alert("上传成功");
                                    // alert(data.path);
                                    $(".autoImageArea_image img").attr("src","/"+data.path);
                                    
                                },
                                error: function(XMLHttpRequest, textStatus, errorThrown) {
                                    alert("上传失败，请检查网络后重试");
                                }
                        });
                });
            // 启动图片异步上传
            // 启动图片当前异步上传
                $("#btnStyle_up_currentImage").on("click",function(){
                    $("#btn_currentImage").click();
                });
                $("#btn_currentImage").bind("change", function(){
                        // alert($("#btn_currentImage").val());
                        $("#btn_currentImage").parent("form").ajaxSubmit({
                                type: "POST",
                                url: "/Admin/App/startlogo/add",
                                // data: $("#btn_autoImage").val(),
                                success: function(data) {
                                    alert("上传成功");
                                    // alert(data.path);
                                    $(".currentImageArea_image img").attr("src","/"+data.path);
                                    
                                },
                                error: function(XMLHttpRequest, textStatus, errorThrown) {
                                    alert("上传失败，请检查网络后重试");
                                }
                        });
                });
            // 启动图片当前异步上传
            // 上传结束

    </script>
@endsection