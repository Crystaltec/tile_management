<?php
ob_start();
include_once "include/logincheck.inc";
include_once "include/common.inc";
include_once "include/dbconn.inc";
include_once "include/myfunc.inc";
include_once "htmlinclude/head.php";

$act = $_REQUEST["act"];
if($act=="delete") {
	$category_id = $_REQUEST["category_id"];
	$sql = "SELECT COUNT(*) FROM material WHERE category_id='" .$category_id."'";	
	$row = getRowCount($sql);	
	if($row[0] > 0) {
		echo "<script>alert('Can not delete this Category because already has used Material infomation');history.back();</script>";
	} else {
		$sql = "DELETE FROM category WHERE category_id=".$category_id;
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

$(function() {
	$("input:button, button").button();
	$(".list_table thead").addClass('ui-widget-header');
	$(".list_table tbody").addClass('ui-widget-content');
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
<br>
	<td valign="top" width="100" height="100%">
	<!-- LEFT -------------------------------------------------------------------------------------------------->
	<? //include_once "left.php"; ?>
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
					<td valign="top">
					<table border="0" cellpadding="0" cellspacing="0" width="1000" class="font11_bold">
						<tr><td height="8"></td></tr>
						<tr><td width="252"><img src="images/icon_circle03.gif">&nbsp;<span style="height:21px">Category List</span></td></tr>
						<tr><td height="3"></td></tr>
						<tr><td background="images/bg_check.gif" height="3"></td></tr>
						<tr><td height="2"></td></tr>
						
						
						
					</td>
				</tr>
				<table border="0" cellpadding="0" cellspacing="0" height="40" valign="bottom" width="1000">							
							<tr class="ui-widget-header">
							<tr>
							<td colspan="2" align="right" height="30">
			<input type="button" value="New Category" onclick="location.href='category_regist.php'"></span></td></tr>
						</tr>
						</td>
						</table>
						
				
				<form name="cateForm" method="POST" action="<?=$_SERVER["PHP_SELF"]?>">
				<input type="hidden" name="act" value="<?=$act?>">
				<tr>
						
					<td valign="top">
						<table border="0" width="1000" cellpadding="0" cellspacing="1"  class="list_table">
						<thead>
						<tr align="center">
							<th width="60">No</th>
							<th>Name</th>
							<th width="60"></th>
							<th width="60">Delete</th>
						</tr>
						</thead>
						<tbody>
						<?php
							// 페이지 계산 //////////////////////////////////////////////////////////////////////
							$page = $_REQUEST["page"];
							if(!$page)
								$page = 1;
						
							$limitPage = 10;
							$limitList = 20;
							$total = getRowCount2("SELECT COUNT(*) FROM category WHERE 1=1 ". $s_Cond);
							//echo ceil(1.2);
							$totalPage = ceil($total/$limitList);
							$block = ceil($page/$limitPage);
							$start = ($page-1)*$limitList;

							$startPage = ($block-1)*$limitPage + 1;
							$endPage = $startPage + $limitPage - 1;
							if ($endPage > $totalPage ) $endPage = $totalPage; 
							// 페이지 계산 끝/////////////////////////////////////////////////////////////////


							## 쿼리, 담을 배열 선언
							$list_Records = array();
													
							$Query  = "SELECT * ";
							$Query .= " FROM category ORDER BY category_name ASC LIMIT $start, $limitList";

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
							
							if($i%2 == 0){
									$even_odd = ' class="even" ';
								} else
									$even_odd = ' class="odd" ';
						?>
						<tr align="center" <?php echo $even_odd;?> onmouseover="javascript:this.style.color='#536499';this.style.backgroundColor='#dcebfe';"  onmouseout="javascript:this.style.color='#000000';this.style.backgroundColor='';">
							<td><?=$total - (($limitList * ($page-1)) + $i)?><input type="hidden" value="<?=$list_Records[$i]["category_id"]?>" name="category_id[]"></td>
							<td><a href="category_regist.php?category_id=<?=$list_Records[$i]["category_id"]?>&action_type=modify"><b><?=$list_Records[$i]["category_name"]?></b></a></td>
							<td><a href="category_regist.php?category_id=<?=$list_Records[$i]["category_id"]?>&action_type=modify">[EDIT]</a></td>
							<td><a href="javascript:if(confirm('Are you sure?')) { location.href='<?=$_SERVER['PHP_SELF']?>?category_id=<?=$list_Records[$i]["category_id"]?>&act=delete';}"><img src="zb/images/x.gif"></a></td>
						</tr>
						<?
							}
							} else {
								echo "<tr><td colspan=4 height=40 align=center>Nothing to display.</td></tr>";
							}
						?>
						</tbody>
						</table>
						<br>
						<table border="0" cellpadding="0" cellspacing="0" width="1000">
						<tr><td></td><td align="right"><input type="button" value="New Category" onclick="location.href='category_regist.php'"></td></tr>
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
		  <tr><td colspan="2" height="10"></td></tr>
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