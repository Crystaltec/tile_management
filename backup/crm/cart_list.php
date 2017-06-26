<?php
ob_start();
include "include/logincheck.inc";
include "include/common.inc";
include "include/dbconn.inc";
include "include/myfunc.inc";
include "htmlinclude/head.php";

//echo $Sync_payment;

// Update Quantity
$project_id = $_REQUEST["project_id"];
$action_type = $_REQUEST["action_type"];
if($action_type == "update") {
	$cart_qty = array();
	$cart_material_id = array();
	$cart_qty = $_REQUEST["cart_qty"];
	$cart_material_id = $_REQUEST["cart_material_id"];

	for ($i=0; $i < count($cart_qty); $i++) {
		if($cart_qty[$i] == "0" || $cart_qty[$i] == "") {
			$sql = "DELETE FROM cart WHERE user_id='" . $Sync_id . "' AND material_id='" . $cart_material_id[$i]."'";
			pQuery($sql,"delete");
		} else {
			$sql = "UPDATE cart SET qty=". $cart_qty[$i] . " WHERE user_id='" . $Sync_id . "' AND material_id='" . $cart_material_id[$i]. "'";
			//echo $sql . "<br>";
			pQuery($sql,"update");
		}
	}
} else if($action_type=="delete") {
	
	$cart_material_id = $_REQUEST["material_id"];
	$sql = "DELETE FROM cart WHERE user_id='" . $Sync_id . "' AND material_id='" . $cart_material_id . "'";
	//echo $sql . "<br>";
	pQuery($sql,"delete");
} else if ($action_type == "complete") {
	// 주문 완료 트랜잭션에 넣기
	$cart_qty = array();
	$cart_material_id = array();
	$cart_qty = $_REQUEST["cart_qty"];
	$cart_material_id = $_REQUEST["cart_material_id"];
	$material_list = "";
	
	for($i=0; $i < count($cart_material_id); $i++) {
		if($i != (count($cart_material_id)-1))
			$material_list .= "'".$cart_material_id[$i]. "', ";
		else
			$material_list .= "'".$cart_material_id[$i]. "'";
	}
	$sql  = "SELECT material_id, material_name, unit_id, material_price, material_image	FROM material WHERE material_id IN (".$material_list.")";
	//echo $sql . "<br>";

	$result = mysql_query($sql) or exit(mysql_error());
	while($rows = mysql_fetch_assoc($result)) {
		$material_id = $rows["material_id"];
		$material_name = $rows["material_name"];
		$material_price = $rows["material_price"];
		$key_idx = array_search($material_id, $cart_material_id);
		$qty = $cart_qty[$key_idx];

		$sql = "INSERT INTO orders (project_id, material_id, material_price, user_id, orders_inventory,orders_date ) VALUES ('$project_id', '$material_id', '$material_price', '$Sync_id','$qty','$now_datetimeano')";
		//echo $sql . "<br>";
		pQuery($sql,"insert");
	}

	// 주문 상품 장바구니 지우기
	$sql = "DELETE FROM cart WHERE user_id='$Sync_id' AND material_id IN (".$material_list.")";
	pQuery($sql,"delete");

	echo "<script language='javascript'>
		alert('Success!');
		location.href='material_to_site.php';
		</script>";
	
}
?>
<script language="Javascript">

function addtoCart() {
	var f = document.frm01;
	//var qty = document.f.elements["qty[]"];
	//var buycheckbox = document.f.elements["buycheckbox[]"];
	
	var itemcnt = parseInt(f.itemcnt.value);
	var cartchk = false;

	for(var i=0; i<itemcnt;i++) {
		if(f.elements["qty"+i].value != "" && f.elements["buycheckbox"+i].checked == true) {
			cartchk = true;
		}
	}

	if(cartchk) {
		f.action="cart_insert.php";
		f.submit();
	} else {
		alert("Please, Select Material!");
	}			
}

function updateQty() {
	var f = document.frm01;
	f.action="<?=$_SERVER['PHP_SELF']?>?action_type=update";
	f.submit();
}

function setQueryString(){
    queryString="";
    var frm = document.frm01
    var numberElements =  frm.elements.length;
    for(var i = 0; i < numberElements; i++)  {
            if(i < numberElements-1)  {
                queryString += frm.elements[i].name+"="+
                               encodeURIComponent(frm.elements[i].value)+"&";
            } else {
                queryString += frm.elements[i].name+"="+
                               encodeURIComponent(frm.elements[i].value);
            }
    }
	return queryString;
}

function orderNow() {
	var f = document.frm01;
	f.action="<?=$_SERVER['PHP_SELF']?>?action_type=complete";
	f.submit();
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
	<? include "left.php"; ?>
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
			<td style="padding-left:15px"><? include "top.php"; ?></td>
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
				<table border="0" cellpadding="0" cellspacing="0" width="780">
				<tr>
					<td valign="top">
					<table border="0" cellpadding="0" cellspacing="0" height="40" valign="bottom">
						<tr><td><b style="font-size:11pt">Material to site - Cart list</b>
						
						</td></tr>
					</table>
					</td>
				</tr>
				<form name="frm01" method="post" >
				<tr>
					<td valign="top">
						<table border="1" width="780" cellpadding="0" cellspacing="0" bordercolor="#c3c3c6" bordercolordark="white">
						<thead>
						<tr>
							<th colspan="6" height="40" class="ui-widget-header left"><font color="#E83100" style="font-size:11pt;font-weight:bold;"><?=getName("project",$project_id)?></font>&nbsp;</th>
						</tr>
						</thead>
						<tr>
							<!--<td width="150" align="center"><b>Image</b></td>-->
							<td height="30" style="font-size:10pt" align="center"><b>Supplier</b></td>
							<td height="30" style="font-size:10pt" align="center"><b>Name</b></td>
							<td height="30" style="font-size:10pt" align="center"><b>Code number</b></td>
							<td height="30" align="center"><b>Unit</b></td>
							<td width="80" align="center"><b>Qty</b></td>
							<td width="50" align="center"><b>Delete</b></td>
						</tr>
						<?
							$amount = 0;
							$total_qty = 0;
							$rowamount = 0;
							$sql  = "SELECT a.material_id, a.material_name, a.material_price, a.material_code_number, a.supplier_id, a.unit_id, a.material_image, b.qty ";
							$sql .= "FROM material a ";
							$sql .= "INNER JOIN cart b ";
							$sql .= " ON a.material_id=b.material_id ";
							$sql .= "WHERE b.user_id='$Sync_id'";
							//echo $sql . "<br>";

							$result = mysql_query($sql) or exit(mysql_error());
							while($rows = mysql_fetch_assoc($result)) {
								$material_id = $rows["material_id"];
								$supplier_id = $rows["supplier_id"];
								$material_name = $rows["material_name"];
								$material_price = $rows["material_price"];
								$material_code_number = $rows["material_code_number"];
								$unit_id = $rows["unit_id"];
								$qty = $rows["qty"];
								//$material_image = explode("|", $rows["material_image"]);
								$total_qty += $qty;
								$rowamount = ($material_price * $qty);
								$amount += ($material_price * $qty);				
						?>
							<tr>
								<!--<td width="150">
								<?// if ($material_image[0]) {
								//	echo "<img src='" . $upload_dir ."/thumb_". $material_image[0] ."'>";
								//	}
								?>&nbsp;</td>-->
								<td align="center"><b><?=getName("supplier",$supplier_id)?></b>&nbsp;</td>
								<td align="center"><b><?=$material_name?></b>&nbsp;</td>
								<td align="center"><?=$material_code_number?>&nbsp;</td>
								<td align="center"><?=getName("unit",$unit_id)?>&nbsp;</td>
								<td align="center"><input type="text" name="cart_qty[]" value="<?=$qty;?>" size="4" maxlength="4" style="text-align:right;"><input type="hidden" name="cart_material_id[]" value="<?=$material_id?>"></td>
								<td align="center"><a href="<?=$_SERVER['PHP_SELF']?>?project_id=<?=$project_id?>&material_id=<?=$material_id?>&action_type=delete"><img src="zb/images/x.gif"></a></td>
							</tr>
						<?
							}
						?>
						<!--<tr><td align="right" colspan="6" height="40"><b><font color="#E83100" style="font-size:11pt">Amount : $<?=number_format($amount, 2 , '.', '')?></font> </b></td></tr> -->
						</table>
						<br>
						<table border="0" cellpadding="0" cellspacing="0" width="780">
						<tr><td><input type="button" value="Add More Material" onClick="location.href='material_to_site.php?project_id=<?=$project_id?>'"></td><td align="right"><input type="button" value="Update" onclick="updateQty();">&nbsp;<input type="button" value="Order Now" onclick="orderNow();"></td></tr>
						</table>
					</td>
				</tr>
				<input type="hidden" name="project_id" value="<?=$project_id?>">
				<input type="hidden" name="amount" value="<?=$amount?>">
				</form>
				<tr><td></td></tr>
				</table>
				<!-- CONTENTS END -------------------------------------------------------------------------------------------->
			</td>
			<td>
			</td>
		  </tr>
		  <tr><td colspan="2" height="10"></td></tr>
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
	<? include "bottom.php"; ?>
	<!-- BOTTOM END -------------------------------------------------------------------------------------------->
	</td>
</tr>
</table>
</BODY>
</HTML>
<? ob_flush(); ?>