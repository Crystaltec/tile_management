<?
ob_start();
// Date in the past
header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); 
// always modified
header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
// HTTP/1.1
header ("Cache-Control: no-cache, must-revalidate");
// HTTP/1.0
header ("Pragma: no-cache"); 

session_start();

// 로그인 정보 확인 & 세션 유지
// 세션 유지
if(isset($_COOKIE["MEMBER_USERID"]) && !isset($_SESSION["userid"])) {
	$_SESSION["USER_ID"]		= $_COOKIE["MEMBER_USERID"];
	$_SESSION["USER_NAME"]		= $_COOKIE["MEMBER_USERNAME"];
	$_SESSION["A_LEVEL"]		= $_COOKIE["MEMBER_ALEVEL"];
	$_SESSION["O_LEVEL"]		= $_COOKIE["MEMBER_OLEVEL"];
	$_SESSION["PAYMENT_METHOD"] = $_COOKIE["MEMBER_PAYMENT_METHOD"];
	$_SESSION["PASS"] = $_COOKIE["MEMBER_PASS"];
}

if(!isset($_SESSION["USER_ID"])) {
	Header("Location:login.php");
	exit;
}
//echo $_COOKIE["MEMBER_USERID"] . "<br>";
//echo $_SESSION["userid"];
ob_flush();
?>