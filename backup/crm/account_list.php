<?php
ob_start();
include_once "include/logincheck.inc";
include_once "include/common.inc";
include_once "include/dbconn.inc";
include_once "include/myfunc.inc";
include_once "htmlinclude/head.php";
?>
<script language="Javascript">
$(function() {
	$("input:button, button").button();
	$(".list_table thead").addClass('ui-widget-header');
	$(".list_table tbody").addClass('ui-widget-content');
	$('.ui-widget-content').css({'background-image' :'none','background-color':'none'});
	
	$('#status_title').qtip({
		content: 'Block the account',
		position :{
			adjust: { 
			x: -10,
			y:-70
			}
		},
		style: { 
		    width: 125,
		    padding: 2,
		    color: 'black',
		    textAlign: 'center',
		    border: {
			width: 1,
			radius: 3,
			},
		   	tip: 'bottomLeft',
			name: 'cream' 
		}
	});
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
			<td style="padding-left:15px"><?php include_once "top.php"; ?></td>
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
						<tr><td width="252"><img src="images/icon_circle03.gif">&nbsp;<span style="height:21px">Account List</span></td></tr>
						<tr><td height="3"></td></tr>
						<tr><td background="images/bg_check.gif" height="3"></td></tr>
						<tr><td height="2"></td></tr>
					</table>
					</td>
				</tr>
				<tr>
					<td valign="top">
						<table border="0" width="1000" cellpadding="0" cellspacing="1" class='list_table'>
						<thead>
						<tr align="center" >
							<th width="40">No</th>
							<th width="150">User Id</th>
							<th>Name</th>
							<th width="100">Account Level</th>
							<th width="60">Display</th>
							<th width="60" id="status_title">Status</th>
							<th width="50"></th>
						</tr>
						</thead>
						<tbody>
						<?
							## 쿼리, 담을 배열 선언
							$list_Records = array();
					
							if($Sync_alevel == "C1") {
								$Cond = " WHERE userid='" .$Sync_id."'";
							} else {
								$Cond = " WHERE alevel >='" .$Sync_alevel."'";
							}

							$Query  = "SELECT * ";
							$Query .= " FROM account ".$Cond." ORDER BY alevel ASC, userid ASC";
							//echo $Query;

							$id_cnn = mysql_query($Query) or exit(mysql_error());
							while($id_rst = mysql_fetch_assoc($id_cnn)) {
								$list_Records = array_merge($list_Records, array($id_rst));
								//print_r($list_Records);
								//echo "<p>";
							}
							//echo count($list_Records);
							$cnt = count($list_Records);
							if(is_array($list_Records)) {
							for($i=0; $i<count($list_Records); $i++) {
								$alevel_txt = "";
								if($list_Records[$i]["alevel"] == "A1") {
									$alevel_txt="Admin";
								} else if($list_Records[$i]["alevel"] == "B1") {
									$alevel_txt="Manager";
								} else if($list_Records[$i]["alevel"] == "B2") {
									$alevel_txt="Staff";
								} 
			
								
								if($i%2 == 0){
									$even_odd = ' class="even" ';
								} else
									$even_odd = ' class="odd" ';
						?>
						<tr align="center" <?php echo $even_odd;?> onmouseover="javascript:this.style.color='#536499';this.style.backgroundColor='#dcebfe';"  onmouseout="javascript:this.style.color='#000000';this.style.backgroundColor='';">
							<td height='25'><?=($cnt-$i)?></td>
							<td><a href="account_view.php?userid=<?=$list_Records[$i]["userid"]?>"><b><?=$list_Records[$i]["userid"]?></b></a>&nbsp;</td>
							<td><?=$list_Records[$i]["username"]?>&nbsp;</td>
							<td><?=$alevel_txt?>&nbsp;</td>
							<td><?php echo $list_Records[$i]['display']?></td>
							<td><?php echo $list_Records[$i]['status']?></td>
							<td><a href="account_view.php?userid=<?=$list_Records[$i]["userid"]?>">[EDIT]</a></td>
						</tr>			
						<?
							}
							} else {
								echo "<tr><td colspan=4 height=40 align='center'>Nothing to display.</td></tr>";
							}
						?>
						</tbody>
						</table>
						<br>
						<table border="0" cellpadding="0" cellspacing="0" width="1000">
						<tr><td align="right"><input type="button" value="New Account" onclick="location.href='account_regist.php'"></td></tr>
						</table>
						<br>
						- Click the name of item to view details.
					</td>
				</tr>
				</table>
				<!-- CONTENTS END -------------------------------------------------------------------------------------------->
			</td>
			<td>
			</td>
		  </tr>
		  <tr><td colspan="2" height="202"></td></tr>
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
</html>
<?php ob_flush();?>
