// auth:zww
// date:2016.07.20
$(document).ready(function(){
	$.ajaxSetup({
        headers: {
       'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    
//调用微信JS api 支付
	function callpay(jsApiParameters){
	    wx.chooseWXPay({
	        timestamp: jsApiParameters.timestamp,
	        nonceStr: jsApiParameters.nonceStr, // 支付签名随机串，不长于 32 位
	        package: jsApiParameters.package, // 统一支付接口返回的prepay_id参数值，提交格式如：prepay_id=***）
	        signType: jsApiParameters.signType, // 签名方式，默认为'SHA1'，使用新版支付需传入'MD5'
	        paySign: jsApiParameters.paySign, // 支付签名
	        success: function (res) {
	            // 支付成功后的回调函数
	            //alert(JSON.stringify(res));
	            if(res.errMsg == "chooseWXPay:ok" ) {
	            	window.location.href = '/shop/order/index?unsend'; 
	             }else{
	             	window.location.href = '/shop/order/index?unpay';
				}
	        },
	        cancel: function(res){
                    $.alert("您已取消支付，请在下单后的1小时内完成支付", function() {
                      //点击确认后的回调函数
                      window.location.href = '/shop/order/index?unpay';
                    });
            },
            fail:function(res){
                $.alert("支付失败", function() {
                  //点击确认后的回调函数
                  window.location.href = '/shop/order/index?unpay';
                });
                
            }
    	});
	}
//定义初始变量
	var total = {};//存放选中商品id
	var coupon_id={};
	var key = new Array();//存放选中商品id
	var value=new Array();
	var arr1=new Array();
	var arr2=new Array();
	var arr3=new Array();
	var arr4=new Array();
	var Coupon=new Array();
	var message = {};
	var counts=new Array();
	var Money=new Array();
	var pay_money;
	var coupon_value;
	var pay_coupon=0;
//计算商品件数
	function obtainCount(){
		$(".submitshop_list").each(function(){
			$(this).children().children(".shop_list_content").each(function(){
				var count = $(this).children().children(".commodity-count").html().replace(/[^0-9]/ig,"");
				counts.push(count);		
			});
			var Count=0;
			for(var i=0;i<counts.length;i++){
				Count+=parseInt(counts[i]);
			}
			counts.length=0;
			$(this).children().children().children(".shop-count").html("共"+Count+"件商品 合计：");
		});
	}
	obtainCount();
//计算总金额和优惠券值
	$(".submitshop_list").each(function(){
		var shopName=$(this).children().children().children('a').children(".shopname").html();
		// var express=$(this).children().children().children(".express").html();
		if($(this).children('a').children().children(".coupon-sum").length){
			 coupon_value=$(this).children('a').children().children(".coupon-sum").html();
			 var couponID=$(this).children('a').children().children(".coupon-id").html();
		}else{
			coupon_value=0;
			var couponID=0;
		}
		Coupon.push(coupon_value);
		//合计一个店铺多个商品的合计金额
		var money= $(this).children().children().children(".total").html();//
		Money.push(money);
		//店铺名 金额
			key.push(shopName);
			value.push(money);
			if(key.length==value.length){
				for(var i=0;i<key.length;i++){
					total[key[i]]=parseFloat(value[i]-Coupon[i]).toFixed(2);
				}
			}
		//店铺名 优惠券id
			arr1.push(shopName);
			arr2.push(couponID);
			if(arr1.length==arr2.length){
				for(var i=0;i<arr1.length;i++){
					coupon_id[arr1[i]]=arr2[i];
				}
			}
		pay_money=0;
		for(var i=0;i<Money.length;i++){	
			pay_money= pay_money + parseFloat(Money[i]);
		}
		
	});
		// 计算优惠券总金额
		for(var i=0;i<Coupon.length;i++){
			pay_coupon+=(Coupon[i])*1;
		}
		pay_coupon=parseFloat(pay_coupon).toFixed(2);
	console.log(pay_coupon);
	$(".pay_coupon").html(pay_coupon);
	var final_money=parseFloat($(".express-total").html()-pay_coupon).toFixed(2);
	$(".express-total").html(final_money);
	var total_money = JSON.stringify(total);//{店铺名1：总金额1，店铺名2：总金额2，...}
	var coupon = JSON.stringify(coupon_id);//{店铺名1：优惠券1，店铺名2：优惠券2，...}
	var address_id=$(".submit-address").children(".address-id").html();
//点击结算
	$("#check").on('click',function(){
		$(".submitshop_list").each(function(){
			var Message=$(this).children().children("#commodity-message").val();
			var shopName=$(this).children().children().children('a').children(".shopname").html();
			//店铺名 留言
			arr3.push(shopName);
			arr4.push(Message);
			if(arr3.length==arr4.length){
				for(var i=0;i<arr3.length;i++){
					message[arr3[i]]=arr4[i];
				}
			}
		});
		var shop_message = JSON.stringify(message);//{店铺名1：留言1，店铺名2：留言2，...}
		$.ajax({
			type:'POST',
			url:'/shop/order/submit',
			data:{
				address_id:address_id,
				total:total_money,
				coupon_id:coupon,
				message:shop_message,
				pay_money:final_money
			},
			dataType:"json",
			success:function(result){
					if(result.status=='success'){ 
						//alert(result.msg);
						$('#coupon-collet-cover').show();
						$('body').css('overflow','hidden');
						$("#case-on-weixin").on('click',function(){
							//window.location.href = '/shop/order/pay/'+result.msg+'/'+pay_money;
							$('#coupon-collet-cover').hide();
							$('body').css('overflow','auto');
							$.ajax({
								type : 'get',
								url : '/shop/order/pay/'+result.msg+'/'+final_money+'/'+'0',
								dataType : 'json',
								success:function(data){
									//alert(data);
									if(data.status == 'success'){
										//预支付	
										callpay(data.jsApiParameters);

									}else{
										alert(data.msg);
									}
								}
							})
						});
						$("#cash-on-delivery").on('click',function(){
							//window.location.href = '/shop/order/pay';
							alert('暂不支持货到付款');
							$('#coupon-collet-cover').hide();
							$('body').css('overflow','auto');
						});
						$("#cancle-on").on('click',function(){
							$('#coupon-collet-cover').hide();
							$('body').css('overflow','auto');
							//window.location.href = '/shop/order/index?unpay';
						});
					}else{
						$.alert(result.msg,'');
					} 
			}              
		});
	});
})