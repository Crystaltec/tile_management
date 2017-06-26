<?
ob_start();
include "../../include/common.inc";
include "../../include/dbconn.inc";
include "../../include/user_functions.inc";
//echo $Sync_level;
## 접근권한 설정
if($Sync_level < 50) {
	echo"<script>alert('You don't have authorization to open this page!');</script>";
	echo"<script>history.go(-1)</script>";
	exit;
}

//echo "abc";
?>

<html>
<head>
<title><?echo($company)?></title>
<meta http-equiv="Content-Type" content="text/html"; charset="utf-8">
<link rel="stylesheet" type="text/css" href="/include/admin.css">
</head>
<body  LEFTMARGIN="0" TOPMARGIN="0" MARGINWIDTH="0" MARGINHEIGHT="0">
<?
    include($ABS_DIR."/zb/admin/menu.html");
?>
<br>

<center>

<?
// 기본값설정
if(!$_GET["find_key"]) $_GET["find_key"]="boardid";
if(!$_GET["sort_key"]) $_GET["sort_key"]="boardid";
if(!$_GET["sort_value"]) $_GET["sort_value"]="asc";

// Get폼값설정
$etc_key  = "";
if($find_value) $etc_key .= "&find_key=".$_GET["find_key"];
if($find_value) $etc_key .= "&find_value=".$_GET["find_value"];
if($sort_value) $etc_key .= "&sort_key=".$_GET["sort_key"];
if($sort_value) $etc_key .= "&sort_value=".$_GET["sort_value"];

// 토탈카운트
$Query  = "SELECT count(boardid)";
$Query .= " FROM zb";
$Query .= " where 1";
if($_GET["find_value"]) $Query .= " and ".$_GET["find_key"]." like '%".$_GET["find_value"]."%'";
if($_GET["sort_value"]) $Query .= " order by ".$_GET["sort_key"]." ".$_GET["sort_value"];

$rst = mysql_fetch_assoc(mysql_query($Query));
$countTotalRecord = $rst["count(boardid)"];

## 페이지 시작
$ViewPerPage				= 20;
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
				$PageLinks .= "<B>[$j]</B>";
		} else {
				$PageLinks .= "<A HREF=\"$_SERVER['PHP_SELF']?startPage=$j&$etc_key\">[$j]</A>";
		}
}

if($countTotalPage  > 10) $PageLinks .= " <A HREF=\"$_SERVER['PHP_SELF']?startPage=$countTotalPage&$etc_key\">..[$countTotalPage]</A>";

if($nextBlockPage > 10 && $nextBlockPage < $countTotalPage) {
    $PageLinks .= "<A HREF=\"$_SERVER['PHP_SELF']?startPage=$nextBlockPage&$etc_key\">[Next]</A>";
}
## 페이지 종료

// 쿼리 시작 ----------------------------------------------------------
$Board_db["boardid"]= NULL;
$Board_db["boardname"]= NULL;
$Board_db["readlevel"]= NULL;
$Board_db["writelevel"]= NULL;
$Board_db["signdate"]= NULL;

$Records = array();

$Query  = " SELECT " . selectQuery($Board_db, "zb");
$Query .= " FROM zb";
$Query .= " where 1";
if($_GET["find_value"]) $Query .= " and ".$_GET["find_key"]." like '%".$_GET["find_value"]."%'";
if($_GET["sort_value"]) $Query .= " order by ".$_GET["sort_key"]." ".$_GET["sort_value"];
$Query .= " LIMIT $startNo, $ViewPerPage";
//echo $Query ;
$cnn = mysql_query($Query) or exit(mysql_error());
while($rst = mysql_fetch_assoc($cnn)) {
    $Records = array_merge($Records, array($rst));
}
// 쿼리 종료 ----------------------------------------------------------
?>

<table width="1000" height="20" border="0" cellspacing="0" cellpadding="0">
<tr>
<td><b>Board Infomation</b></td>
<td width="60%" align="right" valign=bottom>  Total <?=$countTotalRecord?> [<?=$startPage?> / <?=$countTotalPage?> page]</td>
</tr>
</table>

<table width="1000" border="1" cellpadding="2" cellspacing="0" bordercolor="#336699" bgcolor="e0eeff" style="border-collapse:collapse">
<tr height="24"  bgcolor="b8c8ee">

<td align=center>No</font></td>
<td align=center>Board name</font></td>
<td align=center>Board Id</font></td>
<td align=center>Read level</font></td>
<td align=center>Write level</font></td>
<td align=center>Regdate</font></td>
<td align=center>Notice count</font></td>
<td align=center>View</font></td>
<td align=center>Modify</font></td>
<td align=center>Delete</font></td>
</tr>

<script language="javascript">
<!--
function recheck(){
	if(confirm("삭제하시겠습니까?")) {
		return true;
	} else {
		return false;
	}
}
-->
</script>

<?
if($countTotalRecord >0) {
	for($i=0; $i<count($Records); $i++){
?>
		<TR align="center">
		<td><?=$ViewPerPage*($startPage-1)+$i+1?></td>
		<td><?=$Records[$i]["boardname"]?></td>
		<td><?=$Records[$i]["boardid"]?></td>
		<td><?for($ii=0; $ii<count($array_level[0]); $ii++){ if($Records[$i]["readlevel"] == $array_level[0][$ii]) echo $array_level[1][$ii]; }?></td>
		<td><?for($ii=0; $ii<count($array_level[0]); $ii++){ if($Records[$i]["writelevel"] == $array_level[0][$ii]) echo $array_level[1][$ii]; }?></td>
		<td><?=substr($Records[$i]["signdate"],0,10)?></td>
		<?

		$query = "SELECT count(uid) FROM ".$Records[$i]["boardid"];
		$nnc = mysql_query($query) or exit(mysql_error());
		$count_result = mysql_fetch_assoc($nnc);
		$totals = number_format($count_result["count(uid)"]);
		?>

		<td><?=$totals?></td>
		<td><a href="<?=$HOME_DIR?>/zb/list.php?boardid=<?=$Records[$i]["boardid"]?>" target="_blank"><img src="../images/view.gif" border="0"></a></td>
		<td><a href="boardmodify.php?boardid=<?=$Records[$i]["boardid"]?>&startPage=<?=$startPage?><?=$etc_key?>"><img src="../images/modify.gif" border="0"></a></td>
		<td><a href="boarddelete.php?boardid=<?=$Records[$i]["boardid"]?>&startPage=<?=$startPage?><?=$etc_key?>" onClick="return recheck()"><img src="../images/delete.gif" border="0"></a></td>
		</tr>
<?
	}
}
?>
</table>

<table width="1000" cellpadding="0" cellspacing="0"><tr><td><img src='images/space.gif' height='2'></td></tr></table>

<table width="1000" cellpadding="0" cellspacing="0">
<tr>
<td align="left">
<input type="button" value="Regist new board" onClick="javascript:location.href='boardregister.php?startPage=<?=$startPage?>'" class="input">
</td>
<td align="right"><?=$PageLinks?></td>
</tr>
</table>

<!-- 검색버튼 테이블 ---------------------------------------------->
<table width="1000" cellSpacing="0" cellpadding="0" border="0">
<form name="S" method="get" action="<?=$_SERVER['PHP_SELF']?>">
<input type="hidden" name="startPage" value="1">
<tr>
<td align="right">
	<input type="radio" name="find_key" value="boardid" <?if($_GET["find_key"]=="boardid") echo "checked";?>>Board id
	<input type="radio" name="find_key" value="boardname" <?if($_GET["find_key"]=="boardname") echo "checked";?>>Board name
	&nbsp;
	<input type="text" size="15" maxlength="30" name="find_value" class="input" value="<?=$_GET["find_value"]?>">
	<input type="submit" value="Search" class="input">
</td>
</tr>
</form>
</table>

</center>

</body>
</html>
<? ob_flush(); ?>
