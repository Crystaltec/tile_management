<?php

include_once "include/common.inc";
include_once "include/dbconn.inc";
include_once "include/myfunc.inc";

if (!$_REQUEST['term']) exit;


$sql = " SELECT * from project where project_name like '%" . secure_string($_REQUEST['term']) . "%' AND project_status <> 'COMPLETED'  ORDER BY project_name";
$result = mysql_query($sql) or exit(mysql_error());
$return_arr = array();
while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
		$row_array['label'] = $row['project_name'] . " | " . $row['project_status'];
	    $row_array['value'] = $row['project_name'];
		$row_array['id'] = $row['project_id'];
	    array_push($return_arr,$row_array);
 }

mysql_free_result($result);


echo __json_encode($return_arr);

?>