<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<?php
ob_start();
include_once "include/common.inc";
include_once "include/dbconn.inc";
include_once "include/myfunc.inc";

$project_inf_id = $_REQUEST["project_inf_id"];
$builder_id = $_REQUEST["builder_id"];
$project_id = $_REQUEST["project_id"];
$job_start_date = getAUDateToDB($_REQUEST["job_start_date"]);
$job_finish_date = getAUDateToDB($_REQUEST["job_finish_date"]);

$tile_detail_floor = $_REQUEST["tile_detail_floor"];
$tile_detail_wall  = $_REQUEST["tile_detail_wall"];
$glue_detail_floor = $_REQUEST["glue_detail_floor"];
$glue_detail_wall = $_REQUEST["glue_detail_wall"];
$grout_detail_floor = $_REQUEST["grout_detail_floor"];
$grout_detail_wall = $_REQUEST["grout_detail_wall"];
$silicon_detail_floor = $_REQUEST["silicon_detail_floor"];
$silicon_detail_wall = $_REQUEST["silicon_detail_wall"];

$sm_name = $_REQUEST["sm_name"];
$sm_contact_no = $_REQUEST["sm_contact_no"];

//$project_id = mysql_real_escape_string($project_id);
						
$action_type = $_REQUEST["action_type"];

if ($action_type=="") {



	$sql = "INSERT INTO project_information (job_start_date, builder_id, project_id, tile_detail_floor, tile_detail_wall, job_finish_date, glue_detail_floor, grout_detail_floor, glue_detail_wall, grout_detail_wall, silicon_detail_floor, silicon_detail_wall, sm_name, sm_contact_no)
	 VALUES('$job_start_date', '$builder_id', '$project_id', '$tile_detail_floor', '$tile_detail_wall', '$job_finish_date', '$glue_detail_floor' , '$grout_detail_floor' , '$glue_detail_wall' , '$grout_detail_wall' , '$silicon_detail_floor', '$silicon_detail_wall' , '$sm_name' , '$sm_contact_no') ";


	//echo $sql;
	pQuery($sql,"insert");
	$string1 = "Registration Completed!";
	
	echo "<script language='javascript'>
		alert('Successfully Added!');
		</script>";
	echo "<script>location.href='project_info_list.php?';</script>";
} 
else if($action_type=="modify") 
{

		
		$query = "update project_information set  job_start_date='$job_start_date' , builder_id='$builder_id' , project_id='$project_id' , tile_detail_floor='$tile_detail_floor' , tile_detail_wall='$tile_detail_wall', job_finish_date='$job_finish_date' ,  glue_detail_floor='$glue_detail_floor' ,grout_detail_floor='$grout_detail_floor' , glue_detail_wall='$glue_detail_wall' , grout_detail_wall='$grout_detail_wall' , silicon_detail_floor='$silicon_detail_floor' , silicon_detail_wall='$silicon_detail_wall', sm_name='$sm_name' , sm_contact_no='$sm_contact_no' where project_inf_id='$project_inf_id'";

mysql_query($query);
		
		
		
	echo "<script language='javascript'>
		alert('Successfully Modified!');
		</script>";

		echo "<script>location.href='project_info_list.php?';</script>";
		
	}
	$string1 = "Update Completed!";


?>
<script>
//alert("<?=$string1?>");

</script>
<? ob_flush(); ?>