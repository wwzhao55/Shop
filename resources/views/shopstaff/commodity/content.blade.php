<div class="list-container"> 
  @foreach($commodity_lists as $list)
  
    <div class="list_content" >
        <span hidden class="sku_id">{{$list->sku_id}}</span>
        <span hidden class="commodity_id">{{$list->commodity_id}}</span>
        <span hidden class="sku_info">{{$list->sku_info}}</span>
        <div class="checkbox"></div>
        <div class="check_click">
          <img src="{{asset('shopstaff/images/check.png')}}" alt="">
        </div>
        <div class="img_name">      
          <img src="{{asset($list->main_img)}}" class="img_size" alt="">
        </div>
        <div class="name_view">
          <span class="name_sort">{{$list->commodity_name}}</span>
          <span hidden>{{$list->group_id}}</span>
          <span class="name_sort">{{$list->group_name}}</span><br><br>
          @foreach($list->commodity_sku as $key=>$value)
          <span>{{$key}}: {{$value}}</span>
          @endforeach<br><br>
          <span>￥{{$list->price}}</span>      
        </div>
        <div class="stock">
          <span class="quantity">{{$list->quantity}}</span>
        </div>
        <div class="salenumber"><span >{{$list->saled_count}}</span>
        </div>
        <!-- <div class="status">
          @if($list->status == 1)
          <span>出售中</span>
          @elseif($list->quantity == 0)
          <span>售罄</span>
          @elseif($list->status == 3)
          <span>暂停</span>
          @endif
        </div>
        <div class="update">
          @if($list->status == 1)
          <span class="update-stock">更新库存</span>&nbsp&nbsp
          <span class="down-stock">下架</span>
          @elseif($list->quantity == 0)
          <span class="update-stock">更新库存</span>
          @elseif($list->status == 3)
          <span class="update-stock">更新库存</span>&nbsp&nbsp
          <span class="up-stock ">上架</span>
          @endif 
        </div> -->
        @if($list->status == 1)
        <div class="status">出售中</div>
        <div class="update">
          <span class="update-stock">更新库存</span>&nbsp&nbsp
          <span class="down-stock">下架</span>
        </div>
        @elseif($list->status == 0)
        <div class="status">售罄</div>
        <div class="update">
          <span class="update-stock">更新库存</span>      
        </div>
        @elseif($list->status == 2)
        <div class="status">暂停</div>
        <div class="update">
          <span class="update-stock">更新库存</span>&nbsp&nbsp
          <span class="up-stock">上架</span>
        </div>
        @endif 
        <div class="clearfix"></div>     
    </div>
  @endforeach
  
  <div><input type="button" value="下架" class="button"></div>
  <div class="page_control">
    <?php echo $commodity_lists->render(); ?>
  </div>
  <!-- layer1弹窗 -->
   <div class="layer1" style="display:none;">
      <div class="now">
        <span class="now-stock">当前库存</span>
        <span class="get-stock"></span>
      </div>
      <div class="latest">
        <span class="latest-stock">最新库存</span>
        <input type="text" class="latest-number" name="" value="">
      </div>
      <div class="up-btn1">
        <input type="text" hidden class="commodityid">
        <input type="text" hidden class="skuid">
        <input type='text' hidden class='window-status'>
        <input type="button" value="取消" class="cancle">
        <input type="submit" class="up-btn" name="" value="上架">
      </div> 
      <div class="tip">注：您现在正在更改库存，商品已暂停销售</div>
   </div>
  <!-- layer2弹窗    -->
   <div class="layer2" style="display:none;">
      <div class="remind">
        <p class="remind1">您的商品还在出售中，您确定要下架吗？</p>
        <p class="remind2">注：下架后微信端该产品就消失不为用户展示了</p>
      </div>
      <div class="choose">
        <input type="text" hidden class="commodityid">
        <input type="text" hidden class="skuid">
        <input type="submit" name="" class="choose-btn choose-cancel" value="取消">
        <input type="submit" name="" class="choose-btn choose-sure" value="确定">
      </div>
   </div>  
</div>
<script type="text/javascript">

// 选中控制
  $(".container").on("click",".checkbox1",function(){
      $(this).css("display","none");
      $(".checkbox").css("display","none");
      $(".check_click").css("display","block");
      $(".check_click1").css("display","block") 
  });
  $(".container").on("click",".check_click1",function(){
      $(this).css("display","none");
      $(".checkbox1").css("display","block");
      $(".checkbox").css("display","block");
      $(".check_click").css("display","none");      
  });

  var chooselen=0;
  var num=new Array();
  $('.sku_id').each(function(){
    var a=$(this).html();
    num.push(a);
  });         
  $(".list-container").on("click",".check_click",function(){
      $(this).css("display","none");
      $(this).parents('.list_content').children(".checkbox").css("display","block");
      if ($(this).parents('.list_content').children(".checkbox").css("display","block")) {
          chooselen-- ;        
      }
      if (chooselen!=num.length) {
            $(".checkbox1").css("display","block");
            $(".check_click1").css("display","none");
          }
      else if (chooselen==num.length){
            $(".checkbox1").css("display","none");
            $(".check_click1").css("display","block");
          }
  });
  
  $(".list-container").on("click",".checkbox",function(){     
      $(this).css("display","none");
      $(this).parents('.list_content').children(".check_click").css("display","block");      
      if ($(this).parents('.list_content').children(".check_click").css("display","block")) {
          chooselen++;       
      }
      if (chooselen==num.length) {
            $(".checkbox1").css("display","none");
            $(".check_click1").css("display","block");
          }
      else if (chooselen!=num.length){
            $(".checkbox1").css("display","block");
            $(".check_click1").css("display","none");
          }    
  });

//搜索结果分页
  $('.list-container').on('click', '.pagination a', function(e) {
    var url = $(this).attr('href');
    var group_id = $(".all_group option:selected").val();
    var status_id = $(".all_status option:selected").val();
    var content = $(".search").val();
    if(url.indexOf('select')>=0){
      e.preventDefault();
      $.post(url, {
        group:group_id,
        status:status_id,
        content:content
      }, function(data){
          $('.list-container').html(data); 
        });
    } 
  });



</script>
