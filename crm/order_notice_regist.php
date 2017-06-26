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
	
	if(frm.order_notice_name.value =="") {
		alert("Please, Insert order_notice name!");
		frm.order_notice_name.focus();
		return;
	}

	frm.submit();
}

$(function() {
	$("input:button, button").button();
	$('.ui-widget-content').css({'background-image' :'none','background-color':'none'});
});
</script>
<?php

$order_notice_id = $_REQUEST["order_notice_id"];
$action_type = $_REQUEST["action_type"];

## ĵ��, �〻 �迭 ����
$list_Records = array();
$Rs_id  = array();
$Rs_id["order_notice_id"]	= NULL;
$Rs_id["order_notice_name"]	= NULL;
$Rs_id["sortno"] = NULL;

$Query  = "SELECT " . selectQuery($Rs_id, "order_notice");
$Query .= " FROM order_notice WHERE order_notice_id='". $order_notice_id ."'";

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
<br>
	<td valign="top" width="100" height="100%">
	<!-- LEFT -------------------------------------------------------------------------------------------------->
	<? //include_once "left.php";?>
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
		<!-- BODY TOP ------------------------------------------------------------------------------------------->
		<tr>
			<td style="padding-left:0px"><? include_once "top.php"; ?></td>
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
						<tr><td width="600"><img src="images/icon_circle03.gif">&nbsp;<span style="height:21px">Delivery Requirements/Instructions Register</span></td></tr>
						<tr><td height="3"></td></tr>
						<tr><td background="images/bg_check.gif" height="3"></td></tr>
						<tr><td height="2"></td></tr>
					</table>
					</td>
				</tr>
				
				<form name="frm1" method="post" action="order_notice_regist_ok.php">
				<tr>
					<td valign="top">
						<table border="0" cellpadding="0" cellspacing="0" width="1000" >
						<tr>
							<td style="padding-left:3px" align="left" width="200"  class="ui-widget-header">Description</td>
							<td style="padding-left:3px"  class="ui-widget-content"><textarea name="order_notice_name" rows="2" cols="90" ><?=$list_Records[0]['order_notice_name']?></textarea></td>
						</tr>
						<input type="hidden" name="action_type" value="<?=$action_type?>">
						<input type="hidden" name="order_notice_id" value="<?=$order_notice_id?>">					
						<tr class="ui-widget-header" >
							<?php if ($action_type == '' ) {
								$max_sortno = getRowCount2(" select max(sortno) from order_notice ");
								if ($list_Records[0]["sortno"] == NULL) {
									$list_Records[0]["sortno"] = $max_sortno +1;
								}
							}
							?>
							<td style="padding-left:3px" align="left" width="200" class="ui-widget-header">Sort No.</td>
							<td style="padding-left:3px" class="ui-widget-content"><input type="text" size="3" maxlength="4" name="sortno" value="<?=$list_Records[0]["sortno"]?>"></td>
						</tr>
						</table>		
						<br>
						<table border="0" cellpadding="0" cellspacing="0" width="1000">
						<tr><td align="right"><input type="button" value="Save" onclick="formchk();"></td></tr>
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
		  <tr><td colspan="2" height="400"></td></tr>
		 </table>
		 </td>
	    </tr>
	    <!-- BODY CENTER END------------------------------------------------------------------------------------->
	   </table>
	<!-- BODY END -------------------------------------------------------------------------------------------->
	</td>

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