// auth:zww
// date:2016.07.20
$(document).ready(function(){
//选择优惠券
$.ajaxSetup({
        headers: {
       'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
   var shopname,total,couponObj;
	// $("#usecoupon").on('click',function(){
	// 	couponObj = $(this);
	// 	shopname = $(this).siblings('#shop_list').find('.shopname').html();
	// 	total = $(this).siblings('#information').find('.total').html();
	// 	total = total.replace('￥','');

	// 	$('#coupon-choose-cover').fadeIn('fast',function(){
	// 		$("#coupon-choose").show('fast',function(){
	// 			var top = $(window).outerHeight() - $("#coupon-choose").outerHeight();
	// 			$("#coupon-choose").animate({'top':top});
	// 		})
	// 	});
	// 	$('body').css('overflow','hidden');
	// });
	// $(".coupon-bottom").on('click',function(){
	// 	$('body').css('overflow','auto');
	// 	$("#coupon-choose").animate({'top':'100%'},function(){
	// 		$("#coupon-choose").hide('fast',function(){
	// 			$('#coupon-choose-cover').hide();
	// 		})
	// 	});
	// });
	//提交订单使用优惠券
	$(".coupon-use").on('click',function(){
		var condition = parseFloat( $(this).siblings('.coupon-use-condition').children('span').html() ) ;
		var coupon_value = parseFloat( $(this).siblings('.coupon-value').children('span').html() );
		if($(this).hasClass('disabled')){
		}else{
			var id = $(this).siblings('.coupon-id').html();
			var shopname = $(this).siblings('.shopname').html();
			var total = $(this).siblings('.total').html();
			$.ajax({
				'type':'post',
				url:'/shop/coupon/choose',
				data:{
					'coupon_id':id,
					'shopname':shopname,
					'total':total
				},
				success:function(data){
					if(data.status == 'success'){
						window.location=document.referrer;
					}else{
						if(data.msg=='login'){
							$.confirm("您还没登录,请先登录", function() {
										  //点击确认后的回调函数
										  window.location.href = "/shop/auth/login"
										  }, function() {
										  //点击取消后的回调函数
										  
										  });
						}else{
							$.alert(data.msg,'');
						}
						
					}
				}
			})
		}		
	});
	//优惠券不满足条件 提示信息
	if($(".coupon_error").length!=0){
		var error=$(".coupon_error").html();
		$.toast(error,"cancel");
	}
	//优惠券中心
	$(".coupon-take").on('click',function(){
		var id=$(this).siblings('.coupon-id').html();
		var obj=$(this);
		$.ajax({
			type:'POST',
			url:'/shop/coupon/collect',
		 	data:{
				coupon_id:id,
				coupon_from:'coupon'
			   },
			 dataType:"json",
			 success:function(result){ 
					 if(result.status=='success'){  			
							$.toast("领取成功");
							window.location.reload();
					}
					if(result.status=='error'){
						if(result.msg=='login'){
							window.location.href="/shop/auth/login";
						}else{
							$.alert(result.msg,"");
						}
					}
			}  	             
		});
	});
	//领取优惠券
	$(".use-description-image").on('click',function(){
 		var description = $(".use-coupon-description").html();
 		$.alert(description,"优惠券使用说明");
 	});
})