<?php
include_once "include/logincheck.inc";
include_once "include/common.inc";
include_once "include/dbconn.inc";
include_once "include/myfunc.inc";

	if (!$_POST) exit;
	
	$_POST['value'] = str_replace(array('%','$',','),'',$_POST['value']);
	
	$project_eval_id = split('project_eval_id_',$_POST['id']);
	
	if ($project_eval_id[1] && $_POST['value'] && $_POST['column_name']) {
		$sql = " UPDATE project_eval SET ". $_POST['column_name'] . " = '" . $_POST['value'] . 
		"' WHERE project_eval_id = '".$project_eval_id[1] . "'";
		pQuery($sql,'UPDATE');
	}

?>