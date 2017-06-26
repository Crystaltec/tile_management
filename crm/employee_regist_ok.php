<?php
include_once "include/logincheck.inc";
include_once "include/common.inc";
include_once "include/dbconn.inc";
include_once "include/myfunc.inc";

$first_name =	htmlspecialchars($_REQUEST["first_name"], ENT_QUOTES);
$last_name =	htmlspecialchars($_REQUEST["last_name"], ENT_QUOTES);
$employee_id =		$_REQUEST["employee_id"];
$id = $_REQUEST["id"];
$phone_number =htmlspecialchars($_REQUEST["phone_number"], ENT_QUOTES) ;
$mobile_number =	htmlspecialchars($_REQUEST["mobile_number"], ENT_QUOTES);
$dob = getAUDateToDB($_REQUEST["dob"]);
$email= $_REQUEST["email"];
$remarks=htmlspecialchars($_REQUEST["remarks"], ENT_QUOTES);
$address =htmlspecialchars($_REQUEST["address"], ENT_QUOTES);
$suburb =htmlspecialchars($_REQUEST["suburb"], ENT_QUOTES);
$state = $_REQUEST["state"] ;
$postcode =htmlspecialchars($_REQUEST["postcode"], ENT_QUOTES);

$hire_date = getAUDateToDB($_REQUEST["hire_date"]);
$termination_date = getAUDateToDB($_REQUEST["termination_date"]);
$tfn_number = htmlspecialchars($_REQUEST["tfn_number"], ENT_QUOTES);
$abn_number = htmlspecialchars($_REQUEST["abn_number"], ENT_QUOTES);
$induction_number = htmlspecialchars($_REQUEST["induction_number"], ENT_QUOTES);
$passport_number = htmlspecialchars($_REQUEST["passport_number"], ENT_QUOTES);
$visa_id = $_REQUEST["visa_id"];
$visa_status_id = $_REQUEST["visa_status_id"];
$visa_expiry_date = getAUDateToDB($_REQUEST["visa_expiry_date"]);
$account_id = $Sync_id;
$current_ft_wage_id = $_REQUEST['current_ft_wage_id'];
$current_hr_wage_id = $_REQUEST['current_hr_wage_id'];
$current_ft_wage = $_REQUEST['current_ft_wage'];
$current_hr_wage = $_REQUEST['current_hr_wage'];
$vehicle = $_REQUEST['vehicle'];
$acc_payment_type = $_REQUEST['acc_payment_type'];
$acc_name = $_REQUEST['acc_name'];
$acc_bsb = $_REQUEST['acc_bsb'];
$acc_number = $_REQUEST['acc_number'];
$finance = $_REQUEST['finance'];

$action_type = $_REQUEST["action_type"];


if($action_type=="") {
	$sql = "SELECT COUNT(*) FROM employee WHERE employee_id='".$employee_id."'";
	$row = getRowCount($sql);
	if($row[0] > 0 ) {
		echo "<script>alert('This employee Id has already registered');history.back();</script>";
		exit;
	}
	$sql = "INSERT INTO employee (employee_id, first_name, last_name, dob, phone_number, mobile_number," .
			" email, address, suburb, state, postcode,hire_date,termination_date, tfn_number,abn_number, " . 
			" induction_number, passport_number, visa_id, visa_status_id, visa_expiry_date, account_id, " . 
			" remarks,regdate,current_ft_wage_id, current_hr_wage_id,vehicle, acc_payment_type, acc_name, acc_bsb, acc_number,finance ) ";
	$sql .= "VALUES ('";
	$sql .= $employee_id . "', '";
	$sql .= $first_name . "', '";
	$sql .= $last_name . "', '";
	$sql .= $dob . "', '";
	$sql .= $phone_number . "', '";
	$sql .= $mobile_number ."', '";
	$sql .= $email . "', '";
	$sql .= $address . "', '";
	$sql .= $suburb . "', '";
	$sql .= $state . "', '";
	$sql .= $postcode . "', '";
	$sql .= $hire_date . "', '";
	$sql .= $termination_date . "', '";
	$sql .= $tfn_number ."', '";
	$sql .= $abn_number ."', '";
	$sql .= $induction_number . "', '";
	$sql .= $passport_number ."', '";
	$sql .= $visa_id ."','";
	$sql .= $visa_status_id . "', '";
	$sql .= $visa_expiry_date . "', '";
	$sql .= $account_id . "', '";
	$sql .= $remarks . "','";
	$sql .= $now_datetimeano ."', '";
	$sql .= $current_ft_wage_id. "', '";
	$sql .= $current_hr_wage_id. "', '";
	$sql .= $vehicle . "', '";
	$sql .= $acc_payment_type . "', '";
	$sql .= $acc_name . "', '";
	$sql .= $acc_bsb . "', '";
	$sql .= $acc_number . "', '";
	$sql .= $finance . "' )";
	pQuery($sql,"insert");
	
	// get employee id
	$sql2 = "SELECT id FROM employee WHERE employee_id='".$employee_id."' and first_name = '".$first_name."' and last_name = '".$last_name."' ";
	$row2 = getRowCount($sql2);
	$id = $row2[0];
	if ($current_ft_wage) {
		// insert full time wage
		$sql3 = "INSERT INTO wages (employee_id,wages_type,wages_amount,account_id, regdate) VALUES ('$row2[0]','f','".$current_ft_wage."','$Sync_id','$now_datetimeano')";
		pQuery($sql3,"insert");
		
		// get full time wage id
		$sql4 = "SELECT wages_id FROM wages WHERE employee_id = '$row2[0]' and wages_type = 'f'  ORDER BY regdate desc limit 1 ";
		$row4 = getRowCount($sql4);
	
		// update employee's full time wage info'
		$sql5 = "UPDATE employee SET  current_ft_wage_id='".$row4[0]."'   WHERE id='".$row2[0]."' ";

		pQuery($sql5,"update");
		
		// insert history info.
		if ($row2[0] <> '' && $row4[0] <> '') {
			$sql6 = "INSERT INTO history (history_table, history_table_id, account_id,regdate) VALUES ('wages','$row4[0]','$Sync_id','$now_datetimeano')";				pQuery($sql6,"insert");
		}
	}
	
	if($current_hr_wage) {
		// insert hourly wage
		$sql7 = "INSERT INTO wages (employee_id,wages_type,wages_amount,account_id, regdate) VALUES ('$row2[0]','h','".$current_hr_wage."','$Sync_id','$now_datetimeano')";
		pQuery($sql7,"insert");
		
		// get hourly wage id
		$sql8 = "SELECT wages_id FROM wages WHERE employee_id = '$row2[0]' and wages_type = 'h'  ORDER BY regdate desc limit 1 ";
		$row8 = getRowCount($sql8);
	
		// update employee's hourly wage info'
		$sql9 = "UPDATE employee SET  current_hr_wage_id='".$row8[0]."'   WHERE id='".$row2[0]."' ";

		pQuery($sql9,"update");
		
		// insert history info.
		if ($row2[0] <> '' && $row8[0] <> '') {
			$sql10 = "INSERT INTO history (history_table, history_table_id, account_id,regdate) VALUES ('wages','$row8[0]','$Sync_id','$now_datetimeano')";				pQuery($sql10,"insert");
		}
	}
	
	
	$str1 = "Registration Completed!";	
		
} else if($action_type=="modify") {
	$sql = "SELECT COUNT(*) FROM employee WHERE employee_id='".$employee_id."' and id <> '".$id."' ";
	$row = getRowCount($sql);
	if($row[0] > 0 ) {
		echo "<script>alert('This employee Id has already registered');history.back();</script>";
		exit;
	}
	$sql = "UPDATE employee SET employee_id='".$employee_id."', first_name='".$first_name."', last_name='".$last_name
			."', dob ='".$dob."', phone_number='".$phone_number."', mobile_number ='".$mobile_number."', email='".$email
			."', address='".$address."', suburb='".$suburb."', state='".$state."', postcode='".$postcode.="', hire_date ='" .$hire_date 
			. "', termination_date='" . $termination_date . "', tfn_number='". $tfn_number ."', abn_number='" . $abn_number 
			."', induction_number = '". $induction_number . "', passport_number='". $passport_number ."', visa_id='". $visa_id 
			."', visa_status_id='". $visa_status_id . "', visa_expiry_date='".$visa_expiry_date . "', remarks='".$remarks 
			."', current_ft_wage_id='".$current_ft_wage_id."', current_hr_wage_id='".$current_hr_wage_id."', vehicle = '".$vehicle
			."', acc_payment_type ='".$acc_payment_type."', acc_name='".$acc_name."', acc_bsb='".$acc_bsb."', acc_number='".$acc_number."', finance ='".$finance."' ";
	
	$sql .= " WHERE id='".$id."' ";

	//echo $sql;
	pQuery($sql,"update");
	
	if($current_ft_wage_id) {
		// get full time wage id
		$sql11 = "SELECT wages_id,wages_apply_date FROM wages WHERE employee_id = '$id' AND wages_type = 'f' AND wages_id = '$current_ft_wage_id' limit 1 ";
		$row11 = getRowCount($sql11);
	
	 	if ($row11[1] <> "0000-00-00" ) {
	 		// update employee's job order info.
			$sql12 = "UPDATE job SET  f_wages_id='".$row11[0]."'  WHERE employee_id='".$id."' AND job_date >= '".$row11[1]."' ";
	
			pQuery($sql12,"update");
			
			// insert history info.
			if ($id <> '' && $row11[1] <> '0000-00-00') {
				$sql13 = "INSERT INTO history (history_table, history_table_id, account_id,regdate) VALUES ('wages','$current_ft_wage_id','$Sync_id','$now_datetimeano')";				
				pQuery($sql13,"insert");
			}
	 	}
		
	}
	if($current_hr_wage_id) {
		// get full time wage id
		$sql14 = "SELECT wages_id,wages_apply_date FROM wages WHERE employee_id = '$id' AND wages_type = 'h' AND wages_id = '$current_hr_wage_id' limit 1 ";
		$row14 = getRowCount($sql14);
	
	 	if ($row14[1] <> "0000-00-00" ) {
	 		// update employee's job order info.
			$sql15 = "UPDATE job SET  h_wages_id='".$row14[0]."'  WHERE employee_id='".$id."' AND job_date >= '".$row14[1]."' ";
	
			pQuery($sql15,"update");
			
			// insert history info.
			if ($id <> '' && $row14[1] <> '0000-00-00') {
				$sql16 = "INSERT INTO history (history_table, history_table_id, account_id,regdate) VALUES ('wages','$current_hr_wage_id','$Sync_id','$now_datetimeano')";				
				pQuery($sql16,"insert");
			}
	 	}
		
	}
	// insert history info.
	if ($id) {
		$sql = "INSERT INTO history (history_table, history_table_id, account_id,regdate) VALUES ('employee','$id','$Sync_id','$now_datetimeano')";	
		pQuery($sql,"insert");
	}
	
	$str1 = "Update Completed!";
}

?>
<script language="Javascript">
//alert("<?=$str1?>");
location.href="employee_regist.php?id=<?=$id?>&action_type=modify";
</script>