<?
include_once "include/logincheck.inc";
include_once "include/common.inc";
include_once "include/dbconn.inc";
include_once "include/myfunc.inc";
include_once $ABS_DIR . "/htmlinclude/head.php";

?>
<script language="Javascript">
function o_searchNow() {
	var f = document.searchform;
	var sday = f.sday.value;
	var smonth = f.smonth.value;
	var syear = f.syear.value;

	var eday = f.eday.value;
	var emonth = f.emonth.value;
	var eyear = f.eyear.value;

	var start_date = syear + "-" + smonth + "-" + sday;
	var end_date = eyear + "-" + emonth + "-" + eday;

	if(start_date > end_date) {
		alert("Can't search!");
		return;
	}
	var group = f.srch_usergroup.value;

	userwidth = screen.width;
	userheight = screen.height;

	if(f.srch_usergroup.value == "FF") {
		if(f.srch_custword.value == "") {
			alert("Please, Insert Customize Key Word!");
			f.srch_custword.focus();
			return;
		}
	}

	if(group != "") {
		window.open("", "totalorder", "width="+userwidth+", height="+userheight+", left=0, top=0,location=yes, status=yes toolbar=yes, menubar=yes, scrollbars=yes, resizable=yes");
		f.target = "totalorder";
		f.action="report_totalorder_sub02.php";
		f.method="post";	
		f.submit();
	} else {
		window.open("", "totalorder_all", "width="+userwidth+", height="+userheight+", left=0, top=0,location=yes, status=yes toolbar=yes, menubar=yes, scrollbars=yes, resizable=yes");
		f.target = "totalorder_all";
		f.action="report_totalorder_sub.php";
		f.method="post";	
		f.submit();
	}
}

function d_searchNow() {
	var f = document.dform;	
	var group = f.srch_usergroup.value;

	userwidth = screen.width;
	userheight = screen.height;

	if(f.srch_usergroup.value == "FF") {
		if(f.srch_custword.value == "") {
			alert("Please, Insert Customize Key Word!");
			f.srch_custword.focus();
			return;
		}
	}
	
	if(group != "") {
		window.open("", "totalorder02", "width="+userwidth+", height="+userheight+", left=0, top=0,location=yes, status=yes toolbar=yes, menubar=yes, scrollbars=yes, resizable=yes");
		f.target = "totalorder02";
		f.action="report_totalorder_sub02.php";
		f.method="post";	
		f.submit();
	} else {
		window.open("", "totalorder_all", "width="+userwidth+", height="+userheight+", left=0, top=0,location=yes, status=yes toolbar=yes, menubar=yes, scrollbars=yes, resizable=yes");
		f.target = "totalorder_all";
		f.action="report_totalorder_sub.php";
		f.method="post";	
		f.submit();
	}
}

function issueTaxinvoice(orderno) {
	window.open("issue_taxinvoice.php?orderno="+orderno, "abc", "");
}
</script>
<script type="text/javascript" src="js/createselbox02.js" ></script>
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
						<tr><td width="252"><img src="images/icon_circle03.gif">&nbsp;<span style="height:21px">Order Sheet</span></td></tr>
						<tr><td height="3"></td></tr>
						<tr><td background="images/bg_check.gif" height="3"></td></tr>
						<tr><td height="2"></td></tr>
					</table>
					</td>
				</tr>
				<tr>
					<td valign="top">			
					<?
						$sday = $_REQUEST["sday"];
						$smonth = $_REQUEST["smonth"];
						$syear = $_REQUEST["syear"];

						$eday = $_REQUEST["eday"];
						$emonth = $_REQUEST["emonth"];
						$eyear = $_REQUEST["eyear"];
						if($sday == "") {
							$sdate = $now_date;

							$sday = substr($sdate, 0,2);
							$smonth = substr($sdate, 3,5);
							$syear = substr($sdate, 6,10);

							$eday = $sday;
							$emonth = $smonth;
							$eyear = $syear;
							$ehour = substr($now_time, 0,2);
							$emin = substr($now_time, 3,5);
							//echo $now_time;
						}
					?><!---- Delivery Base ---------------------------------------------------------------------->
						<form name="dform">
						<table border="0" cellpadding="0" cellspacing="0" height="40" valign="bottom" width="780">
								<tr><td colspan="2" class="dinput7" height="25" align="center">DELIVERY DATE BASE<td></tr>
							<tr><td colspan="2" background="images/bg_check02.gif" height="3"></td></tr>
							<? // 권한설정 ?>
							<? if($Sync_alevel == 'A1') { ?>
							<tr>
							<td bgcolor="#DEDFDE" height="30" style="padding-left:5px">Select</td>
							<td  style="padding-left:5px">
							<select name="srch_usergroup" style="width:150px" id="srch_usergroup" onChange="getSelectInfo(this, '2')">
							<option value="">All</option>
							<option value="B3">Sushione Shop</option>
							<option value="C1">Customer</option>
							<option value="FF">Customize</option>
							</select>
							&nbsp;
							<span id="dispSelect02"></span>
							</td>
							</tr>
							<tr><td colspan="2" background="images/bg_check02.gif" height="3"></td></tr>
							<? } ?>
							<tr>
								<td bgcolor="#DEDFDE" width="120" height="30"  style="padding-left:5px">				
								Delivery Date
								</td>
								<td bgcolor="#FFFFFF" width="500"  style="padding-left:5px">						
								<select name="sday">
								<? for($i=1; $i < 32; $i++) { 
									if($i < 10) {
										$k = "0".$i;
									} else {$k=$i;}
									?>
								<option value="<?=$k?>" <?if($sday==$i) echo "selected";?>><?=$k?></option>
								<? } ?>

								</select>&nbsp;
								<select name="smonth">
								<? for($i=1; $i < 13; $i++) { 
									if($i < 10) {
										$k = "0".$i;
									} else {$k=$i;}
									?>
								<option value="<?=$k?>" <?if($smonth==$i) echo "selected";?>><?=$k?></option>
								<? } ?>
								</select>&nbsp;
								<select name="syear">
								<option value="2006" <?if($syear=="2006") echo "selected";?>>2006</option>
								<option value="2007" <?if($syear=="2007") echo "selected";?>>2007</option>
								<option value="2008" <?if($syear=="2008") echo "selected";?>>2008</option>
								</select>								
								</td>
							</tr>
							<tr><td colspan="2" background="images/bg_check02.gif" height="3"></td></tr>
							<tr>
								<td bgcolor="#DEDFDE" height="30"  style="padding-left:5px">				
								Category
								</td>
								<td bgcolor="#FFFFFF" width="550"  style="padding-left:5px">
								<select style="width:150px" name="srch_product">
								<option value="">All</option>
								<?
									$sql = "SELECT catecode, catename FROM category ORDER BY catename ASC";
									$result = mysql_query($sql) or exit(mysql_error());
									while($rows = mysql_fetch_array($result)) {
								?>													
									<option value="<?=$rows["catecode"]?>"><?=$rows["catename"]?></option>									
								<?
									}
									mysql_free_result($result);
								?>
								</select>		
							</td></tr>
							<tr><td colspan="2" background="images/bg_check02.gif" height="3"></td></tr>							
							<tr>
							<td colspan="2" align="right" height="40"><input type="button" Value="Search" onclick="d_searchNow()"></td></tr>
						</table><input type="hidden" name="act" value="dbase">
						</form>
						<br>
						<!---- Delivery Base End ---------------------------------------------------------------------->
						<form name="searchform">
						<table border="0" cellpadding="0" cellspacing="0" height="40" valign="bottom" width="780">
							<tr><td colspan="2" class="dinput8" height="25" align="center">ORDER DATE BASE<td></tr>
							<tr><td colspan="2" background="images/bg_check02.gif" height="3"></td></tr>
							<? // 권한설정 ?>
							<? if($Sync_alevel == 'A1') { ?>
							<tr>
							<td bgcolor="#C6C7C6" height="30" style="padding-left:5px">Select</td>
							<td  style="padding-left:5px">
							<select name="srch_usergroup" style="width:150px" id="srch_usergroup" onChange="getSelectInfo(this, '1')">
							<option value="">All</option>
							<option value="B3">Sushione Shop</option>
							<option value="C1">Customer</option>
							<option value="FF">Customize</option>
							</select>
							&nbsp;
							<span id="dispSelect01"></span>
							</td>
							</tr>
							<tr><td colspan="2" background="images/bg_check02.gif" height="3"></td></tr>
							<? } ?>
							<tr>
								<td bgcolor="#C6C7C6" width="120" height="30"  style="padding-left:5px">				
								Period
								</td>
								<td bgcolor="#FFFFFF" width="500"  style="padding-left:5px">
						
								<select name="sday">
								<? for($i=1; $i < 32; $i++) { 
									if($i < 10) {
										$k = "0".$i;
									} else {$k=$i;}
									?>
								<option value="<?=$k?>" <?if($sday==$i) echo "selected";?>><?=$k?></option>
								<? } ?>

								</select>&nbsp;
								<select name="smonth">
								<? for($i=1; $i < 13; $i++) { 
									if($i < 10) {
										$k = "0".$i;
									} else {$k=$i;}
									?>
								<option value="<?=$k?>" <?if($smonth==$i) echo "selected";?>><?=$k?></option>
								<? } ?>
								</select>&nbsp;
								<select name="syear">
								<option value="2006" <?if($syear=="2006") echo "selected";?>>2006</option>
								<option value="2007" <?if($syear=="2007") echo "selected";?>>2007</option>
								<option value="2008" <?if($syear=="2008") echo "selected";?>>2008</option>
								</select>
								~
								<select name="eday">
								<? for($i=1; $i < 32; $i++) { 
									if($i < 10) {
										$k = "0".$i;
									} else {$k=$i;}
									?>
								<option value="<?=$k?>" <?if($eday==$i) echo "selected";?>><?=$k?></option>
								<? } ?>
								</select>&nbsp;
								<select name="emonth">
								<? for($i=1; $i < 13; $i++) { 
									if($i < 10) {
										$k = "0".$i;
									} else {$k=$i;}
									?>
								<option value="<?=$k?>" <?if($emonth==$i) echo "selected";?>><?=$k?></option>
								<? } ?>
								</select>&nbsp;
								<select name="eyear">
								<option value="2006" <?if($eyear=="2006") echo "selected";?>>2006</option>
								<option value="2007" <?if($eyear=="2007") echo "selected";?>>2007</option>
								<option value="2008" <?if($eyear=="2008") echo "selected";?>>2008</option>
								</select>&nbsp;
								H:i
								<select name="ehour">
								<? for($i=1; $i < 25; $i++) { 
									if($i < 10) {
										$k = "0".$i;
									} else {$k=$i;}
									?>
								<option value="<?=$k?>" <?if($ehour==$i) echo "selected";?>><?=$k?></option>
								<? } ?>
								</select>&nbsp;
								<select name="emin">
								<? for($i=1; $i < 61; $i++) { 
									if($i < 10) {
										$k = "0".$i;
									} else {$k=$i;}
									?>
								<option value="<?=$k?>" <?if($emin==$i) echo "selected";?>><?=$k?></option>
								<? } ?>
								</select>&nbsp;

								</td>
							</tr>
							<tr><td colspan="2" background="images/bg_check02.gif" height="3"></td></tr>
							<tr>
								<td bgcolor="#C6C7C6" height="30"  style="padding-left:5px">				
								Category
								</td>
								<td bgcolor="#FFFFFF" width="550"  style="padding-left:5px">								
								<select style="width:150px" name="srch_product">
								<option value="" selected="selected">All</option>
								<?
									$sql = "SELECT catecode, catename FROM category ORDER BY catename ASC";
									$result = mysql_query($sql) or exit(mysql_error());
									while($rows = mysql_fetch_array($result)) {
								?>													
									<option value="<?=$rows["catecode"]?>"><?=$rows["catename"]?></option>									
								<?
									}
									mysql_free_result($result);
								?>
								</select>						
							</td></tr>
							<tr><td colspan="2" background="images/bg_check02.gif" height="3"></td></tr>
							<tr>
								<td bgcolor="#C6C7C6" height="30"  style="padding-left:5px">				
								Status
								</td>
								<td bgcolor="#FFFFFF" width="550"  style="padding-left:5px">
								<select style="width:150px" name="srch_orderstat">
									<option value="" selected="selected">All Order Status</option>
									<option value="ORDER_COMPLETED" <?if($srch_orderstat == "ORDER_COMPLETED") echo "selected"?>>ORDER_COMPLETED</option>
									<option value="DELIVERY_STANDBY" <?if($srch_orderstat == "DELIVERY_STANDBY") echo "selected"?>>DELIVERY_STANDBY</option>
									<option value="DELIVERY_COMPLETED" <?if($srch_orderstat == "DELIVERY_COMPLETED") echo "selected"?>>DELIVERY_COMPLETED</option>					
								</select>
								&nbsp;&nbsp;
								<select style="width:150px" name="srch_paystat">
									<option value="" selected="selected">All Payment Status</option>
									<option value="WAITING" <?if($srch_paystat == "WAITING") echo "selected"?>>WAITING</option>
									<option value="PAID" <?if($srch_paystat == "PAID") echo "selected"?>>PAID</option>
								</select>
							</td></tr>
							<tr><td colspan="2" background="images/bg_check02.gif" height="3"></td></tr>
							<tr>
							<td colspan="2" align="right" height="40"><input type="button" Value="Search" onclick="o_searchNow()"></td></tr>
						</table>
						</form>
						<br>					
						
					</td>
				</tr>
				<tr><td></td></tr>
				</table>
				<!-- CONTENTS END -------------------------------------------------------------------------------------------->
			</td>
			<td>
			</td>
		  </tr>
		  <tr><td colspan="2" height="301"></td></tr>
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