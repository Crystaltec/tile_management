<?php
ob_start();
include_once "include/logincheck.inc";
include_once "include/common.inc";
include_once "include/dbconn.inc";
include_once "include/myfunc.inc";
include_once "htmlinclude/head.php";
?>
<script language="Javascript">
function formchk() {
	frm = document.frm1;
	
	if(frm.employee_team_name.value =="") {
		alert("Please, Insert name!");
		frm.employee_team_name.focus();
		return;
	}

	if(confirm("Press OK to confirm?")) {
		frm.submit();
	}
}

$(function() {
	$("input:button, button").button();
	$('.ui-widget-content').css({'background-image' :'none','background-color':'none'});
	
	$(".multiselect").multiselect().multiselectfilter();
			
	$('#search_employee').click(function(){
		if (!$('input[name=search_employee]').val()) {
			alert("Please type search value.");
			$('input[name=search_employee]').focus();
			return;
		}
		
		$('#search_employee').next().append($('.processing').show()).fadeIn(500);
		
		$.post("search_employee.php",{
			q: $('input[name=search_employee]').val()
		}, function(data){
			if(data) {
				$('#project_id').html(data);
			} else {
				alert('Please select manually');
			}
		});
		
	$('#search_employee').next().append($('.processing')).fadeOut(800);
    });
});
</script>
<?php

$employee_team_id = $_REQUEST["employee_team_id"];
$action_type = $_REQUEST["action_type"];

if($action_type=="delete") {
	$sql = "DELETE FROM employee_team WHERE employee_team_id=".$employee_team_id;
	//echo $sql;
	pQuery($sql,"delete");
}

## ĵ��, �〻 �迭 ����
$list_Records = array();

$Query  = "SELECT * ";
$Query .= " FROM employee_team WHERE employee_team_id='". $employee_team_id ."'";

//echo $Query;

$id_cnn = mysql_query($Query) or exit(mysql_error());
while($id_rst = mysql_fetch_assoc($id_cnn)) {
	$list_Records = array_merge($list_Records, array($id_rst));
	//print_r($list_Records);
	//echo "<p>";
}
?>
<BODY leftmargin=0 topmargin=0>
<div class="processing" style="display:none">
	<img src="images/ajax-loader.gif" alt="loading" style="width:18px;vertical-align:middle;margin-left:10px;" /> PROCESSING
</div>
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
				<table border="0" cellpadding="0" cellspacing="0" width="1000">
				<tr>
					<td  valign="top">
					<table border="0" cellpadding="0" cellspacing="0" width="1000" class="font11_bold">
						<tr><td height="8"></td></tr>
						<tr><td width="252"><img src="images/icon_circle03.gif">&nbsp;<span style="height:21px">Team Register</span></td></tr>
						<tr><td height="3"></td></tr>
						<tr><td background="images/bg_check.gif" height="3"></td></tr>
						<tr><td height="2"></td></tr>
					</table>
					</td>
				</tr>

				<form name="frm1" method="post" action="team_regist_ok.php">
				<tr>
					<td valign="top">
						<table border="0" cellpadding="0" cellspacing="0"width="1000">
							
						<tr >
							<td style="padding-left:3px;width:200px;"  align="left" class="ui-widget-header" >*Name</td>
							<td style="padding-left:3px" class="ui-widget-content"><input type="text" size="60" name="employee_team_name" value="<?=$list_Records[0]["employee_team_name"]?>"></td>	
						</tr>
						<tr >
							<td style="padding-left:3px" align="left" class="ui-widget-header" height="30" >*Employee</td>
							<td style="padding-left:3px" class="ui-widget-content">
													
							<select id="employee_id" class="multiselect" multiple="multiple" name="employee_id[]" style="width:500px;">
							<?php
							
							$Query = "";
							$Query  = "SELECT e.id, CONCAT_WS(', ',e.last_name, e.first_name ) as employee_name ";
							$Query .= " FROM employee e LEFT JOIN employee_team et ON et.employee_id = e.id " .
							" WHERE et.employee_team_id IS NULL ORDER BY CONCAT_WS(', ',last_name, first_name ) ";
							$id_cnn = mysql_query($Query) or exit(mysql_error());
								
							while($id_rst = mysql_fetch_assoc($id_cnn)) {
							?>
							<option value="<?=$id_rst['id']?>"><?=$id_rst['employee_name']?></option>
							<?php
							}	
							?>
							</select>
							</td>	
						</tr>

						<tr >
							<td style="padding-left:3px" align="left" class="ui-widget-header" >Remarks</td>
							<td style="padding-left:3px" class="ui-widget-content"><textarea name="remarks" rows="5" cols="94"><?=$list_Records[0]["remarks"]?></textarea></td>	
						</tr>
						

						<input type="hidden" name="action_type" value="<?=$action_type?>"><input type="hidden" name="employee_team_id" value="<?=$employee_team_id?>">					
						</table>		
						<br>
						<table border="0" cellpadding="0" cellspacing="0" width="1000">
						<tr><td align="right">Required fields are marked with an asterisk (*).<br><input type="button" value="Save" onclick="formchk();"></td></tr>
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
<? ob_flush(); ?>