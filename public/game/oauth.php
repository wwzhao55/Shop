<?php
	
	require_once 'global_info.php';
	require_once "jssdk.php";

	$jssdk = new JSSDK($appid, $appsecret);
	$signPackage = $jssdk->GetSignPackage();

	//需要改回来
	function check_date($date = 5){
	//	if(date("w") != $date){
		if(false){
			header('Location: notopen.html');
			exit;
		}
	}

	function check_game_open($host,$username,$password,$dbname){
		$con = mysql_connect($host,$username,$password);
		if (!$con)
  		{
  			die('Could not connect: ' . mysql_error());
  			exit;
  		}
		mysql_select_db($dbname, $con);

		$sql_select = "select * from open";
		$result =  mysql_query($sql_select,$con);
		$state = mysql_fetch_array($result);
		if(strcmp($state['open_state'],"0")==0){
			header('Location: notopen.html');
			exit;
		}
		mysql_free_result($result);
		mysql_close($con);
	}
	
	//判断用户是否关注
	function is_subscribe($appid,$appsecret,$openid){
		$global_token_url='https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$appid.'&secret='.$appsecret;
		$global_token = json_decode(file_get_contents($global_token_url));
		if (isset($global_token->errcode)) {
    		echo '<h1>错误：</h1>'.$global_token->errcode;
    		echo '<br/><h2>错误信息：</h2>'.$global_token->errmsg;
    		exit;
		}
		$other_info_url = 'https://api.weixin.qq.com/cgi-bin/user/info?access_token='.$global_token->access_token.'&openid='.$openid.'&lang=zh_CN';
		$other_info = json_decode(file_get_contents($other_info_url));
		if (isset($other_info->errcode)) {
    		echo '<h1>错误：</h1>'.$other_info->errcode;
    		echo '<br/><h2>错误信息：</h2>'.$other_info->errmsg;
    		exit;
		}
		return $other_info -> subscribe;
	}
	
	function check_user($host,$username,$password,$dbname,$openid){
		$exist = 0;
		$con = mysql_connect($host,$username,$password);
		if (!$con)
  		{
  			die('Could not connect: ' . mysql_error());
  			exit;
  		}
		mysql_select_db($dbname, $con);	
		$result = mysql_query("SELECT * FROM game_user WHERE openid = '$openid'");
		if(mysql_num_rows($result) > 0){
			$openid_row = mysql_fetch_array($result);
			$exist = $openid_row['phone'];//未填写电话号码返回0
		}else{
			//插入一个新用户，但是电话为0
			$insert_user_sql = "insert into game_user (openid,phone) values ('$openid','0')";
			$err = mysql_query($insert_user_sql,$con);
		}

		mysql_free_result($result);
		mysql_close($con);
  		return $exist;
	}

	//得到最高奖金，查询single_game表，如果记录行>0那么可以返回最高奖金，否则返回0
	function get_max_money($host,$username,$password,$dbname,$game_table,$openid,$money){
		$con = mysql_connect($host,$username,$password);
		if (!$con)
  		{
  			die('Could not connect: ' . mysql_error());
  			exit;
  		}
		mysql_select_db($dbname, $con);
		$sql = "SELECT money FROM single_game WHERE openid='$openid' ORDER BY money DESC";
		$result = mysql_query($sql);
		$max_money = 0;
		if(mysql_num_rows($result) > 0){
			$row_0 = mysql_fetch_array($result);
			$max_money = $row_0['money'];
		}
		mysql_free_result($result);
		mysql_close($con);
		return $max_money;
	}

	//得到总剩余次数，默认返回0
	function get_all_time_left($host,$username,$password,$dbname,$openid){
		$con = mysql_connect($host,$username,$password);
		if (!$con)
  		{
  			die('Could not connect: ' . mysql_error());
  			exit;
  		}
		mysql_select_db($dbname, $con);
		$sql = "SELECT count,count_lim FROM game_user WHERE openid='$openid'";
		$result = mysql_query($sql);
		$all_left = 0;
		if(mysql_num_rows($result) > 0 ){
			$row_0 = mysql_fetch_array($result);
			$all_left = $row_0['count_lim']-$row_0['count'];
		}
		if ($all_left < 0) {
			$all_left =0 ;
		}
		mysql_free_result($result);
		mysql_close($con);
		return $all_left;
	}

	//为用户添加phone字段
	function create_user($host,$username,$password,$dbname,$openid,$phone){
		$con = mysql_connect($host,$username,$password);
		if (!$con){
  			die('Could not connect: ' . mysql_error());
  			exit;
  		}
		mysql_select_db($dbname, $con);

		$re_code = 0;
		$err = mysql_query("update game_user set phone = '$phone' where openid = '$openid'",$con);
		if(!$err){
			die('Error: '.mysql_error());
			exit;
		}
		$re_code = 1;
		return $re_code;
	}

	//增加已经玩的次数，这里只增加game_user表的count字段
	function add_game_count($host,$username,$password,$dbname,$openid){
		$con = mysql_connect($host,$username,$password);
		if (!$con){
  			die('Could not connect: ' . mysql_error());
  			exit;
  		}
		mysql_select_db($dbname, $con);
		$err = mysql_query("update game_user set count=count+1 where openid='$openid'",$con);
		if(!$err){
			die('Error: '.mysql_error());
			exit;
		}
		mysql_close($con);
	}

	function add_today_count($host,$username,$password,$dbname,$openid){
		$date_now = date("Y-m-d");
		$con = mysql_connect($host,$username,$password);
		if (!$con){
  			die('Could not connect: ' . mysql_error());
  			exit;
  		}
		mysql_select_db($dbname, $con);

		$query_today_sql = "select * from share_table where openid = '$openid' and date = '$date_now'";
		$result_today_sql = mysql_query($query_today_sql,$con);
		if(mysql_num_rows($result_today_sql) == 0){
			$err = mysql_query("insert into share_table (openid,date) values ('$openid','$date_now')");
		}
		
		$err = mysql_query("update share_table set count=count+1 where openid='$openid' and date='$date_now'",$con);
		mysql_close($con);
	}

	//得到今日游戏次数，也就是single_game中特定日期特定openid的行数
	function get_today_count($host,$username,$password,$dbname,$openid){
		$con = mysql_connect($host,$username,$password);
		if (!$con)
  		{
  			die('Could not connect: ' . mysql_error());
  			exit;
  		}
		mysql_select_db($dbname, $con);
		$today_count = 0;
		$current_time = date("Y-m-d");
		$result = mysql_query("select * from single_game where openid='$openid' and date='$current_time'",$con);
		$today_count = mysql_num_rows($result);
		mysql_free_result($result);
		mysql_close($con);
		return $today_count;
	}

	function get_today_count_new($host,$username,$password,$dbname,$openid){
		$today_count = 0;
		$con = mysql_connect($host,$username,$password);
		if (!$con)
  		{
  			die('Could not connect: ' . mysql_error());
  			exit;
  		}
		mysql_select_db($dbname, $con);

		$current_time = date("Y-m-d");
		$result = mysql_query("select * from share_table where openid='$openid' and date='$current_time'",$con);
		if(mysql_num_rows($result) > 0){
			$row = mysql_fetch_array($result);
			$today_count = $row['count'];
		}

		mysql_free_result($result);
		mysql_close($con);
		return $today_count;
	}

	//得到今日剩余次数
	function get_today_left($host,$username,$password,$dbname,$openid,$day_lim){
		$left = 0;
		$all_left = get_all_time_left($host,$username,$password,$dbname,$openid);//p
		$today_left_tmp = $day_lim - get_today_count_new($host,$username,$password,$dbname,$openid);//l

		if($all_left>$day_lim){
			$left = $today_left_tmp;
		}else{
			$left = min($all_left,$today_left_tmp);
		}
		if($left < 0){
			$left = 0;
		}
		return $left;
	}

	//产生随机爆点
	function generate_break_point($max){
		$if_seed = rand(0,1000);
		if($if_seed > 875){
			return rand(0,$max);
		}else{
			return rand(0,10);
		}
		return rand(0,10);
	}

	function query_time($host,$username,$password,$dbname,$openid,$is_subscribe){
		$count = 0;
		$ym = date("Y-m");
		$con = mysql_connect($host,$username,$password);
		if (!$con)
  		{
  			die('Could not connect: ' . mysql_error());
  			exit;
  		}
		mysql_select_db($dbname, $con);

		$sql = "select * from game_time where openid='$openid' and ym='$ym'";
		//echo $sql;
		$result = mysql_query($sql);

		if(mysql_num_rows($result) > 0){
			//echo mysql_num_rows($result);
			$row = mysql_fetch_array($result);
			if($is_subscribe == 0){
				$count = $row['phone_count'];
			}else{
				$count = $row['game_count'];
			}
			//echo $count;
		}
		mysql_free_result($result);
		mysql_close($con);
		return $count;
	}

	function add_time_record($host,$username,$password,$dbname,$openid,$is_subscribe){
		$ym = date("Y-m");
		//echo $ym;
		$con = mysql_connect($host,$username,$password);
		if (!$con)
  		{
  			die('Could not connect: ' . mysql_error());
  			exit;
  		}
		mysql_select_db($dbname, $con);

		$result = mysql_query("select * from game_time where openid='$openid' and ym='$ym'",$con);
		//echo mysql_num_rows($result);
		$add_sql="";
		if(mysql_num_rows($result) == 0){
			if($is_subscribe == 0){
				$add_sql = "insert into game_time (openid,ym,phone_count) values ('$openid','$ym',1)";
			}else{
				//echo 1;
				$add_sql = "insert into game_time (openid,ym,game_count) values ('$openid','$ym',1)";
			}
		}else{
			if($is_subscribe == 0){
				$add_sql = "update game_time set phone_count = phone_count+1 where openid='$openid' and ym='$ym'";
			}else{
				$add_sql = "update game_time set game_count = game_count+1 where openid='$openid' and ym='$ym'";
			}
		}
		$err = mysql_query($add_sql,$con);

		mysql_free_result($result);
		mysql_close($con);

	}

	//增加一个游戏记录的字段，在single_game中插入
	function add_game_record($host,$username,$password,$dbname,$openid,$break_point,$push_time,$money,$is_subscribe){
		add_time_record($host,$username,$password,$dbname,$openid,$is_subscribe);
		$con = mysql_connect($host,$username,$password);
		if (!$con)
  		{
  			die('Could not connect: ' . mysql_error());
  			exit;
  		}
		mysql_select_db($dbname, $con);
		$date_now = date("Y-m-d");
		$err = mysql_query("insert into single_game (openid,break_point,push_time,money,date) values('$openid','$break_point','$push_time','$money','$date_now') ",$con);
		if(!$err){
			die('Error: '.mysql_error());
			exit;
		}
		mysql_close($con);
		
	}

	//更新分享信息
	function update_share_record($host,$username,$password,$dbname,$openid){
		$date_now = date("Y-m-d");
		$con = mysql_connect($host,$username,$password);
		if (!$con){
  			die('Could not connect: ' . mysql_error());
  			exit;
  		}
		mysql_select_db($dbname, $con);
		//对于share_table先判断是否存在这一行，不存在则插入一行
		$query_today_sql = "select * from share_table where openid = '$openid' and date = '$date_now'";
		$result_today_sql = mysql_query($query_today_sql,$con);
		if(mysql_num_rows($result_today_sql) == 0){
			$err = mysql_query("insert into share_table (openid,date) values ('$openid','$date_now')");
		}
		
		$err = mysql_query("update share_table set share=share+1 where openid='$openid' and date='$date_now'",$con);
		
		$err = mysql_query("update game_user set count_lim=count_lim+1 where openid = '$openid'",$con);
		
		$err = mysql_query("update game_user set share_time=share_time+1 where openid = '$openid'",$con);

		mysql_close($con);
	}
	
	//处理分享信息，处理了share_point
	function add_share_data($host,$username,$password,$dbname,$from_user,$to_user){
		
		//* share_point table里存放的是openid，谁分享给了谁
		//* 首先要从from_user这个电话，查询到对应的openid
		//* 得到来源用户的openid和当前用户的openid，在share_point里查是不是已经有这个字段了，也就是说是不是已经分享给这个用户了
		//* 如果没有分享，那就可以插入这个from_user->to_user映射了
		//* 然后给share_table再+1

		//1
		$con = mysql_connect($host,$username,$password);
		if (!$con)
  		{
  			die('Could not connect: ' . mysql_error());
  			exit;
  		}
		mysql_select_db($dbname, $con);
		$sql_openid = "SELECT openid FROM game_user WHERE phone = '$from_user'";
		$result_openid = mysql_query($sql_openid,$con);
		if(mysql_num_rows($result_openid) > 0){
			$row_openid = mysql_fetch_array($result_openid);
			$from_openid = $row_openid['openid'];

			if(strcmp($from_openid,$to_user)==0){
				return ;
			}
		}else{
			return;
		}
		
			//4
			update_share_record($host,$username,$password,$dbname,$from_openid);
		
		mysql_free_result($result_openid);
		//mysql_free_result($result_share);
		mysql_close($con);
	}

	function refresh_max_money($host,$username,$password,$dbname,$openid,$new_money){
		$con = mysql_connect($host,$username,$password);
		if (!$con)
  		{
  			die('Could not connect: ' . mysql_error());
  			exit;
  		}
		mysql_select_db($dbname, $con);

		$query_sql = "select * from game_user where openid = '$openid'";
		$query_result = mysql_query($query_sql,$con);
		if(mysql_num_rows($query_result) > 0){
			$row_0 = mysql_fetch_array($query_result);
			$max_money = $row_0['max_money'];
		}

		if($new_money > $max_money){
			$refresh_sql = "update game_user set max_money = '$new_money' where openid = '$openid'";
			$err = mysql_query($refresh_sql,$con);
		}
		mysql_free_result($query_result);
		mysql_close($con);

	}

	function get_max_money_new($host,$username,$password,$dbname,$openid){
		$max_money = 0;
		$con = mysql_connect($host,$username,$password);
		if (!$con)
  		{
  			die('Could not connect: ' . mysql_error());
  			exit;
  		}
		mysql_select_db($dbname, $con);

		$query_sql = "select * from game_user where openid = '$openid'";
		$query_result = mysql_query($query_sql,$con);
		if(mysql_num_rows($query_result) > 0){
			$row_0 = mysql_fetch_array($query_result);
			$max_money = $row_0['max_money'];

		}
		$tmp=mysql_num_rows($query_result);
		if(empty($max_money)){
			$max_money = 0;
		}
		return $max_money;
	}

	// 登陆得到OpenID
	function get_openid($code,$appid,$appsecret){
		$token_url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid='.$appid.'&secret='.$appsecret.'&code='.$code.'&grant_type=authorization_code';
		$token = json_decode(file_get_contents($token_url));
		if (isset($token->errcode)) {
    		echo '<h1>错误：</h1>'.$token->errcode;
    		echo '<br/><h2>错误信息：</h2>'.$token->errmsg;
    		exit;
		}

		$access_token_url = 'https://api.weixin.qq.com/sns/oauth2/refresh_token?appid='.$appid.'&grant_type=refresh_token&refresh_token='.$token->refresh_token;
	
		$access_token = json_decode(file_get_contents($access_token_url));
		if (isset($access_token->errcode)) {
    		echo '<h1>错误：</h1>'.$access_token->errcode;
    		echo '<br/><h2>错误信息：</h2>'.$access_token->errmsg;
    		exit;
		}

		$user_info_url = 'https://api.weixin.qq.com/sns/userinfo?access_token='.$access_token->access_token.'&openid='.$access_token->openid.'&lang=zh_CN';
		$openid = $access_token->openid;
	
		$user_info = json_decode(file_get_contents($user_info_url));
		if (isset($user_info->errcode)) {
    		echo '<h1>错误：</h1>'.$user_info->errcode;
    		echo '<br/><h2>错误信息：</h2>'.$user_info->errmsg;
    		exit;
		}	
		return $openid;
	}


//	check_date(5);

	check_game_open($host,$username,$password,$dbname);
	
	$code = $_GET['code'];
	$state = $_GET['state'];
	
	if (!empty($code)) {
		$openid = get_openid($code,$appid,$appsecret);
	}
	
	if(!empty($_REQUEST["param"])){
		$param_arr = json_decode($_REQUEST['param']);
	}

	if (empty($code) && empty($_REQUEST['param'])) {
		exit;
	}
	
	session_start();

	if(!empty($openid)){
		$_SESSION['openid'] = $openid;
	}
	
	$user_subscribe 		= is_subscribe($appid,$appsecret,$_SESSION['openid']);

	//默认输出
	if(empty($_REQUEST['param'])){

		//填了电话没有
		$current_user_registed 	= check_user($host,$username,$password,$dbname,$_SESSION['openid']);

		$max_score =0;

		$max_score 				= get_max_money_new($host,$username,$password,$dbname,$_SESSION['openid']);

		$today_time 			= get_today_left($host,$username,$password,$dbname,$_SESSION['openid'],$day_lim);

		//$all_time 				= get_all_time_left($host,$username,$password,$dbname,$_SESSION['openid']);
		$all_time 				= $N - query_time($host,$username,$password,$dbname,$_SESSION['openid'],$user_subscribe);

		$current_phone 			= $current_user_registed!=0?$current_user_registed:"null";

		

		//if($user_subscribe == 0){
		//	header('Location: game.html');
		//}

		$index_file = file_get_contents('game_index.html');
		$index_file = str_replace("\$max_score",$max_score.'',$index_file);
		$index_file = str_replace("\$today_time",$today_time.'',$index_file);
		$index_file = str_replace("\$all_time",$all_time.'',$index_file);
		$index_file = str_replace("\$current_phone",$current_phone.'',$index_file);

		$index_file = str_replace("\$signPackage['appId']",$signPackage["appId"].'',$index_file);
		$index_file = str_replace("\$signPackage['timestamp']",$signPackage['timestamp'].'',$index_file);
		$index_file = str_replace("\$signPackage['nonceStr']",$signPackage['nonceStr'].'',$index_file);
		$index_file = str_replace("\$signPackage['signature']",$signPackage['signature'].'',$index_file);

		echo $index_file;
	}
	
	//* ----------------------- TEST FOR CHECK REGISTER ------------------------
	if(empty($_SESSION['openid'])==0 && empty($param_arr->{'cmd'})==0 && (strcmp($param_arr->{'cmd'},"get_user_info")==0)){
		//echo "get_user_info<br />";

		$is_sub = is_subscribe($appid,$appsecret,$_SESSION['openid']);
 		$user_in_db = check_user($host,$username,$password,$dbname,$_SESSION['openid']);

	 	if($user_in_db!=0){
 			$login_array = array("subscribe" => $is_sub,"registed" => 1 , "phone" =>$user_in_db);
 		}else{
 			$login_array = array("subscribe" => $is_sub,"registed" => 0);
 		}
 	
		echo json_encode($login_array)."<br />";
	}
	
	

	if(	empty($_SESSION['openid'])==0 && 
		empty($param_arr->{'cmd'})==0 && 
		strcmp($param_arr->{'cmd'},"add_share_data")==0){
		update_share_record($host,$username,$password,$dbname,$_SESSION['openid']);
	}
	
	//* ----------------------- TEST FOR QUERY SCORE --------------------
	if(	empty($_SESSION['openid']) == 0 &&
		empty($param_arr->{'cmd'}) == 0 && 
		strcmp($param_arr->{'cmd'},"get_score") == 0){

		$max_money 			= get_max_money_new($host,$username,$password,$dbname,$_SESSION['openid']);
		$today_count_left 	= get_today_left($host,$username,$password,$dbname,$_SESSION['openid'],$day_lim);
		$all_count_left 	= get_all_time_left($host,$username,$password,$dbname,$_SESSION['openid']);

		$score_array 		= array("highest" => $max_money,
									"today_left" => $today_count_left,
									"all_count_left" =>$all_count_left);
		echo json_encode($score_array);
	}
	
	//* ---------------------- TEST FOR USER REGISTER -------------------
	if(	empty($_SESSION['openid'])				==0 && 
		empty($param_arr->{'cmd'})				==0 && 
		empty($param_arr->{'phone'})			==0 && 
		strcmp($param_arr->{'cmd'},"register")	==0){

		$user_in_db = check_user($host,$username,$password,$dbname,$_SESSION['openid']);
		
		$register_result = 0;
 		if($user_in_db == 0){
			$register_result = create_user($host,$username,$password,$dbname,$_SESSION['openid'],$param_arr->{'phone'});
		}
		$register_result_arr= array("register_info" => $register_result);
		echo json_encode($register_result_arr);
	}

	//* ------------------------- DEAL THE BREAK POINT ----------------- 
	if(empty($_SESSION['bp_point'])){
		$bp_point = generate_break_point($random_break_point);
		$_SESSION['bp_point'] = $bp_point;
		$_SESSION['push_time'] = 0;
		$_SESSION['current_money'] = 0;
	}
	
	//$today = get_today_left($host,$username,$password,$dbname,$_SESSION['openid'],$day_lim);
	//if(empty($_SESSION['openid'])==0 && empty($param_arr->{'cmd'})==0 && strcmp($param_arr->{'cmd'},"today_left")==0){
	//	echo json_encode(array("today" => $today));
	//}

	$phone_return = check_user($host,$username,$password,$dbname,$_SESSION['openid']);
	$phone_cmp = strcmp($phone_return."","0");//不同的话返回1，也就是老用户

	if(	empty($_SESSION['openid'])			==	0 &&
		empty($param_arr->{'cmd'})			==	0 && 
		strcmp($param_arr->{'cmd'},"push")	==	0 && 
		empty($param_arr->{'score'})		==	0 ){

		$_SESSION['current_money'] += $param_arr->{'score'};
		if($_SESSION['current_money'] > $_SESSION['bp_point'] || $_SESSION['push_time'] > rand(5,8)){
			//爆了
			$break_arr = array('break' => 1);
			
			add_game_record($host,$username,$password,$dbname,$_SESSION['openid'],$_SESSION['bp_point'],$_SESSION['push_time'],0,$user_subscribe);
			add_game_count($host,$username,$password,$dbname,$_SESSION['openid']);	
			add_today_count($host,$username,$password,$dbname,$_SESSION['openid']);
			unset($_SESSION['bp_point']);
			unset($_SESSION['push_time']);
			unset($_SESSION['current_money']);

		}else{
			//还没爆
			$break_arr = array('break' => 0);
			$_SESSION['push_time'] ++ ;
			
		}
		
		//$today_count_now = get_today_count_new($host,$username,$password,$dbname,$_SESSION['openid']);
		$today_count = query_time($host,$username,$password,$dbname,$_SESSION['openid'],$user_subscribe);
		//echo $today_count;
		if($today_count > $N-1){
			$break_arr = array('break' => -1);
		}
		echo json_encode($break_arr);

	}elseif(empty($_SESSION['openid'])				==	0 &&
			empty($param_arr->{'cmd'})				==	0 && 
			strcmp($param_arr->{'cmd'},"harvest")	==	0 && 
			$_SESSION['current_money'] < $_SESSION['bp_point']){

		if(	isset($_SESSION['bp_point']) &&
			isset($_SESSION['push_time']) && 
			isset($_SESSION['current_money'])){

			$phone_return = check_user($host,$username,$password,$dbname,$_SESSION['openid']);
			$phone_cmp = strcmp($phone_return."","0");//不同的话返回1，也就是老用户
			add_game_record($host,$username,$password,$dbname,$_SESSION['openid'],$_SESSION['bp_point'],$_SESSION['push_time'],$_SESSION['current_money'],$user_subscribe);

				add_game_count($host,$username,$password,$dbname,$_SESSION['openid']);
				add_today_count($host,$username,$password,$dbname,$_SESSION['openid']);
				refresh_max_money($host,$username,$password,$dbname,$_SESSION['openid'],$_SESSION['current_money']);
				unset($_SESSION['bp_point']);
				unset($_SESSION['push_time']);
				unset($_SESSION['current_money']);
				
			echo json_encode(array("phone_cmp" => $phone_cmp, "harvest_result" => 1));
		}
	}
	
?>
