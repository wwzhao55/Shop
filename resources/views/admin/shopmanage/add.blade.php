<!-- auth:wuwenjia -->
@extends('layouts.app')
@section('siderbar')
@include('layouts.siderbar')
@endsection

@section('addCss')
<link rel="stylesheet" type="text/css" href="{{ URL::asset('shop/css/brandmanage.css')}}">
<!-- <link rel="stylesheet" href="http://code.jquery.com/jPages.css"> -->
@endsection

@section('content')
<div class="addBranch">
        <form action='/Admin/shopmanage/add/{{$brand_id}}' method="post" enctype="multipart/form-data" onsubmit="return check()">
             {!! csrf_field() !!}
            <div class="page-head">创建分店<a href="javascript:history.go(-1)"><input class="add-return" value="返回" type="button"></a></div>
            <div class="add-success">
                {{Session::get('Message')}}
            </div> 
            <div class="branch-left">
                    <div class="branch-top {{ $errors->has('shopname') ? ' has-error' : '' }}">
                        <span>分店名 :</span>
                        <input type="text"  name="shopname" value="{{old('shopname')}}">
                        @if ($errors->has('shopname'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('shopname') }}</strong>
                                        </span>
                        @endif
                    </div>
                    <div class="branch-list {{ $errors->has('customer_service_phone') ? ' has-error' : '' }}">
                        <span>客服电话 :</span>
                        <input type="phone" placeholder=""   name="customer_service_phone" value="{{old('customer_service_phone')}}">
                                @if ($errors->has('customer_service_phone'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('customer_service_phone') }}</strong>
                                                </span>
                                @endif                        
                    </div>
                    <div class="branch-list {{ $errors->has('contacter_name') ? ' has-error' : '' }}">
                        <span>负责人 :</span>
                        <input type="text" placeholder="请输入负责人姓名"  name="contacter_name" value="{{old('contacter_name')}}">
                            @if ($errors->has('contacter_name'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('contacter_name') }}</strong>
                                        </span>
                            @endif
                    </div>
                    <div class="branch-list {{ $errors->has('contacter_phone') ? ' has-error' : '' }}">
                        <span>手机号 :</span>
                        <input type="text" placeholder="请输入手机号"  name="contacter_phone" value="{{old('contacter_phone')}}" autocomplete="off">
                                @if ($errors->has('contacter_phone'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('contacter_phone') }}</strong>
                                                </span>
                                @endif                        
                    </div>
                    <div class="mention3">手机号为商户分店负责人登录名</div>
                    <div class="branch-list{{ $errors->has('password') ? ' has-error' : '' }}">
                        <span>密码 :</span>
                        <input type="text" placeholder="请输入登录密码"  name="password" onfocus="this.type='password'" value="" autocomplete="off">
                            @if ($errors->has('password'))
                                            <span class="help-block">
                                                <strong>*{{ $errors->first('password') }}</strong>
                                            </span>
                            @endif
                    </div>
                    <div class="mention3">密码为该商户分店负责人首次默认登录密码</div>
                    <div class="branch-list{{ $errors->has('contacter_email') ? ' has-error' : '' }}">
                        <span>邮箱 :</span>
                        <input type="email" placeholder=""   name="contacter_email" value="{{old('contacter_email')}}">
                            @if ($errors->has('contacter_email'))
                                            <span class="help-block">
                                                <strong>*{{ $errors->first('contacter_email') }}</strong>
                                            </span>
                            @endif
                    </div>
                    <div class="branch-list{{ $errors->has('contacter_QQ') ? ' has-error' : '' }}">
                        <span>QQ :</span>
                        <input type="text" placeholder=""   name="contacter_QQ" value="{{old('contacter_QQ')}}">
                            @if ($errors->has('contacter_QQ'))
                                            <span class="help-block">
                                                <strong>*{{ $errors->first('contacter_QQ') }}</strong>
                                            </span>
                            @endif
                    </div>
                    
                    <div class="branch-list">
                        <span>地址 :</span>
                        <select  class="distinct-1" name="shop_district"  value="{{old('shop_district')}}"><option value="0">区</option> </select>
                        <select  class="city-1" name="shop_city"  value="{{old('shop_city')}}"><option value="0">市</option> </select>
                        <select  class="province-1" name="shop_province"  value="{{old('shop_province')}}">  </select>   
                    </div>
                    <div class="branch-list{{ $errors->has('shop_address_detail') ? ' has-error' : '' }}">
                            <input type="text" placeholder="请输入详细地址"   name="shop_address_detail" value="{{old('shop_address_detail')}}">
                                @if ($errors->has('shop_address_detail'))
                                            <span class="help-block">
                                                <strong>*{{ $errors->first('shop_address_detail') }}</strong>
                                            </span>
                                @endif
                    </div>
                    <div class="branch-list{{ $errors->has('latitude') ? ' has-error' : '' }}">
                        <span>纬度 :</span>
                            <input type="text" placeholder="纬度"   name="latitude" value="{{old('latitude')}}">
                                @if ($errors->has('latitude'))
                                            <span class="help-block">
                                                <strong>*{{ $errors->first('latitude') }}</strong>
                                            </span>
                                @endif
                    </div>
                    <div class="branch-list{{ $errors->has('longitude') ? ' has-error' : '' }}">
                        <span>经度 :</span>
                            <input type="text" placeholder="经度"   name="longitude" value="{{old('longitude')}}">
                                @if ($errors->has('longitude'))
                                            <span class="help-block">
                                                <strong>*{{ $errors->first('longitude') }}</strong>
                                            </span>
                                @endif
                    </div>
            </div>
            <div class="clearfix" style="clear:both;"></div>

            <div id="brand-bottom">
                    <a href="/Admin/brandmanage/detail/{{$brand_id}}"><img src="{{asset('shop/images/brandmanage/btn-return.png')}}" class="return"></a>
                    <input type="image" src="{{asset('shop/images/brandmanage/btn-determine.png')}}" class="confirm">
            </div>
        </from>
    </div>
    <script type="text/javascript">
    $('#menu-lists-parent').find('.onsidebar').removeClass('onsidebar');
    $('.brandmanage').addClass('onsidebar');
    $(function () {

            var html = "<option value='0'>省</option>";
            $.each(pdata,function(idx,item){
                if (parseInt(item.level) == 0) {
                    html += "<option value='" + item.names + "' exid='" + item.code + "'>" + item.names + "</option>";
                }
            });
            $(".province-1").append(html);

            $(".province-1").change(function(){
                if ($(this).val() == "") 
                    return;
                $(".city-1 option").remove(); 
                $(".distinct-1 option").remove();
                var code = $(this).find("option:selected").attr("exid"); 
                code = code.substring(0,2);
                var html = ""; 
                $(".distinct-1").append(html);
                $.each(pdata,function(idx,item){
                    if (parseInt(item.level) == 1 && code == item.code.substring(0,2)) {
                        html += "<option value='" + item.names + "' exid='" + item.code + "'>" + item.names + "</option>";
                    }
                });
                $(".city-1").append(html);   
                $(".city-1").change();   
            });

            $(".city-1").change(function(){
                if ($(this).val() == "") return;
                $(".distinct-1 option").remove();
                var code = $(this).find("option:selected").attr("exid"); code = code.substring(0,4);
                var html = "";
                $.each(pdata,function(idx,item){
                    if (parseInt(item.level) == 2 && code == item.code.substring(0,4)) {
                        html += "<option value='" + item.names + "' exid='" + item.code + "'>" + item.names + "</option>";
                    }
                });
                $(".distinct-1").append(html);      
            });
            
    });
    // $('.confirm').on('click',function(){
    //             check();
    //         });
            function check(){
                if ($("input[name='shopname']").parent(".branch-top").has(".shopwarning")) {
                    $("input[name='shopname']").parent(".branch-top").find(".shopwarning").remove();
                }
                if ($("input[name='customer_service_phone']").parent(".branch-list").has(".shopwarning")) {
                    $("input[name='customer_service_phone']").parent(".branch-list").find(".shopwarning").remove();
                }
                if ($("input[name='contacter_name']").parent(".branch-list").has(".shopwarning")) {
                    $("input[name='contacter_name']").parent(".branch-list").find(".shopwarning").remove();
                }
                if ($("input[name='contacter_phone']").parent(".branch-list").has(".shopwarning")) {
                    $("input[name='contacter_phone']").parent(".branch-list").find(".shopwarning").remove();
                }
                if ($("input[name='password']").parent(".branch-list").has(".shopwarning")) {
                    $("input[name='password']").parent(".branch-list").find(".shopwarning").remove();
                }
                if ($("input[name='contacter_email']").parent(".branch-list").has(".shopwarning")) {
                    $("input[name='contacter_email']").parent(".branch-list").find(".shopwarning").remove();
                }
                if ($("input[name='contacter_QQ']").parent(".branch-list").has(".shopwarning")) {
                    $("input[name='contacter_QQ']").parent(".branch-list").find(".shopwarning").remove();
                }
                if ($("input[name='shop_address_detail']").parent(".branch-list").has(".shopwarning")) {
                    $("input[name='shop_address_detail']").parent(".branch-list").find(".shopwarning").remove();
                }
                if ($("select[name='shop_province']").parent(".branch-list").has(".shopwarning")) {
                    $("select[name='shop_province']").parent(".branch-list").find(".shopwarning").remove();
                }
                if ($("select[name='shop_city']").parent(".branch-list").has(".shopwarning")) {
                    $("select[name='shop_city']").parent(".branch-list").find(".shopwarning").remove();
                }
                if ($("select[name='shop_district']").parent(".branch-list").has(".shopwarning")) {
                    $("select[name='shop_district']").parent(".branch-list").find(".shopwarning").remove();
                }
                var shop_name=$("input[name='shopname']").val();
                if (shop_name == "" || shop_name == null) {
                    var span = $("<div class='shopwarning'>请您输入店铺名称！</div>");
                    $("input[name='shopname']").parent(".branch-top").append(span);
                }
                var ser_phone=$("input[name='customer_service_phone']").val();
                if (ser_phone == "" || ser_phone == null) {
                    var span = $("<div class='shopwarning'>请输入正确的客服电话!</div>");
                    $("input[name='customer_service_phone']").parent(".branch-list").append(span);
                }
                var con_name=$("input[name='contacter_name']").val();
                if (con_name == "" || con_name == null) {
                    var span = $("<div class='shopwarning'>请输入负责人姓名!</div>");
                    $("input[name='contacter_name']").parent(".branch-list").append(span);
                }
                var con_phone=$("input[name='contacter_phone']").val();
                if (con_phone == "" || con_phone == null) {
                    var span = $("<div class='shopwarning'>请输入正确的手机号码!</div>");
                    $("input[name='contacter_phone']").parent(".branch-list").append(span);
                }
                var pass_word=$("input[name='password']").val();
                if (pass_word == "" || pass_word == null) {
                    var span = $("<div class='shopwarning'>请您输入密码！</div>");
                    $("input[name='password']").parent(".branch-list").append(span);
                }
                var con_email=$("input[name='contacter_email']").val();
                //正则表达式验证邮箱
                var szReg = /^[A-Za-z0-9]+([-_.][A-Za-z0-9]+)*@([A-Za-z0-9]+[-.])+[A-Za-z0-9]{2,5}$/;
                var bChk = szReg.test(con_email);
                if (con_email == "" || con_email == null) {
                    var span = $("<div class='shopwarning'>请输入负责人邮箱!</div>");
                    $("input[name='contacter_email']").parent(".branch-list").append(span);
                }
                else if (!bChk) {
                    var span = $("<div class='shopwarning'>请按正确的邮箱格式输入邮箱!</div>");
                    $("input[name='contacter_email']").parent(".branch-list").append(span);
                }
                var con_qq=$("input[name='contacter_QQ']").val();
                if (con_qq == "" || con_qq == null) {
                    var span = $("<div class='shopwarning'>请您输入QQ号！</div>");
                    $("input[name='contacter_QQ']").parent(".branch-list").append(span);
                }
                var con_address=$("input[name='shop_address_detail']").val();
                if (con_address == "" || con_address == null) {
                    var span = $("<div class='shopwarning'>请您输入详细地址！</div>");
                    $("input[name='shop_address_detail']").parent(".branch-list").append(span);
                }
                if($('.province-1 option:selected').val()==0){
                    var span = $("<div class='shopwarning'>请您选择输入省！</div>");
                    $("select[name='shop_province']").parent(".branch-list").append(span);
                    return false;
                }
                if($('.city-1 option:selected').val()==0){
                    var span = $("<div class='shopwarning'>请您选择输入市！</div>");
                    $("select[name='shop_city']").parent(".branch-list").append(span);
                    return false;
                }
                if($('.distinct-1 option:selected').val()==0){
                    var span = $("<div class='shopwarning'>请您选择输入区！</div>");
                    $("select[name='shop_district']").parent(".branch-list").append(span);
                    return false;
                }
                if(shop_name&&ser_phone&&con_name&&con_phone&&pass_word&&con_email&&con_qq&&con_address){
                    return true;
                }else{
                    return false;
                }
            }
            $("input[name='shopname']").on("blur", function () {
                if ($("input[name='shopname']").parent(".branch-top").has(".shopwarning")) {
                    $("input[name='shopname']").parent(".branch-top").find(".shopwarning").remove();
                }
                var shop_name = $("input[name='shopname']").val();
                if (shop_name == "" || shop_name == null) {
                    var span = $("<div class='shopwarning'>请您输入店铺名称！</div>");
                    $("input[name='shopname']").parent(".branch-top").append(span);
                }
                else if ($("input[name='shopname']").parent(".branch-top").has(".shopwarning")) {
                    {
                        $("input[name='shopname']").parent(".branch-top").find(".shopwarning").remove();
                    }

                }
            });
            $("input[name='customer_service_phone']").on("blur", function () {
                if ($("input[name='customer_service_phone']").parent(".branch-list").has(".shopwarning")) {
                    $("input[name='customer_service_phone']").parent(".branch-list").find(".shopwarning").remove();
                }
                var ser_phone=$("input[name='customer_service_phone']").val();
                if (ser_phone == "" || ser_phone == null) {
                    var span = $("<div class='shopwarning'>请输入正确的客服电话!</div>");
                    $("input[name='customer_service_phone']").parent(".branch-list").append(span);
                }
                else if ($("input[name='customer_service_phone']").parent(".branch-list").has(".shopwarning")) {
                    {
                        $("input[name='customer_service_phone']").parent(".branch-list").find(".shopwarning").remove();
                    }

                }
            });
            $("input[name='contacter_name']").on("blur", function () {
                if ($("input[name='contacter_name']").parent(".branch-list").has(".shopwarning")) {
                    $("input[name='contacter_name']").parent(".branch-list").find(".shopwarning").remove();
                }
                var con_name=$("input[name='contacter_name']").val();
                if (con_name == "" || con_name == null) {
                    var span = $("<div class='shopwarning'>请输入负责人姓名!</div>");
                    $("input[name='contacter_name']").parent(".branch-list").append(span);
                }
                else if ($("input[name='contacter_name']").parent(".branch-list").has(".shopwarning")) {
                    {
                        $("input[name='contacter_name']").parent(".branch-list").find(".shopwarning").remove();
                    }

                }
            });
            $("input[name='contacter_phone']").on("blur", function () {
                if ($("input[name='contacter_phone']").parent(".branch-list").has(".shopwarning")) {
                    $("input[name='contacter_phone']").parent(".branch-list").find(".shopwarning").remove();
                }
                var con_phone=$("input[name='contacter_phone']").val();
                if (con_phone == "" || con_phone == null) {
                    var span = $("<div class='shopwarning'>请输入正确的手机号码!</div>");
                    $("input[name='contacter_phone']").parent(".branch-list").append(span);
                }
                else if ($("input[name='contacter_phone']").parent(".branch-list").has(".shopwarning")) {
                    {
                        $("input[name='contacter_phone']").parent(".branch-list").find(".shopwarning").remove();
                    }

                }
            });
            $("input[name='password']").on("blur", function () {
                if ($("input[name='password']").parent(".branch-list").has(".shopwarning")) {
                    $("input[name='password']").parent(".branch-list").find(".shopwarning").remove();
                }
                var pass_word=$("input[name='password']").val();
                if (pass_word == "" || pass_word == null) {
                    var span = $("<div class='shopwarning'>请您输入密码！</div>");
                    $("input[name='password']").parent(".branch-list").append(span);
                }
                else if ($("input[name='password']").parent(".branch-list").has(".shopwarning")) {
                    {
                        $("input[name='password']").parent(".branch-list").find(".shopwarning").remove();
                    }

                }
            });
            $("input[name='contacter_email']").on("blur", function () {
                if ($("input[name='contacter_email']").parent(".branch-list").has(".shopwarning")) {
                    $("input[name='contacter_email']").parent(".branch-list").find(".shopwarning").remove();
                }
                var con_email=$("input[name='contacter_email']").val();
                //正则表达式验证邮箱
                var szReg = /^[A-Za-z0-9]+([-_.][A-Za-z0-9]+)*@([A-Za-z0-9]+[-.])+[A-Za-z0-9]{2,5}$/;
                var bChk = szReg.test(con_email);
                if (con_email == "" || con_email == null) {
                    var span = $("<div class='shopwarning'>请输入负责人邮箱!</div>");
                    $("input[name='contacter_email']").parent(".branch-list").append(span);
                }
                else if (!bChk) {
                    var span = $("<div class='shopwarning'>请按正确的邮箱格式输入邮箱!</div>");
                    $("input[name='contacter_email']").parent(".branch-list").append(span);
                }
                else if ($("input[name='contacter_email']").parent(".branch-list").has(".shopwarning")) {
                    {
                        $("input[name='contacter_email']").parent(".branch-list").find(".shopwarning").remove();
                    }

                }
            });
            $("input[name='contacter_QQ']").on("blur", function () {
                if ($("input[name='contacter_QQ']").parent(".branch-list").has(".shopwarning")) {
                    $("input[name='contacter_QQ']").parent(".branch-list").find(".shopwarning").remove();
                }
                var con_qq=$("input[name='contacter_QQ']").val();
                if (con_qq == "" || con_qq == null) {
                    var span = $("<div class='shopwarning'>请您输入QQ号！</div>");
                    $("input[name='contacter_QQ']").parent(".branch-list").append(span);
                }
                else if ($("input[name='contacter_QQ']").parent(".branch-list").has(".shopwarning")) {
                    {
                        $("input[name='contacter_QQ']").parent(".branch-list").find(".shopwarning").remove();
                    }

                }
            });
            $("input[name='shop_address_detail']").on("blur", function () {
                if ($("input[name='shop_address_detail']").parent(".branch-list").has(".shopwarning")) {
                    $("input[name='shop_address_detail']").parent(".branch-list").find(".shopwarning").remove();
                }
                var con_address=$("input[name='shop_address_detail']").val();
                if (con_address == "" || con_address == null) {
                    var span = $("<div class='shopwarning'>请您输入详细地址！</div>");
                    $("input[name='shop_address_detail']").parent(".branch-list").append(span);
                }
                else if ($("input[name='shop_address_detail']").parent(".branch-list").has(".shopwarning")) {
                    {
                        $("input[name='shop_address_detail']").parent(".branch-list").find(".shopwarning").remove();
                    }

                }
            });
            $("select[name='shop_province']").on("change", function () {
                if ($("select[name='shop_province']").parent(".branch-list").has(".shopwarning")) {
                    $("select[name='shop_province']").parent(".branch-list").find(".shopwarning").remove();
                }
                if($('.province-1 option:selected').val()==0){
                    var span = $("<div class='shopwarning'>请您选择输入省！</div>");
                    $("select[name='shop_province']").parent(".branch-list").append(span);
                }
                else if ($("select[name='shop_province']").parent(".branch-list").has(".shopwarning")) {
                    {
                        $("select[name='shop_province']").parent(".branch-list").find(".shopwarning").remove();
                    }

                }
            });
            $("select[name='shop_city']").on("change", function () {
                if ($("select[name='shop_city']").parent(".branch-list").has(".shopwarning")) {
                    $("select[name='shop_city']").parent(".branch-list").find(".shopwarning").remove();
                }
                if($('.city-1 option:selected').val()==0){
                    var span = $("<div class='shopwarning'>请您选择输入市！</div>");
                    $("select[name='shop_city']").parent(".branch-list").append(span);
                }
                else if ($("select[name='shop_city']").parent(".branch-list").has(".shopwarning")) {
                    {
                        $("select[name='shop_city']").parent(".branch-list").find(".shopwarning").remove();
                    }

                }
            });
            $("select[name='shop_district']").on("change", function () {
                if ($("select[name='shop_district']").parent(".branch-list").has(".shopwarning")) {
                    $("select[name='shop_district']").parent(".branch-list").find(".shopwarning").remove();
                }
                if($('.distinct-1 option:selected').val()==0){
                    var span = $("<div class='shopwarning'>请您选择输入区！</div>");
                    $("select[name='shop_district']").parent(".branch-list").append(span);
                }
                else if ($("select[name='shop_district']").parent(".branch-list").has(".shopwarning")) {
                    {
                        $("select[name='shop_district']").parent(".branch-list").find(".shopwarning").remove();
                    }

                }
            });

</script>
@endsection