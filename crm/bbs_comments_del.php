<? 
include "include/bbs_app_top.php";

$idx = $_REQUEST["idx"];
$boardid = $_REQUEST["boardid"];
$pass = htmlspecialchars($_REQUEST["pass"], ENT_QUOTES);

$sql = "SELECT COUNT(*) FROM ".$boardid."_comments WHERE idx=".$idx." AND pass='".$pass."'";

$result = mysql_query($sql);
$row = mysql_fetch_array($result);
$cnt = $row[0];
if($cnt > 0 ) {
	// if password is correct
	$sql = "DELETE FROM ".$boardid."_comments WHERE idx=".$idx;
	mysql_query($sql);
	$json_resp.="{\"act\":\"board_comment_delete\", \"result\":\"ok\"}";
} else {
	$json_resp.="{\"act\":\"board_comment_delete\", \"result\":\"no\"}";
}
echo $json_resp;
?>