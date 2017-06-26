<?php
ob_start();
include_once "include/common.inc";
include_once "include/dbconn.inc";
include_once "include/myfunc.inc";

$visa_status_name =	$_REQUEST["visa_status_name"];
$visa_status_id = $_REQUEST["visa_status_id"];
$action_type = $_REQUEST["action_type"];

if ($action_type=="") {
	// insert new visa status name
	$sql = "INSERT INTO visa_status (visa_status_name, account_id, regdate) VALUES('$visa_status_name','$Sync_id','$now_datetimeano')";
	pQuery($sql,"insert");
	
	
	
	
	$string1 = "Registration Completed!";
} else if($action_type=="modify") {
	$sql = "UPDATE visa_status SET visa_status_name='" . $visa_status_name . "' WHERE visa_status_id=" . $visa_status_id;
	pQuery($sql,"update");

	// insert history info.
	if ($visa_status_id) {
		$sql = "INSERT INTO history (history_table, history_table_id, account_id,regdate) VALUES ('visa_status','$visa_status_id','$Sync_id','$now_datetimeano')";	
		pQuery($sql,"insert");
	}


	$string1 = "Update Completed!";
}

?>
<script>
alert("<?=$string1?>");
location.href="visa_status_list.php";
</script>
<? ob_flush(); ?>