<?php
ob_start();
include_once "include/logincheck.inc";
include_once "include/common.inc";
include_once "include/dbconn.inc";
include_once "include/myfunc.inc";
include_once "htmlinclude/head.php";

$supplier_id = $_REQUEST["supplier_id"];
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
	
	var material_id = f.material_id.value;
	var supplier_id = f.supplier_id.value;

	if(start_date > end_date) {
		alert("Please check searching date!");
		return;
	}
	
	userwidth = screen.width;
	userheight = screen.height;
	if ( supplier_id != "" ) {

		if ( material_id != '') {
			window.open("", "Inventory_Detail", "width="+userwidth+", height="+userheight+", left=0, top=0,location=yes, status=yes toolbar=yes, menubar=yes, scrollbars=yes, resizable=yes");
			f.target = "Inventory_Detail";
			f.action="report_inventory_sub.php";
			f.method="post";	
			f.submit();
		} else {
			alert("Please select material!");
			return;
		}
		
	}else {
		if ( material_id != '') {
			window.open("", "Inventory_Detail", "width="+userwidth+", height="+userheight+", left=0, top=0,location=yes, status=yes toolbar=yes, menubar=yes, scrollbars=yes, resizable=yes");
			f.target = "Inventory_Detail";
			f.action="report_inventory_sub.php";
			f.method="post";	
			f.submit();
		}
		else 
		{
			window.open("", "Inventory_Detail", "width="+userwidth+", height="+userheight+", left=0, top=0,location=yes, status=yes toolbar=yes, menubar=yes, scrollbars=yes, resizable=yes");
			f.target = "Inventory_Detail";
			f.action="report_inventory_sub_all.php";
			f.method="post";	
			f.submit();
		}
	} 

}

function reload() {
	var f2 = document.searchform;
	var supplier_id = document.getElementById("supplier_id");
	f2.action="report_inventory.php?supplier_id=" + supplier_id.value;
	f2.target = "_self";
	f2.submit();
}

$(function() {
	$("input:button, button").button();
	$('.ui-widget-content').css({'background-image' :'none','background-color':'none'});
});

</script>
<BODY leftmargin=0 topmargin=0>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
<!-- BODY TOP ------------------------------------------------------------------------------------------->
<tr>
<td style="padding-left:0px"><? include_once "top.php"; ?></td>
</tr>
<!-- BODY TOP END --------------------------------------------------------------------------------------->

<table border="0" cellpadding="0" cellspacing="0" width="100%">
<br>
	<td valign="top" width="100" height="100%">
	<!-- LEFT -------------------------------------------------------------------------------------------------->
	<?// include_once "left.php"; ?>
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	<!-- LEFT END ---------------------------------------------------------------------------------------------->
	</td>
	<!-- LEFT BG------------------------------------------------------------------------------------------------>
	<td width="3">
	</td>
	<!-- LEFT BG END-------------------------------------------------------------------------------------------->
	<td valign="top">
	<!-- BODY -------------------------------------------------------------------------------------------------->
	<table border="0" cellpadding="0" cellspacing="0" width="100%">
			<!-- BODY CENTER ----------------------------------------------------------------------------------------->
		<tr>
			<td width="100%">			
			<table border="0" cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td style="padding-left:15px" valign="bottom" height="24" colspan="2">					
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
						<tr><td width="252"><img src="images/icon_circle03.gif">&nbsp;<span style="height:21px">Inventory Report</span></td></tr>
						<tr><td height="3"></td></tr>
						<tr><td background="images/bg_check.gif" height="3"></td></tr>
						<tr><td height="2"></td></tr>
					</table>
					</td>
				</tr>
				<tr>
					<td valign="top">			
					<?php
						$sday = $_REQUEST["sday"];
						$smonth = $_REQUEST["smonth"];
						$syear = $_REQUEST["syear"];

						$eday = $_REQUEST["eday"];
						$emonth = $_REQUEST["emonth"];
						$eyear = $_REQUEST["eyear"];
						if($sday == "") {
							$sdate = date("d-m-Y" ,time() - (3600 * 24 * 365));
								
							$sday = substr($sdate, 0,2);
							$smonth = substr($sdate, 3,2);
							$syear = substr($sdate, 6,4);
															
							$eday = substr($now_date, 0,2);
							$emonth = substr($now_date, 3,2);
							$eyear = substr($now_date, 6,4);
						
						}
					?>
						<form name="searchform">
						<table border="0" cellpadding="0" cellspacing="0" valign="bottom" width="1000">
							<tr><td colspan="2" class="ui-widget-header" height="30" align="center">ORDER DATE BASE</td></tr>
							<tr><td colspan="2" background="images/bg_check02.gif" height="3"></td></tr>
							<tr>
								<td width="200" height="30"  style="padding-left:5px" class="ui-widget-header">				
								Period
								</td>
								<td style="padding-left:5px" class="ui-widget-content">
						
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
								<? yearOption("syear",$syear); ?>&nbsp;
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
								<? yearOption("eyear",$eyear); ?>&nbsp;
								</td>
							</tr>
							<tr><td colspan="2" background="images/bg_check02.gif" height="3"></td></tr>
							<tr><td height="30"  style="padding-left:5px" class="ui-widget-header">				
								Supplier
								</td>
								<td style="padding-left:5px" class="ui-widget-content">	
							<?php getOption("supplier",$supplier_id,NULL,"onchange='reload();'")?></td>
							</tr>
							<tr><td colspan="2" background="images/bg_check02.gif" height="3"></td></tr>
							<tr>
								<td height="30"  style="padding-left:5px" class="ui-widget-header">				
								Material
								</td>
								<td style="padding-left:5px" class="ui-widget-content">								
								<?php
								$condition = "";
								if ($supplier_id <> "") {
									$condition = " where  supplier_id = '" .$supplier_id . "'";
								}

								$sql = " select material_id, material_name, supplier_id, material_color, material_code_number from material ". $condition ." order by material_name ASC" ;
								$result = mysql_query($sql) or exit(mysql_error());
								echo "<select name='material_id' style='width:700px;' >";
								echo "<option value=''>Please select</option>";
	
								while($rows = mysql_fetch_row($result)) {		
									echo "<option value='" . $rows[0] . "'>" . $rows[1] . "(" . getName("supplier",$rows[2]). " "  . $rows[3]. " " . $rows[4]. ") </option>";
								}
	
								echo "</select>";

								mysql_free_result($result); ?>&nbsp;
																
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
		  <tr><td colspan="2" height="250"></td></tr>
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