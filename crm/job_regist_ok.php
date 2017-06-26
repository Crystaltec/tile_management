<?php
include_once "include/logincheck.inc";
include_once "include/common.inc";
include_once "include/dbconn.inc";
include_once "include/myfunc.inc";

//$job_id =		$_REQUEST["job_id"];
$id = $_REQUEST["id"];
$project_id = $_REQUEST["project_id"];
$employee_id =	$_REQUEST["employee_id"];
$job_date = getAUDateToDB($_REQUEST["job_date"]);
$job_type = $_REQUEST["job_type"];
$job_session= $_REQUEST["job_session"];
$job_session_rates = $_REQUEST["job_session_rates"];
$job_extra_hour = $_REQUEST["job_extra_hour"];
$job_extra_hour_rates = $_REQUEST["job_extra_hour_rates"];
$job_session_rates_txt = $_REQUEST["job_session_rates_txt"];
$job_extra_hour_rates_txt = $_REQUEST["job_extra_hour_rates_txt"];
$time = $_REQUEST['time'];
$limitList = $_REQUEST["limitList"];
$list_view = $_REQUEST["list_view"];
$s_date = $_REQUEST['s_date'];
$s_resort_order = $_REQUEST["resort_order"];
$srch_param = "";
if($s_resort_order != ""){
	$srch_param .= "&resort_order=$s_resort_order";
}

if($limitList) {
	$srch_param .="&limitList=$limitList";
}
					
if($list_view) {
	$srch_param .="&list_view=$list_view";
}

if ($s_date) {
	$srch_param .="&s_date=".$s_date;
}


if(!$job_extra_hour_rates) {
	if(is_numeric($job_extra_hour_rates_txt)) {
		$job_extra_hour_rates = $job_extra_hour_rates_txt;
	}
}

if(!$job_session_rates) {
	if(is_numeric($job_session_rates_txt)) {
		$job_session_rates = $job_session_rates_txt;
	}
}


$remarks=htmlspecialchars($_REQUEST["remarks"], ENT_QUOTES);

$account_id = $Sync_id;

$action_type = $_REQUEST["action_type"];


$project_id2 = $_REQUEST["project_id2"];
$employee_id2 =	$_REQUEST["employee_id2"];
$job_date2 = getAUDateToDB($_REQUEST["job_date2"]);
$job_type2 = $_REQUEST["job_type2"];
$job_session2 = $_REQUEST["job_session2"];
$job_session_rates2 = $_REQUEST["job_session_rates2"];
$job_extra_hour2 = $_REQUEST["job_extra_hour2"];
$job_extra_hour_rates2 = $_REQUEST["job_extra_hour_rates2"];
$job_session_rates_txt2 = $_REQUEST["job_session_rates_txt2"];
$job_extra_hour_rates_txt2 = $_REQUEST["job_extra_hour_rates_txt2"];
$time2 = $_REQUEST['time2'];
if(!$job_extra_hour_rates2) {
	if(is_numeric($job_extra_hour_rates_txt2)) {
		$job_extra_hour_rates2 = $job_extra_hour_rates_txt2;
	}
}

if(!$job_session_rates2) {
	if(is_numeric($job_session_rates_txt2)) {
		$job_session_rates2 = $job_session_rates_txt2;
	}
}

$remarks2=htmlspecialchars($_REQUEST["remarks2"], ENT_QUOTES);


if($action_type=="") {
	$e_dup = "";
	foreach($employee_id as $value) {
		$e_dup ="";
		if ($job_session == 'FULL' && $job_session_rates <> "") {
			$session_cond = " AND job_session IN ('FULL','HALF') ";
			$sql = "SELECT COUNT(*) FROM job WHERE job_date='".$job_date."' AND employee_id='".$value."' ".
				$session_cond;
		
			$row = getRowCount($sql);
			if($row[0] > 0 ) {
				//echo "<script>alert('Please check job schedule.')</script>";
				$e_dup = "error";
			}
		} else if ($job_session == 'HALF' && $job_session_rates <> "") {
			$session_cond = " AND job_session IN ('HALF') ";
			$sql = "SELECT COUNT(*) FROM job WHERE job_date='".$job_date."' AND employee_id='".$value."' ".
				$session_cond;
		
			$row = getRowCount($sql);
			if($row[0] > 1 ) {
				//echo "<script>alert('Please check job schedule.')</script>";
				$e_dup = "error";
			}
			
			$session_cond = " AND job_session IN ('FULL') ";
			$sql = "SELECT COUNT(*) FROM job WHERE job_date='".$job_date."' AND employee_id='".$value."' ".
				$session_cond;
		
			$row = getRowCount($sql);
			if($row[0] > 0 ) {
				//echo "<script>alert('Please check job schedule.')</script>";
				$e_dup = "error";
			}
			
		}
		
		if ($e_dup != "error") {
			$f_wage_id = getValue("employee", "current_ft_wage_id", " and id = '$value' ORDER BY regdate desc limit 1 ");
			$h_wage_id = getValue("employee", "current_hr_wage_id", " and id = '$value' ORDER BY regdate desc limit 1 ");
			
			$sql = "INSERT INTO job (job_id,job_type, project_id, employee_id, job_date, job_session, job_session_rates, job_extra_hour, job_extra_hour_rates, remarks,account_id, regdate, f_wages_id, time,h_wages_id) ";
			$sql .= "VALUES ('";
			$sql .= $job_id . "', '";
			$sql .= $job_type . "', '";
			$sql .= $project_id . "', '";
			$sql .= $value . "', '";
			$sql .= $job_date . "', '";
			$sql .= $job_session ."', '";
			$sql .= $job_session_rates ."', '";
			$sql .= $job_extra_hour ."', '";
			$sql .= $job_extra_hour_rates ."', '";
			$sql .= $remarks . "','";
			$sql .= $account_id . "', '";
			$sql .= $now_datetimeano ."', '";
			$sql .= $f_wage_id . "', '";
			$sql .= $time . "', '";
			$sql .= $h_wage_id ."' )";
			
			pQuery($sql,"insert");
		}
	}
	
	// multi row
	$e_dup2 = "";
	if ( isset($employee_id2) && $project_id2 && $job_date2 && (($job_session2 <> "" && $job_session_rates2 <> "" ) || ($job_extra_hour2 <> "" && $job_extra_hour_rates2 <> "" ))) {

		foreach($employee_id2 as $value) {
			$e_dup2 ="";
			if ($job_session2 == 'FULL' && $job_session_rates2 <> "") {
				$session_cond2 = " AND job_session IN ('FULL','HALF') ";
				$sql2 = "SELECT COUNT(*) FROM job WHERE job_date='".$job_date2."' AND employee_id='".$value."' ".
					$session_cond2;
			
				$row2 = getRowCount($sql2);
				if($row2[0] > 0 ) {
					//echo "<script>alert('Please check job schedule.')</script>";
					$e_dup2 = "error";
				}
			} else if ($job_session2 == 'HALF' && $job_session_rates2 <> "") {
				$session_cond2 = " AND job_session IN ('HALF') ";
				$sql2 = "SELECT COUNT(*) FROM job WHERE job_date='".$job_date2."' AND employee_id='".$value."' ".
					$session_cond2;
			
				$row2 = getRowCount($sql2);
				if($row2[0] > 1 ) {
					//echo "<script>alert('Please check job schedule.')</script>";
					$e_dup2 = "error";
				}
				
				$session_cond2 = " AND job_session IN ('FULL') ";
				$sql2 = "SELECT COUNT(*) FROM job WHERE job_date='".$job_date2."' AND employee_id='".$value."' ".
					$session_cond2;
			
				$row2 = getRowCount($sql2);
				if($row2[0] > 0 ) {
					//echo "<script>alert('Please check job schedule.')</script>";
					$e_dup2 = "error";
				}
				
			}
			
			if ($e_dup2 != "error") {
				$f_wage_id2 = getValue("employee", "current_ft_wage_id", " and id = '$value' ORDER BY regdate desc limit 1 ");
				$h_wage_id2 = getValue("employee", "current_hr_wage_id", " and id = '$value' ORDER BY regdate desc limit 1 ");
				
				$sql2 = "INSERT INTO job (job_id,job_type, project_id, employee_id, job_date, job_session, job_session_rates, job_extra_hour, job_extra_hour_rates, remarks,account_id, regdate, f_wages_id, time,h_wages_id) ";
				$sql2 .= "VALUES ('";
				$sql2 .= $job_id2 . "', '";
				$sql2 .= $job_type2 . "', '";
				$sql2 .= $project_id2 . "', '";
				$sql2 .= $value . "', '";
				$sql2 .= $job_date2 . "', '";
				$sql2 .= $job_session2 ."', '";
				$sql2 .= $job_session_rates2 ."', '";
				$sql2 .= $job_extra_hour2 ."', '";
				$sql2 .= $job_extra_hour_rates2 ."', '";
				$sql2 .= $remarks2 . "','";
				$sql2 .= $account_id . "', '";
				$sql2 .= $now_datetimeano ."', '";
				$sql2 .= $f_wage_id2 . "', '";
				$sql2 .= $time2 . "', '";
				$sql2 .= $h_wage_id2 ."' )";
				
				//echo $sql2;
				pQuery($sql2,"insert");
			}
		}
	}
	$str1 = "Registration Completed!";
	//echo $sql;
}
/* else if($action_type=="modify") {
	$sql = "SELECT COUNT(*) FROM job WHERE job_id='".$job_id."' and id <> '".$id."' ";
	$row = getRowCount($sql);
	if($row[0] > 0 ) {
		echo "<script>alert('This job id has already registered');history.back();</script>";
		exit;
	}
	
	if ($job_session == 'FULL') {
		$session_cond = " AND job_session IN ('FULL','HALF') ";
		$sql = "SELECT COUNT(*) FROM job WHERE job_date='".$job_date."' AND employee_id='".$value."' AND project_id='".$project_id."' " .
			$session_cond . " AND id <> '".$id."' " ;
		$row = getRowCount($sql);
		if($row[0] > 0 ) {
			echo "<script>alert('Please check job schedule.')</script>";
			exit;
		}
	} else if ($job_session == 'HALF') {
			$session_cond = " AND job_session IN ('HALF') ";
			$sql = "SELECT COUNT(*) FROM job WHERE job_date='".$job_date."' AND employee_id='".$value."' AND project_id='".$project_id."' " .
				$session_cond . " AND id <> '".$id."' " ;
		
			$row = getRowCount($sql);
			if($row[0] > 1 ) {
				echo "<script>alert('Please check job schedule.')</script>";
				exit;
			}
			
			$session_cond = " AND job_session IN ('FULL') ";
			$sql = "SELECT COUNT(*) FROM job WHERE job_date='".$job_date."' AND employee_id='".$value."' AND project_id='".$project_id."' " .
				$session_cond . " AND id <> '".$id."' " ;
		
			$row = getRowCount($sql);
			if($row[0] > 0 ) {
				echo "<script>alert('Please check job schedule.')</script>";
				exit;
			}
			
	}
		
	$sql = " UPDATE job SET job_type = '$job_type', job_session = '$job_session', job_session_rates = '$job_session_rates', job_extra_hour = '$job_extra_hour', job_extra_hour_rates='$job_extra_hour_rates', travel_fee = '$travel_fee', parking_fee ='$parking_fee', tool_fee ='$tool_fee',time='$time' WHERE id = '$id'  ";

	pQuery($sql,"update");
	
	// insert history info.
	if ($id) {
		$sql = "INSERT INTO history (history_table, history_table_id, account_id,regdate) VALUES ('job','$id','$Sync_id','$now_datetimeano')";	
		pQuery($sql,"insert");
	}
	
	$str1 = "Update Completed!";
}
*/
?>
<script language="Javascript">
location.href="job_list.php?<?php echo $srch_param;?>";
</script>