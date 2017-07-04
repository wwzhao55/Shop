// auth:zww
// date:2016.08.04
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
                    if(res.errMsg == "chooseWXPay:ok" ) {
                        window.location.href = '/shop/order/index?unsend'; 
                     } else{
                        window.location.href = '/shop/order/index?unpay';
                    }          
                },
                cancel: function(res){
                    $.alert("您已取消支付，请在下单1小时内支付订单", function() {
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
    //订单页js开始
    	var type=document.getElementById('shoporder-select').getElementsByTagName('span');
        var mystatus=document.getElementsByName('status');
        var myselectimg1=document.getElementsByName('order-select-img1');
        var myselectimg2=document.getElementsByName('order-select-img2');
        var f1,f2,f3,test="all",a=0,b=0,c=0,d=0,e=0;
        var noorder=document.getElementById('no-order');
        var commoditylists=$('.commodity-lists');
        var linetext=document.getElementsByName('line-text');
        var allPay=document.getElementsByName('allpay');
        var id = new Array();
        var money=0;

        if(mystatus.length==0){
            noorder.style.display='block';
            commoditylists.css('display','block');
            linetext[0].style.display='block';
        }
        //切换分类
            type[0].onclick=function(){
            	f1=0;test="all";
            	$(this).parents("#shoporder-select").find(".onorder").removeClass('onorder');
                $(this).addClass("onorder");
                noorder.style.display='none';
                commoditylists.css('display','none');
                allPay[0].style.display='none';
                linetext[0].style.display='none';
            	$('.order-select-img1').css('display','none');
            	$('.order-select-img2').css('display','none');
            	for(var i=0;i<mystatus.length;i++){
                    mystatus[i].parentNode.parentNode.style.display='block';
                    a++;            
            	}
            	if(mystatus.length==0||f1==mystatus.length){
                    noorder.style.display='block';
                    commoditylists.css('display','block');
                    linetext[0].style.display='block';
        		}
            }
            type[1].onclick=function(){
            	f1=0;f2=0;f3=0;test="unpay";
            	$(this).parents("#shoporder-select").find(".onorder").removeClass('onorder');
                $(this).addClass("onorder");
                noorder.style.display='none';
                commoditylists.css('display','none');
                allPay[0].style.display='none';
                linetext[0].style.display='none';
            	$('.order-select-img1').css('display','block');
            	$('.order-select-img2').css('display','none');
            	for(var i=0;i<mystatus.length;i++){
            		mystatus[i].parentNode.parentNode.style.display='block';
            		if(mystatus[i].innerHTML!="等待买家付款"){
                        mystatus[i].parentNode.parentNode.style.display='none';
                        f1++;
            		}else{
                        b++;
                    }
            	}
            	if(mystatus.length==0||f1==mystatus.length){
                    noorder.style.display='block';
                    commoditylists.css('display','block');
                    linetext[0].style.display='block';
        		}
            }
            type[2].onclick=function(){
            	f1=0;test="unsend";
            	$(this).parents("#shoporder-select").find(".onorder").removeClass('onorder');
                $(this).addClass("onorder");
                noorder.style.display='none';
                commoditylists.css('display','none');
                allPay[0].style.display='none';
                linetext[0].style.display='none';
                $('.order-select-img1').css('display','none');
                $('.order-select-img2').css('display','none');
                for(var i=0;i<mystatus.length;i++){
                	mystatus[i].parentNode.parentNode.style.display='block';
            		if(mystatus[i].innerHTML!="等待卖家发货"){
                        mystatus[i].parentNode.parentNode.style.display='none';
                        f1++;
            		}else{
                        c++;
                    }     		  		    		    		
            	}
            	if(mystatus.length==0||f1==mystatus.length){
                    noorder.style.display='block';
                    commoditylists.css('display','block');
                    linetext[0].style.display='block';
        		}
            }
            type[3].onclick=function(){
            	f1=0;test="send";
            	$(this).parents("#shoporder-select").find(".onorder").removeClass('onorder');
                $(this).addClass("onorder");
                noorder.style.display='none';
                commoditylists.css('display','none');
                allPay[0].style.display='none';
                linetext[0].style.display='none';
                $('.order-select-img1').css('display','none');
                $('.order-select-img2').css('display','none');
                for(var i=0;i<mystatus.length;i++){
                	mystatus[i].parentNode.parentNode.style.display='block';
            		if(mystatus[i].innerHTML!="卖家已发货"){
                        mystatus[i].parentNode.parentNode.style.display='none';
                        f1++;
            		}else{
                        d++;
                    }
            	}
            	if(mystatus.length==0||f1==mystatus.length){
                    noorder.style.display='block';
                    commoditylists.css('display','block');
                    linetext[0].style.display='block';
        		}
            }
            type[4].onclick=function(){
            	f1=0;test="over";
            	$(this).parents("#shoporder-select").find(".onorder").removeClass('onorder');
                $(this).addClass("onorder");
                noorder.style.display='none';
                commoditylists.css('display','none');
                allPay[0].style.display='none';
                linetext[0].style.display='none';
                $('.order-select-img1').css('display','none');
                $('.order-select-img2').css('display','none');
                for(var i=0;i<mystatus.length;i++){
                	mystatus[i].parentNode.parentNode.style.display='block';
            		if(mystatus[i].innerHTML!="交易完成"){
                        mystatus[i].parentNode.parentNode.style.display='none';
                        f1++;
            		}else{
                        e++;
                    }
            	}
            	if(mystatus.length==0||f1==mystatus.length){
                    noorder.style.display='block';
                    commoditylists.css('display','block');
                    linetext[0].style.display='block';
        		}
            }

            for(var i=0;i<myselectimg1.length;i++){
            	myselectimg1[i].onclick=function(){
                    f2++;
            	    $(this).css('display','none');
            	    $(this).next().css('display','block');
            	    if((f2-f3)>0){
            	        allPay[0].style.display='block';
                        var m=$(this).parents('.order-list').children('.total').html();
                        var n=$(this).parents('.order-list').children('.order_id').html();
                        money=money+parseFloat(m);
                        id.splice(id.length,0,n);
                    }else{
                        allPay[0].style.display='none';
                    }  	    
            	}
            }
            for(var i=0;i<myselectimg2.length;i++){
            	myselectimg2[i].onclick=function(){
            	    f3++;    		
            	    $(this).css('display','none');
            	    $(this).prev().css('display','block');
            	    if((f2-f3)>0){
            	        allPay[0].style.display='block';
                        var m=$(this).parents('.order-list').children('.total').html();
                        var n=$(this).parents('.order-list').children('.order_id').html();
                        money=money-parseFloat(m);
                        id.splice((id.length-1),1);
                    }else{
                        allPay[0].style.display='none';
                    } 
            	}
            }
            //auth:zww
            // date:2016.06.20
            // update start
            var url = window.location.search;
            if(url=='?unpay'){
                type[1].onclick();
            }
            if(url=='?unsend'){
                type[2].onclick();
            }
            if(url=='?payed'){
                type[3].onclick();
            }
            if(url=='?finished'){
                type[4].onclick();
            }
            // update end
        //取消订单    
        	$("#shoporder").on("click", ".conselorder", function() {
                var status=$(this).parents('.order-list').find('.status');
                var orderaction=$(this).parents('.order-list-action');
                var id=$(this).parents('.order-list').children('span').html();
                var object=$(this);
                $.confirm("确定要取消该订单吗？", "取消订单！", function() {
                    if(test=="unpay"){
                        b--;
                    }
                     $.ajax({
                            type:'POST',
                            url:'/shop/order/cancel',                          
                            dataType:"json",
                            data:{
                                order_id:id,
                            },
                            success:function(data){
                                if(data.status=="success"){
                                    object.parents('.order-list').remove();
                                    // status.html("交易关闭");
                                    // if(test=="unpay"){
                                    //     object.parents('.order-list').css('display','none');
                                    // }
                                    // orderaction.html("<img src='http://cache.dataguiding.com/img/shop/myorder/deleteorder.png' class='order-list-action-image2 deleteorder' name='deleteorder'>");
                                    if(b==0&&test=="unpay"){
                                        noorder.style.display='block';
                                        commoditylists.css('display','block');
                                        linetext[0].style.display='block';
                                    }
                                    $.toast(data.msg); 
                                }else{
                                    alert(data.msg);
                                }
                            } 
                    });
                }, function() {
                  //取消操作
                });
            });
        //删除订单
            $("#shoporder").on("click", ".deleteorder", function() {
                var id=$(this).parents('.order-list').children('span').html();
            	var object=$(this);
                if(test=="all"){
                    a--;
                }else if(test=="send"){
                    d--;
                }else if(test=="over"){
                    e--;
                }
                $.confirm("确定要删除该订单吗？", "删除订单！", function() {         
                   $.ajax({
                            type:'POST',
                            url:'/shop/order/delete',                          
                            dataType:"json",
                            data:{
                                order_id:id
                            },
                            success:function(data){
                                if(data.status=="success"){
                                    object.parents('.order-list').remove();
                                    if(a==0&&test=="all"){
                                        noorder.style.display='block';
                                        commoditylists.css('display','block');
                                        linetext[0].style.display='block';
                                    }else if(d==0&&test=="send"){
                                        noorder.style.display='block';
                                        commoditylists.css('display','block');
                                        linetext[0].style.display='block';
                                    }else if(e==0&&test=="over"){
                                        noorder.style.display='block';
                                        commoditylists.css('display','block');
                                        linetext[0].style.display='block';
                                    }                           
                                    $.toast(data.msg); 
                                }else{
                                    alert(data.msg);
                                }
                            }
                    });
                }, function() {

                });
            });
        //提醒发货
            $('#y-order').on("click", ".reminderdelivery", function() {
                var id=$(this).parents('.order-list').children('span').html();
                $.ajax({
                        type:'POST',
                        url:'/shop/order/hurry',                          
                        dataType:"json",
                        data:{
                            order_id:id,
                        },
                        success:function(data){
                            if(data.status=="success"){
                                $.confirm("商家收到提醒后会尽快为您发货哦~", "提醒发货！", function() {
                                        $.toast(data.msg);      
                                }, function() {

                                });
                                 
                            }else{
                                $.alert(data.msg);
                            }
                        }
                });
                
            });
        //付款
            $('.pay').on('click',function(){
                var payobj = $(this);
                var order_id = $(this).closest('.order-list').children('.order_id').html();
                var pay_money = $(this).closest('.order-list').find('.order-list-value-summary span').html();
                 $.actions({
                          actions: [{
                            text: "微信支付",
                            onClick: function() {
                                payobj.addClass('disabled');
                               // window.location.href='/shop/order/pay/'+order_id+'/'+pay_money+'/'+'1';
                              $.ajax({
                                    type : 'get',
                                    url : '/shop/order/pay/'+order_id+'/'+pay_money+'/'+'1',
                                    success:function(data){
                                        if(data.status == 'success'){
                                            //预支付
                                           // var params = JSON.parse(data.jsApiParameters);
                                            callpay(data.jsApiParameters);
                                        }else{
                                            alert(data.msg);
                                            payobj.removeClass('disabled');
                                        }
                                    }
                                });                       
                            }                                         
                          }]
                    });
            });
        //合并付款
            $('.allpay').on('click',function(){
                 $.actions({
                          actions: [{
                            text: "微信支付",
                            onClick: function() {
                                //window.location.href = '/shop/order/paymany';
                                $.ajax({
                                        type:'POST',
                                        url:'/shop/order/paymany',                          
                                        dataType:"json",
                                        data:{
                                            order_id:id,
                                            pay_money:money,
                                        },
                                        success:function(data){
                                            if(data.status=="success"){
                                                //var params = JSON.parse(data.jsApiParameters);
                                               callpay(data.jsApiParameters);                                       
                                            }else{
                                               alert(data.msg); 
                                            }
                                        }
                                });                     
                            }                                         
                          }]
                    });
            });
        //取消退款
            $('.cancle-refund').on('click',function(){
                var id=$(this).parents('.order-list').children('.order_id').html();
                 $.confirm("确认取消这次退货吗？", "取消退款！", function() {
                    $.ajax({
                                type:'POST',
                                url:'/shop/order/cancelrefund',                          
                                dataType:"json",
                                data:{
                                    order_id:id,
                                },
                                success:function(data){
                                    if(data.status=="success"){
                                       $.toast(data.msg);
                                       window.location.reload();                                          
                                    }else{
                                        $.alert(data.msg);
                                    }
                                }
                    });
                }, function() {

                });
            });
        //计算合计金额
            var Money = new Array();
            var Count = new Array();
            var Price = new Array();
                $('.order-list').each(function(){
                    Count.length=0;
                    Price.length=0;
                    total=0;
                    var object = $(this).children().children('.order-list-content');
                    $.each(object,function(key1,val1){
                        count = $(this).children('.order-list-amount').html().replace(/[^0-9]/ig,"");
                        Count.push(count);
                    });
                    $.each(object,function(key2,val2){
                        price = $(this).children().children('.order-list-content-value').html().replace('￥','');
                        Price.push(price);
                    });
                    for(var i=0;i<Count.length;i++){
                        total+=Count[i]*Price[i];
                    }
                    $(this).children().find('.total-money').html(total.toFixed(2)); 
            });
        //再次购买
        
    //订单页js结束
    
})