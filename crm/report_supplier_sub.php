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
$supplier_id = $_REQUEST["supplier_id"];

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
	$title_txt = "SUPPLIER DETAIL (PERIOD : ". getAUDate($start_date)." ~ ". getAUDate($end_date) . ")";
} 

$s_Cond02 = "";

//echo $supplier_id;
if($supplier_id != "") {	
	$s_Cond02 = " AND (s.supplier_id='" .$supplier_id."') ";	
}

// TOTAL SUPPLIER QUERY ------------------------------------------------------------------------------------------


$sql = "SELECT o.orders_number, o.orders_date, o.supplier_id, IF(o.material_id <> '0' and o.material_id <>  '',m.material_name,o.material_description) as material_name, s.supplier_name, s.supplier_category, IF(o.material_id <> '0' and o.material_id <> '',o.material_price,IF(o.orders_tax = 'N', o.material_price,o.material_price/1.1)) AS material_price, o.orders_inventory FROM orders o ";
$sql.="LEFT JOIN supplier s ";
$sql.="  ON o.supplier_id=s.supplier_id ";
$sql.="LEFT JOIN material m ";
$sql.="	 ON o.material_id=m.material_id ";
$sql.="WHERE 1=1 AND orders_number <> ''   ". $s_Cond. $s_Cond02. " ";
$sql.=" ORDER BY s.supplier_name";
//echo $sql."<p>";
$total_list = array();
$id_cnn = mysql_query($sql) or exit(mysql_error());
while($id_rst = mysql_fetch_assoc($id_cnn)) {
	$total_list = array_merge($total_list, array($id_rst));
}
mysql_free_result($id_cnn);
$total_cnt = count($total_list);

?>
<BODY topmargin="0" leftmargin="0">
<table width='800' class="report" cellspacing='0' >
	<tr>
		<td width="200" class="font12_bold">REPORT </td>
		<td class="font12_bold" colspan="3"><?=$title_txt?>&nbsp;<a href="javascript:goExcelPrint()"><img src="images/icon_excel.gif"></a></td>
	</tr>
	<tr>
		<td class="left"> Issue Date </td>
		<td class="left" colspan="3"><?=$now_datetime?></td>
	</tr>
	<tr class="font12_bold" bgcolor="#CECED0">
		<td colspan="4" height="30" align="center"><?=$total_list[0]["supplier_name"]?>(<?php echo $total_list[0]["supplier_category"]?>)</td>
	</tr>
	<tr class="font10_bold" bgcolor="#CECED0">
		<td width="200" align="center" height="30">Date</td>
		<td width="300" align="center" height="30">Material Name</td>
		<td width="100" align="center">Qty</td>
		<td width="200" align="center" class='quantity0101'>Amount</td>
	</tr>
	<?php				
		if(is_array($total_list)) {		
			$color1 = "#D9D9D9";
			$color2 = "#FFFFFF";
					
			(float)$total_amount = 0;

			for($i=0; $i<$total_cnt; $i++) {						
				$bgcolor = $color1;
				
				$amount = 0;
				
				$amount = $total_list[$i]["material_price"] * $total_list[$i]["orders_inventory"];

				$total_amount += $amount;
	?>
	<tr>
		<td align="center" height="30"><?=getAUDate($total_list[$i]["orders_date"])?></td>
		<td align="center" ><?=$total_list[$i]["material_name"]?></td>
		<td align="center" ><?=($total_list[$i]["orders_inventory"])? $total_list[$i]["orders_inventory"] : ""; ?></td>
		<td align="right" class='quantity02' style="text-align:right;"><?=($amount)? "$".number_format($amount,2,".",',') :"";?></td>
	</tr>			
	<?php		
		}
	}				
	?>
	<tr class="font12_bold" bgcolor="#CECED0">
		<td colspan="4" height="30" align="right"><span class="font12_bold" >TOTAL :</span>&nbsp;<span class="quantity_bold02" ><?=($total_amount)? "$".number_format($total_amount,2,".",',') : "";?></span></td>
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
<input type="hidden" name="ehour" value="<?=$ehour?>">
<input type="hidden" name="emin" value="<?=$emin?>">
<input type="hidden" name="act" value="<?=$act?>">
</form>
<script>
function goExcelPrint() {
	var f = document.frm01;
	//f.target="_blank";
	f.action="report_supplier_sub_excel.php";
	f.submit();
}
</script>
</BODY>
</HTML>
<? ob_flush(); ?>
