	// auth:zww
	// date:2016.06.20
$(document).ready(function(){
	$.ajaxSetup({
        headers: {
       'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
	//初始化变量
	var choose_id=new Array();//存放选中商品id
	var dele_id=new Array();
	var number=new Array();
	var Price=new Array();
	var shop_money=new Array();
	var Array_count=new Array();//数量
	var Array_price=new Array();//单价
	var Array_id=new Array();
	var Count;
	var money;
	//初始化 未选中
	$('.shopCat_title').find('.shopcart-logo input').iCheck('uncheck');
	$('.shopCat_content').find('.shopcart-content input').iCheck('uncheck');
	$('#shopcart-btn').find('.shopcart-all input').iCheck('uncheck');
	//如果购物车内没有商品，显示提示信息
	if($(".shopCat_list").length==0){
		$("#shopcart-btn").css("display","none");
		$("#prompt-message").css("display","block");
		$(".shopCat").css("margin-bottom","0");
	}
	//icheck
	 $("input[type='checkbox']").iCheck({
	    checkboxClass: 'icheckbox_minimal-orange',
	    //radioClass: 'iradio_minimal',
	    increaseArea: '0%' // optional
	  });

	/*function hutname(){
		$(".shopCat_title").each(function(){
			var shopname=$(this).parents().children().children(".commodity-name").html();
			$(this).children().children(".shop-name").html(shopname);	
		});
	}*/
	//hutname();

	function CheckAll(){
		if(choose_id.length==$('.shopCat').find('.shopcart-content').length){
			$('#shopcart-btn').find('.shopcart-all input').iCheck('check');
		}
	}
	//单个删除商品，当该店里的商品均删除后，店铺名title栏消失
		if($(".shopCat_content").length==0){
				$(this).parents(".shopCat_list").css("display","none");
		}
	//计算金额公共函数
	function result(){
		var Count=0;
		for(var i=0;i<number.length;i++){
			Count+=parseInt(number[i]);
		}
		var money=0;
		for(var j=0;j<Price.length;j++){
			shop_money[j]=parseFloat(number[j]*Price[j]);
			money+=parseFloat(shop_money[j]);
		}
		$(".check-money").html("结算("+Count+")");
		$(".total-money").html(money.toFixed(2));
	}
	//店铺里选择单个商品
	$(".shopcart-content input").on('ifClicked',function(){
		//icheck 点击之后才变状态的
		//选中单个商品
		var id=$(this).parents().children(".commodity-id").html();
		var count=$(this).parents('.shopcart-content').siblings('#content_detail').children(".number").html().replace(/[^0-9]/ig,"");
		var price_single=$(this).parents('.shopCat_content').children(".commodity-price").html();
		if($(this).is(":checked")){
			//点击前是 checked，即取消选中
			//判断当前店铺是否还有其它被选中
			var shop_check_count = $(this).closest('.shopCat_list').find('.shopcart-content .checked input').length-1;
			$(this).closest('.shopCat_list').find('.shopcart-logo input').iCheck('uncheck');
			var cart_check_count = $('.shopcart-content .checked input').length-1;
			if(cart_check_count == 0){
				$('.shopcart-all input').iCheck('uncheck');
			}
			var cart_index = choose_id.indexOf(id);
			if(cart_index >= 0){
				choose_id.splice(cart_index,1);
				number.splice(cart_index,1);
				Price.splice(cart_index,1);
			}
			$('#shopcart-btn').find('.shopcart-all input').iCheck('uncheck');	
			result();	
		}else{
			//选中
			choose_id.push(id);
			number.push(count);
			Price.push(price_single);			
			result();
			var checked_len = $(this).closest('.shopCat_list').find('.shopcart-content .checked input').length+1;
			var total_len = $(this).closest('.shopCat_list').find('.shopcart-content input').length;
			if(checked_len==total_len){
				$(this).closest('.shopCat_list').find('.shopcart-logo input').iCheck('check');
			}		
		}
		
		if(choose_id.length==0){
			$('.check-money').addClass('disabled');
		}else{
			$('.check-money').removeClass('disabled');
		}
		CheckAll();
	});
	//单选一个店铺里的所有商品
	$(".shopcart-logo input").on('ifClicked',function(){
			if($(this).is(':checked')){
				//取消选中
				$(this).parents().children(".shopCat_content").each(function(){
					var id=$(this).children('.commodity-id').html();
					var count=$(this).children().children(".number").html().replace(/[^0-9]/ig,"");
					var price_single=$(this).children('.commodity-price').html();
					if($(this).children(".shopcart-content").find('input').is(':checked')){
						$(this).children(".shopcart-content").find('input').iCheck('uncheck');										
						var cart_index = choose_id.indexOf(id);
						choose_id.splice(cart_index,1);
						number.splice(cart_index,1);
						Price.splice(cart_index,1);
					}	
				});
				
				//是否要取消全选
				var cart_check_count = $('.shopcart-content .checked input').length-1;
				if(cart_check_count == 0){
					$('.shopcart-all input').iCheck('uncheck');
				}
				result();			
			}else{
				//选中	
				$(this).parents().children(".shopCat_content").each(function(){
					//$(this).children(".shopcart-content").find('input').iCheck('check');
					var id=$(this).children('.commodity-id').html();
					var count=$(this).children().children(".number").html().replace(/[^0-9]/ig,"");
					var price_single=$(this).children('.commodity-price').html();
					if($(this).children(".shopcart-content").find('input').is(':checked')){
					}else{
						$(this).children(".shopcart-content").find('input').iCheck('check');						
						number.push(count);
						Price.push(price_single);
						choose_id.push(id);
					}
					console.log(count);
					result();
				});
				
			}
			if(choose_id.length==0){
				$('.check-money').addClass('disabled');
			}else{
				$('.check-money').removeClass('disabled');
			}
			CheckAll();	
	});
	//全选
	$(".shopcart-all input").on('ifClicked',function(){
		number.length=0;
		Price.length=0;
		choose_id.length=0;
			if($(this).is(':checked')){
				//取消选中					
				$(".shopCat_content").each(function(){
					$(".shopcart-logo input").iCheck('uncheck');
					var id=$(this).children(".commodity-id").html();
					var count=$(this).children().children(".number").html().replace(/[^0-9]/ig,"");
					var price_single=$(this).children('.commodity-price').html();
					if($(this).children(".shopcart-content").find('input').is(':checked')){
						$(this).children(".shopcart-content").find('input').iCheck('uncheck');						
						var cart_index = choose_id.indexOf(id);
						choose_id.splice(cart_index,1);
						number.splice(cart_index,1);
						Price.splice(cart_index,1);
					}							
				});
				result();					
			}else{
				$(".shopCat_content").each(function(){
					$(".shopcart-logo input").iCheck('check');
					$(".shopcart-content input").iCheck('check');
					var id=$(this).children(".commodity-id").html();
					var count=$(this).children().children(".number").html().replace(/[^0-9]/ig,"");
					var price_single=$(this).children('.commodity-price').html();
					number.push(count);
					Price.push(price_single);
					choose_id.push(id);
				});
				
				result();
			}
			if(choose_id.length==0){
				$('.check-money').addClass('disabled');
			}else{
				$('.check-money').removeClass('disabled');
			}
			CheckAll();
	});
	//编辑、完成时调用重新计算数量、金额函数
	function paysubmit(){
		var money=0;
		var Count=0;
		$(".shopcart-content").each(function(){
			var id=$(this).parents().children(".commodity-id").html();
				var count=$(this).parents().children().children(".number").html().replace(/[^0-9]/ig,"");
				var price_single=$(this).parent().children(".commodity-price").html();
			if($(this).find('input').is(':checked')){
				if(choose_id.indexOf(id) >= 0){
					var b = choose_id.indexOf(id);
					choose_id.splice(b,1);
					number.splice(b,1);
					Price.splice(b,1);
				}
				choose_id.push(id);
				number.push(count);
				Price.push(price_single);
						
			}else{
				var a = choose_id.indexOf(id);
				if(a>=0){
					choose_id.splice(a,1);
					number.splice(a,1);
					Price.splice(a,1);
				}
			}					
		});	
		for(var i=0;i<number.length;i++){
				Count+=parseInt(number[i]);
		}

		for(var j=0;j<Price.length;j++){
			shop_money[j]=parseFloat(number[j]*Price[j]);
			money+=parseFloat(shop_money[j]);
		}
		$(".check-money").html("结算("+Count+")");
		$(".total-money").html(money.toFixed(2));				
	}
	//领取优惠券 
	$(".coupon").on('click',function(){
		var shop_id = $(this).siblings('.shop_id').html();
		console.log(shop_id);
		$('.coupon-list').each(function(){
			var coupon_shop_id = $(this).children('.coupon-shop-id').html();
			console.log(coupon_shop_id);
			if(shop_id==coupon_shop_id){
				$(this).css('display','block');
			}else if(coupon_shop_id==0){
				$(this).css('display','block');
			}else{
				$(this).css('display','none');
			}
		});
		$('#coupon-collet-cover').fadeIn('fast',function(){
			$("#coupon-collet").show('fast',function(){
				var top = $(window).outerHeight() - $("#coupon-collet").outerHeight();
				$("#coupon-collet").animate({'top':top});
			})
		});
		$('body').css('overflow','hidden');
	});
	$(".coupon-bottom").on('click',function(){
		$('body').css('overflow','auto');
		$("#coupon-collet").animate({'top':'100%'},function(){
			$("#coupon-collet").hide('fast',function(){
				$('#coupon-collet-cover').hide();
			})
		});
		
	});
	$(".coupon-deal").on('click',function(){
		var id=$(this).parents().children('.coupon-id').html();
		$.ajax({
			type:'POST',
			url:'/shop/coupon/collect',
		 	data:{
				coupon_id:id
			   },
			 dataType:"json",
			 success:function(result){ 
			 	console.log(result);
					  if(result.status=='success'){  							
							$('body').css('overflow','auto');
							$("#coupon-collet").animate({'top':'100%'},function(){
								$("#coupon-collet").hide('fast',function(){
									$('#coupon-collet-cover').hide();
									$.toast(result.msg);
								})
							});
						 }
					if(result.msg=='login'){
						$("#coupon-collet").animate({'top':'100%'},function(){
								$("#coupon-collet").hide('fast',function(){
									$('#coupon-collet-cover').hide();
									$.confirm("您还没登录,请先登录", function() {
										  //点击确认后的回调函数
										  window.location.href = "/shop/auth/login"
										  }, function() {
										  //点击取消后的回调函数
										  
										  });
								})
							});
						
					}
					if(result.status=='error'){						 	
						 	//window.location.href="/shop/auth/login";
						 	//$.toast(result.status, "cancel");
						 	$("#coupon-collet").animate({'top':'100%'},function(){
								$("#coupon-collet").hide('fast',function(){
									$('#coupon-collet-cover').hide();
									$.toast(result.msg, "cancel");
								})
							});
						 }   	
			 }               
		});
	});
	//编辑完成的切换
	$(".edit").on('click',function(){
		var btnObj = $(this);
		var status=$(this).html();
		if(status=="编辑"){
			$(this).parents().parents().parents().children().children().children(".spinner").css("display","block");
			$(this).html("完成");
			$(this).parents().parents().parents().children().children(".edit-delete").css("display","block");
		}else{
			$('.loading').show();
			var inputItem = $(this).closest('.shopCat_list').find('.spinner input');
			var IdArr = {};
			$.each(inputItem,function(key,item){
				$(this).parents('.spinner').siblings('.number').html('x'+$(this).val());
				var a = $(this).closest('.shopCat_content').find('.commodity-id').html();
				var b = $(this).val();
				IdArr[key]= {'id':a,'count':b};
			});
			$.ajax({
				type:'POST',
				url:'/shop/shopcart/changecount',
			    data:{
					cart:IdArr,
			    },
			    dataType:"json",
			    success:function(result){
			    	$('.loading').hide(); 
		  			if(result.status=='success'){ 
			  			btnObj.parents().closest('.shopCat_list').find(".spinner").css("display","none");
						btnObj.html("编辑");
						btnObj.parents().closest('.shopCat_list').find(".edit-delete").css("display","none");
						paysubmit(); 
			 		}else{
					 	$.toast(result.msg, "cancel");
			 		}    
				}               
			});
			
		}		
	});
	//编辑单个删除商品
	$('.shopCat').on("click", ".edit-delete", function() {
		 	var commodity_id=$(this).parents().children(".commodity-id").html();
		 	dele_id.push(commodity_id);
		 	var obj=$(this);
		 	
			$.confirm("确认要将这一个宝贝删除吗？", "", function() { 
				$('.loading').show();
			  //确认删除
				$.ajax({
					type:'POST',
					url:'/shop/shopcart/delete',
					data:{
						cart_id:dele_id
					},
					dataType:"json",
				    success:function(data){
				    	$('.loading').hide();
				    	if(data.status=='success'){ 
							$.toast("删除成功!"); 
							obj.parents(".shopCat_content").remove();
							var cart_index = choose_id.indexOf(dele_id);
							choose_id.splice(cart_index,1);
							number.splice(cart_index,1);
							Price.splice(cart_index,1);	
							result();
							if(choose_id.length==0){
								$('.check-money').addClass('disabled');
							}else{
								$('.check-money').removeClass('disabled');
							}
							//如果购物车内没有商品，显示提示信息
							if($(".shopCat_content").length==0){
								$('.shopCat_list').hide();
								$("#shopcart-btn").css("display","none");
								$("#prompt-message").css("display","block");
								$(".shopCat").css("margin-bottom","0");
							}
						}else{
							 $.toast(data.msg, "cancel");
						}  
					}              
					});			
			}, function() {
			//取消删除
				});

	});
	//全部编辑
	$(".edit-all").on('click',function(){
		var btnObj = $(this);
		var status=$(this).html();
		if(status=="编辑"){
			$(this).html("完成");
			$(".spinner").css("display","block");
			$(".spinner").css("display","block");
			$("#storeDet").css("display","none");
			$("#storeDet").children('.edit').html('编辑');
			$('.edit-delete').css("display","none");
			$(".price").css("display","none");
			$(".spinner input[type='text']").css("width","120px")
			$(".sum").css("display","none");
			$(".number").css("display","none");
			$(".describe").css("display","none");
			$(".check-money").css("display","none");
			$(".delete-all").css("display","block");

		}else{
			$('.loading').show();
			var inputItem = $('.shopCat').find('.spinner input');
			var IdArr = {};
			$.each(inputItem,function(key,item){
				$(this).parents('.spinner').siblings('.number').html('x'+$(this).val());
				var a = $(this).closest('.shopCat_content').find('.commodity-id').html();
				var b = $(this).val();
				IdArr[key]= {'id':a,'count':b};
			});
			$.ajax({
				type:'POST',
				url:'/shop/shopcart/changecount',
			    data:{
					cart:IdArr,
			    },
			    dataType:"json",
			    success:function(result){ 
			    	$('.loading').hide();
			  			if(result.status=='success'){ 
			  			//$.toast(result.status);
				  			btnObj.html("编辑");
							$(".spinner").css("display","none");
							$(".spinner input[type='text']").css("width","70px")
							$("#storeDet").css("display","block");
							$(".price").css("display","block");
							$(".sum").css("display","block");
							$(".number").css("display","block");
							$(".describe").css("display","block");
							$(".check-money").css("display","block");
							$(".delete-all").css("display","none");
							paysubmit();
				 		}else{
						 	$.toast(result.msg, "cancel");
				 		}    
				}               
			});		
		}
	});
	//编辑多个删除商品
	$('.delete-all').on("click", function() {
		$.confirm("确认要将宝贝删除吗？", "", function() { 
			$('.loading').show();
		  //确认删除
				$.ajax({
					type:'POST',
					url:'/shop/shopcart/delete',
				    data:{
						cart_id:choose_id
				    },
					dataType:"json",
					success:function(result){
						$('.loading').hide();
						if(result.status=='success'){ 
							$.toast("删除成功!"); 
							window.location.reload();		
						}else{
						 	$.toast(result.msg, "cancel");
						}  
					}              
				});			
			}, function() {
			//取消删除
				});
	});
	//点击结算
	$(".check-money").on('click',function(){
		if($('.shopCat_title').find('.edit').html()=='完成'){
			$('.shopCat_title').find('.edit').click();
		}
		if(choose_id.length==0){
			$('.check-money').addClass('disabled');
		}else{
			$('.loading').show();
			$('.check-money').addClass('disabled');
			$.ajax({
				type:'POST',
				url:'/shop/shopcart/submit',
				data:{
				cart_array:choose_id	
					}, 
				dataType:"json",
				success:function(result){
					$('.loading').hide();
						if(result.status=='success'){  
								window.location.href = result.msg;
						 }else{
						 	$('.check-money').removeClass('disabled');
						 	$.alert(result.msg,'');
						 	//$.toast(result.msg,"cancel");
						 } 
				}	    	  
			});
		}
	});
	//数量加减

/*	$(".spinner-minus").on('click',function(){
					var ID=$(this).parents().parents().parents().children('.commodity-id').html();		
					var commodity_count=$(this).parent().children("#count").val();
						$(this).parent().parent().children(".number").html("x"+parseInt(commodity_count-1)+"");					
					var value = $(this).parent().parent().children(".number").html().replace(/[^0-9]/ig,"");
						
							$.ajax({
											type:'POST',
											url:'/shop/shopcart/changecount',
										   data:{
												cart_id:ID,
												count:value
										   },
										  dataType:"json",
										  success:function(result){
												if(result.status=='success'){ 
												//$.toast(result.msg);
												commodity_count=commodity_count-1;  
											 }else{
											 	$.toast(result.msg, "cancel");
											 	
											 } 
										}               
								});
					
		});
	$(".spinner-plus").on('click',function(){
					var ID=$(this).parents().parents().parents().children('.commodity-id').html();
					var commodity_count=$(this).parent().children("#count").val();
						$(this).parent().parent().children(".number").html("x"+parseInt(commodity_count*1+1)+"");
					var value = $(this).parent().parent().children(".number").html().replace(/[^0-9]/ig,"");
					
					$.ajax({
						type:'POST',
						url:'/shop/shopcart/changecount',
					   data:{
							cart_id:ID,
							count:value
					   },
					  dataType:"json",
					  success:function(result){ 
					  			if(result.status=='success'){ 
					  			//$.toast(result.status);
					  			commodity_count=(commodity_count)*1+1; 
						 		}else{
								 	$.toast(result.msg, "cancel");
						 		}    
						}               
					});			
		});*/
	$(".spinner").spinner('changing', function(e, newVal, oldVal) {
			// trigger lazed, depend on delay option.
			var quantity=$(this).closest('#content_detail').children(".quantity").html();
			if(newVal==1){
				$(this).siblings('.spinner-minus').addClass('disabled');
			}else{
				$(this).siblings('.spinner-minus').removeClass('disabled');
			}
			/*if(newVal>=quantity){
				$(this).siblings('.spinner-plus').addClass('disabled');
			}else{
				$(this).siblings('.spinner-plus').removeClass('disabled');
			}*/
	});
})