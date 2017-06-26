<?php
include_once "include/logincheck.inc";
include_once "include/common.inc";
include_once "include/dbconn.inc";
include_once "include/myfunc.inc";

$id = $_REQUEST["id"];
$employee_id =	$_REQUEST["employee_id"];

$month_year = $_REQUEST["month_year"];
$amounts= $_REQUEST["amounts"];

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
	if ( $amounts >=0 && $employee_id ) {
	/* 같은 기간에 입력한 내역이 있는 확인 */	
		$sql_check = " SELECT COUNT(*) FROM allowance WHERE employee_id = '".$employee_id."' AND month_year ='".$month_year."' ";
		$row_check = getRowCount($sql_check);
		if ( $row_check[0]>0) {
			/* 
			 * 중복된게 있으면 무시
			 */
		} else {
			$sql_e = "INSERT INTO allowance ( employee_id, amounts, month_year, allowance_remark, account_id, regdate ) " .
		 			" VALUES ( '".$employee_id."', '".$amounts."','".$month_year."','".$remarks."', '$Sync_id', '$now_datetimeano') " ;
			pQuery($sql_e,"insert");
		}	
	}
		
	$str1 = "Registration Completed!";
	//echo $sql;
			
} else if($action_type=="modify") {
	
	$sql = " UPDATE allowance SET  month_year= '$month_year', amounts = '$amounts', employee_id = '$employee_id', allowance_remark='$remarks' WHERE allowance_id = '$id'  ";
	
	pQuery($sql,"update");

	// insert history info.
	if ($id) {
		$sql = "INSERT INTO history (history_table, history_table_id, account_id,regdate) VALUES ('allowance','$id','$Sync_id','$now_datetimeano')";	
		pQuery($sql,"insert");
	}
	
	$str1 = "Update Completed!";
}
?>
			 
<script language="Javascript">
alert("<?=$str1?>");
location.href="allowance_list.php?<?php echo $srch_param;?>";
</script>