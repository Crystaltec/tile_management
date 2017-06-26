<?php
// 2012-02-07 removed financial status
ob_start();
include_once "include/logincheck.inc";
include_once "include/common.inc";
include_once "include/dbconn.inc";
include_once "include/myfunc.inc";
include_once "htmlinclude/head.php";

$act = $_REQUEST["act"];
$limitList = $_REQUEST["limitList"];
$retention_no = $_REQUEST["retention_no"];
$retention_act="none";
$total = 1;


$sql_total = "SELECT * FROM retention_total WHERE retention_total_no=".$total;
										
						$find_total = mysql_query($sql_total) or exit(mysql_error());
						
						
						if($find_total)
						{
						
							
	
						
						$result_total = mysql_fetch_assoc($find_total);
						$total_retention =  $result_total['retention_total'];
						
						
						}

?>






<script type="text/javascript">

function myRetention() {
	
        var amount = prompt("Please enter Total Retention Amount", '<?=number_format($total_retention,2,'.',',')?>');
	location.href='<?=$_SERVER['PHP_SELF']?>?&action_type=total&total_retention='+amount ;
}

function goSort() {
	var f = document.projectForm;
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


<?php
$action_type = $_REQUEST["action_type"];

if ($action_type=="total")
{
$total_retention = $_REQUEST["total_retention"];

$query = "update retention_total set retention_total='$total_retention'  where retention_total_no='$total'";

mysql_query($query);


echo "<script language='javascript'>
		alert('Updated');
		</script>";
		
echo "<script>location.href='retention_list.php';</script>";

}


?>



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
				<table border="0" cellpadding="0" cellspacing="0" width="1200">
				<tr>
					<td valign="top">
					<table border="0" cellpadding="0" cellspacing="0" width="1200" class="font11_bold">
						<tr><td height="8"></td></tr>
						<tr><td width="252"><img src="images/icon_circle03.gif">&nbsp;<span style="height:21px">Retention List</span></td></tr>
						<tr><td height="3"></td></tr>
						<tr><td background="images/bg_check.gif" height="3"></td></tr>
						<tr><td height="2"></td></tr>
					</table>
					</td>
				</tr>
			
				<tr>
					<td valign="top">
						<?php
							//Search Value
							$s_Cond = "";
							
							
							$builder_id = $_REQUEST["builder_id"];
							$srch_opt = $_REQUEST["srch_opt"];
							$srch_opt_value = $_REQUEST["srch_opt_value"];
							$retention_total = $_REQUEST["retention_total"];
							

						
							
							if($builder_id != "") {
								$s_Cond .= " AND builder_id ='". $builder_id ."'";
								$srch_param .= "&builder_id=$builder_id";
							}
							
							if($limitList) {
								$srch_param .="&limitList=$limitList";
								//$srch_param = urlencode($srch_param);
							}
														
							
						?>
						<form name="searchform">
						<table border="0" cellpadding="0" cellspacing="0" height="40" valign="bottom" width="1200">							
						
								
							
							<tr><td colspan="2" background="images/bg_check02.gif" height="3"></td></tr>
							
							
							
							<tr class="ui-widget-header">
								<td height="30" style="padding-left:5px; width:200px;">	Builder Name
								
								</td>
								<td style="padding-left:5px">
								<? getOption("builder", $builder_id); ?>
								</td>
							</tr>
							
							
							
							
							<tr><td colspan="2" background="images/bg_check02.gif" height="3"></td></tr>
							
							
								
							<tr>
							<td colspan="2" align="right" height="30">
							<span style="float:left;">Items per page:<select name="limitList" id="limitList" onchange="searchNow();">
						<option value="300" <?php if($limitList == 300) echo "selected";?> >300</option>
						<option value="600" <?php if($limitList == 600) echo "selected";?> >600</option>
						<option value="900" <?php if($limitList == 900) echo "selected";?> >900</option>
						<option value="999999" <?php if($limitList == 999999) echo "selected";?> >All</option>
						</select>
						
						<input type="hidden" name="list_view" id="list_view" value="<?php echo $list_view;?>" />
						</span>	
						<input type="button" value="New Entry" onclick="location.href='retention_regist.php?lmenu=12'"/>
								<input type="button" Value="Search" onclick="searchNow()"></td></tr>
						</table>
						
						
						
						</form>
						<br>
						
						<form name="form_retention">
						<table border="0" cellpadding="0" cellspacing="0" height="40" valign="bottom" width="1200">
						
						
						<tr><td ><strong>Total Retention Amount: </strong><font color="red"><?=number_format($total_retention,2,'.',',')?>&nbsp;&nbsp;
						<input type="button" value="Change" onclick="myRetention()"></input></td>
						</tr>

					
						
						</table>
						</form>
						
						<tr></tr>
						<form name="projectForm" method="POST" action="<?=$_SERVER["PHP_SELF"]?>">
						<input type="hidden" name="act" value="<?=$act?>">
						
						<table border="0" width="1200" cellpadding="0" cellspacing="1" class="list_table">
						<thead>
						<tr align="center" >
							<th width="370"></th>
							
							
							
							<th width="400" class="">1st Half Retention</th>
							
							<th width="380" class="">2nd Half Retention</th>
						
						</tr>
						</thead>
						</table>
						
						
						
						<table border="0" width="1200" cellpadding="0" cellspacing="1" class="list_table">
						<thead>
						
						
						<tr align="center" >
							<th width="30">No.</th>
							<th width="200">Project</th>
							<th width="200" class="">Builder</th>
							
							<th width="30" class="">&nbsp;</th>
							
							<th width="100" class="">Claimed Date</th>
							<th width="90">Claimed Amount  <br> (Inc. GST)</th>
							<th width="100">Received Date</th>
							<th width="90">Received Amount  <br> (Inc. GST)</th>
							
							
							<th width="30" class="">&nbsp;</th>
							<th width="100" class="">Claimed Date</th>
							<th width="90">Claimed Amount <br> (Inc. GST)</th>
							<th width="100">Received Date</th>
							<th width="90">Received Amount  <br> (Inc. GST)</th>
							
							<th width ="100"> Note </th>
							<th width ="50"> Note </th>
							
						</tr>
						</thead>
						<tbody>
						<?php
							// 페이지 계산 ///////////////////////////////
							$page = $_REQUEST["page"];
							if(!$page)
								$page = 1;
						
							$limitPage = 10;
							
							if (!$_REQUEST["limitList"]) {
								$limitList = 300;
							} else
								$limitList = $_REQUEST["limitList"];
							
							$total = getRowCount2("SELECT COUNT(*) FROM retention_list WHERE 1=1 ". $s_Cond);
							//echo ceil(1.2);
							$totalPage = ceil($total/$limitList);
							$block = ceil($page/$limitPage);
							$start = ($page-1)*$limitList;

							$startPage = ($block-1)*$limitPage + 1;
							$endPage = $startPage + $limitPage - 1;
							if ($endPage > $totalPage ) $endPage = $totalPage; 
							// 페이지 계산 끝/////////////////////////////////////////////////

							## 쿼리, 담을 배열 선언
							$list_Records = array();
							
							
							$Query  = "SELECT * ";
							$Query .= " FROM retention_list WHERE 1=1 " . $s_Cond . "ORDER BY claimed_date_1 DESC LIMIT $start, $limitList";

							$id_cnn = mysql_query($Query) or exit(mysql_error());
							while($id_rst = mysql_fetch_assoc($id_cnn)) {
								$list_Records = array_merge($list_Records, array($id_rst));
								//print_r($list_Records);
								//echo "<p>";
							}
							//echo count($list_Records);
							if(count($list_Records)) {
							for($i=0; $i<count($list_Records); $i++) {

								/*
								$f_status = 0;

								$Query = "SELECT sum(orders_inventory * IF(material_id <> '0' and material_id <> '',material_price,IF(orders_tax = 'N', material_price,material_price/1.1)) ) FROM orders WHERE (orders_number = ' ' or (orders_number  <> '' and material_id = 0) ) and retention_no = '".$list_Records[$i]["retention_no"]."'";
								$result =  mysql_query($Query) or exit(mysql_error());
								
								while($rows = mysql_fetch_row($result)) {
									if ($rows[0] != NULL) 					
										$f_status = $rows[0];
								}
								*/
								$bgcolor = "";
								
								if($i%2 == 0){
									$even_odd = ' class="even" ';
								} else
									$even_odd = ' class="odd" ';
						?>
						<tr align="center" <?php echo $even_odd;?> onmouseover="javascript:this.style.color='#536499';this.style.backgroundColor='#dcebfe';"  onmouseout="javascript:this.style.color='#000000';this.style.backgroundColor='';">
							
					<td class="center"><?=($i+1)?>&nbsp;</td>
			<td class="center"><?php echo getName("project",$list_Records[$i]["project_id"]); ?></td>
			<td class="center"><input type="hidden" value="<?=$list_Records[$i]["retention_no"]?>" name="retention_no[]"><b><?php echo getName("builder",$list_Records[$i]["builder_id"]); ?></td>
					<td class="center">&nbsp;</td>
							
		<td class="center"><?=getAUDate($list_Records[$i]["claimed_date_1"])?></td>
		<td class="right"<?=$bgcolor?>><font color="red">$<?=number_format($list_Records[$i]["claimed_amount_1"],2,'.',',')?>&nbsp;</font></td>
		<td class="center"><?=getAUDate($list_Records[$i]["received_date_1"])?></td>
		<td class="right"<?=$bgcolor?>><font color="red">$<?=number_format($list_Records[$i]["received_amount_1"],2,'.',',')?>&nbsp;</td>
		
		<td class="center">&nbsp;</td>
							
							<td class="center"><?=getAUDate($list_Records[$i]["claimed_date_2"])?></td>
		<td class="right"<?=$bgcolor?>><font color="red">$<?=number_format($list_Records[$i]["claimed_amount_2"],2,'.',',')?>&nbsp;</font></td>
		<td class="center"><?=getAUDate($list_Records[$i]["received_date_2"])?></td>
		<td class="right"<?=$bgcolor?>><font color="red">$<?=number_format($list_Records[$i]["received_amount_2"],2,'.',',')?>&nbsp;</font></td>
				<td class="center"><?=($list_Records[$i]["note"])?></td>	
							
							
							
							
							<td ><a href="retention_regist.php?retention_no= <?=$list_Records[$i]["retention_no"] ?>&action_type=modify">[EDIT]</a></td>
						
						</tr>
						<?php
							}
							} else {
								echo "<tr><td colspan=6 height=40 align=center>Nothing to display</td></tr>";
							}
						?>
						</tbody>
						</table>
						<br>
						<table border="0" cellpadding="0" cellspacing="0" width="1000">
						<tr><td></td><td align="right"><input type="button" value="New Entry" onclick="location.href='retention_regist.php?lmenu=12'"></td></tr>
						</table>
						</form>
						<br>
						
					</td>
				</tr>
				<tr><td align="center"><?php include_once "paging.php"?></td></tr>
				</table>
				<!-- CONTENTS END -------------------------------------------------------------------------------------------->
			</td>
			<td>
			</td>
		  </tr>
		  <tr><td colspan="2" height="20"></td></tr>
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
<?php ob_flush(); ?>