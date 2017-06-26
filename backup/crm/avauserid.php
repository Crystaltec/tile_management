<?
ob_start();
include_once "include/common.inc";
include_once "include/dbconn.inc";
include_once "include/myfunc.inc";

$userid=$_REQUEST["userid"];

$sql = "SELECT COUNT(*) FROM account WHERE userid='".$userid."'";
$row = getRowCount($sql);
echo $row[0];
ob_flush();
?>