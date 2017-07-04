<table>
    <thead>
        <tr>
            <th width="50%">商品名称</th>
            <th width="50%">创建时间</th>
        </tr>
    </thead>
  
    <tbody class="choose-lists" id="shop_commodity">
        @foreach($commoditys as $commodity)
			<tr>
				<td class="commodity_id" hidden>{{$commodity->id}}</td>
				<td>
					<div class="check btn_check"></div>
					<img class="choose_img" src="{{asset($commodity->main_img)}}">
          <span class="commodity_name">{{$commodity->commodity_name}}</span>
				</td>
				<td>{{$commodity->created_at}}</td>
			</tr>
		@endforeach
    </tbody> 
</table>
<div class="holder"></div> 
<div class="div_btn">
    <button class="btn_cancle allhover">取消</button>
    <button class="btn_confirm allhover">确定</button>
</div>
            
<script>
	$("div.holder").jPages({
      containerID : "shop_commodity",
      previous : "《",
      next : "》",
      perPage : 10,
    });
    $('.btn_cancle').on('click',function(){
        $('.new-window').css('display','block');
        $('.choose_commodity').css('display','none');
    });
    $('.btn_confirm').on('click',function(){
        var id;
        $('.btn_check').each(function(){
            if($(this).css('border-style')=="none"){
                id=$(this).parents('tr').children('.commodity_id').html();
                return false;
            }
        });
        var commodityid='http://shop.dataguiding.com/shop/front/detail?commodity_id='+id;
        $("input[name='http_src']").attr('value',commodityid);
        $('.new-window').css('display','block');
        $('.choose_commodity').css('display','none');
    });
    $('.choose-lists').on('click','.check',function(){
            if($(this).css('border-style')!="none"){                
                $('.btn_check').css('border','1px solid');
                $('.btn_check').css('border-color','#d6d6d6');
                $('.btn_check').css('background-image',"");
                $(this).css('border','none');
                $(this).css('background-image',"url('/shopstaff/images/check.png')");  
            }else{
                $(this).css('border','1px solid');
                $(this).css('border-color','#d6d6d6');
                $(this).css('background-image',"");
            }                  
        });
        
</script>