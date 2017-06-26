<?php
ob_start();
include_once "include/logincheck.inc";
include_once "include/common.inc";
include_once "include/dbconn.inc";
include_once "include/myfunc.inc";
include_once "htmlinclude/head.php";

$visa_id = $_REQUEST["visa_id"];
$action_type = $_REQUEST["action_type"];

## 쿼리, 담을 배열 선언
$list_Records = array();

$Query  = "SELECT * ";
$Query .= " FROM visa v WHERE visa_id='". $visa_id ."' ";

//echo $Query;

$id_cnn = mysql_query($Query) or exit(mysql_error());
while($id_rst = mysql_fetch_assoc($id_cnn)) {
	$list_Records = array_merge($list_Records, array($id_rst));
	//print_r($list_Records);
	//echo "<p>";
}

?>
<script language="Javascript">
function formchk() {
	frm = document.frm1;
	
	if(frm.visa_name.value =="") {
		alert("Please, Insert visa name!");
		frm.visa_name.focus();
		return;
	}

	frm.submit();
}
$(function() {
	$( "input:button, button").button();
});
</script>
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
						<tr><td width="252"><img src="images/icon_circle03.gif">&nbsp;<span style="height:21px">Visa Register</span></td></tr>
						<tr><td height="3"></td></tr>
						<tr><td background="images/bg_check.gif" height="3"></td></tr>
					</table>
					</td>
				</tr>
				<form name="frm1" method="post" action="visa_regist_ok.php">
				<tr>
					<td valign="top">
						<table border="0" cellpadding="0" cellspacing="0" width="1000" >
						
						<tr class='ui-widget-header'>
							<td style="padding-left:3px" align="left" width="130">Visa Name</td><td style="padding-left:3px"><input type="text" size="30" maxlength="30" name="visa_name" value="<?=$list_Records[0]["visa_name"]?>"></td>	
						</tr>
						<?php if($action_type) { ?>
							 
						<tr class='ui-widget-content'>
							<td style="padding-left:3px" align="right" colspan="2">Inserted by&nbsp;<?php echo getValue('account', 'username', ' AND userid="'.$list_Records[0]['account_id'].'"')?>&nbsp;<?php echo getAUDate($list_Records[0]["regdate"],1)?>
							<?php 
							$list_history = array();

							$Query  = "SELECT * ";
							$Query .= " FROM history WHERE history_table='visa' AND history_table_id ='". $visa_id ."' ";
							
							//echo $Query;
							
							$result = mysql_query($Query) or exit(mysql_error());
							if ($result) {
								echo "<br />Updated by ";
								while ($row = mysql_fetch_assoc($result)) {
									echo getValue('account','username',' AND userid="'.$row['account_id'].'" '). " ". getAUDate($row["regdate"],1) ."<br />";
								}
							}
							?>
							
							</td>	
						</tr>
						<?php }?>
						<input type="hidden" name="action_type" value="<?=$action_type?>"><input type="hidden" name="visa_id" value="<?=$visa_id?>">				
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
		  <tr><td colspan="2" height="628"></td></tr>
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