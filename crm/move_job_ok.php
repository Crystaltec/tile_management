<?php
include_once "include/common.inc";
include_once "include/dbconn.inc";
include_once "include/myfunc.inc";

if(!$_POST) exit;

$action_type = $_POST["action_type"];
$prev_pid = $_POST["prev_pid"];
$prev_date = $_POST["prev_date"];
$project_id = $_POST["project_id"];
$job_date = getAUDateToDB($_POST["job_date"]);

$msg = '';
if ($action_type == "update") {
	if ($prev_pid && $prev_date && $project_id && $job_date ) {
		$sql = " UPDATE job set project_id = '$project_id', job_date = '$job_date'  WHERE project_id = '$prev_pid' AND job_date = '$prev_date'  ";
		$msg = "success";
		pQuery($sql,"update");
	} else {
		$msg = 'ERROR';
	}
} else {
	$msg = 'ERROR';
}

echo $msg;
?>