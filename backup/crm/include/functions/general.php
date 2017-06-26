<?php
function get_price_status() {
	$sql = "SELECT sts FROM price_sts";
	$result = mysql_query($sql) or exit(mysql_error());
	$rows = mysql_fetch_array($result);
	mysql_free_result($result);
	return $rows[0];
}

function get_ano_date($strdate) {	
	$sdate = $strdate;
	$syear = substr($sdate, 0,4);
	$smonth = substr($sdate, 5,2);
	$sday = substr($sdate, 8,2);
	return $sday."-".$smonth."-".$syear;
}
?>