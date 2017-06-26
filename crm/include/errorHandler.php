<?php
set_error_handler('errorHandler');

function errorHandler($errno, $errstr, $errfile, $errline, $errcontext) {
	switch ($errno) {
      case E_USER_WARNING:
      case E_USER_NOTICE:
      case E_WARNING:
      case E_NOTICE:
      case E_CORE_WARNING:
      case E_COMPILE_WARNING:
         break;
      case E_USER_ERROR:
      case E_ERROR:
      case E_PARSE:
      case E_CORE_ERROR:
      case E_COMPILE_ERROR:
		
		 global $sql;
   
         if (eregi('^(sql)$', $errstr)) {
            $MYSQL_ERRNO = mysql_errno();
            $MYSQL_ERROR = mysql_error();
            $errstr = "MySQL error: $MYSQL_ERRNO : $MYSQL_ERROR";
         } else {
            $sql = NULL;
         } // if

		 $errorstring = "<h2>" .date('Y-m-d H:i:s') ."</h2>\n";
		 $errorstring .= "<p>Fatal Error: $errstr (# $errno).</p>\n";

		 if ($sql) $errorstring .= "<p>SQL query: $sql</p>\n";

		 $errorstring .= "<p>Error in line $errline of file '$errfile'.</p>\n";
         $errorstring .= "<p>Script: '{$_SERVER['PHP_SELF']}'.</p>\n";

		 if (isset($errcontext['this'])) {
            if (is_object($errcontext['this'])) {
               $classname = get_class($errcontext['this']);
               $parentclass = get_parent_class($errcontext['this']);
               $errorstring .= "<p>Object/Class: '$classname', Parent Class: '$parentclass'.</p>\n";
            } // if
         } // if

		 echo "<h2>This system is temporarily unavailable</h2>\n";
         echo "<p>The following has been reported to the administrator:</p>\n";
         echo "<b><font color='red'>\n$errorstring\n</b></font>";


		 // when error trigger send mail to admin
		 //error_log($errorstring, 1, $_SERVER['SERVER_ADMIN']);
		 //echo $_SERVER['SERVER_ADMIN'];

		 $logfile = $_SERVER['DOCUMENT_ROOT'] .'/errorlog.html';
         error_log($errorstring, 3, $logfile);

		 //session_start();
         //session_unset();
         //session_destroy();

		 // TRANSACTION ROLLBACK -----------------------------------------------------------
		 mysql_query("ROLLBACK");
		 echo "ROLL BACKED!";
		 die();
      default:
         break;
   } // switch
} // errorHandler