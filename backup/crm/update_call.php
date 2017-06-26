<?php
include_once "include/logincheck.inc";
include_once "include/common.inc";
include_once "include/dbconn.inc";
include_once "include/myfunc.inc";

if(!$_POST) exit;

if ($_POST['id'] && $_POST['val']) {
		if ($_POST['val'] == 'Y') {
			$sql = "UPDATE job SET check_call = 'N' WHERE id= '".$_POST['id']."' ";
			//echo $sql;
			pQuery($sql,"update");
			$output = array('check_call'=>'N');
		} else if ($_POST['val'] == 'N') {
			$sql = "UPDATE job SET check_call = 'Y' WHERE id= '".$_POST['id']."' ";
			//echo $sql;
			pQuery($sql,"update");
			$output = array('check_call'=>'Y');
		}

} else {
	$output = array("check_call" => "ERROR");
}

echo __json_encode($output);
?>