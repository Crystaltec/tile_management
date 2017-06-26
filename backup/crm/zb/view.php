<?

ob_start();
include "../include/common.inc";
include "../include/user_functions.inc";
include "../include/dbconn.inc";
include "include/board_config.inc";

if(!$_GET["boardid"]) Header("Location:/");

## 게시판 꾸밈 정보를 마스터 테이블에서 가져온다

//$id_Records = array():
//$Records = array();

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

## 기본값설정
if(!$_GET["category"]) $_GET["category"] ="전체";

## Get폼값설정
$etc_key  = "";
$etc_key .= "&boardid=".$_GET["boardid"];
if($_GET["find_value"] && $_GET["find_key_1"]) $etc_key .= "&find_key_1=".$_GET["find_key_1"];
if($_GET["find_value"] && $_GET["find_key_2"]) $etc_key .= "&find_key_2=".$_GET["find_key_2"];
if($_GET["find_value"] && $_GET["find_key_3"]) $etc_key .= "&find_key_3=".$_GET["find_key_3"];
if($_GET["find_value"]) $etc_key .= "&find_value=".$_GET["find_value"];
if($_GET["category"]) $etc_key .= "&category=".urlencode($_GET["category"]);

## 접근권한 설정
if($Records["readlevel"]>0 && $Sync_level<$Records["readlevel"]) {
	echo"<script>alert('You don't have the authority to view this page!')</script>";
	echo"<script>history.go(-1)</script>";
	exit;
}

## 쿼리 시작
$Board_id["uid"]						= NULL;
$Board_id["fid"]						= NULL;
$Board_id["boarder_id"]				= NULL;
$Board_id["boarder_name"]		= NULL;
$Board_id["boarder_passwd"]		= NULL;
$Board_id["category"]				= NULL;
$Board_id["subject"]					= NULL;
$Board_id["secret_passwd"]		= NULL;
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

$Query  = " SELECT " . selectQuery($Board_id, $_GET["boardid"]);
$Query .= " FROM ".$_GET["boardid"];
$Query .= " where uid=".$_GET["uid"];

$id_cnn = mysql_query($Query) or exit(mysql_error());
$id_Records = mysql_fetch_assoc($id_cnn);

$fn=explode("|",$id_Records["userfile_name"]);
$fe=explode("|",$id_Records["userfile_extra"]);
$fs=explode("|",$id_Records["userfile_size"]);
$fd=explode("|",$id_Records["userfile_download"]);

if($_GET["mode"] != "process" && $_GET["mode"] != "vote"){

	if ($id_Records["secret_passwd"] && $id_Records["secret_passwd"]!=$_POST["input_secret_passwd"] && $Sync_level<50 && !$_POST["secret_pass"]) {
	echo"<script>alert('incorrect password.')</script>";
	echo"<script>history.go(-1)</script>";
	exit;
	}

	// Hit 증가
	//if (!${$_GET["boardid"]."_".$_GET["uid"]."_hit"}) {
		$Query = "update ".$_GET["boardid"]." set hit=hit + 1 where uid = ".$_GET["uid"];
		mysql_query($Query) or exit(mysql_error());
		setcookie ($_GET["boardid"]."_".$_GET["uid"]."_hit", "1");
		$id_Records["hit"]++;
		//echo $Query;
	//}
	//echo $_GET["boardid"]."_".$_GET["uid"]."_hit";
?>

<?=eval($Records["head_php"])?>
<table width=<?=$Records["width_sum"]?> cellSpacing="0" cellpadding="0" border="0">
<td><?=$Records["head_note"]?></td>
</table>

<!-- 수정하기 버튼을 누른 경우 시작-->
<script language="javascript">
<!--
function modify_movePopup(ff,xx,yy) { ff.left = xx; ff.top = yy;}
function modify_Auth_Popup(event) {
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
			alert("Please, Input password!");
			document.M.input_modifyer_passwd.focus();
			return false;
		}
	}
	-->
	</script>

	<table width="150" height="60" border="1" cellspacing="0" cellpadding="0" bordercolor="#CCCCCC" style="border-collapse:collapse">
	<form name="M" method="post" action="modify.php?uid=<?=$_GET["uid"]?>&startPage=<?=$_GET["startPage"]?><?=$etc_key?>" onSubmit="return modify_checkInput()">
	<tr bgcolor="#F6F6F6">
		<td align="center" height="20"><font color="#818181">password</font></td>
	</tr>
	<tr bgcolor="#F8F8F8" height="50">
		<td align="center">
		<img src="images/space.gif" width="10" height="5"><br>
		<input type="password" name="input_modifyer_passwd" size="15" maxlength="20" class="input"><br>
		<img src="images/space.gif" width="10" height="5"><br>
		<input type="submit" value="ok" class="button">
		<input type="button" value="cancel" class="button" onClick="modify_Auth_Popup(event)"><br>
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
function delete_confirm(){
	if(confirm("Do you want delete?")) {
		location.href = "delete.php?uid=<?=$id_Records['uid']?>&startPage=<?=$_GET['startPage']?><?=$etc_key?>";
	}
}
-->
</script>

<script language="javascript">
<!--
function delete_movePopup(ff,xx,yy) { ff.left = xx; ff.top = yy;}
function delete_Auth_Popup(event) {
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
			alert("Please, input password!");
			document.D.input_deleter_passwd.focus();
			return false;
		}
	}
	-->
	</script>

	<table width="150" height="60" border="1" cellspacing="0" cellpadding="0" bordercolor="#CCCCCC" style="border-collapse:collapse">
	<form name="D" method="post" action="delete.php?uid=<?=$_GET["uid"]?>&startPage=<?=$_GET["startPage"]?><?=$etc_key?>" onSubmit="return delete_checkInput()">
	<tr bgcolor="#F6F6F6">
		<td align="center" height="20"><font color="#818181">password</font></td>
	</tr>
	<tr bgcolor="#F8F8F8" height="50">
		<td align="center">
		<img src="images/space.gif" width="10" height="5"><br>
		<input type="password" name="input_deleter_passwd" size="15" maxlength="20" class="input"><br>
		<img src="images/space.gif" width="10" height="5"><br>
		<input type="submit" value="ok" class="button">
		<input type="button" value="cancel" class="button" onClick="delete_Auth_Popup(event)"><br>
		<img src="images/space.gif" width="10" height="5"><br>
		</td>
	</tr>
	</form>
	</table>
</div>
<!-- 삭제하기 버튼을 누른 경우 종료-->


<table width=<?=$Records["width_sum"]?> cellSpacing="0" cellpadding="0" border="0">
<tr>
<td>

	<table cellpadding="0" cellspacing="0"><tr><td><img src='images/space.gif' height='2'></td></tr></table>	
	<table cellpadding="0" cellspacing="0" border="0">
	<tr><td colspan="100" bgcolor="#90A0B8"><img src="images/space.gif" height="2"></td></tr>
	<tr><td colspan="100" background="<?=$Records["line_img"]?>"><img src="images/space.gif" height="1"></td></tr>

	<tr height="21">
	<td bgcolor="#F6F6F6" align="left" width="98"><font color="#818181"><b>Date</b></font></td>
	<td style="padding:3 0 2 10" width="<?=$Records["width_sum"]-98?>"><?=($Records["comment_use"]=="1")?date("Y-m-d H:i:s",strtotime($id_Records["signdate"])):date("Y-m-d",strtotime($id_Records["signdate"]))?>
	<?if($Records["hit_use"]=="1"){?>&nbsp; hit: <?=$id_Records["hit"]?><?}?>
	<?if($Records["vote_use"]=="1"){?>&nbsp; recommend: <?=$id_Records["vote"]?><?}?></td>
	</tr>

	<tr><td colspan="100" background="<?=$Records["line_img"]?>"><img src="images/space.gif" height="1"></td></tr>

	<tr height="21">
	<td bgcolor="#F6F6F6" align="left" width="98"><font color="#818181"><b>Subject</b></font></td>
	<td style="padding:3 0 2 10"><?=htmlspecialchars($id_Records["subject"])?></td>
	</tr>

	<?if($Records["name_use"]==1){?>
	<tr><td colspan="100" background="<?=$Records["line_img"]?>"><img src="images/space.gif" height="1"></td></tr>
	<tr height="21">
	<td bgcolor="#F6F6F6" align="left"><font color="#818181"><b><?=$Records["name_title"]?></b></font></td>
	<td style="padding:3 0 2 10"><?=$id_Records["boarder_name"]?></td>
	</tr>
	<?}?>

	<?if($Records["link_use"]==1 && $id_Records["link_1"]){?>
	<tr><td colspan="100" background="<?=$Records["line_img"]?>"><img src="images/space.gif" height="1"></td></tr>
	<tr height="21">
	<td bgcolor="#F6F6F6" align="center"><font color="#818181">link url1</font></td>
	<td style="padding:3 0 2 10"><a href="<?=urldecode($id_Records["link_1"])?>" target="_blank"><?=urldecode($id_Records["link_1"])?></a></td>
	</tr>
	<?}?>

	<?if($Records["link_use"]==1 && $id_Records["link_2"]){?>
	<tr><td colspan="100" background="<?=$Records["line_img"]?>"><img src="images/space.gif" height="1"></td></tr>
	<tr height="21">
	<td bgcolor="#F6F6F6" align="center"><font color="#818181">link url2</font></td>
	<td style="padding:3 0 2 10"><a href="<?=urldecode($id_Records["link_2"])?>" target="_blank"><?=urldecode($id_Records["link_2"])?></a></td>
	</tr>
	<?}?>

	<?if($id_Records["userfile_name"] && $Records["download_use"]=="1"){?>
	<tr><td colspan="100" background="<?=$Records["line_img"]?>"><img src="images/space.gif" height="1"></td></tr>
	<tr height="21">
	<td bgcolor="#F6F6F6" align="center"><font color="#818181">File</font></td>
	<td style="padding:3 0 2 10">

		<table width="500" cellpadding="0" cellspacing="0">
		<?	for($i=0;$i<count($fn)-1;$i++){?>
		<tr>
		<td style="text-overflow:ellipsis; overflow:hidden;"><img src='images/file.gif' border="0"> File name: <a href="download.php?fileName=<?=$fn[$i]?>&fileExtra=<?=$fe[$i]?>&boardid=<?=$_GET["boardid"]?>&uid=<?=$id_Records["uid"]?>&an=<?=$i?>"><span title="<?=$fn[$i]?>"><?=$fn[$i]?></span></a></td>
		<td>size: <?=number_format($fs[$i])?> byte</td>
		<td>download: <?=number_format($fd[$i])?></td>
		</tr>
		<?}?>
		</table>
	</td>
	</tr>
	<?}?>
	<tr><td colspan="100" background="<?=$Records["line_img"]?>"><img src="images/space.gif" height="1"></td></tr>
	</table>

	<table cellpadding="0" cellspacing="0"><tr><td><img src='images/space.gif' height='10'></td></tr></table>

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
			window.open("img_popup.php?boardid=<?=$_GET[boardid]?>&img_name="+fn+"&img_extra="+fe+"&rv="+rv,wn,"left="+x+",top="+y+",width="+wd+",height="+ht+",toolbar=no,menubar=no,status=no,scrollbars=auto,resizable=yes");
	}

	function img_rotate(fnum,wd,ht){
		if(navigator.appName == "Microsoft Internet Explorer"){
			if(!document.getElementById('img_no_'+fnum).alt) document.getElementById('img_no_'+fnum).alt = 0;

			if(document.getElementById('img_no_'+fnum).alt == 0){
				document.getElementById('img_no_'+fnum).alt = 1;
				document.getElementById('img_no_'+fnum).style.filter = "progid:DXImageTransform.Microsoft.BasicImage(rotation=1)";
				if(ht > <?=$Records["width_sum"]?>){
					document.getElementById('img_no_'+fnum).width = Math.round((wd * <?=$Records["width_sum"]?>) / ht);
					document.getElementById('img_no_'+fnum).height = <?=$Records["width_sum"]?>;
				} else {
					document.getElementById('img_no_'+fnum).width = wd;
					document.getElementById('img_no_'+fnum).height = ht;		
				}
				//document.getElementById('rotate_'+fnum).src = "images/rotate2.gif";
			} else if(document.getElementById('img_no_'+fnum).alt == 1) {
				document.getElementById('img_no_'+fnum).alt = 3;
				document.getElementById('img_no_'+fnum).style.filter = "progid:DXImageTransform.Microsoft.BasicImage(rotation=3)";
				if(ht > <?=$Records["width_sum"]?>){
					document.getElementById('img_no_'+fnum).width = Math.round((wd * <?=$Records["width_sum"]?>) / ht);
					document.getElementById('img_no_'+fnum).height = <?=$Records["width_sum"]?>;
				} else {
					document.getElementById('img_no_'+fnum).width = wd;
					document.getElementById('img_no_'+fnum).height = ht;		
				}
				//document.getElementById('rotate_'+fnum).src = "images/rotate3.gif";
			} else if(document.getElementById('img_no_'+fnum).alt == 3) {
				document.getElementById('img_no_'+fnum).alt = 0;
				document.getElementById('img_no_'+fnum).style.filter = "progid:DXImageTransform.Microsoft.BasicImage(rotation=0)";
				if(wd > <?=$Records["width_sum"]?>){
					document.getElementById('img_no_'+fnum).width = <?=$Records["width_sum"]?>;
					document.getElementById('img_no_'+fnum).height = Math.round((ht * <?=$Records["width_sum"]?>) / wd);
				} else {
					document.getElementById('img_no_'+fnum).width = parseInt(wd) + 1;
					document.getElementById('img_no_'+fnum).height = parseInt(ht) + 1;
					document.getElementById('img_no_'+fnum).width = parseInt(wd) - 1;
					document.getElementById('img_no_'+fnum).height = parseInt(ht) - 1;
				}
				//document.getElementById('rotate_'+fnum).src = "images/rotate1.gif";
			}
		}else{
			alert("Sorry! Rotation is Microsoft Internet Explorer Only.");					
		}
	}
	//-->
	</script>

	<table border="0" cellpadding="0" cellspacing="0" width="<?=$Records["width_sum"]?>">
	<tr>
	<td height="100" valign="top" width="<?=$Records["width_sum"]?>">

	<?
	if($id_Records["html"]=="y" && $id_Records["auto_br"]!="y") $id_Records["content"] = $id_Records["content"];
	elseif($id_Records["html"]=="y" && $id_Records["auto_br"]=="y") $id_Records["content"] =  nl2br(str_replace("  ","&nbsp; ",str_replace("\t","&nbsp; &nbsp; ",$id_Records["content"])));
	else  $id_Records["content"] =  nl2br(auto_link(str_replace("  ","&nbsp; ",str_replace("\t","&nbsp; &nbsp; ",htmlspecialchars($id_Records["content"])))));
	?>

	<?
	// 멀티미디어 처리
	preg_match_all('/\[_(.*)_\]/', $id_Records["content"], $arr_inserted);
	if($id_Records["userfile_name"] && $arr_inserted[1]){
		for($f=0;$f<count($arr_inserted[1]);$f++){
			$ai = null;
			$ai = explode(",",$arr_inserted[1][$f]);
			$ext = strtolower(strrchr($ai[0],"."));
			if(array_search($ai[0],$fn) > -1){
				if($ext==".jpg" || $ext==".gif" || $ext==".png"){
					$size = getimagesize($upload_root."/".$_GET["boardid"]."/".$fe[array_search($ai[0],$fn)]);
					if($size[0] > $Records["width_sum"]) $Fix_size = $Records["width_sum"];
					else $Fix_size = $size[0];

					$src = "[_".$arr_inserted[1][$f]."_]";
					$dst = "<img id='img_no_".$f."' name='img_no_".$f."' src='".$upload_dir."/".$_GET["boardid"]."/".$fe[array_search($ai[0],$fn)]."' width='".$Fix_size."' align='".$ai[1]."' onClick=img_popup('".urlencode($ai[0])."','".$fe[array_search($ai[0],$fn)]."','".$size[0]."','".$size[1]."','".$_GET["uid"]."_".$f."','".$f."') style='cursor:pointer;'>";

					$id_Records["content"] = str_replace($src,$dst,$id_Records["content"]);

				}elseif($ext==".asf" || $ext==".wmv" || $ext==".mpg" || $ext==".mpeg"){
					$id_Records["content"] = str_replace("[_".$arr_inserted[1][$f]."_]","<textarea id='emb_".$f."' style='display:none;' cols='0' rows='0'><embed src='".$upload_dir."/".$_GET["boardid"]."/".$fe[array_search($ai[0],$fn)]."'  type='application/x-mplayer2' autostart='false' showstatusbar='true' showcontrols='true' loop='false'></embed></textarea><script language='javascript'>PrintEmbed('emb_".$f."')</script>",$id_Records["content"]);
				}elseif($ext==".wma" || $ext==".mp3"){
					$id_Records["content"] = str_replace("[_".$arr_inserted[1][$f]."_]","<textarea id='emb_".$f."' style='display:none;' cols='0' rows='0'><embed src='".$upload_dir."/".$_GET["boardid"]."/".$fe[array_search($ai[0],$fn)]."' height='25' width='75' type='application/x-mplayer2' autostart='false' showstatusbar='false' showcontrols='true' loop='false'></embed></textarea><script language='javascript'>PrintEmbed('emb_".$f."')</script>",$id_Records["content"]);
				}elseif($ext==".swf"){
					if($ai[1] && $ai[2] && $ai[1]>$Records["width_sum"]) {
						$Fix_width = "width=".$Records["width_sum"];
						$Fix_height = "height=".($Records["width_sum"]*$ai[2])/$ai[1];
					}elseif($ai[1] && $ai[2]){
						$Fix_width = "width=".$ai[1];
						$Fix_height = "height=".$ai[2];
					}elseif($ai[1] && $ai[1]<$Records["width_sum"]){
						$Fix_width = "width=".$ai[1];
						$Fix_height = "";
					}else{
						$Fix_width = "width=".$Records["width_sum"];
						$Fix_height = "";
					}
					
					$par  = "quality=\"high\"";
					$par .= " pluginspage=\"http://www.macromedia.com/go/getflashplayer\"";
					$par .= " type=\"application/x-shockwave-flash\"";
					$par .= $Fix_width." ".$Fix_height;
					$id_Records["content"] = str_replace("[_".$arr_inserted[1][$f]."_]","<textarea id='emb_".$f."' style='display:none;' cols='0' rows='0'><embed src='".$upload_dir."/".$fe[array_search($ai[0],$fn)]."' ".$par."></embed></textarea><script language='javascript'>PrintEmbed('emb_".$f."')</script>",$id_Records["content"]);	
				}
			}
		}
	}

	if($id_Records["userfile_name"] && !$arr_inserted[1]){
		for($f=0;$f<count($fn);$f++){
			$ext = strtolower(strrchr($fn[$f],"."));
			if($ext==".jpg" || $ext==".gif" || $ext==".png"){
				$size = getimagesize("$upload_root/$_GET[boardid]/$fe[$f]");
				if($size[0] > $Records["width_sum"]) $Fix_size = $Records["width_sum"];
				else $Fix_size = $size[0];
				echo " <table cellpadding='0' cellspacing='0'><tr><td onClick=img_rotate('$f','".$size[0]."','".$size[1]."')><span style='cursor:pointer; font:8pt tahoma; color:#778899;'>".$fn[$f]."&nbsp; Rotation</span></td></tr>";
				echo " <tr><td><img id='img_no_".$f."' name='img_no_".$f."' src='".$upload_dir."/".$_GET["boardid"]."/".$fe[$f]."' width='".$Fix_size."' onClick=img_popup('".urlencode($fn[$f])."','".$fe[$f]."','".$size[0]."','".$size[1]."','".$_GET["uid"]."_".$f."','".$f."') style='cursor:pointer;'></tr></table><br>\n";
			}elseif($ext==".asf" || $ext==".wmv" || $ext==".mpg" || $ext==".mpeg"){
				echo "<span style='font:8pt tahoma; color:#778899;'>".$fn[$f]."</span>";
				echo " <br><textarea id='emb_".$f."' style='display:none;' cols='0' rows='0'><embed src='".$upload_dir."/".$_GET["boardid"]."/".$fe[$f]."' type='application/x-mplayer2' autostart='false' showstatusbar='true' showcontrols='true' loop='false'></embed></textarea><script language='javascript'>PrintEmbed('emb_".$f."')</script><br><br>";
			}elseif($ext==".wma" || $ext==".mp3"){
				echo "<span style='font:8pt tahoma; color:#778899;'>".$fn[$f]."</span>";
				echo " <br><textarea id='emb_".$f."' style='display:none;' cols='0' rows='0'><embed src='".$upload_dir."/".$_GET["boardid"]."/".$fe[$f]."' height='25' width='75' type='application/x-mplayer2' autostart='false' showstatusbar='false' showcontrols='true' loop='false'></embed></textarea><script language='javascript'>PrintEmbed('emb_".$f."')</script><br><br>";
			}elseif($ext==".swf"){
				echo "<span style='font:8pt tahoma; color:#778899;'>".$fn[$f]."</span>";
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
			$id_Records["content"] = eregi_replace("(\\$arr_find_value[$j])", "<font color='#FF0066'>\\1</font>", $id_Records["content"]);
		}
	}
	?>

	<?=$id_Records["content"]?>

	</td>
	</tr>
	</table>
	<table cellpadding="0" cellspacing="0"><tr><td><img src='images/space.gif' height='30'></td></tr></table>

<?if($Records["comment_use"]=="1"){?>

	<?
	$CommentQ = array();
	$cmt_Records = array();
	$CommentQ["cmt_idx"]						= null;
	$CommentQ["boardid"]						= null;
	$CommentQ["commenter_name"]		= null;
	$CommentQ["commenter_id"]				= null;
	$CommentQ["commenter_passwd"]		= null;
	$CommentQ["comment_content"]		= null;
	$CommentQ["signdate"]					= null;

	$Query  = " SELECT " . selectQuery($CommentQ, $_GET["boardid"]."_comment");
	$Query .= " FROM ".$_GET["boardid"]."_comment";
	$Query .= " where uid=".$_GET["uid"];
	$Query .= " order by signdate asc";

	$cmt_cnn = mysql_query($Query) or exit(mysql_error());
	while($cmt_rst = mysql_fetch_assoc($cmt_cnn)) {
		$cmt_Records = array_merge($cmt_Records, array($cmt_rst));
	}
	?>

	<!-- 코멘트 삭제 누른 경우 시작 -->
	<script language="javascript">
	<!--
	function comment_delete_confirm(cmt_iDx){
		if(confirm("Are you sure?")) {
			location.href="cmt_delete.php?cmt_idx="+cmt_iDx+"&uid=<?=$_GET['uid']?>&startPage=<?=$_GET['startPage']?><?=$etc_key?>";
		}
	}
	-->
	</script>

	<script language="javascript">
	<!--
	function comment_movePopup(ff,xx,yy) { ff.left = xx; ff.top = yy;}
	function comment_Auth_Popup(value1,event) {
		document.CD.action="cmt_delete.php?cmt_idx="+value1+"&uid=<?=$_GET['uid']?>&startPage=<?=$_GET['startPage']?><?=$etc_key?>";
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
		function comment_delete_checkInput(value1) {
			if (!document.CD.input_commenter_passwd.value) {
				alert("Please, Input password!");
				document.CD.input_commenter_passwd.focus();
				return false;
			}
		}
		-->
		</script>

		<table width="150" height="60" border="1" cellspacing="0" cellpadding="0" bordercolor="#CCCCCC" style="border-collapse:collapse">
		<form name="CD" method="post" onSubmit="return comment_delete_checkInput()">
		<tr bgcolor="#F6F6F6">
			<td align="center" height="20"><font color="#818181">password</font></td>
		</tr>
		<tr bgcolor="#F8F8F8" height="50">
			<td align="center">
			<img src="images/space.gif" width="10" height="5"><br>
			<input type="password" name="input_commenter_passwd" size="15" maxlength="20" class="input"><br>
			<img src="images/space.gif" width="10" height="5"><br>
			<input type="submit" value="ok" class="button">
			<input type="button" value="cancel" class="button" onClick="comment_Auth_Popup('',event)"><br>
			<img src="images/space.gif" width="10" height="5"><br>
			</td>
		</tr>
		</form>
		</table>
	</div>
	<!-- 코멘트 삭제 누른 경우 종료 -->

	<?for($i=0; $i<count($cmt_Records); $i++){?>
		<table cellpadding="0" cellspacing="0" border="0" width="<?=$Records["width_sum"]?>">
		<tr><td colspan="2" background="<?=$Records["line_img"]?>"><img src="images/space.gif" height="1"></td></tr>
		<tr><td colspan="2"><img src="images/space.gif" width="10" height="5"></td>
		<tr><td colspan="2"><b><?=$cmt_Records[$i]["commenter_name"]?>(<?=$cmt_Records[$i]["commenter_id"]?>)</b>
		<span style="font:8pt tahoma; color:#778899;margin-left:5px;"><?=$cmt_Records[$i]["signdate"]?></span>
		</td></tr>
		<tr><td colspan="2"><img src="images/space.gif" width="10" height="5"></td>
		<tr>
		<td width="<?=$Records["width_sum"]?>"><?=nl2br(auto_link(str_replace("  ","&nbsp; ",str_replace("\t","&nbsp; &nbsp; ",$cmt_Records[$i]["comment_content"]))))?></td>
		<td valign="top" align="right">
		<?if($Sync_id==$cmt_Records[$i]["commenter_id"] || $Sync_level>49){?>
			<img src="images/x.gif" style="cursor:pointer" onClick="comment_delete_confirm('<?=$cmt_Records[$i]["cmt_idx"]?>')">
		<?}else{?>
			<img src="images/x.gif" style="cursor:pointer" onClick="comment_Auth_Popup('<?=$cmt_Records[$i]["cmt_idx"]?>',event)">
		<?}?>
		</td>
		</tr>
		<tr><td colspan="2"><img src="images/space.gif" width="10" height="20"></td>
		</tr>
		</table>
	<?}?>

	<script language="javascript">
	<!--
	function comment_checkInput () {
		if (!document.P.commenter_name.value) {
			alert("Please, Input your name!");
			document.P.commenter_name.focus();
			return false;
		}
		if (!document.P.comment_content.value) {
			alert("Please, Input contents");
			document.P.comment_content.focus();
			return false;
		}
	}
	//-->
		</script>

	<table cellSpacing="0" cellpadding="0" border="0" width="<?=$Records["width_sum"]?>">
	<td align="right"><font color="#FF0066"><!--*</font> 표시는 필수항목입니다.--></td>
	</table>

	<form name="P" method="post" action="<?=$_SERVER['PHP_SELF']?>?mode=process&uid=<?=$_GET["uid"]?>&startPage=<?=$_GET["startPage"]?><?=$etc_key?>" onSubmit="return comment_checkInput();">

	<table width="<?=$Records["width_sum"]?>" cellspacing="1" cellpadding="0" border="0">

	<tr style="display:none;">
	<td align="center" bgcolor="#F6F6F6"><font color="#818181">ID(IP)</font></td>
	<td>&nbsp;<input type="text" name="commenter_id" value="<?if($Sync_id) echo $Sync_id; else echo $_SERVER["REMOTE_ADDR"];?>" class="input" readonly></td>
	</tr>

	<tr><td colspan="100" background="<?=$Records["line_img"]?>"><img src="images/space.gif" height="1"></td></tr>

	<tr>
	<td align="center" bgcolor="#F6F6F6" width="98"><font color="#818181">Name</font><font color="#FF0066">*</font></td>
	<td>&nbsp;<input type="text" name="commenter_name" value="" class="input"></td>
	</tr>
	<tr><td colspan="100" background="<?=$Records["line_img"]?>"><img src="images/space.gif" height="1"></td></tr>

	<tr>
	<td align="center" bgcolor="#F6F6F6"><font color="#818181">Password</font></td>
	<td>&nbsp;<input type="password" name="commenter_passwd" class="input"></td>
	</tr>

	<tr><td colspan="100" background="<?=$Records["line_img"]?>"><img src="images/space.gif" height="1"></td></tr>

	<tr>
	<td rowspan="2" valign="top" align="center" bgcolor="#F6F6F6">
	<font color="#818181">Contents</font><font color="#FF0066">*</font>
	</td>
	<td valign="top">&nbsp;<textarea name="comment_content" class="input" style="width:99%;height:50;overflow-y:visible;"></textarea>
	</td>
	</tr>

	<tr>
	<td align="right" valign="middle" height="40" style="padding-right:1">
	&nbsp;<input type="image" src='images/btn_comment.gif' style="cursor:pointer;" align=absmiddle>
	</td>
	</tr>

	<tr><td colspan="100" background="<?=$Records["line_img"]?>"><img src="images/space.gif" height="1"></td></tr>
	</table>

	</form>

<?}else{?>
	<table cellpadding="0" cellspacing="0" border="0" width="<?=$Records["width_sum"]?>"><tr>
	<td background="<?=$Records["line_img"]?>"><img src="images/space.gif" height="1"></tr></table>
<?}?>
	<table cellpadding="0" cellspacing="0"><tr><td><img src='images/space.gif' height="5"></td></tr></table>

	<table cellSpacing="0" cellpadding="0" border="0" width="<?=$Records["width_sum"]?>">
	<tr>
	<td style="padding-bottom:3">
	<?if($Records["link_style"]=="open"){?>
	<a href="open.php?boardid=<?=$Records["boardid"]?>"><img src='images/zbtn_open.gif' border=0 align=absmiddle></a>
	<?}?>
	<a href="list.php?startPage=<?=$_GET["startPage"]?><?=$etc_key?>">	<img src='images/btn_list_eng.gif' border=0 align=absmiddle></a>
	<?if($Records["writelevel"]==0 || $Sync_level>=$Records["writelevel"]){?>
	<a href="write.php?startPage=<?=$_GET["startPage"]?><?=$etc_key?>"><img src='images/btn_write_eng.gif' border=0 align=absmiddle></a>
		<?if($Records["reply_use"]==1){?>
		<a href="reply.php?uid=<?=$_GET["uid"]?>&startPage=<?=$_GET["startPage"]?><?=$etc_key?>"><img src='images/btn_reply_eng.gif' border=0 align=absmiddle></a>
		<?}?>	
		<?if($Sync_level>49 || $Sync_id==$id_Records["boarder_id"]){?>
		<a href="modify.php?uid=<?=$_GET["uid"]?>&startPage=<?=$_GET["startPage"]?><?=$etc_key?>"><img src='images/btn_modify_eng.gif' border=0 align=absmiddle></a>
		<?}else{?>
		<img src='images/btn_modify_eng.gif' border=0 align=absmiddle onClick="modify_Auth_Popup(event)" style="cursor:pointer">
		<?}?>
		<?if($Sync_level>49 || $Sync_id==$id_Records["boarder_id"]){?>
		<img src='images/btn_delete_eng.gif' border=0 align=absmiddle onClick="delete_confirm()" style="cursor:pointer">
		<?}else{?>
		<img src='images/btn_delete_eng.gif' border=0 align=absmiddle onClick="delete_Auth_Popup(event)" style="cursor:pointer">
		<?}?>	
	<?}?>
	<?if (!${$_GET["boardid"]."_".$_GET["uid"]."_vote"} && $Records["vote_use"]=="1") {?>
	<a href="<?=$_SERVER['PHP_SELF']?>?mode=vote&uid=<?=$id_Records["uid"]?>&startPage=<?=$_GET["startPage"]?><?=$etc_key?>"><img src='images/zbtn_vote.gif' border=0 align=absmiddle></a>
	<?}?>
	</td>
	</tr>
	</table>

<table cellpadding="0" cellspacing="0" border="0" style="margin-top:50px;"><tr><td></td></tr></table>

<!-------------리스트 시작--------------->

<?
## 기본값설정
if(!$_GET["find_key_1"] && !$_GET["find_key_2"] && !$_GET["find_key_3"]) $_GET["find_key_1"]="subject";
$_GET["find_value"] = ereg_replace("[?\.\"]","",str_replace(","," ",$_GET["find_value"]));

## Get폼값설정
$etc_key  = "";
$etc_key .= "&boardid=".$_GET["boardid"];
if($_GET["find_value"] && $_GET["find_key_1"]) $etc_key .= "&find_key_1=".$_GET["find_key_1"];
if($_GET["find_value"] && $_GET["find_key_2"]) $etc_key .= "&find_key_2=".$_GET["find_key_2"];
if($_GET["find_value"] && $_GET["find_key_3"]) $etc_key .= "&find_key_3=".$_GET["find_key_3"];
if($_GET["find_value"]) $etc_key .= "&find_value=".$_GET["find_value"];

## 토탈카운트
$Query  = "SELECT count(uid)";
$Query .= " FROM ".$_GET["boardid"];
$Query .= " where 1";
if($_GET["category"]!="전체") $Query .= " and category='".$_GET["category"]."'";
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
    $PageLinks .= "<A HREF=\"list.php?startPage=$prevBlockPage&$etc_key\">[Prev]</A>";
}

if($countTotalPage  > 10) $PageLinks .= "<A HREF=\"list.php?startPage=1&$etc_key\">[1]..</A> ";

for($i = 0, $j = 0; $j < $countTotalPage && $i < 10; $i ++) {
    $j = $startBlockPage + $i;

		if($startPage == $j) {
				$PageLinks .= "<B><font color='#FF8800'>[$j]</font></B>";
		} else {
				$PageLinks .= "<A HREF=\"list.php?startPage=$j&$etc_key\">[$j]</A>";
		}
}

if($countTotalPage  > 10) $PageLinks .= " <A HREF=\"list.php?startPage=$countTotalPage&$etc_key\">..[$countTotalPage]</A>";

if($nextBlockPage > 10 && $nextBlockPage < $countTotalPage) {
    $PageLinks .= "<A HREF=\"list.php?startPage=$nextBlockPage&$etc_key\">[Next]</A>";
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
$Board_id["secret_passwd"]		= NULL;
$Board_id["content"]				= NULL;
$Board_id["signdate"]				= NULL;
$Board_id["hit"]						= NULL;
$Board_id["vote"]					= NULL;
$Board_id["thread"]					= NULL;
$Board_id["userfile_name"]		= NULL;
$Board_id["userfile_extra"]			= NULL;
$Board_id["userfile_size"]			= NULL;
$Board_id["userfile_download"]	= NULL;
$Board_id["html"]						= NULL;
$Board_id["auto_br"]				= NULL;
$Board_id["notice"]					= NULL;

$Query  = " SELECT " . selectQuery($Board_id, $_GET["boardid"]);
$Query .= ",count(".$_GET["boardid"]."_comment.cmt_idx) as cmt_total";
$Query .= ",max(".$_GET["boardid"]."_comment.signdate) as cmt_max";
$Query .= " FROM ".$_GET["boardid"];
$Query .= " LEFT OUTER JOIN ".$_GET["boardid"]."_comment";
$Query .= " ON ".$_GET["boardid"].".uid=".$_GET["boardid"]."_comment.uid";
$Query .= " where 1";
if($_GET["category"]!="전체") $Query .= " and category='".$_GET["category"]."'";
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
$Query .= " GROUP BY ".$_GET["boardid"].".uid";
$Query .= " ORDER BY notice DESC, fid DESC, thread ASC";
$Query .= " LIMIT $startNo, $ViewPerPage";

$list_Records = array();

$id_cnn = mysql_query($Query) or exit(mysql_error());
while($id_rst = mysql_fetch_assoc($id_cnn)) {
    $list_Records = array_merge($list_Records, array($id_rst));
}
?>

<!--table width="<?=$Records["width_sum"]?>" cellSpacing="0" cellpadding="0" border="0">
<tr>
<td-->

	<table cellpadding="0" cellspacing="0"><tr><td><img src='images/space.gif' height='2'></td></tr></table>

	<!-- 비밀글 보기를 누른 경우 시작-->
	<script language="javascript">
	<!--
	function secret_movePopup(ff,xx,yy) { ff.left = xx; ff.top = yy;}
	function secret_Auth_Popup(UID,event) {
		var fvar = document.getElementById('secret_cate').style;
		var xvar = event.clientX -30;
		var yvar = event.clientY + document.body.scrollTop - 100;
			 if (yvar<20) yvar = 30;
		secret_movePopup(fvar,xvar,yvar);

		if (document.getElementById('secret_cate').style.display != "none"){
		document.getElementById('secret_cate').style.display = "none";
		}else{
		document.getElementById('secret_cate').style.display = "";
		}
		document.R.action="view.php?uid="+UID+"&startPage=<?=$startPage?><?=$etc_key?>";
	}
	//-->
	</script>

	<div id="secret_cate" style="position:absolute;left:250;top:300;z-index:100;display:none;">
		<script language="javascript">
		<!--
		function secret_checkInput() {
			if (!document.R.input_secret_passwd.value) {
				alert("비밀번호를 입력하세요.");
				document.R.input_secret_passwd.focus();
				return false;
			}
		}
		-->
		</script>

		<table width="150" height="60" border="1" cellspacing="0" cellpadding="0" bordercolor="#CCCCCC" style="border-collapse:collapse">
		<form name="R" method="post" onSubmit="return secret_checkInput()">

		<tr bgcolor="#F6F6F6">
			<td align="center" height="20"><font color="#818181">비밀번호</font></td>
		</tr>
		<tr bgcolor="#F8F8F8" height="50">
			<td align="center">
			<img src="images/space.gif" width="10" height="5"><br>
			<input type="password" name="input_secret_passwd" size="15" maxlength="20" class="input"><br>
			<img src="images/space.gif" width="10" height="5"><br>
			<input type="submit" value="ok" class="input">
			<input type="button" value="cancel" class="button" onClick="secret_Auth_Popup('',event)"><br>
			<img src="images/space.gif" width="10" height="5"><br>
			</td>
		</tr>
		</form>
		</table>
	</div>
	<!--  비밀글 보기를 누른 경우 종료-->

	<?if($Records["link_style"]!="gallery"){?>
	<!--일반목록-->
	<table border="0" cellpadding="0" cellspacing="0" width="<?=$Records["width_sum"]?>">
		<tr>
		<? if($Records["no_use"] == "1")			{?><col width="<?=$Records["no_wid"]?>">				<?echo"\n";}?>
		<? if($Records["img_icon_use"] == "1")	{?><col width="<?=$Records["img_icon_wid"]?>">	<?echo"\n";}?>
		<? if($Records["category_use"] == "1")	{?><col width="<?=$Records["category_wid"]?>">		<?echo"\n";}?>
		<? if($Records["subject_use"] == "1")		{?><col width="<?=$Records["subject_wid"]?>">		<?echo"\n";}?>
		<? if($Records["name_use"] == "1")		{?><col width="<?=$Records["name_wid"]?>">			<?echo"\n";}?>
		<? if($Records["date_use"] == "1")			{?><col width="<?=$Records["date_wid"]?>">			<?echo"\n";}?>
		<? if($Records["vote_use"] == "1")			{?><col width="<?=$Records["hit_wid"]?>">				<?echo"\n";}?>
		<? if($Records["hit_use"] == "1")			{?><col width="<?=$Records["hit_wid"]?>">				<?echo"\n";}?>
		</tr>

		<tr>
			<td colspan="100"><img src="<?=$Records["title_img"]?>" border="0"></td>
		</tr>

		<script language="JavaScript">
		<!--
		function rolldown(n){
			for (var i=0;i<<?=count($list_Records)?>;i++){
				if(i!=n){
					document.getElementById('roll_q'+i).style.display = "none";
					document.getElementById('roll_a'+i).style.display = "none";
					document.getElementById('roll_line'+i).style.display = "none";
				}
			}
			if(document.getElementById('roll_q'+n).style.display == "none"){
				document.getElementById('roll_q'+n).style.display = "inline";
				document.getElementById('roll_a'+n).style.display = "inline";
				document.getElementById('roll_line'+n).style.display = "inline";
			}else{
				document.getElementById('roll_q'+n).style.display = "none";
				document.getElementById('roll_a'+n).style.display = "none";
				document.getElementById('roll_line'+n).style.display = "none";
			}
		}
		//-->
		</script>

	<?
		for($i=0; $i<count($list_Records); $i++){
	?>
		<tr><td colspan='100'><img src='images/space.gif' height='5'></tr>
		<tr height="22" align="center">

		<? if($Records["no_use"] == "1"){?>
			<td>
			<?=($list_Records[$i]["notice"]=="y")?"<b>공지</b>":$countTotalRecord-($ViewPerPage*($startPage-1)+$i)?>
			</td>
		<?}?>

		<? if($Records["img_icon_use"] == "1"){?>
			<td height="<?=$Records["img_icon_size"]?>">
			<?
				$fn=explode("|",$list_Records[$i]["userfile_name"]);
				$fe=explode("|",$list_Records[$i]["userfile_extra"]);
				$fs=explode("|",$list_Records[$i]["userfile_size"]);
				$fd=explode("|",$list_Records[$i]["userfile_download"]);
				$fp=explode("|",$list_Records[$i]["userfile_play"]);
				
				$icon_width = $Records["img_icon_size"];
				$ext = strtolower(strrchr($fn[0],"."));

				if($ext==".jpg" || $ext==".gif" || $ext==".png"){				
					if(file_exists($upload_root."/".$_GET["boardid"]."/thumb_".$fe[0])){
						$preview_img = "thumb_".$fe[0];
					} else {
						$preview_img = $fe[0];				
					}
					$size = @getimagesize($upload_root."/".$_GET["boardid"]."/".$preview_img); 
					$img_width = $size[0];
					$img_height = $size[1];
					if(!$size) echo "<img src='$upload_dir/$_GET[boardid]/$preview_img' width='$icon_width'>";
					elseif($img_width>$img_height) echo "<img src='$upload_dir/$_GET[boardid]/$preview_img' width='$icon_width'>";
					else echo "<img src='$upload_dir/$_GET[boardid]/$preview_img' height='$icon_width' border='0'>";
				}
			?>
			</td>
		<?}?>

		<? if($Records["category_use"] == "1"){?>
			<td>
				<div style="width:<?=$Records["category_wid"]?>; text-overflow:ellipsis; overflow:hidden;"><nobr><?=$list_Records[$i]["category"]?></nobr></div>
			</td>
		<?}?>

		<? if($Records["subject_use"] == "1"){?>
			<td align="left" style="padding-left:5px;">
			<!--div style="width:<?=$Records["subject_wid"]?>; text-overflow:ellipsis; overflow:hidden;"><nobr-->
			<?
				for($j=1; $j<strlen($list_Records[$i]["thread"])/4; $j++) { //답글 들여쓰기
					echo "<img src='images/space.gif' width='5'>";
					if($j==5) break;
				}
				if(strlen($list_Records[$i]["thread"])/4 > 1) echo "<img src='images/reply.gif' style='margin-right:5'>"; //답글 아이콘
				$my_subject =htmlspecialchars($list_Records[$i]["subject"]);//제목 HTML 치환
				if(strlen($my_subject)<1) $my_subject = "No Subject"; //빈제목 치환

				if($_GET["find_value"]){ // 검색어 치환하기
					$arr_find_value = explode(",",$_GET["find_value"]);
					for($j=0;$j<count($arr_find_value);$j++){
						$my_subject = eregi_replace("(\\$arr_find_value[$j])", "<font color='#FF0066'>\\1</font>", $my_subject);
					}
				}

				if($list_Records[$i]["secret_passwd"]) $list_icon =  "<img src='images/secret.gif' style='margin:0,3,0,0'>"; //비밀글 마크
				if($list_Records[$i]["secret_passwd"]) {
					if($Sync_level<50) {
						$list_link = "<span onclick=\"secret_Auth_Popup(event,'".$list_Records[$i]["uid"]."')\" style='cursor:pointer' onfocus='this.blur();'>$my_subject</span>";
					}else{
						$list_link = "<a href='view.php?uid=".$list_Records[$i]["uid"]."&startPage=$startPage$etc_key' onfocus='this.blur();'>$my_subject</a>";
					}
				}else{
					if($Records["link_style"]=="rolldown") {
						$list_link = "<a href=\"javascript:rolldown('$i');\" onfocus='this.blur();'>$my_subject</a>";
					}elseif($Records["link_style"]=="outerlink"){
						$list_link = "<a href=\"http://".str_replace("http://","",strtolower($list_Records[$i]['content']))."\" target=\"_blank\" onfocus='this.blur();'>$my_subject</a>";
					}else{
						$list_link = "<a href='view.php?uid=".$list_Records[$i]["uid"]."&startPage=$startPage$etc_key' onfocus='this.blur();'>$my_subject</a>";
					}
				}

				echo $list_icon.$list_link;
								
				if(time()-strtotime($list_Records[$i]["cmt_max"])<60*60*24*$new_date) echo "<font color='#FF0066'>";
				if($list_Records[$i]["cmt_total"]) echo "<img src='images/space.gif' width='5'>[".$list_Records[$i]["cmt_total"]."]</font>";

				if($list_Records[$i]["userfile_name"]) echo "<img src='images/space.gif' width='5'><img src='images/file.gif' alt='$bt[202]'>";
				if(time()-strtotime($list_Records[$i]["signdate"])<60*60*24*$new_date) echo " <img src='images/new.gif'>";
				echo "<!--/nobr></div-->";
			?>
			
			</td>
		<?}?>

		<? if($Records["name_use"] == "1"){?>
			<td>
				<div style="width:<?=$Records["name_wid"]-10?>; text-overflow:ellipsis; overflow:hidden;"><nobr><?=htmlspecialchars($list_Records[$i]["boarder_name"])?></nobr></div>
			</td>
		<?}?>

		<? if($Records["date_use"] == "1"){?>
			<td>
				<?=substr($list_Records[$i]["signdate"],0,10)?>
			</td>
		<?}?>

		<? if($Records["vote_use"] == "1"){?>
			<td>
				<?=$list_Records[$i]["vote"]?>
			</td>
		<?}?>

		<? if($Records["hit_use"] == "1"){?>
			<td>
				<?=$list_Records[$i]["hit"]?>
			</td>
		<?}?>

	</tr>
	<tr><td colspan='100' background='<?=$Records["line_img"]?>'><img src='images/space.gif' height='1'></tr>

		<? if($Records["link_style"] == "rolldown"){?>
			<tr bgcolor="#F8F6E7" id="roll_q<?=$i?>" style="display:none;">
			<td align="right" valign="top"><img src='images/ico_q.gif' style="margin-top:9"></td>
			<td colspan="100" style="padding:10 10 0 15">
			<?=$list_Records[$i]["subject"]?>
			</td>
			</tr>
			<tr bgcolor="#F8F6E7" id="roll_a<?=$i?>" style="display:none;">
			<td align="right" valign="top"><img src='images/ico_a.gif' style="margin-top:9"></td>
			<td colspan="100" style="padding:10 10 15 15">
			<?=rtrim($list_Records[$i]["content"])?>
			<?if($Sync_level>=50){?>
			<a href="view.php?uid=<?=$list_Records[$i]["uid"]?>&startPage=<?=$startPage?><?=$etc_key?>"><img src='images/btn_modify_eng.gif' border="0" align="right"></a>
			<?}?>
			</td>
			</tr>
			<tr id="roll_line<?=$i?>" style="display:none;"><td colspan='100' background='<?=$Records["line_img"]?>'><img src='images/space.gif' height='1'></tr>
		<?}?>
	<?}?>
	</table>
	<!--//일반목록-->
	<?}else{?>
	<!--갤러리목록-->
	<table border="0" cellpadding="0" cellspacing="0" width="<?=$Records["width_sum"]?>">
	<tr><td colspan="100" bgcolor="#90A0B8"><img src="images/space.gif" height="2"></td></tr>
	<tr><td colspan=100 height=10></td></tr>

	<tr>
	<?
	for($i=0; $i<count($list_Records); $i++){
	$col_wid =$Records["img_icon_wid"];
	$fe=explode("|",$list_Records[$i]["userfile_extra"]);
	$ext = strtolower(strrchr($fn[0],"."));
	if(file_exists($upload_root."/".$_GET["boardid"]."/thumb_".$fe[0])) $preview_img = $upload_dir."/".$_GET["boardid"]."/thumb_".$fe[0];
	else $preview_img = "images/no_gallery.gif";
	if($i%$Records["col_count"] != 0) echo "<td width='".$Records["col_padding"]."'></td>";
	$img_link = "<a href='view.php?uid=".$list_Records[$i]["uid"]."&startPage=$startPage$etc_key' onfocus='this.blur();'>";
	
	if(file_exists($upload_root."/".$_GET["boardid"]."/thumb_".$fe[0])) {
		$size = getimagesize($upload_root."/".$_GET["boardid"]."/thumb_".$fe[0]);
		if($size[0]>$size[1]) {
			$rewidth = $Records["img_icon_size"];
			$reheight = $Records["img_icon_size"] * $size[1] / $size[0];
		}else{
			$rewidth = $Records["img_icon_size"] * $size[0] / $size[1];
			$reheight = $Records["img_icon_size"];
		}
	} else {
			$rewidth = "54";
			$reheight = "44";	
	}
	?>

	<?if($i>0 && $i%$Records["col_count"]==0) echo "</tr><tr><td colspan=100 height=35></td></tr><tr>";?>
	<td width="<?=$col_wid?>" align="center" valign="top">
	<div style="height:<?=$Records["img_icon_size"]?>;margin-bottom:5"><table border="0" cellpadding="0" cellspacing="0"><td height="<?=$Records["img_icon_size"]?>" valign="middle"><?=$img_link?><img src="<?=$preview_img?>" style="vertical-align:middle;" border="0" width="<?=$rewidth?>" height="<?=$reheight?>"></a></td></table></div>

	<? if($Records["no_use"] == "1"){?>
		<font color="#0953A8"><?=$countTotalRecord-($ViewPerPage*($startPage-1)+$i)?>.</font>
	<?}?>

	<? if($Records["category_use"] == "1"){?>
		[<?=$list_Records[$i]["category"]?>]
	<?}?>

	<?if($Records["subject_use"] == "1"){
		$my_subject =htmlspecialchars($list_Records[$i]["subject"]);//제목 HTML 치환
		if(strlen($my_subject)<1) $my_subject = "No Subject"; //빈제목 치환

		if($_GET["find_value"]){ // 검색어 치환하기
			$arr_find_value = explode(",",$_GET["find_value"]);
			for($j=0;$j<count($arr_find_value);$j++){
				$my_subject = eregi_replace("(\\$arr_find_value[$j])", "<font color='#FF0066'>\\1</font>", $my_subject);
			}
		}

		echo "<a href='view.php?uid=".$list_Records[$i]["uid"]."&startPage=$startPage$etc_key' onfocus='this.blur();'>$my_subject</a>";


		if($list_Records[$i]["cmt_max"] && time()-strtotime($list_Records[$i]["cmt_max"])<60*60*24*$new_date) echo "<font color='#FF0066'>";
		if($list_Records[$i]["cmt_total"]) echo "<img src='images/space.gif' width='5'>[".$list_Records[$i]["cmt_total"]."]</font>";


		if(time()-strtotime($list_Records[$i]["signdate"])<60*60*24*$new_date) echo " <img src='images/new.gif'>";
	}?>

	<span style="font:8pt tahoma; color:#778899;">
	<? if($Records["date_use"] == "1"){?>
		<br><?=date("Y/m/d",strtotime($list_Records[$i]["signdate"]))?>
	<?}?>

	<? if($Records["vote_use"] == "1" || $Records["hit_use"] == "1") echo "(";?><? if($Records["vote_use"] == "1") echo $list_Records[$i]["vote"];?><? if($Records["vote_use"] == "1" && $Records["hit_use"] == "1") echo "/";?><? if($Records["hit_use"] == "1") echo $list_Records[$i]["hit"];?><? if($Records["vote_use"] == "1" || $Records["hit_use"] == "1") echo ")";?>
	</span>

	<? if($Records["name_use"] == "1"){?>
		<br><?=htmlspecialchars($list_Records[$i]["boarder_name"])?>
	<?}?>

	</td>
	<?}?>

	<?	if($i%$Records["col_count"]!=0) for($i=count($list_Records)%$Records["col_count"]; $i<$Records["col_count"]; $i++) echo "<td></td><td></td>";?>
	</tr>
	<tr><td colspan=100 height=35></td></tr>
	<tr><td colspan='100' background='<?=$Records["line_img"]?>'><img src='images/space.gif' height='1'></tr>
	</table>	
	<!--//갤러리복록-->
	<?}?>

	<table cellpadding="0" cellspacing="0"><tr><td><img src='images/space.gif' height='2'></td></tr></table>

	<table cellpadding="0" cellspacing="0" width="<?=$Records["width_sum"]?>">
	<tr>
	<td align="center" style="padding-top:2"><?=$PageLinks?></td>
	</tr>
	</table>

	<table cellSpacing="0" cellpadding="0" border="0" width="<?=$Records["width_sum"]?>">
	<tr>
	<td align="left" valign="top">
	<?if($Sync_level>=$Records["writelevel"]){?>
	<a href="write.php?startPage=<?=$startPage?><?=$etc_key?>"><img src='images/btn_write_eng.gif' border=0 align=absmiddle></a>
	<?}?>
	</td>
	<?if($Records["search_use"]=="1"){?>
	<!-- 검색버튼 테이블 ---------------------------------------------->
	<td align="right" valign="top">
	<form name="S" method="get" action="list.php">
	<input type="hidden" name="boardid" value="<?=$_GET["boardid"]?>">
	<input type="hidden" name="startPage" value="1">
		<input type="checkbox" name="find_key_1" value="subject" <?if($_GET["find_key_1"]) echo "checked";?>>제목
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

<?
} elseif($mode=="vote") {

//checkform("post");
//exit;

// Vote 증가
if (!${$_GET["boardid"]."_".$_GET["uid"]."_vote"}) {
	$Query = "update ".$_GET["boardid"]." set vote = vote + 1 where uid = ".$_GET["uid"];
	mysql_query($Query) or die(mysql_error() . '$bt[307]');
	setcookie ($_GET["boardid"]."_".$_GET["uid"]."_vote", "1");
}
echo "<script>alert('추천하였습니다.')</script>";
echo "
	<body onLoad='document.VF.submit()'>
	<form name='VF' method='post' action='view.php?uid=$_GET[uid]&startPage=$_GET[startPage]$etc_key'>
	<input type='hidden' name='secret_pass' value='1'>
	</form>
	</body>
";
exit;

} else {

//checkform("post");
//exit;

## 쿼리 시작
$Comm["boardid"]					= $_GET["boardid"];
$Comm["uid"]							= $_GET["uid"];
$Comm["commenter_name"]		= $_POST["commenter_name"];
$Comm["commenter_id"]			= $_POST["commenter_id"];
$Comm["commenter_passwd"]	= $_POST["commenter_passwd"];
$Comm["comment_content"]		= htmlspecialchars($_POST["comment_content"]);
$Comm["signdate"]					= date("Y-m-d H:i:s",time());

$Query = insertQuery($Comm, $_GET["boardid"]."_comment");
mysql_query($Query) or die(mysql_error() . 'DB에 기록하지 못했습니다.');

echo "
	<body onLoad='document.CF.submit()'>
	<form name='CF' method='post' action='view.php?uid=$_GET[uid]&startPage=$_GET[startPage]$etc_key'>
	<input type='hidden' name='secret_pass' value='1'>
	</form>
	</body>
";
exit;
}

ob_flush();
?>