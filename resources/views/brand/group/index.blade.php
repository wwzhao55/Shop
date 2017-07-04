<!-- 
Auth:Zhaoweiwei
Date:2016.07.21 
-->
@extends('layouts.app')
@section('siderbar')
@include('layouts.siderbar')
@endsection

@section('addCss')
<link rel="stylesheet" type="text/css" href="{{ URL::asset('shopstaff/commodity.css')}}">
<link rel="stylesheet" type="text/css" href="{{URL::asset('shop/css/add-commodity.css')}}">
@endsection

@section('content')
    <!-- 商品分类管理页面-->
        <div class="delete-success">{{Session::get('Message')}}</div>
        <div class="contentManage">
                <div class="contentManage-title">
                            <span>商品分组列表</span>
                            <div id="account-btn">创建新分类</div>
                </div>
                @if($group_count)
                <table>
                    <thead>
                        <tr>
                            <th width="25%">名称</th>
                            <th width="25%">商品数</th>
                            <th width="25%">创建时间</th>
                            <th width="25%">操作</th>
                        </tr>
                    </thead>
                    <tbody class="tab-lists">
                        @foreach($group_lists as $list)
                        <tr>
                            <td class="id" hidden>{{$list->id}}</td>
                            <td class="groupname">{{$list->name}}</td>
                            <td>{{$list->commodity_count}}</td>
                            <td>{{$list->created_at}}</td>
                            <td><img class="edit" src="{{asset('shopstaff/img/icon-edit.png')}}">
                                <a href="/Brand/group/delete/{{$list->id}}">
                                   <img class="delete" src="{{asset('shopstaff/img/icon-delete.png')}}">
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody> 
                </table>
                @else
                <div class="error-mention"> 咦，还没有数据哎...</div>             
                @endif  
        </div>
        <!-- 商品分类管理新建弹窗 -->
        <div class="new-window" hidden>
            <form action='/Brand/group/add' method="post" enctype="multipart/form-data" id="form">
                 {!! csrf_field() !!}
                <div class="window-content">
                    <table>
                        <thead>
                            <tr>
                                <th>名称</th>
                               <!--  <th width="25.6%">图片(不可用)</th>
                                <th width="33.33%">跳转到页面(不可用)</th> -->
                            </tr>
                        </thead>
                        <tbody>
                            <tr style="height:100px;">
                                <td><input type="text" class="input_name" name="name"></td>
                               <!--  <td>
                                    <div>
                                        <input type="file" name="" id="inputfile" multiple="multiple" /><br><img src="" id="img" >
                                    </div>
                                </td>
                                <td><input type="text" class="input_website" name="" value="不可用"></td> -->
                            </tr>
                        </tbody> 
                    </table>
                    <div class="bottom-btn">
                        <div class="cancle-new allhover">取消</div>
                        <div class="confirm-new"><button class="allhover">提交</button></div>
                    </div>
                </div>
            </form>
        </div>
        <!-- 商品分类管理编辑 -->
        <div class="edit-window" hidden>
            <div class="question">分组修改</div>
            <span>分组名称</span>
            <input  class="group_modify" value="">
            <div class="div_img">
                <button class="cancle-edit allhover">取消</button>
                <button class="confirm-edit allhover">确定</button>
            </div>
        </div>
<script type="text/javascript">
        $('#commodity-manage').addClass('in');
        $('.side-list').find('.onsidebar').removeClass('onsidebar');
        $('.commoditymanage').addClass('onsidebar');
        $('.side-list').find('.onsidebarlist').removeClass('onsidebarlist');
        $('.commoditygroup').addClass('onsidebarlist');
        //悬浮效果
            $(".delete").on("mouseover",function(){
              $(this).attr("src","{{asset('shopstaff/images/icon-delete-hover.png')}}");
            });
            $(".delete").on("mouseout",function(){
              $(this).attr("src","{{asset('shopstaff/images/icon-delete.png')}}");
            });
            $(".edit").on("mouseover",function(){
              $(this).attr("src","{{asset('shopstaff/img/icon-edit-hover.png')}}");
            });
            $(".edit").on("mouseout",function(){
              $(this).attr("src","{{asset('shopstaff/images/icon-edit.png')}}");
            }); 
        //新建弹窗
            $("#account-btn").on('click',function(){
                cancel_index= layer.open({
                             type: 1,
                             title:false,
                             skin: 'layui-layer-demo', //样式类名
                             closeBtn: 0, //不显示关闭按钮
                             shift: 2,
                             shadeClose: true, //开启遮罩关闭
                             area : ["37%" , '330px'],
                             content:$('.new-window'),
                        });
            });
            $(".cancle-new").on("click",function(){
                // $(".new-window").css("display","none");
                 layer.close(cancel_index);
            });
            $(".confirm-new").on("click",function(){
                var content=$('.input_name').val().replace(/[]/g,"");
                if (content==''||content.length==0) {
                    alert('您还没有输入分类名称');
                }
                else { 
                  $("#form").submit();
                  layer.close(cancel_index);
                } 
            });
            
            $("#inputfile").change(function(){
                $("#inputfile").css("display","none");
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
         //编辑
         var id;
        $(".edit").on('click',function(){
                id=$(this).parents('tr').children('.id').html();
                var group=$(this).parents('tr').children('.groupname').html();
                cancel_index1= layer.open({
                             type: 1,
                             title:false,
                             skin: 'layui-layer-demo', //样式类名
                             closeBtn: 0, //不显示关闭按钮
                             shift: 2,
                             shadeClose: true, //开启遮罩关闭
                             area : ["600px" , '250px'],
                             content:$('.edit-window'),
                        });
                $('.group_modify').val(group);
            });
            $(".cancle-edit").on("click",function(){
                $(".edit-window").css("display","none");
                layer.close(cancel_index1);
            });
            $(".confirm-edit").on("click",function(){ 
              var name= $('.group_modify').val().replace(/[]/g,"");
              if (name.length==0||name=='') {
                    alert('您还没有输入分组名称');
                }             
                $.ajax({
                  type:'post',
                  url:'/Brand/group/edit/'+id,
                  data:{                            
                    name:name
                  },
                  dataType:"json",
                  success:function(result){
                    if(result.status=="success"){
                      layer.close(cancel_index1);
                      window.location.reload();
                    } else{
                      alert(result.message);
                    }               
                  }
                });
            });
</script>
@endsection