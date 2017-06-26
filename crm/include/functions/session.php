<?php
function get_session_val($name) {
	return $_SESSION[$name];	
}

function check_session() {
	if(isset($_COOKIE["MADMIN_ID"]) && !isset($_SESSION["admin_id"])) {
		$_SESSION["admin_id"]		= $_COOKIE["MADMIN_ID"];
		$_SESSION["admin_name"]	= $_COOKIE["MADMIN_NAME"];	
	}
}

function check_login() {
	if(!isset($_SESSION["admin_id"])) {
		echo "<script>alert('The User Session is Timeout! Please, Re Loign!');location.replace('/admin01/')</script>";
		exit;
	}
}
?>