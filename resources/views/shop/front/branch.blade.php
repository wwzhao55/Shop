<!-- auth:zww
     date:2016.07.22 
-->
@extends('layouts.shop')

@section('title')
<title>{{Session::get('brand_name')}}</title>
@stop
@section('addCss')
<link href="{{ URL::asset('shop/css/wechat.css')}}" rel="stylesheet">
@stop
@section('content')
<div id="branch">
<div id="chooseshop">   
	<ol class="shop-lists">
    @foreach($shoplists as $list)

        @if ( $list['active'] == 1 )        
            <li class="shop-list onchoose">  
                <span class="shop-id" hidden>{{$list['id']}}</span>
                @if ($list['city'] =="市辖区")
                    {{$list['province']}}{{$list['district']}}{{$list['address']}}{{$list['name']}}
                @else
                    {{$list['province']}}{{$list['city']}}{{$list['district']}}{{$list['address']}}{{$list['name']}}
                @endif
            </li>
        @else
            <li class="shop-list">  
                <span class="shop-id" hidden>{{$list['id']}}</span>
                {{$list['province']}}{{$list['city']}}{{$list['district']}}{{$list['address']}}{{$list['name']}}
            </li>
        @endif

    @endforeach
	</ol>
	<hr class="shop-line"/>
</div>
</div>
@stop
@section('addJs')
<script type="text/javascript">
    //ajax设置csrf_token
    $.ajaxSetup({
          headers: { 'X-CSRF-TOKEN' : '{{ csrf_token() }}' }
    });
	var shoplist=document.getElementById('chooseshop').getElementsByTagName('li');
	for(var i=0;i<shoplist.length-1;i++){
        if( shoplist[i].innerHTML.length==shoplist[i+1].innerHTML.length){
        	continue;        	
        } else{
        	$('.shop-list').css('padding-left','30px');
        }       
	}
    
    $(".shop-list").on('click',function(){
        var id=$(this).children(".shop-id").html();
        var name=$(this).children(".shop-name").html();
        $(this).parents('.shop-lists').find('.onchoose').removeClass('onchoose');
        $(this).addClass('onchoose');
        $.ajax({
            type:'POST',
            url:'/shop/front/branch',
            data:{
                shop_id:id
            },
            dataType:"json",
            success:function(data){
                if(data.status=="success"){
                    window.location.href="/shop/front/index";
                }else{
                    alert(data.msg);
                }
            }               
        });
    });
</script>
@stop

