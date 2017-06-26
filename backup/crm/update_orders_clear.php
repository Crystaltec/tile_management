<?php
include_once "include/common.inc";
include_once "include/dbconn.inc";
include_once "include/myfunc.inc";

if(!$_POST) exit;

$cid = secure_string($_POST['cid']); 
$qty = secure_string($_POST['qty']);
$date = getAUDateToDB($_POST['date']);
$act = $_POST['act'];
if ($act == 'update') {
	if ($cid && $qty && $date) {
		$sql = "UPDATE orders_clear SET orders_clear_qty='$qty', orders_clear_date='$date' WHERE orders_clear_id = '$cid' ";
		pQuery($sql,"update");
		echo "SUCCESS";
	} else {
		echo "ERROR";
	}
} elseif($act=='delete') {
	if ($cid) {
		$sql = "DELETE FROM orders_clear WHERE orders_clear_id = '$cid' ";
		pQuery($sql,"delete");
		echo "SUCCESS";
	} else {
		echo "ERROR";
	}
} else {
	echo "ERROR";
}


?>
