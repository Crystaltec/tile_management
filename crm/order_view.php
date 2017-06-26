<?php
ob_start();
include_once "include/logincheck.inc";
include_once "include/common.inc";
include_once "include/dbconn.inc";
include_once "include/myfunc.inc";
include_once "htmlinclude/head.php";


$act_flag = $_REQUEST["act_flag"];
$orders_tax = $_REQUEST["orders_tax"];
if($act_flag =="update") {
	$itemcnt = $_REQUEST["itemcnt"];

	for($i=0; $i < $itemcnt; $i++) {
		$orders_id = $_REQUEST["orders_id$i"];
		$price_hidden = $_REQUEST["price_hidden"];
		$orders_inventory = $_REQUEST["orders_inventory$i"];
		$material_price = $_REQUEST["material_price$i"];
		$material_description = $_REQUEST["material_description$i"];
		$remarks = $_REQUEST["remarks"];
		if ($orders_id != "") {
			
			if($act_flag == "update") {
				$sql = "UPDATE orders SET orders_inventory='" .$orders_inventory . "', material_price='" . $material_price . "', material_description='".$material_description."', remarks='".$remarks."', price_hidden='" .$price_hidden . "', orders_tax='$orders_tax' ";
				$sql .= " WHERE orders_id='".$orders_id."'";
				pQuery($sql, "update");
			} 		
		}
	}
} else if ( $act_flag == "delete")
{
	$orders_id = $_REQUEST["orders_id"];
	$sql = "DELETE FROM orders WHERE orders_id='".$orders_id."'";
	pQuery($sql, "delete");
}

$orders_number = $_REQUEST["orders_number"];				

$sql= "SELECT o.*, ac.username ";
$sql.="FROM orders o ";
$sql.="INNER JOIN account ac ";
$sql.="	ON o.user_id=ac.userid ";							
$sql.="WHERE orders_number='".$orders_number."'";
					
$list_Records = array();
$id_cnn = mysql_query($sql) or exit(mysql_error());
while($id_rst = mysql_fetch_assoc($id_cnn)) {
	$list_Records = array_merge($list_Records, array($id_rst));
}

if (count($list_Records) == 0) {
	echo "<script language='javascript'>
		location.href='order_list.php';
		</script>";
}		
?>
<script language="javascript">

var myWindow;

function openWin() {
    myWindow = window.open("order_view_edit.php?project_id=<?php echo $list_Records[0]["project_id"]?>&&orders_id=<?php echo $list_Records[0]["orders_id"]?>", "", "width=550, height=350");
}




function save() {
	var f = document.orderform;
	var chk = false;

	var itemcnt = parseInt(f.itemcnt.value);

	for(var i=0; i<itemcnt;i++) {
		if(f.elements["orders_id"+i].checked == true) {
			chk = true;
		}
	}
	
	if(chk) {
		f.act_flag.value="update";
		f.action="<?=$_SERVER['PHP_SELF']?>";
		f.submit();
	} else {
		alert("Please, Choose material to change!");
	}			
}

$(function() {
	$("input:button, button").button();
	$('.ui-widget-content').css({'background-image' :'none','background-color':'none'});
	
	var _i = 1;
	$('.add_clear').click(function() {
		//alert($(this).parent().parent().attr('id'));
		var parentId = $(this).parent().parent().attr('id');
		var o_id = $(this).parent().parent().attr('id').split("_")[0];
		var m_id = $(this).parent().parent().attr('id').split("_")[1];
		$("<tr><td colspan='9'> Qty:<input type='text' size='5' name='orders_clear_qty_"+_i+"'> Clear date:<input type='text' name='orders_clear_date_"+_i+"' class='clear_date' size='7'><input type='button' name='orders_clear_insert_"+_i+"' class='clear_insert' value='insert'></td></tr>").insertAfter($('#'+parentId));
		$("<script>"+"$(function() { $('.clear_date').datepicker($.datepicker.regional['en-GB']); $('.clear_date').datepicker( 'option', 'firstDay', 1 ); $('.clear_date').datepicker(); }); $('input[name=orders_clear_insert_"+_i+"]').click(function() { if($('input[name=\"orders_clear_qty_"+_i+"\"]').val() && $('input[name=\"orders_clear_qty_"+_i+"\"]').val() ) {$('#processing').fadeIn(500); $.post('add_orders_clear.php',{oid:'"+o_id+"',mid:'"+m_id+"',qty:$('input[name=\"orders_clear_qty_"+_i+"\"]').val(),date:$('input[name=\"orders_clear_date_"+_i+"\"]').val()}, function(data){$('#processing').fadeOut(800);if(data=='SUCCESS'){window.location.reload(true);}else{window.location.reload(true);}}); return false; }});"+"<\/script>").insertAfter($('#'+parentId));
		_i = _i +1;
		return false;
	});
	
});
</script>
<BODY leftmargin=0 topmargin=0>
<div id="processing" >
<img src="images/ajax-loader.gif" alt="loading" style="width:35px;vertical-align:middle;margin-left:10px;" /> PROCESSING
</div>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
<tr>
	
	
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
					<br>
					<table border="0" cellpadding="0" cellspacing="0" width="1000" class="font11_bold">
						<tr><td height="8"></td></tr>
						<tr><td width="252"><img src="images/icon_circle03.gif">&nbsp;<span style="height:21px">Order Detail</span></td></tr>
						<tr><td height="3"></td></tr>
						<tr><td background="images/bg_check.gif" height="3"></td></tr>
						<tr><td height="2"></td></tr>
					</table>
					</td>
				</tr>
				<form name="orderform" method="post">
				<tr>
					<td>
						<table border="0" cellpadding="0" cellspacing="0" width="1000">
						<tr>
							<td colspan="4">
							<table border="0" cellpadding="0" cellspacing="0" width="1000" >
							<thead>
							<tr class='ui-widget-header'>
								<th colspan="2" height="30" align="center" >
								Purchase Order Information</th>
							</tr>
							</thead>
							<tbody>
							<tr>
							<td width="200" class='ui-widget-header left' height='30' >*Date</td>
							<td class='left'><b><?=getAUDate($list_Records[0]["orders_date"])?></b>&nbsp;</td></tr>
							<input type="hidden" name="orders_date" value="<?=$orders_date?>">
							<tr>
								<td width="200" class="ui-widget-header left" height='30'>*P.O.No</td>
								<td class='left'><b><?=$orders_number?></b></td></tr>
							<input type="hidden" name="orders_number" value="<?=$orders_number?>">
							
							<?php
						
							
								/*if ($list_Records[0]["orders_type"] != 'B') {*/
															
								$sql = "select * from supplier where supplier_id=" . $list_Records[0]["supplier_id"];
								$result = mysql_query($sql) or exit(mysql_error());
								while($rows = mysql_fetch_assoc($result)) {
									$supplier_id = $rows["supplier_id"];
									$supplier_name = $rows["supplier_name"];
									$supplier_fax_number = $rows["supplier_fax_number"];
									$supplier_phone_number = $rows["supplier_phone_number"];
									$supplier_sales_manager = $rows["supplier_sales_manager"];
								}
								mysql_free_result($result);
								/*}*/
							?>
							<input type="hidden" name="supplier_id" value="<?=$supplier_id?>">
							<tr>
								<td width="200" class="ui-widget-header left" height='30'>To</td>
								<td class='left'><b><?=getName("supplier",$supplier_id)?></b></td>
							</tr>
							<tr>
								<td width="200" class="ui-widget-header left" height='30'>Fax</td>
								<td class='left'><b><?=$supplier_fax_number?></b></td></tr>
							<tr>
								<td width="200" class="ui-widget-header left" height='30'>Ph</td>
								<td class='left'><b><?=$supplier_phone_number?></b></td></tr>
							<tr>
								<td width="200" class="ui-widget-header left" height='30'>Attn</td>
								<td style='padding-left:3px;'><b><?=$supplier_sales_manager?></b></td></tr>
							
							<?php
							 if ( $list_Records[0]["project_id"] ) {
								$sql = "select * from project where project_id = " . $list_Records[0]["project_id"];
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
							<tr><td colspan="2" style='height:3px;' background="images/bg_check.gif"></td></tr>
							</tbody>
							</table>
							<br>
							<table border="0" cellpadding="0" cellspacing="0" width="1000" >
							<thead>
							<tr class='ui-widget-header'>
								<th colspan="2" height="30">Delivery Information</th>
								
							</tr>
							</thead>
							<input type="hidden" name="project_id" value="<?=$project_id?>">
							<tbody>
							<tr>
								<td width="200" class="ui-widget-header left" height='30'>Name</td>
								
								<td class='left'><?=$project_name?></td></tr>
							<tr>
								<td width="200" class="ui-widget-header left">Address</td>
								<td class='left'><?=$project_address?> <?=$project_suburb?> <?=$project_state?> <?=$project_postcode?></td></tr>
							<tr>
								<td width="200" class="ui-widget-header left" height='30'>Delivery Date</td>
								<td class='left'><?=$list_Records[0]["delivery_date"]?>&nbsp;
							</td></tr>
							<input type="hidden" name="delivery_date" value="<?=$list_Records[0]["delivery_date"]?>">
							<tr>
								<td width="200" class="ui-widget-header left" height='30'>Site Contact1</td>
								<td class='left'><?=$project_phone_number?></td></tr>
							<tr>
								<td width="200" class="ui-widget-header left" height='30'>Site Contact2</td>
								<td class='left'><?=$list_Records[0]["supervisor_info"]?></td>
								<input type="hidden" name="supervisor_info" value="<?=$list_Records[0]["supervisor_info"]?>">
							</tr>
							<tr>
								<td width="200" class="ui-widget-header left" height='30'>Ordered By</td>
								<td class='left'><?=$list_Records[0]["username"]?></td></tr>
							<tr>
								<td width="200" class="ui-widget-header left" height='30'>Memo</td>
								<td class='left'><textarea name="remarks" style="width:775px;"><?=$list_Records[0]["remarks"]?></textarea></td></tr>
							
							<tr><td colspan="2" style='height:3px;' background="images/bg_check.gif"></td></tr>
							</tbody>
							</table>
							</td>
							
						</tr>
						</table>
						<br>
								<span style="float:right"><input type="button" name="edit" onclick="openWin()" value="EDIT"/></span>
								<br>
						<br>
						
						<?php if($list_Records[0]["orders_type"] != 'B') { ?>
						<table border="0" width="1000" cellpadding="0" cellspacing="0">
						<thead class='ui-widget-header'>
						<tr >
							<th width="110" height='30'>Qty</th>
							<th width="80">Price</th>
							<th width="120" >Unit</th>
							<th width="100" >Size</th>
							<th width="180" >Code number</th>
							<th width="100" >Colour/Shade</th>
							<th width="280" >Description</th>
							<th width="50">Clear</th>
							<th width="50">DEL</th>
						</tr>
						</thead>
						<tbody>
						<?
							for($i=0; $i < count($list_Records); $i++) {
								
								$sql = "select * from material where material_id = '" . $list_Records[$i]["material_id"] . "'";
								$result = mysql_query($sql) or exit(mysql_error());
								
								while($rows = mysql_fetch_assoc($result)) {
									
									$material_name = $rows["material_name"];
									$material_code_number = $rows["material_code_number"];
							
									$material_color = $rows["material_color"];
									$material_size = $rows["material_size"];
									$unit_id = $rows["unit_id"];
									
						?>
							<tr class='ori_order' id='<?=$list_Records[$i]["orders_id"]?>_<?=$list_Records[$i]["material_id"]?>'>
								<td align="center"><? if ($list_Records[0]["orders_status"] <> "COMPLETED") { ?><input type="checkbox" name="orders_id<?=$i?>" value="<?=$list_Records[$i]["orders_id"]?>"><input type="text" style='text-align:right;' name="orders_inventory<?=$i?>" value="<?=$list_Records[$i]["orders_inventory"]?>" size="4"><? } else { ?><?=$list_Records[$i]["orders_inventory"]?><? }?> </td>
								<td align="center"><? if ($list_Records[0]["orders_status"] <> "COMPLETED") { ?><input type="text" style='text-align:right;' name="material_price<?=$i?>" size="5" value="<?=number_format($list_Records[$i]["material_price"],2,".","")?>" size="7"><? } else { ?><?=number_format($list_Records[$i]["material_price"],2,".","")?><? }?></td>
								<td align="center"><?=getName("unit",$unit_id)?></td>
								<td align="center"><?=$material_size;?></td>
								<td height="30" style="font-size:10pt" align="center" ><b><?=$material_code_number?></b></td>
								<td align="center"><?=$material_color;?></td>
								<td align="center"><textarea name="material_description<?=$i?>" style="width:270px;"><?=$list_Records[$i]["material_description"]?></textarea></td>
								<td align="center"><button class="add_clear">
								<span class="ui-icon ui-icon-plusthick"></span>
							</button></td>
								<td align="center"><? if ($list_Records[0]["project_status"] != "COMPLETED" ) { ?>
							<a href="javascript:if(confirm('Are you sure?')) { location.href='order_view.php?orders_number=<?=$orders_number?>&orders_id=<?=$list_Records[$i]["orders_id"]?>&act_flag=delete';}"><img src='zb/images/x.gif'></a><? }?></td>
								<input type="hidden" name="material_id<?=$i?>" value="<?=$list_Records[$i]["material_id"]?>">
								<input type="hidden" name="unit_id<?=$i?>" value="<?=getName("unit",$unit_id)?>">
								<input type="hidden" name="clear_date2" class="clear_date">
							</tr>
						<?php
								/*
								 * orders_clear
								 */
								$sql_c = "SELECT * FROM orders_clear WHERE orders_id = '" . $list_Records[$i]["orders_id"] . "' ORDER BY orders_clear_date DESC";
								$result_c = mysql_query($sql_c) or exit(mysql_error());
								$int_c = 1;
								while($rows_c = mysql_fetch_assoc($result_c)) {
									$orders_clear_id = $rows_c["orders_clear_id"];
									$qty = $rows_c["orders_clear_qty"];
									$date = getAUDate($rows_c["orders_clear_date"]);
									echo "<tr><td colspan='9'> Qty:<input type='text' size='5' style='text-align:right;' name='orders_clear_qty_u_$int_c' value='$qty'> Clear date:<input type='text' name='orders_clear_date_u_$int_c' size='7' class='clear_date' value='$date'><button id='cc_$orders_clear_id' class='update_orders_clear'><span class='ui-icon ui-icon-refresh'></span></button><button id='del_cc_$orders_clear_id'><span class='ui-icon ui-icon-close'></span></button></td></tr>";
									?>
									<script type='text/javascript'>
									$(function() { 
										$('.clear_date').datepicker($.datepicker.regional['en-GB']); 
										$('.clear_date').datepicker( 'option', 'firstDay', 1 ); 
										$('.clear_date').datepicker(); 
										$('#cc_<?php echo $orders_clear_id;?>').click(function() { 
											if($('input[name="orders_clear_qty_u_<?php echo $int_c;?>"]').val() && $('input[name="orders_clear_date_u_<?php echo $int_c;?>"]').val() ) 
											{
												$('#processing').fadeIn(500); 
												$.post('update_orders_clear.php',{
													cid:'<?php echo $orders_clear_id;?>',
													qty:$('input[name="orders_clear_qty_u_<?php echo $int_c;?>"]').val(),
													date:$('input[name="orders_clear_date_u_<?php echo $int_c;?>"]').val(),
													act:"update"
													}, function(data){
														$('#processing').fadeOut(800);
														if(data=='SUCCESS'){
															window.location.reload();
															
														}else{
															window.location.reload();
															
														}
													}
												); 
												 
											}
											return false;
										});
										$('#del_cc_<?php echo $orders_clear_id;?>').click(function() { 
											$('#processing').fadeIn(500); 
											$.post('update_orders_clear.php',{
												cid:'<?php echo $orders_clear_id;?>',
												act:'delete'
												}, function(data){
													$('#processing').fadeOut(800);
													if(data=='SUCCESS'){
														window.location.reload();
													}else{
														window.location.reload();
													}
												}
											); 
											return false; 
										});
									}); 	
									</script>
								<?php	
									$int_c++;
								}
								mysql_free_result($result_c);
								
								}
							}
							mysql_free_result($result);
						?>
						
						<tr><td colspan="9" style='height:3px;'  background="images/bg_check.gif"></td></tr>
						</tbody>
						</table>
						<? } else {?>
						<table border="0" width="1000" cellpadding="0" cellspacing="0">
						<thead class='ui-widget-header'>
						<tr >
							<th width="750" >Description</th>
							<th width="200" height='30'>Amount</th>
							<th width="50">GST</th>
						</tr>
						</thead>
						<tbody>
							<tr>
								<td align="center">
								<? if ($list_Records[0]["orders_status"] <> "COMPLETED") { ?>
								<input type="checkbox" name="orders_id0" value="<?=$list_Records[0]["orders_id"]?>">
								<textarea name="material_description0" style="width:700px;" rows="3"><?=$list_Records[0]["material_description"]?></textarea> 
								<? } else { ?>
								<?=$list_Records[0]["material_description"]?><? }?></td>
								<td align="center">
								<? if ($list_Records[0]["orders_status"] <> "COMPLETED") { ?>
								<input type="text" style='text-align:right;' name="material_price0" value="<?=number_format($list_Records[0]["material_price"],2,".","")?>" size="7">
								<? } else { ?>
								<?=number_format($list_Records[0]["material_price"],2,".","")?>
								<? }?>&nbsp;</td>
								<td align="center">
								<? if ($list_Records[0]["orders_status"] <> "COMPLETED") { ?>
								<?php echo DrawFromDB("orders","orders_tax","select",$list_Records[0]["orders_tax"]," yes ","",NULL);?>
								<? } else { ?>
								<?=$list_Records[0]["orders_tax"]?><? }?></td>
								
								<input type="hidden" name="orders_inventory0" value="1">
							</tr>
						<tr><td colspan="3" style='height:3px;'  background="images/bg_check.gif"></td></tr>
						</tbody>
						</table>
						<? } ?>
						<br />
						<table border="0" cellpadding="0" cellspacing="0" width="1000">
						<tr>
							<td colspan="2"  style='height:3px;' background="images/bg_check.gif"></td>
						</tr>
						<tr>
							<td width="200" class='ui-widget-header' height='30'>Showing price on purchase order</td>
							<td class='left'><?php echo DrawFromDB("orders","price_hidden","",$list_Records[0]["price_hidden"],$sort="yes ",$title="",$event=NULL);?></td>
						</tr>
						<tr>
							<td colspan="2" style='height:3px;'  background="images/bg_check.gif"></td>
						</tr>
						<input type="hidden" name="itemcnt" value="<?=count($list_Records)?>">		
						<input type="hidden" name="act_flag" >
						</table>
						<br>
						<table border="0" cellpadding="0" cellspacing="0" bordercolor="#c3c3c6" bordercolordark="white" width="1000">
						<tr><td width="100">&nbsp;</td><td></td><td align="right" height="40">
						<? if ($list_Records[0]["orders_status"] <> "COMPLETED") { ?>
						<input type="button" value="Save" onclick="save()">
						<? } ?></td></tr>
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