<?
include_once "include/common.inc";
include_once "include/dbconn.inc";
include_once "include/myfunc.inc";
include_once $ABS_DIR . "/htmlinclude/head.php";
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
		alert("Please, Select product!");
	}			
}

function updateQty() {
	var f = document.frm01;
	f.action="<?=$_SERVER['PHP_SELF']?>";
	f.submit();
}

function orderNow() {
	if(confirm("Do you confirm this order?")) {
		var f = document.frm01;
		f.action="order_process.php";
		f.method="post";
		f.submit();
	}
}
</script>
<BODY leftmargin=0 topmargin=0>
<table border="0" cellpadding="0" cellspacing="0" width="700">
<tr>
	<td style="padding-left:30px" background="images/bg_main01_1.gif" valign="bottom" height="43">
		<? include_once $ABS_DIR . "/include/login_info.php"; ?>
	</td>
</tr>	
<tr>
	<td style="padding-left:30px">
	<table border="0" cellpadding="0" cellspacing="0" height="40" valign="bottom">
		<tr><td><b style="font-size:11pt">Order list confirm </b>
		
		</td></tr>
	</table>
	</td>
</tr>
<form name="frm01" method="post">
<tr>
	<td style="padding-left:30px">
		<table border="1" width="700" cellpadding="3" cellspacing="0" bordercolor="#c3c3c6" bordercolordark="white">
		<tr bgcolor="#CCE2FE">
			<td width="40" align="center"><b>Line</b></td>
			<td width="380" height="30" style="font-size:10pt"><b>Product name</b></td>
			<td width="100" align="right"><b>Price</b></td>
			<td width="80" align="right"><b>Qty</b></td>
			<td width="80" align="right"><b>Amount</b></td>
		</tr>
		<?
			$amount = 0;
			$total_qty = 0;
			$rowamount = 0;
			$i = 1;
			$sql  = "SELECT a.productid, a.productname, a.priceC, a.priceB, a.priceA, a.imgname_extra, b.qty ";
			$sql .= "FROM Products a ";
			$sql .= "INNER JOIN Cart b ";
			$sql .= " ON a.productid=b.productid ";
			$sql .= "WHERE b.userid='$Sync_id'";
			//echo $sql . "<br>";

			$result = mysql_query($sql) or exit(mysql_error());
			while($rows = mysql_fetch_assoc($result)) {
				$productid = $rows["productid"];
				$productname = $rows["productname"];
				$price = $rows["price".$Sync_olevel];
				$qty = $rows["qty"];
				$imgname_extra = explode("|", $rows["imgname_extra"]);
				$total_qty += $qty;
				$rowamount = ($price * $qty);
				$amount += ($price * $qty);				
		?>
			<tr>
				<td align="center"><?=$i?></td>
				<td height="30" style="font-size:10pt"><b><?=$productname?></b></td>
				<td align="right">$<?=$price?></td>
				<td align="right"><?=$qty?></td>
				<td align="right"> $<?=number_format($rowamount)?></td>
			</tr>
		<?
				$i++;
			}
		?>
		<tr><td align="right" colspan="5" height="30"><b><font color="#E83100" style="font-size:11pt">Total Amount : $<?=number_format($amount)?></font></b></td></tr>
		</table>
		<br>
		<table border="0" cellpadding="0" cellspacing="0" width="700">
		<tr><td align="right"><input type="button" value="Confirm" style="width:120" onclick="orderNow();">&nbsp;&nbsp;<input type="button" value="Back to cart" onclick="history.back(-1);"></td></tr>
		</table>
	</td>
</tr>
<input type="hidden" name="action_type" value="update">
<input type="hidden" name="amount" value="<?=$amount?>">
</form>
<tr><td></td></tr>
</table>
</BODY>
</HTML>

