  <!-- auth:zww
	 date:2016.08.08 
-->
<!--申请退货-->
@extends('layouts.shop')

@section('title')
<title>申请退货</title>
@stop
@section('addCss')
<link href="{{ URL::asset('shop/css/refund.css')}}" rel="stylesheet">
@stop
@section('content')
	<div id="order-refund">
		<!-- 商品信息 -->
		 @foreach($order->commoditys as $content)
		<div class="order-list">
			<div class="order-list-content">
				<img src="{{ asset($content->main_img) }}" class="order-list-content-image">
				<div class="order-list-content-title">
					<div class="order-list-content-name">{{$content->commodity_name}}</div>
					@if($content->commodity_sku!=null)
                            @foreach($content->commodity_sku as $key=>$value)
                               <div class="order-list-content-sku">{{$key}}:{{$value}}</div>
                            @endforeach
                     @endif                       
                    <div class="order-list-content-value">￥{{$content->price}}</div>
				</div>
				<div class="order-list-amount">x{{$content->count}}</div>
			</div>
			<img src="{{asset('/shop/images/myorder/contacter.png')}}" class="contacter-buyness">					
		</div>
		@endforeach
		<div class="order-list-value" id="value-blank">
			<div class="order-list-value-summary" id="line-top">
				<span class="money-left">合计</span>
				<!-- <span class="money-right">￥{{$order->total}}</span> -->
				<span class="money-right">￥<span class="total-money"></span></span>
			</div>				
		</div>
		<!-- 优惠信息 -->
		@if($order->coupon_id)
			<div class="coupon-list-value">
				<div class="coupon-list-value-summary">
					<span class="money-coupon">优惠:使用优惠券满￥{{$order->coupon_use_condition}}减￥{{$order->coupon_sum}}</span>
				</div>
			</div>
		@endif	
		<!-- 支付信息 -->
		@if($order->coupon_id)
			<div class="express-way-moneydetail">￥<span class="total-money"></span>—￥{{$order->coupon_sum}}(优惠)
				<div class="real-pay">实付：￥<span class="real-paymoney">{{$order->total}}</span></div>
			</div>
		@else
			<div class="express-way-moneydetail">￥<span class="total-money"></span>—￥0.00(优惠)
				<div class="real-pay">实付：￥<span class="real-paymoney">{{$order->total}}</span></div>
			</div>
		@endif
		<!-- 退货理由描述 -->
		@if($order->status==6)
		<div class="refund-description">
				<span class="problem-description">问题描述</span>
					<input type="text" name="order_id" value="{{$order->id}}" hidden>
					<input class="description-input" type="text" value='{{$order->refund_info}}' hidden>
					<textarea class="description-out" type="text" value='' style="resize:none;"; disabled="disabled"></textarea>
					<span class="upload-image" >上传图片</span>
					<div class="imgshow-container">
						<span class="img-lists">
							@foreach($order->refund_imgs as $img)
							<img src='{{asset($img)}}' class='img_list'>
							@endforeach
						</span>
					</div>
		</div>
		@else		
		<div class="refund-description">
				<span class="problem-description">问题描述</span>
				<form action='/shop/order/refund' method="post" enctype="multipart/form-data" id="form">
					<input type="hidden" name="_token" value="{{ csrf_token() }}">
					<input type="text" name="order_id" value="{{$order->id}}" hidden>
					<textarea class="description-box" type="text" placeholder="请您在此详细描述问题"  name="description" vlaue='' style="resize:none";></textarea>
					<span class="upload-image" >上传图片</span>
					<div class="imgshow-container">
						<span class="img-lists"></span>
						<div class="img-box" id="img-box">							
							<img src="{{asset('/shop/images/myorder/add-2.jpg')}}" class="add-img" />
							<span class='imgupload-description-1'>上传凭证</span>
							<span class='imgupload-description-2'>(最多三张)</span>							
						</div>
					</div>
				</form>
		</div>
		<div class="submit-refund">
			<span class='submit-refund-word'>提交申请</span>
		</div>
		@endif
		<div class='clearfix'></div>
	</div>
@stop
@section('addJs')
<script src="{{asset('shop/js/orderRefund.js')}}"></script>
<script type="text/javascript">
		$(".order-list").on("click", ".contacter-buyness", function() {
            
                  window.location.href="tel:{{$contacter}}";
        });
		var s=0;
        $('.add-img').on('click',function(){
            var input_1="<input type='file' class='image' id='"+s+"'name='img[]' style='display:none'>";
            $('.img-lists').append(input_1);
            $('.img-lists').children('.image').eq(s).click();
            s++;
            console.log(s);
            if(s==4){
                s=0;
            }
        }); 
        var i=0;
        $(".img-lists").on('change','.image',function(){
            var objUrl = getObjectURL(this.files[0]) ;
            if (objUrl&&i<4){ 
                i++;
                var img_list="<img src='"+objUrl+"' class='img_list' />";
                var img_delete="<img class='img-delete' src='{{asset('/shop/images/myorder/De@2x.png')}}' />";
                $('.img-lists').append(img_delete).append(img_list);    
            }
            if(i==3){
                $('.img-box').css('display','none');
            }
        }) ;
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
        //删除图片
        // var obiect=document.getElementById('img-box').getElementsByTagName('input');
        var k=0;
        $('.img-lists').on('click','.img-delete',function(){
        	i--;
        	s--;
            if($(".img-list").length<3){
                $('.img-box').css('display','block');
            }
            $(this).prev(".image").css('display','block');
            $(this).prev(".image").remove();
            $(this).next(".img_list").remove();
            $(this).remove();
            // obiect[0].remove();
        }); 
        console.log(s);
        $('.submit-refund-word').on('click',function(){
            $('#form').submit();
        });
        $('.description-out').html($('.description-input').val());
</script>
@stop