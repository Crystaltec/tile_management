<?php
include_once "include/logincheck.inc";
include_once "include/common.inc";
include_once "include/dbconn.inc";
include_once "include/myfunc.inc";

$pp = $_REQUEST['pp'];

$class_name = '#team_list_' . $pp;
							
$list_Records = array();
$Query = "SELECT DISTINCT employee_team_id, employee_team_name, remarks FROM employee_team WHERE employee_team_id = '".$pp."' ";

$id_cnn = mysql_query($Query) or exit(mysql_error());
while($id_rst = mysql_fetch_assoc($id_cnn)) {
	$list_Records = array_merge($list_Records, array($id_rst));
}

mysql_free_result($id_cnn);						
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<title>Add team</title>
<link rel="stylesheet" type="text/css" href="include/default.css" /> 
<link rel="stylesheet" type="text/css" href="css/jquery.ui.all.css" />
<script type="text/javascript" src="js/jquery.min.js"></script>
<script type="text/javascript" src="js/jquery.ui.core.js"></script>
<script type="text/javascript" src="js/jquery.ui.widget.js"></script>
<script type="text/javascript" src="js/jquery.ui.mouse.js"></script>
<script type="text/javascript" src="js/jquery.ui.draggable.js"></script>
<script type="text/javascript" src="js/jquery.ui.position.js"></script>
<script type="text/javascript" src="js/jquery.ui.resizable.js"></script>
<script type="text/javascript" src="js/jquery.ui.dialog.js"></script>
<script type="text/javascript" src="js/jquery.ui.button.js"></script>
<script type="text/javascript" src="js/jquery.ui.sortable.js"></script>
<link rel="stylesheet" type="text/css" href="css/jquery.multiselect.css" />
<link rel="stylesheet" type="text/css" href="css/jquery.multiselect.filter.css" />
<script type="text/javascript" src="js/jquery.multiselect.min.js"></script>
<script type="text/javascript" src="js/jquery.multiselect.filter.min.js"></script>
</head>
<script type="text/javascript" language="javascript">
$(document).ready(function() {
	$(window.opener.document).find('body').attr('disabled','disabled');
	
	$( "input:button, button").button();
	
	$(".multiselect").multiselect().multiselectfilter();
	
	$('.processing').hide();
		
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
				$('#employee_id').html(data);
			} else {
				alert('Please select manually');
			}
		});
		
		$('#search_employee').next().append($('.processing')).fadeOut(800);
    });
	
	$("#add").click(function() {
				
		if (!$('#employee_id').val()) {
			alert("Please select project.");
			$('#employee_id').focus();
			return;
		}
		
		$('#add').next().append($('.processing').show()).fadeIn(500);
		
		$.post("add_team_ok.php", {
			employee_id : $('#employee_id').val(),
			pp : $('input[name=pp]').val(),
			employee_team_name : $('input[name=employee_team_name]').val(),
			remarks : $('input[name=remarks]').val()
		}, function(data) { 
			if (data != 'Error!') {
				//alert(data);
				$(window.opener.document).find('<?php echo $class_name;?>').html(data);
		
				// Close the window
				window.close();
			} else {
				alert('Please try again');
			}
			
		});
		
		$("#add").next().append($('.processing')).fadeOut(800);
		
		return false;
	});
	
});

$(window).unload(function() {
	window.opener.document.body.disabled=false;
});


</script>
<body >
<form name="teamfrm" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
<div class="processing" style="display:inline">
	<img src="images/ajax-loader.gif" alt="loading" style="width:18px;vertical-align:middle;margin-left:10px;" /> PROCESSING
</div>
<table border="0" cellpadding="0" cellspacing="0" width="600">
<tr>
	<td class='ui-widget-header left' width="80" height="30" >Name</td>
	<td class='ui-widget-content' style="padding-left:3px"><b><?php echo $list_Records[0]['employee_team_name'];?></b></td>	
</tr>
<input type="hidden" name="pp" value="<?php echo $pp;?>" id="pp">
<input type="hidden" name="remarks" value="<?php echo $list_Records[0]['remarks'];?>">
<input type="hidden" name="employee_team_name" value="<?php echo $list_Records[0]['employee_team_name'];?>">
<tr>
	<td class='ui-widget-header' style="padding-left:3px" align="left" height="30">* Employee</td>
	<td class='ui-widget-content' style="padding-left:3px">
	<select id="employee_id" class="multiselect" multiple="multiple" name="employee_id[]" style='width:500px;'>
	<?php
	$Query = "";
	$Query  = "SELECT e.id, CONCAT_WS(', ',e.last_name, e.first_name ) as employee_name ";
	$Query .= " FROM employee e LEFT JOIN employee_team et ON et.employee_id = e.id " .
			" WHERE et.employee_team_id IS NULL ORDER BY CONCAT_WS(', ',last_name, first_name ) ";
	$id_cnn = mysql_query($Query) or exit(mysql_error());
								
	while($id_rst = mysql_fetch_assoc($id_cnn)) {
	?>
	<option value="<?=$id_rst['id']?>"><?=$id_rst['employee_name']?></option>
	<?php }
	?>
	</select>
	</td>	
</tr>
	
</table>
<input id="add" type="button" value="Add" />

</form>
</body>
</html>