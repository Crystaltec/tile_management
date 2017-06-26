<?php
ob_start();
include_once "include/common.inc";
include_once "include/dbconn.inc";
include_once "include/myfunc.inc";
include_once "htmlinclude/head.php";

$project_id = $_REQUEST["project_id"];
$category_id = $_REQUEST["category_id"];
$supplier_id = $_REQUEST["supplier_id"];
$all = $_REQUEST["all"];

?>
<script language="Javascript">
function addtoCart_oldversion() {
	var f = document.frm01;
	//var qty = document.f.elements["qty[]"];
	//var buycheckbox = document.f.elements["buycheckbox[]"];
	
	var itemcnt = parseInt(f.itemcnt.value);
	var cartchk = false;

	for(var i=0; i<f.elements["qty[]"].length;i++) {
		if(f.elements["qty[]"][i].value != "") {
			cartchk = true;
		}
	}

	if(cartchk) {
		f.action="cart_insert.php";
		f.submit();
	} else {
		alert("Please, Select product!");
	}			
}

function addtoCart() {
	var f = document.frm01;
	//var qty = document.f.elements["qty[]"];
	//var buycheckbox = document.f.elements["buycheckbox[]"];
	
	var itemcnt = parseInt(f.itemcnt.value);
	var cartchk = false;

	if(f.project_id.value =="") {
		alert("Please, Select Project!");
		return;
	} 

	for(var i=0; i<itemcnt;i++) {
		if(f.elements["qty"+i].value != "") {
			
			cartchk = true;
		}
	}

	if(cartchk) {
		f.action="cart_insert.php";
		f.submit();
	} else {
		alert("Please, Select product!");
	}			
}
function fix(){
 var a=document.body.scrollTop+80
 bar.style.top = a
}

function img_popup(fn,fe,wd,ht,wn,fnum, material_id){
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
		//alert("abc");
		window.open("product_img_popup.php?material_id="+material_id+"&img_name="+fn+"&img_extra="+fe+"&rv="+rv,wn,"left="+x+",top="+y+",width="+wd+",height="+ht+",toolbar=no,menubar=no,status=no,scrollbars=auto,resizable=yes");
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
					<td valign="top">
					<table border="0" cellpadding="0" cellspacing="0" width="1000" class="font11_bold">
						<tr><td height="8"></td></tr>
						<tr><td width="1000"><div style="height:21px;float:left;"><img src="images/icon_circle03.gif">&nbsp;Material to Site</div><div style="float:right;"><span style="background-color: #646464; width:10;">&nbsp;</span>&nbsp;Project&nbsp;&nbsp;&nbsp;<span style="background-color: #8A0A8A; width:10;">&nbsp;</span>&nbsp;Supplier&nbsp;&nbsp;&nbsp;<span style="background-color: #006400; width:10;">&nbsp;</span>&nbsp;Category</div></td></tr>
						<tr><td height="3"></td></tr>
						<tr><td background="images/bg_check.gif" height="3"></td></tr>
						<tr><td height="2"></td></tr>
					</table>
					</td>
				</tr>
				<tr>
					<td valign="top">
					<table border="0" cellpadding="0" cellspacing="0" height="50" valign="bottom" width="1000">
						<form name="frm01" method="post" onsubmit="return false">
						<tr><td bgcolor="646464"  style="padding-left:5px;color:white;line-height:18pt;">						
						<?
							$project_id = $_REQUEST["project_id"];
							$category_id = $_REQUEST["category_id"];
							$supplier_id = $_REQUEST["supplier_id"];
							$all = $_REQUEST["all"];
						
							// Project		
							## 쿼리, 담을 배열 선언
							$list_Records = array();
							$Rs_id  = array();
							$Rs_id["project_id"]	= NULL;
							$Rs_id["project_name"]	= NULL;
							$Rs_id["regdate"]= NULL;

							// status
							$Query  = "SELECT " . selectQuery($Rs_id, "project");
							$Query .= " FROM project where project_status = 0 ORDER BY project_name ASC";
							//echo $Query;

							$id_cnn = mysql_query($Query) or exit(mysql_error());
							while($id_rst = mysql_fetch_assoc($id_cnn)) {
								$list_Records = array_merge($list_Records, array($id_rst));
								//print_r($list_Records);
								//echo "<p>";
							}
							//echo count($list_Records);														
							if(is_array($list_Records)) {
								$cnt = count($list_Records);
								for($i=0; $i<$cnt; $i++) {
									if($list_Records[$i]["project_id"]==$project_id )
										$style="style='color:white;font-size:12pt;font-weight:bold'";
									else
										$style="style='color:#ffffff;'";
									echo "<a href='$PHP_SELF?project_id=".$list_Records[$i]["project_id"]."&category_id=".$category_id."&supplier_id=".$supplier_id."&all=".$all."' ".$style.">". $list_Records[$i]["project_name"] . "</a>&nbsp; | &nbsp;";

									$srch_param = "project_id=".$list_Records[$i]["project_id"]."&category_id=".$category_id."&supplier_id=".$supplier_id."&all=".$all;

									//if(($i+1)%7 == 0) echo "<br>";
								}
							}
												
							// 배열 초기화
							$list_Records = array();
						?>
						</td></tr>
						<tr><td></td></tr>
						<tr><td bgcolor="#8A0A8A"  style="padding-left:5px;color:white;line-height:18pt;">						
						<?
							$project_id = $_REQUEST["project_id"];
							$category_id = $_REQUEST["category_id"];
							$supplier_id = $_REQUEST["supplier_id"];
						
							// Category			
							## 쿼리, 담을 배열 선언
							$list_Records = array();
							$Rs_id  = array();
							$Rs_id["supplier_id"]	= NULL;
							$Rs_id["supplier_name"]	= NULL;
							$Rs_id["regdate"]= NULL;

							$Query  = "SELECT " . selectQuery($Rs_id, "supplier");
							$Query .= " FROM supplier ORDER BY supplier_name ASC";
							//echo $Query;

							$id_cnn = mysql_query($Query) or exit(mysql_error());
							while($id_rst = mysql_fetch_assoc($id_cnn)) {
								$list_Records = array_merge($list_Records, array($id_rst));
								//print_r($list_Records);
								//echo "<p>";
							}
							//echo count($list_Records);														
							if(is_array($list_Records)) {
								$cnt = count($list_Records);
								for($i=0; $i<$cnt; $i++) {
									if($list_Records[$i]["supplier_id"]==$supplier_id )
										$style="style='color:white;font-size:12pt;font-weight:bold'";
									else
										$style="style='color:#ffffff;'";
									echo "<a href='$PHP_SELF?project_id=".$project_id."&category_id=".$list_Records[$i]["category_id"]."&supplier_id=".$list_Records[$i]["supplier_id"]."&all=".$all."' ".$style.">". $list_Records[$i]["supplier_name"] . "</a>&nbsp; | &nbsp;";
									
									$srch_param = "project_id=".$project_id."&category_id=".$list_Records[$i]["category_id"]."&supplier_id=".$list_Records[$i]["supplier_id"]."&all=".$all;

									//if(($i+1)%7 == 0) echo "<br>";
								}
							}
							
							// 배열 초기화
							$list_Records = array();
						?>
						</td></tr>
						<tr><td></td></tr>
						<tr><td bgcolor="#006400"  style="padding-left:5px;color:white;line-height:18pt;">						
						<?
							$project_id = $_REQUEST["project_id"];
							$category_id = $_REQUEST["category_id"];
							$supplier_id = $_REQUEST["supplier_id"];
							$all = $_REQUEST["all"];
							// Category			
							## 쿼리, 담을 배열 선언
							$list_Records = array();
							$Rs_id  = array();
							$Rs_id["category_id"]	= NULL;
							$Rs_id["category_name"]	= NULL;
							$Rs_id["regdate"] = NULL;

							$Query  = "SELECT " . selectQuery($Rs_id, "category");
							$Query .= " FROM category ORDER BY category_name ASC";
							//echo $Query;

							$id_cnn = mysql_query($Query) or exit(mysql_error());
							while($id_rst = mysql_fetch_assoc($id_cnn)) {
								$list_Records = array_merge($list_Records, array($id_rst));
								//print_r($list_Records);
								//echo "<p>";
							}
							//echo count($list_Records);														
							if(is_array($list_Records)) {
								$cnt = count($list_Records);
								for($i=0; $i<$cnt; $i++) {
									if($list_Records[$i]["category_id"]==$category_id )
										$style="style='color:white;font-size:12pt;font-weight:bold'";
									else
										$style="style='color:#ffffff;'";
									echo "<a href='$PHP_SELF?project_id=".$project_id."&category_id=".$list_Records[$i]["category_id"]."&supplier_id=".$supplier_id."' ".$style.">". $list_Records[$i]["category_name"] . "</a>&nbsp; | &nbsp;";
									//if(($i+1)%7 == 0) echo "<br>";
								}
							}
							if($all == "1" || $category_id == "") {
								$style="style='font-size:12pt;color:#ffffff;font-weight:bold'";
								echo "<a href='material_to_site.php?project_id=".$project_id."&supplier_id=".$supplier_id."&all=1' ".$style.">All Category</a>&nbsp; | &nbsp;";
							} else {
								$style="style='color:#ffffff;'";
								echo "<a href='material_to_site.php?project_id=".$project_id."&supplier_id=".$supplier_id."&all=1' ".$style.">All Category</a>&nbsp; | &nbsp;";
							}

							$srch_param = "project_id=".$project_id."&category_id=".$list_Records[$i]["category_id"]."&supplier_id=".$supplier_id."&all=".$all;

							// 배열 초기화
							$list_Records = array();
						?>
						</td></tr>
					</table>
					</td>
				</tr>
				<tr><td height="50" align="right"><input type="button" value="Add to Cart" onclick="addtoCart();"></td></tr>
				
				<tr>
					<td valign="top">
						<table border="1" width="1000" cellpadding="1" cellspacing="0" bordercolor="#c3c3c6" bordercolordark="white">		
						<?
							## 쿼리, 담을 배열 선언
							$Rs_id = array();
							$list_Records = array();
							$Rs_id["material_id"]			= NULL;
							$Rs_id["category_id"]			= NULL;
							$Rs_id["material_name"]			= NULL;
							$Rs_id["material_price"]		= NULL;
							$Rs_id["material_code_number"]	= NULL;
							$Rs_id["unit_id"]			= NULL;
							$Rs_id["material_image"]		= NULL;
							$Rs_id["material_description"]	= NULL;
							$Rs_id["regdate"]				= NULL;
							
							if($category_id) {
								$Cond = " AND category_id=" . $category_id;
								//$srch_param = "category_id=" . $category_id;
							}

							if($supplier_id) {
								$Cond = " AND supplier_id=" . $supplier_id;
								//$srch_param = "supplier_id=" . $supplier_id;
							}

							// 페이지 계산 /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
							$page = $_REQUEST["page"];
							if(!$page)
								$page = 1;
						
							$limitPage = 10;
							$limitList = 18;
							$total = getRowCount2("SELECT COUNT(*) FROM material WHERE 1=1 ". $Cond);
							//echo ceil(1.2);
							$totalPage = ceil($total/$limitList);
							$block = ceil($page/$limitPage);
							$start = ($page-1)*$limitList;

							$startPage = ($block-1)*$limitPage + 1;
							$endPage = $startPage + $limitPage - 1;
							if ($endPage > $totalPage ) $endPage = $totalPage; 
							// 페이지 계산 끝//////////////////////////////////////////////////////////////////////////////////////////////////////////////////							
							$Query  = "SELECT * ";
							$Query .= " FROM material WHERE 1=1".$Cond." ORDER BY material_name ASC LIMIT $start, $limitList";
							//echo $Query;

							$id_cnn = mysql_query($Query) or exit(mysql_error());
							while($id_rst = mysql_fetch_assoc($id_cnn)) {
								$list_Records = array_merge($list_Records, array($id_rst));				
							}
							$j = 0;
							
							if(count($list_Records) > 0) {
								for($i=0; $i<count($list_Records); $i++) {
									if($i%6 == 0) {
										echo "<tr>";
									}

									$minusInventory = 0;
								$plusInventory = 0;

								$sql = "select IFNULL(sum(orders_inventory),0)  from orders where material_id='".$list_Records[$i]["material_id"] ."' and orders_number = '' " ;
								$minusInventory = getRowCount2($sql);
							
								$sql2 = "select IFNULL(sum(orders_inventory),0) from orders where material_id='".$list_Records[$i]["material_id"] . "' and orders_number <> '' ";
								$plusInventory = getRowCount2($sql2);
								
								$totalInventory = $list_Records[$i]["material_adjustment"] + $plusInventory - $minusInventory;

								$warning = "";
								if ($totalInventory <= 0) {
									$warning = "class='quantity02'";
								}
								
								$bgcolor = "";
								if ($list_Records[$i]["material_adjustment"] <> '0.00') {
									$bgcolor = " style='background-color:#FF8c8c;' " ;
								}

									// each item number
									//$j=$total - (($limitList * ($page-1)) + $i);
										
									//$imgfile = explode("|", $list_Records[$i]["material_image"]);
									//$size = @getimagesize($upload_root."/thumb02_". $imgfile[0]); 
									$str1 = "<td align=left valign=top>";
									$str1 .= "<table border=0 cellpadding= cellspacing=0 width='150'>";
									//if ($list_Records[$i]["material_image"]) {
									//	$str1 .= "<tr><td height='130' valign=top align=center><img src=".$upload_dir."/thumb_".$imgfile[0]." id='img_no_$i' onClick=\"img_popup('".urlencode($imgfile[0])."','thumb02_".$imgfile[0]."','".$size[0]."','".$size[1]."', '', '". $i. "', '".$material_id."')\" style='cursor:pointer;'></td></tr>";
									//}
									//else {
									//	$str1 .= "<tr><td height='130'></td></tr>";
									//}
									$str1 .= "<tr><td height='25'><b>" . $list_Records[$i]["material_name"] . "</b></td></tr>";
									$str1 .= "<tr><td height='25'><b>" . $list_Records[$i]["material_code_number"] . "</b></td></tr>";
									$str1 .= "<tr><td height='25'>Unit : <span class='unit'> " . getName("unit",$list_Records[$i]["unit_id"]) . "</span></td></tr>";
									$str1 .= "<tr><td height='25'>Price : <span class='price'>$ " . $list_Records[$i]["material_price"] . "</span></td></tr>";
									$str1 .= "<tr><td height='25' >Stock : <span ". $warning .">".$totalInventory."</span></td></tr>";
									//$str1 .= "<tr><td height='100' align=left style=padding-left:0px valign=top>".nl2br($list_Records[$i]["material_description"]). "</td></tr>";									
									$str1 .= "<tr><td align=left height='30' valign='top'>Quantity : <input type=text name='qty$i' size=4 maxlength=4 value='".$_REQUEST["qty$i"]."'>";
						
									//for($k=1;$k<100;$k++)
									//	$str1 .= "<option value='" .$k ."'>" . $k . "</option>";
									//$k=1;
									//$str1 .= "</select>&nbsp;&nbsp;&nbsp;<input type=checkbox name=buycheckbox$i value=" . $list_Records[$i]["material_id"] . "></td></tr>";
									$str1 .= "<input type='hidden' name='material_id$i' value='" . $list_Records[$i]["material_id"] . "'></td></tr>";
									$str1 .= "</table></td>";
									echo $str1;
									if($i%6 == 5) {
										echo "</tr>";
									}
								}

							} else {
								echo "<tr><td colspan=9 height=40 align='center'>Nothing to display</td></tr>";
							}
						?>
						</table>		
					</td>
					<!--
					<td style="padding-left:5px" valign="top">
						 <span id="bar" style="position:absolute; visibility:visible; z-index:1; width: 130px; height: 60px">
							<TABLE cellSpacing="0" cellPadding="3" width="130"  border="0" height="60" style="border:1px solid #c3c3c6">
							  <TBODY>
							  <TR>
								<TD align="center"><input type="button" value="Add to Cart" onclick="addtoCart();">
							 </TR>			 
							 </TBODY>
							 </TABLE>
					   </span>
					</td>
					-->
				</tr>

				<input type="hidden" name="project_id" value="<?=$project_id?>">
				<input type="hidden" name="supplier_id" value="<?=$supplier_id?>">

				<input type="hidden" name="itemcnt" value="<?=$i?>">
				</form>
				<tr><td align="center" height="30"><? include_once "paging.php"?></td></tr>
				<tr><td height="30" align="right"><input type="button" value="Add to Cart" onclick="addtoCart();"></td></tr>
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