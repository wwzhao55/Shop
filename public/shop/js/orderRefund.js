 
$(document).ready(function(){
    //退款处理页js开始    
    //图片上传
        // var s=0;
        // $('.add-img').on('click',function(){
        //     var input_1="<input type='file' class='image' name='img[]' style='display:none'>";
        //     $('.img-box').append(input_1);
        //     $(this).siblings('.image').eq(s).click();
        //     s++;
        //     console.log(s);
        //     if(s==4){
        //         s=0;
        //     }
        // }); 
        // var i=0;
        // $(".img-box").on('change','.image',function(){
        //     console.log(i);
        //     var objUrl = getObjectURL(this.files[0]) ;
        //     if (objUrl&&i<4){ 
        //         i++;
        //         var img_list="<img src='"+objUrl+"' class='img_list' />";
        //         var img_delete="<img class='img-delete' src='{{asset('/shop/images/myorder/De@2x.png')}}' />";
        //         $('.img-lists').append(img_list).append(img_delete);    
        //     }
        //     if(i==3){
        //         $('.img-box').css('display','none');
        //     }
        // }) ;
        // //建立一個可存取到該file的url
        // function getObjectURL(file) {
        //     var url = null ; 
        //     if (window.createObjectURL!=undefined) { // basic
        //         url = window.createObjectURL(file) ;
        //     } else if (window.URL!=undefined) { // mozilla(firefox)
        //         url = window.URL.createObjectURL(file) ;
        //     } else if (window.webkitURL!=undefined) { // webkit or chrome
        //         url = window.webkitURL.createObjectURL(file) ;
        //     }
        //         return url ;
        //     } 
        // //删除图片
        // $('.img-lists').on('click','.img-delete',function(){
        //     i--;
        //     if($(".img-list").length<3){
        //         $('.img-box').css('display','block');
        //     }
        //     $(this).prev(".img_list").remove();
        //     $(this).css("display","none");     
        // }); 
        // $('.submit-refund-word').on('click',function(){
        //     $('#form').submit();
        // });
    //订单详情页js开始
        //联系卖家
        // $("#orderdetail").on("click", ".order-list-action-image1", function() {
        //             $.confirm("确定拨打电话{{$contact}}吗？", "拨打电话！", function() {
        //               // $.toast("订单已经取消!");
        //             }, function() {
        //               //取消操作
        //             });
        // });
        // $("#orderdetail").on("click", ".contacter-phone", function() {
        //     $.confirm("确定拨打电话{{$contacter}}吗？", "拨打电话！", function() {
        //           // $.toast("订单已经取消!");
        //     }, function() {
        //           //取消操作
        //     });
        // });
    //订单详情页js结束  

    var money = new Array();
    var total_money = 0;
    $('.order-list-content').each(function(){
        var count = $(this).children('.order-list-amount').html().replace(/[^0-9]/ig,"");
        var price = $(this).children().find('.order-list-content-value').html();
        price = price.replace('￥','');
        var total = parseFloat(count*price);
        money.push(total);
    });
    for(var i=0;i<money.length;i++){
        total_money += money[i];
    }
    $('.total-money').html(total_money.toFixed(2)); 
})