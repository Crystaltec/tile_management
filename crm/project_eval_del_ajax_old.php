<?php
include_once "include/logincheck.inc";
include_once "include/common.inc";
include_once "include/dbconn.inc";
include_once "include/myfunc.inc";

	if (!$_POST) exit;
	
	if ($_POST['pid']) {
		$sql = " DELETE FROM project_eval WHERE project_eval_id = '".$_POST['pid'] . "'";
		pQuery($sql,'DELETE');
		echo "SUCCESS";
	} else {
		echo "ERROR";
	} 

?>