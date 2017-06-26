<?
ob_start();
$_BOARDID = $_REQUEST['boardid'];
include_once "include/logincheck.inc";
include_once "include/common.inc";
include_once "include/functions/database.php";
include_once "include/dbconn.inc";
include_once "include/myfunc.inc";
include_once "htmlinclude/head.php";

include_once "include/bbs_config.php";


$info_file_count = 1;

$subject	 =$_REQUEST["subject"];
$name	= $_REQUEST["name"];
$pass	= $_REQUEST["pass"];
$contents = $_REQUEST["contents"];

if($_USER_ID != "") $userid = $_USER_ID;
elseif($_POST["userid"] != "") $userid = $_POST["userid"];
else $userid="-1";

// check file size
for($f=0;$f<$info_file_count;$f++){
	if ($_FILES["file_up_".$f]["tmp_name"]) {
		$now_file_quota = $info_file_quata  *1024 *1024;
		if($now_file_quota < $_FILES["file_up_".$f]["size"]) {	 // check file size
			echo"<script>alert('file size is bigger than limited size!')</script>";
			echo"<script>history.go(-1)</script>";
			exit;
		}
	}
}

// reply -----------------------------------------------------------------------------------------
// 부모글의 look과 thread를 가져온다.
// 부모글의 look은 그대로 삽입한다.
// 부모글의 
$look	= $_REQUEST["look"];
$thread	= $_REQUEST["thread"];
$len = strlen($thread) + 1; //부모의 길이 보다 한개 더 큰것들 = 답글의 최고 값을 가져온다.
$sql = "SELECT IFNULL(MAX(thread),'0') as max_thread FROM ".$_BOARDID." WHERE look=".$look." AND CHAR_LENGTH(thread)=".$len;
$result = mysql_query($sql);
$row = mysql_fetch_array($result);
$max_thread = $row["max_thread"];

$temp == "";
$next_thread = "";
if($max_thread == "0") { // 답글이 없다면, 처음 답변
	for($i=0; $i < $len; $i++)
		$temp .= "A";
	$next_thread = $temp;
	
} else {
	// 답글이 있다면 부모 답글을 계산해서 1을 증가한다. 
	// 알파벳 대문자 A-Z가 ASCII 코드로 65-90, 전부 26개다. ASCII 코드로 계산을 해서 입력하자!
	// 부모 답글의 마지막 2자리 숫자만 계산해주면 26 * 26 = 676개의 답글을 달 수 있다. 이정도면 충분하지 않을까?
	// 충분하지!
	
	// 부모글에 대한 답글의 최대값 길이. 이 값은 항상 2 (>=) 보다 크거나 같다.
	$len = strlen($max_thread);
	$second = substr($max_thread, -2 , 1);	 // 마지막에서 2번째 문자 가져오기
	$last = substr($max_thread, -1 , 1);		 // 마지막 문자 가져오기

	// 문자를 ASCII 값으로 변환하기
	$second_ord = ord($second); 
	$last_ord = ord($last);

	if($last_ord == "90") { //만약 마지막 문자가 Z(90)와 같다면 2번째 문자를 한칸 증가하고, 마지막을 A(65)로 셋팅한다.
		$second_ord += 1;
		$last_ord = "65";
	} else { //아니라면 마지막 문자만 1증가 한다
		$last_ord += 1;
	}

	// 계산된 ASCII 코드값을 다시 문자로 변환하기
	$second = chr($second_ord);
	$last = chr($last_ord);

	/*
	echo $max_thread . "<br>";
	echo $second_ord . "<br>";
	echo $last_ord . "<br>";
	echo $second . "<br>";
	echo $last . "<br>";
	*/

	// next thread 치환하기. max_thread의 마지막 문자와, 마지막에서 2번째 문자를 위에서 변환시킨 문자로 바꾼다.	
	$temp = substr_replace($max_thread, $last, -1, 1);
	$next_thread = substr_replace($temp, $second, -2, 1);
	//echo $next_thread;
	//exit;
}


$data = array("look"=>$look, "thread"=>$next_thread, "subject"=>$subject, "name"=>$name, "userid"=>$userid, "pass"=>$pass, "contents"=>$contents, "regdate"=>$now_datetimeano);
tep_db_perform($_BOARDID, $data);
$new_no = mysql_insert_id();

// start file upload! ----------------------------------------------------------------------------------------
// if there is no upload dir, create dir
if(!is_dir($upload_root)) @mkdir($upload_root,0755);
if(!is_dir($upload_root."/".$_BOARDID)) @mkdir($upload_root."/".$_BOARDID,0755);

for($f=0;$f<$info_file_count;$f++){
	// if file is ture, upload process!
	if ($_FILES["file_up_".$f]["tmp_name"]) {
		$extra[$f] = $_BOARDID."_".$new_no."_".$f.strtolower(strrchr($_FILES["file_up_".$f]["name"],"."));
		$copy_result = copy($_FILES["file_up_".$f]["tmp_name"], $upload_root."/".$_BOARDID."/".$extra[$f]);	 // file copy;
		if(!$copy_result){
			echo"<script>alert('File upload failed!')</script>";
			echo"<script>history.go(-1)</script>";
			exit;
		}
		
		// Create Thumbnales
		if($isAcceptThumb == "1") {
			$ext = strtolower(strrchr($extra[$f],"."));
			if($ext==".jpg" || $ext==".png" || $ext==".gif"){
				$size = getimagesize($upload_root."/".$_BOARDID."/".$extra[$f]);
				if($size[0]>$size[1]) {
					$rewidth = 98;
					$reheight = ($rewidth * 2.67) / 4;				
				}else{
					$reheight = 98;
					$rewidth = ($reheight * 2.67) / 4;
				}

				$img_file_name = $upload_root."/".$_BOARDID."/".$extra[$f];
				$dstimg = ImageCreatetruecolor($rewidth,$reheight);
				if($ext==".gif") $dstimg = ImageCreate($rewidth,$reheight);
				if($ext==".jpg") $srcimg = ImageCreateFromJPEG($img_file_name);
				if($ext==".gif") $srcimg = ImageCreateFromGIF($img_file_name);
				if($ext==".png") $srcimg = ImageCreateFromPNG($img_file_name);
				Imagecopyresampled($dstimg, $srcimg,0,0,0,0,$rewidth,$reheight,ImageSX($srcimg),ImageSY($srcimg));

				if($ext==".jpg") ImageJPEG($dstimg,$upload_root."/".$_BOARDID."/thumb_".$extra[$f],90);
				if($ext==".gif") ImageGIF($dstimg,$upload_root."/".$_BOARDID."/thumb_".$extra[$f],90);
				if($ext==".png") ImagePNG($dstimg,$upload_root."/".$_BOARDID."/thumb_".$extra[$f],90);

				imageDestroy($dstimg);
				imageDestroy($srcimg);
			}
		}

		// delete temporary uploaded file
		unlink($_FILES["file_up_".$f]["tmp_name"]);
		//unlink($upload_root."/".$_BOARDID. "/". $extra[$f]);

		//$ar_userfile_name			= $_FILES["file_up_".$f]["name"];
		$ar_userfile_extra			= $extra[$f];
		$ar_userfile_size			= $_FILES["file_up_".$f]["size"];

		// update file information!
		$data = array("filename_".$f=>$ar_userfile_extra, "filesize_".$f=>$ar_userfile_size, "filedown_".$f=>"0");
		tep_db_perform($_BOARDID, $data, $action="update", "no=".$new_no);
	}
}

echo "<script type='text/javascript'>location.replace('bbs_list.php?boardid=".$_BOARDID."');</script>";
exit;
?>