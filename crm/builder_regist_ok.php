<?
ob_start();
include_once "include/common.inc";
include_once "include/dbconn.inc";
include_once "include/myfunc.inc";

$builder_id = $_REQUEST["builder_id"];
$builder_name =	$_REQUEST["builder_name"];
$builder_address =	$_REQUEST["builder_address"];
$builder_suburb = $_REQUEST["builder_suburb"];
$builder_state = $_REQUEST["builder_state"];
$builder_postcode = $_REQUEST["builder_postcode"];
$builder_phone_number = $_REQUEST["builder_phone_number"];
$builder_fax_number = $_REQUEST["builder_fax_number"];
$builder_web = $_REQUEST["builder_web"];
$builder_email = $_REQUEST["builder_email"];
$builder_sales_manager = $_REQUEST["builder_sales_manager"];
$builder_comments = $_REQUEST["builder_comments"];

$action_type = $_REQUEST["action_type"];

if ($action_type=="") {
	$sql = "INSERT INTO builder (builder_name, builder_address, builder_suburb, builder_state, builder_postcode, builder_phone_number, builder_fax_number, builder_web, builder_email, builder_sales_manager, builder_comments, regdate) VALUES('$builder_name', '$builder_address', '$builder_suburb', '$builder_state', '$builder_postcode','$builder_phone_number','$builder_fax_number','$builder_web','$builder_email','$builder_sales_manager','$builder_comments', now())";
	pQuery($sql,"insert");
	$string1 = "Registration Completed!";

} else if($action_type=="modify") {
	$sql = "UPDATE builder SET builder_name='" . $builder_name . "', builder_address='".$builder_address . "', builder_suburb='" .$builder_suburb . "', builder_state='".$builder_state . "', builder_postcode='".$builder_postcode . "', builder_phone_number='".$builder_phone_number . "', builder_fax_number='".$builder_fax_number . "', builder_web='".$builder_web . "', builder_email='".$builder_email  . "', builder_sales_manager='".$builder_sales_manager . "', builder_comments='".$builder_comments ."' WHERE builder_id=" . $builder_id;
	pQuery($sql,"update");

	//echo $sql;
	
	// Product Infomation Update!
	//$sql = "UPDATE products SET builder_name='" . $builder_name . "', builder_address='".$builder_address."' WHERE builder_id=" . $builder_id;
	//pQuery($sql,"update");

	//echo "<Br>" . $sql;

	$string1 = "Update Completed!";
}

?>
<script>
alert("<?=$string1?>");
location.href="builder_list.php";
</script>
<? ob_flush(); ?>