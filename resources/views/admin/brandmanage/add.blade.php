<!-- auth:wuwenjia -->
@extends('layouts.app')
@section('siderbar')
@include('layouts.siderbar')
@endsection

@section('addCss')
<link rel="stylesheet" type="text/css" href="{{ URL::asset('shop/css/brandmanage.css')}}">
@endsection

@section('content')

<div class="newBrand">
        <form action='/Admin/brandmanage/add' method="post" enctype="multipart/form-data" onsubmit="return check()">
            {!! csrf_field() !!}
            <div class="page-head">品牌创建
                <a href="/Admin/brandmanage/index"><input value="返回" type="button" class="add-return"></a>
            </div>
            <div class="message">
                {{Session::get('Message')}}
            </div>

            <div class="brand-left">
                <div class="addtitle-1">品牌基本信息</div>
                <div class="branch-top{{ $errors->has('brandname') ? ' has-error' : '' }}">
                    <span>品牌名 :</span>
                    <input type="text" name="brandname" value="{{old('brandname')}}" class='input'>
                        @if ($errors->has('brandname'))
                                        <span class="help-block">
                                            <strong>*{{ $errors->first('brandname') }}</strong>
                                        </span>
                        @endif
                </div>
                <div class="branch-list{{ $errors->has('company_name') ? ' has-error' : '' }}">
                    <span>公司名 :</span>
                    <input type="text" name="company_name"  value="{{old('company_name')}}" class='input'>
                        @if ($errors->has('company_name'))
                                        <span class="help-block">
                                            <strong>*{{ $errors->first('company_name') }}</strong>
                                        </span>
                        @endif
                </div>
                <div class="branch-list{{ $errors->has('contacter_name') ? ' has-error' : '' }}">
                    <span>联系人 :</span>
                    <input type="text" placeholder="请输入联系人姓名"  name="contacter_name" value="{{old('contacter_name')}}" class='input'>
                        @if ($errors->has('contacter_name'))
                                        <span class="help-block">
                                            <strong>*{{ $errors->first('contacter_name') }}</strong>
                                        </span>
                        @endif
                </div>
                <div class="branch-list{{ $errors->has('contacter_phone') ? ' has-error' : '' }}">
                    <span>手机号 :</span>
                    <input type="text" class='input' placeholder="请输入手机号"  name="contacter_phone" value="{{old('contacter_phone')}}" autocomplete="off">
                        @if ($errors->has('contacter_phone'))
                                        <span class="help-block">
                                            <strong>*{{ $errors->first('contacter_phone') }}</strong>
                                        </span>
                        @endif
                    <div class="mention3">&nbsp手机号为商户超级管理员登录名</div>
                </div>
                <div class="branch-list{{ $errors->has('password') ? ' has-error' : '' }}">
                    <span>密码 :</span>
                    <input type="text" class='input' placeholder="请输入登陆密码"   name="password" onfocus="this.type='password'" value="" autocomplete="off" >
                        @if ($errors->has('password'))
                                        <span class="help-block">
                                            <strong>*{{ $errors->first('password') }}</strong>
                                        </span>
                        @endif
                </div>
                <div class="mention3">&nbsp密码为该商户首次默认登录密码</div>
                <div class="branch-list{{ $errors->has('contacter_email') ? ' has-error' : '' }}">
                    <span>邮箱 :</span>
                    <input type="email" class='input' placeholder=""  name="contacter_email" value="{{old('contacter_email')}}">
                        @if ($errors->has('contacter_email'))
                                        <span class="help-block">
                                            <strong>*{{ $errors->first('contacter_email') }}</strong>
                                        </span>
                        @endif
                </div>
                <div class="branch-list{{ $errors->has('contacter_QQ') ? ' has-error' : '' }}">
                    <span>QQ :</span>
                    <input type="text" class='input' placeholder=""  name="contacter_QQ" value="{{old('contacter_QQ')}}">
                        @if ($errors->has('contacter_QQ'))
                                        <span class="help-block">
                                            <strong>*{{ $errors->first('contacter_QQ') }}</strong>
                                        </span>
                        @endif
                </div>
                <div class="branch-list{{ $errors->has('main_business') ? ' has-error' : '' }}">
                    <span>主营 :</span>
                    <select  class="brandSort" name="main_business"  value="{{old('main_business')}}">
                    <option value="全部">全部</option>
                    @foreach($mainbusiness as $type)                  
                        <option value="{{$type->name}}">{{$type->name}}</option>
                    @endforeach                        
                    </select>
                        @if ($errors->has('main_business'))
                                        <span class="help-block">
                                            <strong>*{{ $errors->first('main_business') }}</strong>
                                        </span>
                        @endif
                </div>
                <div class="branch-list">
                    <span>地址 :</span>
                    <select  class="distinct-1" name="company_district"  value="{{old('company_district')}}"><option value="0">区</option></select>
                    <select  class="city-1" name="company_city"  value="{{old('company_city')}}">
                        <option value="0">市</option>
                     </select>
                    <select  class="province-1" name="company_province"  value="{{old('company_province')}}">  </select>   
                </div>
                <div class="branch-list{{ $errors->has('company_address_detail') ? ' has-error' : '' }}">
                        <input type="text" placeholder="请输入详细地址" name="company_address_detail" value="{{old('company_address_detail')}}">
                            @if ($errors->has('company_address_detail'))
                                        <span class="help-block">
                                            <strong>*{{ $errors->first('company_address_detail') }}</strong>
                                        </span>
                            @endif
                </div>
                
                <div class="clearfix" style="clear:both;"></div>
                
                <div class="addtitle-1 publicmessage">公众号信息</div>
                <!-- <div class="publicbody"> -->                
                        <div class="publicgroup{{ $errors->has('name') ? ' has-error' : '' }}">
                            <span>公众号名称：</span>
                            <input type="text" name="name" class="publicinput" value="{{old('name')}}" placeholder="请输入商户微信公众号名称">
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
                            <input type="text" name="weixin_id" class="publicinput" value="{{old('weixin_id')}}" placeholder="请输入商户微信公众号名称">
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
                            <input type="text" name="originalid" class="publicinput" value="{{old('originalid')}}" placeholder="请输入商户微信公众号原始ID">
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
                            <input type="text" name="appid" class="publicinput" value="{{old('appid')}}" placeholder="请输入商户微信公众号AppID（应用ID）">
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
                            <input type="text" name="appsecret" class="publicinput" value="{{old('appsecret')}}" placeholder="请输入32位字符串">
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
                            <input type="text" name="token" class="publicinput" value="{{old('token')}}" placeholder="请输入商户公众号Token(令牌)">
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
                            <input type="text" name="encodingaeskey" class="publicinput" value="{{old('encodingaeskey')}}" placeholder="">
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
                            <textarea style="width:60%;height:150px;" type="text" name="subscribe_text" placeholder="">{{old('subscribe_text')}}</textarea>
                            @if ($errors->has('subscribe_text'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('subscribe_text') }}</strong>
                                </span>
                            @endif
                            <div class="clearfix" style="clear:both;"></div>                            
                        </div>
                        <button type="submit" class="btn" hidden></button>                     
                    
                <!-- </div> -->
                <div class='newbrand-pay'>
                    <div class="addtitle-1 publicmessage">支付信息</div>
                    <!-- <span class="wexinpay">微信支付信息:</span> -->    
                    <div class="branch-list2{{ $errors->has('weixin_shop_num') ? ' has-error' : '' }}">
                        <span>微信支付商户号 :</span>
                        <input type="text" placeholder="请输入商户的微信支付商户号"   name="weixin_shop_num" value="{{old('weixin_shop_num')}}">
                            @if ($errors->has('weixin_shop_num'))
                                            <span class="help-block">
                                                <strong>*{{ $errors->first('weixin_shop_num') }}</strong>
                                            </span>
                            @endif
                    </div>
                    <div class="mention2">微信公众号支付申请通过后，邮件获取到的微信支付商户号</div>
                    <div class="branch-list2{{ $errors->has('weixin_api_key') ? ' has-error' : '' }}">
                        <span>API密钥 :</span>
                        <input type="text" placeholder="请输入商户微信公众号的API密匙"   name="weixin_api_key" value="{{old('weixin_api_key')}}">
                            @if ($errors->has('weixin_api_key'))
                                            <span class="help-block">
                                                <strong>*{{ $errors->first('weixin_api_key') }}</strong>
                                            </span>
                            @endif
                    </div>
                    <div class="mention2">商户微信商户平台=>账户中心=>API安全=>设置密匙（32位密匙）</div>
                    <!-- <div class="branch-list2{{ $errors->has('weixin_staff_account') ? ' has-error' : '' }}">
                        <span>员工登陆账号 :</span>
                        <input type="text" placeholder="请输入商户微信公众号的员工登录账号"   name="weixin_staff_account" value="{{old('weixin_staff_account')}}">
                            @if ($errors->has('weixin_staff_account'))
                                            <span class="help-block">
                                                <strong>*{{ $errors->first('weixin_staff_account') }}</strong>
                                            </span>
                            @endif
                    </div>
                    <div class="mention2">提示：商户微信商户平台=>账户中心=>员工账号管理=>员工列表=>新增员工账号=>获取员工登录账号</div> -->
                    
                    <div class="branch-list2{{ $errors->has('weixin_apiclient_cert') ? ' has-error' : '' }}">
                        <span>apiclient_cert :</span>
                        <textarea type="text" placeholder="请输入商户微信公众号的apiclient_cert"   name="weixin_apiclient_cert" value="{{old('weixin_apiclient_cert')}}" style="width:60%;height:150px;"></textarea>
                            @if ($errors->has('weixin_apiclient_cert'))
                                            <span class="help-block">
                                                <strong>*{{ $errors->first('weixin_apiclient_cert') }}</strong>
                                            </span>
                            @endif
                    </div>
                    <div class="mention2">
                    商户微信商户平台=>账户中心=>API安全=>API证书=>下载证书（pem格式)=>打开apiclient_cert.pem文件获取字符串</div>

                    <div class="branch-list2{{ $errors->has('weixin_apiclient_key') ? ' has-error' : '' }}">
                        <span>apiclient_key :</span>
                        <textarea type="text" placeholder="请输入商户微信公众号的apiclient_key"   name="weixin_apiclient_key" value="{{old('weixin_apiclient_key')}}" style="width:60%;height:150px;"></textarea>
                            @if ($errors->has('weixin_apiclient_key'))
                                            <span class="help-block">
                                                <strong>*{{ $errors->first('weixin_apiclient_key') }}</strong>
                                            </span>
                            @endif
                    </div>
                    <div class="mention2">
                    商户微信商户平台=>账户中心=>API安全=>API证书=>下载证书（pem格式)=>打开apiclient_key.pem文件获取字符串</div> 
                    <div class='clearfix'></div>
                </div>
            </div>
            <!-- <div class="brand-right">
             
            </div> -->
            <div id="brand-bottom">
                    <a href="/Admin/brandmanage/index"><img src="{{asset('shop/images/brandmanage/btn-return.png')}}" class="return bottom-return"></a>
                    <input type="image" src="{{asset('shop/images/brandmanage/btn-determine.png')}}" class="confirm bottom-confirm">
            </div>
                                                                          
        </form>
    </div>
<div class='clearfix'></div>
<script type="text/javascript">
var height=$('.content-right').height();
$('.siderbar').height(height);
$('.siderbar').height($('.content-right').height());
$('#menu-lists-parent').find('.onsidebar').removeClass('onsidebar');
    $('.brandmanage').addClass('onsidebar');
    $(function () {

            var html = "<option value='0'>省</option>";

            $.each(pdata,function(idx,item){
                if (parseInt(item.level) == 0) {
                    html += "<option value='" + item.names + "' exid='" + item.code + "'>" + item.names + "</option>";
                }
            });
            $(".province-1").append(html);

            $(".province-1").change(function(){
                if ($(this).val() == "") 
                    return;
                $(".city-1 option").remove(); 
                $(".distinct-1 option").remove();
                var code = $(this).find("option:selected").attr("exid"); 
                code = code.substring(0,2);
                var html = ""; 
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
                var html = "";
                $.each(pdata,function(idx,item){
                    if (parseInt(item.level) == 2 && code == item.code.substring(0,4)) {
                        html += "<option value='" + item.names + "' exid='" + item.code + "'>" + item.names + "</option>";
                    }
                });
                $(".distinct-1").append(html);      
            });
    });
    //验证
            function check(){
                if ($("input[name='brandname']").parent(".branch-top").has(".shopwarning")) {
                    $("input[name='brandname']").parent(".branch-top").find(".shopwarning").remove();
                }
                if ($("input[name='company_name']").parent(".branch-list").has(".shopwarning")) {
                    $("input[name='company_name']").parent(".branch-list").find(".shopwarning").remove();
                }
                if ($("input[name='contacter_name']").parent(".branch-list").has(".shopwarning")) {
                    $("input[name='contacter_name']").parent(".branch-list").find(".shopwarning").remove();
                }
                if ($("input[name='contacter_phone']").parent(".branch-list").has(".shopwarning")) {
                    $("input[name='contacter_phone']").parent(".branch-list").find(".shopwarning").remove();
                }
                if ($("input[name='password']").parent(".branch-list").has(".shopwarning")) {
                    $("input[name='password']").parent(".branch-list").find(".shopwarning").remove();
                }
                if ($("input[name='contacter_email']").parent(".branch-list").has(".shopwarning")) {
                    $("input[name='contacter_email']").parent(".branch-list").find(".shopwarning").remove();
                }
                if ($("input[name='contacter_QQ']").parent(".branch-list").has(".shopwarning")) {
                    $("input[name='contacter_QQ']").parent(".branch-list").find(".shopwarning").remove();
                }
                if ($("input[name='company_address_detail']").parent(".branch-list").has(".shopwarning")) {
                    $("input[name='company_address_detail']").parent(".branch-list").find(".shopwarning").remove();
                }
                if ($("select[name='company_province']").parent(".branch-list").has(".shopwarning")) {
                    $("select[name='company_province']").parent(".branch-list").find(".shopwarning").remove();
                }
                if ($("select[name='company_city']").parent(".branch-list").has(".shopwarning")) {
                    $("select[name='company_city']").parent(".branch-list").find(".shopwarning").remove();
                }
                if ($("select[name='company_district']").parent(".branch-list").has(".shopwarning")) {
                    $("select[name='company_district']").parent(".branch-list").find(".shopwarning").remove();
                }

                if ($("input[name='name']").parent(".publicgroup").has(".shopwarning")) {
                    $("input[name='name']").parent(".publicgroup").find(".shopwarning").remove();
                }
                if ($("input[name='weixin_id']").parent(".publicgroup").has(".shopwarning")) {
                    $("input[name='weixin_id']").parent(".publicgroup").find(".shopwarning").remove();
                }
                if ($("input[name='originalid']").parent(".publicgroup").has(".shopwarning")) {
                    $("input[name='originalid']").parent(".publicgroup").find(".shopwarning").remove();
                }
                if ($("input[name='appid']").parent(".publicgroup").has(".shopwarning")) {
                    $("input[name='appid']").parent(".publicgroup").find(".shopwarning").remove();
                }
                if ($("input[name='appsecret']").parent(".publicgroup").has(".shopwarning")) {
                    $("input[name='appsecret']").parent(".publicgroup").find(".shopwarning").remove();
                }
                if ($("input[name='token']").parent(".publicgroup").has(".shopwarning")) {
                    $("input[name='token']").parent(".publicgroup").find(".shopwarning").remove();
                }
                if ($("input[name='encodingaeskey']").parent(".publicgroup").has(".shopwarning")) {
                    $("input[name='encodingaeskey']").parent(".publicgroup").find(".shopwarning").remove();
                }
                if ($("textarea[name='subscribe_text']").parent(".publicgroup").has(".shopwarning")) {
                    $("textarea[name='subscribe_text']").parent(".publicgroup").find(".shopwarning").remove();
                }

                if ($("input[name='weixin_shop_num']").parent(".branch-list2").has(".shopwarning")) {
                    $("input[name='weixin_shop_num']").parent(".branch-list2").find(".shopwarning").remove();
                }
                if ($("input[name='weixin_api_key']").parent(".branch-list2").has(".shopwarning")) {
                    $("input[name='weixin_api_key']").parent(".branch-list2").find(".shopwarning").remove();
                }
                if ($("input[name='weixin_staff_account']").parent(".branch-list2").has(".shopwarning")) {
                    $("input[name='weixin_staff_account']").parent(".branch-list2").find(".shopwarning").remove();
                }
                if ($("textarea[name='weixin_apiclient_cert']").parent(".branch-list2").has(".shopwarning")) {
                    $("textarea[name='weixin_apiclient_cert']").parent(".branch-list2").find(".shopwarning").remove();
                }
                if ($("textarea[name='weixin_apiclient_key']").parent(".branch-list2").has(".shopwarning")) {
                    $("textarea[name='weixin_apiclient_key']").parent(".branch-list2").find(".shopwarning").remove();
                }
                
                var brand_name=$("input[name='brandname']").val();
                if (brand_name == "" || brand_name == null) {
                    var span = $("<div class='shopwarning'>请您输入品牌名称！</div>");
                    $("input[name='brandname']").parent(".branch-top").append(span);
                }
                var com_name=$("input[name='company_name']").val();
                if (com_name == "" || com_name == null) {
                    var span = $("<div class='shopwarning'>请输入公司名称!</div>");
                    $("input[name='company_name']").parent(".branch-list").append(span);
                }
                var con_name=$("input[name='contacter_name']").val();
                if (con_name == "" || con_name == null) {
                    var span = $("<div class='shopwarning'>请输入负责人姓名!</div>");
                    $("input[name='contacter_name']").parent(".branch-list").append(span);
                }
                var con_phone=$("input[name='contacter_phone']").val();
                if (con_phone == "" || con_phone == null) {
                    var span = $("<div class='shopwarning'>请输入正确的手机号码!</div>");
                    $("input[name='contacter_phone']").parent(".branch-list").append(span);
                }
                var pass_word=$("input[name='password']").val();
                if (pass_word == "" || pass_word == null) {
                    var span = $("<div class='shopwarning'>请您输入密码！</div>");
                    $("input[name='password']").parent(".branch-list").append(span);
                }
                var con_email=$("input[name='contacter_email']").val();
                //正则表达式验证邮箱
                var szReg = /^[A-Za-z0-9]+([-_.][A-Za-z0-9]+)*@([A-Za-z0-9]+[-.])+[A-Za-z0-9]{2,5}$/;
                var bChk = szReg.test(con_email);
                if (con_email == "" || con_email == null) {
                    var span = $("<div class='shopwarning'>请输入负责人邮箱!</div>");
                    $("input[name='contacter_email']").parent(".branch-list").append(span);
                }
                else if (!bChk) {
                    var span = $("<div class='shopwarning'>请按正确的邮箱格式输入邮箱!</div>");
                    $("input[name='contacter_email']").parent(".branch-list").append(span);
                }
                var con_qq=$("input[name='contacter_QQ']").val();
                if (con_qq == "" || con_qq == null) {
                    var span = $("<div class='shopwarning'>请您输入QQ号！</div>");
                    $("input[name='contacter_QQ']").parent(".branch-list").append(span);
                }
                var con_address=$("input[name='company_address_detail']").val();
                if (con_address == "" || con_address == null) {
                    var span = $("<div class='shopwarning'>请您输入详细地址！</div>");
                    $("input[name='company_address_detail']").parent(".branch-list").append(span);
                }
                if($('.province-1 option:selected').val()==0){
                    var span = $("<div class='shopwarning'>请您选择输入省！</div>");
                    $("select[name='company_province']").parent(".branch-list").append(span);
                    return false;
                }
                if($('.city-1 option:selected').val()==0){
                    var span = $("<div class='shopwarning'>请您选择输入市！</div>");
                    $("select[name='company_city']").parent(".branch-list").append(span);
                    return false;
                }
                if($('.distinct-1 option:selected').val()==0){
                    var span = $("<div class='shopwarning'>请您选择输入区！</div>");
                    $("select[name='company_district']").parent(".branch-list").append(span);
                    return false;
                }

                var name=$("input[name='name']").val();
                if (name == "" || name == null) {
                    var span = $("<div class='shopwarning'>请您输入公众号名称！</div>");
                    $("input[name='name']").parent(".publicgroup").append(span);
                }
                var weixinid=$("input[name='weixin_id']").val();
                if (weixinid == "" || weixinid == null) {
                    var span = $("<div class='shopwarning'>请您输入微信号！</div>");
                    $("input[name='weixin_id']").parent(".publicgroup").append(span);
                }
                var original_id=$("input[name='originalid']").val();
                if (original_id == "" || original_id == null) {
                    var span = $("<div class='shopwarning'>请您输入公众号原始ID！</div>");
                    $("input[name='originalid']").parent(".publicgroup").append(span);
                }
                var app_id=$("input[name='appid']").val();
                if (app_id == "" || app_id == null) {
                    var span = $("<div class='shopwarning'>请您输入公众号AppID！</div>");
                    $("input[name='appid']").parent(".publicgroup").append(span);
                }
                var app_secret=$("input[name='appsecret']").val();
                if (app_secret == "" || app_secret == null) {
                    var span = $("<div class='shopwarning'>请您输入公众号AppSecret！</div>");
                    $("input[name='appsecret']").parent(".publicgroup").append(span);
                }
                var token=$("input[name='token']").val();
                if (token == "" || token == null) {
                    var span = $("<div class='shopwarning'>请您输入公众号Token！</div>");
                    $("input[name='token']").parent(".publicgroup").append(span);
                }
                var encodingaeskey=$("input[name='encodingaeskey']").val();
                if (encodingaeskey == "" || encodingaeskey == null) {
                    var span = $("<div class='shopwarning'>请您输入公众号EncodingAESKey！</div>");
                    $("input[name='encodingaeskey']").parent(".publicgroup").append(span);
                }
                var subscribe_text=$("textarea[name='subscribe_text']").val();
                if (subscribe_text == "" || weixin_apiclientcert == null) {
                    var span = $("<div class='shopwarning'>请您输入欢迎语！</div>");
                    $("textarea[name='subscribe_text']").parent(".publicgroup").append(span);
                }

                var weixin_shopnum=$("input[name='weixin_shop_num']").val();
                if (weixin_shopnum == "" || weixin_shopnum == null) {
                    var span = $("<div class='shopwarning'>请您输入微信支付商户号！</div>");
                    $("input[name='weixin_shop_num']").parent(".branch-list2").append(span);
                }
                var weixin_apikey=$("input[name='weixin_api_key']").val();
                if (weixin_apikey == "" || weixin_apikey == null) {
                    var span = $("<div class='shopwarning'>请您输入API密钥！</div>");
                    $("input[name='weixin_api_key']").parent(".branch-list2").append(span);
                }else if(weixin_apikey.length!=32){
                    var span = $("<div class='shopwarning'>请您输入32位的API密钥！</div>");
                    $("input[name='weixin_api_key']").parent(".branch-list2").append(span);
                }
                /*var weixin_staffaccount=$("input[name='weixin_staff_account']").val();
                if (weixin_staffaccount == "" || weixin_staffaccount == null) {
                    var span = $("<div class='shopwarning'>请您输入员工登陆账号！</div>");
                    $("input[name='weixin_staff_account']").parent(".branch-list2").append(span);
                }*/
                var weixin_apiclientcert=$("textarea[name='weixin_apiclient_cert']").val();
                if (weixin_apiclientcert == "" || weixin_apiclientcert == null) {
                    var span = $("<div class='shopwarning'>请您输入商户微信公众号的ApiclientCert！</div>");
                    $("textarea[name='weixin_apiclient_cert']").parent(".branch-list2").append(span);
                }
                var weixin_apiclientkey=$("textarea[name='weixin_apiclient_key']").val();
                if (weixin_apiclientkey == "" || weixin_apiclientkey == null) {
                    var span = $("<div class='shopwarning'>请您输入商户微信公众号的ApiclientKey！</div>");
                    $("textarea[name='weixin_apiclient_key']").parent(".branch-list2").append(span);
                }

                if(brand_name&&com_name&&con_name&&con_phone&&pass_word&&con_email&&con_qq&&con_address&&name&&weixinid&&original_id&&app_id&&app_secret&&encodingaeskey&&token&&weixin_shopnum&&weixin_apikey&&weixin_apiclientcert&&weixin_apiclientkey&&subscribe_text){
                    return true;
                }else{
                    return false;
                }
            }
            $("input[name='brandname']").on("blur", function () {
                if ($("input[name='brandname']").parent(".branch-top").has(".shopwarning")) {
                    $("input[name='brandname']").parent(".branch-top").find(".shopwarning").remove();
                }
                var brand_name = $("input[name='brandname']").val();
                if (brand_name == "" || brand_name == null) {
                    var span = $("<div class='shopwarning'>请您输入品牌名称！</div>");
                    $("input[name='brandname']").parent(".branch-top").append(span);
                }
                else if ($("input[name='brandname']").parent(".branch-top").has(".shopwarning")) {
                    {
                        $("input[name='brandname']").parent(".branch-top").find(".shopwarning").remove();
                    }

                }
            });
            $("input[name='company_name']").on("blur", function () {
                if ($("input[name='company_name']").parent(".branch-list").has(".shopwarning")) {
                    $("input[name='company_name']").parent(".branch-list").find(".shopwarning").remove();
                }
                var com_name=$("input[name='company_name']").val();
                if (com_name == "" || com_name == null) {
                    var span = $("<div class='shopwarning'>请输入公司名称!</div>");
                    $("input[name='company_name']").parent(".branch-list").append(span);
                }
                else if ($("input[name='company_name']").parent(".branch-list").has(".shopwarning")) {
                    {
                        $("input[name='company_name']").parent(".branch-list").find(".shopwarning").remove();
                    }

                }
            });
            $("input[name='contacter_name']").on("blur", function () {
                if ($("input[name='contacter_name']").parent(".branch-list").has(".shopwarning")) {
                    $("input[name='contacter_name']").parent(".branch-list").find(".shopwarning").remove();
                }
                var con_name=$("input[name='contacter_name']").val();
                if (con_name == "" || con_name == null) {
                    var span = $("<div class='shopwarning'>请输入负责人姓名!</div>");
                    $("input[name='contacter_name']").parent(".branch-list").append(span);
                }
                else if ($("input[name='contacter_name']").parent(".branch-list").has(".shopwarning")) {
                    {
                        $("input[name='contacter_name']").parent(".branch-list").find(".shopwarning").remove();
                    }

                }
            });
            $("input[name='contacter_phone']").on("blur", function () {
                if ($("input[name='contacter_phone']").parent(".branch-list").has(".shopwarning")) {
                    $("input[name='contacter_phone']").parent(".branch-list").find(".shopwarning").remove();
                }
                var con_phone=$("input[name='contacter_phone']").val();
                if (con_phone == "" || con_phone == null) {
                    var span = $("<div class='shopwarning'>请输入正确的手机号码!</div>");
                    $("input[name='contacter_phone']").parent(".branch-list").append(span);
                }
                else if ($("input[name='contacter_phone']").parent(".branch-list").has(".shopwarning")) {
                    {
                        $("input[name='contacter_phone']").parent(".branch-list").find(".shopwarning").remove();
                    }

                }
            });
            $("input[name='password']").on("blur", function () {
                if ($("input[name='password']").parent(".branch-list").has(".shopwarning")) {
                    $("input[name='password']").parent(".branch-list").find(".shopwarning").remove();
                }
                var pass_word=$("input[name='password']").val();
                if (pass_word == "" || pass_word == null) {
                    var span = $("<div class='shopwarning'>请您输入密码！</div>");
                    $("input[name='password']").parent(".branch-list").append(span);
                }
                else if ($("input[name='password']").parent(".branch-list").has(".shopwarning")) {
                    {
                        $("input[name='password']").parent(".branch-list").find(".shopwarning").remove();
                    }

                }
            });
            $("input[name='contacter_email']").on("blur", function () {
                if ($("input[name='contacter_email']").parent(".branch-list").has(".shopwarning")) {
                    $("input[name='contacter_email']").parent(".branch-list").find(".shopwarning").remove();
                }
                var con_email=$("input[name='contacter_email']").val();
                //正则表达式验证邮箱
                var szReg = /^[A-Za-z0-9]+([-_.][A-Za-z0-9]+)*@([A-Za-z0-9]+[-.])+[A-Za-z0-9]{2,5}$/;
                var bChk = szReg.test(con_email);
                if (con_email == "" || con_email == null) {
                    var span = $("<div class='shopwarning'>请输入负责人邮箱!</div>");
                    $("input[name='contacter_email']").parent(".branch-list").append(span);
                }
                else if (!bChk) {
                    var span = $("<div class='shopwarning'>请按正确的邮箱格式输入邮箱!</div>");
                    $("input[name='contacter_email']").parent(".branch-list").append(span);
                }
                else if ($("input[name='contacter_email']").parent(".branch-list").has(".shopwarning")) {
                    {
                        $("input[name='contacter_email']").parent(".branch-list").find(".shopwarning").remove();
                    }

                }
            });
            $("input[name='contacter_QQ']").on("blur", function () {
                if ($("input[name='contacter_QQ']").parent(".branch-list").has(".shopwarning")) {
                    $("input[name='contacter_QQ']").parent(".branch-list").find(".shopwarning").remove();
                }
                var con_qq=$("input[name='contacter_QQ']").val();
                if (con_qq == "" || con_qq == null) {
                    var span = $("<div class='shopwarning'>请您输入QQ号！</div>");
                    $("input[name='contacter_QQ']").parent(".branch-list").append(span);
                }
                else if ($("input[name='contacter_QQ']").parent(".branch-list").has(".shopwarning")) {
                    {
                        $("input[name='contacter_QQ']").parent(".branch-list").find(".shopwarning").remove();
                    }

                }
            });
            $("input[name='company_address_detail']").on("blur", function () {
                if ($("input[name='company_address_detail']").parent(".branch-list").has(".shopwarning")) {
                    $("input[name='company_address_detail']").parent(".branch-list").find(".shopwarning").remove();
                }
                var con_address=$("input[name='company_address_detail']").val();
                if (con_address == "" || con_address == null) {
                    var span = $("<div class='shopwarning'>请您输入详细地址！</div>");
                    $("input[name='company_address_detail']").parent(".branch-list").append(span);
                }
                else if ($("input[name='company_address_detail']").parent(".branch-list").has(".shopwarning")) {
                    {
                        $("input[name='company_address_detail']").parent(".branch-list").find(".shopwarning").remove();
                    }

                }
            });
            $("select[name='company_province']").on("change", function () {
                if ($("select[name='company_province']").parent(".branch-list").has(".shopwarning")) {
                    $("select[name='company_province']").parent(".branch-list").find(".shopwarning").remove();
                }
                if($('.province-1 option:selected').val()==0){
                    var span = $("<div class='shopwarning'>请您选择输入省！</div>");
                    $("select[name='company_province']").parent(".branch-list").append(span);
                }
                else if ($("select[name='company_province']").parent(".branch-list").has(".shopwarning")) {
                    {
                        $("select[name='company_province']").parent(".branch-list").find(".shopwarning").remove();
                    }

                }
            });
            $("select[name='company_city']").on("change", function () {
                if ($("select[name='company_city']").parent(".branch-list").has(".shopwarning")) {
                    $("select[name='company_city']").parent(".branch-list").find(".shopwarning").remove();
                }
                if($('.city-1 option:selected').val()==0){
                    var span = $("<div class='shopwarning'>请您选择输入市！</div>");
                    $("select[name='company_city']").parent(".branch-list").append(span);
                }
                else if ($("select[name='company_city']").parent(".branch-list").has(".shopwarning")) {
                    {
                        $("select[name='company_city']").parent(".branch-list").find(".shopwarning").remove();
                    }

                }
            });
            $("select[name='company_district']").on("change", function () {
                if ($("select[name='company_district']").parent(".branch-list").has(".shopwarning")) {
                    $("select[name='company_district']").parent(".branch-list").find(".shopwarning").remove();
                }
                if($('.distinct-1 option:selected').val()==0){
                    var span = $("<div class='shopwarning'>请您选择输入区！</div>");
                    $("select[name='company_district']").parent(".branch-list").append(span);
                }
                else if ($("select[name='company_district']").parent(".branch-list").has(".shopwarning")) {
                    {
                        $("select[name='company_district']").parent(".branch-list").find(".shopwarning").remove();
                    }

                }
            });                                                                                               
            $("input[name='name']").on("blur", function () {
                if ($("input[name='name']").parent(".publicgroup").has(".shopwarning")) {
                    $("input[name='name']").parent(".publicgroup").find(".shopwarning").remove();
                }
                var name=$("input[name='name']").val();
                if (name == "" || name == null) {
                    var span = $("<div class='shopwarning'>请您输入公众号名称！</div>");
                    $("input[name='name']").parent(".publicgroup").append(span);
                }
                else if ($("input[name='name']").parent(".publicgroup").has(".shopwarning")) {
                    {
                        $("input[name='name']").parent(".publicgroup").find(".shopwarning").remove();
                    }

                }
            });
            $("input[name='weixin_id']").on("blur", function () {
                if ($("input[name='weixin_id']").parent(".publicgroup").has(".shopwarning")) {
                    $("input[name='weixin_id']").parent(".publicgroup").find(".shopwarning").remove();
                }
                var weixinid=$("input[name='weixin_id']").val();
                if (weixinid == "" || weixinid == null) {
                    var span = $("<div class='shopwarning'>请您输入微信号！</div>");
                    $("input[name='weixin_id']").parent(".publicgroup").append(span);
                }
                else if ($("input[name='weixin_id']").parent(".publicgroup").has(".shopwarning")) {
                    {
                        $("input[name='weixin_id']").parent(".publicgroup").find(".shopwarning").remove();
                    }

                }
            });
            $("input[name='originalid']").on("blur", function () {
                if ($("input[name='originalid']").parent(".publicgroup").has(".shopwarning")) {
                    $("input[name='originalid']").parent(".publicgroup").find(".shopwarning").remove();
                }
                var original_id=$("input[name='originalid']").val();
                if (original_id == "" || original_id == null) {
                    var span = $("<div class='shopwarning'>请您输入公众号原始ID！</div>");
                    $("input[name='originalid']").parent(".publicgroup").append(span);
                }
                else if ($("input[name='originalid']").parent(".publicgroup").has(".shopwarning")) {
                    {
                        $("input[name='originalid']").parent(".publicgroup").find(".shopwarning").remove();
                    }

                }
            });
            $("input[name='appid']").on("blur", function () {
                if ($("input[name='appid']").parent(".publicgroup").has(".shopwarning")) {
                    $("input[name='appid']").parent(".publicgroup").find(".shopwarning").remove();
                }
                var app_id=$("input[name='appid']").val();
                if (app_id == "" || app_id == null) {
                    var span = $("<div class='shopwarning'>请您输入公众号AppID！</div>");
                    $("input[name='appid']").parent(".publicgroup").append(span);
                }
                else if ($("input[name='appid']").parent(".publicgroup").has(".shopwarning")) {
                    {
                        $("input[name='appid']").parent(".publicgroup").find(".shopwarning").remove();
                    }

                }
            });
            $("input[name='appsecret']").on("blur", function () {
                if ($("input[name='appsecret']").parent(".publicgroup").has(".shopwarning")) {
                    $("input[name='appsecret']").parent(".publicgroup").find(".shopwarning").remove();
                }
                var app_secret=$("input[name='appsecret']").val();
                if (app_secret == "" || app_secret == null) {
                    var span = $("<div class='shopwarning'>请您输入公众号AppSecret！</div>");
                    $("input[name='appsecret']").parent(".publicgroup").append(span);
                }
                else if ($("input[name='appsecret']").parent(".publicgroup").has(".shopwarning")) {
                    {
                        $("input[name='appsecret']").parent(".publicgroup").find(".shopwarning").remove();
                    }

                }
            });
            $("input[name='token']").on("blur", function () {
                if ($("input[name='token']").parent(".publicgroup").has(".shopwarning")) {
                    $("input[name='token']").parent(".publicgroup").find(".shopwarning").remove();
                }
                var token=$("input[name='token']").val();
                if (token == "" || token == null) {
                    var span = $("<div class='shopwarning'>请您输入公众号Token！</div>");
                    $("input[name='token']").parent(".publicgroup").append(span);
                }
                else if ($("input[name='token']").parent(".publicgroup").has(".shopwarning")) {
                    {
                        $("input[name='token']").parent(".publicgroup").find(".shopwarning").remove();
                    }

                }
            });
            $("input[name='encodingaeskey']").on("blur", function () {
                if ($("input[name='encodingaeskey']").parent(".publicgroup").has(".shopwarning")) {
                    $("input[name='encodingaeskey']").parent(".publicgroup").find(".shopwarning").remove();
                }
                var encodingaeskey=$("input[name='encodingaeskey']").val();
                if (encodingaeskey == "" || encodingaeskey == null) {
                    var span = $("<div class='shopwarning'>请您输入公众号EncodingAESKey！</div>");
                    $("input[name='encodingaeskey']").parent(".publicgroup").append(span);
                }
                else if ($("input[name='encodingaeskey']").parent(".publicgroup").has(".shopwarning")) {
                    {
                        $("input[name='encodingaeskey']").parent(".publicgroup").find(".shopwarning").remove();
                    }

                }
            });
            $("textarea[name='subscribe_text']").on("blur", function () {
                if ($("textarea[name='subscribe_text']").parent(".publicgroup").has(".shopwarning")) {
                    $("textarea[name='subscribe_text']").parent(".publicgroup").find(".shopwarning").remove();
                }
                var subscribe_text=$("textarea[name='subscribe_text']").val();
                if (subscribe_text == "" || weixin_apiclientcert == null) {
                    var span = $("<div class='shopwarning'>请您输入欢迎语！</div>");
                    $("textarea[name='subscribe_text']").parent(".publicgroup").append(span);
                }
                else if ($("textarea[name='subscribe_text']").parent(".publicgroup").has(".shopwarning")) {
                    {
                        $("textarea[name='subscribe_text']").parent(".publicgroup").find(".shopwarning").remove();
                    }

                }
            });
                                                               
            $("input[name='weixin_shop_num']").on("blur", function () {
                if ($("input[name='weixin_shop_num']").parent(".branch-list2").has(".shopwarning")) {
                    $("input[name='weixin_shop_num']").parent(".branch-list2").find(".shopwarning").remove();
                }
                var weixin_shopnum=$("input[name='weixin_shop_num']").val();
                if (weixin_shopnum == "" || weixin_shopnum == null) {
                    var span = $("<div class='shopwarning'>请您输入微信支付商户号！</div>");
                    $("input[name='weixin_shop_num']").parent(".branch-list2").append(span);
                }
                else if ($("input[name='weixin_shop_num']").parent(".branch-list2").has(".shopwarning")) {
                    {
                        $("input[name='weixin_shop_num']").parent(".branch-list2").find(".shopwarning").remove();
                    }

                }
            });
            $("input[name='weixin_api_key']").on("blur", function () {
                if ($("input[name='weixin_api_key']").parent(".branch-list2").has(".shopwarning")) {
                    $("input[name='weixin_api_key']").parent(".branch-list2").find(".shopwarning").remove();
                }
                var weixin_apikey=$("input[name='weixin_api_key']").val();
                if (weixin_apikey == "" || weixin_apikey == null) {
                    var span = $("<div class='shopwarning'>请您输入API密钥！</div>");
                    $("input[name='weixin_api_key']").parent(".branch-list2").append(span);
                }else if(weixin_apikey.length!=32){
                    var span = $("<div class='shopwarning'>请您输入32位的API密钥！</div>");
                    $("input[name='weixin_api_key']").parent(".branch-list2").append(span);
                }
                else if ($("input[name='weixin_api_key']").parent(".branch-list2").has(".shopwarning")) {
                    {
                        $("input[name='weixin_api_key']").parent(".branch-list2").find(".shopwarning").remove();
                    }

                }
            });
            $("input[name='weixin_staff_account']").on("blur", function () {
                if ($("input[name='weixin_staff_account']").parent(".branch-list2").has(".shopwarning")) {
                    $("input[name='weixin_staff_account']").parent(".branch-list2").find(".shopwarning").remove();
                }
                var weixin_staffaccount=$("input[name='weixin_staff_account']").val();
                if (weixin_staffaccount == "" || weixin_staffaccount == null) {
                    var span = $("<div class='shopwarning'>请您输入员工登陆账号！</div>");
                    $("input[name='weixin_staff_account']").parent(".branch-list2").append(span);
                }
                else if ($("input[name='weixin_staff_account']").parent(".branch-list2").has(".shopwarning")) {
                    {
                        $("input[name='weixin_staff_account']").parent(".branch-list2").find(".shopwarning").remove();
                    }

                }
            });
            $("textarea[name='weixin_apiclient_cert']").on("blur", function () {
                if ($("textarea[name='weixin_apiclient_cert']").parent(".branch-list2").has(".shopwarning")) {
                    $("textarea[name='weixin_apiclient_cert']").parent(".branch-list2").find(".shopwarning").remove();
                }
                var weixin_apiclientcert=$("textarea[name='weixin_apiclient_cert']").val();
                if (weixin_apiclientcert == "" || weixin_apiclientcert == null) {
                    var span = $("<div class='shopwarning'>请您输入商户微信公众号的ApiclientCert！</div>");
                    $("textarea[name='weixin_apiclient_cert']").parent(".branch-list2").append(span);
                }
                else if ($("textarea[name='weixin_apiclient_cert']").parent(".branch-list2").has(".shopwarning")) {
                    {
                        $("textarea[name='weixin_apiclient_cert']").parent(".branch-list2").find(".shopwarning").remove();
                    }

                }
            });
            $("textarea[name='weixin_apiclient_key']").on("blur", function () {
                if ($("textarea[name='weixin_apiclient_key']").parent(".branch-list2").has(".shopwarning")) {
                    $("textarea[name='weixin_apiclient_key']").parent(".branch-list2").find(".shopwarning").remove();
                }
                var weixin_apiclientkey=$("textarea[name='weixin_apiclient_key']").val();
                if (weixin_apiclientkey == "" || weixin_apiclientkey == null) {
                    var span = $("<div class='shopwarning'>请您输入商户微信公众号的ApiclientKey！</div>");
                    $("textarea[name='weixin_apiclient_key']").parent(".branch-list2").append(span);
                }
                else if ($("textarea[name='weixin_apiclient_key']").parent(".branch-list2").has(".shopwarning")) {
                    {
                        $("textarea[name='weixin_apiclient_key']").parent(".branch-list2").find(".shopwarning").remove();
                    }

                }
            });
</script>
@endsection