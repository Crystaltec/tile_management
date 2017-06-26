<?php
include_once "include/logincheck.inc";
include_once "include/common.inc";
include_once "include/dbconn.inc";
include_once "include/myfunc.inc";

if(!$_POST) exit;
$pp = $_REQUEST['pp'];
$employee_team_name = $_REQUEST["employee_team_name"];
$remarks = $_REQUEST['remarks'];
$employee_id = $_REQUEST["employee_id"];
$account_id = $Sync_id;

if($pp <> "") {
		
	foreach($employee_id as $value) {
		$sql = "SELECT COUNT(*) FROM employee_team WHERE  employee_id='".$value."' AND employee_team_id='".$pp."' ";
	
		$row = getRowCount($sql);
		if($row[0] > 0 ) {
			
		} else {
			$sql = "INSERT INTO employee_team (employee_team_id, employee_team_name, employee_id, remarks,account_id, regdate) ";
			$sql .= "VALUES ('";
			$sql .= $pp ."', '";
			$sql .= $employee_team_name . "', '";
			$sql .= $value . "', '";
			$sql .= $remarks . "','";
			$sql .= $account_id . "', '";
			$sql .= $now_datetimeano ."')";
			pQuery($sql,"insert");
		}
	}
	
} 

$msg = '';

$child_project_sql = "SELECT et.employee_id, CONCAT_WS(', ',e.last_name, e.first_name ) as employee_name  FROM employee_team et, employee e WHERE et.employee_team_id = '".$pp."' AND e.id = et.employee_id ORDER BY CONCAT_WS(', ',e.last_name, e.first_name ) ";
							
//echo $child_project_sql;
$child_project_result =  mysql_query($child_project_sql) or exit(mysql_error());
while ($row = mysql_fetch_assoc($child_project_result)) {
	if ($row['employee_name']) 
		$msg .= "<span style='margin-right:20px;'>".$row['employee_name']. "<span onclick='delete_team(".$pp.",".$row['employee_id'].")' class='ui-icon ui-icon-close ' style='display:inline-block !important;cursor:pointer;'></span></span>";
}

mysql_free_result($child_project_result);
	
echo $msg;

?>