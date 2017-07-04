     <div class="container-commodity">
          <div class="commodity-status">出售中的商品管理</div>
          <div class="commodity-status">已售罄</div>
          <div class="commodity-status">仓库中</div>
          <div id="commodity-saling">
            <div class="commodity-class">
                 <div class="commodity-className commodity-pubilc">发布商品</div>
                 <div class="commodity-className">全部商品</div>
                 <div class="commodity-className">所有分组</div>
                 <div class="commodity-search">
                 <span class="search">搜索</span>
                 <input type="text" class="search-commodity"/>  
                 </div>
            </div>
        <!--    @if($solding_commodity_count) -->
            <div class="commodity-table">
                <table class="table-commodity" cellspacing="0" >
                    <thead>
                        <th class="table-head">商品价格</th>
                        <th class="table-head">创建时间</th>
                        <th class="table-head">访问量</th>
                        <th class="table-head">库存</th>
                        <th class="table-head">总销量</th>
                        <th class="table-head">序号</th>
                        <th class="table-head">操作</th>
                    </thead>
                      <!--   @foreach($solding_commodity_lists as $list) {{$list->commodity_name}}-->
                    <tbody>
                        <tr>
                            <td class="commodity_list_td"></td>
                            <td class="commodity_list_td"></td>
                            <td class="commodity_list_td"></td>
                            <td class="commodity_list_td"></td>
                            <td class="commodity_list_td"></td>
                            <td class="commodity_list_td"></td>
                            <td class="commodity_list_td"></td>
                        </tr>
                    </tbody>
                </table>
            </div>
          </div>
      </div> 