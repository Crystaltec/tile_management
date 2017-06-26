<?php
ob_start();
include_once "include/logincheck.inc";
include_once "include/common.inc";
include_once "include/dbconn.inc";
include_once "include/myfunc.inc";

$material_id = $_REQUEST["material_id"];
$category_id = $_REQUEST["category_id"];
$material_name =	$_REQUEST["material_name"];
$material_code_number = $_REQUEST["material_code_number"];
$material_factory_number = $_REQUEST["material_factory_number"];
$material_color = $_REQUEST["material_color"];
$material_size = $_REQUEST["material_size"];
$unit_id =	$_REQUEST["unit_id"];
$material_price = $_REQUEST["material_price"];
$supplier_id = $_REQUEST["supplier_id"];
$material_description = $_REQUEST["material_description"];
$material_adjustment = $_REQUEST["material_adjustment"];
$material_adjustment_note = $_REQUEST["material_adjustment_note"];
$action_type = $_REQUEST["action_type"];
$info_file_count =	$_REQUEST["info_file_count"];

//업로드 디렉토리가 없으면 생성
//if(!is_dir($upload_root)) @mkdir($upload_root,0755);
//if(!is_dir($upload_root."/".$_GET["boardid"])) @mkdir($upload_root."/".$_GET["boardid"],0755);
/*
for($f=0;$f<$info_file_count;$f++){
	if ($_FILES["file_up_".$f]["tmp_name"]) {
		
		$extra[$f] = "material_".$material_id."_".$f.strtolower(strrchr($_FILES["file_up_".$f]["name"],"."));
		
		$copy_result = copy($_FILES["file_up_".$f]["tmp_name"], $upload_root. "/". $extra[$f]);	 // 파일을 복사한다.
		if(!$copy_result){
		echo"<script>alert('업로드를 실패했습니다.')</script>";
		echo"<script>history.go(-1)</script>";
		exit;
		}

		$ext = strtolower(strrchr($extra[$f],"."));
		if($f==0 && ($ext==".jpg" || $ext==".png" || $ext==".gif")){
			$size = getimagesize($upload_root."/" . $extra[$f]);

			if($size[0]>$size[1]) {
				$rewidth = 150;
				$reheight = 150 * $size[1] / $size[0];
			}else{
				$rewidth = 150 * $size[0] / $size[1];
				$reheight = 150;
			}

			$img_file_name = $upload_root."/". $extra[$f];
			$dstimg = ImageCreatetruecolor($rewidth,$reheight);
			if($ext==".gif") $dstimg = ImageCreate($rewidth,$reheight);
			if($ext==".jpg") $srcimg = ImageCreateFromJPEG($img_file_name);
			if($ext==".gif") $srcimg = ImageCreateFromGIF($img_file_name);
			if($ext==".png") $srcimg = ImageCreateFromPNG($img_file_name);
			Imagecopyresampled($dstimg, $srcimg,0,0,0,0,$rewidth,$reheight,ImageSX($srcimg),ImageSY($srcimg));
			if($ext==".jpg") ImageJPEG($dstimg,$upload_root."/thumb_".$extra[$f],90);
			if($ext==".gif") ImageGIF($dstimg,$upload_root."/thumb_".$extra[$f],90);
			if($ext==".png") ImagePNG($dstimg,$upload_root."/thumb_".$extra[$f]);

			$rewidth = 600;
			$reheight = ($rewidth * 2.67) / 4;
			$img_file_name = $upload_root."/". $extra[$f];
			$dstimg = ImageCreatetruecolor($rewidth,$reheight);
			if($ext==".gif") $dstimg = ImageCreate($rewidth,$reheight);
			if($ext==".jpg") $srcimg = ImageCreateFromJPEG($img_file_name);
			if($ext==".gif") $srcimg = ImageCreateFromGIF($img_file_name);
			if($ext==".png") $srcimg = ImageCreateFromPNG($img_file_name);
			Imagecopyresampled($dstimg, $srcimg,0,0,0,0,$rewidth,$reheight,ImageSX($srcimg),ImageSY($srcimg));
			if($ext==".jpg") ImageJPEG($dstimg,$upload_root."/thumb02_".$extra[$f],90);
			if($ext==".gif") ImageGIF($dstimg,$upload_root."/thumb02_".$extra[$f],90);
			if($ext==".png") ImagePNG($dstimg,$upload_root."/thumb02_".$extra[$f]);

			imageDestroy($dstimg);
			imageDestroy($srcimg);
		}

		unlink($_FILES["file_up_".$f]["tmp_name"]);
		unlink($upload_root."/".$extra[$f]);

		$ar_userfile_name			.= $extra[$f]."|";
		$ar_userfile_size			.= $_FILES["file_up_".$f]["size"]."|";
		
	}
}

$material_image_size = explode("|",$ar_userfile_size);
*/

if ($action_type=="") {
	$sql = "SELECT COUNT(*) FROM material WHERE material_code_number='$material_code_number' AND material_name='$material_name' AND material_color = '$material_color' AND material_size = '$material_size' ";
	$rows = getRowCount($sql);
	if($rows[0] > 0) {
		echo "<script>alert('This code number of material has already registered!');history.back()</script>";
		exit;
	}
	$sql = "INSERT INTO material (category_id, material_name, material_code_number, material_factory_number, material_color, material_size, unit_id, material_price, supplier_id, material_description, material_image, material_image_size,material_adjustment, material_adjustment_note, regdate) VALUES ( '$category_id', '$material_name', '$material_code_number', '$material_factory_number', '$material_color','$material_size', '$unit_id', '$material_price', '$supplier_id', '$material_description', '$ar_userfile_name', '$material_image_size[0]','$material_adjustment','$material_adjustment_note', '$now_datetimeano')";
	pQuery($sql,"insert");
	$string1 = "Registration Completed!";

	
} else if($action_type=="modify") {
	// 새로운 파일을 업로드 하였으면, 기존파일 삭제!
	
	/*
	if($_FILES["file_up_0"]["tmp_name"] != "" && $old_filename != "") {
		if(file_exists($upload_root."/" .$old_filename)) 	unlink($upload_root."/" . $old_filename);
		if(file_exists($upload_root."/" ."thumb_" . $old_filename)) unlink($upload_root."/" . "thumb_" . $old_filename);
		if(file_exists($upload_root."/" ."thumb02_" . $old_filename)) unlink($upload_root."/" . "thumb02_" . $old_filename);
	}
	*/
	// Code Number로 중복 체크 
	$sql = "SELECT COUNT(*) FROM material WHERE material_code_number='$material_code_number' AND material_name='$material_name' AND material_color = '$material_color' AND material_size = '$material_size' AND material_id != '".$material_id."'";
	$rows = getRowCount($sql);
	if($rows[0] > 0) {
		echo "<script>alert('This code number of material has already registered!');history.back()</script>";
		exit;
	} else {
		$sql = "UPDATE material SET category_id='$category_id', material_name='$material_name', material_code_number = '$material_code_number', material_factory_number = '$material_factory_number', material_color = '$material_color', material_size = '$material_size', unit_id='$unit_id', material_price = '$material_price', supplier_id='$supplier_id',  material_description='$material_description', material_adjustment = '$material_adjustment', material_adjustment_note = '$material_adjustment_note'  WHERE material_id='" . $material_id ."'";
		pQuery($sql,"update");
	}
		
	if($_FILES["file_up_0"]["tmp_name"] != "" ) {	
		$sql = "UPDATE material SET material_image='".$ar_userfile_name."', material_image_size='".$material_image_size[0]."'";
		$sql.=" WHERE material_id='".$material_id."'";
		pQuery($sql, "update");
	}

	$string1 = "Update Completed!";
}

?>
<script>
alert("<?=$string1?>");
location.href="material_list.php";
</script>
<? ob_flush(); ?>
