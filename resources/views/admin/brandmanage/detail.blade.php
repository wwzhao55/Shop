<!-- auth:wuwenjia -->
@extends('layouts.app')
@section('siderbar')
@include('layouts.siderbar')
@endsection

@section('addCss')
<link rel="stylesheet" type="text/css" href="{{ URL::asset('shop/css/brandmanage.css')}}">
@endsection

@section('content')
<div class="brandDetail">
    <form action='/Admin/brandmanage/changename' method="post" enctype="multipart/form-data">
        {!! csrf_field() !!}
        <div class="message">
            {{Session::get('Message')}}
        </div>
        <input type="text" name="brand_id" value="{{$brand->id}}" hidden>
        <div class="detail-head">
            <span class="detail-name">品牌详情——{{$brandname}}</span>
            <span class="detailmodify" style="color:#fb2d5c;font-size:18px;">修改</span>
            <input type="text" name="brandname" id="namemodify" value="{{$brandname}}">
            <button class="modify-btn" onclick="form.submit();">确定</button>       
            <a href="/Admin/brandmanage/index"><button class="detail-btn" type="button">返回</button></a>
        </div>
    </form>
    <div class="clearfix" style="clear:both;"></div>        
    <form action='/Admin/brandmanage/changeinfo' method="post" enctype="multipart/form-data" class="details">
        {!! csrf_field() !!}
        <div class="addtitle">基本信息<img src="{{asset('admin/img/down2.png')}}" class="down2">
            <button class="preservation" onclick="form.submit();">保存</button>
        </div>
        <div class="brand-left">  
            <input type="text" name="brand_id" hidden value="{{$brand->id}}">                     
            <div class="branch-top{{ $errors->has('company_name') ? ' has-error' : '' }}">
                <span>公司名 :</span>
                <input type="text" name="company_name"  required="required" value="{{$brand->company_name}}">
                        @if ($errors->has('company_name'))
                                        <div class="help-block">
                                            <strong>*{{ $errors->first('company_name') }}</strong>
                                        </div>
                        @endif
            </div>
            <div class="branch-list{{ $errors->has('contacter_name') ? ' has-error' : '' }}">
                <span>联系人 :</span>
                <input type="text" placeholder="姓名："  required="required" name="contacter_name" value="{{$brand->contacter_name}}">
                        @if ($errors->has('contacter_name'))
                                        <div class="help-block">
                                            <strong>*{{ $errors->first('contacter_name') }}</strong>
                                        </div>
                        @endif
            </div>
            <div class="branch-list{{ $errors->has('contacter_phone') ? ' has-error' : '' }}">
                <span>手机号 :</span>
                <input type="text" placeholder="电话："  required="required" name="contacter_phone" value="{{$brand->contacter_phone}}" class="brandphone">
                        @if ($errors->has('contacter_phone'))
                                        <div class="help-block">
                                            <strong>*{{ $errors->first('contacter_phone') }}</strong>
                                        </div>
                        @endif
                <div class="mention3">&nbsp手机号为商户超级管理员登录名</div>
            </div>
            <div class="branch-list{{ $errors->has('weixin_password') ? ' has-error' : '' }} brandpassword" style="display:none;">
                <span>密码 :</span>
                <input type="text" placeholder="密码："   name="password" value="">
                        @if ($errors->has('weixin_password'))
                                        <div class="help-block">
                                            <strong>*{{ $errors->first('weixin_password') }}</strong>
                                        </div>
                        @endif

                <div class="mention3">&nbsp密码为该商户首次默认登录密码</div>        
            </div>
            
            <div class="branch-list{{ $errors->has('contacter_email') ? ' has-error' : '' }}">
                <span>邮箱 :</span>
                <input type="email" placeholder="邮箱："  required="required" name="contacter_email" value="{{$brand->contacter_email}}">
                        @if ($errors->has('contacter_email'))
                                        <div class="help-block">
                                            <strong>*{{ $errors->first('contacter_email') }}</strong>
                                        </div>
                        @endif
            </div>
            <div class="branch-list{{ $errors->has('contacter_QQ') ? ' has-error' : '' }}">
                <span>QQ :</span>
                <input type="text" placeholder="QQ："  required="required" name="contacter_QQ" value="{{$brand->contacter_QQ}}">
                        @if ($errors->has('contacter_QQ'))
                                        <div class="help-block">
                                            <strong>*{{ $errors->first('contacter_QQ') }}</strong>
                                        </div>
                        @endif
            </div>
            <div class="branch-list{{ $errors->has('main_business') ? ' has-error' : '' }}">
                <span>主营 :</span>
                <div class="mainbussiness" hidden>{{$brand->main_business}}</div> 
                <select  class="brandSort" name="main_business" required="required" value="">                       
                <option value="全部">全部</option>
                     @foreach($mainbusiness as $type)                  
                        <option value="{{$type->name}}">{{$type->name}}</option>
                    @endforeach  
                                       
                </select>
                        @if ($errors->has('main_business'))
                                        <div class="help-block">
                                            <strong>*{{ $errors->first('main_business') }}</strong>
                                        </div>
                        @endif
            </div>
            <div class="branch-list">
                    <span>地址 :</span>
                    <select  class="distinct-1" name="company_district" required="required" value=""> </select>
                    <select  class="city-1" name="company_city" required="required" value="">  </select>
                    <select  class="province-1" name="company_province" required="required" value="">  </select>
                    <div class="detailprovince" hidden>{{$brand->company_province}}</div> 
                    <div class="detailcity" hidden>{{$brand->company_city}}</div>
                    <div class="detaildistrict" hidden>{{$brand->company_district}}</div>  
                </div>
                <div class="branch-list{{ $errors->has('company_address_detail') ? ' has-error' : '' }}">
                        <input type="text" placeholder="请输入详细地址"  required="required" name="company_address_detail" value="{{$brand->company_address_detail}}">
                            @if ($errors->has('company_address_detail'))
                                        <div class="help-block">
                                            <strong>*{{ $errors->first('company_address_detail') }}</strong>
                                        </div>
                            @endif
                </div>
                
                <div class="clearfix" style="clear:both;"></div>
          </div>
    </form>
          <div class="addtitle">数据信息<img src="{{asset('admin/img/down2.png')}}" class="down2"></div>
          <div class="datamessage">
                <div class="detail-block">
                      @if($shop_count)
                          <div class="block opennum">
                              <span class="block-number on">{{$shop_count}}</span>
                              <span class="block-text" style="font-size:14px;color:#27262f;">开通商家数</span>
                          </div>
                          <div class="block registernum">
                              <span class="block-number">{{$customer_count}}</span>
                              <span class="block-text" style="font-size:14px;color:#27262f;">注册用户数</span>
                          </div>
                          <div class="block-2 ordernum">
                              <span class="block-number">{{$order_count}}</span>
                              <span class="block-text" style="font-size:14px;color:#27262f;">订单数</span>
                          </div>
                          <div class="block-1 turnover">
                              <span class="block-number">{{$total}}</span>
                              <span class="block-text" style="font-size:14px;color:#27262f;">交易额</span>
                          </div>
                    @endif
                </div>
                @if($shop_count)
                <div class="chart-title">
                      <span class="kind">数量</span>
                                  <span class="kind"><img src="{{asset('shop/images/brandmanage/ico1.png')}}">&nbsp开通商家数</span>
                                  <span class="kind"><img src="{{asset('shop/images/brandmanage/ico2.png')}}">&nbsp注册用户数</span>
                                  <span class="kind"><img src="{{asset('shop/images/brandmanage/ico3.png')}}">&nbsp订单数</span>
                                  <span class="kind"><img src="{{asset('shop/images/brandmanage/ico4.png')}}">&nbsp交易额</span>
                      <div class="form-group">
                            <span class="detail-id" hidden>{{$brand_id}}</span>
                                      <button type="button" class="btn btn-info pull-right" id="month">月</button>
                                      <button type="button" class="btn btn-info pull-right" id="week">周</button>
                                      <button type="button" class="btn btn-info pull-right" id="day">日</button>

                      </div>
                      <div class="icon-msg1">
                        <img src="{{URL::asset('admin/img/ico6.png')}}" id="bar"/>
                      </div>
                      <div class="icon-msg1">
                        <img src="{{URL::asset('admin/img/ico5-2.png')}}" id="line"/>
                      </div>
                      <div class="graph">
                        <canvas id="Chart-graph" height='450px'></canvas>
                      </div>
                </div>
                @else   
                <div class="error-mention"> 咦，还没有数据哎，<a href="/Admin/shopmanage/add/{{$brand_id}}">添加分店</a>试试</div>             
                @endif 
          </div>
          <div class="addtitle">分店信息<img src="{{asset('admin/img/down2.png')}}" class="down2"><a href="/Admin/shopmanage/add/{{$brand_id}}"><button class="img-new">创建分店</button></a></div>    
                      
          <div class="brand_table shop_table">
                    <table>
                        <thead>
                            <tr>
                                <th width="14.2%">分店名</th>
                                <th width="14.2%">创建时间</th>
                                <th width="14.8%">地址</th>
                                <th width="14.2%">负责人</th>
                                <th width="14.2%">电话</th>
                                <th width="14.2%">开通冻结时间</th>
                                <th width="14.2%">操作</th>
                            </tr>
                        </thead>
                        <tbody class="tab-contents">
                          @if($shop_count)
                          @foreach($shop_lists as $list)
                            <tr>
                                <td>{{$list->shopname}}</td>
                                <td>{{$list->created_at}}</td>
                                <td>{{$list->shop_province}}{{$list->shop_city}}{{$list->shop_district}}{{$list->shop_address_detail}}</td>
                                <td>{{$list->contacter_name}}</td>
                                <td>{{$list->contacter_phone}}</td>
                                <td>{{$list->status_at}}</td>
                                <td><a href="javascript:void(0)" class="changestatus" shopId="{{$list->id}}" style="text-decoration:none;">
                                        @if($list->status)
                                            <img class="img-switch" status='{{$list->status}}' src="{{asset('shop/images/brandmanage/btn-clear.png')}}">
                                            <span class="word-describe" style="font-size:14px;">开通</span>
                                        @else
                                            <img class="img-switch" status='{{$list->status}}' src="{{asset('shop/images/brandmanage/btn-freeze.png')}}">
                                            <span class="word-describe" style="font-size:14px;">冻结</span>
                                            
                                        @endif
                                    </a>
                                    <a href="/Admin/shopmanage/detail/{{$list->id}}"><div class="more_detail">详情</div></a></td>
                            </tr>
                        @endforeach
                        </tbody> 
                        @endif
                    </table>                 
          </div>
          <div class="addtitle">公众号信息<img src="{{asset('admin/img/down2.png')}}" class="down2"><button class="modify">确定</button></div>
          <form action="/Admin/brandmanage/changeweixin" method="post" class="form" enctype="multipart/form-data">
                    <div class="message">
                        {{Session::get('WeixinMessage')}}
                    </div>
          <div class="detail_public"><div class="addtitle-2">公众号信息</div>
          <!-- <div class="publicbody"> -->
                
                    {!! csrf_field() !!}
                    <div style="margin-top:30px;" class="publicgroup{{ $errors->has('name') ? ' has-error' : '' }}">
                        <span>公众号名称：</span>
                        <input type="text" name="name" class="publicinput" value="{{$account['name']}}" placeholder="请输入商户微信公众号名称">
                        @if ($errors->has('name'))
                            <span class="help-block">
                                <strong>{{ $errors->first('name') }}</strong>
                            </span>
                        @endif
                        <div class="mention">微信公众平台=>设置=>公众号设置=>账号详情=>公开信息</div>
                        <div class="clearfix" style="clear:both;"></div>
                    </div>
                    
                    <div class="publicgroup{{ $errors->has('weixin_id') ? ' has-error' : '' }}">
                        <span>公众号微信号：</span>
                        <input type="text" name="weixin_id" class="publicinput" value="{{$account['weixin_id']}}" placeholder="请输入商户微信公众号名称">
                        @if ($errors->has('weixin_id'))
                            <span class="help-block">
                                <strong>{{ $errors->first('weixin_id') }}</strong>
                            </span>
                        @endif
                        <div class="mention">微信公众平台=>设置=>公众号设置=>账号详情=>公开信息</div>
                        <div class="clearfix" style="clear:both;"></div>
                    </div>

                    <div class="publicgroup{{ $errors->has('originalid') ? ' has-error' : '' }}">
                        <span>公众号原始ID：</span>
                        <input type="text" name="originalid" class="publicinput" value="{{$account['originalid']}}" placeholder="请输入商户微信公众号原始ID">
                        @if ($errors->has('originalid'))
                            <span class="help-block">
                                <strong>{{ $errors->first('originalid') }}</strong>
                            </span>
                        @endif
                        <div class="mention">微信公众平台=>设置=>公众号设置=>账号详情=>注册信息</div>
                        <div class="clearfix" style="clear:both;"></div>
                    </div>
                    

                    <div class="publicgroup{{ $errors->has('appid') ? ' has-error' : '' }}">
                        <span>公众号AppID（应用ID）：</span>
                        <input type="text" name="appid" class="publicinput" value="{{$account['appid']}}" placeholder="请输入商户微信公众号AppID（应用ID）">
                        @if ($errors->has('appid'))
                            <span class="help-block">
                                <strong>{{ $errors->first('appid') }}</strong>
                            </span>
                        @endif
                        <div class="mention">微信公众平台=>开发=>基本配置=>开发者ID</div>
                        <div class="clearfix" style="clear:both;"></div>
                    </div>
                    

                    <div class="publicgroup{{ $errors->has('appsecret') ? ' has-error' : '' }}">
                        <span>公众号AppSecret：</span>
                        <input type="text" name="appsecret" class="publicinput" value="{{$account['appsecret']}}" placeholder="请输入32位字符串">
                        @if ($errors->has('appsecret'))
                            <span class="help-block">
                                <strong>{{ $errors->first('appsecret') }}</strong>
                            </span>
                        @endif
                        <div class="mention">微信公众平台=>开发=>基本配置=>开发者ID</div>
                        <div class="clearfix" style="clear:both;"></div>
                    </div>
                    

                    <div class="tip">您必须启动服务器配置才能获取到以下信息（微信公众平台=>开发=>基本配置=>服务器配置-启用）</div>

                    <div class="publicgroup{{ $errors->has('token') ? ' has-error' : '' }}">
                        <span>公众号Token(令牌)：</span>
                        <input type="text" name="token" class="publicinput" value="{{$account['token']}}" placeholder="请输入商户公众号Token(令牌)">
                        @if ($errors->has('token'))
                            <span class="help-block">
                                <strong>{{ $errors->first('token') }}</strong>
                            </span>
                        @endif
                        <div class="mention">微信公众平台=>开发=>基本配置=>服务器配置</div>
                        <div class="clearfix" style="clear:both;"></div>
                    </div>
                    

                    <div class="publicgroup{{ $errors->has('encodingaeskey') ? ' has-error' : '' }}">
                        <span>公众号EncodingAESKey(消息加解密密钥)：</span>
                        <input type="text" name="encodingaeskey" class="publicinput" value="{{$account['encodingaeskey']}}" placeholder="">
                        @if ($errors->has('encodingaeskey'))
                            <span class="help-block">
                                <strong>{{ $errors->first('encodingaeskey') }}</strong>
                            </span>
                        @endif
                        <div class="mention">微信公众平台=>开发=>基本配置=>服务器配置</div>
                        <div class="clearfix" style="clear:both;"></div>
                    </div>

                    <div class="publicgroup{{ $errors->has('subscribe_text') ? ' has-error' : '' }}">
                        <span>欢迎语：</span>
                        <textarea type="text" name="subscribe_text" placeholder="">{{$account['subscribe_text']}}</textarea>
                        @if ($errors->has('subscribe_text'))
                            <span class="help-block">
                                <strong>{{ $errors->first('subscribe_text') }}</strong>
                            </span>
                        @endif
                        <div class="clearfix" style="clear:both;"></div>
                    </div>
                    <button type="submit" class="btnpublic" hidden></button>                     
                
          <!-- </div> -->
          <div class="newbrand-pay">
          <div class="addtitle-2">支付信息</div>
          <!-- <span class="wexinpay">微信支付信息:</span>  --> 
          <input type="text" name="brand_id" hidden value="{{$brand->id}}">  
          <div class="branch-list-top branch-list{{ $errors->has('weixin_shop_num') ? ' has-error' : '' }}">
          <span>微信支付商户号 :</span>
          <input type="text" placeholder="请输入商户的微信支付商户号"  required="required" name="weixin_shop_num" value="{{$brand['weixin_shop_num']}}">
                        @if ($errors->has('weixin_shop_num'))
                                        <span class="help-block">
                                            <strong>*{{ $errors->first('weixin_shop_num') }}</strong>
                                        </span>
                        @endif
          </div>
          <div class="mention3">微信公众号支付申请通过后，邮件获取到的微信支付商户号</div>
          <div class="branch-list{{ $errors->has('weixin_api_key') ? ' has-error' : '' }}">
          <span>API密钥 :</span>
          <input type="text" placeholder="请输入商户微信公众号的API密匙"  required="required" name="weixin_api_key" value="{{$brand['weixin_api_key']}}">
                        @if ($errors->has('weixin_api_key'))
                                        <span class="help-block">
                                            <strong>*{{ $errors->first('weixin_api_key') }}</strong>
                                        </span>
                        @endif
          </div>
          <div class="mention3">商户微信商户平台=>账户中心=>API安全=>设置密匙（32位密匙）</div>
          <!-- <div class="branch-list{{ $errors->has('weixin_staff_account') ? ' has-error' : '' }}">
                       <span>员工登陆账号 :</span>
                       <input type="text" placeholder="请输入商户微信公众号的员工登录账号"  required="required" name="weixin_staff_account" value="{{$brand['weixin_staff_account']}}">
                                     @if ($errors->has('weixin_staff_account'))
                                                     <span class="help-block">
                                                         <strong>*{{ $errors->first('weixin_staff_account') }}</strong>
                                                     </span>
                                     @endif
                       </div>
                       <div class="mention3">提示：商户微信商户平台=>账户中心=>员工账号管理=>员工列表=>新增员工账号=>获取员工登录账号</div>    -->             
          <div class="branch-list{{ $errors->has('weixin_apiclient_cert') ? ' has-error' : '' }}">
          <span>apiclient_cert :</span>
          <textarea type="text" placeholder="请输入商户微信公众号的apiclient_cert"  required="required" name="weixin_apiclient_cert" style="height:150px;">{{$brand['weixin_apiclient_cert']}}</textarea>
                        @if ($errors->has('weixin_apiclient_cert'))
                                        <span class="help-block">
                                            <strong>*{{ $errors->first('weixin_apiclient_cert') }}</strong>
                                        </span>
                        @endif
          </div>
          <div class="mention3">商户微信商户平台=>账户中心=>API安全=>API证书=>下载证书（pem格式)=>打开apiclient_cert.pem文件获取字符串</div>

          <div class="branch-list{{ $errors->has('weixin_apiclient_key') ? ' has-error' : '' }}">
          <span>apiclient_key :</span>
          <textarea type="text" placeholder="请输入商户微信公众号的apiclient_key"  required="required" name="weixin_apiclient_key" style="height:150px;">{{$brand['weixin_apiclient_key']}}</textarea>
                        @if ($errors->has('weixin_apiclient_key'))
                                        <span class="help-block">
                                            <strong>*{{ $errors->first('weixin_apiclient_key') }}</strong>
                                        </span>
                        @endif
          </div>
          <div class="mention3">商户微信商户平台=>账户中心=>API安全=>API证书=>下载证书（pem格式)=>打开apiclient_key.pem文件获取字符串</div> 
          </div>
          </div>
          </form>
          <div class='clearfix'></div>            
</div>

    <div class='clearfix'></div>
    <script type="text/javascript">
    var height=$('.content-right').height();
    $('.siderbar').height(height);
    $('.brandphone').focus(function(){
        $('.brandpassword').css('display','block');
    });
    $('.changestatus').click(function(){
        var thisobj = $(this);
        var shop_id = $(this).attr('shopId');
        $.ajax({
            type: 'POST',
            url: '/Admin/shopmanage/changestatus/'+shop_id ,
            success: function(data){
                if(data.status='success'){
                    var img = thisobj.find('.img-switch');
                    var span = thisobj.find('span');

                    if(img.attr('status') == '1'){
                        img.attr('status','0');
                        span.html('冻结');
                        img.attr('src','/shop/images/brandmanage/btn-freeze.png');
                    }else{
                        img.attr('status','1');
                        img.attr('src','/shop/images/brandmanage/btn-clear.png');
                        span.html('开通');
                        
                    }
                }
            },

            dataType: 'json'
        })

    })
    $(function () {
      $('.brandSort').val("{{$brand->main_business}}");
            var province_html ="";                
            $.each(pdata,function(idx,item){
                if (parseInt(item.level) == 0) {
                    province_html += "<option value='" + item.names + "' exid='" + item.code + "'>" + item.names + "</option>";
                }
            });
            $(".province-1").append(province_html).val("{{$brand->company_province}}");
            $(".province-1").change(function(){
                if ($(this).val() == "") 
                    return;
                $(".city-1 option").remove(); 
                $(".distinct-1 option").remove();
                var code = $(this).find("option:selected").attr("exid"); 
                code = code.substring(0,2);
                var html=""; 
                $(".distinct-1").append(html);
                $.each(pdata,function(idx,item){
                    if (parseInt(item.level) == 1 && code == item.code.substring(0,2)) {
                        html += "<option value='" + item.names + "' exid='" + item.code + "'>" + item.names + "</option>";
                    }
                });
                $(".city-1").append(html); 
                $(".city-1").change();

            });

            $(".city-1").change(function(){
                if ($(this).val() == "") return;
                $(".distinct-1 option").remove();
                var code = $(this).find("option:selected").attr("exid"); code = code.substring(0,4);
                var html="";
                $.each(pdata,function(idx,item){
                    if (parseInt(item.level) == 2 && code == item.code.substring(0,4)) {
                        html += "<option value='" + item.names + "' exid='" + item.code + "'>" + item.names + "</option>";
                    }
                });
                $(".distinct-1").append(html);      
            });
            //绑定
            $(".province-1").change();
            $(".city-1").val("{{$brand->company_city}}");
            $(".city-1").change();
            $(".distinct-1").val("{{$brand->company_district}}");        
    }); 
$('#menu-lists-parent').find('.onsidebar').removeClass('onsidebar');
    $('.brandmanage').addClass('onsidebar');
    //状态转换
      $("img.img-switch").on('click',function(){
          var logo = $(this).attr("src");
          if(logo=="{{asset('shop/images/brandmanage/btn-clear.png')}}"){
              $(this).attr("src","{{asset('shop/images/brandmanage/btn-freeze.png')}}");
              $(this).siblings(".word-describe").html("冻结");
          }else{
              $(this).attr("src","{{asset('shop/images/brandmanage/btn-clear.png')}}");
              $(this).siblings(".word-describe").html("开通");
          }      
      });
    //悬浮效果
      // $(".img-new").on("mouseover",function(){
      //     $(this).attr("src","{{asset('shop/images/brandmanage/btn-Branch-hover.png')}}");
      // });
      // $(".img-new").on("mouseout",function(){
      //     $(this).attr("src","{{asset('shop/images/brandmanage/btn-Branch.png')}}");
      // });
  $(function(){
          $('#day').css('background-color','#c4c4c4');
          var src1="{{URL::asset('admin/img/ico5.png')}}";
          var src2="{{URL::asset('admin/img/ico6.png')}}";
          $('#bar').attr("src",src2);
          $('#line').attr("src",src1);
          var label_day= ["00:00", "06:00", "12:00", "18:00", "00:00"];
          var label_week= ["一", "二", "三", "四", "五", "六", "日"];
          var label_month=["January","February","March","April","May","June","July"];
          var data1=[10,10,10,60,60,60,60];
          var data1=[40,10,10,60,60,60,60];
          var id=$(".detail-id").html();
          console.log(id);
          var lineChartData = {
                labels: label_day,
                datasets: [
                  
                  {
                    label: "users-num",
                    fillColor: "#81B099",
                    strokeColor: "#81B099",
                    pointColor:  "#81B099",
                    pointStrokeColor:  "#81B099",
                    pointHighlightFill: "#fff",
                    pointHighlightStroke: "rgba(60,141,188,1)",
                    // data: [30, 23, 43,56, 32, 60, 20]
                  }              
                    ]
                };

          var lineChartOptions = {
                  //Boolean - If we should show the scale at all
                  showScale: true,//
                  scaleShowLabels : true,
                  scaleOverride : false,
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
          var endTime = Date.parse(new Date())/1000;
          var startTime = endTime - 60*60*32;
          function Initdata(){
            $('#day').css('background-color','#c4c4c4');
            $('#week').css('background-color','#fff');
            $('#month').css('background-color','#fff');
            var src1="{{URL::asset('admin/img/ico5.png')}}";
            var src2="{{URL::asset('admin/img/ico6.png')}}";
            $('#bar').attr("src",src2);
            $('#line').attr("src",src1);
            var endTime = Date.parse(new Date())/1000;
            var startTime = endTime - 60*60*32;
            lineChartData.labels=label_day;
              if($('.on').next().html()=='开通商家数'){
                $.ajax({
                     type: "post",
                     url: "/Admin/brandmanage/data",
                     data: {
                        start_time:startTime, 
                        end_time:endTime,
                        unit:'day',
                        brand_id:id,
                      },
                     dataType: "json",
                     success: function(data){
                              
                              lineChartData.datasets[0].data=data.shop_array;
                              lineChartOptions.scaleLabel="<%= value   %>";
                              lineChartOptions.scaleOverride=false;
                              $("#Chart-graph").remove();
                              var ctx=$("<canvas id='Chart-graph' height='450px'></canvas>");
                              $('.graph').append(ctx);
                              var lineChartCanvas = $("#Chart-graph").get(0).getContext("2d");
                              var lineChart = new Chart(lineChartCanvas);
                              lineChartOptions.datasetFill = false;
                              lineChart.Line(lineChartData,lineChartOptions);
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

                  var endTime = Date.parse(new Date())/1000;
                  var end_weekday=new Date().getDay();
                
                  lineChartData.labels=new Array();
                  for(var i=end_weekday;i>0;i--){
                     lineChartData.labels.unshift(weekDay(i));
                  }
                  for(var i=7;i>end_weekday;i--){
                    lineChartData.labels.unshift(weekDay(i));
                  }

                  var startTime = endTime - 60*60*24*7;
                  if($('.on').next().html()=='开通商家数'){
                    $.ajax({
                         type: "post",
                         url: "/Admin/brandmanage/data",
                         data: {
                            start_time:startTime, 
                            end_time:endTime,
                            unit:'week',
                            brand_id:id,
                          },
                         dataType: "json",
                          success: function(data){
                                  lineChartData.datasets[0].data=data.shop_array;
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
                var endTime = Date.parse(new Date())/1000;
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
          
                var startTime = endTime - 60*60*24*30;
              if($('.on').next().html()=='开通商家数'){
                $.ajax({
                       type: "post",
                       url: "/Admin/brandmanage/data",
                       data: {
                          start_time:startTime, 
                          end_time:endTime,
                          unit:'month',
                          brand_id:id,
                        },
                       dataType: "json",
                       success: function(data){
                                 lineChartData.datasets[0].data=data.shop_array;
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
                
          })
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
          };
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
          }) 
              
  
  $('.opennum').on('click',function(){
          $('#day').css('background-color','#c4c4c4');
          $('#week').css('background-color','#fff');
          $('#month').css('background-color','#fff');
          var src1="{{URL::asset('admin/img/ico5.png')}}";
          var src2="{{URL::asset('admin/img/ico6.png')}}";
          $('#bar').attr("src",src2);
          $('#line').attr("src",src1);
          $('.detail-block').find('.on').removeClass('on');
          $(this).children('.block-number').addClass('on'); 
          var label_day= ["00:00", "06:00", "12:00", "18:00", "00:00"];
          var label_week= ["一", "二", "三", "四", "五", "六", "日"];
          var label_month=["January","February","March","April","May","June","July"];
          var data1=[10,10,10,60,60,60,60];
          var data1=[40,10,10,60,60,60,60];
          var id=$(".detail-id").html();
          console.log(id);
          var lineChartData = {
                labels: label_day,
                datasets: [
                  
                  {
                    label: "users-num",
                    fillColor: "#81B099",
                    strokeColor: "#81B099",
                    pointColor:  "#81B099",
                    pointStrokeColor:  "#81B099",
                    pointHighlightFill: "#fff",
                    pointHighlightStroke: "rgba(60,141,188,1)",
                    // data: [30, 23, 43,56, 32, 60, 20]
                  }              
                    ]
                }; 
          function Initdata(){
            $('#day').css('background-color','#c4c4c4');
            $('#week').css('background-color','#fff');
            $('#month').css('background-color','#fff');
            var src1="{{URL::asset('admin/img/ico5.png')}}";
            var src2="{{URL::asset('admin/img/ico6.png')}}";
            $('#bar').attr("src",src2);
            $('#line').attr("src",src1);
            var endTime = Date.parse(new Date())/1000;
            var startTime = endTime - 60*60*32;
            lineChartData.labels=label_day;
              if($('.on').next().html()=='开通商家数'){
                $.ajax({
                     type: "post",
                     url: "/Admin/brandmanage/data",
                     data: {
                        start_time:startTime, 
                        end_time:endTime,
                        unit:'day',
                        brand_id:id,
                      },
                     dataType: "json",
                     success: function(data){
                              
                              lineChartData.datasets[0].data=data.shop_array;
                              lineChartOptions.scaleLabel="<%= value   %>";
                              lineChartOptions.scaleOverride=false;
                              $("#Chart-graph").remove();
                              var ctx=$("<canvas id='Chart-graph' height='450px'></canvas>");
                              $('.graph').append(ctx);
                              var lineChartCanvas = $("#Chart-graph").get(0).getContext("2d");
                              var lineChart = new Chart(lineChartCanvas);
                              lineChartOptions.datasetFill = false;
                              lineChart.Line(lineChartData,lineChartOptions);
                     }            
                });
              }
          }
          Initdata();         
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
       });
  $('.registernum').on('click',function(){
          $('#day').css('background-color','#c4c4c4');
          $('#week').css('background-color','#fff');
          $('#month').css('background-color','#fff');
          var src1="{{URL::asset('admin/img/ico5.png')}}";
          var src2="{{URL::asset('admin/img/ico6.png')}}";
          $('#bar').attr("src",src2);
          $('#line').attr("src",src1);
          $('.detail-block').find('.on').removeClass('on');
          $(this).children('.block-number').addClass('on'); 
          var label_day= ["00:00", "06:00", "12:00", "18:00", "00:00"];
          var label_week= ["一", "二", "三", "四", "五", "六", "日"];
          var label_month=["January","February","March","April","May","June","July"];
          var data1=[10,10,10,60,60,60,60];
          var data1=[40,10,10,60,60,60,60];
          var id=$(".detail-id").html();
          console.log(id);
          var lineChartData = {
                labels: label_day,
                datasets: [
                  
                   {
                    label: "Business-mount",
                    fillColor: "#F6CFA9",
                    strokeColor: "#F6CFA9",
                    pointColor: "#F6CFA9",
                    pointStrokeColor: "#F6CFA9",
                    pointHighlightFill: "#fff",
                    pointHighlightStroke: "rgba(60,141,188,1)",
                    // data: [18, 30, 80, 49, 56, 37, 20]
                  }             
                    ]
                };
          function Initdata(){
            $('#day').css('background-color','#c4c4c4');
            $('#week').css('background-color','#fff');
            $('#month').css('background-color','#fff');
            var src1="{{URL::asset('admin/img/ico5.png')}}";
            var src2="{{URL::asset('admin/img/ico6.png')}}";
            $('#bar').attr("src",src2);
            $('#line').attr("src",src1);
            var endTime = Date.parse(new Date())/1000;
            var startTime = endTime - 60*60*32;
            lineChartData.labels=label_day;
            if($('.on').next().html()=='注册用户数'){
              $.ajax({
                     type: "post",
                     url: "/Admin/brandmanage/data",
                     data: {
                        start_time:startTime, 
                        end_time:endTime,
                        unit:'day',
                        brand_id:id,
                      },
                     dataType: "json",
                     success: function(data){
                              lineChartData.datasets[0].data=data.customer_array;
                              lineChartOptions.scaleLabel="<%= value   %>";
                              lineChartOptions.scaleOverride=false;
                              $("#Chart-graph").remove();
                              var ctx=$("<canvas id='Chart-graph' height='450px'></canvas>");
                              $('.graph').append(ctx);
                              var lineChartCanvas = $("#Chart-graph").get(0).getContext("2d");
                              var lineChart = new Chart(lineChartCanvas);
                              lineChartOptions.datasetFill = false;
                              lineChart.Line(lineChartData,lineChartOptions);
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

            var endTime = Date.parse(new Date())/1000;
            var end_weekday=new Date().getDay();
          
            lineChartData.labels=new Array();
            for(var i=end_weekday;i>0;i--){
               lineChartData.labels.unshift(weekDay(i));
            }
            for(var i=7;i>end_weekday;i--){
              lineChartData.labels.unshift(weekDay(i));
            }

            var startTime = endTime - 60*60*24*7;
            if($('.on').next().html()=='注册用户数'){

              $.ajax({
                   type: "post",
                   url: "/Admin/brandmanage/data",
                   data: {
                      start_time:startTime, 
                      end_time:endTime,
                      unit:'week',
                      brand_id:id,
                    },
                    dataType: "json",
                    success: function(data){
                            lineChartData.datasets[0].data=data.customer_array;
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
                var endTime = Date.parse(new Date())/1000;
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
          
                var startTime = endTime - 60*60*24*30;
              if($('.on').next().html()=='注册用户数'){
                $.ajax({
                       type: "post",
                       url: "/Admin/brandmanage/data",
                       data: {
                          start_time:startTime, 
                          end_time:endTime,
                          unit:'month',
                          brand_id:id,
                        },
                       dataType: "json",
                       success: function(data){
                                lineChartData.datasets[0].data=data.customer_array;
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
                
          })
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
          }) 
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
   
       });
  $('.ordernum').on('click',function(){
          $('#day').css('background-color','#c4c4c4');
          $('#week').css('background-color','#fff');
          $('#month').css('background-color','#fff');
          var src1="{{URL::asset('admin/img/ico5.png')}}";
          var src2="{{URL::asset('admin/img/ico6.png')}}";
          $('#bar').attr("src",src2);
          $('#line').attr("src",src1);
          $('.detail-block').find('.on').removeClass('on');
          $(this).children('.block-number').addClass('on'); 
          var label_day= ["00:00", "06:00", "12:00", "18:00", "00:00"];
          var label_week= ["一", "二", "三", "四", "五", "六", "日"];
          var label_month=["January","February","March","April","May","June","July"];
          var data1=[10,10,10,60,60,60,60];
          var data1=[40,10,10,60,60,60,60];
          var id=$(".detail-id").html();
          console.log(id);
          var lineChartData = {
                labels: label_day,
                datasets: [
                  
                  {
                    label: "order-mount",
                    fillColor: "#C7C5A4",
                    strokeColor: "#C7C5A4",
                    pointColor: "#C7C5A4",
                    pointStrokeColor: "#C7C5A4",
                    pointHighlightFill: "#fff",
                    pointHighlightStroke: "rgba(60,141,188,1)",
                    // data: [10, 48, 40, 19, 86, 27, 90]
                  }
                          
                    ]
                };
        function Initdata(){
            $('#day').css('background-color','#c4c4c4');
            $('#week').css('background-color','#fff');
            $('#month').css('background-color','#fff');
            var src1="{{URL::asset('admin/img/ico5.png')}}";
            var src2="{{URL::asset('admin/img/ico6.png')}}";
            $('#bar').attr("src",src2);
            $('#line').attr("src",src1);
            var endTime = Date.parse(new Date())/1000;
            var startTime = endTime - 60*60*32;
            lineChartData.labels=label_day;
            if($('.on').next().html()=='订单数'){
              $.ajax({
                     type: "post",
                     url: "/Admin/brandmanage/data",
                     data: {
                        start_time:startTime, 
                        end_time:endTime,
                        unit:'day',
                        brand_id:id,
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
                              lineChart.Line(lineChartData,lineChartOptions);
                     }            
                });
            }
        }
          $('#day').click(function(){
            Initdata();
                
          });
        Initdata();
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

            var endTime = Date.parse(new Date())/1000;
            var end_weekday=new Date().getDay();
          
            lineChartData.labels=new Array();
            for(var i=end_weekday;i>0;i--){
               lineChartData.labels.unshift(weekDay(i));
            }
            for(var i=7;i>end_weekday;i--){
              lineChartData.labels.unshift(weekDay(i));
            }

            var startTime = endTime - 60*60*24*7;
            if($('.on').next().html()=='订单数'){
                $.ajax({
                                    type: "post",
                                    url: "/Admin/brandmanage/data",
                                    data: {
                                      start_time:startTime, 
                                      end_time:endTime,
                                      unit:'week',
                                      brand_id:id,
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
                var endTime = Date.parse(new Date())/1000;
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
          
                var startTime = endTime - 60*60*24*30;
              if($('.on').next().html()=='订单数'){
                $.ajax({
                       type: "post",
                       url: "/Admin/brandmanage/data",
                       data: {
                          start_time:startTime, 
                          end_time:endTime,
                          unit:'month',
                          brand_id:id,
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
                
          })
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
          }) 
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
          })  
       });
  $('.turnover').on('click',function(){
          $('#day').css('background-color','#c4c4c4');
          $('#week').css('background-color','#fff');
          $('#month').css('background-color','#fff');
          var src1="{{URL::asset('admin/img/ico5.png')}}";
          var src2="{{URL::asset('admin/img/ico6.png')}}";
          $('#bar').attr("src",src2);
          $('#line').attr("src",src1);
          $('.detail-block').find('.on').removeClass('on');
          $(this).children('.block-number').addClass('on'); 
          var label_day= ["00:00", "06:00", "12:00", "18:00", "00:00"];
          var label_week= ["一", "二", "三", "四", "五", "六", "日"];
          var label_month=["January","February","March","April","May","June","July"];
          var data1=[10,10,10,60,60,60,60];
          var data1=[40,10,10,60,60,60,60];
          var id=$(".detail-id").html();
          console.log(id);
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
                    // data: data1
                  }          
                    ]
                };
        function Initdata(){
            $('#day').css('background-color','#c4c4c4');
            $('#week').css('background-color','#fff');
            $('#month').css('background-color','#fff');
            var src1="{{URL::asset('admin/img/ico5.png')}}";
            var src2="{{URL::asset('admin/img/ico6.png')}}";
            $('#bar').attr("src",src2);
            $('#line').attr("src",src1);
            var endTime = Date.parse(new Date())/1000;
            var startTime = endTime - 60*60*32;
            lineChartData.labels=label_day;
            if($('.on').next().html()=='交易额'){
              $.ajax({
                     type: "post",
                     url: "/Admin/brandmanage/data",
                     data: {
                        start_time:startTime, 
                        end_time:endTime,
                        unit:'day',
                        brand_id:id,
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
                              lineChart.Line(lineChartData,lineChartOptions);
                     }            
                });
            }
        }
          $('#day').click(function(){
            Initdata();
                
          });
        Initdata();
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

            var endTime = Date.parse(new Date())/1000;
            var end_weekday=new Date().getDay();
          
            lineChartData.labels=new Array();
            for(var i=end_weekday;i>0;i--){
               lineChartData.labels.unshift(weekDay(i));
            }
            for(var i=7;i>end_weekday;i--){
              lineChartData.labels.unshift(weekDay(i));
            }

            var startTime = endTime - 60*60*24*7;
            if($('.on').next().html()=='交易额'){
                $.ajax({
                                   type: "post",
                                   url: "/Admin/brandmanage/data",
                                   data: {
                                      start_time:startTime, 
                                      end_time:endTime,
                                      unit:'week',
                                      brand_id:id,
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
                var endTime = Date.parse(new Date())/1000;
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
          
                var startTime = endTime - 60*60*24*30;
                if($('.on').next().html()=='交易额'){
                $.ajax({
                       type: "post",
                       url: "/Admin/brandmanage/data",
                       data: {
                          start_time:startTime, 
                          end_time:endTime,
                          unit:'month',
                          brand_id:id,
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
                
          })
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
          }) 

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
          }) 
     
    });
  });


   

   //点击修改
    $('.detailmodify').on('click',function(){
        var input=$("#namemodify").css('display');
        if (input == "inline-block") {
            $('#namemodify').css('display','none');
            $('.modify-btn').css('display','none');
          $(this).html('修改');

        } else if (input == "none") {
            $('#namemodify').css('display','inline-block');
            $('.modify-btn').css('display','inline-block');
            $(this).html('取消');

        }
        
    });
    $('.modify').on('click',function(){
                $('.btnpublic').click();
            });
     
    $('.down2').on('click',function(){
      var m=$(this).parent('.addtitle').next().css('display');
      if(m == "block"){
        $(this).parent('.addtitle').next().css('display','none');
        var src1="{{URL::asset('admin/img/up1.png')}}"
        $(this).attr("src",src1);
      }else if(m == "none"){
        $(this).parent('.addtitle').next().css('display','block');
        var src1="{{URL::asset('admin/img/down2.png')}}"
        $(this).attr("src",src1);
      } 
    }); 
     
</script>
@endsection