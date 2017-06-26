<?
ob_start();

$_BOARDID = $_REQUEST['boardid'];
include_once "include/logincheck.inc";
include_once "include/common.inc";
include_once "include/functions/database.php";
include_once "include/dbconn.inc";
include_once "include/myfunc.inc";
include_once "htmlinclude/head.php";

include_once "include/bbs_config.php";


$no = $_REQUEST["no"];
$name = htmlspecialchars($_REQUEST["name"], ENT_QUOTES);
$pass = $_REQUEST["pass"];
$contents = htmlspecialchars($_REQUEST["contents"], ENT_QUOTES);

$data = array("no"=>$no, "name"=>$name, "pass"=>$pass, "contents"=>$contents, "regdate"=>$now_datetimeano);
tep_db_perform($_BOARDID."_comments", $data);

header("Location:bbs_detail.php?boardid=".$_BOARDID."&no=".$no);
?>


