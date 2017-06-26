<script>
var old_menu = '';
var old_cell = '';
function showsubmenu(obj ) {
	
	if( old_menu != obj ) {
		if( old_menu !='' )
			document.getElementById(old_menu).style.display = 'none';

		//submenu.style.display = 'block';
		document.getElementById(obj).style.display = 'block';
		old_menu = obj;
		//old_cell = cellbar;
		//alert("aa");
	} else {
		document.getElementById(obj).style.display = 'none';
		old_menu = '';
		//old_cell = '';
	}
	//alert(cellbar);
}
</script>
<?php
## 쿼리, 담을 배열 선언
$Rs_id = array();
$menu_Records = array();
$Rs_id["menucd"]						= NULL;
$Rs_id["menunm"]						= NULL;
$Rs_id["menulevel"]						= NULL;

$Query  = "SELECT " . selectQuery($Rs_id, "menu1");
$Query .= " FROM menu1 WHERE menulevel >= '".$Sync_alevel."' ORDER BY sortno ASC";
//echo $Query;
//echo $Sync_alevel;

$id_cnn = mysql_query($Query) or exit(mysql_error());
while($id_rst = mysql_fetch_assoc($id_cnn)) {
    $menu_Records = array_merge($menu_Records, array($id_rst));
	//print_r($menu_Records);
	//echo "<p>";
}
//echo count($menu_Records);
mysql_free_result($id_cnn);
?>

<head>
<style>
h1 {
    color:blue;
    font-family:verdana;
    font-size: 100%;
    text-align: center;
}
</style>
</head>


<table border="0" cellpadding="0" cellspacing="0" height="100%" width="176">
<tr>
	<td width="20" style="border-right:1px solid #DFDFDF" align="right">&nbsp;</td>
	<td height="25" width="156">&nbsp;<a href="index.php" >Home</a></td>
</tr>
<tr>
	<td width="20" style="border-right:1px solid #DFDFDF" background="images/bg_check.gif" height="3"></td>
	<td background="images/bg_check.gif" height="3"></td>
</tr>
<?php
for($i=0; $i<count($menu_Records); $i++) {
echo "<tr>
		<td width='20' style='border-right:1px solid #DFDFDF' align='center'><img src='images/bulletRedicon.png' HEIGHT='10' WIDTH='10' ></td>
		<td height='22' id='menu$i'>&nbsp;<a href=\"javascript:showsubmenu('submenu$i')\" style='text-decoration:none'><span style='color:black' id='menutxt$i'>" . $menu_Records[$i]["menunm"] . "</span></a></td>
	</tr>";
echo "<tr>
		<td background='images/bg_check.gif' height='3' colspan='2'></td></tr>";
// submenu Selecting...
echo "<tr>
		<td width='20' style='border-right:1px solid #DFDFDF'></td><td valign='top'>";
	echo "<span id='submenu$i' style='DISPLAY: none;'>";
	echo "<table border=0 cellpadding=0 cellspacing=0 width='156' height='24' bgcolor='#EBEBEB'>";
	$Query = "SELECT submenunm, submenulevel, url FROM menu2 WHERE upmenucd=" . $menu_Records[$i]["menucd"] . " AND submenulevel >='". $Sync_alevel ."' ORDER BY sortno ASC";
	$result = mysql_query($Query);
	while($rows = mysql_fetch_row($result)) {
		if(strpos($rows[2], "?"))
			$rows[2] .= "&lmenu=$i";
		else 
			$rows[2] .= "?lmenu=$i";
		echo "<tr><td width='10' style='padding-left:5px' height='22'><img src='images/icon_dot.gif'></td>
		<td class='menu01'><a href='" . $rows[2] . "'  style='font-size:9pt'>" . $rows[0] . "</a></td></tr>";
		echo "<tr><td height='3' colspan='2' style='padding-left:5px;' background='images/bg_check02.gif'></td></tr>";
	}
	mysql_free_result($result);
	echo "</table></span>";
echo "</td></tr>";
}
?>
<tr><td colspan="2" height="100%" valign="bottom" align="center" style="padding-bottom:20px"><!--<img src="images/bottom_logo.jpg">--></td></tr>
</table>
<?php
if($_REQUEST["lmenu"]!="") {
	$lmenu = $_REQUEST["lmenu"];
	echo "<script>showsubmenu('submenu". $lmenu . "'); document.getElementById('menu".$lmenu."').style.backgroundColor='#DF0000'; document.getElementById('menutxt".$lmenu."').style.color='#ffffff';</script>";
} else if(isset($_COOKIE["leftmenu"])){
	$lmenu = $_COOKIE["leftmenu"];
	echo "<script>showsubmenu('submenu". $lmenu . "'); document.getElementById('menu".$lmenu."').style.backgroundColor='#DF0000'; document.getElementById('menutxt".$lmenu."').style.color='#ffffff';</script>";
} else {
	echo "<script>showsubmenu('submenu0');</script>";
}
?>
