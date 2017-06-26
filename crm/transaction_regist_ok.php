<?php
include_once "include/logincheck.inc";
include_once "include/common.inc";
include_once "include/dbconn.inc";
include_once "include/myfunc.inc";

//$job_id =		$_REQUEST["job_id"];
$id = $_REQUEST["id"];
$employee_id =	$_REQUEST["employee_id"];

$transaction_date = getAUDateToDB($_REQUEST["transaction_date"]);
$transaction_period_start = getAUDateToDB($_REQUEST["transaction_period_start"]);
$gross_wages= $_REQUEST["gross_wages"];
$deductions = $_REQUEST["deductions"];
$net_wages = $_REQUEST["net_wages"];

$limitList = $_REQUEST["limitList"];

$s_resort_order = $_REQUEST["resort_order"];
$srch_param = "";
if($s_resort_order != ""){
	$srch_param .= "&resort_order=$s_resort_order";
}

if($limitList) {
	$srch_param .="&limitList=$limitList";
}
					
$remarks=htmlspecialchars($_REQUEST["remarks"], ENT_QUOTES);
$account_id = $Sync_id;

$action_type = $_REQUEST["action_type"];

if($action_type=="") {
	if ( $gross_wages >=0 && $employee_id ) {
	/* 같은 기간에 지불한 내역이 있는 확인 */	
		$sql_check = " SELECT COUNT(*) FROM transaction WHERE employee_id = '".$employee_id."' AND transaction_period_start ='".$transaction_period_start."' ";
		$row_check = getRowCount($sql_check);
		if ( $row_check[0]>0) {
			/* 
			 * 중복된게 있으면 무시
			 */
		} else {
			$sql_e = "INSERT INTO transaction ( employee_id, gross_wages, transaction_period_start, transaction_date,deductions, net_wages, transaction_remark, account_id, regdate ) " .
		 			" VALUES ( '".$employee_id."', '".$gross_wages."','".$transaction_period_start."','".$transaction_date."','".$deductions."', '".$net_wages."', '".$remarks."', '$Sync_id', '$now_datetimeano') " ;
			pQuery($sql_e,"insert");
		}	
	}
		
	$str1 = "Registration Completed!";
	//echo $sql;
			
} else if($action_type=="modify") {
	
	$sql = " UPDATE transaction SET  transaction_date= '$transaction_date', transaction_period_start = '$transaction_period_start', gross_wages = '$gross_wages', employee_id = '$employee_id', transaction_remark='$remarks', deductions = '$deductions', net_wages='$net_wages' WHERE transaction_id = '$id'  ";
	pQuery($sql,"update");
	
	// insert history info.
	if ($id) {
		$sql = "INSERT INTO history (history_table, history_table_id, account_id,regdate) VALUES ('transaction','$id','$Sync_id','$now_datetimeano')";	
		pQuery($sql,"insert");
	}
	
	$str1 = "Update Completed!";
}
 
?>
			 
<script language="Javascript">
alert("<?=$str1?>");
location.href="transaction_list.php?<?php echo $srch_param;?>";
</script>