<?php

include_once "include/common.inc";
include_once "include/dbconn.inc";
include_once "include/myfunc.inc";

if (!$_REQUEST['term']) exit;
$cond = "";
if ($_REQUEST['sid']) {
	$cond = " AND supplier_id = '".$_REQUEST['sid']."'";
}
$sql = " select * from material where material_name like '%" . secure_string($_REQUEST['term']) . "%'  " .$cond. " order by material_name";
$result = mysql_query($sql) or exit(mysql_error());
$return_arr = array();
while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
		$row_array['label'] = $row['material_name'] . " | " . $row['material_code_number'] . " | " . $row['material_factory_number'] . " | " . $row['material_price'] . " | " . $row['material_color'] . " | " .  $row['material_size'] . " | " .getName("unit",$row["unit_id"]);
	    $row_array['value'] = $row['material_id'];
	    array_push($return_arr,$row_array);
 }

mysql_free_result($result);


echo __json_encode($return_arr);

?>