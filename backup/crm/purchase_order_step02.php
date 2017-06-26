<?php
ob_start();
include_once "include/logincheck.inc";
include_once "include/common.inc";
include_once "include/dbconn.inc";
include_once "include/myfunc.inc";
include_once "htmlinclude/head.php";

$orders_date = $_REQUEST["orders_date"];
$orders_number = $_REQUEST["orders_number"];
$supplier_id = $_REQUEST["supplier_id"];
$project_id = $_REQUEST["project_id"];
$delivery_date = $_REQUEST["delivery_date"];
$supervisor_info = $_REQUEST["supervisor_info"];
$remarks = $_REQUEST["remarks"];
$order_notice_id_list = set_db_array($_REQUEST['order_notice_id_list'],",");
$action_type = $_REQUEST["action_type"];
$itemcount = 7;

for($i=0; $i < $itemcount; $i++) {
	$qty{$i} = $_REQUEST["qty$i"];
	$material_id{$i} = $_REQUEST["material_id$i"];
	$material_description{$i} = $_REQUEST["material_description$i"];
	$material_price{$i} = $_REQUEST["material_price$i"];
}

if($action_type == "complete") {
	// 주문 완료 트랜잭션에 넣기
	
	for($i=0; $i < $itemcount; $i++) {
		$qty = $_REQUEST["qty$i"];
		$material_id = $_REQUEST["material_id$i"];
		$material_description = $_REQUEST["material_description$i"];
		$material_price = $_REQUEST["material_price$i"];
		
		if ($qty != "") {
			$sql = "INSERT INTO orders (orders_number, material_id, material_price, material_description, orders_inventory, user_id,  orders_status, delivery_date, supplier_id, project_id, supervisor_info, remarks, order_notice_id, orders_date ) VALUES ('$orders_number', '$material_id', '$material_price', '$material_description','$qty','$Sync_id','PROCESSING','$delivery_date','$supplier_id', '$project_id','$supervisor_info','$remarks', '$order_notice_id_list','$now_datetimeano')";
			//echo $sql . "<br>";
			pQuery($sql,"insert");
		}
	}
	
	echo "<script language='javascript'>
		alert('Success!');
		location.href='order_list.php';
		</script>";
	
}

?>
<script type="text/javascript" src="js/selinfo.js" ></script>
<script language="Javascript">
function orderNow() {
	if(confirm("Do you confirm this order?")) {
		var f = document.frm01;
		f.action="order_process.php";
		f.method="post";
		f.submit();
	}
}

function goBack() { 
	var f2 = document.orderform;
	f2.method="post";
	f2.action="purchase_order.php";
	f2.submit();
} 

function orderNow() {
	var f = document.orderform;
	f.action="<?=$_SERVER['PHP_SELF']?>?action_type=complete";
	f.submit();
}

function chkcontrol(obj) {
	var total=0;
	items = document.getElementsByName('order_notice_id_list[]');

	for(var i=0; i < items.length; i++){
		if(items[i].checked){
			total =total +1;
		}
		if(total > 10){
			alert("Please Select maximum 10 requirements/instructions") 
			items[obj].checked = false ;
			return false;
		}
	}
}

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
						<tr><td width="490"><b style="font-size:11pt">Purchase Order Step02 </b>		
						</td>
						<td align="right" width="200">Step01 &gt;&gt; <b>Step02</b> &gt;&gt; Finish!</td>
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
							<tr><td width="130" class="dinput">*Date</td><td colspan="3" class="dinput2" ><b><?=$orders_date?></b></td></tr>
							<input type="hidden" name="orders_date" value="<?=$orders_date?>">
							<tr><td colspan="4" height="3" background="images/bg_check.gif"></td></tr>
							<tr><td width="130" class="dinput">*P.O.No</td><td colspan="3" class="dinput2" ><b><?=$orders_number?></b></td></tr>
							<input type="hidden" name="orders_number" value="<?=$orders_number?>">
							<tr><td colspan="4" height="3" background="images/bg_check.gif"></td></tr>
							<?
								$sql = "select * from supplier where supplier_id=" . $supplier_id;
								$result = mysql_query($sql) or exit(mysql_error());
								while($rows = mysql_fetch_assoc($result)) {
									$supplier_id = $rows["supplier_id"];
									$supplier_name = $rows["supplier_name"];
									$supplier_fax_number = $rows["supplier_fax_number"];
									$supplier_phone_number = $rows["supplier_phone_number"];
									$supplier_sales_manager = $rows["supplier_sales_manager"];
								}
								mysql_free_result($result);

							?>
							<input type="hidden" name="supplier_id" value="<?=$supplier_id?>">
							<tr><td width="130" class="dinput">Company</td><td colspan="3" class="dinput2" ><b><?=getName("supplier",$_REQUEST["supplier_id"])?></b></td></tr>
							<tr><td width="130" class="dinput">Fax</td><td colspan="3" class="dinput2" ><b><?=$supplier_fax_number?></b></td></tr>
							<tr><td width="130" class="dinput">Ph</td><td colspan="3" class="dinput2" ><b><?=$supplier_phone_number?></b></td></tr>
							<tr><td width="130" class="dinput">Attn</td><td colspan="3" class="dinput2" ><b><?=$supplier_sales_manager?></b></td></tr>
							
							<?
							 if ( $project_id ) {
								$sql = "select * from project where project_id = " . $project_id;
								$result = mysql_query($sql) or exit(mysql_error());
								while($rows = mysql_fetch_assoc($result)) {
									
									$project_name = $rows["project_name"];
									$project_address = $rows["project_address"];
									$project_suburb = $rows["project_suburb"];
									$project_postcode = $rows["project_postcode"];
									$project_phone_number = $rows["project_phone_number"];
								}
								mysql_free_result($result);
							}
							else {
								$sql = "select * from company ";
								$result = mysql_query($sql) or exit(mysql_error());
								while($rows = mysql_fetch_assoc($result)) {
									$project_name = $rows["company_name"];
									$project_address = $rows["company_address"];
									$project_suburb = $rows["company_suburb"];
									$project_postcode = $rows["company_postcode"];
									$project_phone_number = $rows["company_phone_number"];
								}
								mysql_free_result($result);
							}
							?>
							<tr><td colspan="4" height="3" background="images/bg_check.gif"></td></tr>
							</table>
							<br>
							<table border="0" cellpadding="5" cellspacing="0" width="1000">
							<tr><td colspan="4" bgcolor="646464" height="28" align="center"><span style="color:white;font-weight:bold">Delivery Information</span></td></tr>
							<input type="hidden" name="project_id" value="<?=$project_id?>">
							
							<tr><td width="130" class="dinput">Name</td><td colspan="3" class="dinput2" ><?=$project_name?></td></tr>
							<tr><td width="130" class="dinput">Address</td><td colspan="3" class="dinput2" ><?=$project_address?><br><?=$project_suburb?><br><?=$project_state?><br><?=$project_postcode?></td></tr>
							<tr><td width="130" class="dinput">Delivery Date</td><td class="dinput2" colspan="3"><?=$delivery_date?>&nbsp;
							</td></tr>
							<input type="hidden" name="delivery_date" value="<?=$delivery_date?>" >
							<tr><td width="130" class="dinput">Site Contact</td><td colspan="3" class="dinput2" ><?=$project_phone_number?></td></tr>
							<tr>
								<td width="130" class="dinput">Supervisor & Ph</td><td class="dinput2"><?=$supervisor_info?></td></td>
								<input type="hidden" name="supervisor_info" value="<?=$supervisor_info?>">
							</tr>
							<tr><td width="130" class="dinput">Ordered By</td><td colspan="3" class="dinput2" ><?=$Sync_name?></td></tr>
							<tr><td width="130" class="dinput">Remarks</td><td colspan="3" class="dinput2"><?=nl2br(stripslashes($remarks))?></td></tr>
							<input type="hidden" name="remarks" value="<?=$remarks?>">
							<tr><td colspan="4" height="3" background="images/bg_check.gif"></td></tr>
							
							</table>
							</td>
						</tr>
						</table>
						<br>
						<table border="1" width="1000" cellpadding="3" cellspacing="0" bordercolor="#c3c3c6" bordercolordark="white">
						<tr bgcolor="#CCE2FE">
							<td width="50" align="center"><b>Qty</b></td>
							<td width="250" align="center" height="30" style="font-size:10pt"><b>Code number</b></td>
							<td width="100" align="center"><b>Unit</b></td>
							<td width="500" align="center"><b>Description</b></td>
						</tr>
						<?
							for($i=0; $i < $itemcount; $i++) {
								
								$sql = "select * from material where material_id = '" . $material_id{$i} . "'";
								//echo $sql;
								$result = mysql_query($sql) or exit(mysql_error());
								
								while($rows = mysql_fetch_assoc($result)) {
									
									$material_name = $rows["material_name"];
									$material_code_number = $rows["material_code_number"];
									$unit_id = $rows["unit_id"];
									$material_price = $rows["material_price"];
						?>
							<tr>
								<td width="50" align="center"><?=$qty{$i}?>&nbsp;</td>
								<td height="30" style="font-size:10pt" align="center" ><b><?=$material_code_number?></b>&nbsp;</td>
								<td align="center"><?=getName("unit",$unit_id)?>&nbsp;</td>
								<td align="center"><?=$material_description{$i}?></textarea></td>
								<input type="hidden" name="material_id<?=$i?>" value="<?=$material_id{$i}?>">
								<input type="hidden" name="qty<?=$i?>" value="<?=$qty{$i}?>">
								<input type="hidden" name="material_description<?=$i?>" value="<?=$material_description{$i}?>">
								<input type="hidden" name="unit_id<?=$i?>" value="<?=getName("unit",$unit_id)?>">
								<input type="hidden" name="material_price<?=$i?>" value="<?=$material_price?>">
							</tr>
						<?
								}
							}
							mysql_free_result($result);
						?>
						</table>
						<br/>
						<table border="1" width="1000" cellpadding="3" cellspacing="0" bordercolor="#c3c3c6" bordercolordark="white">
						<tr bgcolor="#CCE2FE">
							<td colspan="4"><b>Delivery Requirements/Instructions</b>&nbsp;<span style="color:red">Max 10</span></td>
						</tr>
						<?
							$sql2 = "select * from order_notice order by sortno ASC, order_notice_name ASC ";
							
							$result2 = mysql_query($sql2) or exit(mysql_error());
							$count = 0;	
							while($rows2 = mysql_fetch_assoc($result2)) {
								$order_notice_id = $rows2["order_notice_id"];
								$order_notice_name = $rows2["order_notice_name"];
								$sortno = $rows2["sortno"];
								
						?>
							<tr>
								<td width="30" align="center"><input type="checkbox" name="order_notice_id_list[]" value="<?=$order_notice_id?>" onclick="chkcontrol(<?=$count?>);"></td>
								<td align="left" colspan="3"><?=$order_notice_name?></td>
							</tr>
						<?
								$count++;
								}
							mysql_free_result($result2);
						?>
						</table>
						<br>
						<table border="0" cellpadding="0" cellspacing="0" width="1000">
						<tr><td align="right">Required fields are marked with an asterisk (*).<br>&nbsp;<input type="button" value="Back" onclick="goBack();">&nbsp;&nbsp;<input type="button" value="Order Now" onclick="orderNow();"></td></tr>
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