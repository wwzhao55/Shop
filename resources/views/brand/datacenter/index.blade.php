
<!-- auth:wuwenjia
data:16.7.21 -->

@extends('layouts.app')
@section('siderbar')
@include('layouts.siderbar')
@endsection
@section('content')
  <link rel="stylesheet" href="{{URL::asset('admin/css/brand-dataCenter.css')}}">
    <div class="data-center">
      @if($shop_count)
        <div class="data-box">
          <span class="data-num">{{$shop_count}}</span>
          <span class="data-info">分店数</span>
        </div>
         <div class="data-box">
            <span class="data-num">{{$fans_count}}</span>
           <span class="data-info">粉丝数</span>
          
        </div>
        <div class="data-box">
            <span class="data-num">{{$order_count}}</span>
           <span class="data-info">订单数</span>
          
        </div>
        <div class="data-box1">
           <span class="data-num">{{$total}}</span>
           <span class="data-info">交易额</span>
        </div>
         @endif
         <div class='clearfix'></div>
    </div>
      @if($shop_count)
    <div class="data-center graph-box">
      <div class="icon-msg"><span class="icon-title">数量</span></div>
      <!-- <div class="icon-msg">
      <img src="{{URL::asset('admin/img/ico1.png')}}" />
      <span class="icon-title">粉丝数</span>
      </div>
       <div class="icon-msg">
      <img src="{{URL::asset('admin/img/ico2.png')}}" />
      <span class="icon-title">注册用户数</span>
      </div>
       <div class="icon-msg">
      <img src="{{URL::asset('admin/img/ico3.png')}}" />
      <span class="icon-title">订单数</span>
      </div>
       <div class="icon-msg">
      <img src="{{URL::asset('admin/img/ico4.png')}}" />
      <span class="icon-title">交易额</span>
      </div> -->
      <select  class="icon-msg msgselect" id="msgselect">
          <!-- <option value="register">注册用户数</option> -->
          <option value="fans">粉丝数</option>
          <option value="order">订单数</option>
          <option value="turnover">交易额</option>
      </select>
      <div class="date-group">
          <button type="button" class="btn-date pull-right" id="month">月</button>
          <button type="button" class="btn-date  pull-right" id="week">周</button>
          <button type="button" class="btn-date  pull-right" id="day">日</button>
      </div>
       <div class="icon-msg1">
      <img src="{{URL::asset('admin/img/ico6.png')}}" id="bar"/>
      </div>
      <div class="icon-msg1">
      <img src="{{URL::asset('admin/img/ico5.png')}}" id="line"/>
      </div>
      <div class="graph" style="height:450px;">
        <canvas id="Chart-graph" height='450px'></canvas>
      </div>
    </div>
       @else   
           <p class="bg-success" style="padding:15px">咦，还没有数据哎,<a href="">请联系超级管理员，添加店铺</a></p>         
      @endif
     <script type="text/javascript">
     $(function(){       
$('.side-list').find('.onsidebar').removeClass('onsidebar');
$('.datacenterlist').addClass('onsidebar');  
$('#day').css('background-color','#c4c4c4');     
if($('.icon-msg option:selected').html()=='粉丝数'){
            var src1="{{URL::asset('admin/img/ico5.png')}}";
            var src2="{{URL::asset('admin/img/ico6.png')}}";
            $('#bar').attr("src",src2);
            $('#line').attr("src",src1);
            var label_day= ["00:00", "06:00", "12:00", "18:00", "00:00"];
            var label_week= ["一", "二", "三", "四", "五", "六", "日"];
            var label_month=["January","February","March","April","May","June","July"];
            var lineChartData = {
                labels: label_day,
                datasets: [
                  {
                    label: "users-num",
                    fillColor: "#facdae",   //背景色，常用transparent透明
                    strokeColor: "#facdae",  //线条颜色，也可用"#ffffff"
                    pointColor: "#facdae",   //点的填充颜色
                    pointStrokeColor: "#facdae",       //点的外边框颜色
                    pointHighlightFill: "#fff",
                    pointHighlightStroke: "rgba(220,220,220,1)",
                    // data: [30, 23, 43,56, 32, 60, 20]
                  }               
                ]
            };

            var lineChartOptions = {
                //Boolean - If we should show the scale at all
                showScale: true,//
                scaleShowLabels : true,
                scaleOverride: false,
                scaleSteps : 5,        //y轴刻度的个数
                scaleStepWidth : 20,   //y轴每个刻度的宽度
                scaleStartValue : 0,    //y轴的起始值
                // Y轴上的刻度,即文字
                // scaleLabel : "<%= 100/5   %>",

                //Boolean - Whether grid lines are shown across the chart
                scaleShowGridLines: true,//显示网格线
                //String - Colour of the grid lines
                scaleGridLineColor: "rgba(0,0,0,.05)",
                //Number - Width of the grid lines
                scaleGridLineWidth: 1,//网格线宽度
                //Boolean - Whether to show horizontal lines (except X axis)
                scaleShowHorizontalLines: true,//显示水平线
                //Boolean - Whether to show vertical lines (except Y axis)
                scaleShowVerticalLines: false,//显示竖直线
                //Boolean - Whether the line is curved between points
                bezierCurve: false,  // 是否使用贝塞尔曲线? 即:线条是否弯曲    
                //Number - Tension of the bezier curve between points
                bezierCurveTension: 0.3,
                //Boolean - Whether to show a dot for each point
                pointDot: true,//是否显示点数  

                //Number - Radius of each point dot in pixels
                pointDotRadius: 8,//圆点的大小 
                //Number - Pixel width of point dot stroke
                pointDotStrokeWidth: 1,// 圆点的笔触宽度, 即:圆点外层边框大小 

                //Number - amount extra to add to the radius to cater for hit detection outside the drawn point
                pointHitDetectionRadius: 20,
                //Boolean - Whether to show a stroke for datasets
                datasetStroke: true,
                //Number - Pixel width of dataset stroke
                datasetStrokeWidth: 1,
                bezierCurve : false,   // 是否使用贝塞尔曲线? 即:线条是否弯曲   
                //Boolean - Whether to fill the dataset with a color
                datasetFill: true,   // 是否填充数据集 
                animationSteps : 60,          // 动画的时间  
                 
                //String - A legend template
                legendTemplate: "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<datasets.length; i++){%><li><span style=\"background-color:<%=datasets[i].lineColor%>\"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>",
                //Boolean - whether to maintain the starting aspect ratio or not when responsive, if set to false, will take up entire container
                maintainAspectRatio: false,
                //Boolean - whether to make the chart responsive to window resizing
                responsive: true
            };
            function Initdata(){
                $('#day').css('background-color','#c4c4c4');
                $('#week').css('background-color','#fff');
                $('#month').css('background-color','#fff');
                var src1="{{URL::asset('admin/img/ico5.png')}}";
                var src2="{{URL::asset('admin/img/ico6.png')}}";
                $('#bar').attr("src",src2);
                $('#line').attr("src",src1);
                var end_time = Date.parse(new Date())/1000;
                console.log(end_time);
                var start_time = end_time - 60*60*32;
                if($('.icon-msg option:selected').html()=='粉丝数'){
                  $.ajax({
                    type: "post",
                    url: "/Brand/datacenter/data",
                    data: {
                      start_time:start_time, 
                      end_time:end_time,
                      unit:'day',
                    },
                    dataType: "json",
                    success: function(data){
                      console.log(data.total_array);
                      console.log(data.fans_array);
                      console.log(data.customer_array);
                      console.log(data.order_array);
                      lineChartData.labels=label_day;
                      // lineChartData.datasets[3].data=data.fans_array;
                      // lineChartData.datasets[2].data=data.customer_array;
                      // lineChartData.datasets[1].data=data.order_array;
                      lineChartData.datasets[0].data=data.fans_array;
                      lineChartOptions.scaleOverride=false;
                      lineChartOptions.scaleLabel="<%= value   %>";
                      $("#Chart-graph").remove();
                      var ctx=$("<canvas id='Chart-graph' height='450px'></canvas>");
                      $('.graph').append(ctx);
                      var lineChartCanvas = $("#Chart-graph").get(0).getContext("2d");
                      var lineChart = new Chart(lineChartCanvas);
                      lineChartOptions.datasetFill = false;
                      lineChart.Line(lineChartData, lineChartOptions);
                    }
                  });
                }
            }
            Initdata();
            $('#day').click(function(){
                Initdata();
                
            });
            function weekDay(end_weekday) {
                var weekday;
                if(end_weekday==0)
                  end_weekday=7;
                 switch(end_weekday){
                        case 7: weekday="日";break;
                        case 1: weekday="一";break;
                        case 2: weekday="二";break;
                        case 3: weekday="三";break;
                        case 4: weekday="四";break;
                        case 5: weekday="五";break;
                        case 6: weekday="六";break;
                       }
                return weekday;                    
            }
          
            $('#week').click(function(){
                  $(this).css('background-color','#c4c4c4');
                  $('#day').css('background-color','#fff');
                  $('#month').css('background-color','#fff');
                  var src1="{{URL::asset('admin/img/ico5.png')}}";
                  var src2="{{URL::asset('admin/img/ico6.png')}}";
                  $('#bar').attr("src",src2);
                  $('#line').attr("src",src1);

                  var end_time = Date.parse(new Date())/1000;
                  var end_weekday=new Date().getDay();
                
                  lineChartData.labels=new Array();
                  for(var i=end_weekday;i>0;i--){
                      lineChartData.labels.unshift(weekDay(i));
                   //  alert(weekDay(i))
                  }
                   //   alert(lineChartData.labels.length+"changdu")
                  for(var i=7;i>end_weekday;i--){
                      lineChartData.labels.unshift(weekDay(i));
                  // alert("星期"+weekDay(i))
                  }

                  console.log(end_time);
                  var start_time = end_time - 60*60*24*7;
                  if($('.icon-msg option:selected').html()=='粉丝数'){
                    $.ajax({
                    type: "post",
                    url: "/Brand/datacenter/data",
                    data: {
                      start_time:start_time, 
                      end_time:end_time,
                      unit:'week',
                    },
                    dataType: "json",
                    success: function(data){
                     // lineChartData.labels=label_week;

                        // lineChartData.datasets[3].data=data.fans_array;
                        // lineChartData.datasets[2].data=data.customer_array;
                        // lineChartData.datasets[1].data=data.order_array;
                        lineChartData.datasets[0].data=data.fans_array;
                        lineChartOptions.scaleOverride=false;
                        lineChartOptions.scaleLabel="<%= value   %>";
                        $("#Chart-graph").remove();
                        var ctx=$("<canvas id='Chart-graph' height='450px'></canvas>");
                        $('.graph').append(ctx);
                        var lineChartCanvas = $("#Chart-graph").get(0).getContext("2d");
                        var lineChart = new Chart(lineChartCanvas);
                        lineChartOptions.datasetFill = false;
                        lineChart.Line(lineChartData, lineChartOptions);
                    }
                    });
                  }
                  
            });
            $('#month').click(function(){
                $(this).css('background-color','#c4c4c4');
                $('#week').css('background-color','#fff');
                $('#day').css('background-color','#fff');
                var src1="{{URL::asset('admin/img/ico5.png')}}";
                var src2="{{URL::asset('admin/img/ico6.png')}}";
                $('#bar').attr("src",src2);
                $('#line').attr("src",src1);
                var end_time = Date.parse(new Date())/1000;
                var end_date=new Date();//获取当前的时间
                var end_year=end_date.getFullYear();
                var end_month=end_date.getMonth();
                var end_day=end_date.getDate();
                lineChartData.labels=new Array();
                if(end_day>5){  
                  for(end_day;end_day>0;end_day=end_day-5){
                    lineChartData.labels.unshift(timeCheck(end_month+1)+"."+timeCheck(end_day));
                  }
                  var len=6-lineChartData.labels.length;
                  var j=30;
                  for(var i=0;i<len;i++){
                      j=j-5;
                      lineChartData.labels.unshift(timeCheck(end_month)+"."+timeCheck(j));
                  }    
                } 
                else{
                    var k=5;         
                    lineChartData.labels.unshift(timeCheck(end_month+1)+"."+timeCheck(end_day));             
                    end_day=end_day+30;
             
                    for(;end_day>0&&k>0;k--){
                        end_day=end_day-5;              
                        lineChartData.labels.unshift(timeCheck(end_month)+"."+timeCheck(end_day));
                    }    
                }          
                var start_time = end_time - 60*60*24*30;
                console.log(end_time);
                if($('.icon-msg option:selected').html()=='粉丝数'){
                  $.ajax({
                    type: "post",
                    url: "/Brand/datacenter/data",
                    data: {
                      start_time:start_time, 
                      end_time:end_time,
                      unit:'month',
                    },
                    dataType: "json",
                    success: function(data){
                        // lineChartData.datasets[3].data=data.fans_array;
                        // lineChartData.datasets[2].data=data.customer_array;
                        // lineChartData.datasets[1].data=data.order_array;
                        lineChartData.datasets[0].data=data.fans_array;
                        lineChartOptions.scaleLabel="<%= value   %>";
                        lineChartOptions.scaleOverride=false;
                        $("#Chart-graph").remove();
                        var ctx=$("<canvas id='Chart-graph' height='450px'></canvas>");
                        $('.graph').append(ctx);
                        var lineChartCanvas = $("#Chart-graph").get(0).getContext("2d");
                        var lineChart = new Chart(lineChartCanvas);
                        lineChartOptions.datasetFill = false;
                        lineChart.Line(lineChartData, lineChartOptions);
                    }
                  });
                }      
                
            });
            $("#Chart-graph").remove();
            var ctx=$("<canvas id='Chart-graph' height='450px'></canvas>");
            $('.graph').append(ctx);
            var lineChartCanvas = $("#Chart-graph").get(0).getContext("2d");
            var lineChart = new Chart(lineChartCanvas);
            lineChartOptions.datasetFill = false;
            lineChart.Line(lineChartData, lineChartOptions);

            $("#line").on('click',function(){
                var src1="{{URL::asset('admin/img/ico6.png')}}";
                var src2="{{URL::asset('admin/img/ico5.png')}}";
                $(this).attr("src",src2);
                $('#bar').attr("src",src1);
                $("#Chart-graph").remove();
                var ctx=$("<canvas id='Chart-graph' height='450px'></canvas>");
                $('.graph').append(ctx);
                var lineChartCanvas = $("#Chart-graph").get(0).getContext("2d");      
                var lineChart = new Chart(lineChartCanvas);
                lineChartOptions.datasetFill = false;       
                lineChart.Line(lineChartData, lineChartOptions);
            }); 
            defaults = {
                    
                //Boolean - If we show the scale above the chart data     
                scaleOverlay : true,
                
                //Boolean - If we want to override with a hard coded scale
                scaleOverride : false,
                
                //** Required if scaleOverride is true **
                //Number - The number of steps in a hard coded scale
                scaleSteps : 20,
                //Number - The value jump in the hard coded scale
                scaleStepWidth : 5,
                //Number - The scale starting value
                scaleStartValue : 0,

                //String - Colour of the scale line 
                scaleLineColor : "rgba(0,0,0,.1)",
                
                //Number - Pixel width of the scale line  
                scaleLineWidth : 2,

                //Boolean - Whether to show labels on the scale 
                scaleShowLabels : false,
                
                //Interpolated JS string - can access value
                scaleLabel : "<%=value/1 %>",
                
                //String - Scale label font declaration for the scale label
                scaleFontFamily : "'Arial'",
                
                //Number - Scale label font size in pixels  
                scaleFontSize : 12,
                
                //String - Scale label font weight style  
                scaleFontStyle : "normal",
                
                //String - Scale label font colour  
                scaleFontColor : "#666",  
                
                ///Boolean - Whether grid lines are shown across the chart
                scaleShowGridLines : false,
                
                //String - Colour of the grid lines
                scaleGridLineColor : "rgba(0,0,0,.05)",
                
                //Number - Width of the grid lines
                scaleGridLineWidth : 1, 

                //Boolean - If there is a stroke on each bar  
                barShowStroke : true,
                
                //Number - Pixel width of the bar stroke  
                barStrokeWidth : 1,
                
                //Number - Spacing between each of the X value sets
                barValueSpacing : 60,
                
                //Number - Spacing between data sets within X values
                barDatasetSpacing : 20,
                
                //Boolean - Whether to animate the chart
                animation : true,

                //Number - Number of animation steps
                animationSteps : 60,
                
                //String - Animation easing effect
                animationEasing : "easeOutQuart",

                //Function - Fires when the animation is complete
                onAnimationComplete : null
              
            }
            function timeCheck(para){   
                if (para<10){
                  para="0" + para;
                }   
                return para;
            } 

            $("#bar").on('click',function(){
                var src1="{{URL::asset('admin/img/ico5-2.png')}}";
                var src2="{{URL::asset('admin/img/ico6-2.png')}}";
                $(this).attr("src",src2);
                $('#line').attr("src",src1);  
                $("#Chart-graph").remove();
                var wid= $('.graph').width();
                var ctx=$("<canvas id='Chart-graph' height='450px' width="+wid+" ></canvas>");
                $('.graph').append(ctx);
                var lineChartCanvas = $("#Chart-graph").get(0).getContext("2d");
                var barChartCanvas=new Chart(lineChartCanvas);
                barChartCanvas.datasetFill = false;
                barChartCanvas.Bar(lineChartData,defaults);

            }); 
}
$('.msgselect').change(function(){
    if($('.icon-msg option:selected').val()=='fans'){
            $('#day').css('background-color','#c4c4c4');
            $('#week').css('background-color','#fff');
            $('#month').css('background-color','#fff');
            var src1="{{URL::asset('admin/img/ico5.png')}}";
            var src2="{{URL::asset('admin/img/ico6.png')}}";
            $('#bar').attr("src",src2);
            $('#line').attr("src",src1);
            var label_day= ["00:00", "06:00", "12:00", "18:00", "00:00"];
            var label_week= ["一", "二", "三", "四", "五", "六", "日"];
            var label_month=["January","February","March","April","May","June","July"];
            var lineChartData = {
                labels: label_day,
                datasets: [                  
                  {
                    label: "users-num",
                    fillColor: "#facdae",   //背景色，常用transparent透明
                    strokeColor: "#facdae",  //线条颜色，也可用"#ffffff"
                    pointColor: "#facdae",   //点的填充颜色
                    pointStrokeColor: "#facdae",       //点的外边框颜色
                    pointHighlightFill: "#fff",
                    pointHighlightStroke: "rgba(220,220,220,1)",
                  }               
                ]
            };
            
            function Initdata1(){
                $('#day').css('background-color','#c4c4c4');
                $('#week').css('background-color','#fff');
                $('#month').css('background-color','#fff');
                var src1="{{URL::asset('admin/img/ico5.png')}}";
                var src2="{{URL::asset('admin/img/ico6.png')}}";
                $('#bar').attr("src",src2);
                $('#line').attr("src",src1);
                var end_time = Date.parse(new Date())/1000;
                console.log(end_time);
                var start_time = end_time - 60*60*32;
                if($('.icon-msg option:selected').html()=='fans'){
                  $.ajax({
                    type: "post",
                    url: "/Brand/datacenter/data",
                    data: {
                      start_time:start_time, 
                      end_time:end_time,
                      unit:'day',
                    },
                    dataType: "json",
                    success: function(data){
                      lineChartData.labels=label_day;
                      // lineChartData.datasets[3].data=data.fans_array;
                      // lineChartData.datasets[2].data=data.customer_array;
                      // lineChartData.datasets[1].data=data.order_array;
                      lineChartData.datasets[0].data=data.fans_array;
                      lineChartOptions.scaleLabel="<%= value   %>";
                      lineChartOptions.scaleOverride=false;
                      $("#Chart-graph").remove();
                      var ctx=$("<canvas id='Chart-graph' height='450px'></canvas>");
                      $('.graph').append(ctx);
                      var lineChartCanvas = $("#Chart-graph").get(0).getContext("2d");
                      var lineChart = new Chart(lineChartCanvas);
                      lineChartOptions.datasetFill = false;
                      lineChart.Line(lineChartData, lineChartOptions);
                    }
                  });
                }
            }
            Initdata1();
            $("#Chart-graph").remove();
            var ctx=$("<canvas id='Chart-graph' height='450px'></canvas>");
            $('.graph').append(ctx);
            var lineChartCanvas = $("#Chart-graph").get(0).getContext("2d");
            var lineChart = new Chart(lineChartCanvas);
            lineChartOptions.datasetFill = false;
            lineChart.Line(lineChartData, lineChartOptions);          
            function timeCheck(para){   
                if (para<10){
                  para="0" + para;
                }   
                return para;
            }         
    // }else if($('.icon-msg option:selected').val()=='order'){
    //         $('#day').css('background-color','#c4c4c4');
    //         $('#week').css('background-color','#fff');
    //         $('#month').css('background-color','#fff');
    //         var src1="{{URL::asset('admin/img/ico5.png')}}";
    //         var src2="{{URL::asset('admin/img/ico6.png')}}";
    //         $('#bar').attr("src",src2);
    //         $('#line').attr("src",src1);
    //         var label_day= ["00:00", "06:00", "12:00", "18:00", "00:00"];
    //         var label_week= ["一", "二", "三", "四", "五", "六", "日"];
    //         var label_month=["January","February","March","April","May","June","July"];
    //         var lineChartData = {
    //             labels: label_day,
    //             datasets: [              
    //                {
    //                 label: "fansNum",
    //                 fillColor: "#84af9b",   //背景色，常用transparent透明
    //                 strokeColor: "#84af9b",  //线条颜色，也可用"#ffffff"
    //                 pointColor: "#84af9b",   //点的填充颜色
    //                 pointStrokeColor: "#84af9b",       //点的外边框颜色
    //                 pointHighlightFill: "#fff",
    //                 pointHighlightStroke: "rgba(220,220,220,1)",
    //               }             
    //             ]
    //         };  
             
    //         function Initdata2(){
    //             $('#day').css('background-color','#c4c4c4');
    //             $('#week').css('background-color','#fff');
    //             $('#month').css('background-color','#fff');
    //             var src1="{{URL::asset('admin/img/ico5.png')}}";
    //             var src2="{{URL::asset('admin/img/ico6.png')}}";
    //             $('#bar').attr("src",src2);
    //             $('#line').attr("src",src1);
    //             var end_time = Date.parse(new Date())/1000;
    //             console.log(end_time);
    //             var start_time = end_time - 60*60*32;
    //             if($('.icon-msg option:selected').html()=='订单数'){
    //               $.ajax({
    //                 type: "post",
    //                 url: "/Brand/datacenter/data",
    //                 data: {
    //                   start_time:start_time, 
    //                   end_time:end_time,
    //                   unit:'day',
    //                 },
    //                 dataType: "json",
    //                 success: function(data){
    //                   lineChartData.labels=label_day;
    //                   lineChartData.datasets[0].data=data.fans_array;
    //                   lineChartOptions.scaleLabel="<%= value   %>";
    //                   lineChartOptions.scaleOverride=false;
    //                   $("#Chart-graph").remove();
    //                   var ctx=$("<canvas id='Chart-graph' height='450px'></canvas>");
    //                   $('.graph').append(ctx);
    //                   var lineChartCanvas = $("#Chart-graph").get(0).getContext("2d");
    //                   var lineChart = new Chart(lineChartCanvas);
    //                   lineChartOptions.datasetFill = false;
    //                   lineChart.Line(lineChartData, lineChartOptions);
    //                 }
    //               });
    //             }                
    //         }     
    //         Initdata2();  
    //         $('#day').click(function(){
    //             Initdata2(); 
    //         });
    //         function weekDay(end_weekday) {
    //             var weekday;
    //             if(end_weekday==0)
    //               end_weekday=7;
    //              switch(end_weekday){
    //                     case 7: weekday="日";break;
    //                     case 1: weekday="一";break;
    //                     case 2: weekday="二";break;
    //                     case 3: weekday="三";break;
    //                     case 4: weekday="四";break;
    //                     case 5: weekday="五";break;
    //                     case 6: weekday="六";break;
    //                    }
    //             return weekday;                    
    //         }
          
    //         $('#week').click(function(){
    //               $(this).css('background-color','#c4c4c4');
    //               $('#day').css('background-color','#fff');
    //               $('#month').css('background-color','#fff');
    //               var src1="{{URL::asset('admin/img/ico5.png')}}";
    //               var src2="{{URL::asset('admin/img/ico6.png')}}";
    //               $('#bar').attr("src",src2);
    //               $('#line').attr("src",src1);

    //               var end_time = Date.parse(new Date())/1000;
    //               var end_weekday=new Date().getDay();
                
    //               lineChartData.labels=new Array();
    //               for(var i=end_weekday;i>0;i--){
    //                   lineChartData.labels.unshift(weekDay(i));
    //                //  alert(weekDay(i))
    //               }
    //                //   alert(lineChartData.labels.length+"changdu")
    //               for(var i=7;i>end_weekday;i--){
    //                   lineChartData.labels.unshift(weekDay(i));
    //               // alert("星期"+weekDay(i))
    //               }

    //               console.log(end_time);
    //               var start_time = end_time - 60*60*24*7;
    //               if($('.icon-msg option:selected').html()=='订单数'){
    //                   $.ajax({
    //                     type: "post",
    //                     url: "/Brand/datacenter/data",
    //                     data: {
    //                       start_time:start_time, 
    //                       end_time:end_time,
    //                       unit:'week',
    //                     },
    //                     dataType: "json",
    //                     success: function(data){
    //                         lineChartData.datasets[0].data=data.fans_array;
    //                         lineChartOptions.scaleLabel="<%= value   %>";
    //                         lineChartOptions.scaleOverride=false;
    //                         $("#Chart-graph").remove();
    //                         var ctx=$("<canvas id='Chart-graph' height='450px'></canvas>");
    //                         $('.graph').append(ctx);
    //                         var lineChartCanvas = $("#Chart-graph").get(0).getContext("2d");
    //                         var lineChart = new Chart(lineChartCanvas);
    //                         lineChartOptions.datasetFill = false;
    //                         lineChart.Line(lineChartData, lineChartOptions);
    //                     }
    //                   });
    //               }
                  
    //         });
    //         $('#month').click(function(){
    //             $(this).css('background-color','#c4c4c4');
    //             $('#week').css('background-color','#fff');
    //             $('#day').css('background-color','#fff');
    //             var src1="{{URL::asset('admin/img/ico5.png')}}";
    //             var src2="{{URL::asset('admin/img/ico6.png')}}";
    //             $('#bar').attr("src",src2);
    //             $('#line').attr("src",src1);
    //             var end_time = Date.parse(new Date())/1000;
    //             var end_date=new Date();//获取当前的时间
    //             var end_year=end_date.getFullYear();
    //             var end_month=end_date.getMonth();
    //             var end_day=end_date.getDate();
    //             lineChartData.labels=new Array();
    //             if(end_day>5){  
    //               for(end_day;end_day>0;end_day=end_day-5){
    //                 lineChartData.labels.unshift(timeCheck(end_month+1)+"."+timeCheck(end_day));
    //               }
    //               var len=6-lineChartData.labels.length;
    //               var j=30;
    //               for(var i=0;i<len;i++){
    //                   j=j-5;
    //                   lineChartData.labels.unshift(timeCheck(end_month)+"."+timeCheck(j));
    //               }    
    //             } 
    //             else{
    //                 var k=5;         
    //                 lineChartData.labels.unshift(timeCheck(end_month+1)+"."+timeCheck(end_day));             
    //                 end_day=end_day+30;
             
    //                 for(;end_day>0&&k>0;k--){
    //                     end_day=end_day-5;              
    //                     lineChartData.labels.unshift(timeCheck(end_month)+"."+timeCheck(end_day));
    //                 }    
    //             }          
    //             var start_time = end_time - 60*60*24*30;
    //             console.log(end_time);
    //             if($('.icon-msg option:selected').html()=='订单数'){
    //                 $.ajax({
    //                     type: "post",
    //                     url: "/Brand/datacenter/data",
    //                     data: {
    //                       start_time:start_time, 
    //                       end_time:end_time,
    //                       unit:'month',
    //                     },
    //                     dataType: "json",
    //                     success: function(data){
    //                         lineChartData.datasets[0].data=data.fans_array;
    //                         lineChartOptions.scaleLabel="<%= value   %>";
    //                         lineChartOptions.scaleOverride=false;
    //                         $("#Chart-graph").remove();
    //                         var ctx=$("<canvas id='Chart-graph' height='450px'></canvas>");
    //                         $('.graph').append(ctx);
    //                         var lineChartCanvas = $("#Chart-graph").get(0).getContext("2d");
    //                         var lineChart = new Chart(lineChartCanvas);
    //                         lineChartOptions.datasetFill = false;
    //                         lineChart.Line(lineChartData, lineChartOptions);
    //                     }
    //                 });  
    //             }      
                
    //         });
    //         $("#Chart-graph").remove();
    //         var ctx=$("<canvas id='Chart-graph' height='450px'></canvas>");
    //         $('.graph').append(ctx);
    //         var lineChartCanvas = $("#Chart-graph").get(0).getContext("2d");
    //         var lineChart = new Chart(lineChartCanvas);
    //         lineChartOptions.datasetFill = false;
    //         lineChart.Line(lineChartData, lineChartOptions);

    //         $("#line").on('click',function(){
    //             var src1="{{URL::asset('admin/img/ico6.png')}}";
    //             var src2="{{URL::asset('admin/img/ico5.png')}}";
    //             $(this).attr("src",src2);
    //             $('#bar').attr("src",src1); 
    //             $("#Chart-graph").remove();
    //             var ctx=$("<canvas id='Chart-graph' height='450px'></canvas>");
    //             $('.graph').append(ctx);
    //             var lineChartCanvas = $("#Chart-graph").get(0).getContext("2d");      
    //             var lineChart = new Chart(lineChartCanvas);
    //             lineChartOptions.datasetFill = false;       
    //             lineChart.Line(lineChartData, lineChartOptions);
    //         });             
    //         function timeCheck(para){   
    //             if (para<10){
    //               para="0" + para;
    //             }   
    //             return para;
    //         } 

    //         $("#bar").on('click',function(){
    //             var src1="{{URL::asset('admin/img/ico5-2.png')}}";
    //             var src2="{{URL::asset('admin/img/ico6-2.png')}}";
    //             $(this).attr("src",src2);
    //             $('#line').attr("src",src1);  
    //             $("#Chart-graph").remove();
    //             var wid= $('.graph').width();
    //             var ctx=$("<canvas id='Chart-graph' height='450px' width="+wid+" ></canvas>");
    //             $('.graph').append(ctx);
    //             var lineChartCanvas = $("#Chart-graph").get(0).getContext("2d");
    //             var barChartCanvas=new Chart(lineChartCanvas);
    //             barChartCanvas.datasetFill = false;
    //             barChartCanvas.Bar(lineChartData,defaults);

    //         }); 
    // 
    }else if($('.icon-msg option:selected').val()=='order'){
            $('#day').css('background-color','#c4c4c4');
            $('#week').css('background-color','#fff');
            $('#month').css('background-color','#fff');
            var src1="{{URL::asset('admin/img/ico5.png')}}";
            var src2="{{URL::asset('admin/img/ico6.png')}}";
            $('#bar').attr("src",src2);
            $('#line').attr("src",src1);
            var label_day= ["00:00", "06:00", "12:00", "18:00", "00:00"];
            var label_week= ["一", "二", "三", "四", "五", "六", "日"];
            var label_month=["January","February","March","April","May","June","July"];
            var lineChartData = {
                labels: label_day,
                datasets: [
                  {
                    label: "orderNum",
                    fillColor: "#c8c7a9",   //背景色，常用transparent透明
                    strokeColor: "#c8c7a9",  //线条颜色，也可用"#ffffff"
                    pointColor: "#c8c7a9",   //点的填充颜色
                    pointStrokeColor: "#c8c7a9",       //点的外边框颜色
                    pointHighlightFill: "#fff",
                    pointHighlightStroke: "rgba(220,220,220,1)",
                  }          
                ]
            };
            function Initdata3(){
                $('#day').css('background-color','#c4c4c4');
                $('#week').css('background-color','#fff');
                $('#month').css('background-color','#fff');
                var src1="{{URL::asset('admin/img/ico5.png')}}";
                var src2="{{URL::asset('admin/img/ico6.png')}}";
                $('#bar').attr("src",src2);
                $('#line').attr("src",src1);
                var end_time = Date.parse(new Date())/1000;
                console.log(end_time);
                var start_time = end_time - 60*60*32;
                if($('.icon-msg option:selected').html()=='订单数'){
                  $.ajax({
                    type: "post",
                    url: "/Brand/datacenter/data",
                    data: {
                      start_time:start_time, 
                      end_time:end_time,
                      unit:'day',
                    },
                    dataType: "json",
                    success: function(data){
                      lineChartData.labels=label_day;
                      lineChartData.datasets[0].data=data.order_array;
                      lineChartOptions.scaleLabel="<%= value   %>";
                      lineChartOptions.scaleOverride=false;
                      $("#Chart-graph").remove();
                      var ctx=$("<canvas id='Chart-graph' height='450px'></canvas>");
                      $('.graph').append(ctx);
                      var lineChartCanvas = $("#Chart-graph").get(0).getContext("2d");
                      var lineChart = new Chart(lineChartCanvas);
                      lineChartOptions.datasetFill = false;
                      lineChart.Line(lineChartData, lineChartOptions);
                    }
                  });
                }
            }
            Initdata3();
            $('#day').click(function(){
                Initdata3();
                
            });
            
            function weekDay(end_weekday) {
                var weekday;
                if(end_weekday==0)
                  end_weekday=7;
                 switch(end_weekday){
                        case 7: weekday="日";break;
                        case 1: weekday="一";break;
                        case 2: weekday="二";break;
                        case 3: weekday="三";break;
                        case 4: weekday="四";break;
                        case 5: weekday="五";break;
                        case 6: weekday="六";break;
                       }
                return weekday;                    
            }
          
            $('#week').click(function(){
                  $(this).css('background-color','#c4c4c4');
                  $('#day').css('background-color','#fff');
                  $('#month').css('background-color','#fff');
                  var src1="{{URL::asset('admin/img/ico5.png')}}";
                  var src2="{{URL::asset('admin/img/ico6.png')}}";
                  $('#bar').attr("src",src2);
                  $('#line').attr("src",src1);
                  var end_time = Date.parse(new Date())/1000;
                  var end_weekday=new Date().getDay();
                
                  lineChartData.labels=new Array();
                  for(var i=end_weekday;i>0;i--){
                      lineChartData.labels.unshift(weekDay(i));
                   //  alert(weekDay(i))
                  }
                   //   alert(lineChartData.labels.length+"changdu")
                  for(var i=7;i>end_weekday;i--){
                      lineChartData.labels.unshift(weekDay(i));
                  // alert("星期"+weekDay(i))
                  }

                  console.log(end_time);
                  var start_time = end_time - 60*60*24*7;
                  if($('.icon-msg option:selected').html()=='订单数'){
                    $.ajax({
                      type: "post",
                      url: "/Brand/datacenter/data",
                      data: {
                        start_time:start_time, 
                        end_time:end_time,
                        unit:'week',
                      },
                      dataType: "json",
                      success: function(data){
                          lineChartData.datasets[0].data=data.order_array;
                          lineChartOptions.scaleLabel="<%= value   %>";
                          lineChartOptions.scaleOverride=false;
                          $("#Chart-graph").remove();
                          var ctx=$("<canvas id='Chart-graph' height='450px'></canvas>");
                          $('.graph').append(ctx);
                          var lineChartCanvas = $("#Chart-graph").get(0).getContext("2d");
                          var lineChart = new Chart(lineChartCanvas);
                          lineChartOptions.datasetFill = false;
                          lineChart.Line(lineChartData, lineChartOptions);
                      }
                    });
                  }
                  
            });
            $('#month').click(function(){
                $(this).css('background-color','#c4c4c4');
                $('#week').css('background-color','#fff');
                $('#day').css('background-color','#fff');
                var src1="{{URL::asset('admin/img/ico5.png')}}";
                var src2="{{URL::asset('admin/img/ico6.png')}}";
                $('#bar').attr("src",src2);
                $('#line').attr("src",src1);
                var end_time = Date.parse(new Date())/1000;
                var end_date=new Date();//获取当前的时间
                var end_year=end_date.getFullYear();
                var end_month=end_date.getMonth();
                var end_day=end_date.getDate();
                lineChartData.labels=new Array();
                if(end_day>5){  
                  for(end_day;end_day>0;end_day=end_day-5){
                    lineChartData.labels.unshift(timeCheck(end_month+1)+"."+timeCheck(end_day));
                  }
                  var len=6-lineChartData.labels.length;
                  var j=30;
                  for(var i=0;i<len;i++){
                      j=j-5;
                      lineChartData.labels.unshift(timeCheck(end_month)+"."+timeCheck(j));
                  }    
                } 
                else{
                    var k=5;         
                    lineChartData.labels.unshift(timeCheck(end_month+1)+"."+timeCheck(end_day));             
                    end_day=end_day+30;
             
                    for(;end_day>0&&k>0;k--){
                        end_day=end_day-5;              
                        lineChartData.labels.unshift(timeCheck(end_month)+"."+timeCheck(end_day));
                    }    
                }          
                var start_time = end_time - 60*60*24*30;
                console.log(end_time); 
                if($('.icon-msg option:selected').html()=='订单数'){
                  $.ajax({
                    type: "post",
                    url: "/Brand/datacenter/data",
                    data: {
                      start_time:start_time, 
                      end_time:end_time,
                      unit:'month',
                    },
                    dataType: "json",
                    success: function(data){
                        lineChartData.datasets[0].data=data.order_array;
                        lineChartOptions.scaleLabel="<%= value   %>";
                        lineChartOptions.scaleOverride=false;
                        $("#Chart-graph").remove();
                        var ctx=$("<canvas id='Chart-graph' height='450px'></canvas>");
                        $('.graph').append(ctx);
                        var lineChartCanvas = $("#Chart-graph").get(0).getContext("2d");
                        var lineChart = new Chart(lineChartCanvas);
                        lineChartOptions.datasetFill = false;
                        lineChart.Line(lineChartData, lineChartOptions);
                    }
                  });
                }     
                
            });
            $("#Chart-graph").remove();
            var ctx=$("<canvas id='Chart-graph' height='450px'></canvas>");
            $('.graph').append(ctx);
            var lineChartCanvas = $("#Chart-graph").get(0).getContext("2d");
            var lineChart = new Chart(lineChartCanvas);
            lineChartOptions.datasetFill = false;
            lineChart.Line(lineChartData, lineChartOptions);

            $("#line").on('click',function(){
                var src1="{{URL::asset('admin/img/ico6.png')}}";
                var src2="{{URL::asset('admin/img/ico5.png')}}";
                $(this).attr("src",src2);
                $('#bar').attr("src",src1); 
                $("#Chart-graph").remove();
                var ctx=$("<canvas id='Chart-graph' height='450px'></canvas>");
                $('.graph').append(ctx);
                var lineChartCanvas = $("#Chart-graph").get(0).getContext("2d");      
                var lineChart = new Chart(lineChartCanvas);
                lineChartOptions.datasetFill = false;       
                lineChart.Line(lineChartData, lineChartOptions);
            });       
            function timeCheck(para){   
                if (para<10){
                  para="0" + para;
                }   
                return para;
            } 

            $("#bar").on('click',function(){
                var src1="{{URL::asset('admin/img/ico5-2.png')}}";
                var src2="{{URL::asset('admin/img/ico6-2.png')}}";
                $(this).attr("src",src2);
                $('#line').attr("src",src1);  
                $("#Chart-graph").remove();
                var wid= $('.graph').width();
                var ctx=$("<canvas id='Chart-graph' height='450px' width="+wid+" ></canvas>");
                $('.graph').append(ctx);
                var lineChartCanvas = $("#Chart-graph").get(0).getContext("2d");
                var barChartCanvas=new Chart(lineChartCanvas);
                barChartCanvas.datasetFill = false;
                barChartCanvas.Bar(lineChartData,defaults);

            }); 
    }else if($('.icon-msg option:selected').val()=='turnover'){
            $('#day').css('background-color','#c4c4c4');
            $('#week').css('background-color','#fff');
            $('#month').css('background-color','#fff');
            var src1="{{URL::asset('admin/img/ico5.png')}}";
            var src2="{{URL::asset('admin/img/ico6.png')}}";
            $('#bar').attr("src",src2);
            $('#line').attr("src",src1);
            var label_day= ["00:00", "06:00", "12:00", "18:00", "00:00"];
            var label_week= ["一", "二", "三", "四", "五", "六", "日"];
            var label_month=["January","February","March","April","May","June","July"];
            var data1=[10,10,10,60,60,60,60];
            var data1=[40,10,10,60,60,60,60];

            var lineChartData = {
                labels: label_day,
                datasets: [
                  {
                    label: "tradeNum",
                    fillColor: "#fc9d99",   //背景色，常用transparent透明
                    strokeColor: "#fc9d99",  //线条颜色，也可用"#ffffff"
                    pointColor: "#fc9d99",   //点的填充颜色
                    pointStrokeColor: "#fc9d99",       //点的外边框颜色
                    pointHighlightFill: "#fff",
                    pointHighlightStroke: "rgba(220,220,220,1)",
                    // data: data1
                  }            
                ]
            };
            
            function Initdata(){
                $("#day").css('background-color','#c4c4c4');
                $('#week').css('background-color','#fff');
                $('#month').css('background-color','#fff');
                var src1="{{URL::asset('admin/img/ico5.png')}}";
                var src2="{{URL::asset('admin/img/ico6.png')}}";
                $('#bar').attr("src",src2);
                $('#line').attr("src",src1);
                var end_time = Date.parse(new Date())/1000;
                console.log(end_time);
                var start_time = end_time - 60*60*32;
                if($('.icon-msg option:selected').html()=='交易额'){
                  $.ajax({
                    type: "post",
                    url: "/Brand/datacenter/data",
                    data: {
                      start_time:start_time, 
                      end_time:end_time,
                      unit:'day',
                    },
                    dataType: "json",
                    success: function(data){
                      lineChartData.labels=label_day;
                      lineChartData.datasets[0].data=data.total_array;
                      var max = Math.max.apply(null, data.total_array);
                      lineChartOptions.scaleLabel="<%= parseFloat(value).toFixed(2)   %>";
                      lineChartOptions.scaleOverride=true;
                      lineChartOptions.scaleSteps=2 ;       //y轴刻度的个数
                      lineChartOptions.scaleStepWidth=max/2;   //y轴每个刻度的宽度
                      $("#Chart-graph").remove();
                      var ctx=$("<canvas id='Chart-graph' height='450px'></canvas>");
                      $('.graph').append(ctx);
                      var lineChartCanvas = $("#Chart-graph").get(0).getContext("2d");
                      var lineChart = new Chart(lineChartCanvas);
                      lineChartOptions.datasetFill = false;
                      lineChart.Line(lineChartData, lineChartOptions);
                    }
                  });
                }
                
            }
            Initdata();
            $('#day').click(function(){
                Initdata();
            });
            function weekDay(end_weekday) {
                var weekday;
                if(end_weekday==0)
                  end_weekday=7;
                 switch(end_weekday){
                        case 7: weekday="日";break;
                        case 1: weekday="一";break;
                        case 2: weekday="二";break;
                        case 3: weekday="三";break;
                        case 4: weekday="四";break;
                        case 5: weekday="五";break;
                        case 6: weekday="六";break;
                       }
                return weekday;                    
            }

            $('#week').click(function(){
                  $(this).css('background-color','#c4c4c4');
                  $('#day').css('background-color','#fff');
                  $('#month').css('background-color','#fff');
                  var src1="{{URL::asset('admin/img/ico5.png')}}";
                  var src2="{{URL::asset('admin/img/ico6.png')}}";
                  $('#bar').attr("src",src2);
                  $('#line').attr("src",src1);
                  var end_time = Date.parse(new Date())/1000;
                  var end_weekday=new Date().getDay();
                
                  lineChartData.labels=new Array();
                  for(var i=end_weekday;i>0;i--){
                      lineChartData.labels.unshift(weekDay(i));
                   //  alert(weekDay(i))
                  }
                   //   alert(lineChartData.labels.length+"changdu")
                  for(var i=7;i>end_weekday;i--){
                      lineChartData.labels.unshift(weekDay(i));
                  // alert("星期"+weekDay(i))
                  }

                  console.log(end_time);
                  var start_time = end_time - 60*60*24*7;
                  if($('.icon-msg option:selected').html()=='交易额'){
                      $.ajax({
                            type: "post",
                            url: "/Brand/datacenter/data",
                            data: {
                              start_time:start_time, 
                              end_time:end_time,
                              unit:'week',
                            },
                            dataType: "json",
                            success: function(data){
                                lineChartData.datasets[0].data=data.total_array;
                                var max = Math.max.apply(null, data.total_array);
                                lineChartOptions.scaleLabel="<%= parseFloat(value).toFixed(2)   %>";
                                lineChartOptions.scaleOverride=true;
                                lineChartOptions.scaleSteps=2 ;       //y轴刻度的个数
                                lineChartOptions.scaleStepWidth=max/2;   //y轴每个刻度的宽度
                                $("#Chart-graph").remove();
                                var ctx=$("<canvas id='Chart-graph' height='450px'></canvas>");
                                $('.graph').append(ctx);
                                var lineChartCanvas = $("#Chart-graph").get(0).getContext("2d");
                                var lineChart = new Chart(lineChartCanvas);
                                lineChartOptions.datasetFill = false;
                                lineChart.Line(lineChartData, lineChartOptions);
                            }
                      });
                  }
                  
            });
            $('#month').click(function(){
                $(this).css('background-color','#c4c4c4');
                $('#week').css('background-color','#fff');
                $('#day').css('background-color','#fff');
                var src1="{{URL::asset('admin/img/ico5.png')}}";
                var src2="{{URL::asset('admin/img/ico6.png')}}";
                $('#bar').attr("src",src2);
                $('#line').attr("src",src1);
                var end_time = Date.parse(new Date())/1000;
                var end_date=new Date();//获取当前的时间
                var end_year=end_date.getFullYear();
                var end_month=end_date.getMonth();
                var end_day=end_date.getDate();
                lineChartData.labels=new Array();
                if(end_day>5){  
                  for(end_day;end_day>0;end_day=end_day-5){
                    lineChartData.labels.unshift(timeCheck(end_month+1)+"."+timeCheck(end_day));
                  }
                  var len=6-lineChartData.labels.length;
                  var j=30;
                  for(var i=0;i<len;i++){
                      j=j-5;
                      lineChartData.labels.unshift(timeCheck(end_month)+"."+timeCheck(j));
                  }    
                } 
                else{
                    var k=5;         
                    lineChartData.labels.unshift(timeCheck(end_month+1)+"."+timeCheck(end_day));             
                    end_day=end_day+30;
             
                    for(;end_day>0&&k>0;k--){
                        end_day=end_day-5;              
                        lineChartData.labels.unshift(timeCheck(end_month)+"."+timeCheck(end_day));
                    }    
                }          
                var start_time = end_time - 60*60*24*30;
                console.log(end_time); 
                if($('.icon-msg option:selected').html()=='交易额'){
                    $.ajax({
                          type: "post",
                          url: "/Brand/datacenter/data",
                          data: {
                            start_time:start_time, 
                            end_time:end_time,
                            unit:'month',
                          },
                          dataType: "json",
                          success: function(data){
                              lineChartData.datasets[0].data=data.total_array;
                              var max = Math.max.apply(null, data.total_array);
                              lineChartOptions.scaleLabel="<%= parseFloat(value).toFixed(2)   %>";
                              lineChartOptions.scaleOverride=true;
                              lineChartOptions.scaleSteps=2 ;       //y轴刻度的个数
                              lineChartOptions.scaleStepWidth=max/2;   //y轴每个刻度的宽度
                              $("#Chart-graph").remove();
                              var ctx=$("<canvas id='Chart-graph' height='450px'></canvas>");
                              $('.graph').append(ctx);
                              var lineChartCanvas = $("#Chart-graph").get(0).getContext("2d");
                              var lineChart = new Chart(lineChartCanvas);
                              lineChartOptions.datasetFill = false;
                              lineChart.Line(lineChartData, lineChartOptions);
                          }
                    });
                }     
                
            });
            $("#Chart-graph").remove();
            var ctx=$("<canvas id='Chart-graph' height='450px'></canvas>");
            $('.graph').append(ctx);
            var lineChartCanvas = $("#Chart-graph").get(0).getContext("2d");
            var lineChart = new Chart(lineChartCanvas);
            lineChartOptions.datasetFill = false;
            lineChart.Line(lineChartData, lineChartOptions);

            $("#line").on('click',function(){
                var src1="{{URL::asset('admin/img/ico6.png')}}";
                var src2="{{URL::asset('admin/img/ico5.png')}}";
                $(this).attr("src",src2);
                $('#bar').attr("src",src1); 
                $("#Chart-graph").remove();
                var ctx=$("<canvas id='Chart-graph' height='450px'></canvas>");
                $('.graph').append(ctx);
                var lineChartCanvas = $("#Chart-graph").get(0).getContext("2d");      
                var lineChart = new Chart(lineChartCanvas);
                lineChartOptions.datasetFill = false;       
                lineChart.Line(lineChartData, lineChartOptions);
            }); 
            function timeCheck(para){   
                if (para<10){
                  para="0" + para;
                }   
                return para;
            } 

            $("#bar").on('click',function(){
                var src1="{{URL::asset('admin/img/ico5-2.png')}}";
                var src2="{{URL::asset('admin/img/ico6-2.png')}}";
                $(this).attr("src",src2);
                $('#line').attr("src",src1);  
                $("#Chart-graph").remove();
                var wid= $('.graph').width();
                var ctx=$("<canvas id='Chart-graph' height='450px' width="+wid+" ></canvas>");
                $('.graph').append(ctx);
                var lineChartCanvas = $("#Chart-graph").get(0).getContext("2d");
                var barChartCanvas=new Chart(lineChartCanvas);
                barChartCanvas.datasetFill = false;
                barChartCanvas.Bar(lineChartData,defaults);

            }); 
    }
    
});


});
    </script>

@endsection