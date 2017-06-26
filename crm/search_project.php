<?php
include_once "include/common.inc";
include_once "include/dbconn.inc";
include_once "include/myfunc.inc";

if(!$_POST) exit;
if(!$_POST['q']) exit;

$opt = "";

$sql = "SELECT project_id, project_name ";
$sql .= " FROM project where project_name like '%".$_POST['q']."%' ORDER BY project_name  ";
$result = mysql_query($sql) or exit(mysql_error());
if ($result) {
	while ($row = mysql_fetch_assoc($result)) {
		$opt .="<option value=".$row['project_id'].">".$row['project_name']."</option>";
	}
} 

mysql_free_result($result);

if ($opt) {
	echo $opt;
} else {
	$opt2;
	$sql2 = "SELECT project_id, project_name ";
	$sql2 .= " FROM project ORDER BY project_name ";
	$result2 = mysql_query($sql2) or exit(mysql_error());
	if ($result2) {
		while ($row2 = mysql_fetch_assoc($result2)) {
			$opt2 .="<option value=".$row2['project_id'].">".$row2['project_name']."</option>";
		}
	} 

	mysql_free_result($result2);
	echo "<script>alert('No match found');</script>";
	echo $opt2;
}

?>
