<?php
ob_start();
include_once "include/logincheck.inc";
include_once "include/common.inc";
include_once "include/dbconn.inc";
include_once "include/myfunc.inc";

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

// TOTAL SUPPLIER QUERY ------------------------------------------------------------------------------------------


$sql = "SELECT o.orders_number, o.orders_date, o.supplier_id,  IF(o.material_id <> '0' and o.material_id <>  '',m.material_name,o.material_description) as material_name, s.supplier_name, s.supplier_category, sum(IF(o.material_id <> '0' and o.material_id <> '',o.material_price,IF(o.orders_tax = 'N', o.material_price,o.material_price/1.1))  * o.orders_inventory) as amount FROM orders o ";
$sql.="LEFT JOIN supplier s ";
$sql.="  ON o.supplier_id=s.supplier_id ";
$sql.="LEFT JOIN material m ";
$sql.="	 ON o.material_id=m.material_id ";
$sql.="WHERE 1=1 and orders_number <> ''    ". $s_Cond. $s_Cond02. " ";
$sql.=" GROUP BY o.supplier_id ORDER BY s.supplier_name";
//echo $sql."<p>";
$total_list = array();
$id_cnn = mysql_query($sql) or exit(mysql_error());
while($id_rst = mysql_fetch_assoc($id_cnn)) {
	$total_list = array_merge($total_list, array($id_rst));
}
mysql_free_result($id_cnn);
$total_cnt = count($total_list);

header("Content-type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=PROJECT_DETAIL-all.xls");
header("Content-Description: PHP5 Generated Data");
?>
<style type="text/css">
body {
	margin: 0;
	padding: 0;
	font:9pt arial;
	color:#666666;
	line-height:140%;

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
.dinput3 {background:#FFCF7B;font-weight:bold;color:#000000}
.dinput4 {background:#FFCACA;font-weight:bold;color:#000000}
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

.report {
	border:1px solid black;
	padding:0;
	margin:10px;
}
.report td {
	border:1px solid black;
}
</style>
<BODY topmargin="0" leftmargin="0">
<table width='800' class="report" cellspacing='0' >
	<tr>
		<td width="300" class="font12_bold">REPORT </td>
		<td class="font12_bold" colspan="2"><?=$title_txt?>&nbsp;</td>
	</tr>
	<tr>
		<td class="left"> Issue Date </td>
		<td class="left" colspan="2"><?=$now_datetime?></td>
	</tr>
	<tr >
		<td colspan="3" height="30" align="center" class="font12_bold" bgcolor="#CECED0">TOTAL</td>
	</tr>
	<tr >
		<td width="300" align="center" height="30" class="font10_bold" bgcolor="#CECED0">Supplier</td>
		<td width="100" align="center" height="30" class="font10_bold" bgcolor="#CECED0">Category</td>
		<td width="400" align="center" class='quantity0101' bgcolor="#CECED0">Amount</td>
	</tr>
	<?php				
		if(is_array($total_list)) {		
			$color1 = "#D9D9D9";
			$color2 = "#FFFFFF";
					
			(float)$total_amount = 0;

			for($i=0; $i<$total_cnt; $i++) {						
				$bgcolor = $color1;
						
				$total_amount += $total_list[$i]["amount"];
	?>
	<tr>
		<td align="center" height="30"><?=$total_list[$i]["supplier_name"]?></td>
		<td align="center" height="30"><?=$total_list[$i]["supplier_category"]?></td>
		<td align="right" class='quantity02' style="text-align:right;"><?=($total_list[$i]["amount"])? "$".number_format($total_list[$i]["amount"],2,".",',') :"";?></td>
	</tr>			
	<?php		
			}
		}				
	?>
	<tr >
		<td colspan="3" height="30" align="right" class="font12_bold" bgcolor="#CECED0"><span class="font12_bold" >TOTAL :</span>&nbsp;<span class="quantity_bold02" ><?=($total_amount)? "$".number_format($total_amount,2,".",',') : "";?></span></td>
	</tr>
</table>
</BODY>
</HTML>
<? ob_flush(); ?>
