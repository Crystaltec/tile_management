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
<BODY leftmargin=0 topmargin=0>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
<!-- BODY TOP ------------------------------------------------------------------------------------------->
<tr>
<td style="padding-left:0px"><? include_once "top.php"; ?></td>
</tr>
<!-- BODY TOP END --------------------------------------------------------------------------------------->

<table border="1" cellpadding="0" cellspacing="0" width="100%">
    <br>
	<td valign="top" width="100" height="100%">
	<!-- LEFT -------------------------------------------------------------------------------------------------->
	
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	<!-- LEFT END ---------------------------------------------------------------------------------------------->
	</td>
	<!-- LEFT BG------------------------------------------------------------------------------------------------>
	<td width="3">
	</td>
	<!-- LEFT BG END-------------------------------------------------------------------------------------------->
	<td valign="top">
	<!-- BODY -------------------------------------------------------------------------------------------------->
	<table border="0" cellpadding="0" style="float:left;" cellspacing="0" width="50%">
			<!-- BODY CENTER ----------------------------------------------------------------------------------------->
		<tr>
			<td width="100%">			
			<table  cellpadding="0" border="0"  cellspacing="0" width="100%">
			<tr>
				<td style="padding-left:15px" valign="bottom" height="24" colspan="2">					
				</td>
			</tr>	
			<tr>
				<td style="padding-left:15px" valign="top">


				<!-- CONTENTS -------------------------------------------------------------------------------------------->
				<table border="0" cellpadding="0" cellspacing="0" width="600">
				<tr>
					<td valign="top">
					<table border="0" cellpadding="0" cellspacing="0" width="600" class="font11_bold">
						<tr><td height="8"></td></tr>
						<tr><td width="252"><img src="images/icon_circle03.gif">&nbsp;<span style="height:21px">Job Management (Preview)</span></td></tr>
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
							
							$employee_id= $_REQUEST["employee_id"];
							$builder_id = $_REQUEST["builder_id"];
						
							$start_date = date("Y-m-d", mktime(0, 0, 0, date("m")-1, 1, date("Y")));						
							$end_date = date("Y-m-d", mktime(0, 0, 0, date("m"), 0, date("Y")));

							//$start_date = $sday."/".$smonth."/".$syear;
							//$end_date = $eday."/".$emonth."/".$eyear;
							
							$end_date = $end_date;
							
							$s_Cond .= " AND ( job_date >= '$start_date' AND job_date <= '$end_date') ";	

							if($limitList) {
								$srch_param .="&limitList=$limitList";
								//$srch_param = urlencode($srch_param);
							}
											
							
						?>
						<!--<form name="searchform">
						<table border="0" cellpadding="0" cellspacing="0" height="40" valign="bottom" width="600">							
							
								
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
					
								<input type="button" Value="Search" onclick="searchNow()"></td></tr>
						</table>
						</form>-->
						<br>
						<form name="projectForm" method="POST" action="<?=$_SERVER["PHP_SELF"]?>">
						<input type="hidden" name="act" value="<?=$act?>">
						<table border="0" width="600" cellpadding="0" cellspacing="1" class="list_table">
						<thead>
						<tr align="center" >
							<th colspan="3">Previous Month (<?php echo $start_date ." ~ ". $end_date;   ?>)</th>
						</tr>
						</thead>
						<thead>
						<tr align="center" >
							<th width="30">No.</th>
							<th width="100">Date</th>
							<th width="300">Project</th>
							
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
							
							$total = getRowCount2("SELECT COUNT(*) FROM job WHERE 1=1 ". $s_Cond);
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
							
							
							$Query  = "SELECT DISTINCT project_id, job_date FROM job WHERE 1=1 " . $s_Cond . "ORDER BY job_date DESC LIMIT $start, $limitList";
							

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

								$Query = "SELECT sum(orders_inventory * IF(material_id <> '0' and material_id <> '',material_price,IF(orders_tax = 'N', material_price,material_price/1.1)) ) FROM orders WHERE (orders_number = ' ' or (orders_number  <> '' and material_id = 0) ) and inv_no = '".$list_Records[$i]["inv_no"]."'";
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
						
							<td class="center"><?=getAUDate($list_Records[$i]["job_date"])?></td>
							<td class="center"><input type="hidden" value="<?=$list_Records[$i]["id"]?>" name="id[]"><b>
							<?php echo getName("project",$list_Records[$i]["project_id"]); ?></td>
							
		
						</tr>
						<?php
							}
							} else {
								echo "<tr><td colspan=6 height=40 align=center>Nothing to display</td></tr>";
							}
						?>
						</tbody>
						</table>
						
						</form>
						
						
					</td>
				</tr>
				<!--<tr><td align="left"><?php include_once "paging.php"?></td></tr>-->
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
				
				
				<br><br><br><br>
				
				
				<table border="0" cellpadding="0" style="float:right;" cellspacing="0" width="50%">
				
				
				<table border="0"  style="float:right" cellpadding="0" cellspacing="0" width="600">
				
			
				<tr>
					<td valign="top">
						<?php
							//Search Value
							$s_Cond = "";
							
							$employee_id= $_REQUEST["employee_id"];
							$builder_id = $_REQUEST["builder_id"];
						
							$start_date = date('Y-m-01');						
							$end_date = date("Y-m-d");

							//$start_date = $sday."/".$smonth."/".$syear;
							//$end_date = $eday."/".$emonth."/".$eyear;
							
							$end_date = $end_date;
							
							$s_Cond .= " AND ( job_date >= '$start_date' AND job_date <= '$end_date') ";	

							if($limitList) {
								$srch_param .="&limitList=$limitList";
								//$srch_param = urlencode($srch_param);
							}
											
							
						?>
						<!--<form name="searchform">
						<table border="0" cellpadding="0" style="float:right" cellspacing="0" height="40" valign="bottom" width="600">							
							
								
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
					
								<input type="button" Value="Search" onclick="searchNow()"></td></tr>
						</table>
						</form>-->
						<br>
						<form name="projectForm" method="POST" action="<?=$_SERVER["PHP_SELF"]?>">
						<input type="hidden" name="act" value="<?=$act?>">
						<table border="0" width="600" style="float:right" cellpadding="0" cellspacing="1" class="list_table">
						<thead>
						<tr align="center" >
							<th colspan="3">Current Month (<?php echo $start_date ." ~ ". $end_date;   ?>)</th>
						</tr>
						</thead>
						<thead>
						<tr align="center" >
							<th width="30">No.</th>
							<th width="100">Date</th>
							<th width="300">Project</th>
							
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
							
							$total = getRowCount2("SELECT COUNT(*) FROM job WHERE 1=1 ". $s_Cond);
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
							
							
							$Query  = "SELECT DISTINCT project_id, job_date FROM job WHERE 1=1 " . $s_Cond . "ORDER BY job_date DESC LIMIT $start, $limitList";
							

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

								$Query = "SELECT sum(orders_inventory * IF(material_id <> '0' and material_id <> '',material_price,IF(orders_tax = 'N', material_price,material_price/1.1)) ) FROM orders WHERE (orders_number = ' ' or (orders_number  <> '' and material_id = 0) ) and inv_no = '".$list_Records[$i]["inv_no"]."'";
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
						
							<td class="center"><?=getAUDate($list_Records[$i]["job_date"])?></td>
							<td class="center"><input type="hidden" value="<?=$list_Records[$i]["id"]?>" name="id[]"><b>
							<?php echo getName("project",$list_Records[$i]["project_id"]); ?></td>
							
		
						</tr>
						<?php
							}
							} else {
								echo "<tr><td colspan=6 height=40 align=center>Nothing to display</td></tr>";
							}
						?>
						</tbody>
						</table>
						
						</form>
						
						
					</td>
				</tr>
				<!--<tr><td align="left"><?php include_once "paging.php"?></td></tr>-->
				</table>
				
				
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