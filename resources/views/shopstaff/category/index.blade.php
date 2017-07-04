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
        {{Session::get('Message')}}
        <div class="contentManage">
                <div class="contentManage-title">
                            <span>商品分类管理</span>
                            <div id="account-btn"> <img src="{{asset('shopstaff/images/btn-add-to.png')}}"></div>
                </div>
                @if($category_count)
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
                        @foreach($category_lists as $list)
                        <tr>
                            <td>{{$list->name}}</td>
                            <td>{{$list->commodity_count}}</td>
                            <td>{{$list->created_at}}</td>
                            <td><img class="edit" src="{{asset('shopstaff/images/icon-edit.png')}}">
                                <a href="/Shopstaff/category/delete/{{$list->id}}">
                                    <img class="delete" src="{{asset('shopstaff/images/icon-delete.png')}}">
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
            <form action='/Shopstaff/category/add' method="post" enctype="multipart/form-data" id="form">
                 {!! csrf_field() !!}
                <div class="window-content">
                    <table>
                        <thead>
                            <tr>
                                <th width="33.33%">名称</th>
                                <th width="25.6%">图片(不可用)</th>
                                <th width="33.33%">跳转到页面(不可用)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr style="height:140px;">
                                <td><input type="text" class="input_name" name="name"></td>
                                <td>
                                    <div>
                                        <input type="file" name="" id="inputfile" multiple="multiple" /><br><img src="" id="img" >
                                    </div>
                                </td>
                                <td><input type="text" class="input_website" name="" value="不可用"></td>
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
        <!-- 商品分类管理编辑 -->
        <div class="container-commodity" hidden>
            <div class="commodity-edit">
                <span class="commodity-word"><span class="commodity-ban">商品库</span><span class="commodity-head">/编辑商品</span></span>
                <span class="commodity-return"><img src="{{URL::asset('shopstaff/img/btn-return.png')}}"/></span>
            </div> 
            <div class="commodity-status">选择商品分类</div>
            <div class="commodity-status">编辑基本信息</div>
            <div class="commodity-status">编辑商品详情</div>
            <!-- 选择商品分类模块 -->
            <div class="choose-commodity">
                    <div class="commodity-class">
                        @foreach($category_lists as $list)
                            <div class="commodity-list">{{$list->name}}</div>

                        @endforeach
                        <div class="commodity-list">鞋帽</div>
                        <div class="commodity-list">上衣</div>
                        <div class="commodity-list">家居</div>
                        <div class="commodity-list">鞋帽</div>
                        <div class="commodity-list">上衣</div>
                        <div class="commodity-list">家居</div>
                    </div> 
                    <div class="next">
                        <img src="{{URL::asset('shopstaff/img/btn-Next-step.png')}}" id="choose-next" />
                    </div>
            </div>
            <!-- 编辑基本信息 -->
            <div class="commodity-msg-edit">
                <div class="msg-edit">
                    <!--  基本信息 -->
                        <div class="edit-title">基本信息</div>
                        <div class="edit-commodity">
                            <div class="commodity-class-list">
                                <p>商品分类</p>
                                <input type="text" class="class-commodity" id="category_name" />
                            </div>
                            <div class="commodity-class-list">
                                <p>商品分组</p>
                                <input type="text" class="class-commodity" />
                            </div>
                            <div class="commodity-class-list">
                                <p>商品类型</p>
                                <input type="radio" name="commodity-class" /> 实物商品
                                <input type="radio" name="commodity-class" /> 虚拟商品
                            </div>
                            <div class="commodity-class-list">
                                <p>预售设置</p>
                                <input type="radio" name="class-commodity" /> 预售商品
                            </div>
                        </div>
                    <!--  库存规格 -->
                        <div class="edit-title">库存规格</div>
                        <div class="edit-commodity">
                            <div class="commodity-class-list">
                                <p>商品规格</p>
                                <input type="text" class="class-commodity" />
                            </div>
                            <div class="commodity-class-list">
                                <p>总库存</p>
                                <input type="text" class="class-commodity" />
                            </div>
                            <div class="commodity-class-list">
                                <p>商家编码</p>
                                <input type="radio" name="commodity-class" /> 实物商品
                                <input type="radio" name="commodity-class" /> 虚拟商品
                            </div>                       
                        </div>
                    <!--  商品信息 -->
                        <div class="edit-title">商品信息</div>
                        <div class="edit-commodity">
                            <div class="commodity-class-list">
                                <p>商品名</p>
                                <input type="text" class="class-commodity" id="commodity_name" />
                            </div>
                            <div class="commodity-class-list">
                                <p>价格</p>
                                <span class="money-mark">￥</span><input type="text" class="money-input" />
                                <input type="text" class="money-input"  placeholder="原价："/>
                            </div>
                            <div class="commodity-class-list">
                                <p>商品图</p>
                                <form  action='/Shopstaff/commodity/uploadimg' id="rendering_form" enctype="multipart/form-data">
                                    <input type="file" id="upload-img" name="main_img"/>
                                </form>
                                <div class="img-show"></div>
                                <img src="{{URL::asset('shopstaff/img/add.png')}}" id="add-img"/>
                           </div>                            
                        </div>
                    <!--   物流其他 -->
                        <div class="edit-title">物流其他</div>
                        <div class="edit-commodity">
                            <div class="commodity-class-list">
                                <p>运费</p>
                                <input type="radio" name="express_type" value="1" /> 统一运费<input type="text" class="class-commodity" placeholder="￥：" id="express_price" /><br/><br/>
                                <input type="radio" name="express_type" value="0" /> 运费模板<input type="text" class="class-commodity" id="express_price" />
                            </div>
                            <div class="commodity-class-list">
                                <p>开售时间</p>
                                <input type="radio" name="start-time"/> 立即开售<br/><br/>
                                <input type="radio" name="start-time"/> 定时开售<input type="text" class="class-commodity" />
                            </div>
                            <div class="commodity-class-list">
                                <p>会员折扣</p>
                                <input type="checkbox" name="commodity-class" /> 参加会员折扣价
                            </div>
                        </div>
                    <div class="next">
                        <img src="{{URL::asset('shopstaff/img/btn-Last-step-hover.png')}}" class="last-step"/>
                        <img src="{{URL::asset('shopstaff/img/btn-Next-step-hover.png')}}" class="next-step"/>
                    </div>
                </div>
            </div>  
            <!--  编辑商品详情 -->
            <div class="edit-commodity-detail">
                <div class="edit-commodity-detail-container">
                    <div class="commodity-mode">
                        <span >商品页模板</span>
                        <select class="choose-mode">
                            <option>普通版</option>
                            <option>简洁流畅版</option>
                        </select>
                    </div>
                    <div class="commodity-introduce">
                        <span class="introduce-title">商品简介(选填，微信分享给好友会显示这里的文章)</span>
                        <input type="text" class="introduce-msg" id="brief_introduction" />
                    </div>
                    <div class="commodity-detail-edit">
                        <span class="introduce-title">编辑商品详情</span>
                        <input type="text" class="introduce-msg-detail"  id="description" />
                    </div>
                </div>
                <div  class="next">
                    <img src="{{URL::asset('shopstaff/img/btn-Last-step.png')}}" class="last-step-edit"/>
                    <img src="{{URL::asset('shopstaff/img/btn-The-shelves.png')}}" class="on-shelf"/>
                </div>
            </div>
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
        //新建弹窗
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
            $(".edit").on("click",function(){
                $(".contentManage").css("display","none");
                $(".container-commodity").css("display","block");
            });
            $(".commodity-status").on("click",function(){   
                $(".commodity-status").css("background-color","#eee");
                    if($(this).html()=="选择商品分类")  {
                        $(".choose-commodity").css("display","block");
                        $(".commodity-msg-edit").css("display","none");
                        $(".edit-commodity-detail").css("display","none");
                        $(".commodity-head").html("/商品分类");
                    }
                    if($(this).html()=="编辑基本信息")  {
                        $(".choose-commodity").css("display","none");
                        $(".commodity-msg-edit").css("display","block");
                        $(".edit-commodity-detail").css("display","none");
                        $(".commodity-head").html("/基本信息");
                    }
                    if($(this).html()=="编辑商品详情")  {
                        $(".choose-commodity").css("display","none");
                        $(".commodity-msg-edit").css("display","none");
                        $(".edit-commodity-detail").css("display","block");
                        $(".commodity-head").html("/商品详情");

                    }
                    $(this).css("background-color","#fff");
            });
            $("#choose-next").on("click",function(){
                $(".choose-commodity").css("display","none");
                $(".commodity-msg-edit").css("display","block");
            });
            $(".last-step").on("click",function(){
                $(".choose-commodity").css("display","block");
                $(".commodity-msg-edit").css("display","none");
            });
            $(".next-step").on("click",function(){
                $(".edit-commodity-detail").css("display","block");
                $(".commodity-msg-edit").css("display","none");
            });
            $(".last-step-edit").on("click",function(){
                $(".edit-commodity-detail").css("display","none");
                $(".commodity-msg-edit").css("display","block");
            });
            $("#add-img").on("click",function(){
                $('#upload-img').click();
            });
            $("#upload-img").on("change",function(){
                $("#rendering_form").ajaxSubmit({
                  type: "POST",
                  url: "/Shopstaff/commodity/uploadimg",
                  dataType:"json",
                        success: function(data) {
                           alert("上传成功");
                                },
                         error: function(XMLHttpRequest, textStatus, errorThrown) {
                                    alert("上传失败，请检查网络后重试");
                           }
                });       
            });
</script>
@endsection