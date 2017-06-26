<?php
include_once "include/common.inc";
include_once "include/dbconn.inc";
include_once "include/myfunc.inc";

if(!$_POST) exit;
if(!$_POST['n_pay_start']) exit;

if(!$_POST['n_t_date']) exit;

$pay_start_date = getAUDateToDB($_POST["n_pay_start"]);
$transaction_date = getAUDateToDB($_POST["n_t_date"]);
							
if ($pay_start_date && $transaction_date) {
	/* 일주일간의 wage 계산 */
	$sql = " SELECT sum(IF(attendance='A',0,IF(job_session='FULL',1* job_session_rates * fw.wages_amount,IF(job_session='HALF',0.5*job_session_rates * fw.wages_amount,0)))+IF(attendance='A',0,(job_extra_hour * job_extra_hour_rates * hw.wages_amount)) + travel_fee+ parking_fee + tool_fee + extra_fee) as wages, " .
			" e.id as eid, e.employee_id, CONCAT_WS(', ',last_name, first_name ) as employee_name " .
			" FROM job j, wages fw, wages hw, employee e ".
			" WHERE j.employee_id = e.id AND  j.f_wages_id = fw.wages_id and j.h_wages_id = hw.wages_id and j.employee_id = fw.employee_id and j.employee_id = hw.employee_id AND job_date between '$pay_start_date' AND date_add( '$pay_start_date' ,interval 6 day) " .
			" GROUP BY e.id " .
			" ORDER BY CONCAT_WS(', ',last_name, first_name )";
		
	$result = mysql_query($sql) or exit(mysql_error());
	while($row = mysql_fetch_assoc($result)) {
		if ( $row['wages'] >=0 && $row['eid'] ) {
			/* 같은 기간에 지불한 내역이 있는 확인 */	
			$sql_check = " SELECT COUNT(*) FROM transaction WHERE employee_id = '".$row['eid']."' AND transaction_period_start ='".$pay_start_date."' ";
			$row_check = getRowCount($sql_check);
			if ( $row_check[0]>0) {
				/* 
				 * 중복된게 있으면 무시
				 */
			} else {
				$sql_e = "INSERT INTO transaction ( employee_id, gross_wages, net_wages, transaction_period_start, transaction_date,transaction_remark, account_id, regdate ) " .
			 			" VALUES ( '".$row['eid']."', '".$row['wages']."','".$row['wages']."','".$pay_start_date."','".$transaction_date."','added by multiple entry', '$Sync_id', '$now_datetimeano') " ;
				pQuery($sql_e,"insert");
			}	
		}
	}
	
	mysql_free_result($result);
	
	echo "SUCCESS";
} else {
	echo "ERROR";
} 

?>
