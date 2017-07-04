<div class="over">
		<div class="couponstatus">已结束</div>
		@if(count($coupon_listed))
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
				@foreach($coupon_listed as $list)
				<tr>
				    <td hidden class="coupon_id">{{$list->id}}</td>
					<td>{{$list->name}}</td>
					<td><div>{{$list->sum}}</div><div>最低消费：{{$list->use_condition}}</div></td>
					<td><div>{{$list->gettimes}}张/人</div><div>库存：{{$list->number-$list->quantity}}</div></td>
					<td>{{$list->validity_start}}至{{$list->validity_end}}</td>
					<td>{{$list->person_times}}/{{$list->quantity}}</td>
					<td>{{$list->used_num}}</td>
					<td><a href="/Brand/coupon/edit/{{$list->id}}"><span>编辑</span></a><span class="btn-del">删除</span></td>
				</tr>
				@endforeach
			</tbody>
		</table>
		@else
		<div class="error-mention"> 咦，还没有数据哎...</div>             
		@endif 
		<div class="divide-page">
            @include('pagination.page', ['paginator' => $coupon_listed])
      </div>
	</div> 
