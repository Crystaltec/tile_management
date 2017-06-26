<?php
ob_start();
include_once "include/logincheck.inc";
include_once "include/common.inc";
include_once "include/dbconn.inc";
include_once "include/myfunc.inc";
include_once "htmlinclude/head.php";

$act = $_REQUEST["act"];
if($act=="delete") {
	$supplier_id = $_REQUEST["supplier_id"];
	$sql = "SELECT COUNT(*) FROM material WHERE supplier_id='" .$supplier_id."'";	
	$row = getRowCount($sql);	
	if($row[0] > 0) {
		echo "<script>alert('Can not delete this supplier because already has used Material infomation');history.back();</script>";
	} else {
		$sql = "DELETE FROM supplier WHERE supplier_id=".$supplier_id;
		//echo $sql;
		pQuery($sql,"delete");
	}
} elseif($act == "sort") {
	$supplier_id = $_REQUEST["supplier_id"];
	
	for($i=0; $i < count($supplier_id); $i++) {
		//echo $supplier_id[$i]. ", ". $desc_no[$i] . "<br>";
		//$sql = "update supplier set desc_no=".$desc_no[$i]." where supplier_id=".$supplier_id[$i];
		//pQuery($sql, "update");
	}
}



?>
<script type="text/javascript">
function goSort() {
	var f = document.supplierForm;
	f.act.value="sort";
	f.submit();
}

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
						<tr><td width="252"><img src="images/icon_circle03.gif">&nbsp;<span style="height:21px">Supplier List</span></td></tr>
						<tr><td height="3"></td></tr>
						<tr><td background="images/bg_check.gif" height="3"></td></tr>
						<tr><td height="2"></td></tr>
					</table>
					</td>
				</tr>
				<tr><td valign="top">
				<?php
							//Search Value
							$s_Cond = "";
									

							$srch_opt = $_REQUEST["srch_opt"];
							$srch_opt_value = $_REQUEST["srch_opt_value"];
							
							if($srch_opt != "" && $srch_opt_value != "") {
								$s_Cond .= " AND $srch_opt like '%". $srch_opt_value ."%' ";
								$srch_param .= "&srch_opt=$srch_opt";
								$srch_param .= "&srch_opt_value=$srch_opt_value";
							}
							
							//$srch_param = urlencode($srch_param);
						?>
						<form name="searchform">
						<table border="0" cellpadding="0" cellspacing="0" height="40" valign="bottom" width="1000">							
							<tr class="ui-widget-header">
								<td height="30" style="padding-left:5px; width:200px;">
								<select name="srch_opt" id="srch_opt">
									<option value='supplier_name' <?php if($srch_opt == 'supplier_name' ) echo " selected "; ?> >Name</option>
									<option value='supplier_sales_manager' <?php if($srch_opt == 'supplier_sales_manager' ) echo " selected "; ?>>Sales manager</option>
									<option value='supplier_phone_number' <?php if($srch_opt == 'supplier_phone_number' ) echo " selected "; ?>>Phone number</option>
									<option value='supplier_comments' <?php if($srch_opt == 'supplier_comments' ) echo " selected "; ?>>Remarks</option>
								</select>
								</td>
								<td style="padding-left:5px">
								<input type="text" name='srch_opt_value' id='srch_opt_value' value='<?php echo "$srch_opt_value";?>'> 
								</td>
							</tr>
							<tr><td colspan="2" background="images/bg_check02.gif" height="3"></td></tr>
							<tr>
							<td colspan="2" align="right" height="30"><input type="button" Value="Search" onclick="searchNow()"></td></tr>
						</table>
						</form>
					</td>
				</tr>
				<br />
				<form name="supplierForm" method="POST" action="<?=$_SERVER["PHP_SELF"]?>">
				<input type="hidden" name="act" value="<?=$act?>">
				<tr>
					<td valign="top">
						<table border="0" width="1000" cellpadding="0" cellspacing="1" bordercolor="white" class="list_table">
						<thead>
						<tr align="center" >
							<th width="40">No</th>
							<th width="100">Cat</th>
							<th>Name</th>
							<th width="60">Account</th>
							<th width="110">Account Limitation</th>
							<th width="70">Payment Term</th>
							<th width="200">Sales Manager</th>
							<th width="200">Phone</th>
							<th width="50"></th>
							<th width="50">Delete</th>
						</tr>
						</thead>
						<tbody>
						<?php
							// 페이지 계산 /////////////////////////////
							$page = $_REQUEST["page"];
							if(!$page)
								$page = 1;
						
							$limitPage = 10;
							$limitList = 20;
							$total = getRowCount2("SELECT COUNT(*) FROM supplier WHERE 1=1 ". $s_Cond);
							//echo ceil(1.2);
							$totalPage = ceil($total/$limitList);
							$block = ceil($page/$limitPage);
							$start = ($page-1)*$limitList;

							$startPage = ($block-1)*$limitPage + 1;
							$endPage = $startPage + $limitPage - 1;
							if ($endPage > $totalPage ) $endPage = $totalPage; 
							// 페이지 계산 끝//////////////////////////////////////////////////


							## 쿼리, 담을 배열 선언
							$list_Records = array();
													
							$Query  = "SELECT * ";
							$Query .= " FROM supplier WHERE 1=1 ".$s_Cond  ." ORDER BY supplier_name ASC LIMIT $start, $limitList";

							$id_cnn = mysql_query($Query) or exit(mysql_error());
							while($id_rst = mysql_fetch_assoc($id_cnn)) {
								$list_Records = array_merge($list_Records, array($id_rst));
								//print_r($list_Records);
								//echo "<p>";
							}
							//echo count($list_Records);
							if(count($list_Records)) {
							for($i=0; $i<count($list_Records); $i++) {
							
								if($i%2 == 0){
									$even_odd = ' class="even" ';
								} else
									$even_odd = ' class="odd" ';
						?>
						<tr align="center"  <?php echo $even_odd;?> onmouseover="javascript:this.style.color='#536499';this.style.backgroundColor='#dcebfe';"  onmouseout="javascript:this.style.color='#000000';this.style.backgroundColor='';">
							<td height="22"><?=$total - (($limitList * ($page-1)) + $i)?></td>
							<td><?php echo $list_Records[$i]["supplier_category"];?></td>
							<td class="left"><input type="hidden" value="<?=$list_Records[$i]["supplier_id"]?>" name="supplier_id[]"><a href="supplier_regist.php?supplier_id=<?=$list_Records[$i]["supplier_id"]?>&action_type=modify"><b><?=$list_Records[$i]["supplier_name"]?></b></a>&nbsp;</td>
							<td class="center"><?php echo $list_Records[$i]['supplier_account'];?></td>
							<td class="right quantity02"><?php if($list_Records[$i]['supplier_account_limitation'])
							echo "$". number_format($list_Records[$i]["supplier_account_limitation"],2,".",',');?></td>
							<td class="center"><?php echo getName('payment_term',$list_Records[$i]["payment_term_id"]);?></td>
							<td class="left"><?=($list_Records[$i]["supplier_sales_manager"])?>&nbsp;</td>
							<td class="left"><?=($list_Records[$i]["supplier_phone_number"])?>&nbsp;</td>
							<td><a href="supplier_regist.php?supplier_id=<?=$list_Records[$i]["supplier_id"]?>&action_type=modify">[EDIT]</a></td>
							<td><a href="javascript:if(confirm('Are you sure?')) { location.href='<?=$_SERVER['PHP_SELF']?>?supplier_id=<?=$list_Records[$i]["supplier_id"]?>&act=delete';}"><img src="zb/images/x.gif"></a></td>
							
						</tr>
						<?
							}
							} else {
								echo "<tr><td colspan='9' height='40' class='center'>Nothing to display</td></tr>";
							}
						?>
						</tbody>
						</table>
						<br>
						<table border="0" cellpadding="0" cellspacing="0" width="1000">
						<tr><td></td><td align="right"><input type="button" value="New entry" onclick="location.href='supplier_regist.php'"></td></tr>
						</table>
						<br>
						- Click the name of item to change details.
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
	<?php include_once "bottom.php"; ?>
	<!-- BOTTOM END -------------------------------------------------------------------------------------------->
	</td>
</tr>
</table>
</BODY>
</HTML>
<? ob_flush(); ?>
