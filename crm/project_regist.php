<?php
ob_start();
include_once "include/logincheck.inc";
include_once "include/common.inc";
include_once "include/dbconn.inc";
include_once "include/myfunc.inc";
include_once "htmlinclude/head.php";
?>
<script language="Javascript">
function formchk() {
	frm = document.frm1;
	
	if(frm.project_name.value =="") {
		alert("Please, Insert project name!");
		frm.project_name.focus();
		return;
	}

	frm.submit();
	
}

function formchk_copy() {
	frm = document.frm1;
	frm.action_type.value = "";
	
	if(frm.project_name.value =="") {
		alert("Please, Insert project name!");
		frm.project_name.focus();
		return;
	}

	frm.submit();
	
}

$(function() {
	$("input:button, button").button();
	$('.ui-widget-content').css({'background-image' :'none','background-color':'none'});
	
	$("#add_payment_term").click(function() {
		left1 = (screen.width/2)-(400/2);
		top1 = (screen.height/2)-(400/2);
		new_window = window.open('add_payment_term.php','','width=400,height=400,top='+top1+',left='+left1);
		if (window.focus) {
			new_window.focus();
		}
		return false;
	});
	
	
});
</script>
<?php

$project_id = $_REQUEST["project_id"];
$action_type = $_REQUEST["action_type"];

if($action_type=="delete") {
	$project_id = $_REQUEST["project_id"];
	$sql = "SELECT COUNT(*) FROM orders WHERE project_id='" .$project_id."'";	
	$row = getRowCount($sql);	
	
	$sql2 = "SELECT COUNT(*) FROM job WHERE project_id='" .$project_id."'";	
	$row2 = getRowCount($sql2);	
	
	$sql3 = "SELECT COUNT(*) FROM project_eval WHERE project_id='" .$project_id."'";	
	$row3 = getRowCount($sql3);	
	if($row[0] > 0 || $row2[0] > 0 || $row3[0] > 0) {
		echo "<script>alert('Can not delete this project because already has used in Material infomation , Job or Project Evaluation');history.back();</script>";
	} else {
		$sql = "DELETE FROM project WHERE project_id=".$project_id;
		//echo $sql;
		pQuery($sql,"delete");
		echo "<script>location.href='project_list.php';</script>";
	}
}

## ĵ��, �〻 �迭 ����
$list_Records = array();

$Query  = "SELECT * ";
$Query .= " FROM project WHERE project_id='". $project_id ."'";

//echo $Query;

$id_cnn = mysql_query($Query) or exit(mysql_error());
while($id_rst = mysql_fetch_assoc($id_cnn)) {
	$list_Records = array_merge($list_Records, array($id_rst));
	//print_r($list_Records);
	//echo "<p>";
}
?>
<BODY leftmargin=0 topmargin=0>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
<!-- BODY TOP ------------------------------------------------------------------------------------------->
<tr>
<td style="padding-left:0px"><? include_once "top.php"; ?></td>
</tr>
<!-- BODY TOP END --------------------------------------------------------------------------------------->

<table border="0" cellpadding="0" cellspacing="0" width="100%">
<br>
	<td valign="top" width="100" height="100%">
	<!-- LEFT -------------------------------------------------------------------------------------------------->
	<? //include_once "left.php"; ?>
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	<!-- LEFT END ---------------------------------------------------------------------------------------------->
	</td>
	<!-- LEFT BG------------------------------------------------------------------------------------------------>
	<td width="3">
	</td>
	<!-- LEFT BG END-------------------------------------------------------------------------------------------->
	<td valign="top">
	<!-- BODY -------------------------------------------------------------------------------------------------->
	<table border="0" cellpadding="0" cellspacing="0" width="100%">
			<!-- BODY CENTER ----------------------------------------------------------------------------------------->
		<tr>
			<td width="100%">			
			<table border="0" cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td style="padding-left:15px" valign="bottom" height="24" colspan="2">					
				</td>
			</tr>	
			<tr>
				<td style="padding-left:15px" valign="top">
		
				<!-- CONTENTS -------------------------------------------------------------------------------------------->
				<table border="0" cellpadding="0" cellspacing="0" width="1000">
				<tr>
					<td  valign="top">
					<table border="0" cellpadding="0" cellspacing="0" width="1000" class="font11_bold">
						<tr><td height="8"></td></tr>
						<tr><td width="252"><img src="images/icon_circle03.gif">&nbsp;<span style="height:21px">Project Register</span></td></tr>
						<tr><td height="3"></td></tr>
						<tr><td background="images/bg_check.gif" height="3"></td></tr>
						<tr><td height="2"></td></tr>
					</table>
					</td>
				</tr>

				<form name="frm1" method="post" action="project_regist_ok.php">
				<tr>
					<td valign="top">
						<table border="0" cellpadding="0" cellspacing="0" bordercolor="white" width="1000" class="new_entry">
							
						<tr >
							<td class="ui-widget-header left" width="200" height="30" >*Name</td>
							<td class="ui-widget-content left"><input type="text" size="60" name="project_name" value="<?=$list_Records[0]["project_name"]?>"></td>	
						</tr>
						<tr >
							<td class="ui-widget-header left" width="200" height="30" >Address</td>
							<td class="ui-widget-content left"><input type="text" size="60" name="project_address" value="<?=$list_Records[0]["project_address"]?>"></td>	
						</tr>
						<tr >
							<td class="ui-widget-header left" width="200" height="30" >Suburb</td>
							<td class="ui-widget-content left"><input type="text" size="60" name="project_suburb" value="<?=$list_Records[0]["project_suburb"]?>"></td>	
						</tr>
						<tr >
							<td class="ui-widget-header left" width="200" height="30"   >State</td>
							<td class="ui-widget-content left"><? getStateOption("project_state",$list_Records[0]["project_state"]); ?></td>	
						</tr>
						<tr >
							<td class="ui-widget-header left" width="200" height="30" >Postcode</td>
							<td class="ui-widget-content left"><input type="text" size="60" name="project_postcode" value="<?=$list_Records[0]["project_postcode"]?>"></td>	
						</tr>
						<tr >
							<td class="ui-widget-header left" width="200" height="30" >Phone number</td>
							<td class="ui-widget-content left"><input type="text" size="60" name="project_phone_number" value="<?=$list_Records[0]["project_phone_number"]?>"></td>	
						</tr>
						<tr >
							<td class="ui-widget-header left" width="200" height="30"  >Fax number</td>
							<td class="ui-widget-content left"><input type="text" size="60" name="project_fax_number" value="<?=$list_Records[0]["project_fax_number"]?>"></td>	
						</tr>
						<tr >
							<td class="ui-widget-header left" width="200" height="30"  >Builder</td>
							<td class="ui-widget-content left"><? getOption("builder", $list_Records[0]["builder_id"]); ?>
							
							</td>	
						</tr>
						<tr >
							<td class="ui-widget-header left" width="200" height="30" >Status</td>
							<td class="ui-widget-content left"><? processOption("project_status",($action_type)? $list_Records[0]["project_status"] : "PROCESSING")?></td>	
						</tr>
						<tr>
							<Td class="ui-widget-header left" width="200" height="30" >Document</Td>
							<Td class="ui-widget-content left"><?php echo DrawFromDB('project','project_document','select',$list_Records[0]['project_document'],'yes','Please select',NULL)?></Td>
						</tr>
						<tr>
							<td class="ui-widget-header left" width="200" >Retention</td>
							<Td class="ui-widget-content left"><?php echo DrawFromDB('project','project_retention','select',$list_Records[0]['project_retention'],'yes','Please select',NULL)?></Td>
						</tr>
						<Tr>
							<td class="ui-widget-header left" width="200" >Invoicing Date</td>
							<td class="ui-widget-content left"><input type="text" name="project_invoicing_date" id="project_invoicing_date" size="10" value="<?php echo $list_Records[0]['project_invoicing_date'];?>" /> </td>
						</Tr>
						<tr>
							<td class="ui-widget-header left" width="200">Payment Term<button id="add_payment_term"><span class="ui-icon ui-icon-plusthick"></span></button> </td>
							<td class="ui-widget-content left"><?php getOption("payment_term",$list_Records[0]['payment_term_id'],NULL," id='payment_term_id' ")?></td>
						</tr>	
						<tr >
							<td class="ui-widget-header left" width="200" height="30"  >Extra-T</td>
							<td class="ui-widget-content left"><input type="text" size="60" name="extra_t" value="<?=$list_Records[0]["extra_t"]?>"></td>	
						</tr>
						<tr >
							<td class="ui-widget-header left" width="200" height="30"  >Extra-S</td>
							<td class="ui-widget-content left"><input type="text" size="60" name="extra_s" value="<?=$list_Records[0]["extra_s"]?>"></td>	
						</tr>
						<tr >
							<td class="ui-widget-header left" width="200" height="30"  >Extra-W</td>
							<td class="ui-widget-content left"><input type="text" size="60" name="extra_w" value="<?=$list_Records[0]["extra_w"]?>"></td>	
						</tr>
						<tr >
							<td class="ui-widget-header left" width="200" height="30"  >Budget Fixing Cost</td>
							<td class="ui-widget-content left"><input type="text" size="60" name="budget_fc" value="<?=$list_Records[0]["budget_fc"]?>"></td>	
						</tr>
						<tr >
							<td class="ui-widget-header left" width="200" height="30"  >Variation Budget Fixing Cost</td>
							<td class="ui-widget-content left"><input type="text" size="60" name="v_budget_fc" value="<?=$list_Records[0]["v_budget_fc"]?>"></td>	
						</tr>
						
						<tr >
							<td class="ui-widget-header left" width="200" height="30"  >Contract Fixing Cost</td>
							<td class="ui-widget-content left"><input type="text" size="60" name="contract_fc" value="<?=$list_Records[0]["contract_fc"]?>"></td>	
						</tr>
						<tr >
							<td class="ui-widget-header left" width="200" height="30"  >Contract Supply Cost</td>
							<td class="ui-widget-content left"><input type="text" size="60" name="contract_sc" value="<?=$list_Records[0]["contract_sc"]?>"></td>	
						</tr>
						<tr >
							<td class="ui-widget-header left" width="200" height="30"  >Variation Fixing Cost</td>
							<td class="ui-widget-content left"><input type="text" size="60" name="v_fc" value="<?=$list_Records[0]["v_fc"]?>"></td>	
						</tr>
						<tr >
							<td class="ui-widget-header left" width="200" height="30"  >Variation Supply Cost</td>
							<td class="ui-widget-content left"><input type="text" size="60" name="v_sc" value="<?=$list_Records[0]["v_sc"]?>"></td>	

						</tr>
						
						<tr >
							<td class="ui-widget-header left" width="200" >Grout Information</td>
							<td class="ui-widget-content left"><textarea name="grout_com" rows="1" cols="92"><?=$list_Records[0]["grout_com"]?></textarea></td>	
						</tr>
						
						<tr >
							<td class="ui-widget-header left" width="200" >Adhesive Information</td>
							<td class="ui-widget-content left"><textarea name="adhesive_com" rows="1" cols="92"><?=$list_Records[0]["silicone_com"]?></textarea></td>	
						</tr>
						
						<tr >
							<td class="ui-widget-header left" width="200" >Silicone Information</td>
							<td class="ui-widget-content left"><textarea name="silicone_com" rows="1" cols="92"><?=$list_Records[0]["silicone_com"]?></textarea></td>	
						</tr>
						
						
						<tr >
							<td class="ui-widget-header left" width="200" >Comments</td>
							<td class="ui-widget-content left"><textarea name="project_comments" rows="5" cols="92"><?=$list_Records[0]["project_comments"]?></textarea></td>	
						</tr>
						

						<input type="hidden" name="action_type" value="<?=$action_type?>"><input type="hidden" name="project_id" value="<?=$project_id?>">					
						</table>		
						<br>
						<table border="0" cellpadding="0" cellspacing="0" width="1000">
						<tr><td align="left"><?php if ($action_type=='modify') {?>Please change the name before saving it<Br><input type="button" value="Save as copy" onclick="formchk_copy();"><?php } ?></td><td align="right">Required fields are marked with an asterisk (*).<br><input type="button" value="Save" onclick="formchk();"></td></tr>
						</table>
					</td>
				</tr>
				</form>
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
<? ob_flush(); ?>