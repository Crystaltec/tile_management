<?
ob_start();
include "../include/common.inc";
include "../include/dbconn.inc";
include "../include/user_functions.inc";
include "include/board_config.inc";

if(!$_GET["boardid"]) Header("Location:");

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
$Records = array();
$Records = mysql_fetch_assoc($cnn);

$Records["category"] = explode(",",$Records["category"]);

## 기본값설정
if(!$_GET["find_key_1"] && !$_GET["find_key_2"] && !$_GET["find_key_3"]) $_GET["find_key_1"]="subject";
$_GET["find_value"] = ereg_replace("[?\.\"]","",str_replace(","," ",$_GET["find_value"]));
$_GET["category"] = ($_GET["new_category"])?urldecode($_GET["new_category"]):urldecode($_GET["category"]);
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
	echo"<script>location.replace('../zm/login.html')</script>";
	exit;
}

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

//echo $Query . "<p>";

$list_Records = array();

$id_cnn = mysql_query($Query) or exit(mysql_error());
while($id_rst = mysql_fetch_assoc($id_cnn)) {
    $list_Records = array_merge($list_Records, array($id_rst));
	//print_r($list_Records);
	//echo "<p>";
}
//print_r($list_Records);
?>

<?=eval($Records["head_php"])?>

<table width=<?=$Records["width_sum"]?> cellSpacing="0" cellpadding="0" border="0">
<td><?=$Records["head_note"]?></td>
</table>

<table width=<?=$Records["width_sum"]?> cellSpacing="0" cellpadding="0" border="0">
<?if($Records["category_use"] == "1"){?>
	<td align="left">
	<select name="category" onChange="location.href='list.php?new_category='+this.value+'<?=$etc_key?>';">
	<option value="전체">전체</option>
	<?for($i=0;$i<count($Records["category"]);$i++){?>
	<option value="<?=urlencode(trim($Records["category"][$i]))?>" <?if(trim($Records["category"][$i])==$_GET["category"]) echo "selected";?>><?=trim($Records["category"][$i])?></option>
	<?}?>
	</select>
	</td>
<?}?>
</table>

<table width=<?=$Records["width_sum"]?> cellSpacing="0" cellpadding="0" border="0">
<tr>
<td>

	<table cellpadding="0" cellspacing="0"><tr><td><img src='images/space.gif' height='2'></td></tr></table>

	<!-- 비밀글 보기를 누른 경우 시작-->
	<script language="javascript">
	<!--
	function secret_movePopup(ff,xx,yy) { ff.left = xx; ff.top = yy;}
	function secret_Auth_Popup(event,UID) {
		var fvar = document.all.secret_cate.style;
		var xvar = event.clientX -30;
		var yvar = event.clientY + document.body.scrollTop - 100;
			 if (yvar<20) yvar = 30;
		secret_movePopup(fvar,xvar,yvar);

		if (document.all.secret_cate.style.display != "none"){
		document.all.secret_cate.style.display = "none";
		}else{
		document.all.secret_cate.style.display = "";
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

		<table width="150" height="60" border="1" cellspacing="0" cellpadding="0" bordercolor="#D6D6B8" style="border-collapse:collapse">
		<form name="R" method="post" onSubmit="return secret_checkInput()">

		<tr bgcolor="#">
			<td align="center" height="20"><font color="#818181">Password</font></td>
		</tr>
		<tr bgcolor="#F4F4DD" height="50">
			<td align="center">
			<img src="images/space.gif" width="10" height="5"><br>
			<input type="password" name="input_secret_passwd" size="15" maxlength="20" class="input"><br>
			<img src="images/space.gif" width="10" height="5"><br>
			<input type="submit" value="확인" class="button">
			<input type="button" value="닫기" class="button" onClick="secret_Auth_Popup(event)"><br>
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
			<?=($list_Records[$i]["notice"]=="y")?"<b>Notice</b>":$countTotalRecord-($ViewPerPage*($startPage-1)+$i)?>
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
			<a href="view.php?uid=<?=$list_Records[$i]["uid"]?>&startPage=<?=$startPage?><?=$etc_key?>"><img src='images/zbtn_modify.gif' border="0" align="right"></a>
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
	<div style="height:<?=$Records["img_icon_size"]?>;margin-bottom:5;"><table border="0" cellpadding="0" cellspacing="0"><td height="<?=$Records["img_icon_size"]?>" valign="middle"><?=$img_link?><img src="<?=$preview_img?>" style="vertical-align:middle;" border="0" width="<?=$rewidth?>" height="<?=$reheight?>"></a></td></table></div>

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
	<td align="right" valign="top">
	<?if($Records["link_style"]=="open"){?>
	<a href="open.php?boardid=<?=$Records["boardid"]?>"><img src='images/zbtn_open.gif' border=0 align=absmiddle></a>
	<?}?>
	<?if($Records["writelevel"]==0 || $Sync_level>=$Records["writelevel"]){?>
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

<?=eval($Records["tail_php"])?>
<? ob_flush(); ?>