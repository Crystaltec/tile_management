<?php
include_once "include/logincheck.inc";
include_once "include/common.inc";
include_once "include/dbconn.inc";
include_once "include/myfunc.inc";

if(!$_POST) exit;

if ($_POST['id'] && $_POST['val']) {
		if ($_POST['val'] == 'A') {
			$sql = "UPDATE job SET attendance = 'P' WHERE id= '".$_POST['id']."' ";
			pQuery($sql,"update");
			$output = array('attendance'=>'P');
		} else if ($_POST['val'] == 'P') {
			$sql = "UPDATE job SET attendance = 'A' WHERE id= '".$_POST['id']."' ";
			pQuery($sql,"update");
			$output = array('attendance'=>'A');
		}
		
} else {
	$output = array("attendance" => "ERROR");
}

echo __json_encode($output);
?>