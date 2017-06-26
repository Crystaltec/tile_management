<?php
include_once "include/logincheck.inc";
include_once "include/common.inc";
include_once "include/dbconn.inc";
include_once "include/myfunc.inc";

$class_name = '#p_' . $_REQUEST['id'].'.'.$_REQUEST['re_date'];

$sql  = "SELECT * FROM job WHERE id = '".$_REQUEST['id']."'";
								
$list_Records = array();
$id_cnn = mysql_query($sql) or exit(mysql_error());
while($id_rst = mysql_fetch_assoc($id_cnn)) {
	$list_Records = array_merge($list_Records, array($id_rst));
}
	
// have vehicle
/* show use vehicle
$sql = " SELECT vehicle FROM employee WHERE id = '".$list_Records[0]['employee_id']."'  ";
$_v = getRowCount2($sql) ;

 * 
 */
 ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<title>Job Management</title>
<link rel="stylesheet" type="text/css" href="include/default.css" />
<link rel="stylesheet" type="text/css" href="css/jquery.ui.selectmenu.css" />
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
</head>
<script type="text/javascript" language="javascript">
$(document).ready(function() {
	$(window.opener.document).find('body').attr('disabled','disabled');
	
	$("input:button, button").button();
	
	$('#processing').hide();
	
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
		
	$("#update").click(function() {
				
		if (!$('select[name=job_session]').val() && $('#job_session_rates').val() == 0 ) {
			
			if ($('select[name=job_extra_hour]').val()) {
				if (!$('#job_extra_hour_rates').val()) {
					alert("Please select extra hour rates");
					$('#job_extra_hour_rates').focus();
					return;
				}
			} else {
				alert("Please select job session or extra hour");
				$('select[name=job_session]').focus();
				return;
			}
			
		} else {
			if (!$('select[name=job_session]').val()) {
				alert("Please select job session");
				$('select[name=job_session]').focus();
				return;
			}
			
			if ($('#job_session_rates').val() == 0) {
				alert("Please select job session rates");
				$('#job_session_rates').focus();
				return;
			}
		}
				
		$('#processing').fadeIn(500);
		
		$.post("add_job_ok.php", {
			action_type : "update",
			job_id : <?php echo $_REQUEST['id'];?>,
			project_id : $('select[name=project_id]').val(),
			job_type : $('select[name=job_type]').val(),
			job_session : $('select[name=job_session]').val(),
			job_session_rates : $('#job_session_rates').val(),
			job_extra_hour : $('select[name=job_extra_hour]').val(),
			job_extra_hour_rates : $('#job_extra_hour_rates').val(),
			attendance : $('select[name=attendance]').val(),
			travel_fee : $('#travel_fee').val(),
			parking_fee : $('#parking_fee').val(),
			tool_fee : $('#tool_fee').val(),
			extra_fee : $('#extra_fee').val(),
			time : $('.time:checked').val(),
			<?php
			/*	if($_v == 'Y') {
					echo "use_vehicle:$('select[name=use_vehicle]').val(),";
				} */
			?>
			remarks : $('textarea[name=remarks]').val()
		}, function(data) { 
			if (data != 'ERROR') {
				
				//window.opener.searchNow(); 
				window.opener.location.reload();
				// Close the window
				window.close();
			} else {
				alert('Please try again');
			}
		});
		
		$('#processing').fadeOut(800);
		
		return false;
	});
	
	$("#delete").click(function() {
				
		$('#processing').fadeIn(500);
		
		$.post("add_job_ok.php", {
			action_type : "delete",
			job_id : <?php echo $_REQUEST['id']?>,
			project_id : $('select[name=project_id]').val()
		}, function(data) { 
			if (data != 'ERROR') {
				
				window.opener.location.reload();
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

</script>
<body>
<form name="job_frm" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
<div id="processing" style="display:inline">
	<img src="images/ajax-loader.gif" alt="loading" style="width:18px;vertical-align:middle;margin-left:10px;" /> PROCESSING
</div>
<input type="hidden" name="action_type" value="<?=$action_type?>">
<table border="0" cellpadding="0" cellspacing="0" width="500">
<tr>
	<td class='ui-widget-header' style="padding-left:3px" width="80" align="left" height="30" >Date</td>
	<td class='ui-widget-content' style="padding-left:3px"><?php echo getAUDate($list_Records[0]['job_date']);?></td>
</tr>
<input type="hidden" name="id" value="<?php echo $_REQUEST['id'];?>">
<tr>
	<td class="ui-widget-header left">*Type</td>
	<Td class="ui-widget-content left">
	<?php echo DrawFromDB('job','job_type','select',$list_Records[0]['job_type'],"yes ",""," width='120px' style='width:120px;' ");?>
	</Td>
</tr>
<?php if(0) { ?>
<tr>
	<td class='ui-widget-header' style="padding-left:3px" align="left" height="30">* Job Id</td>
	<td class='ui-widget-content' style="padding-left:3px">
	<input type="text" size="20" id="job_id" name="job_id" <?if($action_type=="modify") echo "readonly " ?> value="<?=$list_Records[0]["job_id"]?>"><? if($action_type!="modify") { ?> <input type="button" value="Generate" id="job_generate_id"> <?}?>
	</td>	
</tr>

<?php } ?>
<tr>
	<td class='ui-widget-header' style="padding-left:3px" align="left" height="30">Project</td>
	<td class='ui-widget-content' ><?php getSelectOption('project',$list_Records[0]['project_id'],' project_name ',NULL,NULL,NULL,'400');?>	
	</td>	
</tr>
<tr>
	<td class='ui-widget-header' style="padding-left:3px" align="left" height="30" >Employee</td>
	<td class='ui-widget-content' style="padding-left:3px">
	<?php echo getValue('employee'," CONCAT_WS(', ',last_name, first_name )  "," and id = '".$list_Records[0]['employee_id']."' ");?>
	</td>	
</tr>
<tr>
	<td class='ui-widget-header left' height="30" > </td>
	<td class='ui-widget-content' style="padding-left:3px">
	<input type="checkbox" name="time" value="AM" id="time1" class="time" <?php if($list_Records[0]['time'] == 'AM') echo "checked='yes'";?>>AM
	<input type="checkbox" name="time" value="PM" id="time2" class="time" <?php if($list_Records[0]['time'] == 'PM') echo "checked='yes'";?>>PM
	<input type="checkbox" name="time" value="NI" id="time3" class="time" <?php if($list_Records[0]['time'] == 'NI') echo "checked='yes'";?>>NIGHT
	</td>	
</tr>
<tr>
	<td class='ui-widget-header' style="padding-left:3px" align="left" >* Session</td>
	<td class='ui-widget-content' >
	<?php echo DrawFromDB("job","job_session","select",$list_Records[0]['job_session'],"yes ","Session"," width='120' style='width:120px;' ");?>
	<input type="text" name="job_session_rates" id="job_session_rates" value="<?php echo $list_Records[0]['job_session_rates'];?>" style="width:40px;"><br />
	<em style='padding-left:100px;'>or</em><br />
	&nbsp;Extra hours&nbsp;<?php getCustomizeOption("", "job_extra_hour", $list_Records[0]['job_extra_hour'], 10,"Hour");?> 
	<input type="text" name="job_extra_hour_rates" id="job_extra_hour_rates" value="<?php echo $list_Records[0]['job_extra_hour_rates'];?>" style="width:40px;">
	</td>	
</tr>
<tr>
	<td class='ui-widget-header' style="padding-left:3px" align="left" >* Attendance</td>
	<td class='ui-widget-content' >
	<?php echo DrawFromDB("job","attendance","select",$list_Records[0]['attendance'],"",""," width='120' style='width:120px;' ");?>
	P: Present, A: Absence
	</td>	
</tr>
<tr>
	<td class='ui-widget-header' style="padding-left:3px" align="left" > Travel fee</td>
	<td class='ui-widget-content' >
	<input type="text" name="travel_fee" id="travel_fee" value="<?php echo $list_Records[0]['travel_fee']?>">
	</td>
</tr>
<tr>
	<td class='ui-widget-header' style="padding-left:3px" align="left" > Parking fee</td>
	<td class='ui-widget-content' >
	<input type="text" name="parking_fee" id="parking_fee" value="<?php echo $list_Records[0]['parking_fee']?>">
	</td>
</tr>
<tr>
	<td class='ui-widget-header' style="padding-left:3px" align="left" > Tool fee</td>
	<td class='ui-widget-content' >
	<input type="text" name="tool_fee" id="tool_fee" value="<?php echo $list_Records[0]['tool_fee']?>">
	</td>
</tr>
<tr>
	<td class='ui-widget-header' style="padding-left:3px" align="left" > Extra fee</td>
	<td class='ui-widget-content' >
	<input type="text" name="extra_fee" id="extra_fee" value="<?php echo $list_Records[0]['extra_fee']?>">
	</td>
</tr>
<?php 
	/*if ($_v== 'Y') {
?>	
<tr>
	<td class='ui-widget-header' style="padding-left:3px" align="left" > Use vehicle</td>
	<td class='ui-widget-content' >
	<?php echo DrawFromDB('job','use_vehicle','select', $list_Records[0]['use_vehicle'],'yes','',NULL)?>
	</td>
</tr>
<?php		
	}*/
?>
<tr>
	<td class='ui-widget-header' style="padding-left:3px" align="left" height="30"> Remarks</td>
	<td class='ui-widget-content' >
	<textarea name="remarks" rows="3" cols="45"><?php echo nl2br($list_Records[0]["remarks"])?></textarea> </td>	
</tr>	
</table>
<br />
<div>
<div style="display:inline-block; float:left;"><input id="update" type="button" value="Update" /></div>
<div class="right"><input id="delete" type="button" value="Delete" /></div>
</div>
</form>
</body>
</html>