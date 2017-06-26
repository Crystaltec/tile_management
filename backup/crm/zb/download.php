<?

ob_start();
include "../include/common.inc";
include "../include/user_functions.inc";
include "../include/dbconn.inc";
include "include/board_config.inc";

	$down_file = $upload_root."/".$_GET["boardid"]."/".$_GET["fileExtra"];	// $upload_root은 절대경로.
	$down_mode = "attachment";								// attachment = 다운, inline = 화면에 출력 .
	$bin_mode = "r";												// r = ???, rb = ???.

	if(eregi("MSIE 6.0", $_SERVER["HTTP_USER_AGENT"]))
	{
		Header("Content-Length: ".filesize("$down_file"));
		Header("Content-Disposition: $down_mode; filename=".$_GET["fileName"]);
		Header("Content-type: application/octet-stream");
		Header("Content-Transfer-Encoding: binary");
		Header("Pragma: no-cache");
		Header("Expires: 0");
	}
	elseif(eregi("MSIE 5.5", $_SERVER["HTTP_USER_AGENT"]))
	{
		Header("Content-Length: ".filesize("$down_file"));
		Header("Content-Disposition: $down_mode; filename=".$_GET["fileName"]);
		Header("Content-type: application/octet-stream");
		Header("Content-Transfer-Encoding: binary");
		Header("Pragma: no-cache");
		Header("Expires: 0");
	}
	else
	{
		Header("Content-type: file/unknown");
		Header("Content-Length: ".filesize("$down_file"));
		Header("Content-Disposition: $down_mode; filename=".$_GET["fileName"]); ;
		Header("Content-Description: PHP3 Generated Data");
		Header("Pragma: no-cache");
		Header("Expires: 0");
	}

	if (is_file("$down_file"))
	{
		$fp = fopen("$down_file", "$bin_mode");
		if(!fpassthru($fp)) fclose($fp);
	}

// 다운로드 수 증가
$Query  = " select userfile_download from ".$_GET["boardid"]." where uid=".$_GET["uid"];
$cnn = mysql_query($Query) or exit(mysql_error());
$rst = mysql_fetch_assoc($cnn);
$arr_download = explode("|",$rst["userfile_download"]);
$arr_download[$_GET["an"]]++;
for($i=0;$i<count($arr_download)-1;$i++) $new_arr_download .= $arr_download[$i]."|";
$Query  = " update ".$_GET["boardid"]." set userfile_download='$new_arr_download' where uid=".$_GET["uid"];
mysql_query($Query) or exit(mysql_error());

ob_flush();