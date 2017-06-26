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
	
	if(frm.supplier_name.value =="") {
		alert("Please, Insert supplier name!");
		frm.supplier_name.focus();
		return;
	}

	if(frm.supplier_phone_number.value =="") {
		alert("Please, Insert phone number!");
		frm.supplier_phone_number.focus();
		return;
	}

	if(confirm("Press OK to confirm?")) {
		frm.submit();
	}
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

$supplier_id = $_REQUEST["supplier_id"];
$action_type = $_REQUEST["action_type"];

## ĵ��, �〻 �迭 ����
$list_Records = array();

$Query  = "SELECT * ";
$Query .= " FROM supplier WHERE supplier_id='". $supplier_id ."'";

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
					<td  valign="top">
					<table border="0" cellpadding="0" cellspacing="0" width="1000" class="font11_bold">
						<tr><td height="8"></td></tr>
						<tr><td width="252"><img src="images/icon_circle03.gif">&nbsp;<span style="height:21px">Supplier Register</span></td></tr>
						<tr><td height="3"></td></tr>
						<tr><td background="images/bg_check.gif" height="3"></td></tr>
						<tr><td height="2"></td></tr>
					</table>
					</td>
				</tr>
				<form name="frm1" method="post" action="supplier_regist_ok.php">
				<tr>
					<td valign="top">
						<table border="0" cellpadding="0" cellspacing="0" width="1000">
						<tr>
							<td width="200" class='ui-widget-header left' height="30">Category</td>
							<td class="ui-widget-content left"><?php echo DrawFromDB('supplier','supplier_category','select',$list_Records[0]['supplier_category'],'yes','',NULL)?></td>
						</tr>	
						<tr >
							<td width="200" class='ui-widget-header left' height="30">*Name</td>
							<td class="ui-widget-content left" height="30"><input type="text" size="60" name="supplier_name" value="<?=$list_Records[0]["supplier_name"]?>"></td>	
						</tr>
						<tr>
							<td width="200" class='ui-widget-header left' height="30">Account</td>
							<td class="ui-widget-content left"><?php echo DrawFromDB('supplier','supplier_account','select',$list_Records[0]['supplier_account'],'yes','',NULL)?></td>
						</tr>
						<tr>
							<td width="200" class='ui-widget-header left' height="30">Account Limitation</td>
							<td class="ui-widget-content left"><input type="text" size="60" name="supplier_account_limitation" value="<?=$list_Records[0]["supplier_account_limitation"]?>">eg., 10000.00</td>
						</tr>
						<tr>
							<td width="200" class='ui-widget-header left' height="30">Payment Term <button id="add_payment_term"><span class="ui-icon ui-icon-plusthick"></span></button> </td>
							<td class="ui-widget-content left"><?php getOption("payment_term",$list_Records[0]['payment_term_id'],NULL," id='payment_term_id' ")?></td>
						</tr>
						<tr >
							<td width="130" class='ui-widget-header left'>Address</td>
							<td class="ui-widget-content left" >
							<table border="0" cellpadding="0" cellspacing="0" width="700">
							<tr>
								<td width="130" height="24" class="left"> Address </td>
								<td><input type="text" size="60" name="supplier_address" value="<?=$list_Records[0]["supplier_address"]?>"></td>
							</tr>
							<tr>
								<td width="130" height="24" class="left">City </td>
								<td><input type="text" size="60" name="supplier_suburb" value="<?=$list_Records[0]["supplier_suburb"]?>"></td>
							</tr>
							<tr>
								<td width="130" height="24" class="left">State</td><td>
								<?php getStateOption("supplier_state",$list_Records[0]["supplier_state"]); ?></td>
							</tr>
							<tr>
								<td width="130" height="24" class="left"> Postal Code</td>
								<td><input type="text" size="60" name="supplier_postcode" value="<?=$list_Records[0]["supplier_postcode"]?>"></td>
							</tr>
							
							</table>			
							</td>	
						</tr>
						<tr >
							<td width="130" class='ui-widget-header left' height="30">*Phone number</td>
							<td class="ui-widget-content left"><input type="text" size="60" name="supplier_phone_number" value="<?=$list_Records[0]["supplier_phone_number"]?>"></td>	
						</tr>
						<tr >
							<td width="130" class='ui-widget-header left' height="30">Fax number</td>
							<td  class="ui-widget-content left"><input type="text" size="60" name="supplier_fax_number" value="<?=$list_Records[0]["supplier_fax_number"]?>"></td>	
						</tr>
						<tr >
							<td width="130" class='ui-widget-header left' height="30">Web</td>
							<td class="ui-widget-content left"><input type="text" size="60" name="supplier_web" value="<?=$list_Records[0]["supplier_web"]?>"></td>	
						</tr>
						<tr >
							<td width="130" class='ui-widget-header left' height="30">Email</td>
							<td class="ui-widget-content left"><input type="text" size="60" name="supplier_email" value="<?=$list_Records[0]["supplier_email"]?>"></td>	
						</tr>
						<tr >
							<td width="130" class='ui-widget-header left' height="30">Sales manager</td>
							<td class="ui-widget-content left"><input type="text" size="60" name="supplier_sales_manager" value="<?=$list_Records[0]["supplier_sales_manager"]?>"></td>	
						</tr>
						
						<tr >
							<td width="130" class='ui-widget-header left' >Remarks</td>
							<td class="ui-widget-content left"><textarea name="supplier_comments" rows="5" cols="102"><?=$list_Records[0]["supplier_comments"]?></textarea></td>	
						</tr>
						<input type="hidden" name="action_type" value="<?=$action_type?>"><input type="hidden" name="supplier_id" value="<?=$supplier_id?>">					
						</table>		
						<br>
						<table border="0" cellpadding="0" cellspacing="0" width="1000">
						<tr><td align="right">Required fields are marked with an asterisk (*).<br><input type="button" value="Save" onclick="formchk();"></td></tr>
						</table>
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
<? ob_flush(); ?>