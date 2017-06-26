<?php
ob_start();
include_once "include/logincheck.inc";
include_once "include/common.inc";
include_once "include/dbconn.inc";
include_once "include/myfunc.inc";
include_once "htmlinclude/head.php";

$project_id = $_REQUEST["project_id"];

$act_trans = $_REQUEST["act_trans"];

if($act_trans == "delete") {
	$orders_id = $_REQUEST["orders_id"];
	$sql = "DELETE FROM orders WHERE orders_id='".$orders_id."'";
	pQuery($sql, "delete");
} else if($act_trans == "update") {
	$itemcnt = $_REQUEST["itemcnt"];
	//echo $itemcnt ."<br>";
	
	for($i=0; $i < $itemcnt; $i++) {
		
		$orders_id = $_REQUEST["orders_id$i"];
		//echo $orders_id;
		//echo "<script>alert('$orders_id');</script>";
		$orders_inventory = $_REQUEST["orders_inventory$i"];
		$material_price = $_REQUEST["material_price$i"];
		
		if ($orders_id != "") {
		//echo "orders_id : ".$orders_id.", orders_inventory : ". $orders_inventory . ", material_price : " . $material_price . "<br>";
			$sql = "UPDATE orders SET orders_inventory='" .$orders_inventory . "', material_price='" . $material_price . "'";
			$sql .= " WHERE orders_id='".$orders_id."'";
			pQuery($sql, "update");
		}
	}
}

## 쿼리, 담을 배열 선언
$list_Records = array();

$Query  = "SELECT * ";
$Query .= " FROM project WHERE project_id='" . $project_id . "'";

$id_cnn = mysql_query($Query) or exit(mysql_error());
while($id_rst = mysql_fetch_assoc($id_cnn)) {
	$list_Records = array_merge($list_Records, array($id_rst));
	//print_r($list_Records);
	//echo "<p>";
}

//$temp = explode("|", $list_Records[0]["project_image"]);
//$temp2 = explode("|", $list_Records[0]["project_image_extra"]);

//$project_image = $temp[0];
//$project_image_extra = $temp2[0];
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

function changeQty() {
	var f = document.transForm;
	var chk = false;

	var itemcnt = parseInt($("#itemcnt").val());

	for(var i=0; i<itemcnt;i++) {
		if(f.elements["orders_id"+i].checked == true) {
			chk = true;
		}
	}
	
	if(chk) {
		f.act_trans.value="update";
		f.action="<?=$_SERVER['PHP_SELF']?>";
		f.submit();
	} else {
		alert("Please, Choose Order No. and Tick to Change!");
	}			
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
						<tr><td width="252"><img src="images/icon_circle03.gif">&nbsp;<span style="height:21px">Project Detail</span></td></tr>
						<tr><td height="3"></td></tr>
						<tr><td background="images/bg_check.gif" height="3"></td></tr>
						<tr><td height="2"></td></tr>
					</table>
					</td>
				</tr>
				<form name="frm1" method="post" >
				<tr>
					<td valign="top">
						<table border="0" cellpadding="0" cellspacing="0" width="1000" class="new_entry">
						
						<tr>
							<td class="ui-widget-header left" width="200">Name</td>
							<td class="ui-widget-content left"><b><?=$list_Records[0]["project_name"]?></b>&nbsp;</td>	
						</tr>
						<tr>
							<td width="200" align="left" class="ui-widget-header left">Address</td>
							<td class="ui-widget-content left"><b><?=$list_Records[0]["project_address"]?></b>&nbsp;</td>	
						</tr>
						<tr>
							<td class="ui-widget-header left" width="200">Suburb</td>
							<td class="ui-widget-content left"><b><?=$list_Records[0]["project_suburb"]?></b>&nbsp;</td>	
						</tr>
						<tr>
							<td class="ui-widget-header left" width="200">State</td>
							<td class="ui-widget-content left"><b><?=$list_Records[0]["project_state"]?></b>&nbsp;</td>	
						</tr>
						<tr>
							<td class="ui-widget-header left" width="200">Postcode</td>
							<td class="ui-widget-content left"><b><?=$list_Records[0]["project_postcode"]?></b>&nbsp;</td>	
						</tr>
						<tr>
							<td class="ui-widget-header left" width="200">Phone number</td>
							<td class="ui-widget-content left"><b><?=$list_Records[0]["project_phone_number"]?></b>&nbsp;</td>	
						</tr>
						<tr>
							<td class="ui-widget-header left" width="200">Fax number</td>
							<td class="ui-widget-content left"><b><?=$list_Records[0]["project_fax_number"]?></b>&nbsp;</td>	
						</tr>
						<tr>
							<td class="ui-widget-header left" width="200">Builder</td>
							<td class="ui-widget-content left"><b><?=getName("builder", $list_Records[0]["builder_id"]); ?></b>&nbsp;</td>
						</tr>
						<tr>
							<td class="ui-widget-header left" width="200">Status</td>
							<td class="ui-widget-content left"><b><?=$list_Records[0]["project_status"]?></b>&nbsp;</td>	
						</tr>
						<tr>
							<Td class="ui-widget-header left" width="200" height="30" >Document</Td>
							<Td class="ui-widget-content left"><?php echo $list_Records[0]['project_document'];?></Td>
						</tr>
						<tr>
							<td class="ui-widget-header left" width="200" >Retention</td>
							<Td class="ui-widget-content left"><?php echo $list_Records[0]['project_retention'];?></Td>
						</tr>
						<Tr>
							<td class="ui-widget-header left" width="200" >Invoicing Date</td>
							<td class="ui-widget-content left"><?php echo getAUDate($list_Records[0]['project_invoicing_date']);?></td>
						</Tr>
						<tr>
							<td class="ui-widget-header left" width="200">Payment Term</td>
							<td class="ui-widget-content left"><?=getName("payment_term", $list_Records[0]["payment_term_id"]); ?></td>
						</tr>	
						<tr >
							<td class="ui-widget-header left" width="200" height="30"  >Extra-T</td>
							<td class="ui-widget-content left"><?=$list_Records[0]["extra_t"]?></td>	
						</tr>
						<tr >
							<td class="ui-widget-header left" width="200" height="30"  >Extra-S</td>
							<td class="ui-widget-content left"><?=$list_Records[0]["extra_s"]?></td>	
						</tr>
						<tr >
							<td class="ui-widget-header left" width="200" height="30"  >Extra-W</td>
							<td class="ui-widget-content left"><?=$list_Records[0]["extra_w"]?></td>	
						</tr>
						<tr >
							<td class="ui-widget-header left" width="200" height="30"  >Budget Fixing Cost</td>
							<td class="ui-widget-content left"><?=$list_Records[0]["budget_fc"]?></td>	
						</tr>
						<tr >
							<td class="ui-widget-header left" width="200" height="30"  >Variation Budget Fixing Cost</td>
							<td class="ui-widget-content left"><?=$list_Records[0]["v_budget_fc"]?></td>	
						</tr>
						
						<tr >
							<td class="ui-widget-header left" width="200" height="30"  >Contract Fixing Cost</td>
							<td class="ui-widget-content left"><?=$list_Records[0]["contract_fc"]?></td>	
						</tr>
						<tr >
							<td class="ui-widget-header left" width="200" height="30"  >Contract Supply Cost</td>
							<td class="ui-widget-content left"><?=$list_Records[0]["contract_sc"]?></td>	
						</tr>
						<tr >
							<td class="ui-widget-header left" width="200" height="30"  >Variation Fixing Cost</td>
							<td class="ui-widget-content left"><?=$list_Records[0]["v_fc"]?></td>	
						</tr>
						<tr >
							<td class="ui-widget-header left" width="200" height="30"  >Variation Supply Cost</td>
							<td class="ui-widget-content left"><?=$list_Records[0]["v_sc"]?></td>	
						</tr>
						<tr>
							<td class="ui-widget-header left" width="200">Comments</td>
							<td class="ui-widget-content left"><?=nl2br(stripslashes($list_Records[0]["project_comments"]))?>&nbsp;</td>
						</tr>
						<tr>
							<td class="ui-widget-header left" width="200" >Reg. Date</td>
							<td  class="ui-widget-content left"><b><?=getAUDate($list_Records[0]["regdate"])?></b>&nbsp;</td>	
						</tr>
						<tr>
							<td class="ui-widget-header left" width="200">End Date</td>
							<td  class="ui-widget-content left"><b><?=getAUDate($list_Records[0]["enddate"])?></b>&nbsp;</td>	
						</tr>
						</table>		
						<br>
						<table border="0" cellpadding="0" cellspacing="0" width="1000">
						<tr><td align="left" width="100"><input type="button" value="List" onclick="location.href='project_list.php'"></td><td align="right"><input type="button" value="Modify" onclick="location.href='project_regist.php?action_type=modify&project_id=<?=$list_Records[0]["project_id"]?>'">
						<?php 
						if($Sync_alevel <= "B1") { ?>
							&nbsp;<input type="button" value="Delete" onclick="if(confirm('Are you sure?')) {location.href='project_regist.php?action_type=delete&project_id=<?=$list_Records[0]["project_id"]?>'}">
						<?php } ?>
						</td></tr>
						</table>
					</td>
				</tr>
				</form>
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
				<form name="transForm" method="POST" >
				<tr>
					<td valign="top">
						<table border="0" width="1000" cellpadding="1" cellspacing="0" >
						<tr align="center"  class="ui-widget-header" height="30">
							<td width="50">No</td>
							<td>Supplier</td>
							<td>Material name</td>
							<td>Unit</td>
							<td>Colour</td>
							<td width="70">Size</td>
							<td width="70">Qty</td>
							<td width="90">Price</td>
							<td width="70">Date</td>
							<td width="40">Delete</td>
						</tr>
						<?php

							// 페이지 계산 /////////////////////////////////////////////////////////////////////////
							$page = $_REQUEST["page"];
							if(!$page)
								$page = 1;
						
							$limitPage = 10;
							$limitList = 20;
							$total = getRowCount2("SELECT COUNT(*) FROM orders o LEFT JOIN material m ON o.material_id=m.material_id WHERE ((new_order = 'N' AND (orders_number = ' ' or (orders_number <> '' and o.material_id = 0)) )OR new_order='Y') and o.project_id = '".$project_id."'");
							//echo ceil(1.2);
							$totalPage = ceil($total/$limitList);
							$block = ceil($page/$limitPage);
							$start = ($page-1)*$limitList;

							$startPage = ($block-1)*$limitPage + 1;
							$endPage = $startPage + $limitPage - 1;
							if ($endPage > $totalPage ) $endPage = $totalPage; 
							// 페이지 계산 끝////////////////////////////////////

							$srch_param = "project_id=$project_id";

							## 쿼리, 담을 배열 선언
							$list_Records2 = array();
							
							$Query  = "SELECT o.orders_id,  o.orders_number, o.orders_date, o.project_id, IF(o.material_id <> '0' and o.material_id <>  '',m.material_name,o.material_description) as material_name, IF(o.material_id <> '0' and o.material_id <> '',o.material_price,IF(o.orders_tax = 'N', o.material_price,o.material_price/1.1)) AS material_price, material_color, o.orders_inventory, m.supplier_id, m.unit_id, m.material_size ";
							$Query .= " FROM orders o LEFT JOIN material m ON o.material_id=m.material_id ".
									"WHERE ((new_order = 'N' AND (orders_number = ' ' or (orders_number <> '' and o.material_id = 0)) )OR new_order='Y') and o.project_id = '".$project_id."' ORDER BY o.orders_date DESC LIMIT $start, $limitList";
						//echo $Query;
							$id_cnn = mysql_query($Query) or exit(mysql_error());
							while($id_rst = mysql_fetch_assoc($id_cnn)) {
								$list_Records2 = array_merge($list_Records2, array($id_rst));
								//print_r($list_Records);
								//echo "<p>";
							}
							//echo count($list_Records);
							$cnt = count($list_Records2);
							
							if(count($list_Records2)) {
								for($i=0; $i<count($list_Records2); $i++) {
									if($i%2 == 0){
										$even_odd = ' class="even" ';
									} else
										$even_odd = ' class="odd" ';
						?>
						<tr align="center" <?php echo $even_odd;?> onmouseover="javascript:this.style.color='#536499';this.style.backgroundColor='#dcebfe';"  onmouseout="javascript:this.style.color='#000000';this.style.backgroundColor='';">
							<td><? if ($list_Records[0]["project_status"] != "COMPLETED") { ?><input type="checkbox" name="orders_id<?=$i?>" id="orders_id<?=$i?>" value="<?=$list_Records2[$i]["orders_id"]?>"><? }?>						
							<?=$total - (($limitList * ($page-1)) + $i)?>&nbsp;</td>
							<td><?=getName("supplier",$list_Records2[$i]["supplier_id"])?>&nbsp;</td>
							<td><b><?php echo $list_Records2[$i]["material_name"]?></b>&nbsp;</td>
							<td><?=getName("unit",$list_Records2[$i]["unit_id"])?>&nbsp;</td>
							<td width="70"><?php echo $list_Records2[$i]["material_color"];?></td>
							<td width="70"><?php echo $list_Records2[$i]["material_size"];?></td>
							<td width="70"><? if ($list_Records[0]["project_status"] != "COMPLETED") { ?><input type="text" style='text-align:right;' name="orders_inventory<?=$i?>" value="<?=$list_Records2[$i]["orders_inventory"]?>" size="4"><? } else { ?><?=$list_Records2[$i]["orders_inventory"]?><? }?>&nbsp;</td>
							<td  width="90"><? if ($list_Records[0]["project_status"] != "COMPLETED") { ?><input type="text" style='text-align:right;' name="material_price<?=$i?>" value="<?=number_format($list_Records2[$i]["material_price"],2,".","")?>" size="7"><? } else { ?><?=number_format($list_Records2[$i]["material_price"],2,".","")?><? }?>&nbsp;</td>
							<td width="70"><?=getAUDate($list_Records2[$i]["orders_date"])?>&nbsp;</td>
							<td><? if ($list_Records[0]["project_status"] != "COMPLETED" ) { ?>
							<a href="javascript:if(confirm('Are you sure?')) { location.href='project_view.php?project_id=<?=$project_id?>&orders_id=<?=$list_Records2[$i]["orders_id"]?>&act_trans=delete';}"><img src='zb/images/x.gif'></a><? }?>&nbsp;</td>
						</tr>
						<?php
								}
							} else {
								echo "<tr><td colspan=8 height=40 align=center>Nothing to display.</td></tr>";
							}
						?>
						</table>
						<input type="hidden" name="itemcnt" id="itemcnt" value="<?=count($list_Records2)?>">
						<input type="hidden" name="act_trans" >
						<input type="hidden" name="project_id" value="<?=$project_id?>">
						<table border="0" cellpadding="0" cellspacing="0" bordercolor="#c3c3c6" bordercolordark="white" width="1000">
						<tr><td width="100">&nbsp;</td><td></td><td align="right" height="40">
						<?php if ($list_Records[0]["orders_status"] <> "COMPLETED") { ?>
						<input type="button" value="Change Qty & Price" onclick="changeQty()"><? } ?></td></tr>
						</table>					
					</td>
				</tr>
				</form>
				<tr><td align="center"><?php include_once "paging.php"?></td></tr>
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
	<?php include_once "bottom.php"; ?>
	<!-- BOTTOM END -------------------------------------------------------------------------------------------->
	</td>
</tr>
</table>
</BODY>
</HTML>

<?php ob_flush(); ?>