<?
include_once "include/common.inc";
include_once "include/dbconn.inc";
$user_level=$_REQUEST["val"];
$sql = "SELECT userid, username, alevel, phone, fax, mobile, email, ABN, addr01, addr02, addr03, addr04, payment_method, dlevel FROM account WHERE alevel='$user_level'";
//echo $sql;
$result = mysql_query($sql) or exit(mysql_error());

$r_xml = "<root>";
while($rows = mysql_fetch_assoc($result)) {
	$userid = $rows["userid"];
	$username = $rows["username"];
	$alevel = $rows["alevel"];
	$phone = $rows["phone"];	
		$r_xml.="<item>";
		$r_xml.="<userid>".$userid."</userid>";
		$r_xml.="<username>".$username."</username>";
		$r_xml.="<alevel>".$alevel."</alevel>";
		$r_xml.="<phone>".$phone."</phone>";
		$r_xml.="</item>";
}
mysql_free_result($result);

$r_xml.= "</root>";
Header("Content-type: text/xml;charset=utf-8");
echo $r_xml;
?>