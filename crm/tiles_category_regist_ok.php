<?
ob_start();
include_once "include/common.inc";
include_once "include/dbconn.inc";
include_once "include/myfunc.inc";

$tiles_category_name =	$_REQUEST["tiles_category_name"];
$tiles_category_id = $_REQUEST["tiles_category_id"];
$action_type = $_REQUEST["action_type"];

if ($action_type=="") {
	$sql = "INSERT INTO tiles_category (tiles_category_name, regdate) VALUES('$tiles_category_name', now())";
	pQuery($sql,"insert");
	$string1 = "Registration Completed!";
} else if($action_type=="modify") {
	$sql = "UPDATE tiles_category SET tiles_category_name='" . $tiles_category_name . "' WHERE tiles_category_id=" . $tiles_category_id;
	pQuery($sql,"update");

	//echo $sql;
	
	
	//echo "<Br>" . $sql;

	$string1 = "Update Completed!";
}

?>
<script>
alert("<?=$string1?>");
location.href="tiles_category_list.php";
</script>
<? ob_flush(); ?>