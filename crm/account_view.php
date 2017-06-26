<?php
ob_start();
include_once "include/common.inc";
include_once "include/dbconn.inc";
include_once "include/myfunc.inc";
include_once "htmlinclude/head.php";

$req_userid = $_REQUEST["userid"];
## 쿼리, 담을 배열 선언
$list_Records = array();


$Query  = "SELECT * ";
$Query .= " FROM account WHERE userid='$req_userid'";

$id_cnn = mysql_query($Query) or exit(mysql_error());
while($id_rst = mysql_fetch_assoc($id_cnn)) {
	$list_Records = array_merge($list_Records, array($id_rst));
}
$alevel_txt = getLevelTxt($list_Records[0]["alevel"]);
?>
<script language="Javascript">
$(function() {
	$("input:button, button").button();
	$('.ui-widget-content').css({'background-image' :'none','background-color':'none'});
});
</script>
<BODY leftmargin=0 topmargin=0>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
<!-- BODY TOP ------------------------------------------------------------------------------------------->
<tr>
<td style="padding-left:0px"><? include_once "top.php"; ?></td>
</tr>
<!-- BODY TOP END --------------------------------------------------------------------------------------->

<table border="0" cellpadding="0" cellspacing="0" width="100%">

	<td valign="top" width="191" height="100%">
	<!-- LEFT -------------------------------------------------------------------------------------------------->
	<? include_once "left.php"; ?>
	<!-- LEFT END ---------------------------------------------------------------------------------------------->
	</td>
	<!-- LEFT BG------------------------------------------------------------------------------------------------>
	<td width="3" background="images/gray.jpg">
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
						<tr><td width="252"><img src="images/icon_circle03.gif">&nbsp;<span style="height:21px">Account Detail</span></td></tr>
						<tr><td height="3"></td></tr>
						<tr><td background="images/bg_check.gif" height="3"></td></tr>
						<tr><td height="2"></td></tr>
					</table>
					</td>
				</tr>
				<form name="frm1" method="post" action="account_regist_ok.php">
				<tr>
					<td valign="top">
						<table border="1" cellpadding="0" cellspacing="0"  width="1000">
						<tr>
							<td style="padding-left:3px" width="200" align="left" height="30" class='ui-widget-header'>Name</td>
							<td style="padding-left:3px" class="ui-widget-content"><?=$list_Records[0]["username"]?></td>							</tr>
						<tr>
							<td style="padding-left:3px" align="left" height="30" class='ui-widget-header'>User Id</td>
							<td style="padding-left:3px" class="ui-widget-content"><?=$list_Records[0]["userid"]?>&nbsp;</td>	
						</tr>		
						<tr >
							<td style="padding-left:3px" align="left" height="30" class='ui-widget-header'>Account Level</td>
							<td style="padding-left:3px" class="ui-widget-content"><?=$alevel_txt?>&nbsp;</td>	
						</tr>
						<tr >
							<td style="padding-left:3px" align="left" height="30" class='ui-widget-header'>Address</td>
							<td style="padding-left:3px" class="ui-widget-content"><?=$list_Records[0]["address"]?>&nbsp; <?=$list_Records[0]["suburb"]?>&nbsp;<?=$list_Records[0]["state"]?>&nbsp;<?=$list_Records[0]["postcode"]?></td>	
						</tr>		
						<tr >
							<td style="padding-left:3px" align="left" height="30" class='ui-widget-header'>Phone</td>
							<td style="padding-left:3px" class="ui-widget-content"><?=$list_Records[0]["phone"]?>&nbsp;</td>	
						</tr>
						<tr >
							<td style="padding-left:3px" align="left" height="30" class='ui-widget-header'>Fax</td>
							<td style="padding-left:3px" class="ui-widget-content"><?=$list_Records[0]["fax"]?>&nbsp;</td>	
						</tr>
						<tr>
						<td style="padding-left:3px" align="left" height="30" class='ui-widget-header'>Email</td>
						<td style="padding-left:3px" class="ui-widget-content"><?=$list_Records[0]["email"]?>&nbsp;</td>	
						</tr>	
						<tr>
							<td style="padding-left:3px" align="left" height="30" class='ui-widget-header'>Display</td>
							<td style="padding-left:3px" class="ui-widget-content"><?=$list_Records[0]["display"]?>&nbsp;</td>	
						</tr>
						<tr>
							<td style="padding-left:3px" align="left" height="30" class='ui-widget-header'>Status</td>
							<td style="padding-left:3px" class="ui-widget-content"><?=$list_Records[0]["status"]?>&nbsp;</td>	
						</tr>	
						<tr >
							<td style="padding-left:3px" align="left" height="30" class='ui-widget-header'>Remarks</td>
							<td style="padding-left:3px" class="ui-widget-content" ><?=nl2br($list_Records[0]["remarks"])?>&nbsp;</td>							</tr>		
						</table>		
						<br>
						<table border="0" cellpadding="0" cellspacing="0" width="1000">
						<tr><td align="left" width="100"><input type="button" value="List" onclick="location.href='account_list.php'"></td><td align="right">
						<? if($Sync_alevel =="A1" || $list_Records[0]["alevel"] > $Sync_alevel || $list_Records[0]["userid"]==$Sync_id) { ?>
							<input type="button" value="Modify" onclick="location.href='account_regist.php?action_type=modify&userid=<?=$list_Records[0]["userid"]?>'">
						<? } ?>
						<? if($Sync_alevel =="A1" || $list_Records[0]["alevel"] > $Sync_alevel) { ?>&nbsp;
							<input type="button" value="Delete" onclick="if(confirm('Do you want to delete this account ?')) {location.href='account_regist.php?action_type=delete&userid=<?=$list_Records[0]["userid"]?>';}"><?}?></td></tr>
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
		  <tr><td colspan="2" height="445"></td></tr>
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