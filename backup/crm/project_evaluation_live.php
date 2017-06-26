<?php
ob_start();
include_once "include/logincheck.inc";
include_once "include/common.inc";
include_once "include/dbconn.inc";
include_once "include/myfunc.inc";
$head_title = "Project Evaluation - ECHO TILES Management System";
include_once "htmlinclude/head.php";


/*
 * Update project evaluation value 
 * It runs when it opens
 */
$project_eval = array();
							
$Query  = "SELECT * ";
if ($_GET['status']) {
	$Query .= " FROM project where 1=1 AND project_status = '".$_GET['status']."' ";
}else {
	$Query .= " FROM project where 1=1 AND project_status <> 'COMPLETED' ";
}

$id_cnn = mysql_query($Query) or exit(mysql_error());
while($id_rst = mysql_fetch_assoc($id_cnn)) {
	$project_eval = array_merge($project_eval, array($id_rst));
}	
mysql_free_result($id_cnn);							
$eval_date = $now_dateano . " 23:59:59";
$cnt = count($project_eval);
							
if($cnt > 0) {
	// 각 월별 allowance 및 총 일한 횟수
	// 여기서의 일한 횟수는 full은 1, half 0.5, extra hour * 0.1
	$total_allowance = array();
	$total_allowance_query = " SELECT j.employee_id, SUM(IF(job_session<>'' AND job_session_rates > 0, IF(job_session='FULL',1,IF(job_session='HALF',0.5,0)),0 )) + SUM(IF(job_extra_hour > 0 AND job_extra_hour_rates>0,0.1*job_extra_hour,0)) AS total_time, DATE_FORMAT(job_date, '%m-%Y') AS month_year, amounts " .
							" FROM job j, allowance a WHERE j.employee_id = a.employee_id AND DATE_FORMAT(job_date, '%m-%Y') = a.month_year " .
							" GROUP BY month_year, j.employee_id";
	$total_allowance_result = mysql_query($total_allowance_query) or exit(mysql_error());
	while($id_rst = mysql_fetch_assoc($total_allowance_result)) {
		$total_allowance = array_merge($total_allowance, array($id_rst));
	}	
	mysql_free_result($total_allowance_result);	
	
	// 프로젝트별 총 일한 횟수
	// 여기서의 일한 횟수는 full은 1, half 0.5, extra hour * 0.1
	$total_work_per_project = array();
	$total_work_per_project_query = "SELECT j.employee_id, SUM(IF(job_session<>'' AND job_session_rates > 0, IF(job_session='FULL',1,IF(job_session='HALF',0.5,0)),0 )) + SUM(IF(job_extra_hour > 0 AND job_extra_hour_rates>0,0.1*job_extra_hour,0)) AS total_time, DATE_FORMAT(job_date, '%m-%Y') AS month_year, project_id " .
									" FROM job j " .
									" GROUP BY month_year, j.employee_id, project_id";
	$total_work_per_project_result = mysql_query($total_work_per_project_query) or exit(mysql_error());
	while($id_rst = mysql_fetch_assoc($total_work_per_project_result)) {
		$total_work_per_project = array_merge($total_work_per_project, array($id_rst));
	}	
	mysql_free_result($total_work_per_project_result);	
	
						
	for($i=0; $i<count($project_eval); $i++) {
		if ($project_eval[$i]['project_id'] && $eval_date) {
			/* 해당 프로젝트의 material cost 가져오기 */
			$sql_m = " SELECT sum(IF(o.material_id <> '0' and o.material_id <> '',o.material_price,IF(o.orders_tax = 'N', o.material_price,o.material_price/1.1)) * o.orders_inventory) as cost " .
					" , IF(o.material_id=0,(SELECT supplier_category FROM supplier where supplier_id = o.supplier_id),s.supplier_category) as category " .
					" FROM orders o ". 
					" INNER JOIN project p ON o.project_id=p.project_id ". 
					" LEFT JOIN material m ON o.material_id=m.material_id " .
					" LEFT JOIN supplier s ON m.supplier_id = s.supplier_id " .
					" WHERE 1=1 AND ((new_order = 'N' AND (orders_number = ' ' or (orders_number <> '' and o.material_id = 0)) )OR new_order='Y') " .
					" AND (o.orders_date <= '$eval_date' ) " .
					" AND (p.project_id='".$project_eval[$i]['project_id']."') ".
					" GROUP BY category ";
			
			$material_t_cost = 0;
			$material_m_cost = 0;
			$material_s_cost = 0;
			
			$result = mysql_query($sql_m) or exit(mysql_error());
			while($row = mysql_fetch_assoc($result)) {
				if (strtoupper($row['category']) == 'MATERIAL') {
					$material_m_cost += $row['cost'];
				//} elseif (strtoupper($row['category']) == "TILE") {
				//	$material_t_cost += $row['cost'];
				} elseif (strtoupper($row['category']) == "SUBCONTRACTOR") {
					$material_s_cost += $row['cost'];
				}
			}
			mysql_free_result($result);
			
			/*
			 * material tile cost 
			 */
			$sql_m_t= " SELECT SUM(IF(o.material_id <> '0' and o.material_id <> '',o.material_price,IF(o.orders_tax = 'N', o.material_price,o.material_price/1.1)) * oc.orders_clear_qty) as cost " .
					" , IF(o.material_id=0,(SELECT supplier_category FROM supplier where supplier_id = o.supplier_id),s.supplier_category) as category " .
					" FROM orders_clear oc, orders o ". 
					" LEFT JOIN material m ON o.material_id=m.material_id " .
					" LEFT JOIN supplier s ON m.supplier_id = s.supplier_id " .
					" WHERE 1=1 AND oc.orders_id = o.orders_id " .
					" AND (oc.orders_clear_date <= '$eval_date' ) " .
					" AND (o.project_id='".$project_eval[$i]['project_id']."') ".
					" GROUP BY category ";
			//echo $sql_m_t;	
			$result_t = mysql_query($sql_m_t) or exit(mysql_error());
			while($row_t = mysql_fetch_assoc($result_t)) {
				if (strtoupper($row_t['category']) == 'TILE') {
					$material_t_cost += $row_t['cost'];
				}
			}
			
			mysql_free_result($result_t);
					
			/* labour cost 가져오기 */
			$labour_t_cost = 0;
			$labour_s_cost = 0;
			$labour_w_cost = 0;
									
			$sql_l = " SELECT sum(IF(attendance='A',0,IF(job_session='FULL',1* job_session_rates * fw.wages_amount,IF(job_session='HALF',0.5*job_session_rates * fw.wages_amount,0)))+IF(attendance='A',0,(job_extra_hour * job_extra_hour_rates * hw.wages_amount)) + travel_fee+ parking_fee + tool_fee + extra_fee) as wages, job_type " .
					" FROM job j, wages fw, wages hw " . 
				 	" WHERE j.f_wages_id = fw.wages_id and j.h_wages_id = hw.wages_id and j.employee_id = fw.employee_id and j.employee_id = hw.employee_id AND project_id = '".$project_eval[$i]['project_id']."' AND job_date <= '$eval_date' " .
				 	" GROUP BY j.job_type ";
			
			$result_l = mysql_query($sql_l) or exit(mysql_error());
			while($row_l = mysql_fetch_assoc($result_l)) {
				if (strtoupper($row_l['job_type']) == 'TILING') {
					$labour_t_cost += $row_l['wages'];
				} elseif (strtoupper($row_l['job_type']) == "SILICONE") {
					$labour_s_cost += $row_l['wages'];
				} elseif (strtoupper($row_l['job_type']) == "WATERPROOFING") {
					$labour_w_cost += $row_l['wages'];
				}
			}
			mysql_free_result($result_l);
			
			// allowance 계산
			// 프로젝트별 일한 횟수 배열에서 해당 프로젝트와 같은것을 찾고 
			$allowance = 0;
			foreach ($total_work_per_project as $a) {
				//print_r($a);
				if ($a['project_id'] == $project_eval[$i]['project_id']) {
					foreach($total_allowance as $b) {
						if($a['employee_id'] == $b['employee_id'] && $a['month_year'] == $b['month_year']) {
							// 각 현장별 일한 횟수/해당 월 총일한 횟수 X 해당월 allowance							
							$allowance += ($a['total_time']/$b['total_time']) * $b['amounts'];
						}
					}
				}
			}
			
			$sql = " UPDATE project SET labour_t_c = '$labour_t_cost',labour_s_c = '$labour_s_cost', labour_w_c = '$labour_w_cost',material_tile_c = '$material_t_cost',material_material_c = '$material_m_cost', material_subcontractor_c = '$material_s_cost', allowance = '$allowance', update_date = '$now_dateano' WHERE project_id = '".$project_eval[$i]['project_id']."' ";
			pQuery($sql,"update");
		}
			
	}
}
							
?>
<link rel="stylesheet" type="text/css" href="css/dataTable.css" />
<link rel="stylesheet" type="text/css" href="media/css/TableTools.css" />
<script type="text/javascript" language="javascript" src="js/jquery.dataTables.js"></script>
<script type="text/javascript" language="javascript" src="js/jquery.jeditable.js"></script>
<script type="text/javascript" charset="utf-8" src="media/js/ZeroClipboard.js"></script>
<script type="text/javascript" charset="utf-8" src="media/js/TableTools.js"></script>
<script language="Javascript">
$(function() {
	$("input:button, button").button();

	$("#based_date").datepicker($.datepicker.regional['en-GB']);
	$("#based_date").datepicker( "option", "firstDay", 1 );
	$("#based_date").datepicker();
	
	$(document).ready(function() {
		
		$("#table tbody span.delete_row").live('click', function(){        
			//deleteFunc (this,oTable, "user_delete")    
		});
		
		$("#table tbody tr").click( function( e ) {        
			if ( $(this).hasClass('row_selected') ) {            
				$(this).removeClass('row_selected');        
			}        
			else {            
				oTable.$('tr.row_selected').removeClass('row_selected');            
				$(this).addClass('row_selected');        
			}    
		});
		 
		oTable = $('#table').dataTable({
					
					"sScrollY": "600px",

					"bSort": false,
					"bJQueryUI": true,
					"sPaginationType": "full_numbers",
					"sDom": '<"H"Tfr>t<"F"ip>',
					"oTableTools": {
						"sSwfPath": "media/swf/copy_cvs_xls_pdf.swf",
						"aButtons": [
								"copy",
								"csv",
								"xls",
								{
									"sExtends": "pdf",
									"sPdfOrientation": "landscape",
									"sPdfMessage": " ",
									"sPdfSize" : "A3"
								},
								"print"
						]
					},
					
					"fnFooterCallback": function ( nRow, aaData, iStart, iEnd, aiDisplay ) {
					/*
					 * Calculate the total 
					 */
					var iTotalMarket = 0;
					for ( var i=0 ; i<aaData.length ; i++ )
					{
						iTotalMarket += aaData[i][4];
					}
					
					
					/* Modify the footer row to match what we want */
					var nCells = nRow.getElementsByTagName('th');
					nCells[1].innerHTML = parseInt(iTotalMarket) +'% total';
					},


					"bAutoWidth": false,
					"bProcessing": true,
					"bServerSide": true,
					"iDisplayLength": -1,
					"aLengthMenu": [[50, 100, -1], [50, 100, "All"]],
					"aaSorting": [[2,'asc']],
					"sAjaxSource": "project_eval_dtsource.php?status=<?php echo $_GET['status']?>",		
					
					"aoColumns" :[{"bVisible": false},
					{"sClass":"name","bSortable": false,"sWidth":"300px"},
					{"sClass":"profit_after_managing_fee profit right","bSortable": false,"sWidth":"80px"},
					{"sClass":"budget_left profit right","bSortable": false,"sWidth":"80px"},
					{"sClass":"labour_t_c labour right","bSortable": false,"sWidth":"80px"},
					{"sClass":"labour_s_c labour right","bSortable": false,"sWidth":"80px"},
					{"sClass":"labour_w_c labour right","bSortable": false,"sWidth":"80px"},
					{"sClass":"allowance labour right","bSortable": false,"sWidth":"80px"},
					{"sClass":"extra_t labour right","bSortable": false,"sWidth":"80px"},
					{"sClass":"extra_s labour right","bSortable": false,"sWidth":"80px"},
					{"sClass":"extra_w labour right","bSortable": false,"sWidth":"80px"},
					{"sClass":"labour_c_progress labour right","bSortable": false,"sWidth":"80px"},
					{"sClass":"material_tile_c material right","bSortable": false,"sWidth":"80px"},
					{"sClass":"material_material_c material right","bSortable": false,"sWidth":"80px"},
					{"sClass":"material_subcontractor_c material right","bSortable": false,"sWidth":"80px"},
					{"sClass":"material_fc material right","bSortable": false,"sWidth":"80px"},
					{"sClass":"budget_fc budget right","bSortable": false,"sWidth":"80px"},
					{"sClass":"v_budget_fc budget right","bSortable": false,"sWidth":"80px"},
					{"sClass":"adjusted_budget budget right","bSortable": false,"sWidth":"80px"},
					{"sClass":"contract_fc contract right","bSortable": false,"sWidth":"80px"},
					{"sClass":"contract_sc contract right","bSortable": false,"sWidth":"80px"},
					{"sClass":"v_fc contract right","bSortable": false,"sWidth":"80px"},
					{"sClass":"v_sc contract right","bSortable": false,"sWidth":"80px"},
					{"sClass":"adjusted_contract_fc contract right","bSortable": false,"sWidth":"80px"},
					{"sClass":"adjusted_contract_sc contract right","bSortable": false,"sWidth":"80px"}]
				});
				
		
	});	
	
	function fnGetSelected( oTableLocal ){    
		var aReturn = new Array();    
		var aTrs = oTableLocal.fnGetNodes();         
		for ( var i=0 ; i<aTrs.length ; i++ )    
		{        
			if ( $(aTrs[i]).hasClass('row_selected') )        
			{            
				aReturn.push( aTrs[i] );        
				}    
		}    
		//alert(aReturn[0]);    
		return aReturn;
	} 
	
	
	function deleteFunc (obj,dataTable, cp) {           
		// Show a confirm popup to to be sure that we want to delete that record        
		var r=confirm("Do you want to delete this item?");        
		if (r==true){                
			// get the row that contains the delete btn            
			row = obj.parentNode.parentNode.getAttribute('id');            
			// get the record id from the btn id "delete_1"             
			idAll = row.split("project_eval_id_");                             
			id=idAll[1];          
			//add class to the selected row  
 			$(row).addClass('row_selected');            
			// make the ajax call to delete the record from db            
			$.post("project_eval_del_ajax.php",{
				pid : id
			}, function(data){
				if(data == "SUCCESS") {
				oTable.fnDraw();
				//var anSelected = fnGetSelected( dataTable );                    
				//	dataTable.fnDeleteRow( anSelected[0] );      
				} else {
					alert('Failed!');
				}
			});
			
			
		}                
	}	
});

function currentTime() {
	var currentTime = new Date();
	var month = currentTime.getMonth() + 1;
	var day = currentTime.getDate();
	var year = currentTime.getFullYear();
	
	var hours = currentTime.getHours();
	var minutes = currentTime.getMinutes();
	if (minutes < 10){
		minutes = "0" + minutes;
	}
	if (month < 10) {
		month = "0" + month;
	}
	if (day < 10) {
		day = "0" + day;
	}
	document.write(day + "/" + month + "/" + year + " " + hours + ":" + minutes );
}
</script>
<style>
#table {font-size:11px;  }

</style>
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
			<td style="padding-left:15px"><?php include_once "top.php"; ?></td>
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
				<!--
				<div class="ui-widget">
					<div class="ui-state-error ui-corner-all" style="margin: 10px; padding: 0 .7em;"> 
					<p><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span>
					<strong>Under construction</strong></p>
					</div>
				</div>
				-->
				<table border="0" cellpadding="0" cellspacing="0" width="1000">		
				<tr>
					<td valign="top">
					<table border="0" cellpadding="0" cellspacing="0" width="1000" class="font11_bold">
						<tr><td width="252"><img src="images/icon_circle03.gif">&nbsp;<span style="height:21px">Project Evaluation Live (Updated : <script>currentTime();</script>) </span></td></tr>
						<tr><td height="3"></td></tr>
						<tr><td background="images/bg_check.gif" height="3"></td></tr>
						<tr><td height="2"></td></tr>
					</table>
					</td>
				</tr>
				<tr>
					<td valign="top">
						<div id="table_wrapper" class="ex_highlight" >
						<table border="0" cellpadding="0" cellspacing="0" id="table" style="width:2000px;color:black !important;" class="display">
						<thead>
							<tr>
								<th width="0" rowspan="2">id</th>
								<th rowspan="2" >Site</th>
								<th class="ui-state-default profit" colspan="2">Profit</th>
								<th class="ui-state-default labour" colspan="8">Labour Cost</th>
								
								
								<th class="ui-state-default material" colspan="4">Materials</th>
								<th class="ui-state-default budget" colspan="3">Budget</th>
								
								<th class="ui-state-default contract" colspan="6">Contract Break Up</th>
							</tr>
							<tr>
								<th class="profit">Profit after Managing Fee%<sup>(((17-2-3-4-5-6-8-9)/17)-0.2)*100</sup></th>
								<th class="profit">Budget Left <sup>=12-2-3-4-5-6-9</sup></th>
								<th class="labour">Tiling($)<sup>2</sup></th>
								<th class="labour">Silicone($)<sup>2</sup></th>
								<th class="labour">Water proofing($)<sup>2</sup></th>
								<th class="labour">Allowance($)<sup>3</sup></th>
								<th class="labour">Extra-T($)<sup>4</sup></th>
								<th class="labour">Extra-S($)<sup>5</sup></th>
								<th class="labour">Extra-W($)<sup>6</sup></th>
								<th class="labour">Labour Cost progress %<br/>(Labour/ Adjusted Budget)<sup>((2+3+4+5+6+9)/12)*100</sup></th>
								<th  class="material">Tile($)<sup>7</sup></th>
								<th  class="material">Material($)<sup>8</sup></th>
								<th  class="material">Sub contractor($)<sup>9</sup></th>
								<th  class="material">Material % from Contract Fixing Cost<sup>(8/17)*100</sup></th>
								<th class="budget">Budget<br/>Fixing Cost including Allowance & Extras<sup>10</sup></th>
								<th class="budget">Variation Budget Fixing Cost<sup>11</sup></th>
								<th class="budget">Adjusted Budget<sup>12</sup></th>
								<th class="contract" >Contract Fixing Cost<sup>13</sup></th>
								<th class="contract" >Contract Supply Cost<sup>14</sup></th>
								<th class="contract" >Variation Fixing Cost<sup>15</sup></th>
								<th class="contract" >Variation Supply Cost<sup>16</sup></th>
								<th class="contract" >Adjusted Contract Fixing Cost<sup>17=13+15</sup></th>
								<th class="contract" >Adjusted Contract Supply Cost<sup>18=14+16</sup></th>
							</tr>
						</thead>
						<tfoot>
							<tr>
								<th width="0" rowspan="2">id</th>
								<th rowspan="2">Site</th>
								<th class="ui-state-default profit" colspan="2">Profit</th>
								<th class="ui-state-default labour" colspan="8">Labour Cost</th>
								
								<th class="ui-state-default material" colspan="4">Materials</th>
								<th class="ui-state-default budget" colspan="3">Budget</th>
								
								<th class="ui-state-default contract" colspan="6">Contract Break Up</th>
								
							</tr>
							<tr>
								<th class="profit right">Profit after Managing Fee% <sup>(((17-2-3-4-5-6-8-9)/17)-0.2)*100</sup></th>
								<th class="profit right">Budget Left <sup>=12-2-3-4-5-6-9</sup></th>
								<th class="labour right">Tiling($)<sup>2</sup></th>
								<th class="labour right">Silicone($)<sup>2</sup></th>
								<th class="labour right">Water proofing($)<sup>2</sup></th>
								<th class="labour right">Allowance($)<sup>3</sup></th>
								<th class="labour right">Extra-T($)<sup>4</sup></th>
								<th class="labour right">Extra-S($)<sup>5</sup></th>
								<th class="labour right">Extra-W($)<sup>6</sup></th>
								<th class="labour right">Labour Cost progress %<br/>(Labour/ Adjusted Budget)<sup>((2+3+4+5+6+9)/12)*100</sup></th>
								<th  class="material right">Tile($)<sup>7</sup></th>
								<th  class="material right">Material($)<sup>8</sup></th>
								<th  class="material right">Sub contractor($)<sup>9</sup></th>
								<th  class="material right">Material % from Contract Fixing Cost<sup>(8/17)*100</sup></th>
								
								<th class="budget right">Budget<br/>Fixing Cost including Allowance & Extras<sup>10</sup></th>
								<th class="budget right">Variation Budget Fixing Cost<sup>11</sup></th>
								<th class="budget right">Adjusted Budget<sup>12</sup></th>
								
								
								<th class="contract right" >Contract Fixing Cost<sup>13</sup></th>
								<th class="contract right" >Contract Supply Cost<sup>14</sup></th>
								<th class="contract right" >Variation Fixing Cost<sup>15</sup></th>
								<th class="contract right" >Variation Supply Cost<sup>16</sup></th>
								<th class="contract right" >Adjusted Contract Fixing Cost<sup>17=13+15</sup></th>
								<th class="contract right" >Adjusted Contract Supply Cost<sup>18=14+16</sup></th>
							</tr>
							
						</tfoot>
						</table>
						</div>
					</td>
				</tr>
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
</html>
<?php ob_flush();?>