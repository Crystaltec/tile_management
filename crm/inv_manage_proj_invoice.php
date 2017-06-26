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
$inv_no = $_REQUEST["inv_no"];

if($action_type=="delete") {
		$sql = "DELETE FROM invoice_manage WHERE inv_no=".$inv_no;
		//echo $sql;
		pQuery($sql,"delete");
		echo "<script>location.href='inv_manage_list.php';</script>";
	}

?>


<script type="text/javascript">

function issueOrdersheet(inv_no) {
	window.open("issue_ordersheet_invoice.php?inv_no="+inv_no, "ordersheet", "");
}

function goSort() {
	var f = document.projectForm;
	f.act.value="sort";
	f.submit();
}

function searchNow() {
	var f = document.searchform;
	var sday = f.sday.value;
	var smonth = f.smonth.value;
	var syear = f.syear.value;

	var eday = f.eday.value;
	var emonth = f.emonth.value;
	var eyear = f.eyear.value;

	var start_date = syear + "-" + smonth + "-" + sday;
	var end_date = eyear + "-" + emonth + "-" + eday;

	if(start_date > end_date) {
		alert("Can't search!");
		return;
	}

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
						<tr><td width="252"><img src="images/icon_circle03.gif">&nbsp;<span style="height:21px">Project Invoice</span></td></tr>
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
							
							$srch_param= $_POST["srch_param"];
							$project_id = $_REQUEST["project_id"];
							$ave_inv_amount = $_REQUEST["ave_inv_amount"];
							$ave_rec_amount = $_REQUEST["ave_rec_amount"];
							$ave_ret_amount = $_REQUEST["ave_ret_amount"];
							$contract_fc = $_REQUEST["contract_fc"];
							$contract_sc = $_REQUEST["contract_sc"];
							
							$ave_inv_amount_exc = $_REQUEST["ave_inv_amount_exc"];
							$ave_rec_amount_exc = $_REQUEST["ave_rec_amount_exc"];
							$ave_ret_amount_exc = $_REQUEST["ave_ret_amount_exc"];
							
							
							$sday = $_REQUEST["sday"];
							$smonth = $_REQUEST["smonth"];
							$syear = $_REQUEST["syear"];
							
							$eday = $_REQUEST["eday"];
							$emonth = $_REQUEST["emonth"];
							$eyear = $_REQUEST["eyear"];
							
							if($sday == "") {
								
								$sdate = date("d-m-Y" ,time() - (3600 * 24 * 365));
								
								$sday = substr($sdate, 0,2);
								$smonth = substr($sdate, 3,2);
								$syear = substr($sdate, 6,4);
								//echo $sday . "<br>";
								//echo $smonth . "<br>";
								//echo $syear . "<br>";
								
								$eday = substr($now_date, 0,2);
								$emonth = substr($now_date, 3,2);
								$eyear = substr($now_date, 6,4);
								$ehour = substr($now_time, 0,2);
								$emin = substr($now_time, 3,2);
							}	
								
								
							
							$start_date = $syear."-".$smonth."-".$sday;							
							$end_date = $eyear."-".$emonth."-".$eday;

							//$start_date = $sday."/".$smonth."/".$syear;
							//$end_date = $eday."/".$emonth."/".$eyear;
							
							$end_date = $end_date . " 23:59:59";
							
							$s_Cond .= " AND ( inv_date >= '$start_date' AND inv_date <= '$end_date') ";	
							 
							$srch_param = "srch_pbase=$srch_pbase&sday=$sday&smonth=$smonth&syear=$syear&eday=$eday&emonth=$emonth&eyear=$eyear";


							
							
							
							
							if($project_id != "") {
								$s_Cond .= " AND project_id ='". $project_id ."'";
								$srch_param .= "&project_id=$project_id";
								
								
								$sql = "Select * FROM project WHERE project_id=".$project_id;
								
								$result = mysql_query($sql) or exit(mysql_error());
								
								if($result){
								while ($row = mysql_fetch_assoc($result)) 
								{
								$contract_sc = $row['contract_sc'];
								$contract_fc = $row['contract_fc'];
								
							
								}
								
							}
							}
							
							if($limitList) {
								$srch_param .="&limitList=$limitList";
								//$srch_param = urlencode($srch_param);
							}
							
							
							if($project_id !="") {
							
		
		
		//echo "<script>location.href='inv_manage_list.php';</script>";
	}
							
						
														
							
						?>
						<form name="searchform">
						<table border="0" cellpadding="0" cellspacing="0" height="40" valign="bottom" width="1200">							
							<tr class="ui-widget-header">
								<td width="200" height="30"  style="padding-left:5px">	Invoice Period
								</td>
								<td style="padding-left:5px">
						
								<select name="sday">
								<? for($i=1; $i < 32; $i++) { 
									if($i < 10) {
										$k = "0".$i;
									} else {$k=$i;}
									?>
								<option value="<?=$k?>" <?if($sday==$i) echo "selected";?>><?=$k?></option>
								<? } ?>

								</select>&nbsp;
								<select name="smonth">
								<? for($i=1; $i < 13; $i++) { 
									if($i < 10) {
										$k = "0".$i;
									} else {$k=$i;}
									?>
								<option value="<?=$k?>" <?if($smonth==$i) echo "selected";?>><?=$k?></option>
								<? } ?>
								</select>&nbsp;
								<? yearOption("syear",$syear); ?>
								~
								<select name="eday">
								<? for($i=1; $i < 32; $i++) { 
									if($i < 10) {
										$k = "0".$i;
									} else {$k=$i;}
									?>
								<option value="<?=$k?>" <?if($eday==$i) echo "selected";?>><?=$k?></option>
								<? } ?>

								</select>&nbsp;
								<select name="emonth">
								<?php
								 for($i=1; $i < 13; $i++) { 
									if($i < 10) {
										$k = "0".$i;
									} else {$k=$i;}
									?>
								<option value="<?=$k?>" <?if($emonth==$i) echo "selected";?>><?=$k?></option>
								<? } ?>
								</select>&nbsp;
								<? yearOption("eyear",$eyear); ?>
								</td>
							</tr>
							<tr><td colspan="2" background="images/bg_check02.gif" height="3"></td></tr>
							
							
							
							<tr class="ui-widget-header">
								<td height="30" style="padding-left:5px; width:200px;">	Project Name
								
								</td>
								<td style="padding-left:5px">
								<? getOption("project", $project_id); ?>
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
						
						<input type="button" onClick="window.open('issue_invoice_pdf.php?<?=$srch_param?>')" value="Print as PDF">
								<input type="button" Value="Search" onclick="searchNow()"></td></tr>
								
								
								</table>
								
						<table border="0" cellpadding="0" cellspacing="0" height="35" valign="bottom" width="1200">
						<tr class="ui-widget-header">	
	<th width="40" align="Left">Contract Fixing Cost: <span class='price'><?php  if($project_id != "")  echo "$". number_format($contract_fc,2,'.',',') ?>  </span>
	<th width="50" align="Left">Contract Supply Cost: <span class='price'><?php if($project_id != "") echo "$".  number_format($contract_sc,2,'.',',') ?>  </span>
	<th width="50" align="Left">Total Contract Amount (Ex. GST): <span class='price'><?php if($project_id != "") echo "$". number_format($contract_fc+$contract_sc,2,'.',',') ?>  </span></th>
								
							</tr>
						</table>
						</form>
						
						
						
						<br>
						<form name="projectForm" method="POST" action="<?=$_SERVER["PHP_SELF"]?>">
						<input type="hidden" name="act" value="<?=$act?>">
						<table border="0" width="1200" cellpadding="0" cellspacing="1" class="list_table">
						<thead>
						<tr align="center" >
							<th width="30">No.</th>
							<th width="100" class="">Invoice Date</th>
							<th width="100" class="">Invoice No.</th>
							<th width="200">Project</th>
							<th width="100">Invoice Amount <br> (inc. GST)</th>
							<th width="90">Payment Due</th>
							
							<th width="85">Received Date</th>
							<th width="85">Received Amount <br>  (inc. GST)</th>
							<th width="85">Retention Amount  <br> (inc. GST)</th>
							
							<th width="200">Note</th>
							<th width="50">Invoice<br>Sheet</th>
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
							
							$total = getRowCount2("SELECT COUNT(*) FROM invoice_manage WHERE 1=1 ". $s_Cond);
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
							$Query .= " FROM invoice_manage WHERE 1=1 " . $s_Cond . "ORDER BY inv_no DESC LIMIT $start, $limitList";

							$id_cnn = mysql_query($Query) or exit(mysql_error());
							while($id_rst = mysql_fetch_assoc($id_cnn)) {
								$list_Records = array_merge($list_Records, array($id_rst));
								//print_r($list_Records);
								//echo "<p>";
							}
							//echo count($list_Records);
							if(count($list_Records)) {
							for($i=0; $i<count($list_Records); $i++) {

							
								$ave_inv_amount  += $list_Records[$i]['inv_amount'];
								$ave_rec_amount  += $list_Records[$i]['received_amount'];
								$ave_ret_amount  = $list_Records[$i]['retention_amount'];
								$count += $count;
								
								
								$bgcolor = "";
								
								if($i%2 == 0){
									$even_odd = ' class="even" ';
								} else
									$even_odd = ' class="odd" ';
						?>
						<tr align="center" <?php echo $even_odd;?> onmouseover="javascript:this.style.color='#536499';this.style.backgroundColor='#dcebfe';"  onmouseout="javascript:this.style.color='#000000';this.style.backgroundColor='';">
							
							<td class="center"><?=($i+1)?>&nbsp;</td>
							<td class="center"><?=getAUDate($list_Records[$i]["inv_date"])?></td>
							<td class="center"><?=($list_Records[$i]["invoice_number"])?>&nbsp;</td>
							
							
							<td class="center"><input type="hidden" value="<?=$list_Records[$i]["inv_no"]?>" name="inv_no[]"><b>
							<?php echo getName("project",$list_Records[$i]["project_id"]); ?></td>
							<td class="right" <?=$bgcolor?>>$<?=number_format($list_Records[$i]["inv_amount"],2,'.',',')?>&nbsp;</td>
							<td class="center"><?=getAUDate($list_Records[$i]["payment_due"])?></td>
							
							<td class="center"><?=getAUDate($list_Records[$i]["received_date"])?></td>
							
							<td class="right">$<?=number_format($list_Records[$i]["received_amount"],2,'.',',')?>&nbsp;</td>
							<td class="right">$<?=number_format($list_Records[$i]["retention_amount"],2,'.',',')?>&nbsp;</td>
							
							<td class="center"><?=($list_Records[$i]["note"])?>&nbsp;</td>
							<td><a href="javascript:issueOrdersheet('<?=$list_Records[$i]["inv_no"]?>')"><img src="images/printButton.png"></a></td>
							
							
					
						</tr>
						<?php
							}
							} else {
								echo "<tr><td colspan=6 height=40 align=center>===Nothing to display===</td></tr>";
								
							}
							
						?>
						
						
						
						
						
						
						</tbody>
						</table>
						
						
						
						<table border="0" width="1200" cellpadding="0" cellspacing="1" class="list_table">
						<thead>
						<tr >
							
						
						
						
						<th width="437" align="left">Total (inc. GST)</th>
							
							<th width="100" align="right"> <span class='price'> $<?php echo  number_format($ave_inv_amount,2,'.',',') ?>  </span></th>
							<th width="175"></th>
							
							
							<th width="85" align="right"><span class='price'> $<?php echo  number_format($ave_rec_amount,2,'.',',') ?>  </span></th>
							<th width="85" align="right"><span class='price'> $<?php echo  number_format($ave_ret_amount,2,'.',',') ?>  </span></th>
							
							<th width="250"></th>
						
						
						</tr>
						</thead>
						
						</table>
						
						
						
						<table border="0" width="1200" cellpadding="0" cellspacing="1" class="list_table">
						<thead>
						<tr align="center" >
							
						
						
						
						<th width="437"  align="left">Total (Exc. GST)</th>
							<th width="100" align="right"> <span class='price'> $<?php echo  number_format($ave_inv_amount/1.1,2,'.',',') ?>  </span></th>
							<th width="175"></th>
							
							
							<th width="85" align="right"><span class='price'> $<?php echo  number_format($ave_rec_amount/1.1,2,'.',',') ?>  </span></th>
							<th width="85" align="right"><span class='price'> $<?php echo  number_format($ave_ret_amount/1.1,2,'.',',') ?>  </span></th>
							
							<th width="250"></th>
						
						
						
						</tr>
						</thead>
						
						</table>
						
						
						<table border="0" width="1200" cellpadding="0" cellspacing="1" class="list_table">
						<thead>
						<tr align="center" >
							
						
						
						
						<th width="437"  align="left">Less Amount (Exc. GST)</th>
							<th width="100" align="right"> <span class='price'> &nbsp;  </span></th>
							<th width="175"></th>
							
							<?php
							// Date Modified: Friday, July 22, 2016
							// Author: Ronald Drilon									
							// Old 1 - does not produce the correct result
							// $contract = $contract_fc+$contract_sc;
							$contract = $ave_inv_amount/1.1;
							$ave = $ave_rec_amount/1.1; 
							$less_amount = $contract - $ave;							
							
							?>
							<th width="85" align="right"><span class='price'> $<?php echo number_format($less_amount,2,'.',',') ?>  </span></th>
							<th width="85" align="right"><span class='price'> &nbsp;  </span></th>
							
							<th width="250"></th>
						
						
						
						</tr>
						</thead>
						
						</table>
						
						
						
						
						
						<br>
						
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