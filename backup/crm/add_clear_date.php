<?php
ob_start();
include_once "include/logincheck.inc";
include_once "include/common.inc";
include_once "include/dbconn.inc";
include_once "include/myfunc.inc";

if(!$_POST) exit;
if(!$_POST['p_pon']) exit;

$p_pon = secure_string($_POST['p_pon']); 

$n_clear_date = getAUDateToDB($_POST["n_clear_date"]);

if ($p_pon ) {
	
	$sql = " UPDATE orders SET clear_date = '$n_clear_date' WHERE orders_number = '$p_pon' ";
	pQuery($sql,"update");
	//echo $sql;
	echo "SUCCESS";
} else {
	echo "ERROR";
} 

ob_flush();
?>
