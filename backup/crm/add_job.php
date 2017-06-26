<?php
include_once "include/logincheck.inc";
include_once "include/common.inc";
include_once "include/dbconn.inc";
include_once "include/myfunc.inc";

$class_name = '#p_' . $_REQUEST['id'].'.'.$_REQUEST['re_date'];
$re_list_view = $_REQUEST['re_list_view'];

/* have vehicle
 show use vehicle

$sql = " SELECT vehicle FROM employee WHERE id = '".$_REQUEST['id']."'  ";
$_v = getRowCount2($sql) ;
 * 
 */
 ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<title>Job</title>
<link rel="stylesheet" type="text/css" href="include/default.css" />
<link rel="stylesheet" type="text/css" href="css/jquery.ui.selectmenu.css" />
<link rel="stylesheet" type="text/css" href="css/jquery.ui.autocomplete.css" />
<link rel="stylesheet" type="text/css" href="css/jquery.ui.all.css" />
<script type="text/javascript" src="js/jquery.min.js"></script>
<script type="text/javascript" src="js/jquery.bgiframe-2.1.2.js"></script>
<script type="text/javascript" src="js/jquery.ui.core.js"></script>
<script type="text/javascript" src="js/jquery.ui.widget.js"></script>
<script type="text/javascript" src="js/jquery.ui.datepicker.js"></script>
<script type="text/javascript" src="js/jquery.ui.datepicker-en-GB.js"></script>
<script type="text/javascript" src="js/jquery.ui.mouse.js"></script>
<script type="text/javascript" src="js/jquery.ui.draggable.js"></script>
<script type="text/javascript" src="js/jquery.ui.position.js"></script>
<script type="text/javascript" src="js/jquery.ui.resizable.js"></script>
<script type="text/javascript" src="js/jquery.ui.dialog.js"></script>
<script type="text/javascript" src="js/jquery.ui.button.js"></script>
<script type="text/javascript" src="js/jquery.ui.selectmenu.js"></script>
<script type="text/javascript" src="js/jquery.effects.core.js"></script>
<script type="text/javascript" src="js/jquery.ui.autocomplete.js"></script>
</head>
<script type="text/javascript" language="javascript">
$(document).ready(function() {
	$(window.opener.document).find('body').attr('disabled','disabled');
	
	$( "input:button, button").button();
	
	$('#processing').hide();
		
	$("#job_date").datepicker($.datepicker.regional['en-GB']);
	$("#job_date").datepicker( "option", "firstDay", 1 );
	$("#job_date").datepicker();
	
	$("#project_name").autocomplete({
    	source: "autocomplete_project.php",
    	minLength: 2,
    	select: function(event,ui) {
    		$('#project_id').val(ui.item.id);
			$('#project_name').val(ui.item.value);
    	}
    });
    	
    	
	$('#search_project').click(function(){
		if (!$('input[name=search_project]').val()) {
			alert("Please type search value.");
			$('input[name=search_project]').focus();
			return;
		}
		
		$('#processing').fadeIn(500);
		
		$.post("search_project.php",{
			q: $('input[name=search_project]').val()
		}, function(data){
			if(data) {
				$('select[name=project_id]').html(data);
			} else {
				alert('Please select manually');
			}
		});
		
		$('#processing').fadeOut(800);
    });
	
	$('#search_employee').click(function(){
		if (!$('input[name=search_employee]').val()) {
			alert("Please type search value.");
			$('input[name=search_employee]').focus();
			return;
		}
		
		$('#processing').fadeIn(500);
		
		$.post("search_employee.php",{
			q: $('input[name=search_employee]').val()
		}, function(data){
			if(data) {
				$('#employee_id').html(data);
			} else {
				alert('Please select manually');
			}
		});
		
		$('#processing').fadeOut(800);
    });

	$("#job_generate_id").click(function() { 
		if (!$('#job_date').val()) {
			alert("Please insert job date.");
			$('#job_date').focus();
			return;
		}
		$('#processing').fadeIn(500);
		
		$.post("autogen_job_id.php", {
			job_date: $('#job_date').val()
		}, function(data) { 
			if (data != 'Error!') {
				$('#job_id').val(data);
			} else {
				alert('Please insert manually');
			}
		});
		
		$('#processing').fadeOut(800);
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
	
	function twodigits(digits) {    return (digits > 9) ? digits : '0' + digits;}	
	
	
	// time checkbox
	$('.time').change(function(){
		var id = $(this).attr('id');
		
		if ($(this).is(':checked')) {
			if( id == 'time1') {
				$('#time2').attr('checked',false);
				$('#time3').attr('checked',false);
			} else if ( id == 'time2') {
				$('#time1').attr('checked',false);
				$('#time3').attr('checked',false);
			} else if ( id == 'time3') {
				$('#time1').attr('checked',false);
				$('#time2').attr('checked',false);
			}
		}	
		
	});

		
	$("#add").click(function() {
		if (!$('#job_date').val()) {
			alert("Please insert job date.");
			$('#job_date').focus();
			return;
		}	
		
		if (!$('#project_id').val()) {
			alert("Please select project.");
			$('#project_id').focus();
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
		
		$('#processing').fadeIn(500);
		
		$.post("add_job_ok.php", {
			action_type : "insert",
			job_date: $('#job_date').val(),
			job_id : $('input[name=job_id]').val(),
			project_id : $('#project_id').val(),
			employee_id : $('#employee_id').val(),
			job_type :$('select[name=job_type]').val(),
			job_session : $('select[name=job_session]').val(),
			job_session_rates : $('select[name=job_session_rates]').val(),
			job_extra_hour : $('select[name=job_extra_hour]').val(),
			job_extra_hour_rates : $('select[name=job_extra_hour_rates]').val(),
			job_session_rates_txt : $('#job_session_rates_txt').val(),
			job_extra_hour_rates_txt : $('#job_extra_hour_rates_txt').val(),
			attendance : $('select[name=attendance]').val(),
			travel_fee : $('#travel_fee').val(),
			parking_fee : $('#parking_fee').val(),
			tool_fee : $('#tool_fee').val(),
			extra_fee : $('#extra_fee').val(),
			time : $('.time:checked').val(),
			<?php
				/*if($_v == 'Y') {
					echo "use_vehicle:$('select[name=use_vehicle]').val(),";
				} */
			?>
			remarks : $('textarea[name=remarks]').val()
		}, function(data) { 
			if (data != 'ERROR') {
				
				p_refresh("<?php echo $re_list_view;?>");
				// Close the window
				window.close();
			} else {
				alert('Please try again');
			}
		});
		
		$('#processing').fadeOut(800);
		
		return false;
	});
	
});

$(window).unload(function() {
	window.opener.document.body.disabled=false;
});

function p_refresh(p)
{
	var f = p;
       window.opener.location.reload();
       return false;
}

</script>
<body>
<form name="job_frm" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
<div id="processing" style="display:inline">
	<img src="images/ajax-loader.gif" alt="loading" style="width:18px;vertical-align:middle;margin-left:10px;" /> PROCESSING
</div>
<input type="hidden" name="list_view" value="<?php echo $_REQUEST['re_list_view']?>">
<input type="hidden" name="action_type" value="<?=$action_type?>">
<table border="0" cellpadding="0" cellspacing="0" width="500">
<tr>
	<td class='ui-widget-header left' width="80" height="30" >* Date</td>
	<td class='ui-widget-content left' >
	<input type="text" name="job_date" id="job_date" size="10" value="<?php echo getAUDate($_REQUEST['re_date']);?>" /> <input type="button" value="today" id="today" >&nbsp;<input type="button" value="tomorrow" id="tomorrow" >&nbsp;<input type="button" value="Next monday" id="next_monday" ></td>
</tr>
<input type="hidden" name="id" value="<?php echo $_REQUEST['id'];?>">
<tr>
	<td class="ui-widget-header left">*Type</td>
	<Td class="ui-widget-content left">
	<?php echo DrawFromDB('job','job_type','select','',"yes ",""," width='120px' style='width:120px;' ");?>
	</Td>
</tr>
<?php if(0) { ?>
<tr>
	<td class='ui-widget-header left' height="30">* Job Id</td>
	<td class='ui-widget-content left' >
	<input type="text" size="20" id="job_id" name="job_id" <?if($action_type=="modify") echo "readonly " ?> value="<?=$list_Records[0]["job_id"]?>"><? if($action_type!="modify") { ?> <input type="button" value="Generate" id="job_generate_id"> <?}?>
	</td>	
</tr>
<?php } ?>
<tr>
	<td class='ui-widget-header left' height="30">* Project</td>
	<td class='ui-widget-content' style="padding-left:3px">
	<?php 
	if ($re_list_view == 'p') { ?>
	<?php getSelectOption('project',$_REQUEST['id'],' project_name ',NULL," id='project_id' ",NULL,'400', " AND project_status <> 'COMPLETED' ");?>
	<?php } else { ?>
	<input type="text" id='project_name' name="project_name" style="background-color:transparent;width:350px;"  />
	<input type="hidden" id="project_id" name="project_id" />
	<?php } ?>
	</td>	
</tr>
<tr>
	<td class='ui-widget-header left' height="30" >* Employee</td>
	<td class='ui-widget-content' style="padding-left:3px">
	<?php if ($action_type == "") { ?>
	<input type="text" name="search_employee" size='20'>&nbsp;<input type="button" value="Search" id="search_employee"><span></span><br />
	<?php }?>
	<?php getSelectOption('employee',$_REQUEST['id']," CONCAT_WS(', ',last_name, first_name )  ", 'id', " id='employee_id' ",0, "400");?>
	</td>	
</tr>
<tr>
	<td class='ui-widget-header left' height="30" > </td>
	<td class='ui-widget-content' style="padding-left:3px">
	<input type="checkbox" name="time" value="AM" id="time1" class="time">AM
	<input type="checkbox" name="time" value="PM" id="time2" class="time">PM
	<input type="checkbox" name="time" value="NI" id="time3" class="time">NIGHT
	</td>	
</tr>
<tr>
	<td class='ui-widget-header left' >* Session</td>
	<td class='ui-widget-content left' >
	<?php echo DrawFromDB('job','job_session','select','',"yes ",""," width='120px' style='width:120px;' ");?>
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
	<td class='ui-widget-header' style="padding-left:3px" align="left" >* Attendance</td>
	<td class='ui-widget-content' >
	<?php echo DrawFromDB("job","attendance","select","","",""," width='120' style='width:120px;' ");?>
	P: Present, A: Absence
	</td>	
</tr>
<tr>
	<td class='ui-widget-header left' > Travel fee</td>
	<td class='ui-widget-content' >
	<input type="text" name="travel_fee" id="travel_fee" >
	</td>
</tr>
<tr>
	<td class='ui-widget-header left' > Parking fee</td>
	<td class='ui-widget-content' >
	<input type="text" name="parking_fee" id="parking_fee" >
	</td>
</tr>
<tr>
	<td class='ui-widget-header left' > Tool fee</td>
	<td class='ui-widget-content' >
	<input type="text" name="tool_fee" id="tool_fee" >
	</td>
</tr>
<tr>
	<td class='ui-widget-header left' > Extra fee</td>
	<td class='ui-widget-content' >
	<input type="text" name="extra_fee" id="extra_fee" >
	</td>
</tr>

<?php 
/*	if ($_v== 'Y') {
?>	
<tr>
	<td class='ui-widget-header left' > Use vehicle</td>
	<td class='ui-widget-content' >
	<?php echo DrawFromDB('job','use_vehicle','select', NULL,'yes','',NULL)?>
	</td>
</tr>
<?php		
	} */
?>
<tr>
	<td class='ui-widget-header left' height="30"> Remarks</td>
	<td class='ui-widget-content left' >
	<textarea name="remarks" rows="4" cols="45"></textarea> </td>	
</tr>	
</table>
<input id="add" type="button" value="Add" />
</form>
</body>
</html>