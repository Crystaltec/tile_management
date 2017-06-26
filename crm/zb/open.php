<?

ob_start();
include "../include/common.inc";
include "../include/dbconn.inc";
include "../include/user_functions.inc";
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

if($Records["link_style"]!=="open"){
	echo"<script>alert('이 게시판은 펼침게시판이 아닙니다.');</script>";
	echo"<script>history.go(-1);</script>";
	exit;
}

## 기본값설정
if(!$_GET["find_key_1"] && !$_GET["find_key_2"] && !$_GET["find_key_3"]) $_GET["find_key_2"]="boarder_name";
$_GET["find_value"] = ereg_replace("[?\.\"]","",str_replace(","," ",$_GET["find_value"]));

## Get폼값설정
$etc_key  = "";
$etc_key .= "&link_style=open";
$etc_key .= "&boardid=".$_GET["boardid"];
if($_GET["find_value"] && $_GET["find_key_1"]) $etc_key .= "&find_key_1=".$_GET["find_key_1"];
if($_GET["find_value"] && $_GET["find_key_2"]) $etc_key .= "&find_key_2=".$_GET["find_key_2"];
if($_GET["find_value"] && $_GET["find_key_3"]) $etc_key .= "&find_key_3=".$_GET["find_key_3"];
if($_GET["find_value"]) $etc_key .= "&find_value=".$_GET["find_value"];

## 접근권한 설정
if($Records["readlevel"]>0 && $Sync_level<$Records["readlevel"]) {
	echo"<script>location.replace('../zm/login.html')</script>";
	exit;
}

if($_GET["cmd"]=="cmt_write"){
	$Comm["boardid"]					= $_GET["boardid"];
	$Comm["uid"]							= $_GET["uid"];
	$Comm["commenter_name"]		= $_POST["commenter_name"];
	$Comm["commenter_id"]			= $_POST["commenter_id"];
	$Comm["commenter_passwd"]	= $_POST["commenter_passwd"];
	$Comm["comment_content"]		= htmlspecialchars($_POST["comment_content"]);
	$Comm["signdate"]					= date("Y-m-d H:i:s",time());

	$Query = insertQuery($Comm, $_GET["boardid"]."_comment");
	mysql_query($Query) or die(mysql_error() . 'DB에 기록하지 못했습니다.');

	echo "<meta http-equiv='Refresh' content='0; URL=open.php?startPage=".$_GET["startPage"].$etc_key."'>";
	exit;
}

if($_GET["cmd"]=="cmt_delete"){
	$Query  = " SELECT commenter_passwd FROM ".$_GET["boardid"]."_comment where cmt_idx='".$_GET["cmt_idx"]."'";
	$cmt_cnn = mysql_query($Query) or exit(mysql_error());
	$cmt_rst = mysql_fetch_assoc($cmt_cnn);
	if($Sync_level < 50){
		if(!$_POST["input_commenter_passwd"] || $_POST["input_commenter_passwd"] != $cmt_rst["commenter_passwd"]){
			echo"<script>alert('비밀번호가 틀립니다.')</script>";
			echo"<script>history.go(-1)</script>";
			exit;
		}
	}
	$Query = "delete from ".$_GET["boardid"]."_comment WHERE cmt_idx=".$_GET["cmt_idx"];
	mysql_query($Query) or die(mysql_error());

	echo "<meta http-equiv='Refresh' content='0; URL=open.php?startPage=".$_GET["startPage"].$etc_key."'>";
	exit;
}

## 토탈카운트
$Query  = "SELECT count(uid)";
$Query .= " FROM ".$_GET["boardid"];
$Query .= " where 1";
if($find_value){
	$afv = split("[[:space:]+]",$_GET["find_value"]);
	$Query .= " and (0";
	$ffv = null;
	for($i=0;$i<count($afv);$i++) $ffv .= " and ".$_GET["find_key_1"]." like '%".trim($afv[$i])."%'";
	if($_GET["find_key_1"])	 $Query .= " or (1 $ffv)";
	$ffv = null;
	for($i=0;$i<count($afv);$i++) $ffv .= " and ".$_GET["find_key_2"]." like '%".trim($afv[$i])."%'";
	if($_GET["find_key_2"])	 $Query .= " or (1 $ffv)";
	$ffv = null;
	for($i=0;$i<count($afv);$i++) $ffv .= " and ".$_GET["find_key_3"]." like '%".trim($afv[$i])."%'";
	if($_GET["find_key_3"])	 $Query .= " or (1 $ffv)";
	$Query .= ")";
}

$rst = mysql_fetch_assoc(mysql_query($Query));
$countTotalRecord = $rst["count(uid)"];
## 페이지 시작
$ViewPerPage				= ($Records["link_style"]!="gallery")?$Records["row_count"]:$Records["row_count"]*$Records["col_count"];
$startPage					= $_GET["startPage"];
$countTotalPage			= ceil($countTotalRecord / $ViewPerPage);

if($startPage == NULL || $startPage < 2) {
    $startPage  = 1;
    $startNo    = 0;
} else {
    $startNo = $ViewPerPage * ($startPage - 1);
}

if($countTotalPage > 10) {
    $startBlockPage = floor(($startPage - 1) / 10) * 10 + 1;

    $prevBlockPage = $startBlockPage - 1;
    $nextBlockPage = $startBlockPage + 10;
} else {
	$startBlockPage = 1;
}

$PageLinks = null;
if($prevBlockPage > 0) {
    $PageLinks .= "<A HREF=\"$_SERVER['PHP_SELF']?startPage=$prevBlockPage&$etc_key\">[Prev]</A>";
}

if($countTotalPage  > 10) $PageLinks .= "<A HREF=\"$_SERVER['PHP_SELF']?startPage=1&$etc_key\">[1]..</A> ";

for($i = 0, $j = 0; $j < $countTotalPage && $i < 10; $i ++) {
    $j = $startBlockPage + $i;

		if($startPage == $j) {
				$PageLinks .= "<B><font color='#FF8800'>[$j]</font></B>";
		} else {
				$PageLinks .= "<A HREF=\"$_SERVER['PHP_SELF']?startPage=$j&$etc_key\">[$j]</A>";
		}
}

if($countTotalPage  > 10) $PageLinks .= " <A HREF=\"$_SERVER['PHP_SELF']?startPage=$countTotalPage&$etc_key\">..[$countTotalPage]</A>";

if($nextBlockPage > 10 && $nextBlockPage < $countTotalPage) {
    $PageLinks .= "<A HREF=\"$_SERVER['PHP_SELF']?startPage=$nextBlockPage&$etc_key\">[Next]</A>";
}
## 페이지 종료

## 쿼리 시작
$Board_id["uid"]						= NULL;
$Board_id["fid"]						= NULL;
$Board_id["boarder_id"]				= NULL;
$Board_id["boarder_name"]		= NULL;
$Board_id["boarder_passwd"]		= NULL;
$Board_id["category"]				= NULL;
$Board_id["subject"]					= NULL;
$Board_id["content"]				= NULL;
$Board_id["signdate"]				= NULL;
$Board_id["hit"]						= NULL;
$Board_id["vote"]					= NULL;
$Board_id["thread"]					= NULL;
$Board_id["link_1"]					= NULL;
$Board_id["link_2"]					= NULL;
$Board_id["userfile_name"]		= NULL;
$Board_id["userfile_extra"]			= NULL;
$Board_id["userfile_size"]			= NULL;
$Board_id["userfile_download"]	= NULL;
$Board_id["html"]						= NULL;
$Board_id["auto_br"]				= NULL;
$Board_id["notice"]					= NULL;

$Query  = " SELECT " . selectQuery($Board_id, $_GET["boardid"]);
$Query .= " FROM ".$_GET["boardid"];
$Query .= " where 1";
if($find_value){
	$Query .= " and (0";
	$ffv = null;
	for($i=0;$i<count($afv);$i++) $ffv .= " and ".$_GET["find_key_1"]." like '%".trim($afv[$i])."%'";
	if($_GET["find_key_1"])	 $Query .= " or (1 $ffv)";
	$ffv = null;
	for($i=0;$i<count($afv);$i++) $ffv .= " and ".$_GET["find_key_2"]." like '%".trim($afv[$i])."%'";
	if($_GET["find_key_2"])	 $Query .= " or (1 $ffv)";
	$ffv = null;
	for($i=0;$i<count($afv);$i++) $ffv .= " and ".$_GET["find_key_3"]." like '%".trim($afv[$i])."%'";
	if($_GET["find_key_3"])	 $Query .= " or (1 $ffv)";
	$Query .= ")";
}
$Query .= " ORDER BY notice DESC, fid DESC, thread ASC";
$Query .= " LIMIT $startNo, $ViewPerPage";

$id_cnn = mysql_query($Query) or exit(mysql_error());
while($id_rst = mysql_fetch_assoc($id_cnn)) {
    $list_Records = array_merge($list_Records, array($id_rst));
}
?>

<?=eval($Records["head_php"])?>

<table width=<?=$Records["width_sum"]?> cellSpacing="0" cellpadding="0" border="0">
<td><?=$Records["head_note"]?></td>
</table>

<table cellSpacing="0" cellpadding="0" border="0" width="<?=$Records["width_sum"]?>">
<td align="left" valign="top">
<?if($Records["writelevel"]==0 || $Sync_level>=$Records["writelevel"]){?>
<a href="write.php?startPage=<?=$startPage?><?=$etc_key?>"><img src='images/zbtn_new.gif' border=0 align=absmiddle></a>
<?}?>
</td>
</table>

<table width=<?=$Records["width_sum"]?> cellSpacing="0" cellpadding="0" border="0" style="margin-top:10px">
<tr>
<td>

	<table cellpadding="0" cellspacing="0"><tr><td><img src='images/space.gif' height='2'></td></tr></table>

	<!--펼침목록-->

	<!-- 수정하기 버튼을 누른 경우 시작-->
	<script language="javascript">
	<!--
	function modify_movePopup(ff,xx,yy) { ff.left = xx; ff.top = yy;}
	function modify_Auth_Popup(uid,event) {
		document.M.action="modify.php?uid="+uid+"&startPage=<?=$_GET['startPage']?><?=$etc_key?>";
		var fvar = document.getElementById('modify_cate').style;
		var xvar = event.clientX -30;
		var yvar = event.clientY + document.body.scrollTop - 100;
			 if (yvar<20) yvar = 30;
		modify_movePopup(fvar,xvar,yvar);

		if (document.getElementById('modify_cate').style.display != "none"){
		document.getElementById('modify_cate').style.display = "none";
		}else{
		document.getElementById('modify_cate').style.display = "";
		}
	}
	//-->
	</script>

	<div id="modify_cate" style="position:absolute;left:250;top:300;z-index:100;display:none;">
		<script language="javascript">
		<!--
		function modify_checkInput() {
			if (!document.M.input_modifyer_passwd.value) {
				alert("비밀번호를 입력하세요.");
				document.M.input_modifyer_passwd.focus();
				return false;
			}
		}
		-->
		</script>

		<table width="150" height="60" border="1" cellspacing="0" cellpadding="0" bordercolor="#CCCCCC" style="border-collapse:collapse">
		<form name="M" method="post" onSubmit="return modify_checkInput()">
		<tr bgcolor="#F6F6F6">
			<td align="center" height="20"><font color="#818181">비밀번호</font></td>
		</tr>
		<tr bgcolor="#F8F8F8" height="50">
			<td align="center">
			<img src="images/space.gif" width="10" height="5"><br>
			<input type="password" name="input_modifyer_passwd" size="15" maxlength="20" class="input"><br>
			<img src="images/space.gif" width="10" height="5"><br>
			<input type="submit" value="확인" class="button">
			<input type="button" value="닫기" class="button" onClick="modify_Auth_Popup('',event)"><br>
			<img src="images/space.gif" width="10" height="5"><br>
			</td>
		</tr>
		</form>
		</table>
	</div>
	<!-- 수정하기 버튼을 누른 경우 종료-->

	<!-- 삭제하기 버튼을 누른 경우 시작-->
	<script language="javascript">
	<!--
	function delete_confirm(uid){
		if(confirm("삭제하시겠습니까?")) {
			location.href = "delete.php?uid="+uid+"&startPage=<?=$_GET['startPage']?><?=$etc_key?>";
		}
	}
	-->
	</script>

	<script language="javascript">
	<!--
	function delete_movePopup(ff,xx,yy) { ff.left = xx; ff.top = yy;}
	function delete_Auth_Popup(uid,event) {
		document.D.action="delete.php?uid="+uid+"&startPage=<?=$_GET['startPage']?><?=$etc_key?>";
		var fvar = document.getElementById('delete_cate').style;
		var xvar = event.clientX -30;
		var yvar = event.clientY + document.body.scrollTop - 100;
			 if (yvar<20) yvar = 30;
		delete_movePopup(fvar,xvar,yvar);

		if (document.getElementById('delete_cate').style.display != "none"){
		document.getElementById('delete_cate').style.display = "none";
		}else{
		document.getElementById('delete_cate').style.display = "";
		}
	}
	//-->
	</script>

	<div id="delete_cate" style="position:absolute;left:250;top:300;z-index:100;display:none;">
		<script language="javascript">
		<!--
		function delete_checkInput() {
			if (!document.D.input_deleter_passwd.value) {
				alert("비밀번호를 입력하세요.");
				document.D.input_deleter_passwd.focus();
				return false;
			}
		}
		-->
		</script>

		<table width="150" height="60" border="1" cellspacing="0" cellpadding="0" bordercolor="#CCCCCC" style="border-collapse:collapse">
		<form name="D" method="post" action="delete.php?uid=<?=$_GET["uid"]?>&startPage=<?=$_GET["startPage"]?><?=$etc_key?>" onSubmit="return delete_checkInput()">
		<tr bgcolor="#F6F6F6">
			<td align="center" height="20"><font color="#818181">비밀번호</font></td>
		</tr>
		<tr bgcolor="#F8F8F8" height="50">
			<td align="center">
			<img src="images/space.gif" width="10" height="5"><br>
			<input type="password" name="input_deleter_passwd" size="15" maxlength="20" class="input"><br>
			<img src="images/space.gif" width="10" height="5"><br>
			<input type="submit" value="확인" class="button">
			<input type="button" value="닫기" class="button" onClick="delete_Auth_Popup('',event)"><br>
			<img src="images/space.gif" width="10" height="5"><br>
			</td>
		</tr>
		</form>
		</table>
	</div>
	<!-- 삭제하기 버튼을 누른 경우 종료-->

	<!-- 코멘트 삭제 누른 경우 시작 -->
	<script language="javascript">
	<!--
	function comment_delete_confirm(cmt_iDx){
		if(confirm("삭제하시겠습니까?")) {
			location.href="<?=$PHP_SELP?>?cmd=cmt_delete&cmt_idx="+cmt_iDx+"&startPage=<?=$_GET['startPage']?><?=$etc_key?>";
		}
	}
	-->
	</script>

	<script language="javascript">
	<!--
	function comment_movePopup(ff,xx,yy) { ff.left = xx; ff.top = yy;}
	function comment_Auth_Popup(value1,event) {
		document.CD.action="<?=$PHP_SELP?>?cmd=cmt_delete&cmt_idx="+value1+"&startPage=<?=$_GET['startPage']?><?=$etc_key?>";
		var fvar = document.getElementById('comment_cate').style;
		var xvar = event.clientX - 150;
		var yvar = event.clientY + document.body.scrollTop - 100;
		comment_movePopup(fvar,xvar,yvar);

		if (document.getElementById('comment_cate').style.display != "none"){
		document.getElementById('comment_cate').style.display = "none";
		}else{
		document.getElementById('comment_cate').style.display = "";
		}
	}
	//-->
	</script>

	<div id="comment_cate" style="position:absolute;left:250;top:300;z-index:100;display:none;">
		<script language="javascript">
		<!--
		function comment_delete_checkInput() {
			if (!document.CD.input_commenter_passwd.value) {
				alert("비밀번호를 입력하세요.");
				document.CD.input_commenter_passwd.focus();
				return false;
			}
		}
		-->
		</script>

		<table width="150" height="60" border="1" cellspacing="0" cellpadding="0" bordercolor="#CCCCCC" style="border-collapse:collapse">
		<form name="CD" method="post" onSubmit="return comment_delete_checkInput()">
		<tr bgcolor="#F6F6F6">
			<td align="center" height="20"><font color="#818181">비밀번호</font></td>
		</tr>
		<tr bgcolor="#F8F8F8" height="50">
			<td align="center">
			<img src="images/space.gif" width="10" height="5"><br>
			<input type="password" name="input_commenter_passwd" size="15" maxlength="20" class="input"><br>
			<img src="images/space.gif" width="10" height="5"><br>
			<input type="submit" value="확인" class="button">
			<input type="button" value="닫기" class="button" onClick="comment_Auth_Popup('',event)"><br>
			<img src="images/space.gif" width="10" height="5"><br>
			</td>
		</tr>
		</form>
		</table>
	</div>
	<!-- 코멘트 삭제 누른 경우 종료 -->

	<script language="javascript">
	<!--
	function img_popup(fn,fe,wd,ht,wn,fnum){
		var x, y;
		var rv = document.getElementById('img_no_'+fnum).alt;
		if(rv == 1 || rv == 3) {
			ex = wd;
			wd = ht;
			ht = ex;
		}

		if(wd > window.screen.Width -10) {
			wd = 	window.screen.Width -10;
			x = 0;
		} else {
			x = Math.round((window.screen.Width - wd) / 2);
		}
		if(ht > window.screen.Height -70) {
			ht = window.screen.Height -70;
			y = 0;
		} else {
			y =  Math.round((window.screen.Height - wd) / 2);
		}
			window.open("img_popup.php?img_name="+fn+"&img_extra="+fe+"&rv="+rv,wn,"left="+x+",top="+y+",width="+wd+",height="+ht+",toolbar=no,menubar=no,status=no,scrollbars=auto,resizable=yes");
	}

	function comment_checkInput(form) {
		if (!form.commenter_name.value) {
			alert("이름을 입력하세요.");
			form.commenter_name.focus();
			return false;
		}
		if (!form.comment_content.value) {
			alert("내용을 입력하세요.");
			form.comment_content.focus();
			return false;
		}
	}
	//-->
	</script>

	<?
	for($i=0; $i<count($list_Records); $i++){
		$fn=explode("|",$list_Records[$i]["userfile_name"]);
		$fe=explode("|",$list_Records[$i]["userfile_extra"]);
		$fs=explode("|",$list_Records[$i]["userfile_size"]);
		$fd=explode("|",$list_Records[$i]["userfile_download"]);
	?>

		<?if($i>0){?><table cellpadding="0" cellspacing="0"><tr><td><img src='images/space.gif' height='20'></td></tr></table><?}?>
		<table style="width:<?=$Records["width_sum"]?>; border:1px solid #D6D9E6; border-collapse:collapse;"><td>

		<table align="center" width="<?=$Records["width_sum"]-50?>" border="0" style="margin-top:15px;">
		<tr>
		<td>
		<font color="#0953A8"><b><?=($list_Records[$i]["notice"]=="y")?"공지사항":"no.".($countTotalRecord-($ViewPerPage*($startPage-1)+$i))?></b></font><p>
		<b><?=htmlspecialchars($list_Records[$i]["boarder_name"])?></b>(<?=$list_Records[$i]["boarder_id"]?>)<br>
		<span style="font:8pt tahoma; color:#778899;"><?=$list_Records[$i]["signdate"]?></span>
		</td>
		<td align="right" valign="top">
		<?if($Sync_id==$list_Records[$i]["boarder_id"] || $Sync_level>49){?>
			<a href="modify.php?uid=<?=$list_Records[$i]["uid"]?>&startPage=<?=$_GET["startPage"]?><?=$etc_key?>" style="text-decoration:none;"><font color="0953A8">수정</font></a>
		<?}else{?>
			<span onClick="modify_Auth_Popup('<?=$list_Records[$i]["uid"]?>',event)" style="cursor:pointer;"><font color="0953A8">수정</font></span>
		<?}?>
		|
		<?if($Sync_id==$list_Records[$i]["boarder_id"] || $Sync_level>49){?>
			<span onClick="delete_confirm('<?=$list_Records[$i]["uid"]?>')" style="cursor:pointer;"><font color="0953A8">삭제</font></span>
		<?}else{?>
			<span onClick="delete_Auth_Popup('<?=$list_Records[$i]["uid"]?>',event)" style="cursor:pointer;"><font color="0953A8">삭제</font></span>
		<?}?>
		</td>
		</tr>
		<tr>
		<td colspan="2" style="padding:20px 0 10px 0;">
			<!--본문-->
			<?
			if($list_Records[$i]["html"]=="y" && $list_Records[$i]["auto_br"]!="y") $list_Records[$i]["content"] = $list_Records[$i]["content"];
			elseif($list_Records[$i]["html"]=="y" && $list_Records[$i]["auto_br"]=="y") $list_Records[$i]["content"] =  nl2br(auto_link(str_replace("  ","&nbsp; ",str_replace("\t","&nbsp; &nbsp; ",$list_Records[$i]["content"]))));
			else  $list_Records[$i]["content"] =  nl2br(auto_link(str_replace("  ","&nbsp; ",str_replace("\t","&nbsp; &nbsp; ",htmlspecialchars($list_Records[$i]["content"])))));
			?>

			<?
			// 멀티미디어 처리
			preg_match_all('/\[_(.*)_\]/', $list_Records[$i]["content"], $arr_inserted);
			if($list_Records[$i]["userfile_name"] && $arr_inserted[1]){
				for($f=0;$f<count($arr_inserted[1]);$f++){
					$ai = null;
					$ai = explode(",",$arr_inserted[1][$f]);
					$ext = strtolower(strrchr($ai[0],"."));
					if(array_search($ai[0],$fn) > -1){
						if($ext==".jpg" || $ext==".gif" || $ext==".png"){
							$size = getimagesize($upload_root."/".$_GET["boardid"]."/".$fe[array_search($ai[0],$fn)]);
							if($size[0] > $Records["width_sum"]-50) $Fix_size = $Records["width_sum"]-50;
							else $Fix_size = $size[0];

							$src = "[_".$arr_inserted[1][$f]."_]";
							$dst = "<img id='img_no_".$f."' name='img_no_".$f."' src='".$upload_dir."/".$_GET["boardid"]."/".$fe[array_search($ai[0],$fn)]."' width='".$Fix_size."' align='".trim($ai[1])."' onClick=img_popup('".urlencode($ai[0])."','".$fe[array_search($ai[0],$fn)]."','".$size[0]."','".$size[1]."','".$_GET["uid"]."_".$f."','".$f."') style='cursor:pointer;'>";

							$list_Records[$i]["content"] = str_replace($src,$dst,$list_Records[$i]["content"]);

						}elseif($ext==".asf" || $ext==".wmv" || $ext==".mpg" || $ext==".mpeg"){
							$list_Records[$i]["content"] = str_replace("[_".$arr_inserted[1][$f]."_]","<textarea id='emb_".$f."' style='display:none;' cols='0' rows='0'><embed src='".$upload_dir."/".$_GET["boardid"]."/".$fe[array_search($ai[0],$fn)]."'  type='application/x-mplayer2' autostart='false' showstatusbar='true' showcontrols='true' loop='false'></embed></textarea><script language='javascript'>PrintEmbed('emb_".$f."')</script>",$list_Records[$i]["content"]);
						}elseif($ext==".wma" || $ext==".mp3"){
							$list_Records[$i]["content"] = str_replace("[_".$arr_inserted[1][$f]."_]","<textarea id='emb_".$f."' style='display:none;' cols='0' rows='0'><embed src='".$upload_dir."/".$_GET["boardid"]."/".$fe[array_search($ai[0],$fn)]."' height='25' width='75' type='application/x-mplayer2' autostart='false' showstatusbar='false' showcontrols='true' loop='false'></embed></textarea><script language='javascript'>PrintEmbed('emb_".$f."')</script>",$list_Records[$i]["content"]);
						}elseif($ext==".swf"){
							$par  = "quality=\"high\"";
							$par .= " pluginspage=\"http://www.macromedia.com/go/getflashplayer\"";
							$par .= " type=\"application/x-shockwave-flash\"";
							$par .= " width=\"".$Records["width_sum"]."\"";
							$list_Records[$i]["content"] = str_replace("[_".$arr_inserted[1][$f]."_]","<textarea id='emb_".$f."' style='display:none;' cols='0' rows='0'><embed src='".$upload_dir."/".$_GET["boardid"]."/".$fe[array_search($ai[0],$fn)]."' ".$par."></embed></textarea><script language='javascript'>PrintEmbed('emb_".$f."')</script>",$list_Records[$i]["content"]);
						}
					}
				}
			}

			if($list_Records[$i]["userfile_name"] && !$arr_inserted[1]){
				for($f=0;$f<count($fn);$f++){
					$ext = strtolower(strrchr($fn[$f],"."));
					if($ext==".jpg" || $ext==".gif" || $ext==".png"){
						$size = getimagesize("$upload_root/$_GET[boardid]/$fe[$f]");
						if($size[0] > $Records["width_sum"]-50) $Fix_size = $Records["width_sum"]-50;
						else $Fix_size = $size[0];
						echo " <table cellpadding='0' cellspacing='0'><tr><td>『".$fn[$f]."』</td></tr>";
						echo " <tr><td><img id='img_no_".$f."' name='img_no_".$f."' src='".$upload_dir."/".$_GET["boardid"]."/".$fe[$f]."' width='".$Fix_size."' onClick=img_popup('".urlencode($fn[$f])."','".$fe[$f]."','".$size[0]."','".$size[1]."','".$_GET["uid"]."_".$f."','".$f."') style='cursor:pointer;'></tr></table><br>\n";
					}elseif($ext==".asf" || $ext==".wmv" || $ext==".mpg" || $ext==".mpeg"){
						echo "『".$fn[$f]."』";
						echo " <br><textarea id='emb_".$f."' style='display:none;' cols='0' rows='0'><embed src='".$upload_dir."/".$_GET["boardid"]."/".$fe[$f]."' type='application/x-mplayer2' autostart='false' showstatusbar='true' showcontrols='true' loop='false'></embed></textarea><script language='javascript'>PrintEmbed('emb_".$f."')</script><br><br>";
					}elseif($ext==".wma" || $ext==".mp3"){
						echo "『".$fn[$f]."』";
						echo " <br><textarea id='emb_".$f."' style='display:none;' cols='0' rows='0'><embed src='".$upload_dir."/".$_GET["boardid"]."/".$fe[$f]."' height='25' width='75' type='application/x-mplayer2' autostart='false' showstatusbar='false' showcontrols='true' loop='false'></embed></textarea><script language='javascript'>PrintEmbed('emb_".$f."')</script><br><br>";
					}elseif($ext==".swf"){
						echo "『".$fn[$f]."』";
						$par  = "quality=\"high\"";
						$par .= " pluginspage=\"http://www.macromedia.com/go/getflashplayer\"";
						$par .= " type=\"application/x-shockwave-flash\"";
						$par .= " width=\"".$Records["width_sum"]."\"";
						echo " <br><textarea id='emb_".$f."' style='display:none;' cols='0' rows='0'><embed src='".$upload_dir."/".$_GET["boardid"]."/".$fe[$f]."' ".$par."></embed></textarea><script language='javascript'>PrintEmbed('emb_".$f."')</script><br><br>";
					}
				}
			}
			?>

			<?
			if($_GET["find_value"]){
				$arr_find_value = explode(",",$_GET["find_value"]);
				for($j=0;$j<count($arr_find_value);$j++){
					$list_Records[$i]["content"] = eregi_replace("(\\$arr_find_value[$j])", "<font color='Orange'>\\1</font>", $list_Records[$i]["content"]);
				}
			}
			?>

			<?=$list_Records[$i]["content"]?>
			<!--//본문-->
		</tr>
		</table>

		<?if($Records["link_use"]=="1" && ($list_Records[$i]["link_1"] || $list_Records[$i]["link_2"])){?>
			<table align="center" cellpadding="0" cellspacing="0" border="0"  width="<?=$Records["width_sum"]-50?>">
			<td width="63" valign="top"><font color="0953A8">링크주소 : </font></td>
			<td>
			<a href="<?=urldecode($list_Records[$i]["link_1"])?>" target="_blank" style="font:8pt tahoma; color:#778899;"><?=$list_Records[$i]["link_1"]?></a><br>
			<a href="<?=urldecode($list_Records[$i]["link_2"])?>" target="_blank" style="font:8pt tahoma; color:#778899;"><?=$list_Records[$i]["link_2"]?></a>
			</td>
			</table>
		<?}?>

		<?if($Records["download_use"]=="1" && count($fn)>1){?>
			<table align="center" cellpadding="0" cellspacing="0" border="0"  width="<?=$Records["width_sum"]-50?>">
			<td width="58" valign="top"><font color="0953A8">첨부파일 : </font></td>
			<td>
				<table cellpadding="0" cellspacing="0" border="0" style="margin-left:5px;">
				<?	for($j=0;$j<count($fn)-1;$j++){?>		
				<tr><td>
				<a href="download.php?fileName=<?=$fn[$j]?>&fileExtra=<?=$fe[$j]?>&boardid=<?=$_GET["boardid"]?>&uid=<?=$list_Records[$i]["uid"]?>&an=<?=$j?>" style="font:8pt tahoma; color:#778899;"><?=$fn[$j]?> (<?=number_format($fs[$j])?> byte)</a></td><td style="padding-left:10px; font:8pt tahoma; color:#778899;">Download: <?=number_format($fd[$j])?>
				</td></tr>
				<?}?>
				</table>
			</td>
			</table>
		<?}?>
		
		<!--코멘트-->
		<?if($Records["comment_use"]=="1"){?>
			<table cellpadding="0" cellspacing="0"><tr><td><img src='images/space.gif' height='30'></td></tr></table>
			<?
			$cmt_Records									= null;

			$CommentQ["cmt_idx"]						= null;
			$CommentQ["boardid"]						= null;
			$CommentQ["commenter_name"]		= null;
			$CommentQ["commenter_id"]				= null;
			$CommentQ["commenter_passwd"]		= null;
			$CommentQ["comment_content"]		= null;
			$CommentQ["signdate"]					= null;

			$Query  = " SELECT " . selectQuery($CommentQ, $_GET["boardid"]."_comment");
			$Query .= " FROM ".$_GET["boardid"]."_comment";
			$Query .= " where uid=".$list_Records[$i]["uid"];
			$Query .= " order by signdate asc";

			$cmt_cnn = mysql_query($Query) or exit(mysql_error());
			while($cmt_rst = mysql_fetch_assoc($cmt_cnn)) {
				$cmt_Records = array_merge($cmt_Records, array($cmt_rst));
			}
			?>

			<?for($j=0; $j<count($cmt_Records); $j++){?>
				<table align="center" cellpadding="0" cellspacing="0" border="0" width="<?=$Records["width_sum"]-50?>">
				<tr><td colspan="2" background="<?=$Records["line_img"]?>"><img src="images/space.gif" height="1"></td></tr>
				<tr><td colspan="2"><b><?=$cmt_Records[$j]["commenter_name"]?>(<?=$cmt_Records[$j]["commenter_id"]?>)</b>
				<span style="font:8pt tahoma; color:#778899;"><?=$cmt_Records[$j]["signdate"]?></span>
				</td></tr>
				<tr>
				<td width="<?=$Records["width_sum"]?>"><?=nl2br(auto_link(str_replace("  ","&nbsp; ",str_replace("\t","&nbsp; &nbsp; ",$cmt_Records[$j]["comment_content"]))))?></td>
				<td valign="top" align="right">
				<?if($Sync_id==$cmt_Records[$j]["commenter_id"] || $Sync_level>49){?>
					<img src="images/x.gif" style="cursor:pointer" onClick="comment_delete_confirm('<?=$cmt_Records[$j]["cmt_idx"]?>')">
				<?}else{?>
					<img src="images/x.gif" style="cursor:pointer" onClick="comment_Auth_Popup('<?=$cmt_Records[$j]["cmt_idx"]?>',event)">
				<?}?>
				</td>
				</tr>
				<tr><td colspan="2"><img src="images/space.gif" width="10" height="20"></td>
				</tr>
				</table>
			<?}?>

			<form method="post" action="<?=$_SERVER['PHP_SELF']?>?cmd=cmt_write&uid=<?=$list_Records[$i]["uid"]?>&startPage=<?=$_GET["startPage"]?><?=$etc_key?>" onSubmit="return comment_checkInput(this);">

			<table align="center" width="<?=$Records["width_sum"]-50?>" cellspacing="1" cellpadding="0" border="0">

			<tr style="display:none;">
			<td align="center" bgcolor="#F6F6F6"><font color="#818181">ID(IP)</font></td>
			<td>&nbsp;<input type="text" name="commenter_id" value="<?if($Sync_id) echo $Sync_id; else echo $_SERVER["REMOTE_ADDR"];?>" class="input" readonly></td>
			</tr>

			<tr><td colspan="100" background="<?=$Records["line_img"]?>"><img src="images/space.gif" height="1"></td></tr>

			<tr>
			<td align="center" bgcolor="#F6F6F6" width="98"><font color="#818181">이름</font><font color="Orange">*</font></td>
			<td>&nbsp;<input type="text" name="commenter_name" value="<?=$Sync_name?>" class="input"></td>
			</tr>

			<tr><td colspan="100" background="<?=$Records["line_img"]?>"><img src="images/space.gif" height="1"></td></tr>

			<tr>
			<td align="center" bgcolor="#F6F6F6"><font color="#818181">비밀번호</font></td>
			<td>&nbsp;<input type="password" name="commenter_passwd" class="input"> (비회원이 글 삭제시 필요합니다.)</td>
			</tr>

			<tr><td colspan="100" background="<?=$Records["line_img"]?>"><img src="images/space.gif" height="1"></td></tr>

			<tr>
			<td valign="top" align="center" bgcolor="#F6F6F6">
			<font color="#818181">내용</font><font color="Orange">*</font>
			</td>
			<td valign="top">&nbsp;<textarea name="comment_content" class="input" style="width:98%;height:50;overflow-y:visible;"></textarea>
			</td>
			</tr>

			<tr><td colspan="100" background="<?=$Records["line_img"]?>"><img src="images/space.gif" height="1"></td></tr>

			<tr>
			<td align="right" colspan="2" valign="middle" height="30" style="padding-right:1">
			&nbsp;<input type="image" src='images/zbtn_submit.gif' style="cursor:pointer;" align=absmiddle>
			<a href="javascript:location.reload();"><img src='images/zbtn_reset.gif' border=0 align=absmiddle></a>
			</td>
			</tr>

			</table>

			</form>
			<table cellpadding="0" cellspacing="0"><tr><td><img src='images/space.gif' height='10'></td></tr></table>
		<?}?>		
		<!--//코멘트-->
		
		</td></table>
	<?}?>
	<!--//펼침목록-->

	<table cellpadding="0" cellspacing="0"><tr><td><img src='images/space.gif' height='2'></td></tr></table>

	<table cellpadding="0" cellspacing="0" width="<?=$Records["width_sum"]?>">
	<tr>
	<td align="center" style="padding-top:2"><?=$PageLinks?></td>
	</tr>
	</table>

	<table cellSpacing="0" cellpadding="0" border="0" width="<?=$Records["width_sum"]?>">
	<tr>
	<td align="left" valign="top">
	<?if($Records["writelevel"]==0 || $Sync_level>=$Records["writelevel"]){?>
	<a href="write.php?startPage=<?=$startPage?><?=$etc_key?>"><img src='images/zbtn_new.gif' border=0 align=absmiddle></a>
	<?}?>
	</td>
	<?if($Records["search_use"]=="1"){?>
	<!-- 검색버튼 테이블 ---------------------------------------------->
	<td align="right" valign="top">
	<form name="S" method="get" action="open.php">
	<input type="hidden" name="boardid" value="<?=$_GET["boardid"]?>">
	<input type="hidden" name="startPage" value="1">
		<!--input type="checkbox" name="find_key_1" value="subject" <?if($_GET["find_key_1"]) echo "checked";?>제목-->
		<? if($Records["name_use"] == "1"){?>
		<input type="checkbox" name="find_key_2" value="boarder_name" <?if($_GET["find_key_2"]) echo "checked";?>><?=$Records["name_title"]?>
		<?}?>
		<input type="checkbox" name="find_key_3" value="content" <?if($_GET["find_key_3"]) echo "checked";?>>내용
		&nbsp;
		<input type="text" size="15" maxlength="30" name="find_value" class="input" value="<?=$_GET["find_value"]?>">
		<input type="image" src='images/zbtn_search.gif' style="cursor:pointer;" align=absmiddle>
	</form>
	</td>
	<?}?>
	</tr>
	</table>

</td>
</tr>
</table>

<?=$Records["tail_note"]?>
<?=eval($Records["tail_php"])?>

<? ob_flush(); ?>