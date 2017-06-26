<?php
include_once "include/common.inc";
include_once "include/dbconn.inc";
include_once "include/myfunc.inc";

if(!$_REQUEST && !$_POST) exit;
$eid = $_REQUEST['eid'];
$wtype = $_REQUEST['wtype'];

if ($_POST['action_type'] == 'add' ) {
	if ($_POST['new_value']) {
		$sql = "INSERT INTO wages (employee_id,wages_type,wages_amount,account_id,wages_apply_date, regdate) VALUES ('$eid','$wtype','".$_POST['new_value']."','$Sync_id','".getAUDateToDB($_POST['apply_date'])."','$now_datetimeano')";
		pQuery($sql,"insert");
		
		/*
		$sql  = "SELECT wages_id, wages_amount FROM wages WHERE employee_id = '$eid' and wages_type = '$wtype'  ORDER BY regdate desc limit 1";
		$result = mysql_query($sql) or exit(mysql_error());
		
		if ($result) {
			while ($row = mysql_fetch_assoc($result)) {
				if ($wtype == 'f') { 
				echo "<html><script type=\"text/javascript\" language=\"javascript\">
					$(window.opener.document).find('#current_ft').html(\"<input type='text' name='current_ft_wage' readonly  size='6' id='current_ft_wage' value='".$row['wages_amount']."'><input type='hidden' name='current_ft_wage_id' id='current_ft_wage_id' value='".$row['wages_id']."' > \");
					window.close();
					</script></html>";
				} else if($wtype == 'h') { 
				echo "<html><script type=\"text/javascript\" language=\"javascript\">
					$(window.opener.document).find('#current_hr').html(\"<input type='text' name='current_hr_wage' readonly size='6' id='current_hr_wage' value='".$row['wages_amount']."'><input type='hidden' name='current_hr_wage_id' id='current_hr_wage_id' value='".$row['wages_id']."' > \");
	  window.close();
	  </script></html>";	
				}	
			}
		}
		*/
	}
} else if ($_POST['action_type'] == 'del'){
	if ($_POST['selected_id']){
		
		$sql = "SELECT COUNT(*) FROM job WHERE f_wages_id='".$_POST['selected_id']."' or h_wages_id='".$_POST['selected_id']."' ";
		$row = getRowCount($sql);
		
		if($row[0] > 0 ) {
			echo "<script>alert('This employee wage has allocated in payroll.');history.back();</script>";
			exit;
		}
	
		$sql = "DELETE FROM wages WHERE wages_id = '".$_POST['selected_id']."'";
		pQuery($sql,"delete");
	}
}

$sql  = "SELECT * FROM wages WHERE employee_id = '$eid' and wages_type = '$wtype'  ORDER BY regdate desc ";
$result = mysql_query($sql) or exit(mysql_error());
$new_opt = '';

if ($result) {
	while ($row = mysql_fetch_assoc($result)) {
		$msg .= "<tr><td><span class='choose' id='".$row['wages_id']."'>".$row['wages_amount']. "</span><span onclick='form_delete(".$row['wages_id'].")' class='ui-icon ui-icon-close' style='display:inline-block !important;'></span></td><td>".$row['wages_apply_date']."</td><td>".$row['regdate']."</td></tr>";
		
	}
} else {
	$msg = "Nothing to display";
}
mysql_free_result($result);
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<title>Wage</title>
<link rel="stylesheet" type="text/css" href="include/default.css" />
<link rel="stylesheet" type="text/css" href="css/jquery.ui.all.css" />
<script type="text/javascript" src="js/jquery.min.js"></script>
<script type="text/javascript" src="js/jquery.ui.core.js"></script>
<script type="text/javascript" src="js/jquery.ui.widget.js"></script>
<script type="text/javascript" src="js/jquery.ui.button.js"></script>
<script type="text/javascript" src="js/jquery.ui.datepicker.js"></script>
<script type="text/javascript" src="js/jquery.ui.datepicker-en-GB.js"></script>
</head>
<script type="text/javascript" language="javascript">
$(document).ready(function() {
	$(window.opener.document).find('body').attr('disabled','disabled');
	
	$( "input:button, button").button();
	$("#apply_date").datepicker($.datepicker.regional['en-GB']);
	$("#apply_date").datepicker( "option", "firstDay", 1 );
	$("#apply_date").datepicker();
	
	$('.choose').click(function() {
	
	// Populate the text box on the parent form with value "hello":
	
	<?php if ($wtype == 'f') { ?>
	
	window.opener.document.frm1.current_ft_wage.value = $(this).text();
	window.opener.document.frm1.current_ft_wage_id.value = $(this).attr('id'); 

	//$(window.opener.document).find('#current_ft').html(opener.document.frm1.current_ft_wage_id.value = $(this).attr('id'));
					
	//alert("window.opener.document.forms[0].getElementByID(current_ft_wage_id).value='"+ $(this).attr('id')+"' >" );

		//alert($(this).attr('id'));
	
	<?php }  
	else if($wtype == 'h') { ?>
	window.opener.document.frm1.current_hr_wage.value = $(this).text();
	window.opener.document.frm1.current_hr_wage_id.value = $(this).attr('id');
	<?php } ?>	
		
	// Close the window
	window.close();
	}); 
});


$(window).unload(function() {
	window.opener.document.body.disabled=false;
	//$(window.opener.document).find('body').attr('disabled','false');
});

function form_submit() {
	frm = document.wages_frm;
	frm.action_type.value = 'add';
	frm.submit();
	
}

function form_delete(id) {
	frm = document.wages_frm;
	frm.action_type.value = 'del';
	frm.selected_id.value = id;
	frm.submit();
}
</script>
<style>
.choose { cursor:pointer;}
</style>
<body>
<form name="wages_frm" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">

<?php if (!$msg) {?>
<table>
<thead class='ui-widget-header'>
	<tr><th colspan="4"><?php echo getValue("employee"," CONCAT_WS(' ',first_name, last_name) "," and id='".$eid."' ");?></th></tr>
	<tr>
	<th>Amount</th>
		<th>Apply date</th>
		<th>Reg. date</th>
	</tr>
</thead>
<tbody>
	<tr><th colspan="3">Nothing</th></tr>
</tbody>
</table>
<?php } else {?>
<table>
<thead class='ui-widget-header'>
	<tr><th colspan="4"><?php echo getValue("employee"," CONCAT_WS(' ',last_name,first_name) "," and id='".$eid."' ");?></th></tr>
	<tr>
	<th>Amount</th>
		<th>Apply date</th>
		<th>Reg. date</th>
		
	</tr>
</thead>
<tbody>
	<?php echo $msg; ?>
</tbody>
</table>
<?php } ?>
Note: Make sure that the applied date is equal to given in date Job management <br /><br />
<div>
New Amount:<input type="text" value="" name="new_value" />Apply date(option):<input type="text" value="" name="apply_date" id="apply_date" /><Br/>
<input type="hidden" name="action_type" value="" />
<input type="hidden" name="selected_id" />
<input id="add" type="button" value="Add" onclick="form_submit()"/>
<input type="hidden" name="eid" value="<?php echo $eid;?>"/>
<input type="hidden" name="wtype" value="<?php echo $wtype;?>" /><Br />

</div>
</form>
</body>
</html>