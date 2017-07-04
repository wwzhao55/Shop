@extends('layouts.app')
@section('siderbar')
@include('layouts.siderbar')
@endsection
@section('content')
<link rel="stylesheet" href="{{URL::asset('admin/css/appset.css')}}">
  <div class="navgation">
    <div class="btn-group btn-group-justified" role="group" aria-label="...">
        <div class="btn-group" id="button-startImg" role="group">
            <button type="button"  class="btn">我的主题</button>
        </div>
        <div class="btn-group btnBorder" id="button-advImg" role="group">
            <button type="button"  class="btn">主体商城</button>
        </div>
        <!-- <div class="btn-group" id="button-themeTemplate" role="group">
            <button type="button"  class="btn">主题模板</button>
        </div> -->
    </div>
</div>
<!-- 我的主题开始 -->
<div class="container-fluid startImg">
        <div class="row" >
        <div class="col-md-3 col-xs-3 col-sm-3 col-lg-3 ImageArea"  >
            <div class="ImageArea_image" >
                <img src="{{URL::asset('admin/img/app_img/2.jpeg')}}" />    
                <div class="ImageArea_image_action" >
                    <div type="button" class="btnStyle_del"></div>
                    <div type="button" id="" class="btnStyle_up btnStyle_up0 btnStyle_up1"></div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-xs-3 col-sm-3 col-lg-3 ImageArea" >
            <div class="ImageArea_image" >
                <img src="{{URL::asset('admin/img/app_img/4.jpeg')}}" />  
                <div class="ImageArea_image_action" >
                    <!-- <div type="button" class="btnStyle_del"></div> -->
                    <div type="button" class="btnStyle_sta btnStyle_active"></div>
                </div>    
            </div>
        </div>
        <div class="col-md-3 col-xs-3 col-sm-3 col-lg-3 ImageArea" >
            <div class="ImageArea_image" >
                <img src="{{URL::asset('admin/img/app_img/4.jpeg')}}" />  
                <div class="ImageArea_image_action" >
                    <div type="button" class="btnStyle_sta0 btnStyle_active0"></div>
                    <div type="button" class="btnStyle_del0"></div>
                </div>     
            </div>
        </div>
        <div class="col-md-3 col-xs-3 col-sm-3 col-lg-3 ImageArea" >
            <div class="ImageArea_image" >
                <img src="{{URL::asset('admin/img/app_img/4.jpeg')}}" />   
                <div class="ImageArea_image_action" >
                    <div type="button" class="btnStyle_sta0 btnStyle_active0"></div>
                    <div type="button" class="btnStyle_del0"></div>
                </div>    
            </div>
        </div>
        
        </div>
        <div class="row" >

        <div class="col-md-3 col-xs-3 col-sm-3 col-lg-3 ImageArea" >
            <div class="ImageArea_image" >
                <img src="{{URL::asset('admin/img/app_img/4.jpeg')}}" />   
                <div class="ImageArea_image_action" >
                    <div type="button" class="btnStyle_sta0 btnStyle_active0"></div>
                    <div type="button" class="btnStyle_del0"></div>
                </div>    
            </div>
        </div>
        <div class="col-md-3 col-xs-3 col-sm-3 col-lg-3 ImageArea" >
            <div class="ImageArea_image" >
                <img src="{{URL::asset('admin/img/app_img/4.jpeg')}}" />  
                <div class="ImageArea_image_action" >
                    <div type="button" class="btnStyle_sta0 btnStyle_active0"></div>
                    <div type="button" class="btnStyle_del0"></div>
                </div>     
            </div>
        </div>
        <div class="col-md-3 col-xs-3 col-sm-3 col-lg-3 ImageArea" >
            <div class="ImageArea_image" >
                <img src="{{URL::asset('admin/img/app_img/4.jpeg')}}" />  
                <div class="ImageArea_image_action" >
                    <div type="button" class="btnStyle_sta0 btnStyle_active0"></div>
                    <div type="button" class="btnStyle_del0"></div>
                </div>     
            </div>
        </div>
        <div class="col-md-3 col-xs-3 col-sm-3 col-lg-3 ImageArea" >
            <div class="ImageArea_image" >
                <img src="{{URL::asset('admin/img/app_img/4.jpeg')}}" />  
                <div class="ImageArea_image_action" >
                    <div type="button" class="btnStyle_sta0 btnStyle_active0"></div>
                    <div type="button" class="btnStyle_del0"></div>
                </div>     
            </div>
        </div>
    </div>
    <!-- 弹窗 -->
    <div class="confrimDel">
       <div><span>你确定要删除所选广告图片吗？</span></div>
       <hr class="line" />
       <div>
           <img src="{{URL::asset('admin/img/app_img/btn-cancel.png')}}" id="cancel">       
           <img src="{{URL::asset('admin/img/app_img/btn-determine-s.png')}}" id="confirm"></div>
   </div>
</div>
<!-- 我的主题结束 -->

<!-- 主体商城开始 -->
<div class="container-fluid advImg" style="display:none;">
            <div class="row" >
        <div class="col-md-3 col-xs-3 col-sm-3 col-lg-3 ImageArea"  >
            <div class="ImageArea_image" >
                <a href=""><img src="{{URL::asset('admin/img/app_img/2.jpeg')}}" /></a>
                <div class="ImageArea_image_action1" >
                    <!-- <div type="button" class="btnStyle_sta0 btnStyle_active0"></div>
                    <div type="button" class="btnStyle_del0"></div> -->
                    <span class="name" >小清新</span><span class="price" >￥16.00</span>
                </div>      
            </div>
        </div>
        <div class="col-md-3 col-xs-3 col-sm-3 col-lg-3 ImageArea" >
            <div class="ImageArea_image" >
                <a href="###"><img src="{{URL::asset('admin/img/app_img/4.jpeg')}}" /></a>   
                <div class="ImageArea_image_action1" >
                    <span class="name" >小清新</span><span class="price" >￥16.00</span>
                </div>    
            </div>
        </div>
        <div class="col-md-3 col-xs-3 col-sm-3 col-lg-3 ImageArea" >
            <div class="ImageArea_image" >
                <a href="###"><img src="{{URL::asset('admin/img/app_img/4.jpeg')}}" /></a> 
                <div class="ImageArea_image_action1" >
                    <span class="name" >小清新</span><span class="price" >￥16.00</span>
                </div>     
            </div>
        </div>
        <div class="col-md-3 col-xs-3 col-sm-3 col-lg-3 ImageArea" >
            <div class="ImageArea_image" >
                <a href="###"><img src="{{URL::asset('admin/img/app_img/4.jpeg')}}" /></a> 
                <div class="ImageArea_image_action1" >
                    <span class="name" >小清新</span><span class="price" >￥16.00</span>
                </div>      
            </div>
        </div>
        
        </div>
        <div class="row" >

        <div class="col-md-3 col-xs-3 col-sm-3 col-lg-3 ImageArea" >
            <div class="ImageArea_image" >
                <a href="###"><img src="{{URL::asset('admin/img/app_img/4.jpeg')}}" /></a>  
                <div class="ImageArea_image_action1" >
                    <span class="name" >小清新</span><span class="price" >￥16.00</span>
                </div>      
            </div>
        </div>
        <div class="col-md-3 col-xs-3 col-sm-3 col-lg-3 ImageArea" >
            <div class="ImageArea_image" >
                <a href="###"><img src="{{URL::asset('admin/img/app_img/4.jpeg')}}" /></a> 
                <div class="ImageArea_image_action1" >
                    <span class="name" >小清新</span><span class="price" >￥16.00</span>
                </div>      
            </div>
        </div>
        <div class="col-md-3 col-xs-3 col-sm-3 col-lg-3 ImageArea" >
            <div class="ImageArea_image" >
                <a href="###"><img src="{{URL::asset('admin/img/app_img/4.jpeg')}}" /></a>   
                <div class="ImageArea_image_action1" >
                    <span class="name" >小清新</span><span class="price" >￥16.00</span>
                </div>    
            </div>
        </div>
        <div class="col-md-3 col-xs-3 col-sm-3 col-lg-3 ImageArea" >
            <div class="ImageArea_image" >
                <a href="###"><img src="{{URL::asset('admin/img/app_img/4.jpeg')}}" /></a>  
                <div class="ImageArea_image_action1" >
                    <span class="name" >小清新</span><span class="price" >￥16.00</span>
                </div>     
            </div>
        </div>
    </div>
    
    <!-- 弹窗 -->
    <div class="confrimDel_adv">
       <div><span>你确定要删除所选广告图片吗？</span></div>
       <hr class="line" />
       <div>
           <img src="{{URL::asset('admin/img/app_img/btn-cancel.png')}}" class="cancel">       
           <img src="{{URL::asset('admin/img/app_img/btn-determine-s.png')}}" class="confirm"></div>
   </div>
</div>
<!-- 主体商城结束 -->
<script type="text/javascript">
$(document).ready(function() {
$("#button-startImg").on("click",function(){
        // alert("你好");
        $(".startImg").css("display","block");
        $(".advImg").css("display","none");
        $("#button-startImg button").css("background-color","#fff");
        $("#button-advImg button").css("background-color","#eee");
    });
    $("#button-advImg").on("click",function(){
        $(".advImg").css("display","block");
        $(".startImg").css("display","none");
        $("#button-advImg button").css("background-color","#fff");
        $("#button-startImg button").css("background-color","#eee");
    });




});

</script>
@endsection