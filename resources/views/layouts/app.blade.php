<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>德灵微店</title>
    <!-- Styles -->
    <link rel="stylesheet" href="{{URL::asset('admin/bootstrap/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{URL::asset('admin/css/nav.css')}}">
    <link rel="stylesheet" href="{{URL::asset('admin/css/siderbar.css')}}">
    <!-- <link rel="stylesheet" href="{{URL::asset('admin/css/jedate.css')}}"> -->
     <!-- JavaScripts -->
    <script src="{{URL::asset('admin/js/jquery.min.js')}}"></script>
    <script src="{{URL::asset('admin/bootstrap/js/bootstrap.min.js')}}"></script>
    <script src="{{URL::asset('admin/js/jquery-form.js')}}"></script>
    <script src="{{URL::asset('admin/js/Chart.min.js')}}"></script>
    <script src="{{URL::asset('admin/layer/layer.js')}}"></script>
    <script src="{{ URL::asset('shop/jPages/js/jPages.min.js')}}"></script>
    <script type="text/javascript" src="{{ URL::asset('shop/js/pdata.js')}}"></script>
    <script type="text/javascript" src="{{ URL::asset('admin/js/jedate.js')}}"></script>
    <script src="{{ URL::asset('admin/js/ZeroClipboard.js')}}"></script>


</head>
<body > 
                <!-- Right Side Of Navbar -->
                <nav>
                    <!-- Authentication Links -->
                    @if (Auth::guest())
                       <!--  <li><a href="{{ url('/auth/login') }}">登录</a></li>
                        <li><a href="{{ url('/auth/register') }}">注册</a></li> -->
                    @else  
                <div class="nav-bar">
                    <div class="nav-logo">
                        <img src="{{URL::asset('admin/img/app_img/logo-two.png')}}" /> 
                        
                    </div>
                    <div class="nav-word">
                        <div class="nav-title">
                            <span>德灵商家管理系统</span>
                        </div>
                        <div class="nav-drop">
                                <!-- <li class="dropdown"> -->
                                    <!-- <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"> -->
                                        {{ Auth::user()->account }} <!-- <span class="caret"></span> -->
                                    <!-- </a> -->

                                        <!-- <ul class="dropdown-menu" role="menu"> -->
                                            <!-- <li> -->
                                            <a href="{{ url('/logout') }}"><img src="{{URL::asset('admin/img/Close.png')}}" class='close'/></a>
                                            <!-- </li> -->
                                        <!-- </ul> -->
                                    <!-- </li> -->
                            @endif
                        </div>  
              </div>
                </div>
                </nav>  
   
        <div class="content">
            @yield('siderbar')
            @yield('addCss')
      
         <div class="content-right">
           @yield('content')
         </div>
           
            
            
        </div>
        

    </div>
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $(".close").on("mouseover",function(){
            var src1="{{URL::asset('admin/img/Close2.png')}}"
            $(this).attr("src",src1);
          });
        $(".close").on("mouseout",function(){
            var src1="{{URL::asset('admin/img/Close.png')}}"
            $(this).attr("src",src1);
        });
    </script>
</body>
</html>
