  // auth:zww
  // date:2016.05.25
$(document).ready(function(){
//ajax设置csrf_token
    $.ajaxSetup({
        headers: {
       'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    
//编辑地址页
    //删除地址
    $('.edit_address').on("click", ".edit-delete-address", function() {
      var id=$(this).closest(".weui_cell").siblings("input[name='address_id']").val();
        $.confirm("确定要删除该地址吗？", "", function() {     
              $.ajax({
                  type:'POST',
                  url:'/shop/address/delete',
                  data:{
                      address_id:id
                  },
                  dataType:"json",
                  success:function(result){ 
                      if(result.status == 'success'){
                        $.toast("已经删除!");
                        window.location.href = document.referrer;
                      }else{
                         $.toast(result.msg, "cancle"); 
                       } 
                  }              
              });
          }, function() {

            });
    });
    //点击默认
    var n=0;
    $("input[name='is_default']").val(0);
    $("#set_default").on('click',function(){
      n++;
      if(n%2==0)
      {
        $("input[name='is_default']").val(0);
      }else{
        $("input[name='is_default']").val(1);
      }
    });

//选择收货地址
    // if($("#addressChoose .receive").length==0){
    //   $("#addressChoose").siblings('.prompt').css("display","block");
    // }
    // $("#addressChoose .receive").on('click',function(){
    //   var id=$(this).children(".address-id").html();
    //   $('#input_address').val(id);
    //   $('#addressChoose form').submit();
    // });
    

//管理收货地址
    if($(".addressManage .address-list").length==0){
      $(".addressManage").siblings('.prompt').css("display","block");
    }
    var url = window.location.search;
    if(url=='?type=vip'){
       $(".editAddress").css("display","block");
       $(".address-list .receive").addClass('disabled');
    }
    if(url=='?type=order'){
      $(".editAddress").css("display","none");
      $("#buttom").css("display","block"); 
      // $(".receive").removeClass('disabled');
      $(".receive").on('click',function(){
        var id=$(this).siblings(".address-id").html();
        $('#input_address').val(id);
        $('#form1').submit();
      });  
    }
  //设为默认地址
      $(".choose-default").on('click',function(){
      var imgobj = $(this);
      var img_logo=$(this).attr("src");
      var ID=$(this).parent().parent().children('.address-id').html();
      if(img_logo=="http://cache.dataguiding.com/img/shop/shopcat/dot-small.png"){
        $('.loading').show();
        $.ajax({
              type:'POST',
              url:'/shop/address/setdefault',
              data:{
                  address_id:ID
               },
              dataType:"json",
              success:function(result){
                 $('.loading').hide(); 
                //刷新
                if(result.status == 'success'){
                   //window.location.reload();
                   $(".choose-default").attr("src","http://cache.dataguiding.com/img/shop/shopcat/dot-small.png");
                   imgobj.attr("src","http://cache.dataguiding.com/img/shop/shopcat/check-small.png");
                   $(".choose-default").siblings('label').html('设为默认');
                   imgobj.siblings('label').html('默认地址');
                   $(".choose-default").closest('.address-list').children('.receive').children('h4').remove();  
                   imgobj.closest('.address-list').children('.receive').children('p').last().before("<h4>[默认地址]</h4>");
                }else{
                  alert(result.msg);    
                 }               
              }
            });
      }
      /*else{
        $(".choose-default").attr("src","http://cache.dataguiding.com/img/shop/shopcat/check-small.png");
        $(this).attr("src","http://cache.dataguiding.com/img/shop/shopcat/dot-small.png");
          $.ajax({
            type:'POST',
            url:'/shop/address/setdefault',
            data:{
              address_id:ID
            },
            dataType:"json",
            success:function(result){  
            //刷新
              if(result.status == 'success'){
                 //window.location.reload();
              }else{
                alert(result.msg);    
               }
            }               
          });
      }    */
      });
  //删除收货地址
      $('.addressManage').on("click", ".btn-del", function() {
      var obj = $(this);
      var id=$(this).parent().parent().parent(".address-list").children(".address-id").html();
        $.confirm("确定要删除该收货地址吗？", "", function() {
        $('.loading').show(); 
          $.ajax({
            type:'POST',
            url:'/shop/address/delete',
            data:{
              address_id:id
            },
            dataType:"json",
            success:function(result){
              $('.loading').hide();
              if(result.status=='success'){ 
                //$.toast(result.msg);
                obj.closest('.address-list').remove();                      
              }else{
                $.toast(result.msg);
              } 
            }               
          });
        }, function() {
        });
      });
})
