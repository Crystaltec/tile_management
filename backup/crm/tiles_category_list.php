<?
ob_start();
include_once "include/logincheck.inc";
include_once "include/common.inc";
include_once "include/dbconn.inc";
include_once "include/myfunc.inc";
include_once "htmlinclude/head.php";

$act = $_REQUEST["act"];
if($act=="delete") {
	$tiles_category_id = $_REQUEST["tiles_category_id"];
	$sql = "SELECT COUNT(*) FROM tiles WHERE tiles_category_id='" .$tiles_category_id."'";	
	$row = getRowCount($sql);	
	if($row[0] > 0) {
		echo "<script>alert('Can not delete this Category because already has used Tiles infomation');history.back();</script>";
	} else {
		$sql = "DELETE FROM tiles_category WHERE tiles_category_id=".$tiles_category_id;
		//echo $sql;
		pQuery($sql,"delete");
	}
}


?>
<script type="text/javascript">
function goSort() {
	var f = document.cateForm;
	f.act.value="sort";
	f.submit();
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
				<table border="0" cellpadding="0" cellspacing="0" width="1000">
				<tr>
					<td valign="top">
					<table border="0" cellpadding="0" cellspacing="0" width="1000" class="font11_bold">
						<tr><td height="8"></td></tr>
						<tr><td width="252"><img src="images/icon_circle03.gif">&nbsp;<span style="height:21px">Tiles Categories</span></td></tr>
						<tr><td height="3"></td></tr>
						<tr><td background="images/bg_check.gif" height="3"></td></tr>
						<tr><td height="2"></td></tr>
					</table>
					</td>
				</tr>
				<form name="cateForm" method="POST" action="<?=$_SERVER["PHP_SELF"]?>">
				<input type="hidden" name="act" value="<?=$act?>">
				<tr>
					<td valign="top">
						<table border="1" width="1000" cellpadding="2" cellspacing="0" bordercolor="#c3c3c6" bordercolordark="white">
						<tr align="center" bgcolor="#D5D5D5" class="tr_bold" height="25">
							<td width="60">No</td>
							<td>Name</td>
							<td width="60">Delete</td>
						</tr>
						<?
							// 페이지 계산 /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
							$page = $_REQUEST["page"];
							if(!$page)
								$page = 1;
						
							$limitPage = 5;
							$limitList = 20;
							$total = getRowCount2("SELECT COUNT(*) FROM tiles_category WHERE 1=1 ". $s_Cond);
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
							$Query .= " FROM tiles_category ORDER BY tiles_category_name ASC LIMIT $start, $limitList";

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
						?>
						<tr align="center">
							<td><?=$total - (($limitList * ($page-1)) + $i)?><input type="hidden" value="<?=$list_Records[$i]["tiles_category_id"]?>" name="tiles_category_id[]"></td>
							<td><a href="tiles_category_regist.php?tiles_category_id=<?=$list_Records[$i]["tiles_category_id"]?>&action_type=modify"><b><?=$list_Records[$i]["tiles_category_name"]?></b></a></td>
							<td><a href="javascript:if(confirm('Are you sure?')) { location.href='<?=$_SERVER['PHP_SELF']?>?tiles_category_id=<?=$list_Records[$i]["tiles_category_id"]?>&act=delete';}"><img src="zb/images/x.gif"></a></td>
						</tr>
						<?
							}
							} else {
								echo "<tr><td colspan=4 height=40 align=center>Nothing to display.</td></tr>";
							}
						?>
						</table>
						<br>
						<table border="0" cellpadding="0" cellspacing="0" width="1000">
						<tr><td></td><td align="right"><input type="button" value="New Category" onclick="location.href='tiles_category_regist.php'"></td></tr>
						</table>
						<br>
						- Click the name of basic item to change details.
					</td>
				</tr>
				</form>

				<tr><td align="center"><? include_once "paging.php"?></td></tr>
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
	<? include_once "bottom.php"; ?>
	<!-- BOTTOM END -------------------------------------------------------------------------------------------->
	</td>
</tr>
</table>
</BODY>
</HTML>
<? ob_flush(); ?>
