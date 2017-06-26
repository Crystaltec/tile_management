<?php
include_once "include/logincheck.inc";
include_once "include/common.inc";
include_once "include/dbconn.inc";
include_once "include/myfunc.inc";

if(!$_REQUEST) exit;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<title>Job Management - all move</title>
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
	
	$("#job_date").datepicker($.datepicker.regional['en-GB']);
	$("#job_date").datepicker( "option", "firstDay", 1 );
	$("#job_date").datepicker();
	
	$("#update").click(function() {
		if (!$('#job_date').val()) {
			alert("Please insert job date.");
			$('#job_date').focus();
			return;
		}	
		
		if (!$('select[name=project_id]').val()) {
			alert("Please select project.");
			$('select[name=project_id]').focus();
			return;
		}
				
		$('#processing').fadeIn(500);
		
		$.post("move_job_ok.php", {
			action_type : "update",
			prev_pid : <?php echo $_REQUEST['pid'];?>,
			prev_date : '<?php echo $_REQUEST['date'];?>',
			project_id : $('select[name=project_id]').val(),
			job_date: $('#job_date').val()
			
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
<div class="ui-widget">
	<div class="ui-state-highlight ui-corner-all" style="margin: 10px; padding: 0 .7em;"> 
	<p><span class="ui-icon ui-icon-info" style="float: left; margin-right: .3em;"></span>
	<strong>Please make sure target project and date!</strong><Br/>if there is duplicated data, it may occur loss of data.</p>
	</div>
</div>
<tr>
	<td class='ui-widget-header' style="padding-left:3px" width="80" align="left" height="30" >Date</td>
	<td class='ui-widget-content' style="padding-left:3px"><input type="text" name="job_date" id="job_date" size="10" value="<?php echo getAUDate($_REQUEST['date']);?>" />
	</td>
</tr>
<tr>
	<td class='ui-widget-header' style="padding-left:3px" align="left" height="30">Project</td>
	<td class='ui-widget-content' ><?php getSelectOption('project',$_REQUEST['pid'],' project_name ',NULL,NULL,NULL,'400');?>	
	</td>	
</tr>
</table>
<br />
<div>
<div class="right"><input id="update" type="button" value="Move" /></div>
</div>
</form>
</body>
</html>