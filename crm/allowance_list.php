<?php
ob_start();
include_once "include/logincheck.inc";
include_once "include/common.inc";
include_once "include/dbconn.inc";
include_once "include/myfunc.inc";
include_once "htmlinclude/head.php";

$action_type = $_REQUEST["action_type"];
$limitList = $_REQUEST["limitList"];

if($action_type=="delete") {
	$allowance_id = $_REQUEST["allowance_id"];
	$sql = "DELETE FROM allowance WHERE allowance_id=".$allowance_id;
	pQuery($sql,"delete");
}

?>
<script language="Javascript">
function searchNow() {
	var f = document.searchform;
	f.action="<?=$_SERVER['PHP_SELF']?>";
	f.submit();
}
function reSort(param) {
	var f = document.searchform;
	f.resort_order.value = param;
	f.action="<?=$_SERVER['PHP_SELF']?>";
	f.submit();

}
$(function() {
	$("input:button, button").button();
	$(".list_table thead").addClass('ui-widget-header');
	$(".list_table tbody").addClass('ui-widget-content');
	$('.ui-widget-content').css({'background-image' :'none','background-color':'none'});
	
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
	    
	$("#n_pay_start").datepicker({ 
		beforeShowDay: function(date){ 
			return [date.getDay() ==1,''];
		}
	});
	
	$(".not_in .ui-icon").click(function() {
			var n_eid = $(this).parent().parent("tr").attr('id').split('_');
			var n_val = $("input[name=n_amounts"+n_eid[1]+"]").val();
			var n_date = $("input[name=n_date"+n_eid[1]+"]").val();
			var current_id = $(this).parent().parent("tr").attr('id');
		
			if(n_eid[1] && n_val && n_date) {
				$.post("add_allowance.php", {
					id : n_eid[1],
					val : n_val,
					date : n_date
				}, function(data) {
					if(data.result != 'ERROR') {
						$("form[name=searchform]").submit();
					} else {
						alert('Please try again');
					}
				}, "json");
			}
			
			return false;
		});
		
		
	$('.multi_entry').qtip({
		content: 'Easy way to add multiple transactions',
		position :{
			adjust: { 
			x: -5,
			y:-80
			}
		},
		style: { 
		    width: 170,
		    padding: 2,
		    color: 'black',
		    textAlign: 'center',
		    border: {
			width: 1,
			radius: 3,
			},
		   	tip: 'bottomLeft',
			name: 'cream' 
		}
	});
	
});
</script>
<style>
#table {font-size:11px;}
#dialog-form label,
#dialog-form select { float: left; margin-right: 10px; }
#dialog-form fieldset { border:0; }
.ui-dialog {width:410px !important; }
.ui-dialog .ui-state-error { padding: .3em; }
.ui-dialog-content {padding-left:0 !important;}
#dialog-form { height:120px !important; width:400px !important;}

#dialog-form input,
#dialog-form select { float:left; display:block !important; margin:0 0 5px 0 !important; }
#dialog-form label { width:120px; display:inline-block !important; clear:both;}
.validateTips { border: 1px solid transparent; margin:0.3em; padding: 0.3em; }
</style>
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
	<? //include_once "left.php"; ?>
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
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
					<td valign="top">
					<table border="0" cellpadding="0" cellspacing="0" width="1000" class="font11_bold">
						<tr><td height="8"></td></tr>
						<tr><td width="252"><img src="images/icon_circle03.gif">&nbsp;<span style="height:21px">Allowance List</span></td></tr>
						<tr><td height="3"></td></tr>
						<tr><td background="images/bg_check.gif" height="3"></td></tr>
					</table>
					</td>
				</tr>
				<tr>
					
					<td valign="top">
						<?php
							//Search Value
							$s_Cond = "";
							$s_name = $_REQUEST["s_name"];
							$s_id = $_REQUEST["s_id"];
							$s_resort_order = $_REQUEST["resort_order"];
							$s_date = $_REQUEST["s_date"];
							if($s_name != "") {
								$s_Cond .= " AND CONCAT_WS(', ',last_name, first_name ) like '%". $s_name ."%'";
								$srch_param .= "&s_name=$s_name";
							}

							if($s_id != "") {
								$s_Cond .= " AND employee_id like '%". $s_id ."%'";
								$srch_param .= "&s_id=$s_id";
							}
							
							if ($s_date != "") {
								$s_Cond .= " AND month_year = '" .$s_date . "'";
								$srch_param .= "&s_date=$s_date";
							}else {
								$s_date = date('m-Y', time() + (3600 * 14));
								$s_Cond .= " AND month_year = '" .$s_date . "'";
							}
							
							//$srch_param = urlencode($srch_param);
							
							if($s_resort_order != ""){
								if ($s_resort_order == "name") {
									$s_Sort = " ORDER BY CONCAT_WS(', ',last_name, first_name ) ";
								} else {
									$s_Sort = " ORDER BY " . $s_resort_order ." DESC" ;
								}
								
								$srch_param .= "&resort_order=$s_resort_order";
							} else {
								$s_Sort = " ORDER BY month_year DESC, CONCAT_WS(', ',last_name, first_name ) ";
								$s_resort_order = 'month_year';
							}
							if($limitList) {
								$srch_param .="&limitList=$limitList";
							}
						?>
						<form name="searchform">
						<table border="0" cellpadding="0" cellspacing="0" height="40" valign="bottom" width="1000" >							
							<tr class='ui-widget-header'>
								<td height="30"  style="padding-left:5px; width:120px;"> Name
								</td>
								<td style="padding-left:5px">
								<input type="text" size="30" name="s_name" value="<?php echo $s_name;?>">
								&nbsp;		
								</td>
								<td height="30"  style="padding-left:5px; width:120px;"> Month & Year 
								</td>
								<td style="padding-left:5px">
								<input type="text" size="30" name="s_date" id="s_date" class="date" value="<?php echo $s_date;?>">
								&nbsp;		
								</td>
							</tr>
							<tr><td colspan="4" background="images/bg_check02.gif" height="3"></td></tr>
							<tr>
								<td colspan="4" align="right" height="30">
								<span style="float:left;">Items per page:<select name="limitList" id="limitList" onchange="searchNow();">
						<option value="50" <?php if($limitList == 50) echo "selected";?> >50</option>
						<option value="100" <?php if($limitList == 100) echo "selected";?> >100</option>
						<option value="200" <?php if($limitList == 200) echo "selected";?> >200</option>
						<option value="999999" <?php if($limitList == 999999) echo "selected";?> >All</option>
						</select>
						
						<input type="hidden" name="list_view" id="list_view" value="<?php echo $list_view;?>" />
						</span>	
						<input type="button" Value="Search" onclick="searchNow()"></td>
							</tr>
						</table>
						
						<br>
						
						<table border="0" width="1000" cellpadding="0" cellspacing="1" class="list_table">
						<thead>
						<tr>
							<th width="40">No</th>
							<th width="150" onclick="reSort('month_year')" <?php if($s_resort_order == 'month_year')
							{echo "class='sort_asc'";} 
							else echo "class='sort'";?>
							>Month & Year</th>
							<th onclick="reSort('name')" <?php if($s_resort_order == 'name')
							{echo "class='sort_asc'";} 
							else echo "class='sort'";
							?> >Name</th>
							<th >Amounts($)</th>
							<th width="60">&nbsp;</th>
							<th width="60">DEL</th>
						</tr>
						</thead>
						<tbody>
						<input type="hidden" name="resort_order" id="resort_order" value="">
						<?php
							// 페이지 계산 ///////////////////////////////////////////////////////////////////////////////////////////
							$page = $_REQUEST["page"];
							if(!$page)
								$page = 1;
						
							$limitPage = 10;
							if (!$_REQUEST["limitList"]) {
								$limitList = 50;
							} else
								$limitList = $_REQUEST["limitList"];
							
							$total = getRowCount2("SELECT COUNT(*) FROM allowance a, employee e WHERE 1=1 AND a.employee_id = e.id ". $s_Cond);
							//echo ceil(1.2);
							$totalPage = ceil($total/$limitList);
							$block = ceil($page/$limitPage);
							$start = ($page-1)*$limitList;

							$startPage = ($block-1)*$limitPage + 1;
							$endPage = $startPage + $limitPage - 1;
							if ($endPage > $totalPage ) $endPage = $totalPage; 
							// 페이지 계산 끝//////////////////////////////////////////////////////////////////////////////////////

							## 쿼리, 담을 배열 선언
							$list_Records = array();
							
							$Query  = "SELECT a.*, CONCAT_WS(', ',last_name, first_name )  as employee_name ";
							$Query .= " FROM allowance a, employee e where 1=1 AND a.employee_id = e.id " . $s_Cond . $s_Sort . "  LIMIT $start, $limitList  ";
								
							$id_cnn = mysql_query($Query) or exit(mysql_error());
							while($id_rst = mysql_fetch_assoc($id_cnn)) {
								$list_Records = array_merge($list_Records, array($id_rst));
								//print_r($list_Records);
								//echo "<p>";
							}							
							//echo count($list_Records);
							$cnt = count($list_Records);
							
							$total_allowance = 0;
							
							if($cnt > 0) {
							for($i=0; $i<count($list_Records); $i++) {
								
								// visa 만료 30일전부터 경고 표시 
								$bgcolor = "";
								
								$warn = "";
								/*
								if (($list_Records[$i]["visa_expiry_date"] <> "0000-00-00") && date_diff_day($list_Records[$i]["visa_expiry_date"]) <=30 ) {
									$bgcolor = " style='background-color:#FFC81E !important;' " ;
								}
								*/
								$total_allowance += $list_Records[$i]["amounts"];	
								if($i%2 == 0){
									$even_odd = ' class="even ' . $warn. '" ';
								} else
									$even_odd = ' class="odd ' . $warn. '" ';
									
						?>
						<tr align="center" <?=$bgcolor?> <?php echo $even_odd;?>>
							<td height="25"><?=$total - (($limitList * ($page-1)) + $i)?></td>
							<td class="left"><a href="allowance_regist.php?id=<?=$list_Records[$i]["allowance_id"]?>&action_type=modify"><b><?php echo $list_Records[$i]["month_year"]?></b></a></td>
							<td class="left"><a href="allowance_regist.php?id=<?=$list_Records[$i]["allowance_id"]?>&action_type=modify"><b><?php echo $list_Records[$i]["employee_name"];?></b></a></td>
							<td class="right"><a href="allowance_regist.php?id=<?=$list_Records[$i]["allowance_id"]?>&action_type=modify"><b><?php echo "$".number_format($list_Records[$i]["amounts"],2,".",",")?></b></a></td>
							<td><a href="allowance_regist.php?id=<?=$list_Records[$i]["allowance_id"]?>&action_type=modify">[EDIT]</a></td>
							<td><a href="javascript:if(confirm('Are you sure?')) { location.href='<?php echo $_SERVER['PHP_SELF']?>?allowance_id=<?=$list_Records[$i]["allowance_id"]?>&action_type=delete';}"><span class='ui-icon ui-icon-close '></span></a></td>
						</tr>
						<?php
							}
							} else {
								echo "<tr><td colspan=5 height=40 align=center>Nothing to display</td></tr>";
							}
						?>
						<?php if ($total_allowance <> 0) {
						?>
						<tr>
							<td colspan='5' class='right'>Total</td>
							<td class='right'><b><?php echo "$".number_format($total_allowance,2,".",",")?></b></td>
						</tr>
						<?php } ?>
						
						</tbody>
						</table>
						</form>
						<br>
						<table border="0" cellpadding="0" cellspacing="0" width="1000">
						<tr><td align="left"></td><td align="right"><input type="button" value="New Entry" onclick="location.href='allowance_regist.php'"></td></tr>
						</table>
						<table class="list_table">
						<thead>
							<tr>
							<th colspan="3">Works on <?php echo $s_date;?>, Not in allowance yet</th>
							</tr>
							<tr>
							<th>Name</th>
							<th>Amounts</th>
							<th>Add</th>
							</tr>
						</thead>
						<tbody>	
						<?php
							$not_in = array();
							
							$Query  = "SELECT DISTINCT CONCAT_WS(', ',last_name, first_name )  as employee_name, j.employee_id ";
							$Query .= " FROM job j, employee e WHERE 1=1 AND j.employee_id = e.id AND DATE_FORMAT( j.job_date,'%m-%Y') = '$s_date' AND j.employee_id NOT IN (SELECT DISTINCT(employee_id) FROM allowance WHERE month_year = '$s_date') ORDER BY employee_name ";
								
							$id_cnn = mysql_query($Query) or exit(mysql_error());
							while($id_rst = mysql_fetch_assoc($id_cnn)) {
								$not_in = array_merge($not_in, array($id_rst));
							}
							//echo $Query;							
							//echo count($list_Records);
							$not_in_cnt = count($not_in);
							
							
							if($not_in_cnt > 0) {
							for($i=0; $i<count($not_in); $i++) {
								
								if($i%2 == 0){
									$even_odd = ' class="even not_in' . $warn. '" ';
								} else
									$even_odd = ' class="odd not_in' . $warn. '" ';
									
						?>
						<tr align="center" <?=$bgcolor?> <?php echo $even_odd;?> id="n_<?php echo $not_in[$i]['employee_id'];?>">
							<input type="hidden" name="n_date<?php echo $not_in[$i]['employee_id'];?>" value="<?php echo $s_date;?>">
							<td class="left"><b><?php echo $not_in[$i]["employee_name"]?></b></td>
							<td class="right" ><input type="text" name="n_amounts<?php echo $not_in[$i]['employee_id'];?>" style="width:100px;"> </td>
							<td><span class='ui-icon ui-icon-arrowthick-1-n cursor'></span></td>
						</tr>
						<?php
							}
							} else {
								echo "<Tr><Td></td></tr>";
							}
						?>
						</tbody>
						</table>
						<br>
					</td>
				</tr>
				<tr><td align="center"><?php include_once "paging.php"?></td></tr>
				</table>
				<!-- CONTENTS END -------------------------------------------------------------------------------------------->
			</td>
			<td>
			</td>
		  </tr>
		  <tr><td colspan="2" height="50"></td></tr>
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
	<?php include_once "bottom.php"; ?>
	<!-- BOTTOM END -------------------------------------------------------------------------------------------->
	</td>
</tr>
</table>
</BODY>
</HTML>
<?php ob_flush(); ?>