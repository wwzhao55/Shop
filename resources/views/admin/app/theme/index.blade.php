@extends('layouts.app')
@section('siderbar')
@include('layouts.siderbar')
@endsection

@section('addCss')
<link rel="stylesheet" href="{{URL::asset('admin/css/themeTemplate.css')}}">
@endsection

@section('content')



   <!--  <div class="col-md-9 ">
        <div class="panel panel-default">
            <div class="panel-heading">App主题列表</div>
            <div class="panel-body">
            	@if($theme_count)
                {{Session::get('Message')}}
                <a href="/Admin/App/theme/add" class="pull-right">添加主题</a>
                <table class="table">
                   
                </table>
                
                @else   
                <div> 咦，还没有数据哎，<a href="/Admin/App/theme/add">添加主题</a></div>             
                @endif
            </div>
        </div>
    </div> -->


    <div class="container-fluid themeTemplate">
        <div class="themeTemplateTitle">
            <span class="themeTemplateTitle_word">主题模板</span>
                @if($theme_count)
                {{Session::get('Message')}}
                <a href="/Admin/App/theme/add" ><div class="createTemplate"></div></a>
                @else   
                <a href="/Admin/App/theme/add"  ><div class="createTemplate"></div></a>
                <!-- <div> 咦，还没有数据哎，<a href="/Admin/App/theme/add">添加主题</a></div>              -->
                @endif
            <!-- <div class="createTemplate"></div> -->
        </div>
        <div class="themeTemplateContent">
            <table>
                <thead>
                    <td class="thead_name">名称</td>
                    <td class="thead_rendering">效果图</td>
                    <td class="thead_time">添加时间</td>
                    <td class="thead_price">价格</td>
                    <td class="thead_describe">描述</td>
                    <td class="thead_operation">操作</td>
                    <td class="thead_detail">详情</td>

                </thead>
                <tbody id="theme-list">
                     @foreach($theme_lists as $list)
                     
                    <tr>
                    <!-- {{$theme_lists}} -->
        <!-- {{$theme_count}} -->
                  
                        <td class="tbody_name">{{$list->name}}</td>
                        <td class="tbody_rendering">
                            <!-- <img src="{{URL::asset('admin/img/app_img/L10.png')}}" alt=""></td> -->
                            <!-- <img src="{{URL::asset($list_img->img_src)}}" alt=""></td> -->
                            <img src="{{URL::asset($list->effect_img[0]->img_src)}}" alt=""></td>
                        <td class="tbody_time">{{$list->created_at}}</td>
                        <td class="tbody_price">{{$list->price}}</td>
                        <td class="tbody_describe">{{$list->description}}</td>
                        <td class="tbody_operation">
                            <img id="del" name="{{$list->id}}" src="{{URL::asset('admin/img/app_img/icon-delete.png')}}" alt=""></td>
                        <td class="tbody_detail">
                            <!-- <img src="{{URL::asset('admin/img/app_img/icon-more.png')}}" alt=""> -->
                            <a href="/Admin/App/theme/detail/{{$list->id}}"  ><img src="{{URL::asset('admin/img/app_img/icon-more.png')}}" alt=""></a>
                            </td>
                    </tr> 
                    <!-- @foreach($list->effect_img as $list_img) -->
                    <!-- @endforeach -->
                    @endforeach
                </tbody>
            </table>
        </div>
           <!-- {{$theme_lists}} -->
        <div class="paging"></div>
    </div>
<script type="text/javascript">
    $(function(){
        /* initiate the plugin */
        $("div.paging").jPages({
          containerID  : "theme-list",
          perPage      : 10,//每页显示数据为多少行
          startPage    : 1, //起始页
          startRange   : 1, //开始页码为1个
          endRange     : 1,
          previous     : "《",
          next         : "》"
        });
      });
    
    // $(".tbody_operation img").on
      // 按钮组上传鼠标悬停、移去事件
            
            $(".tbody_operation img").on("mouseover",function(){
              var src1="{{URL::asset('admin/img/app_img/icon-delete-hover.png')}}"
              $(this).attr("src",src1);
          });
          $(".tbody_operation img").on("mouseout",function(){
              var src1="{{URL::asset('admin/img/app_img/icon-delete.png')}}"
              $(this).attr("src",src1);
          });

           $(".tbody_detail img").on("mouseover",function(){
              var src1="{{URL::asset('admin/img/app_img/icon-more-hover.png')}}"
              $(this).attr("src",src1);
          });
          $(".tbody_detail img").on("mouseout",function(){
              var src1="{{URL::asset('admin/img/app_img/icon-more.png')}}"
              $(this).attr("src",src1);
          });

          // 删除模板theme-list
          $("#theme-list").on("click","#del",function(){
            _this=$(this);
            var themeid=$(this).attr("name");
            // alert(themeid);

            $.ajax({
                            type: "POST",
                            url: "/Admin/App/theme/delete/"+themeid,
                           
                            success: function(data) {
                                alert("删除成功");
                                // alert(data.status);
                                _this.remove();
                                window.location.reload();
                            },
                            error: function(XMLHttpRequest, textStatus, errorThrown) {
                                alert("删除失败，请检查网络后重试");
                            }
                    });
          });
</script>
@endsection