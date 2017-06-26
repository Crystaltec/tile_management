<?
ob_start();
include "../include/common.inc";
include "../include/user_functions.inc";
include "../include/dbconn.inc";
include "include/board_config.inc";

## Get폼값설정
$etc_key  = "";
$etc_key .= "&boardid=".$_GET["boardid"];
$etc_key .= "&link_style=".$_GET["link_style"];
if($_GET["find_value"] && $_GET["find_key_1"]) $etc_key .= "&find_key_1=".$_GET["find_key_1"];
if($_GET["find_value"] && $_GET["find_key_2"]) $etc_key .= "&find_key_2=".$_GET["find_key_2"];
if($_GET["find_value"] && $_GET["find_key_3"]) $etc_key .= "&find_key_3=".$_GET["find_key_3"];
if($_GET["find_value"]) $etc_key .= "&find_value=".$_GET["find_value"];

## 접근권한 설정
$Query  = " SELECT boarder_id, boarder_passwd FROM ".$_GET["boardid"]." where uid=".$_GET["uid"];
$cnn = mysql_query($Query) or die(mysql_error());
$Records = mysql_fetch_assoc($cnn);

if($Sync_level > 49 || $Sync_id==$Records["boarder_id"]){
	// 인증 없이 통과
}else{
	$input_deleter_passwd = $_REQUEST["input_deleter_passwd"];
	if($input_deleter_passwd && $input_deleter_passwd == $Records["boarder_passwd"]){
		// 비밀번호 맞으면 통과
	}else{
		echo"<script>alert('password is incorrect.')</script>";
		echo"<script>history.go(-1)</script>";
		exit;
	}
}

## 첨부파일 삭제
@system("rm -rf ".$upload_root."/".$_GET["boardid"]."/".$_GET["boardid"]."_".$_GET["uid"]."_*");
@system("rm -rf ".$upload_root."/".$_GET["boardid"]."/thumb_".$_GET["boardid"]."_".$_GET["uid"]."_*");

## 데이터베이스에 입력값을 삭제한다.
$Query = "delete from ".$_GET["boardid"]."_comment WHERE uid=".$_GET["uid"];
mysql_query($Query) or die(mysql_error() . "오류 : DB에서 삭제하지 못했습니다.");

$Query = "delete from ".$_GET["boardid"]." WHERE uid=".$_GET["uid"];
mysql_query($Query) or die(mysql_error() . "오류 : DB에서 삭제하지 못했습니다.");

if($_GET["link_style"]=="open") echo "<meta http-equiv='Refresh' content='0; URL=open.php?startPage=".$_GET["startPage"].$etc_key."'>";
else echo "<meta http-equiv='Refresh' content='0; URL=list.php?$etc_key'>";
exit;
?>
<? ob_flush(); ?>
