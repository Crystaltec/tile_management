<?PHP
include_once "include/logincheck.inc";
include_once "include/common.inc";
include_once "include/dbconn.inc";
include_once "include/myfunc.inc";
include_once "htmlinclude/head.php";

$action_type = $_REQUEST["action_type"];
$userid = $_REQUEST["userid"];

$list_Records = array();

if($action_type=="modify") {
	## ĵ��, �〻 �迭 ����
	

	$Query  = "SELECT * ";
	$Query .= " FROM account WHERE userid='$userid'";

	$id_cnn = mysql_query($Query) or exit(mysql_error());
	while($id_rst = mysql_fetch_assoc($id_cnn)) {
		$list_Records = array_merge($list_Records, array($id_rst));
		//print_r($list_Records);
		//echo "<p>";
	}
} else if($action_type=="delete") {
	$req_userid = $_REQUEST["userid"];
	
	$sql = "SELECT COUNT(*) FROM orders WHERE user_id='".$userid."'";
	$row = getRowCount($sql);
	
	if($row[0] > 0 ) {
		echo "<script>alert('This account has used in orders.');history.back();</script>";
		exit;
	}

	$sql = "DELETE FROM account WHERE userid='" .$req_userid."'";
	pQuery($sql, "delete");
	echo "<script>alert('Deleted!');location.href='account_list.php';</script>";
	exit;
} 

$sql = "SELECT COUNT(*) FROM account WHERE alevel='A1'";
$admin_count = getRowCount($sql);
?>
<script type="text/javascript" src="js/jquery.password_strength.js"></script>
<script language="Javascript">
function formchk() {
	frm = document.frm1;
	var pass_strength = $(".password_strength");
	
	if(frm.accountlevel.value =="") {
		alert("please, select Account Level!");
		frm.accountlevel.focus();
		return;
	} 
	if(frm.userid.value =="") {
		alert("Please, Insert User Id!");
		frm.userid.focus();
		return;
	} 
	if(frm.uname.value =="") {
		alert("Please, Insert Name!");
		frm.uname.focus();
		return;
	}
	
	if(frm.upass.value =="") {
		alert("Please, Insert Password!");
		frm.upass.focus();
		return;
	} 
	
	if(frm.upass.value != frm.pass_confirm.value) {
		alert("Please type same as above!");
		frm.pass_confirm.focus();
		return;
	} 
	<?php if($action_type == "") { ?>
	if (pass_strength.text() == "" || pass_strength.text() == 'Too weak' || pass_strength.text() == 'Weak password' ) {
		alert("The password is too weak.");
		frm.upass.focus();
		return;
	}
	<?php } ?>
	frm.submit();
	
}

function showHide() {
	frm = document.frm1;
	if(frm.accountlevel.value=="") {
		return;
	}

}

$(function() {
	$("input:button, button").button();
	$('.ui-widget-content').css({'background-image' :'none','background-color':'none'});
	
	$("#n_password").password_strength();
	<?php if($action_type == "") { ?> 
	$("#upass").password_strength();
	<?php } ?>
	
	$( "#dialog:ui-dialog" ).dialog( "destroy" );
		
		var o_password = $( "#o_password" ),
			password = $( "#n_password" ),
			password_confirm = $( "#n_password_confirm" ),
			userid = $("input[name=userid]"),
			allFields = $( [] ).add( o_password ).add( password ).add( password_confirm ).add( userid ),
			tips = $( ".validateTips" );

		function updateTips( t ) {
			tips
				.text( t )
				.addClass( "ui-state-highlight" );
			setTimeout(function() {
				tips.removeClass( "ui-state-highlight", 1500 );
			}, 500 );
		}

		function checkLength( o, n, min, max ) {
			if ( o.val().length > max || o.val().length < min ) {
				o.addClass( "ui-state-error" );
				updateTips( "Length of " + n + " must be between " +
					min + " and " + max + "." );
				return false;
			} else {
				return true;
			}
		}
		
		function checkValue( o, n) {
			if ( o.val() == "" ) {
				o.addClass( "ui-state-error" );
				updateTips( "Please check " + n );
				return false;
			} else {
				return true;
			}
		}
		
	$( "#dialog-form" ).dialog({
			autoOpen: false,
			height: 370,
			width: 360,
			modal: true,
			buttons: {
				"Change": function() {
					var bValid = true;
					allFields.removeClass( "ui-state-error" );

					bValid = bValid && checkValue( o_password, "Old password");
					bValid = bValid && checkValue( password, "New password");
					bValid = bValid && checkValue( password_confirm, "re-type new password");
					
					if (password.val() != password_confirm.val()) {
						allFields.addClass( "ui-state-error" );
						updateTips( "Please confirm password ");
						return false;
					}
					
					if ($("#change_password_form .password_strength").text() == "" || $("#change_password_form .password_strength").text() == 'Too weak' || $("#change_password_form .password_strength").text() == 'Weak password' ) {
						allFields.addClass( "ui-state-error" );
						updateTips( "The password is too weak.");
						return false;
					}
		
								 
					if ( bValid ) {
						$('#processing').fadeIn(500);
						$.post("account_change_pass.php",{
							cid : userid.val(), 
							copass : hex_md5(o_password.val()),
							cpass : hex_md5(password.val())
						}, function(data){
							$('#processing').fadeOut(800);
							if(data == "SUCCESS") {
								alert("Success");
								reload();
							} else {
								alert("Authentication Failed!");
							}
						});
		
						$( this ).dialog( "close" );
					}
				},
				Cancel: function() {
					$( this ).dialog( "close" );
				}
			},
			close: function() {
				allFields.val( "" ).removeClass( "ui-state-error" );
			}
		});

		$( "#change_password" )
			.button()
			.click(function() {
				$( "#dialog-form" ).dialog( "open" );
			});
});
</script>
<script type="text/javascript" src="js/avauserid.js"></script>
<script type="text/javascript" src="js/md5.js"></script>
<style>
#dialog-form label,
#dialog-form select { float: left; margin-right: 10px; }
#dialog-form fieldset { border:0; }
.ui-dialog {width:460px !important; }
.ui-dialog .ui-state-error { padding: .3em; }
.ui-dialog-content {padding-left:0 !important;}
#dialog-form { height:185px !important; width:450px !important;}

#dialog-form input,
#dialog-form select { float:left; display:block !important; margin:0 0 5px 0 !important; }
#dialog-form label { width:110px; display:inline-block !important;}
.validateTips { border: 1px solid transparent; margin:0.3em; padding: 0.3em; }
.password_strength {
	margin-left:5px;
	display: inline-block;
	}
.password_strength_1 {
	background-color: #fcb6b1;
	}
.password_strength_2 {
	background-color: #fccab1;
	}
.password_strength_3 {
	background-color: #fcfbb1;
	}
.password_strength_4 {
	background-color: #dafcb1;
	}
.password_strength_5 {
	background-color: #bcfcb1;
	}

</style>
<BODY leftmargin=0 topmargin=0>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
<tr>
	<td valign="top" width="191" height="100%">
	<!-- LEFT -------------------------------------------------------------------------------------------------->
	<? include_once "left.php"; ?>
	<!-- LEFT END ---------------------------------------------------------------------------------------------->
	</td>
	<!-- LEFT BG------------------------------------------------------------------------------------------------>
	<td width="1" bgcolor="#DFDFDF">
	</td>
	<!-- LEFT BG END-------------------------------------------------------------------------------------------->
	<td valign="top">
	<!-- BODY -------------------------------------------------------------------------------------------------->
	<table border="0" cellpadding="0" cellspacing="0" width="100%">
		<!-- BODY TOP ------------------------------------------------------------------------------------------->
		<tr>
			<td style="padding-left:15px"><? include_once "top.php"; ?></td>
		</tr>
		<!-- BODY TOP END --------------------------------------------------------------------------------------->
		<!-- BODY CENTER ----------------------------------------------------------------------------------------->
		<tr>
			<td width="100%">			
			<table border="0" cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td style="padding-left:15px" valign="bottom" height="14" colspan="2">					
				</td>
			</tr>	
			<tr>
				<td style="padding-left:15px" valign="top">
				<!-- CONTENTS -------------------------------------------------------------------------------------------->
				<table border="0" cellpadding="0" cellspacing="0" width="1000">				
				<tr>
					<td valign="top">
					<table border="0" cellpadding="0" cellspacing="0" width="1000" class="font11_bold">
						<tr><td height="8"></td></tr>
						<tr><td width="252"><img src="images/icon_circle03.gif">&nbsp;<span style="height:21px">Account Register</span></td></tr>
						<tr><td height="3"></td></tr>
						<tr><td background="images/bg_check.gif" height="3"></td></tr>
						<tr><td height="2"></td></tr>
					</table>
					</td>
				</tr>
				<form name="frm1" method="post" action="account_regist_ok.php">
				<input type="hidden" name="action_type" value="<?=$action_type?>">
				<tr>
					<td valign="top">
						<table border="1" cellpadding="0" cellspacing="0" bordercolor="white" width="1000">
						<tr >
							<td style="padding-left:3px" align="left" class='ui-widget-header' height="30" width="200">* Account Level</td>
							<td style="padding-left:3px"  >
							<select name="accountlevel" style="width:120" onChange="showHide()">			
							<? //�ǇѼ�d ?>
							<? if($Sync_alevel == "A1") {?>
							<option value="">Please select</option>
							<option value="A1" <? if($list_Records[0]["alevel"] == "A1") echo "selected"?>>Administrator</option>
							<option value="B1" <? if($list_Records[0]["alevel"] == "B1") echo "selected"?>>Manager</option>
							<option value="B2" <? if($list_Records[0]["alevel"] == "B2") echo "selected"?>>Staff</option>
							<option value="C1" <? if($list_Records[0]["alevel"] == "C1") echo "selected"?>>Tiler</option>
							<?} else if($Sync_alevel == "B1") {?>
							<option value="">Please select</option>
							<option value="B2" <? if($list_Records[0]["alevel"] == "B2") echo "selected"?>>Staff</option>
							<option value="C1" <? if($list_Records[0]["alevel"] == "C1") echo "selected"?>>Tiler</option>
							<?} ?>
							</select>
							</td>	
						</tr>
						<tr >
							<td style="padding-left:3px" align="left"  height="30" class='ui-widget-header'  >* User id</td>
							<td style="padding-left:3px"  ><input type="text" size="12" name="userid" <?if($action_type=="modify") echo " readonly " ?> value="<?=$list_Records[0]["userid"]?>"><? if($action_type!="modify") { ?> <input type="button" value="Availability" onClick="avaUserid()">(Max 12 characters) <?}?>&nbsp;</td>	
						</tr>
						<tr >
							<td style="padding-left:3px"  align="left"  height="30" class='ui-widget-header'  >* Name</td>
							<td style="padding-left:3px"  ><input type="text" size="30" name="uname" value="<?=$list_Records[0]["username"]?>"> </td>	
						</tr>
						
						<tr >
							<td style="padding-left:3px" align="left"  height="30"  class='ui-widget-header' >* Password</td>
							<td style="padding-left:3px" ><input type="password" size="10" name="upass" id="upass" value=""> </td>	
						</tr>
						<tr >
							<td style="padding-left:3px" align="left"  height="30"  class='ui-widget-header' >* Confirm Password</td>
							<td style="padding-left:3px" ><input type="password" size="10" name="pass_confirm" id="pass_confirm" value=""> </td>	
						</tr>					
						<tr  id="h_addr">
							<td style="padding-left:3px" align="left"  height="30" class='ui-widget-header'  > Address</td>
							<td style="padding-left:3px"  >
							<table border="0" cellpadding="0" cellspacing="0" width="500">
							<tr><td width="100" height="25">  Address</td><td><input type="text" size="50" name="address" value="<?=$list_Records[0]["address"]?>"></td></tr>
							<tr><td width="100" height="25">  City</td><td><input type="text" size="50" name="suburb" value="<?=$list_Records[0]["suburb"]?>"></td></tr>
							<tr><td width="100" height="25">  State</td><td>
							<? getStateOption("state",$list_Records[0]["state"]) ?>
							</td></tr>
							<tr><td width="100" height="25">  Postal Code</td><td><input type="text" size="21" name="postcode" value="<?=$list_Records[0]["postcode"]?>"></td></tr>
							</table>			
							</td>	
						</tr>		
						<tr  id="h_phone">
							<td style="padding-left:3px" align="left"  height="30" class='ui-widget-header'  > Phone</td>
							<td style="padding-left:3px"  ><input type="text" size="20" name="phone" value="<?=$list_Records[0]["phone"]?>"></td>	
						</tr>
						<tr  id="h_fax">
							<td style="padding-left:3px" align="left"  height="30" class='ui-widget-header'  > Fax</td>
							<td style="padding-left:3px" ><input type="text" size="20" name="fax" value="<?=$list_Records[0]["fax"]?>"></td>
						</tr>
						<tr  id="h_email">
							<td style="padding-left:3px" align="left"  height="30" class='ui-widget-header'  > Email</td>
							<td style="padding-left:3px" ><input type="text" size="40" name="email" value="<?=$list_Records[0]["email"]?>"></td>	
						</tr>
						<tr  id="h_email">
							<td style="padding-left:3px" align="left"  height="30" class='ui-widget-header'  > Remarks</td>
							<td style="padding-left:3px" ><textarea name="remarks" rows="4" cols="65"><?=$list_Records[0]["remarks"]?></textarea> </td>	
						</tr>	
						<tr >
							<td style="padding-left:3px" align="left" class='ui-widget-header'  >Display</td>
							<td style="padding-left:3px" ><?php echo DrawFromDB('account','display','select',$list_Records[0]['display'],'yes','',NULL)?><em>If display sets to N, then this account will not be shown on site contact list on purchase order</em></td>	
						</tr>
						<tr >
							<td style="padding-left:3px" align="left" class='ui-widget-header'  >Status</td>
							<td style="padding-left:3px" ><?php echo DrawFromDB('account','status','select',$list_Records[0]['status'],'yes','',NULL)?><em>If status sets to N, then this account will block to login</em></td>	
						</tr>
						</table>		
						<br>
						<table border="0" cellpadding="0" cellspacing="0" width="1000">
						<tr><td align="right">Required fields are marked with an asterisk (*).<br><span style="float:left;"><input type="button" value="Change Password" id="change_password"></span> <input type="button" value="Save" onclick="formchk();"></td></tr>
						</table>
					</td>
				</tr>
				</form>
				<tr><td></td></tr>
				</table>
				<?php
				if($action_type=="modify")
					echo "<script>showHide();</script>"
				?>
				<!-- CONTENTS END -------------------------------------------------------------------------------------------->
			</td>
			<td>
			</td>
		  </tr>
		  <tr><td colspan="2" height="20"></td></tr>
		 </table>
		 </td>
	    </tr>
	    <!-- BODY CENTER END------------------------------------------------------------------------------------->
	   </table>
	<!-- BODY END -------------------------------------------------------------------------------------------->
	</td>
</tr>
<tr>
	<td colspan="3">
	<!-- BOTTOM -------------------------------------------------------------------------------------------->
	<? include_once "bottom.php"; ?>
	<!-- BOTTOM END -------------------------------------------------------------------------------------------->
	</td>
</tr>
</table>
<div id="dialog-form" title="Change Password">
	<p class="validateTips">All form fields are required.</p>
	<form id="change_password_form">
		<fieldset>
		<label for="o_password">Old Password</label>
		<input type="password" name="o_password" id="o_password" value="" class="text ui-widget-content ui-corner-all" />
		<div style="clear:both;"></div>
		<label for="password">New Password</label>
		<input type="password" name="n_password" id="n_password" value="" class="text ui-widget-content ui-corner-all" />
		
		<div style="clear:both;"></div>
		<label for="password_confirm">Confirm New Password</label>
		<input type="password" name="n_password_confirm" id="n_password_confirm" value="" class="text ui-widget-content ui-corner-all" />
		</fieldset>
	</form>
</div>
</BODY>
</HTML>
