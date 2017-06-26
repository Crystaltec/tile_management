<?php
include_once "include/logincheck.inc";
include_once "include/common.inc";
include_once "include/dbconn.inc";
include_once "include/myfunc.inc";

if(!$_POST) exit;
$output = array("result" => "ERROR");
if ($_POST['id'] && $_POST['val'] && $_POST['date']) {
	/* 같은 기간에 입력한 내역이 있는 확인 */	
	$sql_check = " SELECT COUNT(*) FROM allowance WHERE employee_id = '".$_POST['id']."' AND month_year ='".$_POST['date']."' ";
	$row_check = getRowCount($sql_check);
	if ( $row_check[0]>0) {
		/* 
		 * 중복된게 있으면 무시
		 */
	} else {
		$sql_e = "INSERT INTO allowance ( employee_id, amounts, month_year, account_id, regdate ) " .
	 			" VALUES ( '".$_POST['id']."', '".$_POST['val']."','".$_POST['date']."','$Sync_id', '$now_datetimeano') " ;
		pQuery($sql_e,"insert");
	}	
	
	$output = array('result'=>'OK');
			
} else {
	$output = array("result" => "ERROR");
}

echo __json_encode($output);
?>