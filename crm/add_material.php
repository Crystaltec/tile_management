<?php
include_once "include/common.inc";
include_once "include/dbconn.inc";
include_once "include/myfunc.inc";

if(!$_POST) exit;
if(!$_POST['name']) exit;

$opt = "";

$cid = secure_string($_POST['cid']); 
$name = secure_string($_POST['name']);
$cn = secure_string($_POST['cn']);
$fn = secure_string($_POST['fn']);
$color = secure_string($_POST['color']);
$size = secure_string($_POST['size']);
$uid = secure_string($_POST['uid']);
$price = secure_string($_POST['price']);
$sid = secure_string($_POST['sid']);
							
if ($cid) {
	$sql = "SELECT COUNT(*) FROM material WHERE material_code_number='$cn'";
	$rows = getRowCount($sql);
	if($rows[0] > 0) {
		echo "ERROR";
		exit;
	}
	$sql = "INSERT INTO material (category_id, material_name, material_code_number, material_factory_number, material_color, material_size, unit_id, material_price, supplier_id, regdate) VALUES ( '$cid', '$name', '$cn', '$fn', '$color','$size', '$uid', '$price', '$sid', '$now_datetimeano')";
	pQuery($sql,"insert");
	echo "SUCCESS";

} 


?>
