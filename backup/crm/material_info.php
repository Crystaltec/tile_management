<?php
ob_start();
include_once "include/common.inc";
include_once "include/dbconn.inc";
$req_userid=$_GET["id"];
$index = $_GET["index"];

$sql = "SELECT a.*, b.unit_name FROM material a LEFT JOIN unit b ON a.unit_id = b.unit_id WHERE material_id='$req_userid' ";
//echo $sql;
$result = mysql_query($sql) or exit(mysql_error());
while($rows = mysql_fetch_assoc($result)) {
	$material_id = $rows["material_id"];
	$material_name = $rows["material_name"];
	$material_price = $rows["material_price"];
	$unit_id = $rows["unit_name"];
	$material_code_number = $rows["material_code_number"];
	$material_color = $rows["material_color"];
	$material_size = $rows["material_size"];
	$material_factory_number = $rows["material_factory_number"];
}
mysql_free_result($result);
//echo $fax;

$json_resp = "{";
$json_resp.="\"material_id".$index."\":\"".$material_id."\",";
$json_resp.="\"material_name".$index."\":\"".$material_name."\",";
$json_resp.="\"material_price".$index."\":\"".$material_price."\",";
$json_resp.="\"unit_id".$index."\":\"".$unit_id."\",";
$json_resp.="\"material_factory_number".$index."\":\"".$material_factory_number."\",";
$json_resp.="\"material_code_number".$index."\":\"".$material_code_number."\",";
$json_resp.="\"material_color".$index."\":\"".$material_color."\",";
$json_resp.="\"material_size".$index."\":\"".$material_size."\"}";

echo $json_resp;
ob_flush();
?>