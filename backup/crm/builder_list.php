<?php
ob_start();
include_once "include/logincheck.inc";
include_once "include/common.inc";
include_once "include/dbconn.inc";
include_once "include/myfunc.inc";
include_once "htmlinclude/head.php";

$act = $_REQUEST["act"];
if($act=="delete") {
	$builder_id = $_REQUEST["builder_id"];
	$sql = "SELECT COUNT(*) FROM project WHERE builder_id='" .$builder_id."'";	
	$row = getRowCount($sql);	
	if($row[0] > 0) {
		echo "<script>alert('Can not delete this builder because already has used Material infomation');history.back();</script>";
	} else {
		$sql = "DELETE FROM builder WHERE builder_id=".$builder_id;
		//echo $sql;
		pQuery($sql,"delete");
	}
} elseif($act == "sort") {
	$builder_id = $_REQUEST["builder_id"];
	
	for($i=0; $i < count($builder_id); $i++) {
		//echo $builder_id[$i]. ", ". $desc_no[$i] . "<br>";
		//$sql = "update builder set desc_no=".$desc_no[$i]." where builder_id=".$builder_id[$i];
		//pQuery($sql, "update");
	}
}



?>
<script type="text/javascript">
function goSort() {
	var f = document.builderForm;
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
						<tr><td width="252"><img src="images/icon_circle03.gif">&nbsp;<span style="height:21px">Builder List</span></td></tr>
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
									<option value='builder_name' <?php if($srch_opt == 'builder_name' ) echo " selected "; ?> >Name</option>
									<option value='builder_sales_manager' <?php if($srch_opt == 'builder_sales_manager' ) echo " selected "; ?>>Sales manager</option>
									<option value='builder_phone_number' <?php if($srch_opt == 'builder_phone_number' ) echo " selected "; ?>>Phone number</option>
									<option value='builder_comments' <?php if($srch_opt == 'builder_comments' ) echo " selected "; ?>>Remarks</option>
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
				<form name="builderForm" method="POST" action="<?=$_SERVER["PHP_SELF"]?>">
				<input type="hidden" name="act" value="<?=$act?>">
				<tr>
					<td valign="top">
						<table border="0" width="1000" cellpadding="0" cellspacing="1" class="list_table">
						<thead>
						<tr align="center" >
							<th width="40">No</th>
							<th>Name</th>
							<th>Sales Manager</th>
							<th width="140" >Phone</th>
							<th width="60"></th>
							<th width="60">Delete</th>
						</tr>
						</thead>
						<?php
							// 페이지 계산 ///////////////////////////////////////////////////////////
							$page = $_REQUEST["page"];
							if(!$page)
								$page = 1;
						
							$limitPage = 10;
							$limitList = 20;
							$total = getRowCount2("SELECT COUNT(*) FROM builder WHERE 1=1 ". $s_Cond);
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
							$Query .= " FROM builder WHERE 1=1 ". $s_Cond . " ORDER BY builder_name ASC LIMIT $start, $limitList";

							$id_cnn = mysql_query($Query) or exit(mysql_error());
							while($id_rst = mysql_fetch_assoc($id_cnn)) {
								$list_Records = array_merge($list_Records, array($id_rst));
								//print_r($list_Records);
								//echo "<p>";
							}
							//echo count($list_Records);
							if(is_array($list_Records)) {
							for($i=0; $i<count($list_Records); $i++) {
							if($i%2 == 0){
									$even_odd = ' class="even" ';
								} else
									$even_odd = ' class="odd" ';
						?>
						<tr align="center" <?php echo $even_odd;?> onmouseover="javascript:this.style.color='#536499';this.style.backgroundColor='#dcebfe';"  onmouseout="javascript:this.style.color='#000000';this.style.backgroundColor='';">
							<td><?=$total - (($limitList * ($page-1)) + $i)?><input type="hidden" value="<?=$list_Records[$i]["unit_id"]?>" name="unit_id[]"></td>
							<td class="left"><input type="hidden" value="<?=$list_Records[$i]["builder_id"]?>" name="builder_id[]"><a href="builder_regist.php?builder_id=<?=$list_Records[$i]["builder_id"]?>&action_type=modify"><b><?=$list_Records[$i]["builder_name"]?></b></a>&nbsp;</td>
							<td class="left"><?=($list_Records[$i]["builder_sales_manager"])?>&nbsp;</td>
							<td class="left"><?=($list_Records[$i]["builder_phone_number"])?>&nbsp;</td>
							<td><a href="builder_regist.php?builder_id=<?=$list_Records[$i]["builder_id"]?>&action_type=modify">[EDIT]</a>&nbsp;</td>
							<td><a href="javascript:if(confirm('Are you sure?')) { location.href='<?=$_SERVER['PHP_SELF']?>?builder_id=<?=$list_Records[$i]["builder_id"]?>&act=delete';}"><img src="zb/images/x.gif"></a></td>
						</tr>
						<?
							}
							} else {
								echo "<tr><td colspan=4 height=40 align=center>accounts are nothing</td></tr>";
							}
						?>
						</table>
						<br>
						<table border="0" cellpadding="0" cellspacing="0" width="1000">
						<tr><td></td><td align="right"><input type="button" value="New Builder" onclick="location.href='builder_regist.php'"></td></tr>
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
