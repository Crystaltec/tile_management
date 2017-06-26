<?php
ob_start();
include_once "include/common.inc";
include_once "include/dbconn.inc";
include_once "include/myfunc.inc";

$category_name =	$_REQUEST["category_name"];
$category_id = $_REQUEST["category_id"];
$action_type = $_REQUEST["action_type"];

if ($action_type=="") {
	$sql = "INSERT INTO category (category_name, regdate) VALUES('$category_name', now())";
	pQuery($sql,"insert");
	$string1 = "Registration Completed!";
} else if($action_type=="modify") {
	$sql = "UPDATE category SET category_name='" . $category_name . "' WHERE category_id=" . $category_id;
	pQuery($sql,"update");

	//echo $sql;
	
	
	//echo "<Br>" . $sql;

	$string1 = "Update Completed!";
}

?>
<script>
alert("<?=$string1?>");
location.href="category_list.php";
</script>
<? ob_flush(); ?>