<?
include_once "include/common.inc";
include_once "include/dbconn.inc";
$user_level=$_REQUEST["val"];
$sql = "SELECT userid, username, alevel, phone, fax, mobile, email, ABN, addr01, addr02, addr03, addr04, payment_method, dlevel FROM account WHERE alevel='$user_level'";
//echo $sql;
$result = mysql_query($sql) or exit(mysql_error());
$json_resp = "{\"users\":";
$json_resp.= "[";
while($rows = mysql_fetch_assoc($result)) {
	$userid = $rows["userid"];
	$username = $rows["username"];
	$alevel = $rows["alevel"];
	$phone = $rows["phone"];	
	
	$json_resp.="{\"userid\":\"".$userid."\",";
	$json_resp.="\"username\":\"".$username."\",";
	$json_resp.="\"alevel\":\"".$alevel."\",";
	$json_resp.="\"phone\":\"".$phone."\"},";
}
mysql_free_result($result);

$length = strlen($json_resp);
$json_resp = substr($json_resp, 0, $length-1);
$json_resp.="]}";
Header("Content-type: text/html");
echo $json_resp;
?>