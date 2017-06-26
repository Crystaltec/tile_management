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
$orders_tax = $_REQUEST["orders_tax"];
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
$orders_type = $_REQUEST["orders_type"];
if ($orders_type == 'B') {
	$itemcount = 1;
	
	$selunit_id0 = $_REQUEST["unit_id0"];
	$selmaterial_id0 = $_REQUEST["material_id0"];
	$qty0 = $_REQUEST["qty0"];
	$selmaterial_code_number0 = $_REQUEST["material_code_number0"];
	$selmaterial_description0 = $_REQUEST["material_description0"];
	$selmaterial_price0 = $_REQUEST["material_price0"];
	
} else {
	$itemcount = 10;
	for($i=0; $i<$itemcount ; $i++) {
		$selunit_id{$i} = $_REQUEST["unit_id$i"];
		$selmaterial_id{$i} = $_REQUEST["material_id$i"];
		$qty{$i} = $_REQUEST["qty$i"];
		$selmaterial_code_number{$i} = $_REQUEST["material_code_number$i"];
		$selmaterial_description{$i} = $_REQUEST["material_description$i"];
		$selmaterial_price{$i} = $_REQUEST["material_price$i"];
	}
}
$price_hidden = $_REQUEST["price_hidden"];


?>
<script type="text/javascript" src="js/materialinfo.js" ></script>
<script language="Javascript">
<!-- 
function getSelsupervisor(){ 
    var f2 = document.orderform;
	var ValueA = document.getElementById("selsupervisor"); 
	document.getElementById("supervisor_info").value = ValueA.value;
} 

function reload() {
	var f2 = document.orderform;
	var supplier_id = document.getElementById("supplier_id");
	var project_id = document.getElementById("project_id");
	var orders_type = $('input:radio[name=orders_type]:checked').val();
	f2.action="<?php echo $_SERVER['PHP_SELF'];?>?project_id=" + project_id.value + "&supplier_id=" + supplier_id.value+"&orders_type="+orders_type;
	f2.submit();
}

function goStep02() {
	var f2 = document.orderform;
	var orders_number = document.getElementById("orders_number");
	var supplier_id = document.getElementById("supplier_id");
	var qtychk = false;

	if( orders_number.value == "") {
		alert("Please, type a P.O. number.");
		orders_number.focus();
		return;
	}
	<?php if($orders_type != 'B') { ?>
	
	if(supplier_id.value == "") {
		alert("Please, Select Supplier!");
		supplier_id.focus();
		return;
	}
	
	for(var i=0; i< <?=$itemcount?>;i++) {
		if(f2.elements["qty"+i].value != "" && f2.elements["material_id"+i].value != "") {
			qtychk = true;
		}
	}
	<?php } else { ?>
	if (!$("textarea[name=material_description0]").val()) {
		alert("Please fill in description!");
		$("textarea[name=material_description0]").focus();
		return;
	}
	if (!$("input[name=material_price0]").val()) {
		alert("Please fill in amount!");
		$("input[name=material_price0]").focus();
		return;
	}
	qtychk = true;
	
	<?php } ?>
	
	var tomorrow = "<?=$tomorrow_date?>";
	var today = "<?=$now_dateano?>";
	var now_time = "<?=$now_time?>";
		
	if ( qtychk) {
		f2.method="post";
		f2.action="purchase_order_ok2.php";
		f2.submit();
	}
	else {
		alert("Please check Material(s)!");
	}
	return false;

}

function select_material(p) {
	var _name = $( "#_id"+p )
	$( "#dialog-form" ).dialog( "open" );
	
	return false;
}
$(function() {
		$("input:button, button").button();
			
		$("#supervisor_info").autocomplete({
    		source: "autocomplete_employee_phone.php",
    		minLength: 2
    	});
    	
		$("#_id0").autocomplete({
    		source: "autocomplete_material.php?sid=<?php echo $supplier_id;?>",
    		minLength: 2,
			select: function(e, ui) { 
				getInfo(ui.item ,0);
			}
    	});
		
		$("#_id1").autocomplete({
    		source: "autocomplete_material.php?sid=<?php echo $supplier_id;?>",
    		minLength: 2,
			select: function(e, ui) { 
				getInfo(ui.item ,1);
			}
    	});
		
		$("#_id2").autocomplete({
    		source: "autocomplete_material.php?sid=<?php echo $supplier_id;?>",
    		minLength: 2,
			select: function(e, ui) { 
				getInfo(ui.item ,2);
			}
    	});
		
		$("#_id3").autocomplete({
    		source: "autocomplete_material.php?sid=<?php echo $supplier_id;?>",
    		minLength: 2,
			select: function(e, ui) { 
				getInfo(ui.item ,3);
			}
    	});
		
		$("#_id4").autocomplete({
    		source: "autocomplete_material.php?sid=<?php echo $supplier_id;?>",
    		minLength: 2,
			select: function(e, ui) { 
				getInfo(ui.item ,4);
			}
    	});
		
		$("#_id5").autocomplete({
    		source: "autocomplete_material.php?sid=<?php echo $supplier_id;?>",
    		minLength: 2,
			select: function(e, ui) { 
				getInfo(ui.item ,5);
			}
    	});
		
		$("#_id6").autocomplete({
    		source: "autocomplete_material.php?sid=<?php echo $supplier_id;?>",
    		minLength: 2,
			select: function(e, ui) { 
				getInfo(ui.item ,6);
			}
    	});
	
	$("#_id7").autocomplete({
    		source: "autocomplete_material.php?sid=<?php echo $supplier_id;?>",
    		minLength: 2,
			select: function(e, ui) { 
				getInfo(ui.item ,7);
			}
    	});
	
	$("#_id8").autocomplete({
    		source: "autocomplete_material.php?sid=<?php echo $supplier_id;?>",
    		minLength: 2,
			select: function(e, ui) { 
				getInfo(ui.item ,8);
			}
    	});
	
	$("#_id9").autocomplete({
    		source: "autocomplete_material.php?sid=<?php echo $supplier_id;?>",
    		minLength: 2,
			select: function(e, ui) { 
				getInfo(ui.item ,9);
			}
    	});
		
					
		$( "#dialog:ui-dialog" ).dialog( "destroy" );
		
		var n_category_id = $( "#n_category_id" ),
			n_name = $( "#n_name" ),
			n_code_number = $( "#n_code_number" ),
			n_factory_number = $("#n_factory_number"),
			n_color = $("#n_color"),
			n_size = $("#n_size"),
			n_unit_id = $("#n_unit_id"),
			n_price = $("#n_price"),
			n_supplier_id = $("#n_supplier_id"),
			allFields = $( [] ).add( n_category_id ).add( n_name ).add( n_code_number ).add( n_factory_number ).add(n_color).add(n_size).add(n_unit_id).add(n_price).add(n_supplier_id),
			tips = $( ".validateTips" );

		function updateTips( t ) {
			tips
				.text( t )
				.addClass( "ui-state-highlight" );
			setTimeout(function() {
				tips.removeClass( "ui-state-highlight", 1500 );
			}, 500 );
		}

		function checkLength( o, n, min, max ) {
			if ( o.val().length > max || o.val().length < min ) {
				o.addClass( "ui-state-error" );
				updateTips( "Length of " + n + " must be between " +
					min + " and " + max + "." );
				return false;
			} else {
				return true;
			}
		}
		
		function checkValue( o, n) {
			if ( o.val() == "" ) {
				o.addClass( "ui-state-error" );
				updateTips( "Please check " + n );
				return false;
			} else {
				return true;
			}
		}
		
		
		$( "#dialog-form" ).dialog({
			autoOpen: false,
			height: 370,
			width: 360,
			modal: true,
			buttons: {
				"Add New Item": function() {
					var bValid = true;
					allFields.removeClass( "ui-state-error" );

					bValid = bValid && checkValue( n_category_id, "Category");
					bValid = bValid && checkValue( n_name, "Material name");
					bValid = bValid && checkValue( n_code_number, "Code number");
					bValid = bValid && checkValue( n_factory_number, "Factory number");
					bValid = bValid && checkValue( n_color, "Colour/Shade");
					bValid = bValid && checkValue( n_size, "Size");
					bValid = bValid && checkValue( n_unit_id, "Unit");
					bValid = bValid && checkValue( n_price, "Price");
					bValid = bValid && checkValue( n_supplier_id, "Supplier");
					 
					if ( bValid ) {
						$('#processing').fadeIn(500);
						$.post("add_material.php",{
							cid : n_category_id.val(), 
							name : n_name.val(),
							cn : n_code_number.val(),
							fn : n_factory_number.val(),
							color : n_color.val(),
							size : n_size.val(),
							uid : n_unit_id.val(),
							price : n_price.val(),
							sid : n_supplier_id.val()
						}, function(data){
							$('#processing').fadeOut(800);
							if(data == "SUCCESS") {
								reload();
							} else {
								alert('Failed!');
							}
						});
		
						$( this ).dialog( "close" );
					}
				},
				Cancel: function() {
					$( this ).dialog( "close" );
				}
			},
			close: function() {
				allFields.val( "" ).removeClass( "ui-state-error" );
			}
		});

		$( "#add_new_item" )
			.button()
			.click(function() {
				$( "#dialog-form" ).dialog( "open" );
			});
		
});
//--> 
</script>
<style>
label,select { float: left; margin-right: 10px; }
fieldset { border:0; }
.ui-dialog .ui-state-error { padding: .3em; }
#dialog-form { height:295px !important; width:360px !important;}

#dialog-form input,
#dialog-form select { float:left; display:block !important; margin:0 0 5px 0 !important; }
#dialog-form label { width:110px; display:inline-block !important;}
.validateTips { border: 1px solid transparent; padding: 0.3em; }
</style>
<BODY leftmargin=0 topmargin=0>
<div id="processing" >
<img src="images/ajax-loader.gif" alt="loading" style="width:35px;vertical-align:middle;margin-left:10px;" /> PROCESSING
</div>
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
				<img src="images/icon_circle03.gif">Purchase Order</span>
				<span class="right" style="display:inline-block; width:490px;"><b>Step01</b> &gt;&gt; Step02 &gt;&gt; Finish!</span>
				</div>
				<table border="0" cellpadding="0" cellspacing="0" width="1000">				
				<thead>
				<tr class="ui-widget-header">
					<th colspan="4" style="height:30px;">Purchase Order Information</th>
				</tr>
				</thead>
				<tr>
					<td width="200" class="left dinput">*Date</td>
					<td width="800" colspan="3" class="dinput2" ><input type="text" name="orders_date" value="<?=$orders_date?>"></td>
				</tr>
				<tr><td colspan="4" height="3" class="divider"></td></tr>
				<tr>
					<td width="200" class="left dinput">*P.O.N.</td>
					<td width="800" colspan="3" class="dinput2" ><input type="text" name="orders_number" id="orders_number" value="<?=getPON($orders_number)?>"></td>
				</tr>
				<tr><td colspan="4" height="3" class="divider"></td></tr>
				<tr>
					<td width="200" class="left dinput">*To(Supplier)</td>
					<td colspan="3" class="dinput2" ><? getOption("supplier",$supplier_id,NULL,"onchange='reload();'")?></td></tr>
				<tr>
					<td colspan="4" height="3" class="divider"></td>
				</tr>
				<tr>
					<td style="height:20px;" colspan="4">&nbsp;</td>
				</tr>
				<tr class="ui-widget-header center">	
					<td colspan="4" style="height:30px;">Delivery Information</td>
				</tr>
				<tr>
					<td class="left dinput">*Deliver To</td>
					<td colspan="3" class="dinput2" ><? getOption("project",$project_id,'1')?>&nbsp;Default - company</td>
				</tr>
				<tr>
					<td colspan="4" height="3" class="divider"></td>
				</tr>
				<tr>
					<td width="200" class="left dinput">Delivery Date</td>
					<td class="dinput2" colspan="3">
					<input type="text" name="delivery_date" value="<?=$delivery_date?>">
					</td>
				</tr>
				<tr>
					<td colspan="4" height="3" class="divider"></td>
				</tr>
				<tr>
					<td width="200" class="left dinput">Site Contact2</td>
					<td class="dinput2" colspan="3">
					<?php
					$sql = " SELECT userid, username, phone from account where alevel > 'A1' AND display = 'Y' and status= 'Y' ORDER BY username";
					$result = mysql_query($sql) or exit(mysql_error());
					echo "<select id='selsupervisor' name='selsupervisor' style='width:260px;' onchange='getSelsupervisor();'>";
					echo "<option value=''>Please select</option>";
		
					while($rows = mysql_fetch_row($result)) {		
						if( $_REQUEST["selsupervisor"] == '$rows[1] . " " . $rows[2]' ) {
							echo "<option value='" . $rows[1] . " " . $rows[2] . "' selected>" . $rows[1] . " " . $rows[2] . "</option>";					} else {
							echo "<option value='" . $rows[1] . " " . $rows[2] . "'>" . $rows[1] . " ". $rows[2] . "</option>";
						}	
					}
					echo "</select>";
					mysql_free_result($result);
					?><em>OR</em>
					<input type="text" id="supervisor_info" name="supervisor_info" size="40" value="<?=$supervisor_info?>" >&larr;<small>If you want to type employee's name and phone number. Type min. 2 letters of employee's name, then select an entity on the list.</small></td>
				</tr>
				<tr>
					<td colspan="4" height="3" class="divider"></td>
				</tr>
				<tr>
					<td class="left dinput" style="width:200px;">Memo</td>
					<td class="dinput2" colspan="3"><textarea name="remarks" rows="2" cols="95"><?=$remarks?></textarea></td>
				</tr>
				<tr>
					<td style="height:10px;" colspan="4"></td>
				</tr>
				<tr>
					<td colspan="4" height="3" class="divider"></td>
				</tr>
				<tr>
					<td class="left dinput" width="200">Type of order sheet</td>
					<td colspan="3" class="dinput2" >
					<input type="radio" name="orders_type" id="order_type1" value="" <?php if ($orders_type !='B') echo "checked";?> onchange="reload();">Original style&nbsp;&nbsp;&nbsp;&nbsp;
					<input type="radio" name="orders_type" id="order_type2" value="B" <?php if ($orders_type == 'B') echo "checked";?> onchange="reload();">Text-based style</td>
				</tr>
				<tr>
					<td colspan="4" height="3" class="divider"></td>
				</tr>
				
				<tr>
					<td style="height:10px;" colspan="4"></td>
				</tr>
				<!-- material -->
				<?php if ($orders_type !='B') { ?>
				<tr>
					<td colspan="4" height="3" class="divider"></td>
				</tr>
				
				<tr>
					<td class="left dinput">Showing price on purchase order</td>
					<td class='dinput2' colspan="3">
					<?php echo DrawFromDB("orders","price_hidden","",$price_hidden," yes ","",NULL);?> Default - N</td>
				</tr>
				<tr>
					<td colspan="4" height="3" class="divider"></td>
				</tr>
				<tr>
					<td style="height:10px;" colspan="4"></td>
				</tr>
				
				<Tr>
					<td colspan="4"><input type="button" value="Add New Item" id="add_new_item"></td>
				</Tr>
				<tr>
					<td style="height:10px;" colspan="4"></td>
				</tr>
				<tr>
					<Td colspan="4">
					
					<table width="100%" cellpadding="0" cellspacing="1" >
						<thead class="ui-widget-header center">
						<tr >
							<th width="85">Search</th>
							<th width="60" >Qty</th>
							<th width="60">Price</th>
							<th width="85">Unit</th>
							<th width="85">Size</th>
							<th width="115">Code number</th>
							<th width="95">Colour/Shade</th>
							<th width="115">Factory number</th>
							<th width="200">Description</th>
						</tr>
						</thead>
						<tbody>
						<tr>
							<td colspan="8">
							Type material name in search field.<br />
							(minimum 2 letters, wildcard is '%%') </td>
						</tr>
					<?php
					for ( $i=0; $i < $itemcount ; $i++) {
					?>
						<tr>
							<td height="30" align="center">
							<?php	
							//onChange='getInfo(this,$i)'
							$str = "<input id='_id$i' name='_id$i' class=' no_bg ' size=9  ></input>";
							echo $str; 
							?></td>
							<input type="hidden" name="material_id<?=$i?>" value="<?=$selmaterial_id{$i};?>">
							<td align="center">
							<input type="text" name="qty<?=$i?>" value="<?=$qty{$i}?>" style="width:60px;"></td>
							<td align="center">
							<input type="text" name="material_price<?=$i?>" value="<?=$selmaterial_price{$i}?>" style="width:60px;"></td>
							<td align="center">
							<input type="text" name="unit_id<?=$i?>" value="<?=$selunit_id{$i}?>" style="width:75px;"></td>
							<td align="center">
							<input type="text" name="material_size<?=$i?>" readonly value="<?=$selmaterial_size{$i}?>" style="width:70px;">&nbsp;</td>
							<td align="center">
							<input type="text" name="material_code_number<?=$i?>" readonly value="<?=$selmaterial_code_number{$i};?>" style="width:105px;">&nbsp;</td>
							<td align="center">
							<input type="text" name="material_color<?=$i?>" readonly value="<?=$selmaterial_color{$i}?>" style="width:80px;">&nbsp;</td>
							<td align="center">
							<input type="text" name="material_factory_number<?=$i?>" readonly value="<?=$selmaterial_factory_number{$i};?>" style="width:105px;">&nbsp;</td>
							
												
							<td align="center">
							<textarea name="material_description<?=$i?>" rows="2" style="width:200px;" ><?=$selmaterial_description{$i}?></textarea></td>
									
						</tr>
					<?
					}
					?>
					</tbody>
					</table>
				
					</Td>
				</tr>
				<?} else { ?>
				<tr>
					<td colspan="4">
					
					<table width="1000" cellpadding="0" cellspacing="1" >
					<thead class="ui-widget-header center">
						<tr >
							<th width="750">Description</th>
							<th width="200">Amount</th>
							<th width="50">GST</th>
						</tr>
					</thead>
					<tbody >
						<tr >
							<td align="center" class="ui-widget-content">
							<textarea name="material_description0" id="material_description0" rows="5" style="width:750px;" ><?=$selmaterial_description0?></textarea>
							</td>
							<td align="center" class="ui-widget-content">
							<input type="hidden" name="qty0" value="1" >
							<input type="text" name="material_price0" id="material_price0" value="<?=$selmaterial_price0?>" style="width:150px;">
							</td>
							<td align="center" class="ui-widget-content"><?php echo DrawFromDB("orders","orders_tax","select",$orders_tax," yes ","",NULL);?></td>
						</tr>
					</tbody>
					</table>
								
					</td>
				</tr>
				<? } ?>
				<!-- material end -->
				
				<tr>
					<td style="height:20px;" colspan="4">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="4" class="right">Required fields are marked with an asterisk.(*)<br><input type="button" value="Next" style="width:120px;" onclick="goStep02();">&nbsp;</td>
				</tr>
					
				
				<tr>
					<td style="height:20px;" colspan="4">&nbsp;</td>
				</tr>
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
	<? include_once "bottom.php"; ?>
	<!-- BOTTOM END -------------------------------------------------------------------------------------------->
	</td>
</tr>
</table>
<div id="dialog-form" title="Add New Item">
	<p class="validateTips">All form fields are required.</p>

	<form>
		<fieldset>
		<label for="n_category">Category</label>
		<?php echo getOption2("category", NULL,NULL, " class='text ui-widget-content ui-corner-all' id='n_category_id' ","category_name",NULL,NULL,"n_category_id",null,'200'); ?><br />
		<label for="n_name">Name</label>
		<input type="text" name="n_name" id="n_name" value="" class="text ui-widget-content ui-corner-all" />
		<label for="n_code_number">Code Number</label>
		<input type="text" name="n_code_number" id="n_code_number" value="" class="text ui-widget-content ui-corner-all" />
		<label for="n_factory_number">Factory Number</label>
		<input type="text" name="n_factory_number" id="n_factory_number" value="" class="text ui-widget-content ui-corner-all" />
		<label for="n_color">Colour/Shade</label>
		<input type="text" name="n_color" id="n_color" value="" class="text ui-widget-content ui-corner-all" />
		<label for="n_size">Size</label>
		<input type="text" name="n_size" id="n_size" value="" class="text ui-widget-content ui-corner-all" />
		<label for="n_unit">Unit</label>
		<?php echo getOption2("unit", NULL,NULL, " class='text ui-widget-content ui-corner-all' id='n_unit_id' ", "unit_name",NULL,NULL,'n_unit_id',null,'200'); ?><br />
		<label for="n_price">Price</label>
		<input type="text" name="n_price" id="n_price" value="" class="text ui-widget-content ui-corner-all" /><br />
		<label for="n_supplier">Supplier</label>
		<?php echo getOption2("supplier", $supplier_id,NULL, " class='text ui-widget-content ui-corner-all' id='n_supplier_id' ","supplier_name",NULL,NULL,'n_supplier_id',NULL,'200'); ?>
		
		</fieldset>
	</form>
</div>
</BODY>
</HTML>
<?php ob_flush(); ?>