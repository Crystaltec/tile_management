<?
include_once "include/logincheck.inc";
include_once "include/common.inc";
include_once "include/dbconn.inc";
include_once "include/myfunc.inc";
$itemcnt = $_REQUEST["itemcnt"];
$project_id = $_REQUEST["project_id"];
//echo $itemcnt ."<br>";

$str1 = "";
for($i=0; $i < $itemcnt; $i++) {
	$qty = $_REQUEST["qty$i"];
	$material_id=$_REQUEST["material_id$i"];
	//$buycheckbox = $_REQUEST["buycheckbox$i"];
	if ($qty != "") {
		//echo "material_id : ". $material_id . ", qty : " . $qty . "<br>";
		$sql = "SELECT COUNT(cart_id) FROM cart WHERE user_id='$Sync_id' AND material_id='$material_id'";
		//echo $sql;
		$row = getRowCount($sql);		
		if($row[0] > 0) {
			$str1 = "<script>alert('This product has aleady inserted to cart!');</script>";			
		} else {
			$sql = "INSERT INTO cart (user_id, material_id, qty, regdate) VALUES ('$Sync_id', '$material_id', $qty, $now_dateano)";
			pQuery($sql,"insert");
		}
	}
}

if($str1 != "") 
	echo $str1;
?>
<script language="javascript">
location.href="cart_list.php?project_id=<?=$project_id?>";
</script>
