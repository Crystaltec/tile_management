<?
ob_start();
include_once "include/common.inc";
include_once "include/dbconn.inc";
include_once "include/myfunc.inc";

$unit_name =	$_REQUEST["unit_name"];
$unit_id = $_REQUEST["unit_id"];
$action_type = $_REQUEST["action_type"];

if ($action_type=="") {
	$sql = "INSERT INTO unit (unit_name ) VALUES('$unit_name')";
	pQuery($sql,"insert");
	$string1 = "Registation Completed!";
} else if($action_type=="modify") {
	$sql = "UPDATE unit SET unit_name='" . $unit_name . "' WHERE unit_id=" . $unit_id;
	pQuery($sql,"update");

	//echo $sql;
	
	
	//echo "<Br>" . $sql;

	$string1 = "Update Completed!";
}

?>
<script>
alert("<?=$string1?>");
location.href="unit_list.php";
</script>
<? ob_flush(); ?>