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
	
	if(frm.contact_name.value =="") {
		alert("Please, Insert Contact Name!");
		frm.contact_name.focus();
		return;
	}
	
	if(frm.contact_no.value=="")
	{
	alert("Please, Insert Contact Number!");
	frm.contact_no.focus();
	
	}
	

	frm.submit();
	
}

function formchk_copy() {
	frm = document.frm1;
	frm.action_type.value = "";
	
	if(frm.contact_name.value =="") {
		alert("Please, Insert Contact Name!");
		frm.contact_name.focus();
		return;
	}
	
	if(frm.contact_no.value=="")
	{
	alert("Please, Insert Contact Number!");
	frm.contact_no.focus();
	
	}

	frm.submit();
	
}
$(function() {
	$( "input:button, button").button();
	$(".new_entry thead").addClass('ui-widget-header');
	$(".new_entry tbody").addClass('ui-widget-content');
	$(".new_entry td").addClass('center');
	$("#table_option thead").addClass('ui-widget-header');
	$("#table_option tbody").addClass('ui-widget-content');
	
	$(".option_table").hide();
	
	$("#option_toggle").click(function() {
		$(".option_table").toggle();
		return false;
	});
	
	
	
	
	
	$("#submitted_date").datepicker($.datepicker.regional['en-GB']);
	$("#submitted_date").datepicker( "option", "firstDay", 1 );
	$("#submitted_date").datepicker();
	
        
	
	
	$("#due_date").datepicker( "option", "firstDay", 1 );
	$("#due_date").datepicker({ 

  constrainInput: false,
  dateFormat: 'yy-mm-dd'
});
	
});

</script>
<?php

$tender_no = $_REQUEST["tender_no"];
$action_type = $_REQUEST["action_type"];
$builder_id = $_REQUEST["builder_id"];
$temp_builder = $_REQUEST["temp_builder"];

$one = 1;

if($action_type=="delete") {


$query = "Update temp_var set temp_builder='$builder_id' where temp_no='$one'";

mysql_query($query);

$sql_temp = "SELECT * FROM temp_var WHERE temp_no='$one'";
										
						$result = mysql_query($sql_temp) or exit(mysql_error());
							
				if($result)
					{
							
						while ($row = mysql_fetch_assoc($result)) 
							
						{
						$temp_builder = $row['temp_builder'] ;
						
						}		
					}

	$sql = "DELETE FROM tender_manage WHERE tender_no=".$tender_no;
		//echo $sql;
		pQuery($sql,"delete");
		echo "<script language='javascript'>
		alert('Successfully Deleted!');
		</script>";

		echo "<script>location.href='tender_manage_list.php?builder_id=$temp_builder';</script>";
		
	}


## ĵ��, �〻 �迭 ����
$list_Records = array();

$Query  = "SELECT * ";
$Query .= " FROM tender_manage WHERE tender_no='". $tender_no ."'";

//echo $Query;

$id_cnn = mysql_query($Query) or exit(mysql_error());
while($id_rst = mysql_fetch_assoc($id_cnn)) {
	$list_Records = array_merge($list_Records, array($id_rst));
	//print_r($list_Records);
	//echo "<p>";
}
?>

<BODY leftmargin=0 topmargin=0>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
<!-- BODY TOP ------------------------------------------------------------------------------------------->
<tr>
<td style="padding-left:0px"><? include_once "top.php"; ?></td>
</tr>
<!-- BODY TOP END --------------------------------------------------------------------------------------->

<table border="0" cellpadding="0" cellspacing="0" width="100%">

	<td valign="top" width="191" height="100%">
	<!-- LEFT -------------------------------------------------------------------------------------------------->
	<? include_once "left.php"; ?>
	<!-- LEFT END ---------------------------------------------------------------------------------------------->
	</td>
	<!-- LEFT BG------------------------------------------------------------------------------------------------>
	<td width="3" background="images/gray.jpg">
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
						<tr><td width="252"><img src="images/icon_circle03.gif">&nbsp;<span style="height:21px">Tender Register</span></td></tr>
						<tr><td height="3"></td></tr>
						<tr><td background="images/bg_check.gif" height="3"></td></tr>
						<tr><td height="2"></td></tr>
					</table>
					</td>
				</tr>

				<form name="frm1" method="post" action="tender_manage_regist_ok.php">
				<tr>
					<td valign="top">
					
						<table border="0" cellpadding="0" cellspacing="0" bordercolor="white" width="1000" class="new_entry">
						
						
						<tr >
							<td class="ui-widget-header left" width="200" height="30" >Builder</td>
							
								<td class="ui-widget-content left"><? getOption("builder", $list_Records[0]['builder_id']); ?> </td>				
							
								</tr>
								
								
								<tr >
							<td class="ui-widget-header left" width="200" height="30" >Project</td>
							<td class="ui-widget-content left"><textarea  name="project_name" rows="1" cols="50"><?=$list_Records[0]["project_name"]?></textarea></td>	
						</tr>
						
						
						<tr >
							<td class="ui-widget-header left" width="100" >Address</td>
							<td class="ui-widget-content left"><textarea name="address" rows="5" cols="92"><?=$list_Records[0]["address"]?></textarea></td>	
						</tr>
						
							
							<tr >
							<td class="ui-widget-header left" width="200" height="30" >Contact Name</td>
							<td class="ui-widget-content left"><input type="text" size="60" name="contact_name" value="<?=$list_Records[0]["contact_name"]?>"></td>	
						</tr>
						
						</tr>
						
							
							<tr >
							<td class="ui-widget-header left" width="200" height="30" >Contact No.</td>
							<td class="ui-widget-content left"><input type="text" size="60" name="contact_no" value="<?=$list_Records[0]["contact_no"]?>"></td>	
						</tr>
						
						
						<tr >
							<td class="ui-widget-header left" width="200" height="30" >Email Address</td>
							<td class="ui-widget-content left"><input type="text" size="60" name="email_address" value="<?=$list_Records[0]["email_address"]?>"></td>	
						</tr>
						
						
							
						<tr >
				
							<td class="ui-widget-header left" width="200" height="30" >Due Date</td>
							<td class="ui-widget-content left">
							<input type="text" name="due_date" id="due_date" size="30"  value="<?=$list_Records[0]["due_date"];?>" /></td>
							
						</tr>
						
						
						<tr >
							<td class="ui-widget-header left" width="200" height="30" >Submitted Date</td>
							<td class="ui-widget-content left"><input type="text" name="submitted_date" id="submitted_date" size="30" value="<?php echo getAUDate($list_Records[0]["submitted_date"]);?>" /></td>
							
						
						</tr>
						<tr >
							<td class="ui-widget-header left" width="200" height="30" >Tender / Quote</td>
							<td class="ui-widget-content left"><? processOption3("tender_quote",($action_type)? $list_Records[0]["tender_quote"] : "TENDER")?></td>
						</tr>
						<tr >
							<td class="ui-widget-header left" width="200" height="30" >Tender Status</td>
							<td class="ui-widget-content left">
						<? processOption2("tender_status",($action_type)? $list_Records[0]["tender_status"] : "IN-PROGRESS")?></td>	
						
						</tr>
						
						<tr >
							<td class="ui-widget-header left" width="100" >Note</td>
							<td class="ui-widget-content left"><textarea name="note" rows="5" cols="92"><?=$list_Records[0]["note"]?></textarea></td>	
						</tr>
						
						
						
						
						

				<input type="hidden" name="action_type" value="<?=$action_type?>"><input type="hidden" name="tender_no" value="<?=$tender_no?>">				
						</table>		
						<br>
						<table border="0" cellpadding="0" cellspacing="0" width="1000">
						<tr><td align="left">
						<?php if ($action_type=='modify') 
						{?><input type="button" value="Save as copy" onclick="formchk_copy();">
						
						
						<input type="button" value="Delete" onclick="javascript:if(confirm('Are you sure you want to delete this?')) { location.href='<?=$_SERVER['PHP_SELF']?>?builder_id=<?=$list_Records[0]["builder_id"]?>&tender_no=<?=$list_Records[0]["tender_no"]?>&action_type=delete';}">
						
						<?php } ?>
						
						</td><td align="right">Required fields are marked with an asterisk (*).<br><input type="button" value="Save" onclick="formchk();"></td></tr>
						
						
						
						</table>
					</td>
				</tr>
				</form>
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