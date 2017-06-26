<?
include_once "include/logincheck.inc";
include_once "include/common.inc";
include_once "include/dbconn.inc";
include_once "include/myfunc.inc";

$orderno = $_REQUEST["orderno"];
$deliverydate = $_REQUEST["pyear"]."-".$_REQUEST["pmonth"]."-".$_REQUEST["pday"];
// 1. 주문입력
$sql = "INSERT INTO orders (userid, username, user_level, d_name, d_phone, d_abn, d_fax, d_mobile, d_email, d_addr, porderno, amount, dcharge, dis_rate, dis_amount, orderstat, payment_method, paystat, deliverydate, orderdate, orderdate_ano, ordermemo, orderby, alevel) ";
$sql.= "SELECT userid, username, user_level, d_name, d_phone, d_abn, d_fax, d_mobile, d_email, d_addr, porderno, amount, dcharge, dis_rate, dis_amount, orderstat, payment_method, paystat, '$deliverydate' , '$now_datetime', '$now_datetimeano', ordermemo, orderby, alevel FROM orders WHERE orderno=".$orderno;
//echo $sql . "<p>";
//exit;
pQuery($sql,"insert");

$NEW_ORDERNO = mysql_insert_id();	

// 주문상세 인서트
$sql = "INSERT INTO orderdetail (orderno, productid, productnm, qty, price, orderdate, orderdate_ano) ";
$sql.= "SELECT $NEW_ORDERNO, productid, productnm, qty, price,  '$now_datetime',  '$now_datetimeano	 ' FROM orderdetail WHERE orderno=".$orderno;
//echo $sql . "<p>";
pQuery($sql,"insert");

// 변경된 수량 업데이트
$cart_qty = $_REQUEST["cart_qty"];
$cart_productid = $_REQUEST["cart_productid"];
for($i=0; $i < count($cart_productid); $i++) {
	$sql = "UPDATE orderdetail set qty=".$cart_qty[$i]. " WHERE orderno=".$NEW_ORDERNO." AND productid='".$cart_productid[$i]. "'";	
	//echo $sql . "<p>";
	pQuery($sql,"update");	
}

// 변경된 수량에 대한 Ex. Amount 업데이트
$sql  = "SELECT qty, price FROM orderdetail WHERE orderno=".$NEW_ORDERNO;
$result = mysql_query($sql) or exit(mysql_error());
$amount = 0;
while($rows = mysql_fetch_assoc($result)) {
	$price = $rows["price"];
	$qty = $rows["qty"];
	$amount += ($price * $qty);	
}
$sql = "UPDATE orders SET amount=".$amount." WHERE orderno=".$NEW_ORDERNO;
pQuery($sql,"update");	
?>
<script type="text/javascript">
alert("Order Completed!");
self.close();
opener.location.replace("order_list.php");
</script>