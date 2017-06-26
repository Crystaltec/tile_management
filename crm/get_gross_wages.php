<?php
include_once "include/common.inc";
include_once "include/dbconn.inc";
include_once "include/myfunc.inc";

if(!$_POST) exit;
if(!$_POST['pay_start']) exit;

$pay_start_date = getAUDateToDB($_POST["pay_start"]);
$eid = $_POST['eid'];							
if ($pay_start_date && $eid) {
	/* 일주일간의 wage 계산 */
	$sql = " SELECT sum(IF(attendance='A',0,IF(job_session='FULL',1* job_session_rates * fw.wages_amount,IF(job_session='HALF',0.5*job_session_rates * fw.wages_amount,0)))+IF(attendance='A',0,(job_extra_hour * job_extra_hour_rates * hw.wages_amount)) + travel_fee+ parking_fee + tool_fee + extra_fee) as wages, " .
			" e.id as eid, e.employee_id, CONCAT_WS(', ',last_name, first_name ) as employee_name " .
			" FROM job j, wages fw, wages hw, employee e ".
			" WHERE j.employee_id = e.id AND  j.f_wages_id = fw.wages_id and j.h_wages_id = hw.wages_id and j.employee_id = fw.employee_id and j.employee_id = hw.employee_id AND job_date between '$pay_start_date' AND date_add( '$pay_start_date' ,interval 6 day) " .
			" AND j.employee_id = ". $eid. 
			
			" ORDER BY CONCAT_WS(', ',last_name, first_name )";
		
	$result = getRowCount($sql);
	echo $result[0];
	
} else {
	echo "ERROR";
} 

?>
