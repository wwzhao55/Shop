<!-- auth:zww -->
<!-- time:2016.09.02 -->
@extends('layouts.app')
@section('siderbar')
@include('layouts.siderbar')
@endsection
@section('addCss')
<link rel="stylesheet" type="text/css" href="{{ URL::asset('shop/css/brandmanage.css')}}">
<link rel="stylesheet" type="text/css" href="{{ URL::asset('shop/css/timepicki.css')}}">
@endsection
@section('content')
    <div class="addBranch">
        <form action='/Brand/shopmanage/edit' method="post" enctype="multipart/form-data" id='form'>
             {!! csrf_field() !!}
            <div class="page-head">分店信息维护
                <a href="/Brand/shopmanage">
                    <input value="返回" type="button" class="add-return">
                </a>
            </div>
            <div>
                {{Session::get('message')}}
            </div> 
            <div class="branch-left">
                <input type="text" name="shop_id" value="{{$shop->id}}" hidden>
                    <div class="branch-top {{ $errors->has('shopname') ? ' has-error' : '' }}">
                        <span>分店名 :</span>
                        <div class='shop-name'>{{$shop->shopname}}</div>
                    </div>
                    <div class="branch-list {{ $errors->has('created_at') ? ' has-error' : '' }}">
                        <span>开通时间 :</span>                    
                        <div class='created_at'>{{$shop->created_at}}</div>

                    </div>
                    <div class="branch-list">
                        <span>地址 :</span>
                        <select  class="distinct-1 padding-left" name="shop_district"  value=""> </select>
                        <select  class="city-1 padding-left" name="shop_city"  value="">  </select>
                        <select  class="province-1 padding-left" name="shop_province"  value="">  </select>   
                    </div>
                    <div class="branch-list {{ $errors->has('shop_address_detail') ? ' has-error' : '' }}">
                        <input type="text" placeholder="详细地址"   name="shop_address_detail" value="{{$shop->shop_address_detail}}">    
                            @if ($errors->has('shop_address_detail'))
                                            <span class="help-block">
                                                <strong>*{{ $errors->first('shop_address_detail') }}</strong>
                                            </span>
                                @endif
                    </div>
                    <div class="branch-list {{ $errors->has('customer_service_phone') ? ' has-error' : '' }}">
                        <span>客服电话 :</span>
                        <input type="text" placeholder="电话："   name="customer_service_phone" value="{{$shop->customer_service_phone}}">    
                                @if ($errors->has('customer_service_phone'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('customer_service_phone') }}</strong>
                                                </span>
                                @endif 
                    </div>
                    <div class="branch-list ">
                        <span>营业时间 :</span>
                        <input type="text" readonly  class='worktime' name="close_at" placeholder="请选择" value='{{$shop->close_at}}' id='end-time'>   
                        <span class='zhi'>至</span> 
                        <input type="text" readonly  class='worktime' name="open_at" placeholder="请选择" value='{{$shop->open_at}}' id='start-time'>
                    </div>
                    <div class="branch-list {{ $errors->has('special') ? ' has-error' : '' }}">
                        <span>商家推荐 :</span>
                        <!-- <input type="text"   class='customer_like' name="customer_like" value="推荐商品，商家福利">  -->
                        <textarea class='customer_like' type="text" placeholder="推荐商品，商家福利"  name="special"  style="resize:none">{{$shop->special}}</textarea>   
                                @if ($errors->has('special'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('special') }}</strong>
                                                </span>
                                @endif 
                    </div>
                    <div class="branch-list {{ $errors->has('shoplogo') ? ' has-error' : '' }}">
                        <span>店铺图片:</span>   
                        <div class='right'>
                            <input type="file"  name="shoplogo" class='img' style='display:none'> 
                            <input type="text"  name="logo_changed" value='0' style='display:none' class='logo_changed'>
                            @if ($shop->shoplogo)
                                <img src='{{asset($shop->shoplogo)}}' class='img-list' style="cursor:pointer;">
                            @else
                                <img src="{{asset('shopstaff/images/add.png')}}" class='add-img' style="cursor:pointer;">
                                <img src='' class='img-list' hidden style="cursor:pointer;">
                            @endif
                        </div>
                                @if ($errors->has('shoplogo'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('shoplogo') }}</strong>
                                                </span>
                                @endif 
                    </div>       
            </div>
            <div class="clearfix" style="clear:both;"></div>

            <div id="brand-bottom" style="border:none;">
                    <a href="javascript:history.go(-1)"><img src="{{asset('shop/images/brandmanage/btn-return.png')}}" class="return"></a>
                    <input type="image" src="{{asset('shop/images/brandmanage/btn-determine.png')}}" class="confirm" >
            </div>
        </form>
    </div>
<script src="{{asset('shop/js/timepicki.js')}}"></script>    
<script type="text/javascript">
    $('#menu-lists-parent').find('.onsidebar').removeClass('onsidebar');
    $('.shopmanage').addClass('onsidebar');
    $("#start-time").timepicki();
    $("#end-time").timepicki();
    $('.add-img').on('click',function(){
        $('.img').click();
    });
    $('.img').on('change',function(){
        var objUrl = getObjectURL(this.files[0]) ;
        if(objUrl){
            $('.add-img').css('display','none');
            $('.img-list').attr('src',objUrl);
            $('.img-list').css('display','block');
            $('.logo_changed').val(1);
        }else{
            $('.logo_changed').val(0);
        }
        alert($('.logo_changed').val());
    });
    $('.img-list').on('click',function(){
        $('.img').click();
        // $('.logo_changed').val(1);
    });
    //建立一個可存取到該file的url
    function getObjectURL(file) {
            var url = null ; 
            if (window.createObjectURL!=undefined) { // basic
                url = window.createObjectURL(file) ;
            } else if (window.URL!=undefined) { // mozilla(firefox)
                url = window.URL.createObjectURL(file) ;
            } else if (window.webkitURL!=undefined) { // webkit or chrome
                url = window.webkitURL.createObjectURL(file) ;
            }
                return url ;
    } 
    $(function () {
            var province_html = "";
            $.each(pdata,function(idx,item){
                if (parseInt(item.level) == 0) {
                    province_html += "<option value='" + item.names + "' exid='" + item.code + "'>" + item.names + "</option>";
                }
            });
            $(".province-1").append(province_html);
            $(".province-1").val("{{$shop->shop_province}}");
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
            //绑定
            $(".province-1").change();
            $(".city-1").val("{{$shop->shop_city}}");
            $(".city-1").change();
            $(".distinct-1").val("{{$shop->shop_district}}");
    }); 
    $('.confirm').on('click',function(){
        $('#form').submit();
    });
</script>
@endsection