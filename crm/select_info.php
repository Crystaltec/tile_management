<?
ob_start();
include_once "include/common.inc";
include_once "include/dbconn.inc";
$req_userid=$_GET["id"];

$sql = "SELECT * FROM project WHERE project_id='$req_userid'";
//echo $sql;
$result = mysql_query($sql) or exit(mysql_error());
while($rows = mysql_fetch_assoc($result)) {
	$project_id = $rows["project_id"];
	$project_name = $rows["project_name"];
	$phone = $rows["project_phone_number"];
	$address = $rows["project_address"];
	$suburb = $rows["project_suburb"];
	$state = $rows["project_state"];
	$postcode = $rows["project_postcode"];
	
}
mysql_free_result($result);
//echo $fax;

$json_resp = "{";
$json_resp.="\"deliverto_project\":\"".$project_id."\",";
$json_resp.="\"deliverto_name\":\"".$project_name."\",";
$json_resp.="\"phone\":\"".$phone."\",";
$json_resp.="\"address\":\"".$address."\",";
$json_resp.="\"state\":\"".$state."\",";
$json_resp.="\"suburb\":\"".$suburb."\",";
$json_resp.="\"postcode\":\"".$postcode."\"}";
echo $json_resp;
ob_flush();
?>