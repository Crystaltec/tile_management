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
	$('.ui-widget-content').css({'background-image' :'none','background-color':'transparent'});
	
		
	$("#s_date").datepicker($.datepicker.regional['en-GB']);
	$("#s_date").datepicker( "option", "firstDay", 1 );
	$("#s_date").datepicker();
	
	$("#n_t_date").datepicker($.datepicker.regional['en-GB']);
	$("#n_t_date").datepicker( "option", "firstDay", 1 );
	$("#n_t_date").datepicker();
	
	$('.list_table').fixheadertable({ caption : '',height:600,colratio:['40','100','140','140','140','140','140','140','140','80','80'], whiteSpace: 'normal'});
	
	$(".update_job").click(function() {
		var re_id = $(this).attr('id').split(":")[0];
		var re_date = $(this).attr('id').split(":")[1];
		left1 = (screen.width/2)-(500/2);
		top1 = (screen.height/2)-(470/2);
		new_window = window.open('update_job.php?id='+re_id+'&re_date='+re_date,'','width=500,height=470,top='+top1+',left='+left1);
		if (window.focus) {
			new_window.focus();
		}
		return false;
	});
	
	$(".attendance").click(function() {
		var a_id = $(this).attr('id').substring(4);
		var a_val = $(this).html();
		var current_id = $(this).attr('id');
		
		if (a_val) {
			$.post("update_attendance.php", {
				id : a_id,
				val : a_val
				
			}, function(data) { 
				if (data.attendance != 'ERROR') {
					//$("#"+current_id).parent().html("<span class='attendance' id='"+current_id+"'>"+data.attendance+"</span>");
					/*
					if (data.attendance == 'A') {
						$("#"+current_id).addClass('absence');
					} else {
						$("#"+current_id).removeClass('absence');
					}
					$("#"+current_id).html(data.attendance);
					*/
					$("form[name=searchform]").submit();
				} else {
					alert('Please try again');
				}
			}, "json");
		}
		return false;
	});
	
	$(".add_transaction").click(function() {
			var re_id = $(this).attr('id');
			var re_date = $(this).attr('id').split(":")[1];
			left1 = (screen.width / 2) - (500 / 2);
			top1 = (screen.height / 2) - (300 / 2);
			new_window = window.open('add_trans_popup.php?id=' + re_id + '&re_date=' + re_date, '', 'width=500,height=300,top=' + top1 + ',left=' + left1);
			if(window.focus) {
				new_window.focus();
			}
			return false;
		});
	
	$(".update_transaction").click(function() {
			var re_id = $(this).attr('id');
			left1 = (screen.width / 2) - (500 / 2);
			top1 = (screen.height / 2) - (300 / 2);
			new_window = window.open('update_trans_popup.php?id=' + re_id, '', 'width=500,height=300,top=' + top1 + ',left=' + left1);
			if(window.focus) {
				new_window.focus();
			}
			return false;
	});
			
	$( "#dialog:ui-dialog" ).dialog( "destroy" );
		
		var n_clear_date = $("#n_clear_date"),
			allFields = $( [] ).add( n_clear_date ),
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
				"Update": function() {
					var bValid = true;
					allFields.removeClass( "ui-state-error" );
					/*&& checkValue( n_clear_date, "Clear Date")*/
					bValid = bValid ;
												 
					if ( bValid ) {
						$('#processing').fadeIn(500);
						$.post("add_clear_date.php",{
							'p_pon' : $(this).data('p_pon'),
							n_clear_date : n_clear_date.val()
							
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

		$( ".pay" ).click(function() {
			/* ,'p_existed_date':$(this).attr('class').split("clear_date")[1]*/
			$( "#dialog-form" ).data({'p_pon':$(this).attr('id')}).dialog( "open" );
		});
		
});
</script>
<style>
#dialog-form label,
#dialog-form select { float: left; margin-right: 10px; }
#dialog-form fieldset { border:0; }
.ui-dialog {width:310px !important; }
.ui-dialog .ui-state-error { padding: .3em; }
.ui-dialog-content {padding-left:0 !important;}
#dialog-form { height:110px !important; width:300px !important;}

#dialog-form input,
#dialog-form select { float:left; display:block !important; margin:0 0 5px 0 !important; }
#dialog-form label { width:80px; display:inline-block !important; clear:both;}
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
				<table border="0" cellpadding="0" cellspacing="0" width="1300">
				<tr>
					<td valign="top">
					<table border="0" cellpadding="0" cellspacing="0" width="1300" class="font11_bold">
						<tr><td height="8"></td></tr>
						<tr><td width="252"><img src="images/icon_circle03.gif">&nbsp;<span style="height:21px">Payroll List</span></td></tr>
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
							
							if($s_name != "") {
								$s_Cond .= " AND CONCAT_WS(', ',last_name, first_name ) like '%". $s_name ."%'";
								$srch_param .= "&s_name=$s_name";
							}

							if($s_id != "") {
								$s_Cond .= " AND job_id like '%". $s_id ."%'";
								$srch_param .= "&s_id=$s_id";
							}
							
							//$srch_param = urlencode($srch_param);
							
							if($s_resort_order != ""){
								if ($s_resort_order == "name") {
									$s_Sort = " ORDER BY CONCAT_WS(', ',last_name, first_name ), project_name ";
								} else if ($s_resort_order == "project_name"){
									$s_Sort = " ORDER BY " . $s_resort_order . ", CONCAT_WS(', ',last_name, first_name ) " ;
								} 
								
								$srch_param .= "&resort_order=$s_resort_order";
							}
							
							if($limitList) {
								$srch_param .="&limitList=$limitList";
							}
													
						?>
						<form name="searchform">
						<table border="0" cellpadding="0" cellspacing="0" height="40" valign="bottom" width="1300">									<tr class='ui-widget-header'>
								<td width="120" height="30"  style="padding-left:5px"> Date
								</td>
								
								<td style="padding-left:5px">
								<?php 
								$s_date = $_REQUEST['s_date'];
								
								// 서버 시간을 호주 시간에 맞게 불러옴
								if (!$s_date) { 
									$s_date = date('d-m-Y', time() + (3600 * 14)); 
									$_s = getdate(strtotime(getAUDateToDB($s_date)));
									
									if($_s['weekday'] == "Monday") {
										
										$s_db_date = getAUDateToDB($s_date);
									} else {
										$s_date = date('d-m-Y', strtotime('last monday ',strtotime(getAUDateToDB($s_date)))); 
										$s_db_date = getAUDateToDB($s_date);
									}
								} else {
								
									$_s = getdate(strtotime(getAUDateToDB($s_date)));
									if($_s['weekday'] == "Monday") {
										//$s_date = date('d-m-Y', strtotime('this monday ',strtotime(getAUDateToDB($s_date)))); 
										$s_db_date = getAUDateToDB($s_date);
									} else {
										$s_date = date('d-m-Y', strtotime('last monday ',strtotime(getAUDateToDB($s_date)))); 
										$s_db_date = getAUDateToDB($s_date);
									}
								}
								
								?>
								<input type="text" name="s_date" id="s_date" size="10" value="<?php echo $s_date;?>" />
									
							</td>
							<td height="30"  style="padding-left:5px"> Name
								</td>
								<td style="padding-left:5px">
								<input type="text" size="30" name="s_name" value="<?php echo $s_name;?>">
								</td>
							</tr>
							<tr><td colspan="4" background="images/bg_check02.gif" height="3"></td></tr>
							<!-- <tr class='ui-widget-header'>
								<td width="120" height="30"  style="padding-left:5px"> Job ID
								</td>
								<td style="padding-left:5px">
								<input type="text" size="30" name="s_id" value="<?php echo $s_id;?>">
								</td>
							</tr>
					
							<tr><td colspan="2" background="images/bg_check02.gif" height="3"></td></tr>
						-->
							<tr>
							<td colspan="4" align="right" height="30">
							<span style="float:left;">Items per page:
							<select name="limitList" id="limitList" onchange="searchNow();">
							<option value="200" <?php if($limitList == 200) echo "selected";?> >200</option>
							<option value="99999" <?php if($limitList == 99999) echo "selected";?> >All</option>
								</select>
														
							</span>
						<input type="button" Value="Search" id='searchnow' onclick="searchNow()">
						</td>
						</tr>
						</table>
						
						<br>
				
						<table class="list_table" cellspacing="1" cellpadding="0" border="0" >
						<thead >
							<th width="40" >No</th>
							<th width="100" ><span onclick="reSort('name')">Name</span></th>
							<?php
							$date_array = '';
							for ($i=0; $i<=6; $i++ ) {
									$date_array[$i] = date("d-m-Y D", strtotime("$s_db_date + $i day"));
									echo "<th>".$date_array[$i]."</th>";
							} ?>
							<th width="80">Week Total</th>
							<th width="80">T</th>
							<th width="20">&nbsp;</th>
						</thead>
						<input type="hidden" name="resort_order" id="resort_order" value="">
						
						<?php
							// 페이지 계산 ////////////////////////
							$page = $_REQUEST["page"];
							if(!$page)
								$page = 1;
						
							$limitPage = 10;
							
							if (!$_REQUEST["limitList"]) {
								$limitList = 200;
							} else
								$limitList = $_REQUEST["limitList"];
							
							$s_Cond .= " AND (e.termination_date >= '". $s_db_date ."' or e.termination_date = '0000-00-00') ";
								
							$total = getRowCount2("SELECT COUNT(*) FROM employee e WHERE 1=1 ". $s_Cond);
							//echo ceil(1.2);
							$totalPage = ceil($total/$limitList);
							$block = ceil($page/$limitPage);
							$start = ($page-1)*$limitList;

							$startPage = ($block-1)*$limitPage + 1;
							$endPage = $startPage + $limitPage - 1;
							if ($endPage > $totalPage ) $endPage = $totalPage; 
							// 페이지 계산 끝////////////

							if (!$s_Sort) {
								$s_Sort = " ORDER BY employee_name ";
							}
							
							$s_Sort .= "  ";
							
							## 쿼리, 담을 배열 선언
							$list_Records = array();
							
							$Query  = "SELECT id,CONCAT_WS(', ',last_name, first_name ) as employee_name, IF(phone_number,phone_number,mobile_number) as contacts  "; 
							
							$Query .= " FROM employee e WHERE 1=1 ". $s_Cond .  $s_Sort . " LIMIT $start, $limitList";
							
							$id_cnn = mysql_query($Query) or exit(mysql_error());
							while($id_rst = mysql_fetch_assoc($id_cnn)) {
								$list_Records = array_merge($list_Records, array($id_rst));
								//print_r($list_Records);
								//echo "<p>";
							}							
							
							$cnt = count($list_Records);
							if($cnt > 0) {
							for($i=0; $i<count($list_Records); $i++) {
								
								$bgcolor = "";
															
								if ($i%2 == 0) {
									$even_odd = " class='even' ";
								} else {
									$even_odd = " class='odd' ";
								}
						?>
						<?php 
							echo "<tbody><tr align='center' $bgcolor  $even_odd >";
							
							echo  "<td height='22' style='width:44px;'>".($total - (($limitList * ($page-1)) + $i))."</td>".
								  "<td class='left' style='width:100px;'>".$list_Records[$i]["employee_name"]."<br/>".$list_Records[$i]["contacts"]."</td>";			  
							$weekly_total = 0;
							for ($k=0; $k<=6; $k++) {
								
								echo "<td class='detail' style='width:140px;' id='p_".$list_Records[$i]['id'].":".date('Y-m-d',strtotime("$s_db_date +$k day"))."'>";
								$sql  = " SELECT IF(job_session='FULL',1* job_session_rates * fw.wages_amount,IF(job_session='HALF',0.5*job_session_rates * fw.wages_amount,0)) AS job_normal, job_extra_hour * job_extra_hour_rates * hw.wages_amount AS job_extra, job_session, job_session_rates, job_extra_hour, job_extra_hour_rates, travel_fee, parking_fee, tool_fee, extra_fee,  f_wages_id, h_wages_id, j.project_id, p.project_name, id,attendance FROM job j, project p, wages fw, wages hw ".
								" WHERE j.project_id = p.project_id AND j.f_wages_id = fw.wages_id and j.h_wages_id = hw.wages_id and j.employee_id = fw.employee_id and j.employee_id = hw.employee_id and j.employee_id = '".$list_Records[$i]['id']. "' AND job_date = DATE_FORMAT(DATE_ADD('".$s_db_date."', interval $k day),'%Y-%m-%d') ORDER BY job_date, job_session DESC, project_name ";
								
								$result = mysql_query($sql) or exit(mysql_error());
								if ($result) {
										
									$_day_normal = 0;
									$_day_extra = 0;
									$_day_total = 0;
									$_day_aux_fee = 0;
									while ($row = mysql_fetch_assoc($result)) {
											
										$absence_class = "";
										if ($row['attendance'] == 'A') {
											$absence_class = "absence";
										}	
									
										$_day_normal = $row['job_normal'];
										$_day_extra = $row['job_extra'];
										if ($row['attendance'] == 'A') {
											$_day_normal = 0;
											$_day_extra = 0;
										} 
										
										$_day_total = $_day_normal + $_day_extra + $row['travel_fee'] + $row['parking_fee'] + $row['tool_fee'] + $row['extra_fee'];
										$_day_aux_fee = $row['travel_fee'] + $row['parking_fee'] + $row['tool_fee'] + $row['extra_fee'];
										
										$_t = "";
										$_p = "";
										$_to = "";
										$_e = "";
										$_s = "";
										$_ex = "";
										
										if ($_day_normal <> '0')
											$_s = 'S'. $_day_normal;
										if ($_day_extra <> '0')
											$_ex = 'Ex'.$_day_extra;
										if($row['travel_fee'] <> '0' )
											$_t = 'T'.$row['travel_fee'];
										if($row['parking_fee'] <> '0')
											$_p = 'P'.$row['parking_fee'];
										if ($row['tool_fee'] <> '0')
											$_to = 'To'.$row['tool_fee'];
										if ($row['extra_fee'] <> '0')
											$_e = 'E'.$row['extra_fee'];
																			
											
										if ($row['attendance'] == 'A') {
											$weekly_total += $_day_aux_fee;	
											
											echo "<span class='nill update_job ' id='".$row['id'].":".date('Y-m-d',strtotime("$s_db_date +$k day"))."' >"."<span class='attendance $absence_class' id='att_".$row['id']."'>". $row['attendance']."</span> ".$row['project_name']. " <span class='price font12'>$" .number_format($_day_aux_fee,2,".",",") . "</span> ".$_t.$_p.$_to.$_e."</span>";
											
										} else {
											$weekly_total += $_day_total;
											//echo $weekly_total;
											
											if (($row['job_session'] && $row['job_session_rates'] > 0) || ($row['job_extra_hour'] > 0 && $row['job_extra_hour_rates'] > 0)) {
												echo "<span class='".strtolower($row['job_session'])." update_job' id='".$row['id'].":".date('Y-m-d',strtotime("$s_db_date +$k day"))."'>"."<span class='attendance $absence_class' id='att_".$row['id']."'>". $row['attendance']."</span> ".$row['project_name']. " <span class='price font12'>$" .number_format($_day_total,2,".",",") . "</span> ".$_s.$_ex." ".$_t.$_p.$_to.$_e."</span>";
											}

										}
									}
									
								} 
								mysql_free_result($result);
								
								echo "</td>";	
													
							}
							echo "<td style='width:83px;'><span class='price font12'>$".number_format($weekly_total,2,".",",")."</span></td>";
							
							$sql_trans = "SELECT gross_wages, net_wages, deductions, transaction_id FROM transaction WHERE employee_id = '".$list_Records[$i]['id']."' AND transaction_period_start = '".$s_db_date."'";
							
							$transaction = getRowCount($sql_trans);
							
							$pay_text = "";
							if ($transaction[0] == 0 && $weekly_total == 0) {
								$pay_text = "";
								echo "<td style='width:80px;'>$pay_text</td>";
							} elseif ($transaction[3] && $weekly_total <> 0) {
								$pay_text = "G".number_format($transaction[0],2,".",",")." - "."D".number_format($transaction[2],2,".",","). " = "."N".number_format($transaction[1],2,".",",");
								$bal = $transaction[0] - $transaction[1]-$transaction[2];
								$bal_class = "";
								if ($bal > 0) {
									$bal_class = " class='blue' ";
								} elseif ($bal < 0) {
									$bal_class = " class='red' ";
								}
								echo "<td style='width:83px;' class='update_transaction' id='$transaction[3]'>$pay_text<br/><span $bal_class >".number_format($bal,2,".",",")."</span></td>";
							} else {
								echo "<td style='width:83px;' ><button class='add_transaction' id='".$list_Records[$i]['id'].":$s_db_date'><span class='ui-icon ui-icon-plusthick' ></span></button></td>";
							}
							
							echo "</tr>
							</tbody>";
							?>
												
						<?php
								}
							} else {
								echo "<tbody><tr><td colspan=11 height=40 align=center>Nothing to display</td></tr></tbody>";
							}
						?>
						</table>
						</form>
					</td>
				</tr>
				<tr><td align="center"><?php // include_once "paging.php"?></td></tr>
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
	<?php include_once "bottom.php"; ?>
	<!-- BOTTOM END -------------------------------------------------------------------------------------------->
	</td>
</tr>
</table>
<div id="special"></div>
<div id="dialog-form" title="Pay transaction">
	<p class="validateTips">All form fields are required.</p>
	<form>
		<fieldset>
		<label for="n_date">Transaction date</label>
		<input type="text" name="n_t_date" id="n_t_date" ><br />
		</fieldset>
	</form>
</div>
</BODY>
</HTML>
<?php ob_flush(); ?>