<?php
ob_start();

include_once "include/logincheck.inc";
include_once "include/common.inc";
include_once "include/dbconn.inc";
include_once "include/myfunc.inc";
include_once "htmlinclude/head.php";
?>
<script language="Javascript">
function searchNow() {
	var f = document.searchform;

	f.action="<?=$_SERVER['PHP_SELF']?>";
	f.submit();
}

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
	<!-- LEFT -->
	<? include_once "left.php"; ?>
	<!-- LEFT END -->
	</td>
	<!-- LEFT BG -->
	<td width="1" bgcolor="#DFDFDF">
	</td>
	<!-- LEFT BG END -->
	<td valign="top">
	<!-- BODY  -->
	<table border="0" cellpadding="0" cellspacing="0" width="100%">
		<!-- BODY TOP  -->
		<tr>
			<td style="padding-left:15px"><? include_once "top.php"; ?></td>
		</tr>
		<!-- BODY TOP END -->
		<!-- BODY CENTER -->
		<tr>
			<td width="100%">			
			<table border="0" cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td style="padding-left:15px" valign="bottom" height="14" colspan="2">					
				</td>
			</tr>	
			<tr>
				<td style="padding-left:15px" valign="top">
				<!-- CONTENTS -->
				<table border="0" cellpadding="0" cellspacing="0" width="1000">		
				<tr>
					<td valign="top">
					<table border="0" cellpadding="0" cellspacing="0" width="1000" class="font11_bold">
						<tr><td height="8"></td></tr>
						<tr><td width="252"  ><img src="images/icon_circle03.gif" style="vertical-align:middle;">&nbsp;<span style="height:21px; ">Company Details</span></td></tr>
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
							<th width="390">Name</th>
							<th width="390">ABN</th>
							<th width="120">Phone</th>
							<th></th>
						</tr>
						</thead>
						<tbody>
						<?
							// 페이지 계산 /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
							$page = $_REQUEST["page"];
							if(!$page)
								$page = 1;
						
							$limitPage = 5;
							$limitList = 20;
							$total = getRowCount2("SELECT COUNT(*) FROM company ");
							//echo ceil(1.2);
							$totalPage = ceil($total/$limitList);
							$block = ceil($page/$limitPage);
							$start = ($page-1)*$limitList;

							$startPage = ($block-1)*$limitPage + 1;
							$endPage = $startPage + $limitPage - 1;
							if ($endPage > $totalPage ) $endPage = $totalPage; 
							// 페이지 계산 끝//////////////////////////////////////////////////////////////////////////////////////////////////////////////////

							## 쿼리, 담을 배열 선언
							$list_Records = array();
									
							$Query  = "SELECT * ";
							$Query .= " FROM company ORDER BY company_name ASC LIMIT $start, $limitList";
							//echo $Query;

							$id_cnn = mysql_query($Query) or exit(mysql_error());
							while($id_rst = mysql_fetch_assoc($id_cnn)) {
								$list_Records = array_merge($list_Records, array($id_rst));
								//print_r($list_Records);
								//echo "<p>";
							}
							//echo count($list_Records);
							$cnt = count($list_Records);
							if($cnt) {
							for($i=0; $i<count($list_Records); $i++) {
								
						
						?>
						<tr align="center" style="background-color:'';color:#000000" onmouseover="javascript:this.style.color='#536499';this.style.backgroundColor='#dcebfe';"  onmouseout="javascript:this.style.color='#000000';this.style.backgroundColor='';" height="25">
							<td><a href="company_regist.php?action_type=modify&company_id=<?=$list_Records[$i]["company_id"]?>"><b><?=$list_Records[$i]["company_name"]?></b></a>&nbsp;</td>
							<td><a href="company_regist.php?action_type=modify&company_id=<?=$list_Records[$i]["company_id"]?>"><b><?=$list_Records[$i]["company_abn"]?></b></a>&nbsp;</td>
							<td><?=$list_Records[$i]["company_phone_number"]?>&nbsp;</td>
							<td><a href="company_regist.php?action_type=modify&company_id=<?=$list_Records[$i]["company_id"]?>">[EDIT]</a></td>
						</tr>			
						<?
							}
							} else {
								echo "<tr><td colspan=3 height=40 align=center>Nothing to display.</td></tr>";
							}
						?>
						</table>
						<br>
						<table border="0" cellpadding="0" cellspacing="0" width="900">
						<tr><td align="right">
						<?php
						
						$cnt_total = getRowCount2("SELECT COUNT(*) FROM company ");
						
						if ($cnt_total <1 ) {
						?>
						<input type="button" value="New Company" onclick="location.href='company_regist.php'">
						<?php }
						?>
						</td></tr>
						</tbody>
						</table>
						<br>
						- Click the name  to view details.
					</td>
				</tr>
				<tr><td align="center"><? include_once "paging.php"?></td></tr>
				</table>
				<!-- CONTENTS END -->
			</td>
			<td>
			</td>
		  </tr>
		  <tr><td colspan="2" height="202"></td></tr>
		 </table>
		 </td>
	    </tr>
	    <!-- BODY CENTER END -->
	   </table>
	<!-- BODY END -->
	</td>
</tr>
<tr>
	<td colspan="3">
	<!-- BOTTOM -->
	<? include_once "bottom.php"; ?>
	<!-- BOTTOM END -->
	</td>
</tr>
</table>
</BODY>
</html>
<? ob_flush();?>
