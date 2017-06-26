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
	
	if(frm.project_id.value =="") {
		alert("Please, Insert Project Name!");
		frm.project_id.focus();
		return;
	}
	
	if(frm.builder_id.value=="")
	{
	alert("Please, Insert Builder Name!");
	frm.builder_id.focus();
	
	}
	

	frm.submit();
	
}

function formchk_copy() {
	frm = document.frm1;
	frm.action_type.value = "";
	
	if(frm.project_id.value =="") {
		alert("Please, Insert Project Name!");
		frm.project_id.focus();
		return;
	}
	
	if(frm.builder_id.value=="")
	{
	alert("Please, Insert Builder Name!");
	frm.builder_id.focus();
	
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
	
		
	$("#claimed_date_1").datepicker($.datepicker.regional['en-GB']);
	$("#claimed_date_1").datepicker( "option", "firstDay", 1 );
	$("#claimed_date_1").datepicker();
	
	$("#claimed_date_2").datepicker($.datepicker.regional['en-GB']);
	$("#claimed_date_2").datepicker( "option", "firstDay", 1 );
	$("#claimed_date_2").datepicker();
	
	
	
	$("#received_date_1").datepicker($.datepicker.regional['en-GB']);
	$("#received_date_1").datepicker( "option", "firstDay", 1 );
	$("#received_date_1").datepicker();

	$("#received_date_2").datepicker($.datepicker.regional['en-GB']);
	$("#received_date_2").datepicker( "option", "firstDay", 1 );
	$("#received_date_2").datepicker();
	
	
});

</script>
<?php

$retention_no = $_REQUEST["retention_no"];
$action_type = $_REQUEST["action_type"];


if($action_type=="delete") {
		$sql = "DELETE FROM retention_list WHERE retention_no=".$retention_no;
		//echo $sql;
		pQuery($sql,"delete");
		echo "<script language='javascript'>
		alert('Successfully Deleted!');
		</script>";

		echo "<script>location.href='retention_list.php';</script>";
		
	}


## ĵ��, �〻 �迭 ����
$list_Records = array();

$Query  = "SELECT * ";
$Query .= " FROM retention_list WHERE retention_no='". $retention_no ."'";

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
<br>
	<td valign="top" width="100" height="100%">
	<!-- LEFT -------------------------------------------------------------------------------------------------->
	<?// include_once "left.php"; ?>
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
						<tr><td width="252"><img src="images/icon_circle03.gif">&nbsp;<span style="height:21px">Retention Register</span></td></tr>
						<tr><td height="3"></td></tr>
						<tr><td background="images/bg_check.gif" height="3"></td></tr>
						<tr><td height="2"></td></tr>
					</table>
					</td>
				</tr>

				<form name="frm1" method="post" action="retention_regist_ok.php">
				<tr>
					<td valign="top">
					
						<table border="0" cellpadding="0" cellspacing="0" bordercolor="white" width="1000" class="new_entry">
						
						
						<tr >
							<td class="ui-widget-header left" width="200" height="30" >Builder</td>
							
								<td class="ui-widget-content left"><? getOption("builder", $list_Records[0]['builder_id']); ?> </td>				
							
								</tr>
								
								
								<tr >
							<td class="ui-widget-header left" width="200" height="30" >Project</td>
							<td class="ui-widget-content left"><? getOption("project", $list_Records[0]['project_id']); ?> </td>	
						</tr>
						
						
						<tr>
						
						</tr>
						
						
						<tr >
							<td class="ui-widget-header left" width="200" height="30" >1st Half Retention </td>
						<td class="ui-widget-content left">&nbsp;</td>
						</tr>
						
						
						<tr >
							<td class="ui-widget-header left" width="100" >Claimed Date (1)</td>
						<td class="ui-widget-content left"><input type="text" name="claimed_date_1" id="claimed_date_1" size="30" value="<?php echo getAUDate($list_Records[0]['claimed_date_1']);?>" />
						</tr>
						
						<tr >
							<td class="ui-widget-header left" width="200" height="30" >Claimed Amount (1)</td>
							<td class="ui-widget-content left"><input type="text" size="60" name="claimed_amount_1" value="<?=$list_Records[0]["claimed_amount_1"]?>"></td>	
						</tr>
						
					
						<tr >
							<td class="ui-widget-header left" width="100" >Received Date (1)</td>
						<td class="ui-widget-content left">	<input type="text" name="received_date_1" id="received_date_1" size="30" value="<?php echo getAUDate($list_Records[0]['received_date_1']);?>" /></td>
						</tr>
						
						<tr >
							<td class="ui-widget-header left" width="200" height="30" >Received Amount (1)</td>
							<td class="ui-widget-content left"><input type="text" size="60" name="received_amount_1" value="<?=$list_Records[0]["received_amount_1"]?>"></td>	
						</tr>
						
						
						<tr>
						</tr>
						
						<tr >
							<td class="ui-widget-header left" width="200" height="30" >2nd Half Retention </td>
						<td class="ui-widget-content left">&nbsp;</td>
						</tr>
						
						
						
						
						<tr >
							<td class="ui-widget-header left" width="100" >Claimed Date (2)</td>
						<td class="ui-widget-content left">	<input type="text" name="claimed_date_2" id="claimed_date_2" size="30" value="<?php echo getAUDate($list_Records[0]['claimed_date_2']);?>" /></td>
						</tr>
						
						<tr >
							<td class="ui-widget-header left" width="200" height="30" >Claimed Amount (2)</td>
							<td class="ui-widget-content left"><input type="text" size="60" name="claimed_amount_2" value="<?=$list_Records[0]["claimed_amount_2"]?>"></td>	
						</tr>
						
					
						<tr >
							<td class="ui-widget-header left" width="100" >Received Date (2)</td>
						<td class="ui-widget-content left"><input type="text" name="received_date_2" id="received_date_2" size="30" value="<?php echo getAUDate($list_Records[0]['received_date_2']);?>" /></td>
						</tr>
						
						<tr >
							<td class="ui-widget-header left" width="200" height="30" >Received Amount (2)</td>
							<td class="ui-widget-content left"><input type="text" size="60" name="received_amount_2" value="<?=$list_Records[0]["received_amount_2"]?>"></td>	
						</tr>
						
						<tr></tr>
						
						<tr >
							<td class="ui-widget-header left" width="200" height="30" >Note</td>
							<td class="ui-widget-content left"><textarea name="note" rows="5" cols="92"><?=$list_Records[0]["note"]?></textarea></td>	
						</tr>
						
						
						
					
				<input type="hidden" name="action_type" value="<?=$action_type?>"><input type="hidden" name="retention_no" value="<?=$retention_no?>">				
						</table>		
						<br>
						<table border="0" cellpadding="0" cellspacing="0" width="1000">
						<tr><td align="left">
						<?php if ($action_type=='modify') 
						{?><input type="button" value="Save as copy" onclick="formchk_copy();">
						
						
						<input type="button" value="Delete" onclick="javascript:if(confirm('Are you sure you want to delete this?')) { location.href='<?=$_SERVER['PHP_SELF']?>?retention_no=<?=$list_Records[0]["retention_no"]?>&action_type=delete';}">
						
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