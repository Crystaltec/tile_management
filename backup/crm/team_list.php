<?php
ob_start();
include_once "include/logincheck.inc";
include_once "include/common.inc";
include_once "include/dbconn.inc";
include_once "include/myfunc.inc";
include_once "htmlinclude/head.php";

$employee_team_id = $_REQUEST["employee_team_id"];
$act = $_REQUEST["act"];
if($act=="delete") {
	$sql = "DELETE FROM employee_team WHERE employee_team_id='".$employee_team_id."'";
	//echo $sql;
	pQuery($sql,"delete");
	
} elseif($act == "sort") {
	
	//for($i=0; $i < count($project_id); $i++) {
		//echo $project_id[$i]. ", ". $desc_no[$i] . "<br>";
		//$sql = "update project set desc_no=".$desc_no[$i]." where project_id=".$project_id[$i];
		//pQuery($sql, "update");
	//}
}

?>
<script type="text/javascript">
function goSort() {
	var f = document.teamForm;
	f.act.value="sort";
	f.submit();
}


$(function() {
	$("input:button, button").button();
	$(".list_table thead").addClass('ui-widget-header');
	$(".list_table tbody").addClass('ui-widget-content');
	$('.ui-widget-content').css({'background-image' :'none','background-color':'none'});
	
});

function delete_team(pp,p)
{
     $.ajax({
          type: "POST",
          url: "delete_team.php",
          data: "pp=" + pp + "&p=" + p,
          success: function(msg){
              $("#team_list_"+pp).html(msg);	
          }
     });
}

function add_team(pp)
{
	left1 = (screen.width/2)-(600/2);
	top1 = (screen.height/2)-(300/2);
	new_window = window.open('add_team.php?pp='+pp,'','width=600,height=300,top='+top1+',left='+left1);
	if (window.focus) {
		new_window.focus();
	}
	return false;
}
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
						<tr><td width="252"><img src="images/icon_circle03.gif">&nbsp;<span style="height:21px">Team List</span></td></tr>
						<tr><td height="3"></td></tr>
						<tr><td background="images/bg_check.gif" height="3"></td></tr>
						<tr><td height="2"></td></tr>
					</table>
					</td>
				</tr>
			
				<tr>
					<td valign="top">
						<form name="teamForm" method="POST" action="<?=$_SERVER["PHP_SELF"]?>">
						<input type="hidden" name="act" value="<?=$act?>">
						<table border="0" width="1000" cellpadding="0" cellspacing="1" class="list_table">
						<thead>
						<tr align="center" >
							<th width='40'>No</th>
							<th width="150">Name</th>
							<th width="650">Employee(s)</th>
							<th></th>
						</tr>
						</thead>
						<tbody>
						<?php
							// 페이지 계산 //////////////////////////////////////////////////////
							$page = $_REQUEST["page"];
							if(!$page)
								$page = 1;
						
							$limitPage = 10;
							$limitList = 10;
							$total = getRowCount2("SELECT COUNT(DISTINCT employee_team_id) FROM employee_team WHERE 1=1 ". $s_Cond);
							$totalPage = ceil($total/$limitList);
							$block = ceil($page/$limitPage);
							$start = ($page-1)*$limitList;

							$startPage = ($block-1)*$limitPage + 1;
							$endPage = $startPage + $limitPage - 1;
							if ($endPage > $totalPage ) $endPage = $totalPage; 
							// 페이지 계산 끝//////////////////////////////////////////////////

							## 쿼리, 담을 배열 선언
							$list_Records = array();
														
							$Query  = "SELECT DISTINCT employee_team_id, employee_team_name ";
							$Query .= " FROM employee_team WHERE 1=1 " . $s_Cond . "ORDER BY employee_team_name ASC LIMIT $start, $limitList";

							$id_cnn = mysql_query($Query) or exit(mysql_error());
							while($id_rst = mysql_fetch_assoc($id_cnn)) {
								$list_Records = array_merge($list_Records, array($id_rst));
								//print_r($list_Records);
								//echo "<p>";
							}
							//echo count($list_Records);
							if(count($list_Records)) {
								for($i=0; $i<count($list_Records); $i++) {
	
									if($i%2 == 0){
										$even_odd = ' class="even" ';
									} else
										$even_odd = ' class="odd" ';
						?>
						<tr align="center" <?php echo $even_odd;?> onmouseover="javascript:this.style.color='#536499';this.style.backgroundColor='#dcebfe';"  onmouseout="javascript:this.style.color='#000000';this.style.backgroundColor='';">
							<td height="25" ><?=$total - (($limitList * ($page-1)) + $i)?></td>
							<td class="left"><b><?=$list_Records[$i]["employee_team_name"]?></b></td>
							<td class="left" id='team_list_<?=$list_Records[$i]["employee_team_id"]?>'>
							<?php
							$employee_team_sql = "SELECT et.employee_id, CONCAT_WS(', ',e.last_name, e.first_name ) as employee_name  FROM employee_team et, employee e WHERE et.employee_team_id = '".$list_Records[$i]["employee_team_id"]."' AND e.id = et.employee_id ORDER BY CONCAT_WS(', ',e.last_name, e.first_name ) ";
							
							//echo $employee_team_sql;
							$employee_team_result =  mysql_query($employee_team_sql) or exit(mysql_error());
									
							while ($row = mysql_fetch_assoc($employee_team_result)) {
								if ($row['employee_name']) 
									echo "<span style='margin-right:20px;'>".$row['employee_name']. "<span onclick='delete_team(".$list_Records[$i]["employee_team_id"].",".$row['employee_id'].")' class='ui-icon ui-icon-close ' style='display:inline-block !important;cursor:pointer;'></span></span>";
							}
							mysql_free_result($employee_team_result);
	
							?>	
							</td>
							<td><a href='#' onclick='add_team(<?php echo $list_Records[$i]['employee_team_id']?>)'>[ADD]</a> <span style='margin-left:10px;'><a href="javascript:if(confirm('Are you sure?')) { location.href='<?php echo $_SERVER['PHP_SELF']?>?employee_team_id=<?=$list_Records[$i]["employee_team_id"]?>&act=delete';}">[ALL DELETE]</a></span></td>
						</tr>
						<?
							}
							} else {
								echo "<tr><td colspan=3 height=40 align=center>Nothing to display</td></tr>";
							}
						?>
						</tbody>
						</table>
						<br>
						<table border="0" cellpadding="0" cellspacing="0" width="1000">
						<tr><td></td><td align="right"><input type="button" value="New Entry" onclick="location.href='team_regist.php'"></td></tr>
						</table>
						</form>
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
		  <tr><td colspan="2" height="481"></td></tr>
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
