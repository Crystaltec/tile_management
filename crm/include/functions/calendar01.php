<?php

// todays
$dateyy = date("Y");
$datemm = date("m");
$datedd = date("d");
$datehh = date("h");

####################### 해당연월의 일수를 계산한다. #############################
function get_totaldays($year,$month) {
	$date = 1;
	while(checkdate($month,$date,$year)) {
		$date++;
	}
	$date--;
	return $date;
}

function showCalendar($year,$month,$total_days)
{

	global $dateyy, $datemm, $datedd;

	switch($month) {
	 case (1) : $mon = 'January'; break;
	 case (2) : $mon = 'February'; break;
	 case (3) : $mon = 'March'; break;
	 case (4) : $mon = 'April'; break;
	 case (5) : $mon = 'May'; break;
	 case (6) : $mon = 'June'; break;
	 case (7) : $mon = 'July'; break;
	 case (8) : $mon = 'August'; break;
	 case (9) : $mon = 'September'; break;
	 case (10) : $mon = 'October'; break;
	 case (11) : $mon = 'November'; break;
	 case (12) : $mon = 'December'; break;	 
	 default: echo "err";
	}

	$year_o = $year;
	$month_b = $month - 1;
	if ($month_b < 1) { $month_a = 12; $year_o = $year_o - 1; }
	$month_a = $month + 1;
	if ($month_a > 12) { $month_a = 1; $year_o = $year_o + 1; }

	$first_day = date('w', mktime(0,0,0,$month,1,$year));

	// schedules table 데이터 가져오기
	$sql ="select yy, mm, dd, contents from schedules where yy='".$year."' and mm='".$month."'";
	$result = tep_db_query($sql);
	$schedule_list = array();
	while($id_rst = tep_db_fetch_array($result)) {
		$schedule_list = array_merge($schedule_list, array($id_rst));								
	}
	tep_db_free_result($result);

	$schedule_list_cnt = count($schedule_list);
	//print_r($schedule_list);

	  echo "
		<table border=0 cellspacing=0 cellpadding=1> <tr><td>

			<table border=1 align='center' cellpadding='2' cellspacing='0' bordercolordark='white' 
					  bordercolorlight='black'  style='border-top-width:1; border-right-width:1; border-bottom-width:1; 
					  border-left-width:1; border-color:silver; border-style:solid;' id='schedule'>
			  <tr align=center bgcolor=c0c0c0 height=30>
				<td align=center>
				  <a href=schedule.php?inputY=$year_o&inputM=$month_b><font size=2 color='0099cc'>&lt;&lt;</a></font>
				</td>
				<td colspan=5>				  
				  <font size=3 color=blue><b> $month ($mon)</b>
				  <font size=2 color='white' face=굴림><b>$year</b>				  
				</td>
				<td align=center>
				  <a href=schedule.php?inputY=$year_o&inputM=$month_a><font size=2 color='0099cc'>&gt;&gt;</a></font>
				</td>
			  </tr>
			  <tr height=30>
				<td width=30 align=center style='font-weight:bold' bgcolor=red>Sun.</td>
				<td width=30 align=center style='font-weight:bold'>Mon.</td>
				<td width=30 align=center style='font-weight:bold'>Tues.</td>
				<td width=30 align=center style='font-weight:bold'>Wed.</td>
				<td width=30 align=center style='font-weight:bold'>Thu.</td> 
				<td width=30 align=center style='font-weight:bold'>Fri.</td> 
				<td width=30 align=center style='font-weight:bold'>Sat.</td>
			  </tr>

			  <tr height=30> ";

		$col = 0;

		// 달력앞에 자리가 나으면 공백으로 채줘주기	
		for($i = 0; $i < $first_day; $i++) {
			echo " <td bgcolor=#FFFFFF width=30>&nbsp;</td>";
			$col++;
		}

		for($j = 1; $j <= $total_days; $j++) {

			// 1. 일정이 있는 경우 굵은 글씨
			// 2. 오늘 날짜랑 같으면 배경색 다르게 표시 
			// 3. 일요일은 빨간색으로
			$backColor = "#FFFFFF";
			$textStyle = "color:black; font-size:10pt;";
			if ( $datedd == $j ) $backColor = "#989384";
			if ( $col % 7 == 0) $textStyle = "color:red; font-size:10pt";

			// 저장된 스케쥴이 있다면 bold처리 하기
			for($k = 0; $k < $schedule_list_cnt; $k++) {
				//echo $schedule_list[$k]["yy"] . "<br>";
				//echo $day . "<br>"; ;
				if ($schedule_list[$k]["yy"] == $year && $schedule_list[$k]["mm"] == $month && $schedule_list[$k]["dd"] == $j) {
					$textStyle .= "color:#8FD820; font-size:10pt;font-weight:bold;";
					break;
				}
			}

			echo "<td bgcolor='$backColor' align=center width=30 id='day_$j'><a href=\"javascript:getScheduleDetail('$year', '$month', '$j', '$col')\"><span style='$textStyle'>$j</span></a></td>";
			
			$col++; //요일을 계산하여 7이 되면 tr시켜줌

			if($col == 7) {
				echo " </tr> ";
				if($j != $total_days) { echo "<tr height=30>"; }
				$col = 0;
			}

		}

		// 달력뒤에 자리가 나으면 공백으로 채줘주기	
		while($col > 0 && $col < 7) {
			echo "<td bgcolor=#ffffff>&nbsp;</td>";
			$col++;
		}

		echo " 
		</tr> </table>
		</td> </tr> </table>
		";

	$year = $year_o;

}

?>



