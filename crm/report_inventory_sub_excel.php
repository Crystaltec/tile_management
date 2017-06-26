<?php
ob_start();
include_once "include/logincheck.inc";
include_once "include/common.inc";
include_once "include/dbconn.inc";
include_once "include/myfunc.inc";

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
	$s_Cond .= " AND (o.user_level='" .$srch_usergroup."') ";
}

//echo $material_id;
if($material_id != "") {	
	$s_Cond02 = " AND (m.material_id='" .$material_id."') ";	
}

// TOTAL IVENTORY QUERY ------------------------------------------------------------------------------------------

// IN STOCK
$sql = "SELECT o.orders_number, o.orders_date, o.project_id, m.material_id, m.material_name,m.material_adjustment, o.supplier_id, o.material_price,  sum(o.orders_inventory) as orders_inventory FROM orders o ";
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

header("Content-type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=INVENTORY_DETAIL-".$total_list[0]['material_name'].".xls");
header("Content-Description: PHP5 Generated Data");
?>
<HTML>
<HEAD>
<style type="text/css">
body {
	margin: 0;
	padding: 0;
	font:9pt arial;
	color:#666666;
	line-height:140%;

	/*
	scrollbar-3dlight-color:#7080C0; 
	scrollbar-arrow-color:#7080C0; 
	scrollbar-base-color:#7080C0; 
	scrollbar-darkshadow-color:#1F2C70; 
	scrollbar-face-color:#1F2C70; 
	scrollbar-highlight-color:#1F2C70; 
	scrollbar-shadow-color:#7080C0;
	*/
}

a {
	color:#666666;
	line-height:140%;
	text-decoration: none;
}

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

img {
	border:0;
}

form {
	margin:0;
}

.input {
	border:1px solid #8B9CB4;
	background-Color:#FFFFFF;
	font:9pt Tahoma;
	color:#000000;
	margin:1px;
}

.textarea {
	border:1px solid #8B9CB4;
	background-Color:#FFFFFF;
	font:9pt Tahoma;
	color:#000000;
	margin:1px;
}

.button {
	border:1px solid #8B9CB4;
	background-Color:E0E0E0;
	font:9pt Tahoma;
	color:#000000;
	margin:1px;
}


.button02 {	
	background-Color:E0E0E0;
	font:10pt Tahoma;
	color:#000000;
	margin:1px;
}

.tr_bold { font-weight:bold }
.tr_bold02 { font-weight:bold;color:#940000;}
.tr_normal02 { font-weight:normal;color:black; }
.td_fontcolor1 {color:00111B}


.price {font-family:arial;color:#E83100;font-weight:bold};
.price2 {
	color:#B60000;
	font-size:9pt;
	padding-right:4px;
	text-align:right;
	font-weight:bold;	
}
.pricenb {font-family:arial;color:#E83100;};
.pricedis {font-family:arial;color:#034074;font-weight:bold};
.menu01 {font-size:10pt}

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

.font10 {font-size:10pt;font-family:arial;color:#000000;font-weight:normal};
.font10_bold {font-size:10pt;font-family:arial;color:#000000;font-weight:bold};
.font12_bold {font-size:12pt;font-family:arial;color:#000000;font-weight:bold};

.dinput {background:#D6D6D6;font-weight:bold;color:#000000}
.dinput2 {background:#E8E8E8;font-weight:normal;color:#000000}
.dinput3 {background:FFCF7B;font-weight:bold;color:#000000}
.dinput4 {background:FFCACA;font-weight:bold;color:#000000}
.dinput5 {background:#E7E3E7;font-weight:bold;color:#000000}
.dinput6 {background:#FFFFFF;font-weight:bold;color:#000000}
.dinput7 {background:#DEDFDE;font-weight:bold;color:#000000}
.dinput8 {background:#C6C7C6;font-weight:bold;color:#000000}

.font11_bold {font-size:11pt;font-family:Tahoma;color:#1A426A;font-weight:bold};

.padd01 {padding:0 0 0 4}

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
		width: 200px;
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
</style>
</HEAD>
<BODY topmargin="0" leftmargin="0">
<TABLE border="0" cellpadding="0" cellspacing="0" width="1000" align="left" bordercolor="#CECED0" bordercolordark="white">
<TR>
	<TD style="padding:10 10 10 10">
	<TABLE border="0" cellpadding="0" cellspacing="0" width="800" align="left" bordercolor="#CECED0" bordercolordark="white">
	<TR>
		<TD valign="top" style="padding:10 10 10 10">
			<table border="0" cellpadding="0" cellspacing="0" bordercolor="#CECED0" bordercolordark="white" width="800" class="font10">
			<tr>
				<td width="120" class="font12_bold">REPORT </td><td colspan="2" width='680' class="font12_bold">: <?=$title_txt?>&nbsp;</td>
			</tr>
			<tr>
				<td width="120"> Issue Date </td>
				<td width="120">: <?=$now_datetime?></td>
				<td width="560"></td>
			</tr>
			</table>
			<!-- TOTAL INVENTORY -->
			<table border="0" cellpadding="0" cellspacing="1" width="800" bgcolor="#000000">
			<tr class="font12_bold" bgcolor="#CECED0"><td colspan="6" height="30" align="center"><?=$total_list[0]["material_name"]?>&nbsp;&nbsp;<?=($total_list[0]["supplier_id"])? "(".getName('supplier',$total_list[0]["supplier_id"]) .")": "" ?></td></tr>
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
				<td align="left" bgcolor="<?=$color1?>">Adjustment</td>
			</tr>	
			
			<?
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
				<td align="center" bgcolor="<?=$bgcolor?>"><?=($in_stock  <> "0.00")? $in_stock : ""; ?></td>
				<td align="center" bgcolor="<?=$bgcolor?>"><?=($out_stock  <> "0.00")? $out_stock : ""; ?></td>
				<td align="center" bgcolor="<?=$bgcolor?>" class='quantity02'><?=($total_stock)? $total_stock :"";?></td>
				<td align="left" bgcolor="<?=$bgcolor?>"><?=$project_name?></td>
			</tr>			
			<?		
						
					}
				}				
			?>
			</table>
			<!-- TOTAL INVENTORY END -->
		</TD>
	</TR>
	
	</TABLE>
	</TD>
</TR>
</TABLE>
</BODY>
</HTML>
<? ob_flush(); ?>
