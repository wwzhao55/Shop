<!-- auth:zww
	 date:2016.05.10 
-->
<!--编辑地址-->
<!-- 继承的模板 -->
@extends('layouts.shop')
<!-- 标题 -->
@section('title')
<title>编辑地址</title>
@stop
<!-- 自己额外要加的css -->
@section('addCss')
<!-- <link rel="stylesheet" type="text/css" href="http://cache.dataguiding.com/css/shop/wechat2.css">  -->
<link rel="stylesheet" type="text/css" href="{{URL::asset('shop/css/wechat2.css')}}">
@stop
<!-- 内容 -->
@section('content')
<form action='/shop/address/edit' method="post" enctype="multipart/form-data">
	<input type="hidden" name="_token" value="{{ csrf_token() }}">
	<div class="edit_address">
		<div class="weui_cells weui_cells_form">
				<div class="weui_cell" id="address-list">
					<div class="weui_cell_hd"><label class="weui_label">收货人</label></div>
					<div class="weui_cell_bd weui_cell_primary">
					    <input class="weui_input" type="text" placeholder=""  name="receiver_name" value="{{$address->receiver_name}}">
					</div>
				</div>
				<div class="weui_cell" id="address-list">
					<div class="weui_cell_hd"><label class="weui_label">联系电话</label></div>
					<div class="weui_cell_bd weui_cell_primary">
					    <input class="weui_input" type="phone" placeholder="" name="receiver_phone" value="{{$address->receiver_phone}}">
					</div>
				</div>
			  	<div class="weui_cell" id="address-list">
			    	<div class="weui_cell_hd"><label class="weui_label">所在地区</label></div>
			    	<div class="weui_cell_bd weui_cell_primary">
			    	@if ($type =='new')
			      		<input class="weui_input" id="area" type="text"  placeholder="请选择" name="district" value="">
			      	@else
			      		<input class="weui_input" id="area" type="text"  placeholder="请选择" name="district" value="{{$address->province}} {{$address->city}} {{$address->district}}">
			      	@endif
			      		<img class="choose-em" src="http://cache.dataguiding.com/img/shop/shopcat/em-detail.png">
			    	</div>
			  	</div>
			  	<!-- <div class="weui_cell" id="address-list">
			    	<div class="weui_cell_hd"><label class="weui_label">街道</label></div>
			    	<div class="weui_cell_bd weui_cell_primary">
			      		<input class="weui_input" id="street" type="text"  placeholder="请选择" name="street">
			      		<img class="choose-em" src="http://cache.dataguiding.com/img/shop/shopcat/em-detail.png')}}">
			    	</div>
			  	</div> -->
  				
	
			<div class="weui_cells weui_cells_form" id="address-detail">
		  		<div class="weui_cell" id="weui_cell_detail">
			    	<div class="weui_cell_bd weui_cell_primary" id="text-area-line">
			      			<input id="text-detailaddress" class="weui_textarea" placeholder="请填写详细地址，不少于5个字" rows="5" name="address_details" value="{{$address->address_details}}">
			    	</div>
		  		</div>
			</div>
			<div class="blank"></div>
			@if ($type =='new')
			<div class="weui_cell weui_cell_switch" id="address-set">
			    	<div class="weui_cell_hd weui_cell_primary" id="set-parmeter">设为默认</div>
			    	<div class="weui_cell_ft">
			      		<input class="weui_switch" type="checkbox" value="" id="set_default">
			      		<input type="text" name="is_default" hidden>
			    	</div>
			 </div>

			 <input class="weui_input" type="text" hidden name="address_id" value="{{$address->id}}">
			@else
			 <div class="weui_cell" id="delete-address">

					<div class="weui_cell_hd"><label class="edit-delete-address weui_label">删除地址</label></div>
					<!-- <div class="weui_cell_bd weui_cell_primary">
					    <input class="weui_input" type="text" placeholder="" hidden>
					</div> -->
			 </div>

			 <input class="weui_input" type="text" hidden name="address_id" value="{{$address->id}}">
			 @endif	
			<!--  <input class="weui_input" type="text" hidden name="address_id" value="{{$address->id}}"> -->
		</div>
	</div>
	@if (Session::has('address_error'))
		<div class="coupon_error text-center" style='font-size: 30px;margin-top:10px;'>{{Session::get('address_error')}}
		</div>
	@endif

	<input type='submit' class="confirm" value="保存">
</form>
@stop
@section('addJs')
<!-- <script src="http://cache.dataguiding.com/plugins/weui/js/city-picker.min.js"></script> -->
<script src="{{URL::asset('shop/weui/js/city-picker.js')}}"></script>
<script src="{{URL::asset('shop/weui/js/swiper.min.js')}}"></script>
<script src="{{URL::asset('shop/js/address.js')}}"></script>
<script type="text/javascript">
	$("input[name='district']").cityPicker({
        title: "",
      //  showDistrict:true
    });</script>
 @stop