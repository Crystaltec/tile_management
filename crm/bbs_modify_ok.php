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
include_once 'include/image_proc.function.php';

$subject	 =htmlspecialchars(stripslashes($_REQUEST["subject"]), ENT_QUOTES);

$name	= $_REQUEST["name"];
$pass	= $_REQUEST["pass"];

$contents = htmlspecialchars(stripslashes($_REQUEST["contents"]), ENT_QUOTES);

$link = htmlspecialchars(stripslashes($_POST["link"]), ENT_QUOTES);
$no = $_REQUEST["no"];

$filename_0 = $_REQUEST["filename_0"];
$filesize_0 = $_REQUEST["filesize_0"];

if ($_BOARDID == "project_web" )
{
	$hide = htmlspecialchars(stripslashes($_REQUEST["hide"]), ENT_QUOTES);
	$builder_id = htmlspecialchars(stripslashes($_REQUEST["builder_id"]), ENT_QUOTES);
}
else if ($_BOARDID == "tiles")
{
	$tiles_category_id = htmlspecialchars(stripslashes($_REQUEST["tiles_category_id"]), ENT_QUOTES);
} 
else if ($_BOARDID == "nobel_stones")
{
	$nobel_stones_category_id = htmlspecialchars(stripslashes($_REQUEST["nobel_stones_category_id"]), ENT_QUOTES);
} 

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

// query update! ----------------------------------------------------------------------------------
if ($_BOARDID == "project_web" )
{
	$data = array("subject"=>$subject, "name"=>$name, "pass"=>$pass,"link"=>$link, "hide"=>$hide, "builder_id"=>$builder_id,"contents"=>$contents);
}
else if ($_BOARDID == "tiles" )
{
	$data = array("subject"=>$subject, "name"=>$name, "pass"=>$pass, "link"=>$link, "hide"=>$hide,"category_id"=>$tiles_category_id,"contents"=>$contents);
}
else if ( $_BOARDID == "nobel_stones" )
{
	$data = array("subject"=>$subject, "name"=>$name, "pass"=>$pass, "link"=>$link, "hide"=>$hide,"category_id"=>$nobel_stones_category_id,"contents"=>$contents);
}
else 
{
	$data = array("subject"=>$subject, "name"=>$name, "pass"=>$pass,"link"=>$link, "contents"=>$contents);
}

tep_db_perform($_BOARDID, $data, $action="update", "no=".$no);

// 새로운 파일을 업로드 하였으면, 기존파일 삭제!
$fn=explode("|",$filename_0);
$fs=explode("|",$filesize_0);

$new_no = $no;

// start file upload! ----------------------------------------------------------------------------------------
// if there is no upload dir, create dir
if(!is_dir($upload_root)) @mkdir($upload_root,0777);
if(!is_dir($upload_root."/".$_BOARDID)) @mkdir($upload_root."/".$_BOARDID,0777);

for($i=0;$i<count($fn)-1;$i++){
	if($_POST["file_del_".$i] == "y") {
		if(file_exists($upload_root."/".$_BOARDID."/".$fn[$i]))			unlink($upload_root."/".$_BOARDID."/".$fn[$i]);
		if(file_exists($upload_root."/".$_BOARDID."/thumb_".$fn[$i]))	unlink($upload_root."/".$_BOARDID."/thumb_".$fn[$i]);
		if(file_exists($upload_root."/".$_BOARDID."/thumb02_".$fn[$i]))	unlink($upload_root."/".$_BOARDID."/thumb02_".$fn[$i]);
		
	}else{
		$ar_userfile_name		.=$fn[$i]."|";
		$ar_userfile_extra		.=$fn[$i]."|";
		$ar_userfile_size			.=$fs[$i]."|";
	}
}

//첫번째 썸네일은 무조건 갱신 시작
$nfn=explode("|",$ar_userfile_name);
$nfe=explode("|",$ar_userfile_extra);
$nfs=explode("|",$ar_userfile_size);

$ext = strtolower(strrchr($nfe[0],"."));
if($f==0 && ($ext==".jpg" || $ext==".png" || $ext==".gif")){
	$size = getimagesize($upload_root."/".$_BOARDID."/".$nfn[0]);
	$ratio_orig = $size[0]/$size[1];
	if($_BOARDID == 'project_web' || $_BOARDID == 'tiles' || $_BOARDID == 'nobel_stones' || $_BOARDID == 'testimonials'){
					$rewidth = 208;
					$reheight = 104;			
					
					if ($rewidth/$reheight > $ratio_orig) {
					  $new_height = $rewidth/$ratio_orig;
					 $new_width = $rewidth;
					} else {
					   $new_width = $reheight*$ratio_orig;
					   $new_height = $reheight;
					}
					$x_mid = $new_width/2;  //horizontal middle
					//$y_mid = $new_height/2; //vertical middle

				} else {
					if($size[0]>$size[1]) {
					$rewidth = 190;
					$reheight = 143;			
					}else{
						$reheight = 190;
						$rewidth = 143;
					}
				}

				if ($size[0] > 1024 && $size[0]>$size[1]) {
					$rewidth2 = 1024;
					$reheight2 = (1024*$size[1])/$size[0];
				} elseif ($size[1] > 768 && $size[0]<$size[1]) {
					$reheight2 = 768;
					$rewidth2 = (768 * $size[1]) / $size[0] ;
				} else {
					$rewidth2 = $size[0];
					$reheight2 = $size[1];
				}
						
				$img_file_name = $upload_root."/".$_BOARDID."/".$nfn[0];
				$dstimg = ImageCreatetruecolor($rewidth,$reheight);
				$dstimg2 = ImageCreatetruecolor($rewidth2,$reheight2);
				
				if($ext==".gif") {
					$dstimg = ImageCreate($rewidth,$reheight);
					$dstimg2 = ImageCreate($rewidth2,$reheight2);
				}
				if($ext==".jpg") $srcimg = ImageCreateFromJPEG($img_file_name);
				if($ext==".gif") $srcimg = ImageCreateFromGIF($img_file_name);
				if($ext==".png") $srcimg = ImageCreateFromPNG($img_file_name);
				
				// for crop image
				$process = ImageCreatetruecolor(round($new_width), round($new_height)); 
				imagecopyresampled($process, $srcimg, 0, 0, 0, 0, $new_width, $new_height,ImageSX($srcimg),ImageSY($srcimg));

				Imagecopyresampled($dstimg, $process,0,0,($x_mid-($rewidth/2)), 0,$rewidth,$reheight,$rewidth,$reheight);
				Imagecopyresampled($dstimg2, $srcimg,0,0,0,0,$rewidth2,$reheight2,ImageSX($srcimg),ImageSY($srcimg));

				if($ext==".jpg") {
					ImageJPEG($dstimg,$upload_root."/".$_BOARDID."/thumb_".$nfn[0],90);
					ImageJPEG($dstimg2,$upload_root."/".$_BOARDID."/".$nfn[0],90);
				}
				if($ext==".gif") {
					ImageGIF($dstimg,$upload_root."/".$_BOARDID."/thumb_".$nfn[0],90);
					ImageGIF($dstimg2,$upload_root."/".$_BOARDID."/".$nfn[0],90);
				}
				if($ext==".png") {
					ImagePNG($dstimg,$upload_root."/".$_BOARDID."/thumb_".$nfn[0],9);
					ImagePNG($dstimg2,$upload_root."/".$_BOARDID."/".$nfn[0],9);
				}

				imageDestroy($dstimg);
				imageDestroy($dstimg2);
				imageDestroy($srcimg);
}
//첫번째 썸네일은 무조건 갱신 종료


for($f=0;$f<$info_file_count;$f++){
	// if file is ture, upload process!
	if ($_FILES["file_up_".$f]["tmp_name"]) {
		//echo "abc";
		$extra[$f] = $_BOARDID."_".$new_no."_".$f.strtolower(strrchr($_FILES["file_up_".$f]["name"],"."));
		$copy_result = copy($_FILES["file_up_".$f]["tmp_name"], $upload_root."/".$_BOARDID."/".$extra[$f]);	 // file copy;
		if(!$copy_result){
			echo"<script>alert('File upload failed!')</script>";
			echo"<script>history.go(-1)</script>";
			exit;
		}
		
		if($isAcceptThumb == "1") {
			$ext = strtolower(strrchr($extra[$f],"."));
			if($ext==".jpg" || $ext==".png" || $ext==".gif"){
				$size = getimagesize($upload_root."/".$_BOARDID."/".$extra[$f]);
				$ratio_orig = $size[0]/$size[1];
				if($_BOARDID == 'project_web' || $_BOARDID == 'tiles' || $_BOARDID == 'nobel_stones' || $_BOARDID == 'testimonials'){
					$rewidth = 208;
					$reheight = 104;			
					
					if ($rewidth/$reheight > $ratio_orig) {
					  $new_height = $rewidth/$ratio_orig;
					 $new_width = $rewidth;
					} else {
					   $new_width = $reheight*$ratio_orig;
					   $new_height = $reheight;
					}
					$x_mid = $new_width/2;  //horizontal middle
					//$y_mid = $new_height/2; //vertical middle

				} else {
					if($size[0]>$size[1]) {
					$rewidth = 190;
					$reheight = 143;			
					}else{
						$reheight = 190;
						$rewidth = 143;
					}
				}

				if ($size[0] > 1024 && $size[0]>$size[1]) {
					$rewidth2 = 1024;
					$reheight2 = (1024*$size[1])/$size[0];
				} elseif ($size[1] > 768 && $size[0]<$size[1]) {
					$reheight2 = 768;
					$rewidth2 = (768 * $size[1]) / $size[0] ;
				} else {
					$rewidth2 = $size[0];
					$reheight2 = $size[1];
				}
						
				$img_file_name = $upload_root."/".$_BOARDID."/".$extra[$f];
				$dstimg = ImageCreatetruecolor($rewidth,$reheight);
				$dstimg2 = ImageCreatetruecolor($rewidth2,$reheight2);
				
				if($ext==".gif") {
					$dstimg = ImageCreate($rewidth,$reheight);
					$dstimg2 = ImageCreate($rewidth2,$reheight2);
				}
				if($ext==".jpg") $srcimg = ImageCreateFromJPEG($img_file_name);
				if($ext==".gif") $srcimg = ImageCreateFromGIF($img_file_name);
				if($ext==".png") $srcimg = ImageCreateFromPNG($img_file_name);
				
				// for crop image
				$process = ImageCreatetruecolor(round($new_width), round($new_height)); 
				imagecopyresampled($process, $srcimg, 0, 0, 0, 0, $new_width, $new_height,ImageSX($srcimg),ImageSY($srcimg));

				Imagecopyresampled($dstimg, $process,0,0,($x_mid-($rewidth/2)), 0,$rewidth,$reheight,$rewidth,$reheight);
				Imagecopyresampled($dstimg2, $srcimg,0,0,0,0,$rewidth2,$reheight2,ImageSX($srcimg),ImageSY($srcimg));

				if($ext==".jpg") {
					ImageJPEG($dstimg,$upload_root."/".$_BOARDID."/thumb_".$extra[$f],90);
					ImageJPEG($dstimg2,$upload_root."/".$_BOARDID."/".$extra[$f],90);
				}
				if($ext==".gif") {
					ImageGIF($dstimg,$upload_root."/".$_BOARDID."/thumb_".$extra[$f],90);
					ImageGIF($dstimg2,$upload_root."/".$_BOARDID."/".$extra[$f],90);
				}
				if($ext==".png") {
					ImagePNG($dstimg,$upload_root."/".$_BOARDID."/thumb_".$extra[$f],9);
					ImagePNG($dstimg2,$upload_root."/".$_BOARDID."/".$extra[$f],9);
				}

				imageDestroy($dstimg);
				imageDestroy($dstimg2);
				imageDestroy($srcimg);
			}
		}

		// delete temporary uploaded file
		unlink($_FILES["file_up_".$f]["tmp_name"]);
		//unlink($upload_root."/".$_BOARDID. "/". $extra[$f]);

		//$ar_userfile_name			= $_FILES["file_up_".$f]["name"];
		
		$ar_userfile_extra			.= $extra[$f] ."|";
		$ar_userfile_size			.= $_FILES["file_up_".$f]["size"]."|";

	
		
	}
}

// update file information!
$data = array("filename_0"=>$ar_userfile_extra, "filesize_0"=>$ar_userfile_size, "filedown_0"=>"0");
tep_db_perform($_BOARDID, $data, $action="update", "no=".$new_no);

echo "<script type='text/javascript'>location.replace('bbs_list.php?boardid=".$_BOARDID."');</script>";
exit;
?>