<?php
ob_start();
include_once "include/logincheck.inc";
include_once "include/common.inc";
include_once "include/dbconn.inc";
include_once "include/myfunc.inc";
 
$now_datetimeano = date("Y-m-d H:i:s", time() + (3600 * 15));
$today = date("Y-m-d H:i:s", time() + (3600 * 15));
$day = date("d", time() + (3600 * 15));
$month = date("m", time() + (3600 * 15));
$year = date("Y", time() + (3600 * 15));
$hour = date("H", time() + (3600 * 15));
$min = date("i", time() + (3600 * 15));
$sec = date("s", time() + (3600 * 15));  
$filename = $DB_NAME."-".$year.$month.$day."-".$hour.$min.".sql";

$save_path = dirname(__FILE__) . "/dbbackup/";
//$result = mysql_query("show variables");
// while($row = mysql_fetch_row($result)) 
//	if($row[0] == "basedir")
//		$bindir = $row[1]."bin/";


//passthru("mysqldump --opt -h $DB_SERVER -u $DB_LOGIN -p $DB_PASSWORD $db_database >  $save_path$filename ");
//passthru("gzip $filename"); 
/* 
$filename.=".gz";

if (is_file("$filename") && file_exists("$filename"))  { 

      if(eregi("(MSIE 5.5|MSIE 6.0)", $HTTP_USER_AGENT)) { 
    header("Content-Type: application/octet-stream");header("Content-Length: ".filesize("$filename"));header("Content-Disposition: attachment; filename=$filename");header("Content-Transfer-Encoding: binary");header("Pragma: no-cache");header("Expires: 0"); 
   } else { 
    header("Content-type: file/unknown");header("Content-Length: ".filesize("$filename"));header("Content-Disposition: attachment; filename=$filename");header("Content-Description: PHP3 Generated Data");header("Pragma: no-cache");header("Expires: 0");
   } 

      $fp = fopen("$filename", "rb"); 
      if (!fpassthru($fp)) fclose($fp); 

  }
//unlink("$filename");
echo $filename;
 * */

ob_flush(); ?>