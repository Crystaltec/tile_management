<?php
// 2012-02-07 removed financial status
ob_start();
include_once "include/logincheck.inc";
include_once "include/common.inc";
include_once "include/dbconn.inc";
include_once "include/myfunc.inc";
include_once "htmlinclude/head.php";

$m = date('n'); 

$lastmonth_end = date('Y-m-d',mktime(1,1,1,$m,0,date('Y')));
$cur_lastday = date('Y-m-d',mktime(1,1,1,++$m,0,date('Y'))); 

//last month
$lastmonth_start = date("Y-m-d", mktime(0, 0, 0, date("m")-1, 1, date("Y")));
$lstmonth_end = date('Y-m-t', strtotime("last month"));

//This Month
$cur_1month_day = date('Y-m-d', strtotime(date('Y-m-1')));
$cur_day = date('Y-m-d');



?>
<script type="text/javascript">
function goSort() {
	var f = document.projectForm;
	f.act.value="sort";
	f.submit();
}

function searchNow() {
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

	f.action="<?=$_SERVER['PHP_SELF']?>";
	f.submit();
}


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
	<? //include_once "left.php"; ?>
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
				<table border="0" cellpadding="0" cellspacing="0" width="1200">
				<tr>
					<td valign="top">
					<table border="0" cellpadding="0" cellspacing="0" width="1200" class="font11_bold">
						<tr><td height="8"></td></tr>
						<tr><td width="252"><img src="images/icon_circle03.gif">&nbsp;<span style="height:21px">Invoice Management Status</span></td></tr>
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
							
							
							$builder_id = $_REQUEST["builder_id"];
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
								//echo $sday . "<br>";
								//echo $smonth . "<br>";
								//echo $syear . "<br>";
								
								$eday = substr($now_date, 0,2);
								$emonth = substr($now_date, 3,2);
								$eyear = substr($now_date, 6,4);
								$ehour = substr($now_time, 0,2);
								$emin = substr($now_time, 3,2);
							}	
								
								
							
							$start_date = $syear."-".$smonth."-".$sday;							
							$end_date = $eyear."-".$emonth."-".$eday;

							//$start_date = $sday."/".$smonth."/".$syear;
							//$end_date = $eday."/".$emonth."/".$eyear;
							
							$end_date = $end_date . " 23:59:59";
							
							$s_Cond .= " AND ( inv_date >= '$start_date' AND inv_date <= '$end_date') ";	
							 
							$srch_param = "srch_pbase=$srch_pbase&sday=$sday&smonth=$smonth&syear=$syear&eday=$eday&emonth=$emonth&eyear=$eyear";


							
							
							if($limitList) {
								$srch_param .="&limitList=$limitList";
								//$srch_param = urlencode($srch_param);
							}
														
							
						?>
						<form name="searchform">
						<table border="0" cellpadding="0" cellspacing="0" height="40" valign="bottom" width="1200">							
							<tr class="ui-widget-header">
								<td width="200" height="30"  style="padding-left:5px">	Invoice Period
								
								</td>
								<td style="padding-left:5px">
						
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
								<? yearOption("syear",$syear); ?>
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
								<?php
								 for($i=1; $i < 13; $i++) { 
									if($i < 10) {
										$k = "0".$i;
									} else {$k=$i;}
									?>
								<option value="<?=$k?>" <?if($emonth==$i) echo "selected";?>><?=$k?></option>
								<? } ?>
								</select>&nbsp;
								<? yearOption("eyear",$eyear); ?>
								
								<input type="button" Value="Search" onclick="searchNow()">
								</td>
							</tr>
							<tr>
							<tr><td colspan="2" background="images/bg_check02.gif" height="3"></td></tr>	
							
							<td colspan="2" align="right" height="3">
							
						
						
						</span>	
								</td></tr>
						</table>
						</form>
						<br>
						<form name="projectForm" method="POST" action="<?=$_SERVER["PHP_SELF"]?>">
						<input type="hidden" name="act" value="<?=$act?>">
						
						
						

						
						</tbody>
						
						<br>
						
						
						<?	
							

//This month

			$sql_period_month = "SELECT * FROM invoice_manage WHERE inv_date BETWEEN '".$start_date."' AND '".$end_date."'";
										
						$result = mysql_query($sql_period_month) or exit(mysql_error());
							
				if($result)
					{
						$ave_period_inv_amount = 0;
						$ave_period_rec_amount = 0;
							
						while ($row = mysql_fetch_assoc($result)) 
							
						{
						$ave_period_inv_amount  += $row['inv_amount'] ;
						$ave_period_rec_amount  += $row['received_amount'] ;
						}		
					}
					
					
					
echo "<tr><td width='100'> Total Invoice Amount : <span class='price' style='display:inline-block; width:1px;text-align:right;padding-right:3px;'>$".number_format($ave_period_inv_amount,2,'.',',')."</span>  " ;
					
echo "<tr> <td width='100'> <span class='price' style='display:inline-block; width:1px;text-align:right;padding-right:3px;'> " ;
							
								
echo "<tr> <td width='100'> Total Received Amount : <span class='price' style='display:inline-block; width:1px;text-align:right;padding-right:3px;'>$".number_format($ave_period_rec_amount,2,'.',',')."</span>  " ;

			
echo "<tr> <td width='100'> <span class='price' style='display:inline-block; width:1px;text-align:right;padding-right:3px;'> " ;


			?>
					
						
						
						
						<tr class="ui-widget-header" height="30">
									
				
						<td >Current Month: <?php echo getAUDate($cur_1month_day)?> ~ <?php echo getAUDate($cur_day)?> </td>
						<tr><td height="3"></td></tr>
						<tr><td background="images/bg_check.gif" height="3"></td></tr>
						<tr><td height="2"></td></tr>
								
							
						</td>
					</tr>	
					<?	
							

//This month

			$sql_this_month = "SELECT * FROM invoice_manage WHERE inv_date BETWEEN '".$cur_1month_day."' AND '".$cur_day."'";
										
						$result = mysql_query($sql_this_month) or exit(mysql_error());
							
				if($result)
					{
						$ave_inv_amount = 0;
						$ave_rec_amount = 0;
							
						while ($row = mysql_fetch_assoc($result)) 
							
						{
						$ave_inv_amount  += $row['inv_amount'] ;
						$ave_rec_amount  += $row['received_amount'] ;
						}		
					}
					
		
		echo "<tr> <td width='100'> <span class='price' style='display:inline-block; width:1px;text-align:right;padding-right:3px;'> " ;
			
					
echo "<tr><td width='100'> Total Invoice Amount : <span class='price' style='display:inline-block; width:1px;text-align:right;padding-right:3px;'>$".number_format($ave_inv_amount,2,'.',',')."</span>  " ;
					
											
			
echo "<tr> <td width='100'> <span class='price' style='display:inline-block; width:1px;text-align:right;padding-right:3px;'> " ;
								
echo "<tr> <td width='100'> Total Received Amount : <span class='price' style='display:inline-block; width:1px;text-align:right;padding-right:3px;'>$".number_format($ave_rec_amount,2,'.',',')."</span>  " ;


			?>
			
			
			<table border="0" cellpadding="0" cellspacing="0" height="105" valign="bottom" width="1200">	
			<tr class="ui-widget-header">
					
					<tr><td background="images/bg_check.gif" height="3"></td></tr>
						<tr class="ui-widget-header">
						
						<td>Previous Month: <?php echo getAUDate($lastmonth_start)?> ~ <?php echo getAUDate($lastmonth_end)?> </td>
						<tr><td height="3"></td></tr>
						<tr><td background="images/bg_check.gif" height="3"></td></tr>
						<tr><td height="10"></td></tr>
						

<?php


//Last month

			$sql_prev_month = "SELECT * FROM invoice_manage WHERE inv_date BETWEEN '".$lastmonth_start."' AND '".$lastmonth_end."'";
								
						$result = mysql_query($sql_prev_month) or exit(mysql_error());
							
				if($result)
					{
						$ave_inv_prev_amount = 0;
						$ave_rec_prev_amount = 0;
							
						while ($row = mysql_fetch_assoc($result)) 
							
						{
						$ave_inv_prev_amount  += $row['inv_amount'] ;
						$ave_rec_prev_amount += $row['received_amount'] ;
						}		
					}

			


echo "<tr> <td width='100'> Total Invoice Amount : <span class='price' style='display:inline-block; width:1px;text-align:left;padding-right:3px;'>$".number_format($ave_inv_prev_amount,2,'.',',')."</span>  " ;
					
	
								
echo "<tr> <td width='100'> Total Received Amount : <span class='price' style='display:inline-block; width:1px;text-align:right;padding-right:3px;'>$".number_format($ave_rec_prev_amount,2,'.',',')."</span>  " ;



											
					?>
			
			
			
			
			
			
						
						</table>
						
						
						
						</form>
						<br>
						
					</td>
				</tr>
				
				</table>
				<!-- CONTENTS END -------------------------------------------------------------------------------------------->
			</td>
			<td>
			</td>
		  </tr>
		  <tr><td colspan="2" height="20"></td></tr>
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
<?php ob_flush(); ?>