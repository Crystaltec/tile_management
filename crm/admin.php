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
	if(document.F['userid'].value =="") { window.alert('Please Input your userid!'); document.F['userid'].focus(); return false }
	if(document.F['userpw'].value =="") { window.alert('Please Input your password'); document.F['userpw'].focus(); return false }
}


//-->


</script>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <link rel="stylesheet" type="text/css" href="css/admin_login.css">
         <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
        <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
        <link rel="stylesheet" href="bootstrap-3.3.4-dist/css/bootstrap.css"></link>
        <link rel="stylesheet" href="bootstrap-3.3.4-dist/font-awesome-4.3.0/css/font-awesome.css"></link>
        <title></title>
    </head>
    <body style="display: block;" onload="document.F['userid'].focus();">
   
<div class="container">
    <div class="row">
        <div class="col-md-4 col-md-offset-7">
            <div class="panel panel-default">
                <div class="panel-heading">
                   <img src="img/logo.png" alt="SGT" style="width:35px;height:35px"> &nbsp; <b>Sun Gold Tiles Admin </b></div>
                <div class="panel-body">
   <form method="post" name="F" action = "<?=$_SERVER[PHP_SELF]?>?cmd=exec" onsubmit="return checkForm();" class="form-horizontal" role="form">
                 
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-3 control-label">
                            Email</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="userid" id="inputEmail3" placeholder="ID" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputPassword3" class="col-sm-3 control-label">
                            Password</label>
                        <div class="col-sm-9">
                            <input type="password" class="form-control" name="userpw" id="inputPassword3" placeholder="Password" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-9">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox"/>
                                    Remember me
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group last">
                        <div class="col-sm-offset-3 col-sm-9">
                            <button type="submit" class="btn btn-success btn-sm">
                                Sign in</button>
                                 <button type="reset" class="btn btn-default btn-sm">
                                Reset</button>
                        </div>
                    </div>
                    </form>
                </div>
                <div class="panel-footer">
                   Sun Gold Tiles Admin Login</div>
            </div>
        </div>
    </div>
</div>       
    </body>
</html>