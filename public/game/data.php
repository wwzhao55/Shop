<?php

	require_once 'global_info.php';
	require_once './phpexcel/PHPExcel.php';  

	$param_arr = json_decode($_REQUEST['param']);
	$cmd = "excel";
	if(empty($param_arr->{'type'})==0){
		$cmd = $param_arr->{'type'};
	}else{
		exit;
	}


	if(strcmp($param_arr->{'type'}, "data_statistics")==0){
		echo get_data_statistics($host,$username,$password,$dbname,$param_arr->{'page'});
	}elseif(strcmp($param_arr->{'type'}, "data_table")==0){
		echo get_data_table($host,$username,$password,$dbname,$param_arr->{'page'});
	}elseif(strcmp($param_arr->{'type'}, "excel")==0){
		get_statistics_excel($host,$username,$password,$dbname);
	}elseif(strcmp($param_arr->{'type'}, "add_count")==0){
		add_five_times($host,$username,$password,$dbname);
	}elseif(strcmp($param_arr->{'type'}, "change_game_state")==0){
		update_open($host,$username,$password,$dbname);
	}

	function update_open($host,$username,$password,$dbname){
		$con = mysql_connect($host,$username,$password);
		if (!$con)
  		{
  			die('Could not connect: ' . mysql_error());
  			exit;
  		}
		mysql_select_db($dbname, $con);
		$sql_select = "select open_state from open";
		$result = mysql_query($sql_select,$con);

		$state = mysql_fetch_array($result);
		echo $state['open_state']."<br />";
		if(strcmp($state['open_state'],"1")==0){
			mysql_query("update open set open_state = 0");
			echo "游戏关闭";
		}else{
			mysql_query("update open set open_state = 1");
			echo "游戏开启";
		}

	}

	function add_five_times($host,$username,$password,$dbname){
		$con = mysql_connect($host,$username,$password);
		if (!$con)
  		{
  			die('Could not connect: ' . mysql_error());
  			exit;
  		}
		mysql_select_db($dbname, $con);
		$sql_times = "update add_times set times=times+1";
		$sql_add_lim = "update game_user set count_lim =count_lim +5";
		$err = mysql_query($sql_times,$con);
		$err = mysql_query($sql_add_lim,$con);
		mysql_close($con);
		echo "增加成功！";
	}

	function get_add_times($host,$username,$password,$dbname){
		$count = 0;
		$con = mysql_connect($host,$username,$password);
		if (!$con)
  		{
  			die('Could not connect: ' . mysql_error());
  			exit;
  		}
		mysql_select_db($dbname, $con);
		$sql = "select * from add_times";
		$result = mysql_query($sql,$con);
		if(mysql_num_rows($result) > 0){
			$row = mysql_fetch_array($result);
			$count = $row['times'];
		}
		return $count;
	}

	function get_statistics_excel($host,$username,$password,$dbname){
		
		$objPHPExcel = new PHPExcel();
		
		$con = mysql_connect($host,$username,$password);
		if (!$con)
  		{
  			die('Could not connect: ' . mysql_error());
  			exit;
  		}
		mysql_select_db($dbname, $con);
		
		$sql = "select date,count(distinct(openid)),sum(money) from single_game group by date order by id asc";
		$result = mysql_query($sql,$con);
		
		$objPHPExcel->setActiveSheetIndex(0);
		$objPHPExcel->getActiveSheet()->setCellValue('A1', '日期'); 
		$objPHPExcel->getActiveSheet()->setCellValue('B1', '参与人数'); 
		$objPHPExcel->getActiveSheet()->setCellValue('C1', '发出金额');
		$idx = 2; 
		while($row = mysql_fetch_array($result)){
  			$objPHPExcel->getActiveSheet()->setCellValue('A'.$idx, $row['date']); 
			$objPHPExcel->getActiveSheet()->setCellValue('B'.$idx, $row['count(distinct(openid))']); 
			$objPHPExcel->getActiveSheet()->setCellValue('C'.$idx, $row['sum(money)']);
			$idx++;
  		}

  		$sql_for_table ="select user.phone,count(single.push_time),user.share_time,max(single.money) from game_user as user left join single_game as single on user.openid = single.openid group by user.openid";
		$result_table = mysql_query($sql_for_table,$con);
		
		$objPHPExcel->createSheet();
		$objPHPExcel->setActiveSheetIndex(1);

		$objPHPExcel->getActiveSheet()->setCellValue('A1', '电话'); 
		$objPHPExcel->getActiveSheet()->setCellValue('B1', '总打气次数'); 
		$objPHPExcel->getActiveSheet()->setCellValue('C1', '邀请人数');
		$objPHPExcel->getActiveSheet()->setCellValue('D1', '最高金额');

		$idx = 2;
		while($row_table = mysql_fetch_array($result_table)){
			if(strcmp($row_table['phone'], '0')!=0){
  			$objPHPExcel->getActiveSheet()->setCellValue('A'.$idx, $row_table['phone'].""); 
			$objPHPExcel->getActiveSheet()->setCellValue('B'.$idx, $row_table['count(single.push_time)']); 
			$objPHPExcel->getActiveSheet()->setCellValue('C'.$idx, $row_table['share_time']);
			$objPHPExcel->getActiveSheet()->setCellValue('D'.$idx, $row_table['max(single.money)']);
			$idx++;}
  		}

		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="数据统计.xls"');
		header('Cache-Control: max-age=1000000');
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');

	}

	function get_data_table($host,$username,$password,$dbname,$page){

		$num_per_page = 10;
		$con = mysql_connect($host,$username,$password);
		if (!$con)
  		{
  			die('Could not connect: ' . mysql_error());
  			exit;
  		}
		mysql_select_db($dbname, $con);
		$sql ="select user.phone,count(single.push_time),user.share_time,max(single.money) from game_user as user left join single_game as single on user.openid = single.openid group by user.openid";
		$result = mysql_query($sql,$con);
		$result_num = mysql_num_rows($result);
		//超过最大页数
		if($page > $result_num/$num_per_page){
			$page_num = 1;
		}
		$i = 0;
		$start = ($page-1) * $num_per_page;
		$end = $page * $num_per_page;
		$iter = $start;
		$return_arr = array();
		while($row = mysql_fetch_array($result))
  		{
  			if($iter < $end && strcmp($row['phone'], '0') != 0){
	  			$tmp_arr = array(	
	  								"phone" => $row['phone'],
	  								"push" =>$row['count(single.push_time)'],
	  								"invite" => $row['share_time'],
	  								"highest" => $row['max(single.money)']
	  							);
  				$return_arr["".$i] = json_encode($tmp_arr);
  				//echo json_encode($tmp_arr);
  				$i++;
  				$iter++;
  			}
  		}
  		$data_arr = array();
  		$data_arr['list'] = json_encode($return_arr);
  		$data_arr['page_count'] =floor($result_num/$num_per_page)+1;
  		//echo json_encode($data_arr);
  		mysql_free_result($result_num);
		mysql_close($con);
		return json_encode($data_arr);
	}

	function get_data_statistics($host,$username,$password,$dbname,$page){
		$num_per_page = 10;
		$con = mysql_connect($host,$username,$password);
		if (!$con)
  		{
  			die('Could not connect: ' . mysql_error());
  			exit;
  		}
		mysql_select_db($dbname, $con);

		$sql = "select date,count(distinct(openid)),sum(money) from single_game group by date order by id asc";
		$sql_0 ="select game.date,count(distinct(game.openid)),sum(game.money) from single_game as game left join game_user as user on user.openid=game.openid where char_length(user.phone)>1 group by game.date order by game.id asc";

		$result = mysql_query($sql,$con);
		$result_0 =mysql_query($sql_0,$con);

		$result_num = mysql_num_rows($result);
		//超过最大页数
		if($page > $result_num/$num_per_page){
			$page_num = 1;
		}

		$i = 0;
		$start = ($page-1) * $num_per_page;
		$end = $page * $num_per_page;

		$iter = $start;
		//echo "end:".$end."<br />";
		$return_arr = array();
		while($row = mysql_fetch_array($result))
  		{
  			$row_0 =mysql_fetch_array($result_0);
  			if($iter < $end && $iter == $i){
	  			$tmp_arr = array("date" => $row['date'],"people_num" =>$row['count(distinct(openid))'],"money" => $row['sum(money)']
	  				,"valid_num"=>$row_0['count(distinct(game.openid))']>0?$row_0['count(distinct(game.openid))']:0);
  				$return_arr["".$i] = json_encode($tmp_arr);
  				//echo json_encode($tmp_arr);
  				
  				$iter++;
  			}
  			$i++;
  		}
  		$data_arr = array();
  		$data_arr['list'] = json_encode($return_arr);
  		$data_arr['page_count'] =floor($result_num/$num_per_page)+1;


  		//总参与人数
  		$sql_count_user= "select count(openid) from game_user";
  		$result_count_user = mysql_query($sql_count_user,$con);
  		if($row_count_user = mysql_fetch_array($result_count_user)){
  			//echo "openidnum:".$row_count_user['count(openid)']."<br />";
  			$data_arr['attender'] = $row_count_user['count(openid)'];
  		}
  		//总资金
  		$all_money = 100000;
  		$data_arr['capital'] = $all_money;
  		//余额
  		$sql_remain = "select sum(money) from single_game;";
  		$result_remain = mysql_query($sql_remain,$con);
  		if($row_remain = mysql_fetch_array($result_remain)){
  			$data_arr['capital'] =$row_remain['sum(money)'];
  		}

  		$sql_high ="select max(money) from single_game";
  		$result_high =mysql_query($sql_high);
  		if($row_high =mysql_fetch_array($result_high)){
  			$data_arr['remain'] = $row_high['max(money)'];
  		}
  		
  		$data_arr['valid_num'] = get_valid_num($host,$username,$password,$dbname);

		mysql_free_result($result_num);
		mysql_close($con);
		return json_encode($data_arr);
	}

	function get_valid_num($host,$username,$password,$dbname){
		$exist = 0;
		$con = mysql_connect($host,$username,$password);
		if (!$con)
  		{
  			die('Could not connect: ' . mysql_error());
  			exit;
  		}
		mysql_select_db($dbname, $con);

		$sql = "select count(*) from game_user where char_length(phone)>1";
		$result = mysql_query($sql,$con);
		if(mysql_num_rows($result) > 0){
			$row = mysql_fetch_array($result);
			$exist =$row['count(*)'];
		}
		return $exist;
	}

?>