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

$sday = $_REQUEST["sday"];
$smonth = $_REQUEST["smonth"];
$syear = $_REQUEST["syear"];

$eday = $_REQUEST["eday"];
$emonth = $_REQUEST["emonth"];
$eyear = $_REQUEST["eyear"];
$ehour = $_REQUEST["ehour"];
$emin = $_REQUEST["emin"];

// Order Date Base, Deliver Date Base 결정 플래그.
$act = $_REQUEST["act"];

$start_date = $syear."-".$smonth."-".$sday;
$end_date = $eyear."-".$emonth."-".$eday;
$end_date = $end_date . " ".$ehour.":".$emin.":59";
if($act == "obase" || $act == "") {
	$s_Cond .= " AND (o.orders_date >= '$start_date' AND o.orders_date <= '$end_date') ";
	$title_txt = "PROJECT DETAIL (PERIOD : ". getAUDate($start_date)." ~ ". getAUDate($end_date) . ")";
} 

$s_Cond02 = "";

$s_Cond .= "and  o.project_id <> '' ";
// TOTAL PROJECT QUERY ------------------------------------------------------------------------------------------


$sql = "SELECT o.orders_number, o.orders_date, o.project_id, IF(o.material_id <> '0' and o.material_id <>  '',m.material_name,o.material_description) as material_name, p.project_name, sum(IF(o.material_id <> '0' and o.material_id <> '',o.material_price,IF(o.orders_tax = 'N', o.material_price,o.material_price/1.1)) * o.orders_inventory) as amount FROM orders o ";
$sql.="INNER JOIN project p ";
$sql.="  ON o.project_id=p.project_id ";
$sql.="LEFT JOIN material m ";
$sql.="	 ON o.material_id=m.material_id ";
$sql.="WHERE 1=1 and ((new_order = 'N' AND (orders_number = ' ' or (orders_number <> '' and o.material_id = 0)) )OR new_order='Y') ".  $s_Cond. $s_Cond02. " ";
$sql.=" group by project_id ORDER BY p.project_name ";
//echo $sql."<p>";
$total_list = array();
$id_cnn = mysql_query($sql) or exit(mysql_error());
while($id_rst = mysql_fetch_assoc($id_cnn)) {
	
	//if ( $id_rst["project_id"] == $total_list[key($total_list)]["project_id"] ) {

	//	$total_list[key($total_list)]["amount"] += $id_rst["amount"];
		
	//} else {
		
		$total_list = array_merge($total_list, array($id_rst));
	//}
}
mysql_free_result($id_cnn);
$total_cnt = count($total_list);

?>
<BODY topmargin="0" leftmargin="0">
<TABLE border="0" cellpadding="0" cellspacing="0" width="1000" align="left" bordercolor="#CECED0" bordercolordark="white">
<TR>
	<TD style="padding:10 10 10 10">
	<TABLE border="0" cellpadding="0" cellspacing="0" width="800" align="left" bordercolor="#CECED0" bordercolordark="white">
	<TR>
		<TD valign="top" style="padding:10 10 10 10">
			<table border="0" cellpadding="0" cellspacing="0" bordercolor="#CECED0" bordercolordark="white" width="<?=$f_width?>" class="font10">
			<tr>
				<td width="120" class="font12_bold">REPORT </td><td class="font12_bold">: <?=$title_txt?>&nbsp;<a href="javascript:goExcelPrint()"><img src="images/icon_excel.gif"></a></td>
			</tr>
			<tr>
				<td> Issue Date </td>
				<td>: <?=$now_datetime?></td>
				<td></td>
			</tr>
			</table>
			<!-- TOTAL PROJECT -------------------------------------------------------------------------------------->
			<table border="0" cellpadding="0" cellspacing="1" width="800" bgcolor="#000000">
			<tr class="font12_bold" bgcolor="#CECED0"><td colspan=2 height="30" align="center">TOTAL</td></tr>
			<tr class="font10_bold" bgcolor="#CECED0">
				<td width="300" align="center" height="30">Project Name</td>
				<td width="500" align="center" class='quantity0101'>Amount</td>
			</tr>
			<?				
				if(is_array($total_list)) {		
					$color1 = "#D9D9D9";
					$color2 = "#FFFFFF";
					
					(float)$total_amount = 0;

					for($i=0; $i<$total_cnt; $i++) {						
						$bgcolor = $color1;
						
						$total_amount += $total_list[$i]["amount"];
					
							
			?>
			<tr>
				<td align="center" bgcolor="<?=$bgcolor?>" height="25"><?=$total_list[$i]["project_name"]?></td>
				<td align="right" bgcolor="<?=$bgcolor?>" class='quantity02' style="text-align:right;"><?=($total_list[$i]["amount"])? "$".number_format($total_list[$i]["amount"],2,".",',') :"";?></td>
			</tr>			
			<?		
						
					}
				}				
			?>
			<tr class="font12_bold" bgcolor="#CECED0"><td colspan=2 height="30" align="right"><span class="font12_bold" >TOTAL :</span>&nbsp;<span class="quantity_bold02" ><?=($total_amount)? "$".number_format($total_amount,2,".",',') : "";?></span></td></tr>
			</table>
			<!-- TOTAL PROJECT END------------------------------------------------------------------------------------->
		</TD>
	</TR>
	
	</TABLE>
	</TD>
</TR>
</TABLE>
<form name="frm01" method="post">
<input type="hidden" name="project_id" value="<?=$project_id?>">
<input type="hidden" name="sday" value="<?=$sday?>">
<input type="hidden" name="smonth" value="<?=$smonth?>">
<input type="hidden" name="syear" value="<?=$syear?>">
<input type="hidden" name="eday" value="<?=$eday?>">
<input type="hidden" name="eyear" value="<?=$eyear?>">
<input type="hidden" name="emonth" value="<?=$emonth?>">
<input type="hidden" name="ehour" value="<?=$ehour?>">
<input type="hidden" name="emin" value="<?=$emin?>">
<input type="hidden" name="act" value="<?=$act?>">
</form>
<script>
function goExcelPrint() {
	var f = document.frm01;
	//f.target="_blank";
	f.action="report_project_sub_excel_all.php";
	f.submit();
}
</script>
</BODY>
</HTML>
<? ob_flush(); ?>
