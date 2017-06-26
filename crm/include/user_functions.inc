<?
## checkform : process파일로 넘어온 값 보기
function checkform($action){
	if($action=="post"){
		while(list($key,$value)= each($_POST)){

			if(is_array($value)){
				while(list($key1,$value1)=each($value)){
					echo $key."[".$key1."]" ." = ". $value1."<br>\n";
				}
			}else{
			echo $key ." = ". $value."<br>\n";
			}

		}
	}elseif($action=="get"){
		while(list($key,$value)= each($_GET)){

			if(is_array($value)){
				while(list($key1,$value1)=each($value)){
					echo $key."[".$key1."]" ." = ". $value1."<br>\n";
				}
			}else{
			echo $key ." = ". $value."<br>\n";
			}

		}
	}else{
		echo "사용방법 오류 : checkform 사용방법 = checkform(get or post);";
	}
}

## URL을 자동으로 링크
function auto_link($contents) {
	$pattern = "/(http|https|ftp|mms):\/\/[0-9a-z-]+(\.[_0-9a-z-]+)+(:[0-9]{2,4})?\/?";	// domain+port
	$pattern .= "([\.~_0-9a-z-]+\/?)*";	// sub roots
	$pattern .= "(\S+\.[_0-9a-z]+)?";		// file & extension string
	$pattern .= "(\?[_0-9a-z&amp;#%=\-\+]+)*/i";		// parameters
	$replacement = "<a href=\"\\0\" target=\"_blank\">\\0</a>";
	return preg_replace($pattern, $replacement, $contents, -1);
}

## 날짜 및 시간 더하기.
function f_dateadd($C,$N,$D) { //ex. f_dateadd(m,1,"2005-01-01 10:00:00")
	$ad_time=explode(" ",$D);
	$ad=explode("-",$ad_time[0]);
	$at=explode(":",$ad_time[1]);

	if($C=="Y"){$fdate= mktime($at[0],$at[1],$at[2],$ad[1],$ad[2],$ad[0]+$N);}
	if($C=="d"){$fdate= mktime($at[0],$at[1],$at[2],$ad[1],$ad[2]+$N,$ad[0]);}
	if($C=="H"){$fdate= mktime($at[0]+$N,$at[1],$at[2],$ad[1],$ad[2],$ad[0]);}
	if($C=="i"){$fdate= mktime($at[0],$at[1]+$N,$at[2],$ad[1],$ad[2],$ad[0]);}
	if($C=="s"){$fdate= mktime($at[0],$at[1],$at[2]+$N,$ad[1],$ad[2],$ad[0]);}
	if($C=="m") {
		$tdate= mktime($at[0],$at[1],$at[2],$ad[1]+$N,1,$ad[0]);
		if($ad[2]>mon(date("Y",$tdate),date("m",$tdate))) {$ad[2]=mon(date("Y",$tdate),date("m",$tdate));}
		$fdate= mktime($at[0],$at[1],$at[2],date("m",$tdate),$ad[2],date("Y",$tdate));
	}

	$ldate=date('Y-m-d H:i:s',$fdate);
	return $ldate;
}

## null 이 포함된 배열 더하기.
function my_array_sum($somearray){
   if(is_array($somearray)) $tmparr = array_sum($somearray);
   else $tmparr = 0;
   return $tmparr;
}

## 월별 날짜 계산
function mon($year,$month){
	if($month<1 || $month>12){
	echo "<script>window.alert('에러:잘못된 날짜입니다.');history.go(-1);</script>";
	exit;
}

$L=array('1','3','5','7','8','10','12');
$M=array('4','6','9','11');
	if(in_array($month,$L)){
		return 31;
	}elseif(in_array($month,$M)){
		return 30;
	}else{
		if($year%4==0){
			return 29;
		}else{
			return 28;
		}
	}
}

## DB쿼리 사용자정의 함수
function git($array) {
    if(is_array($array)) {
        reset($array);
        while(list ($key, $vlu) = each($array)) {
            if($vlu == NULL && strlen($vlu) < 1) unset($array[$key]);
        }
     return $array;
    }
}

function selectQuery($Array, $Table) {
    if(!is_array($Array) || sizeof($Array) < 1) {
		echo("DB인수가 적절하지 않습니다.");
		exit;
    }
    $Fields = "";
    while(list($key) = each($Array))    $Fields = $Fields . "$Table.$key, ";
    $Fields = rtrim($Fields, ", ");

    return $Fields;
}

function insertQuery($Array, $Table) {
    $Array = git($Array);

    reset($Array);

    $QueryTmp1 = ""; $QueryTmp2 = "";

	while(list($key, $vlu) = each($Array)) {
			$QueryTmp1 .= "$Table.$key, ";
			if(eregi("^\-[0-9]", $vlu) || !eregi("[^0-9]", $vlu) || !is_string($vlu)) $QueryTmp2 .= "'$vlu', ";
			else $QueryTmp2 .= "'$vlu', ";
	}

    $QueryTmp1  = rtrim($QueryTmp1, ", ");
    $QueryTmp2  = rtrim($QueryTmp2, ", ");

    $Query = "INSERT INTO $Table( " . $QueryTmp1 . " ) VALUES( " . $QueryTmp2 . " );";

    return $Query;
}

function updateQuery($NewArray, $Table, $Index, $IndexValue) {
	if(is_array($NewArray) && $Table != "" && $Index != "" && $IndexValue != "") {
		while(list($key, $vlu) = each($NewArray)) {
			if($vlu !="" && (eregi("^\-[0-9]", $vlu) || !eregi("[^0-9]", $vlu) || !is_string($vlu))) {
				$QueryTmp .= "$Table.$key = '$vlu', ";
			}else{
				$QueryTmp .= "$Table.$key = '$vlu', ";
			}
		}
		$QueryTmp = rtrim($QueryTmp, ", ");
		$Query  = "";
		$Query  = "UPDATE $Table";
		$Query .= " SET $QueryTmp";
			if(eregi("^\-[0-9]", $IndexValue) || !eregi("[^0-9]", $IndexValue) || !is_string($IndexValue)){
				$Query .= " WHERE $Table.$Index = $IndexValue;";
			}else{
				$Query .= " WHERE $Table.$Index = '$IndexValue';";
			}

		return $Query;
	}
	return false;
}

function deleteRecord($Table, $Index, $IndexValue) {
    if($Table != "" && $Index != "" && $IndexValue != "") {
        $Query = "";
        $Query = "DELETE FROM $Table WHERE $Index = $IndexValue;";
		 return $Query;
    }
}
?>