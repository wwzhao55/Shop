@extends('layouts.app')
@section('siderbar')
@include('layouts.siderbar')
@endsection

@section('addCss')
<link rel="stylesheet" type="text/css" href="{{ URL::asset('shopstaff/commodity.css')}}">
@endsection
<!-- 物流管理 -->
@section('content')
		
	  	<div class="trans-nav">
			<button class="wait-list">待发货订单列表</button>
			<button class="ready-list">已发货订单列表</button>
		</div>
		<div class="transferManage">
		<!-- 待发货订单列表 -->
			<div class="wait-tab">
		    	<table>
		    		<thead>
		                <tr>
		                    <th width="25%">订单号</th>
		                    <th width="25%">时间</th>
		                    <th width="25%">用户信息</th>
		                    <th width="25%">快递单号</th>
		                </tr>
		            </thead>
		            <tbody>
		            	@foreach($orders as $list)
		            	@if(!$list->express_num && $list->status==1)
			            	<tr>
			                    <td>{{$list->order_num}}</td>
			                    <td>{{$list->created_at}}</td>
			                    <td>{{$list->receiver->province}}</td>
			                    <td hidden class="order-id">{{$list->id}}</td>
			                    <td><input type="text" class="input" value="{{$list->express_num}}"> <img class="confirm" src="{{asset('shopstaff/images/determine-s.png')}}"></td>
			                </tr>
			                @foreach ($list->commodity as $value)
			                <tr style="height:120px;" hidden class="tr-detail">
			                    <td><img class="commodity-logo" src="{{asset($value->main_img)}}">
			                    	<span class="commodity-name">{{$value->commodity_name}}</span><br/>
			                    	<span class="commodity-number">{{$value->count}}</span><br/>
			                    	<span class="commodity-price">￥{{$value->price}}</span></td>
			                    <td hidden class="id">{{$list->id}}</td>
			                    <td></td>
			                    <td></td>
			                    <td></td>
			                    <td></td>
			                    <td></td>
			                </tr>
			                @endforeach
			            @endif
			        @endforeach	
		            </tbody> 
		        </table>
		        <div class="wait-detail" hidden>
		        	<table>
			    		<thead style="border-bottom: 1px #e8e8e8 solid">
			                <tr>
			                    <th>订单详情</th>
			                    <th>姓名</th>
			                    <th>电话</th>
			                    <th>地址</th>
			                    <th>总金额</th>
			                    <th>备注</th>
			                </tr>
			            </thead>
			            <tbody class="order-detail">
			            </tbody> 
		        	</table>
		        </div>
	    	</div>
	    <!-- 已发货订单列表 -->
	    	<div class="ready-tab">
		    	<table>
		    		<thead>		    		
		                <tr>
		                    <th width="20%">订单号</th>
		                    <th width="20%">时间</th>
		                    <th width="20%">用户信息</th>
		                    <th width="20%">快递单号</th>
		                    <th width="20%">状态</th>
		                </tr>
		            </thead>
		            <tbody class="tab-lists">
			          	@foreach($orders as $order)
			            	@if($order->express_num&&$order->status!=1)
				            	<tr>
				                    <td>{{$order->order_num}}</td>
				                    <td>{{$order->created_at}}</td>
				                    <td>{{$order->receiver->province}}</td>
				                    <td hidden class="ready-order-id">{{$order->id}}</td>
				                    <td><input type="text" class="transfercode" value="{{$order->express_num}}"> <img class="btn-sure" src="{{asset('shopstaff/images/determine-s.png')}}"></td>
				                    <td>@if($order->status == 2)
					                    	<img class="staus" src="{{asset('shopstaff/images/In-transit.png')}}">
					                    	<span class="describe">运送中</span>
				                    	@elseif($order->status == 3)
					                    	<img class="staus" src="{{asset('shopstaff/images/Already-sign.png')}}">
					                    	<span class="describe">已签收</span>
				                    	@endif	
				                    </td>
				                </tr>
				                @foreach ($order->commodity as $value)
					                <tr style="height:120px;" class="tr-ready-detail" hidden>
					                    <td><img class="commodity-logo" src="{{asset($value->main_img)}}">
					                    	<span class="commodity-name">{{$value->commodity_name}}</span><br/>
					                    	<span class="commodity-number">{{$value->count}}</span><br/>
					                    	<span class="commodity-price">￥{{$value->price}}</span></td>
					                    <td>{{$order->receiver->receiver_name}}</td>
					                    <td>{{$order->receiver->receiver_phone}}</td>
					                    <td>{{$order->receiver->province}}</td>
					                    <td>{{$order->total}}</td>
					                    <td>{{$order->message}}</td>
					                    <td hidden class="ready-id">{{$order->id}}</td>
					                </tr>
				                @endforeach	
			                @endif
				        @endforeach	
		            </tbody> 
		        </table>
		        <div class="ready-detail" hidden>
		        	<table>
			    		<thead>
			                <tr>
			                    <th style="border:none;">订单详情</th>
			                    <th>姓名</th>
			                    <th>电话</th>
			                    <th>地址</th>
			                    <th>总金额</th>
			                    <th>备注</th>
			                </tr>
			            </thead>
			            <tbody class="ready-order-detail">
			            </tbody> 
		        	</table>
		        </div>
	    	</div>	    	
	    </div>
</body>
<Script type="text/javascript">
	//导航切换
		$(".wait-list").on('click',function(){
			$(".ready-tab").css("display","none");
			$(".wait-tab").css("display","block");
			$(".wait-list").css("background","#fff");
			$(".wait-list").css("color","#fb2d5d");
			$(".ready-list").css("background","#ccc");
			$(".ready-list").css("color","#000000");
		});
		$(".ready-list").on('click',function(){
			$(".wait-tab").css("display","none");
			$(".ready-tab").css("display","block");
			$(".ready-list").css("background","#fff");
			$(".ready-list").css("color","#fb2d5d");
			$(".wait-list").css("background","#ccc");
			$(".wait-list").css("color","#000000");
		});
	//悬浮效果
		$(".staus").on("mouseover",function(){
			if($(this).attr("src")=="{{asset('shopstaff/images/In-transit.png')}}"){
				 $(this).attr("src","{{asset('shopstaff/images/In-transit-hover.png')}}");
			}else{
				$(this).attr("src","{{asset('shopstaff/images/Already-signAlready-sign.png')}}");
			}  
	    });
	    $(".staus").on("mouseout",function(){
	          if($(this).attr("src")=="{{asset('shopstaff/images/In-transit-hover.png')}}"){
				 $(this).attr("src","{{asset('shopstaff/images/In-transit.png')}}");
			}else{
				$(this).attr("src","{{asset('shopstaff/images/Already-sign.png')}}");
			} 
	    });
	//快递单号查询
		$(".confirm").on('click',function(){
			var express_num = $(this).siblings(".input").val();
			var order_id = $(this).parent().siblings(".order-id").html();
			$(".order-detail").empty();
			$(".wait-detail").css("display","block");
			$(".tr-detail").each(function(){
				var id = $(this).children(".id").html();
				console.log(id);
				if(order_id==id){
					var detail = $(this).html();
					console.log(detail);
					$('.order-detail').append("<tr>"+detail+"</tr>");
				}
			});
				$.ajax({
						type:'POST',
						url:'/Shopstaff/logistics/addexpressnum',
					 	data:{
							express_num:express_num,
							order_id:order_id,
						   },
						 dataType:"json",
						 success:function(result){ 
								  if(result.status=='success'){  
									console.log(result);
									 }else{
									 console.log(result.message);
									 }   	
						 }               
				});
		});
		$(".btn-sure").on('click',function(){
			var express_num = $(this).siblings(".transfercode").val();
			var order_id = $(this).parent().siblings(".ready-order-id").html();
			$(".ready-order-detail").empty();
			$(".ready-detail").css("display","block");
			$(".tr-ready-detail").each(function(){
				var id = $(this).children(".ready-id").html();
				console.log(id);
				if(order_id==id){
					var detail = $(this).html();
					console.log(detail);
					$('.ready-order-detail').append("<tr>"+detail+"</tr>");
				}
			});
				$.ajax({
						type:'POST',
						url:'/Shopstaff/logistics/addexpressnum',
					 	data:{
							express_num:express_num,
							order_id:order_id,
						   },
						 dataType:"json",
						 success:function(result){ 
								  if(result.status=='success'){  
									console.log(result);
									 }else{
									 console.log(result.message);
									 }   	
						 }               
				});
		});            
</script>
@endsection