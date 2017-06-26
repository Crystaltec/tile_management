<?
include_once "include/logincheck.inc";
include_once "include/common.inc";
include_once "include/dbconn.inc";
include_once "include/myfunc.inc";

$company_id =		$_REQUEST["company_id"];
$company_name =	htmlspecialchars($_REQUEST["company_name"], ENT_QUOTES);
$company_phone_number =htmlspecialchars($_REQUEST["company_phone_number"], ENT_QUOTES) ;
$company_fax_number=	htmlspecialchars($_REQUEST["company_fax_number"], ENT_QUOTES);
$company_email= $_REQUEST["company_email"];

$company_address =htmlspecialchars($_REQUEST["company_address"], ENT_QUOTES);
$company_suburb =htmlspecialchars($_REQUEST["company_suburb"], ENT_QUOTES);
$company_state = $_REQUEST["company_state"] ;
$company_postcode =htmlspecialchars($_REQUEST["company_postcode"], ENT_QUOTES);

$company_address2 =htmlspecialchars($_REQUEST["company_address2"], ENT_QUOTES);
$company_suburb2 =htmlspecialchars($_REQUEST["company_suburb2"], ENT_QUOTES);
$company_state2 = $_REQUEST["company_state2"] ;
$company_postcode2 =htmlspecialchars($_REQUEST["company_postcode2"], ENT_QUOTES);

$company_phone_number2 =htmlspecialchars($_REQUEST["company_phone_number2"], ENT_QUOTES) ;
$company_fax_number2=	htmlspecialchars($_REQUEST["company_fax_number2"], ENT_QUOTES);

$company_homepage= $_REQUEST["company_homepage"];
$company_abn= $_REQUEST["company_abn"];
$company_facebook= $_REQUEST["company_facebook"];
$company_youtube= $_REQUEST["company_youtube"];
$company_blog= $_REQUEST["company_blog"];
$company_twitter= $_REQUEST["company_twitter"];
$company_introduction=htmlspecialchars($_REQUEST["company_introduction"], ENT_QUOTES);

$action_type = $_REQUEST["action_type"];

if($action_type=="") {
	$sql = "SELECT COUNT(*) FROM company WHERE company_id='".$company_id."'";
	$row = getRowCount($sql);
	if($row[0] > 0 ) {
		echo "<script>alert('This company has already registered');history.back();</script>";
		exit;
	}
	$sql = "INSERT INTO company (  company_name, company_phone_number, company_fax_number, company_email, company_address, company_suburb, company_state, company_postcode, company_phone_number2, company_fax_number2, company_address2, company_suburb2, company_state2, company_postcode2,company_homepage, company_abn,company_facebook,company_youtube,company_blog,company_twitter, company_introduction) ";
	$sql .= "VALUES ('";
	$sql .= $company_name . "', '";
	$sql .= $company_phone_number . "', '";
	$sql .= $company_fax_number . "', '";
	$sql .= $company_email . "', '";
	$sql .= $company_address . "', '";
	$sql .= $company_suburb . "', '";
	$sql .= $company_state . "', '";
	$sql .= $company_postcode . "', '";
	$sql .= $company_phone_number2 . "', '";
	$sql .= $company_fax_number2 . "', '";
	$sql .= $company_address2 . "', '";
	$sql .= $company_suburb2 . "', '";
	$sql .= $company_state2 . "', '";
	$sql .= $company_postcode2 . "', '";
	$sql .= $company_homepage . "', '";
	$sql .= $company_abn . "', '";
	$sql .= $company_facebook . "', '";
	$sql .= $company_youtube . "', '";
	$sql .= $company_blog . "', '";
	$sql .= $company_twitter . "', '";
	$sql .= $company_introduction."')";

	$str1 = "Registration Completed!";
	
	pQuery($sql,"insert");	
} else if($action_type=="modify") {
	$sql = "UPDATE company SET company_name='".$company_name."', company_phone_number='".$company_phone_number."',company_fax_number='".$company_fax_number."',company_email='".$company_email."', company_address='".$company_address."', company_suburb='".$company_suburb."',company_state='".$company_state."',company_postcode='".$company_postcode."',company_phone_number2='".$company_phone_number2."',company_fax_number2='".$company_fax_number2."', company_address2='".$company_address2."', company_suburb2='".$company_suburb2."',company_state2='".$company_state2."',company_postcode2='".$company_postcode2."',company_homepage='".$company_homepage."',company_abn='".$company_abn."',company_facebook='".$company_facebook."',company_youtube='".$company_youtube."',company_blog='".$company_blog."',company_twitter='".$company_twitter."', company_introduction='".$company_introduction."' ";
	$sql .= " WHERE company_id='".$company_id."' ";
	$str1 = "Update Completed!";
	//echo $sql;
	pQuery($sql,"update");
}

?>
<script language="Javascript">
alert("<?=$str1?>");
location.href="company_list.php";
</script>