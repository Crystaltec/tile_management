<?
ob_start();

include_once "include/logincheck.inc";
include_once "include/common.inc";
include_once "include/functions/database.php";
include_once "include/dbconn.inc";
include_once "include/myfunc.inc";
include_once "htmlinclude/head.php";

$_BOARDID = $_REQUEST['boardid'];
include_once "include/bbs_config.php";

?>
<script type="text/javascript" src="js/http_request.js"></script>
<script type="text/javascript" src="js/bbs.js"></script>
<script type="text/javascript" src="js/admin.js"></script>
<script type="text/javascript" src="js/menu.js"></script>
<script language="Javascript">
function searchNow() {
	var f = document.searchform;

	f.action="<?=$_SERVER['PHP_SELF']?>";
	f.submit();
}
</script>
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
						<tr><td width="500"><img src="images/icon_circle03.gif"  style="vertical-align:middle;">&nbsp;<span style="height:21px;text-transform:capitalize;"><?php echo $bbs_title?></span></td></tr>
						<tr><td height="3"></td></tr>
						<tr><td background="images/bg_check.gif" height="3"></td></tr>
						<tr><td height="2"></td></tr>
					</table>
					</td>
				</tr>
				<tr>
					<td valign="top">
					<?php

							$cond = "";
							$srch_param = "";

							$srch_param = "boardid=$_BOARDID";

							$builder_id = $_REQUEST["builder_id"];
							$tiles_category_id = $_REQUEST["tiles_category_id"];
							$nobel_stones_category_id = $_REQUEST["nobel_stones_category_id"];
					
							if($builder_id != "") {
								$cond .= " AND builder_id ='". $builder_id ."'";
								$srch_param .= "&builder_id=$builder_id";
							}

							if($tiles_category_id != "") {
								$cond .= " AND category_id ='". $tiles_category_id ."'";
								$srch_param .= "&tiles_category_id=$tiles_category_id";
							}

							if($nobel_stones_category_id != "") {
								$cond .= " AND category_id ='". $nobel_stones_category_id ."'";
								$srch_param .= "&nobel_stones_category_id=$nobel_stones_category_id";
							}
						
					if ($_BOARDID == "project_web" ) { ?>
					<form name="searchform" >
						<table border="0" cellpadding="0" cellspacing="0" height="40" valign="bottom" width="100%">							
							<input type='hidden' name='boardid' value="<?php echo $_BOARDID;?>" >
							<tr>
								<td width="100" bgcolor="#D6D7D6" height="30"  style="padding-left:5px">	Builder
								</td>
								<td bgcolor="#FFFFFF" width="550"  style="padding-left:5px">
								<? getOption("builder",$builder_id)?>
								&nbsp;		
							</td></tr>
							<tr><td colspan="2" background="images/bg_check02.gif" height="3"></td></tr>
							<tr>
							<td colspan="2" align="right" height="30"><input type="button" Value="Search" onclick="searchNow()"></td></tr>
						</table>
						</form>
						<br>
						<?php }else if ($_BOARDID == "tiles" ) {?>
						<form name="searchform">
						<table border="0" cellpadding="0" cellspacing="0" height="40" valign="bottom" width="100%">							
							<input type='hidden' name='boardid' value="<?php echo $_BOARDID;?>" >
							<tr>
								<td width="100" bgcolor="#D6D7D6" height="30"  style="padding-left:5px">	Category
								</td>
								<td bgcolor="#FFFFFF" width="550"  style="padding-left:5px">
								<? getOption("tiles_category",$tiles_category_id)?>
								&nbsp;		
							</td></tr>
							<tr><td colspan="2" background="images/bg_check02.gif" height="3"></td></tr>
							<tr>
							<td colspan="2" align="right" height="30"><input type="button" Value="Search" onclick="searchNow()"></td></tr>
						</table>
						</form>
						<br>
						<?php } else if ( $_BOARDID == "nobel_stones" ) {?>
						<form name="searchform">
						<table border="0" cellpadding="0" cellspacing="0" height="40" valign="bottom" width="100%">							
							<input type='hidden' name='boardid' value="<?php echo $_BOARDID;?>" >
							<tr>
								<td width="100" bgcolor="#D6D7D6" height="30"  style="padding-left:5px">	Category
								</td>
								<td bgcolor="#FFFFFF" width="550"  style="padding-left:5px">
								<? getOption("nobel_stones_category",$nobel_stones_category_id)?>
								&nbsp;		
							</td></tr>
							<tr><td colspan="2" background="images/bg_check02.gif" height="3"></td></tr>
							<tr>
							<td colspan="2" align="right" height="30"><input type="button" Value="Search" onclick="searchNow()"></td></tr>
						</table>
						</form>
						<br>
						<?php } ?>
						<table border="1" width="100%" cellpadding="2" cellspacing="0" bordercolor="#c3c3c6" bordercolordark="white">
						<tr align="center" bgcolor="#D5D5D5" class="tr_bold" height="25">
							<th width="50">No.</th>
							<?php if ($_BOARDID == "project_web" ) { ?>
							<th width="150" >Builder</th>
							<?php }else if ($_BOARDID == "tiles" || $_BOARDID == "nobel_stones"){
							?>
							<th width="150" >Category</th>
							<?php }?>
							<th >Title</th>
							<?php if ($_BOARDID == "project_web" ) { ?>
							<th width="60">Featured</th>
							<?php } ?>
						</tr>	
						<?php
							
							// page -------------------------------------------------------------------------------------------
							$page = $_REQUEST["page"];
							if(!$page)
								$page = 1;

							$limitPage = 10;
							$limitList = 7;
							$Query = mysql_query("select count(*) from ".$_BOARDID." where 1=1 ". $cond);
					
							$row = mysql_fetch_array($Query);
							$total = $row[0];
							$totalPage = ceil($total/$limitList);
							$block = ceil($page/$limitPage);
							$start = ($page-1)*$limitList;

							$startPage = ($block-1)*$limitPage + 1;
							$endPage = $startPage + $limitPage - 1;
							if ($endPage > $totalPage ) $endPage = $totalPage; 
							// page calc end ------------------------------------------------------------------------------------
							
							$sql ="SELECT * ";
							$sql.="FROM ".$_BOARDID . " ";
							$sql.="WHERE 1=1 ".$cond;
							if ( $_BOARDID == 'project_web')
							{
								$sql.="ORDER BY hide DESC, look desc, thread ASC LIMIT ".$start.", ".$limitList;
							}
							else 
							{
								$sql.="ORDER BY  look DESC, thread ASC LIMIT ".$start.", ".$limitList;
							}
							//echo $sql;
							$Query = mysql_query($sql);
							$bbs_list = array();
							while($id_rst = mysql_fetch_array($Query)) {
								$bbs_list = array_merge($bbs_list, array($id_rst));								
							}
							mysql_free_result($Query);
							$bbs_cnt = count($bbs_list);

							//print_r($bbs_list);

							for($i = 0; $i < $bbs_cnt; $i++) {		
								$fn = explode("|", $bbs_list[$i]["filename_0"]);
								$fs = explode("|",$bbs_list[$i]["filesize_0"]);
						?>
						<tr <?php if($isAcceptThumb == "1")  echo "height='60'"; else echo "height='22'";?>>
							<td align="center"><?php echo ($total-(($page-1) * $limitList) - $i);?></td>
							<?php if ($_BOARDID == "project_web")
								{?>
							<td >&nbsp;<?php echo getName("builder",$bbs_list[$i]["builder_id"]);?></td>
							<?php }else if ($_BOARDID == "tiles" ) {?>
							<td >&nbsp;<?php echo getName("tiles_category",$bbs_list[$i]["category_id"]);?></td>
							<?php }else if ($_BOARDID == "nobel_stones" ) {?>
							<td >&nbsp;<?php echo getName("nobel_stones_category",$bbs_list[$i]["category_id"]);?></td>
							<?php }?>
							<td style="padding:0 0 0 2" valign="top">
							<?php 
								if($isAcceptThumb == "1") {
									if($fn[0] != "") {
										echo "<img src='".$upload_dir."/".$_BOARDID."/thumb_".$fn[0]."' align='left' alt='thumbnail'>";
									}
								}
								$len = strlen($bbs_list[$i]["thread"]);
								if($len >= 2) {
									for($j=0; $j < $len; $j++)
										echo "&nbsp;";	
									echo "<img src='images/reply.gif'>";
								}
							?>
							&nbsp;<a href="bbs_detail.php?boardid=<?php echo $_BOARDID;?>&no=<?php echo $bbs_list[$i]["no"];?>"><?php echo htmlspecialchars_decode($bbs_list[$i]["subject"]);?></a>
							<?php 
									if($bbs_list[$i]["comments_cnt"] > 0) echo "&nbsp;<span style='color:#0075A6;font-size:8pt'>(".$bbs_list[$i]["comments_cnt"].")</span>";
							?>
							</td>
							
							<?php if ($_BOARDID == "project_web" ) 
								{ 
								$featured = "";
								if ($bbs_list[$i]["hide"] == "Y") {
									$featured = " background-color:yellow; ";
								}
								?>
							
							<td style="text-align: center; <?php echo $featured;?>"><?php echo $bbs_list[$i]["hide"];?>&nbsp;</td>
							<?php } ?>
						</tr>	
						
						<?php
							}
						?>
						</table>
						
						<?php if ($_BOARDID == "contents" && $bbs_cnt >= 2 ) { ?>
						<?} else {?>
						<!-- page list -->
						<table width="100%" height="30" border="0" cellpadding="0" cellspacing="0">
						<tr><td align="center" valign="top" style="font-size:8pt"><?php include "paging.php"; ?></td></tr>
						</table>
						<!-- page list end -->
						<table border="0" cellpadding="0" cellspacing="0" width="100%">
						<tr>
							<td align="right"><input type="button" value="New Post" onclick="location.href='bbs_write.php?boardid=<?=$_BOARDID?>'"></td>
						</tr>
						</table>
						<? } ?>
						

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
<? ob_flush();?>