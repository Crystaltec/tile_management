<?php
if ($block <= 1) echo "";
else echo "[<a href='".$_SERVER["PHP_SELF"]."?page=".($startPage-1)."&$srch_param'>prev</a>]&nbsp;&nbsp;";

for ($i = $startPage; $i <= $endPage; $i++) {
	if ($page == $i) echo "<b>$i</b>&nbsp&nbsp;";
	else echo "<a href='".$_SERVER["PHP_SELF"]."?page=$i&$srch_param'>[".$i."]</a>&nbsp;&nbsp;";
}

// ��v�� ��v �ƴс� �˻砍
$totalBlock = ceil($totalPage/$limitPage);
if ($block >= $totalBlock) echo " ";
else echo "&nbsp;&nbsp;[<a href='".$_SERVER["PHP_SELF"]."?page=".($endPage + 1)."&$srch_param'>next</a>]";
?>
