@extends('layouts.app')
@section('siderbar')
@include('layouts.siderbar')
@endsection

@section('addCss')
<link rel="stylesheet" type="text/css" href="{{ URL::asset('shopstaff/shuffling.css')}}">
@endsection

@section('content')
    <!-- 轮播图管理 -->
    <div class="contentManage">
            <div class="contentManage-title">
                        <span>轮播图管理</span>
                        <div id="account-btn"> <img src="{{asset('shopstaff/images/btn-add-to.png')}}"></div>
            </div>
              @if($shuffling_count)
            <table>
                <thead>
                    <tr>
                        <th width="28.9%">名称</th>
                        <th width="13.2%">图片</th>
                        <th width="28.9%">跳转到页面</th>
                        <th width="28.9%">操作</th>
                    </tr>
                </thead>
              
                <tbody class="tab-lists">
                    @foreach($shuffling_lists as $list)
                    <tr>
                        <td>{{$list->name}}</td>
                        <td><img class="main-img" src="{{asset($list->img_src)}}"></td>
                        <td>{{$list->http_src}}</td>
                        <td><a href="/Shopstaff/shuffling/delete/{{$list->id}}"><img class="delete" src="{{asset('shopstaff/images/icon-delete.png')}}"></a></td>
                    </tr>
                    @endforeach
                </tbody>
                @else   
                    <div class="error-mention"> 咦，还没有数据哎...</div>             
                @endif  
            </table>
        </div>
        <div class="new-window" hidden>
            <form action='/Shopstaff/shuffling/add' method="post" enctype="multipart/form-data" id="form">
                 {!! csrf_field() !!}
                <div class="window-content">
                    <table>
                        <thead>
                            <tr>
                                <th width="33.33%">名称</th>
                                <th width="25.6%">图片</th>
                                <th width="33.33%">跳转到页面</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr style="height:140px;">
                                <td><input type="text" value="" class="input_name" name="name"></td>
                                <td>
                                    <div>
                                      <img src="{{asset('shopstaff/images/add.png')}}" class="upload">
                                        <input type="file" name="img_src" id="inputfile" style="display:none;"/><br><img src="" id="img" >
                                    </div>
                                </td>
                                <td><input type="text" value="" class="input_website" name="http_src"></td>
                            </tr>
                        </tbody> 
                    </table>
                    <div class="bottom-btn">
                        <div class="cancle-new"><img src="{{asset('shopstaff/images/btn-cancel-tc.png')}}"></div>
                        <div class="confirm-new"><img src="{{asset('shopstaff/images/btn-Submit-tc.png')}}"></div>
                    </div>
                </div>
            </form>
        </div>
<Script type="text/javascript">
        //悬浮效果
            $(".delete").on("mouseover",function(){
              $(this).attr("src","{{asset('shopstaff/images/icon-delete-hover.png')}}");
            });
            $(".delete").on("mouseout",function(){
              $(this).attr("src","{{asset('shopstaff/images/icon-delete.png')}}");
            });
            $("#account-btn").on("mouseover",function(){
              $(this).attr("src","{{asset('shopstaff/images/btn-add-to-hover')}}");
            });
            $("#account-btn").on("mouseout",function(){
              $(this).attr("src","{{asset('shopstaff/images/btn-add-to.png')}}");
            });
        //新建
            $("#account-btn").on('click',function(){
                cancel_index= layer.open({
                             type: 1,
                             title:false,
                             skin: 'layui-layer-demo', //样式类名
                             closeBtn: 0, //不显示关闭按钮
                             shift: 2,
                             shadeClose: true, //开启遮罩关闭
                             area : ["47%" , '440px'],
                             content:$('.new-window'),
                        });
            });
            $(".cancle-new").on("click",function(){
                $(".new-window").css("display","none");
                layer.close(cancel_index);
            });
            $(".confirm-new").on("click",function(){
                form.submit();
                layer.close(cancel_index);
            });
            $(".upload").on('click',function(){
              $("#inputfile").click();
            });
        $("#inputfile").change(function(){
                $(".upload").css("display","none");
                  var objUrl = getObjectURL(this.files[0]) ;
                  console.log("objUrl = "+objUrl) ;
                  if (objUrl) {
                    $("#img").attr("src", objUrl) ;
                  }
            }) ;
            //建立一個可存取到該file的url
                function getObjectURL(file) {
                  var url = null ; 
                      if (window.createObjectURL!=undefined) { // basic
                        url = window.createObjectURL(file) ;
                      } else if (window.URL!=undefined) { // mozilla(firefox)
                        url = window.URL.createObjectURL(file) ;
                      } else if (window.webkitURL!=undefined) { // webkit or chrome
                        url = window.webkitURL.createObjectURL(file) ;
                      }
                      return url ;
                }           
            
 </script>
@endsection