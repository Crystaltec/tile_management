<?
include_once "include/common.inc";
include_once "include/dbconn.inc";
include_once "include/myfunc.inc";
include_once $ABS_DIR . "/htmlinclude/head.php";
$ORDERNO=$_REQUEST["ORDERNO"];

echo $_SESSION["USER_ID"] ."<br>";
echo $_SESSION["USER_NAME"] ."<br>";
echo $_SESSION["A_LEVEL"] ."<br>";
echo $_SESSION["O_LEVEL"] ."<br>";
echo $_SESSION["PAYMENT_METHOD"] ."<br>";
?>
<script type="text/javascript">
function issueTaxinvoice(orderno) {
	window.open("issue_taxinvoice.php?orderno="+orderno, "abc", "");
}
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
				<div style="position:absolute;top:100px">
				<TABLE border="0" cellpadding="0" cellspacing="0" width="700" style="border:1px solid #c3c3c6">
				<TR>
					<TD width="300" align="right"><img src="images/logo2.jpg"></TD>
					<TD>
						<table border="0" cellpadding="0" cellspacing="0" align="left" width="400">
						<tr><td height="80" align="left"><span style="font-size:16pt;color:black;font-weight:bold">Order Completed!</span></td></tr>
						<tr><td align="left" height="50" valign="top"><span style="font-size:10pt;"><b><font color="#FE005A"><?=$Sync_id?>!</font></b> Thank you for your orders!</span></td></tr>
						</table>			
					</TD>
				</TR>
				<TR>
					<TD height="80" align="center" valign="bottom" colspan="2"><input type="button" value="View Invoice" onClick="issueTaxinvoice(<?=$ORDERNO?>)">&nbsp;&nbsp;<input type="button" value="Back to Home" onClick="location.href='/index.php'"></TD>
				</TR>
				<tr><td height="200"></td></tr>
				</TABLE>
				</div>
				<!-- CONTENTS END -------------------------------------------------------------------------------------------->
			</td>
			<td>
			</td>
		  </tr>
		  <tr><td colspan="2" height="735"></td></tr>
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