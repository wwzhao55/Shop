@extends('layouts.app')
@section('siderbar')
@include('layouts.siderbar')
@endsection

@section('addCss')
<link rel="stylesheet" href="{{URL::asset('admin/css/materialManagement.css')}}">
@endsection

@section('content')

    <div class="navgation">
    <div class="btn-group btn-group-justified" role="group" aria-label="...">
        <div class="btn-group" id="button-startImg" role="group">
            <button type="button"  class="btn">启动图片</button>
        </div>
        <div class="btn-group btnBorder" id="button-advImg" role="group">
            <button type="button"  class="btn">广告图片</button>
        </div>
        <div class="btn-group" id="button-themeTemplate" role="group">
            <button type="button"  class="btn">主题模板</button>
        </div>
    </div>
</div>
<!-- 启动图片开始 -->
<div class="container-fluid startImg">
    <div class="row" >
        <div class="col-md-6 col-xs-6 col-sm-6 col-lg-6 autoImageArea"  >
            <span class="areaTitle">默认启动图片</span>
            <div class="autoImageArea_image" >
                <!-- <img src="{{URL::asset('admin/img/app_img/autoimage.png')}}" /> -->
                <!-- {{URL::asset('admin/img/app_img/logo.png')}} -->
                <img src="{{URL::asset('admin/img/app_img/2.jpeg')}}" />      
            </div>
            <div class="autoImageArea_image_action">
                <div type="button" class="btnStyle_del"></div>
                <div type="button" class="btnStyle_sta"></div>
                <form  method='post' id="autoImage" action='/Admin/App/material/add/startlogo' enctype="multipart/form-data">
                    <div type="button" id="btnStyle_up_autoImage" class="btnStyle_up"></div>
                    <input type="file" id="btn_autoImage"  style="display:none;" name="material"/>
                </form>
            </div>
        </div>
        <div class="col-md-6 col-xs-6 col-sm-6 col-lg-6 currentImageArea" >
            <span class="areaTitle">当前启动图片</span>
            <div class="currentImageArea_image" >
                <img src="{{URL::asset('admin/img/app_img/4.jpeg')}}" />      
            </div>
            <div class="autoImageArea_image_action">
                <div type="button" class="btnStyle_del"></div>
                <div type="button" class="btnStyle_sta btnStyle_active"></div>
                <form  method='post' id="currentImage" action='/Admin/App/material/add/startlogo' enctype="multipart/form-data">
                    <div type="button" id="btnStyle_up_currentImage" class="btnStyle_up"></div>
                    <input type="file" id="btn_currentImage"  style="display:none;" name="material"/>                
                </form>
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
<!-- 启动图片结束 -->

<!-- 广告图片开始 -->
<div class="container-fluid advImg" style="display:none;">
    <div class="row">
        <div class="col-md-6 col-xs-6 col-sm-6 col-lg-6 advImageArea_left"  >
        <div class="advImageArea_left_image">
            <img id="img1" src="{{URL::asset('admin/img/app_img/a0.jpeg')}}" />
            <img id="img3" class="advImageArea_left_image_blow" src="{{URL::asset('admin/img/app_img/a6.jpg')}}" />
            <div class="advImageArea_left_image_action1">
                <div type="button" id="btnStyle_del1" class="btnStyle_del0 btnStyle_del1"></div>
                <form  method='post' id="" action='/Admin/App/material/add/advertisement' enctype="multipart/form-data">
                    <div type="button" id="" class="btnStyle_up btnStyle_up0 btnStyle_up1"></div>
                    <input type="file" id="input1"  style="display:none;" name="material"/>                
                </form>
            </div>
            <div class="advImageArea_left_image_action2">
                <div type="button" id="btnStyle_del3" class="btnStyle_del0 btnStyle_del3"></div>
                <form  method='post' id="" action='/Admin/App/material/add/advertisement' enctype="multipart/form-data">
                    <div type="button" id="" class="btnStyle_up btnStyle_up0 btnStyle_up3"></div>
                    <input type="file" id="input3"  style="display:none;" name="material"/>                
                </form>
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
            <img id="img2" src="{{URL::asset('admin/img/app_img/a1.jpg')}}" />
            <img id="img4" class="advImageArea_right_image_blow" src="{{URL::asset('admin/img/app_img/a3.jpg')}}" />
            <div class="advImageArea_right_image_action1">
                <div type="button" id="btnStyle_del2" class="btnStyle_del0 btnStyle_del2"></div>
                <form  method='post' id="" action='/Admin/App/material/add/advertisement' enctype="multipart/form-data">
                    <div type="button" id="" class="btnStyle_up btnStyle_up0 btnStyle_up2"></div>
                    <input type="file" id="input2"  style="display:none;" name="material"/>                
                </form>
            </div>
            <div class="advImageArea_right_image_action2">
                <div type="button" id="btnStyle_del4" class="btnStyle_del0 btnStyle_del4"></div>
                <form  method='post' id="" action='/Admin/App/material/add/advertisement' enctype="multipart/form-data">
                    <div type="button" id="" class="btnStyle_up btnStyle_up0 btnStyle_up4"></div>
                    <input type="file" id="input4"  style="display:none;" name="material"/>                
                </form>
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
    
    <!-- 弹窗 -->
    <div class="confrimDel_adv">
       <div><span>你确定要删除所选广告图片吗？</span></div>
       <hr class="line" />
       <div>
           <img src="{{URL::asset('admin/img/app_img/btn-cancel.png')}}" class="cancel">       
           <img src="{{URL::asset('admin/img/app_img/btn-determine-s.png')}}" class="confirm"></div>
   </div>
</div>
<!-- 广告图片结束 -->

<!-- 主题模板开始 -->
<div class="container-fluid createTemplate" style="display:none;">
<form class="form-inline">
    <div class="templateType">
        <ul>
            <li ><input id="btn1" class="list_active" type="button" value="小清新"></li>
            <li><input id="btn2" type="button" value="文艺范"></li>
            <li><input id="btn3" type="button" value="黑白灰"></li>
            <li><input id="btn4" type="button" value="暗黑"></li>
            <li><input id="btn5" type="button" value="唯美"></li>
            <li><input id="btn6" type="button" value="罗曼蒂克"></li>
            <li><input id="btn7" type="button" value="极简主义"></li>
            <li><input id="btn8" type="button" value="卡哇伊"></li>
        </ul>
    </div>
    <h1>
        小清新主题模板
    </h1>
        <div class="templateSet">
            <div class="form-group childPartSize">
                <label class="label1" for="template_name">模板名称：</label>
                <input type="text" class="form-control" id="template_name" placeholder="&nbsp;小清新"></div>
            <div class="form-group childPartSize">
                <label for="template_backgroundColor">背景色：</label>
                <input type="text" class="form-control" id="template_backgroundColor" placeholder="&nbsp;#fbece9"></div>
            <div class="form-group childPartSize">
                <label for="template_font">字体：</label>
                <input type="text" class="form-control" id="template_font" placeholder="&nbsp;苹方 常规"></div>
        </div>
        <div class="container-fluid templateImg_btns">
            <div class="templateRendering">
                <label for="templateRendering_imgs">&nbsp;效果图:</label>
                <div id="templateRendering_imgs" class="templateRendering_imgs">
                    <ul>
                        <li id="img1"><img src="{{URL::asset('admin/img/app_img/icon-add.png')}}" alt=""></li>
                        <li id="img2"><img src="{{URL::asset('admin/img/app_img/icon-add.png')}}" alt=""></li>
                        <li id="img3"><img src="{{URL::asset('admin/img/app_img/icon-add.png')}}" alt=""></li>
                        <li id="img4"><img src="{{URL::asset('admin/img/app_img/icon-add.png')}}" alt=""></li>
                    </ul>
                </div>
            </div>
            <div class="templateBtns">
                <label for="btns_left">&nbsp;按钮组:</label>
                <div id="btns_left"  class="btns_left">
                    <table>
                        <thead>
                            <td class="btns_left_thead_name">名称</td>
                            <td class="btns_left_thead_ICON">ICON</td>
                            <td class="btns_left_thead_operation">操作</td>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="btns_left_tbody_name">ADD</td>
                                <td class="btns_left_tbody_ICON">
                                    <img src="{{URL::asset('admin/img/app_img/L10.png')}}" alt=""></td>
                                <td class="btns_left_tbody_operation">
                                    <img class="btns_left_tbody_operation_img1" src="{{URL::asset('admin/img/app_img/icon-cancel-gray.png')}}" alt="">
                                    &nbsp;
                                    <img class="btns_left_tbody_operation_img2" src="{{URL::asset('admin/img/app_img/icon-upload-gray.png')}}" alt=""></td>
                            </tr>
                            <tr>
                                <td class="btns_left_tbody_name">ADD</td>
                                <td class="btns_left_tbody_ICON">
                                    <img src="{{URL::asset('admin/img/app_img/L10.png')}}" alt=""></td>
                                <td class="btns_left_tbody_operation">
                                    <img class="btns_left_tbody_operation_img1" src="{{URL::asset('admin/img/app_img/icon-cancel-gray.png')}}" alt="">
                                    &nbsp;
                                    <img class="btns_left_tbody_operation_img2" src="{{URL::asset('admin/img/app_img/icon-upload-gray.png')}}" alt=""></td>
                            </tr>
                            <tr>
                                <td class="btns_left_tbody_name">ADD</td>
                                <td class="btns_left_tbody_ICON">
                                    <img src="{{URL::asset('admin/img/app_img/L10.png')}}" alt=""></td>
                                <td class="btns_left_tbody_operation">
                                    <img class="btns_left_tbody_operation_img1" src="{{URL::asset('admin/img/app_img/icon-cancel-gray.png')}}" alt="">
                                    &nbsp;
                                    <img class="btns_left_tbody_operation_img2" src="{{URL::asset('admin/img/app_img/icon-upload-gray.png')}}" alt=""></td>
                            </tr>
                            <tr>
                                <td class="btns_left_tbody_name">ADD</td>
                                <td class="btns_left_tbody_ICON">
                                    <img src="{{URL::asset('admin/img/app_img/L10.png')}}" alt=""></td>
                                <td class="btns_left_tbody_operation">
                                    <img class="btns_left_tbody_operation_img1" src="{{URL::asset('admin/img/app_img/icon-cancel-gray.png')}}" alt="">
                                    &nbsp;
                                    <img class="btns_left_tbody_operation_img2" src="{{URL::asset('admin/img/app_img/icon-upload-gray.png')}}" alt=""></td>
                            </tr>
                            <tr>
                                <td class="btns_left_tbody_name">ADD</td>
                                <td class="btns_left_tbody_ICON">
                                    <img src="{{URL::asset('admin/img/app_img/L10.png')}}" alt=""></td>
                                <td class="btns_left_tbody_operation">
                                    <img class="btns_left_tbody_operation_img1" src="{{URL::asset('admin/img/app_img/icon-cancel-gray.png')}}" alt="">
                                    &nbsp;
                                    <img class="btns_left_tbody_operation_img2" src="{{URL::asset('admin/img/app_img/icon-upload-gray.png')}}" alt=""></td>
                            </tr>
                            <tr>
                                <td class="btns_left_tbody_name">ADD</td>
                                <td class="btns_left_tbody_ICON">
                                    <img src="{{URL::asset('admin/img/app_img/L10.png')}}" alt=""></td>
                                <td class="btns_left_tbody_operation">
                                    <img class="btns_left_tbody_operation_img1" src="{{URL::asset('admin/img/app_img/icon-cancel-gray.png')}}" alt="">
                                    &nbsp;
                                    <img class="btns_left_tbody_operation_img2" src="{{URL::asset('admin/img/app_img/icon-upload-gray.png')}}" alt=""></td>
                            </tr>
                            <tr>
                                <td class="btns_left_tbody_name">ADD</td>
                                <td class="btns_left_tbody_ICON">
                                    <img src="{{URL::asset('admin/img/app_img/L10.png')}}" alt=""></td>
                                <td class="btns_left_tbody_operation">
                                    <img class="btns_left_tbody_operation_img1" src="{{URL::asset('admin/img/app_img/icon-cancel-gray.png')}}" alt="">
                                    &nbsp;
                                    <img class="btns_left_tbody_operation_img2" src="{{URL::asset('admin/img/app_img/icon-upload-gray.png')}}" alt=""></td>
                            </tr>
                            <tr>
                                <td class="btns_left_tbody_name">ADD</td>
                                <td class="btns_left_tbody_ICON">
                                    <img src="{{URL::asset('admin/img/app_img/L10.png')}}" alt=""></td>
                                <td class="btns_left_tbody_operation">
                                    <img class="btns_left_tbody_operation_img1" src="{{URL::asset('admin/img/app_img/icon-cancel-gray.png')}}" alt="">
                                    &nbsp;
                                    <img class="btns_left_tbody_operation_img2" src="{{URL::asset('admin/img/app_img/icon-upload-gray.png')}}" alt=""></td>
                            </tr>
                            <tr>
                                <td class="btns_left_tbody_name">ADD</td>
                                <td class="btns_left_tbody_ICON">
                                    <img src="{{URL::asset('admin/img/app_img/L10.png')}}" alt=""></td>
                                <td class="btns_left_tbody_operation">
                                    <img class="btns_left_tbody_operation_img1" src="{{URL::asset('admin/img/app_img/icon-cancel-gray.png')}}" alt="">
                                    &nbsp;
                                    <img class="btns_left_tbody_operation_img2" src="{{URL::asset('admin/img/app_img/icon-upload-gray.png')}}" alt=""></td>
                            </tr>
                            <tr>
                                <td class="btns_left_tbody_name">ADD</td>
                                <td class="btns_left_tbody_ICON">
                                    <img src="{{URL::asset('admin/img/app_img/L10.png')}}" alt=""></td>
                                <td class="btns_left_tbody_operation">
                                    <img class="btns_left_tbody_operation_img1" src="{{URL::asset('admin/img/app_img/icon-cancel-gray.png')}}" alt="">
                                    &nbsp;
                                    <img class="btns_left_tbody_operation_img2" src="{{URL::asset('admin/img/app_img/icon-upload-gray.png')}}" alt=""></td>
                            </tr>
                            <tr>
                                <td class="btns_left_tbody_name">ADD</td>
                                <td class="btns_left_tbody_ICON">
                                    <img src="{{URL::asset('admin/img/app_img/L10.png')}}" alt=""></td>
                                <td class="btns_left_tbody_operation">
                                    <img class="btns_left_tbody_operation_img1" src="{{URL::asset('admin/img/app_img/icon-cancel-gray.png')}}" alt="">
                                    &nbsp;
                                    <img class="btns_left_tbody_operation_img2" src="{{URL::asset('admin/img/app_img/icon-upload-gray.png')}}" alt=""></td>
                            </tr>
                            <tr>
                                <td class="btns_left_tbody_name">ADD</td>
                                <td class="btns_left_tbody_ICON">
                                    <img src="{{URL::asset('admin/img/app_img/L10.png')}}" alt=""></td>
                                <td class="btns_left_tbody_operation">
                                    <img class="btns_left_tbody_operation_img1" src="{{URL::asset('admin/img/app_img/icon-cancel-gray.png')}}" alt="">
                                    &nbsp;
                                    <img class="btns_left_tbody_operation_img2" src="{{URL::asset('admin/img/app_img/icon-upload-gray.png')}}" alt=""></td>
                            </tr>
                            <tr>
                                <td class="btns_left_tbody_name">ADD</td>
                                <td class="btns_left_tbody_ICON">
                                    <img src="{{URL::asset('admin/img/app_img/L10.png')}}" alt=""></td>
                                <td class="btns_left_tbody_operation">
                                    <img class="btns_left_tbody_operation_img1" src="{{URL::asset('admin/img/app_img/icon-cancel-gray.png')}}" alt="">
                                    &nbsp;
                                    <img class="btns_left_tbody_operation_img2" src="{{URL::asset('admin/img/app_img/icon-upload-gray.png')}}" alt=""></td>
                            </tr>
                            <tr>
                                <td class="btns_left_tbody_name">ADD</td>
                                <td class="btns_left_tbody_ICON">
                                    <img src="{{URL::asset('admin/img/app_img/L10.png')}}" alt=""></td>
                                <td class="btns_left_tbody_operation">
                                    <img class="btns_left_tbody_operation_img1" src="{{URL::asset('admin/img/app_img/icon-cancel-gray.png')}}" alt="">
                                    &nbsp;
                                    <img class="btns_left_tbody_operation_img2" src="{{URL::asset('admin/img/app_img/icon-upload-gray.png')}}" alt=""></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="btns_right">
                    <table>
                        <thead>
                            <td class="btns_right_thead_name">名称</td>
                            <td class="btns_right_thead_ICON">ICON</td>
                            <td class="btns_right_thead_operation">操作</td>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="btns_left_tbody_name">ADD</td>
                                <td class="btns_left_tbody_ICON">
                                    <img src="{{URL::asset('admin/img/app_img/L10.png')}}" alt=""></td>
                                <td class="btns_left_tbody_operation">
                                    <img class="btns_left_tbody_operation_img1" src="{{URL::asset('admin/img/app_img/icon-cancel-gray.png')}}" alt="">
                                    &nbsp;
                                    <img class="btns_left_tbody_operation_img2" src="{{URL::asset('admin/img/app_img/icon-upload-gray.png')}}" alt=""></td>
                            </tr>
                            <tr>
                                <td class="btns_left_tbody_name">ADD</td>
                                <td class="btns_left_tbody_ICON">
                                    <img src="{{URL::asset('admin/img/app_img/L10.png')}}" alt=""></td>
                                <td class="btns_left_tbody_operation">
                                    <img class="btns_left_tbody_operation_img1" src="{{URL::asset('admin/img/app_img/icon-cancel-gray.png')}}" alt="">
                                    &nbsp;
                                    <img class="btns_left_tbody_operation_img2" src="{{URL::asset('admin/img/app_img/icon-upload-gray.png')}}" alt=""></td>
                            </tr>
                            <tr>
                                <td class="btns_left_tbody_name">ADD</td>
                                <td class="btns_left_tbody_ICON">
                                    <img src="{{URL::asset('admin/img/app_img/L10.png')}}" alt=""></td>
                                <td class="btns_left_tbody_operation">
                                    <img class="btns_left_tbody_operation_img1" src="{{URL::asset('admin/img/app_img/icon-cancel-gray.png')}}" alt="">
                                    &nbsp;
                                    <img class="btns_left_tbody_operation_img2" src="{{URL::asset('admin/img/app_img/icon-upload-gray.png')}}" alt=""></td>
                            </tr>
                            <tr>
                                <td class="btns_left_tbody_name">ADD</td>
                                <td class="btns_left_tbody_ICON">
                                    <img src="{{URL::asset('admin/img/app_img/L10.png')}}" alt=""></td>
                                <td class="btns_left_tbody_operation">
                                    <img class="btns_left_tbody_operation_img1" src="{{URL::asset('admin/img/app_img/icon-cancel-gray.png')}}" alt="">
                                    &nbsp;
                                    <img class="btns_left_tbody_operation_img2" src="{{URL::asset('admin/img/app_img/icon-upload-gray.png')}}" alt=""></td>
                            </tr>
                            <tr>
                                <td class="btns_left_tbody_name">ADD</td>
                                <td class="btns_left_tbody_ICON">
                                    <img src="{{URL::asset('admin/img/app_img/L10.png')}}" alt=""></td>
                                <td class="btns_left_tbody_operation">
                                    <img class="btns_left_tbody_operation_img1" src="{{URL::asset('admin/img/app_img/icon-cancel-gray.png')}}" alt="">
                                    &nbsp;
                                    <img class="btns_left_tbody_operation_img2" src="{{URL::asset('admin/img/app_img/icon-upload-gray.png')}}" alt=""></td>
                            </tr>
                            <tr>
                                <td class="btns_left_tbody_name">ADD</td>
                                <td class="btns_left_tbody_ICON">
                                    <img src="{{URL::asset('admin/img/app_img/L10.png')}}" alt=""></td>
                                <td class="btns_left_tbody_operation">
                                    <img class="btns_left_tbody_operation_img1" src="{{URL::asset('admin/img/app_img/icon-cancel-gray.png')}}" alt="">
                                    &nbsp;
                                    <img class="btns_left_tbody_operation_img2" src="{{URL::asset('admin/img/app_img/icon-upload-gray.png')}}" alt=""></td>
                            </tr>
                            <tr>
                                <td class="btns_left_tbody_name">ADD</td>
                                <td class="btns_left_tbody_ICON">
                                    <img src="{{URL::asset('admin/img/app_img/L10.png')}}" alt=""></td>
                                <td class="btns_left_tbody_operation">
                                    <img class="btns_left_tbody_operation_img1" src="{{URL::asset('admin/img/app_img/icon-cancel-gray.png')}}" alt="">
                                    &nbsp;
                                    <img class="btns_left_tbody_operation_img2" src="{{URL::asset('admin/img/app_img/icon-upload-gray.png')}}" alt=""></td>
                            </tr>
                            <tr>
                                <td class="btns_left_tbody_name">ADD</td>
                                <td class="btns_left_tbody_ICON">
                                    <img src="{{URL::asset('admin/img/app_img/L10.png')}}" alt=""></td>
                                <td class="btns_left_tbody_operation">
                                    <img class="btns_left_tbody_operation_img1" src="{{URL::asset('admin/img/app_img/icon-cancel-gray.png')}}" alt="">
                                    &nbsp;
                                    <img class="btns_left_tbody_operation_img2" src="{{URL::asset('admin/img/app_img/icon-upload-gray.png')}}" alt=""></td>
                            </tr>
                            <tr>
                                <td class="btns_left_tbody_name">ADD</td>
                                <td class="btns_left_tbody_ICON">
                                    <img src="{{URL::asset('admin/img/app_img/L10.png')}}" alt=""></td>
                                <td class="btns_left_tbody_operation">
                                    <img class="btns_left_tbody_operation_img1" src="{{URL::asset('admin/img/app_img/icon-cancel-gray.png')}}" alt="">
                                    &nbsp;
                                    <img class="btns_left_tbody_operation_img2" src="{{URL::asset('admin/img/app_img/icon-upload-gray.png')}}" alt=""></td>
                            </tr>
                            <tr>
                                <td class="btns_left_tbody_name">ADD</td>
                                <td class="btns_left_tbody_ICON">
                                    <img src="{{URL::asset('admin/img/app_img/L10.png')}}" alt=""></td>
                                <td class="btns_left_tbody_operation">
                                    <img class="btns_left_tbody_operation_img1" src="{{URL::asset('admin/img/app_img/icon-cancel-gray.png')}}" alt="">
                                    &nbsp;
                                    <img class="btns_left_tbody_operation_img2" src="{{URL::asset('admin/img/app_img/icon-upload-gray.png')}}" alt=""></td>
                            </tr>
                            <tr>
                                <td class="btns_left_tbody_name">ADD</td>
                                <td class="btns_left_tbody_ICON">
                                    <img src="{{URL::asset('admin/img/app_img/L10.png')}}" alt=""></td>
                                <td class="btns_left_tbody_operation">
                                    <img class="btns_left_tbody_operation_img1" src="{{URL::asset('admin/img/app_img/icon-cancel-gray.png')}}" alt="">
                                    &nbsp;
                                    <img class="btns_left_tbody_operation_img2" src="{{URL::asset('admin/img/app_img/icon-upload-gray.png')}}" alt=""></td>
                            </tr>
                            <tr>
                                <td class="btns_left_tbody_name">ADD</td>
                                <td class="btns_left_tbody_ICON">
                                    <img src="{{URL::asset('admin/img/app_img/L10.png')}}" alt=""></td>
                                <td class="btns_left_tbody_operation">
                                    <img class="btns_left_tbody_operation_img1" src="{{URL::asset('admin/img/app_img/icon-cancel-gray.png')}}" alt="">
                                    &nbsp;
                                    <img class="btns_left_tbody_operation_img2" src="{{URL::asset('admin/img/app_img/icon-upload-gray.png')}}" alt=""></td>
                            </tr>
                            <tr>
                                <td class="btns_left_tbody_name">ADD</td>
                                <td class="btns_left_tbody_ICON">
                                    <img src="{{URL::asset('admin/img/app_img/L10.png')}}" alt=""></td>
                                <td class="btns_left_tbody_operation">
                                    <img class="btns_left_tbody_operation_img1" src="{{URL::asset('admin/img/app_img/icon-cancel-gray.png')}}" alt="">
                                    &nbsp;
                                    <img class="btns_left_tbody_operation_img2" src="{{URL::asset('admin/img/app_img/icon-upload-gray.png')}}" alt=""></td>
                            </tr>
                            <tr>
                                <td class="btns_left_tbody_name">ADD</td>
                                <td class="btns_left_tbody_ICON">
                                    <img src="{{URL::asset('admin/img/app_img/L10.png')}}" alt=""></td>
                                <td class="btns_left_tbody_operation">
                                    <img class="btns_left_tbody_operation_img1" src="{{URL::asset('admin/img/app_img/icon-cancel-gray.png')}}" alt="">
                                    &nbsp;
                                    <img class="btns_left_tbody_operation_img2" src="{{URL::asset('admin/img/app_img/icon-upload-gray.png')}}" alt=""></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            
        </div>
</form>
</div>
<!-- 主题模板结束 -->
<script type="text/javascript">
$(document).ready(function() {
    // alert("nihao");
    $("#button-startImg").on("click",function(){
        // alert("你好");
        $(".startImg").css("display","block");
        $(".advImg").css("display","none");
        $(".createTemplate").css("display","none");
        $("#button-startImg button").css("background-color","#fff");
        $("#button-advImg button").css("background-color","#eee");
        $("#button-themeTemplate button").css("background-color","#eee");
    });
    $("#button-advImg").on("click",function(){
        $(".advImg").css("display","block");
        $(".startImg").css("display","none");
        $(".createTemplate").css("display","none");
        // $("#button-advImg button").css("background-color","#fff");
        $("#button-advImg button").css("background-color","#fff");
        $("#button-startImg button").css("background-color","#eee");
        $("#button-themeTemplate button").css("background-color","#eee");
    });
    $("#button-themeTemplate").on("click",function(){
        $(".createTemplate").css("display","block");
        $(".advImg").css("display","none");
        $(".startImg").css("display","none");
        // $("#button-themeTemplate button").css("background-color","#fff");
        $("#button-themeTemplate button").css("background-color","#fff");
        $("#button-advImg button").css("background-color","#eee");
        $("#button-startImg button").css("background-color","#eee");
    });

    $(".templateType input").on("click",function(){
        $(".templateType input").css({
            "background-color": "#fff",
            "color": "#707070"
        });
        $(this).css({
            "background-color": "#fa2e5c",
            "color": "#fff"
        });
    });

            // 启动图片按钮切换开始
                var cancel_index;
                var img_adv_serialNum;
                var _this_advUp_nextInput;
        $(".btnStyle_sta").on("click",function(){
            // _this=$(this);
            if($(this).hasClass("btnStyle_active")){

            }else{
                $(".btnStyle_sta").removeClass("btnStyle_active");
                $(this).addClass("btnStyle_active");
            }
        });
            // 启动图片按钮切换结束
           // 启动图片按钮删除开始
        $(".btnStyle_del").on("click",function(){
               _this=$(this);
                is_src_empty = _this.parent("div").parent("div").find("img").attr("src");
                if(is_src_empty==""){
                    
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
            // 启动图片按钮删除结束
            // 广告图片删除按钮开始
                
                $(".btnStyle_del0").on("click",function(){
                    _this=$(this);
                    is_src_empty_adv = $("#img1").attr("src");

                    if( _this.hasClass("btnStyle_del1") ){
                        img_adv_serialNum=1;
                    }else if( _this.hasClass("btnStyle_del2") ){
                        img_adv_serialNum=2;

                    }else if( _this.hasClass("btnStyle_del3") ){
                        img_adv_serialNum=3;
                    }else{
                        img_adv_serialNum=4;
                    }
                    
                            if(is_src_empty_adv==""){
                                
                            }else{
                                cancel_index_adv=layer.open({
                               type: 1,
                               title:false,
                               skin: 'layui-layer-demo', //样式类名
                               closeBtn: 0, //不显示关闭按钮
                               shift: 2,
                               shadeClose: true, //开启遮罩关闭
                               area : ["41.66%" , '300px'],
                               content:$('.confrimDel_adv'),
                                }); 
                            }
        });
            // 广告图片删除按钮结束
        //点击取消按钮，选中按钮，弹窗消失
            $("#cancel").on("click",function(){
               layer.close(cancel_index);
            });
            $(".cancel").on("click",function(){
               layer.close(cancel_index_adv);
            });
        //点击确定按钮，取消选中的按钮，弹窗消失
            $("#confirm").on("click",function(){
                layer.close(cancel_index);
                // $(".btnStyle_sta").addClass("btnStyle_active");
                // _this.removeClass("btnStyle_active");
                _this.parent("div").parent("div").find("img").attr("src","");

                $.ajax({
                type:'POST',
                url:'/Admin/App/startlogo/delete',                          
                dataType:"json",
                success:function(result){
                    if(result.status=="success"){
                        // $('#no-search-history').css('display','block');
                        // $('#y-search-history').css('display','none'); 
                        // $.toast(result.msg);
                        alert("删除成功！");
                    }else{
                        alert("删除失败！");
                    }
                }               
                });
            });
            $(".confirm").on("click",function(){
                layer.close(cancel_index_adv);
                
                    if( img_adv_serialNum==1 ){
                        $("#img1").attr("src","");
                    }else if( img_adv_serialNum==2 ){
                        $("#img2").attr("src","");
                    }else if( img_adv_serialNum==3 ){
                        $("#img3").attr("src","");
                    }else{
                        $("#img4").attr("src","");
                    }

                $.ajax({
                type:'POST',
                url:'/Admin/App/startlogo/delete',                          
                dataType:"json",
                success:function(result){
                    if(result.status=="success"){
                        alert("删除成功！");
                    }else{
                        alert("删除失败！");
                    }
                }               
                });
            });
            // 启动图片异步上传
                $("#btnStyle_up_autoImage").on("click",function(){
                    $("#btn_autoImage").click();
                });
                $("#btn_autoImage").bind("change", function(){
                        // alert($("#btn_autoImage").val());
                        $("#btn_autoImage").parent("form").ajaxSubmit({
                                type: "POST",
                                url: "/Admin/App/material/add/startlogo",
                                // data: $("#btn_autoImage").val(),
                                success: function(data) {
                                    alert("上传成功");
                                    // alert(data.path);
                                    $(".autoImageArea_image img").attr("src","http://localhost:8000/"+data.path);
                                    
                                },
                                error: function(XMLHttpRequest, textStatus, errorThrown) {
                                    alert("上传失败，请检查网络后重试");
                                }
                        });
                });
            // 启动图片异步上传
            // 启动图片异步上传2
                $("#btnStyle_up_currentImage").on("click",function(){
                    $("#btn_currentImage").click();
                    // $(this).next("input").click();
                });
                $("#btn_currentImage").bind("change", function(){
                        // alert($("#btn_autoImage").val());
                        $("#btn_currentImage").parent("form").ajaxSubmit({
                                type: "POST",
                                url: "/Admin/App/material/add/startlogo",
                                // data: $("#btn_autoImage").val(),
                                success: function(data) {
                                    alert("上传成功");
                                    // alert(data.path);
                                    $(".currentImageArea_image img").attr("src","http://localhost:8000/"+data.path);
                                    
                                },
                                error: function(XMLHttpRequest, textStatus, errorThrown) {
                                    alert("上传失败，请检查网络后重试");
                                }
                        });
                });
            // 启动图片异步上传2
            // 广告图片异步上传
                $(".btnStyle_up0").on("click",function(){
                    // $("#btn_currentImage").click();
                    _this_advUp=$(this);
                    // _this_advUp_nextInput=_this_advUp.next("input");
                    // alert(_this_advUp_nextInput);

                        if( _this_advUp.hasClass("btnStyle_up1") ){
                            _img_serialNum=$("#img1");
                            // _this_advUp_nextInput=$("#input1");
                            // _this_advUp_nextInput=$("#input1");
                            // alert("1");
                        }else if( _this_advUp.hasClass("btnStyle_up2") ){
                            _img_serialNum=$("#img2");
                            // alert("2");

                        }else if( _this_advUp.hasClass("btnStyle_up3") ){
                            _img_serialNum=$("#img3");
                            // alert("3");
                        }else{
                            _img_serialNum=$("#img4");
                            // alert("4");
                        }   

                    _this_advUp.next("input").click();
                });
                // $("#input1").bind("change", function(){
                    // _this_advUp_nextInput.on("change", function(){
                        $("#input1,#input2,#input3,#input4").on("change", function(){
                        // _this_advUp_nextInput.change(function(){
                        // alert($("#btn_autoImage").val());
                        // alert("进入input改变");

                        _this_advUp.parent("form").ajaxSubmit({
                                type: "POST",
                                url: "/Admin/App/material/add/advertisement",
                                // data: $("#btn_autoImage").val(),
                                success: function(data) {
                                    alert("上传成功");
                                    // alert(data.path);
                                    _img_serialNum.attr("src","http://localhost:8000/"+data.path);
                                    
                                },
                                error: function(XMLHttpRequest, textStatus, errorThrown) {
                                    alert("上传失败，请检查网络后重试");
                                }
                        });
                });
            // 广告图片异步上传
        
});

</script>

@endsection