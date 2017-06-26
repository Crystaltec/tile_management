<?php
ob_start();
include_once "include/logincheck.inc";
include_once "include/common.inc";
include_once "include/dbconn.inc";
include_once "include/myfunc.inc";
include_once "htmlinclude/head.php";

$date_today= $_REQUEST["date_today"];
$act_flag = $_REQUEST["act_flag"];
$message = $_REQUEST["message"];
$author= $_REQUEST["author"];
$date = date("Y-m-d");

		
		
			if($act_flag == "add")
			{
				
$sql = "INSERT INTO notif_board (date, author, message) VALUES('$date_today','$author', '$message') ";

	pQuery($sql,"insert");
	echo "<script>alert('Notification Published!');location.href='index.php';</script>";

			
			
			
			} 
		

	
		
		
	
		
?>
<script language="javascript">


function save()
 {
	var f = document.notice_board;
	
		f.act_flag.value="add";
		f.action="<?=$_SERVER['PHP_SELF']?>";
		f.submit();
				
}




</script>
<BODY leftmargin=0 topmargin=0>

<table border="0" cellpadding="0" cellspacing="0" width="100%">
<tr>
<td style="padding-left:0px"><? include_once "top.php"; ?></td>
</tr>
	
	
	<td valign="top">
	<!-- BODY -------------------------------------------------------------------------------------------------->
	<table border="0" cellpadding="0" cellspacing="0" width="50%">
		<!-- BODY CENTER ----------------------------------------------------------------------------------------->
		<tr>
			<td width="50%">			
			<table border="0" cellpadding="0" cellspacing="0" width="50%">
			<tr>
				<td style="padding-left:15px" valign="bottom" height="14" colspan="2">					
				</td>
			</tr>	
			<tr>
				<td style="padding-left:15px" valign="top">
				<!-- CONTENTS -------------------------------------------------------------------------------------------->
				<table border="0" cellpadding="0" cellspacing="0" width="500">				
				<tr>
					<td>
					<br>
					<table border="0" cellpadding="0" cellspacing="0" width="500" class="font11_bold">
						<tr><td height="8"></td></tr>
						<tr><td width="252"><img src="images/icon_circle03.gif">&nbsp;<span style="height:21px">Notification Board Registry</span></td></tr>
						<tr><td height="3"></td></tr>
						
					</table>
					</td>
				</tr>
				<form name="notice_board" method="post">
				<tr>
					<td>
						<table border="0" cellpadding="0" cellspacing="0" width="500">
						<tr>
							<td colspan="4">
							<table border="0" cellpadding="0" cellspacing="0" width="500" >
							
							<tr><td colspan="2" style='height:3px;' background="images/bg_check.gif"></td></tr>
							</tbody>
							</table>
							<br>
							<table border="0" cellpadding="0" cellspacing="0" width="500" >
							<thead>
							<tr class='ui-widget-header'>
								<th colspan="2" height="30">Notification Board</th>
								
							</tr>
							</thead>
							<input type="hidden" name="<?=act_flag?>"/>
							<tbody>
							<tr>
								<td width="200" class="ui-widget-header left">Date</td>
								<td class='left'><input type="text" name="date_today"value="<?=$date?>"/></td>
							</tr>
						
							
							
							<tr>
								<td width="200" class="ui-widget-header left" height='30'>Author</td>	
								<td class='left'><input type="text" name="author"value="<?=$Sync_id?>"/></td>	
						        </tr>
								
								
							<tr>
								<td width="200" class="ui-widget-header left" height='30'>Message</td>
								
								<td class='left'><textarea rows="4" cols="50" name="message"> </textarea>
								
								</tr>
								<tr>
								</td>
								<td class="right" colspan="2"><input type="button" onclick="save()" value="SAVE"/>
								</td>
								</tr>
							
						
							</tbody>
							</table>
							</td>
							
						</tr>
						</table>
						
						
						
						
						</table>
					</td>
				</tr>
				</form>
				<tr><td height="40"></td></tr>
				</table>
				<!-- CONTENTS END -------------------------------------------------------------------------------------------->
			</td>
			<td>
			</td>
		  </tr>
		  <tr><td colspan="2" height="0"></td></tr>
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