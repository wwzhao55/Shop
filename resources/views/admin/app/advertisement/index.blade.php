@extends('layouts.app')
@section('siderbar')
@include('layouts.siderbar')
@endsection

@section('addCss')
<link rel="stylesheet" href="{{URL::asset('admin/css/advImg.css')}}">
@endsection

@section('content')
    <div class="container-fluid advImg">
	<div class="row">
		<div class="col-md-6 col-xs-6 col-sm-6 col-lg-6 advImageArea_left"  >
		<div class="advImageArea_left_image">
			<img class="image_src1" src="{{URL::asset($advertisement->image_src1)}}" />
			<img class="advImageArea_left_image_blow image_src3" src="{{URL::asset($advertisement->image_src3)}}"  />
			<div class="advImageArea_left_image_action1">
				<div type="button" class="btnStyle_del" name="image_src1"></div>
				<div type="button" name="image_src1" class="btnStyle_up btnStyle_up_adv"></div>
			</div>
			<div class="advImageArea_left_image_action2">
				<div type="button" class="btnStyle_del" name="image_src3"></div>
				<div type="button" name="image_src3" class="btnStyle_up btnStyle_up_adv"></div>
			</div>
			<div class="serialNum1">
				1
			</div>
			<div class="serialNum3">
				3
			</div>
		</div>
		</div>
		<div class="col-md-6 col-xs-6 col-sm-6 col-lg-6 advImageArea_right"  >
		<div class="advImageArea_right_image">
			<img class="image_src2" src="{{URL::asset($advertisement->image_src2)}}"  />
			<img class="advImageArea_right_image_blow image_src4" src="{{URL::asset($advertisement->image_src4)}}"  />
			<div class="advImageArea_right_image_action1">
				<div type="button" class="btnStyle_del" name="image_src2"></div>
				<div type="button" name="image_src2" class="btnStyle_up btnStyle_up_adv"></div>
			</div>
			<div class="advImageArea_right_image_action2">
				<div type="button" class="btnStyle_del" name="image_src4"></div>
				<div type="button" name="image_src4" class="btnStyle_up btnStyle_up_adv"></div>
			</div>
			<div class="serialNum2">
				2
			</div>
			<div class="serialNum4">
				4
			</div>
		</div>
		</div>
	</div>
	<!-- {{$advertisement}} -->
</div>
<form  method='post' id="form" action='/Admin/App/material/add/advertisement' enctype="multipart/form-data">
	<!-- <div type="button" id="" class="btnStyle_up btnStyle_up0 btnStyle_up3"></div> -->
	<input type="file" id="input"  style="display:none;" name=""/>
</form>
<!-- <div class="advImg_btnArea">
	<a href="/Admin/datacenter"><button type="button" class="btn btn-danger btnStyle_cancle">取消</button></a>
	<button type="button" class="btn btn-danger btnStyle_sure">确定</button>
</div> -->
<div class="confrimDel">
       <div><span>你确定要删除所选广告图片吗？</span></div>
       <hr class="line" />
       <div>
           <img src="{{URL::asset('admin/img/app_img/btn-cancel.png')}}" id="cancel">       
           <a href=""><img src="{{URL::asset('admin/img/app_img/btn-determine-s.png')}}" id="confirm"></a></div>
   </div>
<script>
                // 效果图图片异步上传
                $(".btnStyle_up_adv").on("click",function(){
                    _this_img_name=$(this);
                    img_name=$(this).attr("name");
                    // alert(img_name);
                    if( img_name=="image_src1" ){
                        // $("#img1").attr("src","");
                        classSerial=$(".image_src1");
                    }else if( img_name=="image_src2" ){
                        // $("#img2").attr("src","");
                        classSerial=$(".image_src2");
                    }else if( img_name=="image_src3" ){
                        // $("#img3").attr("src","");
                        classSerial=$(".image_src3");
                    }else{
                        // $("#img4").attr("src","");
                        classSerial=$(".image_src4");
                    }
                    // alert(classSerial);
                    $("#input").attr("name",img_name);
                    $("#input").click();
                });

                        $("#input").on("change", function(){
                            // alert("进入input改变");
                            // alert($("#input").value());

                            $("#form").ajaxSubmit({
                                    type: "POST",
                                    url: "/Admin/App/advertisement/add",
                                    // data: $("#btn_autoImage").val(),
                                    success: function(data) {
                                        alert("上传成功");
                                        // alert(data.path);
                                        
                                        // alert($("img").hasclass(img_name));
                                        classSerial.attr("src","");
                                        	// alert(classSerial.attr("src"));
                                        	// alert($(this).attr("src",""););
                                        	classSerial.attr("src","/"+data.path);
                                        // $("img").hasclass(img_name).attr("src","");
                                        // alert($("img").hasclass(img_name).attr("src",""));
                                        // $("img").hasclass(img_name).attr("src","http://localhost:8000/"+data.path);
                                        
                                        
                                    },
                                    error: function(XMLHttpRequest, textStatus, errorThrown) {
                                        alert("上传失败，请检查网络后重试");
                                        // alert(data.path);
                                    }
                            });
                        });
            // 效果图图片异步上传
            $(".btnStyle_del").on("click",function(){
            _this=$(this);
            name=_this.attr("name");
            // /Admin/App/advertisement/delete/$name
                $("a").attr("href","/Admin/App/advertisement/delete/"+name); 
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
</script>

@endsection