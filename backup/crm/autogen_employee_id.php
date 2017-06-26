<?php
include_once "include/common.inc";
include_once "include/dbconn.inc";
include_once "include/myfunc.inc";

if(!$_POST) exit;
if(!$_POST['hire_date']) exit;

$pre_string = 'S04';
$param_year = substr($_POST['hire_date'],8,2);

// 같은해 employee가 있는지 확인
$sql = "SELECT COUNT(*) FROM employee WHERE SUBSTRING(employee_id,1,5) = CONCAT('".$pre_string."','".$param_year."')";
$row = getRowCount2($sql);

// 있으면 기존 최대 employee ID 에서 1 추가
if ($row>0) {
	$sql2 = "SELECT CONCAT('".$pre_string."','".$param_year."',LPAD(SUBSTRING(MAX(employee_id),6) + 1,4,'0')) FROM employee WHERE SUBSTRING(employee_id,1,5) = CONCAT('".$pre_string."','".$param_year."')";
	$row2 = getRowCount2($sql2);
} 
// 없으면 해당해의 0001로 생성
else {
	$sql2 = "SELECT CONCAT('".$pre_string."','".$param_year."',LPAD(1,4,'0')) ";
	$row2 = getRowCount2($sql2);
}
if (strlen($row2) == 9) {
	// 새로 생성된 employee ID가 존재하는지 확인
	$sql3 = "SELECT COUNT(*) FROM employee WHERE employee_id = '".$row2."'";
	$row3 = getRowCount2($sql3);
	if ($sql3 == 0) {
		echo $row2;
	} 
	
	if (!$row2){
		echo "Error!";
	}
} else {
	echo "Error!";
}

?>