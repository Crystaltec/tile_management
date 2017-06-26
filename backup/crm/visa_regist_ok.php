<?php
ob_start();
include_once "include/common.inc";
include_once "include/dbconn.inc";
include_once "include/myfunc.inc";

$visa_name =	$_REQUEST["visa_name"];
$visa_id = $_REQUEST["visa_id"];
$action_type = $_REQUEST["action_type"];

if ($action_type=="") {
	// insert new visa name
	$sql = "INSERT INTO visa (visa_name, account_id, regdate) VALUES('$visa_name','$Sync_id','$now_datetimeano')";
	pQuery($sql,"insert");
	
	
	
	
	$string1 = "Registration Completed!";
} else if($action_type=="modify") {
	$sql = "UPDATE visa SET visa_name='" . $visa_name . "' WHERE visa_id=" . $visa_id;
	pQuery($sql,"update");

	// insert history info.
	if ($visa_id) {
		$sql = "INSERT INTO history (history_table, history_table_id, account_id,regdate) VALUES ('visa','$visa_id','$Sync_id','$now_datetimeano')";	
		pQuery($sql,"insert");
	}


	$string1 = "Update Completed!";
}

?>
<script>
alert("<?=$string1?>");
location.href="visa_list.php";
</script>
<? ob_flush(); ?>