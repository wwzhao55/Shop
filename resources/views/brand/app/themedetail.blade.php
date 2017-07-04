@extends('layouts.app')
@section('siderbar')
@include('layouts.siderbar')
@endsection
@section('content')
<link rel="stylesheet" href="{{URL::asset('admin/css/appset_detail.css')}}">

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

</div>
<!-- 我的主题结束 -->
@endsection