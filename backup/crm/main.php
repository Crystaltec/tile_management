<?
ob_start();
include_once "include/common.inc";
include_once "include/dbconn.inc";
include_once "include/myfunc.inc";
include_once "htmlinclude/head.php";
$new_date = 1;


?>
<BODY leftmargin=0 topmargin=0>
<table border="0" cellpadding="0" cellspacing="0" width="700">
<tr>
	<td style="padding-left:30px" background="images/bg_main01_1.gif" valign="bottom" height="43">
		<? include_once $ABS_DIR . "/include/login_info.php"; ?>
	</td>
</tr>	
<tr>
	<td style="padding-left:30px" valign="top">		
		<table border="0" cellpadding="0" cellspacing="0" width="700" background="images/main_top01.gif" height="172">
		<tr>
			<td valign="top" height="60">&nbsp;</td><td >&nbsp;</td>
		</tr>	
		<?	 
			$today_s = date("Y-m-d") . " 00:00:00";
			$today_e = date("Y-m-d") . " 23:59:50";

			$Cond = " WHERE (orderdate >='$today_s' AND orderdate <='$today_e') ";
			if($Sync_alevel != "A") {
				$Cond .= " AND userid='$Sync_id' ";
			} 

			$row = array();
			
			$sql = "SELECT COUNT(*) FROM orders " . $Cond;
			$result = mysql_query($sql);
			$row = mysql_fetch_row($result);
			$orderCount = $row[0];
			mysql_free_result($result);

			$sql = "SELECT COUNT(*) FROM orders " . $Cond;
			$result = mysql_query($sql);
			$row = mysql_fetch_row($result);
			$orderCount = $row[0];
			mysql_free_result($result);

			$sql = "SELECT IFNULL(SUM(amount),0) FROM orders " . $Cond;
			$result = mysql_query($sql);
			$row = mysql_fetch_row($result);
			$orderSum = $row[0];
			mysql_free_result($result);
			//echo $sql;

			$sql = "SELECT IFNULL(SUM(dis_amount),0) FROM orders " . $Cond;
			$result = mysql_query($sql);
			$row = mysql_fetch_row($result);
			$disSum = $row[0];
			mysql_free_result($result);
			//echo $sql;			
		?>
		<tr>
			<td style="padding-left:8px" valign="top" width="223">Order count : <?=$orderCount?> <br> Order amount : $ <?=number_format($orderSum)?> 
			<br>Discount amount: $ <?=number_format($disSum)?> </td>
			<td width="477" valign="top">
				<!-- Latest Order ----------------------------------------------------------->
				<table border="0" cellpadding="0" cellspacing="0" width="447">
				<tr><td height="5" colspan="3"></td></tr>
				<?
					// 권한설정
					if($Sync_alevel == "A") {
						$sql = "SELECT orderno, orderstat, paystat, amount, orderdate FROM orders ORDER BY orderno DESC LIMIT 0,4";
					} else {
						$sql = "SELECT orderno, orderstat, paystat, amount, orderdate FROM orders WHERE userid='$Sync_id' ORDER BY orderno DESC LIMIT 0,4";
					}
					$result = mysql_query($sql);
					while($rows = mysql_fetch_row($result)) {
						$orderno = $rows[0];
						$orderstat = $rows[1];
						$paystat = $rows[2];
						$amount = $rows[3];
						$orderdate = $rows[4];
						echo "<tr><td width='4' height='24' style='padding-left:7px'><img src='images/icon_dot02.gif'></td>";
						echo "<td width='400'><a href='order_view.php?orderno=$orderno' style='color:black'>Orderno : $orderno&nbsp;&nbsp; Amount : $<span class=price>$amount</span>&nbsp;&nbsp;Order Status :$orderstat</a>";
						if((time()-strtotime($orderdate)) < 60*60*24*$new_date) echo " <img src='/zb/images/new.gif'>";
						echo "</td>";
						echo "<td width='70' align='center'>". substr($orderdate,0,10) . "</td></tr>";
					}
					//echo $sql;
					mysql_free_result($result);
				?>
				</table>	
			<!-- Latest Orders End!-------------------------------------------------------->
			</td>
		</tr>		
		</table>
		<table border="0" cellpadding="0" cellspacing="0" width="700">
		<tr><td height="10"></td></tr>
		<tr><td height="3" background="images/bg_check.gif"></td></tr>
		<tr><td height="20"></td></tr>
		</table>
		<table border="0" cellpadding="0" cellspacing="0" width="700">
		<tr>
			<td height="150" valign="top">
				<!-- News ----------------------------------------------------------->
				<table border="0" cellpadding="0" cellspacing="0">
				<tr><td colspan="3"><a href="/zb/list.php?startPage=1&boardid=zb_notice"><img src="images/main_title01.gif"></a></td></tr>
				<?
					$sql = "SELECT uid, boarder_id, subject, signdate, userfile_extra FROM zb_notice ORDER BY uid DESC LIMIT 0,4";
					$result = mysql_query($sql);
					while($rows = mysql_fetch_row($result)) {
						$uid = $rows[0];
						$boarder_id = $rows[1];
						$subject = $rows[2];
						$signdate = $rows[3];
						$userfile_extra = $rows[4];
						$filename=explode("|",$userfile_extra);
						echo "<tr><td width='8' height='24' style='padding-left:7px'><img src='images/icon_dot02.gif'></td>";
						echo "<td width='250'><a href='zb/view.php?uid=$uid&startPage=1&boardid=zb_notice'>$subject</a>";
						if((time()-strtotime($rows[3])) < 60*60*24*$new_date) echo " <img src='/zb/images/new.gif'>";
						//echo time()-strtotime($rows[3])<60*60*24*$new_date;
						//echo $rows[3];
						echo "</td>";
						echo "<td width='90' align='center'>". substr($signdate,0,10) . "</td></tr>";
					}
					mysql_free_result($result);
				?>
				</table>	
			<!-- News End!-------------------------------------------------------->
			</td>
			<td width="8"></td>
			<td height="150" valign="top">
				<table border="0" cellpadding="0" cellspacing="0">
				<tr><td colspan="3"><a href="/zb/list.php?startPage=1&boardid=zb_qna"><img src="images/main_title02.gif"></a></td></tr>
				<?
					$sql = "SELECT uid, boarder_id, subject, signdate FROM zb_qna ORDER BY notice DESC, fid DESC, thread ASC LIMIT 0,4";
					$result = mysql_query($sql);
					while($rows = mysql_fetch_row($result)) {
						$uid = $rows[0];
						$boarder_id = $rows[1];
						$subject = $rows[2];
						$signdate = $rows[3];
						echo "<tr><td width='8' height='24' style='padding-left:7px'><img src='images/icon_dot02.gif'></td>";
						echo "<td width='250' style='padding:5 0 5 5'><a href='zb/view.php?uid=$uid&startPage=1&boardid=zb_qna'>$subject</a>";
						if((time()-strtotime($rows[3])) < 60*60*24*$new_date) echo " <img src='/zb/images/new.gif'>";
						echo "</td><td width='90' align='center'>". substr($signdate,0,10) . "</td></tr>";
					}
					mysql_free_result($result);
				?>
				</table>	
			</td>
		</tr>
		</table>
		<table border="0" cellpadding="0" cellspacing="0" width="700">
		<tr><td height="10"></td></tr>
		<tr><td height="3" background="images/bg_check.gif"></td></tr>
		<tr><td height="20"></td></tr>
		</table>
		<table border="0" cellpadding="0" cellspacing="0" width="700">
		<tr><td ><img src="images/main_title03.gif"></td></tr>
		</table>
		<table border="0" cellpadding="0" cellspacing="0" width="700" style="border:1px solid #c3c3c6">	
		<?
			$sql = "SELECT imgname_extra, productexp, priceC, productname FROM products ORDER BY regdate DESC LIMIT 0,2";
			$result = mysql_query($sql);
			while($rows = mysql_fetch_row($result)) {
				//echo $rows[0];
				$imgname_extra = explode("|", $rows[0]);
				//echo $imgname_extra[0];
				$productexp = ($rows[1]);
				$price = $rows[2];
				$productname = $rows[3];				
		?>		
		<tr>
			<td valign="top" style="padding:10 10 0 10" width="180">
			<img src="<?=$upload_dir?>/thumb_<?=$imgname_extra[0]?>">
			</td>			
			<td valign="top" style="padding:10 10 0 10;line-height:20pt">
			<b style="font-size:11pt"><u><?=$productname?></u></b><br>
			<?=$productexp?><br>
			Price : <span class="price">$<?=$price?></span>
			</td>
		</tr>
		<tr><td height="3" background="images/bg_check.gif" colspan="2"></td></tr>
		<?
			}
			mysql_free_result($result);
		?>
		</table>
	</td>
</tr>

<tr><td></td></tr>
</table>
</BODY>
</HTML>
<? ob_flush(); ?>
