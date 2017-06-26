<?php
ob_start();
include_once "include/logincheck.inc";
include_once "include/common.inc";
include_once "include/dbconn.inc";
include_once "include/myfunc.inc";
include_once "htmlinclude/head.php";

$action_type = $_REQUEST["action_type"];
$limitList = $_REQUEST["limitList"];
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
	
	$("#n_t_date").datepicker($.datepicker.regional['en-GB']);
	$("#n_t_date").datepicker( "option", "firstDay", 1 );
	$("#n_t_date").datepicker();
	
	$("#n_pay_start").datepicker({ 
		beforeShowDay: function(date){ 
			return [date.getDay() ==1,''];
		}
	});
	$("#n_pay_start").datepicker($.datepicker.regional['en-GB']);
	
	$("#s_date").datepicker({ 
		beforeShowDay: function(date){ 
			return [date.getDay() ==1,''];
		}
	});
	$("#s_date").datepicker($.datepicker.regional['en-GB']);
	
	
	$( "#dialog:ui-dialog" ).dialog( "destroy" );
		
		var n_t_date = $("#n_t_date"),
			n_pay_start = $("#n_pay_start"),
			allFields = $( [] ).add( n_t_date ).add(n_pay_start),
			tips = $( ".validateTips" );

		function updateTips( t ) {
			tips
				.text( t )
				.addClass( "ui-state-highlight" );
			setTimeout(function() {
				tips.removeClass( "ui-state-highlight", 1500 );
			}, 500 );
		}

		function checkValue( o, n) {
			if ( o.val() == "" ) {
				o.addClass( "ui-state-error" );
				updateTips( "Please check " + n );
				return false;
			} else {
				return true;
			}
		}
		
		
		$( "#dialog-form" ).dialog({
			autoOpen: false,
			height: 370,
			width: 360,
			modal: true,
			buttons: {
				"Add": function() {
					var bValid = true;
					allFields.removeClass( "ui-state-error" );
					bValid = bValid && checkValue( n_pay_start, "Pay period date");
					bValid = bValid && checkValue( n_t_date, "Transaction Date");
												 
					if ( bValid ) {
						$('#processing').fadeIn(500);
						$.post("add_transaction.php",{
							n_pay_start : n_pay_start.val(),
							n_t_date : n_t_date.val()
							
						}, function(data){
							$('#processing').fadeOut(800);
							if(data == "SUCCESS") {
								location.reload();
							} else {
								alert('Failed!');
							}
						});
		
						$( this ).dialog( "close" );
					}
				},
				Cancel: function() {
					$( this ).dialog( "close" );
				}
			},
			close: function() {
				allFields.val( "" ).removeClass( "ui-state-error" );
			}
		});

		$( ".multi_entry" ).click(function() {
			$( "#dialog-form" ).dialog( "open" );
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
<tr>
	<td valign="top" width="191" height="100%">
	<!-- LEFT -------------------------------------------------------------------------------------------------->
	<?php include_once "left.php"; ?>
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
						<tr><td width="252"><img src="images/icon_circle03.gif">&nbsp;<span style="height:21px">Transaction List</span></td></tr>
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
								$s_Cond .= " AND transaction_period_start = '" .getAUDateToDB($s_date) . "'";
								$srch_param .= "&s_date=$s_date";
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
								$s_Sort = " ORDER BY transaction_period_start DESC, CONCAT_WS(', ',last_name, first_name ) ";
								$s_resort_order = 'transaction_period_start';
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
								<td height="30"  style="padding-left:5px; width:120px;"> Period 
								</td>
								<td style="padding-left:5px">
								<input type="text" size="30" name="s_date" id="s_date" value="<?php echo $s_date;?>">
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
							<th width="150" onclick="reSort('transaction_period_start')" <?php if($s_resort_order == 'transaction_period_start')
							{echo "class='sort_asc'";} 
							else echo "class='sort'";?>
							>Period</th>
							<th width="150" onclick="reSort('transaction_date')" <?php if($s_resort_order == 'transaction_date')
							{echo "class='sort_asc'";} 
							else echo "class='sort'";
							?>>Transaction date</th>
							<th onclick="reSort('name')" <?php if($s_resort_order == 'name')
							{echo "class='sort_asc'";} 
							else echo "class='sort'";
							?> >Name</th>
							<th >Gross</th>
							<th >Deductions</th>
							<th >NET</th>
							<th>&nbsp;</th>
							
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
							
							$total = getRowCount2("SELECT COUNT(*) FROM transaction t, employee e WHERE 1=1 AND t.employee_id = e.id ". $s_Cond);
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
							
							$Query  = "SELECT t.*, CONCAT_WS(', ',last_name, first_name )  as employee_name, date_add(transaction_period_start, interval 6 day) as transaction_period_end ";
							$Query .= " FROM transaction t, employee e where 1=1 AND t.employee_id = e.id " . $s_Cond . $s_Sort . "  LIMIT $start, $limitList  ";
								
							$id_cnn = mysql_query($Query) or exit(mysql_error());
							while($id_rst = mysql_fetch_assoc($id_cnn)) {
								$list_Records = array_merge($list_Records, array($id_rst));
								//print_r($list_Records);
								//echo "<p>";
							}							
							//echo count($list_Records);
							$cnt = count($list_Records);
							
							
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
									
								if($i%2 == 0){
									$even_odd = ' class="even ' . $warn. '" ';
								} else
									$even_odd = ' class="odd ' . $warn. '" ';
									
						?>
						<tr align="center" <?=$bgcolor?> <?php echo $even_odd;?>>
							<td height="25"><?=$total - (($limitList * ($page-1)) + $i)?></td>
							<td class="left"><a href="transaction_regist.php?id=<?=$list_Records[$i]["transaction_id"]?>&action_type=modify"><b><?php echo getAUDate($list_Records[$i]["transaction_period_start"])." to ".getAUDate($list_Records[$i]["transaction_period_end"])?></b></a></td>
							<td ><a href="transaction_regist.php?id=<?=$list_Records[$i]["transaction_id"]?>&action_type=modify"><b><?php echo getAUDate($list_Records[$i]["transaction_date"])?></b></a></td>
							<td class="left"><a href="transaction_regist.php?id=<?=$list_Records[$i]["transaction_id"]?>&action_type=modify"><b><?php echo $list_Records[$i]["employee_name"];?></b></a></td>
							<td class="right"><a href="transaction_regist.php?id=<?=$list_Records[$i]["transaction_id"]?>&action_type=modify"><b><?php echo "$".number_format($list_Records[$i]["gross_wages"],2,".",",")?></b></a></td>
							<td class="right"><a href="transaction_regist.php?id=<?=$list_Records[$i]["transaction_id"]?>&action_type=modify"><b><?php echo "$".number_format($list_Records[$i]["deductions"],2,".",",")?></b></a></td>
							<td class="right"><a href="transaction_regist.php?id=<?=$list_Records[$i]["transaction_id"]?>&action_type=modify"><b><?php echo "$".number_format($list_Records[$i]["net_wages"],2,".",",")?></b></a></td>
							<td><a href="transaction_regist.php?id=<?=$list_Records[$i]["transaction_id"]?>&action_type=modify">[EDIT]</a></td>
							<!-- <Td><a href="issue_payslip.php?transaction_id=<?=$list_Records[$i]["transaction_id"]?>" target="_blank"><img src="images/printButton.png"></a></td>-->				
						</tr>
						<?php
							}
							} else {
								echo "<tr><td colspan=8 height=40 align=center>Nothing to display</td></tr>";
							}
						?>
						</tbody>
						</table>
						</form>
						<br>
						<table border="0" cellpadding="0" cellspacing="0" width="1000">
						<tr><td align="left"><input type="button" class="multi_entry" value="Multi Entry" ></td><td align="right"><input type="button" value="New Entry" onclick="location.href='transaction_regist.php'"></td></tr>
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
<div id="dialog-form" title="Multi Entry">
	<p class="validateTips">All form fields are required.</p>
	<form>
		<fieldset>
		<label for="n_pay_start">Pay Period Start</label>
		<input type="text" name="n_pay_start" id="n_pay_start"><br />
		<label for="n_t_date">Transaction Date</label>
		<input type="text" name="n_t_date" id="n_t_date" ><br />
		</fieldset>
	</form>
</div>
</BODY>
</HTML>
<?php ob_flush(); ?>
