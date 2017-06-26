<?php

include_once "include/common.inc";
include_once "include/dbconn.inc";
include_once "include/myfunc.inc";

if (!$_REQUEST['term']) exit;


$sql = " SELECT CONCAT_WS(', ',last_name, first_name ) as employee_name, id, employee_id FROM employee where CONCAT_WS(', ',last_name, first_name ) like '%" . secure_string($_REQUEST['term']) . "%' AND (termination_date = '0000-00-00' OR termination_date > '$now_dateano') ORDER BY CONCAT_WS(', ',last_name, first_name )";
$result = mysql_query($sql) or exit(mysql_error());
$return_arr = array();
while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
		$row_array['label'] = $row['employee_name'] . " | " . $row['employee_id'];
	    $row_array['value'] = $row['employee_name'] . " | " . $row['employee_id'];
		$row_array['id'] = $row['id'];
	    array_push($return_arr,$row_array);
 }

mysql_free_result($result);


echo __json_encode($return_arr);

?>