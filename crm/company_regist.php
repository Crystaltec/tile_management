<?php
include_once "include/logincheck.inc";
include_once "include/common.inc";
include_once "include/dbconn.inc";
include_once "include/myfunc.inc";
include_once "htmlinclude/head.php";

$action_type = $_REQUEST["action_type"];
$company_id = $_REQUEST["company_id"];

if($action_type=="modify") {
	## 쿼리, 담을 배열 선언
	$list_Records = array();
	$Query  = "SELECT * ";
	$Query .= " FROM company WHERE company_id='$company_id'";

	$id_cnn = mysql_query($Query) or exit(mysql_error());
	while($id_rst = mysql_fetch_assoc($id_cnn)) {
		$list_Records = array_merge($list_Records, array($id_rst));
	
	}
} else if($action_type=="delete") {
	$reqcompany_id = $_REQUEST["company_id"];
	
	$sql = "DELETE FROM company WHERE company_id='" .$reqcompany_id."'";
	pQuery($sql, "delete");
	echo "<script>alert('Deleted!');location.href='company_list.php';</script>";
	exit;
}

?>
<script type="text/javascript" src="ckeditor/ckeditor.js"></script>
<script language="Javascript">
function formchk() {
	frm = document.frm1;

	if(frm.company_name.value =="") {
		alert("Please, Insert Name!");
		frm.company_name.focus();
		return;
	}

	if(frm.company_phone_number.value =="") {
		alert("Please, Insert phone number!");
		frm.company_phone_number.focus();
		return;
	} 
	
	frm.submit();
	
}

$(function() {
	$("input:button, button").button();
	$(".list_table thead").addClass('ui-widget-header');
	$(".list_table tbody").addClass('ui-widget-content');
	$('.ui-widget-content').css({'background-image' :'none','background-color':'none'});
});
</script>
<BODY leftmargin=0 topmargin=0>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
<!-- BODY TOP ------------------------------------------------------------------------------------------->
<tr>
<td style="padding-left:0px"><? include_once "top.php"; ?></td>
</tr>
<!-- BODY TOP END --------------------------------------------------------------------------------------->

<table border="0" cellpadding="0" cellspacing="0" width="100%">

	<td valign="top" width="191" height="100%">
	<!-- LEFT -------------------------------------------------------------------------------------------------->
	<? include_once "left.php"; ?>
	<!-- LEFT END ---------------------------------------------------------------------------------------------->
	</td>
	<!-- LEFT BG------------------------------------------------------------------------------------------------>
	<td width="3" background="images/gray.jpg">
	</td>
	<!-- LEFT BG END-------------------------------------------------------------------------------------------->
	<td valign="top">
	<!-- BODY -------------------------------------------------------------------------------------------------->
	<table border="0" cellpadding="0" cellspacing="0" width="100%">
			<!-- BODY CENTER ----------------------------------------------------------------------------------------->
		<tr>
			<td width="100%">			
			<table border="0" cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td style="padding-left:15px" valign="bottom" height="24" colspan="2">					
				</td>
			</tr>	
			<tr>
				<td style="padding-left:15px" valign="top">



				<!-- CONTENTS -->
				<table border="0" cellpadding="0" cellspacing="0" width="1000">				
				<tr>
					<td valign="top">
					<table border="0" cellpadding="0" cellspacing="0" width="1000" class="font11_bold">
						<tr><td height="8"></td></tr>
						<tr><td width="252"><img src="images/icon_circle03.gif" style="vertical-align:middle;">&nbsp;<span style="height:21px">Company Details</span></td></tr>
						<tr><td height="3"></td></tr>
						<tr><td background="images/bg_check.gif" height="3"></td></tr>
						<tr><td height="2"></td></tr>
					</table>
					</td>
				</tr>
				<form name="frm1" method="post" action="company_regist_ok.php">
				<input type="hidden" name="action_type" value="<?=$action_type?>">
				<input type="hidden" name="company_id" value="<?=$company_id?>">
				<tr>
					<td valign="top">
						<table border="1" cellpadding="0" cellspacing="0" bordercolor="white" width="1000">
						<tr >
							<td style="padding-left:3px" width="200" align="left"  height="30" class="ui-widget-header" >*Name</td>
							<td style="padding-left:3px" ><input type="text" size="80" name="company_name" value="<?php echo $list_Records[0]["company_name"];?>"> </td>	
						</tr>
						<tr >
							<td style="padding-left:3px" width="130" align="left"  height="30" class="ui-widget-header" >ABN</td>
							<td  style="padding-left:3px" ><input type="text" size="80" name="company_abn" value="<?php echo $list_Records[0]["company_abn"];?>"> </td>	
						</tr>
						<tr >
							<td style="padding-left:3px" width="130" align="left"  height="30" class="ui-widget-header" >Homepage Address</td>
							<td  style="padding-left:3px" ><input type="text" size="80" name="company_homepage" value="<?=$list_Records[0]["company_homepage"]?>"> </td>	
						</tr>
						<tr  id="h_addr">
							<td style="padding-left:3px" align="left"  height="30" class="ui-widget-header" > Address</td>
							<td style="padding-left:3px" >
							<table border="0" cellpadding="0" cellspacing="0" width="700">
							<tr><td width="130" height="28">  Address</td><td><input type="text" size="60" name="company_address" value="<?=$list_Records[0]["company_address"]?>"></td></tr>
							<tr><td width="130" height="28">  City</td><td><input type="text" size="60" name="company_suburb" value="<?=$list_Records[0]["company_suburb"]?>"></td></tr>
							<tr><td width="130" height="28">  State</td><td>
							<?php getStateOption("company_state",$list_Records[0]["company_state"]) ?>
							</td></tr>
							<tr><td width="130" height="28">  Postal Code</td><td><input type="text" size="21" name="company_postcode" value="<?=$list_Records[0]["company_postcode"]?>"></td></tr>
							
							</table>			
							</td>	
						</tr>		
						<tr  id="h_phone">
							<td style="padding-left:3px" align="left"  height="30" class="ui-widget-header" > Phone</td>
							<td style="padding-left:3px" ><input type="text" size="20" name="company_phone_number" value="<?=$list_Records[0]["company_phone_number"]?>"></td>	
						</tr>
						<tr  id="h_fax">
							<td style="padding-left:3px" align="left"  height="30" class="ui-widget-header" > Fax</td>
							<td style="padding-left:3px" ><input type="text" size="20" name="company_fax_number" value="<?=$list_Records[0]["company_fax_number"]?>"></td>
						</tr>
						<tr  id="h_addr">
							<td style="padding-left:3px" align="left"  height="30" class="ui-widget-header"> Other branch</td>
							<td style="padding-left:3px" >
							<table border="0" cellpadding="0" cellspacing="0" width="700">
							<tr><td width="130" height="28">  Address </td><td><input type="text" size="60" name="company_address2" value="<?=$list_Records[0]["company_address2"]?>"></td></tr>
							<tr><td width="130" height="28">  City </td><td><input type="text" size="60" name="company_suburb2" value="<?=$list_Records[0]["company_suburb2"]?>"></td></tr>
							<tr><td width="130" height="28">  State</td><td>
							<?php getStateOption("company_state2",$list_Records[0]["company_state2"]) ?>
							</td></tr>
							<tr><td width="130" height="28">  Postal Code</td><td><input type="text" size="21" name="company_postcode2" value="<?=$list_Records[0]["company_postcode2"]?>"></td></tr>
							
							</table>			
							</td>	
						</tr>		
						<tr  id="h_phone">
							<td style="padding-left:3px" align="left"  height="30" class="ui-widget-header" > Phone</td>
							<td style="padding-left:3px" ><input type="text" size="20" name="company_phone_number2" value="<?=$list_Records[0]["company_phone_number2"]?>"></td>	
						</tr>
						<tr  id="h_fax">
							<td style="padding-left:3px" align="left"  height="30" class="ui-widget-header" > Fax </td>
							<td style="padding-left:3px" ><input type="text" size="20" name="company_fax_number2" value="<?=$list_Records[0]["company_fax_number2"]?>"></td>
						</tr>
						<tr  id="h_email">
							<td style="padding-left:3px" align="left"  height="30" class="ui-widget-header" > Email</td>
							<td style="padding-left:3px" ><input type="text" size="40" name="company_email" value="<?=$list_Records[0]["company_email"]?>"></td>	
						</tr>
						<tr  >
							<td style="padding-left:3px" align="left"  height="30" class="ui-widget-header" > Facebook </td>
							<td style="padding-left:3px" ><input type="text" size="80" name="company_facebook" value="<?=$list_Records[0]["company_facebook"]?>"></td>	
						</tr>
						<tr  >
							<td style="padding-left:3px" align="left"  height="30" class="ui-widget-header" > Youtube</td>
							<td style="padding-left:3px" ><input type="text" size="80" name="company_youtube" value="<?=$list_Records[0]["company_youtube"]?>"></td>	
						</tr>
						<tr  >
							<td style="padding-left:3px" align="left"  height="30" class="ui-widget-header" > Blog</td>
							<td style="padding-left:3px" ><input type="text" size="80" name="company_blog" value="<?=$list_Records[0]["company_blog"]?>"></td>	
						</tr>
						<tr  >
							<td style="padding-left:3px" align="left"  height="30" class="ui-widget-header" > Twitter</td>
							<td style="padding-left:3px" ><input type="text" size="80" name="company_twitter" value="<?=$list_Records[0]["company_twitter"]?>"></td>	
						</tr>
						<!--
						<tr  >
							<td style="padding-left:3px" align="left"  height="30" > Introduction</td><td style="padding-left:3px"><textarea class="ckeditor"  name="company_introduction" rows="4" cols="65"><?=$list_Records[0]["company_introduction"]?></textarea> </td>	
						</tr>
						-->
						</table>		
						<br>
						<table border="0" cellpadding="0" cellspacing="0" width="1000">
						<tr><td align="right">Required fields are marked with an asterisk (*).<br><input type="button" value="Save" onclick="formchk();"></td></tr>
						</table>
					</td>
				</tr>
				</form>
				<tr><td></td></tr>
				</table>
				
				<!-- CONTENTS END  -->
			</td>
			<td>
			</td>
		  </tr>
		  <tr><td colspan="2" height="20"></td></tr>
		 </table>
		 </td>
	    </tr>
	    <!-- BODY CENTER END -->
	   </table>
	<!-- BODY END -->
	</td>
</tr>
<tr>
	<td colspan="3">
	<!-- BOTTOM  -->
	<? include_once "bottom.php"; ?>
	<!-- BOTTOM END -->
	</td>
</tr>
</table>
</BODY>
</HTML>
