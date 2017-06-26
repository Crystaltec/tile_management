<?php
include_once "include/common.inc";
include_once "include/dbconn.inc";
include_once "include/myfunc.inc";

if(!$_POST) exit;
if(!$_POST['pid']) exit;

$pid = secure_string($_POST['pid']); 


$based_date = getAUDateToDB($_POST["based_date"]);

if ($based_date) {
	$regen_based_date = $based_date . " 23:59:59";
}
							
if ($pid && $regen_based_date) {
	/* 해당 프로젝트의 material cost 가져오기 */
	$sql_m = " SELECT sum(IF(o.material_id <> '0' and o.material_id <> '',o.material_price,IF(o.orders_tax = 'N', o.material_price,o.material_price/1.1)) * o.orders_inventory) as cost " .
			" , IF(o.material_id=0,(SELECT supplier_category FROM supplier where supplier_id = o.supplier_id),s.supplier_category) as category " .
			" FROM orders o ". 
			" INNER JOIN project p ON o.project_id=p.project_id ". 
			" LEFT JOIN material m ON o.material_id=m.material_id " .
			" LEFT JOIN supplier s ON m.supplier_id = s.supplier_id " .
			" WHERE 1=1 and ((new_order = 'N' AND (orders_number = ' ' or (orders_number <> '' and o.material_id = 0)) )OR new_order='Y') " .
			" AND (o.orders_date <= '$regen_based_date' ) " .
			" AND (p.project_id='$pid') ".
			" GROUP BY supplier_category ";
	
	$material_t_cost = 0;
	$material_m_cost = 0;
	$material_s_cost = 0;
	
	$result = mysql_query($sql_m) or exit(mysql_error());
	while($row = mysql_fetch_assoc($result)) {
		if (strtoupper($row['supplier_category']) == 'MATERIAL') {
			$material_m_cost += $row['cost'];
		} elseif (strtoupper($row['supplier_category']) == "TILE") {
			$material_t_cost += $row['cost'];
		} elseif (strtoupper($row['supplier_category']) == "SUBCONTRACTOR") {
			$material_s_cost += $row['cost'];
		}
	}
	
	mysql_free_result($result);
	
	/* labour cost 가져오기 */
	$labour_t_cost = 0;
	$labour_s_cost = 0;
	$labour_w_cost = 0;
							
	$sql_l = " SELECT sum(IF(attendance='A',0,IF(job_session='FULL',1* job_session_rates * fw.wages_amount,IF(job_session='HALF',0.5*job_session_rates * fw.wages_amount,0)))+IF(attendance='A',0,(job_extra_hour * job_extra_hour_rates * hw.wages_amount)) + travel_fee+ parking_fee + tool_fee + extra_fee) as wages, job_type " .
			" FROM job j, wages fw, wages hw " . 
		 	" WHERE j.f_wages_id = fw.wages_id and j.h_wages_id = hw.wages_id and j.employee_id = fw.employee_id and j.employee_id = hw.employee_id AND project_id = '$pid' AND job_date <= '$regen_based_date' " .
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
	
	/*
	 * 2011-10-23을 기준으로 입력된 labour cost를 그 이후에 입력되는 프로젝트 labour cost에 더해 준다 
	 */
	if ($based_date > '2011-10-23') {
		$sql_b = " SELECT labour_t_c, labour_s_c, labour_w_c " .
			" FROM project_eval " . 
		 	" WHERE project_id = '$pid' ";
	
			$result_b = mysql_query($sql_b) or exit(mysql_error());
			while($row_b = mysql_fetch_assoc($result_b)) {
				$labour_t_cost += $row_b['labour_t_c'];
				$labour_s_cost += $row_b['labour_s_c'];
				$labour_w_cost += $row_b['labour_w_c'];	
			}
			mysql_free_result($result_b);
	}
	
	$sql = " INSERT INTO project_eval (project_id, account_id, regdate, based_date, labour_t_c,labour_s_c, labour_w_c,material_tile_c,material_material_c, material_subcontractor_c) VALUES ( '$pid','$Sync_id', '$now_datetimeano','$based_date','$labour_t_cost','$labour_s_cost','$labour_w_cost','$material_t_cost','$material_m_cost','$material_s_cost') ";
	pQuery($sql,"insert");
	echo "SUCCESS";
} else {
	echo "ERROR";
} 


?>
