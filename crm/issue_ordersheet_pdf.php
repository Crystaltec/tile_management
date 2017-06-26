<?
ob_start();
require_once 'dompdf/dompdf_config.inc.php';
include_once 'include/common.inc';
include_once 'include/dbconn.inc';
include_once 'include/myfunc.inc';

$orders_number = $_REQUEST['orders_number'];

## 쿼리, 담을 배열 선언

$list_Records = array();


$Query  = "SELECT * FROM orders WHERE orders_number='" .$orders_number ."'";

$id_cnn = mysql_query($Query) or exit(mysql_error());
while($id_rst = mysql_fetch_assoc($id_cnn)) {
	$list_Records = array_merge($list_Records, array($id_rst));

}
mysql_free_result($id_cnn);

$sql = "SELECT username, phone, fax, address, suburb, state, postcode FROM account WHERE userid='".$list_Records[0]['user_id'] . "'";
$id_cnn = mysql_query($sql) or exit(mysql_error());
$userinfo = mysql_fetch_assoc($id_cnn);
mysql_free_result($id_cnn);

$sql = "select * from supplier where supplier_id=".$list_Records[0]['supplier_id'];
$id_cnn = mysql_query($sql) or exit(mysql_error());
$supplierinfo = mysql_fetch_assoc($id_cnn);
mysql_free_result($id_cnn);

if ($list_Records[0]['project_id']) {
	$sql = "select * from project where project_id=".$list_Records[0]['project_id'];
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



$html = "<style type='text/css'> 
#border_name
{
	font-family: verdana;
	font-size: 14px;
	text-align: center;
	padding-bottom: 20px;
	
}

#border_date
{
	background-image: url(images/border_order_date.jpg);
	background-repeat: no-repeat;
	text-align: center;
	font-size: 16px;
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

#border_pon
{
	text-align: center;
	font-size: 16px;
	font-weight: bold;
}

#border_content
{

}

#border_comments
{

}
body
{
	font-family: calibri;
	font-size: 11px;
}


.company_name
{
	font-family: Tahoma;
	font-size: 14px;
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

.main_content {
	font-weight: bold;
	text-align: center;
	border-right-width: 1px;
	border-top-style: none;
	border-right-style: solid;
	border-left-style: none;
	border-right-color: #000;

}

.main_content_right {
	font-weight: bold;
	text-align: center;

	border-top-style: none;
	border-left-style: none;
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
}

.main_top_content_right {
	font-weight: bold;
	text-align: center;
	border-bottom-width: 1px;
	border-top-style: none;
	border-bottom-style: solid;
	border-left-style: none;
	border-bottom-color: #000;
}
</style>
<body>
<TABLE border='0' cellpadding='0' cellspacing='0' width='631' height='1053' align='center'>
<tr>
<td>
<table width='631' height='1053' border='0' cellspacing='0' cellpadding='0'>
  <tr>
	<td colspan='6' id='border_name'>PURCHASE ORDER</td>
  </tr>
  <tr>
    <td width='100' class='subcontent'>P.O. No:</td>
    <td colspan='5' id='border_pon'>".$list_Records[0]['orders_number']."</td>
  </tr>
  <tr>
	<td class='content'>Date:</td>
	<td colspan='2' id='border_date' >".getAUDate($list_Records[0]['orders_date'])."</td>
	<td colspan='3' class='content'>Delivery to:</td>
  </tr>
  <tr>
    <td height='16' colspan='3' class='content'>To:</td> 
	<td class='subcontent'>Project Name:</td>
     <td colspan='2' class='content'>".($list_Records[0]['project_id'])? $projectinfo['project_name']: $companyinfo['company_name']."</td>
   </tr>
  <tr>
     <td class='subcontent'>Company:</td>
     <td colspan='2' class='content'>".$supplierinfo['supplier_name']."</td>
	<td class='subcontent'>Address:</td>
     <td colspan='2' class='content'>".($list_Records[0]['project_id'])? $projectinfo['project_address']: $companyinfo['company_address'] ."</td>
  </tr>
  <tr>
     <td class='subcontent'>&nbsp;</td>
     <td colspan='2' class='content'>&nbsp;</td>
	 <td class='subcontent'>&nbsp;</td>
     <td colspan='2' class='content'>".($list_Records[0]['project_id'])? $projectinfo['project_suburb']: $companyinfo['company_suburb'] ."</td>
  </tr>
   <tr>
     <td class='subcontent'>&nbsp;</td>
     <td colspan='2' class='content'>&nbsp;</td>
		<td class='subcontent'>&nbsp;</td>
        <td colspan='2' class='content'>".($list_Records[0]['project_id'])? $projectinfo['project_state']: $companyinfo['company_state'] ."</td>
   </tr>
    <tr>
        <td class='subcontent'>Fax:</td>
        <td colspan='2' class='content'>".$supplierinfo['supplier_fax_number']."</td>
		<td class='subcontent'>&nbsp;</td>
        <td colspan='2' class='content'>".($list_Records[0]['project_id'])? $projectinfo['project_postcode']: $companyinfo['company_postcode'] ."</td>
    </tr>
      <tr>
        <td class='subcontent'>Ph:</td>
        <td colspan='2' class='content'>".$supplierinfo['supplier_phone_number']."</td>
		<td class='subcontent'>Delivery Date:</td>
        <td colspan='2' class='content'>".$list_Record[0]['delivery_date']."</td>
      </tr>
      <tr>
        <td class='subcontent' >Attn:</td>
        <td colspan='2' class='content'>".$supplierinfo['supplier_sales_manager']."</td>
		<td class='subcontent'>Site Contact:</td>
        <td colspan='2' class='content'>".($list_Records[0]['project_id'])? $projectinfo['project_phone_number']: $companyinfo['company_phone_number'] ."</td>
      </tr>
      <tr>
        <td></td>
		<td colspan='2'></td>
		<td class='subcontent'>Supervisor &amp; Ph:</td>
        <td colspan='2' class='content'>".$list_Records[0]['supervisor_info']."</td>
      </tr>
      <tr>
		<td></td>
		<td colspan='2'></td>
        <td class='subcontent'>Order By:</td>
        <td colspan='2' class='content'>".$userinfo['username']."</td>
      </tr>
  
 
      <tr>
        <td class='main_top_content'>Qty</td>
        <td class='main_top_content'>Unit</td>
        <td class='main_top_content'>Code</td>
        <td colspan='2' class='main_top_content_right'>Description</td>
      </tr>";
      for ($i=0;$i<6;$i++) { 
		$html .="
	  <tr>
        <td class='main_content'>".$list_Records[$i]['orders_inventory']."&nbsp;</td>
        <td class='main_content'>".getName('unit',getSpecificName('material','unit_id',$list_Records[$i]['material_id']))."&nbsp;</td>
        <td class='main_content'>".getSpecificName('material','material_code_number',$list_Records[$i]['material_id'])."&nbsp;</td>
        <td colspan='2' class='main_content_right'>".$list_Records[$i]['material_description']."&nbsp;</td>
      </tr>";
	  }
    
      $html .="
      <tr>
        <td class='main_content'>&nbsp;</td>
        <td class='main_content'>&nbsp;</td>
        <td class='main_content'>&nbsp;</td>
        <td colspan='2' >&nbsp;</td>
      </tr>
   
  <tr>
    <td height='17' colspan='6'>&nbsp;</td>
  </tr>
  <tr>
       <td>&nbsp;</td>
      <td colspan='5' class='content'>Delivery Requirements/Instructions:</td>
  </tr>
    <tr>
       <td class='subcontent'>1</td>
        <td colspan='5'>Delivery Dockets must be check & signed for by Sun Gold Tiles Site Foreman.</td>
    </tr>
   <tr>
        <td class='subcontent'>2</td>
        <td colspan='5'>Invoices not accompanied by signed Delivery Dockets will result in delayed payment until confirmed.</td>
   </tr>
   <tr>
  <td>&nbsp;</td>
  <td colspan='5'>&nbsp;</td>
 </tr>
   <tr>
      <td>&nbsp;</td>
        <td colspan='5'>&nbsp;</td>
      </tr>
     <tr>
       <td>&nbsp;</td>
      <td colspan='5'>&nbsp;</td>
     </tr>
    <tr>
      <td>&nbsp;</td>
        <td colspan='5'>&nbsp;</td>
    </tr>
     <tr>
     <td>&nbsp;</td>
      <td colspan='5'>&nbsp;</td>
      </tr>
   
  <tr>
    <td height='30' colspan='6' class='subcontent'>Approval ...................................&nbsp;&nbsp;&nbsp;&nbsp;</td>
  </tr>
</table>

</td>
</tr>


</TABLE>";
 ob_flush();

$dompdf = new DOMPDF();
$dompdf->load_html($html);
$dompdf->render();
$dompdf->stream('sample.pdf');


?>

