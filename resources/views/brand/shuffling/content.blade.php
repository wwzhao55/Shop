<div class="contentManage">
    <div class="contentManage-title">
                <span>广告图列表</span>
                <select class="shufflinglists" value="">
                  <option value="0">全部</option>
                  @foreach($shop_lists as $list)
                  <option value="{{$list->id}}">{{$list->shopname}}</option>
                  @endforeach
                </select>                    
                
                <a href="/Brand/shuffling/add"><button id="account-btn">添加</button></a>                       
    </div>
      @if($shuffling_count)
    <table>
        <thead>
            <tr>
                <th width="9.64%"><div class="checkall btn-check"></div>序号</th>
                <th width="18.07%">名称</th>
                <th width="18.07%">图片</th>
                <th width="18.07%">所属分店</th>
                <th width="18.07%">跳转</th>
                <th width="18.08%">操作</th>
            </tr>
        </thead>
      
        <tbody class="tab-lists">
            @foreach($shuffling_lists as $list)
            <tr> 
                <td class="commodity_id" hidden>{{$list->id}}</td>
                <td><div class="check btn-check"></div>{{$list->order}}</td>
                <td>{{$list->name}}</td>
                <td><img class="main-img" src="{{asset($list->img_src)}}"></td>
                <td>{{$list->shopname}}</td>
                <td>{{$list->http_src}}</td>
                <td><img class="edit" src="{{asset('shopstaff/images/edit.png')}}"><a href="/Brand/shuffling/delete/{{$list->id}}"><img class="delete" src="{{asset('shopstaff/images/delete.png')}}"></a></td>
            </tr>
            @endforeach
        </tbody>
        @else   
            <div class="error-mention"> 咦，还没有数据哎...</div>             
        @endif  
    </table>
    <button class="btn-up">上移</button>
    <button class="btn-down">下移</button>
    <button class="btn-del">删除</button>
    <div class="clearfix" style="clear:both;"></div>
    <div class="divide-page">
        @include('pagination.page', ['paginator' => $shuffling_lists])
    </div>
</div>