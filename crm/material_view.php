<?php
// 2012-02-07 fixed inventory count
ob_start();
include_once "include/logincheck.inc";
include_once "include/common.inc";
include_once "include/dbconn.inc";
include_once "include/myfunc.inc";
include_once "htmlinclude/head.php";

$material_id = $_REQUEST["material_id"];

## 쿼리, 담을 배열 선언

$list_Records = array();

$Query  = "SELECT  *  FROM material WHERE material_id='" . $material_id . "'";

$id_cnn = mysql_query($Query) or exit(mysql_error());
while($id_rst = mysql_fetch_assoc($id_cnn)) {
	$list_Records = array_merge($list_Records, array($id_rst));
	//print_r($list_Records);
	//echo "<p>";
}

$temp = explode("|", $list_Records[0]["material_image"]);
$temp2 = explode("|", $list_Records[0]["material_image_extra"]);

$material_image = $temp[0];
$material_image_extra = $temp2[0];
?>
<script language="javascript">
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
	
$(function() {
	$("input:button, button").button();
	$('.ui-widget-content').css({'background-image' :'none','background-color':'none'});
});
</script>
<BODY leftmargin=0 topmargin=0>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
<tr>
	<td valign="top" width="191" height="100%">
	<!-- LEFT -------------------------------------------------------------------------------------------------->
	<? include_once "left.php"; ?>
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
			<td style="padding-left:15px"><? include_once "top.php"; ?></td>
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
				<td style="padding-left:15px" valign="top">
				<!-- CONTENTS -------------------------------------------------------------------------------------------->
				<table border="0" cellpadding="0" cellspacing="0" width="1000">
				<tr>
					<td valign="top">
					<table border="0" cellpadding="0" cellspacing="0" width="1000" class="font11_bold">
						<tr><td height="8"></td></tr>
						<tr><td width="252"><img src="images/icon_circle03.gif">&nbsp;<span style="height:21px">Material Detail</span></td></tr>
						<tr><td height="3"></td></tr>
						<tr><td background="images/bg_check.gif" height="3"></td></tr>
						<tr><td height="2"></td></tr>
					</table>
					</td>
				</tr>
				<form name="frm1" method="post" action="account_regist_ok.php">
				<tr>
					<td valign="top">
						<table border="0" cellpadding="0" cellspacing="0"  width="1000">
						<tr>
							<td style="padding-left:3px" width="200" align="left" class="ui-widget-header" height="30" >Category</td>
							<td  style="padding-left:3px" class="ui-widget-content"><b><?=getName("category",$list_Records[0]["category_id"]); ?></b>&nbsp;</td>	
						</tr>
						<tr>
							<td style="padding-left:3px" width="200" align="left"  height="30" class="ui-widget-header" >Name</td>
							<td  style="padding-left:3px" class="ui-widget-content"><b><?=$list_Records[0]["material_name"]?></b>&nbsp;</td>	
						</tr>
						<tr>
							<td style="padding-left:3px" width="200" align="left"  height="30" class="ui-widget-header" >Code number</td><td  style="padding-left:3px" class="ui-widget-content"><b><?=$list_Records[0]["material_code_number"]?></b>&nbsp;</td>	
						</tr>
						<tr>
							<td style="padding-left:3px" width="200" align="left"  height="30" class="ui-widget-header" >Factory number</td><td  style="padding-left:3px" class="ui-widget-content"><b><?=$list_Records[0]["material_factory_number"]?></b>&nbsp;</td>	
						</tr>
						<tr>
							<td style="padding-left:3px" width="200" align="left"  height="30" class="ui-widget-header" >Colour/Shade</td>
							<td  style="padding-left:3px" class="ui-widget-content"><b><?=$list_Records[0]["material_color"]?></b>&nbsp;</td>	
						</tr>
						<tr>
							<td style="padding-left:3px" width="200" align="left"  height="30" class="ui-widget-header" >Size</td>
							<td  style="padding-left:3px" class="ui-widget-content"><b><?=$list_Records[0]["material_size"]?></b>&nbsp;</td>	
						</tr>
						<tr>
							<td style="padding-left:3px" width="200" align="left"  height="30" class="ui-widget-header" >Unit</td>
							<td  style="padding-left:3px" class="ui-widget-content"><?=getName("unit",$list_Records[0]["unit_id"]); ?>&nbsp;</td>	
						</tr>
						<tr>
							<td style="padding-left:3px" width="200" align="left"  height="30" class="ui-widget-header" >Price</td>
							<td  style="padding-left:3px" class="ui-widget-content"><?=$list_Records[0]["material_price"]?>&nbsp;</td>	
						</tr>
						<tr >
							<td style="padding-left:3px" width="200" align="left"  height="30" class="ui-widget-header" >Inventory</td>
							<td  style="padding-left:3px" class="ui-widget-content">
								<?php 
								$minusInventory = 0;
								$plusInventory = 0;

								$sql = "select IFNULL(sum(orders_inventory),0)  from orders where material_id='".$list_Records[0]["material_id"] ."' and ((new_order = 'N' AND orders_number = '') OR new_order = 'Y') AND project_id <> '0'  " ;
								$minusInventory = getRowCount2($sql);
							
								$sql2 = "select IFNULL(sum(orders_inventory),0) from orders where material_id='".$list_Records[0]["material_id"] . "' and orders_number <> '' ";
								$plusInventory = getRowCount2($sql2);
								
								$totalInventory = $list_Records[0]["material_adjustment"] + $plusInventory - $minusInventory; 
								echo $totalInventory;
								?>&nbsp;</td>	
						</tr>
						<tr >
							<td style="padding-left:3px" width="200" align="left"  height="30" class="ui-widget-header" >Adjustment</td>
							<td  style="padding-left:3px" class="ui-widget-content"><?=$list_Records[0]["material_adjustment"]?>&nbsp;</td>	
						</tr>
						<tr >
							<td style="padding-left:3px" width="200" align="left"  height="30" class="ui-widget-header" >Adjustment Notes</td><td  style="padding-left:3px" class="ui-widget-content"><?=nl2br(stripslashes($list_Records[0]["material_adjustment_note"]))?>&nbsp;</td>	
						</tr>
						<tr>
							<td style="padding-left:3px" width="200" align="left"  height="30" class="ui-widget-header" >Supplier</td>
							<td  style="padding-left:3px" class="ui-widget-content"><?=getName("supplier",$list_Records[0]["supplier_id"]);?>&nbsp;</td>	
						</tr>
						<!--<tr>
							<td style="padding-left:3px" align="left"  height="30" >Image</td><td style="padding-left:3px" class="ui-widget-content">
							<?
								
							//	if ($list_Records[0]["material_image"] ) {
							//		echo " <table cellpadding='0' cellspacing='0'>";
							//		echo " <tr><td><img id='img_no_0' name='img_no_0' src='". $upload_dir. "/thumb02_" . $material_image . //"'></tr></table>";
							//	}
							?>
							&nbsp;</td>	
						</tr>
						-->
						<tr>
							<td style="padding-left:3px" width="200" align="left"  height="30" class="ui-widget-header"  >Description</td>
							<td  style="padding-left:3px" class="ui-widget-content"><?=nl2br(stripslashes($list_Records[0]["material_description"]))?>&nbsp;</td>
						</tr>
						</table>		
						<br>
						<table border="0" cellpadding="0" cellspacing="0" width="1000">
						<tr><td align="left" width="100"><input type="button" value="List" onclick="location.href='material_list.php'"></td>
						<td align="right"><input type="button" value="Modify" onclick="location.href='material_regist.php?action_type=modify&material_id=<?=$list_Records[0]["material_id"]?>'">
						<?if($Sync_alevel <= "B1") { ?>
							&nbsp;<input type="button" value="Delete" onclick="if(confirm('Are you sure?')) {location.href='material_regist.php?action_type=delete&material_id=<?=$list_Records[0]["material_id"]?>'}">
						<? } ?>
						</td></tr>
						</table>
					</td>
				</tr>
				</form>
				<tr><td></td></tr>
				</table>
				<!-- CONTENTS END -------------------------------------------------------------------------------------------->
			</td>
			<td>
			</td>
		  </tr>
		  <tr><td colspan="2" height="113"></td></tr>
		 </table>
		 </td>
	    </tr>
	    <!-- BODY CENTER END------------------------------------------------------------------------------------->
	   </table>
	<!-- BODY END -------------------------------------------------------------------------------------------->
	</td>
</tr>
<tr>
	<td colspan="3">
	<!-- BOTTOM -------------------------------------------------------------------------------------------->
	<? include_once "bottom.php"; ?>
	<!-- BOTTOM END -------------------------------------------------------------------------------------------->
	</td>
</tr>
</table>
</BODY>
</HTML>

<? ob_flush(); ?>