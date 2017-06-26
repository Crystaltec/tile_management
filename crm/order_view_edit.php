<?php
ob_start();
include_once "include/logincheck.inc";
include_once "include/common.inc";
include_once "include/dbconn.inc";
include_once "include/myfunc.inc";
include_once "htmlinclude/head.php";


$delivery_date= $_REQUEST["delivery_date"];
$orders_id = $_REQUEST["orders_id"];
$project_id = $_REQUEST["project_id"];
$act_flag = $_REQUEST["act_flag"];
$project_phone_number = $_REQUEST["project_phone_number"];
$supervisor_info= $_REQUEST["supervisor_info"];

		

		
			if($act_flag == "update")
			{
				$sql = "UPDATE project SET project_phone_number='" .$project_phone_number. "'";
				$sql .= " WHERE project_id='".$project_id."'";
				pQuery($sql, "update");
				
			} 
		
	
		
			if($act_flag == "update_date")
			{					
				$sql = "UPDATE orders SET delivery_date='" .$delivery_date. "'";
				$sql .= " WHERE orders_id='".$orders_id."'";
				pQuery($sql, "update");
			} 
		
		if($act_flag == "update_contact2")
			{					
				$sql = "UPDATE orders SET supervisor_info='" .$supervisor_info. "'";
				$sql .= " WHERE orders_id='".$orders_id."'";
				pQuery($sql, "update");
			} 
	
		
?>
<script language="javascript">


function save()
 {
	var f = document.orderform;
	
		f.act_flag.value="update";
		f.action="<?=$_SERVER['PHP_SELF']?>";
		f.submit();
				
}

function save_date()
 {
	var f = document.orderform;
	
		f.act_flag.value="update_date";
		f.action="<?=$_SERVER['PHP_SELF']?>";
		f.submit();
				
}

function save_contact2()
 {
	var f = document.orderform;
	
		f.act_flag.value="update_contact2";
		f.action="<?=$_SERVER['PHP_SELF']?>";
		f.submit();
				
}


</script>
<BODY leftmargin=0 topmargin=0>
<div id="processing" >
<img src="images/ajax-loader.gif" alt="loading" style="width:35px;vertical-align:middle;margin-left:10px;" /> PROCESSING
</div>
<table border="0" cellpadding="0" cellspacing="0" width="50%">
<tr>
	
	
	<td valign="top">
	<!-- BODY -------------------------------------------------------------------------------------------------->
	<table border="0" cellpadding="0" cellspacing="0" width="50%">
		<!-- BODY CENTER ----------------------------------------------------------------------------------------->
		<tr>
			<td width="50%">			
			<table border="0" cellpadding="0" cellspacing="0" width="50%">
			<tr>
				<td style="padding-left:15px" valign="bottom" height="14" colspan="2">					
				</td>
			</tr>	
			<tr>
				<td style="padding-left:15px" valign="top">
				<!-- CONTENTS -------------------------------------------------------------------------------------------->
				<table border="0" cellpadding="0" cellspacing="0" width="500">				
				<tr>
					<td>
					<br>
					<table border="0" cellpadding="0" cellspacing="0" width="500" class="font11_bold">
						<tr><td height="8"></td></tr>
						<tr><td width="252"><img src="images/icon_circle03.gif">&nbsp;<span style="height:21px">Order Detail EDIT</span></td></tr>
						<tr><td height="3"></td></tr>
						
					</table>
					</td>
				</tr>
				<form name="orderform" method="post">
				<tr>
					<td>
						<table border="0" cellpadding="0" cellspacing="0" width="500">
						<tr>
							<td colspan="4">
							<table border="0" cellpadding="0" cellspacing="0" width="500" >
							
							
							
							<?php
							
								$sql = "select * from project where project_id = " . $project_id;
								$result = mysql_query($sql) or exit(mysql_error());
								while($rows = mysql_fetch_assoc($result)) {
									
									$project_name = $rows["project_name"];
									$project_address = $rows["project_address"];
									$project_suburb = $rows["project_suburb"];
									$project_postcode = $rows["project_postcode"];
									$project_phone_number = $rows["project_phone_number"];
								}
								mysql_free_result($result);
							
							
							 	$sql1 = "select * from orders where orders_id = " . $orders_id;
								$result1 = mysql_query($sql1) or exit(mysql_error());
								while($rows1 = mysql_fetch_assoc($result1))
								{
									$delivery_date = $rows1["delivery_date"];
									$supervisor_info= $rows1["supervisor_info"];
								}
								mysql_free_result($result1);
								
							
							
							?>
							<tr><td colspan="2" style='height:3px;' background="images/bg_check.gif"></td></tr>
							</tbody>
							</table>
							<br>
							<table border="0" cellpadding="0" cellspacing="0" width="500" >
							<thead>
							<tr class='ui-widget-header'>
								<th colspan="2" height="30">Delivery Information</th>
								
							</tr>
							</thead>
							<input type="hidden" name="act_flag" >
							<input type="hidden" name="project_id" value="<?=$project_id?>">
							<input type="hidden" name="orders_id" value="<?=$orders_id?>">
							<tbody>
							<tr>
								<td width="200" class="ui-widget-header left" height='30'>Name</td>
								
								<td class='left'><?=$project_name?></td></tr>
							<tr>
								<td width="200" class="ui-widget-header left">Address</td>
								<td class='left'><?=$project_address?> <?=$project_suburb?> <?=$project_state?> <?=$project_postcode?></td></tr>
						
							<tr>
								<td width="200" class="ui-widget-header left" height='30'>Delivery Date</td>
								<td class='left'><input type="text" name="delivery_date" value="<?php echo $delivery_date?>"/>
								<input type="button" onclick="save_date()" value="SAVE"/>
								
								</td></tr>
								
							<tr>
								<td width="200" class="ui-widget-header left" height='30'>Site Contact 1</td>
								<td class='left'><input type="text" name="project_phone_number" value="<?=$project_phone_number?>"/>
								<input type="button" onclick="save()" value="SAVE"/>
							</td>
							</tr>
							
							<tr>
								<td width="200" class="ui-widget-header left" height='30'>Site Contact 2</td>
								<td class='left'><input type="text" name="supervisor_info" value="<?=$supervisor_info?>"/>
								<input type="button" onclick="save_contact2()" value="SAVE"/>
							</td>
							</tr>
							
						<td colspan="2" style='height:3px;' background="images/bg_check.gif"></td></tr>
							</tbody>
							</table>
							</td>
							
						</tr>
						</table>
						
						
						
						
						</table>
					</td>
				</tr>
				</form>
				<tr><td height="40"></td></tr>
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