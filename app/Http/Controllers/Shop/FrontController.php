<?php
	/**
		@author hetutu
		@modify 07.20
	**/
	namespace App\Http\Controllers\Shop;
	use App\Http\Controllers\Controller,Illuminate\Http\Request;
	use View,Session,Auth,DB,Response,Redirect,Validator,URL;
	use App\Models\Shop\Shuffling,App\Models\Shop\Shopinfo,App\Models\Weixin\Account;
	use App\Models\Customer\Customer,App\Models\User,App\Models\Brand\Brand,App\Models\Commodity\Commodity,App\Models\Commodity\Shopcommodity,App\Models\Commodity\Shopcart;
	use App\Models\Search\Search,App\Models\Search\Searchlist,EasyWeChat\Foundation\Application;
	//use App\libraries\Wxpay\lib\WxPayConfig;

	class FrontController extends CommonController{

		//首页 要求输入brand_id shop_id
		public function getIndex(Request $request){
			if(Session::has('coupon_user')){
				Session::forget('coupon_user');
			}
			if( $request->has('b')) {
				$this->brand_id = $request->b;
				$this->brand_name = Brand::where('id',$this->brand_id)->first()->brandname;
				Session::put('brand_id', $this->brand_id);
	        	Session::put('brand_name', $this->brand_name);
			}else{
				if(!Session::has('brand_id')){
					$this->brand_id =1;
					$this->brand_name = Brand::where('id',$this->brand_id)->first()->brandname;
					Session::put('brand_id', $this->brand_id);
		        	Session::put('brand_name', $this->brand_name);
				}	
			}	
			if($request->has('s')){
				$this->shop_id = $request->s;
				Session::put('shop_id',$this->shop_id);
			}else{
				if(!Session::has('shop_id')){
					$is_open=Shopinfo::where('brand_id',$this->brand_id)->where('status',1)->where('open_weishop',1)->first();
					if($is_open){
						$this->shop_id = $is_open->id;
		        		Session::put('shop_id',$this->shop_id);
					}else{
						return Redirect::to('/shop/front/rest');
					}	
				}			
			}
			$account = Account::where('brand_id',$this->brand_id)->first();
			$options = [
			    'debug'  => true,
			    'app_id' => $account->appid,
			    'secret' => $account->appsecret,
			    'token'  => $account->token,
			    'aes_key' =>$account->encodingaeskey, // 可选
			    'log' => [
			        'level' => 'debug',
			        'file'  => storage_path('logs/easywechat.log'), // XXX: 绝对路径！！！！
			    ],
			    'oauth' => [
				     'scopes'   => ['snsapi_userinfo'],
				     'callback' => '/shop/front/checkoauth/'.$this->brand_id.'/'.$this->shop_id.'/index',
				],
			];
			$app = new Application($options);
			$js = $app->js;
			/*$oauth = $app->oauth;
			if($request->has('from')){
				return $oauth->redirect();
			}*/
			/*if((!$this->brand_id) || (!$this->shop_id)){
				return Redirect::to('/shop/error');
			}*/


			$visit_shop = Shopinfo::find($this->shop_id);
			if(($visit_shop->status == 0) || ($visit_shop->open_weishop == 0)){
				//没开微店或店铺休息
				return Redirect::to('/shop/front/rest');
			}
			$publicPath = public_path();
			if($this->openid){
				$customer_id = DB::table($this->brand_name.'_customers')->where('openid',$this->openid)->value('id');
			}

			//获取轮播图片
			$shufflings = DB::table('shuffling')->where('brand_id',$this->brand_id)->where(function($query){
				$query->where('shop_id',$this->shop_id)->orWhere('shop_id',0);
			})->where('status',1)->orderBy('order','asc')->select('img_src','http_src')->get();

			//获取商品分类
			$group = DB::table($this->brand_name.'_group')->where('status',1)->where(function($query){
				$query->where('shop_id',$this->shop_id)->orWhere('shop_id',0);
			})->orderBy('name','asc')->select('id','name')->get();//商品分类
			//删掉空的分类
			$group = array_where($group, function ($a, $b) {
				$hasCommodity = DB::table($this->brand_name.'_commodity')
							->join($this->brand_name.'_shop_commodity',$this->brand_name.'_commodity.id','=',$this->brand_name.'_shop_commodity.commodity_id')
							->where($this->brand_name.'_shop_commodity.shop_id',$this->shop_id)
							->where('group_id',$b->id)->where($this->brand_name.'_commodity.status',1)->where($this->brand_name.'_shop_commodity.status',1)->count();
			   return $hasCommodity>0;
			});
			$commoditys = array();
			if(count($group)>0){
				foreach ($group as $key => $g) {
					if($g->id){
						$per = 6;
						$commodityTemp = DB::table($this->brand_name.'_commodity')
									->join($this->brand_name.'_shop_commodity',$this->brand_name.'_commodity.id','=',$this->brand_name.'_shop_commodity.commodity_id')
									->where($this->brand_name.'_shop_commodity.shop_id',$this->shop_id)
									->where($this->brand_name.'_commodity.group_id',$g->id)
									->where($this->brand_name.'_commodity.status',1)
									->where($this->brand_name.'_shop_commodity.status',1)
									->select($this->brand_name.'_commodity.id',$this->brand_name.'_commodity.commodity_name',$this->brand_name.'_commodity.main_img',$this->brand_name.'_commodity.group_id',$this->brand_name.'_commodity.group_name',$this->brand_name.'_commodity.base_price')
									->take($per+1)->get();
						foreach ($commodityTemp as $k => $v) {
							$v->base_price = number_format($v->base_price,2,'.','');
						}
						if(count($commodityTemp) > $per){
							$commoditys[$g->name]['finish'] = false;
							array_pop($commodityTemp);
						}else{
							$commoditys[$g->name]['finish'] = true;
						}
						$commoditys[$g->name]['commodity'] = $commodityTemp;
					}
				}	
			}else{
				$group = [];
				$finish = true;
			}	
			$groupjson = json_encode($group);
			$pad = count($group)%5;
			if($pad>0){
				$group = array_pad($group,count($group)+(5-$pad),(object)array(
				'id'=>'',
				'name'=>''));
			}	
			$groupCollection = collect($group);
			$group = $groupCollection->chunk(5)->toArray();

			$shop = Shopinfo::find($this->shop_id);
			$shopaddress = $shop->shop_province.$shop->shop_city.$shop->shop_district.$shop->shop_address_detail;
			$shopaddress = str_replace('市辖区','',$shopaddress);

			if(DB::table($this->brand_name."_coupon")->where('status',1)->where('validity_end','>=',time())->count()>0){
				$coupons = true;
			}else{
				$coupons = false;
			}
			$search_arr = $this->searchlist();

			
			//	var_dump($commoditys);
			return View::make('shop.front.index',array(
				'shufflings' => $shufflings,//轮播图
				'tags' => $group,//切换的标签
				'tagjson' => $groupjson,//js会用到的json格式
				'commoditys' => $commoditys,
				//'finish' => $finish,//是否加载结束
				'shopaddress' => $shopaddress,
				'coupons' => $coupons,//是否有优惠券
				//'shopcart' => $shopcart,//购物车有值否
				'recentSearch' => $search_arr['recentSearch'],//最近搜索
				'hotSearch' => $search_arr['hotSearch'],//热门搜索
				'js' => $js
				));
		}

		//获取商品
		public function getProduct($type){
			$commoditys = [];
			$group = DB::table($this->brand_name.'_group')->find((int)$type);//商品分类
			if($group){
				$commodityTemp = DB::table($this->brand_name.'_commodity')
								->join($this->brand_name.'_shop_commodity',$this->brand_name.'_commodity.id','=',$this->brand_name.'_shop_commodity.commodity_id')
								->where($this->brand_name.'_shop_commodity.shop_id',$this->shop_id)
								->where($this->brand_name.'_commodity.group_id',$group->id)
								->where($this->brand_name.'_commodity.status',1)
								->where($this->brand_name.'_shop_commodity.status',1)
								->select($this->brand_name.'_commodity.id',$this->brand_name.'_commodity.commodity_name',$this->brand_name.'_commodity.main_img',$this->brand_name.'_commodity.group_id',$this->brand_name.'_commodity.group_name',$this->brand_name.'_commodity.base_price as price')
								->get();							
				foreach ($commodityTemp as $k => $value) {
					//多规格时首页显示商品最低价格 
					$value->price = number_format($value->price,2,'.','');				
				}
				$commoditys = $commodityTemp;
			}else{
				return Redirect::back();
			}	
			return View::make('shop.front.product',array('commodity' => $commoditys));
		}

		public function getBranch(){
			if(!isset($this->brand_id)){
				return Redirect::to('/shop/error');
			}
			$shoplists = array();
			$shoplist = Shopinfo::where('brand_id',$this->brand_id)->where('status',1)->where('open_weishop',1)->get();
			
			if($shoplist){
				foreach ($shoplist as $shop) {
					if($shop->id == $this->shop_id){
						$active = 1;
					}else{
						$active = 0;
					}
					$a = array(
						'id' => $shop->id,
						'name' => $shop->shopname,
						'province' => $shop->shop_province,
						'city' => $shop->shop_city,
						'district' => $shop->shop_district,
						'address' => $shop->shop_address_detail,
						'active' => $active
						);
					array_push($shoplists,$a);
				}
			}else{
				//没开微店或店铺休息
				return Redirect::to('/shop/front/rest');
			}
			$account = Account::where('brand_id',$this->brand_id)->first();
			$options = [
			    'debug'  => true,
			    'app_id' => $account->appid,
			    'secret' => $account->appsecret,
			    'token'  => $account->token,
			    'aes_key' =>$account->encodingaeskey, // 可选
			    'log' => [
			        'level' => 'debug',
			        'file'  => storage_path('logs/easywechat.log'), // XXX: 绝对路径！！！！
			    ],
			];
			$app = new Application($options);
			$js = $app->js;
			//return $shoplists;
			$shop = Shopinfo::find($this->shop_id);
			$shopaddress = $shop->shop_province.$shop->shop_city.$shop->shop_district.$shop->shop_address_detail;
			$shopaddress = str_replace('市辖区','',$shopaddress);
			return View::make('shop.front.branch',array('shoplists' => $shoplists,'js'=>$js,'shopaddress'=>$shopaddress));
		}

		public function postBranch(Request $request){
			if(!$this->brand_id){
				return Response::json(['status'=>'error','msg'=>'缺少品牌参数']);//特殊情况，跳转至默认
			}
			$validator = Validator::make($request->all(), [
	            'shop_id' => 'required|integer',
	        ]);
	        if ($validator->fails()) {
	            return Response::json(['status' => 'error','msg' => '缺少店铺参数']);
	        }
			//切换分店
			$newshop_id =$request->shop_id;
			//分店是否存在
			$has_shop = Shopinfo::where('brand_id',$this->brand_id)->where('id',$newshop_id)->where('status',1)->where('open_weishop',1)->count();
			if($has_shop){
				if($this->openid){
					$customer = new Customer;
					$customer->setTable($this->brand_name.'_customers');
					$customer->where('openid',$this->openid)->update(['shop_id' => $newshop_id]);
				}			
				Session::forget('shop_id');
				Session::put('shop_id',$newshop_id);
				$this->shop_id = $newshop_id;
				return Response::json(['status'=>'success','msg'=>'切换成功']);
			}else{
				return Response::json(['status'=>'error','msg'=>'店铺已打烊']);
			}
		}

		private function searchlist(){
			$recentSearch = array();
			$hotSearch = array();
			if($this->openid){
				$Customer = new Customer;
				$Customer->setTable($this->brand_name.'_customers');
				$customer_id = $Customer->where('openid',$this->openid)->first()->id;

				$Searchlist = new Searchlist;
				$Searchlist->setTable($this->brand_name.'_search_list');
			    $lists = $Searchlist->where('customer_id',$customer_id)->orderBy('created_at','desc')->take(10)->lists('search_id');
				if(!empty($lists)){
					//有搜索记录，获取最近搜索
					$Content = new Search;
					$Content->setTable($this->brand_name.'_search');
					foreach ($lists as $id) {
						$content = $Content->find($id)->content;
						array_push($recentSearch, $content);
					}
				}
			}else{
				if(isset( $_COOKIE['search']) && ($_COOKIE['search']!='null')){
					$recentSearch = json_decode($_COOKIE['search'],true);
				}
			}
			
			//获取热门搜索
			$hot = array();
			$Hot = new Search;
			$Hot->setTable($this->brand_name.'_search');
			$count = $Hot->count();
			if($count && ($count<=12)){
				//获取全部
				$hot = $Hot->orderBy('times','desc')->get();
			}else if($count>12){
				$hot = $Hot->orderBy('times','desc')->take(12)->get();
			}
			foreach ($hot as $value) {
				array_push($hotSearch,$value->content);
			}
			$hotSearch = array_unique($hotSearch);
			$recentSearch = array_unique($recentSearch);
			
			return array(
				'recentSearch' => $recentSearch,//最近搜索
				'hotSearch' => $hotSearch,//热门搜索
				);
		}

		public function postSearch(Request $request){
			$validator = Validator::make($request->all(), [
	            'content' => 'required',
	        ]);
	        if ($validator->fails()) {
	            return Response::json(['status' => 'error','msg' => '参数有误']);
	        }
			if($this->openid){
				$customer_id = DB::table($this->brand_name.'_customers')->where('openid',$this->openid)->value('id');
			}else{
				$customer_id = 0;
			}
			$content = $request->content;
			$content_arr = explode(" ",$content);
			//若没有登录，将搜索记录存入cookie
			if($customer_id == 0){
				$search_cookie = (isset( $_COOKIE['search']) && ($_COOKIE['search']!='null'))? json_decode($_COOKIE['search'],true):array();
				array_push($search_cookie,$content);
				$search_cookie = array_flip(array_flip($search_cookie));
				setcookie('search',json_encode($search_cookie),0);
			}
			//搜索记录存入数据库
			$Old_count = DB::table($this->brand_name.'_search')->where('content',$content)->count();
			if($Old_count){
				//已有搜索记录，times++
        		DB::beginTransaction();
        		try{
        			$old_search = new Search;
        			$old_search->setTable($this->brand_name.'_search');
				    $old_search->where('content',$content)->increment('times');
				    $old_id = DB::table($this->brand_name.'_search')->where('content',$content)->value('id');
				    if(DB::table($this->brand_name.'_search_list')->where('search_id',$old_id)->where('customer_id',$customer_id)->count() == 0){
				    	//顾客没有搜索过
				    	$list = new Searchlist;
					    $list->setTable($this->brand_name.'_search_list');
					    $list->search_id = $old_id;
					    $list->customer_id = $customer_id;
					    $list->save();
				    }else{
				    	//更新搜索时间戳
				    	DB::table($this->brand_name.'_search_list')->where('search_id',$old_id)->where('customer_id',$customer_id)->update(['updated_at' => time()]);
				    }	    
				    DB::commit();
        		}catch (Exception $e){
		            DB::rollback();
		            return Response::json(['status' => 'error','msg' => '出错啦，请稍后再试']);
		        }
			}else{
				//新增搜索记录
				DB::beginTransaction();
        		try{
        			$old_search = new Search;
        			$old_search->setTable($this->brand_name.'_search');
				    $old_search->content = $content;
				    $old_search->times = 1;
				    $old_search->save();
				    $search_id = $old_search->id;

				    $list = new Searchlist;
				    $list->setTable($this->brand_name.'_search_list');
				   // $list->create(['search_id' => $search_id, 'customer_id' => $customer_id]);
				    $list->search_id = $search_id;
				    $list->customer_id = $customer_id;
				    $list->save();
				    DB::commit();
        		}catch (Exception $e){
		            DB::rollback();
		            return Response::json(['status' => 'error','msg' => '出错啦，请稍后再试']);
		        }
			}
			//返回搜索结果
			$Sql = new Commodity;
			$Sql->setTable($this->brand_name.'_commodity');
			foreach ($content_arr as $key => $word) {
				$Sql = $Sql->where('status',1)->where(function($query) use($word){
									$query->where('group_name','like','%'.$word.'%')
								->orWhere('commodity_name','like','%'.$word.'%');
								});
			}
			$sql = $Sql->select('id','commodity_name','main_img','group_id','group_name','base_price as price')->get();
			$resultArr = $sql->filter(function($item){
				$Shopcom = new Shopcommodity;
				$Shopcom->setTable($this->brand_name.'_shop_commodity');
				$shopcom = $Shopcom->where('shop_id',$this->shop_id)->where('commodity_id',$item->id)->first();
				return $shopcom->status == 1;
			});
			 //var_dump($resultArr);
			foreach ($resultArr as $key => $value) {
				//多规格时首页显示商品最低价格
				$value->price = number_format($value->price,2,'.','');
			}
			return Response::json(['status' => 'success','msg' => $resultArr]);

		}

		public function postDelsearch(){
			if($this->openid){
				$customer_id = DB::table($this->brand_name.'_customers')->where('openid',$this->openid)->value('id');
				if(DB::table($this->brand_name.'_search_list')->where('customer_id',$customer_id)->count()){
					$num = DB::table($this->brand_name.'_search_list')->where('customer_id',$customer_id)->delete();
					if($num){
						return Response::json(['status'=>'success','msg'=>'删除成功']);
					}else{
						return Response::json(['status'=>'error','msg'=>'删除失败']);
					}
				}else{
					return Response::json(['status'=>'error','msg'=>'没有找到搜索记录']);
				}
			}else{
				//用户未登录，删除cookie
				setcookie('search','',time()-3600);
				return Response::json(['status'=>'success','msg'=>'删除成功']);
			}
			
		}

		public function getFocus(){
			$account = Account::where('brand_id',$this->brand_id)->first();
			$options = [
			    'debug'  => true,
			    'app_id' => $account->appid,
			    'secret' => $account->appsecret,
			    'token'  => $account->token,
			    'aes_key' =>$account->encodingaeskey, // 可选
			    'log' => [
			        'level' => 'debug',
			        'file'  => storage_path('logs/easywechat.log'), // XXX: 绝对路径！！！！
			    ],
			];
			$app = new Application($options);
			$js = $app->js;
			$shop = Shopinfo::find($this->shop_id);
			$shopaddress = $shop->shop_province.$shop->shop_city.$shop->shop_district.$shop->shop_address_detail;
			$shopaddress = str_replace('市辖区','',$shopaddress);
			return View::make('shop.front.focus',array('account'=>$account->weixin_id,'name'=>$account->name,'js'=>$js,'shopaddress'=>$shopaddress));
		}

		public function getDetail(Request $request){
			if($request->has('b')){
				$this->brand_id = $request->b;
				$this->brand_name = Brand::where('id',$this->brand_id)->first()->brandname;
				Session::put('brand_id', $this->brand_id);
		        Session::put('brand_name', $this->brand_name);
			}else{
				if(!isset($this->brand_id)){
					return Redirect::to('/shop/error');//特殊情况，跳转至默认
				}
			}
			if($request->has('shop_id')){
				$temp_shop = Shopinfo::find($request->shop_id);
				if(($temp_shop->status == 0) || ($temp_shop->open_weishop == 0)){
					return Redirect::to('/shop/front/rest');
				}
				$this->shop_id = $request->shop_id;
				Session::put('shop_id',$this->shop_id);
			}
			$account = Account::where('brand_id',$this->brand_id)->first();
			$options = [
			    'debug'  => true,
			    'app_id' => $account->appid,
			    'secret' => $account->appsecret,
			    'token'  => $account->token,
			    'aes_key' =>$account->encodingaeskey, // 可选
			    'log' => [
			        'level' => 'debug',
			        'file'  => storage_path('logs/easywechat.log'), // XXX: 绝对路径！！！！
			    ],
			    'oauth' => [
				     'scopes'   => ['snsapi_userinfo'],
				     'callback' => '/shop/front/checkoauth/'.$this->brand_id.'/'.$this->shop_id.'/'.$request->commodity_id,
				],
			];
			$app = new Application($options);
			$js = $app->js;
			/*$oauth = $app->oauth;
			if($request->has('from')){
				return $oauth->redirect();
			}*/
			$commodity_id = $request->commodity_id;
			//增加页面访问量
			$Visit = new Commodity;
			$Visit->setTable($this->brand_name.'_commodity');
			$visit = $Visit->find($commodity_id);
			if(!$visit || ($visit->status !=1)){
				return Redirect::action('Shop\FrontController@getIndex',['b'=>$this->brand_id]);//特殊情况，跳转至默认

			}
			$visit->setTable($this->brand_name.'_commodity');
			$visit->PV = $visit->PV+1;
			//用户访问	
			if($this->openid){
				$customer_id = DB::table($this->brand_name.'_customers')->where('openid',$this->openid)->value('id');
				$old_visit = DB::table($this->brand_name.'_commodity_customer')->where('commodity_id',$commodity_id)->where('customer_id',$customer_id)->first();
				if(count($old_visit)){
					DB::table($this->brand_name.'_commodity_customer')->where('commodity_id',$commodity_id)->where('customer_id',$customer_id)->increment('count',1,['updated_at' => time()]);
					if( $old_visit->updated_at + 24*60*60 < time() ){
						//24小时内未访问过		
						$visit->UV = $visit->UV+1;
					}
				}else{
					//该用户第一次访问该商品
					$created_at = time(); 
					DB::table($this->brand_name.'_commodity_customer')->insert(
					    ['commodity_id' => $commodity_id, 'customer_id' => $customer_id, 'count' => 1,'created_at' => $created_at, 'updated_at' => $created_at]);
					$visit->UV = $visit->UV+1;
				}
			}
			$visit->save();
			//获取商品信息
			$detail = DB::table($this->brand_name.'_commodity')
							->where('id',$commodity_id)
							->select($this->brand_name.'_commodity.id',$this->brand_name.'_commodity.shop_id',$this->brand_name.'_commodity.commodity_name',$this->brand_name.'_commodity.group_name',$this->brand_name.'_commodity.sku_info',$this->brand_name.'_commodity.main_img',$this->brand_name.'_commodity.produce_area1',$this->brand_name.'_commodity.produce_area2',$this->brand_name.'_commodity.express_price','brief_introduction','description')
							->first();
			if($detail->description==''){
				$detail->description = array();
			}else{
				$detail->description = json_decode($detail->description,true);
			}
			
			$min_price = DB::table($this->brand_name.'_skulist')
							->where('commodity_id',$commodity_id)->where($this->brand_name.'_skulist.status','!=',9)->min('price');
			$max_price = DB::table($this->brand_name.'_skulist')
							->where('commodity_id',$commodity_id)->where($this->brand_name.'_skulist.status','!=',9)->max('price');

			$detail->min_price = number_format($min_price,2,'.','');
			$detail->max_price = number_format($max_price,2,'.','');
			$sku_lists = DB::table($this->brand_name.'_skulist')
							->join($this->brand_name.'_shop_sku',$this->brand_name.'_skulist.id','=',$this->brand_name.'_shop_sku.sku_id')
							->where($this->brand_name.'_shop_sku.commodity_id',$commodity_id)
							->where('shop_id',$this->shop_id)
							->where($this->brand_name.'_skulist.status','!=',9)
							->select('commodity_sku','price',$this->brand_name.'_shop_sku.quantity',$this->brand_name.'_shop_sku.status')->get();
			$imgArr = DB::table($this->brand_name.'_commodity_img')->where('commodity_id',$detail->id)->lists('img_src');
			$detail->img = array_filter($imgArr);
			$sku_name = array();
			$sku_status = array();
			$sku_info_temp = array();
			$sku_info = array();
			if(count($sku_lists)==0){
				//没有有效规格
				$detail->status = 0;
			}else{
				$detail->status = 1;
			}
			//if($detail->sku_info == 1){
			
				foreach ($sku_lists as $key => $value) {
					//规格
					$value->commodity_sku= json_decode($value->commodity_sku,true);
					array_push($sku_info_temp, $value->commodity_sku);

					//获取规格
					foreach ($value->commodity_sku as $name => $content) {
						array_push($sku_name,$name);	
					}
				}
				$sku_name = array_unique($sku_name);//规格名称
				foreach ($sku_name as $key => $value) {
					$sku_info[$value] = array();
				}
				foreach ($sku_info_temp as $key => $value) {
					foreach($value as $name => $content){
						array_push($sku_info[$name],$content);
					}
				}
				foreach ($sku_info as $key => $value) {
						$sku_info[$key] = array_unique($value);
				}
			//}			
			//判断是否已有地址
			if($this->openid){
				$customer_id = DB::table($this->brand_name.'_customers')->where('openid',$this->openid)->value('id');
				$address_count = DB::table($this->brand_name.'_receiver_address')->where('customer_id',$customer_id)->where('status',1)->count();
				$shopcart = DB::table($this->brand_name.'_shopcart')->where('customer_id',$customer_id)->where('status',1)->count();
				$shopcart = ($shopcart>0) ? true:false;
			}else{
				$address_count = 0;
				$shopcart = false;
			}
			if($address_count){
				$has_address = true;
			}else{
				$has_address = false;
			}
			$contact = Shopinfo::where('id',$this->shop_id)->first()->customer_service_phone;
			//获取精选商品
			$more = DB::table($this->brand_name.'_commodity')
					->join($this->brand_name.'_shop_commodity',$this->brand_name.'_commodity.id','=',$this->brand_name.'_shop_commodity.commodity_id')
					->where($this->brand_name.'_shop_commodity.status',1)->where('is_recommend',1)->where($this->brand_name.'_shop_commodity.shop_id',$this->shop_id)
					->orderBy($this->brand_name.'_shop_commodity.saled_count','desc')
					->select($this->brand_name.'_commodity.id','commodity_name','main_img','group_id','base_price as price')
					->take(10)->get();
			foreach ($more as $key => $value) {
				//多规格时首页显示商品最低价格
				$more[$key]->price = number_format($value->price,2,'.','');
			}
			
			//var_dump($sku_lists);
			$shop = Shopinfo::find($this->shop_id);
			$shopaddress = $shop->shop_province.$shop->shop_city.$shop->shop_district.$shop->shop_address_detail;
			$shopaddress = str_replace('市辖区','',$shopaddress);
			if($detail->brief_introduction=="" || $detail->brief_introduction=="微信分享给好友会显示这里的文章"){
				$detail->brief_introduction = $shopaddress;
			}
			return View::make('shop.front.detail',array(
				'detail' => $detail,//商品基本信息
				'sku_lists' => $sku_lists,//每种规格对应的价格和库存
				'sku_json' => json_encode($sku_lists),
				'sku_info' => $sku_info,//共有哪些规格
				'contact' => $contact,//客服联系方式
				'has_address' => $has_address,//立即购买时是否已有地址
				'more' => $more,
				'shopid' => $this->shop_id,
				'shopcart' => $shopcart,
				'js'=>$js,
				'shopaddress'=>$shopaddress
				));
		}

		public function getStore($id){
			$shopinfo = DB::table('shopinfo')->where('id',$this->shop_id)->select('shopname','shoplogo','contacter_phone','shop_province','shop_city','shop_district','shop_address_detail','special')->first();
			$account = Account::where('brand_id',$this->brand_id)->first();
			$options = [
			    'debug'  => true,
			    'app_id' => $account->appid,
			    'secret' => $account->appsecret,
			    'token'  => $account->token,
			    'aes_key' =>$account->encodingaeskey, // 可选
			    'log' => [
			        'level' => 'debug',
			        'file'  => storage_path('logs/easywechat.log'), // XXX: 绝对路径！！！！
			    ],
			];
			$app = new Application($options);
			$js = $app->js;
			$shop = Shopinfo::find($this->shop_id);
			$shopaddress = $shop->shop_province.$shop->shop_city.$shop->shop_district.$shop->shop_address_detail;
			$shopaddress = str_replace('市辖区','',$shopaddress);
			return View::make('shop.front.store',array(
				'shopinfo' => $shopinfo,
				'js'=>$js,
				'shopaddress'=>$shopaddress
				));
		}

		public function postTabstore(Request $request){
			//切换店铺但不改变用户所属店铺
			//切换分店
			$newshop_id =$request->shop_id;
			//分店是否存在
			$has_shop = Shopinfo::where('brand_id',$this->brand_id)->where('id',$newshop_id)->where('status',1)->where('open_weishop',1)->count();
			if($has_shop){
				Session::put('shop_id',$newshop_id);
				$this->shop_id = $newshop_id;
				return Response::json(['status'=>'success','msg'=>'跳转回首页']);
			}else{
				return Response::json(['status'=>'error','msg'=>'无效的店铺参数']);
			}
		}

		public function getRest(){
			$account = Account::where('brand_id',Session::get('brand_id'))->first();
			$options = [
			    'debug'  => true,
			    'app_id' => $account->appid,
			    'secret' => $account->appsecret,
			    'token'  => $account->token,
			    'aes_key' =>$account->encodingaeskey, // 可选
			    'log' => [
			        'level' => 'debug',
			        'file'  => storage_path('logs/easywechat.log'), // XXX: 绝对路径！！！！
			    ],
			];
			$app = new Application($options);
			$js = $app->js;
			$shop = Shopinfo::find(Session::get('shop_id'));
			$shopaddress = $shop->shop_province.$shop->shop_city.$shop->shop_district.$shop->shop_address_detail;
			$shopaddress = str_replace('市辖区','',$shopaddress);
			return View::make('shop.front.rest',array('brandname' => Brand::find(Session::get('brand_id'))->brandname,'js'=>$js,'shopaddress'=>$shopaddress));
		}

		public function getCheckoauth($brand_id,$shop_id,$type,$commodity=0){
			$account = Account::where('brand_id',$brand_id)->first();
			$brandname = Brand::find($brand_id)->brandname;
			$options = [
			    'debug'  => true,
			    'app_id' => $account->appid,
			    'secret' => $account->appsecret,
			    'token'  => $account->token,
			    'aes_key' =>$account->encodingaeskey, // 可选
			    'log' => [
			        'level' => 'debug',
			        'file'  => storage_path('logs/easywechat.log'), // XXX: 绝对路径！！！！
			    ],
			];
			$app = new Application($options);
			$oauth = $app->oauth;
			// 获取 OAuth 授权结果用户信息
			$user = $oauth->user();
			$data = $user->getOriginal();
			$new_user = new Customer;
			$new_user->setTable($brandname.'_customers');
			if($new_user->where('openid',$data['openid'])->count()==0){
				$data['shop_id'] = $shop_id;
				$new_user->fill($data)->save();
				$customer = $new_user;
			}else{
				$customer = $new_user->where('openid',$data['openid'])->first();
			}
			Session::put('openid',$data['openid']);
			if($type=='order'){
				return redirect()->action('Shop\OrderController@getIndex');
			}else if($type=='submit'){
				//登录成功,将购物车cookie存储
	            if(Session::has('cart')){
	            	$cart_cookie = Session::get('cart');
	            	$shopArr = array();
	            	foreach ($cart_cookie as $key => $cart) {
	            		$Shopcart = new Shopcart;
	            		$Shopcart->setTable(Session::get('brand_name').'_shopcart');
	            		$shopcart = $Shopcart->where('customer_id',$customer->id)->where('sku_id',$cart['sku_id'])->where('status',1)->first();
	            		if($shopcart){
	            			$shopcart->count = $cart['count'];
	            			$shopcart->setTable(Session::get('brand_name').'_shopcart')->save();
	            			if(isset($shopArr[$cart['shop_id']])){
								array_push($shopArr[$cart['shop_id']], $shopcart->id);
							}else{
								$shopArr[$cart['shop_id']] = array();
								array_push($shopArr[$cart['shop_id']], $shopcart->id);
							}
	            		}else{
	            			$Shopcart->customer_id = $customer->id;
		            		$Shopcart->shop_id = $cart['shop_id'];
		            		$Shopcart->commodity_id = $cart['commodity_id'];
		            		$Shopcart->sku_id = $cart['sku_id'];
		            		$Shopcart->count = $cart['count'];
		            		$Shopcart->status = 1;
		            		$Shopcart->save();
		            		if(isset($shopArr[$cart['shop_id']])){
								array_push($shopArr[$cart['shop_id']], $Shopcart->id);
							}else{
								$shopArr[$cart['shop_id']] = array();
								array_push($shopArr[$cart['shop_id']], $Shopcart->id);
							}
	            		}  		
	            	}
	            	Session::put('cartArr',$shopArr);
	            } 
	            Session::forget('cart');
	            return redirect()->action('Shop\OrderController@getSubmit');      
			}else if($type=='address'){
				 return redirect()->action('Shop\FrontController@getDetail',['commodity_id'=>$commodity]); 
			}else if($type=='vip'){
				return redirect()->action('Shop\VipController@getIndex'); 
			}
		}
	}
