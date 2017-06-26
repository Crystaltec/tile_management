<?
ob_start();
include_once "include/common.inc";
include_once "include/dbconn.inc";
include_once "include/myfunc.inc";
include_once "htmlinclude/head.php";

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
				<table border="0" cellpadding="0" cellspacing="0" width="780">
				<tr>
					<td valign="top">
					<table border="0" cellpadding="0" cellspacing="0" width="780" class="font11_bold">
						<tr><td height="8"></td></tr>
						<tr><td width="252"><img src="images/icon_circle03.gif">&nbsp;<span style="height:21px">Backup Detail</span></td></tr>
						<tr><td height="3"></td></tr>
						<tr><td background="images/bg_check.gif" height="3"></td></tr>
						<tr><td height="2"></td></tr>
					</table>
					</td>
				</tr>
				<tr>
					<td valign="top">
						<table border="0" width="780" cellpadding="2" cellspacing="0" bordercolor="#c3c3c6" bordercolordark="white">
						<tr>
							<td>
							<?
								// Get a file into an array.  In this example we'll go through HTTP to get 
								// the HTML source of a URL.
								$filename = $_REQUEST["filename"];
								$lines = file('dbbackup/'.$filename);

								// Loop through our array, show HTML source as HTML source; and line numbers too.
								foreach ($lines as $line_num => $line) {
								   echo "Line #<b>{$line_num}</b> : " . htmlspecialchars($line) . "<br />\n";
								}
								// Another example, let's get a web page into a string.  See also file_get_contents().
								//$html = implode('', file('http://www.example.com/'));
							?>
							</td>
						</tr>						
						</table>
						<br>
						<table border="0" cellpadding="0" cellspacing="0" width="780">
						<tr><td align="right"><input type="button" value="Restore" onclick="if(confirm('Warning!!!\r\nDo you really want to restore now?')) {location.replace('backup_restore_do.php?filename=<?=$filename?>');}"></td></tr>
						</table>
						<br>
						Format : DatabaseName-NowDate-NowTime.sql
					</td>
				</tr>
				<tr><td></td></tr>
				</table>
				<!-- CONTENTS END -------------------------------------------------------------------------------------------->
			</td>
			<td>
			</td>
		  </tr>
		  <tr><td colspan="2" height="50"></td></tr>
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