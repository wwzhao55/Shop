@extends('layouts.app')
@section('siderbar')
@include('layouts.siderbar')
@endsection

@section('addCss')
<link rel="stylesheet" type="text/css" href="{{ URL::asset('shop/css/brandmanage.css')}}">
@endsection

@section('content')
    <div class="brandDetail">
        <div class="detail-block">
                    <div class="block-data">
                        <span class="block-number">{{$customer_count}}</span>
                        <span class="block-text">注册用户数</span>
                    </div>
                    <div class="block-data">
                        <span class="block-number">{{$order_count}}</span>
                        <span class="block-text">订单数</span>
                    </div>
                    <div class="block-data-1">
                        <span class="block-number">{{$total}}</span>
                        <span class="block-text">交易额</span>
                    </div>
        </div>
        <div class="chart-title">
                <span class="kind">数量</span>
                      <span class="kind"><img src="{{asset('shop/images/brandmanage/ico2.png')}}">&nbsp注册用户数</span>
                      <span class="kind"><img src="{{asset('shop/images/brandmanage/ico3.png')}}">&nbsp订单数</span>
                      <span class="kind"><img src="{{asset('shop/images/brandmanage/ico4.png')}}">&nbsp交易额</span>
                <div class="form-group">
                      <button type="button" class="btn btn-info pull-right" id="month">按月</button>
                      <button type="button" class="btn btn-info pull-right" id="week">按周</button>
                      <button type="button" class="btn btn-info pull-right" id="day">按日</button>

                </div>
                <div class="graph">
                  <canvas id="Chart-graph" height='450px'></canvas>
                </div>
        </div>
    </div>  
<script type="text/javascript">
    $(function(){

          var label_day= ["00:00", "06:00", "12:00", "18:00", "00:00"];
          var label_week= ["一", "二", "三", "四", "五", "六", "日"];
          var label_month=["January","February","March","April","May","June","July"];
          var data1=[40,10,10,60,60,60,60];
          var lineChartData = {
              labels: label_day,
              datasets: [
                {
                  label: "tradePay",
                  fillColor: "#FA9C95",   //背景色，常用transparent透明
                  strokeColor: "#FA9C95",  //线条颜色，也可用"#ffffff"
                  pointColor: "#FA9C95",   //点的填充颜色
                  pointStrokeColor: "#FA9C95",       //点的外边框颜色
                  pointHighlightFill: "#fff",
                  pointHighlightStroke: "rgba(220,220,220,1)",
                  data: [10,10,10,60,60,60,60]
                },
                {
                  label: "order-mount",
                  fillColor: "#FACDAE",
                  strokeColor: "#FACDAE",
                  pointColor: "#FACDAE",
                  pointStrokeColor: "#FACDAE",
                  pointHighlightFill: "#fff",
                  pointHighlightStroke: "rgba(60,141,188,1)",
                  data: [20,20,20,30,30,30,30]
                },
                
                {
                  label: "users-num",
                  fillColor: "#81B099",
                  strokeColor: "#81B099",
                  pointColor:  "#81B099",
                  pointStrokeColor:  "#81B099",
                  pointHighlightFill: "#fff",
                  pointHighlightStroke: "rgba(60,141,188,1)",
                  data: [40, 40, 40,50,50, 50, 50]
                }
               
              ]
          };

          var lineChartOptions = {
            //Boolean - If we should show the scale at all
            showScale: true,//
            scaleShowLabels : true,
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
          function daypost(){
              var end_time = Date.parse(new Date())/1000;
              var start_time = end_time - 60*60*32;
              $.ajax({
                   type: "post",
                   url: "/Shopadmin/datacenter/data",
                   data: {
                      start_time:start_time, 
                      end_time:end_time,
                      unit:'day',
                    },
                   dataType: "json",
                   success: function(data){
                            lineChartData.labels=label_day;
                            lineChartData.datasets[2].data=data.customer_array;
                            lineChartData.datasets[1].data=data.order_array;
                            lineChartData.datasets[0].data=data.total_array;
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
          $('#day').click(function(){
              var end_time = Date.parse(new Date())/1000;
              var start_time = end_time - 60*60*32;
              daypost();
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

              var end_time = Date.parse(new Date())/1000;
              var end_weekday=new Date().getDay();
            
              lineChartData.labels=new Array();
              for(var i=end_weekday;i>0;i--){
                 lineChartData.labels.unshift(weekDay(i));
              }
              for(var i=7;i>end_weekday;i--){
                lineChartData.labels.unshift(weekDay(i));
              }
              var start_time = end_time - 60*60*24*7;
              $.ajax({
                       type: "post",
                       url: "/Shopadmin/datacenter/data",
                       data: {
                          start_time:start_time, 
                          end_time:end_time,
                          unit:'week',
                        },
                       dataType: "json",
                       success: function(data){
                              // lineChartData.labels=label_week;
                              lineChartData.datasets[2].data=data.customer_array;
                              lineChartData.datasets[1].data=data.order_array;
                              lineChartData.datasets[0].data=data.total_array;
                              $("#Chart-graph").remove();
                              var ctx=$("<canvas id='Chart-graph' height='450px'></canvas>");
                              $('.graph').append(ctx);
                              var lineChartCanvas = $("#Chart-graph").get(0).getContext("2d");
                              var lineChart = new Chart(lineChartCanvas);
                              lineChartOptions.datasetFill = false;
                              lineChart.Line(lineChartData, lineChartOptions);
                        }
              });
          });
            
          $('#month').click(function(){
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
              }else{
                  var k=5;
             
                  lineChartData.labels.unshift(timeCheck(end_month+1)+"."+timeCheck(end_day));
                 
                  end_day=end_day+30;
             
                  for(;end_day>0&&k>0;k--){
                    end_day=end_day-5;
                  
                    lineChartData.labels.unshift(timeCheck(end_month)+"."+timeCheck(end_day));
                  }    
              }
          
              var start_time = end_time - 60*60*24*30;

                  $.ajax({
                         type: "post",
                         url: "/Shopadmin/datacenter/data",
                         data: {
                            start_time:start_time, 
                            end_time:end_time,
                            unit:'month',
                          },
                         dataType: "json",
                          success: function(data){
                                    lineChartData.datasets[2].data=data.customer_array;
                                    lineChartData.datasets[1].data=data.order_array;
                                    lineChartData.datasets[0].data=data.total_array;
                                    $("#Chart-graph").remove();
                                    var ctx=$("<canvas id='Chart-graph' height='450px'></canvas>");
                                    $('.graph').append(ctx);
                                    var lineChartCanvas = $("#Chart-graph").get(0).getContext("2d");
                                    var lineChart = new Chart(lineChartCanvas);
                                    lineChartOptions.datasetFill = false;
                                    lineChart.Line(lineChartData, lineChartOptions);
                          }
                  });
          })

          var lineChartCanvas = $("#Chart-graph").get(0).getContext("2d");
          var lineChart = new Chart(lineChartCanvas);
          lineChartOptions.datasetFill = false;
          lineChart.Line(lineChartData, lineChartOptions);
            }) 
       //鼠标悬停、移去事件     
       $("#bar").on("mouseover",function(){
        var src1="{{URL::asset('admin/img/ico6-2.png')}}"
        $(this).attr("src",src1);
       });
         $("#bar").on("mouseout",function(){
        var src1="{{URL::asset('admin/img/ico6.png')}}"
        $(this).attr("src",src1);
       });
           $("#line").on("mouseover",function(){
        var src1="{{URL::asset('admin/img/ico5.png')}}"
        $(this).attr("src",src1);
       });
         $("#line").on("mouseout",function(){
        var src1="{{URL::asset('admin/img/ico5-2.png')}}"
        $(this).attr("src",src1);
       })

       
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
  


    
          

    </script>



@endsection