<?
ob_start();
include "../include/common.inc";
include "../include/user_functions.inc";
include "../include/dbconn.inc";
include "include/board_config.inc";

//checkform("get");
//echo "<p>";
//checkform("post");
//exit;

## Get폼값설정
$etc_key  = "";
$etc_key .= "&boardid=".$_GET["boardid"];
if($_GET["find_value"] && $_GET["find_key_1"]) $etc_key .= "&find_key_1=".$_GET["find_key_1"];
if($_GET["find_value"] && $_GET["find_key_2"]) $etc_key .= "&find_key_2=".$_GET["find_key_2"];
if($_GET["find_value"] && $_GET["find_key_3"]) $etc_key .= "&find_key_3=".$_GET["find_key_3"];
if($_GET["find_value"]) $etc_key .= "&find_value=".$_GET["find_value"];

## 접근권한 설정
$Query  = " SELECT commenter_passwd, commenter_id FROM ".$_GET["boardid"]."_comment where cmt_idx='".$_GET["cmt_idx"]."'";
$cmt_cnn = mysql_query($Query) or exit(mysql_error());
$cmt_rst = mysql_fetch_assoc($cmt_cnn);

if($Sync_level > 49 || $Sync_id == $cmt_rst["commenter_id"]){
	// Manager 이상이거나, 내가 적은거면 인증 없이 통과
}else{
	if($_POST["input_commenter_passwd"] && $_POST["input_commenter_passwd"] == $cmt_rst["commenter_passwd"]){
		// 비밀번호 맞으면 통과
	}else{
		echo"<script>alert('incorrect password.')</script>";
		echo"<script>history.go(-1)</script>";
		exit;
	}
}

$Query = "delete from ".$_GET["boardid"]."_comment WHERE cmt_idx=".$_GET["cmt_idx"];
mysql_query($Query) or die(mysql_error() . "오류 : DB에서 삭제하지 못했습니다.");

echo ("<meta http-equiv='Refresh' content='0; URL=/zb/view.php?uid=".$_GET["uid"].$etc_key."'>");
exit;
?>
<? ob_flush(); ?>
