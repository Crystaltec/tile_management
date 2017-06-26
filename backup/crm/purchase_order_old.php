<?php
ob_start();
include_once "include/logincheck.inc";
include_once "include/common.inc";
include_once "include/dbconn.inc";
include_once "include/myfunc.inc";
include_once "htmlinclude/head.php";


$supplier_id = $_REQUEST["supplier_id"];
$project_id = $_REQUEST["project_id"];
$orders_number = $_REQUEST["orders_number"];
$itemcount = 7;
if ($_REQUEST["orders_date"] == '') {
	$orders_date = $now_date;
} else {
	$orders_date = $_REQUEST["orders_date"];
}


if ($_REQUEST["delivery_date"] == '') {
	$delivery_date = $now_date;
} else {
	$delivery_date = $_REQUEST["delivery_date"];
}

$supervisor_info = $_REQUEST["supervisor_info"];
$remarks = $_REQUEST["remarks"];

for($i=0; $i<$itemcount ; $i++) {
	$selunit_id{$i} = $_REQUEST["unit_id$i"];
	$selmaterial_id{$i}=$_REQUEST["material_id$i"];
	$qty{$i} = $_REQUEST["qty$i"];
	$selmaterial_description{$i} = $_REQUEST["material_description$i"];
}

?>
<script type="text/javascript" src="js/materialinfo.js" ></script>
<script language="Javascript">
<!-- 
function getSelsupervisor(){ 
    var f2 = document.orderform;
	var ValueA = document.getElementById("selsupervisor"); 
	f2.document.getElementById("supervisor_info").value = ValueA.value;
} 

function reload() {
	var f2 = document.orderform;
	var supplier_id = document.getElementById("supplier_id");
	var project_id = document.getElementById("project_id");
	f2.action="purchase_order.php?project_id=" + project_id.value + "&supplier_id=" + supplier_id.value;
	f2.submit();
}

function goStep02() {
	var f2 = document.orderform;
	var supplier_id = document.getElementById("supplier_id");
	var qtychk = false;

	if(supplier_id.value == "") {
			alert("Please, Select Supplier!");
			supplier_id.focus();
			return;
	}

	var orders_number = document.getElementById("orders_number");
	if(orders_number.value =="") {
		alert("Please Insert Purchase Order Number!");
		orders_number.focus();
		return;
	}

	for(var i=0; i< <?=$itemcount?>;i++) {
		if(f2.elements["qty"+i].value != "" && f2.elements["material_id"+i].value != "") {
			qtychk = true;
		}
	}

	var tomorrow = "<?=$tomorrow_date?>";
	var today = "<?=$now_dateano?>";
	var now_time = "<?=$now_time?>";
	//alert(deli_date);
	//alert(today);
	
	if ( qtychk) {
		f2.method="post";
		f2.action="purchase_order_step02.php";
		f2.submit();
	}
	else {
		alert("Please, check Material!");
	}

}
//--> 
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
					<td>
					<table border="0" cellpadding="0" cellspacing="0" height="40" valign="bottom">
						<tr>
						<td width="490"><b style="font-size:11pt">Purchase Order Step01 </b></td>
						<td align="right"><b>Step01</b> &gt;&gt; Step02 &gt;&gt; Finish!</td>
						</tr>
					</table>
					</td>
				</tr>
				<form name="orderform" method="post">
				<tr>
					<td>
						<table border="0" cellpadding="0" cellspacing="0" width="1000">
						<tr>
							<td colspan="4">
							
							<table border="0" cellpadding="5" cellspacing="0" width="1000">
							<tr><td colspan="4" bgcolor="646464" height="28" align="center"><span style="color:white;font-weight:bold">Purchase Order Information</span></td></tr>
							<tr><td width="130" class="dinput">*Date</td><td colspan="3" class="dinput2" ><input type="text" name="orders_date"  value="<?=$orders_date?>"></td></tr>
							<tr><td colspan="4" height="3" background="images/bg_check.gif"></td></tr>
							<tr><td width="130" class="dinput">*P.O.No</td><td colspan="3" class="dinput2" ><input type="text" name="orders_number" value="<?=getPON($orders_number)?>"></td></tr>
							<tr><td colspan="4" height="3" background="images/bg_check.gif"></td></tr>
							<tr><td width="130" class="dinput">*To(Supplier)</td><td colspan="3" class="dinput2" ><? getOption("supplier",$supplier_id,NULL,"onchange='reload();'")?></td></tr>
							<tr><td colspan="4" height="3" background="images/bg_check.gif"></td></tr>
							<br>
							<table border="0" cellpadding="5" cellspacing="0" width="1000">
							<tr><td colspan="4" bgcolor="646464" height="28" align="center"><span style="color:white;font-weight:bold">Delivery Information</span></td></tr>
							<tr><td class="dinput">*Deliver To</td><td colspan="3" class="dinput2" ><? getOption("project",$project_id,'1')?>&nbsp;Default - company</td></tr>
							<tr><td colspan="4" height="3" background="images/bg_check.gif"></td></tr>
							<tr><td width="130" class="dinput">Delivery Date</td><td width="400" class="dinput2" colspan="3">
							<input type="text" name="delivery_date" value="<?=$delivery_date?>">
							
							</td></tr>
							<tr><td colspan="4" height="3" background="images/bg_check.gif"></td></tr>
							<tr>
								<td width="130" class="dinput">Supervisor & Ph</td><td class="dinput2">
								<?
								$sql = " select userid, username, phone from account where alevel > 'A1' order by alevel";
								$result = mysql_query($sql) or exit(mysql_error());
								echo "<select id='selsupervisor' name='selsupervisor' style='width=260;' onchange='getSelsupervisor();'>";
								echo "<option value=''>Please select</option>";
		
								while($rows = mysql_fetch_row($result)) {		
									if( $_REQUEST["selsupervisor"] == '$rows[1] . " " . $rows[2]' ) {
										echo "<option value='" . $rows[1] . " " . $rows[2] . "' selected>" . $rows[1] . " " . $rows[2] . "</option>";
									} else {
										echo "<option value='" . $rows[1] . " " . $rows[2] . "'>" . $rows[1] . " ". $rows[2] . "</option>";
									}	
								}
	
								echo "</select>";

								mysql_free_result($result);
								?>
								<input type="text" id="supervisor_info" name="supervisor_info" size="40" value="<?=$supervisor_info?>"></td></td>
							</tr>
							<br>			
							</td></tr>
							<tr><td colspan="4" height="3" background="images/bg_check.gif"></td></tr>
							<tr><td class="dinput">Memo</td><td colspan="3" class="dinput2"><textarea name="remarks" rows="4" cols="65"><?=$remarks?></textarea></td></tr>
							<tr><td colspan="4" height="3" background="images/bg_check.gif"></td></tr>
							
							</table>
							</td>
						</tr>
						</table>
						<br>
						<table border="1" width="1000" cellpadding="3" cellspacing="0" bordercolor="#c3c3c6" bordercolordark="white">
						<tr bgcolor="#CCE2FE">
							<td width="50" align="center"><b>Qty</b></td>
							<td width="400" align="center" height="30" style="font-size:10pt"><b>Code number</b></td>
							<td width="100" align="center"><b>Unit</b></td>
							<td width="350" align="center"><b>Description</b></td>
						</tr>
						<?
							for ( $i=0; $i < $itemcount ; $i++) {
						?>
							<tr>
								<td width="50" align="center"><input type="text" name="qty<?=$i?>" size="4" value="<?=$qty{$i}?>">&nbsp;</td>
								<td height="30" align="center">
								
						<?	
							$str = "<select name='material_id$i' onchange='getInfo(this,$i)'>
								<option value=''>Select Material</option>";	
						$sql = "SELECT * FROM material WHERE supplier_id = '" .$supplier_id . "' ORDER BY material_name ASC";
							$result = mysql_query($sql) or exit(mysql_error());
							while($rows = mysql_fetch_assoc($result)) {
								$material_id = $rows["material_id"];
								$material_name = $rows["material_name"];
								$material_code_number = $rows["material_code_number"];	
								$material_price = $rows["material_price"];
								if ($selmaterial_id{$i} == $material_id) {
									$str.="<option value='".$material_id."' selected>".$material_code_number."(".$material_name. "  $".$material_price.")</option>";
								}
								else {
									$str.="<option value='".$material_id."'>".$material_code_number."(".$material_name. "  $".$material_price.")</option>";
								}

							}
							mysql_free_result($result);
							$str.="</select>";
							echo $str; 
							?>&nbsp;</td>
								<input type="hidden" name="material_code_number<?=$i?>" value="<?=$selmaterial_code_number{$i}?>">
								<td width="100" align="center"><input type="text" name="unit_id<?=$i?>" size="10" value=<?=$selunit_id{$i}?>>&nbsp;</td>
								<td align="center"><textarea name="material_description<?=$i?>" rows="2" cols="35" ><?=$selmaterial_description{$i}?></textarea></td>
								
							</tr>
						<?
							}
						?>
												
						</table>
						<br>
						<table border="0" cellpadding="0" cellspacing="0" width="1000">
						<tr><td align="right">Required fields are marked with an asterisk.(*)<br><input type="button" value="Next" style="width:120" onclick="goStep02();">&nbsp;</td></tr>
						</table>
					</td>
				</tr>
				</form>
				<tr><td height="40"></td></tr>
				</table>
				<!-- CONTENTS END -------------------------------------------------------------------------------------------->
			</td>
			<td>
			</td>
		  </tr>
		  <tr><td colspan="2" height="0"></td></tr>
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