<!-- auth:wuwenjia -->

@extends('layouts.app')
@section('siderbar')
@include('layouts.siderbar')
@endsection

@section('addCss')
<link rel="stylesheet" type="text/css" href="{{ URL::asset('shop/css/brandmanage.css')}}">
@endsection

@section('content')
<div class="brandManage">
    <div class="brandManage-title">
                <span>品牌管理</span>
                <a href='/Admin/brandmanage/add'><button id="brand-change" ><img class="img-new" src="{{asset('shop/images/brandmanage/btn-brand.png')}}" class="detail-btn"></button></a>
    </div>
    <div class="brand_table">
        <table>
            <thead>
                <tr>
                    <th width="12.04%">品牌名</th>
                    <th width="13.97%">创建时间</th>
                    <th width="13.97%">公司名</th>
                    <th width="13.97%">主营品牌</th>
                    <th width="13.97%">联系人</th>
                    <th width="13.97%">电话</th>
                    <th width="18.11%">操作</th>
                </tr>
            </thead>
            <tbody id="tab-contents">
                @if($brand_count)
                <!-- 提示操作成功或失败 -->
                {{Session::get('Message')}}

                @foreach($brand_lists as $list)
                <tr>
                    <td>{{$list->brandname}}</td>
                    <td>{{$list->created_at}}</td>
                    <td>{{$list->company_name}}</td>
                    <td>{{$list->main_business}}</td>
                    <td>{{$list->contacter_name}}</td>
                    <td>{{$list->contacter_phone}}</td>
                    <td><a href="javascript:void(0)" class="changestatus" brandId="{{$list->id}}" style="text-decoration:none;">
                                @if($list->status)
                                    <img class="img-switch" status='{{$list->status}}' src="{{asset('shop/images/brandmanage/btn-clear.png')}}">
                                    <span class="word-describe">开通</span>
                                @else
                                    <img class="img-switch" status='{{$list->status}}'  src="{{asset('shop/images/brandmanage/btn-freeze.png')}}">
                                    <span class="word-describe">冻结</span>
                                    
                                @endif
                            </a>
                    <a href="/Admin/brandmanage/detail/{{$list->id}}"><p class="more_detail">详情</p></a></td>
                </tr>
                @endforeach
            </tbody> 
        </table>
    </div>
    @else
    <p class="bg-success" style="padding:15px">咦，还没有数据哎</p>
    @endif 
     <div class="holder" id="holder"></div>
</div>
<div class='clearfix'></div>
<script type="text/javascript">
    $('.changestatus').click(function(){
        var thisobj = $(this);
        var shop_id = $(this).attr('brandId');
        $.ajax({
            type: 'POST',
            url: '/Admin/brandmanage/changestatus/'+shop_id ,
            success: function(data){
                if(data.status='success'){
                    var img = thisobj.find('.img-switch');
                    var span = thisobj.find('span');

                    if(img.attr('status') == '1'){
                        img.attr('status','0');
                        span.html('冻结');
                        img.attr('src','/shop/images/brandmanage/btn-freeze.png');
                    }else{
                        img.attr('status','1');
                        img.attr('src','/shop/images/brandmanage/btn-clear.png');
                        span.html('开通');
                        
                    }
                }
            },

            dataType: 'json'
        })

    })
    $('#menu-lists-parent').find('.onsidebar').removeClass('onsidebar');
    $('.brandmanage').addClass('onsidebar');
    $(function(){
        /* initiate the plugin */
        $("div.holder").jPages({
          containerID  : "tab-contents",
          perPage      : 10,//每页显示数据为多少行
          startPage    : 1, //起始页
          startRange   : 1, //开始页码为1个
          endRange     : 1,
          previous     : "《",
          next         : "》"
        });
      });
    
    //悬浮效果
     $(".img-new").on("mouseover",function(){
        $(this).attr("src","{{asset('shop/images/brandmanage/btn-brand-hover.png')}}");
    });
    $(".img-new").on("mouseout",function(){
        $(this).attr("src","{{asset('shop/images/brandmanage/btn-brand.png')}}");
     });
     $(".more_detail").on("mouseover",function(){
        $(this).attr("src","{{asset('shop/images/brandmanage/icon-more-hover.png')}}");
    });
    $(".more_detail").on("mouseout",function(){
        $(this).attr("src","{{asset('shop/images/brandmanage/icon-more.png')}}");
     });
</script>
@endsection
