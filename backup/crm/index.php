<?php
ob_start();
// time() + (3600 * 16)
include_once "include/logincheck.inc";
include_once "include/common.inc";
include_once "include/dbconn.inc";
include_once "include/myfunc.inc";
include_once "htmlinclude/head.php";
$new_date = 1;

/*
echo $_SESSION["USER_ID"] ."<br>";
echo $_SESSION["USER_NAME"] ."<br>";
echo $_SESSION["A_LEVEL"] ."<br>";
echo $_SESSION["O_LEVEL"] ."<br>";
echo $_SESSION["PAYMENT_METHOD"] ."<br>";
*/

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
				<td style="padding-left:15px" valign="bottom" height="24" colspan="2">					
				</td>
			</tr>	
			<tr>
				<td style="padding-left:15px" valign="top">
					<!-- CONTENTS ---------------------------------------------------------------------------------------->
					<table border="0" cellpadding="0" cellspacing="0" width="100%" height="172">
					<tr >
						<td height="22" colspan="3">
							<table border="0" cellpadding="0" cellspacing="0" width="100%" height="30" >
							<tr class="ui-widget-header">
							<td width="400" style="padding-left:5px">Today Order Status</td>
							<td style="padding-left:13px">Latest Orders</td></tr>
							</table>
						</td>
					</tr>	
					<?	 
						$today_s = $now_dateano;
						$today_e = $now_dateano . " 23:59:59";
						
						$Cond = " WHERE (orders_date >='$today_s' AND orders_date <='$today_e') ";
						$Cond .= " AND orders_number <> '' ";
						 
						
						$row = array();
						$orderCount = 0;
						$sql = "SELECT distinct(orders_number) FROM orders " . $Cond;
						$result = mysql_query($sql);
						while($row = mysql_fetch_row($result)) {
							$orderCount += 1;
						}
						//print_r($row);
						
						mysql_free_result($result);
						
						$sql = "SELECT sum(material_price * orders_inventory) as amount FROM orders " . $Cond;
						$result = mysql_query($sql);
						$row1 = mysql_fetch_row($result);
						
						$orderSum = $row1[0];
						mysql_free_result($result);
											
					?>
					<tr>
						<td valign="top" width="400" style="padding-left:5px">Order Count : <?=$orderCount?> <br> Order Amount : $ <?=number_format($orderSum,2,".",",")?> 
						</td>
						<td width="1" bgcolor="#DFDFDF"></td>
						<td valign="top" style="padding-left:10px">
							<!-- Latest Order ----------------------------------------------------------->
							<table border="0" cellpadding="0" cellspacing="0" width="100%">
							<tr><td height="5" colspan="3"></td></tr>
							<?php
								// 권한설정
								$sql = "SELECT orders_number, orders_status, sum(material_price * orders_inventory) as amount, orders_date FROM orders where orders_number <> '' group by orders_number ORDER BY orders_number DESC LIMIT 0,5";
								
								$result = mysql_query($sql);
								$i = 1;
								while($rows = mysql_fetch_row($result)) {
									$orders_number = $rows[0];
									$orders_status = $rows[1];
									$amount = $rows[2];
									$orders_date = $rows[3];
									
									if($orders_status =="PROCESSING")
										$ost_img="processing.gif";
									else if($orders_status =="COMPLETED")
										$ost_img="completed.gif";
									else if($orders_status =="HOLDING")
										$ost_img="holding.gif";
									//echo $ost_img;
									echo "<tr><td width='130' height='24' style='padding-left:0px'><img src='images/".$ost_img."'></td>";
									echo "<td width='300'><a href='order_view.php?orders_number=$orders_number' style='color:black'>Order No : $orders_number &nbsp;&nbsp;&nbsp;&nbsp;Amount : <span class='price' style='display:inline-block; width:90px;text-align:right;padding-right:3px;'>$".number_format($amount,2,'.',',')."</span></a>";
									if((time()-strtotime($orders_date)) < 60*60*24*$new_date) echo "<img src='zb/images/new.gif'>";
									echo "</td>";
									echo "<td width='70' align='center'>". substr(getAUdate($orders_date),0,10) . "</td><td></td></tr>";
									echo "<tr><td height='3' background='images/bg_check.gif' colspan='4'></td></tr>";
									//if($i != 4) {
									//	echo "<tr><td colspan='4'  background='images/bg_check.gif' height='3'></td></tr>";
									//}
									$i++;
								}
								//echo $sql;
								mysql_free_result($result);
							?>
							</table>	
						<!-- Latest Orders End!-------------------------------------------------------->
						</td>
					</tr>		
					</table>

			<!-- CONTENTS END------------------------------------------------------------------------------------->
			</td>
			<td>
			</td>
		  </tr>
		  <tr><td colspan="2" height="116"></td></tr>
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