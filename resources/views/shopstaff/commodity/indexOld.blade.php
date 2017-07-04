@extends('layouts.app')
@section('siderbar')
@include('layouts.siderbar')
@endsection
@section('addCss')
<link rel="stylesheet" type="text/css" href="{{ URL::asset('shop/css/commodity.css')}}">
@endsection
@section('content')
     <div class="container-commodity">

          <div class="commodity-status commodity-active" list-value='0'>出售中的商品管理</div>
          <div class="commodity-status" list-value='1'>已售罄</div>
          <div class="commodity-status" list-value='2'>仓库中</div>
          <div id="commodity-saling">
            <div class="commodity-class">
             <a href="/Shopstaff/commodity/add" class="commodity-className commodity-pubilc">发布商品</a>
<!--              <div class="commodity-className">全部商品</div>
                 <div class="commodity-className">所有分组</div> -->
                 <div class="commodity-search">
                 <span class="search">搜索</span>
                 <input type="text" class="search-commodity"/>  
                 </div>
            </div>
            @if(Session::get('Message'))
            <div class="bg-info" style="padding:15px;color:#337ab7;text-align:center">{{Session::get('Message')}}</div>
            @endif
            <div class="commodity-table">
                <table class="table-commodity"  >
                    <thead >
                        <th class="table-head" width="322px">商品 价格</th>
                        <th class="table-head" width="224px">创建时间</th>
                        <th class="table-head" width="222px">访问量</th>
                        <th class="table-head" width="222px">库存</th>
                        <th class="table-head" width="224px">总销量</th>
                        <th class="table-head" width="224px">序号</th>
                        <th class="table-head" width="222px">操作</th>
                    </thead>
                    <tbody class="commodity-tbody" cellspacing="0">
                        @foreach($commodity_lists as $list)
                        @if($list->status==1&&$list->quantity>0)
                        <tr class="commodity-tr">
                            <td class="commodity_list_td commodity-display">
                                <img src="{{asset($list->main_img)}}" class="commodity-img">
                                <div class="commodity-name">
                                  <p>{{$list->commodity_name}}</p>
                                  <p style="font-size:16px">1</p>
                                  <p>￥{{$list->skulist[0]->price}}</p>
                                </div>
                            </td>
                            <td class="commodity_list_td">
                               <p>{{$list->created_at}}</p>
                            </td>
                            <td class="commodity_list_td">
                               <p>PV:{{$list->PV}}</p>
                               <p>UV:{{$list->UV}}</p>
                            </td>
                            <td class="commodity_list_td">
                               <p>{{$list->quantity}}</p>
                            </td>
                            <td class="commodity_list_td">
                               <p>{{$list->commodity_trade_count}}</p>
                            </td>
                            <td class="commodity_list_td">
                               <p>{{$list->id}}</p>
                            </td>
                            <td class="commodity_list_td">
                               <a href="/Shopstaff/commodity/edit/{{$list->id}}">
                                  <img src="{{asset('shopstaff/img/icon-edit.png')}}" class="someImg">
                                  <img src="{{asset('shopstaff/img/icon-edit-hover.png')}}" class="someImg_hover element-hide">
                               </a>
                               <a href="/Shopstaff/commodity/delete/{{$list->id}}" style="margin-left:5px;">
                                 <img src="{{asset('shopstaff/img/icon-delete.png')}}" class="someImg">
                                 <img src="{{asset('shopstaff/img/icon-delete-hover.png')}}" class="someImg_hover element-hide" >
                               </a>                          
                               <a href="/Shopstaff/commodity/changestatus/{{$list->id}}" style="margin-left:5px;">
                                 <img src="{{asset('shopstaff/img/In-transit.png')}}" class="someImg">
                                 <img src="{{asset('shopstaff/img/In-transit-hover.png')}}" class="someImg_hover element-hide" >
                               </a>

                            </td>
                        </tr>
                        @endif
                        @endforeach
                    </tbody>

                    <tbody class="commodity-tbody element-hide" cellspacing="1">
                        @foreach($commodity_lists as $list)
                        @if($list->quantity==0)
                        <tr class="commodity-tr">
                            <td class="commodity_list_td commodity-display">
                                <img src="{{asset($list->main_img)}}" class="commodity-img">
                                <div class="commodity-name">
                                  <p>{{$list->commodity_name}}</p>
                                  <p style="font-size:16px">1</p>
                                  <p>￥{{$list->skulist[0]->price}}</p>
                                </div>
                            </td>
                            <td class="commodity_list_td">
                               <p>{{$list->created_at}}</p>
                            </td>
                            <td class="commodity_list_td">
                               <p>PV:{{$list->PV}}</p>
                               <p>UV:{{$list->UV}}</p>
                            </td>
                            <td class="commodity_list_td">
                               <p>{{$list->quantity}}</p>
                            </td>
                            <td class="commodity_list_td">
                               <p>{{$list->commodity_trade_count}}</p>
                            </td>
                            <td class="commodity_list_td">
                               <p>{{$list->id}}</p>
                            </td>
                            <td class="commodity_list_td">
                               <a href="/Shopstaff/commodity/edit/{{$list->id}}">
                                  <img src="{{asset('shopstaff/img/icon-edit.png')}}" class="someImg">
                                  <img src="{{asset('shopstaff/img/icon-edit-hover.png')}}" class="someImg_hover element-hide">
                               </a>
                               <a href="/Shopstaff/commodity/delete/{{$list->id}}" style="margin-left:5px;">
                                 <img src="{{asset('shopstaff/img/icon-delete.png')}}" class="someImg">
                                 <img src="{{asset('shopstaff/img/icon-delete-hover.png')}}" class="someImg_hover element-hide" >
                               </a>
                            </td>

                        </tr>
                        @endif
                        @endforeach
                    </tbody>

                    <tbody class="commodity-tbody element-hide" cellspacing="2">
                        @foreach($commodity_lists as $list)
                        @if($list->status==0 && $list->quantity>=1)
                        <tr class="commodity-tr">
                            <td class="commodity_list_td commodity-display">
                                <img src="{{asset($list->main_img)}}" class="commodity-img">
                                <div class="commodity-name">
                                  <p>{{$list->commodity_name}}</p>
                                  <p style="font-size:16px">1</p>
                                  <p>￥{{$list->skulist[0]->price}}</p>
                                </div>
                            </td>
                            <td class="commodity_list_td">
                               <p>{{$list->created_at}}</p>
                            </td>
                            <td class="commodity_list_td">
                               <p>PV:{{$list->PV}}</p>
                               <p>UV:{{$list->UV}}</p>
                            </td>
                            <td class="commodity_list_td">
                               <p>{{$list->quantity}}</p>
                            </td>
                            <td class="commodity_list_td">
                               <p>{{$list->commodity_trade_count}}</p>
                            </td>
                            <td class="commodity_list_td">
                               <p>{{$list->id}}</p>
                            </td>
                            <td class="commodity_list_td">
                               <a href="/Shopstaff/commodity/edit/{{$list->id}}">
                                  <img src="{{asset('shopstaff/img/icon-edit.png')}}" class="someImg">
                                  <img src="{{asset('shopstaff/img/icon-edit-hover.png')}}" class="someImg_hover element-hide">
                               </a>
                               <a href="/Shopstaff/commodity/delete/{{$list->id}}" style="margin-left:5px;">
                                 <img src="{{asset('shopstaff/img/icon-delete.png')}}" class="someImg">
                                 <img src="{{asset('shopstaff/img/icon-delete-hover.png')}}" class="someImg_hover element-hide" >
                               </a>
                               <a href="/Shopstaff/commodity/changestatus/{{$list->id}}" style="margin-left:5px;">
                                 <img src="{{asset('shopstaff/img/icon-Already-processed.png')}}" class="someImg">
                                 <img src="{{asset('shopstaff/img/icon-Already-processed-hover.png')}}" class="someImg_hover element-hide" >
                               </a>
                            </td>
                        </tr>
                        @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
          </div>
      </div>   
       <script>

            $('.commodity-status').click(function(){
                $('.commodity-status').removeClass('commodity-active');
                $(this).addClass('commodity-active');
                $('.commodity-tbody').addClass('element-hide');
                $('tbody[cellspacing='+$(this).attr('list-value')+']').removeClass('element-hide');
            });
         
         function hoverChangeImgClass(img_class_info){
            for(var i=0;i<img_class_info.length;i++){
              (function(i){
                var img_class = img_class_info[i];
                $("."+img_class).hover(function(){
                    $(this).addClass("element-hide");
                    $(this).next().removeClass("element-hide");
                  });
                  $("."+img_class+"_hover").hover(function(){
                  },function(){
                    $(this).addClass("element-hide");
                    $(this).prev().removeClass("element-hide");

                  });
              })(i);      
            }
          }
          hoverChangeImgClass(['someImg']);
       </script>
@endsection