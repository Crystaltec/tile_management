<?
include "../../include/common.inc";
include "../../include/dbconn.inc";
include "../../include/user_functions.inc";

## 접근권한 설정
if($Sync_level < 100) {
	echo"<script>alert('본 페이지를 열람할 권한이 없습니다.')</script>";
	echo"<script>history.go(-1)</script>";
	exit;
}

// Get폼값설정
$etc_key  = "";
if($find_value) $etc_key .= "&find_key=".$_GET["find_key"];
if($find_value) $etc_key .= "&find_value=".$_GET["find_value"];
if($sort_value) $etc_key .= "&sort_key=".$_GET["sort_key"];
if($sort_value) $etc_key .= "&sort_value=".$_GET["sort_value"];

## 자료파일을 지운다#
system("rm -rf ".$upload_root."/".$_GET["boardid"]."/thumb_zb_*");
system("rm -rf ".$upload_root."/".$_GET["boardid"]."/zb_*");

## 테이블 삭제
mysql_query("DROP TABLE ".$_GET["boardid"]);

## 리플데이타 삭제
mysql_query("DROP TABLE ".$_GET["boardid"]."_comment");

## 마스타 테이블에서 삭제
mysql_query("DELETE FROM zb WHERE boardid='".$_GET["boardid"]."'") or die(mysql_error());


## 목록 출력화면으로 이동한다
echo("<meta http-equiv='Refresh' content='0; URL=boardlist.php?startPage=$startPage$etc_key'>");
?>
