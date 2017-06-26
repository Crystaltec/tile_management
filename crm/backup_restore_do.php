<? 
	ob_start();
	include_once "include/common.inc";
?>
<html>
<body>
<span id="now" style="display:block">Now DataBase is Backuping.....</span>
<?

  $backupdir = 'dbbackup'; 

  $filename = $_REQUEST["filename"];

  echo $filename . "<br>";


  // Execute mysql command.
  // It will produce a file named $db-$year$month$day-$hour$min.sql 
  // under $DOCUMENT_ROOT/$backupdir
  system(sprintf( 
   'mysql -h %s -u %s -p%s %s < %s/%s/%s',
   $DB_SERVER,
   $DB_LOGIN,
   $DB_PASSWORD,
   $DB_NAME,
   getenv('DOCUMENT_ROOT'),
   $backupdir,
   $filename
  )); 
?> 
<div style="display:none" id="result">
	<script>alert("Restore Success!");</script>
	<script>location.href='backup_list.php';</script>
</div>
<script>
document.getElementById("now").style.display="none";
document.getElementById("result").style.display="block";
</script>
</body>
</html>

<? ob_flush(); ?>