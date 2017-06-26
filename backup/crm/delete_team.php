<?php
include_once "include/common.inc";
include_once "include/dbconn.inc";
include_once "include/myfunc.inc";

if(!$_POST) exit;

$pp = $_POST['pp'];
$p = $_POST['p'];

$sql = "DELETE FROM employee_team WHERE employee_team_id='".$pp."' AND employee_id = '".$p."'";
pQuery($sql,"delete");

$msg = '';

$employee_team_sql = "SELECT et.employee_id, CONCAT_WS(', ',e.last_name, e.first_name ) as employee_name  FROM employee_team et, employee e WHERE et.employee_team_id = '".$pp."' AND e.id = et.employee_id ";
							
//echo $employee_team_sql;
$employee_team_result =  mysql_query($employee_team_sql) or exit(mysql_error());
while ($row = mysql_fetch_assoc($employee_team_result)) {
	if ($row['employee_name']) 
		$msg .= "<span style='margin-right:20px;'>".$row['employee_name']. "<span onclick='delete_team(".$pp.",".$row['employee_id'].")' class='ui-icon ui-icon-close ' style='display:inline-block !important;'></span></span>";
}

mysql_free_result($employee_team_result);
	
echo $msg;


?>