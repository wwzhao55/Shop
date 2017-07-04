  // auth:zww
  // date:2016.08.02
$(document).ready(function(){
  window.onload = function () {
    var u = navigator.userAgent;
    if (u.indexOf('Android') > -1 || u.indexOf('Linux') > -1) {
        //安卓手机
        $("body").css('font-family','思源黑体 CN Regular');
    } else if (u.indexOf('iPhone') > -1) {
      //苹果手机
      $("body").css('font-family','PingFang Regular');
      // setActiveStyleSheet("{{asset('shop/css/coupon.css')}}");//加载对应的css
    } else {
      //其他设备
      $("body").css('font-family','PingFang Regular');
    } 
  }
}) 