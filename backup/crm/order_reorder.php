<?
ob_start();
include_once "include/logincheck.inc";
include_once "include/common.inc";
include_once "include/dbconn.inc";
include_once "include/myfunc.inc";
include_once "htmlinclude/head.php";

$orderno = $_REQUEST["orderno"];
$sql = "SELECT * FROM orders WHERE orderno=".$orderno;
$list_Records = array();
$id_cnn = mysql_query($sql) or exit(mysql_error());
while($id_rst = mysql_fetch_assoc($id_cnn)) {
	$list_Records = array_merge($list_Records, array($id_rst));
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
		alert("Please, Select product!");
	}			
}

function updateQty() {
	var f = document.frm01;
	f.action_type.value="update";
	f.action="<?=$_SERVER['PHP_SELF']?>";
	f.submit();
}

function reOrderNow() {
	var f = document.frm01;
	var pday = f.pday.value;
	var pmonth = f.pmonth.value;
	var pyear = f.pyear.value;
	var deli_date = pyear + "-" + pmonth + "-" + pday;
	var tomorrow = "<?=$tomorrow_date?>";
	var today = "<?=$now_dateano?>";
	var now_time = "<?=$now_time?>";
	<? if($Sync_alevel >= "B3") { ?>
	if(deli_date == today) {
		alert('WE CAN NOT PROCESS SAME DAY ORDER, PLEASE CONTACT OUR OFFICE (TEL 07 5528 6100)');
		return;
	}
	
	if(deli_date == tomorrow) {
		if(now_time >= "15:00:00") {
			alert('ORDER CUT OFF TIME IS 3PM FOR NEXT DAY DELIVERY, PLEASE CONTACT OUR OFFICE (TEL 07 5528 6100)');
			return;
		}
	}
	<? } else { ?>
	if(deli_date == today) {
		if(confirm('YOU CAN NOT PROCESS SAME DAY ORDER, ARE YOU 100% SURE TO CONTINUE?\n\r (YOU HAVE TO MAKE YOUR OWN SUSHI ^^;;')) {
			f.action="order_reorder_ok.php";
			f.method="POST";
			f.submit();	
			return;
		} else {
			return;
		}		
	}

	if(deli_date == tomorrow) {
		if(now_time >= "15:00:00") {
			if(confirm('ORDER CUT OFF TIME IS 3PM FOR NEXT DAY DELIVERY. DO YOU WANT TO CONTINUE THIS ORDER?')) {
				f.method="post";
				f.action="order_procstep02.php";
				f.submit();
				return;
			} else {
				return;
			}
		}
	}
	<?}?>

	if(confirm("Do you want to reorder?")) {	
		f.action="order_reorder_ok.php";
		f.method="POST";
		f.submit();
	}
}

</script>
<BODY leftmargin=0 topmargin=0>
<table border="0" width="100%" cellpadding="3" cellspacing="0" bordercolor="#c3c3c6" bordercolordark="white">
<form name="frm01">
<input type="hidden" name="orderno" value="<?=$orderno?>">
<tr>
	<td>
	<table border="0" width="700" cellpadding="3" cellspacing="0" bordercolor="#c3c3c6" bordercolordark="white" class="font10_bold">
	<tr><td width="100">Order No</td><td>: <?=$orderno?></td></tr>
	<tr><td>Order By</td><td>: 
	<? 
		if($list_Records[0]["alevel"] <= "B2")
			echo "Sushione (".$list_Records[0]["orderby"].") for ".$list_Records[0]["username"];
		else
			echo $list_Records[0]["username"];			
	?>&nbsp;</td></tr>
	<tr><td>Delivery Date</td><td>
	<?								
		$pday = substr($now_date, 0,2);
		$pmonth = substr($now_date, 3,2);
		$pyear = substr($now_date, 6,4);
	?>
	<select name="pday">
	<option value=="">Day</option>
	<? for($i=1; $i < 32; $i++) { 
		if($i < 10) {
			$k = "0".$i;
		} else {$k=$i;}
		?>
	<option value="<?=$k?>" <?if($pday==$i) echo "selected";?>><?=$k?></option>
	<? } ?>

	</select>&nbsp;
	<select name="pmonth">
	<option value=="">Month</option>
	<? for($i=1; $i < 13; $i++) { 
		if($i < 10) {
			$k = "0".$i;
		} else {$k=$i;}
		?>
	<option value="<?=$k?>" <?if($pmonth==$i) echo "selected";?>><?=$k?></option>
	<? } ?>
	</select>&nbsp;
	<select name="pyear">
	<option value=="">Year</option>
	<option value="2006" <?if($pyear=="2006") echo "selected";?>>2006</option>
	<option value="2007" <?if($pyear=="2007") echo "selected";?>>2007</option>
	<option value="2008" <?if($pyear=="2008") echo "selected";?>>2008</option>
	</select>

	</td></tr>
	</table>
	</td>
</tr>	
<tr>
	<td>
	<table border="1" width="700" cellpadding="3" cellspacing="0" bordercolor="#c3c3c6" bordercolordark="white">
		<tr bgcolor="#CCE2FE">
			<td width="100" align="center"><b>Category</b></td>
			<td width="100" align="center"><b>Item No.</b></td>
			<td height="30" align="center"><b>Product Name</b></td>
			<td width="80" align="center"><b>Qty</b></td>
		</tr>
		<?		
			$amount = 0;
			$total_qty = 0;
			$rowamount = 0;
			$i = 0;
		
			$sql = "SELECT od.productid, od.productnm, od.price, od.qty, p.catename FROM orderdetail od INNER JOIN products p ON od.productid=p.productid WHERE orderno=".$orderno;
			$result = mysql_query($sql) or exit(mysql_error());
			while($rows = mysql_fetch_assoc($result)) {
				$productid = $rows["productid"];
				$productnm = $rows["productnm"];
				$qty = $rows["qty"];
				$catename = $rows["catename"];
				
		?>
			<tr>
				<td align="center"><?=$catename?></td>
				<td align="center"><?=$productid?></td>
				<td align="center"><b><?=$productnm?></b></td>
				<td align="center"><input type="text" name="cart_qty[]" value="<?=$qty?>" size="4" maxlength="4"><input type="hidden" name="cart_productid[]" value="<?=$productid?>"></td>
			</tr>
		<?
				$i++;
			}
			mysql_free_result($result);
		?>		
	</table>
	<br>
	<table border="0" cellpadding="0" cellspacing="0" width="700">
	<tr><td align="right"><input type="button" value="ReOrder Now" onclick="reOrderNow(<?=$orderno?>)"></td></tr>
	</table>
	</td>
</tr>
</form>
</table>
</BODY>
</HTML>
<? ob_flush(); ?>