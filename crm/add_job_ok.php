<?php
include_once "include/common.inc";
include_once "include/dbconn.inc";
include_once "include/myfunc.inc";

if(!$_POST) exit;

$action_type = $_POST["action_type"];
$id = $_POST["job_id"];
$project_id = $_POST["project_id"];
$employee_id =	$_POST["employee_id"];
$job_type = $_POST["job_type"];
$job_date = getAUDateToDB($_POST["job_date"]);
$job_session= $_POST["job_session"];
$job_session_rates = $_POST["job_session_rates"];
$job_extra_hour = $_POST["job_extra_hour"];
$job_extra_hour_rates = $_POST["job_extra_hour_rates"];
$attendance = $_POST["attendance"];
$tool_fee = $_POST['tool_fee'];
$travel_fee = $_POST['travel_fee'];
$parking_fee = $_POST['parking_fee'];
$remarks=htmlspecialchars($_POST["remarks"], ENT_QUOTES);
$extra_fee = $_POST['extra_fee'];
//$use_vehicle = $_POST['use_vehicle'];
$job_session_rates_txt = $_REQUEST["job_session_rates_txt"];
$job_extra_hour_rates_txt = $_REQUEST["job_extra_hour_rates_txt"];
$time = $_REQUEST["time"];

if($use_vehicle <> 'Y') {
	$use_vehicle = 'N';
}
$account_id = $Sync_id;


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

$msg = '';
if ($action_type == "update") {
	if ($id) {
		$sql = " UPDATE job set job_type = '$job_type', project_id = '$project_id', job_session = '$job_session', job_session_rates = '$job_session_rates', job_extra_hour = '$job_extra_hour', job_extra_hour_rates='$job_extra_hour_rates', attendance = '$attendance', travel_fee = '$travel_fee', parking_fee ='$parking_fee', tool_fee ='$tool_fee', extra_fee ='$extra_fee', time = '$time', remarks='$remarks'  WHERE id = '$id'  ";
		
		pQuery($sql,"update");
	}
} else if ($action_type == "delete") {
	if ($id) {
		$sql = " DELETE FROM job WHERE id = '$id' and project_id = '$project_id' ";
		
		pQuery($sql,"delete");
	}

} else if ($action_type == "insert") {
	if($employee_id) {
		if ($job_session == 'FULL' && $job_session_rates <> "") {
			$session_cond = " AND job_session IN ('FULL','HALF') ";
			$sql = "SELECT COUNT(*) FROM job WHERE job_date='".$job_date."' AND employee_id='".$employee_id."' ".
				$session_cond;
		
			$row = getRowCount($sql);
			if($row[0] > 0 ) {
				$msg = "ERROR";
				break;
			}
		} else if ($job_session == 'HALF' && $job_session_rates <> "") {
			$session_cond = " AND job_session IN ('HALF') ";
			$sql = "SELECT COUNT(*) FROM job WHERE job_date='".$job_date."' AND employee_id='".$employee_id."' ".
				$session_cond;
		
			$row = getRowCount($sql);
			if($row[0] > 1 ) {
				$msg = "ERROR";
				break;
			}
			
			$session_cond = " AND job_session IN ('FULL') ";
			$sql = "SELECT COUNT(*) FROM job WHERE job_date='".$job_date."' AND employee_id='".$employee_id."' ".
				$session_cond;
		
			$row = getRowCount($sql);
			if($row[0] > 0 ) {
				$msg = "ERROR";
				break;
			}
			
		}
		
		$f_wage_id = getValue("employee", "current_ft_wage_id", " and id = '$employee_id' ORDER BY regdate desc limit 1 ");
		$h_wage_id = getValue("employee", "current_hr_wage_id", " and id = '$employee_id' ORDER BY regdate desc limit 1 ");
		
		$sql = "INSERT INTO job (job_id, job_type, project_id, employee_id, job_date, job_session, job_session_rates, job_extra_hour, job_extra_hour_rates, attendance, remarks,account_id, regdate, f_wages_id, h_wages_id, travel_fee, parking_fee, tool_fee, time, extra_fee) ";
		$sql .= "VALUES ('";
		$sql .= $job_id . "', '";
		$sql .= $job_type . "', '";
		$sql .= $project_id . "', '";
		$sql .= $employee_id . "', '";
		$sql .= $job_date . "', '";
		$sql .= $job_session ."', '";
		$sql .= $job_session_rates ."', '";
		$sql .= $job_extra_hour ."', '";
		$sql .= $job_extra_hour_rates ."', '";
		$sql .= $attendance ."', '";
		$sql .= $remarks . "','";
		$sql .= $account_id . "', '";
		$sql .= $now_datetimeano ."', '";
		$sql .= $f_wage_id . "', '";
		$sql .= $h_wage_id ."', '";
		$sql .= $travel_fee ."', '";
		$sql .= $parking_fee ."', '";
		$sql .= $tool_fee ."', '";
		$sql .= $time ."', '";
		$sql .= $extra_fee ."'   )";
		
		pQuery($sql,"insert");
	}
}


echo $msg;


?>