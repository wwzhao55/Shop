<!-- auth:wuwenjia
data:16.7.26 -->
@extends('layouts.app')
@section('siderbar')
@include('layouts.siderbar')
@endsection
@section('addCss')
<link rel="stylesheet" type="text/css" href="{{ URL::asset('shopadmin/css/coupon.css')}}">
@endsection
@section('content')
<div class="add_coupon">
	<div class="couponheading">设置优惠券
	    <a href="/Brand/coupon"><button class="returncoupon allhover">返回</button></a>
	</div>
	<div class="coupon_view">
		<div class="view_name"></div>
		<div class="share">分享</div>
		<div class="view_value">￥<span></span></div>
		<div class="view_condition"></div>
		<div class="view_time">有效期：</div>
		<div class="view_description">使用说明</div>
		<div class="view_des_content">暂无使用说明</div>
	</div>
	<div class="coupon_detail">
	    <form action="/Brand/coupon/add" method="post" class="form" enctype="multipart/form-data" onsubmit="return toVaild()">
            <div class="add-success">
                {{Session::get('Message')}}
            </div>
            {!! csrf_field() !!}
			<div class="coupongroup{{ $errors->has('name') ? ' has-error' : '' }}">
	            <span>优惠券名称：</span>
	            <input type="text" name="name" placeholder="最多输入10个字" class="coupon_name" required="required" value="{{old('name')}}">
	            @if ($errors->has('name'))
	                <span class="help-block">
	                    <strong>{{ $errors->first('name') }}</strong>
	                </span>
	            @endif
	            <div class="clearfix" style="clear:both;"></div>
	        </div>
	        <div class="coupongroup{{ $errors->has('number') ? ' has-error' : '' }}">
	            <span>发放总量：</span>
	            <div style="display:inline-block;" class="div_number"><input type="number" required="required" name="number" class="coupon_number" value="{{old('number')}}"><span class="zhang">张</span></div>
	            @if ($errors->has('number'))
	                <span class="help-block">
	                    <strong>{{ $errors->first('number') }}</strong>
	                </span>
	            @endif
	            <div class="clearfix" style="clear:both;"></div>
	        </div>
	        <div class="coupongroup{{ $errors->has('sum') ? ' has-error' : '' }}">
	            <span>优惠金额（元）：</span>
	            <input type="number" name="sum" required="required" class="coupon_sum" value="{{old('sum')}}">
	            @if ($errors->has('sum'))
	                <span class="help-block">
	                    <strong>{{ $errors->first('sum') }}</strong>
	                </span>
	            @endif
	            <div class="clearfix" style="clear:both;"></div>
	        </div>
	        <div class="coupongroup{{ $errors->has('use_condition') ? ' has-error' : '' }}">
	            <span class="condition_title">优惠条件：</span>
	            <div style="display:inline-block;" class="div_condition">
		            <div><img class="dot check1" src="{{asset('shopstaff/images/dot1.png')}}"><span>无限制（任意金额均可享受优惠）</span></div><br>
		            <div><img class="dot check2" src="{{asset('shopstaff/images/dot.png')}}"><span>满</span><input type="number" name="use_condition" class="coupon_use_condition" value="{{old('use_condition')}}"><span>可使用</span></div>
		            @if ($errors->has('use_condition'))
		                <span class="help-block">
		                    <strong>{{ $errors->first('use_condition') }}</strong>
		                </span>
		            @endif
		        </div>
	            <div class="clearfix" style="clear:both;"></div>
	        </div>
	        <div class="coupongroup{{ $errors->has('gettimes') ? ' has-error' : '' }}">
	            <span>每人限领：</span>
	            <select type="text" name="gettimes" required="required" class="coupon_gettimes" value="{{old('gettimes')}}">
	            	<option value="1">1</option>
	            	<option value="2">2</option>
	            	<option value="3">3</option>
	            	<option value="5">5</option>
	            	<option value="10">10</option>
	            	<option value="不限">不限</option>
	            </select>	           
	            <div class="clearfix" style="clear:both;"></div>
	        </div>
	        <div class="coupongroup{{ $errors->has('validity_start ') ? ' has-error' : '' }}">
	            <span>有效期：</span>
	            <div style="display:inline-block;"><input readonly type="text" required="required" placeholder="请选择"  id="coupon_validity_start" class="coupon_validity_start" value="{{old('validity_start ')}}"><span class="zhi">至</span><input readonly type="text" placeholder="请选择" required="required"  id="coupon_validity_end" class="coupon_validity_end" value="{{old('validity_end')}}"></div>
	            <input class="time_start" hidden type="text" name="validity_start">
	            <input class="time_end" hidden type="text" name="validity_end"> 
	            @if ($errors->has('validity_start '))
	                <span class="help-block">
	                    <strong>{{ $errors->first('validity_start ') }}</strong>
	                </span>
	            @endif
	            <div class="clearfix" style="clear:both;"></div>
	        </div>
	        <div class="coupongroup{{ $errors->has('share_introduce') ? ' has-error' : '' }}">
	            <span class="share_title">推广：</span>
	            <div style="display:inline-block;" class="div_share">
		            <div><img class="dot check3" src="{{asset('shopstaff/images/dot1.png')}}"><span>允许买家分享朋友圈领取优惠券</span><input class="allow_share" hidden name="allow_share"></div><br>
		            <textarea type="text" placeholder="请你编辑分享朋友圈的文案" name="share_introduce" class="share_introduce" value="{{old('share_introduce')}}"></textarea>
		            @if ($errors->has('share_introduce'))
		                <span class="help-block">
		                    <strong>{{ $errors->first('share_introduce') }}</strong>
		                </span>
		            @endif
		        </div>
	            <div class="clearfix" style="clear:both;"></div>
	        </div>
	        <div class="coupongroup{{ $errors->has('shop_id') ? ' has-error' : '' }}">
	            <span>使用范围：</span>
	            <select type="text" name="shop_id" class="shop_id" value="{{old('shop_id')}}">
	            	<option value="0">全店</option>
	            	@foreach($shop_lists as $list)
	            	    <option value="{{$list->id}}">{{$list->shopname}}</option>
	            	@endforeach
	            </select>
	            <select type="text" name="commodity_category" class="commodity_category" value="{{old('commodity_category')}}">
	            	<option value="0">通用</option>
	            	@foreach($category_lists as $list)
	            	    <option value="{{$list->id}}">{{$list->name}}</option>
	            	@endforeach
	            </select>	           
	            <div class="clearfix" style="clear:both;"></div>
	        </div>
	        <div class="coupongroup{{ $errors->has('description') ? ' has-error' : '' }}">
	            <span class="description_title">使用说明：</span>
	            <textarea type="text" required="required" placeholder="请填写活动的详细说明" name="description" class="coupon_description" value="{{old('description')}}"></textarea>
	            @if ($errors->has('description'))
	                <span class="help-block">
	                    <strong>{{ $errors->first('description') }}</strong>
	                </span>
	            @endif
	            <div class="clearfix" style="clear:both;"></div>
	        </div>
            <a href="/Brand/coupon"><div class="btn_cancle allhover">取消</div></a>
            <input type="submit" class="btn_publish allhover" value="发布">

	    </form>
	</div>
</div>

<script type="text/javascript">
    $('.side-list').find('.onsidebar').removeClass('onsidebarlist');
    $('.coupon').addClass('onsidebarlist');
    $('.side-list').find('.in').removeClass('in');
    $('#wexin-manage').addClass('in');
    $('.side-list').find('.onsidebar').removeClass('onsidebar');
    $('.weixinmanage').addClass('onsidebar');
    $('.dot').on('click',function(){
    	if($(this).attr('src')=="{{asset('shopstaff/images/dot1.png')}}"){
    	    $(this).attr('src',"{{asset('shopstaff/images/dot.png')}}");
    	}else{
    		$(this).attr('src',"{{asset('shopstaff/images/dot1.png')}}");
    	}
    });
    $('.check1').on('click',function(){
    	if($(this).attr('src')=="{{asset('shopstaff/images/dot1.png')}}"){
    	    $('.check2').attr('src',"{{asset('shopstaff/images/dot.png')}}");
    	}else{
    		$('.check2').attr('src',"{{asset('shopstaff/images/dot1.png')}}");
    	}
    });
    $('.check2').on('click',function(){
    	if($(this).attr('src')=="{{asset('shopstaff/images/dot1.png')}}"){
    	    $('.check1').attr('src',"{{asset('shopstaff/images/dot.png')}}");
    	}else{
    		$('.check1').attr('src',"{{asset('shopstaff/images/dot1.png')}}");
    	}
    });
    if($('.check1').attr('src')=="{{asset('shopstaff/images/dot.png')}}"){
	    $('.coupon_use_condition').val(0);
	}
	var start = {
	    dateCell: '#coupon_validity_start',
	    format: 'YYYY-MM-DD hh:mm:ss',
		festival:true,
	    isTime: true,
	    choosefun:function(datas){
            var b=$('coupon_validity_end').val();
    	    $('.view_time').html("有效期："+datas+"至"+b);
	    }
	};
	var end = {
	    dateCell: '#coupon_validity_end',
	    format: 'YYYY-MM-DD hh:mm:ss',
		festival:true,
	    isTime: true,
	    choosefun:function(datas){
            var a=$('.coupon_validity_start').val();
    	    $('.view_time').html("有效期："+a+"至"+datas);
	    }
	};
    jeDate(start);
    jeDate(end);

//数据绑定
    $('.coupon_name').on('change',function(){
    	var a=$('.coupon_name').val();
    	$('.view_name').html(a);
    });
    $('.coupon_sum').on('change',function(){
    	var a=$('.coupon_sum').val();
    	$('.view_value span').html(a);
    });
    $('.coupon_use_condition').on('change',function(){
    	if($('.check2').attr('src')=="{{asset('shopstaff/images/dot1.png')}}"||$('.coupon_use_condition').val()==0){
            $('.view_condition').html("无限制");
    	}else{
            $('.view_condition').html("订单满"+$('.coupon_use_condition').val()+"元可用");
    	}
    });   
    $('.coupon_description').on('change',function(){
    	var m=$('.coupon_description').val();
    	$('.view_des_content').html(m);
    	if($('.coupon_description').val()==''){
    		$('.view_des_content').html("暂无使用说明");
    	}
    });
//提交前验证
    function toVaild(){
    	// alert("lalala");
    	if($('.coupon_validity_start').val()>$('.coupon_validity_end').val()){
    		alert("结束日期不能小于开始日期");
    		return false;
    	}
    	var timestamp1 = Date.parse($('.coupon_validity_start').val());
        timestamp_start = timestamp1 / 1000;
        var timestamp2 = Date.parse($('.coupon_validity_end').val());
        timestamp_end = timestamp2 / 1000;
    	$('.time_start').val(timestamp_start);
        $('.time_end').val(timestamp_end);
    	if($('.check2').attr('src')=="{{asset('shopstaff/images/dot.png')}}"){
	        $('.coupon_use_condition').attr('required','required');
	    }
	    
	    if($('.check3').attr('src')=="{{asset('shopstaff/images/dot.png')}}"){
	    	$('.allow_share').val(1);
	        $('.share_introduce').attr('required','required');
	    }else{
            $('.allow_share').val(0);
	    }
	    return true;
    }
    
</script>
@endsection