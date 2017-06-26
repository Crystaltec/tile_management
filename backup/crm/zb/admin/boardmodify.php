<?
set_time_limit(0);
include "../../include/common.inc";
include "../../include/dbconn.inc";
include "../../include/user_functions.inc";
include "../include/board_config.inc";

## 접근권한 설정
if($Sync_level < 100) {
	echo"<script>alert('본 페이지를 열람할 권한이 없습니다.')</script>";
	echo"<script>history.go(-1)</script>";
	exit;
}

// Get폼값설정
$etc_key  = "";
if($find_value) $etc_key .= "&find_key=".$_GET["find_key"];
if($find_value) $etc_key .= "&find_value=".$_GET["find_value"];
if($sort_value) $etc_key .= "&sort_key=".$_GET["sort_key"];
if($sort_value) $etc_key .= "&sort_value=".$_GET["sort_value"];

if($_GET["cmd"] == "thumb_update") {

	$Query  = " SELECT uid, userfile_extra FROM ".$_GET["boardid"]." ORDER BY uid ASC";
	$cnn = mysql_query($Query) or exit(mysql_error());
	while($rst = mysql_fetch_assoc($cnn)) {
		$first_img = explode("|",$rst["userfile_extra"]);
		$rst[] = $first_img[0];
		$Records = array_merge($Records, array($rst));
		$ext = strtolower(strrchr($first_img[0],"."));
		if($ext==".jpg" || $ext==".png" || $ext==".gif"){
			$size = getimagesize($upload_root."/".$_GET["boardid"]."/".$first_img[0]);
			
			if($size[0]>$size[1]) {
				$rewidth = $_GET["new_size"];
				$reheight = $_GET["new_size"] * $size[1] / $size[0];
			}else{
				$rewidth = $_GET["new_size"] * $size[0] / $size[1];
				$reheight = $_GET["new_size"];
			}
			$img_file_name = $upload_root."/".$_GET["boardid"]."/".$first_img[0];			
			if($ext==".gif") $dstimg = ImageCreate($rewidth,$reheight);
			else $dstimg = ImageCreatetruecolor($rewidth,$reheight);
			if($ext==".jpg") $srcimg = ImageCreateFromJPEG($img_file_name);
			if($ext==".gif") $srcimg = ImageCreateFromGIF($img_file_name);
			if($ext==".png") $srcimg = ImageCreateFromPNG($img_file_name);
			Imagecopyresampled($dstimg, $srcimg,0,0,0,0,$rewidth,$reheight,ImageSX($srcimg),ImageSY($srcimg));
			if($ext==".jpg") ImageJPEG($dstimg,$upload_root."/".$_GET["boardid"]."/thumb_".$first_img[0],90);
			if($ext==".gif") ImageGIF($dstimg,$upload_root."/".$_GET["boardid"]."/thumb_".$first_img[0],90);
			if($ext==".png") ImagePNG($dstimg,$upload_root."/".$_GET["boardid"]."/thumb_".$first_img[0],90);
		}
	}

	echo "<script>alert('모든 게시물의 첫번째 썸네일 이미지를 갱신하였습니다.')</script>";
	echo "<meta http-equiv='Refresh' content='0; URL=".$PHP_SELP."?boardid=".$_GET["boardid"]."&startPage=$startPage$etc_key'>";
	exit;
}

if($_GET["cmd"] == "exec") {

	if(!$_POST["file_quota"]) $_POST["file_quota"] = 5;

	$Board["boardname"]		=$_POST["boardname"];
	$Board["readlevel"]			=$_POST["readlevel"];
	$Board["writelevel"]			=$_POST["writelevel"];
	$Board["file_quota"]			=$_POST["file_quota"];
	$Board["file_count"]			=$_POST["file_count"];
	$Board["title_img"]			=$_POST["title_img"];
	$Board["line_img"]			=$_POST["line_img"];
	$Board["link_style"]			=$_POST["link_style"];
	$Board["width_sum"]		=$_POST["width_sum"];
	$Board["no_use"]			=$_POST["no_use"];
	$Board["no_wid"]				=$_POST["no_wid"];
	$Board["img_icon_use"]	=$_POST["img_icon_use"];
	$Board["img_icon_wid"]		=$_POST["img_icon_wid"];
	$Board["img_icon_size"]	=$_POST["img_icon_size"];
	$Board["category_use"]	=$_POST["category_use"];
	$Board["category_wid"]		=$_POST["category_wid"];
	$Board["subject_use"]		=$_POST["subject_use"];
	$Board["subject_wid"]		=$_POST["subject_wid"];
	$Board["name_use"]			=$_POST["name_use"];
	$Board["name_wid"]			=$_POST["name_wid"];
	$Board["name_title"]			=$_POST["name_title"];
	$Board["date_use"]			=$_POST["date_use"];
	$Board["date_wid"]			=$_POST["date_wid"];
	$Board["file_use"]			=$_POST["file_use"];
	$Board["download_use"]	=$_POST["download_use"];
	$Board["comment_use"]	=$_POST["comment_use"];
	$Board["reply_use"]			=$_POST["reply_use"];
	$Board["secret_use"]		=$_POST["secret_use"];
	$Board["link_use"]			=$_POST["link_use"];
	$Board["html_use"]			=$_POST["html_use"];
	$Board["vote_use"]			=$_POST["vote_use"];
	$Board["vote_wid"]			=$_POST["vote_wid"];
	$Board["hit_use"]				=$_POST["hit_use"];
	$Board["hit_wid"]				=$_POST["hit_wid"];
	$Board["search_use"]		=$_POST["search_use"];
	$Board["row_count"]		=$_POST["row_count"];
	$Board["col_count"]			=$_POST["col_count"];
	$Board["col_padding"]		=$_POST["col_padding"];
	$Board["category"]			=$_POST["category"];
	$Board["head_note"]		=$_POST["head_note"];
	$Board["tail_note"]			=$_POST["tail_note"];
	$Board["head_php"]			=$_POST["head_php"];
	$Board["tail_php"]			=$_POST["tail_php"];

	$Query = updateQuery($Board, "zb", 'boardid', $_POST["boardid"]);
	//echo $Query;
	//$Query = htmlspecialchars($Query);
	//echo $Query;
	//exit;
	@mysql_query("set names utf8"); 
	mysql_query($Query) or die(mysql_error());

	

	echo "<meta http-equiv='Refresh' content='0; URL=boardmodify.php?boardid=".$_POST["boardid"]."&startPage=$startPage$etc_key'>";
	exit;
}

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
?>

<html>
<head>
<title><?echo($company)?></title>
<meta http-equiv="Content-Type" content="text/html"; charset="utf-8">
<link rel="stylesheet" type="text/css" href="/include/admin.css">

<script language="javascript">
<!--
function checkInput() {
	if (!document.F.boardname.value) {
		alert("게시판 이름 항목을 입력하세요!");
		document.F.boardname.focus();
		return false;
	}
}

function calc() {
	var sum_value = 0;
	if(document.F.link_style[3].checked==true) { //갤러리게시판
		document.F.img_icon_use.checked=true;		
		sum_value = (document.F.img_icon_wid.value * document.F.col_count.value) + (document.F.col_padding.value * document.F.col_count.value - (1 * document.F.col_padding.value));
		document.F.width_sum.value = sum_value;
	}else{
		if(document.F.no_use.checked==true)			{sum_value = sum_value + parseInt(document.F.no_wid.value);				}
		if(document.F.img_icon_use.checked==true)	{sum_value = sum_value + parseInt(document.F.img_icon_wid.value);	}
		if(document.F.category_use.checked==true)	{sum_value = sum_value + parseInt(document.F.category_wid.value);		}
		if(document.F.subject_use.checked==true)	{sum_value = sum_value + parseInt(document.F.subject_wid.value);		}
		if(document.F.name_use.checked==true)		{sum_value = sum_value + parseInt(document.F.name_wid.value);			}
		if(document.F.date_use.checked==true)		{sum_value = sum_value + parseInt(document.F.date_wid.value);			}
		if(document.F.vote_use.checked==true)		{sum_value = sum_value + parseInt(document.F.vote_wid.value);			}
		if(document.F.hit_use.checked==true)			{sum_value = sum_value + parseInt(document.F.hit_wid.value);				}
		document.F.width_sum.value = sum_value;
	}
}

function dim() {
	if(document.F.link_style[0].checked==true) { //일반게시판
		document.getElementById('colgroup').style.display = "none";
		document.getElementById('replygroup').style.display = "inline";
		document.getElementById('commentgroup').style.display = "inline";
		document.getElementById('categorygroup').style.display = "inline";
		document.getElementById('votegroup').style.display = "inline";
		document.getElementById('hitgroup').style.display = "inline";
		document.getElementById('img_icongroup').style.display = "inline";		
		document.getElementsByName('title_img')[0].style.display = "inline";
		document.getElementsByName('line_img')[0].style.display = "inline";
		document.getElementsByName('no_wid')[0].style.display = "inline";
		document.getElementsByName('category_wid')[0].style.display = "inline";
		document.getElementsByName('subject_wid')[0].style.display = "inline";
		document.getElementsByName('name_wid')[0].style.display = "inline";
		document.getElementsByName('date_wid')[0].style.display = "inline";
		document.getElementsByName('vote_wid')[0].style.display = "inline";
		document.getElementsByName('hit_wid')[0].style.display = "inline";
	}
	if(document.F.link_style[1].checked==true) { //롤다운게시판
		document.getElementById('colgroup').style.display = "none";
		document.getElementById('replygroup').style.display = "none";
		document.getElementById('commentgroup').style.display = "none";
		document.getElementById('categorygroup').style.display = "inline";
		document.getElementById('votegroup').style.display = "inline";
		document.getElementById('hitgroup').style.display = "inline";
		document.getElementById('img_icongroup').style.display = "inline";	
		document.getElementsByName('title_img')[0].style.display = "inline";
		document.getElementsByName('line_img')[0].style.display = "inline";
		document.getElementsByName('category_wid')[0].style.display = "inline";
		document.getElementsByName('subject_wid')[0].style.display = "inline";
		document.getElementsByName('name_wid')[0].style.display = "inline";
		document.getElementsByName('date_wid')[0].style.display = "inline";
		document.getElementsByName('vote_wid')[0].style.display = "inline";
		document.getElementsByName('hit_wid')[0].style.display = "inline";
	}
	if(document.F.link_style[2].checked==true) { //외부링크게시판
		document.getElementById('colgroup').style.display = "none";
		document.getElementById('replygroup').style.display = "none";
		document.getElementById('commentgroup').style.display = "none";
		document.getElementById('categorygroup').style.display = "inline";
		document.getElementById('votegroup').style.display = "inline";
		document.getElementById('hitgroup').style.display = "inline";
		document.getElementById('img_icongroup').style.display = "inline";	
		document.getElementsByName('title_img')[0].style.display = "inline";
		document.getElementsByName('line_img')[0].style.display = "inline";
		document.getElementsByName('category_wid')[0].style.display = "inline";
		document.getElementsByName('subject_wid')[0].style.display = "inline";
		document.getElementsByName('name_wid')[0].style.display = "inline";
		document.getElementsByName('date_wid')[0].style.display = "inline";
		document.getElementsByName('vote_wid')[0].style.display = "inline";
		document.getElementsByName('hit_wid')[0].style.display = "inline";
	}
	if(document.F.link_style[3].checked==true) { //갤러리게시판
		document.getElementById('colgroup').style.display = "inline";
		document.getElementById('replygroup').style.display = "inline";
		document.getElementById('commentgroup').style.display = "inline";
		document.getElementById('categorygroup').style.display = "inline";
		document.getElementById('votegroup').style.display = "inline";
		document.getElementById('hitgroup').style.display = "inline";
		document.getElementById('img_icongroup').style.display = "inline";	
		document.getElementsByName('title_img')[0].style.display = "none";
		document.getElementsByName('line_img')[0].style.display = "none";
		document.getElementsByName('no_wid')[0].style.display = "none";
		document.getElementsByName('category_wid')[0].style.display = "none";
		document.getElementsByName('subject_wid')[0].style.display = "none";
		document.getElementsByName('name_wid')[0].style.display = "none";
		document.getElementsByName('date_wid')[0].style.display = "none";
		document.getElementsByName('vote_wid')[0].style.display = "none";
		document.getElementsByName('hit_wid')[0].style.display = "none";
	}
	if(document.F.link_style[4].checked==true) { //펼침게시판
		document.getElementById('colgroup').style.display = "none";
		document.getElementById('replygroup').style.display = "none";
		document.getElementById('commentgroup').style.display = "inline";
		document.getElementById('categorygroup').style.display = "none";
		document.getElementById('votegroup').style.display = "none";
		document.getElementById('hitgroup').style.display = "none";
		document.getElementById('img_icongroup').style.display = "none";	
		document.getElementsByName('title_img')[0].style.display = "inline";
		document.getElementsByName('line_img')[0].style.display = "inline";
		document.getElementsByName('no_wid')[0].style.display = "inline";
		document.getElementsByName('category_wid')[0].style.display = "none";
		document.getElementsByName('subject_wid')[0].style.display = "inline";
		document.getElementsByName('name_wid')[0].style.display = "inline";
		document.getElementsByName('date_wid')[0].style.display = "inline";
		document.getElementsByName('vote_wid')[0].style.display = "none";
		document.getElementsByName('hit_wid')[0].style.display = "none";
	}
	calc();
}

function confirmThumb(){
	if(confirm('썸네일 이미지를 지정한 사진크기에 맞게 일괄 변경하시겠습니까?\n\n이 작업은 시간이 오래 걸릴 수 있습니다.')){
		var new_size = document.getElementsByName('img_icon_size')[0].value;
		location.href="<?=$_SERVER['PHP_SELF']?>?boardid=<?=$_GET["boardid"]?>&cmd=thumb_update&new_size="+new_size+"startPage=<?=$startPage?><?=$etc_key?>";
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

</head>

<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="dim()">
<?
    include("$ABS_DIR/zb/admin/menu.html");
?>
<br>

<center>

<table width="1000" height="20" border="0" cellspacing="0" cellpadding="0">
<tr>
<td><b>게시판 수정</b></td>
</tr>
</table>

<table width="1000" border="1" cellpadding="0" cellspacing="0" bordercolor="#336699" style="border-collapse:collapse">

<col width="140"></col>
<col width="355"></col>
<col width="140"></col>
<col width="355"></col>

<form name="F" method="post" action="<?=$_SERVER['PHP_SELF']?>?cmd=exec&startPage=<?=$startPage?><?=$etc_key?>" onSubmit="return checkInput()">
<input type="hidden" name="boardid" value="<?=$_GET["boardid"]?>">
<tr>
	<td bgcolor="b8c8ee" align="center" style="padding:5px 0;">게시판 ID</td>
	<td bgcolor="e0eeff" style="padding:2px 3px;"><b><?=$Records["boardid"]?></b> (<?=$Records["signdate"]?>)</td>
	<td bgcolor="b8c8ee" align="center" style="padding:5px 0;">게시판 이름</td>
	<td bgcolor="e0eeff" style="padding:2px 3px;"><input type="text" name="boardname" value="<?=$Records["boardname"]?>"></td>
</tr>

<tr>
	<td bgcolor="b8c8ee" align="center" style="padding:5px 0;">게시판 특성</td>
	<td bgcolor="e0eeff" style="padding:2px 3px;">
		<input type="radio" name="link_style" value="normal" <?if($Records["link_style"] == "normal") echo "checked";?> onClick="dim()">일반
		<input type="radio" name="link_style" value="rolldown" <?if($Records["link_style"] == "rolldown") echo "checked";?> onClick="dim()">롤다운
		<input type="radio" name="link_style" value="outerlink" <?if($Records["link_style"] == "outerlink") echo "checked";?> onClick="dim()">외부링크
		<input type="radio" name="link_style" value="gallery" <?if($Records["link_style"] == "gallery") echo "checked";?> onClick="dim()">갤러리
		<input type="radio" name="link_style" value="open" <?if($Records["link_style"] == "open") echo "checked";?> onClick="dim()">펼침
		<?
		$helptext  = "◈일반 게시판<br>";
		$helptext .= "보편적인 스타일의 게시판입니다.<br><br>";
		$helptext .= "◈롤다운 게시판<br>";
		$helptext .= "목록에서 제목을 클릭하면 읽기화면으로 이동하지 않고 목록의 제목아래에 내용을 롤다운하여 보여줍니다. FAQ등에 사용됩니다.<br><br>";
		$helptext .= "◈외부링크 게시판<br>";
		$helptext .= "글내용에 URL만 적은 후 목록에서 글제목을 클릭하면 URL로 이동합니다. 관리자 전용으로서 추천사이트, 뉴스링크 등에 사용됩니다.<br><br>";
		$helptext .= "◈갤러리 게시판<br>";
		$helptext .= "목록을 갤러리 스타일로 보여줍니다. 포토앨범, 작품집 등에 사용됩니다.<br><br>";
		$helptext .= "◈펼침 게시판<br>";
		$helptext .= "목록에 제목 대신 내용을 보여줍니다. 방명록 등에 사용됩니다.<br>";
		$helptext .= "펼침게시판은 [list.php] 대신 펼침목록 [open.php]가 따로 제공되므로 이 파일을 초기화면으로 링크해야합니다.";
		?>
		<img src="../images/help.gif" align="absmiddle" onmouseOver="helpOn('<?=$helptext?>',350,400);" onmouseOut="helpOff();">
	</td>
	<td bgcolor="b8c8ee" align="center" style="padding:5px 0;">권한</td>
	<td bgcolor="e0eeff" style="padding:2px 3px;">
		&nbsp;※ 읽기:
		<select name="readlevel">
		<? for ($i=0;$i<count($array_level[0]);$i++) { ?><option value="<?=$array_level[0][$i]?>"<? if ($Records["readlevel"] == $array_level[0][$i]) { echo " selected"; } ?>><?=$array_level[1][$i]?></option><? echo"\n"; }?>
		</select>
		&nbsp;※ 쓰기:
		<select name="writelevel">
		<? for ($i=0;$i<count($array_level[0]);$i++) { ?><option value="<?=$array_level[0][$i]?>"<? if ($Records["writelevel"] == $array_level[0][$i]) { echo " selected"; } ?>><?=$array_level[1][$i]?></option><? echo"\n"; }?>
		</select>
	</td>
</tr>

<tr>
	<td bgcolor="b8c8ee" align="center" style="padding:5px 0;">업로드 기능</td>
	<td bgcolor="e0eeff" style="padding:2px 3px;">
		<input type="checkbox" name="file_use" value="1" <?if($Records["file_use"] == "1") echo "checked";?>>사용
		&nbsp;※ 용량: (파일당) <input type="text" name="file_quota" size="3" value="<?=$Records["file_quota"]?>"> MB 미만
		&nbsp;※ 수량: <input type="text" name="file_count" size="3" value="<?=$Records["file_count"]?>"> 개
	</td>
	<td bgcolor="b8c8ee" align="center" style="padding:5px 0;">부가기능1</td>
	<td bgcolor="e0eeff" style="padding:2px 3px;">
		<input type="checkbox" name="download_use" value="1" <?if($Records["download_use"] == "1") echo "checked";?>>다운로드 허용
		<img src="../images/help.gif" align="absmiddle" onmouseOver="helpOn('체크를 해제하면 읽기화면의 상단에 [파일다운로드] 줄이 표시되지 않습니다.',350,50);" onmouseOut="helpOff();">
		<input type="checkbox" name="link_use" value="1" <?if($Records["link_use"] == "1") echo "checked";?>>작성자 링크 사용
		<input type="checkbox" name="html_use" value="1" <?if($Records["html_use"] == "1") echo "checked";?>>본문 HTML 허용
	</td>
</tr>
<tr>
	<td bgcolor="b8c8ee" align="center" style="padding:5px 0;">분류 종류<br>( , 로 구분)</td>
	<td colspan="3" bgcolor="e0eeff" style="padding:2px 3px;">
		<textarea name="category" style="width:99%;height:42;overflow-y:visible;" class="textarea"><?=$Records["category"]?></textarea><br>
	</td>
</tr>

<tr><td colspan="4" height="5" bgcolor="#FFFFFF"></td></tr>

<tr>
	<td bgcolor="b8c8ee" align="center" style="padding:5px 0;">페이지당 게시물</td>
	<td bgcolor="e0eeff" style="padding:2px 3px;">
		<input type="text" name="row_count" size="4" value="<?=$Records["row_count"]?>"> 줄 &nbsp;
		<span id="colgroup">
		<input type="text" name="col_count" size="4" value="<?=$Records["col_count"]?>" onKeyup="calc()"> 열 &nbsp;
		열간격: <input type="text" name="col_padding" size="4" value="<?=$Records["col_padding"]?>" onKeyup="calc()"> 픽셀 &nbsp;
		</span>
	</td>
	<td bgcolor="b8c8ee" align="center" style="padding:5px 0;">부가기능2</td>
	<td bgcolor="e0eeff" style="padding:2px 3px;">
		<span id="commentgroup">
		<input type="checkbox" name="comment_use" value="1" <?if($Records["comment_use"] == "1") echo "checked";?>>덧글쓰기 사용
		</span>
		<span id="replygroup">
		<input type="checkbox" name="reply_use" value="1" <?if($Records["reply_use"] == "1") echo "checked";?>>답글쓰기 사용	
		<input type="checkbox" name="secret_use" value="1" <?if($Records["secret_use"] == "1") echo "checked";?>>비밀글 사용
		</span>
	</td>
</tr>

<tr>
	<td bgcolor="b8c8ee" align="center" style="padding:5px 0;">제목행 이미지</td>
	<td bgcolor="e0eeff" style="padding:2px 3px;">
		<input type="text" name="title_img" value="<?=$Records["title_img"]?>" size="30">
	</td>
	<td bgcolor="b8c8ee" align="center" style="padding:5px 0;">행구분 이미지</td>
	<td bgcolor="e0eeff" style="padding:2px 3px;">
		<input type="text" name="line_img" value="<?=$Records["line_img"]?>" size="30">
	</td>
</tr>

<tr>
	<td bgcolor="b8c8ee" align="center" style="padding:5px 0;">번호열 폭</td>
	<td bgcolor="e0eeff" style="padding:2px 3px;">
		<input type="text" name="no_wid" size="10" value="<?=$Records["no_wid"]?>" onKeyup="calc()">
		<input type="checkbox" name="no_use" value="1" onClick="calc()" <?if($Records["no_use"] == "1") echo "checked";?>>보임
	</td>
	<td bgcolor="b8c8ee" align="center" style="padding:5px 0;">사진열 폭</td>
	<td bgcolor="e0eeff" style="padding:2px 3px;">
		<span id="img_icongroup">
		<input type="text" name="img_icon_wid" size="10" value="<?=$Records["img_icon_wid"]?>" onKeyup="calc()">
		<input type="checkbox" name="img_icon_use" value="1" onClick="calc()" <?if($Records["img_icon_use"] == "1") echo "checked";?>>보임
		&nbsp;※ 사진크기: <input type="text" name="img_icon_size" size="3" value="<?=$Records["img_icon_size"]?>" onClick="calc()">
		<input type="button" value="썸네일갱신" class="button" onClick="confirmThumb();">
		</span>
	</td>
</tr>
<tr>
	<td bgcolor="b8c8ee" align="center" style="padding:5px 0;">분류열 폭</td>
	<td bgcolor="e0eeff" style="padding:2px 3px;">
		<span id="categorygroup">
		<input type="text" name="category_wid" size="10" value="<?=$Records["category_wid"]?>" onKeyup="calc()">
		<input type="checkbox" name="category_use" value="1" onClick="calc()" <?if($Records["category_use"] == "1") echo "checked";?>>보임
		</span>
	</td>
	<td bgcolor="b8c8ee" align="center" style="padding:5px 0;">제목열 폭</td>
	<td bgcolor="e0eeff" style="padding:2px 3px;">
		<input type="text" name="subject_wid" size="10" value="<?=$Records["subject_wid"]?>" onKeyup="calc()">
		<input type="checkbox" name="subject_use" value="1" onClick="calc()" <?if($Records["subject_use"] == "1") echo "checked";?>>보임
	</td>
</tr>
<tr>
	<td bgcolor="b8c8ee" align="center" style="padding:5px 0;">이름열 폭</td>
	<td bgcolor="e0eeff" style="padding:2px 3px;">
		<input type="text" name="name_wid" size="10" value="<?=$Records["name_wid"]?>" onKeyup="calc()">
		<input type="checkbox" name="name_use" value="1" onClick="calc()" <?if($Records["name_use"] == "1") echo "checked";?>>보임 &nbsp;
		Title: <input type="text" name="name_title" size="10" value="<?=$Records["name_title"]?>">
		<img src="../images/help.gif" align="absmiddle" onmouseOver="helpOn('[이름]을 [출처] 혹은 [이벤트기간] 등으로 대체하여 보도자료 또는 이벤트안내 게시판 등으로 사용할 수 있습니다.',350,50);" onmouseOut="helpOff();">
	<td bgcolor="b8c8ee" align="center" style="padding:5px 0;">날짜열 폭</td>
	</td>
	<td bgcolor="e0eeff" style="padding:2px 3px;">
		<input type="text" name="date_wid" size="10" value="<?=$Records["date_wid"]?>" onKeyup="calc()">
		<input type="checkbox" name="date_use" value="1" onClick="calc()" <?if($Records["date_use"] == "1") echo "checked";?>>보임
	</td>
</tr>
<tr>
	<td bgcolor="b8c8ee" align="center" style="padding:5px 0;">추천열 폭</td>
	<td bgcolor="e0eeff" style="padding:2px 3px;">
		<span id="votegroup">
		<input type="text" name="vote_wid" size="10" value="<?=$Records["vote_wid"]?>" onKeyup="calc()">
		<input type="checkbox" name="vote_use" value="1" onClick="calc()" <?if($Records["vote_use"] == "1") echo "checked";?>>보임
		</span>
	</td>
	<td bgcolor="b8c8ee" align="center" style="padding:5px 0;">조회열 폭</td>
	<td bgcolor="e0eeff" style="padding:2px 3px;">
		<span id="hitgroup">
		<input type="text" name="hit_wid" size="10" value="<?=$Records["hit_wid"]?>" onKeyup="calc()">
		<input type="checkbox" name="hit_use" value="1" onClick="calc()" <?if($Records["hit_use"] == "1") echo "checked";?>>보임
		</span>
	</td>
</tr>
<tr>
	<td bgcolor="b8c8ee" align="center" style="padding:5px 0;">검색기능</td>
	<td bgcolor="e0eeff" style="padding:2px 3px;">
		<input type="checkbox" name="search_use" value="1" <?if($Records["search_use"] == "1") echo "checked";?>>검색기능 사용
	</td>
	<td bgcolor="b8c8ee" align="center" style="padding:5px 0;">폭 합계</td>
	<td bgcolor="e0eeff" style="padding:2px 3px;">
		<input type="text" name="width_sum" size="4" value="<?=$Records["width_sum"]?>" style="border:0px; background-color:#e0eeff; color:#666699;" readonly>
	</td>
</tr>

<tr><td colspan="4" height="5" bgcolor="#FFFFFF"></td></tr>

<tr>
	<td bgcolor="b8c8ee" align="center" style="padding:5px 0;">상단PHP<br>(PHP가능)</td>
	<td colspan="3" bgcolor="e0eeff" style="padding:2px 3px;">
		&lt;?<br>
		<textarea name="head_php" style="width:99%;height:70;overflow-y:visible;" class="textarea"><?=htmlspecialchars($Records["head_php"])?></textarea><br>
		?&gt;
	</td>
</tr>

<tr>
	<td bgcolor="b8c8ee" align="center" style="padding:5px 0;">상단Html<br>(Html가능)</td>
	<td colspan="3" bgcolor="e0eeff" style="padding:2px 3px;">
		<textarea name="head_note" style="width:99%;height:70;overflow-y:visible;" class="textarea"><?=htmlspecialchars($Records["head_note"])?></textarea><br>
		<-- 게시판 본문 시작 -->
	</td>
</tr>

<tr>
	<td bgcolor="b8c8ee" align="center" style="padding:5px 0;">하단Html<br>(Html가능)</td>
	<td colspan="3" bgcolor="e0eeff" style="padding:2px 3px;">
		<-- 게시판 본문 종료 --><br>
		<textarea name="tail_note" style="width:99%;height:70;overflow-y:visible;" class="textarea"><?=htmlspecialchars($Records["tail_note"])?></textarea>
	</td>
</tr>

<tr>
	<td bgcolor="b8c8ee" align="center" style="padding:5px 0;">하단PHP<br>(PHP가능)</td>
	<td colspan="3" bgcolor="e0eeff" style="padding:2px 3px;">
		&lt;?<br>
		<textarea name="tail_php" style="width:99%;height:70;overflow-y:visible;" class="textarea"><?=htmlspecialchars($Records["tail_php"])?></textarea><br>
		?&gt;
	</td>
</tr>

</table>

<table width=1000 border=0 cellspacing=0 cellpadding=0>
<tr>
	<td align="center" height="50">
		<input type="submit" value='작성완료' class="input">
		<input type="reset" value='새로고침' class="input">
		<input type="button" value='목록보기'onClick="javascript:location.href='boardlist.php?startPage=<?=$startPage?><?=$etc_key?>'" class="input">
	</td>
</tr>
</form>
</table>

</center>

</body>
</html>
