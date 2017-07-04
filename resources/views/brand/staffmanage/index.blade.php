<!-- auth:wuwenjia -->
@extends('layouts.app')
@section('siderbar')
@include('layouts.siderbar')
@endsection

@section('addCss')
<link rel="stylesheet" type="text/css" href="{{ URL::asset('shopadmin/css/accountManage.css')}}">
@endsection

@section('content')
{{Session::get('Message')}}
	<div class="accountstaff">
		<div class="accountManage-title">
			  	 	<span>员工列表</span>
                    <select class="shopnamelists">
			       			<option>全部</option>
			       			<option value="{{Session::get('brandname')}}总店">{{Session::get('brandname')}}总店</option>
			       			@foreach($shoplists as $list)
			       			    <option value="{{$list->shopname}}">{{$list->shopname}}</option>
			       			@endforeach
			       	</select>
			  	 	<button id="staff-btn"><!-- <img src="btn-brand.png"> -->创建账号</button>
		</div>

    	<table>
    	@if($shopstaff_count)
    		<thead>
                <tr>
                    <th width="13.8%">员工姓名</th>
                    <th width="13.8%">手机号</th>
                    <th width="18.1%">所属分店</th>
                    <th width="13.8%">加入时间</th>                    
                    <th width="40.5%">操作</th>
                </tr>
            </thead>
            
            <tbody class="tab-lists">
            	@foreach($shopstaff_lists as $list)
            	<tr>
            		<td hidden class="staff_id">{{$list->id}}</td>
            		<td hidden class="shop_id">{{$list->shop_id}}</td>
                    <td class="name">{{$list->name}}</td>
                    <td class="phone">{{$list->phone}}</td>                    
                    <td class="tableshopname">{{$list->shopname}}</td>
                    <td>{{$list->created_at}}</td>
                    <td><div class="staus">
                                @if($list->status)
                                    <input type="button" value="冻结" class="frozen">
                                @else
                                	<input type="button" value="开通" class="open">
                                @endif
                                    <input type="button" value="编辑" class="img-edit">
                                    <input type="button" value="删除" class="img-delete">
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
            
            @else   
          	<div class="error-mention"> 咦，还没有数据哎...</div>             
          	@endif  
        </table>
    </div>
    <div class="frozen-window" hidden>
	       <div class="question">您确定要冻结此员工账号吗？</div>
	       <input type="password" class="del-line" placeholder="请输入登录密码">
	       <div>
	            <button  class="cancle-frozen allhover">取消</button>
	            <button class="confirm-frozen allhover">确定</button>
	       		<!-- <img src="{{asset('shopadmin/images/btn-cancel.png')}}"  class="cancle-frozen">
	       		<img class="confirm-frozen" src="{{asset('shopadmin/images/btn-determine.png')}}"> -->
	       </div>
	</div>
	<div class="open-window" hidden>
	       <div class="question">您确定要开通此员工账号吗？</div>
	       <input type="password" class="del-line" placeholder="请输入登录密码">
	       <div>
	            <button  class="cancle-open allhover">取消</button>
	            <button class="confirm-open allhover">确定</button>
	       			<!-- <img src="{{asset('shopadmin/images/btn-cancel.png')}}"  class="cancle-open">
	       			<img class="confirm-open" src="{{asset('shopadmin/images/btn-determine.png')}}"> -->
	       </div>
	</div>
    <div class="newStaff-window" hidden>
	  			   <div class="createdtitle">创建员工账号</div>
			       <div class="shopstaff-name{{ $errors->has('name') ? ' has-error' : '' }}">
			       		<span>姓名：</span>
			       		<input type="text" name="name" class="newname" required="required" value="{{old('name')}}" placeholder="请输入员工姓名">
			       	</div>
			       	<div class="shopstaff-phone{{ $errors->has('phone') ? ' has-error' : '' }}">
			       		<span>电话：</span>
			       		<input type="text" name="phone" class="newphone" required="required" value="{{old('phone')}}" placeholder="请输入员工手机号">
			       	</div>
			       	<div class="mention">手机号为其登录账号，密码默认为123456</div>
			       	<div class="shopname{{ $errors->has('shopname') ? ' has-error' : '' }}">
			       		<span>所属分店：</span>
			       		<select class="shoplists" name="shop_id" value="">
			       		    <option value="0">{{Session::get('brandname')}}总店</option>
			       			@foreach($shoplists as $list)
			       			    <option value="{{$list->id}}">{{$list->shopname}}</option>
			       			@endforeach
			       		</select>
			       	</div>
			       <div class="bottom-btn">
			                <button  class="cancle-new allhover">取消</button>
	                        <button  type="submit" class="confirm-new allhover">确定</button>
			       			<!-- <img class="cancle-new" src="{{asset('shopadmin/images/btn-cancel.png')}}">
			       			<button type="submit"><img class="confirm-new" src="{{asset('shopadmin/images/btn-determine.png')}}"></button> -->
			       </div>
	</div>
	<div class="editStaff-window" hidden>
	  			   <div class="createdtitle">账号信息</div>
			       <div class="shopstaff-name">
			       		<span>姓名：</span>
			       		<input type='text' class="detailname" required='required' value='' placeholder='请输入员工姓名'>
			       	</div>
			       	<div class="shopstaff-phone">
			       		<span>电话：</span>
			       		<input type='text' class="detailphone" required='required' value='' placeholder='请输入员工手机号'>
			       	</div>
			       	<div class="mention">手机号为其登录账号，密码默认为123456</div>
			       	<div class="shopname">
			       		<span>所属分店：</span>
			       		<select class="shoplists" value="">
			       		    <option value="0">{{Session::get('brandname')}}总店</option>
			       			@foreach($shoplists as $list)
			       			    <option value="{{$list->id}}">{{$list->shopname}}</option>
			       			@endforeach
			       		</select>
			       	</div>
			       <div class="bottom-btn">
			                <button  class="cancle-edit allhover">取消</button>
	                        <button  class="confirm-edit allhover">确定</button>
			       			<!-- <img class="cancle-edit" src="{{asset('shopadmin/images/btn-cancel.png')}}">
			       			<button type="submit"><img class="confirm-edit" src="{{asset('shopadmin/images/btn-determine.png')}}"></button> -->
			       </div>
	</div>
	<div class="delete-window" hidden>
	       <div class="question">您确定要删除此员工账号吗？</div>
	       <input type="password" class="del-line" placeholder="请输入登录密码">
	       <div>
	            <button  class="cancle-del allhover">取消</button>
	            <button class="confirm-del allhover">确定</button>
	       		<!-- <img src="{{asset('shopadmin/images/btn-cancel.png')}}"  class="cancle-del">
	       		<img class="confirm-del" src="{{asset('shopadmin/images/btn-determine.png')}}"> -->	       			
	       </div>
	</div>
<Script type="text/javascript">
$('.side-list').find('.onsidebar').removeClass('onsidebar');
$('.staffmanage').addClass('onsidebar');
    //筛选分店员工
            $('.shopnamelists').change(function(){            	
                $('.tab-lists .tableshopname').each(function(){
                	$(this).parent('tr').css('display','');
                	if($('.shopnamelists option:selected').val()=='全部'){
                        $(this).parent('tr').css('display','');
                    }else if($(this).html()!=$('.shopnamelists option:selected').val()){
                        $(this).parent('tr').css('display','none');
                    }
                }); 
            });

	//悬浮效果
			$(".frozen,.open,.img-delete,.img-edit").on("mouseover",function(){
	          $(this).css('color','#fff');
	          $(this).css('background-color','#fb2d5c');
	      	});
	      	$(".frozen,.open,.img-delete,.img-edit").on("mouseout",function(){
	          $(this).css('color','#999');
	          $(this).css('background-color','#fff');
	      	});

	//状态转换
			$(".frozen").on('click',function(){
		        	cancel_index1= layer.open({
			             type: 1,
			             title:false,
			             skin: 'layui-layer-demo', //样式类名
			             closeBtn: 0, //不显示关闭按钮
			             shift: 2,
			             shadeClose: true, //开启遮罩关闭
			             area : ["600px" , '250px'],
			             content:$('.frozen-window'),
		            });
		            var shop_id=$(this).parents('tr').find('.shop_id').html();	
		            var id=$(this).parents('tr').find('.staff_id').html();		          
		            $('.confirm-frozen').on('click',function(){
		            	    var password=$(".frozen-window .del-line").val();
		                    $.ajax({
				                type:'POST',
				                url:'/Brand/staffmanage/changestatus',
				                data:{
				                    shopstaff_id:id,
		                            password:password,
		                            shop_id:shop_id
				                },
				                dataType:"json",
				                success:function(result){
				                    if(result.status=="success"){
				                        layer.close(cancel_index1);
				                        window.location.reload();
				                    }else{
				                        alert(result.message);
				                    }                    
				                }
				            });
				    });
		    });
		    $(".open").on('click',function(){
		        	cancel_index2= layer.open({
			             type: 1,
			             title:false,
			             skin: 'layui-layer-demo', //样式类名
			             closeBtn: 0, //不显示关闭按钮
			             shift: 2,
			             shadeClose: true, //开启遮罩关闭
			             area : ["600px" , '250px'],
			             content:$('.open-window'),
		            });
		            var shop_id=$(this).parents('tr').find('.shop_id').html();
		            var id=$(this).parents('tr').find('.staff_id').html();		            
		            $('.confirm-open').on('click',function(){
		            	    var password=$(".open-window .del-line").val();
		                    $.ajax({
				                type:'POST',
				                url:'/Brand/staffmanage/changestatus',
				                data:{
				                    shopstaff_id:id,
		                            password:password,
		                            shop_id:shop_id
				                },
				                dataType:"json",
				                success:function(result){
				                    if(result.status=="success"){
				                        layer.close(cancel_index2);
				                        window.location.reload();
				                    }else{
				                        alert(result.message);
				                    }                    
				                }
				            });
				    });
		    });
		    		      		 
	//新建员工账号
		    $("#staff-btn").on('click',function(){
						cancel_index3= layer.open({
				             type: 1,
				             title:false,
				             skin: 'layui-layer-demo', //样式类名
				             closeBtn: 0, //不显示关闭按钮
				             shift: 2,
				             shadeClose: true, //开启遮罩关闭
				             area : ["600px" , '520px'],
				             content:$('.newStaff-window'),
			            });
			            $('.confirm-new').on('click',function(){
                            var i_name=$('.newStaff-window .newname').val();
                            var i_phone=$('.newStaff-window .newphone').val();
                            var i_shop_id=$('.newStaff-window .shoplists option:selected').val();
                            console.log(i_name+" "+i_phone+' '+i_shop_id);
                            $.ajax({
					                type:'POST',
					                url:'/Brand/staffmanage/add',
					                data:{					                	
			                            name:i_name,
			                            phone:i_phone,
			                            shop_id:i_shop_id,
					                },
					                dataType:"json",
					                success:function(result){
					                    if(result.status=="success"){
					                        layer.close(cancel_index3);
					                        window.location.reload();
					                    }else{
					                        alert(result.message);
					                    }                    
					                }
					        });
			            });

			});
	//编辑
			var ID;
			var Shop_id;
	        $(".img-edit").on('click',function(){
						cancel_index5= layer.open({
				             type: 1,
				             title:false,
				             skin: 'layui-layer-demo', //样式类名
				             closeBtn: 0, //不显示关闭按钮
				             shift: 2,
				             shadeClose: true, //开启遮罩关闭
				             area : ["600px" , '520px'],
				             content:$('.editStaff-window'),
			            });
			            var name=$(this).parents('tr').children('.name').html();
			            var phone=$(this).parents('tr').children('.phone').html();
			            Shop_id=$(this).parents('tr').children('.shop_id').html();
			            ID=$(this).parents('tr').children('.staff_id').html();
			            $('.editStaff-window .detailname').val(name);
			            $('.editStaff-window .detailphone').val(phone);
			            $('.editStaff-window .shoplists').val(Shop_id);
			            console.log(name+" "+phone+' '+Shop_id+' '+ID);
			});
			$('.confirm-edit').on('click',function(){
                            var i_name=$(this).parents('.editStaff-window').children().find('.detailname').val();
                            var i_phone=$(this).parents('.editStaff-window').children().find('.detailphone').val();
                            var i_shop_id=$(this).parents('.editStaff-window').children().find('.shoplists option:selected').val();
                            console.log(i_name+" "+i_phone+' '+i_shop_id+' '+ID+' '+Shop_id);
                            $.ajax({
					                type:'POST',
					                url:'/Brand/staffmanage/changeinfo',
					                data:{					                	
					                    shopstaff_id:ID,
			                            name:i_name,
			                            phone:i_phone,
			                            shop_id:i_shop_id,
			                            old_shop:Shop_id
					                },
					                dataType:"json",
					                success:function(result){
					                    if(result.status=="success"){
					                        layer.close(cancel_index5);
					                        window.location.reload();
					                    }else{
					                        alert(result.message);
					                    }                    
					                }
					        });
						});
			
			
		    
	//删除
		    $(".img-delete").on("click",function(){
			      		cancel_index4= layer.open({
					             type: 1,
					             title:false,
					             skin: 'layui-layer-demo', //样式类名
					             closeBtn: 0, //不显示关闭按钮
					             shift: 2,
					             shadeClose: true, //开启遮罩关闭
					             area : ["600px" , '250px'],
					             content:$('.delete-window'),
				        });
				        var shop_id=$(this).parents('tr').find('.shop_id').html();
				        var id=$(this).parents('tr').find('.staff_id').html();
				        $('.confirm-del').on('click',function(){
				        	    var password=$(".delete-window .del-line").val();
			                    $.ajax({
					                type:'POST',
					                url:'/Brand/staffmanage/delete',
					                data:{
					                    shopstaff_id:id,
			                            password:password,
			                            shop_id:shop_id
					                },
					                dataType:"json",
					                success:function(result){
					                    if(result.status=="success"){
					                        layer.close(cancel_index4);
					                        window.location.reload();
					                    }else{
					                        alert(result.message);
					                    }                    
					                }
					            });
					    });

		    });

	//点击取消按钮，弹窗消失，状态不改变
	      	$(".cancle-frozen").on("click",function(){
	          			$(".frozen-window").css("display","none");
	             		layer.close(cancel_index1);
	      	});
	      	$(".cancle-open").on("click",function(){
	          			$(".open-window").css("display","none");
	             		layer.close(cancel_index2);
	      	});
	      	$(".cancle-new").on("click",function(){
				        $(".newStaff-window").css("display","none");
				        layer.close(cancel_index3);
		    });
		    $(".cancle-del").on("click",function(){
				        $(".delete-window").css("display","none");
				        layer.close(cancel_index4);
		    });
		    $(".cancle-edit").on("click",function(){
				        $(".editStaff-window").css("display","none");
				        layer.close(cancel_index5);
		    });
	//点击确定，提交信息
		    
		
 </script>
@endsection