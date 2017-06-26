<?php
ob_start();
include_once "include/logincheck.inc";
include_once "include/common.inc";
include_once "include/dbconn.inc";
include_once "include/myfunc.inc";
include_once "htmlinclude/head.php";

$itemcount = 10;

$orders_number = secure_string($_REQUEST["orders_number"]);

## ĵ��, �〻 �迭 ����

$list_Records = array();

$Query  = "SELECT * FROM orders WHERE orders_number='" .$orders_number ."' ORDER BY orders_id ";

$id_cnn = mysql_query($Query) or exit(mysql_error());
while($id_rst = mysql_fetch_assoc($id_cnn)) {
	$list_Records = array_merge($list_Records, array($id_rst));
}
mysql_free_result($id_cnn);

$sql = "SELECT username, phone, fax, address, suburb, state, postcode FROM account WHERE userid='".$list_Records[0]["user_id"] . "'";
$id_cnn = mysql_query($sql) or exit(mysql_error());
$userinfo = mysql_fetch_assoc($id_cnn);
mysql_free_result($id_cnn);

$sql = "select * from supplier where supplier_id='".$list_Records[0]["supplier_id"]."'";
$id_cnn = mysql_query($sql) or exit(mysql_error());
$supplierinfo = mysql_fetch_assoc($id_cnn);
mysql_free_result($id_cnn);

if ($list_Records[0]["project_id"]) {
	$sql = "select * from project where project_id='".$list_Records[0]["project_id"]."'";
	$id_cnn = mysql_query($sql) or exit(mysql_error());
	$projectinfo = mysql_fetch_assoc($id_cnn);
	mysql_free_result($id_cnn);
}
else {
	$sql = "select * from company ";
	$id_cnn = mysql_query($sql) or exit(mysql_error());
	$companyinfo = mysql_fetch_assoc($id_cnn);
	mysql_free_result($id_cnn);
}
?>
<script>
function issueOrdersheet_pdf(orders_number) {
	window.open("issue_ordersheet_pdf.php?orders_number="+orders_number, "ordersheet_pdf", "");
}

function printpage() {
	window.print();
}


</script>
<style type="text/css"> 

body
{
	font-family: calibri;
	font-size: 12px;
}

#order_logo
{
	background-image: url(images/logo.jpg);
	background-repeat: no-repeat;
	vertical-align: bottom;
	text-align:center;
	width:120px;
}

#border_name
{
	font-family: verdana;
	font-size: 24px;
	text-align: center;
	vertical-align: top;
	font-weight:bold;	
}
#border_name span {
	display:inline-block;
	padding:5px 0;
	font-size:17px;
}

#border_date
{
	text-align: left;
	font-size: 15px;
	font-weight: bold;
}


#border_deliver_to
{
	vertical-align: top;
}

#border_to
{
	vertical-align: top;
}

#order_to {
	margin-left:12px;
}
#border_pon
{
	text-align: center;
	font-size: 17px;
	font-weight: bold;
	color:red;
}

#border_content
{
	border:1px solid #000;
	background-position: top;
	height:430px;
}

#border_comments
{
	border:1px solid #000;
}

.comments
{
	font-size: 9px !important;
	text-align: center !important;
	display:block !important;
	padding:0 !important;
	margin:0 !important;
	font-weight:normal !important;
}

.company_name
{
	font-family: Tahoma;
	font-size: 15px;
	font-weight: bold;
	vertical-align:top;
}
.address
{
	vertical-align: top;
}

.title {
	text-align: left;
	font-weight:bold;
	font-size: 14px;
	vertical-align:bottom;
	height:20px;
}

.content
{
	text-align: left;
	font-weight: bold;
}

.subcontent
{
	text-align: left;
	padding-right: 5px;
}

.subcontent_title
{
	text-align: right;
	padding-right: 5px;
	vertical-align: top;
	height:20px;
}
.subcontent_inst {
	font-size:.9em;
}

.remarks {
	font-weight: bold;
	text-align: left;
	padding-left: 10px;
	padding-right: 10px;
	border-top-width: 1px;
	border-top-style: solid;
	border-top-color: #000;
	width:650px;
	height:100px;
	overflow:hidden;
	text-overflow:ellipsis;
	vertical-align:top;
}

.main_content {
	font-weight: bold;
	text-align: center;
	border-right-width: 1px;
	border-top-style: none;
	border-right-style: solid;
	border-left-style: none;
	border-right-color: #000;
	line-height:100%;
	margin: 0;
	padding: 0;
	height: 25px;
	font-size:11px;
}

.main_content_right {
	font-weight: bold;
	text-align: center;
	border-top-style: none;
	border-left-style: none;
	overflow:hidden;
	text-overflow:ellipsis;
	margin: 0;
	padding: 0;
	line-height:100%;
	height:25px;
	font-size:11px;
}

.main_content_big {
	font-weight: bold;
	border-right-width: 1px;
	border-top-style: none;
	border-right-style: solid;
	border-left-style: none;
	border-right-color: #000;
	line-height:150%;
	height:400px; 
	vertical-align:top; 
	text-align:left; 
	padding:10px 0 0 10px;
	
}

.main_content_big_right {
	font-weight: bold;
	border-top-style: none;
	border-left-style: none;
	overflow:hidden;
	text-overflow:ellipsis;
	line-height:150%;
	height:400px; 
	vertical-align:top; 
	text-align:right; 
	padding:10px 10px 0 0;
}

.main_top_content {
	font-weight: bold;
	text-align: center;
	border-right-width: 1px;
	border-bottom-width: 1px;
	border-top-style: none;
	border-right-style: solid;
	border-bottom-style: solid;
	border-left-style: none;
	border-right-color: #000;
	border-bottom-color: #000;
	margin: 0;
	padding: 0;
	line-height: 100%;
	height: 20px;
}

.main_top_content_right {
	font-weight: bold;
	text-align: center;
	border-bottom-width: 1px;
	border-top-style: none;
	border-bottom-style: solid;
	border-left-style: none;
	border-bottom-color: #000;
	margin: 0;
	padding: 0;
	line-height: 100%;
	height: 20px;
}
.gst { display:block !important; float:right !important; }
.signature { text-align:right; padding-right:10px; height:50px; vertical-align:bottom;}
.style2 {
	font-family: Arial;
}
.style3 {
	text-align: right;
	font-family: Arial;
}
.style8 {
	font-family: "Wingdings 2";
	font-size: medium;
	text-align: center;
}
.style11 {
	text-align: center;
	font-family: "Courier New", Courier, monospace;
	font-size: x-large;
	color: #FF0000;
}
.style13 {
	text-align: right;
	font-family: Arial;
	font-size: medium;
}
.style14 {
	font-size: small;
}
.style15 {
	font-family: Wingdings;
	font-size: small;
	text-align: center;
}
.style19 {
	text-align: left;
	font-weight: bold;
	font-size: 14px;
	vertical-align: bottom;
	height: 20px;
	font-family: Arial;
}
.style20 {
	text-align: left;
	padding-right: 5px;
	font-size: medium;
}
.style21 {
	font-size: medium;
}
.style22 {
	vertical-align: middle;
}
.style23 {
	font-family: Arial;
	font-size: large;
	font-weight: bold;
	vertical-align: middle;
}
.style26 {
	margin-left: 5px;
}
.style27 {
	margin-left: 5px;
	text-align: left;
}
</style>

<BODY topmargin="0" leftmargin="0" onload="printpage();">

<TABLE border="0" cellpadding="0" cellspacing="0" width="651" height="1053" align="center">
<tr>
<td>
<table width="651" border="0" cellspacing="1" cellpadding="0" class="style2">
  <tr >
	<td width="120" id="order_logo" class="style22" ></td>
    <td colspan="3" width="250" style="vertical-align:top; " class="style22" >
	<div class="style23">Sun Gold Tile PTY LTD</div>
    <div class="style22"><span class="style2"><span class="style14">PO Box 2843
		<br>
		Southport BC, QLD 4215<br />
		Mob.&nbsp;0431 224 434</span></span>&nbsp;</div></td>

<td colspan="2" id="border_name" class="style3" style="height: 31px" >
	
	<table style="width: 100%">
		<tr>
			<td class="style11" colspan="2">

				<strong>&nbsp;</strong></td>
				</tr>
		<tr>
			<td style="width: 141px; height: 23px;" class="style13"><strong>Purchase Order</strong></td>
			<td class="style11" colspan="2">

				<strong>

				<?=$list_Records[0]["orders_number"]?></strong></td>
			<!--<td class="style8" style="height: 23px"><strong>R</strong></td>-->
		</tr>
		<!--<tr>
			<td style="width: 141px" class="style13"><strong>Job Order</strong></td>
			<td class="style15">o</td>

		</tr>
		<tr>
			<td style="width: 141px; height: 23px;" class="style13"><strong>Site Instruction</strong></td>
			<td class="style15" style="height: 23px">o</td>

		</tr>-->
		
		<tr>
			<td style="width: 141px; height: 11px;" class="style13"><strong>
			Order Date </strong></td>
			<td class="style8" style="height: 11px">
			<span id="border_date" class="style13"><?=getAUDate($list_Records[0]["orders_date"])?></span></td>
		</tr>
		<tr>
			<td style="width: 141px; height: 23px;" class="style13"><strong>&nbsp;</strong></td>
			

		</tr>
	</table>
	
	</td>

  </tr>

  <tr>
  	<td class="title" colspan="2" >&nbsp;</td>
    <td width="100"></td>
    <td width="82" class="title">&nbsp;</td>
    <td width="61"></td>
    <td width="220"></td>
   </tr>
  <tr>
    <td colspan="3" class="style19"><span class="style21">Vendor: </span> 
	<span id="order_to" class="style21"><?=$supplierinfo["supplier_name"]?></span></td>
    <td height="160" colspan="3" width="343" rowspan="3" id="border_deliver_to">
    <table width="363" height="90%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="24%" class="style20" colspan="2" style="width: 100%">
		<strong>Ship To</strong></td> 
      </tr>
      <tr>
        <td class="subcontent" >Contact on Site: </td> 
		<td class="content"><?=$list_Records[0]["supervisor_info"]?></td>
     
      </tr>
     
      <tr>
        <td class="subcontent">Address: </td>
		<td class="content"><?=($list_Records[0]["project_id"])? $projectinfo["project_address"] . " " . $projectinfo["project_suburb"] . " " . $projectinfo["project_state"] . " " . $projectinfo["project_postcode"]: $companyinfo["company_address"] . " " . $companyinfo["company_suburb"] . " " .$companyinfo["company_state"] . " " . $companyinfo["company_postcode"]; ?></td>
      </tr>
	  
      <tr>
        <td class="subcontent">Delivery Date: </td>
		<td class="content"><?=$list_Records[0]["delivery_date"]?></td>
      </tr>
      <tr>
        <td class="subcontent">Project 
		Name:</td>
		<td class="content"><?=($list_Records[0]["project_id"])? $projectinfo["project_name"]: $companyinfo["company_name"]; ?></td>
      </tr>
    </table>
	</td>
  </tr>
  <tr>
    <td height="106" colspan="3" id="border_to" class="style2" >
    <table width="99%" height="100%" border="0" cellspacing="0" cellpadding="0">
     <tr>
        <td class="subcontent" style="width: 32px">&nbsp;</td>
		<td width="225" class="content"><?=$supplierinfo["supplier_address"]?><?=($supplierinfo["supplier_suburb"])? " ".$supplierinfo["supplier_suburb"]: ""?><?=($supplierinfo["supplier_state"])? " ".$supplierinfo["supplier_state"]: ""?><?=($supplierinfo["supplier_postcode"])? " ".$supplierinfo["supplier_postcode"]: ""?></td>
      </tr>
      <tr>
        <td class="subcontent" style="width: 32px">Attn: </td>
		<td class="content"><?=$supplierinfo["supplier_sales_manager"]?></td>
      </tr>
      <tr>
        <td class="subcontent" style="width: 32px">Email:</td>
		<td class="content"><?=$supplierinfo["supplier_email"]?></td>
      </tr>
      <tr>
        <td class="subcontent" style="width: 32px" >Ph.: </td>
		<td class="content"><?=$supplierinfo["supplier_phone_number"]?></td>
      </tr>
    </table>
    <td></td>

  </tr>
  <tr>
    <td height="10" colspan="6"></td>
  </tr>
  <tr>
<td colspan="6" id="border_content" class="ui-corner-all" style="behavior:url(css/PIE.htc);">
    <table width="651" height="100%" border="0" cellspacing="0" cellpadding="0" >
    <?php if($list_Records[0]["orders_type"] !='B') { ?>
		
	  <tr>
		<?
		if ($list_Records[0]["price_hidden"] == "Y") {
		?>
		<th width="240" class="main_top_content">Product Name</th>
		<? } else { ?>
        <th width="270" class="main_top_content">Product Name</th>
		<? }?>

        <th width="70" class="main_top_content">Order Qty</th>
		<?
		if ($list_Records[0]["price_hidden"] == "Y") {
		?>
		<th width="60" class="main_top_content">Price</th>
		<?php } ?>
		<th width="60" class="main_top_content">Unit</th>
		<th width="90" class="main_top_content">Unit Size</th>

		<th width="130" class="main_top_content">Code number</th>
      </tr>
     <? for ($i=0;$i< $itemcount;$i++) { ?>
						
	  <tr>
	   <?php
		if ($list_Records[0]["price_hidden"] == "Y") {
		?>
		<td class="main_content" >
		<p class="style27"><?=$list_Records[$i]["material_description"]?>&nbsp;</p>
		</td>
		<?php } else { ?>
		<td class="main_content" >
		<p class="style26"><?=$list_Records[$i]["material_description"]?>&nbsp;</p>
		</td>
		<?php } ?>

        <td class="main_content" ><?=$list_Records[$i]["orders_inventory"]?>&nbsp;</td>
        <?
		if ($list_Records[0]["price_hidden"] == "Y") {
		?>
		<td class="main_content"><?=($list_Records[$i]["material_price"])? "$".$list_Records[$i]["material_price"]:"" ?>&nbsp;</td>
		<?php } ?>
		<td class="main_content" ><?=getName("unit",getSpecificName("material","unit_id",$list_Records[$i]["material_id"]))?>&nbsp;</td>
		<td class="main_content" ><?=getSpecificName("material","material_size",$list_Records[$i]["material_id"])?>&nbsp;</td>

	     
		<td class="main_content" ><?=getSpecificName("material","material_code_number",$list_Records[$i]["material_id"])?>&nbsp;</td>
      </tr>
	  <?php } ?>
      <tr>
		<? $colspan="6";
			// showing price where price_hidden is 'Y'
			if ($list_Records[0]["price_hidden"] == "Y") $colspan="7"; ?>
        <td class="remarks" colspan="<?=$colspan?>" >Memo&nbsp;:&nbsp;<?=$list_Records[0]['remarks']?>&nbsp;</td>
      </tr>	  <?php }?>
    </table></td>
  </tr>
  <tr>
    <td height="10" colspan="6"></td>
  </tr>
 <!-- <tr>
    <td height="250" colspan="6" id="border_comments" class="ui-corner-all" style="behavior:url(css/PIE.htc);">
        <table width="100%" height="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="25" >&nbsp;</td>
            <td width="625" class="content">Comments:</td>
          </tr>-->
          <? 
		  	$count = 0;
		  
		  if (!$list_Records[0]['order_notice_id']) { ?>
		  <tr>
            <td class="subcontent_title"></td>
            <td class="subcontent_inst"></td>
          </tr>
          <tr>
            <td class="subcontent_title"></td>
            <td class="subcontent_inst"></td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
		  <? 
		  $count=6;
		  
		  } else {
			$sql2 = "select * from order_notice where order_notice_id IN (".$list_Records[0]['order_notice_id'].") order by sortno ASC, order_notice_name ";
			$result2 = mysql_query($sql2) or exit(mysql_error());
			$count = 1;	
			while($rows2 = mysql_fetch_assoc($result2)) {
				$order_notice_id = $rows2["order_notice_id"];
				$order_notice_name = $rows2["order_notice_name"];
			?>
		  <tr >
            <td class="subcontent_title"><?=$count?></td>
            <td style="width:600px;vertical-align:top;" class="subcontent_inst"><?=$order_notice_name?></td>
          </tr>
          <? $count++;
			}
			 mysql_free_result($result2);
		  } 
		 if ($count < 10 ) {
			  for ( $i=0; $i < 10-$count; $i++) {
			  ?>
			   <tr >
				<td class="subcontent">&nbsp;</td>
				<td >&nbsp;</td>
			  </tr>
		  <? }
		  }?>
        </table>
       </td>
  </tr>
  <tr>
    <td colspan="6" class="signature" >Ordered&nbsp;by <?=$userinfo["username"]?>
  </tr>
</table>
</td>
</tr>
</TABLE>
</BODY>
</HTML>
<? ob_flush(); ?>