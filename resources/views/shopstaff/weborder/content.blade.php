<!-- auth:xuedan -->
  <div class="list-content">  
      <table  align="center" class="listContent" >

        <thead align="right" class="lists-title">
          <tr class="title-tr">
               <th class="list-title1">商品</th>
               <th class="list-title2">单价/数量</th>
               <th>买家</th>
               <th>下单时间</th>
               <th>订单状态</th>
               <th>付款金额</th>
          </tr>
        </thead>
        <tbody align="center" class="lists-detail-body" > 

          @foreach($order_lists as $list)  
            <tr>

                <td><span class="orderNum" style="padding-left:30px;"> 订单号：{{$list->order_num}} </span></td> 
                <td></td>
                <td></td>
                <td>
                          <div class="pay-num">
                          @if($list->status==0||$list->status==1||$list->status==5)
                          <span></span>
                          @else
                          <span>支付流水号： {{$list->trade_num}}</span>
                          @endif
                          </div>
                </td>
                <td></td>
                <td></td>
            </tr> 
            <tr class="detail-tr">
                  <td> 
                        <a href="/Shopstaff/weborder/detail/{{$list->id}}">
                        @foreach($list->commoditys as $commodity)
                        <img src="{{asset($commodity->main_img)}}" class="commodity-img" style="float:left; width:60px; height:60px;margin-left:30px;">
                        <div style="" class="commoditydetail">
                            <span class="commodity_name">{{$commodity->commodity_name}}</span><br>
                            @foreach($commodity->commodity_sku as $key=>$value)
                                <span>{{$key}}:{{$value}}</span>
                            @endforeach
                        </div>
                        @endforeach
                        </a>
                        <div class="clearfix" style="clear:both;"></div>
                  </td>
                  <td class="receiver_province" hidden>{{$list->receiver_province}}{{$list->receiver_city}}{{$list->receiver_district}}{{$list->receiver_address_details}}</td>
                  <td class="orderID" hidden>{{$list->id}}</td>
                  <td class="servicePhone" hidden>{{$list->service_phone}}</td>
                  <td>
                    @foreach($list->commoditys as $commodity)
                    <div class="commodity_price">￥{{$commodity->price}} <div>({{$commodity->count}}件)</div></div>
                    @endforeach
                  </td>
                  <td>{{$list->nickname}}<p  class="buyerName">姓名：{{$list->receiver_name}}</p><p  class="buyerPhnoe">手机号：{{$list->receiver_phone}}</p></td>
                  <td>{{$list->order_at}}</td>
                   @if($list->status==0)
                          <td class="status" name="status">用户删除订单</td>
                   @elseif($list->status==1)
                          <td class="status" name="status">待付款</td>
                   @elseif($list->status==2)
                          <td class="status" name="status">待发货<br><input type="button" value="发货" class="detail-btn send-btn" />
                            @if($list->hurry_times==0)
                            <p>买家未催货</p>
                            @else
                            <p>催货{{$list->hurry_times}}次</p>
                            @endif
                            </td>
                   @elseif($list->status==3)
                        <td class="status" name="status">已发货<br><input type="button" value="确认收货" class="detail-btn confirm-btn" />
                        <p>快递单号{{$list->express_num}}</p>
                        </td>
                   @elseif($list->status==4)
                          <td class="status" name="status">已完成</td>
                   @elseif($list->status==5)
                          @if($list->close_type==1)
                          <td class="status" name="status"><p>已关闭</p><p>有效时间内未付款</p></td>
                          @elseif($list->close_type==2)
                          <td class="status" name="status"><p>已关闭</p><p>用户取消订单</p></td>
                          @elseif($list->close_type==3)
                          <td class="status" name="status"><p>已关闭</p><p>已退款(实际退款金额)</p></td>
                          @endif
                   @elseif($list->status==6)
                          <td class="status" name="status">退款中<br><input type="button" value="退款" class="detail-btn refund-btn" /></td>
                          @if($list->refund_imgs)
                            @foreach($list->refund_imgs as $img)
                              <td class='refund-img-information' hidden>
                                <img src='{{asset($img)}}' class='img_list'>
                              </td>
                            @endforeach
                          @endif
                   @elseif($list->status==7)
                          <td class="status" name="status"><p>已关闭</p><p>已退款(￥{{$list->refund_money}})</p></td>
                   @endif
                      
                  <td><p class="orderMoney">￥{{$list->total}}</p></td>
                  <td class="refundDescription" hidden>{{$list->refund_description}}</td>
                  
            </tr> 
          @endforeach
        </tbody>
      </table> 
      <span class='mention-tips' hidden>查无数据</span>
       <div class="page_control">
           @include('pagination.page', ['paginator' => $order_lists])
       </div>
  </div>



