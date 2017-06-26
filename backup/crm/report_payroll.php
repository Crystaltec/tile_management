<?php
ob_start();
include_once "include/logincheck.inc";
include_once "include/common.inc";
include_once "include/dbconn.inc";
include_once "include/myfunc.inc";
include_once "htmlinclude/head.php";

?>
<script language="Javascript">
function processDate(date){ 
   var parts = date.split("-"); 
   var date = new Date(parts[1] + "-" + parts[0] + "-" + parts[2]); 
   return date; 
} 

function o_searchNow() {
	var f = document.searchform;
	var from =$('#from').val();
	var to = $('#to').val();
	
	if(processDate(from) > processDate(to)) {
		alert("Please check the period.");
		return;
	}
	
	var project_id = f.project_id.value;
	var employee_id = f.employee_id.value;
	
	userwidth = screen.width;
	userheight = screen.height;

	if ( project_id != '' || employee_id != '') {
		window.open("", "Payroll", "width="+userwidth+", height="+userheight+", left=0, top=0,location=yes, status=yes toolbar=yes, menubar=yes, scrollbars=yes, resizable=yes");
		f.target = "Payroll";
		f.action="report_payroll_sub.php";
		f.method="post";	
		f.submit();
	} else {
		window.open("", "Payroll", "width="+userwidth+", height="+userheight+", left=0, top=0,location=yes, status=yes toolbar=yes, menubar=yes, scrollbars=yes, resizable=yes");
		f.target = "Payroll";
		f.action="report_payroll_sub_all.php";
		f.method="post";	
		f.submit();

	}

}

$(function() {
	$("input:button, button").button();
	$('.ui-widget-content').css({'background-image' :'none','background-color':'transparent'});
	
	$("#from").datepicker($.datepicker.regional['en-GB']);
	$("#from").datepicker( "option", "firstDay", 1 );
	$("#from").datepicker();
	
	$("#to").datepicker($.datepicker.regional['en-GB']);
	$("#to").datepicker( "option", "firstDay", 1 );
	$("#to").datepicker();
	
});
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
				<table border="0" cellpadding="0" cellspacing="0" width="1000">				
				<tr>
					<td valign="top">
					<table border="0" cellpadding="0" cellspacing="0" width="1000" class="font11_bold">
						<tr><td height="8"></td></tr>
						<tr><td width="252"><img src="images/icon_circle03.gif">&nbsp;<span style="height:21px">Payroll Report</span></td></tr>
						<tr><td height="3"></td></tr>
						<tr><td background="images/bg_check.gif" height="3"></td></tr>
						<tr><td height="2"></td></tr>
					</table>
					</td>
				</tr>
				<tr>
					<td valign="top">			
					<?php
						$from = $_REQUEST["from"];
						$to = $_REQUEST["to"];
						
						if($from == "") {
							$from = date("d-m-Y" ,time() + (3600 * 14));
						}
						
						if($to == "") {
							$to = date("d-m-Y" ,time() + (3600 * 14));
						}
					?>
						<form name="searchform">
						<table border="0" cellpadding="0" cellspacing="0" valign="bottom" width="1000">
							<tr><td colspan="4" background="images/bg_check02.gif" height="3"></td></tr>
							<tr>
								<td height="30"  style="padding-left:5px; width:200px;" class="ui-widget-header">				
								Period
								</td>
								<td style="padding-left:5px" class="ui-widget-content" colspan="3">
								From : 
								<input type="text" name="from" id="from" size="10" value="<?php echo $from;?>" />
								To : 
								<input type="text" name="to" id="to" size="10" value="<?php echo $to;?>" />
								
								</td>
							</tr>
							<tr><td colspan="4" background="images/bg_check02.gif" height="3"></td></tr>
							<tr>
								<td height="30"  style="padding-left:5px; width:200px;" class="ui-widget-header">				
								Type
								</td>
								<td style="padding-left:5px" class="ui-widget-content" colspan="3">
								<input type="radio" name="report_type" value="monthly" >Monthly
								<input type="radio" name="report_type" value="weekly" >Weekly
								<input type="radio" name="report_type" value="oneday" >Daily						
								</td>
							</tr>
							<tr><td colspan="4" background="images/bg_check02.gif" height="3"></td></tr>
							<tr>
								<td height="30"  style="padding-left:5px" class="ui-widget-header">				
								Project
								</td>
								<td class="ui-widget-content" >
								<?php getSelectOption('project','',' project_name ',NULL," id='project_id' ",NULL,'300');?>
								</td>
								<td class="ui-widget-header" style="padding-left:5px" width="200">Employee</td>
								<td  class="ui-widget-content">								
								<?php getSelectOption('employee',' '," CONCAT_WS(', ',last_name, first_name )  ", 'id', " id='employee_id' ",0, "300");?>
								</td>
							</tr>
							<tr><td colspan="4" background="images/bg_check02.gif" height="3"></td></tr>
							<tr><td colspan="4" align="right" height="40"><input type="button" Value="Generate" onclick="o_searchNow()"></td></tr>
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
<?php ob_flush(); ?>