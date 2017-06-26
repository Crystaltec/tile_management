<?
ob_start();
include_once "include/logincheck.inc";
include_once "include/common.inc";
include_once "include/dbconn.inc";
include_once "include/myfunc.inc";
include_once "htmlinclude/head.php";

$list_Records = array();
$s_Cond = "";
$srch_userid = $_REQUEST["srch_userid"];
$material_id = $_REQUEST["material_id"];

$sday = $_REQUEST["sday"];
$smonth = $_REQUEST["smonth"];
$syear = $_REQUEST["syear"];

$eday = $_REQUEST["eday"];
$emonth = $_REQUEST["emonth"];
$eyear = $_REQUEST["eyear"];

// Order Date Base, Deliver Date Base 결정 플래그.
$act = $_REQUEST["act"];

$start_date = $syear."-".$smonth."-".$sday;
$end_date = $eyear."-".$emonth."-".$eday;
$end_date = $end_date . " 23:59:59";
if($act == "obase" || $act == "") {
	$s_Cond .= " AND (o.orders_date >= '$start_date' AND o.orders_date <= '$end_date') ";
	$title_txt = "INVENTORY DETAIL (PERIOD : ". getAUDate($start_date)." ~ ". getAUDate($end_date) . ")";
} 

$s_Cond02 = "";
if($srch_usergroup != "") {
	//$s_Cond .= " AND (o.user_level='" .$srch_usergroup."') ";
}


// TOTAL INVENTORY QUERY ------------------------------------------------------------------------------------------

// STOCK
$sql = "SELECT o.orders_number, o.orders_date, o.project_id, o.material_id, m.material_name,m.material_adjustment, m.supplier_id, m.material_price, o.material_price,  sum(o.orders_inventory) as  inventory FROM orders o ";
$sql.="INNER JOIN material m";
$sql.="  ON o.material_id=m.material_id ";
$sql.="WHERE 1=1 ". $s_Cond. $s_Cond02 . " group by o.material_id ORDER BY m.material_name" ;
//echo $sql."<p>";
$total_list = array();

$id_cnn = mysql_query($sql) or exit(mysql_error());
$i=0;
while($id_rst = mysql_fetch_assoc($id_cnn)) {
	$id_rst["out_inventory"] = 0;
	
	$total_list = array_merge($total_list, array($id_rst));
	
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
			<table border="0" cellpadding="0" cellspacing="0" bordercolor="#CECED0" bordercolordark="white" width="800" class="font10">
			<tr>
				<td width="120" class="font12_bold">REPORT </td><td class="font12_bold">: <?=$title_txt?>&nbsp;<a href="javascript:goExcelPrint()"><img src="images/icon_excel.gif"></a></td>
			</tr>
			<tr>
				<td> Issue Date </td>
				<td>: <?=$now_datetime?></td>
				<td></td>
			</tr>
			</table>
			<!-- TOTAL INVENTORY -------------------------------------------------------------------------------------->
			<table border="0" cellpadding="0" cellspacing="1" width="800" bgcolor="#000000">
			<tr class="font12_bold" bgcolor="#CECED0"><td colspan=7 height="30" align="center">INVENTORY</td></tr>
			<tr class="font10_bold" bgcolor="#CECED0">
				<td width="350" align="center" height="30">Material Name(Supplier)</td>
				<td width="80" align="center">Unit Price</td>
				<td width="70" align="center">Adjustment</td>
				<td width="70" align="center">IN</td>
				<td width="70" align="center">OUT</td>
				<td width="70" align="center" class='quantity02'>STOCK</td>
				<td width="90" align="center" class='quantity02'>Valuation</td>
			</tr>
			<?				
				if(is_array($total_list)) {		
					$color1 = "#D9D9D9";
					$color2 = "#FFFFFF";
					
					$total_stock = 0;

					for($i=0; $i<$total_cnt; $i++) {						
									
						$bgcolor = $color1;
						$minusInventory = 0;
								$plusInventory = 0;

								$sql = "select IFNULL(sum(orders_inventory),0)  from orders where material_id='".$total_list[$i]["material_id"] ."' and ((new_order = 'N' AND orders_number = '') OR new_order = 'Y') AND project_id <> '0'  " ;
								$minusInventory = getRowCount2($sql);
							
								$sql2 = "select IFNULL(sum(orders_inventory),0) from orders where material_id='".$total_list[$i]["material_id"] . "' and orders_number <> '' ";
								$plusInventory = getRowCount2($sql2);


						$total_stock = $total_list[$i]["material_adjustment"] + $plusInventory - $minusInventory;
					
			?>
			<tr>
				<td bgcolor="<?=$bgcolor?>" height="25" class="left"><?=$total_list[$i]["material_name"]?>&nbsp;&nbsp;<?=($total_list[$i]["supplier_id"])? "(".getName('supplier',$total_list[$i]["supplier_id"]) .")": "" ?></td>
				<td bgcolor="<?=$bgcolor?>" class="right"><?php echo ($total_list[$i]["material_price"])? "$".$total_list[$i]["material_price"]: "";?></td>
				<td align="center" bgcolor="<?=$bgcolor?>" class="right"><?=($total_list[$i]["material_adjustment"]  <> "0.00")? $total_list[$i]["material_adjustment"] : ""; ?></td>
				
				<td align="center" bgcolor="<?=$bgcolor?>" class="right"><?=($plusInventory  <> "0.00")? $plusInventory : ""; ?></td>
				<td align="center" bgcolor="<?=$bgcolor?>" class="right"><?=($minusInventory  <> "0.00")? $minusInventory : ""; ?></td>
				<td align="center" bgcolor="<?=$bgcolor?>" class='quantity02 right'><?=($total_stock)? $total_stock :"";?></td>
				<td bgcolor="<?=$bgcolor?>" class="quantity02 right"><?php echo ($total_stock)? "$".number_format($total_stock*$total_list[$i]["material_price"],2,".",","): ""?></td>
			</tr>			
			<?		
						
					}
				}				
			?>
			</table>
			<!-- TOTAL INVENTORY END------------------------------------------------------------------------------------->
		</TD>
	</TR>
	
	</TABLE>
	</TD>
</TR>
</TABLE>
<form name="frm01" method="post">
<input type="hidden" name="material_id" value="<?=$material_id?>">
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
	f.action="report_inventory_sub_excel_all.php";
	f.submit();
}
</script>
</BODY>
</HTML>
<? ob_flush(); ?>
