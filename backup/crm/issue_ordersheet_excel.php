<?php
ob_start();
include_once "include/common.inc";
include_once "include/dbconn.inc";
include_once "include/myfunc.inc";

$itemcount = 10;

$orders_number = secure_string($_REQUEST["orders_number"]);

## ĵ��, �〻 �迭 ����

$list_Records = array();


$Query  = "SELECT * FROM orders WHERE orders_number='" .$orders_number ."'";

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

header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");  
header("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");  
header("Cache-Control: no-cache, must-revalidate, post-check=0,pre-check=0");  
header("Pragma: no-cache");  
header("Content-type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=PURCHASE_ORDER-".$orders_number.".xls");
header("Content-Description: PHP5 Generated Data");
?>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<HEAD>
<style type="text/css"> 

body
{
	font-family: calibri;
	font-size: 12px;
}

#order_logo
{
	background-image: url(images/logo4.jpg);
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
	width:630px;
	height:50px;
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
</style>
</HEAD>
<BODY topmargin="0" leftmargin="0" >

<TABLE border="0" cellpadding="0" cellspacing="0" width="631" height="1053" align="center">
<tr>
<td>
<table width="631" border="0" cellspacing="1" cellpadding="0">
  <tr >
	<td width="120" height="85" id="order_logo" >www.sungoldtile.com</td>
    <td colspan="3" width="250" style="vertical-align:top;" >
	<div class="company_name">&nbsp;SunGold Tiles PTY LTD</div>
    <div class="address">&nbsp;PO Box 2843 Southport BC, QLD 4215 Australia<br />
&nbsp;Tel:&nbsp; 0432475965<br />
&nbsp;</div></td>
    <td colspan="2" id="border_name" >
	PURCHASE ORDER<br />
	<span class="subcontent">P.O. No:</span> <span id='border_pon'><?=$list_Records[0]["orders_number"]?></span>
	<span class="comments">* P.O.No. must appear on all invoices and packages</span>
	</td>
  </tr>
  <tr>
  	<td class="title" colspan="2" >Date: <span id="border_date"><?=getAUDate($list_Records[0]["orders_date"])?></span></td>
    <td width="100"></td>
    <td width="82" class="title">Deliver to: </td>
    <td width="61"></td>
    <td width="200"></td>
   </tr>
  <tr>
    <td colspan="3" class="title">To: <span id="order_to"><?=$supplierinfo["supplier_name"]?></span></td>
    <td height="160" colspan="3" width="343" rowspan="3" id="border_deliver_to">
    <table width="343" height="90%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="24%" class="subcontent">Project: </td> 
		<td width="76%" class="content"><?=($list_Records[0]["project_id"])? $projectinfo["project_name"]: $companyinfo["company_name"]; ?></td>
      </tr>
      <tr>
        <td class="subcontent" >Address: </td> 
		<td class="content"><?=($list_Records[0]["project_id"])? $projectinfo["project_address"] . " " . $projectinfo["project_suburb"] . " " . $projectinfo["project_state"] . " " . $projectinfo["project_postcode"]: $companyinfo["company_address"] . " " . $companyinfo["company_suburb"] . " " .$companyinfo["company_state"] . " " . $companyinfo["company_postcode"]; ?></td>
     
      </tr>
     
      <tr>
        <td class="subcontent">Delivery Date: </td>
		<td class="content"><?=$list_Records[0]["delivery_date"]?></td>
      </tr>
	  
      <tr>
        <td class="subcontent">Site Contact1: </td>
		<td class="content"><?=($list_Records[0]["project_id"])? $projectinfo["project_phone_number"]: $companyinfo["company_phone_number"]; ?></td>
      </tr>
      <tr>
        <td class="subcontent">Site Contact2: </td>
		<td class="content"><?=$list_Records[0]["supervisor_info"]?></td>
      </tr>
      <tr>
        <td class="subcontent">Order By: </td>
		<td class="content"><?=$userinfo["username"]?></td>
      </tr>
    </table>
	</td>
  </tr>
  <tr>
    <td height="106" colspan="3" id="border_to" >
    <table width="99%" height="100%" border="0" cellspacing="0" cellpadding="0">
     <tr>
        <td width="32" class="subcontent"></td>
		<td width="225" class="content"><?=$supplierinfo["supplier_address"]?><?=($supplierinfo["supplier_suburb"])? " ".$supplierinfo["supplier_suburb"]: ""?><?=($supplierinfo["supplier_state"])? " ".$supplierinfo["supplier_state"]: ""?><?=($supplierinfo["supplier_postcode"])? " ".$supplierinfo["supplier_postcode"]: ""?></td>
      </tr>
      <tr>
        <td class="subcontent">Fax:</td>
		<td class="content"><?=$supplierinfo["supplier_fax_number"]?></td>
      </tr>
      <tr>
        <td class="subcontent">Ph: </td>
		<td class="content"><?=$supplierinfo["supplier_phone_number"]?></td>
      </tr>
      <tr>
        <td class="subcontent" >Attn: </td>
		<td class="content"><?=$supplierinfo["supplier_sales_manager"]?></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td height="10" colspan="6"></td>
  </tr>
  <tr>
    <td colspan="6" id="border_content" class="ui-corner-all" >
    <table width="631" height="100%" border="0" cellspacing="0" cellpadding="0" >
    <?php if($list_Records[0]["orders_type"] !='B') { ?>
		
	  <tr>
        <th width="40" class="main_top_content">Qty</th>
		<?
		if ($list_Records[0]["price_hidden"] == "Y") {
		?>
		<th width="60" class="main_top_content">Price</th>
		<?php } ?>
		<th width="60" class="main_top_content">Unit</th>
		<th width="80" class="main_top_content">Size</th>
		<th width="110" class="main_top_content">Code number</th>
		<th width="85" class="main_top_content">Colour/Shade</th>
		<?
		if ($list_Records[0]["price_hidden"] == "Y") {
		?>
		<th width="195" class="main_top_content_right">Description</th>
		<? } else { ?>
        <th width="225" class="main_top_content_right">Description</th>
		<? }?>
      </tr>
     <? for ($i=0;$i< $itemcount;$i++) { ?>
						
	  <tr>
        <td class="main_content" ><?=$list_Records[$i]["orders_inventory"]?>&nbsp;</td>
        <?
		if ($list_Records[0]["price_hidden"] == "Y") {
		?>
		<td class="main_content"><?=($list_Records[$i]["material_price"])? "$".$list_Records[$i]["material_price"]:"" ?>&nbsp;</td>
		<?php } ?>
		<td class="main_content" ><?=getName("unit",getSpecificName("material","unit_id",$list_Records[$i]["material_id"]))?>&nbsp;</td>
		<td class="main_content" ><?=getSpecificName("material","material_size",$list_Records[$i]["material_id"])?>&nbsp;</td>
		<td class="main_content" ><?=getSpecificName("material","material_code_number",$list_Records[$i]["material_id"])?>&nbsp;</td>
		<td class="main_content" ><?=getSpecificName("material","material_color",$list_Records[$i]["material_id"])?>&nbsp;</td>
	     
	   <?php
		if ($list_Records[0]["price_hidden"] == "Y") {
		?>
		<td class="main_content_right" ><?=$list_Records[$i]["material_description"]?>&nbsp;</td>
		<?php } else { ?>
		<td class="main_content_right" ><?=$list_Records[$i]["material_description"]?>&nbsp;</td>
		<?php } ?>
      </tr>
	  <?php } ?>
      <tr>
		<? $colspan="6";
			// showing price where price_hidden is 'Y'
			if ($list_Records[0]["price_hidden"] == "Y") $colspan="7"; ?>
        <td class="remarks" colspan="<?=$colspan?>" >Memo&nbsp;:&nbsp;<?=$list_Records[0]['remarks']?>&nbsp;</td>
      </tr>
	  <?php } else { ?>
	  <tr>
        <td width="80%" class="main_top_content" style="height:30px !important;">Description</td>
		<td width="20%" class="main_top_content_right" style="height:30px !important;">Price</td>
	  </tr>
	  <tr>
	  	<td width="80%" class="main_content_big" >
		<?php echo nl2br($list_Records[0]["material_description"]); ?> 
		
		</td>
		<td width="20%" class="main_content_big_right" >
		<?=($list_Records[0]["material_price"])? "$".$list_Records[0]["material_price"]:"" ?><br />
		<span class="gst">
		<?php if($list_Records[0]["orders_tax"] <> 'N') { echo "GST INCL"; }
		else { echo "GST EXCL"; } ?>
		</span></td>
		
	  </tr>	 
	  <?php }?>
    </table></td>
  </tr>
  <tr>
    <td height="10" colspan="6"></td>
  </tr>
  <tr>
    <td height="250" colspan="6" id="border_comments" class="ui-corner-all" >
        <table width="100%" height="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="25" >&nbsp;</td>
            <td width="605" class="content">Delivery Requirements/Instructions:</td>
          </tr>
          <?php
		  	$count = 0;
		  
		  if (!$list_Records[0]['order_notice_id']) { ?>
		  <tr>
            <td class="subcontent_title">1</td>
            <td class="subcontent_inst">Delivery Dockets must be check & signed for by Sungold Tile Site Foreman.</td>
          </tr>
          <tr>
            <td class="subcontent_title">2</td>
            <td class="subcontent_inst">Invoices not accompanied by signed Delivery Dockets will result in delayed payment until confirmed.</td>
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
    <td colspan="6" class="signature" >Approval ...................................</td>
  </tr>
</table>
</td>
</tr>
</TABLE>
</BODY>
</HTML>
<? ob_flush(); ?>
