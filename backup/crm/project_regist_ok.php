<?php
ob_start();
include_once "include/common.inc";
include_once "include/dbconn.inc";
include_once "include/myfunc.inc";

$project_id = $_REQUEST["project_id"];
$project_name =	$_REQUEST["project_name"];
$project_address =	$_REQUEST["project_address"];
$project_suburb = $_REQUEST["project_suburb"];
$project_state = $_REQUEST["project_state"];
$project_postcode = $_REQUEST["project_postcode"];
$project_phone_number = $_REQUEST["project_phone_number"];
$project_fax_number = $_REQUEST["project_fax_number"];
$builder_id = $_REQUEST["builder_id"];
$project_comments = $_REQUEST["project_comments"];
$project_status = $_REQUEST["project_status"];
$project_document = $_POST["project_document"];
$project_retention = $_POST['project_retention'];
$project_invoicing_date = getAUDateToDB($_POST['project_invoicing_date']);
$payment_term_id = $_POST['payment_term_id'];
$budget_fc = $_POST['budget_fc'];
$v_budget_fc = $_POST["v_budget_fc"];
$extra_t = $_POST["extra_t"];
$extra_s = $_POST["extra_s"];
$extra_w = $_POST["extra_w"];
$contract_fc = $_POST["contract_fc"];
$contract_sc = $_POST["contract_sc"];
$v_fc = $_POST["v_fc"];
$v_sc = $_POST["v_sc"];
						
$action_type = $_REQUEST["action_type"];

if ($action_type=="") {
	$sql = "INSERT INTO project (project_name, project_address, project_suburb, project_state, project_postcode, project_phone_number, project_fax_number, builder_id, project_comments, project_status, project_document, project_retention, project_invoicing_date, payment_term_id,regdate , " . 
	" budget_fc, v_budget_fc, extra_t, extra_s, extra_w,contract_fc, contract_sc, v_fc, v_sc ) " .
	" VALUES('$project_name', '$project_address', '$project_suburb', '$project_state','$project_postcode','$project_phone_number','$project_fax_number','builder_id','$project_comments', '$project_status','$project_document','$project_retention','$project_invoicing_date','$payment_term_id','$now_datetimeano',".
	" '$budget_fc', '$v_budget_fc', '$extra_t', '$extra_s', '$extra_w','$contract_fc', '$contract_sc', '$v_fc', '$v_sc' ) ";

	//echo $sql;
	pQuery($sql,"insert");
	$string1 = "Registration Completed!";

} else if($action_type=="modify") {

	if ($project_status == "COMPLETED") {
		$sql = "UPDATE project SET project_name='" . $project_name . 
		"', project_address='".$project_address . 
		"', project_suburb='" .$project_suburb . 
		"', project_state='" .$project_state . 
		"', project_postcode='" .$project_postcode . 
		"', project_phone_number='".$project_phone_number . 
		"', project_fax_number='".$project_fax_number . 
		"', builder_id='".$builder_id . 
		"', project_comments='".$project_comments .
		"', project_status= '".$project_status . 
		"', enddate = '".$now_datetimeano. 
		"', project_document = '".$project_document .
		"', project_retention = '". $project_retention .
		"', project_invoicing_date = '". $project_invoicing_date .
		"', payment_term_id ='" . $payment_term_id . 
		"', budget_fc = '". $budget_fc . 
		"', v_budget_fc = '". $v_budget_fc .
		"', extra_t = '". $extra_t .
		"', extra_s = '". $extra_s .
		"', extra_w = '". $extra_w .
		"', contract_fc = '". $contract_fc .
		"', contract_sc = '". $contract_sc .
		"', v_fc = '". $v_fc .
		"', v_sc = '". $v_sc . "' ".
		" WHERE project_id=" . $project_id;
		pQuery($sql,"update");
	}
	else {
		$sql = "UPDATE project SET project_name='" . $project_name . 
		"', project_address='".$project_address . 
		"', project_suburb='" .$project_suburb . 
		"', project_state='" .$project_state . 
		"', project_postcode='" .$project_postcode . 
		"', project_phone_number='".$project_phone_number . 
		"', project_fax_number='".$project_fax_number . 
		"', builder_id='".$builder_id . 
		"', project_comments='".$project_comments .
		"', project_status='".$project_status . 
		"', project_document = '".$project_document .
		"', project_retention = '". $project_retention .
		"', project_invoicing_date = '". $project_invoicing_date .
		"', payment_term_id ='" . $payment_term_id . 
		"', budget_fc = '". $budget_fc . 
		"', v_budget_fc = '". $v_budget_fc .
		"', extra_t = '". $extra_t .
		"', extra_s = '". $extra_s .
		"', extra_w = '". $extra_w .
		"', contract_fc = '". $contract_fc .
		"', contract_sc = '". $contract_sc .
		"', v_fc = '". $v_fc .
		"', v_sc = '". $v_sc . "' ".
		" WHERE project_id=" . $project_id;
		pQuery($sql,"update");
	}
	$string1 = "Update Completed!";
}

?>
<script>
//alert("<?=$string1?>");
location.href="project_list.php?srch_project_status=PROCESSING";
</script>
<? ob_flush(); ?>