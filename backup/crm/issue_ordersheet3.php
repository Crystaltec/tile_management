<?php
ob_start();
include_once "include/logincheck.inc";
include_once "include/common.inc";
include_once "include/dbconn.inc";
include_once "include/myfunc.inc";

include_once "htmlinclude/head.php";

$itemcount = 7;

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
#order_logo
{
	background-image: url(images/logo4.jpg);
	background-repeat: no-repeat;
}

#border_name
{
	
	font-family: verdana;
	font-size: 15px;
	text-align: center;
	vertical-align: top;	
}
#border_name span {
	display:block;
	width:100%;
	padding:15px 0;
	margin:0;
	border:1px solid #000;
}

#border_date
{
	border:1px solid #000;
	text-align: center;
	font-size: 17px;
	font-weight: bold;
}

#border_deliver_to
{
	vertical-align: top;
	border:1px solid #000;
}

#border_to
{
	border:1px solid #000;
	vertical-align: top;
}

#border_pon
{
	border:1px solid #000;
	text-align: center;
	font-size: 17px;
	font-weight: bold;
}

#border_content
{
	border:1px solid #000;
	background-position: top;
}

#border_comments
{
	border:1px solid #000;
}
body
{
	font-family: calibri;
	font-size: 12px;
}

.comments
{
	font-size: 10px;
	text-align: right;
	padding-right: 5px;
}

.company_name
{
	font-family: Tahoma;
	font-size: 15px;
	font-weight: bold;

}
.address
{
	vertical-align: top;
}

.website
{
	text-align: center;
	vertical-align: top;
}

.content
{
	text-align: left;
	font-weight: bold;
}

.subcontent
{
	text-align: right;
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
	height:35px;
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
</style>

<BODY topmargin="0" leftmargin="0" onload="printpage();">

<TABLE border="0" cellpadding="0" cellspacing="0" width="631" height="1053" align="center">
<!--<TR>
<TD>
<TABLE border="0" cellpadding="0" cellspacing="0" width="631" align="center">
	<TR>
	<TD align="right" height="40">
	<a href="javascript:issueOrdersheet_pdf('<?=$list_Records[0]["orders_number"]?>')"><img src="images/pdf_button.png"></a>
	</TD>
	</TR>
</TABLE>
</TD>
</TR>
-->
<tr>
<td>
<table width="631" border="0" cellspacing="1" cellpadding="0">
  <tr >
	<td width="120" height="64" rowspan="3" id="order_logo" ></td>
    <td colspan="3" ><div class="company_name">&nbsp;ECHO TILES PTY LTD</div>
    <div class="address">&nbsp;10/74 Millaroo Dr, Helensvale QLD 4212<br />
&nbsp;Tel : (07) 5519 9566<br />
&nbsp;Fax: (07) 5519 9588</div></td>
    <td colspan="2" rowspan="2" id="border_name" ><span class="ui-corner-all" style="behavior:url(css/PIE.htc);""  >PURCHASE ORDER</span></td>
  </tr>
  <tr>
    <td colspan="3" rowspan="3"> </td>
    </tr>
  <tr>
    <td width="61" height="33"  rowspan="2" class="subcontent">P.O. No:</td>
    <td width="202" height="33" rowspan="2" id="border_pon" class="ui-corner-all" style=" 
behavior:url(css/PIE.htc);" ><?=$list_Records[0]["orders_number"]?></td>
  </tr>
  <tr>
    <td height="33"  class="website">www.echotiles.com</td>
  </tr>
  <tr>
    <td height="10" ></td>
    <td width="65"></td>
    <td width="100"></td>
    <td width="80"></td>
    <td colspan="2" class="comments" > * P.O.No. must appear on all invoices and packages</td>
  </tr>
 
  <tr>
  <td class="content">Date:</td>
    <td height="15"></td>
    <td></td>
    <td class="content">Deliver to:</td>
    <td></td>
    <td></td>
    </tr>
  <tr>
    <td colspan="2" height="62" id="border_date" class="ui-corner-all" style="behavior:url(css/PIE.htc);"><?=getAUDate($list_Records[0]["orders_date"])?></td>
    <td >&nbsp;</td>
    <td height="216" colspan="3" rowspan="3" id="border_deliver_to" class="ui-corner-all" style="behavior:url(css/PIE.htc);">
    <table width="100%" height="90%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="30%" class="subcontent">Project Name:</td>
        <td width="70%" class="content"><?=($list_Records[0]["project_id"])? $projectinfo["project_name"]: $companyinfo["company_name"]; ?></td>
      </tr>
      <tr>
        <td class="subcontent">Address:</td>
        <td class="content"><?=($list_Records[0]["project_id"])? $projectinfo["project_address"]: $companyinfo["company_address"]; ?></td>
      </tr>
      <tr>
        <td class="subcontent">&nbsp;</td>
        <td class="content"><?=($list_Records[0]["project_id"])? $projectinfo["project_suburb"]: $companyinfo["company_suburb"]; ?></td>
      </tr>
      <tr>
        <td class="subcontent">&nbsp;</td>
        <td class="content"><?=($list_Records[0]["project_id"])? $projectinfo["project_state"]: $companyinfo["company_state"]; ?></td>
      </tr>
      <tr>
        <td class="subcontent">&nbsp;</td>
        <td class="content"><?=($list_Records[0]["project_id"])? $projectinfo["project_postcode"]: $companyinfo["company_postcode"]; ?></td>
      </tr>
      <tr>
        <td class="subcontent">Delivery Date:</td>
        <td class="content"><?=$list_Records[0]["delivery_date"]?></td>
      </tr>
	  <tr>
        <td class="subcontent">Builder:</td>
        <td class="content"><?=getName("builder",$projectinfo["builder_id"]); ?></td>
      </tr>
      <tr>
        <td class="subcontent">Site Contact:</td>
        <td class="content"><?=($list_Records[0]["project_id"])? $projectinfo["project_phone_number"]: $companyinfo["company_phone_number"]; ?></td>
      </tr>
      <tr>
        <td class="subcontent">Supervisor &amp; Ph:</td>
        <td class="content"><?=$list_Records[0]["supervisor_info"]?></td>
      </tr>
      <tr>
        <td class="subcontent">Order By:</td>
        <td class="content"><?=$userinfo["username"]?></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td height="16" colspan="3" class="content">To:</td>
  </tr>
  <tr>
    <td height="143" colspan="3" id="border_to" class="ui-corner-all" style="behavior:url(css/PIE.htc);">
    <table width="98%" height="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="25%" class="subcontent">Company:</td>
        <td width="75%"  class="content"><?=$supplierinfo["supplier_name"]?></td>
      </tr>
     <tr>
        <td class="subcontent">&nbsp;</td>
        <td class="content"><?=$supplierinfo["supplier_address"]?><?=($supplierinfo["supplier_suburb"])? " ".$supplierinfo["supplier_suburb"]: ""?><?=($supplierinfo["supplier_state"])? " ".$supplierinfo["supplier_state"]: ""?><?=($supplierinfo["supplier_postcode"])? " ".$supplierinfo["supplier_postcode"]: ""?>&nbsp;</td>
      </tr>
      <tr>
        <td class="subcontent">Fax:</td>
        <td class="content"><?=$supplierinfo["supplier_fax_number"]?></td>
      </tr>
      <tr>
        <td class="subcontent">Ph:</td>
        <td class="content"><?=$supplierinfo["supplier_phone_number"]?></td>
      </tr>
      <tr>
        <td class="subcontent" >Attn:</td>
        <td class="content"><?=$supplierinfo["supplier_sales_manager"]?></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td height="10" colspan="6"></td>
  </tr>
  <tr>
    <td height="325" colspan="6" id="border_content" class="ui-corner-all" style="behavior:url(css/PIE.htc);">
    <table width="100%" height="100%" border="0" cellspacing="0" cellpadding="0" >
      <tr>
        <td width="40" class="main_top_content">Qty</td>
		<?
		if ($list_Records[0]["price_hidden"] == "Y") {
		?>
		<td width="50" class="main_top_content">Price</td>
		<?php } ?>
		<th width="110" class="main_top_content">Code<br />number</th>
		<th width="110" class="main_top_content">Factory<br />number</th>
		<th width="75" class="main_top_content">Colour/<br />Shade</th>
		<th width="60" class="main_top_content">Size</th>
        <td width="60" class="main_top_content">Unit</td>
		<?
		if ($list_Records[0]["price_hidden"] == "Y") {
		?>
		<td width="125" class="main_top_content_right">Description</td>
		<? } else { ?>
        <td width="155" class="main_top_content_right">Description</td>
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
		 <td class="main_content" ><?=getSpecificName("material","material_code_number",$list_Records[$i]["material_id"])?>&nbsp;</td>
		
		<td class="main_content" ><?=getSpecificName("material","material_factory_number",$list_Records[$i]["material_id"])?>&nbsp;</td>
		
		<td class="main_content" ><?=getSpecificName("material","material_color",$list_Records[$i]["material_id"])?>&nbsp;</td>
		
		<td class="main_content" ><?=getSpecificName("material","material_size",$list_Records[$i]["material_id"])?>&nbsp;</td>
		 
		<td class="main_content" ><?=getName("unit",getSpecificName("material","unit_id",$list_Records[$i]["material_id"]))?>&nbsp;</td>
       
	   <?
		if ($list_Records[0]["price_hidden"] == "Y") {
		?>
		<td width="100" class="main_content_right" ><?=$list_Records[$i]["material_description"]?>&nbsp;</td>
		<? } else { ?>
		<td width="130" class="main_content_right" ><?=$list_Records[$i]["material_description"]?>&nbsp;</td>
		<? } ?>
      </tr>
	  <? } ?>
      <tr>
		<? $colspan="7";
			if ($list_Records[0]["price_hidden"] == "Y") $colspan="8"; ?>
        <td class="remarks" colspan="<?=$colspan?>" >Memo&nbsp;:&nbsp;<?=$list_Records[0]['remarks']?>&nbsp;</td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td height="10" colspan="6"></td>
  </tr>
  <tr>
    <td height="260" colspan="6" id="border_comments" class="ui-corner-all" style="behavior:url(css/PIE.htc);">
        <table width="100%" height="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="25" >&nbsp;</td>
            <td width="605" class="content">Delivery Requirements/Instructions:</td>
          </tr>
          <? 
		  	$count = 0;
		  
		  if (!$list_Records[0]['order_notice_id']) { ?>
		  <tr>
            <td class="subcontent_title">1</td>
            <td class="subcontent_inst">Delivery Dockets must be check & signed for by Echo Tiles Site Foreman.</td>
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
    <td height="25" colspan="6" class="subcontent">Approval ...................................&nbsp;&nbsp;&nbsp;&nbsp;</td>
  </tr>
</table>
</td>
</tr>
</TABLE>
</BODY>
</HTML>
<? ob_flush(); ?>