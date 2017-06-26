<?php
ob_start();
include_once "include/logincheck.inc";
include_once "include/common.inc";
include_once "include/dbconn.inc";
include_once "include/myfunc.inc";
include_once "htmlinclude/head.php";

$builder_id = $_REQUEST["builder_id"];
$project_id = $_REQUEST["project_id"];

?>
<script language="Javascript">
function formchk() {
	frm = document.frm1;
	
	
	

	frm.submit();
	
}

function formchk_copy() {
	frm = document.frm1;
	frm.action_type.value = "";
	
	
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
	
	
	
	
	
	$("#job_start_date").datepicker($.datepicker.regional['en-GB']);
	$("#job_start_date").datepicker( "option", "firstDay", 1 );
	$("#job_start_date").datepicker();
	
        
	
	
	
	$("#job_finish_date").datepicker($.datepicker.regional['en-GB']);
	$("#job_finish_date").datepicker( "option", "firstDay", 1 );
	$("#job_finish_date").datepicker();

	
	
});

</script>
<?php

$project_inf_id = $_REQUEST["project_inf_id"];
$action_type = $_REQUEST["action_type"];


if($action_type=="delete") {
		$sql = "DELETE FROM project_information WHERE project_inf_id=".$project_inf_id;
		//echo $sql;
		pQuery($sql,"delete");
		echo "<script language='javascript'>
		alert('Successfully Deleted!');
		</script>";

		echo "<script>location.href='project_info_list.php';</script>";
		
	}


## ĵ��, �〻 �迭 ����
$list_Records = array();

$Query  = "SELECT * ";
$Query .= " FROM project_information WHERE project_inf_id='". $project_inf_id ."'";

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
						<tr><td width="252"><img src="images/icon_circle03.gif">&nbsp;<span style="height:21px">Project Information Register</span></td></tr>
						<tr><td height="3"></td></tr>
						<tr><td background="images/bg_check.gif" height="3"></td></tr>
						<tr><td height="2"></td></tr>
					</table>
					</td>
				</tr>

				<form name="frm1" method="post" action="project_information_regist_ok.php">
				<tr>
					<td valign="top">
					
						<table border="0" cellpadding="0" cellspacing="0" bordercolor="white" width="1000" class="new_entry">
						
						<tr >
							<td class="ui-widget-header left" width="200" height="30" >Project Name</td>
							<td class="ui-widget-content left"><? getOption("project", $list_Records[0]['project_id']); ?></td>	
						</tr>
						
								
						<tr >
							<td class="ui-widget-header left" width="200" height="30" >Builder Name</td>
							
								<td class="ui-widget-content left"><? getOption("builder", $list_Records[0]['builder_id']); ?> </td></td>				
							
								</tr>
								
								
							<tr></tr>	
						
						
						<tr >
							<td class="ui-widget-header left" width="100" >Tile Detail (Floor)</td>
							<td class="ui-widget-content left"><input type="text" size="60" name="tile_detail_floor" value="<?=$list_Records[0]["tile_detail_floor"]?>"></td>										</tr>
						
							</tr>
							
							<tr >
							<td class="ui-widget-header left" width="100" >Tile Detail (Wall)</td>
							<td class="ui-widget-content left"><input type="text" size="60" name="tile_detail_wall" value="<?=$list_Records[0]["tile_detail_wall"]?>"></td>										</tr>
						
							</tr>
							
							<tr></tr>	
						
						
						<tr >
							<td class="ui-widget-header left" width="100" >Glue Detail (Floor)</td>
							<td class="ui-widget-content left"><input type="text" size="60" name="glue_detail_floor" value="<?=$list_Records[0]["glue_detail_floor"]?>"></td>										</tr>
						
							</tr>
							
							<tr >
							<td class="ui-widget-header left" width="100" >Glue Detail (Wall)</td>
							<td class="ui-widget-content left"><input type="text" size="60" name="glue_detail_wall" value="<?=$list_Records[0]["glue_detail_wall"]?>"></td>										</tr>
						
							</tr>
							
							
							
							
							<tr></tr>	
						
						
						<tr >
							<td class="ui-widget-header left" width="100" >Grout Detail (Floor)</td>
							<td class="ui-widget-content left"><input type="text" size="60" name="grout_detail_floor" value="<?=$list_Records[0]["grout_detail_floor"]?>"></td>										</tr>
						
							</tr>
							
							<tr >
							<td class="ui-widget-header left" width="100" >Grout Detail (Wall)</td>
							<td class="ui-widget-content left"><input type="text" size="60" name="grout_detail_wall" value="<?=$list_Records[0]["grout_detail_wall"]?>"></td>										</tr>
						
							</tr>
							
							
							<tr></tr>	
						
						
						<tr >
							<td class="ui-widget-header left" width="100" >Silicon Detail (Floor)</td>
							<td class="ui-widget-content left"><input type="text" size="60" name="silicon_detail_floor" value="<?=$list_Records[0]["silicon_detail_floor"]?>"></td>										</tr>
						
							</tr>
							
							<tr >
							<td class="ui-widget-header left" width="100" >Silicon Detail (Wall)</td>
							<td class="ui-widget-content left"><input type="text" size="60" name="silicon_detail_wall" value="<?=$list_Records[0]["silicon_detail_wall"]?>"></td>										</tr>
						
							</tr>
							
							<tr></tr>	
							
						<tr >
							<td class="ui-widget-header left" width="200" height="30" >Site Manager Name</td>
							<td class="ui-widget-content left"><textarea  name="sm_name" rows="1" cols="50"><?=$list_Records[0]["sm_name"]?></textarea></td>	
						</tr>
							
						<tr >
							<td class="ui-widget-header left" width="200" height="30" >Site Manager Contact No.</td>
							<td class="ui-widget-content left"><textarea  name="sm_contact_no" rows="1" cols="50"><?=$list_Records[0]["sm_contact_no"]?></textarea></td>	
						</tr>	
							
							
							
							
							<tr >
				
							<td class="ui-widget-header left" width="200" height="30" >Job Start Date</td>
							<td class="ui-widget-content left">
							<input type="text" name="job_start_date" id="job_start_date" size="30" value="<?php echo getAUDate($list_Records[0]["job_start_date"]);?>" /></td>
							
						</tr>
						
						
						<tr >
							<td class="ui-widget-header left" width="200" height="30" >Job Finish Date</td>
							<td class="ui-widget-content left"><input type="text" name="job_finish_date" id="job_finish_date" size="30" value="<?php echo getAUDate($list_Records[0]["job_finish_date"]);?>" /></td>
							
						

				<input type="hidden" name="action_type" value="<?=$action_type?>"><input type="hidden" name="project_inf_id" value="<?=$project_inf_id?>">				
						</table>		
						<br>
						<table border="0" cellpadding="0" cellspacing="0" width="1000">
						<tr><td align="left">
						<?php if ($action_type=='modify') 
						{?><input type="button" value="Save as copy" onclick="formchk_copy();">
						
						
						<input type="button" value="Delete" onclick="javascript:if(confirm('Are you sure you want to delete this?')) { location.href='<?=$_SERVER['PHP_SELF']?>?project_inf_id=<?=$list_Records[0]["project_inf_id"]?>&action_type=delete';}">
						
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