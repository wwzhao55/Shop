@extends('layouts.app')
@section('siderbar')
@include('layouts.siderbar')
@endsection
@section('content')
    <link rel="stylesheet" href="{{URL::asset('admin/css/Template_detail.css')}}">
    <div class="container-fluid createTemplate">
    <form class="form-inline">
        <h1>
            模板详情
        </h1>
            <div class="templateSet" style="height:160px;">
                <div class="form-group childPartSize">
                    <label for="template_name">模板名称：</label>
                    <input type="text" disabled class="form-control" id="template_name" placeholder="&nbsp;&nbsp;&nbsp;{{$theme->name}}"></div>
                <div class="form-group childPartSize">
                    <label for="template_backgroundColor">背景色：</label>
                    <input type="text" disabled class="form-control" id="template_backgroundColor" placeholder="&nbsp;&nbsp;&nbsp;{{$theme->background_color}}"></div>
                <div class="form-group childPartSize">
                    <label for="template_font">字体：</label>
                    <input type="text" disabled class="form-control" id="template_font" placeholder="&nbsp;&nbsp;&nbsp;{{$theme->font}}"></div>

                <div class="form-group childPartSize" style="margin-top:25px;">
                    <label for="template_describe">模板描述：</label>
                    <input type="text" disabled class="form-control" id="template_describe" placeholder="&nbsp;&nbsp;&nbsp;{{$theme->description}}"></div>
                <div class="form-group childPartSize" style="margin-top:25px;">
                    <label for="template_price">&nbsp;价格 ：</label>
                    <input type="text" disabled class="form-control" id="template_price" placeholder="&nbsp;&nbsp;&nbsp;{{$theme->price}}"></div>
            </div>
            <div class="container-fluid templateImg_btns">
                <div class="templateRendering">
                    <label for="templateRendering_imgs">&nbsp;效果图:</label>
                    <div id="templateRendering_imgs" class="templateRendering_imgs">
                        <ul>
                            <li id="img1" class="li_style">
                                @if($theme->effect_img[0]->img_src)
                                <img src="{{URL::asset($theme->effect_img[0]->img_src)}}" alt="">
                                @endif
                                <div class="li_image_action1">
                                    <div type="button" class="btnStyle_del btnStyle_del_li btnStyle_del_li1"></div>
                                    <div type="button" id="" class="btnStyle_up btnStyle_up_li btnStyle_up_li1"></div>
                                    <!-- <form  method='post' id="" action='/Admin/App/theme/effectimg' enctype="multipart/form-data">
                                        
                                        <input type="file" id="input_li1"  style="display:none;" name="effect_img"/>
                                    </form> -->
                                </div>
                            </li>
                            <li id="img2" class="li_style">
                            @if($theme->effect_img[1]->img_src)
                                <img src="{{URL::asset($theme->effect_img[1]->img_src)}}" alt="">
                                @endif
                                <div class="li_image_action1">
                                    <div type="button" class="btnStyle_del btnStyle_del_li btnStyle_del_li2"></div>
                                    <div type="button" id="" class="btnStyle_up btnStyle_up_li btnStyle_up_li2"></div>
                                    <!-- <form  method='post' id="" action='/Admin/App/theme/effectimg' enctype="multipart/form-data">
                                        
                                        <input type="file" id="input_li2"  style="display:none;" name="effect_img"/>
                                    </form> -->
                                </div>
                            </li>
                            <li id="img3" class="li_style">
                            @if($theme->effect_img[2]->img_src)
                                <img src="{{URL::asset($theme->effect_img[2]->img_src)}}" alt="">
                                @endif
                                <div class="li_image_action1">
                                    <div type="button" class="btnStyle_del btnStyle_del_li btnStyle_del_li3"></div>
                                    <div type="button" id="" class="btnStyle_up btnStyle_up_li btnStyle_up_li3"></div>
                                    <!-- <form  method='post' id="" action='/Admin/App/theme/effectimg' enctype="multipart/form-data">
                                        
                                        <input type="file" id="input_li3"  style="display:none;" name="effect_img"/>
                                    </form> -->
                                </div>
                            </li>
                            <li id="img4" class="li_style">
                            @if($theme->effect_img[3]->img_src)
                                <img src="{{URL::asset($theme->effect_img[3]->img_src)}}" alt="">
                                @endif
                                <div class="li_image_action1">
                                    <div type="button" class="btnStyle_del btnStyle_del_li btnStyle_del_li4"></div>
                                    <div type="button" id="" class="btnStyle_up btnStyle_up_li btnStyle_up_li4"></div>
                                    <!-- <form  method='post' id="" action='/Admin/App/theme/effectimg' enctype="multipart/form-data">
                                        
                                        <input type="file" id="input_li4"  style="display:none;" name="effect_img"/>
                                    </form> -->
                                </div>
                            </li>
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
                                <!-- <form  method='post' id="icon_left_form" action='/Admin/App/theme/'.$theme->id.'' enctype="multipart/form-data">
                                    <input type="file" id="icon_left"  style="display:none;" name=""/>                                
                                </form> -->
                                <tr>
                                    <td class="btns_left_tbody_name">ADD</td>
                                    <td class="btns_left_tbody_ICON">
                                    <!-- {{$theme->id}} -->
                                        <img  src="{{URL::asset('uploads/app/theme/'.$theme->id.'/add.png')}}" alt="">
                                        </td>
                                    <td class="btns_left_tbody_operation">
                                        <!-- <img class="btns_left_tbody_operation_img1 icon_img1" src="{{URL::asset('admin/img/app_img/icon-cancel-gray.png')}}" alt="">
                                        &nbsp; -->
                                        <img  name="add" class="btns_left_tbody_operation_img2" src="{{URL::asset('admin/img/app_img/icon-upload-gray.png')}}" alt=""></td>
                                </tr>
                                <tr>
                                    <td class="btns_left_tbody_name">BILL</td>
                                    <td class="btns_left_tbody_ICON">
                                        <img src="{{URL::asset('uploads/app/theme/'.$theme->id.'/bill.png')}}" alt=""></td>
                                    <td class="btns_left_tbody_operation">
                                        <!-- <img class="btns_left_tbody_operation_img1" src="{{URL::asset('admin/img/app_img/icon-cancel-gray.png')}}" alt="">
                                        &nbsp; -->
                                        <img name="bill" class="btns_left_tbody_operation_img2" src="{{URL::asset('admin/img/app_img/icon-upload-gray.png')}}" alt=""></td>
                                </tr>
                                <tr>
                                    <td class="btns_left_tbody_name">BTN</td>
                                    <td class="btns_left_tbody_ICON">
                                        <img src="{{URL::asset('uploads/app/theme/'.$theme->id.'/btn.png')}}" alt=""></td>
                                    <td class="btns_left_tbody_operation">
                                        <!-- <img class="btns_left_tbody_operation_img1" src="{{URL::asset('admin/img/app_img/icon-cancel-gray.png')}}" alt="">
                                        &nbsp; -->
                                        <img name="btn" class="btns_left_tbody_operation_img2" src="{{URL::asset('admin/img/app_img/icon-upload-gray.png')}}" alt=""></td>
                                </tr>
                                <tr>
                                    <td class="btns_left_tbody_name">CONFIRM</td>
                                    <td class="btns_left_tbody_ICON">
                                        <img src="{{URL::asset('uploads/app/theme/'.$theme->id.'/confirm.png')}}" alt=""></td>
                                    <td class="btns_left_tbody_operation">
                                        <!-- <img class="btns_left_tbody_operation_img1" src="{{URL::asset('admin/img/app_img/icon-cancel-gray.png')}}" alt="">
                                        &nbsp; -->
                                        <img name="confirm" class="btns_left_tbody_operation_img2" src="{{URL::asset('admin/img/app_img/icon-upload-gray.png')}}" alt=""></td>
                                </tr>
                                <tr>
                                    <td class="btns_left_tbody_name">DELECT</td>
                                    <td class="btns_left_tbody_ICON">
                                        <img src="{{URL::asset('uploads/app/theme/'.$theme->id.'/delect.png')}}" alt=""></td>
                                    <td class="btns_left_tbody_operation">
                                        <!-- <img class="btns_left_tbody_operation_img1" src="{{URL::asset('admin/img/app_img/icon-cancel-gray.png')}}" alt="">
                                        &nbsp; -->
                                        <img name="delect" class="btns_left_tbody_operation_img2" src="{{URL::asset('admin/img/app_img/icon-upload-gray.png')}}" alt=""></td>
                                </tr>
                                <tr>
                                    <td class="btns_left_tbody_name">GATHERING</td>
                                    <td class="btns_left_tbody_ICON">
                                        <img src="{{URL::asset('uploads/app/theme/'.$theme->id.'/gathering.png')}}" alt=""></td>
                                    <td class="btns_left_tbody_operation">
                                        <!-- <img class="btns_left_tbody_operation_img1" src="{{URL::asset('admin/img/app_img/icon-cancel-gray.png')}}" alt="">
                                        &nbsp; -->
                                        <img  name="gathering" class="btns_left_tbody_operation_img2" src="{{URL::asset('admin/img/app_img/icon-upload-gray.png')}}" alt=""></td>
                                </tr>
                                <tr>
                                    <td class="btns_left_tbody_name">HISTORY</td>
                                    <td class="btns_left_tbody_ICON">
                                        <img src="{{URL::asset('uploads/app/theme/'.$theme->id.'/history.png')}}" alt=""></td>
                                    <td class="btns_left_tbody_operation">
                                        <!-- <img class="btns_left_tbody_operation_img1" src="{{URL::asset('admin/img/app_img/icon-cancel-gray.png')}}" alt="">
                                        &nbsp; -->
                                        <img name="history" class="btns_left_tbody_operation_img2" src="{{URL::asset('admin/img/app_img/icon-upload-gray.png')}}" alt=""></td>
                                </tr>
                                <tr>
                                    <td class="btns_left_tbody_name">HISTORY-HOVER</td>
                                    <td class="btns_left_tbody_ICON">
                                        <img src="{{URL::asset('uploads/app/theme/'.$theme->id.'/history_hover.png')}}" alt=""></td>
                                    <td class="btns_left_tbody_operation">
                                        <!-- <img class="btns_left_tbody_operation_img1" src="{{URL::asset('admin/img/app_img/icon-cancel-gray.png')}}" alt="">
                                        &nbsp; -->
                                        <img name="history_hover" class="btns_left_tbody_operation_img2" src="{{URL::asset('admin/img/app_img/icon-upload-gray.png')}}" alt=""></td>
                                </tr>
                                <tr>
                                    <td class="btns_left_tbody_name">ICON</td>
                                    <td class="btns_left_tbody_ICON">
                                        <img src="{{URL::asset('uploads/app/theme/'.$theme->id.'/icon.png')}}" alt=""></td>
                                    <td class="btns_left_tbody_operation">
                                        <!-- <img class="btns_left_tbody_operation_img1" src="{{URL::asset('admin/img/app_img/icon-cancel-gray.png')}}" alt="">
                                        &nbsp; -->
                                        <img name="icon" class="btns_left_tbody_operation_img2" src="{{URL::asset('admin/img/app_img/icon-upload-gray.png')}}" alt=""></td>
                                </tr>
                                <tr>
                                    <td class="btns_left_tbody_name">INPUT.9</td>
                                    <td class="btns_left_tbody_ICON">
                                        <img src="{{URL::asset('uploads/app/theme/'.$theme->id.'/input_9.png')}}" alt=""></td>
                                    <td class="btns_left_tbody_operation">
                                        <!-- <img class="btns_left_tbody_operation_img1" src="{{URL::asset('admin/img/app_img/icon-cancel-gray.png')}}" alt="">
                                        &nbsp; -->
                                        <img name="input_9" class="btns_left_tbody_operation_img2" src="{{URL::asset('admin/img/app_img/icon-upload-gray.png')}}" alt=""></td>
                                </tr>
                                <tr>
                                    <td class="btns_left_tbody_name">INPUT_MIDDLE.9</td>
                                    <td class="btns_left_tbody_ICON">
                                        <img src="{{URL::asset('uploads/app/theme/'.$theme->id.'/input_middle_9.png')}}" alt=""></td>
                                    <td class="btns_left_tbody_operation">
                                        <!-- <img class="btns_left_tbody_operation_img1" src="{{URL::asset('admin/img/app_img/icon-cancel-gray.png')}}" alt="">
                                        &nbsp; -->
                                        <img name="input_middle_9" class="btns_left_tbody_operation_img2" src="{{URL::asset('admin/img/app_img/icon-upload-gray.png')}}" alt=""></td>
                                </tr>
                                <tr>
                                    <td class="btns_left_tbody_name">LACE</td>
                                    <td class="btns_left_tbody_ICON">
                                        <img src="{{URL::asset('uploads/app/theme/'.$theme->id.'/lace.png')}}" alt=""></td>
                                    <td class="btns_left_tbody_operation">
                                        <!-- <img class="btns_left_tbody_operation_img1" src="{{URL::asset('admin/img/app_img/icon-cancel-gray.png')}}" alt="">
                                        &nbsp; -->
                                        <img name="lace" class="btns_left_tbody_operation_img2" src="{{URL::asset('admin/img/app_img/icon-upload-gray.png')}}" alt=""></td>
                                </tr>
                                <tr>
                                    <td class="btns_left_tbody_name">LIST</td>
                                    <td class="btns_left_tbody_ICON">
                                        <img src="{{URL::asset('uploads/app/theme/'.$theme->id.'/list.png')}}" alt=""></td>
                                    <td class="btns_left_tbody_operation">
                                        <!-- <img class="btns_left_tbody_operation_img1" src="{{URL::asset('admin/img/app_img/icon-cancel-gray.png')}}" alt="">
                                        &nbsp; -->
                                        <img name="list" class="btns_left_tbody_operation_img2" src="{{URL::asset('admin/img/app_img/icon-upload-gray.png')}}" alt=""></td>
                                </tr>
                                <tr>
                                    <td class="btns_left_tbody_name">LIST_HOVER</td>
                                    <td class="btns_left_tbody_ICON">
                                        <img src="{{URL::asset('uploads/app/theme/'.$theme->id.'/list_hover.png')}}" alt=""></td>
                                    <td class="btns_left_tbody_operation">
                                        <!-- <img class="btns_left_tbody_operation_img1" src="{{URL::asset('admin/img/app_img/icon-cancel-gray.png')}}" alt="">
                                        &nbsp; -->
                                        <img name="list_hover" class="btns_left_tbody_operation_img2" src="{{URL::asset('admin/img/app_img/icon-upload-gray.png')}}" alt=""></td>
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
                                    <td class="btns_left_tbody_name">LOGIN_BTN</td>
                                    <td class="btns_left_tbody_ICON">
                                        <img src="{{URL::asset('uploads/app/theme/'.$theme->id.'/login_btn.png')}}" alt=""></td>
                                    <td class="btns_left_tbody_operation">
                                        <!-- <img class="btns_left_tbody_operation_img1" src="{{URL::asset('admin/img/app_img/icon-cancel-gray.png')}}" alt="">
                                        &nbsp; -->
                                        <img name="login_btn" class="btns_left_tbody_operation_img2" src="{{URL::asset('admin/img/app_img/icon-upload-gray.png')}}" alt=""></td>
                                </tr>
                                <tr>
                                    <td class="btns_left_tbody_name">ORDER</td>
                                    <td class="btns_left_tbody_ICON">
                                        <img src="{{URL::asset('uploads/app/theme/'.$theme->id.'/order.png')}}" alt=""></td>
                                    <td class="btns_left_tbody_operation">
                                        <!-- <img class="btns_left_tbody_operation_img1" src="{{URL::asset('admin/img/app_img/icon-cancel-gray.png')}}" alt="">
                                        &nbsp; -->
                                        <img name="order" class="btns_left_tbody_operation_img2" src="{{URL::asset('admin/img/app_img/icon-upload-gray.png')}}" alt=""></td>
                                </tr>
                                <tr>
                                    <td class="btns_left_tbody_name">PAY</td>
                                    <td class="btns_left_tbody_ICON">
                                        <img src="{{URL::asset('uploads/app/theme/'.$theme->id.'/pay.png')}}" alt=""></td>
                                    <td class="btns_left_tbody_operation">
                                        <!-- <img class="btns_left_tbody_operation_img1" src="{{URL::asset('admin/img/app_img/icon-cancel-gray.png')}}" alt="">
                                        &nbsp; -->
                                        <img name="pay" class="btns_left_tbody_operation_img2" src="{{URL::asset('admin/img/app_img/icon-upload-gray.png')}}" alt=""></td>
                                </tr>
                                <tr>
                                    <td class="btns_left_tbody_name">PAY_MODE</td>
                                    <td class="btns_left_tbody_ICON">
                                        <img src="{{URL::asset('uploads/app/theme/'.$theme->id.'/pay_mode.png')}}" alt=""></td>
                                    <td class="btns_left_tbody_operation">
                                        <!-- <img class="btns_left_tbody_operation_img1" src="{{URL::asset('admin/img/app_img/icon-cancel-gray.png')}}" alt="">
                                        &nbsp; -->
                                        <img name="pay_mode" class="btns_left_tbody_operation_img2" src="{{URL::asset('admin/img/app_img/icon-upload-gray.png')}}" alt=""></td>
                                </tr>
                                <tr>
                                    <td class="btns_left_tbody_name">PAY_MODE_BG</td>
                                    <td class="btns_left_tbody_ICON">
                                        <img src="{{URL::asset('uploads/app/theme/'.$theme->id.'/pay_mode_bg.png')}}" alt=""></td>
                                    <td class="btns_left_tbody_operation">
                                        <!-- <img class="btns_left_tbody_operation_img1" src="{{URL::asset('admin/img/app_img/icon-cancel-gray.png')}}" alt="">
                                        &nbsp; -->
                                        <img name="pay_mode_bg" class="btns_left_tbody_operation_img2" src="{{URL::asset('admin/img/app_img/icon-upload-gray.png')}}" alt=""></td>
                                </tr>
                                <tr>
                                    <td class="btns_left_tbody_name">RADIO</td>
                                    <td class="btns_left_tbody_ICON">
                                        <img src="{{URL::asset('uploads/app/theme/'.$theme->id.'/radio.png')}}" alt=""></td>
                                    <td class="btns_left_tbody_operation">
                                        <!-- <img class="btns_left_tbody_operation_img1" src="{{URL::asset('admin/img/app_img/icon-cancel-gray.png')}}" alt="">
                                        &nbsp; -->
                                        <img name="radio" class="btns_left_tbody_operation_img2" src="{{URL::asset('admin/img/app_img/icon-upload-gray.png')}}" alt=""></td>
                                </tr>
                                <tr>
                                    <td class="btns_left_tbody_name">RADIO_ACTIVE</td>
                                    <td class="btns_left_tbody_ICON">
                                        <img src="{{URL::asset('uploads/app/theme/'.$theme->id.'/radio_active.png')}}" alt=""></td>
                                    <td class="btns_left_tbody_operation">
                                        <!-- <img class="btns_left_tbody_operation_img1" src="{{URL::asset('admin/img/app_img/icon-cancel-gray.png')}}" alt="">
                                        &nbsp; -->
                                        <img name="radio_active" class="btns_left_tbody_operation_img2" src="{{URL::asset('admin/img/app_img/icon-upload-gray.png')}}" alt=""></td>
                                </tr>
                                <tr>
                                    <td class="btns_left_tbody_name">REDUCE</td>
                                    <td class="btns_left_tbody_ICON">
                                        <img src="{{URL::asset('uploads/app/theme/'.$theme->id.'/reduce.png')}}" alt=""></td>
                                    <td class="btns_left_tbody_operation">
                                        <!-- <img class="btns_left_tbody_operation_img1" src="{{URL::asset('admin/img/app_img/icon-cancel-gray.png')}}" alt="">
                                        &nbsp; -->
                                        <img name="reduce" class="btns_left_tbody_operation_img2" src="{{URL::asset('admin/img/app_img/icon-upload-gray.png')}}" alt=""></td>
                                </tr>
                                <tr>
                                    <td class="btns_left_tbody_name">RETURN</td>
                                    <td class="btns_left_tbody_ICON">
                                        <img src="{{URL::asset('uploads/app/theme/'.$theme->id.'/return.png')}}" alt=""></td>
                                    <td class="btns_left_tbody_operation">
                                        <!-- <img class="btns_left_tbody_operation_img1" src="{{URL::asset('admin/img/app_img/icon-cancel-gray.png')}}" alt="">
                                        &nbsp; -->
                                        <img  name="return" class="btns_left_tbody_operation_img2" src="{{URL::asset('admin/img/app_img/icon-upload-gray.png')}}" alt=""></td>
                                </tr>
                                <tr>
                                    <td class="btns_left_tbody_name">SEARCH</td>
                                    <td class="btns_left_tbody_ICON">
                                        <img src="{{URL::asset('uploads/app/theme/'.$theme->id.'/search.png')}}" alt=""></td>
                                    <td class="btns_left_tbody_operation">
                                        <!-- <img class="btns_left_tbody_operation_img1" src="{{URL::asset('admin/img/app_img/icon-cancel-gray.png')}}" alt="">
                                        &nbsp; -->
                                        <img name="search" class="btns_left_tbody_operation_img2" src="{{URL::asset('admin/img/app_img/icon-upload-gray.png')}}" alt=""></td>
                                </tr>
                                <tr>
                                    <td class="btns_left_tbody_name">SELECT</td>
                                    <td class="btns_left_tbody_ICON">
                                        <img src="{{URL::asset('uploads/app/theme/'.$theme->id.'/select.png')}}" alt=""></td>
                                    <td class="btns_left_tbody_operation">
                                        <!-- <img class="btns_left_tbody_operation_img1" src="{{URL::asset('admin/img/app_img/icon-cancel-gray.png')}}" alt="">
                                        &nbsp; -->
                                        <img name="select" class="btns_left_tbody_operation_img2" src="{{URL::asset('admin/img/app_img/icon-upload-gray.png')}}" alt=""></td>
                                </tr>
                                <tr>
                                    <td class="btns_left_tbody_name">SHOPPING</td>
                                    <td class="btns_left_tbody_ICON">
                                        <img src="{{URL::asset('uploads/app/theme/'.$theme->id.'/shopping.png')}}" alt=""></td>
                                    <td class="btns_left_tbody_operation">
                                        <!-- <img class="btns_left_tbody_operation_img1" src="{{URL::asset('admin/img/app_img/icon-cancel-gray.png')}}" alt="">
                                        &nbsp; -->
                                        <img name="shopping" class="btns_left_tbody_operation_img2" src="{{URL::asset('admin/img/app_img/icon-upload-gray.png')}}" alt=""></td>
                                </tr>
                                <tr>
                                    <td class="btns_left_tbody_name">SUBMIT</td>
                                    <td class="btns_left_tbody_ICON">
                                        <img src="{{URL::asset('uploads/app/theme/'.$theme->id.'/submit.png')}}" alt=""></td>
                                    <td class="btns_left_tbody_operation">
                                        <!-- <img class="btns_left_tbody_operation_img1" src="{{URL::asset('admin/img/app_img/icon-cancel-gray.png')}}" alt="">
                                        &nbsp; -->
                                        <img name="submit" class="btns_left_tbody_operation_img2" src="{{URL::asset('admin/img/app_img/icon-upload-gray.png')}}" alt=""></td>
                                </tr>
                                <tr>
                                    <td class="btns_left_tbody_name">SUCCESS</td>
                                    <td class="btns_left_tbody_ICON">
                                        <img src="{{URL::asset('uploads/app/theme/'.$theme->id.'/success.png')}}" alt=""></td>
                                    <td class="btns_left_tbody_operation">
                                        <!-- <img class="btns_left_tbody_operation_img1" src="{{URL::asset('admin/img/app_img/icon-cancel-gray.png')}}" alt="">
                                        &nbsp; -->
                                        <img  name="success" class="btns_left_tbody_operation_img2" src="{{URL::asset('admin/img/app_img/icon-upload-gray.png')}}" alt=""></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- <div class="selectForSubmit">
                    <a href="/Admin/App/theme" ><button type="button" id="create_cancle" class="btn btn-danger btnStyle_cancle">取消</button></a>
                    <button type="button" id="create_sure" class="btn btn-danger btnStyle_sure">确定</button>
                </div> -->
            </div>
    </form>
        <!-- 弹窗 -->
        <div class="confrimDel_adv">
           <div><span>你确定要删除所选广告图片吗？</span></div>
           <hr class="line" />
           <div>
               <img src="{{URL::asset('admin/img/app_img/btn-cancel.png')}}" class="cancel">       
               <img src="{{URL::asset('admin/img/app_img/btn-determine-s.png')}}" class="confirm"></div>
       </div>
       <form  method='post' autocomplete="off"  id="icon_left_form" action='/Admin/App/theme/buttongroup' enctype="multipart/form-data">
           <input type="file" id="icon_left"  style="display:none;" name=""/>       
       </form>
       <form  method='post'  id="rendering_form" action='/Admin/App/theme/effectimg' enctype="multipart/form-data">
           <input type="file" id="rendering_input"  style="display:none;" name="effect_img"/>       
       </form>
        <!-- {{$theme}} -->
    </div>
    <script>
    var _this_advUp;
    var _img_serialNum;
    var name_icon;
    var effect_imgs;
    var path_num;
    var path_arr=[];
        // 模板图片删除按钮开始
                
                $(".btnStyle_del_li").on("click",function(){
                    _this=$(this);
                    is_src_empty_adv = $("#img1 img").attr("src");

                    if( _this.hasClass("btnStyle_del_li1") ){
                        img_adv_serialNum=1;
                    }else if( _this.hasClass("btnStyle_del_li2") ){
                        img_adv_serialNum=2;

                    }else if( _this.hasClass("btnStyle_del_li3") ){
                        img_adv_serialNum=3;
                    }else{
                        img_adv_serialNum=4;
                    }
                    
                            if(is_src_empty_adv==""){
                                // alert("空");
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
        // 模板图片删除按钮结束
        //点击取消按钮，选中按钮，弹窗消失
            $(".cancel").on("click",function(){
               layer.close(cancel_index_adv);
            });
        // 点击确定按钮，取消选中的按钮，弹窗消失
          $(".confirm").on("click",function(){
                layer.close(cancel_index_adv);
                // alert(img_adv_serialNum);
                    if( img_adv_serialNum==1 ){
                        $("#img1 img").attr("src","");
                    }else if( img_adv_serialNum==2 ){
                        $("#img2 img").attr("src","");
                    }else if( img_adv_serialNum==3 ){
                        $("#img3 img").attr("src","");
                    }else{
                        $("#img4 img").attr("src","");
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

          // 效果图图片异步上传
                $(".btnStyle_up_li").on("click",function(){
                    // $("#btn_currentImage").click();
                    _this_advUp=$(this);
                    // _this_advUp_nextInput=_this_advUp.next("input");
                    // alert(_this_advUp_nextInput);

                        if( _this_advUp.hasClass("btnStyle_up_li1") ){
                            _img_serialNum=$("#img1 img");
                            path_num=0;
                            // alert("1");
                        }else if( _this_advUp.hasClass("btnStyle_up_li2") ){
                            _img_serialNum=$("#img2 img");
                            path_num=1;
                            // alert("2");

                        }else if( _this_advUp.hasClass("btnStyle_up_li3") ){
                            _img_serialNum=$("#img3 img");
                            path_num=2;
                            // alert("3");
                        }else{
                            _img_serialNum=$("#img4 img");
                            path_num=3;
                            // alert("4");
                        }   

                    $("#rendering_input").click();
                });
                // $("#input1").bind("change", function(){
                    // _this_advUp_nextInput.on("change", function(){
                        // $("#input_li1,#input_li2,#input_li3,#input_li4").on("change", function(){
                            $("#rendering_input").on("change", function(){
                        // _this_advUp_nextInput.change(function(){
                        // alert($("#btn_autoImage").val());
                        // alert("进入input改变");

                        $("#rendering_form").ajaxSubmit({
                                type: "POST",
                                url: "/Admin/App/theme/effectimg",
                                // data: $("#btn_autoImage").val(),
                                success: function(data) {
                                    alert("上传成功");
                                    // alert(data.path);
                                    path_arr[path_num]=data.path;
                                    // _img_serialNum.attr("src","http://localhost:8000/"+data.path);
                                    _img_serialNum.attr("src","http://localhost:8000/"+data.path);
                                    // _img_serialNum.attr("src","http://shop.dataguiding.com//"+data.path);
                                    
                                },
                                error: function(XMLHttpRequest, textStatus, errorThrown) {
                                    alert("上传失败，请检查网络后重试");
                                }
                        });
                });
            // 效果图图片异步上传

            // 创建模板确定操作
                // id="create_sure"
                $("#create_sure").on("click",function(){
                    // effect_imgs=$("#img1 img")+","+$("#img2 img")+","+$("#img3 img")+","+$("#img3 img");
                    // alert(path_arr);
                    // document.write(path_arr.join(","));
                    // alert("进入确定");
                    path_str=path_arr.join(",");
                    // alert(path_str);
                    // alert($("#template_name").val());
                    // alert($("#template_backgroundColor").val());
                    // alert($("#template_font").val());
                    // alert($("#template_price").val());
                    // alert($("#template_describe").val());
                    $.ajax({
                                    type: "POST",
                                    url: "/Admin/App/theme/add",
                                    data: {
                                        name: $("#template_name").val(),
                                        effect_img: path_arr.join(","),
                                        background_color: $("#template_backgroundColor").val(),
                                        font: $("#template_font").val(),
                                        price: $("#template_price").val(),
                                        description: $("#template_describe").val(),
                                    },
                                    success: function(data) {
                                        alert("上传成功");
                                        // alert(data.status);
                                    },
                                    error: function(XMLHttpRequest, textStatus, errorThrown) {
                                        alert("上传失败，请检查网络后重试");
                                    }
                            });
                });
            // 创建模板取消操作

            // 按钮组删除鼠标悬停、移去事件
            
            $(".btns_left_tbody_operation_img1").on("mouseover",function(){
              var src1="{{URL::asset('admin/img/app_img/icon-cancel-s-hover.png')}}"
              $(this).attr("src",src1);
          });
          $(".btns_left_tbody_operation_img1").on("mouseout",function(){
              // var src1="{{URL::asset('admin/img/app_img/icon-upload-s-hover.png')}}"
              var src1="{{URL::asset('admin/img/app_img/icon-cancel-gray.png')}}"
              $(this).attr("src",src1);
          });
            
            // 按钮组上传鼠标悬停、移去事件
            
            $(".btns_left_tbody_operation_img2").on("mouseover",function(){
              var src1="{{URL::asset('admin/img/app_img/icon-upload-s-hover.png')}}"
              $(this).attr("src",src1);
          });
          $(".btns_left_tbody_operation_img2").on("mouseout",function(){
              var src1="{{URL::asset('admin/img/app_img/icon-upload-gray.png')}}"
              $(this).attr("src",src1);
          });
          // 按钮组删除操作开始
          //   $(".btns_left_tbody_operation_img1").on("click",function(){

          // });
            $(".btns_left_tbody_operation_img1").on("click",function(){
                    _this_icon_del=$(this);
                    is_icon_empty_del = _this_icon_del.attr("src");
                    
                            if(is_icon_empty_del==""){
                            }else{
                                cancel_index_icon=layer.open({
                               type: 1,
                               title:false,
                               skin: 'layui-layer-demo', 
                               closeBtn: 0, 
                               shift: 2,
                               shadeClose: true, 
                               area : ["41.66%" , '300px'],
                               content:$('.confrimDel_adv'),
                                }); 
                            }
                });
            //点击取消按钮，选中按钮，弹窗消失
            $(".cancel_icon_del").on("click",function(){
               layer.close(cancel_index_icon);
            });
        // 点击确定按钮，取消选中的按钮，弹窗消失
          $(".confirm_icon_del").on("click",function(){
                layer.close(cancel_index_icon);
                _this_icon_del.attr("src","");

                // $.ajax({
                // type:'POST',
                // url:'/Admin/App/startlogo/delete',                          
                // dataType:"json",
                // success:function(result){
                //     if(result.status=="success"){
                //         alert("删除成功！");
                //     }else{
                //         alert("删除失败！");
                //     }
                // }               
                // });
            });

          // 按钮组删除操作结束
          // 按钮组上传操作开始
                // 效果图图片异步上传
                $(".btns_left_tbody_operation_img2").on("click",function(){
                    _this_icon_up=$(this);
                    name_icon=$(this).attr("name");
                    $("#icon_left").attr("name",name_icon);
                    $("#icon_left").click();
                });

                        $("#icon_left").on("change", function(){

                            $("#icon_left_form").ajaxSubmit({
                                    type: "POST",
                                    url: "/Admin/App/theme/buttongroup",
                                    success: function(data) {
                                        alert("上传成功");

                                        _this_icon_up.parent("td").prev("td").find("img").attr("src","");
                                        _this_icon_up.parent("td").prev("td").find("img").attr("src","http://localhost:8000/"+data.path+ "?temp=" + Math.random());
                                        
                                    },
                                    error: function(XMLHttpRequest, textStatus, errorThrown) {
                                        alert("上传失败，请检查网络后重试");
                                    }
                            });
                        });
            // 效果图图片异步上传
            // 按钮组上传操作结束
    </script>
@endsection