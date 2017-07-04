  // auth:zww
  // date:2016.08.03
  // content：update
$(document).ready(function(){
    // $.ajaxSetup({
    //     headers: { 'X-CSRF-TOKEN' : '{{ csrf_token() }}' }
    // });
    $.ajaxSetup({
        headers: {
       'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $(".swiper-container").swiper({
        autoplay:5000,
        paginationClickable:true,
        speed:1000,
        loop:true
    });
    var countryBrotherUp_name=new Array();
    var  countryBrotherNext_name=new Array();
    var  countryBrotherUp;
    var  countryBrotherNext;
    var  countryFather;
    var  countryFather_name;
    var  type = $(".area").children(".country").first().children("span").attr("id");
    var  page=1;
    var  name;
   // $(".list-title2").html( $("#selectCountry .on").html());
    //点击分类时进行post数据请求
    $(".country").on("click",'.countryName',function(){
        $(this).parents('.area').find('.on').removeClass('on');
        countryBrotherUp_name.length=0;
        countryBrotherNext_name.length=0;
         // 找出点击的国家名称的父级元素
        countryFather=$(this).parents('.country') ;
        countryBrotherUp=countryFather.prevAll(".country");
        countryBrotherNext=countryFather.nextAll(".country");      
        countryFather_name=countryFather.prop('innerHTML');

        $.each(countryBrotherUp,function(i,val){
            countryBrotherUp_name.unshift($(this).prop('innerHTML'));
        }); 

        $.each(countryBrotherNext,function(i,val){
            countryBrotherNext_name.push($(this).prop('innerHTML'));
        });  
        country= $(this).parents('.area').find(".country");

        $.each(country,function(i,val){       
            if(i==0){
              $(this).html(countryFather_name);
              $(this).find('span').addClass('on');
            }
            else if(i-1<countryBrotherNext_name.length){
             $(this).html(countryBrotherNext_name[i-1]);
            }
            else{
              $(this).html(countryBrotherUp_name[i-countryBrotherNext_name.length-1]);
            }
        }); 
        //滚动
        var offset_top = $("#"+$(this).html()).offset().top;
        $('body').animate({scrollTop: offset_top - 68}, 300); 
        //请求数据显示对应的商品
           /* type=$(this).attr("id");
            name=$(this).html();
            page=0;
              $.ajax({
                    type:'POST',
                    url:'/shop/front/product',                          
                    dataType:"json",
                    data:{
                        type:type,
                        page:page
                    },
                    success:function(result){
                     
                        var  commodity_length =result.msg.commodity.length;
                        if(commodity_length>0){   
                                page++;
                                var commodity=result.msg.commodity;
                                $("#commodity").html("");
                                var div=$("<div class='list-title1' >"+name+"</div>");
                                $("#commodity").append(div);
                                for(var i=0;i<commodity_length;i++){
                                    var src="http://shop.dataguiding.com/"+commodity[i].main_img;
                                    var a=$("<a href='/shop/front/detail?commodity_id="+commodity[i].id+" '></a>");
                                    var divlist=$("<div class='commodity-list'></div>");
                                    var div_item=$("<div class='list-img'></div>");
                                    var img=$("<img src="+src+" class='commodity-image' width='330px' height='330px' />");
                                    var div_price=$("<div class='list-value'>￥"+commodity[i].price+"</div>");
                                    var div_title=$("<div class='list-title'>"+commodity[i].commodity_name+"</div>");
                                    div_item.append(img).append(div_price);
                                    divlist.append(div_item).append(div_title);
                                    a.append(divlist);
                                    $("#commodity").append(a); 
                                }
                                if(result.msg.finish==false){ 
                                  var div=$("<div class='commodity-more'>查看更多</div>");
                                  $("#commodity").append(div);
                                }else{
                                        var tips_down=$("<div class='commodity-down'></div>");
                                        var href_1=$("<a href='/shop/front/index'><span class='commodity-down-list'>店铺首页</span></a>");
                                        var href_2=$("<a href='/shop/vip/index'><span class='commodity-down-list'>会员中心</span><a>");
                                        var href_3=$("<a href='/shop/front/focus'><span class='commodity-down-list'>关注我们</span></a>");                   
                                        tips_down.append(href_1).append(href_2).append(href_3);
                                        var div1=$("<div class='clear' style='clear:both'></div>");
                                        var div2=$("<div class='commodity-cover' ></div>");
                                        $("#commodity").append(tips_down).append(div1).append(div2);
                                }                
                        }
                        else if(page==0&&commodity_length==0)
                        {
                          var div=$("<div class='no-commoditys'>暂无此类商品！</div>");
                          $("#commodity").html(div);
                        }
                    }                     
              });*/
    });
    //点击查看更多
      // $("#commodity").on("click",'.commodity-more',function(){
      //           $.ajax({
      //                 type:'POST',
      //                 url:'/shop/front/product',                          
      //                 dataType:"json",
      //                 data:{
      //                     type:type,
      //                     page:page
      //                 },
      //                 success:function(result){
      //                   console.log(result.msg);
      //                   var commodity_more_title=$(".commodity-more");
      //                  //   alert(commodity_more_title.length);
      //                  for(var i=0;i<commodity_more_title.length;i++){
      //                    commodity_more_title.eq(i).remove();
      //                   }
      //                   page++;
      //                   var  commodity_length =result.msg.commodity.length;
      //                   if(commodity_length>0){
      //                        var commodity=result.msg.commodity;
      //                       // $("#commodity").html("");
      //                       for(var i=0;i<commodity_length;i++){
      //                           var src="http://shop.dataguiding.com/"+commodity[i].main_img;
      //                           var a=$("<a href='/shop/front/detail?commodity_id="+commodity[i].id+" ' class='commodity-block'></a>");
      //                           var divlist=$("<div class='commodity-list'></div>");
      //                           var div_item=$("<div class='list-img'></div>");
      //                           var img=$("<img src="+src+" class='commodity-image' width='330px' height='330px' />");
      //                           var div_price=$("<div class='list-value'>￥"+commodity[i].price+"</div>");
      //                           var div_title=$("<div class='list-title'>"+commodity[i].commodity_name+"</div>");
      //                           div_item.append(img).append(div_price);
      //                           divlist.append(div_item).append(div_title);
      //                           a.append(divlist);
      //                           $("#commodity").append(a);
      //                       }
      //                       var div1=$("<div class='clear' style='clear:both'></div>");
      //                       var div2=$("<div class='commodity-cover' ></div>");
      //                         $("#commodity").append(div1).append(div2);
      //                         if(result.msg.finish==false){ 
      //                             var div=$("<div class='commodity-more'>查看更多</div>");
      //                             $("#commodity").append(div);
      //                          }else{
      //                           var tips_down=$("<div class='commodity-down'></div>");
      //                           var href_1=$("<a href='/shop/front/index'><span class='commodity-down-list'>店铺首页</span></a>");
      //                           var href_2=$("<a href='/shop/vip/index'><span class='commodity-down-list'>会员中心</span><a>");
      //                           var href_3=$("<a href='/shop/front/focus'><span class='commodity-down-list'>关注我们</span></a>");                   
      //                           tips_down.append(href_1).append(href_2).append(href_3);
      //                           var div1=$("<div class='clear' style='clear:both'></div>");
      //                           var div2=$("<div class='commodity-cover' ></div>");
      //                           $("#commodity").append(tips_down).append(div1).append(div2);
      //                          }
      //                   }
      //                  if(page==0&&commodity_length==0)
      //                   {
      //                       var div=$("<div class='no-commoditys'>暂无此类商品！</div>");
      //                       $("#commodity").html(div);
      //                   }             
      //                 }                     
      //             });
      // })
  //切换分类
  $(".change-cols").on("click",'.changecountryName',function(){
          $('.area').css('display','block');         
          $('#changeArea').css('display','none'); 
          $('#commodity').css('background-color','');
          $(this).parents('.change-list').find('.on').removeClass('on');
          $(this).parents('.change-cols').addClass('on');
          var clickname=$(this).html();
          var selectcountry=$('.countryName');
          $.each(selectcountry,function(i,val){
              if(selectcountry[i].innerHTML==clickname){
                  $(this).trigger("click");                  
              }
          });
          $('.commodity-cover').css('display','none');
          $('body').css('overflow','auto');
          $('html').css('overflow','auto');
  });
     
  $("span.down").on('click',function(){         
      $('.area').css('display','none');          
      $('#changeArea').css('display','block');
      $('body').css('overflow','hidden');
      $('html').css('overflow','hidden'); 
      $('.commodity-cover').css('display','block');
      var s=$('.area').find('.on').html();
      country= $('.change-list').find(".changecountryName");
      $.each(country,function(i,val){       
          if(country[i].innerHTML==s){
            $(this).parents('.change-list').find('.on').removeClass('on');
            $(this).parents('.change-cols').addClass('on');
          }              
      });  
  });

  $("span.up").on('click',function(){         
      $('.area').css('display','block');         
      $('#changeArea').css('display','none'); 
      $('body').css('overflow','auto');
      $('html').css('overflow','auto');
      $('.commodity-cover').css('display','none');  
  });

  $(".commodity-cover").on('click',function(){         
      $('.area').css('display','block');         
      $('#changeArea').css('display','none'); 
      $('body').css('overflow','auto');
      $('html').css('overflow','auto');
      $('.commodity-cover').css('display','none');       
  });

  $(function () {
    var ie6 = document.all;
    var dv = $('#selectCountry');
    dv.attr('otop', dv.offset().top); //存储原来的距离顶部的距离
    $(window).scroll(function () {
      st = Math.max(document.body.scrollTop || document.documentElement.scrollTop);
      if (st > parseInt(dv.attr('otop'))) {
        if (ie6) {//IE6不支持fixed属性，所以只能靠设置position为absolute和top实现此效果
          dv.css({ position: 'absolute', top: st });
        }
        else if (dv.css('position') != 'fixed') 
          dv.css({ 'position': 'fixed', top: 0 });
      } 
      else if (dv.css('position') != 'static') 
        dv.css({ 'position': 'static' });
    });
  });
  $(window).scroll(function(){  
      var top=$(document).scrollTop();
      var index=$('.index');
      var selectcountry=$('.countryName');
      $.each(index,function(i,val){
          if(top==((index[i].innerHTML-1)/2*586+726)){
            $(".countryName").eq(i).trigger("click"); 
          }
         // if((i+1)==index.length){
         //    // if(top==((index[i].innerHTML-1)/2*586+726)){
         //       $(".countryName").eq(i).trigger("click");alert('2')
         //    }     
      });
  });
  //搜索框点击事件
  $('.search').on('click',function(){
     $('#firstPage').css('display','none');
     $('#sea').css('display','block');
     $(".search-content").click();
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
        $("#sea .commodity-down").hide();
        // location.reload();

    });
    $('.search-image').on('click',function(){   
      if($('.search-content').val()!=''){
        $('#search_result').empty();
        $('.loading').show();
        var content=$('.search-content').val();    
            $.ajax({
                type:'POST',
                url:'/shop/front/search',                          
                dataType:"json",
                data:{
                    content:content
                },
                success:function(result){
                  $('.loading').hide();
                    if(result.status=="success"){
                        var search_result=$('#search_result');                        
                        if(result.msg.length==0){                            
                            var tip=$("<div class='tips'>没有找到相关产品哦~</div><a href='/shop/front/index'><span class='gotofirpage'>去逛逛</span></a>");
                            search_result.append(tip);
                            var search1=$("<div class='search-content-his-pop'><span class='search-popular-commodity'>"+content+"</span></div>");
                            $(".search-popular-list").append(search1);
                            $('#search_view').css('display','block');
                            $(".search-delete").hide();
                        }else{
                            for(var i=0;i<result.msg.length;i++){
                                var a_id=$("<a href='/shop/front/detail?commodity_id="+result.msg[i].id  +"' class='commodity-block'></a>");
                                var div_list=$("<div class='commodity-list'></div>");
                                var div_img=$("<div class='list-img'></div>");
                                var img=$("<img src='/"+result.msg[i].main_img+"' class='commodity-image'>");
                                var div_value=$("<div class='list-value'>￥"+result.msg[i].price+"</div>");
                                var div_title=$("<div class='list-title'>"+result.msg[i].commodity_name+"</div>"); 
                                div_img.append(img).append(div_value);
                                div_list.append(div_img).append(div_title);
                                a_id.append(div_list);
                                search_result.append(a_id);
                                $('#search_view').css('display','none');
                                var search1=$("<div class='search-content-his-pop'><span class='search-popular-commodity'>"+content+"</span></div>");
                                $(".search-popular-list").append(search1);
                            }
                                var blank=$("<div class='commodity-blank'></div>");
                                var tips_down=$("<div class='commodity-down'></div>");
                                var href_1=$("<a href='/shop/front/index'><span class='commodity-down-list'>店铺首页</span></a>");
                                var href_2=$("<a href='/shop/vip/index'><span class='commodity-down-list'>会员中心</span><a>");
                                var href_3=$("<a href='/shop/front/focus'><span class='commodity-down-list'>关注我们</span></a>");                   
                                tips_down.append(href_1).append(href_2).append(href_3);
                                var div1=$("<div class='clear' style='clear:both'></div>");
                                var div2=$("<div class='commodity-cover' ></div>");
                                $("#mysearch").append(blank).append(tips_down).append(div1).append(div2);
                        }
                        // $('#search_view').css('display','none');
                        $('#search_result').css('display','block');
                    }else{
                        alert(result.msg);
                    }       
                }                     
            });
        }else{
            // alert("请输入搜索内容！");
        }  
    });

    $(".search-content").keyup(function(e){
      $('#search_result').empty();
      if($('.search-content').val()!=''){
        if(e.keyCode == 13){
            //这里写你要执行的事件
        $('#search_result').empty();
        var content=$('.search-content').val();  
        $('.loading').show();  
            $.ajax({
                type:'POST',
                url:'/shop/front/search',                          
                dataType:"json",
                data:{
                    content:content
                },
                success:function(result){
                  $('.loading').hide();
                    if(result.status=="success"){
                        var search_result=$('#search_result');                        
                        if(result.msg.length==0){                            
                            var tip=$("<div class='tips'>没有找到相关产品哦~</div><a href='/shop/front/index'><span class='gotofirpage'>去逛逛</span></a>");
                            search_result.append(tip);
                            $('#search_view').css('display','block');
                            $(".search-delete").hide();
                        }else{
                            for(var i=0;i<result.msg.length;i++){
                                var a_id=$("<a href='/shop/front/detail?commodity_id="+result.msg[i].id  +"' class='commodity-block'></a>");
                                var div_list=$("<div class='commodity-list'></div>");
                                var div_img=$("<div class='list-img'></div>");
                                var img=$("<img src='/"+result.msg[i].main_img+"' class='commodity-image'>");
                                var div_value=$("<div class='list-value'>￥"+result.msg[i].price+"</div>");
                                var div_title=$("<div class='list-title'>"+result.msg[i].commodity_name+"</div>"); 
                                div_img.append(img).append(div_value);
                                div_list.append(div_img).append(div_title);
                                a_id.append(div_list);
                                search_result.append(a_id);
                                $('#search_view').css('display','none');
                                
                            }
                                var blank=$("<div class='commodity-blank'></div>");
                                var tips_down=$("<div class='commodity-down'></div>");
                                var href_1=$("<a href='/shop/front/index'><span class='commodity-down-list'>店铺首页</span></a>");
                                var href_2=$("<a href='/shop/vip/index'><span class='commodity-down-list'>会员中心</span><a>");
                                var href_3=$("<a href='/shop/front/focus'><span class='commodity-down-list'>关注我们</span></a>");                   
                                tips_down.append(href_1).append(href_2).append(href_3);
                                var div1=$("<div class='clear' style='clear:both'></div>");
                                var div2=$("<div class='commodity-cover' ></div>");
                                $("#mysearch").append(blank).append(tips_down).append(div1).append(div2);
                        }
                        
                        $('#search_result').css('display','block');
                    }else{
                        alert(result.msg);
                    }       
                }                     
            });
        }
      }else{
        // alert("请输入搜索内容！");
      }
    });

    $('.search-content-his-pop').on('click',function(){
        var ser=$(this).children('span').html();
        $('.search-content').val(ser);
        $('#search_view').css('display','none');
        $('#search_result').css('display','block');
        $('.loading').show();
        $('#search_result').empty();
        $.ajax({
            type:'POST',
            url:'/shop/front/search',                          
            dataType:"json",
            data:{
                content:ser
            },
            success:function(result){
              $('.loading').hide();
                    if(result.status=="success"){
                        var search_result=$('#search_result');                        
                        if(result.msg.length==0){                            
                            var tip=$("<div class='tips'>没有找到相关产品哦~</div><a href='/shop/front/index'><span class='gotofirpage'>去逛逛</span></a>");
                            search_result.append(tip);
                            $('#search_view').css('display','block');
                            $(".search-delete").hide();
                        }else{
                            for(var i=0;i<result.msg.length;i++){
                                var a_id=$("<a href='/shop/front/detail?commodity_id="+result.msg[i].id  +"' class='commodity-block'></a>");
                                var div_list=$("<div class='commodity-list'></div>");
                                var div_img=$("<div class='list-img'></div>");
                                var img=$("<img src='/"+result.msg[i].main_img+"' class='commodity-image'>");
                                var div_value=$("<div class='list-value'>￥"+result.msg[i].price+"</div>");
                                var div_title=$("<div class='list-title'>"+result.msg[i].commodity_name+"</div>"); 
                                div_img.append(img).append(div_value);
                                div_list.append(div_img).append(div_title);
                                a_id.append(div_list);
                                search_result.append(a_id);
                                $('#search_view').css('display','none');
                                                            
                            }
                                var blank=$("<div class='commodity-blank'></div>");
                                var tips_down=$("<div class='commodity-down'></div>");
                                var href_1=$("<a href='/shop/front/index'><span class='commodity-down-list'>店铺首页</span></a>");
                                var href_2=$("<a href='/shop/vip/index'><span class='commodity-down-list'>会员中心</span><a>");
                                var href_3=$("<a href='/shop/front/focus'><span class='commodity-down-list'>关注我们</span></a>");                   
                                tips_down.append(href_1).append(href_2).append(href_3);
                                var div1=$("<div class='clear' style='clear:both'></div>");
                                var div2=$("<div class='commodity-cover' ></div>");
                                $("#mysearch").append(blank).append(tips_down).append(div1).append(div2);
                        $('#search_result').css('display','block');
                        }    
                    }else{
                        alert(result.msg);
                    }
            }                       
        });
    });
})
