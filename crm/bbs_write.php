<?
ob_start();

$_BOARDID = $_REQUEST['boardid'];
include_once "include/logincheck.inc";
include_once "include/common.inc";
include_once "include/functions/database.php";
include_once "include/dbconn.inc";
include_once "include/myfunc.inc";
include_once "htmlinclude/head.php";

include_once "include/bbs_config.php";


// 게시글 쓰기 ------------------------------------------------------------------------------------------
// 게시글 쓰기, 수정, 답변에서 모두 공통으로 사용한다.
// $act로 각 액션을 구분한다.

$no=$_REQUEST["no"];
$act=$_REQUEST["act"];

if($act == "") {
	$action_url = "bbs_write_ok.php";
	$submit_action = "return checkBoardForm(this)";
	$txt = "Write";
} elseif($act=="modify") {
	$sql="select * from ".$_BOARDID." where no=".$no;
	$result = mysql_query($sql);
	$rows = mysql_fetch_array($result);
	
	$no = $rows["no"];
	$thread = $rows["thread"];
	$look = $rows["look"];
	$link = $rows["link"];
	
	//$filename = array();
	//$filesize = array();
	//$filedown = array();
	//for($i=0; $i < $info_file_count; $i++) {
	//	$filename[$i] = $rows["filename_".$i];
	//	$filesize[$i] = $rows["filesize_".$i];
	//	$filedown[$i] = $rows["filedown_".$i];
	//}

	$filename_0 = $rows['filename_0'];
	$filesize_0 = $rows['filesize_0'];

	$userid = $rows["userid"];
	$subject = $rows["subject"];
	$subtitle = $rows["subtitle"];
	$name = $rows["name"];
	$regdate = $rows["regdate"];
	$contents = ($rows["contents"]);
	$pass = $rows["pass"];
	if ($_BOARDID == "project_web") 
	{
		$hide = $rows["hide"];
		$builder_id = $rows["builder_id"];
	}else if ($_BOARDID == "tiles" || $_BOARDID == "nobel_stones" ) 
	{
		$category_id = $rows["category_id"];
	}
	//echo $pass;
	mysql_free_result($result);

	$action_url = "bbs_modify_ok.php";
	$submit_action = "return checkUpdateBoardForm(this)";
	$txt = "Modify";

} elseif($act=="reply") {
	$sql="select no, look, thread, subject, contents, userid from ".$_BOARDID." where no=".$no;	
	$result = mysql_query($sql);
	$rows = mysql_fetch_array($result);
	$no = $rows["no"];
	$thread = $rows["thread"];
	$look = $rows["look"];	
	$subject = $rows["subject"];
	$subject = "RE : ". $subject;
	$contents = $rows["contents"];
	$userid = $rows["userid"];
	//$contents = "REPLY : \r\n". $contents;;
	mysql_free_result($result);

	$action_url = "bbs_reply_ok.php";
	$submit_action = "return checkBoardForm(this)";	
	$txt = "Reply";

}
?>
<script type="text/javascript" >

function file_del_ctrl(f) {
		if(eval("document.boardForm.file_del_"+f).checked == true) file_ctrl('p',f);
		else  file_ctrl('m',f);
	}

function extCheck(str) {
	image_ext = ["jpg","png","gif"];
	movie_ext = ["asf","wmv","mpg","mpeg","wma","mp3","swf"];
	for (i in image_ext) if (str.split(".").pop().toLowerCase() == image_ext[i]) return "image_File";
	for (i in movie_ext) if (str.split(".").pop().toLowerCase() == movie_ext[i]) return "movie_File";
	return false;
}

function org_img_ctrl(fnum,val,fileName,event) {
		var fnum = parseInt(fnum);
		if(extCheck(fileName)=="image_File"){
			document.getElementById('org_img_'+fnum).src = fileName;
			if(val=="inline" || val=="none"){
				if(navigator.appName == "Microsoft Internet Explorer"){
					document.getElementById('org_div_img_'+fnum).style.display = val;
					var cutWidth = 200;
					var cutHeight = 150;
					var iMgWidth = document.getElementById('org_img_'+fnum).width;
					var iMgHeight = document.getElementById('org_img_'+fnum).height;
					if(Math.round(iMgWidth*cutHeight/iMgHeight) > cutWidth) {
						Fixwidth=cutWidth;
						Fixheight = Math.round(iMgHeight*cutWidth/iMgWidth);
					}else{
						Fixheight = cutHeight;
						Fixwidth = Math.round(iMgWidth*cutHeight/iMgHeight);
					}
					document.getElementById('org_img_'+fnum).width =Fixwidth;
					document.getElementById('org_div_img_'+fnum).style.left = event.clientX - Fixwidth;
					document.getElementById('org_div_img_'+fnum).style.top = event.clientY + document.body.scrollTop - Fixheight;
				}else{
					alert("Sorry! Preview is Microsoft Internet Explorer Only.");
				}
			}else if(val=="insert"){
				document.boardForm.content.focus();
				document.boardForm.content.value = document.boardForm.content.value + "\r\n[_" + fileName + ", left_] ";
				document.boardForm.html.checked=true;
				document.boardForm.auto_br.checked=true;
				br_check();
			}
		}else if(extCheck(fileName)=="movie_File"){
			if(val=="insert") {
				document.boardForm.content.focus();
				document.boardForm.content.value = document.boardForm.content.value + "\r\n[_" + fileName + "_] ";
				document.boardForm.html.checked=true;
				document.boardForm.auto_br.checked=true;
				br_check();
			}
		}
	}

function img_ctrl(fnum,val,event) {
			var fnum = parseInt(fnum);
			if(extCheck(eval("document.boardForm.file_up_"+fnum).value)=="image_File"){
				//eval("document.boardForm.file_insert_"+fnum).style.color = "";
				if(navigator.appName == "Microsoft Internet Explorer"){
				//	eval("document.boardForm.file_view_"+fnum).style.color = "";
				}else{
				//	eval("document.boardForm.file_view_"+fnum).style.color = "#888888";
				}
				document.getElementById('img_'+fnum).src = eval("document.boardForm.file_up_"+fnum).value;
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
					document.boardForm.content.focus();
					document.boardForm.content.value = document.boardForm.content.value + "\n\r[_" + eval("document.boardForm.file_up_"+fnum).value.split("\\").pop() + ", left_] ";
					document.boardForm.html.checked=true;
					document.boardForm.auto_br.checked=true;
					br_check();
				}
			}else if(extCheck(eval("document.boardForm.file_up_"+fnum).value)=="movie_File"){
				//eval("document.boardForm.file_insert_"+fnum).style.color = "";
				if(val=="insert") {
					document.boardForm.content.focus();
					document.boardForm.content.value = document.boardForm.content.value + "\n\r[_" + eval("document.boardForm.file_up_"+fnum).value.split("\\").pop() + "_] ";
					document.boardForm.html.checked=true;
					document.boardForm.auto_br.checked=true;
					br_check();
				}
			}else{
				//eval("document.boardForm.file_view_"+fnum).style.color = "#888888";
				//eval("document.boardForm.file_insert_"+fnum).style.color = "#888888";
			}
}

function file_ctrl(f, f2 ) {
			if(f=="p"){
				if (f2 != null) 
				{
					if(document.getElementById('f_num_'+f2).style.display=="none") {
						document.getElementById('f_num_'+f2).style.display="inline";
					}
				}
				else
				{
					for(i=0;i<<?=$info_file_count?>;i++){
						if(document.getElementById('f_num_'+i).style.display=="none") {
							document.getElementById('f_num_'+i).style.display="inline";
							break;
						}
					}
				}
			}
			if(f=="m"){
				if (f2 != null) 
				{
					if(document.getElementById('f_num_'+f2).style.display=="inline") {
						document.getElementById('f_num_'+f2).style.display="none";
					}
				}
				else
				{
					for(i=<?=$info_file_count-1?>;i>1;i--){
						if(document.getElementById('f_num_'+i).style.display=="inline") {
							document.getElementById('f_num_'+i).style.display="none";
							eval("document.boardForm.file_up_"+i).outerHTML = "<input class=\"input\" type=\"file\" size=\"80\" name=\"file_up_"+i+"\">";
							break;
						}
					}
				}
			}
}

function file_mctrl(f) {
		var del_count = 0;
		for(i=0;i<<?=count($fn)-1?>;i++) {
			if(eval("document.boardForm.file_del_"+i).checked == true) del_count++;
		}

		if(f=="p"){
			for(i=0;i<<?=$info_file_count-(count($fn)-1)?>+del_count;i++){
				if(document.getElementById('f_num_'+i).style.display=="none") {
					document.getElementById('f_num_'+i).style.display="inline";
					break;
				}
			}
		}
		if(f=="m"){
			for(i=<?=$info_file_count-(count($fn))?>+del_count;i>-1;i--){
				if(document.getElementById('f_num_'+i).style.display=="inline") {
					document.getElementById('f_num_'+i).style.display="none";
					eval ("document.boardForm.file_up_"+i).outerHTML = "<input class=\"input\" type=\"file\" size=\"80\" name=\"file_up_"+i+"\">";
					break;
				}
			}
		}
	}
</script>
<script type="text/javascript" src="ckeditor/ckeditor.js"></script>
<script type="text/javascript" src="js/http_request.js"></script>
<script type="text/javascript" src="js/bbs.js"></script>
<script type="text/javascript" src="js/admin.js"></script>
<script type="text/javascript" src="js/menu.js"></script>
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
						<tr><td width="252"><img src="images/icon_circle03.gif"   style="vertical-align:middle;">&nbsp;<span style="height:21px"><?php echo $bbs_title?></span></td></tr>
						<tr><td height="3"></td></tr>
						<tr><td background="images/bg_check.gif" height="3"></td></tr>
						<tr><td height="2"></td></tr>
					</table>
					</td>
				</tr>
				<tr>
					<td valign="top">
						<form name="boardForm" method="POST" action="<?php echo $action_url;?>" enctype="multipart/form-data" onSubmit="<?php echo $submit_action;?>">
						<input type="hidden" name="no" value="<?=$no?>">
						<input type="hidden" name="look" value="<?=$look?>">
						<input type="hidden" name="thread" value="<?=$thread?>">
						<input type="hidden" name="boardid" value="<?=$_BOARDID?>">
						<input type="hidden" name="userid" value="<?=$userid?>">
						<input type="hidden" name="filename_0" value="<?=$filename_0?>">
						<input type="hidden" name="filesize_0" value="<?=$filesize_0?>">
						<table border="1" cellpadding="0" cellspacing="0" width="100%" bordercolor="#c3c3c6" bordercolordark="white" class="font9">
						<tr>
							<td width="130" height="25" bgcolor="#D5D5D5" class="tr_bold"><font color="#FF6C2C">*</font>Title </td>
							<td><input type="text" name="subject" size="115" value="<?php echo $subject; ?>" class="input" 
							<?php 
								if ($_BOARDID == "contents") 
							{?> ><font color="red">*Fixed</font>
							<?php 
							} ?>
								</td>
						</tr>
						<tr>
							<td width="130" height="25" bgcolor="#D5D5D5" class="tr_bold"><font color="#FF6C2C">*</font> Name</td><td><input type="text" name="name" size="30" value="<?php if($act != "") echo $name; else echo $Sync_name; ?>" class="input"><input type="hidden" name="pass" size="30" class="input" value="<?php if($act != "") echo $pass; else echo $Sync_pass;?>" maxlength="15"></td>
						</tr>
						<?php 
								if ($_BOARDID == "project_web") 
								{
						?>
						<tr>
							<td width="130" height="25" bgcolor="#D5D5D5" class="tr_bold"><font color="#FF6C2C">*</font> Featured</td><td><?php echo ynOption("hide",$hide); ?><font color="#FF6C2C"> i.e. Y = it is shown on the main page.</font></td>
						</tr>
						<tr>
							<td width="130" height="25" bgcolor="#D5D5D5" class="tr_bold"><font color="#FF6C2C">*</font> Builder</td><td><? getOption("builder",$builder_id)?></td>
						</tr>

						<?php }else if ( $_BOARDID == "tiles") { ?>
						<tr>
							<td width="130" height="25" bgcolor="#D5D5D5" class="tr_bold"><font color="#FF6C2C">*</font> Category</td><td><?php getOption("tiles_category",$category_id)?></td>
						</tr>
						<?php } else if ( $_BOARDID == "nobel_stones") { ?>
						<tr>
							<td width="130" height="25" bgcolor="#D5D5D5" class="tr_bold"><font color="#FF6C2C">*</font> Category</td><td><?php getOption("nobel_stones_category",$category_id)?></td>
						</tr>
						<?php } ?>
						<?php if ($isHtmlEditor == "0") { ?>
						<tr>
							<td width="130" height="25" bgcolor="#D5D5D5" class="tr_bold"><font color="#FF6C2C">*</font> Contents <br /><?php if ( $_BOARDID == "project_web") echo "<font color='#FF6C2C'>*100 characters of contents will be shown on main page.</font>";?> </td><td> 
							&nbsp;<textarea class="ckeditorNo"  name="contents" rows="4" cols="87"><?=$contents?></textarea>
							</td>
						</tr>
						<? } else { ?>
						<tr>
							<td width="130" height="25" bgcolor="#D5D5D5" class="tr_bold"><font color="#FF6C2C">*</font> Contents
							</td><td> 
							&nbsp;<textarea class="ckeditor"  name="contents" rows="4" cols="87"><?=$contents?></textarea>
							</td>
						</tr>
						
						<? } ?>
						<?php
							if($isAcceptFileUpload == "1") {
						?>
						<tr>
						<td colspan=2>
							<? if ( $act == '') { ?>
							<?for($f=0;$f<$info_file_count;$f++){?>
								<span id="f_num_<?=$f?>" <?if($f>1){?>style="display:none;"<?}?>>
								&nbsp;<?=substr($f+101,-2)?>:
								<input  type="file" size="80" name="file_up_<?=$f?>" onChange="img_ctrl('<?=$f?>','')">
								<!--within <?=$Records["file_quota"]?>Mbyte-->
								<div id="div_img_<?=$f?>" style="position:absolute;left:250;top:300;z-index:10<?=$f?>;display:none;"><img id="img_<?=$f?>" name="img_<?=$f?>"></div>
								
								<?if($f==0){?>
									<img src="images/space.gif" height="10" width="2">
									<img src="images/arrow_up.gif" onClick="file_ctrl('m');">
									<img src="images/arrow_down.gif" onClick="file_ctrl('p');">
													
								<?}?>
								<br>
								</span>
							<?		} 
								} 
								elseif ($act == 'modify') 
								{
								?>
								<?
									$fn=explode("|",$filename_0);
									$fs=explode("|",$filesize_0);
									
									for($i=0;$i<count($fn)-1;$i++){
										$ext = strtolower(strrchr($fn[$i],"."));
										if($ext==".jpg" || $ext==".gif" || $ext==".png") 
										{
											$onoff_A="";  $onoff_B="";
										}
										elseif($ext==".wmv" || $ext==".wma" || $ext==".mp3" || $ext==".swf") 
										{
											$onoff_A="#888888";  $onoff_B="";
										}
										else 
										{	
											$onoff_A="#888888";  $onoff_B="#888888";
										}
										if(!eregi("MSIE", $_SERVER["HTTP_USER_AGENT"])) $onoff_A="#888888";
									?>
									<div>&nbsp;<?=substr($i+101,-2)?>: &nbsp;<?=$fn[$i]?>
										<div id="org_div_img_<?=$i?>" style="position:absolute;left:250;top:300;z-index:10<?=$i?>;display:none;"><img id="org_img_<?=$i?>" name="org_img_<?=$i?>">	</div>
										<input type="button" value="view" name="org_file_view_<?=$i?>"  onMouseover="org_img_ctrl('<?=$i?>','inline','<?=$upload_dir."/".$_BOARDID."/".$fn[$i]?>',event)" onMouseout="org_img_ctrl('<?=$i?>','none','<?=$upload_dir."/".$_BOARDID."/".$fn[$i]?>',event)" style="color:<?=$onoff_A?>">
									<input type="checkbox" name="file_del_<?=$i?>" value="y" onClick="file_del_ctrl('<?=$i?>')">Delete</div>
									<?php
									}
									
									for($f=0;$f<$info_file_count;$f++){?>
								<span id="f_num_<?=$f?>" <? if($f>0 || $f > 1-count($fn)){?>style="display:none;"<?}?>>
								&nbsp;<?=substr($f+101,-2)?>:
								<input  type="file" size="80" name="file_up_<?=$f?>" onChange="img_ctrl('<?=$f?>','',event)">
								within <?=$info_file_quata?> Mbyte
								<div id="div_img_<?=$f?>" style="position:absolute;left:250;top:300;z-index:10<?=$f?>;display:none;"><img id="img_<?=$f?>" name="img_<?=$f?>"></div>
									<?if($f==0){?>
										<img src="images/space.gif" height="10" width="2">
										<img src="images/arrow_up.gif" onClick="file_mctrl('m');" >
										<img src="images/arrow_down.gif" onClick="file_mctrl('p');" >
									<?}?>
								<br>
								</span>
							<?
								}
							}
							}?>
						</td>
						</table>
						<table border="0" cellpadding="0" cellspacing="0" width="100%" bordercolor="#c3c3c6" bordercolordark="white">
						<tr><td><input type="button" value="List" onclick="location.href='bbs_list.php?boardid=<?=$_BOARDID?>&page=<?=$page?>'"></td><td height="50" align="right"><input type="submit" value="Save">&nbsp;<input type="button" value="Cancel" onclick="history.back()"></td></tr>
						</table>
						</form>
						<div style="position:absolute;top:280;left:500;display:none;width:300;height:50;border:1px solid #c3c3c6;background-color:#E2E2E2;font-weight:bold;font-size:10pt;color:#000000;z-index: 2;" id="loading" align="center" valign="middle">Please Wait, Now Data Uploading....</div>

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

