<?php
ob_start();
include_once "include/common.inc";
include_once "include/dbconn.inc";
include_once "include/myfunc.inc";

$retention_no = $_REQUEST["retention_no"];
$claimed_date_1 = getAUDateToDB($_REQUEST["claimed_date_1"]);
$claimed_date_2 = getAUDateToDB($_REQUEST["claimed_date_2"]);
$received_date_1 = getAUDateToDB($_REQUEST["received_date_1"]);
$received_date_2 = getAUDateToDB($_REQUEST["received_date_2"]);
$builder_id = $_REQUEST["builder_id"];
$project_id = $_REQUEST["project_id"];
$received_amount_1 = $_REQUEST["received_amount_1"];
$received_amount_2  = $_REQUEST["received_amount_2"];
$claimed_amount_1 = $_REQUEST["claimed_amount_1"];
$claimed_amount_2 = $_REQUEST["claimed_amount_2"];
$note = $_REQUEST["note"];



				
$action_type = $_REQUEST["action_type"];

if ($action_type=="") {


 


	$sql = "INSERT INTO retention_list(claimed_date_1, builder_id, project_id, received_amount_1, received_amount_2, received_date_1, claimed_date_2, claimed_amount_2, received_date_2, claimed_amount_1, note)
	 VALUES('$claimed_date_1', '$builder_id', '$project_id', '$received_amount_1', '$received_amount_2', '$received_date_1', '$claimed_date_2' , '$claimed_amount_2' , '$received_date_2' , '$claimed_amount_1' , '$note') ";




	//echo $sql;
	pQuery($sql,"insert");
	$string1 = "Registration Completed!";
	
	echo "<script language='javascript'>
		alert('Successfully Added!');
		</script>";

} 
else if($action_type=="modify") 
{

		
		$query = "update retention_list set  claimed_date_1='$claimed_date_1' , builder_id='$builder_id' , project_id='$project_id' , received_amount_1='$received_amount_1' , received_amount_2='$received_amount_2', received_date_2='$received_date_2' , received_date_1='$received_date_1' , note='$note' ,  claimed_date_2='$claimed_date_2' , claimed_date_1='$claimed_date_1' , claimed_amount_2='$claimed_amount_2', claimed_amount_1='$claimed_amount_1' where retention_no='$retention_no'";

mysql_query($query);
		
		
		
	echo "<script language='javascript'>
		alert('Successfully Modified!');
		</script>";

		
		
	}
	$string1 = "Update Completed!";


?>
<script>
//alert("<?=$string1?>");
location.href="retention_list.php";
</script>
<? ob_flush(); ?>