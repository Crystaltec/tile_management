<?php
include_once "include/common.inc";
include_once "include/dbconn.inc";
include_once "include/myfunc.inc";

if(!$_POST) exit;
if(!$_POST['transaction_date']) exit;

if(!$_POST['transaction_period_start']) exit;

$transaction_date = getAUDateToDB($_POST["transaction_date"]);
$transaction_period_start = getAUDateToDB($_POST["transaction_period_start"]);
$employee_id = $_POST['employee_id'];
$net_wages = $_POST['net_wages'];	
$deductions = $_POST['deductions'];	
$remarks = $_POST['remarks'];

$action_type = $_POST['action_type'];

if ($action_type == 'insert') {										
	if ($transaction_period_start && $employee_id) {
		/* 일주일간의 wage 계산 */
		$sql = " SELECT sum(IF(attendance='A',0,IF(job_session='FULL',1* job_session_rates * fw.wages_amount,IF(job_session='HALF',0.5*job_session_rates * fw.wages_amount,0)))+IF(attendance='A',0,(job_extra_hour * job_extra_hour_rates * hw.wages_amount)) + travel_fee+ parking_fee + tool_fee + extra_fee) as wages, " .
				" e.id as eid, e.employee_id, CONCAT_WS(', ',last_name, first_name ) as employee_name " .
				" FROM job j, wages fw, wages hw, employee e ".
				" WHERE j.employee_id = e.id AND  j.f_wages_id = fw.wages_id and j.h_wages_id = hw.wages_id and j.employee_id = fw.employee_id and j.employee_id = hw.employee_id AND job_date between '$transaction_period_start' AND date_add( '$transaction_period_start' ,interval 6 day) " .
				" AND j.employee_id = '".$employee_id."' ".
				" GROUP BY e.id ";
				
			
		$result = mysql_query($sql) or exit(mysql_error());
		while($row = mysql_fetch_assoc($result)) {
			if ( $row['wages'] >=0 && $row['eid'] ) {
				/* 같은 기간에 지불한 내역이 있는 확인 */	
				$sql_check = " SELECT COUNT(*) FROM transaction WHERE employee_id = '".$employee_id."' AND transaction_period_start ='".$transaction_period_start."' ";
				$row_check = getRowCount($sql_check);
				if ( $row_check[0]>0) {
					/* 
					 * 중복된게 있으면 무시
					 */
				} else {
					$sql_e = "INSERT INTO transaction ( employee_id, gross_wages, net_wages, deductions, transaction_period_start, transaction_date,transaction_remark, account_id, regdate ) " .
				 			" VALUES ( '".$employee_id."', '".$row['wages']."','".$net_wages."','".$deductions."','".$transaction_period_start."','".$transaction_date."','".$remarks."', '$Sync_id', '$now_datetimeano') " ;
					pQuery($sql_e,"insert");
				}	
			}
		}
		
		mysql_free_result($result);
		
		echo "SUCCESS";
	} else {
		echo "ERROR";
	} 
} elseif ($action_type == 'update' && $_POST['transaction_id']) {
	$gross_wages = $_POST['gross_wages'];
	$transaction_id = $_POST['transaction_id'];
	$sql = " UPDATE transaction SET  transaction_date= '$transaction_date', transaction_period_start = '$transaction_period_start', gross_wages = '$gross_wages', transaction_remark='$remarks', net_wages='$net_wages', deductions='$deductions' WHERE transaction_id = '$transaction_id'  ";
	pQuery($sql,"update");
	
	// insert history info.
	if ($id) {
		$sql = "INSERT INTO history (history_table, history_table_id, account_id,regdate) VALUES ('transaction','$transaction_id','$Sync_id','$now_datetimeano')";	
		pQuery($sql,"insert");
	}
	echo "SUCCESS";
} else {
	echo "ERROR";
}

?>
