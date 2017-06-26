<?php
ob_start();
include_once "include/logincheck.inc";
include_once "include/common.inc";
include_once "include/dbconn.inc";
include_once "include/myfunc.inc";

$list_Records = array();
$s_Cond = "";
$srch_userid = $_REQUEST["srch_userid"];

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


header("Content-type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=SUPPLIER_SUMMARY-".$now_datetime.".xls");
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
<table width='1200' class="report" cellspacing='0' >
	<tr>
		<td class="font12_bold left" colspan="2">REPORT</td>
		<td class="font12_bold left" colspan="12"><?=$title_txt?>&nbsp;</td>
	</tr>
	<tr>
		<td class="left" colspan="2">Issue Date</td>
		<td class="left" colspan="12"><?=$now_datetime?></td>
	</tr>

	<tr >
		<td colspan="14" height="30" align="center" class="font12_bold" bgcolor="#CECED0">Supplier Summary</td>
	</tr>
	<tr >
		<td width="100" height="30" align="center" class="font10_bold" bgcolor="#CECED0">Category</td>
		<td width="100" align="center" height="30" class="font10_bold" bgcolor="#CECED0">JAN</td>
		<td width="100" align="center" height="30" class="font10_bold" bgcolor="#CECED0">FEB</td>
		<td width="100" align="center" height="30" class="font10_bold" bgcolor="#CECED0">MAR</td>
		<td width="100" align="center" height="30" class="font10_bold" bgcolor="#CECED0">APR</td>
		<td width="100" align="center" height="30" class="font10_bold" bgcolor="#CECED0">MAY</td>
		<td width="100" align="center" height="30" class="font10_bold" bgcolor="#CECED0">JUN</td>
		<td width="100" align="center" height="30" class="font10_bold" bgcolor="#CECED0">JUL</td>
		<td width="100" align="center" height="30" class="font10_bold" bgcolor="#CECED0">AUG</td>
		<td width="100" align="center" height="30" class="font10_bold" bgcolor="#CECED0">SEP</td>
		<td width="100" align="center" height="30" class="font10_bold" bgcolor="#CECED0">OCT</td>
		<td width="100" align="center" height="30" class="font10_bold" bgcolor="#CECED0">NOV</td>
		<td width="100" align="center" height="30" class="font10_bold" bgcolor="#CECED0">DEC</td>
		<td width="100" align="center" class="font10_bold" bgcolor="#CECED0">Total</td>
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
	<tr> 
		<td align="center" height="30" class="font12_bold" bgcolor="#CECED0">Total</td>
		<td class='quantity02 right font12_bold' bgcolor="#CECED0"><?=($total[0])? "$".number_format($total[0],2,".",',') :"";?></td>
		<td class='quantity02 right font12_bold' bgcolor="#CECED0"><?=($total[1])? "$".number_format($total[1],2,".",',') :"";?></td>
		<td class='quantity02 right font12_bold' bgcolor="#CECED0"><?=($total[2])? "$".number_format($total[2],2,".",',') :"";?></td>
		<td class='quantity02 right font12_bold' bgcolor="#CECED0"><?=($total[3])? "$".number_format($total[3],2,".",',') :"";?></td>
		<td class='quantity02 right font12_bold' bgcolor="#CECED0"><?=($total[4])? "$".number_format($total[4],2,".",',') :"";?></td>
		<td class='quantity02 right font12_bold' bgcolor="#CECED0"><?=($total[5])? "$".number_format($total[5],2,".",',') :"";?></td>
		<td class='quantity02 right font12_bold' bgcolor="#CECED0"><?=($total[6])? "$".number_format($total[6],2,".",',') :"";?></td>
		<td class='quantity02 right font12_bold' bgcolor="#CECED0"><?=($total[7])? "$".number_format($total[7],2,".",',') :"";?></td>
		<td class='quantity02 right font12_bold' bgcolor="#CECED0"><?=($total[8])? "$".number_format($total[8],2,".",',') :"";?></td>
		<td class='quantity02 right font12_bold' bgcolor="#CECED0"><?=($total[9])? "$".number_format($total[9],2,".",',') :"";?></td>
		<td class='quantity02 right font12_bold' bgcolor="#CECED0"><?=($total[10])? "$".number_format($total[10],2,".",',') :"";?></td>
		<td class='quantity02 right font12_bold' bgcolor="#CECED0"><?=($total[11])? "$".number_format($total[11],2,".",',') :"";?></td>
		<td class='quantity02 right font12_bold' bgcolor="#CECED0"><?=($total[12])? "$".number_format($total[12],2,".",',') :"";?></td>
	</tr>
	
</table>
</BODY>
</HTML>
<?php ob_flush(); ?>
