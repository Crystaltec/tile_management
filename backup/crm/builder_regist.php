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
	
	if(frm.builder_name.value =="") {
		alert("Please, Insert builder name!");
		frm.builder_name.focus();
		return;
	}

	if(frm.builder_phone_number.value =="") {
		alert("Please, Insert phone number!");
		frm.builder_phone_number.focus();
		return;
	}

	if(confirm("Press OK to confirm?")) {
		frm.submit();
	}
}

$(function() {
	$("input:button, button").button();
	$('.ui-widget-content').css({'background-image' :'none','background-color':'none'});
});
</script>
<?php

$builder_id = $_REQUEST["builder_id"];
$action_type = $_REQUEST["action_type"];

## ĵ��, �〻 �迭 ����
$list_Records = array();

$Query  = "SELECT * ";
$Query .= " FROM builder WHERE builder_id='". $builder_id ."'";

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
						<tr><td width="252"><img src="images/icon_circle03.gif">&nbsp;<span style="height:21px">Builder Register</span></td></tr>
						<tr><td height="3"></td></tr>
						<tr><td background="images/bg_check.gif" height="3"></td></tr>
						<tr><td height="2"></td></tr>
					</table>
					</td>
				</tr>

				<form name="frm1" method="post" action="builder_regist_ok.php">
				<tr>
					<td valign="top">
						<table border="0" cellpadding="0" cellspacing="0" width="1000">
							
						<tr >
							<td style="padding-left:3px" align="left" class="ui-widget-header" width="200" >*Name</td>
							<td style="padding-left:3px" class="ui-widget-content"><input type="text" size="60" name="builder_name" value="<?=$list_Records[0]["builder_name"]?>"></td>	
						</tr>
						<tr >
							<td style="padding-left:3px"  align="left" class="ui-widget-header"  >Address</td>
							<td style="padding-left:3px" class="ui-widget-content"><input type="text" size="60" name="builder_address" value="<?=$list_Records[0]["builder_address"]?>"></td>	
						</tr>
						<tr >
							<td style="padding-left:3px"  align="left" class="ui-widget-header"  >Suburb</td>
							<td style="padding-left:3px" class="ui-widget-content"><input type="text" size="60" name="builder_suburb" value="<?=$list_Records[0]["builder_suburb"]?>"></td>	
						</tr>
						<tr >
							<td style="padding-left:3px"  align="left" class="ui-widget-header"  >State</td>
							<td style="padding-left:3px" class="ui-widget-content"><? getStateOption("builder_state",$list_Records[0]["builder_state"]); ?>
							</td>	
						</tr>
						<tr >
							<td style="padding-left:3px"  align="left"  class="ui-widget-header" >Postcode</td>
							<td style="padding-left:3px" class="ui-widget-content"><input type="text" size="60" name="builder_postcode" value="<?=$list_Records[0]["builder_postcode"]?>"></td>	
						</tr>
						<tr >
							<td style="padding-left:3px"  align="left"  class="ui-widget-header" >*Phone number</td>
							<td style="padding-left:3px" class="ui-widget-content"><input type="text" size="60" name="builder_phone_number" value="<?=$list_Records[0]["builder_phone_number"]?>"></td>	
						</tr>
						<tr >
							<td style="padding-left:3px"  align="left" class="ui-widget-header"  >Fax number</td>
							<td style="padding-left:3px" class="ui-widget-content"><input type="text" size="60" name="builder_fax_number" value="<?=$list_Records[0]["builder_fax_number"]?>"></td>	
						</tr>
						<tr >
							<td style="padding-left:3px"  align="left"  class="ui-widget-header" >Web</td>
							<td style="padding-left:3px" class="ui-widget-content"><input type="text" size="60" name="builder_web" value="<?=$list_Records[0]["builder_web"]?>"></td>	
						</tr>
						<tr >
							<td style="padding-left:3px"  align="left" class="ui-widget-header"  >Email</td>
							<td style="padding-left:3px" class="ui-widget-content"><input type="text" size="60" name="builder_email" value="<?=$list_Records[0]["builder_email"]?>"></td>	
						</tr>
						<tr >
							<td style="padding-left:3px"  align="left"  class="ui-widget-header" >Sales manager</td>
							<td style="padding-left:3px" class="ui-widget-content"><input type="text" size="60" name="builder_sales_manager" value="<?=$list_Records[0]["builder_sales_manager"]?>"></td>	
						</tr>
						<tr >
							<td style="padding-left:3px"   align="left"  class="ui-widget-header" >Remarks</td>
							<td style="padding-left:3px" class="ui-widget-content"><textarea name="builder_comments" rows="5" cols="94"><?=$list_Records[0]["builder_comments"]?></textarea></td>	
						</tr>
						<input type="hidden" name="action_type" value="<?=$action_type?>"><input type="hidden" name="builder_id" value="<?=$builder_id?>">					
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