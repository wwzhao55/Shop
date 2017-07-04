@if(count($commodity_lists))
<div class='commodity-content'>
              <div class="commodity-table">
                  <table class="table-commodity"  >
                      <thead >
                          <th class="table-head" width="21.28%"><div class="checkall btn-check"></div>商品名称(价格)</th> 
                          <th class="table-head" width="13.12%" >分组</th>                      
                          <th class="table-head" width="13.12%">访问量</th>
                          <th class="table-head" width="13.12%">库存(总)</th>
                          <th class="table-head" width="13.12%">总销量</th>
                          <th class="table-head" width="13.12%">创建时间</th>
                          <th class="table-head" width="13.12%">操作</th>
                      </thead>
                      

                      <tbody class="commodity-tbody">
                          @foreach($commodity_lists as $list)
                          <tr class="commodity-tr">
                              <td class="group_id" hidden>{{$list->group_id}}</td>
                              <td class="commodity_list_td commodity-display">
                                  <div class="check btn-check"></div>
                                  <img src="{{asset($list->main_img)}}" class="commodity-img">
                                  <div class="commodity-name">
                                    <p class="name">{{$list->commodity_name}}</p>
                                    <p class="value">￥{{$list->base_price}}</p>
                                  </div>
                              </td>  
                              <td class="commodity_list_td">
                                <p>{{$list->group_name}}</p>
                              </td>                          
                              <td class="commodity_list_td">
                                 <p class="PV">PV:{{$list->PV}}</p>
                                 <p>UV:{{$list->UV}}</p>
                              </td>
                              <td class="commodity_list_td">
                                 <p>{{$list->quantity}}</p>
                              </td>
                              <td class="commodity_list_td">
                                 <p>{{$list->saled_count}}</p>
                              </td>
                              <td class="commodity_list_td commodity_id" hidden>{{$list->id}}</td>
                              <td class="commodity_list_td">
                                 <p>{{$list->created_at}}</p>
                              </td>
                              <td class="commodity_list_td">
                                 <a href="/Brand/commodity/edit/{{$list->id}}">
                                    <img src="{{asset('shopstaff/img/icon-edit.png')}}" class="someImg">
                                    <img src="{{asset('shopstaff/img/icon-edit-hover.png')}}" class="someImg_hover element-hide">
                                 </a>
                                 <a href="/Brand/commodity/delete/{{$list->id}}" style="margin-left:5px;">
                                   <img src="{{asset('shopstaff/img/icon-delete.png')}}" class="someImg">
                                   <img src="{{asset('shopstaff/img/icon-delete-hover.png')}}" class="someImg_hover element-hide" >
                                 </a>
                              </td>

                          </tr>
                          @endforeach
                      </tbody>                   
                  </table>                
              </div>            
            
              <button class="btn-down">商品改分组</button>
              <ul class="group_modify">
              @foreach($group_lists as $list)
                <li value="{{$list->id}}">{{$list->name}}</li>
              @endforeach
              </ul>
              <button class="btn-del">删除</button>
              <div class="divide-page">
                    <?php echo $commodity_lists->render(); ?>
              </div>
            </div>
@else
<div class="error-mention"> 咦，还没有数据哎...</div>             
@endif 