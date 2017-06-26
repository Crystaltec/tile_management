<?php
include_once "include/common.inc";
include_once "include/dbconn.inc";
include_once "include/myfunc.inc";

if ($_POST['action_type'] == 'add' ) {
	if ($_POST['new_value']) {
		$sql = "INSERT INTO visa (visa_name,account_id,regdate) VALUES ('".$_POST['new_value']."','$Sync_id','$now_datetimeano')";
		pQuery($sql,"insert");
	}
} else if ($_POST['action_type'] == 'del'){
	if ($_POST['selected_id']){
		$sql = "DELETE FROM visa WHERE visa_id = '".$_POST['selected_id']."'";
		pQuery($sql,"delete");
	}
}

$sql  = "SELECT visa_name, visa_id FROM visa ORDER BY visa_name";
$result = mysql_query($sql) or exit(mysql_error());
$new_opt = "<option value=''>Please Select</option>";
$msg='';
if ($result) {
	while ($row = mysql_fetch_assoc($result)) {
		$msg .= "<span>".$row['visa_name']. "<span onclick='form_delete(".$row[visa_id].")' class='ui-icon ui-icon-close' style='display:inline-block !important;'></span></span><br />";
		$new_opt .="<option value=".$row['visa_id'].">".$row['visa_name']."</option>";
	}
} else {
	$msg = "Nothing to display";
}
mysql_free_result($result);
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<title>Visa</title>
<link rel="stylesheet" type="text/css" href="css/jquery.ui.all.css" />
<script type="text/javascript" src="js/jquery.min.js"></script>
<script type="text/javascript" src="js/jquery.ui.core.js"></script>
<script type="text/javascript" src="js/jquery.ui.widget.js"></script>
<script type="text/javascript" src="js/jquery.ui.button.js"></script>
</head>
<script type="text/javascript" language="javascript">
$(document).ready(function() {
	$(window.opener.document).find('body').attr('disabled','disabled');
	
	$( "input:button, button").button();
	$('#btn').click(function() {
	
	// Populate the text box on the parent form with value "hello":
	$(window.opener.document).find('#visa_id').html("<?php echo $new_opt;?>");
	
	// Close the window
	window.close();
	});
});

$(window).unload(function() {
	window.opener.document.body.disabled=false;
});

function form_submit() {
	frm = document.visa_frm;
	frm.action_type.value = 'add';
	frm.submit();
	
}

function form_delete(id) {
	frm = document.visa_frm;
	frm.action_type.value = 'del';
	frm.selected_id.value = id;
	frm.submit();
}
</script>
<body>
<form name="visa_frm" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
<div>
<?php
	echo $msg;
?>
</div>
<input type="text" value="" name="new_value" />
<input type="hidden" name="action_type" value="" />
<input type="hidden" name="selected_id" />
<input id="add" type="button" value="Add" onclick="form_submit()"/>
<input id="btn" type="button" value="Update & Close" /> 

</form>
</body>
</html>