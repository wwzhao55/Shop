<!-- auth:zww
	 date:2016.08.04 
-->
<!-- 继承的模板 -->
@extends('layouts.shop')
<!-- 标题 -->
@section('title')
<title>{{Session::get('brand_name')}}</title>
@stop
<!-- 自己额外要加的css -->
@section('addCss')
<!-- <link href="http://cache.dataguiding.com/css/shop/wechat.css" rel="stylesheet"> -->
<!-- <link href="http://cache.dataguiding.com/css/shop/detail.css" rel="stylesheet"> -->
<link href="{{asset('shop/css/wechat.css')}}" rel="stylesheet">
<link href="{{asset('shop/css/detail.css')}}" rel="stylesheet">

@stop
<!-- 内容 -->
@section('content')
	<div class='loading'><img src="{{asset('shop/images/loading.gif')}}"></div>
	<div id='detail-scroll'>
	    <span class="shopid" hidden>{{$shopid}}</span>
		<!-- 轮播商品详情 -->
		<div class="commodity-carousel swiper-container" data-pagination='.swiper-pagination'>
		    <div class="swiper-wrapper">		       
		        <div class="swiper-slide"><img src="{{asset($detail->main_img)}}" alt=""></div>
		        @for($i=0;$i<count($detail->img);$i++)
		            <div class="swiper-slide"><img src="{{asset($detail->img[$i])}}" alt=""></div>
		        @endfor
		    </div>
		    <!-- If we need pagination -->
    		<div class="swiper-pagination"></div>
		</div>
		<!-- 商品信息 -->
		<div id='detail-intro' class='detail-intro'>
			<div class='intro-row intro-title'>
				{{$detail->commodity_name}}
			</div>
			<div class='intro-row'>
				<div class='intro-value pull-left'>
					@if($detail->min_price==$detail->max_price)
					    ￥{{$detail->min_price}}
                    @else
                        ￥{{$detail->min_price}}-{{$detail->max_price}}
                    @endif
				</div>
				<!-- <div class='intro-post pull-left'>
					运费 : {{$detail->express_price}}元
				</div> -->
				@if($detail->sku_info==1)
					<div class='intro-rest pull-left'>
						剩余 :{{$sku_lists[0]->quantity}}件
					</div>
				@endif
				<div class='clearfix'></div>
			</div>
			<div class='intro-row'>
				<div class='intro-place pull-left'>
					产地:{{$detail->produce_area1}}{{$detail->produce_area2}}
				</div>
				@if($detail->sku_info==0)
				  @foreach($sku_info as $key=>$value)
					<div class='intro-quantity pull-left'>
						{{$key}} : {{$value[0]}}
					</div>
				  @endforeach
				@endif
				<div class='intro-kind pull-left'>
					种类 : {{$detail->group_name}}
				</div>
				<div class='clearfix'></div>
			</div>
		</div>
		<!-- 线下门店 -->
		<div class='weui_cells list-arrow'>
			<a class="weui_cell" href="{{ URL::asset('shop/front/store')}}/{{$detail->shop_id}}">
				<div class="weui_cell_hd">
		            <span class='img-gps pull-left'></span>
		        </div>
		        <div class="weui_cell_bd weui_cell_primary">
		            <p>线下门店</p>
		        </div>
		        <div class="weui_cell_ft">
		        	<span class=' img-rightarrow pull-left'><img src="{{asset('shop/images/shopcat/em-detail.png')}}"></span>
		        </div>
			</a>
		</div>
		<!-- 更多精选商品 -->
		<div class='line-text1'>
			<hr>
			<div>更多精选商品</div>
		</div>
			@if (count($more) >0)
			<div class='commodity-lists-detail'>
			@foreach($more as $list)
				<a href="/shop/front/detail?commodity_id={{$list->id}}">
		          <div class='commodity-list'>
		            <div class='list-img'>
		                <img src="{{ asset($list->main_img) }}" class="commodity-image">
		                <div class='list-value'>￥{{$list->price}}</div>
		            </div>
		            <div class='list-title'>
		                {{$list->commodity_name}}
		            </div>
		          </div>
		        </a>
			@endforeach			
				<div class='clearfix'></div>
			</div>
			@endif
	</div>
	<!-- 幽灵按钮 -->
		<span class="ghost-y-n" hidden>{{$shopcart}}</span>
		<a href="{{ URL::asset('shop/shopcart/index')}}">
		  <div id="ghost"><img src="{{asset('shop/images/firstPage/ghostcart.png')}}"></div>
		</a>

		@if($detail->status==0)
			<div class='status-tip'>商品已经下架啦</div>
		@endif

	<!-- 底部按钮 -->
		<div class='detail-btn btn-bottom'>
			<div class='btn-icon'>
				<div class="btn-number">
					<span class='img-phone'><img src="{{asset('shop/images/detail/phone.png')}}" class="phone"></span><div>联系卖家</div>
				</div>
			</div>
			<div class='btn-icon'>
				<div class="btn-shop">
					<span class='img-storebig'><img src="{{asset('shop/images/detail/store.png')}}" class="store"></span><div>店铺</div>
				</div>
			</div>
			@if($detail->status!=0)
				<div class='btn-cart'>加入购物车</div>
				<div class='btn-buy'>立即购买</div>
			@else
				<div class='btn-cart-no'>加入购物车</div>
				<div class='btn-buy-no'>立即购买</div>
			@endif
		</div>

	<!-- 加入购物车 -->
	<div class="add-cart-cover1" ></div>
	<div class='add-cart' id="add_cart">
		<div class='add-cart-content'>
			<div class='add-cart-close close1'></div>
			<div class='add-cart-detail'>
			    <span class="commodity_id" hidden="">{{$detail->id}}</span>
				<div class='add-cart-img pull-left'>
					<img src="{{asset($detail->main_img)}}" class="addcart-commodityimg">
				</div>
				<div class='add-cart-text pull-left'>
					@if($detail->sku_info==0)
						<div class='add-cart-value'>
							￥{{$detail->min_price}}
						</div>
						<div class='add-cart-title'>
							{{$detail->commodity_name}}
						</div>
						<!-- <div class='add-cart-post pull-left'>
							运费 :{{$detail->express_price}}元
						</div> -->
						<div class='add-cart-rest pull-left'>
							库存 :<span class="quantity">{{$sku_lists[0]->quantity}}</span>件
						</div>
						<div class='add-cart-choose pull-left'hidden>
							选择 :<span class="choose-size"></span>
						</div>
					@else
					    <div class='add-cart-value'>
					    @if($detail->min_price==$detail->max_price)
						    ￥{{$detail->min_price}}
	                    @else
	                        ￥{{$detail->min_price}}-{{$detail->max_price}}
	                    @endif
						</div>
						<div class='add-cart-title'>
							{{$detail->commodity_name}}
						</div>
						<!-- <div class='add-cart-post pull-left'>
							运费 :{{$detail->express_price}}元
						</div> -->
						<div class='add-cart-rest pull-left'>
							库存 :<span class="quantity">{{$sku_lists[0]->quantity}}</span>件
						</div>
						<div class='add-cart-choose pull-left' hidden>
							选择 :<span class="choose-size"></span>
						</div>										    	
					@endif	
					<div class='clearfix'></div>
				</div>
				<div class='clearfix'></div>
			</div>
                    @foreach($sku_info as $key=>$value)
						<div class='sku_info'>
						    <div class="skuinfo_key">{{$key}}</div>				
						    <div class="skuinfo_valuelist">			    
						    @foreach($value as $list)
						        <button class='skuinfo_value'>{{$list}}</button>
							@endforeach
							</div>
							<div class='clearfix'></div>
						</div>
						
					@endforeach				 
          
			<div class='add-cart-count'>
				<div class='pull-left'>
					购买数量
				</div>					
				<div data-trigger="spinner" class='spinner pull-right'>
				  <a href="javascript:;" data-spin="down" class='spinner-minus disabled'>-</a>
				  <input type="text" value="1" readonly data-rule="quantity" data-min="1"   class="inputnum">
				  <a href="javascript:;" data-spin="up" class='spinner-plus'>+</a>
				</div>
				
				    <div class="commodity-sku-quantity"></div>
				
			</div>
		</div>
		<div class='add-cart-btn1'>
			确定
		</div>
	</div>
			
<!-- 立即购买 -->
<div class="add-cart-cover2" id="buy_now"></div>
<span class="y-n-address" hidden>{{$has_address}}</span>
<!-- 立即购买无地址 -->
    <div class='buy-now-noaddress'>
			<div class='add-cart-content'>
				<div class='add-cart-close close2'></div>
                <ol class='add-cart-lists'>
                	<li class='add-cart-list'>
                	    收货人<input class="inputbuyerName"  autofocus="autofocus" />
                	</li>
                	<li class='add-cart-list'>
                	    联系电话<input class="inputbuyerNumber" type="tel" />
                	</li>
                	<li class='add-cart-list'>
                	    所在地区<input class="inputbuyerArea"  placeholder="请选择" id="inputbuyerArea" type="text" />
                	    <span class="right-arrow"></span>                 	    
                	</li>
                	<!-- <li class='add-cart-list'>
                	    街道<input class="inputbuyerStreet"  placeholder="请选择" />
                	    <img src="http://cache.dataguiding.com/img/shop/detail/right-arrow.png" class="right-arrow">
                	</li> -->
                </ol>
                <input class='addressDetail' placeholder="请填写详细地址，不少于5个字" />
			</div>
			<div class='add-cart-btn2'>
				确定
			</div>
	</div>
<!-- 立即购买有地址 -->
	<div class='buy-now-hasaddress'>
			<div class='add-cart-content'>
				<div class='add-cart-close close1'></div>
				<div class='add-cart-detail'>
				    <span class="commodity_id" hidden="">{{$detail->id}}</span>
					<div class='add-cart-img pull-left'>
						<img src="{{asset($detail->main_img)}}" class="addcart-commodityimg">
					</div>
					<div class='add-cart-text pull-left'>
					@if($detail->sku_info==0)
						<div class='add-cart-value'>
							￥{{$detail->min_price}}
						</div>
						<div class='add-cart-title'>
							{{$detail->commodity_name}}
						</div>
						<!-- <div class='add-cart-post pull-left'>
							运费 :{{$detail->express_price}}元
						</div> -->
						<div class='add-cart-rest pull-left'>
							库存 : <span class="quantity">{{$sku_lists[0]->quantity}}</span>件
						</div>
						<div class='add-cart-choose pull-left'hidden>
							选择 :<span class="choose-size"></span>
						</div>
					@else
					    <div class='add-cart-value'>
						@if($detail->min_price==$detail->max_price)
						    ￥{{$detail->min_price}}
	                    @else
	                        ￥{{$detail->min_price}}-{{$detail->max_price}}
	                    @endif
						</div>
						<div class='add-cart-title'>
							{{$detail->commodity_name}}
						</div>
						<!-- <div class='add-cart-post pull-left'>
							运费 :{{$detail->express_price}}元
						</div>	 -->
						<div class='add-cart-rest pull-left'>
							库存 : <span class="quantity">{{$sku_lists[0]->quantity}}</span>件
						</div>
						<div class='add-cart-choose pull-left'hidden>
							选择 :<span class="choose-size"></span>
						</div>									    	
					@endif	
						<div class='clearfix'></div>
					</div>
					<div class='clearfix'></div>
				</div>
               
                                            
                        @foreach($sku_info as $key=>$value)
							<div class='sku_info'>
							    <div class="skuinfo_key">{{$key}}</div>				
							    <div class="skuinfo_valuelist">			    
								    @foreach($value as $list)
								        <span class='skuinfo_value'>{{$list}}</span>
									@endforeach
								</div>
								<div class='clearfix'></div>
							</div>							
						 @endforeach
                    
              
				<div class='add-cart-count'>
					<div class='pull-left'>
						购买数量
					</div>					
					<div data-trigger="spinner" class='spinner pull-right'>
					  <a href="javascript:;" data-spin="down" class='spinner-minus disabled'>-</a>
					  <input type="text" value="1" readonly data-rule="quantity" data-min="1"   class="inputnum2">
					  <a href="javascript:;" data-spin="up" class='spinner-plus'>+</a>
					</div>
					
					    <div class="commodity-sku-quantity"></div>
					
				</div>
			</div>
			<div class='add-cart-btn3'>
				确定
			</div>
	</div>


@stop
<!-- 自己额外要加的js -->
@section('addJs')
	<script src="{{asset('shop/weui/js/swiper.min.js')}}"></script>
	<script src="{{asset('shop/js/spinner.min.js')}}"></script>
	<!-- <script src="http://cache.dataguiding.com/plugins/weui/js/city-picker.min.js"></script> -->
	<script src="{{asset('shop/weui/js/city-picker.js')}}"></script>
		
	<script type="text/javascript">
		$(document).ready(function(){
		//ajax设置csrf_token
		    $.ajaxSetup({
	            headers: { 'X-CSRF-TOKEN' : '{{ csrf_token() }}' }
	        });

	        if(!$('.ghost-y-n').html()){
	             $('#ghost').css('display','none');
	        }
	        
			//轮播
				$(".swiper-container").swiper({
					autoplay:5000,
					paginationClickable:true,
					speed:1000,
					loop:true
				});
			var a = '<?php echo $sku_json;?>';
			//console.log(a);
	       	// a = JSON.stringify(a);
	        a = JSON.parse(a);
	        console.log(a);
	        // var max=a[0].quantity;
	        // $('.inputnum').attr("data-max",max);
	        // $('.inputnum2').attr("data-max",max);
			//加减
			$(".spinner").spinner('changing', function(e, newVal, oldVal) {
			    // trigger lazed, depend on delay option.
			    if(newVal==1){
			    	$('.spinner-minus').addClass('disabled');
			    }else{
			    	$('.spinner-minus').removeClass('disabled');
			    }
			    // if(newVal==max){
			    // 	$('.spinner-plus').addClass('disabled');
			    // }else{
			    // 	$('.spinner-plus').removeClass('disabled');
			    // }
			});
	        
			$('.btn-cart').on('click',function(){
				$('.inputnum').val(1);		
				var top = $(window).outerHeight() - $('.add-cart').outerHeight();	
				$('.add-cart-cover1').fadeIn('fast',function(){
					$('.add-cart').show().animate({'top':top});
					$('body').css('overflow','hidden');
				});
				
				$('body').css('overflow','hidden');
				$('.commodity-sku-quantity').empty();

				var skuinfo=$(".add-cart").children(".add-cart-content").children('.sku_info');
				console.log(skuinfo);
				//单规格默认选中
					$.each(skuinfo,function(key,value){
						var count = $(this).find('.skuinfo_value').length;
		                if(count==1){
		                    $(this).find('.skuinfo_value').addClass('selected-sku');
		                }else{
		                	$(this).find('.selected-sku').removeClass('selected-sku');
		                }
					});
			      
			});
			$('.btn-buy').on('click',function(){
				var hasaddress=$('.y-n-address').html();
				var top;
	            $('.add-cart-cover2').fadeIn('fast',function(){
	            	if(!hasaddress){
	            		top = $(window).height() - $('.buy-now-noaddress').height();
			            $('.buy-now-noaddress').show().animate({'top':top});
			            $('body').css('overflow','hidden');
			        }else{
	            		top = $(window).height() - $('.buy-now-hasaddress').outerHeight();
			        	$('.buy-now-hasaddress').show().animate({'top':top});
			        	$('body').css('overflow','hidden');
			        }
	            });
	            
				$('.inputbuyerName').val('');
				$('.inputbuyerNumber').val('');
				$('.addressDetail').val('');
				$('.inputbuyerArea').val('');
				// $('.inputbuyerStreet').val('');
				$('.inputnum2').val(1);
				$('.commodity-sku-quantity').empty();

				var skuinfo=$(".buy-now-hasaddress").children(".add-cart-content").children('.sku_info');
				console.log(skuinfo);
				$.each(skuinfo,function(key,value){
					var count = $(this).find('.skuinfo_value').length;
	                if(count==1){
	                    $(this).find('.skuinfo_value').addClass('selected-sku')
	                    console.log($(this));
	                }else{
	                	$(this).find('.selected-sku').removeClass('selected-sku');
	                }
				});
			});
			$('.add-cart-close').on('click',function(){				
				$('.add-cart').animate({'top':'100%'},function(){
					$('.add-cart').hide();
				});
				$('.buy-now-noaddress').animate({'top':'100%'},function(){
					$('.buy-now-noaddress').hide();
				});
				$('.buy-now-hasaddress').animate({'top':'100%'},function(){
					$('.buy-now-hasaddress').hide();
				});
				$('.add-cart-cover1').hide();
				$('.add-cart-cover2').hide();
				$('body').css('overflow','auto');
			});
			// 加入购物车确定				            
			$('.add-cart-btn1').on('click',function(){
				var count=$('.inputnum').val();
				var id=$('.add-cart-detail').children('.commodity_id').html();
				var selected=$('#add_cart .selected-sku');
				var key=new Array();
				var value=new Array();
				var sku=$('#add_cart .sku_info');
				var sku_lists={};
				$.each(sku,function(i,val){
					key.push($(this).children('.skuinfo_key').html());
					value.push($(this).find('.selected-sku').html());
		        }); 
		        // var quantity=$(".commodity-sku-quantity").find('.quantity').html();
		        var quantity = $(this).parents(".add-cart").find(".quantity").html();	
		        console.log(quantity);				
	            if(selected.length==key.length){
	            	for(var i=0;i<key.length;i++){
	            	    sku_lists[key[i]]=value[i];
	                }
	                
	                if(parseInt(count)<=parseInt(quantity)&&parseInt(count)>0){
		               // sku_lists["年份"] = "2011年";
		                var skulists = JSON.stringify(sku_lists); 
		                console.log(skulists);
						$.ajax({
			                type:'POST',
			                url:'/shop/shopcart/add',                          
			                dataType:"json",
			                data:{
			                	commodity_id:id,
			                	count:count,
			                	sku_lists:skulists
			                },
			                success:function(data){
			                	if(data.status=="success"){
			                		$('.add-cart').animate({'top':'100%'},function(){
										$('.add-cart').hide();
									});
			                        $('.add-cart-cover1').hide();
							        $('body').css('overflow','auto');
				                    $.toast(data.msg);
				                    $('#ghost').css('display','block');
			                    }else{
			                    	if(data.msg == 'login'){
			                    		window.location.href = '/shop/login';
			                    	}else{
			                    		alert(data.msg);
			                    	}
			                    	
			                    }
		                    } 
			                             
			            });
			        }else if(count==0){
	                    alert('请选择数量')
			        }else{
			        	alert('超出剩余库存量，请重新选择！');
			        }
		            
	            }else{
	                alert('请选择商品属性!');
	            }                      			
			});
			//立即购买无地址
			$('.add-cart-btn2').on('click',function(){ 
			    var name=$('.inputbuyerName').val();
			    var phone=$('.inputbuyerNumber').val();
			    var district=$('.inputbuyerArea').val();
			    // var street=$('.inputbuyerStreet').val();
			    var address=$('.addressDetail').val();
			    if(name&&phone&&district&&address){
			    	$('.loading').show();
			    	$.ajax({
			                type:'POST',
			                url:'/shop/address/edit',                          
			                dataType:"json",
			                data:{
			                	address_id:'',
			                	receiver_name:name,
			                	receiver_phone:phone,
			                	district:district,
			                	street:'',
			                	address_details:address,
			                	'address_from':2,//表示post来源
			                	'commodity_id':'{{$detail->id}}'
			                },
			                success:function(data){
			                	$('.loading').hide();
			                	if(data.status=="success"){
			                		$('.buy-now-noaddress').hide();
			                        $('.buy-now-hasaddress').show('fast',function(){
			                        	var top = $(window).height() - $('.buy-now-hasaddress').outerHeight();
							        	$('.buy-now-hasaddress').animate({'top':top});
							        	$('body').css('overflow','hidden');
			                        });	                        
			                    }else{
			                    	$('.buy-now-noaddress').animate({'top':'100%'},'fast',function(){
			                    		$('.buy-now-noaddress').hide('fast',function(){
			                    			$('.add-cart-cover2').hide('fast',function(){
			                    				if(data.msg == 'login'){
						                    		/*$.confirm('您还没有登录！',function(){
						                    			window.location.href="/shop/auth/login";
						                    		},function(){

						                    		});*/
						                    		window.location.href="/shop/address/checkoauth/{{$detail->id}}";
						                    	}else{
						                    		alert(data.msg);
						                    	}
			                    			});
			                    		})
			                    	});
			                    		
			                    }
		                    } 
			                             
			        });
			    }else{
			    	alert('请把信息填写完整');
			    }                   
			});
	        //立即购买有地址确定
	        $('.add-cart-btn3').on('click',function(){
	            var count=$('.inputnum2').val();
				var id=$(this).parents().find('.commodity_id').html();
				var sku_lists={};			
				var key=new Array();
				var value=new Array();
				var sku=$('.buy-now-hasaddress .sku_info');
				var selected=$('.buy-now-hasaddress .selected-sku');
				$.each(sku,function(i,val){
					key.push($(this).children('.skuinfo_key').html());
					value.push($(this).find('.selected-sku').html());
	            }); 
	            // var quantity=$(".commodity-sku-quantity").find('.quantity').html();
	            var quantity = $(this).parents(".buy-now-hasaddress").find(".quantity").html();
	            if(selected.length==key.length){
	                for(var i=0;i<key.length;i++){
	            	    sku_lists[key[i]]=value[i];
	                }
	                if(parseInt(count)<=parseInt(quantity)&&parseInt(count)>0){
		               // sku_lists["年份"] = "2011年";
		                var skulists = JSON.stringify(sku_lists);
		                 //console.log(skulists);
			           // $('.add-cart-cover2').hide();
			            //$('.buy-now-hasaddress').hide();
					    $('body').css('overflow','auto');
				        window.location.href="/shop/order/buynow/"+id+"/"+count+"/"+skulists;		                    	             		           
			        }else if(count==0){
	                    alert('请选择数量')
			        }else{
				        alert('超出剩余库存量，请重新选择！');
				    }   
	            }else{
	                alert('请选择商品属性!');
	            }        
	        });

	        //联系卖家
			$(document).on("click", ".btn-number", function() {
	            $.confirm("确定拨打电话{{$contact}}吗？", "拨打电话！", function() {
	              window.location.href="tel:{{$contact}}";
	            }, function() {
	              //取消操作
	            });
	        });

	        //返回店铺首页
	        $('.btn-shop').on('click',function(){
	        	var shopid=$('.shopid').html();
	             $.ajax({
			                type:'POST',
			                url:'/shop/front/tabstore',                          
			                dataType:"json",
			                data:{
			                	shop_id:shopid
			                },
			                success:function(data){
			                	if(data.status=="success"){
		                             window.location.href="/shop/front/index";
			                    }else{
			                    	alert(data.msg);
			                    }
		                    } 
			                             
			        });
	        });

	        $("#inputbuyerArea").cityPicker({
	            title: ""
	        });
	        //判断商品是否上架(状态)
		       $.each(a,function(key,value){
		       		var skuinfo_value_lists = $('.add-cart').find('.skuinfo_value');
		       		var skuinfo_value_list_1 = new Array();
		       		var skuinfo_value_list_2 = new Array();
		       		var disabled_logo;
		       		if(a[key].status==0){
		       			$.each(a[key].commodity_sku,function(i,val){
		       				skuinfo_value_list_2.push(a[key].commodity_sku[i]);
		       			})
		       			
		       			$.each(skuinfo_value_lists,function(j){
		       				skuinfo_value_list_1.push($(this).html());

		       			});
		       			for(var c=0;c<skuinfo_value_lists.length;c++){
			       			for(var b=0;b<skuinfo_value_list_2.length;b++){
			       				if(skuinfo_value_list_2[b]==skuinfo_value_list_1[c]){
			       					console.log(skuinfo_value_list_1[c]);
			       					disabled_logo = skuinfo_value_list_1[c];
			       				}
			       				$('.add-cart-content').find('.skuinfo_value').each(function(){
										if($(this).html()==disabled_logo){
											$(this).addClass("disabled");
										}
			       				});
			       			}		       			
			       		}
			       	}
		       });
			//选择商品规格尺寸
	        $('.skuinfo_value').on('click',function(){
	        	if($(this).hasClass('disabled')){
	        	}else{
	        		$(this).parents('.sku_info').find('.selected-sku').removeClass('selected-sku');//移除其他选中状态
	            	$(this).addClass('selected-sku');//点击选中当前
	        	}
	        	//定义变量	            
	            var count1=$('.inputnum').val();
	            var count2=$('.inputnum2').val();
				var id=$(this).parents().find('.commodity_id').html();
				var selected1=$('#add_cart .selected-sku');
				var selected2=$('.buy-now-hasaddress .selected-sku');
				var key1=new Array();
				var key2=new Array();
				var value1=new Array();
				var value2=new Array();
				var sku1=$('#add_cart .sku_info');
				var sku2=$('.buy-now-hasaddress .sku_info');
				var skulists1={};
				var skulists2={};
				var object = $(this);
				var obj=$(this).parents().find(".add-cart-content");
				$('.choose-size').html('');
				$('.choose-size').append($('.skuinfo_key').html());
				$('.add-cart-choose').css('display','block');
				$.each(sku1,function(i,val){
					key1.push($(this).find('.skuinfo_key').html());
		        });
		        $.each(sku1,function(i,val){
				    value1.push($(this).find('.selected-sku').html());
		        });
		        $.each(sku2,function(i,val){
					key2.push($(this).find('.skuinfo_key').html());
		        }); 
		        $.each(sku2,function(i,val){
				    value2.push($(this).find('.selected-sku').html());
		        });
		        //加入购物车
		            if(selected1.length==key1.length){
			        	for(var i=0;i<key1.length;i++){
			            	    skulists1[key1[i]]=value1[i];
			                }	       
			            // $('.inputnum').val(1);
			            for(var i=0;i<a.length;i++){
			                var s=0;
			                var flag=0;
			            	$.each(a[i].commodity_sku,function(keys,value){
			            	    s++;            		
				            	if(a[i].commodity_sku[keys]==skulists1[keys]){
				            		if(s==key1.length){
				            			if(a[i].status){
				            				// object.parents('.sku_info').find('.selected-sku').removeClass('selected-sku');
				            				// object.addClass('selected-sku');
				            				// object.removeClass("disabled");
				            				// object.attr("disabled","false");
					            			flag=1;
								            $('.add-cart-value').html("￥"+a[i].price);
								            $('.commodity-sku-quantity').html("(剩余 : <span class='quantity'>"+a[i].quantity+"</span>件)");
											obj.children().find(".quantity").html(a[i].quantity);
								            return false;
				            			}else{
				            				// object.addClass("disabled");
				            				// object.removeClass('selected-sku');
				            				// object.attr("disabled","true");
				            				$('.add-cart-value').html("￥"+a[i].price);
								            $('.commodity-sku-quantity').html("(剩余 : <span class='quantity'>"+a[i].quantity+"</span>件)");
											obj.children().find(".quantity").html(a[i].quantity);
				            			}
				            			
						            }else{
						            	return true;
						            }
		                        
				            	}else{
				            		return false;
				            	}			            	   			                
			                });
	                        if(flag==1){
	                        	break;
	                        }				            					            	
			            }
			        }
			    //立即购买有地址
		        	if(selected2.length==key2.length){
			        	for(var i=0;i<key2.length;i++){
			            	    skulists2[key2[i]]=value2[i];
			                }
			            // $('.inputnum2').val(1);
			            for(var i=0;i<a.length;i++){
			                var s=0;
			            	var flag2=0;
			            	$.each(a[i].commodity_sku,function(keys,value){
			            	    s++;            		
				            	if(a[i].commodity_sku[keys]==skulists2[keys]){
				            		if(s==key2.length){
				            			if(a[i].status){
				            				// object.parents('.sku_info').find('.selected-sku').removeClass('selected-sku');
				            				// object.addClass('selected-sku');
				            				// object.removeClass("disabled");
				            				// object.attr("disabled","false");
				            				flag2=1;
								            $('.add-cart-value').html("￥"+a[i].price);
								            $('.commodity-sku-quantity').html("(剩余 : <span class='quantity'>"+a[i].quantity+"</span>件)");
								            obj.children().find(".quantity").html(a[i].quantity);
								            
								            return false;
				            			}else{
				            				// object.addClass("disabled");
				            				// object.removeClass('selected-sku');
				            				// object.attr("disabled","true");
				            				$('.add-cart-value').html("￥"+a[i].price);
								            $('.commodity-sku-quantity').html("(剩余 : <span class='quantity'>"+a[i].quantity+"</span>件)");
								            obj.children().find(".quantity").html(a[i].quantity);
								            
				            			}
				            			
						            }else{
						            	return true;
						            }
		                        
				            	}else{
				            		return false;
				            	}			            	   			                
			                });
	                        if(flag2==1){
	                        	break;
	                        }
				            	
			            }
		            }
	        });
	})
	</script>
@stop

@section("jsSDK")
<script type="text/javascript">
  //微信分享
  wx.config(<?php echo $js->config(array('onMenuShareTimeline','onMenuShareAppMessage'), false) ?>);
  document.addEventListener('WeixinJSBridgeReady', function onBridgeReady() {
                // 发送给好友
                WeixinJSBridge.on('menu:share:appmessage', function (argv) {
                    WeixinJSBridge.invoke('sendAppMessage', {
                        "appid": "123",
                        "img_url": "{{asset($detail->main_img)}}",
                        "img_width": "160",
                        "img_height": "160",
                        "link": "shop.dataguiding.com/shop/front/detail?commodity_id={{$detail->id}}&b={{Session::get('brand_id')}}&shop_id={{Session::get('shop_id')}}&from=share",
                        "desc":  "{{$detail->brief_introduction}}",
                        "title": "{{$detail->commodity_name}}"
                    }, function (res) {
                        console.log(res);
                    })
                });

                // 分享到朋友圈
                WeixinJSBridge.on('menu:share:timeline', function (argv) {
                    WeixinJSBridge.invoke('shareTimeline', {
                        "img_url": "{{asset($detail->main_img)}}",
                        "img_width": "160",
                        "img_height": "160",
                        "link": "shop.dataguiding.com/shop/front/detail?commodity_id={{$detail->id}}&b={{Session::get('brand_id')}}&shop_id={{Session::get('shop_id')}}&from=share",
                        "desc":  "{{$detail->brief_introduction}}",
                        "title": "{{$detail->commodity_name}}"
                    }, function (res) {
                        console.log(res);
                    });
                });
            }, false)
</script>
@stop