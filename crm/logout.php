<?php
session_start();
session_unset();
session_destroy();
$_SESSION = array(); 
@setcookie("MEMBER_USERID","", -1, "/");
@setcookie("MEMBER_USERNAME","", -1, "/");
@setcookie("MEMBER_ALEVEL","", -1, "/");
@setcookie("MEMBER_OLEVEL","", -1, "/");
@setcookie("MEMBER_PASS", "",-1,"/");
@setcookie("leftmenu","", -1, "/");
		
?>
<script language="Javascript">
location.href="login.php";
</script>