<?php
ob_start();
include_once "include/logincheck.inc";
include_once "include/common.inc";
include_once "include/dbconn.inc";
include_once "include/myfunc.inc";
include_once "htmlinclude/head.php";

$list_Records = array();
$s_Cond = "";
$srch_userid = $_REQUEST["srch_userid"];
$project_id = $_REQUEST["project_id"];
$employee_id = $_REQUEST["employee_id"];
$from = $_REQUEST["from"];
$to = $_REQUEST["to"];

// report_type = monthly, weekly, daily
$report_type = $_REQUEST["report_type"]; 

$title_txt = "PAYROLL DETAIL (PERIOD : ". $from." ~ ". $to . ")";

$s_Cond02 = "";

if ($project_id) {
	$s_Cond .= " AND j.project_id = '$project_id' ";
	$s_Cond02 = ", p.project_name, CONCAT_WS(', ',e.last_name, e.first_name ) ";
}

if ($employee_id) {
	$s_Cond .= " AND j.employee_id = '$employee_id' ";
	$s_Cond02 = ", CONCAT_WS(', ',e.last_name, e.first_name ) , p.project_name ";	
}

// QUERY ------------------------------------------------------------------------------------------
$sql = " SELECT e.employee_id,j.job_session, IF(attendance='A',0,IF(job_session='FULL',1* job_session_rates * fw.wages_amount,IF(job_session='HALF',0.5*job_session_rates * fw.wages_amount,0))) AS job_normal, IF(attendance='A',0,(job_extra_hour * job_extra_hour_rates * hw.wages_amount)) AS job_extra, job_session, job_extra_hour, travel_fee, parking_fee, tool_fee, extra_fee,  f_wages_id, h_wages_id, p.project_id, p.project_name,j.attendance,  j.id, CONCAT_WS(', ',e.last_name, e.first_name ) as employee_name,j.job_date  ". 
" FROM job j, project p, employee e, wages fw, wages hw ".
" WHERE j.f_wages_id = fw.wages_id and j.h_wages_id = hw.wages_id AND j.employee_id = e.id AND j.project_id = p.project_id " . 
" AND j.employee_id = fw.employee_id and j.employee_id = hw.employee_id " . 
" AND (j.job_date >= '".getAUDateToDB($from)."' AND j.job_date <= '".getAUDateToDB($to)."' ) " . 
$s_Cond .
" ORDER BY job_date " . $s_Cond02;

//echo $sql."<p>";
$total_list = array();

$id_cnn = mysql_query($sql) or exit(mysql_error());
while($id_rst = mysql_fetch_assoc($id_cnn)) {
	$total_list = array_merge($total_list, array($id_rst));
}
mysql_free_result($id_cnn);
$total_cnt = count($total_list);

?>
<BODY style="margin: 10px; width:800px;">
<?php 
	$listpage = 34;
					
	if(is_array($total_list)) {		
	
	$total_page = ceil($total_cnt / $listpage);
	$current_page = 1;
					
	(float)$total_amount = 0;
?>
<table width='800' class="report" cellspacing='0' >
	<TR>
		<td class='font12_bold' >REPORT </td>
		<td class='font12_bold left' colspan='7'><?=$title_txt;?>&nbsp;<a href='javascript:goExcelPrint()'><img src='images/icon_excel.gif'></a><span class='right' style='float:right;'>Page <?php echo $current_page . " / " . $total_page;?></span>	</td>
	</tr>
	<tr>
		<td> Issue Date </td>
		<td colspan='7' class='left'><?=$now_datetime?></td>
	</tr>
	
			<tr class='font12_bold' >
				<td colspan='8' height='30' class='center'>
				<?php
				if ($project_id) {
					echo  $total_list[0]["project_name"];
				}else if ($employee_id) {
					echo $total_list[0]["employee_name"];
					if ($total_list[0]["employee_id"] <> "") echo "(" . $total_list[0]["employee_id"] . ")";
				}
				?>
				</td>	
			</tr>
			<tr class='font10_bold' >
				<td width='105' class='center' height='30'>Date</td>
				<td width='245' class='center' height='30'>
				<?php
				if ($project_id) {
					echo  "Name";
				}else if ($employee_id) {
					echo "Project";
				}
				?>
				</td>
				<td width='90' class='center'>Wage</td>
				<td width='90' class='center'>Travel fee</td>
				<td width='90' class='center'>Parking fee</td>
				<td width='90' class='center'>Tool fee</td>
				<td width='90' class='center'>Extra fee</td>

				<td width='100' class='center' class='quantity0101'>Total</td>
			</tr>
<?php			
					
	for($i=0; $i<$total_cnt; $i++) {						
				
			if ( $i <> 0 && ($i % $listpage) == 0) {
				echo "</table>";
				echo "<div class='break right'></div>"; 
				$current_page +=1;
?>
<table width='800' class="report" cellspacing='0' >
	<TR>
		<td class='font12_bold' >REPORT </td>
		<td class='font12_bold' colspan='7'><?=$title_txt;?>&nbsp;<a href='javascript:goExcelPrint()'><img src='images/icon_excel.gif'></a><span class='right' style='float:right;'>Page <?php echo $current_page . " / " . $total_page;?></span>	</td>
	</tr>
	<tr>
		<td> Issue Date </td>
		<td colspan='7'><?=$now_datetime?></td>
	</tr>
	
			<tr class='font12_bold' >
				<td colspan='8' height='30' class='center'>
				<?php
				if ($project_id) {
					echo  $total_list[0]["project_name"];
				}else if ($employee_id) {
					echo $total_list[0]["employee_name"];
					if ($total_list[0]["employee_id"] <> "") echo "(" . $total_list[0]["employee_id"] . ")";
				}
				?>
				</td>	
			</tr>
				<tr class='font10_bold' >
				<td width='105' class='center' height='30'>Date</td>
				<td width='245' class='center' height='30'>
				<?php
				if ($project_id) {
					echo  "Name";
				}else if ($employee_id) {
					echo "Project";
				}
				?>
				</td>
				<td width='90' class='center' >Wage</td>
				<td width='90' class='center'>Travel fee</td>
				<td width='90' class='center'>Parking fee</td>
				<td width='90' class='center'>Tool fee</td>
				<td width='90' class='center'>Extra fee</td>
				<td width='100' class='center' class='quantity0101'>Total</td>
			</tr>
<?php				
			}
												
						$bgcolor = $color1;
						
						$total = 0;
						
						if ($total_list[$i]["attendance"] == 'A') {
							$total_list[$i]["job_normal"] = 0;
							$total_list[$i]["job_extra"] = 0;
						}
						$total = $total_list[$i]["job_normal"]+ $total_list[$i]["job_extra"]+ $total_list[$i]["travel_fee"] + 
								$total_list[$i]["parking_fee"]+$total_list[$i]["tool_fee"]+$total_list[$i]["extra_fee"];

						$sub_total += $total;
									
			?>
			<tr>
				<td class="left"  height="25"><?=date('d-m-Y, D',strtotime($total_list[$i]["job_date"]))?></td>
				<td class="center"  height="25">
				<?php
				if ($project_id) {
					echo $total_list[$i]["employee_name"];
					if ($total_list[$i]["employee_id"] <> "") echo "(" . $total_list[$i]["employee_id"] . ")";
				}else if ($employee_id) {
					echo  $total_list[$i]["project_name"];	
				}
				?>
				</td>
				<td  class='quantity02 right'><?=($total_list[$i]["job_normal"]+ $total_list[$i]["job_extra"])? "$".number_format($total_list[$i]["job_normal"]+ $total_list[$i]["job_extra"],2,".",',') :"";?></td>
				<td  class='quantity02 right'><?=($total_list[$i]["travel_fee"])? "$".number_format($total_list[$i]["travel_fee"],2,".",',') :"";?></td>
				<td  class='quantity02 right'><?=($total_list[$i]["parking_fee"])? "$".number_format($total_list[$i]["parking_fee"],2,".",',') :"";?></td>
				<td  class='quantity02 right'><?=($total_list[$i]["tool_fee"])? "$".number_format($total_list[$i]["tool_fee"],2,".",',') :"";?></td>
				<td  class='quantity02 right'><?=($total_list[$i]["extra_fee"])? "$".number_format($total_list[$i]["extra_fee"],2,".",',') :"";?></td>
				<td  class='quantity02 right'><?=($total)? "$".number_format($total,2,".",',') :"";?></td>
			</tr>			
			<?php		
					}
					
				}				
			?>
			<tr class="font12_bold" >
				<td colspan="8" height="30" class="right"><span class="font12_bold" >SUB TOTAL :</span>&nbsp;<span class="quantity_bold02" ><?=($sub_total)? "$".number_format($sub_total,2,".",',') : "";?></span></td>
			</tr>

</TABLE>
<form name="frm01" method="post">

<input type="hidden" name="project_id" value="<?=$project_id?>">
<input type="hidden" name="employee_id" value="<?=$employee_id?>">
<input type="hidden" name="from" value="<?=$from?>">
<input type="hidden" name="to" value="<?=$to?>">
<input type="hidden" name="report_type" value="<?=$report_type?>">
<input type="hidden" name="act" value="<?=$act?>">
</form>
<script>
function goExcelPrint() {
	var f = document.frm01;
	//f.target="_blank";
	f.action="report_payroll_sub_excel.php";
	f.submit();
}
</script>
</BODY>
</HTML>
<? ob_flush(); ?>
