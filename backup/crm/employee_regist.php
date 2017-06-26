<?php
include_once "include/logincheck.inc";
include_once "include/common.inc";
include_once "include/dbconn.inc";
include_once "include/myfunc.inc";
include_once "htmlinclude/head.php";

$action_type = $_REQUEST["action_type"];
$id = $_REQUEST["id"];

if($action_type=="modify") {
	## 쿼리, 담을 배열 선언
	$list_Records = array();
	
	$Query  = "SELECT * ";
	$Query .= " FROM employee WHERE id='$id'";

	$id_cnn = mysql_query($Query) or exit(mysql_error());
	while($id_rst = mysql_fetch_assoc($id_cnn)) {
		$list_Records = array_merge($list_Records, array($id_rst));
		//print_r($list_Records);
		//echo "<p>";
	}
} else if($action_type=="delete") {
	$req_id = $_REQUEST["id"];
	
	$sql = "SELECT COUNT(*) FROM job WHERE employee_id='".$req_id."'";
	$row = getRowCount($sql);
	
	if($row[0] > 0 ) {
		echo "<script>alert('This employee has allocated in jobs.');history.back();</script>";
		exit;
	}

	$sql = "DELETE FROM employee WHERE id='" .$req_id."'; ";
	// wage 삭제 sql 문 추가 
	pQuery($sql, "delete");
	echo "<script>alert('Deleted!');location.href='employee_list.php';</script>";
	exit;
}

?>

<script language="Javascript">
function formchk() {
	frm = document.frm1;

	if(frm.employee_id.value =="") {
		alert("Please, Insert employee id!");
		frm.employee_id.focus();
		return;
	} 
	if(frm.first_name.value =="") {
		alert("Please, Insert first name!");
		frm.first_name.focus();
		return;
	}
	
	if(frm.last_name.value =="") {
		alert("Please, Insert last name!");
		frm.last_name.focus();
		return;
	}
	
	if(frm.current_ft_wage.value == "") {
		alert("Please, Insert full time wage!");
		frm.current_ft_wage.focus();
		return;
	}
	
	if(frm.current_hr_wage.value =="") {
		alert("Please, Insert hourly wage!");
		frm.current_hr_wage.focus();
		return;
	}

	frm.submit();
	
}

$(function() {
	$( "input:button, button").button();
	$(".new_entry thead").addClass('ui-widget-header');
	$(".new_entry tbody").addClass('ui-widget-content');
	$(".new_entry td").addClass('center');
	$("#table_option thead").addClass('ui-widget-header');
	$("#table_option tbody").addClass('ui-widget-content');
	
	$(".option_table").hide();
	
	$("#option_toggle").click(function() {
		$(".option_table").toggle();
		return false;
	});
	
	$("#history").click(function() {
		$("#history_details").toggle();
		return false;
	});
	$("#dob").datepicker($.datepicker.regional['en-GB']);
	$("#dob").datepicker( "option", "firstDay", 1 );
	$("#dob").datepicker( "option", "changeMonth", true );
	$("#dob").datepicker( "option", "changeYear", true );
	$("#dob").datepicker( "option", "yearRange", "-70:-15" );
	$("#dob").datepicker();
	$("#hire_date").datepicker($.datepicker.regional['en-GB']);
	$("#hire_date").datepicker( "option", "firstDay", 1 );
	$("#hire_date").datepicker();
	
	$("#termination_date").datepicker($.datepicker.regional['en-GB']);
	$("#termination_date").datepicker( "option", "firstDay", 1 );
	$("#termination_date").datepicker();

	$("#visa_expiry_date").datepicker($.datepicker.regional['en-GB']);
	$("#visa_expiry_date").datepicker( "option", "firstDay", 1 );
	$("#visa_expiry_date").datepicker();

	$("#employee_generate_id").click(function() { 
		if (!$('#hire_date').val()) {
			alert("Please insert hire date.");
			$('#hire_date').focus();
			return false;
		}
		$('#processing').fadeIn(500);
		
		$.post("autogen_employee_id.php", {
			hire_date: $('#hire_date').val()
		}, function(data) { 
			if (data != 'Error!') {
				$('#employee_id').val(data);
			} else {
				alert('Please check hire date and do it again, or insert manually');
			}
			
		});
		
		$('#processing').fadeOut(800);
		return false;
	});
	
	$("#add_visa").click(function() {
		left1 = (screen.width/2)-(400/2);
		top1 = (screen.height/2)-(400/2);
		new_window = window.open('add_visa.php','','width=400,height=400,top='+top1+',left='+left1);
		if (window.focus) {
			new_window.focus();
		}
		return false;
	});

	$("#add_visa_status").click(function() {
		left1 = (screen.width/2)-(400/2);
		top1 = (screen.height/2)-(400/2);
		new_window = window.open('add_visa_status.php','','width=400,height=400,top='+top1+',left='+left1);
		if (window.focus) {
			new_window.focus();
		}	
		return false;
	});
	
	<?php if ($action_type <> '') { ?>
	$(".current_ft_wage").click(function() {
		var eid = $("#eid").val();
		var wtype = "f";
		left1 = (screen.width/2)-(500/2);
		top1 = (screen.height/2)-(500/2);
		new_window = window.open('add_wage.php?eid='+eid+'&wtype='+wtype,'','width=500,height=500,top='+top1+',left='+left1);
		if (window.focus) {
			new_window.focus();
		}
		return false;
	});
	
	$(".current_hr_wage").click(function() {
		var eid = $("#eid").val();
		var wtype = "h";
		left1 = (screen.width/2)-(500/2);
		top1 = (screen.height/2)-(500/2);
		new_window = window.open('add_wage.php?eid='+eid+'&wtype='+wtype,'','width=500,height=500,top='+top1+',left='+left1);
		if (window.focus) {
			new_window.focus();
		}
		return false;
	});
	<?php }?>	
});
</script>

<BODY leftmargin=0 topmargin=0>
<div id="processing" style="display:none; position:absolute; top:50%;left:50%; z-index:4">
	<img src="images/ajax-loader.gif" alt="loading" style="width:35px;vertical-align:middle;margin-left:10px;" /> PROCESSING
</div>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
<tr>
	<td valign="top" width="191" height="100%">
	<!-- LEFT -------------------------------------------------------------------------------------------------->
	<? include_once "left.php"; ?>
	<!-- LEFT END ---------------------------------------------------------------------------------------------->
	</td>
	<!-- LEFT BG------------------------------------------------------------------------------------------------>
	<td width="1" bgcolor="#DFDFDF">
	</td>
	<!-- LEFT BG END-------------------------------------------------------------------------------------------->
	<td valign="top">
	<!-- BODY -------------------------------------------------------------------------------------------------->
	<table border="0" cellpadding="0" cellspacing="0" width="100%">
		<!-- BODY TOP ------------------------------------------------------------------------------------------->
		<tr>
			<td style="padding-left:15px"><? include_once "top.php"; ?></td>
		</tr>
		<!-- BODY TOP END --------------------------------------------------------------------------------------->
		<!-- BODY CENTER ----------------------------------------------------------------------------------------->
		<tr>
			<td width="100%">			
			<table border="0" cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td style="padding-left:15px" valign="bottom" height="14" colspan="2">					
				</td>
			</tr>	
			<tr>
				<td style="padding-left:15px" valign="top">
				<!-- CONTENTS -------------------------------------------------------------------------------------------->
				<table border="0" cellpadding="0" cellspacing="0" width="1000">				
				<tr>
					<td valign="top">
					<table border="0" cellpadding="0" cellspacing="0" width="1000" class="font11_bold">
						<tr><td height="8"></td></tr>
						<tr><td width="252"><img src="images/icon_circle03.gif">&nbsp;<span style="height:21px">Employee Register</span></td></tr>
						<tr><td height="3"></td></tr>
						<tr><td background="images/bg_check.gif" height="3"></td></tr>
						<tr><td height="2"></td></tr>
					</table>
					</td>
				</tr>
				<form name="frm1" method="post" action="employee_regist_ok.php">
				<input type="hidden" name="action_type" value="<?=$action_type?>">
				
				<tr>
					<td valign="top">
						<?php 
							if ($action_type <> "" && $list_Records[0]["termination_date"] <> "0000-00-00" && $list_Records[0]["termination_date"] < $now_dateano) {
						?>
						<div class="ui-state-highlight ui-corner-all" style="margin-top: 10px; margin-bottom:10px; padding: 0 .7em;"> 
							<p><span class="ui-icon ui-icon-info" style="float: left; margin-right: .3em;"></span>
							<strong>Terminated!</strong></p>
						</div>
						<?php } ?>
						<span><span class="mandatory">*</span> Mandatory fields</span>
						<table border="0" cellpadding="0" cellspacing="0"  width="1000" class="new_entry">
						<thead>
							<tr>
							<th><span class="mandatory">*</span>Hire Date</th>
							<th><span class="mandatory">*</span>Employee ID<? if($action_type!="modify") { ?> <button id="employee_generate_id"><span class="ui-icon ui-icon-pencil"></span></button><br/> <?}?></th>
							<th><span class="mandatory">*</span>First Name</th>
							<th><span class="mandatory">*</span>Last Name</th>
							<th><span class="mandatory">*</span>D.O.B.</th>
							</tr>
						</thead>
						<tbody>
							<tr>
							<td ><input type="text" name="hire_date" id="hire_date" size="10" value="<?php echo getAUDate($list_Records[0]['hire_date']);?>" /></td>	
							<td><input type="text" size="20" id="employee_id" name="employee_id" <?if($action_type=="modify") echo "readonly='readonly' " ?> value="<?=$list_Records[0]["employee_id"]?>">
							
							</td>	
							<input type="hidden" name="id" id='eid' value="<?php echo $list_Records[0]['id'];?>">
							<td><input type="text" size="15" name="first_name" value="<?=$list_Records[0]["first_name"]?>"> </td>								<td><input type="text" size="15" name="last_name" value="<?=$list_Records[0]["last_name"]?>"> </td>	
							<td><input type="text" name="dob" id="dob" size="10" value="<?php echo getAUDate($list_Records[0]['dob']);?>" /> </td>	
						</tr>
						</tbody>
						</table>
						<table border="0" cellpadding="0" cellspacing="0" width="1000" class="new_entry">
						<thead>
							<tr>
							<th><span class="mandatory">*</span>Phone/Mobile</th>
							<th><span class="mandatory">*</span>Full time wage</th>
							<th><span class="mandatory">*</span>Hourly wage</th>
							</tr>
						</thead>
						<tbody>
							<tr>
							<td>
							<input type="text" size="15" name="phone_number" value="<?=$list_Records[0]["phone_number"]?>">
											</td>	
                                                        <td id='current_ft'>
							<input type='text' name='current_ft_wage' <?php if ($action_type <> '') echo " readonly='readonly' ";?>  class='current_ft_wage' size='6' value='<?php echo getValue('wages','wages_amount'," and wages_id = '".$list_Records[0]['current_ft_wage_id']."' ")?>'>
					
                                                        <input type='hidden' name='current_ft_wage_id' id='current_ft_wage_id' value='<?php echo $list_Records[0]['current_ft_wage_id'];?>' ></td>	
							

                                                        <td id='current_hr'>
							<input type='text' name='current_hr_wage' <?php if ($action_type <> '') echo " readonly='readonly' ";?>  size='6' class='current_hr_wage' value='<?php echo getValue('wages','wages_amount'," and wages_id = '".$list_Records[0]['current_hr_wage_id']."' ")?>'>
							<input type='hidden' name='current_hr_wage_id' id='current_hr_wage_id' value='<?php echo $list_Records[0]['current_hr_wage_id'];?>' ></td>	


</tr>
						</tbody>
						</table>
						

					
						
						<table border="0" cellpadding="0" cellspacing="0" width="1000" class="new_entry">
						<thead>
						<tr>
							<th>Payment Type</th>
							<th>Account Name</th>
							<th>BSB</th>
							<th>Account Number</th>
						</tr>
						</thead>
						<tbody>
						<tr>
							<td >
								<input type='text' name='acc_payment_type' size='10' value='<?php echo $list_Records[0]['account_payment_type'];?>'>
							</td>	
							<td >
								<input type='text' name='acc_name' size='10' value='<?php echo $list_Records[0]['account_name'];?>'>
							</td>	
							<td >
								<input type='text' name='acc_bsb' size='10' value='<?php echo $list_Records[0]['account_bsb'];?>'>
							</td>	
							<td >
								<input type='text' name='acc_number' size='10' value='<?php echo $list_Records[0]['account_number'];?>'>
							</td>	
						</tr>
						</tbody>
						</table>
						
						<table border="0" cellpadding="0" cellspacing="0" width="1000" class="new_entry">
						<thead>
							<tr>
								<th>Remarks</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td><textarea name="remarks" rows="4" cols="121"><?=$list_Records[0]["remarks"]?></textarea></td>	
							</tr>
						</tbody>
						</table>
						<Br />
						<button id="option_toggle">Optional Fields</button>
						<br /><Br />
						<div class="option_table">
						<table border="0" cellpadding="0" cellspacing="0" width="1000" id="table_option" class="new_entry">
						<thead>
							<tr>
							<th width="240">Address</th>
							<th>E-mail</th>
							<th width='140'>Termination Date</th>
							<th>TFN</th>
							<th>ABN</th>
							<th>Induction Number</th>
							<th>Vehicle</th>
							</tr>
						</thead>
						<tbody>
							<tr>
							<td align='right' >
							<span style="display:inline-block; width:70px; text-align:right;padding-right:5px;">Address :</span><input type="text" size="20" name="address" value="<?=$list_Records[0]["address"]?>"><br />
							<span style="display:inline-block; width:70px; text-align:right;padding-right:5px;">Suburb :</span><input type="text" size="20" name="suburb" value="<?=$list_Records[0]["suburb"]?>">
							<span style="display:inline-block; width:70px; text-align:right;padding-right:5px;">State :</span><?php getStateOption("state",$list_Records[0]["state"],'155');?>
							<span style="display:inline-block; width:70px; text-align:right;padding-right:5px;">Postcode :</span><input type="text" size="20" name="postcode" value="<?=$list_Records[0]["postcode"]?>"></Td>
							<td><input type="text" size="20" name="email" value="<?=$list_Records[0]["email"]?>"></td>	
							<Td><input type="text" name="termination_date" id="termination_date" size="10" value="<?php echo getAUDate($list_Records[0]['termination_date']);?>" /> </td>	
							
							<Td><input type="text" size="15" name="tfn_number" value="<?=$list_Records[0]["tfn_number"]?>"></td>	
							<Td><input type="text" size="15" name="abn_number" value="<?=$list_Records[0]["abn_number"]?>"></td>
							<td><input type="text" size="15" name="induction_number" value="<?=$list_Records[0]["induction_number"]?>"></td>
							<td><?php echo DrawFromDB('employee','vehicle','select',$list_Records[0]['vehicle'],'yes','',NULL)?></td>
						</tr>
											
						
						</tbody>
						</table>
						</div>
						<?php if($action_type) { ?>
						<table border="0" cellpadding="0" cellspacing="0" width="1000" class="new_entry">
						<thead>
							<tr>
								<th><span id="history">History</span>&larr;click to see history</th>
							</tr>
						</thead>
						<tbody id="history_details" style="display:none;">	 
							<tr >
							<td class='right' >Inserted by&nbsp;<?php echo getValue('account', 'username', ' AND userid="'.$list_Records[0]['account_id'].'"')?>&nbsp;<?php echo getAUDate($list_Records[0]["regdate"],1)?>
							<?php 
							$Query  = "SELECT * ";
							$Query .= " FROM history WHERE history_table='employee' AND history_table_id ='". $list_Records[0]['id'] ."' ";
							//echo $Query;
							
							$result = mysql_query($Query) or exit(mysql_error());
							$result_str ='';
							if ($result) {
								$result_str = "<br />Updated by ";
								while ($row = mysql_fetch_assoc($result)) {
									$result_str .= getValue('account','username',' AND userid="'.$row['account_id'].'" '). " ". getAUDate($row["regdate"],1) ."<br />";
								}
								if ($result_str != '<br />Updated by ')
									echo $result_str;
							}
							
							?>
							
							</td>	
						</tr>
						</tbody>
						</table>
						<?php }?>
								
						<br>
						<div class="right">
						<input type="button" value="Save" onclick="formchk();"><input type="button" value="Delete" onclick="javascript:if(confirm('Are you sure?')) { location.href='<?=$_SERVER['PHP_SELF']?>?id=<?=$list_Records[0]["id"]?>&action_type=delete';}">
						</div>
					</td>
				</tr>
				</form>
				<tr><td></td></tr>
				</table>
				<!-- CONTENTS END -------------------------------------------------------------------------------------------->
			</td>
			<td>
			</td>
		  </tr>
		  <tr><td colspan="2" height="20"></td></tr>
		 </table>
		 </td>
	    </tr>
	    <!-- BODY CENTER END------------------------------------------------------------------------------------->
	   </table>
	<!-- BODY END -------------------------------------------------------------------------------------------->
	</td>
</tr>
<tr>
	<td colspan="3">
	<!-- BOTTOM -------------------------------------------------------------------------------------------->
	<? include_once "bottom.php"; ?>
	<!-- BOTTOM END -------------------------------------------------------------------------------------------->
	</td>
</tr>
</table>


</BODY>
</HTML>