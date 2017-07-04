<div class="living">
		<div class="couponstatus">进行中</div>
		@if(count($coupon_listing))
		<table>
		    <thead>
		        <tr class="couponhead">
					<th width="10%">优惠券名称</th>
					<th width="15%">价值（元）</th>
					<th width="10%">领取规则</th>
					<th width="30%">有效期</th>
					<th width="10%">领取人/次</th>
					<th width="10%">已使用</th>
					<th width="15%">操作</th>
				</tr>
			</thead>
			<tbody>
			    @foreach($coupon_listing as $list)
				<tr>
				    <td hidden class="coupon_id">{{$list->id}}</td>
				    <td hidden class="address">http://shop.dataguiding.com/shop/coupon/share/{{$list->id}}/{{$list->$brand_id}}</td>
					<td>{{$list->name}}</td>
					<td><div>{{$list->sum}}</div><div>最低消费：{{$list->use_condition}}</div></td>
					<td><div>{{$list->gettimes}}张/人</div><div>库存：{{$list->number-$list->quantity}}</div></td>
					<td>{{$list->validity_start}}至{{$list->validity_end}}</td>
					<td>{{$list->person_times}}/{{$list->quantity}}</td>
					<td>{{$list->used_num}}</td>					
					<td><a href="/Brand/coupon/edit/{{$list->id}}"><span>编辑</span></a><span class="overdue">失效</span><span class="link">链接</span>
					<div class="copy">
						<input type="text" id="copy_input" class="copy_input">
						<input type="button"  class="btn-copy allhover" value="复制">
						<span class="mention">提示：若您的浏览器不支持此功能，请自行复制。</span>
					</div>
					</td>
				</tr>
				@endforeach
			</tbody>
		</table>
		@else
		<div class="error-mention"> 咦，还没有数据哎...</div>             
		@endif 
		<div class="divide-page">
            @include('pagination.page', ['paginator' => $coupon_listing])
      </div>
	</div>

	<script type="text/javascript">
	//复制
		$('.living .btn-copy').each(function(){
		    $(this).on('click',function(){
		    	var e=$(this).prev();//对象是content 
		        e.select(); //选择对象 
		        document.execCommand("Copy"); //执行浏览器复制命令
		        $(this).parent('.copy').css('display','none');
		        $().toastmessage('showSuccessToast', "已复制");    
		    });
	    });
	    $('.living .link').each(function(){
		    $('.living').on('click','.link',function(event){
		    	$('.copy').css('display','none');
		    	var address=$(this).parents('tr').children('.address').html();
		    	$(this).next().children('.copy_input').val(address);
		        $(this).parents('tr').find('.copy').css('display','block'); 
		        event.stopPropagation();//阻止冒泡   
		    });					     

	    });
	    $('body').on("click",function(){ 
	    	$('.living .link').each(function(){
	    		$(this).parents('tr').find('.copy').css('display','none'); 
	    	});

		}); 	
		    
	</script>