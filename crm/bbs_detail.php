<?php
ob_start();

$_BOARDID = $_REQUEST['boardid'];
include_once "include/logincheck.inc";
include_once "include/common.inc";
include_once "include/functions/database.php";
include_once "include/dbconn.inc";
include_once "include/myfunc.inc";
include_once "htmlinclude/head.php";

include_once "include/bbs_config.php";


$info_file_count = 1;

$no=$_REQUEST["no"];
$act=$_REQUEST["act"];

/*
$sql = "UPDATE ".$_BOARDID." SET hit=hit+1 WHERE no=".$no;
tep_db_query($sql);
*/
$sql="SELECT * FROM ".$_BOARDID." WHERE no=".$no;

$result = mysql_query($sql);
while ($rows = mysql_fetch_array($result)) {
	$no = $rows["no"];
	$look = $rows["look"];
	$thread = $rows["thread"];
	//$filename = array();
	//$filesize = array();
	//$filedown = array();
	//for($i=0; $i < $info_file_count; $i++) {
//		$filename[$i] = $rows["filename_".$i];
//		$filesize[$i] = $rows["filesize_".$i];
//		$filedown[$i] = $rows["filedown_".$i];
//	}

	$filename = $rows['filename_0'];
	$filesize = $rows['filesize_0'];
	$fn = explode("|", $rows["filename_0"]);
	$fs = explode("|",$rows["filesize_0"]);

	$subject =stripslashes(htmlspecialchars_decode($rows["subject"]));
	
	$link = stripslashes(htmlspecialchars_decode($rows["link"]));
	$name = $rows["name"];
	$regdate = $rows["regdate"]; 	
	$regdate = date("Y-m-d h:i:s", strtotime("$regdate  + 13 hours"));
		
	$regdate = getAUDate($regdate ,1);
	//$contents = nl2br($rows["contents"]);
	$contents = htmlspecialchars_decode(stripslashes($rows["contents"]));
	$pass = $rows["pass"];
	$hit = $rows["hit"];
	$builder_id = $rows["builder_id"];
}

?>
<script type="text/javascript" src="js/http_request.js"></script>
<script type="text/javascript" src="js/bbs.js"></script>
<script type="text/javascript" src="js/admin.js"></script>
<script type="text/javascript" src="js/menu.js"></script>
<script type="text/javascript" src="js/jquery.min.js"></script>
<script type="text/javascript" src="js/jquery.cycle.all.js"></script>
<script type="text/javascript">
function img_popup(boarid,fn,fe,wd,ht,wn,fnum){
		var x, y;
		
		
		var rv = 0;
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
			window.open("img_popup.php?boardid="+boarid+"&img_name="+fn+"&img_extra="+fn+"&rv="+rv,wn,"left="+x+",top="+y+",width="+wd+",height="+ht+",toolbar=no,menubar=no,status=no,scrollbars=auto,resizable=yes");
}

$(document).ready(function() {
    //$('.slideshow').cycle({
	//	fx: 'shuffle', // choose your transition type, ex: fade, scrollUp, shuffle, etc...
	//   speed: 900,
    //    timeout: 6000
//	});
});
</script>
<style type="text/css"> 
//.slideshow { height: 232px; width: 302px; margin: auto;}
.slideshow img { padding: 10px; border: 1px solid #ccc; background-color: #eee; }
</style>
</head>
<BODY leftmargin="0" topmargin="0">
<table border="0" cellpadding="0" cellspacing="0" width="100%">
<tr>
	<td width="191" height="100%" valign="top">
	<!-- LEFT -------------------------------------------------------------------------------------------------->
	<?php include "left.php"; ?>
	<!-- LEFT END ---------------------------------------------------------------------------------------------->
	</td>
	<!-- LEFT BG------------------------------------------------------------------------------------------------>
	<td width="1" bgcolor="#DFDFDF">
	</td>
	<!-- LEFT BG END-------------------------------------------------------------------------------------------->
	<td valign="top">
	<!-- BODY -------------------------------------------------------------------------------------------------->
	<table border="0" cellpadding="0" cellspacing="0" width="100%">
		<!-- BODY TOP ------------------------------------------------------------------------------------------->
		<tr>
			<td style="padding-left:15px"><?php include "top.php"; ?></td>
		</tr>
		<!-- BODY TOP END --------------------------------------------------------------------------------------->
		<!-- BODY CENTER ----------------------------------------------------------------------------------------->
		<tr>
			<td width="100%">			
			<table border="0" cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td style="padding-left:15px" valign="bottom" height="14" colspan="2">			
				</td>
			</tr>	
			<tr>
				<td style="padding-left:15px" valign="top" >
				<!-- CONTENTS -------------------------------------------------------------------------------------------->
				<table border="0" cellpadding="0" cellspacing="0" width="1000">
				<tr>
					<td valign="top">
					<table border="0" cellpadding="0" cellspacing="0" width="1000" class="font11_bold">
						<tr><td width="252"><img src="images/icon_circle03.gif"  style="vertical-align:middle;">&nbsp;<span style="height:21px"><?php echo $bbs_title?></span></td></tr>
						<tr><td height="3"></td></tr>
						<tr><td background="images/bg_check.gif" height="3"></td></tr>
						<tr><td height="2"></td></tr>
					</table>
					</td>
				</tr>
				<tr>
					<td valign="top">
					<table border="0" cellpadding="0" cellspacing="0" class="font9_02" width="1000">
					<tr>
						<td height="25" width="100" class="font9_boldb">Title</td><td width="900"><?=$subject?></td>
					</tr>
					<tr><td colspan="2"  height="1" bgcolor="#D8D5D5"></td></tr>
					<tr>
						<td height="25" width="100" class="font9_boldb">link </td><td width="900"><?=$link?></td>
					</tr>
					<tr><td colspan="2"  height="1" bgcolor="#D8D5D5"></td></tr>
					<tr>
						<td height="25" width="100" class="font9_boldb">Name </td><td width="900"><?=$name?></td>
					</tr>
					<tr><td colspan="2"  height="1" bgcolor="#D8D5D5"></td></tr>
					<tr>
						<td height="25" width="100" class="font9_boldb">Date </td><td width="900"><?=$regdate?></td>
					</tr>
					<tr><td colspan="2"  height="1" bgcolor="#D8D5D5"></td></tr>
					<?php
					if ($_BOARDID == "project_web") 
					{
					?>
					<tr>
						<td height="25" width="100" class="font9_boldb">Builder</td><td width="900"><?php echo getName("builder", $builder_id);?></td>
					</tr>
					<tr><td colspan="2"  height="1" bgcolor="#D8D5D5"></td></tr>
					<?php
					} else if ($_BOARDID == "tiles") {
					?>
					<tr>
						<td height="25" width="100" class="font9_boldb">Category</td><td width="900"><?php echo getName("tiles_category", $category_id);?></td>
					</tr>
					<tr><td colspan="2"  height="1" bgcolor="#D8D5D5"></td></tr>
					<?php }  else if ($_BOARDID == "nobel_stones") {
					?>
					<tr>
						<td height="25" width="100" class="font9_boldb">Category</td><td width="900"><?php echo getName("nobel_stones_category", $category_id);?></td>
					</tr>
					<tr><td colspan="2"  height="1" bgcolor="#D8D5D5"></td></tr>
					<?php } ?>
					<tr >
						<td colspan="2" height="250" valign="top" style="padding:3 0 3 0; ">
						
						<? // Slide show
						if($isAcceptThumb == "1") {
							if($filename){
						?>
						<div class="slideshow" style="float:center;">
						<?
								for($f=0;$f<count($fn);$f++){ 
								$ext = strtolower(strrchr($fn[$f],"."));
									if($ext==".jpg" || $ext==".gif" || $ext==".png"){
										$size = getimagesize($upload_dir."/".$_BOARDID."/".$fn[$f]);
										echo "<img src='$upload_dir/".$_BOARDID."/".$fn[$f]."' onClick=img_popup('".urlencode($_BOARDID)."','".urlencode($fn[$f])."','$fn[$f]','$size[0]','$size[1]','project_web_$f','$f') width='280' height='210' />";
									} 
								}
							?>
						</div> 
				
						<? } 
						} 	
						echo $contents;
						?>						
						</td>
					</tr>
					<tr><td colspan="2"  height="1" bgcolor="#D8D5D5"></td></tr>
					<tr>
						<td colspan="2" height="10" align="right">
							<div id="pass_space" style="display:none;height:40px">
							<span style="width:80px:height:25px">Password:</span>
							<span style="width:100px:height:25"><input type="password" class="input" name="pass" id="pass" size="15" maxlength="15"></span>
							<span style="width:30px:height:25px"><input type="button" value="ok" class="button03" onClick="goAction()"></span>
							</div>
						</td>
					</tr>
					<tr>
						<td><input type="button" value="list" onclick="location.href='bbs_list.php?boardid=<?=$_BOARDID?>&page=<?=$page?>'"></td><td align="right"><?if($isAcceptReply == "1") { ?><input type="button" value="reply" onclick="goReply()"><?}?>&nbsp;<input type="button" value="modify" onclick="location.href='bbs_write.php?boardid=<?=$_BOARDID?>&act=modify&no=<?=$no?>'">&nbsp;
						<?php
							if ($_BOARDID <> "contents" ) 
							{
						?>
						<input type="button" value="delete" onclick="goAction()">
						<?php 
							}
						?></td>
					</tr>
					</table>
					<p>
					<!-- Comments ------------------------------------------------------------------------------------------------------>
					
					<!-- Comments End -------------------------------------------------------------------------------------------------->
					<form name="hiddenForm">
					<input type="hidden" name="no" value="<?=$no?>">
					<input type="hidden" name="look" value="<?=$look?>">
					<input type="hidden" name="thread" value="<?=$thread?>">
					<input type="hidden" name="boardid" value="<?=$_BOARDID?>">
					<input type="hidden" name="act" value="delete">
					</form>

					</td>
				</tr>

				<tr><td></td></tr>
				</table>
				<!-- CONTENTS END -------------------------------------------------------------------------------------------->
			</td>
			<td>
			</td>
		  </tr>
		  <tr><td colspan="2" height="152"></td></tr>
		 </table>
		 </td>
	    </tr>
	    <!-- BODY CENTER END------------------------------------------------------------------------------------->
	   </table>
	<!-- BODY END -------------------------------------------------------------------------------------------->
	</td>
</tr>
<tr>
	<td colspan="4">
	<!-- BOTTOM -------------------------------------------------------------------------------------------->
	<?php include "bottom.php"; ?>
	<!-- BOTTOM END -------------------------------------------------------------------------------------------->
	</td>
</tr>
</table>
</BODY>
</HTML>

