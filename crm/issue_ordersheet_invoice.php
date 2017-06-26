<?php
ob_start();
include_once "include/logincheck.inc";
include_once "include/common.inc";
include_once "include/dbconn.inc";
include_once "include/myfunc.inc";
include_once "htmlinclude/head.php";


$inv_no = secure_string($_REQUEST["inv_no"]);

## ĵ��, �〻 �迭 ����

$list_Records = array();

$Query  = "SELECT * FROM invoice_manage WHERE inv_no='" .$inv_no ."' ORDER BY inv_no ";

$id_cnn = mysql_query($Query) or exit(mysql_error());
while($id_rst = mysql_fetch_assoc($id_cnn)) {
	$list_Records = array_merge($list_Records, array($id_rst));
}
mysql_free_result($id_cnn);


$sql = "SELECT username FROM account WHERE userid='".$list_Records[0]["user_id"] . "'";
$id_cnn = mysql_query($sql) or exit(mysql_error());
$userinfo = mysql_fetch_assoc($id_cnn);
mysql_free_result($id_cnn);


//invoice management
$sql = "select * from invoice_manage where inv_no='".$list_Records[0]["inv_no"]."'";
$id_cnn = mysql_query($sql) or exit(mysql_error());
$invoinfo = mysql_fetch_assoc($id_cnn);
mysql_free_result($id_cnn);


?>
<script>
function issueOrdersheet_pdf(inv_no) {
	window.open("issue_ordersheet_invoice.php?orders_number="+inv_no, "ordersheet_pdf", "");
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
	height:400px;
}

#border_comments
{
	border:1px solid #000;
}

.comments
{
	font-size: 9px !important;
	text-align: left !important;
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
	text-align: right;
}
</style>

<BODY topmargin="0" leftmargin="0" onload="printpage();">

<TABLE border="0" cellpadding="1" cellspacing="0" width="451" height="753" align="center">
<tr>
<td>
<table width="651" border="0" cellspacing="1" cellpadding="0" class="style2">
  <tr >
	<td width="120"  height="95" id="order_logo" class="style22" ></td>
    <td colspan="3" width="250" style="vertical-align:top; " class="style22" >
	<div class="style23">Sun Gold Tiles PTY LTD</div>
    <div class="style22"><span class="style2"><span class="style14">PO Box 2843
		<br>
		Southport BC, QLD 4215<br />
		Mob.&nbsp;0431 224 434</span></span>&nbsp;</div></td>	
	</td>
  </tr>

  
  <tr> <td colspan="3" class="style19"><span class="style21">Invoice Details: </span> </td>
    
     <tr>
        <td class="subcontent" style="width: 100px">Invoice No: </td>
		<td class="content"><?=$invoinfo["invoice_number"]?></td>
      </tr>
      <tr>
        <td class="subcontent" style="width: 100px">Invoice Date:</td>
		<td class="content"><?php echo getAUDate($invoinfo["inv_date"])?></td>
      </tr>
      <tr>
        <td class="subcontent" >Builder Name: </td> 
		<td class="content"><?php echo getName("builder",$invoinfo["builder_id"]); ?></td>
      </tr>
     
      <tr>
        <td class="subcontent">Project Name: </td>
		<td class="content"><?php echo getName("project",$invoinfo["project_id"]); ?></td>
      </tr>
	  
      <tr>
        <td class="subcontent">Payment Due: </td>
		<td class="content"><?php echo getAUDate($invoinfo["payment_due"])?></td>
      </tr>
      <tr>
        <td class="subcontent">Received Date: </td>
		<td class="content"><?php echo getAUDate($invoinfo["received_date"])?></td>
      </tr>
   
	</td>

     </table>
 <tr>
    <td height="20" colspan="6"></td>
  </tr>

<td colspan="6" id="border_content" class="ui-corner-all" style="behavior:url(css/PIE.htc);">
    <table width="650" height="100%" border="0" cellspacing="0" cellpadding="0" >
 
		
	  <tr>
		
		<th width="125" class="main_top_content">Invoice Amount<br>(Inc. Amount)</th>
	
        <th width="125" class="main_top_content">Received Amount<br> (Inc. Amount)</th>
		

        <th width="125" class="main_top_content">Retention Amount<br>(Inc. Amount) </th>
         <th width="125" class="main_top_content">Difference Amount </th>
		
		
      </tr>
   
						
	   <tr>
	  
		<td class="main_content" >
		<p class="style27">$<?=number_format($invoinfo["inv_amount"],2,'.',',')?>&nbsp;</p></td>
		
		<td class="main_content" >
		<p class="style27">$<?=number_format($invoinfo["received_amount"],2,'.',',')?>&nbsp;</p></td>
		
		<td class="main_content" >
		<p class="style27">$<?=number_format($invoinfo["retention_amount"],2,'.',',')?>&nbsp;</p></td>

		<td class="main_content" >
		<p class="style27">$<?=number_format($invoinfo["diff_amount"],2,'.',',')?>&nbsp;</p></td>
 
      </tr>
	 
      <tr>
		
			
			
        <td class="remarks" colspan="6" >Memo&nbsp;:&nbsp;<?=$invoinfo['note']?>&nbsp;</td>
      </tr>	  
    </table></td>
  </tr>
  <tr>
    <td height="10" colspan="6"></td>
  </tr>
 
    <td height="200" colspan="6" id="border_comments" class="ui-corner-all" style="behavior:url(css/PIE.htc);">
        <table width="100%" height="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td class="remarks">Comments:</td>
          </tr>
        </table>
       </td>
  
  
</table>
</td>
</tr>
</TABLE>
</BODY>
</HTML>
<? ob_flush(); ?>