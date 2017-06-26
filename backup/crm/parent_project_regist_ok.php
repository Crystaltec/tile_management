<?php
include_once "include/logincheck.inc";
include_once "include/common.inc";
include_once "include/dbconn.inc";
include_once "include/myfunc.inc";

$parent_project_name = $_REQUEST["parent_project_name"];
$project_id = $_REQUEST["project_id"];
$remarks=htmlspecialchars($_REQUEST["remarks"], ENT_QUOTES);
$account_id = $Sync_id;
$action_type = $_REQUEST["action_type"];


if($action_type=="") {
	$sql5 = "SELECT IF(MAX(parent_project_id),MAX(parent_project_id)+1,1) FROM parent_project ";
	$row_id = getRowCount($sql5);
		
	foreach($project_id as $value) {
		$sql = "SELECT COUNT(*) FROM parent_project WHERE  project_id='".$value."' AND parent_project_id='".$parent_project_id."' ";
	
		$row = getRowCount($sql);
		if($row[0] > 0 ) {
			echo "<script>alert('The project already has placed in this parent project.')</script>";
			break;
		}
		
		$sql = "INSERT INTO parent_project (parent_project_id, parent_project_name, project_id, remarks,account_id, regdate) ";
		$sql .= "VALUES ('";
		$sql .= $row_id[0] ."', '";
		$sql .= $parent_project_name . "', '";
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
location.href="parent_project_list.php";
</script>