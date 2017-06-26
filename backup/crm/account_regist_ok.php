<?PHP
include_once "include/logincheck.inc";
include_once "include/common.inc";
include_once "include/dbconn.inc";
include_once "include/myfunc.inc";

$username =	htmlspecialchars($_REQUEST["uname"], ENT_QUOTES);
$userid =	$_POST["userid"];
$pass =		$_POST["upass"];
$alevel =	$_POST["accountlevel"];

$phone =htmlspecialchars($_POST["phone"], ENT_QUOTES) ;
$fax=	htmlspecialchars($_POST["fax"], ENT_QUOTES);
$email= $_POST["email"];
$remarks=htmlspecialchars($_POST["remarks"], ENT_QUOTES);
$address =htmlspecialchars($_POST["address"], ENT_QUOTES);
$suburb =htmlspecialchars($_POST["suburb"], ENT_QUOTES);
$state = $_POST["state"] ;
$postcode =htmlspecialchars($_POST["postcode"], ENT_QUOTES);
$display = $_POST['display'];
$status = $_POST['status'];
$action_type = $_POST["action_type"];


if($action_type=="") {
	$sql = "SELECT COUNT(*) FROM account WHERE userid='".$userid."'";
	$row = getRowCount($sql);
	if($row[0] > 0 ) {
		echo "<script>alert('This User Id has already registered');history.back();</script>";
		exit;
	}
	$sql = "INSERT INTO account (userid, hashed_pass, username, alevel, phone, fax, email, address, suburb, state, postcode, logo, remarks,display,status) ";
	$sql .= "VALUES ('";
	$sql .= $userid . "', '";
	$sql .= md5(md5($pass).$salt) . "', '";
	$sql .= $username . "', '";
	$sql .= $alevel . "', '";
	$sql .= $phone . "', '";
	$sql .= $fax . "', '";
	$sql .= $email . "', '";
	$sql .= $address . "', '";
	$sql .= $suburb . "', '";
	$sql .= $state . "', '";
	$sql .= $postcode . "', '', '".$remarks."','".$display."','".$status."')";
	
	$str1 = "Registration Completed!";

	//echo $sql;
	pQuery($sql,"insert");	
} else if($action_type=="modify") {
	$sql = " SELECT count(*) FROM account WHERE userid = '" . $userid ."' AND hashed_pass = '".md5(md5($pass).$salt)."' ";
	$row = getRowCount($sql);
	if ($row[0] == 1) {
		$sql = "UPDATE account SET username='".$username."' ";
		$sql .= ", alevel='".$alevel."', phone='".$phone."',fax='".$fax."',email='".$email."', address='".$address."', suburb='".$suburb."', state='".$state."', postcode='".$postcode.="', remarks='".$remarks."', display='".$display."', status='".$status."' ";
		$sql .= " WHERE userid='".$userid."' AND hashed_pass = '".md5(md5($pass).$salt)."' ";
		$str1 = "Update Completed!";
		//echo $sql;
		pQuery($sql,"update");
	}else {
		$str1 = "Authentication failed!";	
	}
}

?>
<script language="Javascript">
alert("<?=$str1?>");
location.href="account_view.php?userid=<?=$userid?>";
</script>