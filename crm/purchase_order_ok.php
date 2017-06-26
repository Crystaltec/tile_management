<?php
ob_start();
include_once "include/logincheck.inc";
include_once "include/common.inc";
include_once "include/dbconn.inc";
include_once "include/myfunc.inc";
include_once "htmlinclude/head.php";

if(!$_REQUEST) exit;
$orders_number = $_REQUEST["orders_number"];
$orders_date = $_REQUEST["orders_date"];
$supplier_id = $_REQUEST["supplier_id"];
$project_id = $_REQUEST["project_id"];
$delivery_date = $_REQUEST["delivery_date"];
$supervisor_info = $_REQUEST["supervisor_info"];
$remarks = $_REQUEST["remarks"];
$order_notice_id_list = set_db_array($_REQUEST['order_notice_id_list'],",");
$action_type = $_REQUEST["action_type"];
$orders_type = $_REQUEST["orders_type"];
$orders_tax = $_REQUEST["orders_tax"];
$orders_number_chk = $_REQUEST["orders_number_chk"];

if ($orders_type == 'B') {
	$material_description0 = $_REQUEST["material_description0"];
	$material_price0 = $_REQUEST["material_price0"];
	$qty0 = $_REQUEST["qty0"];
} else {
	$itemcount = 10;
	for($i=0; $i < $itemcount; $i++) {
		$qty{$i} = $_REQUEST["qty$i"];
		$material_id{$i} = $_REQUEST["material_id$i"];
		$material_code_number{$i} = $_REQUEST["material_code_number$i"];
		$material_description{$i} = $_REQUEST["material_description$i"];
		$material_price{$i} = $_REQUEST["material_price$i"];
		$material_color{$i} = $_REQUEST["material_color$i"];
		$material_size{$i} = $_REQUEST["material_size$i"];
	}
}
$price_hidden = $_REQUEST["price_hidden"];



if($action_type == "complete") {
	
	if ($orders_number_chk <> 'on') {
		$sql_pon = "SELECT count(*) FROM orders WHERE orders_number = '$orders_number'";
		$_c = getRowCount2($sql_pon);
		if ($_c <> "0") {
			$orders_number = getPON();
		}
	}
	// 주문 완료 트랜잭션에 넣기
	if ($orders_type != 'B') {
		
		for($i=0; $i < $itemcount; $i++) {
			$qty = $_REQUEST["qty$i"];
			$material_id = $_REQUEST["material_id$i"];
			$material_description = $_REQUEST["material_description$i"];
			$material_price = $_REQUEST["material_price$i"];
			
			if ($qty != "") {
				$sql = "INSERT INTO orders (orders_number, material_id, material_price, material_description, orders_inventory, user_id,  orders_status, delivery_date, supplier_id, project_id, supervisor_info, remarks, order_notice_id, orders_date,price_hidden ) VALUES ('$orders_number', '$material_id', '$material_price', '$material_description','$qty','$Sync_id','PROCESSING','$delivery_date','$supplier_id', '$project_id','$supervisor_info','$remarks', '$order_notice_id_list','$now_datetimeano','$price_hidden')";
				//echo $sql . "<br>";
				pQuery($sql,"insert");
			}
		}
	//echo "<script language='javascript'>alert('$orders_number');</script>";
	
		echo "<script language='javascript'>
			alert('Success!');
			location.href='order_list.php';
			</script>";
		
	} else {
		$sql = "INSERT INTO orders (orders_number, material_price, material_description, orders_inventory, user_id,  orders_status, delivery_date, supplier_id,project_id, supervisor_info, remarks, order_notice_id, orders_date, orders_type,orders_tax ) VALUES ('$orders_number',  '$material_price0', '$material_description0','$qty0','$Sync_id','PROCESSING','$delivery_date','$supplier_id', '$project_id','$supervisor_info','$remarks', '$order_notice_id_list','$now_datetimeano', '$orders_type','$$orders_tax')";
			//echo $sql . "<br>";
		pQuery($sql,"insert");
		
		echo "<script language='javascript'>
		alert('Success!');
		location.href='order_list.php';
		</script>";
		
	}
}

?>
<script type="text/javascript" src="js/selinfo.js" ></script>
<script language="Javascript">
function goBack() { 
	var f2 = document.orderform;
	f2.method="post";
	f2.action="purchase_order.php";
	f2.submit();
	return false;
	
} 

function orderNow() {
	var f = document.orderform;
	f.action="<?=$_SERVER['PHP_SELF'];?>?action_type=complete";
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
$(function() {
		$("input:button, button").button();
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
				<form name="orderform" method="post">
				<div style="width:1000px;" >
				<span style="display:inline-block;font-weight:bold; width:500px;" class="font11_bold">&nbsp;
				<img src="images/icon_circle03.gif">Purchase Order Step02</span>
				<span class="right" style="display:inline-block; width:490px;">Step01 &gt;&gt; <b>Step02</b> &gt;&gt; Finish!</span>
				</div>
				<table border="0" cellpadding="0" cellspacing="0" width="1000">		
				<tr>
					<td>
						<table border="0" cellpadding="0" cellspacing="0" width="1000">
						<tr>
							<td colspan="4">
							
							<table border="0" cellpadding="0" cellspacing="0" width="1000">
							<thead>
							<tr class="ui-widget-header">
								<th colspan="4" style="height:30px;">Purchase Order Information</th>
							</tr>
							</thead>
							<tr>
								<td width="200" class="dinput left" height="30">*Date</td>
								<td colspan="3" class="dinput2 left" ><b><?=$orders_date?></b></td>
							</tr>
							<input type="hidden" name="orders_date" value="<?=$orders_date?>">
							<tr><td colspan="4" height="3" class="divider"></td></tr>
							<tr>
								<td width="200" class="left dinput" height="30">*P.O.N.</td>
								<td colspan="3" class="dinput2 left" ><b><?=$orders_number?></b><?php if ($orders_number_chk == 'on') echo ' <b>Add on existed P.O.N.</b>';?></td>
							</tr>
							<input type="hidden" name="orders_number_chk" value="<?=$orders_number_chk?>" >
							<input type="hidden" name="orders_number" value="<?=$orders_number?>">
							<tr><td colspan="4" height="3" class="divider"></td></tr>
							<input type="hidden" name="orders_type" value="<?=$orders_type?>" >
							<?php 
								if($orders_type != 'B') {
														
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
								}
							?>
							<input type="hidden" name="supplier_id" value="<?=$supplier_id?>">
							<tr>
								<td width="200" class="dinput left" style="height:30px;">To</td>
								<td colspan="3" class="dinput2 left" ><b><?=getName("supplier",$_REQUEST["supplier_id"])?></b></td></tr>
							<tr><td width="200" class="dinput left" style="height:30px;">Fax</td>
							<td colspan="3" class="dinput2 left" ><b><?=$supplier_fax_number?></b></td>
						</tr>
							<tr>
								<td width="200" class="dinput left" style="height:30px;">Ph</td>
								<td colspan="3" class="dinput2 left" ><b><?=$supplier_phone_number?></b></td>
							</tr>
							<tr><td width="200" class="dinput left" style="height:30px;">Attn</td>
							<td colspan="3" class="dinput2 left" ><b><?=$supplier_sales_manager?></b></td></tr>
							
							<?php
							 if ( $project_id ) {
								$sql = "select * from project where project_id = " . $project_id;
								$result = mysql_query($sql) or exit(mysql_error());
								while($rows = mysql_fetch_assoc($result)) {
									
									$project_name = $rows["project_name"];
									$project_address = $rows["project_address"];
									$project_suburb = $rows["project_suburb"];
									$project_postcode = $rows["project_postcode"];
									$project_state = $rows["project_state"];
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
									$project_state = $rows["company_state"];
									$project_postcode = $rows["company_postcode"];
									$project_phone_number = $rows["company_phone_number"];
								}
								mysql_free_result($result);
							}
							?>
							<tr><td colspan="4" height="3" class="divider"></td></tr>
							</table>
							<br>
							<table border="0" cellpadding="0" cellspacing="0" width="1000">
							<thead>
							<tr class="ui-widget-header">
								<th colspan="4" style="height:30px;">Delivery Information</th>
							</tr>
							</thead>
							<input type="hidden" name="project_id" value="<?=$project_id?>">
							
							<tr>
								<td width="200" class="dinput left" height="30">Project</td>
								<td colspan="3" class="dinput2 left" ><?=$project_name?></td></tr>
							<tr>
								<td width="200" class="dinput left" height="30">Address</td>
								<td colspan="3" class="dinput2 left" >
								<?=$project_address?>&nbsp;<?=$project_suburb?>&nbsp;
								<?=$project_state?>&nbsp;<?=$project_postcode?></td>
							</tr>
							<tr><td width="200" class="dinput left" height="30">Delivery Date</td>
								<td class="dinput2 left" colspan="3"><?=$delivery_date?>&nbsp;</td>
							</tr>
							<input type="hidden" name="delivery_date" value="<?=$delivery_date?>" >
							<tr>
								<td width="200" class="dinput left" height="30">Site Contact1</td>
								<td colspan="3" class="dinput2 left" ><?=$project_phone_number?></td>
							</tr>
							<tr>
								<td width="200" class="dinput left" height="30">Site Contact2</td>
								<td class="dinput2 left"><?=$supervisor_info?></td>
								<input type="hidden" name="supervisor_info" value="<?=$supervisor_info?>">
							</tr>
							<tr>
								<td width="200" class="dinput left" height="30">Ordered By</td>
								<td colspan="3" class="dinput2 left" ><?=$Sync_name?></td>
							</tr>
							<tr>
								<td width="200" class="dinput left" height="30">Memo</td>
								<td colspan="3" class="dinput2 left"><?=nl2br(stripslashes($remarks))?></td>
							</tr>
							<input type="hidden" name="remarks" value="<?=$remarks?>">
							<tr><td colspan="4" height="3" class="divider"></td>
							</tr>
							<tr>
								<td class="dinput left" height="30">Showing price on purchase order</td>
								<?php 
									if($price_hidden !='Y') {
										$price_hidden = 'N';
									}
								?>
								<td colspan="3" class="dinput2 left"><?=$price_hidden?></td>
								<input type="hidden" name="price_hidden" value="<?php echo $price_hidden?>">
							</tr>
							</table>
							</td>
						</tr>
						</table>
						<br>
						<?php if($orders_type !='B') { ?>
						<table border="0" cellpadding="0" cellspacing="1" width="1000">
						<thead>
						<tr class="ui-widget-header">
							<th width="50" align="center"><b>Qty</b></th>
							<th width="50" align="center"><b>Price</b></th>
							<th width="100" align="center"><b>Unit</b></th>
							<th width="100" align="center"><b>Size</b></th>
							<th width="115" align="center"><b>Code number</b></th>
							<th width="100" align="center"><b>Colour/Shade</b></th>
							<th width="115" align="center"><b>Factory number</b></th>
			
							<th width="270" align="center"><b>Description</b></th>
						</tr>
						</thead>
						<tbody>
						<?php
							for($i=0; $i < $itemcount; $i++) {
								
								$sql = "select * from material where material_id = '" . $material_id{$i} . "'";
								//echo $sql;
								$result = mysql_query($sql) or exit(mysql_error());
								
								while($rows = mysql_fetch_assoc($result)) {
									$material_name = $rows["material_name"];
									$material_code_number = $rows["material_code_number"];
									$unit_id = $rows["unit_id"];
									$material_color = $rows["material_color"];
									$material_size = $rows["material_size"];
									$material_factory_number = $rows["material_factory_number"];
									
						?>
							<tr style="font-size:12px;">
								<td width="50" align="center"><?=$qty{$i}?>&nbsp;</td>
								<td width="50" align="center"><?=$material_price{$i}?></td>
								<td align="center"><?=getName("unit",$unit_id)?>&nbsp;</td>
								<td align="center"><?=$material_size;?></td>
								<td height="30" align="center" ><b><?=$material_code_number?></b></td>
								<td align="center"><?=$material_color;?></td>
								<td align="center" ><b><?=$material_factory_number?></b></td>
								<td align="center"><?=$material_description{$i}?></td>
								<input type="hidden" name="material_id<?=$i?>" value="<?=$material_id{$i}?>">
								<input type="hidden" name="qty<?=$i?>" value="<?=$qty{$i}?>">
								<input type="hidden" name="material_description<?=$i?>" value="<?=$material_description{$i}?>">
								<input type="hidden" name="material_factory_number<?=$i?>" value="<?=$material_factory_number?>">
								<input type="hidden" name="material_code_number<?=$i?>" value="<?=$material_code_number?>">
								<input type="hidden" name="unit_id<?=$i?>" value="<?=getName("unit",$unit_id)?>">
								<input type="hidden" name="material_price<?=$i?>" value="<?=$material_price{$i}?>">
								<input type="hidden" name="material_color<?=$i?>" value="<?=$material_color?>">
								<input type="hidden" name="material_size<?=$i?>" value="<?=$material_size?>">
							</tr>
						<?php
								}
							}
							mysql_free_result($result);
						?>
						</tbody>
						</table>
						<?php } else { ?>
						<table border="0" cellpadding="0" cellspacing="1" width="1000">
						<thead>
						<tr class="ui-widget-header">
							<th width="750" align="center"><b>Description</b></th>
							<th width="200" align="center"><b>Amount</b></th>
							<th width="50" align="center"><b>GST</b></th>
						</tr>
						</thead>
						<tbody>
							<tr style="font-size:12px;">
								<td align="center"><?=$material_description0;?></td>
								<td align="center"><?=$material_price0;?></td>
								<Td align="center"><?=$orders_tax;?></Td>
								<input type="hidden" name="material_description0" value="<?=$material_description0?>">
								<input type="hidden" name="material_price0" value="<?=$material_price0?>">		
								<input type="hidden" name="qty0" value="<?=$qty0;?>">
								<input type="hidden" name="orders_tax" value="<?=$orders_tax;?>">
							</tr>
						</tbody>
						</table>
						<?php }?>
						<br/>
						<table border="0" cellpadding="0" cellspacing="1" width="1000">
						<thead>
						<tr class="ui-widget-header">
							<th colspan="4"><b>Delivery Requirements/Instructions</b>&nbsp;<span style="color:red">Max 10</span></th>
						</tr>
						</thead>
						<tbody>
						<?php
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
						<?php
								$count++;
								}
							mysql_free_result($result2);
						?>
						</tbody>
						</table>
						<br>
						<table border="0" cellpadding="0" cellspacing="0" width="1000">
						<tr><td align="right">Required fields are marked with an asterisk (*).<br>&nbsp;<input type="button" value="Back" onclick="goBack();">&nbsp;&nbsp;<input type="button" value="Order Now" onclick="orderNow();"></td></tr>
						</table>
					
					</td>
					
				</tr>
				
				<tr><td height="40"></td></tr>
				</table>
				</form>		
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
	<?php include_once "bottom.php"; ?>
	<!-- BOTTOM END -------------------------------------------------------------------------------------------->
	</td>
</tr>
</table>
</BODY>
</HTML>
<?php ob_flush(); ?>