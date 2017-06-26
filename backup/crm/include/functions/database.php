<?php
/*
  $Id: database.php,v 1.21 2003/06/09 21:21:59 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  function tep_db_connect($server = DB_SERVER, $username = DB_LOGIN, $password = DB_PASSWORD, $database = DB_NAME, $dbconn = 'db_link') {
    global $$dbconn;

    if (USE_PCONNECT == 'true') {
      $$dbconn = mysql_pconnect($DB_SERVER, $DB_LOGIN, $DB_PASSWORD);
    } else {
      $$dbconn = mysql_connect($DB_SERVER, $DB_LOGIN, $DB_PASSWORD);
    }

    if ($$dbconn) mysql_select_db($database);
	//echo $$dbconn;

    return $$dbconn;
  }

  function tep_db_close($dbconn = 'db_link') {
    global $$dbconn;

    return mysql_close($$dbconn);
  }

  function tep_db_error($query, $errno, $error) { 
    die('<font color="#000000"><b>' . $errno . ' - ' . $error . '<br><br>' . $query . '<br><br><small><font color="#ff0000">[TEP STOP]</font></small><br><br></b></font>');
  }

  function tep_db_query($query) {
    global $$dbconn;
	
    if (defined('STORE_DB_TRANSACTIONS') && (STORE_DB_TRANSACTIONS == 'true')) {
      error_log('QUERY ' . $query . "\n", 3, STORE_PAGE_PARSE_TIME_LOG);
    }
	@mysql_query("set names utf8");
    $result = mysql_query($query) or tep_db_error($query, mysql_errno(), mysql_error());

    if (defined('STORE_DB_TRANSACTIONS') && (STORE_DB_TRANSACTIONS == 'true')) {
       $result_error = mysql_error();
       error_log('RESULT ' . $result . ' ' . $result_error . "\n", 3, STORE_PAGE_PARSE_TIME_LOG);
    }

    return $result;
  }

  function tep_db_perform($table, $data, $action = 'insert', $parameters = '', $dbconn = 'db_link') {
    reset($data);
    if ($action == 'insert') {
      $query = 'insert into ' . $table . ' (';
      while (list($columns, ) = each($data)) {
        $query .= $columns . ', ';
      }
      $query = substr($query, 0, -2) . ') values (';
      reset($data);
      while (list(, $value) = each($data)) {
        switch ((string)$value) {
          case 'now()':
            $query .= 'now(), ';
            break;
          case 'null':
            $query .= 'null, ';
            break;
          default:
            $query .= '\'' . tep_db_input($value) . '\', ';
            break;
        }
      }
      $query = substr($query, 0, -2) . ')';
    } elseif ($action == 'update') {
      $query = 'update ' . $table . ' set ';
      while (list($columns, $value) = each($data)) {
        switch ((string)$value) {
          case 'now()':
            $query .= $columns . ' = now(), ';
            break;
          case 'null':
            $query .= $columns .= ' = null, ';
            break;
          default:
            $query .= $columns . ' = \'' . tep_db_input($value) . '\', ';
            break;
        }
      }
      $query = substr($query, 0, -2) . ' where ' . $parameters;
    }
    return tep_db_query($query);
  }

  function tep_db_fetch_array($result) {
    //return mysql_fetch_array($result, MYSQL_ASSOC);
	return mysql_fetch_array($result);
  }

   function tep_db_fetch_row($result) {
    return mysql_fetch_row($result);
  }

  function tep_db_num_rows($result) {
    return mysql_num_rows($result);
  }

  function tep_db_data_seek($result, $row_number) {
    return mysql_data_seek($result, $row_number);
  }

  function tep_db_insert_id() {
    return mysql_insert_id();
  }

  function tep_db_free_result($result) {
    return mysql_free_result($result);
  }

  function tep_db_fetch_fields($result) {
    return mysql_fetch_field($result);
  }

  function tep_db_output($string) {
    return htmlspecialchars($string);
  }

  function tep_db_input($string, $dbconn = 'db_link') {
    global $$dbconn;

    if (function_exists('mysql_real_escape_string')) {
      return mysql_real_escape_string($string);
    } elseif (function_exists('mysql_escape_string')) {
      return mysql_escape_string($string);
    }

    return addslashes($string);
  }

  function tep_db_prepare_input($string) {
    if (is_string($string)) {
      return trim(tep_sanitize_string(stripslashes($string)));
    } elseif (is_array($string)) {
      reset($string);
      while (list($key, $value) = each($string)) {
        $string[$key] = tep_db_prepare_input($value);
      }
      return $string;
    } else {
      return $string;
    }
  }
?>
