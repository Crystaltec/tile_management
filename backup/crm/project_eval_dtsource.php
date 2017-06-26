<?php
ob_start();
include_once "include/logincheck.inc";
include_once "include/common.inc";
include_once "include/dbconn.inc";
include_once "include/myfunc.inc";

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
* Easy set variables
*/
	
/* Array of database columns which should be read and sent back to DataTables. Use a space where
 * you want to insert a non-database field (for example a counter or static image)
 */
$aColumns = array( 'project_id','project_name','CONCAT(ROUND((((contract_fc + v_fc) - labour_t_c - labour_s_c - labour_w_c - allowance - extra_t - extra_s - extra_w - material_material_c - material_subcontractor_c)/(contract_fc + v_fc) - 0.2)*100,2 ),"%")', 'FORMAT((budget_fc + v_budget_fc) - labour_t_c - labour_s_c - labour_w_c - allowance - extra_t - extra_s - extra_w - material_subcontractor_c,2)','FORMAT(labour_t_c,2)', 'FORMAT(labour_s_c,2)', 'FORMAT(labour_w_c,2)', 'FORMAT(allowance,2)', 'FORMAT(extra_t,2)', 'FORMAT(extra_s,2)', 'FORMAT(extra_w,2)', 'CONCAT( ROUND(((labour_t_c + labour_s_c + labour_w_c + allowance + extra_t + extra_s + extra_w + material_subcontractor_c)/(budget_fc + v_budget_fc))*100,2) ,"%")', 'FORMAT(material_tile_c,2)', 'FORMAT(material_material_c,2)', 'FORMAT(material_subcontractor_c,2)', 'CONCAT(ROUND((material_material_c/(contract_fc + v_fc))*100,2),"%")', 'FORMAT(budget_fc,2)', 'FORMAT(v_budget_fc,2)', 'FORMAT(budget_fc + v_budget_fc,2)', 'FORMAT(contract_fc,2)', 'FORMAT(contract_sc,2)', 'FORMAT(v_fc,2)', 'FORMAT(v_sc,2)', 'FORMAT(contract_fc + v_fc,2)', 'FORMAT(contract_sc + v_sc,2)' );
	
/* Indexed column (used for fast and accurate table cardinality) */
$sIndexColumn = "project_id";
	
/* DB table to use */
$sTable = "project ";
	

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * If you just want to use the basic configuration for DataTables with PHP server-side, there is
 * no need to edit below this line
 */
	
/* 
 * Paging
 */
$sLimit = "";
if ( isset( $_GET['iDisplayStart'] ) && $_GET['iDisplayLength'] != '-1' )
{
	$sLimit = "LIMIT ".mysql_real_escape_string( $_GET['iDisplayStart'] ).", ".
		mysql_real_escape_string( $_GET['iDisplayLength'] );
}
	
	
/*
 * Ordering
 */
$sOrder = "";
$sOrder = " ORDER BY project_name ";
/*
if ( isset( $_GET['iSortCol_0'] ) )
{
	
	$sOrder = "ORDER BY  ";
	for ( $i=0 ; $i<intval( $_GET['iSortingCols'] ) ; $i++ )
	{
		if ( $_GET[ 'bSortable_'.intval($_GET['iSortCol_'.$i]) ] == "true" )
		{
			$sOrder .= $aColumns[ intval( $_GET['iSortCol_'.$i] ) ]."
			 	".mysql_real_escape_string( $_GET['sSortDir_'.$i] ) .", ";
		}
	}
	
	$sOrder = substr_replace( $sOrder, "", -2 );
	
	if ( $sOrder == "ORDER BY" )
	{
		$sOrder = "";
	}
}
*/


/* 
 * Filtering
 * NOTE this does not match the built-in DataTables filtering which does it
 * word by word on any field. It's possible to do here, but concerned about efficiency
 * on very large tables, and MySQL's regex functionality is very limited
 */
$sWhere = " WHERE 1=1 ";
if ( isset($_GET['status']) && $_GET['status'] != "" )
{
	$sWhere .= " AND project_status = 'COMPLETED'  ";
} else {
	$sWhere .= " AND project_status <> 'COMPLETED' ";
}

if ( isset($_GET['sSearch']) && $_GET['sSearch'] != "" )
{
	$sWhere .= " AND (";
	for ( $i=0 ; $i<count($aColumns) ; $i++ )
	{
		$sWhere .= $aColumns[$i]." LIKE '%".mysql_real_escape_string( $_GET['sSearch'] )."%' OR ";
	}
	$sWhere = substr_replace( $sWhere, "", -3 );
	$sWhere .= ')';
}

/* Individual column filtering */
for ( $i=0 ; $i<count($aColumns) ; $i++ )
{
	if ( isset($_GET['bSearchable_'.$i]) && $_GET['bSearchable_'.$i] == "true" && $_GET['sSearch_'.$i] != '' )
	{
		$sWhere .= " AND ";
		
		$sWhere .= $aColumns[$i]." LIKE '%".mysql_real_escape_string($_GET['sSearch_'.$i])."%' ";
	}
}


/*
 * SQL queries
 * Get data to display
 */
$sQuery = "
	SELECT SQL_CALC_FOUND_ROWS ".str_replace(" , ", " ", implode(", ", $aColumns))."
	FROM   $sTable
	$sWhere
	$sOrder
	$sLimit
";

$rResult = mysql_query( $sQuery ) or die(mysql_error());

/* Data set length after filtering */
$sQuery = "
	SELECT FOUND_ROWS()
";
$rResultFilterTotal = mysql_query( $sQuery ) or die(mysql_error());
$aResultFilterTotal = mysql_fetch_array($rResultFilterTotal);
$iFilteredTotal = $aResultFilterTotal[0];

/* Total data set length */
$sQuery = "
	SELECT COUNT(".$sIndexColumn.")
	FROM   $sTable
	$sWhere
";
$rResultTotal = mysql_query( $sQuery ) or die(mysql_error());
$aResultTotal = mysql_fetch_array($rResultTotal);
$iTotal = $aResultTotal[0];


/*
 * Output
 */
$output = array(
	"sEcho" => intval($_GET['sEcho']),
	"iTotalRecords" => $iTotal,
	"iTotalDisplayRecords" => $iFilteredTotal,
	"aaData" => array()
);

while ( $aRow = mysql_fetch_array( $rResult ) )
{
	$row = array();
	
	$row['DT_RowId'] = 'project_id_'.$aRow['project_id'];
		
	for ( $i=0 ; $i<count($aColumns) ; $i++ )
	{
		if ( $aColumns[$i] == "version" )
		{
			/* Special output formatting for 'version' column */
			$row[] = ($aRow[ $aColumns[$i] ]=="0") ? '-' : $aRow[ $aColumns[$i] ];
		}
		else if ( $aColumns[$i] != ' ' )
		{
			/* General output */
			$row[] = $aRow[ $aColumns[$i] ];
		}
	}
	$output['aaData'][] = $row;
}

echo __json_encode( $output );
?>