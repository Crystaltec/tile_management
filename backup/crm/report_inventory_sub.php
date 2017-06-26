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

//echo $material_id;
if($material_id != "") {	
	$s_Cond02 = " AND (m.material_id='" .$material_id."') ";	
}

// TOTAL IVENTORY QUERY ------------------------------------------------------------------------------------------

// IN STOCK
$sql = "SELECT o.orders_number, o.orders_date, o.project_id, m.material_id, m.material_name, m.material_adjustment, o.material_price, sum(o.orders_inventory) as orders_inventory, o.supplier_id FROM orders o ";
$sql.="INNER JOIN material m";
$sql.="  ON o.material_id=m.material_id ";
$sql.="WHERE 1=1 ". $s_Cond. $s_Cond02. " ";
$sql.="GROUP BY m.material_id, orders_date, orders_number, o.material_price ORDER BY m.material_name";
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
			<tr class="font12_bold" bgcolor="#CECED0"><td colspan=6 height="30" align="center"><?=$total_list[0]["material_name"]?>&nbsp;<?=($total_list[0]["supplier_id"])? "(".getName('supplier',$total_list[0]["supplier_id"]) .")": "" ?></td></tr>
			<tr class="font10_bold" bgcolor="#CECED0">
				<td width="100" align="center" height="30">Date</td>
				<td width="100" align="center" height="30">PO</td>
				<td width="100" align="center">IN</td>
				<td width="100" align="center">OUT</td>
				<td width="100" align="center" class='quantity0101'>STOCK</td>
				<td width="300" align="center">PROJECT</td>
			</tr>
			<?				
				if(is_array($total_list)) {		
					$color1 = "#D9D9D9";
					$color2 = "#FFFFFF";
					
					$total_stock = 0;

					// adjustment
					if ( $total_list[0]["material_adjustment"]) {
						$total_stock = $total_list[0]["material_adjustment"];
					?>
					<tr>
				<td align="center" bgcolor="<?=$color1?>" height="25">&nbsp;</td>
				<td align="center" bgcolor="<?=$color1?>" height="25">&nbsp;</td>
				<td align="center" bgcolor="<?=$color1?>">&nbsp;</td>
				<td align="center" bgcolor="<?=$color1?>">&nbsp;</td>
				<td align="center" bgcolor="<?=$color1?>" class='quantity02'><?=($total_stock)? $total_stock :"";?></td>
				<td align="left" bgcolor="<?=$color1?>" style="padding-left:3px;">Adjustment</td>
			</tr>	
			
			<?php
					}

					for($i=0; $i<$total_cnt; $i++) {						
						$bgcolor = $color1;
						
						$in_stock = 0;
						$out_stock = 0;

								
						if ($total_list[$i]["orders_number"]) {
							$in_stock = $total_list[$i]["orders_inventory"] ;
							$total_stock += $in_stock;
							$project_name = "STOCK ORDER";
							
							if($total_list[$i]["project_id"]) {
								$out_stock = $in_stock;
								$total_stock -= $out_stock;
								$project_name = getName("project",$total_list[$i]["project_id"]);
							}
							
						}else {
							$out_stock = $total_list[$i]["orders_inventory"] ;
							$total_stock -= $out_stock;
							$project_name = getName("project",$total_list[$i]["project_id"]);
							
							
						}
							
			?>
			<tr>
				<td align="center" bgcolor="<?=$bgcolor?>" height="25"><?=getAUDate($total_list[$i]["orders_date"])?></td>
				<td align="center" bgcolor="<?=$bgcolor?>" height="25"><?=$total_list[$i]["orders_number"]?></td>
				<td align="center" bgcolor="<?=$bgcolor?>"><?=($in_stock <> "0.00")? $in_stock : ""; ?></td>
				<td align="center" bgcolor="<?=$bgcolor?>"><?=($out_stock <> "0.00")? $out_stock : ""; ?></td>
				<td align="center" bgcolor="<?=$bgcolor?>" class='quantity02'><?=($total_stock)? $total_stock :"";?></td>
				<td align="left" bgcolor="<?=$bgcolor?>" style="padding-left:3px;"><?=$project_name?></td>
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
	f.action="report_inventory_sub_excel.php";
	f.submit();
}
</script>
</BODY>
</HTML>
<? ob_flush(); ?>
