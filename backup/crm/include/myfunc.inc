<?php

function pQuery($sql, $act) {
	@mysql_query("set names utf8"); 
	mysql_query($sql) or die(mysql_error() . 'Data Can not insert into Database!');
}

function selectQuery($Array, $Table) {
    if(!is_array($Array) || sizeof($Array) < 1) {
		echo("DB error");
		exit;
    }
    $Fields = "";
    while(list($key) = each($Array))    $Fields = $Fields . "$Table.$key, ";
    $Fields = rtrim($Fields, ", ");

    return $Fields;
}

function getRowCount($sql) {
	$result = mysql_query($sql) or exit(mysql_error());
	$rows = mysql_fetch_array($result);
	mysql_free_result($result);
	return $rows;
}

function getRowCount2($sql) {
	$result = mysql_query($sql) or exit(mysql_error());
	$rows = mysql_fetch_array($result);
	mysql_free_result($result);
	return $rows[0];
}

function getLevelTxt($val) {
	$alevel_txt = "";	
	if($val == "A1") {
		$alevel_txt="Admin";
	} else if($val == "B1") {
		$alevel_txt="Manager";
	} else if($val == "B2") {
		$alevel_txt="Staff";
	} else if($val == "C1") {
		$alevel_txt="Tiler";
	}
	return $alevel_txt;
}

// DB to Web
function getAUDate($strdate,$option=NULL) {	
	// 2010-12-31 to 31-12-2010
	if ($strdate <> '0000-00-00' && $strdate <> '0000-00-00 00:00:00' && $strdate <> "") {
		$sdate = $strdate;
		$syear = substr($sdate, 0,4);
		$smonth = substr($sdate, 5,2);
		$sday = substr($sdate, 8,2);
		
		if ($option == '1') {
			return $sday."-".$smonth."-".$syear." ".substr($sdate,11,8);
		} else {
			return $sday."-".$smonth."-".$syear;
		}
		
	}
}

// Web to DB
function getAUDateToDB($strdate,$option=NULL) {	
	// 31-12-2010 to 2010-12-31
	if ($strdate) {
		$sdate = $strdate;
		$syear = substr($sdate, 6,4);
		$smonth = substr($sdate, 3,2);
		$sday = substr($sdate, 0,2);
		if ($option == '1') {
			return $syear."-".$smonth."-".$sday." ".substr($sdate,11,8);
		} else {
			return $syear."-".$smonth."-".$sday;
		}
	}
}

//round 올림
function getCalcMoney($m) {
	$m = round($m,2);
	$m = number_format($m, 2, '.', '');
	$temp = (string)$m;	
	$pos = strpos($m, ".");
	if($pos == 0) {
		return $temp;
	}
	$prev_d = substr($temp, 0, $pos);
	$prevlast_d = substr($temp, $pos+1, -1);
	$last_d = substr($temp, $pos+2, strlen($m));

	/*
	echo $temp . "<br>";
	echo $prev_d . "<br>";
	echo $prevlast_d . "<br>";
	echo $last_d . "<br>";
	*/


	if($last_d < 3) {
		$last_d = 0;
	} elseif($last_d >=3 && $last_d < 8) {
		$last_d = 5;
	} elseif($last_d >= 8) {
		$last_d = 0;
		$prevlast_d += 1;		
		if($prevlast_d == 10) {
			$prevlast_d = 0;
			$prev_d += 1;
		}
	}
	/*
	echo $prev_d . "<br>";
	echo $prevlast_d . "<br>";
	echo $last_d . "<br>";
	*/

	$temp = (string)$prev_d . '.' . (string)$prevlast_d . (string)$last_d;
	//echo $temp;
	return (float)$temp;
}

function getStateOption($columnname, $state, $width=NULL) {
	$AU_states = array ("ACT" ,"NSW" ,"NT","QLD","SA","TAS","VIC","WA" );
	if ($width) {
		echo "<select name='".$columnname . "' width='".$width."' style='width:".$width."px;'>";
	} else {
		echo "<select name='".$columnname . "' style='width:150px;'>";
	}
	
	echo "<option value=''>Please select</option>";

	for($j=0; $j < count($AU_states); $j++) {
		if($state == $AU_states[$j]) 
			echo "<option value='".$AU_states[$j]."' selected>".$AU_states[$j]."</option>";
		else
			echo "<option value='".$AU_states[$j]."'>".$AU_states[$j]."</option>";
	}	
	
	echo "</select>";
}

function  getOption($table,$value,$status = NULL, $event = NULL) {
	// except COMPLETED
	if ($status == '1') {
		$sql = " select " . $table . "_id, " . $table . "_name from " . $table . " where ". $table . "_status <> 'COMPLETED' order by " . $table . "_name ASC" ;
		
	}
	else {
		$sql = " select " . $table . "_id, " . $table . "_name from " . $table . " order by " . $table . "_name ASC" ;
	}
	$result = mysql_query($sql) or exit(mysql_error());
	
	echo "<select name='". $table . "_id' id='".$table ."_id' " . $event .">";
	echo "<option value=''>Please select</option>";
	
	while($rows = mysql_fetch_row($result)) {		
		if( $value == $rows[0] ) {
			echo "<option value='" . $rows[0] . "' selected>" . $rows[1] . "</option>";
		} else {
			echo "<option value='" . $rows[0] . "'>" . $rows[1] . "</option>";
		}	
	}
	
	echo "</select>";

	mysql_free_result($result);
	
}



function  getConOption($table,$value,$condition) {
	// except COMPLETED
	if ($status == '1') {
		$sql = " select " . $table . "_id, " . $table . "_name from " . $table . " where ". $table . "_status <> 'COMPLETED' order by " . $table . "_name" ;
	}
	else {
		$sql = " select " . $table . "_id, " . $table . "_name from " . $table . " order by " . $table . "_name" ;
	}
	$result = mysql_query($sql) or exit(mysql_error());
	
	echo "<select name='". $table . "_id' style='width:150px;'>";
	echo "<option value=''>Please select</option>";
	
	while($rows = mysql_fetch_row($result)) {		
		if( $value == $rows[0] ) {
			echo "<option value='" . $rows[0] . "' selected>" . $rows[1] . "</option>";
		} else {
			echo "<option value='" . $rows[0] . "'>" . $rows[1] . "</option>";
		}	
	}
	
	echo "</select>";

	mysql_free_result($result);
	
}

// $table Table name
// $value each option value
// $scolumn each option name
// $event Action event
// $order Query order
// $type display type
// $condition Query condition
// attribute name
function  getOption2($table,$value,$scolumn=NULL, $event = NULL, $order = NULL,$type = NULL,$condition = NULL,$name,$default=NULL,$width=NULL) {
	if ($scolumn) {
		$sql = " select " . $table . "_id, " . $scolumn ." from " . $table;
	} else {
		$sql = " select " . $table . "_id, " . $table . "_name from " . $table;
	}
	
	if ($condition) {
		$sql .= " WHERE ". $condition;
	}

	if ($order != '' ) {
		$sql .=  " order by " . $order . " ASC" ;
	}
	
	$result = mysql_query($sql) or exit(mysql_error());
	
	switch($type) {
		case "radio":
		$text = "\n";
		while($rows = mysql_fetch_row($result)) {		
			if(get_db_array($value,$rows[0])) {			
				$text.="<INPUT TYPE='radio' $event checked NAME='" . $name . "' VALUE='". $rows[0] . "'>&nbsp;".$rows[1]."&nbsp;&nbsp;&nbsp;";
			} else {
				$text.="<INPUT TYPE='radio' $event NAME='" . $name . "' VALUE='". $rows[0] . "'>&nbsp;".$rows[1]."&nbsp;&nbsp;&nbsp;";
			}	
		}
		break;
		
		case "checkbox":
		$text = "\n";
		while($rows = mysql_fetch_row($result)) {		
						
			if(get_db_array($value,$rows[0])) {		
				$text.="<INPUT TYPE='checkbox' $event checked NAME='" . $name . "[]' VALUE='". $rows[0] . "'>&nbsp;".$rows[1]."&nbsp;&nbsp;&nbsp;";
			} else {
				$text.="<INPUT TYPE='checkbox' $event NAME='" . $name . "[]' VALUE='". $rows[0] . "'>&nbsp;".$rows[1]."&nbsp;&nbsp;&nbsp;";
			}	
		}
		break;
		
		
		default:
		if ($default == "") {
			$default = "Please select";
		}
		$_width = 0;
		if($width != "") {
			$_width = "style='width:".$width."px;' "; 
		}
		
		$text = "<select name='". $name . "' ". $_width.  $event .">";
		$text .= "<option value=''>$default</option>";
	
		while($rows = mysql_fetch_row($result)) {		
			if(get_db_array($value,$rows[0])) {		
				$text .= "<option value='" . $rows[0] . "' selected>" . $rows[1] . "</option>";
			} else {
				$text .= "<option value='" . $rows[0] . "'>" . $rows[1] . "</option>";
			}	
		}
	
		$text .= "</select>";

		mysql_free_result($result);
		break;
	}
	
	return $text;
	
	
}

//$table = Table name
//$field = Enum or Set field in $table
//$style = select or whatever (defaults to check box)
//$data = field value (optional the data from field in table)
//$sort = yes or no (sort options ascending)
//$title = optional; null first field for a select
//$name = name of tag
function DrawFromDB($table,$field,$style,$data="",$sort="yes ",$title="",$event=NULL,$name = NULL)
{
	//connect to DB;
	$query=mysql_query("SHOW COLUMNS FROM ".$table." LIKE '".$field."'") or die (mysql_error());
	$text = "";
	if(mysql_num_rows($query)>0)
	{
		$row=mysql_fetch_row($query);
		
		$options=explode("','",preg_replace("/(enum|set)\('(.+?)'\)/","\\2",$row[1]));
		
		if ($sort =="yes")
			sort($options);
		$ARay = explode(",",$data);
		
	}
	
	switch ($style)
	{
		case "select":
			
		if ($name) {
			$text="\n\n<select name='". $name ."' $event >\n";
		} else {
			$text="\n\n<select name='". $field ."' $event >\n";
		}
		
		if ($title > "") $text.="<option value=\"\">" . $title . "</option>\n";
		for ($i=0;$i<count($options);$i++)
		{
			$selected = NULL;
			if ($data == $options[$i]) $selected ="SELECTED ";
			$text.="<option " . $selected . "value=\"".$options[$i]."\">".ucfirst($options[$i])."</option>\n";
		}
		$text.="</select>\n\n";
		break;

		case "checkbox":
		$text="\n";
		for ($i=0;$i<count($options);$i++)
		{
			$checked=NULL;
			for ($j=0;$j<count($ARay);$j++)
			{
				if ($ARay[$j] == $options[$i]) $checked=" CHECKED ";
			}
			
			if($name) {
				$text.="<INPUT TYPE='checkbox'" .$event. $checked . " NAME='" . $name."[]' VALUE='". $options[$i] . "'>&nbsp;".ucfirst($options[$i])."&nbsp;&nbsp;&nbsp; \n";
			} else {
				$text.="<INPUT TYPE='checkbox'" .$event. $checked . " NAME='" . $field."[]' VALUE='". $options[$i] . "'>&nbsp;".ucfirst($options[$i])."&nbsp;&nbsp;&nbsp; \n";
			}
			
		}
		$text.="\n";
		
		break;
		
		default:
		$text="\n";
		for ($i=0;$i<count($options);$i++)
		{
			$checked=NULL;
			for ($j=0;$j<count($ARay);$j++)
			{
				if ($ARay[$j] == $options[$i]) $checked=" CHECKED ";
			}
			
			if($name) {
				$text.="<INPUT TYPE='radio'" .$event. $checked . " NAME='" . $name."' VALUE='". $options[$i] . "'>&nbsp;".ucfirst($options[$i])."&nbsp;&nbsp;&nbsp; ";
			}else {
				$text.="<INPUT TYPE='radio'" .$event. $checked . " NAME='" . $field."' VALUE='". $options[$i] . "'>&nbsp;".ucfirst($options[$i])."&nbsp;&nbsp;&nbsp; ";
			}
			
		}
		$text.="\n";
		
		break;
	}

	return $text;
}

function getName($table, $index) {
	$sql = " select " . $table . "_name from " . $table . " where " . $table . "_id = '" . $index . "'" ;

	$result1 = mysql_query($sql) or exit(mysql_error());
	while ($rows = mysql_fetch_row($result1)) {
		if ($rows) {
			return $rows[0];
		}
		else {
			return "";
		}
	}

	mysql_free_result($result1);
}

/*
 $table = table name
 $column_name = column name
 $cond = select condition with and command
 */
function getValue($table, $column_name, $cond) {
	$sql = " SELECT " . $column_name . " FROM ". $table . " WHERE 1=1 ".$cond ;

	$result1 = mysql_query($sql) or exit(mysql_error());
	while ($rows = mysql_fetch_row($result1)) {
		if ($rows) {
			return $rows[0];
		}
		else {
			return "";
		}
	}
	mysql_free_result($result1);
}

function getSpecificName($table,$value, $index ) {
	$sql = " select " . $value . " from " . $table . " where " . $table . "_id = '" . $index . "'" ;

	$result1 = mysql_query($sql) or exit(mysql_error());
	while ($rows = mysql_fetch_row($result1)) {
		if ($rows) {
			return $rows[0];
		}
		else {
			return "";
		}
	}

	mysql_free_result($result1);
}

function processOption($name,$value,$width=null) {
	$process_status = array ("PROCESSING" ,"COMPLETED" ,"HOLDING");
	
	if ($width) {
		echo "<select name='". $name . "' style='width:$width;'>";
	} else {
		echo "<select name='". $name . "' style='width:150px;'>";
	}
	
	echo "<option value=''>Please select</option>";
	
	for($j=0; $j < count($process_status); $j++) {
		if($value == $process_status[$j]) 
			echo "<option value='".$process_status[$j]."' selected>".$process_status[$j]."</option>";
		else
			echo "<option value='".$process_status[$j]."'>".$process_status[$j]."</option>";
	}
	
	echo "</select>";
}

function yearOption($name,$value) {

	echo "<select name='". $name . "' >";
	
	$currentYear = date('Y',time());

	for($j=5; $j >= 0 ; $j--) {
		if($value == ($currentYear-$j )) 
			echo "<option value='".($currentYear - $j) ."' selected>".($currentYear - $j)."</option>";
		else
			echo "<option value='".($currentYear - $j) ."'>".($currentYear - $j) ."</option>";
	}
	
	echo "</select>";

}

function getPON($value=NULL) {
	if ($value) {
		return $value;
	}
	else {
		$sql = "select max(orders_number) as orders_number from orders ";
		$result = mysql_query($sql) or exit(mysql_error());
		$rows = mysql_fetch_assoc($result);
		if($rows["orders_number"]) {
			$orders_number = explode("SGT",$rows["orders_number"]);
			$number = $orders_number[1];
			$year = substr($number,0,2);
			$no = substr($number,2,4);
		
			if ($year = date('y',time())) {
				$no += 1;
				if ( strlen($no) < 4 ) {
					$no = str_pad($no, 4, "0", STR_PAD_LEFT);
				}
			}
			else {
				$year = date('y',time());
				$no = "0001";
			}

			return "E".$year.$no;
		}
		else {
			$orders_number = "SGT".date('y',time())."0001";
			return $orders_number;
		}
	}
}


/** 
 * multi_array_key_exists function. 
 * 
 * @param mixed $needle The key you want to check for 
 * @param mixed $haystack The array you want to search 
 * @return bool 
 */ 
function multi_array_key_exists( $needle, $haystack ) { 
  
    foreach ( $haystack as $key => $value ) : 

        if ( $needle == $key ) 
            return true; 
        
        if ( is_array( $value ) ) : 
             if ( multi_array_key_exists( $needle, $value ) == true ) 
                return true; 
             else 
                 continue; 
        endif; 
        
    endforeach; 
    
    return false; 
} 


function &array_find_element_by_key($key, &$form) {
  if (array_key_exists($key, $form)) {
    $ret =& $form[$key];
    return $ret;
  }
  foreach ($form as $k => $v) {
    if (is_array($v)) {
      $ret =& array_find_element_by_key($key, $form[$k]);
      if ($ret) {
        return $ret;
      }
    }
  }
  return FALSE;
}

// for multivalue in column
//	default delimiter ; 
function set_db_array($value, $delimiter=NULL) {
	$text = "";
	if ($delimiter) {
		if (is_array($value) ) {
			if (count($value) > 0) {
				for($i=0; $i < count($value); $i++) {
					if ($text) {
						$text .= $delimiter . $value[$i];
					} else {
						$text .= $value[$i];
					}
				}
			} else {
				$text = $value[0];
			}
		} 
	} else {
		if (is_array($value) ) {
			if (count($value) > 0) {
				for($i=0; $i < count($value); $i++) {
					if ($text) {
						$text .= ";" . $value[$i];
					} else {
						$text .= $value[$i];
					}
				}
			} else {
				$text = $value[0];
			}
		} 
	}
	
	return $text;
}

function get_db_array($array,$value, $delimiter=NULL) {
	if ($delimiter) {
		$value_array = explode($delimiter,$array);
	} else {
		$value_array = explode(";",$array);
	}
	
	if (is_array($value_array) ) {
		for($i=0; $i < count($value_array); $i++) {
			if ($value_array[$i] == $value ) {
				return true;
			} else {
				return false;
			}
		}
	}
}


if (!function_exists('htmlspecialchars_decode')) {
        function htmlspecialchars_decode($str, $options="") {
                return strtr($str, array_flip(get_html_translation_table(HTML_SPECIALCHARS)));

        }
} 

function ynOption($name,$value,$type = NULL) {
	$status = array ("Y" ,"N" );
	
	switch($type) {
		case "radio":
		$text = "\n";
		
		for($j=0; $j < count($status); $j++) {
			if($value == $status[$j]) 
				$text .= "<input type='radio' checked value='".$status[$j]."' name='$name'>&nbsp;".$status[$j]."&nbsp;&nbsp;&nbsp;";
			else
				$text .= "<input type='radio' value='".$status[$j]."' name='$name'>&nbsp;".$status[$j]."&nbsp;&nbsp;&nbsp;";
		}

		break;
		
		case "checkbox":
		$text = "\n";
		
		for($j=0; $j < count($status); $j++) {
			if($value == $status[$j]) 
				$text .= "<input type='checkbox' checked value='".$status[$j]."' name='$name'>&nbsp;".$status[$j]."&nbsp;&nbsp;&nbsp;";
			else
				$text .= "<input type='checkbox' value='".$status[$j]."' name='$name'>&nbsp;".$status[$j]."&nbsp;&nbsp;&nbsp;";
		}

		break;
		
		
		default:
		
		$text = "<select name='". $name . "' style='width:150px;'>";
		$text .= "<option value=''>Please select</option>";
	
		for($j=0; $j < count($status); $j++) {
			if($value == $status[$j]) 
				$text .= "<option value='".$status[$j]."' selected>".$status[$j]."</option>";
			else
				$text .= "<option value='".$status[$j]."'>".$status[$j]."</option>";
		}
	
		$text .= "</select>";

		break;
	}
	
	return $text;
}

function date_diff_day($date) {
  $timepast ="";
  $timepast = ceil((strtotime($date)-strtotime("now"))/86400);
   
  return $timepast;
}

/*
* $table = table name
* $value = selected value
* $text = option text
* $opt_value = option value
* $multi = multiple
* $width = width
 * $where = condition
*/
function  getSelectOption($table,$value,$text,$opt_value=NULL, $event = NULL,$multi=NULL,$width=NULL,$where = NULL) {
	
	if ($opt_value) {
		$sql = " select " . $opt_value . ", " . $text . " FROM " . $table . " WHERE 1=1 " . $where .  " order by " . $text ." ASC" ;
	} else {
		$sql = " select " . $table . "_id, " . $text . " FROM " . $table . " WHERE 1=1 " . $where . " order by " . $text ." ASC" ;
	}
		
	$result = mysql_query($sql) or exit(mysql_error());
	
	$multi_arr = "";
	if ($multi) $multi_arr = "[]' multiple='multiple";
	
	if ($width){
		echo "<select name='". $table . "_id$multi_arr' " . $event . "width='".$width . "' style='width:" . $width."px' >";
	} else {
		echo "<select name='". $table . "_id$multi_arr' " . $event .">";
	}
	
	
	if (!$multi){
		echo "<option value=''>Please select</option>";
	}
		
	while($rows = mysql_fetch_row($result)) {		
		if( $value == $rows[0] ) {
			echo "<option value='" . $rows[0] . "' selected>" . $rows[1] . "</option>";
		} else {
			echo "<option value='" . $rows[0] . "'>" . $rows[1] . "</option>";
		}	
	}
	
	echo "</select>";

	mysql_free_result($result);
	
}

// DB set weeks 'Jan, Week 1'
function set_week_array($from,$to) {
	$value = array();
	$interval =  strftime('%W',strtotime($to)) - strftime('%W',strtotime($from)) + 1;
	
	$from = strtotime($from);
	if ( $interval >= 0 ) {
		for ($i=0; $i < $interval; $i++) {
			// return 'Jan, Week 1'
			$interval_week = '+'.$i.'weeks';
				
			$value[$i] = date("M", strtotime($interval_week, $from)) . ", Week " . getWeekNum(date("Y-m-j", strtotime($interval_week, $from))) . " " . date("y", strtotime($interval_week, $from)); 
		}
	}
	return $value;
}

function setoption_set_week_array($from,$to) {
	
	$interval =  strftime('%W',strtotime($to)) - strftime('%W',strtotime($from)) + 1;
	
	$from = strtotime($from);
	$value ="<select name='week'>";
	if ( $interval >= 0 ) {
		for ($i=0; $i < $interval; $i++) {
			// return 'Jan, Week 1'
			$interval_week = '+'.$i.'weeks';
			$str_week = date("M", strtotime($interval_week, $from)) . ", Week " . getWeekNum(date("Y-m-j", strtotime($interval_week, $from))) . " " . date("y", strtotime($interval_week, $from));
			$value .= "<option value='".$str_week."'>".$str_week."</option>"; 
		}
		
	}
	$value .="</select>";
	return $value;
}

function getWeekNum($date) {
	$i=1;
	$week=1;
		
	while ($i <= date('j',strtotime($date))) {
		if (date("N", mktime(0, 0, 0, date('n',strtotime($date)), $i, date('Y',strtotime($date)))) == "7") {
			$week++;
		}	
		$i++;
	}
	return $week;
}

function secure_string($string){
	 if(get_magic_quotes_gpc()) // prevents duplicate backslashes
	 {
		$string = stripslashes($string);
	 }
	 if (phpversion() >= '4.3.0') //if using new version of PHP and mysql_real_escape_string
	 {
		$string = mysql_real_escape_string(htmlentities($string, ENT_QUOTES));
	 }
	 else //for the old version of PHP and mysql_escape_string
	 {
		$string = mysql_escape_string(htmlentities($string, ENT_QUOTES));
	 }
	
	$string = preg_replace(sql_regcase("/(from|select|insert|delete|where|drop table|show tables|#|\*|--|\\\\)/"),"",$string);
	$string = str_replace("--","- -", $string);
	$string = str_replace("#", "\#", $string);

	return $string; //return the secure string
}

// parameter $array
// $name  
// $value
function getCustomizeOption($arrayname, $name, $value, $max = NULL,$choose = NULL,$event=NULL) {
	$name_title = array("Mr.", "Ms.","Mrs.", "Miss", "Dr.");
	$one2more = array("1","2","3","4","5","6","7","8","9","10","more");
	$rates = array("1.0","1.2","1.5","1.7","2.0","2.5","3.0");
	
	if ($arrayname == "name_title") {
		$array = $name_title;
	} else if ($arrayname == "one2more") {
		$array = $one2more;
	} else if ($arrayname == "" && $max <> "" ) {
		$array = array();
		for ($i = 1; $i <= $max ; $i++) {
			array_push($array, $i);
		}
	} else if ($arrayname == "rates" ) {
		$array = $rates;
	}
	
	echo "<select name='".$name . "' $event >";
	echo "<option value=''>$choose</option>";

	for($j=0; $j < count($array); $j++) {
		if($value == $array[$j]) {
			echo "<option value='".$array[$j]."' selected>".$array[$j]."</option>";
		}else {
			echo "<option value='".$array[$j]."' >".$array[$j]."</option>";
		}
	}
	
	echo "</select>";
}

function __json_encode( $data ) {            
    if( is_array($data) || is_object($data) ) { 
        $islist = is_array($data) && ( empty($data) || array_keys($data) === range(0,count($data)-1) );
         
        if( $islist ) { 
            $json = '[' . implode(',', array_map('__json_encode', $data) ) . ']';
         } else { 
            $items = Array(); 
            foreach( $data as $key => $value ) { 
                $items[] = __json_encode("$key") . ':' . __json_encode($value); 
            } 
            $json = '{' . implode(',', $items) . '}'; 
        } 
    } elseif( is_string($data) ) { 
        # Escape non-printable or Non-ASCII characters. 
        # I also put the \\ character first, as suggested in comments on the 'addclashes' page.
         $string = '"' . addcslashes($data, "\\\"\n\r\t/" . chr(8) . chr(12)) . '"';
         $json    = ''; 
        $len    = strlen($string); 
        # Convert UTF-8 to Hexadecimal Codepoints. 
        for( $i = 0; $i < $len; $i++ ) { 
            
            $char = $string[$i]; 
            $c1 = ord($char); 
            
            # Single byte; 
            if( $c1 <128 ) { 
                $json .= ($c1 > 31) ? $char : sprintf("\\u%04x", $c1); 
                continue; 
            } 
            
            # Double byte 
            $c2 = ord($string[++$i]); 
            if ( ($c1 & 32) === 0 ) { 
                $json .= sprintf("\\u%04x", ($c1 - 192) * 64 + $c2 - 128); 
                continue; 
            } 
            
            # Triple 
            $c3 = ord($string[++$i]); 
            if( ($c1 & 16) === 0 ) { 
                $json .= sprintf("\\u%04x", (($c1 - 224) <<12) + (($c2 - 128) << 6) + ($c3 - 128));
                 continue; 
            } 
                
            # Quadruple 
            $c4 = ord($string[++$i]); 
            if( ($c1 & 8 ) === 0 ) { 
                $u = (($c1 & 15) << 2) + (($c2>>4) & 3) - 1; 
            
                $w1 = (54<<10) + ($u<<6) + (($c2 & 15) << 2) + (($c3>>4) & 3); 
                $w2 = (55<<10) + (($c3 & 15)<<6) + ($c4-128); 
                $json .= sprintf("\\u%04x\\u%04x", $w1, $w2); 
            } 
        } 
    } else { 
        # int, floats, bools, null 
        $json = strtolower(var_export( $data, true )); 
    } 
    return $json; 
} 
 

/*
 * SQL Failure Handler
 * usage
 * mysql_query('$query) or die(sql_failure_handler($query, mysql_error()));
 * 
 */
function sql_failure_handler($query, $error) {
	$msg = htmlspecialchars('Failed Query: {$query}<br>SQL Error: {$error}');
	//error_log($msg, 3, '/public_html/order/logs/sql_error_log');
	if (defined(‘debug’)) {
		return $msg;
	}
	return 'An error occurs, please try again later.';
}

function getMsgArr($msg) {
   $convMsg = mb_convert_encoding($msg, "UTF-8", "EUC-KR");
   $resultArr = array();

 

   // 1: 한글
   $pattern = '/[\x{1100}-\x{11FF}\x{3130}-\x{318F}\x{AC00}-\x{D7AF}]+/u';
   preg_match_all($pattern,$convMsg,$match);
   $resultArr[1] = mb_convert_encoding(implode('',$match[0]),"EUC-KR", "UTF-8");

 

   // 2: 영문
   $pattern = '/[a-zA-Z]/';
   preg_match_all($pattern,$convMsg,$match);
   $resultArr[2] = mb_convert_encoding(implode('',$match[0]),"EUC-KR", "UTF-8");

 

   // 4: 숫자
   $pattern = '/[0-9]/';
   preg_match_all($pattern,$convMsg,$match);
   $resultArr[4] = mb_convert_encoding(implode('',$match[0]),"EUC-KR", "UTF-8");

 

   // 8: 특수기호
   $pattern = '/[^\x{1100}-\x{11FF}\x{3130}-\x{318F}\x{AC00}-\x{D7AF}0-9a-zA-Z]+/u';
   preg_match_all($pattern,$convMsg,$match);
   $resultArr[8] = mb_convert_encoding(implode('',$match[0]),"EUC-KR", "UTF-8");

 

   // 3: 한글 + 영문
   $pattern = '/[\x{1100}-\x{11FF}\x{3130}-\x{318F}\x{AC00}-\x{D7AF}a-zA-Z]+/u';
   preg_match_all($pattern,$convMsg,$match);
   $resultArr[3] = mb_convert_encoding(implode('',$match[0]),"EUC-KR", "UTF-8");

 

   // 5: 한글 + 숫자
   $pattern = '/[\x{1100}-\x{11FF}\x{3130}-\x{318F}\x{AC00}-\x{D7AF}0-9]+/u';
   preg_match_all($pattern,$convMsg,$match);
   $resultArr[5] = mb_convert_encoding(implode('',$match[0]),"EUC-KR", "UTF-8");

 

   // 9: 한글 + 특수기호
   $pattern = '/[^0-9a-zA-Z]/';
   preg_match_all($pattern,$convMsg,$match);
   $resultArr[9] = mb_convert_encoding(implode('',$match[0]),"EUC-KR", "UTF-8");

 

   // 6: 영문 + 숫자
   $pattern = '/[0-9a-zA-Z]/';
   preg_match_all($pattern,$convMsg,$match);
   $resultArr[6] = mb_convert_encoding(implode('',$match[0]),"EUC-KR", "UTF-8");

 

   // 10: 영문 + 특수기호
   $pattern = '/[^\x{1100}-\x{11FF}\x{3130}-\x{318F}\x{AC00}-\x{D7AF}0-9]+/u';
   preg_match_all($pattern,$convMsg,$match);
   $resultArr[10] = mb_convert_encoding(implode('',$match[0]),"EUC-KR", "UTF-8"); 

 

   // 12: 숫자 + 특수기호
   $pattern = '/[^\x{1100}-\x{11FF}\x{3130}-\x{318F}\x{AC00}-\x{D7AF}a-zA-Z]+/u';
   preg_match_all($pattern,$convMsg,$match);
   $resultArr[12] = mb_convert_encoding(implode('',$match[0]),"EUC-KR", "UTF-8");

 

   // 7: 한글 + 영문 + 숫자
   $pattern = '/[\x{1100}-\x{11FF}\x{3130}-\x{318F}\x{AC00}-\x{D7AF}0-9a-zA-Z]+/u';
   preg_match_all($pattern,$convMsg,$match);
   $resultArr[7] = mb_convert_encoding(implode('',$match[0]),"EUC-KR", "UTF-8");

 

   // 11: 한글 + 영어 + 특수기호
   $pattern = '/[^0-9]/';
   preg_match_all($pattern,$convMsg,$match);
   $resultArr[11] = mb_convert_encoding(implode('',$match[0]),"EUC-KR", "UTF-8");

 

   // 14: 영문 + 숫자 + 특수기호
   $pattern = '/[^\x{1100}-\x{11FF}\x{3130}-\x{318F}\x{AC00}-\x{D7AF}]+/u';
   preg_match_all($pattern,$convMsg,$match);
   $resultArr[14] = mb_convert_encoding(implode('',$match[0]),"EUC-KR", "UTF-8");

 

   // 13: 한글 + 숫자 + 특수기호
   $pattern = '/[^a-zA-Z]/';
   preg_match_all($pattern,$convMsg,$match);
   $resultArr[13] = mb_convert_encoding(implode('',$match[0]),"EUC-KR", "UTF-8");

 

   // 15: 한글 + 영문 + 숫자 + 특수기호
   $resultArr[15] = $convMsg;

   return $resultArr;
  }

function arr_search ( $array, $expression ) { 
    $result = array(); 
    $expression = preg_replace ( "/([^\s]+?)(=|<|>|!)/", "\$a['$1']$2", $expression );
     foreach ( $array as $a ) if ( eval ( "return $expression;" ) ) $result[] = $a;
     return $result; 
} 

?>