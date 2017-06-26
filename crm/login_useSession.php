<?
include_once "include/common.inc";
include_once "include/myfunc.inc";
include_once "include/dbconn.inc";

//if($_SESSION["user_id"]) Header("Location:/enjoymember_login.php");

if($_GET["cmd"]=="exec"){

$Query = "DELETE FROM Cart WHERE userid='".$_POST["userid"]."'";
mysql_query($Query) or exit(mysql_error());

$Member["userid"]				= NULL;
$Member["pass"]					= NULL;
$Member["username"]			= NULL;
$Member["alevel"]				= NULL;
$Member["olevel"]				= NULL;
$Member["payment_method"]	= NULL;


$Query  = "SELECT " . selectQuery($Member, 'account');
$Query .= " FROM account";
$Query .= " WHERE userid = '".$_POST["userid"]."'";
//echo $Query . "<br>";
$cnn = mysql_query($Query) or exit(mysql_error());
$Row = mysql_fetch_assoc($cnn);
$countRows = mysql_num_rows($cnn);

if($Row["pass"] == $_POST["userpw"]) {		
		$_SESSION["userid"] = $Row["userid"];
		$_SESSION["username"] = $Row["username"];
		$_SESSION["alevel"] = $Row["alevel"];
		$_SESSION["olevel"] = $Row["olevel"];
		$_SESSION["payment_method"] = $Row["payment_method"];

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
		
		/*
		echo $Row["userid"];;
		echo $Row["username"];
		echo $Row["alevel"];
		echo $Row["olevel"];
		exit;
		*/

		Header("Location:/");
		
		
}else{
		
		if($countRows<1) {
		echo"<SCRIPT>alert('Non registered Userid.'); history.back();</SCRIPT>";
		} else {
		echo"<SCRIPT>alert('Incorrect password.'); history.back();</SCRIPT>";
		}
	}
}
?>
<? include_once $ABS_DIR . "/htmlinclude/head.php"; ?>
<body>
<table width="998" border="0" cellpadding="0" cellspacing="0" align="center">
	<tr>				
		<td width="780" valign="top">					
			<table border="0" cellpadding="0" cellspacing="0" width="780" height="500">
			<tr>
			<td width="12"></td>
			<td width="768" valign="top" style="padding-right:50px;">						
			<div style="padding-top:30px"></div>
			<script language="javascript">
			<!--//
			function checkForm(){
				if(document.F['userid'].value =="") { window.alert('Please, Input userid!'); document.F['userid'].focus(); return false }
				if(document.F['userpw'].value =="") { window.alert('Please, Input password'); document.F['userpw'].focus(); return false }
			}
			//-->
			</script>
			</head>
			<body onload="document.F['userid'].focus();" bgcolor="#FFFFE9">
			<table width="100%" height="100%" border="0" cellspacing="0" cellpadding="0" align="center">
			<tr>
			<td align="center" valign="middle">

			<table width="480" height="250" cellspacing="0" cellpadding="0" style="border:5px solid #99AABB" bgcolor="#F0F0F0">
			<form method="post" name="F" action = "<?=$PHP_SELP?>?cmd=exec" onsubmit="return checkForm();">
			<tr>
			<td height="70" bgcolor="#99AABB" style="padding-left:20px;font:bold 20pt verdana; color:#FFFFFF">Member Login</td>
			</tr>
			<tr>
			<td align="center" valign="middle">
				<table border="0" cellspacing="0" cellpadding="0">
				<tr>
				<td><b>Userid</b></td>
				<td style="padding-left:5px;"><input name="userid" type="text" class="input" style="width:120"></td>
				<td style="padding-left:5px;"><img src="images/space.gif"></td>
				</tr>
				<tr>
				<td><b>Password</b></td>
				<td style="padding-left:5px;"><input name="userpw" type="password" class="input" style="width:120"></td>
				<td style="padding-left:5px;"><input type="submit" value="login" style="background-color:#99AABB; border:1px solid #336699; font:9pt Tahoma; height:19"></td>
				</tr>
				</table>
			</td>
			</tr>
			</form>
			</table>

			<table width=480 border=0 cellspacing=0 cellpadding=0>
			<tr>
			<td align="right"><font face="arial"><b>Copyright 2006 <?=ucfirst(str_replace("www.","",$_SERVER["HTTP_HOST"]))?>. All right reserved.</b></font></td>
			</tr>
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
</BODY>
</HTML>
