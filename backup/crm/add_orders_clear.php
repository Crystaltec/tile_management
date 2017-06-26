<?php
include_once "include/common.inc";
include_once "include/dbconn.inc";
include_once "include/myfunc.inc";

if(!$_POST) exit;


$oid = secure_string($_POST['oid']); 
$mid = secure_string($_POST['mid']);
$qty = secure_string($_POST['qty']);
$date = getAUDateToDB($_POST['date']);

if ($oid && $mid && $qty && date) {
	//$sql = "SELECT COUNT(*) FROM orders_clear WHERE orders_id = '$oid' AND material_id = '$mid'";
	//$rows = getRowCount($sql);
	//if($rows[0] > 0) {
	//	echo "ERROR";
	//	exit;
	//}
	$sql = "INSERT INTO orders_clear (orders_id, material_id, orders_clear_qty, orders_clear_date, account_id ,regdate) VALUES ( '$oid', '$mid', '$qty', '$date', '$Sync_id','$now_datetimeano')";
	//echo $sql;
	pQuery($sql,"insert");
	echo "SUCCESS";

} else {
	echo "ERROR";
}


?>
