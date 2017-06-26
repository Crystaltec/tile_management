<?php
ob_start();
include_once "include/logincheck.inc";
include_once "include/common.inc";
include_once "include/dbconn.inc";
include_once "include/myfunc.inc";

$list_Records = array();
$s_Cond = "";
$srch_userid = $_REQUEST["srch_userid"];
$project_id = $_REQUEST["project_id"];
$employee_id = $_REQUEST["employee_id"];
$from = $_REQUEST["from"];
$to = $_REQUEST["to"];

//report_type = monthly, weekly, daily
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

if ($project_id) {
	$_title =  $total_list[0]["project_name"];
}else if ($employee_id) {
	$_title =  $total_list[0]["employee_name"];
}

header("Content-type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=PAYROLL_DETAIL-".$_title.".xls");
header("Content-Description: PHP5 Generated Data");
?>
<html>
<style type="text/css">
body {
	margin: 0;
	padding: 0;
	font:9pt arial;
	color:#666666;
	line-height:140%;
}

ol, ul,li {
	list-style: none;
	margin:0;
	padding:0;
}
blockquote, q {
	quotes: none;
}
blockquote:before, blockquote:after,
q:before, q:after {
	content: '';
	content: none;
}
a { color:#666666; line-height:140%; text-decoration: none; }

a:active, a:hover {
	color:#666666;
	line-height:140%;
	text-decoration: underline;
}

table {
	font:9pt arial;
	color:#000000;
	line-height:140%;
}

img { border:0; }

form { 	margin:0; }

.input {
	border:1px solid #8B9CB4;
	background-color:transparent;
	font:9pt Tahoma;
	color:#000000;
	margin:1px;
}

input:focus, select:focus, textarea:focus { background-color: lightyellow; }

input, select, textarea { margin:3px; }

sub { margin:3px; }
.textarea {
	border:1px solid #8B9CB4;
	background-color:#FFFFFF;
	font:9pt Tahoma;
	color:#000000;
	margin:1px;
}

.button {
	border:1px solid #8B9CB4;
	background-color:#E0E0E0;
	font:9pt Tahoma;
	color:#000000;
	margin:1px;
}


.button02 {	
	background-color:#E0E0E0;
	font:10pt Tahoma;
	color:#000000;
	margin:1px;
}

.tr_bold { font-weight:bold }
.tr_bold02 { font-weight:bold;color:#940000;}
.tr_normal02 { font-weight:normal;color:black; }
.td_fontcolor1 {color:#00111B;}


.price {font-family:arial;color:#E83100;font-weight:bold;}
.price2 { color:#B60000; font-size:9pt; padding-right:4px; text-align:right; font-weight:bold; }
.pricenb {font-family:arial;color:#E83100;}
.pricedis {font-family:arial;color:#034074;font-weight:bold}
.menu01 {font-size:10pt;}

.report {
	border:1px solid black;
	padding:0;
	margin:10px;
}
.report td {
	border:1px solid black;
}
.quantity01 {
	color:#000000;
	font-size:9pt;
	padding-right:4px;
	text-align:right;
	font-weight:bold;
}

.quantity0101 {
	color:#000000;
	font-size:10pt;
	padding-right:4px;
	text-align:center;
	font-weight:bold;
}

.quantity02 {
	color:#B60000;
	font-size:9pt;
	padding-right:4px;
	text-align:center;
	font-weight:bold;
}

.quantity_bold02 {
	color:#B60000;
	font-size:12pt;
	padding-right:4px;
	text-align:right;
	font-weight:bold;
}


.quantity03 {
	color:#B60000;
	font-size:9pt;
	padding-right:4px;
	text-align:right;
	font-weight:bold;
}

.font10 {font-size:10pt;font-family:arial;color:#000000;font-weight:normal}
.font10_bold {font-size:10pt;font-family:arial;color:#000000;font-weight:bold}
.font12_bold {font-size:12pt;font-family:arial;color:#000000;font-weight:bold}

.dinput {background-color:#D6D6D6;font-weight:bold;color:#000000}
.dinput2 {background-color:#E8E8E8;font-weight:normal;color:#000000}
.dinput3 {background-color:#FFCF7B;font-weight:bold;color:#000000}
.dinput4 {background-color:#FFCACA;font-weight:bold;color:#000000}
.dinput5 {background-color:#E7E3E7;font-weight:bold;color:#000000}
.dinput6 {background-color:#FFFFFF;font-weight:bold;color:#000000}
.dinput7 {background-color:#DEDFDE;font-weight:bold;color:#000000}
.dinput8 {background-color:#C6C7C6;font-weight:bold;color:#000000}

.font11_bold {font-size:11pt;font-family:Tahoma;color:#1A426A;font-weight:bold}

a.info{
        position:relative;           /*this is the key*/
        z-index:24;
       }
 
        a.info:hover {
        z-index:25;
      
        }
 
        a.info span{
        display: none;  /* hide the span text using this css */
        }
 
        a.info:hover span{ /*the span will display just on :hover state*/
        display:block;
        position:absolute;
        top: 1.7em;
        left: 1.5em;
		font-size:9pt ;
		background :#DF0000;
		width: 150px;
        z-index:30;
        }
.completed {
	background-color:#0474a3; 
	color:#ffffff;
}
.holding {
	background-color:#ecac00;
	color:#ffffff;
}
table.list {
	
}
table.list th {
	border-bottom: 1px solid black;
    cursor: pointer;
	font-weight: bold;
}
table.list td {
	padding:3px 10px;
	text-align: left;
}
.even {
	background-color: white;
}
.odd {
	background-color: #e9e9e9;
}
.mandatory {
	color: #FAA834;
	font-size:18px;
	font-weight: bold;
}

.new_entry th,
.new_entry tr,
.list_table th, th{
	height: 30px;
}

.list_table td {
	height: 25px;
}

.car {
	background-color:yellow;
	color:red;
}
.center {
	text-align:center;	
}
.right {
	text-align:right;
	padding-right:3px;
}
.left {
	text-align:left;
	padding-left:3px;
}
.detail {
	font-size: .9em;
	cursor: pointer;
}
.break {
	page-break-after: always;
}
.half,
.full,
.nill,
.job_extra_hour {
	display:inline-block;
	font-size:10px;
	width:150px;
	cursor:pointer;
}
.full {
	height:68px;
	background-color:#e2e2e2;
	color:#000;
}
.half {
	background-color:#e2e2e2;
	color:#000;
	height:34px;
}

.font90{ font-size: .9em !important; }
.right10 { text-align:right; padding-right: 10px; }
#processing {display:none; position:fixed; top:50%;left:50%; z-index:999; width:200px; height:40px; margin-left:-10px; margin-top:-20px; }
.no_bg:focus { background-color:transparent !important; }
/* IE 6 doesn't support max-height
 * we use height instead, but this forces the menu to always be this tall
 */
* html .ui-autocomplete {	height: 150px;}
.red {	color: red;}
.font11 { font-size:11px;} 
.font12 { font-size:12px;}
.strike { text-decoration: line-through;}
</style>
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
		<td class='font12_bold left' colspan='7'><?=$title_txt;?>&nbsp;<span class='right' style='float:right;'>Page <?php echo $current_page . " / " . $total_page;?></span>	</td>
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
				<td width='90' class='center' >Wage</td>
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
			<?		
					}
					
				}				
			?>
			<tr class="font12_bold" >
				<td colspan="8" height="30" class="right"><span class="font12_bold" >SUB TOTAL :</span>&nbsp;<span class="quantity_bold02" ><?=($sub_total)? "$".number_format($sub_total,2,".",',') : "";?></span></td>
			</tr>

</TABLE>
</BODY>
</HTML>
<?php ob_flush(); ?>
