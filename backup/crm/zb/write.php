<?
set_time_limit(0);
ob_start();
include "../include/common.inc";
include "../include/user_functions.inc";
include "../include/dbconn.inc";
include "include/board_config.inc";

if(!$_GET["boardid"]) Header("Location:/");

## 게시판 꾸밈 정보를 마스터 테이블에서 가져온다
$Board["boardid"]			=null;
$Board["boardname"]		=null;
$Board["readlevel"]			=null;
$Board["writelevel"]			=null;
$Board["signdate"]			=null;
$Board["file_quota"]			=null;
$Board["file_count"]			=null;
$Board["title_img"]			=null;
$Board["line_img"]			=null;
$Board["link_style"]			=null;
$Board["width_sum"]		=null;
$Board["no_use"]			=null;
$Board["no_wid"]				=null;
$Board["img_icon_use"]	=null;
$Board["img_icon_wid"]		=null;
$Board["img_icon_size"]	=null;
$Board["category_use"]	=null;
$Board["category_wid"]		=null;
$Board["subject_use"]		=null;
$Board["subject_wid"]		=null;
$Board["name_use"]			=null;
$Board["name_wid"]			=null;
$Board["name_title"]			=null;
$Board["date_use"]			=null;
$Board["date_wid"]			=null;
$Board["file_use"]			=null;
$Board["download_use"]	=null;
$Board["comment_use"]	=null;
$Board["reply_use"]			=null;
$Board["secret_use"]		=null;
$Board["link_use"]			=null;
$Board["html_use"]			=null;
$Board["vote_use"]			=null;
$Board["vote_wid"]			=null;
$Board["hit_use"]				=null;
$Board["hit_wid"]				=null;
$Board["search_use"]		=null;
$Board["row_count"]		=null;
$Board["col_count"]			=null;
$Board["col_padding"]		=null;
$Board["category"]			=null;
$Board["head_note"]		=null;
$Board["tail_note"]			=null;
$Board["head_php"]			=null;
$Board["tail_php"]			=null;

$Query  = " SELECT " . selectQuery($Board, "zb");
$Query .= " FROM zb";
$Query .= " where boardid = '".$_GET["boardid"]."'";

$cnn = mysql_query($Query) or exit(mysql_error());
$Records = mysql_fetch_assoc($cnn);

$Records["category"] = explode(",",$Records["category"]);

## Get폼값설정
$etc_key  = "";
$etc_key .= "&boardid=".$_GET["boardid"];
$etc_key .= "&link_style=".$_GET["link_style"];
if($_GET["find_value"] && $_GET["find_key_1"]) $etc_key .= "&find_key_1=".$_GET["find_key_1"];
if($_GET["find_value"] && $_GET["find_key_2"]) $etc_key .= "&find_key_2=".$_GET["find_key_2"];
if($_GET["find_value"] && $_GET["find_key_3"]) $etc_key .= "&find_key_3=".$_GET["find_key_3"];
if($_GET["find_value"]) $etc_key .= "&find_value=".$_GET["find_value"];
if($_GET["startPage"])	$etc_key .= "&startPage=".$_GET["startPage"];

## 접근권한 설정
if($Records["writelevel"]>0 && $Sync_level<$Records["writelevel"]) {
	echo"<script>alert('본 페이지를 열람할 권한이 없습니다.')</script>";
	echo"<script>history.go(-1)</script>";
	exit;
}

if($_GET["cmd"]=="exec"){
	//파일크기 검사
	for($f=0;$f<$Records["file_count"];$f++){
		if ($_FILES["file_up_".$f]["tmp_name"]) {
			$now_file_quota = $Records["file_quota"]  *1024 *1024;
			if($now_file_quota < $_FILES["file_up_".$f]["size"]) {	 // 파일크기 검사.
				echo"<script>alert('파일의 크기가 제한된 크기보다 큽니다.')</script>";
				echo"<script>history.go(-1)</script>";
				exit;
			}
		}
	}

	if(!$_POST["html"])					$_POST["html"]="n";
	if(!$_POST["auto_br"])				$_POST["auto_br"]="n";
	if(!$_POST["notice"])				$_POST["notice"]="n";

	## 쿼리 시작
	$Board_id["boarder_id"]				= $_POST["boarder_id"];
	$Board_id["boarder_name"]		= $_POST["boarder_name"];
	$Board_id["boarder_passwd"]		= $_POST["boarder_passwd"];
	$Board_id["category"]				= $_POST["category"];
	$Board_id["subject"]					= $_POST["subject"];
	$Board_id["secret_passwd"]		= $_POST["secret_passwd"];
	$Board_id["content"]				= $_POST["content"];
	$Board_id["signdate"]				= date("Y-m-d H:i:s",time());
	$Board_id["hit"]						= "0";
	$Board_id["vote"]					= "0";
	$Board_id["thread"]					= "0001";
	$Board_id["link_1"]					= $_POST["link_1"];
	$Board_id["link_2"]					= $_POST["link_2"];
	$Board_id["html"]						= $_POST["html"];
	$Board_id["auto_br"]				= $_POST["auto_br"];
	$Board_id["notice"]					= $_POST["notice"];

	$Query = insertQuery($Board_id, $_GET["boardid"]);
	@mysql_query("set names utf8"); 
	mysql_query($Query) or die(mysql_error() . 'DB에 기록하지 못했습니다.');

	//echo $Query;
	//exit;	

	$new_uid = mysql_insert_id();

	### 지정디렉토리에 저장
	if ($Records["file_use"]=="1") {

		//업로드 디렉토리가 없으면 생성
		if(!is_dir($upload_root)) @mkdir($upload_root,0755);
		if(!is_dir($upload_root."/".$_GET["boardid"])) @mkdir($upload_root."/".$_GET["boardid"],0755);

		for($f=0;$f<$Records["file_count"];$f++){
			if ($_FILES["file_up_".$f]["tmp_name"]) {

				$extra[$f] = $_GET["boardid"]."_".$new_uid."_".$f.strtolower(strrchr($_FILES["file_up_".$f]["name"],"."));

				$copy_result = copy($_FILES["file_up_".$f]["tmp_name"], $upload_root."/".$_GET["boardid"]."/".$extra[$f]);	 // 파일을 복사한다.
				if(!$copy_result){
				echo"<script>alert('업로드를 실패했습니다.')</script>";
				echo"<script>history.go(-1)</script>";
				exit;
				}

				$ext = strtolower(strrchr($extra[$f],"."));
				if($f==0 && ($ext==".jpg" || $ext==".png" || $ext==".gif")){
					$size = getimagesize($upload_root."/".$_GET["boardid"]."/".$extra[$f]);

					if($size[0]>$size[1]) {
						$rewidth = $Records["img_icon_size"];
						$reheight = $Records["img_icon_size"] * $size[1] / $size[0];
					}else{
						$rewidth = $Records["img_icon_size"] * $size[0] / $size[1];
						$reheight = $Records["img_icon_size"];
					}

					$img_file_name = $upload_root."/".$_GET["boardid"]."/".$extra[$f];
					$dstimg = ImageCreatetruecolor($rewidth,$reheight);
					if($ext==".gif") $dstimg = ImageCreate($rewidth,$reheight);
					if($ext==".jpg") $srcimg = ImageCreateFromJPEG($img_file_name);
					if($ext==".gif") $srcimg = ImageCreateFromGIF($img_file_name);
					if($ext==".png") $srcimg = ImageCreateFromPNG($img_file_name);
					Imagecopyresampled($dstimg, $srcimg,0,0,0,0,$rewidth,$reheight,ImageSX($srcimg),ImageSY($srcimg));
					if($ext==".jpg") ImageJPEG($dstimg,$upload_root."/".$_GET["boardid"]."/thumb_".$extra[$f],90);
					if($ext==".gif") ImageGIF($dstimg,$upload_root."/".$_GET["boardid"]."/thumb_".$extra[$f],90);
					if($ext==".png") ImagePNG($dstimg,$upload_root."/".$_GET["boardid"]."/thumb_".$extra[$f],90);
				}

				unlink($_FILES["file_up_".$f]["tmp_name"]);

				$ar_userfile_name			.= $_FILES["file_up_".$f]["name"]."|";
				$ar_userfile_extra			.= $extra[$f]."|";
				$ar_userfile_size			.= $_FILES["file_up_".$f]["size"]."|";
				$ar_userfile_download	.= "0|";
			}
		}
	}

	$Board_up["fid"]						= $new_uid;
	$Board_up["userfile_name"]		= $ar_userfile_name;
	$Board_up["userfile_extra"]		= $ar_userfile_extra;
	$Board_up["userfile_size"]			= $ar_userfile_size;
	$Board_up["userfile_download"]	= $ar_userfile_download;

	$Query = updateQuery($Board_up, $_GET["boardid"], "uid", $new_uid);
	@mysql_query("set names utf8"); 
	mysql_query($Query) or die(mysql_error() . 'DB에 기록하지 못했습니다');
	
	if($_GET["link_style"]=="open") echo "<meta http-equiv='Refresh' content='0; URL=open.php?startPage=".$_GET["startPage"].$etc_key."'>";
	else echo "<meta http-equiv='Refresh' content='0; URL=view.php?uid=".$new_uid.$etc_key."'>";
	exit;
}
?>

<?=eval($Records["head_php"])?>
<table width=<?=$Records["width_sum"]?> cellSpacing="0" cellpadding="0" border="0">
<td><?=$Records["head_note"]?></td>
</table>

<table width=<?=$Records["width_sum"]?> cellSpacing="0" cellpadding="0" border="0">
<tr>
<td>

	<table cellpadding="0" cellspacing="0"><tr><td><img src='images/space.gif' height='2'></td></tr></table>


	<script language="javascript">
	<!--
	function checkInput(){		
		if (!document.F.boarder_name.value || !document.F.content.value <?if($_GET["link_style"]!="open"){?>|| !document.F.subject.value<?}?>) {
			alert("Please, input blank item!");
			return false;
		}
	}
	//-->
	</script>

	<div id="titleWin" style="width:300; position:absolute; z-index:300; display:none;"></div>
	<script language=javascript>
	<!--
	var offsetx = 10; //우측여백
	var offsety = 5; //좌측여백
	var width = 10; //임시값
	var height = 10; //임시값
	function helpOff() {
		document.getElementById('titleWin').style.display="none";
	}
	function helpOn(text, newWidth, newHeight) {
		width = newWidth; //가로크기
		height = newHeight; //세로크기
		document.getElementById('titleWin').style.width = width;
		document.getElementById('titleWin').innerHTML = "<table bgcolor=\"#FFFFE0\" style=\"border:1 solid #000000\" cellpadding=5 cellspacing=0><tr><td>"+text+"</td></tr></table>";
		document.getElementById('titleWin').style.left = x+offsetx;
		document.getElementById('titleWin').style.top = y+offsety;
		document.getElementById('titleWin').style.display = "inline";

	}
	document.onmousemove = mouseMove;
	function mouseMove(e) {
		x=event.x + document.body.scrollLeft+0
		y=event.y + document.body.scrollTop
		if (x+width-document.body.scrollLeft > document.body.clientWidth) x=x-width-25;
		if (y+height-document.body.scrollTop > document.body.clientHeight) y=y-height;
		document.getElementById('titleWin').style.left = x+offsetx;
		document.getElementById('titleWin').style.top = y+offsety;
	}
	//-->
	</script>

	<table border="0" cellpadding="0" cellspacing="0" width="<?=$Records["width_sum"]?>">
	<form name="F" method="post" enctype="multipart/form-data" action="<?=$_SERVER['PHP_SELF']?>?cmd=exec<?=$etc_key?>" onSubmit="return checkInput();">
	<tr><td colspan="100" bgcolor="#90A0B8"><img src="images/space.gif" height="2"></td></tr>

	<? if($Records["category_use"] == "1"){?>
	<tr><td colspan="100" background="<?=$Records["line_img"]?>"><img src="images/space.gif" height="1"></td></tr>

	<tr>
	<td align="center" bgcolor="#F6F6F6">분류<font color="Orange">*</font></td>
	<td>&nbsp;<select name="category">
	<?for($i=0;$i<count($Records["category"]);$i++){?>
	<option value="<?=trim($Records["category"][$i])?>"><?=trim($Records["category"][$i])?></option>
	<?}?>
	</select>
	</td>
	</tr>
	<?}?>

	<tr style="display:none">
	<td align="center" bgcolor="#F6F6F6">ID</td>
	<td>&nbsp;<input type="text" name="boarder_id" value="<?if($Sync_id) echo $Sync_id; else echo $_SERVER["REMOTE_ADDR"];?>" class="input" readonly></td>
	</tr>

	<tr><td colspan="100" background="<?=$Records["line_img"]?>"><img src="images/space.gif" height="1"></td></tr>

	<tr>
	<td align="center" bgcolor="#F6F6F6"><?=$Records["name_title"]?><font color="Orange">*</font></td>
	<td>&nbsp;<input type="text" name="boarder_name" value="<?=($Records["name_title"]=="Name")?$Sync_name:""?>" class="input"></td>
	</tr>

	<tr><td colspan="100" background="<?=$Records["line_img"]?>"><img src="images/space.gif" height="1"></td></tr>

	<?if(!$Sync_id){?>
	<tr>
	<td align="center" bgcolor="#F6F6F6">Password</td>
	<td>&nbsp;<input type="password" name="boarder_passwd" class="input"> (need to modify or delete this post )</td>
	</tr>

	<tr><td colspan="100" background="<?=$Records["line_img"]?>"><img src="images/space.gif" height="1"></td></tr>
	<?}?>

	<?if($_GET["link_style"]!="open"){?>
	<tr>
	<td align="center" bgcolor="#F6F6F6">Subject<font color="Orange">*</font></td>
	<td>&nbsp;<input type="text" name="subject" style="width:99%;" class="input"></td>
	</tr>

	<tr><td colspan="100" background="<?=$Records["line_img"]?>"><img src="images/space.gif" height="1"></td></tr>
	<?}?>

	<?if($Records["secret_use"]==1){?>
	<tr>
	<td align="center" bgcolor="#F6F6F6">비밀글</td>
	<td>&nbsp;<input type="password" name="secret_passwd" class="input"> (비밀글 적용시 비밀번호)</td>
	</tr>

	<tr><td colspan="100" background="<?=$Records["line_img"]?>"><img src="images/space.gif" height="1"></td></tr>
	<?}?>

	<script language="javascript">
	<!--
	function content_ctrl(f) {
		if(f=="vp" && document.F.content.rows < 50){
			document.F.content.rows = parseInt(document.F.content.rows) + 10;
		}
		if(f=="vm" && document.F.content.rows > 20){
			document.F.content.rows = parseInt(document.F.content.rows) - 10;
		}
	}
	-->
	</script>

	<tr>
	<td align="center" width="88" valign="top" bgcolor="#F6F6F6"><br>
	Messagebody<font color="Orange">*</font>
	</td>
	<td>
	<?if($Records["html_use"]==1){?>
	<input type="checkbox" name="html" value="y" onClick="br_check();">use html
	<span id="auto_br_id"><input type="checkbox" name="auto_br" value="y">use auto &lt;BR&gt; Tag</span>
	<?}?>
	<?if($Sync_level>49){?>
	<input type="checkbox" name="notice" value="y">important notice
	<?}?>
	<script language="javascript">
	<!--
	function br_check() {
		if(document.F.html.checked==true) {
			document.getElementById('auto_br_id').style.display='inline';
			document.getElementsByName('auto_br').checked=true;
		}else{
			document.getElementById('auto_br_id').style.display='none';
		}
	}
	br_check();
	//-->
	</script>
	<br>
	&nbsp;<textarea name="content" class="input" style="width:99%;height:200;overflow-y:visible;"></textarea>
	</td>
	</tr>

	<tr><td colspan="100" background="<?=$Records["line_img"]?>"><img src="images/space.gif" height="1"></td></tr>

	<?if($Records["link_use"]==1){?>
	<tr>
	<td align="center" bgcolor="#F6F6F6">link url</td>
	<td>
	&nbsp;<input type="text" name="link_1" style="width:99%;" class="input" value=""><br>
	&nbsp;<input type="text" name="link_2" style="width:99%;" class="input" value="">
	</td>
	</tr>
	
	<tr><td colspan="100" background="<?=$Records["line_img"]?>"><img src="images/space.gif" height="1"></td></tr>
	<?}?>

	<?if($Records["file_use"]=="1"){?>
		<script language="javascript">
		<!--
		function file_ctrl(f) {
			if(f=="p"){
				for(i=0;i<<?=$Records["file_count"]?>;i++){
					if(document.getElementById('f_num_'+i).style.display=="none") {
						document.getElementById('f_num_'+i).style.display="inline";
						break;
					}
				}
			}
			if(f=="m"){
				for(i=<?=$Records["file_count"]-1?>;i>1;i--){
					if(document.getElementById('f_num_'+i).style.display=="inline") {
						document.getElementById('f_num_'+i).style.display="none";
						eval ("document.F.file_up_"+i).outerHTML = "<input class=\"input\" type=\"file\" size=\"20\" name=\"file_up_"+i+"\">";
						break;
					}
				}
			}
		}

		function extCheck(str) {
			image_ext = ["jpg","png","gif"];
			movie_ext = ["asf","wmv","mpg","mpeg","wma","mp3","swf"];
			for (i in image_ext) if (str.split(".").pop().toLowerCase() == image_ext[i]) return "image_File";
			for (i in movie_ext) if (str.split(".").pop().toLowerCase() == movie_ext[i]) return "movie_File";
			return false;
		}

		function img_ctrl(fnum,val,event) {
			var fnum = parseInt(fnum);
			if(extCheck(eval("document.F.file_up_"+fnum).value)=="image_File"){
				eval("document.F.file_insert_"+fnum).style.color = "";
				if(navigator.appName == "Microsoft Internet Explorer"){
					eval("document.F.file_view_"+fnum).style.color = "";
				}else{
					eval("document.F.file_view_"+fnum).style.color = "#888888";
				}
				document.getElementById('img_'+fnum).src = eval("document.F.file_up_"+fnum).value;
				if(val=="inline" || val=="none"){
					if(navigator.appName == "Microsoft Internet Explorer"){
						document.getElementById('div_img_'+fnum).style.display = val;
						var cutWidth = 200;
						var cutHeight = 150;
						var iMgWidth = document.getElementById('img_'+fnum).width;
						var iMgHeight = document.getElementById('img_'+fnum).height;
						if(Math.round(iMgWidth*cutHeight/iMgHeight) > cutWidth) {
							Fixwidth=cutWidth;
							Fixheight = Math.round(iMgHeight*cutWidth/iMgWidth);
						}else{
							Fixheight = cutHeight;
							Fixwidth = Math.round(iMgWidth*cutHeight/iMgHeight);
						}
						document.getElementById('img_'+fnum).width = Fixwidth;
						document.getElementById('div_img_'+fnum).style.left = event.clientX - Fixwidth;
						document.getElementById('div_img_'+fnum).style.top = event.clientY + document.body.scrollTop - Fixheight;
					}else{
						alert("Sorry! Preview is Microsoft Internet Explorer Only.");
					}
				}else if(val=="insert"){
					document.F.content.focus();
					document.F.content.value = document.F.content.value + "\n\r[_" + eval("document.F.file_up_"+fnum).value.split("\\").pop() + ", left_] ";
					document.F.html.checked=true;
					document.F.auto_br.checked=true;
					br_check();
				}
			}else if(extCheck(eval("document.F.file_up_"+fnum).value)=="movie_File"){
				eval("document.F.file_insert_"+fnum).style.color = "";
				if(val=="insert") {
					document.F.content.focus();
					document.F.content.value = document.F.content.value + "\n\r[_" + eval("document.F.file_up_"+fnum).value.split("\\").pop() + "_] ";
					document.F.html.checked=true;
					document.F.auto_br.checked=true;
					br_check();
				}
			}else{
				eval("document.F.file_view_"+fnum).style.color = "#888888";
				eval("document.F.file_insert_"+fnum).style.color = "#888888";
			}
		}
		//-->
		</script>

		<tr>
		<td align="center" bgcolor="#F6F6F6">file size</td>
		<td>
		<?for($f=0;$f<$Records["file_count"];$f++){?>
			<span id="f_num_<?=$f?>" <?if($f>1){?>style="display:none;"<?}?>>
			&nbsp;<?=substr($f+101,-2)?>:
			<input class="input" type="file" size="20" name="file_up_<?=$f?>"onChange="img_ctrl('<?=$f?>','')">
			<!--within <?=$Records["file_quota"]?>Mbyte-->
			<div id="div_img_<?=$f?>" style="position:absolute;left:250;top:300;z-index:10<?=$f?>;display:none;"><img id="img_<?=$f?>" name="img_<?=$f?>">	</div>
			<input type="button" value="insert" name="file_insert_<?=$f?>" class="button" onClick="img_ctrl('<?=$f?>','insert')" style="color:#888888">
			<input type="button" value="view" name="file_view_<?=$f?>" class="button" onMouseover="img_ctrl('<?=$f?>','inline',event)" onMouseout="img_ctrl('<?=$f?>','none',event)" style="color:#999999">

			<?if($f==0){?>
				<img src="images/space.gif" height="10" width="2">
				<img src="images/arrow_up.gif" onClick="file_ctrl('m');" onmouseOver="helpOn('less',50,20);" onmouseOut="helpOff();">
				<img src="images/arrow_down.gif" onClick="file_ctrl('p');" onmouseOver="helpOn('more',50,20);" onmouseOut="helpOff();">
				<?
				$helptext = "<b>Help for [Insert] Button</b><br>"; 
				$helptext .= "[Insert] button(function) will automatically activate with folowing file extensions.<br>"; 
				$helptext .= "Image : jpg, gif, png<br>"; 
				$helptext .= "Movie : asf, wmv, mpg, mpeg<br>"; 
				$helptext .= "Music : wma, mp3<br>"; 
				$helptext .= "Flash : swf<br>"; 
				$helptext .= "Uploaded files appear top position in the content automatically. "; 
				$helptext .= "For other position, press [Insert] button with anywhere in the content. "; 
				$helptext .= "Uploaded files display [_filename_] in Edit Mode and Photo files appear [_filename_, left]. "; 
				$helptext .= "Right Alignment is possible. Change [left] to [right] in [_filename_, left_]. <br>"; 
				$helptext .= "Flash file display size can adjust with [_filename,Width,Height_]. "; 
				$helptext .= "Use [AutoBR] for manual file insert.";
				?>
				<img src="images/help.gif" align="absmiddle" onmouseOver="helpOn('<?=$helptext?>',350,280);" onmouseOut="helpOff();">
			<?}?>
			<br>
			</span>
		<?}?>
		</td>
		</tr>

		<tr><td colspan="100" background="<?=$Records["line_img"]?>"><img src="images/space.gif" height="1"></td></tr>
	<?}?>
	</table>

	<table cellpadding="0" cellspacing="0"><tr><td><img src="images/space.gif" height="5"></td></tr></table>

	<table cellSpacing="0" cellpadding="0" border="0" width="<?=$Records["width_sum"]?>">
	<tr>
	<td align="right">
	<input type="image" src='images/btn_submit_eng.gif' align=absmiddle style="cursor:pointer">
	<!--<a href="javascript:location.reload();"><img src='images/zbtn_reset.gif' border=0 align=absmiddle></a>-->
	<? //echo $Records["readlevel"] . "," . $Sync_level?>
	<!--<?if($Records["readlevel"]<=$Sync_level || $Sync_level==""){?><a href="<?=($_GET["link_style"]=="open")?"open.php":"list.php"?>?<?=$etc_key?>"><img src='images/btn_list_eng.gif' border=0 align=absmiddle></a><?}?>-->
	</td>
	</tr>
	</form>
	</table>

</td>
</tr>
</table>

<?=$Records["tail_note"]?>
<?=eval($Records["tail_php"])?>

<? ob_flush(); ?>
