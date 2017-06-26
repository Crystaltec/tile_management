<?PHP
include_once "include/logincheck.inc";
include_once "include/common.inc";
include_once "include/dbconn.inc";
include_once "include/myfunc.inc";

if (!$_POST) {
	echo "error";
	exit;
}

$cid = $_POST["cid"];
$o_pass = $_POST["copass"];
$pass = $_POST["cpass"];

$sql = " SELECT count(*) FROM account WHERE userid = '" . $cid . "' AND hashed_pass = '" . md5($o_pass.$salt) ."' ";
$result = getRowCount($sql);
if ($result[0] == 1) {
	$sql = " UPDATE account SET hashed_pass = '" .md5($pass.$salt). "' WHERE userid = '" .$cid . "' ";	
	pQuery($sql,"update");
	echo "SUCCESS";
} else {
	echo "error";
}
?>