<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBrandTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //品牌信息和账号（既时品牌信息，同时也是品牌账号，与店铺的有区别，因为店铺有两种账号类型，不能将店铺信息和账号表合并）
        Schema::create('brand', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('uid');//uid 对应users表中的id（认证时使用）
            $table->string('brandname',200);//品牌名称
            $table->string('brandlogo');
            $table->string('main_business',100);//品牌主营

            $table->string('contacter_name',60);//联系人姓名
            $table->string('contacter_phone',20);//联系人电话
            $table->string('contacter_email',50);//联系人邮箱
            $table->string('contacter_QQ',20);//联系人QQ号
            
            $table->string('company_name',100);//公司名称
            $table->string('company_province',30);//公司省份
            $table->string('company_city',30);//公司城市
            $table->string('company_district',30);//公司所在区
            $table->string('company_address_detail');//公司详细地址

            $table->string('weixin_account');
            $table->string('weixin_appid');
            $table->string('weixin_appsecret');
            $table->string('weixin_shop_num',100);//微信商户号（支付）
            $table->string('weixin_api_key',100);//微信api密钥（支付）
            $table->string('weixin_staff_account',100);//微信员工登录账号（支付）
            $table->string('weixin_apiclient_cert',100);//微信账号证书（支付）
            //$table->string('weixin_apiclient_key',100);//微信账号证书（支付）

            $table->string('zhifubao_pid',100);//支付宝账号
            $table->string('zhifubao_appid',100);//支付宝账号
            $table->string('zhifubao_public_key',100);//支付宝账号
            $table->string('zhifubao_private_key',100);//支付宝账号
            /*
            $table->string('weixin');
            $table->string('weixin_password');
            */
            $table->tinyInteger('status');//账号状态
            $table->integer('created_at');
            $table->integer('updated_at');
            //$table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::drop('brand');
    }
}
