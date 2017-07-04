<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShopTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //店铺信息
        Schema::create('shopinfo', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('brand_id');//店铺所属品牌id
            $table->string('shopname');//店铺名称
            $table->string('customer_service_phone');//客服电话
            $table->tinyInteger('open_weishop');
            
            $table->string('shoplogo');//店铺logo
            $table->string('app_id');//ping++参数
            $table->string('api_key');//ping++参数
            
            $table->string('contacter_name',60);//店铺联系人
            $table->string('contacter_phone',20);//店铺联系人电话
            $table->string('contacter_email',50);//店铺联系人邮箱
            $table->string('contacter_QQ',20);//店铺联系人QQ号
            /*
            $table->string('weixin_shop_num',100);//微信商户号（支付）
            $table->string('weixin_api_key',100);//微信api密钥（支付）
            $table->string('weixin_staff_account',100);//微信账号（支付）
            $table->string('weixin_apiclient_cert',100);//微信账号（支付）
            $table->string('weixin_apiclient_key',100);//微信账号（支付）
            $table->string('zhifubao_pid',100);//支付宝账号
            $table->string('zhifubao_appid',100);//支付宝账号
            $table->string('zhifubao_public_key',100);//支付宝账号
            $table->string('zhifubao_private_key',100);//支付宝账号
            */
            $table->string('shop_province',30);//店铺省份
            $table->string('shop_city',30);//店铺城市
            $table->string('shop_district',30);//店铺所在区
            $table->string('shop_address_detail');//店铺详细地址
            $table->decimal('latitude',10,6);//纬度
            $table->decimal('longitude',10,6);//经度
            $table->string('special');//商家推荐。。
            $table->string('open_at',10);//营业时间
            $table->string('close_at',10);
            $table->tinyInteger('status');//0禁用 1正常 2休息。。。
            $table->integer('status_at');//0禁用 1正常 2休息。。。

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
        Schema::drop('shopinfo');
    }
}
