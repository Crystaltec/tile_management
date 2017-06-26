<?
ob_start();
include_once "include/common.inc";

  $backupdir = 'dbbackup';  

  // Compute day, month, year, hour and min.  
  //$now_datetimeano = date("Y-m-d H:i:s");
  //$today = getdate();
  $now_datetimeano = date("Y-m-d H:i:s", time() + (3600 * 15));
  $today = date("Y-m-d H:i:s", time() + (3600 * 15));
  $day = date("d", time() + (3600 * 15));
  $month = date("m", time() + (3600 * 15));
  $year = date("Y", time() + (3600 * 15));
  $hour = date("H", time() + (3600 * 15));
  $min = date("i", time() + (3600 * 15));
  $sec = date("s", time() + (3600 * 15));

  // Execute mysqldump command.
  // It will produce a file named $db-$year$month$day-$hour$min.gz 
  // under $DOCUMENT_ROOT/$backupdir
  system(sprintf( 
   'mysqldump --opt -h %s -u %s -p%s %s > %s/%s/%s-%s%s%s-%s%s.sql',
   $DB_SERVER,
   $DB_LOGIN,
   $DB_PASSWORD,
   $DB_NAME,
   getenv('DOCUMENT_ROOT') . "/crm",
   $backupdir,
   $DB_NAME,
   $year,
   $month,
   $day,
   $hour,
   $min
  ));  
  
  
  $filename = $DB_NAME."-".$year.$month.$day."-".$hour.$min.".sql";
  $filesize = filesize("dbbackup/".$filename);
  
  $dbconn = mysql_connect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD) or die("failed to connect DB.");
  $status = mysql_select_db($DB_NAME,$dbconn) or die("ERROR CODE ".mysql_errno()." : ".mysql_error());
  $sql = "INSERT INTO backuplist (filename, filesize,regdate ) VALUES ('".$filename."', ".$filesize.",'$now_datetimeano' )";
  mysql_query($sql) or die(mysql_error() . 'Data Can not insert into Database!');
 
  
  
   "<script>alert('Backup Success!');</script>";
  echo "<script>location.href='backup_list.php';</script>";

?> 
