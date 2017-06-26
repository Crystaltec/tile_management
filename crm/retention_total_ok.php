<?php
ob_start();
include_once "include/common.inc";
include_once "include/dbconn.inc";
include_once "include/myfunc.inc";


$total = 1;
$retention_no = $_REQUEST["retention_no"];

				
$action_type = $_REQUEST["action_type"];
$total_retention = $_REQUEST["total_retention"];

$query = "update retention_total set  retention_total='$total_retention'  where retention_total_no='$total'";

mysql_query($query);
		


echo "<script language='javascript'>
		alert('pasok1');
		</script>";

<script>
//alert("<?=$string1?>");
location.href="retention_list.php";
</script>
<? ob_flush(); ?>