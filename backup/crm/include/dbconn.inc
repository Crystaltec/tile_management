<?
$dbconn = mysql_connect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD) or die("fail to connect DB");
//mysql_set_charset('utf8',$dbconn); 
//for old php 4.x
mysql_query("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'", $dbconn);
$status = mysql_select_db($DB_NAME,$dbconn) or die("ERROR CODE ".mysql_errno()." : ".mysql_error());
?>
