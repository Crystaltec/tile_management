<?
include_once "include/common.inc";
include_once "include/dbconn.inc";
$req_userid=$_GET["userid"];

$sql = "SELECT userid, username, alevel, phone, fax, mobile, email, ABN, addr01, addr02, addr03, addr04, payment_method, dlevel FROM account WHERE userid='$req_userid'";
//echo $sql;
$result = mysql_query($sql) or exit(mysql_error());
while($rows = mysql_fetch_assoc($result)) {
	$userid = $rows["userid"];
	$username = str_replace('"', '', $rows["username"]);
	$alevel = $rows["alevel"];
	$phone = $rows["phone"];
	$mobile = $rows["mobile"];
	$fax = $rows["fax"];
	$email = $rows["email"];
	$abn = $rows["ABN"];
	$addr = $rows["addr01"] . " " . $rows["addr02"]." ".$rows["addr03"]." ".$rows["addr04"];
	$payment_method = $rows["payment_method"];
	$dlevel = $rows["dlevel"];
}
mysql_free_result($result);
//echo $fax;

$json_resp = "{";
$json_resp.="\"userid\":\"".$userid."\",";
$json_resp.="\"username\":\"".$username."\",";
$json_resp.="\"alevel\":\"".$alevel."\",";
$json_resp.="\"phone\":\"".$phone."\",";
$json_resp.="\"mobile\":\"".$mobile."\",";
$json_resp.="\"fax\":\"".$fax."\",";
$json_resp.="\"abn\":\"".$abn."\",";
$json_resp.="\"email\":\"".$email."\",";
$json_resp.="\"paymentm\":\"".$payment_method."\",";
$json_resp.="\"dlevel\":\"".$dlevel."\",";
$json_resp.="\"addr\":\"".$addr."\"}";
echo $json_resp;
?>