<?

// 로그인 정보 확인 & 세션 유지
// 세션 유지
if($_COOKIE["userid"] && !isset($_SESSION["userid"])) {
	$_SESSION["userid"]		= $_COOKIE["userid"];
	$_SESSION["username"]	= $_COOKIE["username"];
	$_SESSION["alevel"]		= $_COOKIE["alevel"];
	$_SESSION["olevel"]		= $_COOKIE["olevel"];
	$_SESSION["payment_method"] = $_COOKIE["payment_method"];
}
?>