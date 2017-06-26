<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<?php
ob_start();
include_once "include/common.inc";
include_once "include/dbconn.inc";
include_once "include/myfunc.inc";



$tender_no = $_REQUEST["tender_no"];
$submitted_date = getAUDateToDB($_REQUEST["submitted_date"]);
$due_date =$_REQUEST["due_date"];
$address = $_REQUEST["address"];
$builder_id = $_REQUEST["builder_id"];
$project_name = $_REQUEST["project_name"];
$contact_name  = $_REQUEST["contact_name"];
$tender_status = $_REQUEST["tender_status"];
$contact_no = $_REQUEST["contact_no"];
$tender_quote = $_REQUEST["tender_quote"];
$email_address = $_REQUEST["email_address"];
$note = $_REQUEST["note"];

$project_name = mysql_real_escape_string($project_name);
$address = mysql_real_escape_string($address);
$due_date = mysql_real_escape_string($due_date);

						
$action_type = $_REQUEST["action_type"];

if ($action_type=="") {



	$sql = "INSERT INTO tender_manage (submitted_date, builder_id, project_name, address, contact_name, due_date, tender_status, tender_quote, contact_no, email_address, note)
	 VALUES('$submitted_date', '$builder_id', '$project_name', '$address', '$contact_name', '$due_date', '$tender_status' , '$tender_quote' , '$contact_no' , '$email_address' , '$note') ";


	//echo $sql;
	pQuery($sql,"insert");
	$string1 = "Registration Completed!";
	
	echo "<script language='javascript'>
		alert('Successfully Added!');
		</script>";
	echo "<script>location.href='tender_manage_list.php?';</script>";
} 
else if($action_type=="modify") 
{

		
		$query = "update tender_manage set  submitted_date='$submitted_date' , builder_id='$builder_id' , project_name='$project_name' , address='$address' , contact_name='$contact_name', due_date='$due_date' ,  tender_status='$tender_status' ,tender_quote='$tender_quote' , contact_no='$contact_no' , email_address='$email_address' , note='$note' where tender_no='$tender_no'";

mysql_query($query);
		
		
		
	echo "<script language='javascript'>
		alert('Successfully Modified!');
		</script>";

		echo "<script>location.href='tender_manage_list.php?builder_id=$builder_id';</script>";
		
	}
	$string1 = "Update Completed!";


?>
<script>
//alert("<?=$string1?>");

</script>
<? ob_flush(); ?>