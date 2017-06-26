<?php
ob_start();
include_once "include/logincheck.inc";
include_once "include/common.inc";
include_once "include/dbconn.inc";
include_once "include/myfunc.inc";
include_once "htmlinclude/head.php";

$action_type = $_REQUEST["action_type"];
$limitList = $_REQUEST["limitList"];
$list_view = $_REQUEST["list_view"];
if ($list_view == '')
	$list_view = 'e';
?>
<script language="Javascript">
	function searchNow() {
var f = document.searchform;
f.action="<?=$_SERVER['PHP_SELF']?>";
f.submit();
}

function changeView(param) {
var f = document.searchform;
f.list_view.value = param;
f.action="<?=$_SERVER['PHP_SELF']?>";
f.submit();
}

function reSort(param) {
var f = document.searchform;
f.resort_order.value = param;
f.action="<?=$_SERVER['PHP_SELF']?>
	";
	f.submit();
	}

	function getMonday(d) {
		var day = d.getDay(), diff = d.getDate() - day + (day == 0 ? -6 : 1);
		// adjust when day is sunday
		return new Date(d.setDate(diff));
	}

	$(function() {

		$("input:button, button").button();
		$(".list_table thead").addClass('ui-widget-header');
		$(".list_table tbody").addClass('ui-widget-content');
		$('.ui-widget-content').css({
			'background-image' : 'none',
			'background-color' : 'transparent'
		});

		$("#s_date").datepicker($.datepicker.regional['en-GB']);
		$("#s_date").datepicker("option", "firstDay", 1);
		$("#s_date").datepicker();

		$(".multiselect").multiselect({
			selectedList : 4
		});

		$('.list_table').fixheadertable({ caption : '',height:600, minColWidth:140,colratio:['40','140','140','140','140','140','140','140','140'], whiteSpace: 'normal'});
		
		$('#search_name').qtip({
			content : 'If employee view, name field for employee name. If projece view, name field for project name',
			position : {
				adjust : {
					x : -10,
					y : -70
				}
			},
			style : {
				width : 280,
				padding : 2,
				color : 'black',
				textAlign : 'center',
				border : {
					width : 1,
					radius : 3,
				},
				tip : 'bottomLeft',
				name : 'cream'
			}
		});

		$(".add_job").click(function() {
			var re_id = $(this).attr('id').split(":")[0];
			var re_date = $(this).attr('id').split(":")[1];
			var re_list_view = $("#list_view").val();
			left1 = (screen.width / 2) - (500 / 2);
			top1 = (screen.height / 2) - (550 / 2);
			new_window = window.open('add_job.php?id=' + re_id + '&re_date=' + re_date + '&re_list_view=' + re_list_view, '', 'width=500,height=550,top=' + top1 + ',left=' + left1);
			if(window.focus) {
				new_window.focus();
			}
			return false;
		});


		$(".update_job").click(function() {
			var re_id = $(this).attr('id');
			left1 = (screen.width / 2) - (500 / 2);
			top1 = (screen.height / 2) - (550 / 2);
			new_window = window.open('update_job.php?id=' + re_id, '', 'width=500,height=550,top=' + top1 + ',left=' + left1);
			if(window.focus) {
				new_window.focus();
			}
			return false;
		});

		$(".all_move").click(function() {
			var m = $(this).attr('id').split(':');
			if (m[0].substring(2) && m[1]){
				left1 = (screen.width / 2) - (500 / 2);
				top1 = (screen.height / 2) - (350 / 2);
				new_window = window.open('move_job.php?pid=' + m[0].substring(2)+'&date='+m[1], '', 'width=500,height=350,top=' + top1 + ',left=' + left1);
				if(window.focus) {
					new_window.focus();
				}
			}
			return false;	
		});
		
		$(".all_del").click(function() {
			var d = $(this).attr('id').split(':');
			
			if (d[0].substring(2) && d[1]){
				if(confirm('Are you sure?')) {
					$.post("delete_job.php", {
						id : d[0].substring(2),
						date : d[1]
					}, function(data) {
						if(data.result != 'ERROR') {
							$("form[name=searchform]").submit();
						} else {
							alert('Please try again');
						}
					}, "json");	
				}
			}
			return false;	
		});
		
		$(".attendance").click(function() {
			var a_id = $(this).attr('id').substring(4);
			var a_val = $(this).html();
			var current_id = $(this).attr('id');

			if(a_val) {
				$.post("update_attendance.php", {
					id : a_id,
					val : a_val

				}, function(data) {
					if(data.attendance != 'ERROR') {
						if(data.attendance == 'A') {
							$("#" + current_id).addClass('absence');
						} else {
							$("#" + current_id).removeClass('absence');
						}
						$("#" + current_id).html(data.attendance);
					} else {
						alert('Please try again');
					}
				}, "json");
			}
			return false;
		});

		$(".contact").click(function() {
			var ca_id = $(this).attr('id').substring(5);
			var ca_val = $(this).html();
			var current_id = $(this).attr('id');

			if(ca_val) {
				$.post("update_call.php", {
					id : ca_id,
					val : ca_val

				}, function(data) {
					if(data.check_call != 'ERROR') {
						if(data.check_call == 'Y') {
							$("#" + current_id).addClass('ui-icon ui-icon-check-1');
							$("#" + current_id).removeClass('ui-icon-mobilephone-1');
						} else {
							$("#" + current_id).addClass('ui-icon-mobilephone-1');
							$("#" + current_id).removeClass('ui-icon ui-icon-check-1');
						}
						$("#" + current_id).html(data.check_call);
					} else {
						alert('Please try again');
					}
				}, "json");
			}
			return false;
		});
	});

</script>
<BODY leftmargin="0" topmargin="0">
	<table border="0" cellpadding="0" cellspacing="0" width="100%">
		<tr>
			<td valign="top" width="191" height="100%"><!-- LEFT --------------------------------------------------------------------------------------------------><?php
			include_once "left.php";
			?>
			<!-- LEFT END ----------------------------------------------------------------------------------------------></td>
			<!-- LEFT BG------------------------------------------------------------------------------------------------>
			<td width="1" bgcolor="#DFDFDF"></td>
			<!-- LEFT BG END-------------------------------------------------------------------------------------------->
			<td valign="top"><!-- BODY -------------------------------------------------------------------------------------------------->
			<table border="0" cellpadding="0" cellspacing="0" width="100%">
				<!-- BODY TOP ------------------------------------------------------------------------------------------->
				<tr>
					<td style="padding-left:15px"><?php
					include_once "top.php";
					?></td>
				</tr>
				<!-- BODY TOP END --------------------------------------------------------------------------------------->
				<!-- BODY CENTER ----------------------------------------------------------------------------------------->
				<tr>
					<td width="100%">
					<table border="0" cellpadding="0" cellspacing="0" width="100%">
						<tr>
							<td style="padding-left:15px" valign="bottom" height="14" colspan="2"></td>
						</tr>
						<tr>
							<td style="padding-left:15px" valign="top"><!-- CONTENTS -------------------------------------------------------------------------------------------->
							<table border="0" cellpadding="0" cellspacing="0" width="1160">
								<tr>
									<td valign="top">
									<table border="0" cellpadding="0" cellspacing="0" width="1160" class="font11_bold">
										<tr>
											<td width="252"><img src="images/icon_circle03.gif">&nbsp;<span style="height:21px">Job Order List</span></td>
										</tr>
										<tr>
											<td height="3"></td>
										</tr>
										<tr>
											<td background="images/bg_check.gif" height="3"></td>
										</tr>
									</table></td>
								</tr>
								<tr>
									<td valign="top"><?php

									//Search Value
									$s_Cond = "";
									$s_Cond_project = "";
									$s_name = $_REQUEST["s_name"];
									$s_id = $_REQUEST["s_id"];
									$s_resort_order = $_REQUEST["resort_order"];
									$s_project_id = $_REQUEST["s_project_id"];
									$visa_id = $_REQUEST["visa_id"];

									if ($s_name != "") {
										if ($list_view == "e") {
											$s_Cond .= " AND CONCAT_WS(', ',last_name, first_name )  like '%" . $s_name . "%'";
											$srch_param .= "&s_name=$s_name";
										} else if ($list_view == "p") {
											$s_Cond .= " AND project_name like '%" . $s_name . "%'";
											$srch_param .= "&s_name=$s_name";
										}
									}

									$str_visa = "";
									if (isset($visa_id)) {
										foreach ($visa_id as $i => $value) {
											if ($str_visa) {
												$str_visa .= "," . $visa_id[$i];
											} else {
												$str_visa = $visa_id[$i];
											}
										}

										if ($list_view == "e") {
											$s_Cond .= " AND e.visa_id IN (" . $str_visa . ") ";
										} else if ($list_view = "p") {
											$s_Cond_project .= " AND e.visa_id IN (" . $str_visa . ") ";
										}

										$srch_param .= "&visa_id=$visa_id";
									}

									if ($s_id != "") {
										$s_Cond .= " AND job_id like '%" . $s_id . "%'";
										$srch_param .= "&s_id=$s_id";
									}

									//$srch_param = urlencode($srch_param);

									if ($s_resort_order != "") {
										if ($s_resort_order == "name") {
											$s_Sort = " ORDER BY CONCAT_WS(', ',last_name, first_name ) , project_name ";
										} else if ($s_resort_order == "project_name") {
											$s_Sort = " ORDER BY " . $s_resort_order . ", CONCAT_WS(', ',last_name, first_name )  ";
										}

										$srch_param .= "&resort_order=$s_resort_order";
									}

									if ($limitList) {
										$srch_param .= "&limitList=$limitList";
									}

									if ($list_view) {
										$srch_param .= "&list_view=$list_view";
									}
									?>
									<form name="searchform">
										<table border="0" cellpadding="0" cellspacing="0" height="40" valign="bottom" width="1160">
											<tr class='ui-widget-header'>
												<td width="120" height="30"  style="padding-left:5px"> Date </td>
												<td style="padding-left:5px"><?php
												$s_date = $_REQUEST['s_date'];

												// 서버 시간을 호주 시간에 맞게 불러옴
												if (!$s_date) {
													$s_date = date('d-m-Y', time() + (3600 * 14));
													$_s = getdate(strtotime(getAUDateToDB($s_date)));

													if ($_s['weekday'] == "Monday") {

														$s_db_date = getAUDateToDB($s_date);
													} else {
														$s_date = date('d-m-Y', strtotime('last monday ', strtotime(getAUDateToDB($s_date))));
														$s_db_date = getAUDateToDB($s_date);
													}
												} else {

													$_s = getdate(strtotime(getAUDateToDB($s_date)));

													if ($_s['weekday'] == "Monday") {

														$s_db_date = getAUDateToDB($s_date);
													} else {
														$s_date = date('d-m-Y', strtotime('last monday ', strtotime(getAUDateToDB($s_date))));
														$s_db_date = getAUDateToDB($s_date);
													}
												}

												if ($s_date) {
													$srch_param .= "&s_date=" . $s_date;
												}
												?>
												<input type="text" name="s_date" id="s_date" size="10" value="<?php echo $s_date;?>" />
												</td>
												<td height="30"  style="padding-left:5px"><span id="search_name">Name</span>
												<input type="text" size="30" name="s_name" value="<?php echo $s_name;?>">
												</td>
												<td>Visa
												<select id="visa_id" class="multiselect" multiple="multiple" name="visa_id[]" style="width:190px;">
													<?php

													$Query = "";
													$Query  = "SELECT * ";
													$Query .= " FROM visa ORDER BY visa_name";
													$id_cnn = mysql_query($Query) or exit(mysql_error());
													
													while($id_rst = mysql_fetch_assoc($id_cnn)) {
														$str_select = "";
														if (sizeof($visa_id) == 0 || !in_array($id_rst['visa_id'], $visa_id)) {
															 
														} else if (in_array($id_rst['visa_id'],$visa_id)){
															$str_select= "selected";
														}
													?>
													<option value="<?=$id_rst['visa_id']?>" <?php echo $str_select;?>><?=$id_rst['visa_name']
														?></option>
													<?php
													}
													?>
												</select></td>
											</tr>
											<tr>
												<td colspan="4" background="images/bg_check02.gif" height="3"></td>
											</tr>
											<!-- <tr class='ui-widget-header'>
											<td width="120" height="30"  style="padding-left:5px"> Job ID
											</td>
											<td style="padding-left:5px">
											<input type="text" size="30" name="s_id" value="<?php echo $s_id;?>">
											&nbsp;
											</td>
											</tr>
											<tr><td colspan="2" background="images/bg_check02.gif" height="3"></td></tr>
											-->
											<tr>
												<td colspan="4" align="right" height="30">
												<span style="float:left;">Items per page:
													<select name="limitList" id="limitList" onchange="searchNow();">
														<option value="200" <?php
														if ($limitList == 200)
															echo "selected";
														?> >200</option>
														<option value="999999" <?php
														if ($limitList == 999999)
															echo "selected";
														?> >All</option>
													</select>
													<input type="hidden" name="list_view" id="list_view" value="<?php echo $list_view;?>" />
												<span style="background-color:#FAEB78; width:20px; display:inline-block;">&nbsp;</span> NILL,
												<span style="background-color:rgb(218,238,243); width:20px; display:inline-block;">&nbsp;</span> Default and AM,
												<span style="background-color:rgb(253,233,217); width:20px; display:inline-block;">&nbsp;</span> PM,
												<span style="background-color:rgb(221,217,196); width:20px; display:inline-block;">&nbsp;</span> Night
											    
												</span>
												
												<input type="button" Value="Search" id='searchnow' onclick="searchNow()">
												</td>
											</tr>
											
											<tr>
												
												<Td colspan="4">
												<span style="float:left">
												<input type="button" value="New Entry" onclick="location.href='job_regist.php?<?php echo $srch_param;?>'">
												</span>
												<span style="float:left; padding-left:400px;">
												<input type="button" Value="Employee view" id="employee_view" onclick="changeView('e')" 
												<?php
												if ($list_view == 'e')
													echo 'class="ui-state-focus"';
												?> >
												<input type="button" Value="Project view" id="project_view"  onclick="changeView('p')" 
												<?php
												if ($list_view == 'p')
													echo 'class="ui-state-focus"';
												?> >
												</span>
												<span style="float:right">
												<input type="button" value="New Entry" onclick="location.href='job_regist.php?<?php echo $srch_param;?>'">
												</span></td>
											</tr>
											<tr>
												<td colspan="4"></td>
											</tr>
										</table>
										<table class="list_table" cellspacing="1" cellpadding="0" border="0" style="width: 1180px;">
											<?php
											// project view
											if ($list_view == 'p') {
											?>
											<thead>
												<th width="40" style="width:40px;">No</th>
												<th width="140"><span onclick="reSort('name')">Name</span></th>
												<?php
												/* 요일 가져오기 */

												$date_array = '';

												$date_count = array();
												$sql_p = "SELECT COUNT(DISTINCT employee_id) as count , job_date FROM job WHERE job_date between '$s_db_date' and date_add('$s_db_date', INTERVAL 6 day) GROUP BY job_date ";

												$result_p = mysql_query($sql_p) or exit(mysql_error());
												while ($result_r = mysql_fetch_assoc($result_p)) {
													$date_count = array_merge($date_count, array($result_r));
												}
												mysql_free_result($result_p);

												for ($i = 0; $i <= 6; $i++) {
													$date_array[$i] = date("d-m-Y D", strtotime("$s_db_date + $i day"));
													$date_p = date("Y-m-d", strtotime("$s_db_date + $i day"));
													//$sql_p = " SELECT COUNT(DISTINCT employee_id) FROM job WHERE job_date = '$date_p' ";
													$p_count = 0;
													
													for ($j=0; $j < count($date_count); $j++) {
														if ($date_p == $date_count[$j]['job_date']) {
															$p_count = $date_count[$j]['count'];
														}
														
													}
													
													echo "<th width='140' class='$date_p'>" . $date_array[$i] . "<small>(" . $p_count . ")</small>" . "</th>";
												}
												?>
											</thead>
											<input type="hidden" name="resort_order" id="resort_order" value="">
											<tbody>
											<?php
											// 페이지 계산 ///////////////////////
											$page = $_REQUEST["page"];
											if(!$page)
											$page = 1;
											
											$limitPage = 10;
											if (!$_REQUEST["limitList"]) {
											$limitList = 200;
											} else
											$limitList = $_REQUEST["limitList"];
											//echo "SELECT COUNT(*) FROM project WHERE 1=1  AND project_id IN ( SELECT project_id FROM job WHERE job_date BETWEEN '".$s_db_date."' AND DATE_FORMAT(DATE_ADD('".$s_db_date."', interval 6 day))) ". $s_Cond ." GROUP BY project_id ";
											$total = getRowCount2("SELECT COUNT(*) FROM project WHERE 1=1  AND project_id IN ( SELECT project_id FROM job WHERE job_date BETWEEN '".$s_db_date."' AND DATE_FORMAT(DATE_ADD('".$s_db_date."', interval 6 day),'%Y-%m-%d')) ". $s_Cond ." ");
											
											$totalPage = ceil($total/$limitList);
											$block = ceil($page/$limitPage);
											$start = ($page-1)*$limitList;
											
											$startPage = ($block-1)*$limitPage + 1;
											$endPage = $startPage + $limitPage - 1;
											if ($endPage > $totalPage ) $endPage = $totalPage;
											// 페이지 계산 끝//////////////////////
											
											if (!$s_Sort) {
												$s_Sort = " ORDER BY project_name ";
											}
											
											$s_Sort .= "  ";
											
											## 쿼리, 담을 배열 선언
											$list_Records = array();
											
											$Query  = "SELECT DISTINCT project_id as id, project_name ";
											
											$Query .= " FROM project WHERE 1=1 AND project_id IN ( SELECT DISTINCT project_id FROM job WHERE job_date AND job_date BETWEEN '".$s_db_date."' AND DATE_FORMAT(DATE_ADD('".$s_db_date."', interval 6 day),'%Y-%m-%d')) " .$s_Cond .  $s_Sort . " LIMIT $start, $limitList";
											
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
											
											echo "<tr align='center' $bgcolor  $even_odd >";
											
											echo  "<td height='22' style='width:44px;'>".($total - (($limitList * ($page-1)) + $i))."</td>".
											"<td class='left' style='width:140px;' ><a href='project_view.php?project_id=".$list_Records[$i]['id']."'>".$list_Records[$i]["project_name"]."</a></td>";
											
											for ($k=0; $k<=6; $k++) {
											echo "<td class='detail' id='p_".$list_Records[$i]['id'].":".date('Y-m-d',strtotime("$s_db_date +$k day"))."'>";
											$sql  = " SELECT j.*, CONCAT_WS(', ',last_name, first_name ) as employee_name FROM job j, employee e WHERE j.employee_id = e.id AND project_id = '".$list_Records[$i]['id'].
											"' AND job_date = DATE_FORMAT(DATE_ADD('".$s_db_date."', interval $k day),'%Y-%m-%d') ".$s_Cond_project." ORDER BY job_date, job_session, CONCAT_WS(', ',last_name, first_name ) ";
											
											$result = mysql_query($sql) or exit(mysql_error());
											
											$bflag = false;
											
											if ($result) {
											while ($row = mysql_fetch_assoc($result)) {
												$bflag = true;	
												$absence_class = "";
												
												if ($row['attendance'] == 'A') {
													$absence_class = "absence";
												}
											
												if ($row['check_call'] == 'Y') {
													$call_class = "ui-icon ui-icon-check-1";
												} else {
													$call_class = "ui-icon-mobilephone-1";
												}
											
												if ($row['attendance'] == 'A') {
													if ($row['job_session'] && $row['job_session_rates'] > 0) {
														echo "<span class='".strtolower($row['job_session'])." update_job ".strtolower($row['time'])."' id='".$row['id']."' >"."<span class='contact $call_class' id='call_".$row['id']."'>".$row['check_call']."</span>"."<span class='attendance $absence_class' id='att_".$row['id']."'>". $row['attendance']."</span> ".$row['employee_name']. " ". $row['job_session'];
														if($row['travel_fee'] <> 0 || $row["parking_fee"] <> 0) {
															echo " <span class='car'>C</span> ";
														}
													echo "</span>";
													}
											
													if ($row['job_extra_hour'] <> 0 && $row['job_extra_hour_rates'] >0) {
														echo "<span class='job_extra_hour update_job ".strtolower($row['time'])."' id='".$row['id']."' >"."<span class='contact $call_class' id='call_".$row['id']."'>".$row['check_call']."</span>"."<span class='attendance $absence_class' id='att_".$row['id']."'>". $row['attendance']."</span> ".$row['employee_name'] . " ". $row['job_extra_hour'] . "H";
														if($row['travel_fee'] <> 0 || $row["parking_fee"] <> 0) {
															echo " <span class='car'>C</span> ";
														}
															echo "</span>";
													}
													
													
												} else {
													if ($row['job_session'] && $row['job_session_rates'] > 0) {
														echo "<span class='".strtolower($row['job_session'])." update_job ".strtolower($row['time'])."' id='".$row['id']."' >"."<span class='contact $call_class' id='call_".$row['id']."'>".$row['check_call']."</span>"."<span class='attendance $absence_class' id='att_".$row['id']."'>". $row['attendance']."</span> ".$row['employee_name'] . " ". $row['job_session'];
														if($row['travel_fee'] <> 0 || $row["parking_fee"] <> 0) {
															echo " <span class='car'>C</span> ";
														}
													echo "</span>";
													}
											
													if ($row['job_extra_hour'] <> 0 && $row['job_extra_hour_rates'] >0) {
														echo "<span class='job_extra_hour update_job ".strtolower($row['time'])."' id='".$row['id']."' >"."<span class='contact $call_class' id='call_".$row['id']."'>".$row['check_call']."</span>"."<span class='attendance $absence_class' id='att_".$row['id']."'>". $row['attendance']."</span> ".$row['employee_name'] . " ". $row['job_extra_hour'] . "H";
														if($row['travel_fee'] <> 0 || $row["parking_fee"] <> 0) {
														echo " <span class='car'>C</span> ";
														}
													echo "</span>";
													}
													
													
												}
											}
											
											}
											mysql_free_result($result);
											
											if ($bflag) {
												// all move	
												echo "<input type='button' value='ALL MOVE' class='all_move' id='m_".$list_Records[$i]['id'].":".date('Y-m-d',strtotime("$s_db_date +$k day"))."'>";
												// all delete
												echo "<input type='button' value='ALL DEL' class='all_del' id='d_".$list_Records[$i]['id'].":".date('Y-m-d',strtotime("$s_db_date +$k day"))."'>";
											}
											
											echo "<button class='add_job' id='".$list_Records[$i]['id'].":".date('Y-m-d',strtotime("$s_db_date +$k day"))."'><span class='ui-icon ui-icon-plusthick' ></span></button></td>";
											}
											
											echo "</tr>
											</tbody>";
											?>

											<?php
											}
											} else {
											echo "<tbody><tr><td colspan=6 height=40 align=center>Nothing to display</td></tr></tbody>";
											}
											} else if ($list_view == 'e') {
											// employee view
											?>
											<thead >
												<th width="40" style="width:40px;">No</th>
												<th width="140"><span onclick="reSort('name')">Name</span></th>
												<?php
												$date_array = '';

												$date_count = array();
												$sql_p = "SELECT COUNT(DISTINCT employee_id) as count , job_date FROM job WHERE job_date between '$s_db_date' and date_add('$s_db_date', INTERVAL 6 day) GROUP BY job_date ";
												//echo $sql_p;
												$result_p = mysql_query($sql_p) or exit(mysql_error());
												while ($result_r = mysql_fetch_assoc($result_p)) {
													$date_count = array_merge($date_count, array($result_r));
												}
												mysql_free_result($result_p);

												for ($i = 0; $i <= 6; $i++) {
													$date_array[$i] = date("d-m-Y D", strtotime("$s_db_date + $i day"));
													$date_p = date("Y-m-d", strtotime("$s_db_date + $i day"));
													//$sql_p = " SELECT COUNT(DISTINCT employee_id) FROM job WHERE job_date = '$date_p' ";
													$p_count = 0;
													
													for ($j=0; $j < count($date_count); $j++) {
														if ($date_p == $date_count[$j]['job_date']) {
															$p_count = $date_count[$j]['count'];
														}
														
													}
													echo "<th width='140' class='$date_p'>" . $date_array[$i] . "<small>(" . $p_count . ")</small>" . "</th>";
												
												}
												?>
											</thead>
											<input type="hidden" name="resort_order" id="resort_order" value="">
											<tbody>
											<?php
											// 페이지 계산 ///////////////////////
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
											// 페이지 계산 끝//////////////////////
											
											if (!$s_Sort) {
											$s_Sort = " ORDER BY employee_name ";
											}
											
											$s_Sort .= "  ";
											
											## 쿼리, 담을 배열 선언
											$list_Records = array();
											
											$Query  = " SELECT id,CONCAT_WS(', ',last_name, first_name )  as employee_name, IF(phone_number,phone_number,mobile_number) as contacts ";
											
											$Query .= " FROM employee e WHERE 1=1" . $s_Cond.  $s_Sort . " LIMIT $start, $limitList";
											
											$id_cnn = mysql_query($Query) or exit(mysql_error());
											while($id_rst = mysql_fetch_assoc($id_cnn)) {
											$list_Records = array_merge($list_Records, array($id_rst));
											//print_r($list_Records);
											//echo "<p>";
											}
											
											//echo $Query;
											mysql_free_result($id_cnn);
											
											$cnt = count($list_Records);
											if($cnt > 0) {
											for($i=0; $i<count($list_Records); $i++) {
											
											$bgcolor = "";
											
											if ($i%2 == 0) {
											$even_odd = " class='even' ";
											} else {
											$even_odd = " class='odd' ";
											}
											
											echo "<tr align='center' $bgcolor  $even_odd >";
											
											echo  "<td height='22' style='width:44px;'>".($total - (($limitList * ($page-1)) + $i))."</td>".
											"<td class='left' style='width:140px;' ><a href='employee_regist.php?id=".$list_Records[$i]['id']."&action_type=modify'><b>".$list_Records[$i]["employee_name"]."</b><br/>".$list_Records[$i]["contacts"]."</a></td>";
											
											for ($k=0; $k<=6; $k++) {
											echo "<td class='detail' id='p_".$list_Records[$i]['id'].":".date('Y-m-d',strtotime("$s_db_date +$k day"))."'>";
											$sql  = " SELECT j.*, p.project_name FROM job j, project p WHERE j.project_id = p.project_id AND employee_id = '".$list_Records[$i]['id'].
											"' AND job_date = DATE_FORMAT(DATE_ADD('".$s_db_date."', interval $k day),'%Y-%m-%d') ORDER BY job_date, job_session, project_name ";
											//echo $sql;
											$result = mysql_query($sql) or exit(mysql_error());
											if ($result) {
											while ($row = mysql_fetch_assoc($result)) {
											$absence_class = "";
											if ($row['attendance'] == 'A') {
											$absence_class = "absence";
											}
											
											if ($row['check_call'] == 'Y') {
											$call_class = "ui-icon ui-icon-check-1";
											} else {
											$call_class = "ui-icon-mobilephone-1";
											}
											if ($row['attendance'] == 'A') {
												if ($row['job_session'] && $row['job_session_rates'] > 0) {
													echo "<span class='".strtolower($row['job_session'])." update_job ".strtolower($row['time'])."' id='".$row['id']."' >"."<span class='contact $call_class' id='call_".$row['id']."'>".$row['check_call']."</span>"."<span class='attendance $absence_class' id='att_".$row['id']."'>". $row['attendance']."</span> ".
													$row['project_name'] . " ". $row['job_session'] ;
											
													if($row['travel_fee'] <> 0 || $row["parking_fee"] <> 0) {
														echo " <span class='car'>C</span> ";
													}
											
												echo "</span>";
												}
											
												if ($row['job_extra_hour'] <> 0 && $row['job_extra_hour_rates'] > 0) {
													echo "<span class='job_extra_hour update_job ".strtolower($row['time'])."' id='".$row['id']."' >"."<span class='contact $call_class' id='call_".$row['id']."'>".$row['check_call']."</span>"."<span class='attendance $absence_class' id='att_".$row['id']."'>". $row['attendance']."</span> ".
													$row['project_name'] . " ". $row['job_extra_hour'] . "H";
											
													if($row['travel_fee'] <> 0 || $row["parking_fee"] <> 0) {
														echo " <span class='car'>C</span> ";
													}
													echo "</span>";
												}
												
												
											} else {
												if ($row['job_session'] && $row['job_session_rates'] > 0) {
											
													echo "<span class='".strtolower($row['job_session'])." update_job ".strtolower($row['time'])."' id='".$row['id']."' >"."<span class='contact $call_class' id='call_".$row['id']."'>".$row['check_call']."</span>"."<span class='attendance $absence_class' id='att_".$row['id']."'>". $row['attendance']."</span> ".$row['project_name'] . " ". $row['job_session'] ;
											
													if($row['travel_fee'] <> 0 || $row["parking_fee"] <> 0) {
													echo " <span class='car'>C</span> ";
													}
													echo "</span>";
													}
											
												if ($row['job_extra_hour'] <> 0 && $row['job_extra_hour_rates'] > 0) {
													echo "<span class='job_extra_hour update_job ".strtolower($row['time'])."' id='".$row['id']."' >"."<span class='contact $call_class' id='call_".$row['id']."'>".$row['check_call']."</span>"."<span class='attendance $absence_class' id='att_".$row['id']."'>". $row['attendance']."</span> ".$row['project_name'] . " ". $row['job_extra_hour'] . "H";
											
													if($row['travel_fee'] <> 0 || $row["parking_fee"] <> 0) {
													echo " <span class='car'>C</span> ";
													}
													echo "</span>";
												}
												
												
													
											}
											}
											
											}
											mysql_free_result($result);
											
											echo "<button class='add_job' id='".$list_Records[$i]['id'].":".date('Y-m-d',strtotime("$s_db_date +$k day"))."'><span class='ui-icon ui-icon-plusthick' ></span></button></td>";
											}
											
											echo "</tr>
											</tbody>";
											?>

											<?php
											}
											} else {
											echo "<tbody><tr><td colspan=6 height=40 align=center>Nothing to display</td></tr></tbody>";
											}
											}
											?>
										</table>
									</form>
									<br>
									<table border="0" cellpadding="0" cellspacing="0" width="1160">
										<tr>
											<td >
												<span style="float:left">
												<input type="button" value="New Entry" onclick="location.href='job_regist.php?<?php echo $srch_param;?>'">
												<span style="background-color:#FAEB78; width:20px; display:inline-block;">&nbsp;</span> NILL,
												<span style="background-color:rgb(218,238,243); width:20px; display:inline-block;">&nbsp;</span> Default and AM,
												<span style="background-color:rgb(253,233,217); width:20px; display:inline-block;">&nbsp;</span> PM,
												<span style="background-color:rgb(221,217,196); width:20px; display:inline-block;">&nbsp;</span> Night
											
											</span><span style="float:right">
												<input type="button" value="New Entry" onclick="location.href='job_regist.php?<?php echo $srch_param;?>'">
											</span></td>
										</tr>
									</table></td>
								</tr>
								<tr>
									<td align="center"><?php  include_once "paging.php"
									?></td>
								</tr>
							</table><!-- CONTENTS END --------------------------------------------------------------------------------------------></td>
							<td></td>
						</tr>
						<tr>
							<td colspan="2" height="20"></td>
						</tr>
					</table></td>
				</tr>
				<!-- BODY CENTER END------------------------------------------------------------------------------------->
			</table><!-- BODY END --------------------------------------------------------------------------------------------></td>
		</tr>
		<tr>
			<td colspan="3"><!-- BOTTOM --------------------------------------------------------------------------------------------><?php
			include_once "bottom.php";
			?>
			<!-- BOTTOM END --------------------------------------------------------------------------------------------></td>
		</tr>
	</table>
	<div id="special"></div>
</BODY>
</HTML> <?php ob_flush();?>