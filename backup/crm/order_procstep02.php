<?
include_once "include/logincheck.inc";
include_once "include/common.inc";
include_once "include/dbconn.inc";
include_once "include/myfunc.inc";
include_once $ABS_DIR . "/htmlinclude/head.php";

echo $_SESSION["USER_ID"] ."<br>";
echo $_SESSION["USER_NAME"] ."<br>";
echo $_SESSION["A_LEVEL"] ."<br>";
echo $_SESSION["O_LEVEL"] ."<br>";
echo $_SESSION["PAYMENT_METHOD"] ."<br>";
?>
<script language="Javascript">
function orderNow() {
	if(confirm("Press OK to finalize this order?")) {
		var f1 = document.orderform02;
		f1.method="post";
		f1.action="order_procstep03_final.php";
		f1.submit();
	}
}

function toggleInfo() {
	var f1 = document.frm01;
	var f2 = document.frm02;
	//alert(f1.length);
	if(f1.useok.checked == true) {
		for(var i=0; i < f2.length; i++) {
			//alert(f1[i].value);
			f2[i].value = f1[i].value;
		}
	} else {
		for(var i=0; i < f2.length; i++) {
			f2[i].value="";
		}
	}
}
</script>
<?
	$d_name = $_POST["d_name"];
	$d_phone = $_POST["d_phone"];
	$d_mobile = $_POST["d_mobile"];
	$d_fax = $_POST["d_fax"];
	$d_email = $_POST["d_email"];
	$d_abn = $_POST["d_abn"];
	$d_addr = $_POST["d_addr"];
	$d_memo= $_POST["d_memo"];
	$porderno= $_POST["porderno"];
	$deliverydate = $_POST["pyear"]."-".$_POST["pmonth"]."-".$_POST["pday"];
	$puserid = $_POST["puserid"];
	$pusername = $_POST["pusername"];
	$puser_level = $_POST["puser_level"];
	$payment_method = $_POST["payment_method"];
	$amount = $_POST["amount"];
	$freight = $_POST["freight"];

	//echo "puserid: $puserid, pusername : $pusername <br>";
	//echo "payment_method: $payment_method, amount : $amount <br>";
	//echo "freight: $payment_method<br>";
?>
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
				<table border="0" cellpadding="0" cellspacing="0" width="700">				
				<tr>
					<td>
					<table border="0" cellpadding="0" cellspacing="0" height="40" valign="bottom">
						<tr><td width="490"><b style="font-size:11pt">Order Step02 - Your order confirm</b>		
						</td><td align="right" width="200">Step01 &gt;&gt; <b>Step02</b> &gt;&gt; Finish!</td></tr>
					</table>
					</td>
				</tr>
				<form name="orderform02">
				<tr>
					<td>
						<table border="1" width="700" cellpadding="3" cellspacing="0" bordercolor="#c3c3c6" bordercolordark="white">
						<tr bgcolor="#FCCFE3">
							<td width="100" align="center"><b>Item No.</b></td>
							<td width="380" height="30" style="font-size:10pt"><b>Product Name</b></td>
							<td width="80" align="center"><b>Unit</b></td>
							<td width="100" align="right"><b>Ex. Price</b></td>					
							<td width="80" align="right"><b>Qty</b></td>
							<td width="80" align="right"><b>Ex. Amount</b></td>
						</tr>
						<?
							$cart_qty = $_REQUEST["cart_qty"];
							$cart_productid = $_REQUEST["cart_productid"];
							$product_list = "";

							for($i=0; $i < count($cart_productid); $i++) {
								if($i != (count($cart_productid)-1))
									$product_list .= "'".$cart_productid[$i]. "', ";
								else
									$product_list .= "'".$cart_productid[$i]. "'";
							}
							//echo $product_list . "<br>";
							//exit;
							$amount = 0;
							$total_qty = 0;
							$rowamount = 0;
							$i = 1;
							$sql  = "SELECT productid, productname, issue_unit, priceD, priceC, priceB, priceA, imgname_extra ";
							$sql .= "FROM products WHERE productid IN (".$product_list.")";

							$result = mysql_query($sql) or exit(mysql_error());
							while($rows = mysql_fetch_assoc($result)) {
								$productid = $rows["productid"];
								$productname = $rows["productname"];
								$issue_unit = $rows["issue_unit"];
								$price = $rows["price".$Sync_olevel];

								$key_idx = array_search($productid, $cart_productid);
								$qty = $cart_qty[$key_idx];
								$imgname_extra = explode("|", $rows["imgname_extra"]);
								$total_qty += $qty;
								$rowamount = ($price * $qty);
								$amount += ($price * $qty);				
						?>
							<tr>
								<td align="center"><?=$productid?></td>
								<td height="30" style="font-size:10pt"><b><?=$productname?></b></td>
								<td align="center"><?=$issue_unit?></td>
								<td align="right">$<?=$price?></td>						
								<td align="right"><?=$qty?></td>
								<td align="right"> $<?=number_format($rowamount, 2, '.', '')?></td>
								<input type="hidden" name="cart_qty[]" value="<?=$qty?>">
								<input type="hidden" name="cart_productid[]" value="<?=$productid?>">
							</tr>
						<?
								$i++;
							}
							mysql_free_result($result);
						?>
						<!-- 계산된 금액 보여주기 -------------------------------------------------------------------------------->
						<tr>
							<td align="right" colspan="6" height="30">
							<table border="0" cellpadding="0" cellspacing="0">
							<tr>
								<td width="480" align="right"><b><font color="#000000" style="font-size:11pt">Amount :</font></b>	</td>
								<td width="73" align="right"><b><font color="#E83100" style="font-size:10pt">$<?=number_format($amount, 2 , '.', '')?></font></b></td>
							</tr>
							</table>
							</td>
						</tr>				
						<tr>
							<td align="right" colspan="6" height="30">
							<table border="0" cellpadding="0" cellspacing="0">
							<tr>
								<td width="480" align="right"><b><font color="#000000" style="font-size:11pt">Feight Charge :</font></b>	</td>
								<td width="73" align="right"><b><font color="#E83100" style="font-size:10pt">$<?=$freight?></font></b></td>
							</tr>
							</table>
							</td>
						</tr>
						<?
						$gst = ($amount + $freight) * 0.1;
						$total_inc_gst = $amount + $freight+ $gst;
						echo $total_inc_gst;
						$total_inc_gst = getCalcMoney($total_inc_gst);

						?>
						<tr>
							<td align="right" colspan="6" height="30">
							<table border="0" cellpadding="0" cellspacing="0">
							<tr>
								<td width="480" align="right"><b><font color="#000000" style="font-size:11pt">GST :</font></b>	</td>
								<td width="73" align="right"><b><font color="#E83100" style="font-size:10pt">$<?=number_format($gst, 2, '.', '')?></font></b></td>
							</tr>
							</table>
							</td>
						</tr>				
						<tr>
							<td align="right" colspan="6" height="30">
							<table border="0" cellpadding="0" cellspacing="0">
							<tr>
								<td width="480" align="right"><b><font color="#000000" style="font-size:11pt">Total inc GST :</font></b>	</td>
								<td width="73" align="right"><b><font color="#E83100" style="font-size:10pt">$<?=number_format($total_inc_gst ,2,'.','')?></font></b></td>
							</tr>
							</table>
							</td>
						</tr>
						<!-- 계산된 금액 보여주기  끝 ----------------------------------------------------------------------------->
						</table>
						<br>
						<table border="0" cellpadding="0" cellspacing="0" width="700">
						<tr><td height="28" style="font-size:11pt;font-weight:bold">* Purchase Order No : <?=$porderno?></td></tr>
						</table>				
						<input type="hidden" name="d_name" value="<?=$d_name?>">
						<input type="hidden" name="d_abn" value="<?=$d_abn?>">
						<input type="hidden" name="d_phone" value="<?=$d_phone?>">
						<input type="hidden" name="d_mobile" value="<?=$d_mobile?>">
						<input type="hidden" name="d_fax" value="<?=$d_fax?>">
						<input type="hidden" name="d_email" value="<?=$d_email?>">		
						<input type="hidden" name="d_addr" value="<?=$d_addr?>">
						<input type="hidden" name="d_memo" value="<?=$d_memo?>">
						<input type="hidden" name="amount" value="<?=$amount?>">
						<input type="hidden" name="porderno" value="<?=$porderno?>">
						<input type="hidden" name="deliverydate" value="<?=$deliverydate?>">
						<input type="hidden" name="puserid" value="<?=$puserid?>">
						<input type="hidden" name="pusername" value="<?=$pusername?>">
						<input type="hidden" name="puser_level" value="<?=$puser_level?>">
						<input type="hidden" name="payment_method" value="<?=$payment_method?>">
						<input type="hidden" name="freight" value="<?=$freight?>">
						<table border="0" cellpadding="5" cellspacing="0" width="700">
						<tr><td colspan="4" bgcolor="A22A60" height="28" align="center"><span style="color:white;font-weight:bold">Delivery Infomation</span></td></tr>
						<tr><td class="dinput4">Name</td><td colspan="3" class="dinput2"><?=$d_name?></td></tr>
						<tr><td colspan="4" height="3" background="images/bg_check.gif"></td></tr>
						<tr><td class="dinput4">ABN</td><td colspan="3" class="dinput2"><?=$d_abn?></td></tr>
						<tr><td colspan="4" height="3" background="images/bg_check.gif"></td></tr>
						<tr>
							<td width="90" class="dinput4">Phone</td><td width="120" class="dinput2"><?=$d_phone?></td>
							<td width="90" class="dinput4">Mobile</td><td class="dinput2"><?=$d_mobile?></td>
						</tr>
						<tr><td colspan="4" height="3" background="images/bg_check.gif"></td></tr>
						<tr>
							<td width="90" class="dinput4">Fax</td><td width="120" class="dinput2"><?=$d_fax?>&nbsp;</td>
							<td width="90" class="dinput4">Email</td><td class="dinput2"><?=$d_email?>&nbsp;</td>
						</tr>
						<tr><td colspan="4" height="3" background="images/bg_check.gif"></td></tr>
						<tr><td width="90" class="dinput4">Address</td><td width="300" class="dinput2" colspan="3"><?=$d_addr?>&nbsp;</td></tr>
						<tr><td colspan="4" height="3" background="images/bg_check.gif"></td></tr>
						<tr><td width="90" class="dinput4">Delivery or <br>Pick up Date</td><td width="300" class="dinput2" colspan="3"><?=getAnoDate($deliverydate)?>&nbsp;</td></tr>
						<tr><td colspan="4" height="3" background="images/bg_check.gif"></td></tr>
						<tr><td class="dinput4" height="80">Remarks</td><td colspan="3" class="dinput2"><?=nl2br($d_memo)?></td></tr>
						<tr><td colspan="4" height="3" background="images/bg_check.gif"></td></tr>				
						</table>
						<br>
						<table border="0" cellpadding="0" cellspacing="0" width="700">
						<tr><td align="right"><input type="button" value="Confirm" style="width:120" onclick="orderNow();">&nbsp;&nbsp;<input type="button" value="Cancel" onclick="location.replace('buy_product_list.php');"></td></tr>
						</table>
					</td>
				</tr>
				</form>
				<tr><td></td></tr>
				</table>
				<!-- CONTENTS END -------------------------------------------------------------------------------------------->
			</td>
			<td>
			</td>
		  </tr>
		  <tr><td colspan="2" height="59"></td></tr>
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