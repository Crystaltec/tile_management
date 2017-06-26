<?php
ob_start();
include_once "include/logincheck.inc";
include_once "include/common.inc";
include_once "include/dbconn.inc";
include_once "include/myfunc.inc";
include_once "htmlinclude/head.php";

$action_type = $_REQUEST["action_type"];

?>
<script language="Javascript">
function searchNow() {
	var f = document.searchform;

	f.action="<?=$_SERVER['PHP_SELF']?>";
	f.submit();
}

function reSort(param) {
	var f = document.listform;
	f.resort_order.value = param;
	f.action="<?=$_SERVER['PHP_SELF']?>";
	f.submit();

}

$(function() {
	$("input:button, button").button();
	$(".list_table thead").addClass('ui-widget-header');
	$(".list_table tbody").addClass('ui-widget-content');
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
						<tr><td width="252"><img src="images/icon_circle03.gif">&nbsp;<span style="height:21px">Material List</span></td></tr>
						<tr><td height="3"></td></tr>
						<tr><td background="images/bg_check.gif" height="3"></td></tr>
						<tr><td height="2"></td></tr>
					</table>
					</td>
				</tr>
				<tr>
					<td valign="top">
						<?php
							//Search Value
							$s_Cond = "";
														
							$s_name = $_REQUEST["s_name"];
							$supplier_id = $_REQUEST["supplier_id"];
							$category_id = $_REQUEST["category_id"];
							$s_resort_order = $_REQUEST["resort_order"];
							
							if($s_name != "") {
								$s_Cond .= " AND material_name like '%". $s_name ."%'";
								$srch_param .= "&s_name=$s_name";
							}
							
							if($supplier_id != "") {
								$s_Cond .= " AND supplier_id ='". $supplier_id ."'";
								$srch_param .= "&supplier_id=$supplier_id";
							}

							if($category_id != "") {
								$s_Cond .= " AND category_id ='". $category_id ."'";
								$srch_param .= "&category_id=$category_id";
							}
							
							if($s_resort_order != ""){
								if ($s_resort_order == "name") {
									$s_Sort = " ORDER BY material_name ";
								} else {
									$s_Sort = " ORDER BY " . $s_resort_order ;
								}
								
								$srch_param .= "&resort_order=$s_resort_order";
							} else {
								$s_Sort = " ORDER BY material_name ";
								$s_resort_order = 'name';
							}
							
							//$srch_param = urlencode($srch_param);
						?>
						<form name="searchform">
							<table border="0" cellpadding="0" cellspacing="0" height="40" valign="bottom" width="1000" >									<tr class='ui-widget-header'>
									<td width="200" height="30"  style="padding-left:5px">Material name
									</td>
									<td style="padding-left:5px">
									<input type='text' size="60" name="s_name" value="<?php echo $s_name;?>"/>
									&nbsp;		
								</td></tr>	
							<tr class='ui-widget-header'>
								<td width="200" height="30"  style="padding-left:5px">Supplier
								</td>
								<td style="padding-left:5px">
								<?php  getOption("supplier",$supplier_id)?>
								&nbsp;		
							</td></tr>
							<tr><td colspan="2" background="images/bg_check02.gif" height="3"></td></tr>
							<tr class='ui-widget-header'>
								<td height="30"  style="padding-left:5px">Category
								</td>
								<td style="padding-left:5px">
								<? getOption("category",$category_id)?>
								&nbsp;		
							</td></tr>
							
							<tr><td colspan="2" background="images/bg_check02.gif" height="3"></td></tr>
							<tr>
							<td colspan="2" align="right" height="30"><input type="button" Value="Search" onclick="searchNow()"></td></tr>
						</table>
						</form>
						<br>
						<form name="listform">
						<input type="hidden" name="resort_order" id="resort_order" value="">
						<table border="0" width="1000" cellpadding="0" cellspacing="1" class="list_table">
						<thead>
						<tr align="center" >
							<th width="40">No</th>
							<th width="300" onclick="reSort('name')" <?php if($s_resort_order == 'name')
							{echo "class='sort_asc'";} 
							else echo "class='sort'";
							?>>Name</th>
							<th width="135" onclick="reSort('material_code_number')" 
							<?php if($s_resort_order == 'material_code_number')
							{echo "class='sort_asc'";} 
							else echo "class='sort'";
							?> >Code number</th>
							<th width="135" onclick="reSort('material_factory_number')" 
							<?php if($s_resort_order == 'material_factory_number')
							{echo "class='sort_asc'";} 
							else echo "class='sort'";
							?> >Factory number</th>
							<th width="95">Colour/Shade</th>
							<th width="60">Size</th>
							<th width="60">Unit</th>
							<th width="60">Unit price</th>
							<th width="60">Inventory</th>
							<th></th>
						</tr>
						</thead>
						<tbody>
						<?php
							// 페이지 계산 //////////////////////////////////////////////////////////
							$page = $_REQUEST["page"];
							if(!$page)
								$page = 1;
						
							$limitPage = 10;
							$limitList = 20;
							$total = getRowCount2("SELECT COUNT(*) FROM material WHERE 1=1 ". $s_Cond);
							//echo ceil(1.2);
							$totalPage = ceil($total/$limitList);
							$block = ceil($page/$limitPage);
							$start = ($page-1)*$limitList;

							$startPage = ($block-1)*$limitPage + 1;
							$endPage = $startPage + $limitPage - 1;
							if ($endPage > $totalPage ) $endPage = $totalPage; 
							// 페이지 계산 끝///////////////////////////////////////////////////////////////////

							## 쿼리, 담을 배열 선언
							$list_Records = array();
						
							$Query  = "SELECT * ";
							$Query .= " FROM material where 1=1 " . $s_Cond . $s_Sort . "  LIMIT $start, $limitList";
							//echo $Query;
							$id_cnn = mysql_query($Query) or exit(mysql_error());
							while($id_rst = mysql_fetch_assoc($id_cnn)) {
								$list_Records = array_merge($list_Records, array($id_rst));
								//print_r($list_Records);
								//echo "<p>";
							}							
							//echo count($list_Records);
							$cnt = count($list_Records);
							if($cnt > 0) {
							for($i=0; $i<count($list_Records); $i++) {
								
								$minusInventory = 0;
								$plusInventory = 0;

								$sql = "select IFNULL(sum(orders_inventory),0)  from orders where material_id='".$list_Records[$i]["material_id"] ."' and ((new_order = 'N' AND orders_number = '') OR new_order = 'Y') AND project_id <> '0' " ;
								$minusInventory = getRowCount2($sql);
							
								$sql2 = "select IFNULL(sum(orders_inventory),0) from orders where material_id='".$list_Records[$i]["material_id"] . "' and orders_number <> '' ";
								$plusInventory = getRowCount2($sql2);
							
								$totalInventory = $list_Records[$i]["material_adjustment"] + $plusInventory - $minusInventory ;
								
								$warning = "";
								if ($totalInventory <= 0) {
									$warning = "class='quantity02'";
								}
								
								$bgcolor = "";
								if ($list_Records[$i]["material_adjustment"] <> '0.00') {
									$bgcolor = " style='background-color:#FF8c8c;' " ;
								}
								
								if($i%2 == 0){
									$even_odd = ' class="even" ';
								} else
									$even_odd = ' class="odd" ';
						?>
						<tr align="center" <?=$bgcolor?> <?php echo $even_odd;?> onmouseover="javascript:this.style.color='#536499';this.style.backgroundColor='#dcebfe';"  onmouseout="javascript:this.style.color='#000000';this.style.backgroundColor='';">
							<td height="25"><?=$total - (($limitList * ($page-1)) + $i)?></td>
							<td class="left"><a href="material_view.php?material_id=<?=$list_Records[$i]["material_id"]?>&action_type=modify"><b><?=$list_Records[$i]["material_name"]?></b></a></td>
							<td class="left"><?=$list_Records[$i]["material_code_number"]?>&nbsp;</td>
							<td class="left"><?=$list_Records[$i]["material_factory_number"]?>&nbsp;</td>
							<td><?php echo $list_Records[$i]["material_color"];?></td>
							<td><?php echo $list_Records[$i]["material_size"];?></td>
							<td><?php echo getName("unit",$list_Records[$i]["unit_id"]); ?></td>
							<td class="quantity01"><?=number_format($list_Records[$i]["material_price"],2,".",',')?>&nbsp;</td>
							<td class='right' <?=$warning?>>  <a class="info" href="javascript:void(0)"><?=$totalInventory?><span><table border="0" cellpadding="2" cellspacing="1" >
	<tr><td height="22" width="200" bgcolor="white">Adjustment(<?=$list_Records[$i]["material_adjustment"]?>) + IN(<?=$plusInventory?>) - OUT(<?=$minusInventory?>)</td></tr>
	</table></span></a></td>
	<td><a href="material_regist.php?material_id=<?=$list_Records[$i]["material_id"]?>&action_type=modify">[EDIT]</a></td>
						</tr>
						<?
							}
							} else {
								echo "<tr><td colspan=5 height=40 align=center>Nothing to display</td></tr>";
							}
						?>
						</tbody>
						</table>
						</form>
						<br>
						<table border="0" cellpadding="0" cellspacing="0" width="1000">
						<tr><td align="right"><input type="button" value="New Material" onclick="location.href='material_regist.php'"></td></tr>
						</table>
						<br>
						- Click the name of material to view details.
					</td>
				</tr>
				<tr><td align="center"><? include_once "paging.php"?></td></tr>
				</table>
				<!-- CONTENTS END -------------------------------------------------------------------------------------------->
			</td>
			<td>
			</td>
		  </tr>
		  <tr><td colspan="2" height="50"></td></tr>
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