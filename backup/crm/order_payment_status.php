<?
include_once "include/logincheck.inc";
include_once "include/common.inc";
include_once "include/dbconn.inc";
include_once "include/myfunc.inc";
include_once $ABS_DIR . "/htmlinclude/head.php";
?>
<script>
function changeStatus() {
	var f = document.orderfrm;
	
	var itemcnt = parseInt(f.itemcnt.value);
	var chk = false;

	for(var i=0; i<itemcnt;i++) {
		if(f.elements["orderno"+i].checked == true) {
			chk = true;
		}
	}
	if(chk) {
		f.update_status_flag.value="yes";
		f.action="<?=$_SERVER['PHP_SELF']?>";
		f.submit();
	} else {
		alert("Please, Check order!");
	}			
}

function showDateField(i) {
	var f = document.orderfrm;
	var ddate = document.getElementById("ddate"+i);
	if(f.elements["orderstat"+i].value == "delivery_complete") {
		ddate.style.display="block";
	} else 
		ddate.style.display="none";
}

function searchNow() {
	var f = document.searchform;
	f.action="<?=$_SERVER['PHP_SELF']?>";
	f.submit();
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
				<table border="0" cellpadding="0" cellspacing="0" width="780">
				<tr>
					<td valign="top">
					<table border="0" cellpadding="0" cellspacing="0" width="780" class="font11_bold">
						<tr><td height="8"></td></tr>
						<tr><td width="252"><img src="images/icon_circle03.gif">&nbsp;<span style="height:21px">Payment Status</span></td></tr>
						<tr><td height="3"></td></tr>
						<tr><td background="images/bg_check.gif" height="3"></td></tr>
						<tr><td height="2"></td></tr>
					</table>
					</td>
				</tr>
				<tr>
					<td valign="top">
						<?
							//Search Value			
							$s_Cond = "";
							$wantdue = $_REQUEST["wantdue"];

							if($wantdue == "" || $wantdue == "today" ) {
								$s_Cond .= " AND DATE_ADD(o.deliverydate , INTERVAL o.payment_method DAY) = '".$now_dateano."' ";
								$due_txt ="TODAY";
							} else if($wantdue == "over") {
								$s_Cond .= " AND DATE_ADD(o.deliverydate, INTERVAL o.payment_method DAY) < '".$now_dateano."'";
								$due_txt ="OVER";
							} else if($wantdue == "tomo") {
								$s_Cond .= " AND DATE_ADD(o.deliverydate , INTERVAL o.payment_method DAY) = DATE_ADD('".$now_dateano."', INTERVAL 1 DAY) ";
								$due_txt ="TOMORROW";
							}
						?>
						<form name="searchform">
						<table border="0" cellpadding="0" cellspacing="0" width="780">
							<tr><td colspan="2" class="dinput7" height="25" align="center">SEARCH OPTION<td></tr>
							<tr><td colspan="2" background="images/bg_check02.gif" height="3"></td></tr>
							<tr>				
								<td width="500"  style="padding-left:5px" height="40">
								<select style="width:150px" name="wantdue">					
									<option value="today" <? if($wantdue=="today") echo "selected"?>>Due on Today</option>
									<option value="over" <? if($wantdue=="over") echo "selected"?>>Over due</option>
									<option value="tomo" <? if($wantdue=="tomo") echo "selected"?>>Due on Tomorrow</option>
								</select>
							</td></tr>
							<tr><td colspan="2" background="images/bg_check02.gif" height="3"></td></tr>			
							<td colspan="2" align="right" height="40"><input type="button" Value="Search" onclick="searchNow()"></td></tr>
						</table>
						</form>
						<br>
						<form name="orderfrm">
						<table border="1" cellpadding="0" cellspacing="0" bordercolor="#c3c3c6" bordercolordark="white" width="780">
						<tr align="center" bgcolor="#D5D5D5" class="tr_bold" height="30">
							<td width="50">Order <br>No</td>
							<td>Order By</td>
							<td width="80">Amount</td>			
							<td width="140">Status</td>			
							<td width="80">Payment<br>Status</td>
							<td width="80">Payment Terms</td>
							<td width="80">Delivery Date</td>
							<td width="60" bgcolor="#E5E5E5">Due</td>
						</tr>
						<?							
							// 권한설정
							if($Sync_alevel <= 'B2') {
								$sql= "SELECT o.orderno, o.userid, ac.username, o.amount, o.dis_amount, o.dis_rate, o.orderstat, o.payment_method, o.paystat, ";
								$sql.= "o.orderdate, o.deliverydate, o.orderby, o.alevel ";
								$sql.="FROM orders o ";
								$sql.="INNER JOIN account ac ";
								$sql.="	ON o.userid=ac.userid ";							
								$sql.="WHERE 1=1 ". $s_Cond . " ORDER BY orderdate DESC";
							} else {
								$sql= "SELECT o.orderno, o.userid, ac.username, o.amount, o.dis_amount, o.dis_rate, o.orderstat, o.payment_method, o.paystat, ";
								$sql.= "o.orderdate, o.deliverydate, o.orderby, o.alevel ";
								$sql.="FROM orders o ";
								$sql.="INNER JOIN account ac ";
								$sql.="	ON o.userid=ac.userid ";							
								$sql.="WHERE o.userid='".$Sync_id."' ". $s_Cond . " ORDER BY orderdate DESC";
							}
		
							//echo $sql;
							$list_Records = array();							
							$id_cnn = mysql_query($sql) or exit(mysql_error());
							while($id_rst = mysql_fetch_assoc($id_cnn)) {
								$list_Records = array_merge($list_Records, array($id_rst));
								//print_r($list_Records);
								//echo "<p>";
							}
							//echo count($list_Records);
							if(is_array($list_Records)) {
							for($i=0; $i<count($list_Records); $i++) {

								$method_txt = "-";
								if($list_Records[$i]["alevel"] >= "C1") {
									if($list_Records[$i]["payment_method"] == "0") 
										$method_txt = "C.O.D";
									else 
										$method_txt = $list_Records[$i]["payment_method"] . " days";
								}

								$freight = $list_Records[$i]["dcharge"];
								$examount = $list_Records[$i]["amount"];
								$dis_amount = $list_Records[$i]["dis_amount"];
								$dis_rate = $list_Records[$i]["dis_rate"];
								$total_amount = ($examount - $dis_amount) + $freight;
								$gst = ($total_amount) * 0.1;
								$total_inc_gst = $total_amount + $gst;
								$total_inc_gst = getCalcMoney($total_inc_gst);

						?>
						<tr align="center">
							<td height="30" style="cursor:hand"><?if($Sync_alevel=="A"){?><input type="checkbox" name="orderno<?=$i?>" value="<?=$list_Records[$i]["orderno"]?>"><? }?><a href="order_view.php?orderno=<?=$list_Records[$i]["orderno"]?>"><?=$list_Records[$i]["orderno"]?></a></td>
							<td><?=$list_Records[$i]["username"]?></td>
							<td class="price2"><?=number_format($total_inc_gst,2,'.','')?></td>		
							<td>
								<? if($Sync_alevel == "A") { ?>
									<select name="orderstat<?=$i?>" style="width:140;font-size:8pt">
									<option value="ORDER_COMPLETED" <?if($list_Records[$i]["orderstat"]=="ORDER_COMPLETED") echo "selected"?>>ORDER_COMPLETED</option>
									<option value="DELIVERY_STANDBY" <?if($list_Records[$i]["orderstat"]=="DELIVERY_STANDBY") echo "selected"?>>DELIVERY_STANDBY</option>
									<option value="DELIVERY_COMPLETED" <?if($list_Records[$i]["orderstat"]=="DELIVERY_COMPLETED") echo "selected"?>>DELIVERY_COMPLETED</option>
									</select>						
								<?} else { ?>
									<?=$list_Records[$i]["orderstat"]?>
								<? } ?>
							</td>
							<td>				
								<? if($Sync_alevel == "A") { ?>
									<select name="paystat<?=$i?>" style="font-size:8pt">
									<option value="WAITING" <?if($list_Records[$i]["paystat"]=="WAITING") echo "selected"?>>WAITING</option>
									<option value="PAID" <?if($list_Records[$i]["paystat"]=="PAID") echo "selected"?>>PAID</option>
									</select>
								<?} else { ?>
									<?=$list_Records[$i]["paystat"]?>
								<? } ?>
							</td>
							<td><?=$method_txt?></td>
							<td><?=getAnoDate($list_Records[$i]["deliverydate"])?></td>
							<td><font style="color:red;font-weight:bold"><? if($list_Records[$i]["alevel"] >= "C1") echo $due_txt; else echo "-"; ?></font></td>
						</tr>			
						<?
							}
							} else {
								echo "<tr><td colspan=9 height=40 align=center>orders are nothing</td></tr>";
							}
						?>
						</table>
						<? if($Sync_alevel == "A") { ?>
						<table border="0" cellpadding="0" cellspacing="0" bordercolor="#c3c3c6" bordercolordark="white" width="780">
						<tr><td align="right" height="40"><input type="button" value="Change status" onclick="changeStatus()"></td></tr>
						</table>
						<? } ?>
						<input type="hidden" name="itemcnt" value="<?=$i?>">
						<input type="hidden" name="update_status_flag">
						</form>
					</td>
				</tr>
				<tr><td></td></tr>
				</table>
				<!-- CONTENTS END -------------------------------------------------------------------------------------------->
			</td>
			<td>
			</td>
		  </tr>
		  <tr><td colspan="2" height="531"></td></tr>
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