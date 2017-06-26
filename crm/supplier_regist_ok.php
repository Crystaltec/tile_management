<?php
ob_start();
include_once "include/common.inc";
include_once "include/dbconn.inc";
include_once "include/myfunc.inc";

$supplier_id = $_REQUEST["supplier_id"];
$supplier_name =	$_REQUEST["supplier_name"];
$supplier_category = $_POST["supplier_category"];
$supplier_account = $_POST["supplier_account"];
$supplier_account_limitation = $_POST["supplier_account_limitation"];
$payment_term_id = $_POST["payment_term_id"];
$supplier_address =	$_REQUEST["supplier_address"];
$supplier_suburb = $_REQUEST["supplier_suburb"];
$supplier_state = $_REQUEST["supplier_state"];
$supplier_postcode = $_REQUEST["supplier_postcode"];
$supplier_phone_number = $_REQUEST["supplier_phone_number"];
$supplier_fax_number = $_REQUEST["supplier_fax_number"];
$supplier_web = $_REQUEST["supplier_web"];
$supplier_email = $_REQUEST["supplier_email"];
$supplier_sales_manager = $_REQUEST["supplier_sales_manager"];
$supplier_comments = $_REQUEST["supplier_comments"];

$action_type = $_REQUEST["action_type"];

if ($action_type=="") {
	$sql = "SELECT COUNT(*) FROM supplier WHERE supplier_name='".$supplier_name."'";
	$row = getRowCount($sql);
	if($row[0] > 0 ) {
		echo "<script>alert('This supplier name has already registered');history.back();</script>";
		exit;
	}

	$sql = "INSERT INTO supplier (supplier_category, supplier_name, supplier_account, supplier_account_limitation, payment_term_id, supplier_address, supplier_suburb, supplier_state, supplier_postcode, supplier_phone_number, supplier_fax_number, supplier_web, supplier_email, supplier_sales_manager, supplier_comments) VALUES('$supplier_category','$supplier_name', '$supplier_account','$supplier_account_limitation', '$payment_term_id','$supplier_address', '$supplier_suburb', '$supplier_state', '$supplier_postcode','$supplier_phone_number','$supplier_fax_number','$supplier_web','$supplier_email','$supplier_sales_manager','$supplier_comments')";
	pQuery($sql,"insert");
	$string1 = "Registration Completed!";

} else if($action_type=="modify") {
	$sql = "UPDATE supplier SET supplier_category='".$supplier_category."', supplier_name='" . $supplier_name . "', supplier_account='" . $supplier_account . "', supplier_account_limitation='" . $supplier_account_limitation . "', payment_term_id='". $payment_term_id . "', supplier_address='".$supplier_address . "', supplier_suburb='" .$supplier_suburb . "', supplier_state='".$supplier_state . "', supplier_postcode='".$supplier_postcode . "', supplier_phone_number='".$supplier_phone_number . "', supplier_fax_number='".$supplier_fax_number . "', supplier_web='".$supplier_web . "', supplier_email='".$supplier_email  . "', supplier_sales_manager='".$supplier_sales_manager . "', supplier_comments='".$supplier_comments ."' WHERE supplier_id=" . $supplier_id;
	
	pQuery($sql,"update");

	$string1 = "Update Completed!";
}

?>
<script>
alert("<?=$string1?>");
location.href="supplier_list.php";
</script>
<? ob_flush(); ?>