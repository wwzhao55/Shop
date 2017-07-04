<!--auth:ZWW 
    date:2016-09-08
-->
@extends('layouts.app')
@section('siderbar')
@include('layouts.siderbar')
@endsection
@section('addCss')
<link rel="stylesheet" type="text/css" href="{{ URL::asset('brand/Publicmanage.css')}}">
@endsection

@section('content')
<!-- publicmanage -->
<div class='Publicmanage'>
        <span class='welcome_title'>欢迎消息：</span>
        <textarea class="welcome_message" type="text" name='subscribe_text' value='' style="resize:none;" placeholder="请您在填写关注公众号欢迎信息"></textarea>
        <input class='mesage-out' type="text" hidden value='{{$subscribe_text}}'>
        <span class='myself_menu'>自定义菜单</span>
        <button class='add_first_menu all_hover'>添加一级菜单</button>
        @if(count($menu)>0)
        <div class='menu_container'>
            <div class='close_all'><img src="{{URL::asset('shop/images/detail/close.png')}}" class='img-size'></div>
            @foreach($menu as $first)
                <div class="first_menu_list">
                    <span class='name'>名称</span><input class="menu_first_name" type="text" value="{{$first->name}}" placeholder="菜单名称">
                    @if(isset($first->url))
                        <input class="menu_first_url" type="text" value="{{$first->url}}" placeholder="跳转网页">
                        <button class="add_second_menu all_hover">添加二级菜单</button>
                    @else
                        <button class="add_second_menu all_hover">添加二级菜单</button>
                        @foreach($first->sub_button as $second)
                        <div class="second_menu_list">
                            <input class="menu_second_name" value="{{$second->name}}" placeholder="菜单名称">
                            <input class="menu_second_url" value="{{$second->url}}" placeholder="跳转网页">
                            <img src="{{URL::asset('shop/images/detail/close.png')}}" class="close_this">
                        </div>
                        @endforeach
                    @endif
                </div>
            @endforeach
        </div>
        @else
        <div class='menu_container' hidden>
            <div class='close_all'><img src="{{URL::asset('shop/images/detail/close.png')}}" class='img-size'></div>
        </div>
        @endif
        <div class='btn-bottom'>
            <span class='Public-btn btn-cancle all_hover'>取消</span>
            <span class='Public-btn btn-confirm all_hover'>确定</span>
        </div>
</div>
<div class='confirm_window' hidden>
    <div class='window-title'>温馨提示</div>
    <div class='warning-content'>
        <div class='warning-tip'>！</div>
        <span class='warning-title'>发布确认</span>
        <span class='warning-detail'>发布成功后会覆盖原版本，且将在24小时内对所有用户生效，确认发布？</span>
    </div>
    <div class='window-btn-bottom'>
        <span class='window-btn btn-window-cancle all_hover_window'>取消</span>
        <span class='window-btn btn-window-confirm all_hover_window'>确定</span>
    </div>
</div>
<script type="text/javascript">
    //全局变量定义   
        var first_menu_name = new Array();
        var first_menu_url = new Array();
        var second_menu_name = new Array();
        var second_menu_url = new Array();  
    //侧边导栏
        $('.side-list').find('.onsidebar').removeClass('onsidebar');
        $('.publicmanage-new').addClass('onsidebar');
    //载入初始数据
    $('.welcome_message').html($('.mesage-out').val());
    //hover效果
        $('.all_hover').on("mouseover",function(){
            $(this).css('color','#fff');
            $(this).css('background-color','#fb2d5c');
        });
        $('.all_hover').on("mouseout",function(){
            $(this).css('color','#fff');
            $(this).css('background-color','#a7a4a4');
        });
        $('.all_hover_window').on("mouseover",function(){
            $(this).css('color','#fff');
            $(this).css('background-color','#fb2d5c');
        });
        $('.all_hover_window').on("mouseout",function(){
            $(this).css('color','#212121');
            $(this).css('background-color','#fff');
        });
    //添加一级菜单
        $('.add_first_menu').on('click',function(){
            $('.menu_container').show();
            var len1 = $('.menu_container').children('.first_menu_list').length;
            if(len1>=3){
                $(this).addClass('disabled');
                // $(this).attr('disabled','true');
            }else{
                $(this).removeClass('disabled');
            }
            if($(this).hasClass('disabled')){
            }else{
                var element = '<div class="first_menu_list">'+
                                  '<span class="menu_name_title">菜单名称</span>'+
                                  '<input class="menu_first_name" type="text"  value="" placeholder="菜单名称">'+
                                  '<input class="menu_first_url" type="text" value="" placeholder="跳转网页">'+
                                  '<button class="add_second_menu all_hover">添加二级菜单</button>'+
                              '</div>';
            $('.menu_container').append(element);
            }
        });
    //删除全部菜单
        $('.close_all').on('click',function(){
            $('.menu_container').empty();
            $('.menu_container').hide();
        });
    //添加二级菜单
        var order = 0;
        $('.menu_container').on('click','.add_second_menu',function(){
            order++;
            var len2 = $(this).parent('.first_menu_list').children('.second_menu_list').length;
            if(len2>=5){
                $(this).addClass('disabled');
                // $(this).attr('disabled','false');
            }else{
                $(this).removeClass('disabled');
                 // $(this).attr('disabled','');
            }
            if($(this).hasClass('disabled')){
            }else{
                var menu_list = '<div class="second_menu_list menu'+order+'">'+
                                '<input class="menu_second_name" value="" placeholder="菜单名称">'+
                                '<input class="menu_second_url" value="" placeholder="跳转网页">'+
                                '<img src="{{URL::asset("shop/images/detail/close.png")}}" class="close_this">'+
                            '</div>';
                $(this).parent('.first_menu_list').append(menu_list);
            }
            $(this).siblings('.menu_first_url').hide();
            $(this).siblings('.menu_first_url').val(0);
            
        });
    //删除单个二级菜单
        $('.menu_container').on('click','.close_this',function(){
            var object = $(this).parent('.second_menu_list').parent('.first_menu_list');
            var length = object.children('.second_menu_list').length;
            $(this).parent(".second_menu_list").remove();
            if(length==1){
                object.children('.menu_first_url').show();
                object.children('.menu_first_url').val("");
            }
        }); 
    //点击出现弹窗
        $('.btn-confirm').on('click',function(){
            check();
            if($('.error-box').length==0){
                cancel_index=layer.open({
                      type: 1,
                      title: false,
                      closeBtn: 0,
                      shadeClose: true,
                      skin: 'yourclass',
                      shade: 0.5,
                      area : ['660px' , '390px'],
                      content:$('.confirm_window'),          
                });
            }else{
                alert('请输入格式正确的url地址！');
            }
        });
    //弹窗按钮操作
        $(".btn-window-cancle").on("click",function(){
             layer.close(cancel_index);
             $('.confirm_window').hide();
        });
        $(".btn-window-confirm").on("click",function(){
            var menu3 = new Array();
            var first_menu = $('.first_menu_list');
            $.each(first_menu,function(i,val){
                var first = new Object();
                var second = new Array();
                var second_menu = $(this).children('.second_menu_list');
                first_menu_name.push($(this).children('.menu_first_name').val());
                first_menu_url.push($(this).children('.menu_first_url').val());
                first['name']=$(this).children('.menu_first_name').val();
                first['type']='view';
                if($(this).children('.menu_first_url').val()!=0){
                    first['url']=$(this).children('.menu_first_url').val();
                }else{
                    first['sub_button'] = new Array();
                    $.each(second_menu,function(key,value){
                        var middle = new Object();
                        second_menu_name.push($(this).children('.menu_second_name').val());
                        second_menu_url.push($(this).children('.menu_second_url').val());
                        middle['name']=$(this).children('.menu_second_name').val();
                        middle['url']=$(this).children('.menu_second_url').val();
                        middle['type']='view';
                        first['sub_button'].push(middle);
                    });    
                }
                menu3[i]=first;
            });
            // post操作
            console.log(menu3);
            menu3 = JSON.stringify(menu3);
                var text = $('.welcome_message').val();
                $.ajax({
                          type:'post',
                          url:'/Brand/publicmanage/edit',
                          data:{                            
                            subscribe_text:text,
                            menu:menu3,
                            menu_changed:0,
                          },
                          dataType:"json",
                          success:function(result){
                            if(result.status=="success"){
                                layer.close(cancel_index);
                                $('.confirm_window').hide();
                            } else{
                              alert(result.message);
                            }               
                          }
                });
        });
    //校验url
        function check(){
            var first_url = $('.menu_first_url');
            var strRegex = /http(s)?:\/\/([\w-]+\.)+[\w-]+(\/[\w- .\/?%&=]*)?/;
            var re = new RegExp(strRegex);
            $.each(first_url,function(i,val){
                    if (re.test($(this).val())||$(this).val==0) {
                        return true;
                    } else {
                        var tip = '<div class="error-box"></div>';
                        $(this).parent('.first_menu_list').append(tip);
                        $(this).parent('.first_menu_list').children('.error-box').html("请输入正确的url格式！");
                        return false;
                    }
            });
            var second_url=$('.menu_second_url');
            $.each(second_url,function(){
                if (re.test($(this).val()) ){

                }else{
                    var tip = '<div class="error-box"></div>';
                    $(this).parent('.second_menu_list').append(tip);
                    $(this).parent('.second_menu_list').children('.error-box').html("请输入正确的url格式！");
                }
            });
        }   
</script>
@endsection