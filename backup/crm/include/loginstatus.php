<?

// �α��� ���� Ȯ�� & ���� ����
// ���� ����
if($_COOKIE["userid"] && !isset($_SESSION["userid"])) {
	$_SESSION["userid"]		= $_COOKIE["userid"];
	$_SESSION["username"]	= $_COOKIE["username"];
	$_SESSION["alevel"]		= $_COOKIE["alevel"];
	$_SESSION["olevel"]		= $_COOKIE["olevel"];
	$_SESSION["payment_method"] = $_COOKIE["payment_method"];
}
?>