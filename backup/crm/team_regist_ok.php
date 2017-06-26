<?php
include_once "include/logincheck.inc";
include_once "include/common.inc";
include_once "include/dbconn.inc";
include_once "include/myfunc.inc";

$employee_team_name = $_REQUEST["employee_team_name"];
$employee_id = $_REQUEST["employee_id"];
$remarks=htmlspecialchars($_REQUEST["remarks"], ENT_QUOTES);
$account_id = $Sync_id;
$action_type = $_REQUEST["action_type"];


if($action_type=="") {
	$sql5 = "SELECT IF(MAX(employee_team_id),MAX(employee_team_id)+1,1) FROM employee_team ";
	$row_id = getRowCount($sql5);
		
	foreach($employee_id as $value) {
		$sql = "SELECT COUNT(*) FROM employee_team WHERE  employee_id='".$value."' AND employee_team_id='".$employee_team_id."' ";
	
		$row = getRowCount($sql);
		if($row[0] > 0 ) {
			echo "<script>alert('The employee already has placed in this team.')</script>";
			break;
		}
		
		$sql = "INSERT INTO employee_team (employee_team_id, employee_team_name, employee_id, remarks,account_id, regdate) ";
		$sql .= "VALUES ('";
		$sql .= $row_id[0] ."', '";
		$sql .= $employee_team_name . "', '";
		$sql .= $value . "', '";
		$sql .= $remarks . "','";
		$sql .= $account_id . "', '";
		$sql .= $now_datetimeano ."')";
		pQuery($sql,"insert");
	}
		
	$str1 = "Registration Completed!";
	//echo $sql;
		
} 
?>
<script language="Javascript">
alert("<?=$str1?>");
location.href="team_list.php";
</script>