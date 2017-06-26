<?php
include_once "include/logincheck.inc";
include_once "include/common.inc";
include_once "include/dbconn.inc";
include_once "include/myfunc.inc";
include_once "htmlinclude/head.php";

$action_type = $_REQUEST["action_type"];
$limitList = $_REQUEST["limitList"];
$list_view = $_REQUEST["list_view"];
$s_date = $_REQUEST['s_date'];
$s_resort_order = $_REQUEST["resort_order"];
$srch_param = "";
if($s_resort_order != ""){
	$srch_param .= "&resort_order=$s_resort_order";
}

if($limitList) {
	$srch_param .="&limitList=$limitList";
}
					
if($list_view) {
	$srch_param .="&list_view=$list_view";
}

if ($s_date) {
	$srch_param .="&s_date=".$s_date;
}
					
							
$id = $_REQUEST["id"];
/*
if($action_type=="modify") {
	## 쿼리, 담을 배열 선언
	$list_Records = array();
	
	$Query  = "SELECT * ";
	$Query .= " FROM job WHERE id='$id'";

	$id_cnn = mysql_query($Query) or exit(mysql_error());
	while($id_rst = mysql_fetch_assoc($id_cnn)) {
		$list_Records = array_merge($list_Records, array($id_rst));
		//print_r($list_Records);
		//echo "<p>";
	}
} 

else if($action_type=="delete") {
	$req_id = $_REQUEST["id"];
	
	$sql = "DELETE FROM job WHERE id='" .$req_id."'";
	pQuery($sql, "delete");
	echo "<script>alert('Deleted!');location.href='job_list.php?$srch_param';</script>";
	exit;
}
*/
?>

<script language="Javascript">
function formchk() {
	frm = document.frm1;
			
	if (!$('#job_date').val()) {
		alert("Please insert job date.");
		$('#job_date').focus();
		return;
	}	
		
	if (!$('#project_id').val()) {
		alert("Please select project.");
		$('#project_name').focus();
		return;
	}
	
	if (!$('#employee_id').val()) {
		alert("Please select employee");
		$('#employee_id').focus();
		return;
	}	
		
	if (( !$('select[name=job_session_rates]').val() && !$('#job_session_rates_txt').val())) {
			
		if ($('select[name=job_extra_hour]').val()) {
			if (!$('select[name=job_extra_hour_rates]').val() && !$('#job_extra_hour_rates_txt').val()) {
				alert("Please select extra hour rates");
				$('select[name=job_extra_hour_rates]').focus();
				return;
			}
		} else {
			alert("Please select job session or extra hour");
			$('select[name=job_session_rates]').focus();
			return;
		}
			
	} else {
		if (!$('select[name=job_session_rates]').val() && !$('#job_session_rates_txt').val()) {
			alert("Please select job session rates");
			$('select[name=job_session_rates]').focus();
			return;
		}
	}
	
	if($("#multi2_table").is(':visible')) {
		if (!$('#job_date2').val()) {
			alert("Please insert job date.");
			$('#job_date2').focus();
			return;
		}	
			
		if (!$('#project_id2').val()) {
			alert("Please select project.");
			$('#project_name2').focus();
			return;
		}
		
		if (!$('#employee_id2').val()) {
			alert("Please select employee");
			$('#employee_id2').focus();
			return;
		}	
			
		if (( !$('select[name=job_session_rates2]').val() && !$('#job_session_rates_txt2').val())) {
				
			if ($('select[name=job_extra_hour2]').val()) {
				if (!$('select[name=job_extra_hour_rates2]').val() && !$('#job_extra_hour_rates_txt2').val()) {
					alert("Please select extra hour rates");
					$('select[name=job_extra_hour_rates2]').focus();
					return;
				}
			} else {
				alert("Please select job session or extra hour");
				$('select[name=job_session_rates2]').focus();
				return;
			}
				
		} else {
			if (!$('select[name=job_session_rates2]').val() && !$('#job_session_rates_txt2').val()) {
				alert("Please select job session rates");
				$('select[name=job_session_rates2]').focus();
				return;
			}
		}
	}else {
		
	}
	
	frm.submit();
	
}

$(function() {

	$("#job_date").datepicker($.datepicker.regional['en-GB']);
	$("#job_date").datepicker( "option", "firstDay", 1 );
	$("#job_date").datepicker();
	
	$("#job_date2").datepicker($.datepicker.regional['en-GB']);
	$("#job_date2").datepicker( "option", "firstDay", 1 );
	$("#job_date2").datepicker();
	
	$( "input:button, button").button();
	
	$("#multi2_table").hide();
	
	$(".multi_add").click(function() {
		if($("#multi2_table").is(':hidden')) {
			$("#multi2_table").show();
		} else {
			$("#multi2_table").hide();
			$('#job_date2').val('');
			$('#project_id2').val('');
			$('#employee_id2').val('');
			$('select[name=job_session_rates2]').val('');
			$('#job_session_rates_txt2').val('');
			$('select[name=job_extra_hour2]').val('');
			$('select[name=job_extra_hour_rates2]').val('');
			$('#job_extra_hour_rates_txt2').val('');
			$('#job_session_rates_txt2').val('');
			$('#remarks2').val('');
		}
		return false;
	});
	
	$(".multiselect").multiselect({selectedList: 4,height:350}).multiselectfilter();
	
	$("#project_name").autocomplete({
    	source: "autocomplete_project.php",
    	minLength: 2,
    	select: function(event,ui) {
    		$('#project_id').val(ui.item.id);
			$('#project_name').val(ui.item.value);
    	}
    });
    
    $("#project_name2").autocomplete({
    	source: "autocomplete_project.php",
    	minLength: 2,
    	select: function(event,ui) {
    		$('#project_id2').val(ui.item.id);
			$('#project_name2').val(ui.item.value);
    	}
    });
    
	$("#today").click(function() {
		var fullDate = new Date();
		var twoDigitDays = twodigits(fullDate.getDate());
		var twoDigitMonth = twodigits(fullDate.getMonth() + 1); 
		var currentDate = twoDigitDays + "-" + twoDigitMonth + "-" + fullDate.getFullYear();
		$("#job_date").val(currentDate);
		return;
	});
	
	$("#tomorrow").click(function() {
		var fullDate = new Date();
		fullDate.setDate(fullDate.getDate() + 1);
		var twoDigitDays = twodigits(fullDate.getDate());
		var twoDigitMonth = twodigits(fullDate.getMonth() + 1); 
		var currentDate = twoDigitDays + "-" + twoDigitMonth + "-" + fullDate.getFullYear();
		$("#job_date").val(currentDate);
		return;
	});
	
	$("#next_monday").click(function() {
		var fullDate = new Date();
		fullDate.setDate(fullDate.getDate() + (-1 + 7 - fullDate.getDay()) + 1);
		var twoDigitDays = twodigits(fullDate.getDate()+1);
		var twoDigitMonth = twodigits(fullDate.getMonth() + 1); 
		var currentDate = twoDigitDays + "-" + twoDigitMonth + "-" + fullDate.getFullYear();
		$("#job_date").val(currentDate);
		return;
	});
	
	$("#today2").click(function() {
		var fullDate = new Date();
		var twoDigitDays = twodigits(fullDate.getDate());
		var twoDigitMonth = twodigits(fullDate.getMonth() + 1); 
		var currentDate = twoDigitDays + "-" + twoDigitMonth + "-" + fullDate.getFullYear();
		$("#job_date2").val(currentDate);
		return;
	});
	
	$("#tomorrow2").click(function() {
		var fullDate = new Date();
		fullDate.setDate(fullDate.getDate() + 1);
		var twoDigitDays = twodigits(fullDate.getDate());
		var twoDigitMonth = twodigits(fullDate.getMonth() + 1); 
		var currentDate = twoDigitDays + "-" + twoDigitMonth + "-" + fullDate.getFullYear();
		$("#job_date2").val(currentDate);
		return;
	});
	
	$("#next_monday2").click(function() {
		var fullDate = new Date();
		fullDate.setDate(fullDate.getDate() + (-1 + 7 - fullDate.getDay()) + 1);
		var twoDigitDays = twodigits(fullDate.getDate()+1);
		var twoDigitMonth = twodigits(fullDate.getMonth() + 1); 
		var currentDate = twoDigitDays + "-" + twoDigitMonth + "-" + fullDate.getFullYear();
		$("#job_date2").val(currentDate);
		return;
	});
	
	// time checkbox
	$('.time').change(function(){
		var id = $(this).attr('id');
		
		if ($(this).is(':checked')) {
			if( id == 'time1_1') {
				$('#time1_2').attr('checked',false);
				$('#time1_3').attr('checked',false);
			} else if ( id == 'time1_2') {
				$('#time1_1').attr('checked',false);
				$('#time1_3').attr('checked',false);
			} else if ( id == 'time1_3') {
				$('#time1_1').attr('checked',false);
				$('#time1_2').attr('checked',false);
			}
		}	
		
	});
	
	$('.time2').change(function(){
		var id = $(this).attr('id');
		
		if ($(this).is(':checked')) {
			if( id == 'time2_1') {
				$('#time2_2').attr('checked',false);
				$('#time2_3').attr('checked',false);
			} else if ( id == 'time2_2') {
				$('#time2_1').attr('checked',false);
				$('#time2_3').attr('checked',false);
			} else if ( id == 'time2_3') {
				$('#time2_1').attr('checked',false);
				$('#time2_2').attr('checked',false);
			}
		}	
	});
	
	
	$("#job_generate_id").click(function() { 
		if (!$('#job_date').val()) {
			alert("Please insert job date.");
			$('#job_date').focus();
			return;
		}
		$("#job_generate_id").next().append($('.processing').show()).fadeIn(500);
		
		$.post("autogen_job_id.php", {
			job_date: $('#job_date').val()
		}, function(data) { 
			if (data != 'Error!') {
				$('#job_id').val(data);
			} else {
				alert('Please insert manually');
			}
			
		});
		
		$("#job_generate_id").next().append($('.processing')).fadeOut(800);
	});
	
	function twodigits(digits) {    return (digits > 9) ? digits : '0' + digits;}	
});

	
</script>
<BODY leftmargin=0 topmargin=0>
<div class="processing" style="display:none">
	<img src="images/ajax-loader.gif" alt="loading" style="width:18px;vertical-align:middle;margin-left:10px;" /> PROCESSING
</div>
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
						<tr><td width="252"><img src="images/icon_circle03.gif">&nbsp;<span style="height:21px">Job Order Register</span> <button class="multi_add"><span class="ui-icon ui-icon-plusthick"></span></button></td></tr>
						<tr><td height="3"></td></tr>
						<tr><td background="images/bg_check.gif" height="3"></td></tr>
						<tr><td height="2"></td></tr>
					</table>
					</td>
				</tr>
				<form name="frm1" method="post" action="job_regist_ok.php?<?php echo $srch_param;?>">
				<input type="hidden" name="action_type" value="<?=$action_type?>">
				<tr>
					<td valign="top">
						
						
						<div id="multi1">
						<table border="0" cellpadding="0" cellspacing="0" width="500" >
						<tr >
							<td width="100" height="30"  class="ui-widget-header left" >* Date</td>
							<td width="400" class='ui-widget-content left' >
							<input type="text" name="job_date" id="job_date" size="10" value="<?php echo getAUDate($list_Records[0]['job_date']);?>" /><input type="button" value="today" id="today" >&nbsp;<input type="button" value="tomorrow" id="tomorrow" >&nbsp;<input type="button" value="Next monday" id="next_monday" >  </td>	
						</tr>
						<input type="hidden" name="id" value="<?php echo $list_Records[0]['id']?>">
						<tr>
							<td width="100" height="30" class="ui-widget-header left">* Type</td>
							<Td width="400" class="ui-widget-content left">
								<?php echo DrawFromDB('job','job_type','select','',"yes ",""," width='120px' style='width:120px;' ");?>
							</Td>
						</tr>
						<!--
						<tr >
							<td style="padding-left:3px" align="left"  height="30"  class="ui-widget-header" >* Job Id</td>
							<td style="padding-left:3px" class='ui-widget-content' >
							<input type="text" size="63" id="job_id" name="job_id" <?if($action_type=="modify") echo "readonly " ?> value="<?=$list_Records[0]["job_id"]?>"><? if($action_type!="modify") { ?> <input type="button" value="Generate" id="job_generate_id"> <?}?>&nbsp;
							</td>	
							
						</tr>
						-->
						<tr>
							<td height="30"  class="ui-widget-header left" >* Project</td>
							<td class='ui-widget-content left' >
							
							<input type="text" id='project_name' name="project_name" style="background-color:transparent;width:395px;"  />
							<input type="hidden" id="project_id" name="project_id" value="<?php echo $list_Records[0]['project_id'];?>" />
						
							</td>	
						</tr>
						<tr>
							<td height="30" class="ui-widget-header left"  >* Employee</td>
							<td class='ui-widget-content left' >
							<select id="employee_id" class="multiselect" multiple="multiple" name="employee_id[]" style="width:400px;">
							<?php
							$Query = "";
							$Query  = "SELECT e.id, CONCAT_WS(', ',e.last_name, e.first_name ) as employee_name, et.employee_team_name ";
							$Query .= " FROM employee e LEFT JOIN employee_team et ON et.employee_id = e.id " .
							" WHERE et.employee_team_id IS NOT NULL ORDER BY et.employee_team_name ";
							$id_cnn = mysql_query($Query) or exit(mysql_error());
							
							$team_name = "";	
							while($id_rst = mysql_fetch_assoc($id_cnn)) {
								if ($team_name != $id_rst['employee_team_name']) {
									echo "</optgroup>";
									echo "<optgroup label='".$id_rst['employee_team_name']."'>";
									$team_name = $id_rst['employee_team_name'];
								}
									
							?>
							<option value="<?=$id_rst['id']?>"><?=$id_rst['employee_name']?></option>
							<?php
							}	
																					
							$Query = "";
							$Query  = "SELECT e.id, CONCAT_WS(', ',e.last_name, e.first_name ) as employee_name ";
							$Query .= " FROM employee e LEFT JOIN employee_team et ON et.employee_id = e.id " .
							" WHERE et.employee_team_id IS NULL ORDER BY CONCAT_WS(', ',last_name, first_name ) ";
							$id_cnn = mysql_query($Query) or exit(mysql_error());
							echo "<optgroup label='NONE'>";	
							while($id_rst = mysql_fetch_assoc($id_cnn)) {
							?>
							<option value="<?=$id_rst['id']?>"><?=$id_rst['employee_name']?></option>
							<?php
							}	
							echo "</optgroup>";
							?>
							
							</select>
							</td>	
						</tr>
						<tr>
							<td class='ui-widget-header left' height="30" > </td>
							<td class='ui-widget-content' style="padding-left:3px">
							<input type="checkbox" name="time" value="AM" id="time1_1" class="time">AM
							<input type="checkbox" name="time" value="PM" id="time1_2" class="time">PM
							<input type="checkbox" name="time" value="NI" id="time1_3" class="time">NIGHT
							</td>	
						</tr>
						<tr >
							<td class='ui-widget-header left' >* Session or Extra Hour</td>
							<td class='ui-widget-content left' >
							<?php echo DrawFromDB('job','job_session','select','',"yes ",""," width='120' style='width:120px;' ");?>
							<?php getCustomizeOption("rates", "job_session_rates", "","","Rates");?>
							<em style='padding-left:2px;'>or</em>
							<input type="text" name="job_session_rates_txt" id="job_session_rates_txt" value="" style="width:40px;">
							<br />
							<em style='padding-left:100px;'>or</em><br />
							Extra hours&nbsp;<?php getCustomizeOption("", "job_extra_hour", "", 10,"Hour");?> 
							<?php getCustomizeOption("rates", "job_extra_hour_rates", "","","Rates");?>
							<em style='padding-left:2px;'>or</em>
							<input type="text" name="job_extra_hour_rates_txt" id="job_extra_hour_rates_txt" value="" style="width:40px;">
							</td>	
						</tr>
						

						<tr>
							<td height="30" class="ui-widget-header left" > Remarks</td>
							<td class='ui-widget-content left' ><textarea name="remarks" rows="4" cols="47"><?=$list_Records[0]["remarks"]?></textarea> </td>	
						</tr>
						</table>
						</div>
						<div id="multi2">
						<table border="0" cellpadding="0" cellspacing="0" width="500" id="multi2_table">
						<tr >
							<td width="100" height="30"  class="ui-widget-header left" >* Date</td>
							<td width="400" class='ui-widget-content left' >
							<input type="text" name="job_date2" id="job_date2" size="10" /><input type="button" value="today" id="today2" >&nbsp;<input type="button" value="tomorrow" id="tomorrow2" >&nbsp;<input type="button" value="Next monday" id="next_monday2" >  </td>	
						</tr>
						<tr>
							<td width="100" height="30" class="ui-widget-header left">* Type</td>
							<Td width="400" class="ui-widget-content left">
							<?php echo DrawFromDB('job','job_type','select','',"yes ",""," width='120px' style='width:120px;'", "job_type2");?>
							</Td>
						</tr>
						
						<tr>
							<td height="30"  class="ui-widget-header left" >* Project</td>
							<td class='ui-widget-content left' >
							
							<input type="text" id='project_name2' name="project_name2" style="background-color:transparent;width:395px;"  />
							<input type="hidden" id="project_id2" name="project_id2" />
						
							</td>	
						</tr>
						<tr>
							<td height="30" class="ui-widget-header left"  >* Employee</td>
							<td class='ui-widget-content left' >
							<select id="employee_id2" class="multiselect" multiple="multiple" name="employee_id2[]" style="width:400px;">
							<?php
							$Query = "";
							$Query  = "SELECT e.id, CONCAT_WS(', ',e.last_name, e.first_name ) as employee_name, et.employee_team_name ";
							$Query .= " FROM employee e LEFT JOIN employee_team et ON et.employee_id = e.id " .
							" WHERE et.employee_team_id IS NOT NULL ORDER BY et.employee_team_name ";
							$id_cnn = mysql_query($Query) or exit(mysql_error());
							
							$team_name = "";	
							while($id_rst = mysql_fetch_assoc($id_cnn)) {
								if ($team_name != $id_rst['employee_team_name']) {
									echo "</optgroup>";
									echo "<optgroup label='".$id_rst['employee_team_name']."'>";
									$team_name = $id_rst['employee_team_name'];
								}
									
							?>
							<option value="<?=$id_rst['id']?>"><?=$id_rst['employee_name']?></option>
							<?php
							}	
																					
							$Query = "";
							$Query  = "SELECT e.id, CONCAT_WS(', ',e.last_name, e.first_name ) as employee_name ";
							$Query .= " FROM employee e LEFT JOIN employee_team et ON et.employee_id = e.id " .
							" WHERE et.employee_team_id IS NULL ORDER BY CONCAT_WS(', ',last_name, first_name ) ";
							$id_cnn = mysql_query($Query) or exit(mysql_error());
							echo "<optgroup label='NONE'>";	
							while($id_rst = mysql_fetch_assoc($id_cnn)) {
							?>
							<option value="<?=$id_rst['id']?>"><?=$id_rst['employee_name']?></option>
							<?php
							}	
							echo "</optgroup>";
							?>
							
							</select>
							</td>	
						</tr>
						<tr>
							<td class='ui-widget-header left' height="30" > </td>
							<td class='ui-widget-content' style="padding-left:3px">
							<input type="checkbox" name="time2" value="AM" id="time2_1" class="time2">AM
							<input type="checkbox" name="time2" value="PM" id="time2_2" class="time2">PM
							<input type="checkbox" name="time2" value="NI" id="time2_3" class="time2">NIGHT
							</td>	
						</tr>
						<tr >
							<td class='ui-widget-header left' >* Session or Extra Hour</td>
							<td class='ui-widget-content left' >
							<?php echo DrawFromDB('job','job_session','select','',"yes ",""," width='120' style='width:120px;' ","job_session2");?>
							<?php getCustomizeOption("rates", "job_session_rates2", "","","Rates");?>
							<em style='padding-left:2px;'>or</em>
							<input type="text" name="job_session_rates_txt2" id="job_session_rates_txt2" value="" style="width:40px;">
							<br />
							<em style='padding-left:100px;'>or</em><br />
							Extra hours&nbsp;<?php getCustomizeOption("", "job_extra_hour2", "", 10,"Hour");?> 
							<?php getCustomizeOption("rates", "job_extra_hour_rates2", "","","Rates");?>
							<em style='padding-left:2px;'>or</em>
							<input type="text" name="job_extra_hour_rates_txt2" id="job_extra_hour_rates_txt2" value="" style="width:40px;">
							</td>	
						</tr>
				
						<tr>
							<td height="30" class="ui-widget-header left" > Remarks</td>
							<td class='ui-widget-content left' ><textarea name="remarks2" rows="4" cols="47"></textarea> </td>	
						</tr>
						</table>	
						</div>	
						<?php if($action_type) { ?>
						<table border="0" cellpadding="0" cellspacing="0" >
						<tr >
							<td class="right"  colspan="2">Inserted by&nbsp;<?php echo getValue('account', 'username', ' AND userid="'.$list_Records[0]['account_id'].'"')?>&nbsp;<?php echo getAUDate($list_Records[0]["regdate"],1)?>
							<?php 
							$Query  = "SELECT * ";
							$Query .= " FROM history WHERE history_table='job' AND history_table_id ='". $list_Records[0]['id'] ."' ";
							//echo $Query;
							
							$result = mysql_query($Query) or exit(mysql_error());
							
							if ($result) {
								echo "<br />Updated by ";
								while ($row = mysql_fetch_assoc($result)) {
									echo getValue('account','username',' AND userid="'.$row['account_id'].'" '). " ". getAUDate($row["regdate"],1) ."<br />";
								}
							}
							?>
							
							</td>	
						</tr>
						</table>
						<?php }?>
								
						<br>
						<table border="0" cellpadding="0" cellspacing="0" width="1000">
						<tr>
							<td class="right">Required fields are marked with an asterisk (*).<br><input type="button" value="Save" onclick="formchk(); return false;"> 
							</td>
						</tr>
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
	<?php include_once "bottom.php"; ?>
	<!-- BOTTOM END -------------------------------------------------------------------------------------------->
	</td>
</tr>
</table>
</BODY>
</HTML>