<? 
$bbs_title = "";
$isAcceptWrite = "0";
$isAcceptReply = "0";
$isAcceptModify = "0";
$isAcceptDelete = "0";
$isAcceptThumb = "0";
$isAcceptFileUpload = "0";
$isHtmlEditor = "0";

if ( $rows["header"] != '' ) {
	$bbs_header = stripslashes($rows["header"]);
}

if ($_BOARDID == "contents") {
	$bbs_title = "Contents";
	$isAcceptThumb = "0";
	$isAcceptFileUpload = "0";
	$isHtmlEditor = "1";
} else 	if ($_BOARDID == "project_web") {
	$bbs_title = "Projects";
	$isAcceptThumb = "1";
	$isAcceptFileUpload = "1";
	$isHtmlEditor = "0";
} else 	if ($_BOARDID == "testimonials") {
	$bbs_title = "Testimonials";
	$isAcceptThumb = "1";
	$isAcceptFileUpload = "1";
	$isHtmlEditor = "0";
} else 	if ($_BOARDID == "news") {
	$bbs_title = "News & Press Releases";
	$isAcceptThumb = "0";
	$isAcceptFileUpload = "0";
	$isHtmlEditor = "0";
} else 	if ($_BOARDID == "tiles") {
	$bbs_title = "Tiles";
	$isAcceptThumb = "1";
	$isAcceptFileUpload = "1";
	$isHtmlEditor = "0";
} else 	if ($_BOARDID == "nobel_stones") {
	$bbs_title = "Nobel Stones";
	$isAcceptThumb = "1";
	$isAcceptFileUpload = "1";
	$isHtmlEditor = "0";
}else {
	$bbs_title = $_BOARDID;
}

?>