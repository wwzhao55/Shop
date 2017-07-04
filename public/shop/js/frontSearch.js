$(document).ready(function(){
    //ajax设置csrf_token
    $.ajaxSetup({
        headers: {
       'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });  
    var ynhistory=$('.y-n-history').html();
    if(ynhistory==0){
        $('#no-search-history').css('display','block');
    }else{
        $('#y-search-history').css('display','block');
    }

    $("#mysearch").on("click", ".search-delete", function() {
        $.confirm("清除所有单品搜索记录", "确认清除搜索记录！", function() {                       
            $.ajax({
                type:'POST',
                url:'/shop/front/delsearch',                          
                dataType:"json",
                success:function(result){
                    if(result.status=="success"){
                        $('#no-search-history').css('display','block');
                        $('#y-search-history').css('display','none'); 
                        $.toast(result.msg);
                    }else{
                        alert(result.msg);
                    }
                }               
            });
        }, function() {
          //取消操作
        });
    });

    $('#cancel').on('click',function(){
        $('#search_view').css('display','block');
        $('#search_result').empty();
        $('#search_result').css('display','none');
        $('.search-content').val('');
        $('#firstPage').css('display','block');
        $('#sea').css('display','none');

    });
    $('.search-image').on('click',function(){
        $('#search_result').empty();
        var content=$('.search-content').val();    
            $.ajax({
                type:'POST',
                url:'/shop/front/search',                          
                dataType:"json",
                data:{
                    content:content
                },
                success:function(result){
                    if(result.status=="success"){
                        var search_result=$('#search_result');                        
                        if(result.msg.length==0){                            
                            var tip=$("<div class='tips'>暂无搜索结果</div>");
                            search_result.append(tip);
                        }else{
                            for(var i=0;i<result.msg.length;i++){
                                var a_id=$("<a href='/shop/front/detail?commodity_id="+result.msg[i].id  +"'></a>");
                                var div_list=$("<div class='commodity-list'></div>");
                                var div_img=$("<div class='list-img'></div>");
                                var img=$("<img src='/"+result.msg[i].main_img+"' class='commodity-image'>");
                                var div_value=$("<div class='list-value'>￥"+result.msg[i].price+"</div>");
                                var div_title=$("<div class='list-title'>"+result.msg[i].commodity_name+"</div>"); 
                                div_img.append(img).append(div_value);
                                div_list.append(div_img).append(div_title);
                                a_id.append(div_list);
                                search_result.append(a_id);
                            }
                        }
                        $('#search_view').css('display','none');
                        $('#search_result').css('display','block');
                    }else{
                        alert(result.msg);
                    }       
                }                     
            });  
    });

    $(".search-content").keyup(function(e){
        if(e.keyCode == 13){
            //这里写你要执行的事件
            $('#search_result').empty();
        var content=$('.search-content').val();    
            $.ajax({
                type:'POST',
                url:'/shop/front/search',                          
                dataType:"json",
                data:{
                    content:content
                },
                success:function(result){
                    if(result.status=="success"){
                        var search_result=$('#search_result');                        
                        if(result.msg.length==0){                            
                            var tip=$("<div class='tips'>暂无搜索结果</div>");
                            search_result.append(tip);
                        }else{
                            for(var i=0;i<result.msg.length;i++){
                                var a_id=$("<a href='/shop/front/detail?commodity_id="+result.msg[i].id  +"'></a>");
                                var div_list=$("<div class='commodity-list'></div>");
                                var div_img=$("<div class='list-img'></div>");
                                var img=$("<img src='/"+result.msg[i].main_img+"' class='commodity-image'>");
                                var div_value=$("<div class='list-value'>￥"+result.msg[i].price+"</div>");
                                var div_title=$("<div class='list-title'>"+result.msg[i].commodity_name+"</div>"); 
                                div_img.append(img).append(div_value);
                                div_list.append(div_img).append(div_title);
                                a_id.append(div_list);
                                search_result.append(a_id);
                            }
                        }
                        $('#search_view').css('display','none');
                        $('#search_result').css('display','block');
                    }else{
                        alert(result.msg);
                    }       
                }                     
            });
        }
    });

    $('.search-content-his-pop').on('click',function(){
        var ser=$(this).children('span').html();
        $('.search-content').val(ser);
        $('#search_view').css('display','none');
        $('#search_result').css('display','block');
        $.ajax({
            type:'POST',
            url:'/shop/front/search',                          
            dataType:"json",
            data:{
                content:ser
            },
            success:function(result){
                    if(result.status=="success"){
                        var search_result=$('#search_result');                        
                        if(result.msg.length==0){                            
                            var tip=$("<div class='tips'>暂无搜索结果</div>");
                            search_result.append(tip);
                        }else{
                            for(var i=0;i<result.msg.length;i++){
                                var a_id=$("<a href='/shop/front/detail?commodity_id="+result.msg[i].id  +"'></a>");
                                var div_list=$("<div class='commodity-list'></div>");
                                var div_img=$("<div class='list-img'></div>");
                                var img=$("<img src='/"+result.msg[i].main_img+"' class='commodity-image'>");
                                var div_value=$("<div class='list-value'>￥"+result.msg[i].price+"</div>");
                                var div_title=$("<div class='list-title'>"+result.msg[i].commodity_name+"</div>"); 
                                div_img.append(img).append(div_value);
                                div_list.append(div_img).append(div_title);
                                a_id.append(div_list);
                                search_result.append(a_id);
                            }
                        }
                        $('#search_view').css('display','none');
                        $('#search_result').css('display','block');
                    }else{
                        alert(result.msg);
                    }       
            }                    
        });
    });
  
});