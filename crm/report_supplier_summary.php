<?php
ob_start();
include_once "include/logincheck.inc";
include_once "include/common.inc";
include_once "include/dbconn.inc";
include_once "include/myfunc.inc";
include_once "htmlinclude/head.php";

if(!$_REQUEST) exit;


$list_Records = array();
$s_Cond = "";
$srch_userid = $_REQUEST["srch_userid"];

$summary_year = $_REQUEST["summary_year"];
if(!$summary_year) exit;

// Order Date Base, Deliver Date Base 결정 플래그.
$act = $_REQUEST["act"];


if ($summary_year) {
	$start_date = $summary_year . "-01-01";
	$end_date =  $summary_year . "-12-31 23:59:59";
}

if($act == "obase" || $act == "") {
	$s_Cond .= " AND (o.orders_date >= '$start_date' AND o.orders_date <= '$end_date') ";
	$s_Cond_t .= " AND (oc.orders_clear_date >= '$start_date' AND oc.orders_clear_date <= '$end_date') ";
	$title_txt = "SUPPLIER SUMMARY (PERIOD : ". getAUDate($start_date)." ~ ". getAUDate($end_date) . ")";
} 

$s_Cond02 = "";

// TOTAL SUPPLIER QUERY ------------------------------------------------------------------------------------------

$sql .= "
SELECT s.supplier_category,
SUM(CASE WHEN SUBSTRING(o.orders_date,6,2) = '01' THEN o.material_price * o.orders_inventory END) AS '01', 
SUM(CASE WHEN SUBSTRING(o.orders_date,6,2) = '02' THEN o.material_price * o.orders_inventory END) AS '02', 
SUM(CASE WHEN SUBSTRING(o.orders_date,6,2) = '03' THEN o.material_price * o.orders_inventory END) AS '03', 
SUM(CASE WHEN SUBSTRING(o.orders_date,6,2) = '04' THEN o.material_price * o.orders_inventory END) AS '04', 
SUM(CASE WHEN SUBSTRING(o.orders_date,6,2) = '05' THEN o.material_price * o.orders_inventory END) AS '05', 
SUM(CASE WHEN SUBSTRING(o.orders_date,6,2) = '06' THEN o.material_price * o.orders_inventory END) AS '06',
SUM(CASE WHEN SUBSTRING(o.orders_date,6,2) = '07' THEN o.material_price * o.orders_inventory END) AS '07',
SUM(CASE WHEN SUBSTRING(o.orders_date,6,2) = '08' THEN o.material_price * o.orders_inventory END) AS '08',
SUM(CASE WHEN SUBSTRING(o.orders_date,6,2) = '09' THEN o.material_price * o.orders_inventory END) AS '09',
SUM(CASE WHEN SUBSTRING(o.orders_date,6,2) = '10' THEN o.material_price * o.orders_inventory END) AS '10',
SUM(CASE WHEN SUBSTRING(o.orders_date,6,2) = '11' THEN o.material_price * o.orders_inventory END) AS '11',
SUM(CASE WHEN SUBSTRING(o.orders_date,6,2) = '12' THEN o.material_price * o.orders_inventory END) AS '12',
SUM(o.material_price * o.orders_inventory) as amount 
FROM orders o LEFT JOIN supplier s ON o.supplier_id=s.supplier_id 
WHERE 1=1 AND o.orders_number <> '' AND s.supplier_category IN ('Material','Subcontractor')  ". $s_Cond. $s_Cond02. " ";
$sql .= " GROUP BY s.supplier_category ORDER BY s.supplier_category; ";

//echo $sql."<p>";
$total_list = array();
$id_cnn = mysql_query($sql) or exit(mysql_error());
while($id_rst = mysql_fetch_assoc($id_cnn)) {
	$total_list = array_merge($total_list, array($id_rst));
}
mysql_free_result($id_cnn);


// TOTAL SUPPLIER QUERY Tile------------------------------------------------------------------------------------------

$sql_t .= "
SELECT s.supplier_category,
SUM(CASE WHEN SUBSTRING(oc.orders_clear_date,6,2) = '01' THEN o.material_price * oc.orders_clear_qty END) AS '01', 
SUM(CASE WHEN SUBSTRING(oc.orders_clear_date,6,2) = '02' THEN o.material_price * oc.orders_clear_qty END) AS '02', 
SUM(CASE WHEN SUBSTRING(oc.orders_clear_date,6,2) = '03' THEN o.material_price * oc.orders_clear_qty END) AS '03', 
SUM(CASE WHEN SUBSTRING(oc.orders_clear_date,6,2) = '04' THEN o.material_price * oc.orders_clear_qty END) AS '04', 
SUM(CASE WHEN SUBSTRING(oc.orders_clear_date,6,2) = '05' THEN o.material_price * oc.orders_clear_qty END) AS '05', 
SUM(CASE WHEN SUBSTRING(oc.orders_clear_date,6,2) = '06' THEN o.material_price * oc.orders_clear_qty END) AS '06',
SUM(CASE WHEN SUBSTRING(oc.orders_clear_date,6,2) = '07' THEN o.material_price * oc.orders_clear_qty END) AS '07',
SUM(CASE WHEN SUBSTRING(oc.orders_clear_date,6,2) = '08' THEN o.material_price * oc.orders_clear_qty END) AS '08',
SUM(CASE WHEN SUBSTRING(oc.orders_clear_date,6,2) = '09' THEN o.material_price * oc.orders_clear_qty END) AS '09',
SUM(CASE WHEN SUBSTRING(oc.orders_clear_date,6,2) = '10' THEN o.material_price * oc.orders_clear_qty END) AS '10',
SUM(CASE WHEN SUBSTRING(oc.orders_clear_date,6,2) = '11' THEN o.material_price * oc.orders_clear_qty END) AS '11',
SUM(CASE WHEN SUBSTRING(oc.orders_clear_date,6,2) = '12' THEN o.material_price * oc.orders_clear_qty END) AS '12',
SUM(o.material_price * oc.orders_clear_qty) as amount 
FROM orders_clear oc, orders o LEFT JOIN supplier s ON o.supplier_id=s.supplier_id 
WHERE 1=1 AND oc.orders_id = o.orders_id AND s.supplier_category ='Tile'  ". $s_Cond_t. $s_Cond02. " ";
$sql_t .= " GROUP BY s.supplier_category ORDER BY s.supplier_category; ";

//echo $sql_t."<p>";
$total_list_t = array();
$id_cnn = mysql_query($sql_t) or exit(mysql_error());
while($id_rst = mysql_fetch_assoc($id_cnn)) {
	$total_list_t = array_merge($total_list_t, array($id_rst));
}
mysql_free_result($id_cnn);
$total_list = array_merge($total_list_t,$total_list );
$total_cnt = count($total_list);

?>
<BODY topmargin="0" leftmargin="0">
<table width='1200' class="report" cellspacing='0' >
	<tr>
		<td class="font12_bold left" colspan="2">REPORT</td>
		<td class="font12_bold left" colspan="12"><?=$title_txt?>&nbsp;<a href="javascript:goExcelPrint()"><img src="images/icon_excel.gif"></a></td>
	</tr>
	<tr>
		<td class="left" colspan="2">Issue Date</td>
		<td class="left" colspan="12"><?=$now_datetime?></td>
	</tr>

	<tr class="font12_bold" bgcolor="#CECED0">
		<td colspan="14" height="30" align="center">TOTAL</td>
	</tr>
	<tr class="font10_bold" bgcolor="#CECED0">
		<td width="100" height="30" align="center">Category</td>
		<td width="100" align="center" height="30">JAN</td>
		<td width="100" align="center" height="30">FEB</td>
		<td width="100" align="center" height="30">MAR</td>
		<td width="100" align="center" height="30">APR</td>
		<td width="100" align="center" height="30">MAY</td>
		<td width="100" align="center" height="30">JUN</td>
		<td width="100" align="center" height="30">JUL</td>
		<td width="100" align="center" height="30">AUG</td>
		<td width="100" align="center" height="30">SEP</td>
		<td width="100" align="center" height="30">OCT</td>
		<td width="100" align="center" height="30">NOV</td>
		<td width="100" align="center" height="30">DEC</td>
		<td width="100" align="center" >Total</td>
	</tr>
	<?php				
		if(is_array($total_list)) {		
			$color1 = "#D9D9D9";
			$color2 = "#FFFFFF";
					
			(float)$total_amount = 0;
			
			$total = array();
			
			for($i=0; $i<$total_cnt; $i++) {						
				$bgcolor = $color1;
					
				$total_amount += $total_list[$i]["amount"];	
				$total[0] += $total_list[$i]["01"];
				$total[1] += $total_list[$i]["02"];
				$total[2] += $total_list[$i]["03"];
				$total[3] += $total_list[$i]["04"];
				$total[4] += $total_list[$i]["05"];
				$total[5] += $total_list[$i]["06"];
				$total[6] += $total_list[$i]["07"];
				$total[7] += $total_list[$i]["08"];
				$total[8] += $total_list[$i]["09"];
				$total[9] += $total_list[$i]["10"];
				$total[10] += $total_list[$i]["11"];
				$total[11] += $total_list[$i]["12"];
				$total[12] += $total_list[$i]["amount"];		
	?>
	<tr>
		<td align="center" height="30"><?php echo $total_list[$i]["supplier_category"]?></td>
		<td class='quantity02 right'><?=($total_list[$i]["01"])? "$".number_format($total_list[$i]["01"],2,".",',') :"";?></td>
		<td class='quantity02 right'><?=($total_list[$i]["02"])? "$".number_format($total_list[$i]["02"],2,".",',') :"";?></td>
		<td class='quantity02 right'><?=($total_list[$i]["03"])? "$".number_format($total_list[$i]["03"],2,".",',') :"";?></td>
		<td class='quantity02 right'><?=($total_list[$i]["04"])? "$".number_format($total_list[$i]["04"],2,".",',') :"";?></td>
		<td class='quantity02 right'><?=($total_list[$i]["05"])? "$".number_format($total_list[$i]["05"],2,".",',') :"";?></td>
		<td class='quantity02 right'><?=($total_list[$i]["06"])? "$".number_format($total_list[$i]["06"],2,".",',') :"";?></td>
		<td class='quantity02 right'><?=($total_list[$i]["07"])? "$".number_format($total_list[$i]["07"],2,".",',') :"";?></td>
		<td class='quantity02 right'><?=($total_list[$i]["08"])? "$".number_format($total_list[$i]["08"],2,".",',') :"";?></td>
		<td class='quantity02 right'><?=($total_list[$i]["09"])? "$".number_format($total_list[$i]["09"],2,".",',') :"";?></td>
		<td class='quantity02 right'><?=($total_list[$i]["10"])? "$".number_format($total_list[$i]["10"],2,".",',') :"";?></td>
		<td class='quantity02 right'><?=($total_list[$i]["11"])? "$".number_format($total_list[$i]["11"],2,".",',') :"";?></td>
		<td class='quantity02 right'><?=($total_list[$i]["12"])? "$".number_format($total_list[$i]["12"],2,".",',') :"";?></td>
		<td class='quantity02 right'><?=($total_list[$i]["amount"])? "$".number_format($total_list[$i]["amount"],2,".",',') :"";?></td>
	</tr>			
	<?php		
		}
	}				
	?>
	<tr class="font12_bold" bgcolor="#CECED0"> 
		<td align="center" height="30">Total</td>
		<td class='quantity02 right'><?=($total[0])? "$".number_format($total[0],2,".",',') :"";?></td>
		<td class='quantity02 right'><?=($total[1])? "$".number_format($total[1],2,".",',') :"";?></td>
		<td class='quantity02 right'><?=($total[2])? "$".number_format($total[2],2,".",',') :"";?></td>
		<td class='quantity02 right'><?=($total[3])? "$".number_format($total[3],2,".",',') :"";?></td>
		<td class='quantity02 right'><?=($total[4])? "$".number_format($total[4],2,".",',') :"";?></td>
		<td class='quantity02 right'><?=($total[5])? "$".number_format($total[5],2,".",',') :"";?></td>
		<td class='quantity02 right'><?=($total[6])? "$".number_format($total[6],2,".",',') :"";?></td>
		<td class='quantity02 right'><?=($total[7])? "$".number_format($total[7],2,".",',') :"";?></td>
		<td class='quantity02 right'><?=($total[8])? "$".number_format($total[8],2,".",',') :"";?></td>
		<td class='quantity02 right'><?=($total[9])? "$".number_format($total[9],2,".",',') :"";?></td>
		<td class='quantity02 right'><?=($total[10])? "$".number_format($total[10],2,".",',') :"";?></td>
		<td class='quantity02 right'><?=($total[11])? "$".number_format($total[11],2,".",',') :"";?></td>
		<td class='quantity02 right'><?=($total[12])? "$".number_format($total[12],2,".",',') :"";?></td>
	</tr>
	
</table>
		

<form name="frm01" method="post">
<input type="hidden" name="supplier_id" value="<?=$supplier_id?>">
<input type="hidden" name="sday" value="<?=$sday?>">
<input type="hidden" name="smonth" value="<?=$smonth?>">
<input type="hidden" name="syear" value="<?=$syear?>">
<input type="hidden" name="eday" value="<?=$eday?>">
<input type="hidden" name="eyear" value="<?=$eyear?>">
<input type="hidden" name="emonth" value="<?=$emonth?>">
<input type="hidden" name="act" value="<?=$act?>">
</form>
<script>
function goExcelPrint() {
	var f = document.frm01;
	//f.target="_blank";
	f.action="report_supplier_summary_excel.php";
	f.submit();
}
</script>
</BODY>
</HTML>
<?php ob_flush(); ?>
