<?php
include_once "include/common.inc";
include_once "include/dbconn.inc";
include_once "include/myfunc.inc";

if(!$_POST) exit;

$pp = $_POST['pp'];
$p = $_POST['p'];

$sql = "DELETE FROM parent_project WHERE parent_project_id='".$pp."' AND project_id = '".$p."'";
pQuery($sql,"delete");

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