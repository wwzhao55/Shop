
<div class="siderbar">
	@if(Auth::user()->role == 0)
	<div class="side-list" id="menu-lists-parent" >
		<a href="/Admin/datacenter" class="list-item datacenter">
		    	数据中心
		</a>
		<a href="/Admin/brandmanage" class="list-item brandmanage">品牌管理</a>
		<!-- <a  class="list-item"  data-target="#app-manage"  data-toggle="collapse" data-parent="#menu-lists-parent">App管理</a>
	      <ul id="app-manage" class="panel-collapse">
			<li  class="menu-cell-li">
		    <a  href="/Admin/App/startlogo" >启动图片</a>
			</li>
			<li  class="menu-cell-li">
		    <a href="/Admin/App/advertisement" >广告图片</a>
			</li>
			<li class="menu-cell-li">
		   <a href="/Admin/App/theme">主题模板</a>
		   </li>
		   <li class="menu-cell-li">
		  <a href="/Admin/App/material" >素材管理</a>
		   </li>
		</ul>	 -->
	
	</div>

	@elseif(Auth::user()->role == 1)
	<div class="side-list">
	  	<a href="/Brand/datacenter" class="list-item datacenterlist">数据中心</a>
	  	<a href="/Brand/publicmanage" class="list-item publicmanage-new">公众号管理</a>
	  	<a href="/Brand/shopmanage" class="list-item shopmanage">分店管理</a>
	  	<a href="/Brand/staffmanage/index" class="list-item staffmanage">员工账号</a>
	  	<a  class="list-item commoditymanage" id="commodity_a"  data-toggle="collapse" data-target="#commodity-manage" aria-expanded="false" aria-controls="commodity-manage">商品管理</a>
		    <ul id="commodity-manage" class="collapse">
				<li class="menu-cell-li" >
			        <a href="/Brand/commodity" class="list-item-a maintenance">商品维护</a>
				</li>
				<li class="menu-cell-li">
			        <a href="/Brand/group" class="list-item-a commoditygroup">商品分组</a>
			   </li>			 
			</ul>
		<a  class="list-item weixinmanage" id="weixin_a"  data-toggle="collapse" data-target="#wexin-manage" aria-expanded="false" aria-controls="wexin-manage">营销管理</a>
		    <ul id="wexin-manage" class="collapse">
				<li  class="menu-cell-li">
			        <a href="/Brand/shuffling/index/0" class="list-item-a shuffingmanage">微店广告图管理</a>
				</li>
				<li class="menu-cell-li">
			        <a href="/Brand/coupon" class="list-item-a coupon">优惠券管理</a>
			   </li>			 
			</ul>
	  		  		  	
	  	
	  	<!--
	  	<a href="/Brand/app" class="list-item">App设置</a>
	  	-->
	</div>

	@elseif(Auth::user()->role == 2)
	<div class="side-list">
		<a href="/Shopadmin/staffmanage" class="list-item">员工账号</a>
		<a href="/Shopadmin/datacenter" class="list-item">数据中心</a>  	
	</div>
	
	@elseif(Auth::user()->role == 3)
	<div class="side-list">
	    <a href="/Shopstaff/weborder" class="list-item order-active">订单管理</a>
	  	<a href="/Shopstaff/commodity" class="list-item stock-active">商品库存</a>
	</div>
	@endif
</div>
