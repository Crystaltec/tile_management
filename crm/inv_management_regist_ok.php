<?php
ob_start();
include_once "include/common.inc";
include_once "include/dbconn.inc";
include_once "include/myfunc.inc";

$inv_no = $_REQUEST["inv_no"];
$inv_date = getAUDateToDB($_REQUEST["inv_date"]);
$received_date = getAUDateToDB($_REQUEST["received_date"]);
$payment_due = getAUDateToDB($_REQUEST["payment_due"]);
$builder_id = $_REQUEST["builder_id"];
$project_id = $_REQUEST["project_id"];
$inv_amount  = $_REQUEST["inv_amount"];
$received_amount = $_REQUEST["received_amount"];
$diff_amount = $_REQUEST["diff_amount"];
$note = $_REQUEST["note"];
$invoice_number = $_REQUEST["invoice_number"];
$retention_amount = $_REQUEST["retention_amount"];
$advised_remit	= $_REQUEST["advised_remit"];
			
				
$action_type = $_REQUEST["action_type"];

if ($action_type=="") {

$diff_amount = $received_amount - $inv_amount;

 


	$sql = "INSERT INTO invoice_manage (invoice_number, inv_date, builder_id, project_id, payment_due, inv_amount, advised_remit, received_date, received_amount, retention_amount, diff_amount, note)
	 VALUES('$invoice_number' , '$inv_date', '$builder_id', '$project_id', '$payment_due','$inv_amount', '$advised_remit' ,'$received_date','$received_amount' , '$retention_amount' ,'$diff_amount' , '$note') ";




	//echo $sql;
	pQuery($sql,"insert");
	$string1 = "Registration Completed!";
	
	echo "<script language='javascript'>
		alert('Successfully Added!');
		</script>";
		
		echo "<script>location.href='inv_manage_list.php';</script>";

} 
else if($action_type=="modify") 
{
$diff_amount = $received_amount - $inv_amount;


		
		
		
		$query = "update invoice_manage set invoice_number='$invoice_number' , inv_date='$inv_date' , builder_id='$builder_id' , project_id='$project_id' , payment_due='$payment_due' , inv_amount='$inv_amount', received_date='$received_date' ,  received_amount='$received_amount' , diff_amount='$diff_amount' , retention_amount='$retention_amount' , note='$note' , advised_remit='$advised_remit' where inv_no='$inv_no'";

mysql_query($query);
		
		
		
	echo "<script language='javascript'>
		alert('Successfully Modified!');
		</script>";
		
		echo "<script>location.href='inv_manage_list.php?builder_id=$builder_id';</script>";

		
		
	}
	$string1 = "Update Completed!";


?>
<script>
//alert("<?=$string1?>");

</script>
<? ob_flush(); ?>