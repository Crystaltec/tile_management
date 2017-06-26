<?php
ob_start();
include_once "include/common.inc";
include_once "include/dbconn.inc";
include_once "include/myfunc.inc";

if($_GET["cmd"]=="exec"){

$Query = "DELETE FROM cart WHERE user_id='".$_POST["userid"]."'";
mysql_query($Query) or exit(mysql_error());

$Member = array();
$Row = array();

$Member["userid"]				= NULL;
$Member["hashed_pass"]					= NULL;
$Member["username"]			= NULL;
$Member["alevel"]				= NULL;

$Query  = "SELECT " . selectQuery($Member, 'account');
$Query .= " FROM account";
$Query .= " WHERE userid = '".$_POST["userid"]."' and status = 'Y' ";
//echo $Query . "<br>";
$cnn = mysql_query($Query) or exit(mysql_error());
$Row = mysql_fetch_assoc($cnn);
$countRows = mysql_num_rows($cnn);

if($Row["hashed_pass"] == md5(md5($_POST["userpw"]).$salt)) {	
		$_SESSION["USER_ID"] = $Row["userid"];
		$_SESSION["USER_NAME"] = $Row["username"];
		$_SESSION["A_LEVEL"] = $Row["alevel"];
		$_SESSION["PASS"] = $Row["hashed_pass"];

		setcookie("MEMBER_USERID", $Row["userid"], 0, "/");
		setcookie("MEMBER_USERNAME", $Row["username"], 0, "/");
		setcookie("MEMBER_ALEVEL", $Row["alevel"], 0, "/");
		setcookie("MEMBER_PASS", $Row["hashed_pass"],0,"/");
		/*
		session_register("userid");
		session_register("username");
		session_register("accoutlevel");
		session_register("orderlevel");	
		
		$userid			= $Row["userid"];
		$username		= $Row["username"];
		$accoutlevel	= $Row["alevel"];
		$orderlevel		= $Row["olevel"];
		*/		
		
		
		//echo $Row["userid"];;
		//echo $Row["username"];
		//echo $Row["alevel"];
		//echo $Row["olevel"];
		//exit;
		

		Header("Location: index.php");
		exit;
		
}else{
		
		if($countRows<1) {
		echo"<SCRIPT>alert('Non registered Userid.'); history.back();</SCRIPT>";
		} else {
		echo"<SCRIPT>alert('Incorrect password.'); history.back();</SCRIPT>";
		}
	}
}


?>
<? include_once "htmlinclude/head.php"; ?>
<script language="javascript">
<!--//
function checkForm(){
	if(document.F['userid'].value =="") { window.alert('Please, Input userid!'); document.F['userid'].focus(); return false }
	if(document.F['userpw'].value =="") { window.alert('Please, Input password'); document.F['userpw'].focus(); return false }
}


//-->
</script>
<body onload="document.F['userid'].focus();">
<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0" align="center" >
	<tr>				
		<td width="100%" valign="top" align="center">					
			<table border="0" cellpadding="0" cellspacing="0" width="780" height="500">
			<tr>
			<td width="12"></td>
			<td valign="top" style="padding-right:50px;" >						
			<div style="padding-top:30px"><br>
				
				
			</div>
				<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center" style="height: 416px">
				<tr>
				<td align="center" valign="middle" style="height: 305px">
				<table cellspacing="0" cellpadding="0" border="0">
				<form method="post" name="F" action = "<?=$_SERVER[PHP_SELF]?>?cmd=exec" onsubmit="return checkForm();">
				<tr>
					<td><img src="images/bg_login055.gif" width="359" height="293"></td>
					<td valign="top">
						<table border="0" cellpadding="0" cellspacing="0" style="font-size:9pt">
						<tr><td width="20" >						
						<a class="info" href="javascript:void(0)"><span><table border="0" cellpadding="2" cellspacing="1" >
	<tr><td height="22" width="200" style="color:white">Tip</td></tr>
		</table></span></a></td><td width="70">
							<span style="color:#666666;line-height:140%;font-size:8pt"> 
							LOGIN ID</span></td><td width="100">
							<input type="text" name="userid" size="20" class="input" tabindex="1"></td></tr>
						<tr><td width="20"><a class="info" href="javascript:void(0)"><span><table border="0" cellpadding="2" cellspacing="1" >
	<tr><td height="20" width="200" style="color:white">Tip</td></tr>
	
	</table></span></a></td><td width="70"><span style="color:#666666;line-height:140%;font-size:8pt">
							PASSWORD</span></td><td width="100"><input type="password" name="userpw" size="20" class="input" tabindex="2"></td></tr>
						<tr><td width="20" height="22"><br>
							<br>
							<br>
							<br>
							<br>
							<br>
							<br>
							<br>
							<br>
							<br>
							</td><td colspan="2">
							<a class="info" href="javascript:void(0)"><span><table border="0" cellpadding="2" cellspacing="1" >
	
	</table></span></a> <input type="image" src="images/login.gif"></td></tr>
						</table>
					</td>
				</td>
				</tr>
				</form>
				</table>
				<table width=813 border=0 cellspacing=0 cellpadding=0>
				<tr>
				
				</table>
			</td>
			</tr>
			</table>
			</div>
			</td></tr>
			</table>
		</td>
		<!-- CENTER  END-->
	</tr>
</table>
</td>
</tr>
<tr>
<td valign="top">
</td>
</tr>
</table>

<span id="forgot_exp" style="position:absolute;top:280;left:500;display:none">
	
</span>
</BODY>
</HTML>
<? ob_flush(); ?>