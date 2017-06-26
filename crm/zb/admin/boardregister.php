<?
include "../../include/common.inc";
include "../../include/dbconn.inc";
include "../../include/user_functions.inc";

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

if($_GET["cmd"] == "exec") {

	//업로드 디렉토리가 없으면 생성
	if(!is_dir($upload_root)) @mkdir($upload_root,0755);
	if(!is_dir($upload_root."/zb_".$_POST["boardid"])) @mkdir($upload_root."/zb_".$_POST["boardid"],0755);

	$query = "CREATE TABLE zb_".$_POST["boardid"]." (
		uid mediumint(9) unsigned NOT NULL auto_increment,
		fid mediumint(9) unsigned NOT NULL default '0',
		boarder_id varchar(50) NOT NULL default '',
		boarder_name varchar(50) NOT NULL default '',
		boarder_email varchar(50) default NULL,
		boarder_passwd varchar(50) default NULL,
		secret_passwd varchar(50) default NULL,
		category varchar(255) NOT NULL default '',
		subject varchar(255) NOT NULL default '',
		content text,
		signdate datetime NOT NULL default '0000-00-00 00:00:00',
		hit smallint(5) unsigned NOT NULL default '0',
		vote smallint(5) unsigned NOT NULL default '0',
		thread varchar(255) NOT NULL default '',
		link_1 varchar(255) default NULL,
		link_2 varchar(255) default NULL,
		userfile_name text,
		userfile_extra text,
		userfile_size text,
		userfile_download text,
		html char(3) NOT NULL default 'n',
		auto_br char(3) NOT NULL default 'n',
		notice char(3) NOT NULL default 'n',
		PRIMARY KEY  (uid)
	)";
	mysql_query($query) or die(mysql_error());

	$query = "ALTER TABLE zb_".$_POST["boardid"]." ADD INDEX index_subject (subject)";
	mysql_query($query) or die(mysql_error());

	$query = "CREATE TABLE zb_".$_POST["boardid"]."_comment (
		cmt_idx int(11) unsigned NOT NULL auto_increment,
		boardid varchar(50) NOT NULL default '',
		uid mediumint(9) unsigned NOT NULL default '0',
		commenter_name varchar(50) NOT NULL default '',
		commenter_id varchar(50) NOT NULL default '',
		commenter_passwd varchar(50) default NULL,
		comment_content text NOT NULL,
		signdate datetime NOT NULL default '0000-00-00 00:00:00',
		PRIMARY KEY  (cmt_idx)
	)";
	mysql_query($query) or die(mysql_error());

	$query = "ALTER TABLE zb_".$_POST["boardid"]."_comment ADD INDEX index_uid (uid)";
	mysql_query($query) or die(mysql_error());

	if(!$_POST["file_quota"]) $_POST["file_quota"] = 5;

	$Board["boardid"]			="zb_".$_POST["boardid"];
	$Board["boardname"]		=$_POST["boardname"];
	$Board["readlevel"]			=$_POST["readlevel"];
	$Board["writelevel"]			=$_POST["writelevel"];
	$Board["signdate"]			=date("Y-m-d H:i:s",time());
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

	$Query = insertQuery($Board, "zb");
	mysql_query($Query) or die(mysql_error() . "오류 : 데이타베이스에 기록하지 못했습니다.");

	echo("<meta http-equiv='Refresh' content='0; URL=boardlist.php?startPage=$startPage$etc_key'>");
}
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
	if (!document.F.boardid.value) {
		alert("게시판 아이디 항목을 입력하세요!");
		document.F.boardid.focus();
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

function help(){

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
<td><b>신규게시판 등록</b></td>
</tr>
</table>

<table width="1000" border="1" cellpadding="0" cellspacing="0" bordercolor="#336699" style="border-collapse:collapse">

<col width="140"></col>
<col width="355"></col>
<col width="140"></col>
<col width="355"></col>

<form name="F" method="post" action="<?=$_SERVER['PHP_SELF']?>?cmd=exec<?=$etc_key?>" onSubmit="return checkInput()">
<tr>
	<td bgcolor="b8c8ee" align="center" style="padding:5px 0;">게시판 ID</td>
	<td bgcolor="e0eeff" style="padding:2px 3px;">zb_<input type="text" name="boardid"> (영문,숫자)</td>
	<td bgcolor="b8c8ee" align="center" style="padding:5px 0;">게시판 이름</td>
	<td bgcolor="e0eeff" style="padding:2px 3px;"><input type="text" name="boardname"></td>
</tr>

<tr>
	<td bgcolor="b8c8ee" align="center" style="padding:5px 0;">게시판 특성</td>
	<td bgcolor="e0eeff" style="padding:2px 3px;">
		<input type="radio" name="link_style" value="normal" checked onClick="dim()">일반
		<input type="radio" name="link_style" value="rolldown" onClick="dim()">롤다운
		<input type="radio" name="link_style" value="outerlink" onClick="dim()">외부링크
		<input type="radio" name="link_style" value="gallery" onClick="dim()">갤러리
		<input type="radio" name="link_style" value="open" onClick="dim()">펼침
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
		<?
		for ($i=0;$i<count($array_level[0]);$i++) {
		echo "<option value='".$array_level[0][$i]."'>".$array_level[1][$i]."</option>\n";
		}
		?>
		</select>
		&nbsp;※ 쓰기:
		<select name="writelevel">
		<?
		for ($i=0;$i<count($array_level[0]);$i++) {
		echo "<option value='".$array_level[0][$i]."'>".$array_level[1][$i]."</option>\n";
		}
		?>
		</select>
	</td>
</tr>

<tr>
	<td bgcolor="b8c8ee" align="center" style="padding:5px 0;">업로드 기능</td>
	<td bgcolor="e0eeff" style="padding:2px 3px;">
		<input type="checkbox" name="file_use" value="1" checked>사용
		&nbsp;※ 용량: (파일당) <input type="text" name="file_quota" size="3" value="5"> MB 미만
		&nbsp;※ 수량: <input type="text" name="file_count" size="3" value="2"> 개
	</td>
	<td bgcolor="b8c8ee" align="center" style="padding:5px 0;">부가기능1</td>
	<td bgcolor="e0eeff" style="padding:2px 3px;">
		<input type="checkbox" name="download_use" value="1" checked>다운로드 허용
		<img src="../images/help.gif" align="absmiddle" onmouseOver="helpOn('체크를 해제하면 읽기화면의 상단에 [파일다운로드] 줄이 표시되지 않습니다.',350,50);" onmouseOut="helpOff();">
		<input type="checkbox" name="link_use" value="1" checked>작성자 링크 사용
		<input type="checkbox" name="html_use" value="1" checked>본문 HTML 허용
		
	</td>
</tr>

<tr>
	<td bgcolor="b8c8ee" align="center" style="padding:5px 0;">분류 종류<br>( , 로 구분)</td>
	<td colspan="3" bgcolor="e0eeff" style="padding:2px 3px;">
		<textarea name="category" style="width:99%;height:42;overflow-y:visible;" class="textarea"></textarea><br>
	</td>
</tr>

<tr><td colspan="4" height="5" bgcolor="#FFFFFF"></td></tr>

<tr>
	<td bgcolor="b8c8ee" align="center" style="padding:5px 0;">페이지당 게시물</td>
	<td bgcolor="e0eeff" style="padding:2px 3px;">
		<input type="text" name="row_count" size="4" value="20"> 줄 &nbsp;
		<span id="colgroup">
		<input type="text" name="col_count" size="4" value="4" onKeyup="calc()"> 열 &nbsp;
		열간격: <input type="text" name="col_padding" size="4" value="20" onKeyup="calc()"> 픽셀 &nbsp;
		</span>
	</td>
	<td bgcolor="b8c8ee" align="center" style="padding:5px 0;">부가기능2</td>
	<td bgcolor="e0eeff" style="padding:2px 3px;">
		<span id="commentgroup">
		<input type="checkbox" name="comment_use" value="1" checked>덧글쓰기 사용
		</span>
		<span id="replygroup">
		<input type="checkbox" name="reply_use" value="1" >답글쓰기 사용	
		<input type="checkbox" name="secret_use" value="1">비밀글 사용
		</span>
	</td>
</tr>

<tr>
	<td bgcolor="b8c8ee" align="center" style="padding:5px 0;">제목행 이미지</td>
	<td bgcolor="e0eeff" style="padding:2px 3px;">
		<input type="text" name="title_img" value="/zb/images/title_default.jpg" size="30">
	</td>
	<td bgcolor="b8c8ee" align="center" style="padding:5px 0;">행구분 이미지</td>
	<td bgcolor="e0eeff" style="padding:2px 3px;">
		<input type="text" name="line_img" value="/zb/images/line_default.gif" size="30">
	</td>
</tr>

<tr>
	<td bgcolor="b8c8ee" align="center" style="padding:5px 0;">번호열 폭</td>
	<td bgcolor="e0eeff" style="padding:2px 3px;">
		<input type="text" name="no_wid" size="10" value="70" onKeyup="calc()">
		<input type="checkbox" name="no_use" value="1" onClick="calc()" checked>보임
	</td>
	<td bgcolor="b8c8ee" align="center" style="padding:5px 0;">사진열 폭</td>
	<td bgcolor="e0eeff" style="padding:2px 3px;">
		<span id="img_icongroup">
		<input type="text" name="img_icon_wid" size="10" value="60" onKeyup="calc()">
		<input type="checkbox" name="img_icon_use" value="1" onClick="calc()">보임
		&nbsp;※ 사진크기: <input type="text" name="img_icon_size" size="3" value="48" onClick="calc()">
		</span>
	</td>
</tr>
<tr>
	<td bgcolor="b8c8ee" align="center" style="padding:5px 0;">분류열 폭</td>
	<td bgcolor="e0eeff" style="padding:2px 3px;">
		<span id="categorygroup">
		<input type="text" name="category_wid" size="10" value="100" onKeyup="calc()">
		<input type="checkbox" name="category_use" value="1" onClick="calc()">보임
		</span>
	</td>
	<td bgcolor="b8c8ee" align="center" style="padding:5px 0;">제목열 폭</td>
	<td bgcolor="e0eeff" style="padding:2px 3px;">
		<input type="text" name="subject_wid" size="10" value="465" onKeyup="calc()">
		<input type="checkbox" name="subject_use" value="1" onClick="calc()" checked>보임
	</td>
</tr>
<tr>
	<td bgcolor="b8c8ee" align="center" style="padding:5px 0;">이름열 폭</td>
	<td bgcolor="e0eeff" style="padding:2px 3px;">
		<input type="text" name="name_wid" size="10" value="100" onKeyup="calc()">
		<input type="checkbox" name="name_use" value="1" onClick="calc()" checked>보임 &nbsp;
		Title: <input type="text" name="name_title" size="10" value="이름">
		<img src="../images/help.gif" align="absmiddle" onmouseOver="helpOn('[이름]을 [출처] 혹은 [이벤트기간] 등으로 대체하여 보도자료 또는 이벤트안내 게시판 등으로 사용할 수 있습니다.',350,50);" onmouseOut="helpOff();">

	<td bgcolor="b8c8ee" align="center" style="padding:5px 0;">날짜열 폭</td>
	</td>
	<td bgcolor="e0eeff" style="padding:2px 3px;">
		<input type="text" name="date_wid" size="10" value="80" onKeyup="calc()">
		<input type="checkbox" name="date_use" value="1" onClick="calc()" checked>보임
	</td>
</tr>
<tr>
	<td bgcolor="b8c8ee" align="center" style="padding:5px 0;">추천열 폭</td>
	<td bgcolor="e0eeff" style="padding:2px 3px;">
		<span id="votegroup">
		<input type="text" name="vote_wid" size="10" value="70" onKeyup="calc()">
		<input type="checkbox" name="vote_use" value="1" onClick="calc()">보임
		</span>
	</td>
	<td bgcolor="b8c8ee" align="center" style="padding:5px 0;">조회열 폭</td>
	<td bgcolor="e0eeff" style="padding:2px 3px;">
		<span id="hitgroup">
		<input type="text" name="hit_wid" size="10" value="70" onKeyup="calc()">
		<input type="checkbox" name="hit_use" value="1" onClick="calc()">보임
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
<?
$head_php=<<<EOF
\$sub_num = "02";

@include "\$ABS_DIR/include/top.html";
echo "
<table width='950' border='0' cellspacing='0' cellpadding='0' style='margin-top:15px;'>
<td width='10'></td>
<!--레프트메뉴-->
<td width='210' valign='top'>
";
@include "\$ABS_DIR/include/left5.html";
echo "
</td>
<!--//레프트메뉴-->
<td width='15' valign='top'></td>
<td width='715' valign='top'>

<table width='100%' border='0' cellspacing='0' cellpadding='0' style='margin-top:13px;'>
<tr>
<!--타이틀-->
<td><img src='/images/title_08.jpg'></td>
<!--네비게이션-->
<td align='right' valign='bottom'>
<a href='/'><font color='#555555'>HOME</font></a>
> <a href='/zb/list.php?boardid=zb_notice'><font color='#555555'>커뮤니티</font></a>
> <font color='#0256B2'><b>자유게시판</b></font>
</td>
</tr>
<tr><td colspan='2' height='10'></td></tr>
<tr><td colspan='2' height='1' bgcolor='#999999'></td></tr>
<tr><td colspan='2' height='25'></td></tr>
</table>

<!--스테이지-->
";
EOF;
?>
	<textarea name="head_php" style="width:99%;height:70;overflow-y:visible;" class="textarea"><?=$head_php?></textarea><br>
	?&gt;
	</td>
</tr>

<tr>
	<td bgcolor="b8c8ee" align="center" style="padding:5px 0;">상단Html<br>(Html가능)</td>
	<td colspan="3" bgcolor="#e0eeff" style="padding:2px 3px;">
	<textarea name="head_note" style="width:99%;height:70;overflow-y:visible;" class="textarea"></textarea><br>
	<-- 게시판 본문 시작 -->
	</td>
</tr>

<tr>
	<td bgcolor="b8c8ee" align="center" style="padding:5px 0;">하단Html<br>(Html가능)</td>
	<td colspan="3" bgcolor="e0eeff" style="padding:2px 3px;">
	<-- 게시판 본문 종료 --><br>
<?
$tail_note =<<<EOF
echo "
<table cellpadding='0' cellspacing='0' border='0' align='right'><td>
<a href='http://ziwoo.net' target='_blank'>ziwooboard v2.0</a>
</td></table>
";
EOF;
?>
	<textarea name="tail_note" style="width:99%;height:70;overflow-y:visible;" class="textarea"><?=$tail_note?></textarea>
	</td>
</tr>

<tr>
	<td bgcolor="b8c8ee" align="center" style="padding:5px 0;">하단PHP<br>(PHP가능)</td>
	<td colspan="3" bgcolor="e0eeff" style="padding:2px 3px;">
	&lt;?<br>
<?
$tail_php =<<<EOF
echo "
<!--//스테이지-->
</td>
</table>
";

@include "\$ABS_DIR/include/bottom.html";

echo "
</body>
</html>
";
EOF;
?>
		<textarea name="tail_php" style="width:99%;height:70;overflow-y:visible;" class="textarea"><?=$tail_php?></textarea><br>
		?&gt;
	</td>
</tr>

</table>

<table width=1000 border=0 cellspacing=0 cellpadding=0>
<tr>
	<td align="center" height="50">
		<input type="submit" value='작성완료' class="input">
		<input type="reset" value='새로고침' class="input">
		<input type="button" value='목록보기'onClick="javascript:location.href='boardlist.php'" class="input">
	</td>
</tr>
</form>
</table>

</center>

</body>
</html>
