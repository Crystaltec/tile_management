<?php
include_once "include/logincheck.inc";
include_once "include/common.inc";
include_once "include/dbconn.inc";
include_once "include/myfunc.inc";

$pp = $_REQUEST['pp'];

$class_name = '#child_project_list_' . $pp;
							
$list_Records = array();
$Query = "SELECT DISTINCT parent_project_id, parent_project_name, remarks FROM parent_project WHERE parent_project_id = '".$pp."' ";

$id_cnn = mysql_query($Query) or exit(mysql_error());
while($id_rst = mysql_fetch_assoc($id_cnn)) {
	$list_Records = array_merge($list_Records, array($id_rst));
}

mysql_free_result($id_cnn);						
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<title>Add project</title>
<link rel="stylesheet" type="text/css" href="include/default.css" /> 
<link rel="stylesheet" type="text/css" href="css/jquery.ui.all.css" />
<link rel="stylesheet" type="text/css" href="css/jquery.multiselect.css" />
<link rel="stylesheet" type="text/css" href="css/jquery.multiselect.filter.css" />
<script type="text/javascript" src="js/jquery.min.js"></script>
<script type="text/javascript" src="js/jquery.ui.core.js"></script>
<script type="text/javascript" src="js/jquery.ui.widget.js"></script>
<script type="text/javascript" src="js/jquery.ui.mouse.js"></script>
<script type="text/javascript" src="js/jquery.ui.draggable.js"></script>
<script type="text/javascript" src="js/jquery.ui.position.js"></script>
<script type="text/javascript" src="js/jquery.ui.resizable.js"></script>
<script type="text/javascript" src="js/jquery.ui.dialog.js"></script>
<script type="text/javascript" src="js/jquery.ui.button.js"></script>
<script type="text/javascript" src="js/jquery.multiselect.min.js"></script>
<script type="text/javascript" src="js/jquery.multiselect.filter.min.js"></script>
</head>
<script type="text/javascript" language="javascript">
$(document).ready(function() {
	$(window.opener.document).find('body').attr('disabled','disabled');
	
	$( "input:button, button").button();
	
	$(".multiselect").multiselect( ).multiselectfilter();
	
	$('.processing').hide();
		
	$('#search_project').click(function(){
		if (!$('input[name=search_project]').val()) {
			alert("Please type search value.");
			$('input[name=search_project]').focus();
			return;
		}
		
		$('#search_project').next().append($('.processing').show()).fadeIn(500);
		
		$.post("search_project.php",{
			q: $('input[name=search_project]').val()
		}, function(data){
			if(data) {
				$('#project_id').html(data);
			} else {
				alert('Please select manually');
			}
		});
		
		$('#search_project').next().append($('.processing')).fadeOut(800);
    });
	
	$("#add").click(function() {
				
		if (!$('#project_id').val()) {
			alert("Please select project.");
			$('#project_id').focus();
			return;
		}
		
		$('#add').next().append($('.processing').show()).fadeIn(500);
		
		$.post("add_project_ok.php", {
			project_id : $('#project_id').val(),
			pp : $('input[name=pp]').val(),
			parent_project_name : $('input[name=parent_project_name]').val(),
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
<body>
<form name="job_frm" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
<div class="processing" style="display:inline">
	<img src="images/ajax-loader.gif" alt="loading" style="width:18px;vertical-align:middle;margin-left:10px;" /> PROCESSING
</div>
<table border="0" cellpadding="0" cellspacing="0" width="500">
<tr>
	<td class='ui-widget-header' style="padding-left:3px" width="80" align="left" height="30" >Name</td>
	<td class='ui-widget-content' style="padding-left:3px"><b><?php echo $list_Records[0]['parent_project_name'];?></b></td>	
</tr>
<input type="hidden" name="pp" value="<?php echo $pp;?>" id="pp">
<input type="hidden" name="remarks" value="<?php echo $list_Records[0]['remarks'];?>">
<input type="hidden" name="parent_project_name" value="<?php echo $list_Records[0]['parent_project_name'];?>">
<tr>
	<td class='ui-widget-header' style="padding-left:3px" align="left" height="30">* Project</td>
	<td class='ui-widget-content' style="padding-left:3px">
	
	<select id="project_id" class="multiselect" multiple="multiple" name="project_id[]" style="width:400px;">
	<?php
	$Query = "";
	$Query  = "SELECT p.project_id, p.project_name  " . 
			" FROM project p LEFT JOIN parent_project pp ON pp.project_id = p.project_id " . 
			" WHERE pp.parent_project_id IS NULL " . 
			" ORDER BY project_name ";
	$id_cnn = mysql_query($Query) or exit(mysql_error());
						
	while($id_rst = mysql_fetch_assoc($id_cnn)) {
	?>
	<option value="<?=$id_rst['project_id']?>"><?=$id_rst['project_name']?></option>
	<?php } ?>
	</select>
	</td>	
</tr>
	
</table>
<input id="add" type="button" value="Add" />

</form>
</body>
</html>