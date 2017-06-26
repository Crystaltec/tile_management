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
	
	$('#visa_status_title').qtip({
		content: 'Visa status and expiry date ',
		position :{
			adjust: { 
			x: -10,
			y:-70
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
<BODY leftmargin=0 topmargin=0>
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
						<tr><td width="252"><img src="images/icon_circle03.gif">&nbsp;<span style="height:21px">Employee List</span></td></tr>
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
							$s_terminated = $_REQUEST["s_terminated"];
							
							if($s_name != "") {
								$s_Cond .= " AND CONCAT_WS(', ',last_name, first_name ) like '%". $s_name ."%'";
								$srch_param .= "&s_name=$s_name";
							}

							if($s_id != "") {
								$s_Cond .= " AND employee_id like '%". $s_id ."%'";
								$srch_param .= "&s_id=$s_id";
							}
							//$srch_param = urlencode($srch_param);
							
							if($s_resort_order != ""){
								if ($s_resort_order == "name") {
									$s_Sort = " ORDER BY CONCAT_WS(', ',last_name, first_name ) ";
								} else {
									$s_Sort = " ORDER BY " . $s_resort_order ;
								}
								
								$srch_param .= "&resort_order=$s_resort_order";
							} else {
								$s_Sort = " ORDER BY CONCAT_WS(', ',last_name, first_name ) ";
								$s_resort_order = 'name';
							}
							
							if($s_terminated == 'Y') {
								$s_Cond .= " AND termination_date <> '0000-00-00' ";
								$srch_param .= "&s_terminated=$s_terminated";
							}else if ($s_terminated == 'N') {
								$s_Cond .= " AND termination_date = '0000-00-00' ";
								$srch_param .= "&s_terminated=$s_terminated";
							}
							if($limitList) {
								$srch_param .="&limitList=$limitList";
							}
						?>
						<form name="searchform">
						<table border="0" cellpadding="0" cellspacing="0" height="40" valign="bottom" width="1000" >							
							<tr class='ui-widget-header'>
								<td height="30"  style="padding-left:5px"> Name
								</td>
								<td style="padding-left:5px">
								<input type="text" size="30" name="s_name" value="<?php echo $s_name;?>">
								&nbsp;		
								</td>
								<td height="30"  style="padding-left:5px"> Termination
								</td>
								<td>
								<select name='s_terminated'>
									<option value=''>Select</option>
									<option value='Y' <?php if($s_terminated == 'Y') echo 'selected';?> >Y</option>
									<option value='N' <?php if($s_terminated == 'N') echo 'selected';?> >N</option>
								</select>
								* based on termination date	
								</td>
							</tr>
							<tr><td colspan="4" background="images/bg_check02.gif" height="3"></td></tr>
					
							<tr class='ui-widget-header'>
								<td  width="120" height="30"  style="padding-left:5px"> Employee ID
								</td>
								<td style="padding-left:5px" colspan="3">
								<input type="text" size="30" name="s_id" value="<?php echo $s_id;?>">		
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
							<th width="110" onclick="reSort('employee_id')" <?php if($s_resort_order == 'employee_id')
							{echo "class='sort_asc'";} 
							else echo "class='sort'";
							?>>Employee ID</th>
							<th onclick="reSort('name')" <?php if($s_resort_order == 'name')
							{echo "class='sort_asc'";} 
							else echo "class='sort'";
							?> >Name</th>
							<th width="150">Contact number</th>
							<th width="90" onclick="reSort('hire_date')" <?php if($s_resort_order == 'hire_date')
							{echo "class='sort_asc'";} 
							else echo "class='sort'";
							?>>Hire date</th>
							<th width="100" onclick="reSort('visa_id')" <?php if($s_resort_order == 'visa_id')
							{echo "class='sort_asc'";} 
							else echo "class='sort'";
							?>>Visa</th>
							<th width="150" id="visa_status_title">Visa status</th>
							<th width="60">Vehicle</th>
							<th width="100">Finance</th>
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
							
							$total = getRowCount2("SELECT COUNT(*) FROM employee WHERE 1=1 ". $s_Cond);
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
							
							$Query  = "SELECT *, CONCAT_WS(', ',last_name, first_name )  as employee_name ";
							$Query .= " FROM employee where 1=1 " . $s_Cond . $s_Sort . " LIMIT $start, $limitList";
											
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

								if (($list_Records[$i]["visa_expiry_date"] <> "0000-00-00") && date_diff_day($list_Records[$i]["visa_expiry_date"]) <=30 ) {
									$bgcolor = " style='background-color:#FFC81E !important;' " ;
								}
								
								if ($list_Records[$i]["termination_date"] <> "0000-00-00" && $list_Records[$i]["termination_date"] < $now_dateano) {
									$warn = " strike ";
								}
								
								if($i%2 == 0){
									$even_odd = ' class="even ' . $warn. '" ';
								} else
									$even_odd = ' class="odd ' . $warn. '" ';
									
								
								
						?>
						<tr align="center" <?=$bgcolor?> <?php echo $even_odd;?>>
							<td height="22"><?=$total - (($limitList * ($page-1)) + $i)?></td>
							<td><a href="employee_regist.php?id=<?=$list_Records[$i]["id"]?>&action_type=modify"><b><?php echo $list_Records[$i]["employee_id"];?></b></a></td>
							<td><a href="employee_regist.php?id=<?=$list_Records[$i]["id"]?>&action_type=modify"><b><?=$list_Records[$i]["employee_name"]?></b></a></td>
							<td><?php echo $list_Records[$i]["phone_number"];?> <?php echo $list_Records[$i]["mobile_number"];?> &nbsp;</td>
							
							<td ><?php echo getAUDate($list_Records[$i]["hire_date"])?></td>
							<td ><?php echo getName('visa',$list_Records[$i]["visa_id"]);?></td>
							<td><?php echo getName('visa_status',$list_Records[$i]["visa_status_id"]) . " " .getAUDate($list_Records[$i]["visa_expiry_date"]);?>
							</td>
							<td><?php echo $list_Records[$i]['vehicle'];?></td>
							<?php
								
								$finance_now = getRowCount2("SELECT finance - (SELECT SUM(gross_wages - net_wages - deductions) FROM `transaction` WHERE employee_id = '".$list_Records[$i]["id"]."') FROM employee WHERE id = '".$list_Records[$i]["id"]."' ");
								$bal_class = "";
								
								if ($finance_now > 0) {
									$bal_class = " class='red' ";
								} elseif ($finance_now < 0) {
									$bal_class = " class='blue' ";
								}
							?>
							<td <?php echo $bal_class;?>>
								<?php echo number_format($finance_now,2,".",",");?>
							</td>
							<td><a href="employee_regist.php?id=<?=$list_Records[$i]["id"]?>&action_type=modify">[EDIT]</a></td>
						</tr>
						<?php
							}
							} else {
								echo "<tr><td colspan=5 height=40 align=center>Nothing to display</td></tr>";
							}
						?>
						</tbody>
						</table>
						</form>
						<br>
						<table border="0" cellpadding="0" cellspacing="0" width="1000">
						<tr><td align="right"><input type="button" value="New Entry" onclick="location.href='employee_regist.php'"></td></tr>
						</table>
						<br>
						<ul>
						<li>Click the name of employee to view details.</li>
						<li>Colour of record</li>
							<ul>
							<li><span style="background-color:#FFC81E; width:20px; display:inline-block;">&nbsp;</span> when visa expiry date is whith 1 month.</li>
							<li><span style="text-decoration:line-through; width:20p; display:inline-block;">Tile</span> when termination date is over</li>
							</ul>
						</ul>
					</td>
				</tr>
				<tr><td align="center"><? include_once "paging.php"?></td></tr>
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
</BODY>
</HTML>
<?php ob_flush(); ?>