<!-- auth:wuwenjia -->
@extends('layouts.app')
@section('siderbar')
@include('layouts.siderbar')
@endsection
@section('content')
@section('addCss')
<link rel="stylesheet" type="text/css" href="{{URL::asset('shop/css/fileinput.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{URL::asset('shop/jPages/css/jPages.css')}}">
<link rel="stylesheet" type="text/css" href="{{ URL::asset('shopstaff/shuffling.css')}}">

<script src="{{URL::asset('shop/jPages/js/jPages.js')}}"></script>
@endsection
    <script src="{{URL::asset('shop/js/examineadvertiser-uploadview.js')}}"></script>
    <script src="{{URL::asset('shop/js/fileinput.min.js')}}"></script>
    <div class="container-commodity">
        <div class="commodity-edit">
            <span class="commodity-word">广告编辑</span>
            <a href="/Brand/shuffling/"><button class="commodity-return allhover">返回</button></a>
        </div>
        <div class="new-window">
            <form action='/Brand/shuffling/edit/{{$shuffling->id}}' method="post" enctype="multipart/form-data" id="form">
                 {!! csrf_field() !!}
                 {{Session::get('Message')}}
                 @if (count($errors) > 0)
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <div class="add-content">
                    <span>广告名称：</span>
                    <input type="text" name="name" class="ad_name" value="{{$shuffling->name}}">
                </div>
                <div class="add-content">
                    <span>所属分店：</span>
                    <select class="shopname" value="" name="shop_id">                    
                        <option value="0" @if($shuffling->shop_id==0)selected="selected"@endif>全部</option>
                        @foreach($shop_lists as $list)
                        <option value="{{$list->id}}" @if($shuffling->shop_id==$list->id)selected="selected"@endif>{{$list->shopname}}</option>
                        @endforeach
                    </select>
                    <div class="tip">微信端可以为不同分店展示不同广告图，默认选择“全部”时所有分店都可展示</div>
                </div>
                <div class="add-content">
                    <span class="ad_title">广告图片：</span>
                    <img class="add" src="{{asset('/shopstaff/images/add.png')}}">
                    <div class="js_uploadBox1">
                        <div>
                            <input style="display: none;" class="js_upFile1"   type="file" name="img_src"  /> 
                        </div>
                        <div class="js_showBox1" id="js_showBox1">
                            <img src="{{asset($shuffling->img_src)}}" class="js_logoBox" height="200px">
                        </div>        
                    </div> 
                    <div class="clearfix" style="clear:both;"></div>
                </div>
                <div class="add-content">
                    <span class="jump">跳转：</span>
                    <div class="link">请选择用户点击要跳转的商品</div>
                    <div class="httpsrc_div" hidden><input type="text" name="http_src" value="{{$shuffling->http_src}}" class="http_src"></div>
                    <div class="clearfix" style="clear:both;"></div>
                </div>                              
                <button class="confirm-new allhover">提交</button>
                <div class="clearfix" style="clear:both;"></div>
            </form>
        </div>
        <input type="text" hidden value="" class="choose_id">
        <div class="choose_commodity">
            <div class="choose_head">
                <span>选择商品</span>
                <button class="btn-search-choose">搜索</button>                
                <input type="text" name="" class="choose_search">
                <img class="img-search" src="{{asset('shopstaff/images/search.png')}}">
            </div>
            <div class="choose_body">
                @include('brand.shuffling.commodity')
            </div>
            
        </div>
    </div>
    <script>
    $('.siderbar').height($('.content-right').height());
            $('.side-list').find('.in').removeClass('in');
            $('#wexin-manage').addClass('in');
            $('.side-list').find('.onsidebar').removeClass('onsidebar');
            $('.weixinmanage').addClass('onsidebar');
            $('.side-list').find('.onsidebarlist').removeClass('onsidebarlist');
            $('.shuffingmanage').addClass('onsidebarlist');

           
        $(".js_upFile1").uploadView({
            uploadBox: '.js_uploadBox1',//设置上传框容器
            showBox : '.js_showBox1',//设置显示预览图片的容器                  
            height : 200, //预览图片的高度，单位px
            allowType: ["gif", "jpeg", "jpg", "bmp", "png"], //允许上传图片的类型
            maxSize :2, //允许上传图片的最大尺寸，单位M
            success:function(e){
                var src_img=$('.js_showBox1  img').attr('src');
                $('.js_showBox1  img').addClass('js_logoBox');
                                    
            }
        });
        $(".js_upFile1").change(function(){
            
        });
        $(".add").click(function(){
            $(".js_upFile1").click();
        });
        //搜索功能
          $('.btn-search-choose').on('click',function(){
              var keyword=$('.choose_search').val();
              var id=$('.choose_id').val();console.log(id);
              if(keyword!=''){
                  $.ajax({
                      type: 'POST',
                      url: '/Brand/shuffling/search/'+id,
                      data:{
                        keyword:keyword,
                        shop_id:id
                      },
                      dataType: 'json',
                      success: function(result){
                        $('.choose_body').html(result);       
                      }              
                  });  
              }else{
                  alert("搜索内容不能为空！");
              }
              
          });       

        $('.link').on('click',function(){
            // $('.choose-lists').empty();
            var id=$('.shopname option:selected').val();
            $('.choose_id').val(id);
            
            $.ajax({
                    type: 'POST',
                    url: '/Brand/shuffling/commodity/'+id,
                    data:{
                        shop_id:id
                    },
                    dataType: 'json',
                    success: function(result){

                        $('.new-window').css('display','none');
                        $('.choose_commodity').css('display','block');
                        $('.choose_body').html(result);
                        $('.siderbar').height($('.content-right').height());
                        // if(result.status=="success"){
                        //     for(var i=0;i<result.commoditys.length;i++){
                        //         var tr='<tr>'+'<td class="commodity_id" hidden>'+result.commoditys[i].id+'</td><td><div class="check btn_check"></div><img class="choose_img" src="'+result.commoditys[i].main_img+'">'+result.commoditys[i].commodity_name+'</td>'+'<td>'+result.commoditys[i].created_at+'</td>'+'</tr>';
                        //         $('.choose-lists').append(tr);                                
                        //     }
                            
                        //     $('.new-window').css('display','none');
                        //     $('.choose_commodity').css('display','block');
                        // }
                          
                    }
            });

        });
</script>
@endsection