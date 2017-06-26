<?php
include_once "include/logincheck.inc";
include_once "include/common.inc";
include_once "include/dbconn.inc";
include_once "include/myfunc.inc";

if(!$_POST) exit;
$pp = $_REQUEST['pp'];
$parent_project_name = $_REQUEST["parent_project_name"];
$remarks = $_REQUEST['remarks'];
$project_id = $_REQUEST["project_id"];
$account_id = $Sync_id;

if($pp <> "") {
		
	foreach($project_id as $value) {
		$sql = "SELECT COUNT(*) FROM parent_project WHERE  project_id='".$value."' AND parent_project_id='".$pp."' ";
	
		$row = getRowCount($sql);
		if($row[0] > 0 ) {
			
		} else {
			$sql = "INSERT INTO parent_project (parent_project_id, parent_project_name, project_id, remarks,account_id, regdate) ";
			$sql .= "VALUES ('";
			$sql .= $pp ."', '";
			$sql .= $parent_project_name . "', '";
			$sql .= $value . "', '";
			$sql .= $remarks . "','";
			$sql .= $account_id . "', '";
			$sql .= $now_datetimeano ."')";
			pQuery($sql,"insert");
		}
	}
	
} 

$msg = '';

$child_project_sql = "SELECT p.project_id, p.project_name  FROM parent_project pp, project p WHERE pp.parent_project_id = '".$pp."' AND p.project_id = pp.project_id ";
							
//echo $child_project_sql;
$child_project_result =  mysql_query($child_project_sql) or exit(mysql_error());
while ($row = mysql_fetch_assoc($child_project_result)) {
	if ($row['project_name']) 
		$msg .= "<span style='margin-right:20px;'>".$row['project_name']. "<span onclick='delete_project(".$pp.",".$row['project_id'].")' class='ui-icon ui-icon-close ' style='display:inline-block !important;'></span></span>";
}

mysql_free_result($child_project_result);
	
echo $msg;

?>