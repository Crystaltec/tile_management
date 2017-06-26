<?php
ob_start();
include_once "include/logincheck.inc";
include_once "include/common.inc";
include_once "include/dbconn.inc";
include_once "include/myfunc.inc";
include_once "htmlinclude/head.php";
?>
<script language="Javascript">

   function OnChangeCheckbox (checkbox) {
            if (checkbox.checked) {
            
             
                document.getElementById('received_amount').value =  document.getElementById('advised_remit').value;
            }
            else {
               
            }
        }


function formchk() {
	frm = document.frm1;
	
	if(frm.inv_date.value =="") {
		alert("Please, Insert Invoice Date!");
		frm.inv_date.focus();
		return;
	}
	
	if(frm.invoice_number.value=="")
	{
	alert("Please, Insert Invoice Number!");
	frm.invoice_number.focus();
	
	}
	

	frm.submit();
	
}

function formchk_copy() {
	frm = document.frm1;
	frm.action_type.value = "";
	
	if(frm.inv_date.value =="") {
		alert("Please, Insert Invoice Date!");
		frm.inv_date.focus();
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
	
	
	
	
	
	$("#inv_date").datepicker($.datepicker.regional['en-GB']);
	$("#inv_date").datepicker( "option", "firstDay", 1 );
	$("#inv_date").datepicker();
	
        
	
	
	
	$("#payment_due").datepicker($.datepicker.regional['en-GB']);
	$("#payment_due").datepicker( "option", "firstDay", 1 );
	$("#payment_due").datepicker();

	$("#received_date").datepicker($.datepicker.regional['en-GB']);
	$("#received_date").datepicker( "option", "firstDay", 1 );
	$("#received_date").datepicker();
	
});

</script>
<?php

$inv_no = $_REQUEST["inv_no"];
$action_type = $_REQUEST["action_type"];


if($action_type=="delete") {
		$sql = "DELETE FROM invoice_manage WHERE inv_no=".$inv_no;
		//echo $sql;
		pQuery($sql,"delete");
		echo "<script language='javascript'>
		alert('Successfully Deleted!');
		</script>";

		echo "<script>location.href='inv_manage_list.php';</script>";
		
	}


## ĵ��, �〻 �迭 ����
$list_Records = array();

$Query  = "SELECT * ";
$Query .= " FROM invoice_manage WHERE inv_no='". $inv_no ."'";

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
	<?// include_once "left.php"; ?>
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
						<tr><td width="252"><img src="images/icon_circle03.gif">&nbsp;<span style="height:21px">Invoice Register</span></td></tr>
						<tr><td height="3"></td></tr>
						<tr><td background="images/bg_check.gif" height="3"></td></tr>
						<tr><td height="2"></td></tr>
					</table>
					</td>
				</tr>

				<form name="frm1" method="post" action="inv_management_regist_ok.php">
				<tr>
					<td valign="top">
					
						<table border="0" cellpadding="0" cellspacing="0" bordercolor="white" width="1000" class="new_entry">
						
						
						
						<tr >
				
							<td class="ui-widget-header left" width="300" height="30" >*Invoice No.</td>
							
							<td class="ui-widget-content left">
							<input type="text" size="30" name="invoice_number" value="<?=$list_Records[0]["invoice_number"]?>"></td>
							
						</tr>
						
							
						<tr >
				
							<td class="ui-widget-header left" width="200" height="30" >*Invoice Date</td>
							<td class="ui-widget-content left">
							<input type="text" name="inv_date" id="inv_date" size="30" value="<?php echo getAUDate($list_Records[0]['inv_date']);?>" /></td>
							
						</tr>
						<tr >
							<td class="ui-widget-header left" width="200" height="30" >Builder</td>
							
								<td class="ui-widget-content left"><? getOption("builder", $list_Records[0]["builder_id"]); ?> </td>				
							
								</tr>
						<tr >
							<td class="ui-widget-header left" width="200" height="30" >Project</td>
							<td class="ui-widget-content left"><? getOption("project", $list_Records[0]["project_id"]); ?> </td>	
						</tr>
						<tr >
							<td class="ui-widget-header left" width="200" height="30" >Payment Due</td>
							<td class="ui-widget-content left"><input type="text" name="payment_due" id="payment_due" size="30" value="<?php echo getAUDate($list_Records[0]["payment_due"]);?>" /></td>
							
						
						</tr>
						<tr >
							<td class="ui-widget-header left" width="200" height="30" >Invoice Amount (inc. GST)</td>
							<td class="ui-widget-content left"><input type="text" size="60" name="inv_amount" value="<?=$list_Records[0]["inv_amount"]?>"></td>	
						</tr>
						
						<tr >
<td class="ui-widget-header left" width="200" height="30" >Advised Remittance  (inc. GST)</td>
<td class="ui-widget-content left">
<input type="text" size="60" id="advised_remit" name="advised_remit" value="<?=$list_Records[0]["advised_remit"]?>">&nbsp;


 
</td>
						</tr>
						
						
						<tr >
							<td class="ui-widget-header left" width="200" height="30" >Received Date</td>
							<td class="ui-widget-content left">
							<input type="text" name="received_date" id="received_date" size="30" value="<?php echo getAUDate($list_Records[0]['received_date']);?>" /></td>
								
						</tr>
						
						<tr >
							<td class="ui-widget-header left" width="200" height="30"  >Received Amount (inc. GST)</td>
							<td class="ui-widget-content left"><input type="text" size="60" id="received_amount" name="received_amount" value="<?=$list_Records[0]["received_amount"]?>">&nbsp;
<input type="checkbox" onclick="OnChangeCheckbox (this)" id="myCheckbox" /> (*Check if same as Advised Remittance  (inc. GST))</td>	
							
							
						</tr>
						
						
						
						
						<tr >
							<td class="ui-widget-header left" width="200" height="30"  >Retention Amount (inc. GST)</td>
							<td class="ui-widget-content left"><input type="text" size="60" name="retention_amount" value="<?=$list_Records[0]["retention_amount"]?>"></td>	
							
							
						</tr>
						
						
						<tr >
							<td class="ui-widget-header left" width="100" >Notes</td>
							<td class="ui-widget-content left"><textarea name="note" rows="5" cols="92"><?=$list_Records[0]["note"]?></textarea></td>	
						</tr>
						

				<input type="hidden" name="action_type" value="<?=$action_type?>"><input type="hidden" name="inv_no" value="<?=$inv_no?>">				
						</table>		
						<br>
						<table border="0" cellpadding="0" cellspacing="0" width="1000">
						<tr><td align="left">
						<?php if ($action_type=='modify') 
						{?><input type="button" value="Save as copy" onclick="formchk_copy();">
						
						
						<input type="button" value="Delete" onclick="javascript:if(confirm('Are you sure you want to delete this?')) { location.href='<?=$_SERVER['PHP_SELF']?>?inv_no=<?=$list_Records[0]["inv_no"]?>&action_type=delete';}">
						
						<?php } ?>
						
						</td><td align="right">Required fields are marked with an asterisk (*).<br><input type="button" value="Save" onclick="formchk();"></td></tr>
						
						
						
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