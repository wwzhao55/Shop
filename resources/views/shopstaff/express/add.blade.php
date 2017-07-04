@extends('layouts.app')
@section('siderbar')
@include('layouts.siderbar')
@endsection
@section('content')
    <link rel="stylesheet" type="text/css" href="{{URL::asset('shop/css/express-mode.css')}}">
     <div class="container-commodity">
       <div class="commodity-edit">
            <span class="commodity-word"><span class="commodity-ban">运费模板</span>/新建运费模板</span>
        </div> 
        <div class="mode-container">
            <div class="mode-name">
                <label for="name" class="title-express">模板名称</label><input type="text" name="name" id="name">
            </div>
            <div class="express-area">
              <div class="title-express1">配送区域</div>
              <div class="express-table">
                <table class="table-box">
                  <thead>
                    <th>可配送区域</th>
                    <th>首件（个）</th>
                    <th>运费（元）</th>
                    <th>续件（个）</th>
                    <th>续费（元）</th>
                  </thead>
                  <tbody>
                    <tr>
                      <td><span class="td-word">指定可配送区域和运费</span></td>
                     <td><input type="text" class="data-express" id="first_num"/></td>
                     <td><input type="text" class="data-express" id="first_price"/></td>
                     <td><input type="text" class="data-express" id="second_num"/></td>
                     <td><input type="text" class="data-express" id="second_price"/></td>
                  </tbody>
                </table>
                <div class="btns">
                  <img  src="{{URL::asset('shopstaff/img/preservation.png')}}" class="save"/>
                </div>

                <div class="area-choose-container">
                     <div class="choose-title">选择可配送区域</div>

                     <div class="area-detail">
                       <div class="province-word"><span>可选省、市、区<span></div>
                       <div class="province-name">
                       </div>
                     </div>
                          <div class="btn-add-del">
                            <button class="add-btn">添加</button>
                           <button class="del-btn">删除</button>
                          </div>
                          
                      <div class="area-detail1">
                       <div class="province-word"><span>已选省、市、区<span></div>
                       <div class="province-choosen">
                       </div>
                     </div>
                      <div class="confrim">
                  <img  src="{{URL::asset('shopstaff/img/determine.png')}}" />
                </div>

                </div>
              </div>
            </div>
        </div>
      </div>
      <script>
     var provinceArray = new Array("北京","上海","天津","重庆","河北","山西","内蒙","辽宁","吉林","黑龙江","江苏","浙江","安徽","福建","江西","山东","河南","湖北","湖南","广东","广西","海南","四川","贵州","云南","西藏","陕西","甘肃","宁夏","青海","新疆","香港","澳门","台湾","其它");
        $(".td-word").on("click",function(){
            cancel_index=layer.open({
              type: 1,
              title: false,
              closeBtn: 0,
              shadeClose: true,
              skin: 'yourclass',
              shade: 0.5,
              area : ['800px' , '800px'],
              content:$('.area-choose-container'),          
                      });
    });
    $(document).ready(function(){
      for(var i=0;i<provinceArray.length;i++){
        var  province=$("<p class='province-p'>"+provinceArray[i]+"</p>")
         $(".province-name").append(province);
      }

     var express_province=new Array();

     $(".province-name").on("click",".province-p",function(){
      var i;
     for(i=0;i<express_province.length;i++){
       if(express_province[i]==$(this).html()){
           layer.tips("你已经选择该省/市！请不要重复选择", $(this), {
                tips: [1, '#F92672'],
               time: 2000
         });
           break;
       }   
     }
         if(i==express_province.length){
             express_province.push($(this).html());
              $(this).css("background-color","#969696");
            }
     });


     $(".add-btn").on("click",function(){
      $(".province-choosen").html("");
       for(var i=0;i<express_province.length;i++){
       var  province=$("<p class='province-p1'>"+express_province[i]+"</p>")
         $(".province-choosen").append(province);
       }
          $(".province-p").css("background-color","#fff");
     })
      
           var del_province=new Array();
     $(".province-choosen").on("click",".province-p1",function(){
          del_province.push($(this).html()) ; 
          $(this).css("background-color","#969696");
     })
     $(".del-btn").on("click",function(){
         for(var i=0;i<del_province.length;i++){
             for(var j=0;j<express_province.length;j++){
              if(del_province[i]==express_province[j]){
                express_province.splice(j,1);
              }
             }
         }
         $(".province-choosen").html("");
       for(var i=0;i<express_province.length;i++){
       var  province=$("<p class='province-p1'>"+express_province[i]+"</p>")
         $(".province-choosen").append(province);
       }
     })
     
     $(".confrim").on("click",function(){
         layer.close(cancel_index);
     });
    
    $(".save").on("click",function(){
      var name=$("#name").val();
      var first_num=$("#first_num").val();
      var first_price=$("#first_price").val();
      var second_num=$("#second_num").val();
      var second_price=$("#second_price").val();
     $.ajax({
                type:'POST',
                url:'/Shopstaff/express/add',  
                data:{
                 name:name,
                 first_num:first_num,
                 first_price:first_price,
                 second_num:second_num,
                 second_price:second_price,
                 express_province:express_province
                } ,                       
                dataType:"json",
                success:function(result){
                    if(result.status=="success"){
                        alert("添加成功！");
                    }else{
                        alert("添加失败！");
                    }
                }               
    });
   });
    })    
      </script>
@endsection