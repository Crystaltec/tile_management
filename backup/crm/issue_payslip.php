<?php
ob_start();
include_once "include/logincheck.inc";
include_once "include/common.inc";
include_once "include/dbconn.inc";
include_once "include/myfunc.inc";

if (!$_REQUEST["transaction_id"])
	exit ;

$transaction_id = $_REQUEST["transaction_id"];

## 쿼리, 담을 배열 선언
$list_Records = array();

$Query = "SELECT t.*,e.*, e.id as eid, date_add(transaction_period_start, interval 6 day) as transaction_period_end,CONCAT_WS(', ',last_name, first_name )  as employee_name  ";
$Query .= " FROM transaction t, employee e WHERE t.employee_id = e.id AND transaction_id='" . $transaction_id . "'";

$id_cnn = mysql_query($Query) or exit(mysql_error());
while ($id_rst = mysql_fetch_assoc($id_cnn)) {
	$list_Records = array_merge($list_Records, array($id_rst));
	//print_r($list_Records);
	//echo "<p>";
}
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<title>Payslip</title>
		<meta name="description" content="" />
		<meta name="author" content="goodideal" />
		<meta name="viewport" content="width=device-width; initial-scale=1.0" />
	</head>
	<style type="text/css">
		body {
			font-family: calibri;
			font-size: 12px;
		}
		#order_logo {
			background-image: url(images/logo4.jpg);
			background-repeat: no-repeat;
			vertical-align: bottom;
			text-align: center;
			width: 120px;
		}
			
		.comments {
			font-size: 9px !important;
			text-align: center !important;
			display: block !important;
			padding: 0 !important;
			margin: 0 !important;
			font-weight: normal !important;
		}
		.company_name {
			font-family: Tahoma;
			font-size: 15px;
			font-weight: bold;
			vertical-align: top;
		}
		.address {
			vertical-align: top;
		}
		.title {
			text-align: left;

			font-size: 14px;
			vertical-align: bottom;
			height: 20px;
		}
					
		.remarks {

			text-align: left;
			padding-left: 10px;
			padding-right: 10px;
			border-top-width: 1px;
			border-top-style: solid;
			border-top-color: #000;
			width: 650px;
			height: 50px;
			overflow: hidden;
			text-overflow: ellipsis;
			vertical-align: top;
		}
		.main_content {

			text-align: left;
			border-top-style: none;
			border-right-style: none;
			border-left-style: solid;
			border-bottom-style:solid;
			border-color: #000;
			border-width: 1px;
			line-height: 100%;
			margin: 0;
			padding: 0 0 0 5px;
			height: 25px;
			font-size: 11px;
		}
		.main_content_right {

			text-align: center;
			border-top-style: none;
			border-left-style: solid;
			border-right-style: solid;
			border-bottom-style:solid;
			border-color:#000;
			border-width:1px;
			
			overflow: hidden;
			text-overflow: ellipsis;
			margin: 0;
			padding: 0;
			line-height: 100%;
			height: 25px;
			font-size: 11px;
		}
		.main_bottom {

			text-align: left;
			border-top-style: none;
			border-bottom-style: solid;
			border-left-style: solid;
			border-color: #000;
			border-width: 1px;
			line-height: 100%;
			margin: 0;
			padding: 0 0 0 5px;
			height: 25px;
			font-size: 11px;
			font-weight:bold;
		}
		.main_bottom_right {

			text-align: center;
			border:1px solid #000;
			overflow: hidden;
			text-overflow: ellipsis;
			border-top-style:none;
			margin: 0;
			padding: 0;
			line-height: 100%;
			height: 25px;
			font-size: 11px;
			font-weight:bold;
		}
	
		
		.main_top_content {

			text-align: left;
			border-left-width: 1px;
			border-top-width: 1px;
			border-bottom-width: 1px;
			border-bottom-style: solid;
			border-top-style: solid;
			border-left-style: solid;
			border-color:#000;
	
			margin: 0;
			padding: 0 0 0 5px;
			line-height: 100%;
			height: 20px;
			font-weight:bold;
		}
		.main_top_content_right {

			text-align: center;
			border: 1px solid #000;
			margin: 0;
			padding: 0;
			line-height: 100%;
			height: 20px;
			font-weight:bold;
		}
	</style>
	<BODY topmargin="0" leftmargin="0" onload="printpage();">
		<TABLE border="0" cellpadding="0" cellspacing="0" width="651" align="center">
			<tr >
				<td width="120" height="85" id="order_logo" >www.echotiles.com</td>
				<td colspan="2" style="vertical-align:top;" >
				<div class="company_name">
					&nbsp;ECHO TILES PTY LTD
				</div>
				<div class="address">
					&nbsp;10/74 Millaroo Dr, Helensvale QLD 4212
					<br />
					&nbsp;Tel:&nbsp; (07) 5519 9566
					<br />
					&nbsp;Fax: (07) 5519 9588
					<br />
					&nbsp;ABN: 64 107 565 239
				</div></td>
				<td width="140"> Date of Payment<br />Pay of Period </td>
				<td width="140"><?=getAUDate($list_Records[0]["transaction_date"])?><br/><?php echo getAUDate($list_Records[0]["transaction_period_start"])." to ".getAUDate($list_Records[0]["transaction_period_end"])?></td>
			</tr>
			<tr>
				<td colspan="6">&nbsp;</td>
			</tr>
			<tr>
				<td >Employee's Name</td>
				<td colspan="2"><?=$list_Records[0]["employee_name"]?></td>
				<Td></Td>
				<td></td>
			</tr>
			<tr>
				<td colspan="5">&nbsp;</td>
			</tr>
			<tr>
				<td colspan="4" class="main_top_content" >DESCRIPTION</td>
				<td class="main_top_content_right" >AMOUNT</td>
			</tr>
			<?php 
				$Query = "SELECT sum(IF(attendance='A',0,IF(job_session='FULL',1* job_session_rates * fw.wages_amount,IF(job_session='HALF',0.5*job_session_rates * fw.wages_amount,0)))) as ordinary , SUM(IF(attendance='A',0,(job_extra_hour * job_extra_hour_rates * hw.wages_amount))) as extra_hour, SUM(travel_fee+parking_fee) as travel_allowance, SUM(tool_fee) as tool_allowance, SUM(extra_fee) as Others , " .
			" e.id as eid, e.employee_id, CONCAT_WS(', ',last_name, first_name ) as employee_name " .
			" FROM job j, wages fw, wages hw, employee e ".
			" WHERE j.employee_id = e.id AND  j.f_wages_id = fw.wages_id and j.h_wages_id = hw.wages_id and j.employee_id = fw.employee_id and j.employee_id = hw.employee_id AND job_date between '".$list_Records[0]["transaction_period_start"]."' AND '".$list_Records[0]["transaction_period_end"]."' " .
			" AND e.id = '". $list_Records[0]['eid']."'" .
			" ORDER BY CONCAT_WS(', ',last_name, first_name )";
			$wages_details = getRowCount($Query);
			
			?>
			<tr>
				<td colspan="4" class="main_content" >Ordinary</td>
				<td class="main_content_right" ><?php echo $wages_details[0];?></td>
			</tr>
			<tr>
				<td colspan="4" class="main_content" >Extra hours</td>
				<td class="main_content_right" ><?php echo $wages_details[1];?></td>
			</tr>
			<tr>
				<td colspan="4" class="main_content" >Travel Allowance</td>
				<td class="main_content_right" ><?php echo $wages_details[2];?></td>
			</tr>
			<tr>
				<td colspan="4" class="main_content" >Tool Allowance</td>
				<td class="main_content_right" ><?php echo $wages_details[3];?></td>
			</tr>
			<tr>
				<td colspan="4" class="main_content" >Others</td>
				<td class="main_content_right" ><?php echo $wages_details[4];?></td>
			</tr>
			<tr>
				<td colspan="4" class="main_bottom">GROSS WAGES</td>
				<Td class="main_bottom_right" ><?php echo $list_Records[0]["gross_wages"];?></Td>
			</tr>
			<tr>
				<td colspan="5">&nbsp</td>
			</tr>
			<tr>
				<td colspan="5">DEDUCTIONS</td>
			</tr>
			<?php for ($i=1; $i<=5; $i++) {
				 if ($list_Records[0]['deduction_amounts'.$i] >0) {
				 	if ($i==1) {
			?>
			<tr>
				<td colspan="4" class="main_top_content" style="font-weight:normal"><?php echo $list_Records[0]['deduction_name'.$i];?></td>
				<td class="main_top_content_right" style="font-weight:normal"><?php echo $list_Records[0]['deduction_amounts'.$i];?></td>
			</tr>	
			<?php 
					}
					else {
			?>
			<tr>
				<td colspan="4" class="main_content"><?php echo $list_Records[0]['deduction_name'.$i];?></td>
				<td class="main_content_right"><?php echo $list_Records[0]['deduction_amounts'.$i];?></td>
			</tr>	
			<?php 			
					}
				}
			}
			?>
		
			<tr>
				<Td colspan="4" class="main_bottom" >TOTAL DEDUCTIONS</Td>
				<td class="main_bottom_right" ><?php echo $list_Records[0]['gross_wages']- $list_Records[0]['net_wages'];?></td>
			</tr>
			<Tr>
				<td colspan="5">&nbsp</td>
			</Tr>
			<tr>
				<Td colspan="4" class="main_top_content">NET WAGES</Td>
				<td class="main_top_content_right"><?php echo $list_Records[0]['net_wages'];?></td>
			</tr>
			
			<tr>
				<td colspan="5">&nbsp</td>
			</tr>
			<tr>
				<td class="main_top_content" colspan="4">EMPLOYER SUPERANNUATION CONTRIBUTION</td><td class="main_top_content_right"></td>
			</tr>
			<?php for ($i=1; $i<=5; $i++) {
				 if ($list_Records[0]['deduction_amounts'.$i] >0) {
				 	if ($i==1) {
			?>
			<tr>
				<td colspan="4" class="main_content" ><?php echo $list_Records[0]['_name'.$i];?></td>
				<td class="main_content_right" ><?php echo $list_Records[0]['_amounts'.$i];?></td>
			</tr>	
			<?php 
					}
					else {
			?>
			<tr>
				<td colspan="4" class="main_content"><?php echo $list_Records[0]['_name'.$i];?></td>
				<td class="main_content_right"><?php echo $list_Records[0]['_amounts'.$i];?></td>
			</tr>	
			<?php 			
					}
				}
			}
			?>
			<tr>
				<td colspan="5">&nbsp</td>
			</tr>
			<!--
			<tr>
				<td colspan="3" class="main_top_content">EMPLOYER SUPERANNUATION CONTRIBUTION</td>
				<td class="main_top_content">&nbsp</td>
				<td class="main_top_content_right">&nbsp</td>
			</tr>
			<Tr>
				<td colspan="3"></td>
				<td>&nbsp</td>
				<td>&nbsp</td>
			</Tr>
			<tr>
				<td colspan="2" class="main_top_content" style="border-right:solid;border-width:1px;">YEAR TO DATE DETAILS</td>
				<td>&nbsp</td>
				<td colspan="2" class="main_top_content" style="border-right:solid;border-width:1px;">LEAVE+OTHER BALANCES</td>
			</tr>
			<tr>
				<Td >&nbsp</Td>
				<td width="110"></td>
				<td></td>
				<td></td>
				<td></td>
			</tr>
			-->
			<tr>
				<td class="main_top_content" >PAYMENT</td><td class="main_top_content_right" width="80"><?php echo $list_Records[0]['acc_payment_type'];?></td>
				<td colspan="3"></td>
			</tr>
			<tr>
				<td class="main_content" >BSB</td><td class="main_content_right"><?php echo $list_Records[0]['acc_bsb'];?></td>
				<td colspan="3"></td>
			</tr>
			<tr>
				<td class="main_content" >ACC #</td><td class="main_content_right"><?php echo $list_Records[0]['acc_number'];?></td>
				<td colspan="3"></td>
			</tr>
			<tr>
				<td class="main_bottom" >ACC Name</td><td class="main_bottom_right"><?php echo $list_Records[0]['acc_name'];?></td>
				<td colspan="3"></td>
			</tr>
			
		</TABLE>
	</BODY>
</HTML>
<?php ob_flush(); ?>