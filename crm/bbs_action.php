<?

include_once "include/common.inc";
include_once "include/functions/database.php";
include_once "include/dbconn.inc";
include_once "include/myfunc.inc";
include_once "include/bbs_config.php";

$_BOARDID = $_REQUEST['boardid'];

$act = $_REQUEST["act"];
$no = $_REQUEST["no"];
//$pass = htmlspecialchars($_REQUEST["pass"], ENT_QUOTES);

$info_file_count = 1;

$sql = "SELECT COUNT(*) FROM ".$_BOARDID." WHERE no='".$no."'";
//echo $sql;
$result = mysql_query($sql) or exit(mysql_error());
$row = mysql_fetch_array($result);
mysql_free_result($result);
if($row[0] > 0) {
	$sql="SELECT no, ";
	for($i=0; $i < $info_file_count; $i++) {
		$sql.="filename_".$i.",";
	}
	$sql.=" regdate FROM ".$_BOARDID." WHERE no='".$no."'";

	$result = mysql_query($sql) or exit(mysql_error());
	$row = mysql_fetch_array($result);
	mysql_free_result($result);
	if($act == "delete") {
		//file delete
		for($i=0; $i < $info_file_count; $i++) {
			if($row["filename_".$i] != null && $row["filename_".$i] != "" ) {
				@system("rm -rf ".$upload_root."/".$_BOARDID."/*".$row["filename_".$i]."*");

				//unlink($upload_root."/".$_BOARDID."/".$row["filename_".$i]);
				//unlink($upload_root."/".$_BOARDID."/thumb_".$row["filename_".$i]);
				//unlink($upload_root."/".$_BOARDID."/thumb02_".$row["filename_".$i]);
			}
		}		

		//data delete
		$sql = "DELETE FROM ".$_BOARDID." WHERE no=".$no;
		mysql_query($sql);

		$json_resp.="{\"act\":\"board_delete\", \"result\":\"ok\"}";
	} else if($act == "modify") {
		$json_resp.="{\"act\":\"board_modify\", \"result\":\"ok\"}";
	}
} else if($pass == "MediaNara2008") {
	$sql="SELECT no, ";
	for($i=0; $i < $info_file_count; $i++) {
		$sql.="filename_".$i.",";
	}
	$sql.=" regdate FROM ".$_BOARDID." WHERE no=".$no." AND pass='".$pass."'";

	$result = mysql_query($sql) or exit(mysql_error());
	$row = mysql_fetch_array($result);
	mysql_free_result($result);

	if($act == "delete") {		
		//file delete
		for($i=0; $i < $info_file_count; $i++) {
			if($row["filename_".$i] != null && $row["filename_".$i] != "" ) {
				@system("rm -rf ".$upload_root."/".$_BOARDID."/*".$row["filename_".$i]."*");
				//unlink($upload_root."/".$_BOARDID."/".$row["filename_".$i]);
				//unlink($upload_root."/".$_BOARDID."/thumb_".$row["filename_".$i]);
				//unlink($upload_root."/".$_BOARDID."/thumb02_".$row["filename_".$i]);
			}
		}		

		//data delete
		$sql = "DELETE FROM ".$_BOARDID." WHERE no=".$no;
		mysql_query($sql);

		$json_resp.="{\"act\":\"board_delete\", \"result\":\"ok\"}";
	} else if($act == "modify") {
		$json_resp.="{\"act\":\"board_modify\", \"result\":\"ok\"}";
	}
} else {
	$json_resp.="{\"act\":\"board_".$act."\", \"result\":\"no\", \"sql\":\"".$sql."\"}";

}
echo $json_resp;
?>