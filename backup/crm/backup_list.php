<?php
ob_start();
include_once "include/logincheck.inc";
include_once "include/common.inc";
include_once "include/dbconn.inc";
include_once "include/myfunc.inc";
include_once "htmlinclude/head.php";

if ($_GET['act'] == "delete" && secure_string($_GET['fn']) <> "") {
	//echo 	"/crm/dbbackup/".$_GET['fn'];
	unlink("/home/sungoldt/public_html/crm/dbbackup/".$_GET['fn']);
	echo "<script>location.href='backup_list.php';</script>";
}
?>
<script language="Javascript">
$(function() {
	$("input:button, button").button();
	$(".list_table thead").addClass('ui-widget-header');
	$(".list_table tbody").addClass('ui-widget-content');
	$('.ui-widget-content').css({'background-image' :'none','background-color':'none'});
	});
</script>
<BODY leftmargin=0 topmargin=0>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
<tr>
	<td valign="top" width="191" height="100%">
	<!-- LEFT -------------------------------------------------------------------------------------------------->
	<?php include_once "left.php"; ?>
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
					<td valign="top">
					<table border="0" cellpadding="0" cellspacing="0" width="1000" class="font11_bold">
						<tr><td height="8"></td></tr>
						<tr><td width="252"><img src="images/icon_circle03.gif">&nbsp;<span style="height:21px">Backup List</span></td></tr>
						<tr><td height="3"></td></tr>
						<tr><td background="images/bg_check.gif" height="3"></td></tr>
						<tr><td height="2"></td></tr>
					</table>
					</td>
				</tr>
				<form name="backupForm" method="POST" action="<?=$_SERVER["PHP_SELF"]?>">
				<input type="hidden" name="act" value="<?=$act?>">
				<tr>
					<td valign="top">
						<table border="0" width="1000" cellpadding="0" cellspacing="1" class='list_table'>
						<thead>
						<tr align="center" >
							<td>Backup File Name</td>
							<td width="200">FileSize (Bytes)</td>
							<td width="50">DEL</td>
						</tr>
						</thead>
						<tbody>
						<?php
							$dir = getenv("DOCUMENT_ROOT") . "/crm/dbbackup/";
							if(is_dir($dir)) {
								if($dh = opendir($dir)) {
									$files = array();
									 
									while(($filename = readdir($dh)) != false) {
										if(strpos($filename,".sql") > 0) {
											array_push($files, $filename);
										}											
									}
									closedir($dh);
								}
							}
							rsort($files);
							$count = 0;
							foreach ($files as $file) {

							$filesize = filesize($dir.$file);
								if($count%2 == 0){
									$even_odd = ' class="even" ';
								} else
									$even_odd = ' class="odd" ';
								
						?>
									<tr align="center" <?php echo $even_odd;?>  style="background-color:'';color:#000000" onmouseover="javascript:this.style.color='#536499';this.style.backgroundColor='#dcebfe';"  onmouseout="javascript:this.style.color='#000000';this.style.backgroundColor='';">
										<td><a href="backup_view.php?filename=<?=$file;?>"><b><?=$file;?></b></a></td>
										<td><?=number_format($filesize);?></td>
										<td><a href="javascript:if(confirm('Are you sure?')) { location.href='<?=$_SERVER['PHP_SELF']?>?fn=<?=$file?>&act=delete';}"><img src="zb/images/x.gif"></a></td>
									</tr>
						<?php
							$count += 1;
							}
						?>		
						</tbody>						
						</table>
						<br>
						<table border="0" cellpadding="0" cellspacing="0" width="1000">
						<tr><td align="right"><input type="button" value="Now Backup" onclick="if(confirm('Do you want to backup now?')) {location.replace('backup_do.php');}"></td></tr>
						</table>
						<br>
						Format : DatabaseName-NowDate-NowTime.sql
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
		  <tr><td colspan="2" height="481"></td></tr>
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
	<?php include_once "bottom.php"; ?>
	<!-- BOTTOM END -------------------------------------------------------------------------------------------->
	</td>
</tr>
</table>
</BODY>
</HTML>
<?php ob_flush(); ?>
