<?php
include_once "include/logincheck.inc";
include_once "include/common.inc";
include_once "include/dbconn.inc";
include_once "include/myfunc.inc";

if(!$_POST) exit;
$output = array("result" => "ERROR");
// id & date
if ($_POST['id'] && $_POST['date']) {
	$sql = "DELETE FROM job WHERE project_id = '". $_POST['id']."' AND job_date ='".$_POST['date']."'";	
	//echo $sql;
	pQuery($sql,"insert");
	$output = array('result'=>'OK');
} else {
	$output = array("result" => "ERROR");
}

echo __json_encode($output);
?>