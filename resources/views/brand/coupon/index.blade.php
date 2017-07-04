<!-- auth:wuwenjia
data:16.7.25 -->
@extends('layouts.app')
@section('siderbar')
@include('layouts.siderbar')
@endsection
@section('addCss')
<link rel="stylesheet" href="{{ URL::asset('shop/css/jquery.toastmessage.css')}}"/>
<link rel="stylesheet" type="text/css" href="{{ URL::asset('shopadmin/css/coupon.css')}}">
@endsection
@section('content')
<div class="couponmanage">
	<div class="couponheading">优惠券列表
        <select class="couponselect" value="">
        	<option value="all">全部</option>
        	<option value="over">已结束</option>
        	<option value="on">进行中</option>
        </select>
        <img class="img-search" src="{{asset('shopstaff/images/search.png')}}">
        <input type="text" class="coupon_search">
        <button class="btn-search">搜索</button>
	    <a href="/Brand/coupon/add"><button class="addcoupon">新增优惠券</button></a>
	</div>
	@include('brand.coupon.couponing')
	@include('brand.coupon.couponed')
	<div class="overdue-window" hidden>
	       <div class="question">您确定要使这组优惠券失效？</div>
	       <div>
	       			<button class="cancle-overdue allhover">取消</button>
	       			<button class="confirm-overdue allhover">确定</button>
	       </div>
	       <div class="tip">注：失效后，买家无法再领取该优惠券；您也不能继续编辑优惠内容；买家之前已领到的优惠券，在有效期内还能继续使用。</div>
	</div>
	<div class="del-window" hidden>
	       <div class="question">您确定要删除这组优惠券？</div>
	       <div>
	       			<button class="cancle-del allhover">取消</button>
	       			<button class="confirm-del allhover">确定</button>
	       </div>
	       
	</div>     
</div>
<script src="{{asset('shop/js/jquery.toastmessage.js')}}"></script>
<script type="text/javascript">
 $.get('/Brand/coupon/couponing', function(data){
          $('.living').html(data); 
      });
      $.get('/Brand/coupon/couponed', function(data){
          $('.over').html(data); 
      });
$('.couponmanage').on('click', '.pagination a', function(e) {
  var url = $(this).attr('href');
  e.preventDefault();
  if(url.indexOf('search')>=0){
  	if(url.indexOf('page1')>=0){  
	      $.get(url,{keyword:$('.coupon_search').val()},function(data){
	          $('.living').html(data); 
	      });
	  }else if(url.indexOf('page2')>=0){
	  	 $.get(url, {keyword:$('.coupon_search').val()},function(data){
	          $('.over').html(data); 
	      });
	  }
  }else if(url.indexOf('Brand/coupon?page')>=0){
  	//初始get还没执行到
  	if(url.indexOf('page1')>=0){  
	      $.get(url.replace('Brand/coupon','Brand/coupon/couponing'), function(data){
	          $('.living').html(data); 
	      });
	  }else if(url.indexOf('page2')>=0){
	  	 $.get(url.replace('Brand/coupon','Brand/coupon/couponed'), function(data){
	          $('.over').html(data); 
	      });
	  }
  }else{
  	if(url.indexOf('page1')>=0){  
	      $.get(url, function(data){
	          $('.living').html(data); 
	      });
	  }else if(url.indexOf('page2')>=0){
	  	 $.get(url, function(data){
	          $('.over').html(data); 
	      });
	  }
  } 
   
});
    $('.side-list').find('.onsidebar').removeClass('onsidebarlist');
    $('.coupon').addClass('onsidebarlist');
    $('.side-list').find('.in').removeClass('in');
    $('#wexin-manage').addClass('in');
    $('.side-list').find('.onsidebar').removeClass('onsidebar');
    $('.weixinmanage').addClass('onsidebar');
   
    $('.living').on('click','.overdue',function(){
        cancel_index= layer.open({
			type: 1,
			title:false,
			skin: 'layui-layer-demo', //样式类名
			closeBtn: 0, //不显示关闭按钮
			shift: 2,
			shadeClose: true, //开启遮罩关闭
			area : ["500px" , '300px'],
			content:$('.overdue-window'),
		});
		$(".cancle-overdue").on("click",function(){
	        $(".overdue-window").css("display","none");
	        layer.close(cancel_index);
	    });
		var id=$(this).parents('tr').find('.coupon_id').html();		            
		$('.confirm-overdue').on('click',function(){
		    $.ajax({
				type:'get',
				url:'/Brand/coupon/changestatus/'+id,
				success:function(result){
				    if(result.status=="success"){
				        layer.close(cancel_index);
				        window.location.href="/Brand/coupon";
				    }else{
				        alert(result.msg);
				    }                    
				}
			});
		});
    });
    
    
    
        
  
    $('.couponselect').change(function(){
    	$('.living').css('display','');
        $('.over').css('display','');
        if($('.couponselect option:selected').val()=="all"){
            $('.living').css('display','');
            $('.over').css('display','');
            if($('.coupon_search').val()==''){
            	$.ajax({
					type:'GET',
					url:'/Brand/coupon/searchon',
					data:{					                	
						keyword:'',
					},
					dataType:"json",
					success:function(result){
						$('.living').html(result);                  
					}
				});
				$.ajax({
					type:'GET',
					url:'/Brand/coupon/searchover',
					data:{					                	
						keyword:'',
					},
					dataType:"json",
					success:function(result){
						$('.over').html(result);                 
					}
				});
            }
        }else if($('.couponselect option:selected').val()=="over"){
            $('.living').css('display','none');
            $('.over').css('display','');
            if($('.coupon_search').val()==''){
            	$.ajax({
					type:'GET',
					url:'/Brand/coupon/searchon',
					data:{					                	
						keyword:'',
					},
					dataType:"json",
					success:function(result){
						$('.living').html(result);                  
					}
				});
				$.ajax({
					type:'GET',
					url:'/Brand/coupon/searchover',
					data:{					                	
						keyword:'',
					},
					dataType:"json",
					success:function(result){
						$('.over').html(result);                 
					}
				});
            }
        }else if($('.couponselect option:selected').val()=="on"){
            $('.living').css('display','');
            $('.over').css('display','none');
            if($('.coupon_search').val()==''){
            	$.ajax({
					type:'GET',
					url:'/Brand/coupon/searchon',
					data:{					                	
						keyword:'',
					},
					dataType:"json",
					success:function(result){
						$('.living').html(result);                  
					}
				});
				$.ajax({
					type:'GET',
					url:'/Brand/coupon/searchover',
					data:{					                	
						keyword:'',
					},
					dataType:"json",
					success:function(result){
						$('.over').html(result);                 
					}
				});
            }
        }
    }); 

//删除优惠券
$('.over').on('click','.btn-del',function(){
	    cancel_index1= layer.open({
			type: 1,
			title:false,
			skin: 'layui-layer-demo', //样式类名
			closeBtn: 0, //不显示关闭按钮
			shift: 2,
			shadeClose: true, //开启遮罩关闭
			area : ["500px" , '250px'],
			content:$('.del-window'),
		});
		$(".cancle-del").on("click",function(){
	        $(".del-window").css("display","none");
	        layer.close(cancel_index1);
	    });
		var id=$(this).parents('tr').find('.coupon_id').html();		            
		$('.confirm-del').on('click',function(){
		    $.ajax({
				type:'get',
				url:'/Brand/coupon/delete/'+id,
				success:function(result){
				    if(result.status=="success"){
				        layer.close(cancel_index1);
				        window.location.href="/Brand/coupon";
				    }else{
				        alert(result.msg);
				    }                    
				}
			});
		});
})
    $('.btn-search').on('click',function(){
    	var keyword=$('.coupon_search').val();
    	if($('.coupon_search').val() == ""){
    		alert("请输入关键词");
    	}else{
    		$.ajax({
				type:'GET',
				url:'/Brand/coupon/searchon',
				data:{					                	
					keyword:keyword,
				},
				dataType:"json",
				success:function(result){
					$('.living').html(result);                  
				}
			});
			$.ajax({
				type:'GET',
				url:'/Brand/coupon/searchover',
				data:{					                	
					keyword:keyword,
				},
				dataType:"json",
				success:function(result){
					$('.over').html(result);                 
				}
			});
    	}
    	
    });
    
    
    
</script>
@endsection