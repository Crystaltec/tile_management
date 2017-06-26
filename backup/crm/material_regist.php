<?php
ob_start();
include_once "include/logincheck.inc";
include_once "include/common.inc";
include_once "include/dbconn.inc";
include_once "include/myfunc.inc";
include_once "htmlinclude/head.php";
?>
<script language="Javascript">
function formchk() {
	frm = document.frm1;
	if(frm.material_name.value =="") {
		alert("Please, Insert Material Name!");
		frm.material_name.focus();
		return;
	} 

	if(frm.material_code_number.value =="") {
		alert("Please, Insert Material Code Number!");
		frm.material_code_number.focus();
		return;
	} 
	
	if(frm.material_price.value =="") {
		alert("Please, Insert Material price!");
		frm.material_price.focus();
		return;
	}

	if(confirm("Press OK to confirm?")) {
		frm.submit();

	}	
	
}
$(function() {
	$("input:button, button").button();
	$('.ui-widget-content').css({'background-image' :'none','background-color':'none'});
});
</script>
<?php

$material_id = $_REQUEST["material_id"];
$action_type = $_REQUEST["action_type"];

if($action_type=="modify") {

	## 쿼리, 담을 배열 선언
	$list_Records = array();
	
	$Query  = "SELECT * ";
	$Query .= " FROM material WHERE material_id='". $material_id ."'";

	//echo $Query;

	$id_cnn = mysql_query($Query) or exit(mysql_error());
	while($id_rst = mysql_fetch_assoc($id_cnn)) {
		$list_Records = array_merge($list_Records, array($id_rst));
		//print_r($list_Records);
		//echo "<p>";
	}

	$temp = explode("|", $list_Records[0]["material_image"]);
}
else if($action_type == "delete") {
	$material_id = $_REQUEST["material_id"];
	$sql = "SELECT COUNT(*) FROM orders WHERE material_id='" .$material_id."'";
	
	$row = getRowCount($sql);
	if($row[0] > 0) {
		echo "<script>alert('You can not delete this material because it is currently with the order.');history.back();</script>";
	} else {
		
		$sql = "SELECT IFNULL(material_image,'No') FROM material WHERE material_id='$material_id'";
		$result = mysql_query($sql) or exit(mysql_error());
		$row = mysql_fetch_row($result);
		mysql_free_result($result);

		/*
		if($row[0] != "No") {
			$imgfile = explode("|", $row[0]);
			//파일 삭제!
			unlink($upload_root."/".$imgfile[0]);
			unlink($upload_root."/thumb_".$imgfile[0]);
			unlink($upload_root."/thumb02_".$imgfile[0]);
			//exit;

			
		}
		*/
		$sql = "DELETE FROM material WHERE material_id='$material_id'";
		pQuery($sql, "delete");

		echo "<script>location.href='material_list.php';</script>";
		exit;
	}
}

//echo $list_Records[0]["material_name"];
?>
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
						<tr><td width="252"><img src="images/icon_circle03.gif">&nbsp;<span style="height:21px">Material Register</span></td></tr>
						<tr><td height="3"></td></tr>
						<tr><td background="images/bg_check.gif" height="3"></td></tr>
						<tr><td height="2"></td></tr>
					</table>
					</td>
				</tr>
				<tr>
				
				  <td valign="top">
						<form name="frm1" method="post" action="material_regist_ok.php" enctype="multipart/form-data">
						<table border="0" cellpadding="0" cellspacing="0" bordercolor="white" width="1000">
						<tr >
							<td style="padding-left:3px" align="left"  width="200" class="ui-widget-header">Category</td>
							<td style="padding-left:3px"  class="ui-widget-content"><? getOption("category", $list_Records[0]["category_id"]); ?> </td>	
						</tr>
						<tr >
							<td style="padding-left:3px" align="left"  width="200" class="ui-widget-header">*Name</td>
							<td style="padding-left:3px"  class="ui-widget-content"><input type="text" size="60" name="material_name" value="<?=$list_Records[0]["material_name"]?>"> </td>	
						</tr>
						<input type="hidden" name="action_type" value="<?=$action_type?>">
						<input type="hidden" name="material_id" value="<?=$material_id?>">
						<input type="hidden" name="material_old_name" value="<?=$list_Records[0]["material_name"]?>">
						<tr >
							<td style="padding-left:3px" align="left"  width="200" class="ui-widget-header">*Code number</td>
							<td style="padding-left:3px"  class="ui-widget-content"><input type="text" size="60" name="material_code_number" value="<?=$list_Records[0]["material_code_number"]?>"> </td>	
						</tr>
						<tr >
							<td style="padding-left:3px" align="left"  width="200" class="ui-widget-header">Factory number</td>
							<td style="padding-left:3px"  class="ui-widget-content"><input type="text" size="60" name="material_factory_number" value="<?=$list_Records[0]["material_factory_number"]?>"> </td>	
						</tr>
						<tr >
							<td style="padding-left:3px" align="left"  width="200" class="ui-widget-header">Colour/Shade</td>
							<td style="padding-left:3px"  class="ui-widget-content"><input type="text" size="60" name="material_color" value="<?=$list_Records[0]["material_color"]?>"> </td>	
						</tr>
						<tr >
							<td style="padding-left:3px" align="left"  width="200" class="ui-widget-header">Size</td>
							<td style="padding-left:3px"  class="ui-widget-content"><input type="text" size="60" name="material_size" value="<?=$list_Records[0]["material_size"]?>"> </td>	
						</tr>
						<tr >
							<td style="padding-left:3px" align="left"  width="200" class="ui-widget-header">Unit</td>
							<td style="padding-left:3px"  class="ui-widget-content"><? getOption("unit", $list_Records[0]["unit_id"]); ?> </td>	
						</tr>
						<tr >
							<td style="padding-left:3px" align="left"  width="200" class="ui-widget-header">*Price</td>
							<td style="padding-left:3px"  class="ui-widget-content"><input type="text" size="60" name="material_price" value="<?=$list_Records[0]["material_price"]?>">&nbsp;0.00 </td>	
						</tr>
						<tr >
							<td style="padding-left:3px" align="left"  width="200" class="ui-widget-header" height="30">Inventory</td>
							<td style="padding-left:6px"  class="ui-widget-content">
							<?php
								if ($action_type <> "") {
									
								$minusInventory = 0;
								$plusInventory = 0;

								$sql = "select IFNULL(sum(orders_inventory),0)  from orders where material_id='".$list_Records[0]["material_id"] ."' and ((new_order = 'N' AND orders_number = '') OR new_order = 'Y') AND project_id <> '0'  " ;
								$minusInventory = getRowCount2($sql);
							
								$sql2 = "select IFNULL(sum(orders_inventory),0) from orders where material_id='".$list_Records[0]["material_id"] . "' and orders_number <> '' ";
								$plusInventory = getRowCount2($sql2);
								
								$totalInventory = $list_Records[0]["material_adjustment"] + $plusInventory - $minusInventory; 
								echo $totalInventory;
								}
								?>&nbsp;</td>	
						</tr>
						<tr >
							<td style="padding-left:3px" align="left"  width="200" class="ui-widget-header" height="30">Adjustment</td>
							<td style="padding-left:3px"  class="ui-widget-content"><input type="text" size="60" name="material_adjustment" value="<?=$list_Records[0]["material_adjustment"]?>">&nbsp;</td>	
						</tr>
						<tr >
							<td style="padding-left:3px"  align="left"  width="200" class="ui-widget-header">Adjustment Notes</td>
							<td style="padding-left:3px"  class="ui-widget-content"><textarea name="material_adjustment_note" rows="3" cols="94"><?=$list_Records[0]["material_adjustment_note"]?></textarea></td>	
						</tr>
						<tr >
							<td style="padding-left:3px" align="left"  width="200" class="ui-widget-header" height="30">Supplier</td>
							<td style="padding-left:3px"  class="ui-widget-content"><? getOption("supplier", $list_Records[0]["supplier_id"]); ?> </td>	
						</tr>
						<!--<tr  height="30">
							<td style="padding-left:3px" align="left"  bgcolor="#D5D5D5">Image</td><td style="padding-left:3px"><input type="file" name="file_up_0" size="67">
							<input type="hidden" name="info_file_count" value="1">
							</td>	
						</tr>	-->	
						<tr >
							<td style="padding-left:3px"  align="left"  width="200" class="ui-widget-header">Description</td>
							<td style="padding-left:3px"  class="ui-widget-content"><textarea name="material_description" rows="3" cols="94"><?=$list_Records[0]["material_description"]?></textarea></td>	
						</tr>
						</table>		
						<br>
						<table border="0" cellpadding="0" cellspacing="0" width="1000">
						<tr><td align="right">Required fields are marked with an asterisk (*).<br><input type="button" value="Save" onclick="formchk();"></td></tr>
						</table>
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
		  <tr><td colspan="2" height="20"></td></tr>
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