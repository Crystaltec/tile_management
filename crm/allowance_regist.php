<?php
include_once "include/logincheck.inc";
include_once "include/common.inc";
include_once "include/dbconn.inc";
include_once "include/myfunc.inc";
include_once "htmlinclude/head.php";

$action_type = $_REQUEST["action_type"];
$limitList = $_REQUEST["limitList"];
$s_resort_order = $_REQUEST["resort_order"];
$srch_param = "";
if($s_resort_order != ""){
	$srch_param .= "&resort_order=$s_resort_order";
}

if($limitList) {
	$srch_param .="&limitList=$limitList";
}
					
							
$id = $_REQUEST["id"];

if($action_type=="modify") {
	## 쿼리, 담을 배열 선언
	$list_Records = array();
	
	$Query  = "SELECT a.*,CONCAT_WS(', ',last_name, first_name ) as employee_name  ";
	$Query .= " FROM allowance a, employee e WHERE a.employee_id = e.id AND allowance_id='$id'";

	$id_cnn = mysql_query($Query) or exit(mysql_error());
	while($id_rst = mysql_fetch_assoc($id_cnn)) {
		$list_Records = array_merge($list_Records, array($id_rst));
		//print_r($list_Records);
		//echo "<p>";
	}
} else if($action_type=="delete") {
	$req_id = $_REQUEST["id"];
	
	$sql = "DELETE FROM allowance WHERE allowance_id='" .$req_id."'";
	pQuery($sql, "delete");
	echo "<script>alert('Deleted!');location.href='allowance_list.php?$srch_param';</script>";
	exit;
}

?>

<script language="Javascript">
function formchk() {
	frm = document.frm1;

	if (!$('#employee_id').val()) {
		alert("Please select employee.");
		$('#employee_id').focus();
		return;
	}
	
	if (!$('#month_year').val()) {
		alert("Please select month & year.");
		$('#month_year').focus();
		return;
	}	
	
	if (!$('#amounts').val()) {
		alert("Please insert amounts.");
		$('#amounts').focus();
		return;
	}	
		
	frm.submit();
}

$(function() {
	$(".new_entry thead").addClass('ui-widget-header');
	$(".new_entry tbody").addClass('ui-widget-content');
	$(".new_entry td").addClass('center');
	
	$("#history").click(function() {
		$("#history_details").toggle();
		return false;
	});
	
	$('.date').datepicker( {
	         dateFormat: 'mm-yy',
		     changeMonth: true,
		     changeYear: true,
		     showButtonPanel: true,
		     onClose: function(dateText, inst) {
			 var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
		     var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
		     	$(this).val($.datepicker.formatDate('mm-yy', new Date(year, month, 1)));
		     },
		      beforeShow : function(input, inst) { 
	            if ((datestr = $(this).val()).length > 0) { 
	                actDate = datestr.split('-'); 
	                year = actDate[1]; 
	                month = actDate[0]-1; 
	                $(this).datepicker('option', 'defaultDate', new Date(year, month)); 
	                $(this).datepicker('setDate', new Date(year, month)); 
	            } 
	        } 
 	   });
	
	 $(".date").focus(function () {
	        $(".ui-datepicker-calendar").hide();
	        $("#ui-datepicker-div").position({
	            my: "center top",
	            at: "center bottom",
	            of: $(this)
	        });
	    });
	    
	
	$( "input:button, button").button();
	
	$(".multiselect").multiselect({selectedList: 4,height:350}).multiselectfilter();
	
	$("#employee_name").autocomplete({
    	source: "autocomplete_employee.php",
    	minLength: 2,
    	select: function(event,ui) {
    		$('#employee_id').val(ui.item.id);
			$('#employee_name').val(ui.item.value);
    	}
    });
    	
});

	
</script>
<BODY leftmargin=0 topmargin=0>
<div class="processing" style="display:none">
	<img src="images/ajax-loader.gif" alt="loading" style="width:18px;vertical-align:middle;margin-left:10px;" /> PROCESSING
</div>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
<tr>
	<td valign="top" width="191" height="100%">
	<!-- LEFT -------------------------------------------------------------------------------------------------->
	<? include_once "left.php"; ?>
	<!-- LEFT END ---------------------------------------------------------------------------------------------->
	</td>
	<!-- LEFT BG------------------------------------------------------------------------------------------------>
	<td width="1" bgcolor="#DFDFDF">
	</td>
	<!-- LEFT BG END-------------------------------------------------------------------------------------------->
	<td valign="top">
	<!-- BODY -------------------------------------------------------------------------------------------------->
	<table border="0" cellpadding="0" cellspacing="0" width="100%">
		<!-- BODY TOP ------------------------------------------------------------------------------------------->
		<tr>
			<td style="padding-left:15px"><? include_once "top.php"; ?></td>
		</tr>
		<!-- BODY TOP END --------------------------------------------------------------------------------------->
		<!-- BODY CENTER ----------------------------------------------------------------------------------------->
		<tr>
			<td width="100%">			
			<table border="0" cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td style="padding-left:15px" valign="bottom" height="14" colspan="2">					
				</td>
			</tr>	
			<tr>
				<td style="padding-left:15px" valign="top">
				<!-- CONTENTS -------------------------------------------------------------------------------------------->
				<table border="0" cellpadding="0" cellspacing="0" width="1000">				
				<tr>
					<td valign="top">
					<table border="0" cellpadding="0" cellspacing="0" width="1000" class="font11_bold">
						<tr><td height="8"></td></tr>
						<tr><td width="252"><img src="images/icon_circle03.gif">&nbsp;<span style="height:21px">Allowance Register</span></td></tr>
						<tr><td height="3"></td></tr>
						<tr><td background="images/bg_check.gif" height="3"></td></tr>
						<tr><td height="2"></td></tr>
					</table>
					</td>
				</tr>
				<form name="frm1" method="post" action="allowance_regist_ok.php?<?php echo $srch_param;?>">
				<input type="hidden" name="action_type" value="<?=$action_type?>">
				<tr>
					<td valign="top">
						<table border="0" cellpadding="0" cellspacing="0" width="1000">
						<input type="hidden" name="id" value="<?php echo $list_Records[0]['allowance_id']?>">
						<tr>
							<td height="30"  class="ui-widget-header left" >* Employee</td>
							<td class='ui-widget-content left' >
							
							<input type="text" id='employee_name' name="employee_name" style="background-color:transparent;width:495px;"  value="<?php echo $list_Records[0]['employee_name'];?>" />
							<input type="hidden" id="employee_id" name="employee_id" value="<?php echo $list_Records[0]['employee_id'];?>" />
						
							</td>	
						</tr>
						<tr >
							<td width="100" height="30"  class="ui-widget-header left" >* Month & Year</td>
							<td width="570" class='ui-widget-content left' >
							<input type="text" name="month_year" id="month_year" class="date" size="10" value="<?php echo $list_Records[0]['month_year'];?>" /></td>	
						</tr>
						
						<tr >
							<td width="100" height="30"  class="ui-widget-header left" >* Amounts</td>
							<td width="570" class='ui-widget-content left' >
							<input type="text" name="amounts" id="amounts" size="10" value="<?php echo $list_Records[0]['amounts'];?>" /></td>	
						</tr>					
						<tr>
							<td height="30" class="ui-widget-header left" > Remarks</td>
							<td class='ui-widget-content left' ><textarea name="remarks" rows="4" cols="60"><?=$list_Records[0]["allowance_remark"]?></textarea> </td>	
						</tr>	
						</table>
						<br/>
						<?php if($action_type) { ?>
						<table border="0" cellpadding="0" cellspacing="0" width="1000" class="new_entry">
						<thead>
							<tr>
								<th><span id="history">History</span>&larr;click to see history</th>
							</tr>
						</thead>
						<tbody id="history_details" style="display:none;">	 		 
						<tr >
							<td class="right"  colspan="2">Inserted by&nbsp;<?php echo getValue('account', 'username', ' AND userid="'.$list_Records[0]['account_id'].'"')?>&nbsp;<?php echo getAUDate($list_Records[0]["regdate"],1)?>
							<?php 
							$Query  = "SELECT * ";
							$Query .= " FROM history WHERE history_table='allowance' AND history_table_id ='". $list_Records[0]['allowance_id'] ."' ";
														
							$result = mysql_query($Query) or exit(mysql_error());
							$result_str ='';
							if ($result) {
								$result_str = "<br />Updated by ";
								while ($row = mysql_fetch_assoc($result)) {
									$result_str .= getValue('account','username',' AND userid="'.$row['account_id'].'" '). " ". getAUDate($row["regdate"],1) ."<br />";
								}
								if ($result_str != '<br />Updated by ')
									echo $result_str;
							}
							?>
							</td>	
						</tr>
						</tbody>
						</table>
						<?php }?>
								
						<br>
						<table border="0" cellpadding="0" cellspacing="0" width="1000">
						<tr>
							<td class="right">Required fields are marked with an asterisk (*).<br><input type="button" value="Save" onclick="formchk();"> 
						<?php if($action_type <> '') { ?>
						<input type="button" value="Delete" onclick="javascript:if(confirm('Are you sure?')) { location.href='<?=$_SERVER['PHP_SELF']?>?id=<?=$list_Records[0]["allowance_id"]?>&action_type=delete';}">
						<?php } ?>
							</td>
						</tr>
						</table>
					</td>
				</tr>
				</form>
				<tr><td></td></tr>
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