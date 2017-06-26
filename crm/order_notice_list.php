<?php
ob_start();
include_once "include/logincheck.inc";
include_once "include/common.inc";
include_once "include/dbconn.inc";
include_once "include/myfunc.inc";
include_once "htmlinclude/head.php";

$act = $_REQUEST["act"];
if($act=="delete") {
	$order_notice_id = $_REQUEST["order_notice_id"];
	$sql = "SELECT COUNT(*) FROM orders WHERE order_notice_id='" .$order_notice_id."'";	
	$row = getRowCount($sql);	
	if($row[0] > 0) {
		echo "<script>alert('Can not delete this order_notice because already has used Material infomation');history.back();</script>";
	} else {
		$sql = "DELETE FROM order_notice WHERE order_notice_id=".$order_notice_id;
		//echo $sql;
		pQuery($sql,"delete");
	}
} elseif($act == "sort") {
		$order_notice_id = $_REQUEST["order_notice_id"];
	$sortno = $_REQUEST["sortno"];
	
	for($i=0; $i < count($order_notice_id); $i++) {
		$sql = "update order_notice set sortno='".$sortno[$i]."' where order_notice_id='".$order_notice_id[$i]."'";
		pQuery($sql, "update");
	}
}


?>
<script type="text/javascript">
function goSort() {
	var f = document.ordernoticeForm;
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
	<?// include_once "left.php"; ?>
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
						<tr><td width="600"><img src="images/icon_circle03.gif">&nbsp;<span style="height:21px">Delivery Requirements/Instructions List</span></td></tr>
						<tr><td height="3"></td></tr>
						<tr><td background="images/bg_check.gif" height="3"></td></tr>
						<tr><td height="2"></td></tr>
					</table>
					</td>
				</tr>
				<form name="ordernoticeForm" method="POST" action="<?=$_SERVER["PHP_SELF"]?>">
				<input type="hidden" name="act" value="<?=$act?>">
				<tr>
					<td valign="top">
						<table border="0" width="1000" cellpadding="0" cellspacing="1" class="list_table">
						<thead>
						<tr align="center" height="30">
							<th width="60">No</th>
							<th>Description</th>
							<th width="60">Delete</th>
						</tr>
						</thead>
						<tbody>
						<?

							// 페이지 계산 /////////////////////////////////////////////////////////
							$page = $_REQUEST["page"];
							if(!$page)
								$page = 1;
						
							$limitPage = 5;
							$limitList = 20;
							$total = getRowCount2("SELECT COUNT(*) FROM order_notice WHERE 1=1 ". $s_Cond);
							//echo ceil(1.2);
							$totalPage = ceil($total/$limitList);
							$block = ceil($page/$limitPage);
							$start = ($page-1)*$limitList;

							$startPage = ($block-1)*$limitPage + 1;
							$endPage = $startPage + $limitPage - 1;
							if ($endPage > $totalPage ) $endPage = $totalPage; 
							// 페이지 계산 끝////////////


							## 쿼리, 담을 배열 선언
							$list_Records = array();
							$Rs_id  = array();
							$Rs_id["order_notice_id"]	= NULL;
							$Rs_id["order_notice_name"]	= NULL;
							$Rs_id["sortno"]	= NULL;
													
							$Query  = "SELECT " . selectQuery($Rs_id, "order_notice");
							$Query .= " FROM order_notice ORDER BY sortno ASC, order_notice_name ASC LIMIT $start, $limitList";

							$id_cnn = mysql_query($Query) or exit(mysql_error());
							while($id_rst = mysql_fetch_assoc($id_cnn)) {
								$list_Records = array_merge($list_Records, array($id_rst));
								//print_r($list_Records);
								//echo "<p>";
							}
							//echo count($list_Records);
							$cnt = count($list_Records);
							if(count($list_Records)) {
							for($i=0; $i<count($list_Records); $i++) {
							
								if($i%2 == 0){
											$even_odd = ' class="even" ';
										} else
											$even_odd = ' class="odd" ';
						?>
						<tr align="center"  <?php echo $even_odd;?>  onmouseover="javascript:this.style.color='#536499';this.style.backgroundColor='#dcebfe';"  onmouseout="javascript:this.style.color='#000000';this.style.backgroundColor='';">
							<td><input type="text" size="3" value="<?=$list_Records[$i]["sortno"]?>" name="sortno[]" style="text-align:right;"><input type="hidden" value="<?=$list_Records[$i]["order_notice_id"]?>" name="order_notice_id[]"></td>
							<td align="left"><a href="order_notice_regist.php?order_notice_id=<?=$list_Records[$i]["order_notice_id"]?>&action_type=modify"><b><?=$list_Records[$i]["order_notice_name"]?></b></a></td>
							<td><a href="javascript:if(confirm('Are you sure?')) { location.href='<?=$_SERVER['PHP_SELF']?>?order_notice_id=<?=$list_Records[$i]["order_notice_id"]?>&act=delete';}"><img src="zb/images/x.gif"></a></td>
						</tr>
						<?
							}
							} else {
								echo "<tr><td colspan=4 height=40 align=center>Nothing to display</td></tr>";
							}
						?>
						</tbody>
						</table>
						<br>
						<table border="0" cellpadding="0" cellspacing="0" width="1000">
						<tr><td><input type="button" value="Change Sort" onclick="goSort();"></td><td align="right"><input type="button" value="New Order notice" onclick="location.href='order_notice_regist.php'"></td></tr>
						</table>
						<br>
						- Click the name to change details.
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
		  <tr><td colspan="2" height="331"></td></tr>
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