<?php
ob_start();
include_once "include/common.inc";
include_once "include/dbconn.inc";
include_once "include/myfunc.inc";

$order_notice_name =	$_REQUEST["order_notice_name"];
$order_notice_id = $_REQUEST["order_notice_id"];
$sortno = $_REQUEST["sortno"];
$action_type = $_REQUEST["action_type"];

if ($action_type=="") {
	$sql = "INSERT INTO order_notice (order_notice_name, sortno ) VALUES('$order_notice_name','$sortno')";
	pQuery($sql,"insert");
	$string1 = "Registation Completed!";
} else if($action_type=="modify") {
	$sql = "UPDATE order_notice SET order_notice_name='" . $order_notice_name . "', sortno='" . $sortno ."' WHERE order_notice_id=" . $order_notice_id;
	pQuery($sql,"update");

	$string1 = "Update Completed!";
}

?>
<script>
alert("<?=$string1?>");
location.href="order_notice_list.php";
</script>
<?php ob_flush(); ?>