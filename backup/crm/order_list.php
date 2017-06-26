<?php
ob_start();
include_once "include/logincheck.inc";
include_once "include/common.inc";
include_once "include/dbconn.inc";
include_once "include/myfunc.inc";
include_once "htmlinclude/head.php";

$act_flag = $_REQUEST["act_flag"];
if($act_flag != "") {
	$itemcnt = $_REQUEST["itemcnt"];
	//echo $itemcnt ."<br>";
	for($i=0; $i < $itemcnt; $i++) {
		$orders_number = $_REQUEST["orders_number$i"];
		$orders_status = $_REQUEST["orders_status$i"];
		$supplier_id = $_REQUEST["supplier_id"];
		$delivery_date = $_REQUEST["delivery_date$i"];
		if ($orders_number != "") {
			//echo "orders_number : ".$orders_number.", orders_status : ". $orders_status . ", paystat : " . $paystat . "<br>";
			if($act_flag == "update") {
				$sql = "UPDATE orders SET orders_status='" .$orders_status . "'";
				$sql .= " WHERE orders_number='".$orders_number."'";
				pQuery($sql, "update");
				echo "<script>location.href='order_list.php'</script>";
			} else if($act_flag == "delete") {
				$sql = "DELETE FROM orders WHERE orders_number='".$orders_number."'";
				pQuery($sql, "delete");
				echo "<script>location.href='order_list.php'</script>";
			}			
		}
	}
}
?>
<script language="Javascript">
function changeStatus() {
	var f = document.orderfrm;
	
	var itemcnt = parseInt(f.itemcnt.value);
	var chk = false;

	for(var i=0; i<itemcnt;i++) {
		if(f.elements["orders_number"+i].checked == true) {
			chk = true;
		}
	}
	if(chk) {
		f.act_flag.value="update";
		f.action="<?=$_SERVER['PHP_SELF']?>";
		f.submit();
	} else {
		alert("Please, Choose Order No. and Tick to Change!");
	}			
}

function orderDelete() {	
	var f = document.orderfrm;
	
	var itemcnt = parseInt(f.itemcnt.value);
	var chk = false;

	for(var i=0; i<itemcnt;i++) {
		if(f.elements["orders_number"+i].checked == true) {
			chk = true;
		}
	}
	if(chk) {
		if(confirm("Are you sure?")) {
			f.act_flag.value="delete";
			f.action="<?=$_SERVER['PHP_SELF']?>";
			f.submit();
		}
	} else {
		alert("Please, Tick the order no to delete!");
	}
}

function showDateField(i) {
	var f = document.orderfrm;
	var ddate = document.getElementById("ddate"+i);
	if(f.elements["orders_status"+i].value == "delivery_complete") {
		ddate.style.display="block";
	} else 
		ddate.style.display="none";
}

function searchNow() {
	var f = document.searchform;
	var sday = f.sday.value;
	var smonth = f.smonth.value;
	var syear = f.syear.value;

	var eday = f.eday.value;
	var emonth = f.emonth.value;
	var eyear = f.eyear.value;

	var start_date = syear + "-" + smonth + "-" + sday;
	var end_date = eyear + "-" + emonth + "-" + eday;

	if(start_date > end_date) {
		alert("Can't search!");
		return;
	}

	f.action="<?=$_SERVER['PHP_SELF']?>";
	f.submit();
}

function issueOrdersheet(orders_number) {
	window.open("issue_ordersheet.php?orders_number="+orders_number, "ordersheet", "");
}

function goExcelPrint(orders_number) {
	var f = document.orderfrm;
	//f.target="_blank";
	f.method="post";
	f.action="issue_ordersheet_excel.php?orders_number="+orders_number;
	f.submit();
}

$(function() {
	$("input:button, button").button();
	$(".list_table thead").addClass('ui-widget-header');
	$(".list_table tbody").addClass('ui-widget-content');
	$('.ui-widget-content').css({'background-image' :'none','background-color':'none'});
	
	$("#n_clear_date").datepicker($.datepicker.regional['en-GB']);
	$("#n_clear_date").datepicker( "option", "firstDay", 1 );
	$("#n_clear_date").datepicker();
	
	$( "#dialog:ui-dialog" ).dialog( "destroy" );
		
		var n_clear_date = $("#n_clear_date"),
			allFields = $( [] ).add( n_clear_date ),
			tips = $( ".validateTips" );

		function updateTips( t ) {
			tips
				.text( t )
				.addClass( "ui-state-highlight" );
			setTimeout(function() {
				tips.removeClass( "ui-state-highlight", 1500 );
			}, 500 );
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
				"Update": function() {
					var bValid = true;
					allFields.removeClass( "ui-state-error" );
					/*&& checkValue( n_clear_date, "Clear Date")*/
					bValid = bValid ;
												 
					if ( bValid ) {
						$('#processing').fadeIn(500);
						$.post("add_clear_date.php",{
							'p_pon' : $(this).data('p_pon'),
							n_clear_date : n_clear_date.val()
							
						}, function(data){
							$('#processing').fadeOut(800);
							if(data == "SUCCESS") {
								location.reload();
							} else {
								alert('Failed!');
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

		$( ".clear_date" ).click(function() {
			/* ,'p_existed_date':$(this).attr('class').split("clear_date")[1]*/
			$( "#dialog-form" ).data({'p_pon':$(this).attr('id')}).dialog( "open" );
		});
		
});
</script>
<style>
#table {font-size:11px;}
#dialog-form label,
#dialog-form select { float: left; margin-right: 10px; }
#dialog-form fieldset { border:0; }
.ui-dialog {width:310px !important; }
.ui-dialog .ui-state-error { padding: .3em; }
.ui-dialog-content {padding-left:0 !important;}
#dialog-form { height:110px !important; width:300px !important;}

#dialog-form input,
#dialog-form select { float:left; display:block !important; margin:0 0 5px 0 !important; }
#dialog-form label { width:80px; display:inline-block !important; clear:both;}
.validateTips { border: 1px solid transparent; margin:0.3em; padding: 0.3em; }
</style>
<BODY leftmargin=0 topmargin=0>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
<tr>
	<td valign="top" width="191" height="100%">
	<!-- LEFT -------------------------------------------------------------------------------------------------->
	<?php include_once "left.php"; ?>
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
						<tr><td width="252"><img src="images/icon_circle03.gif">&nbsp;<span style="height:21px">Order List</span></td></tr>
						<tr><td height="3"></td></tr>
						<tr><td background="images/bg_check.gif" height="3"></td></tr>
						<tr><td height="2"></td></tr>
					</table>
					</td>
				</tr>
				<tr>
					<td valign="top">
						<?php
							//Search Value
							$s_Cond = "";
							$srch_username = $_REQUEST["srch_username"];
							$srch_orders_status = $_REQUEST["srch_orders_status"];
							$supplier_id = $_REQUEST["supplier_id"];
							$project_id = $_REQUEST["project_id"];
							$srch_opt = $_REQUEST["srch_opt"];
							$srch_opt_value = $_REQUEST["srch_opt_value"];
							
							$sday = $_REQUEST["sday"];
							$smonth = $_REQUEST["smonth"];
							$syear = $_REQUEST["syear"];
							
							$eday = $_REQUEST["eday"];
							$emonth = $_REQUEST["emonth"];
							$eyear = $_REQUEST["eyear"];
							if($sday == "") {
								
								$sdate = date("d-m-Y" ,time() - (3600 * 24 * 365));
								
								$sday = substr($sdate, 0,2);
								$smonth = substr($sdate, 3,2);
								$syear = substr($sdate, 6,4);
								
								$eday = substr($now_date, 0,2);
								$emonth = substr($now_date, 3,2);
								$eyear = substr($now_date, 6,4);
								$ehour = substr($now_time, 0,2);
								$emin = substr($now_time, 3,2);
							}
							$start_date = $syear."-".$smonth."-".$sday;							
							$end_date = $eyear."-".$emonth."-".$eday;

							//$start_date = $sday."/".$smonth."/".$syear;
							//$end_date = $eday."/".$emonth."/".$eyear;
							
							$end_date = $end_date . " 23:59:59";
							
							$s_Cond .= " AND (o.orders_date >= '$start_date' AND o.orders_date <= '$end_date') ";	
							 
							$srch_param = "srch_pbase=$srch_pbase&sday=$sday&smonth=$smonth&syear=$syear&eday=$eday&emonth=$emonth&eyear=$eyear";

							if($srch_username != "") {
								$s_Cond .= " AND o.user_id='" .$srch_username."' ";
								$srch_param .= "&sch_username=$sch_username";
							}
							if($srch_orders_status != "") {
								$s_Cond .= " AND o.orders_status='" .$srch_orders_status."' ";
								$srch_param .= "&srch_orders_status=$srch_orders_status";
							}
							
							if($supplier_id != "") {
								$s_Cond .= " AND o.supplier_id ='". $supplier_id ."'";
								$srch_param .= "&supplier_id=$supplier_id";
							}
							
							if($project_id != "") {
								$s_Cond .= " AND o.project_id ='". $project_id ."'";
								$srch_param .= "&project_id=$project_id";
							}
							
							if($srch_opt != "" && $srch_opt_value != "") {
								$s_Cond .= " AND $srch_opt like '%". $srch_opt_value ."%' ";
								$srch_param .= "&srch_opt=$srch_opt";
								$srch_param .= "&srch_opt_value=$srch_opt_value";
							}
							
							//$srch_param = urlencode($srch_param);
						?>
						<form name="searchform">
						<table border="0" cellpadding="0" cellspacing="0" height="40" valign="bottom" width="1000">							
							<tr class="ui-widget-header">
								<td width="150" height="30"  style="padding-left:5px" >	Period
								</td>
								<td style="padding-left:5px" colspan="3">
						
								<select name="sday">
								<? for($i=1; $i < 32; $i++) { 
									if($i < 10) {
										$k = "0".$i;
									} else {$k=$i;}
									?>
								<option value="<?=$k?>" <?if($sday==$i) echo "selected";?>><?=$k?></option>
								<? } ?>

								</select>&nbsp;
								<select name="smonth">
								<? for($i=1; $i < 13; $i++) { 
									if($i < 10) {
										$k = "0".$i;
									} else {$k=$i;}
									?>
								<option value="<?=$k?>" <?if($smonth==$i) echo "selected";?>><?=$k?></option>
								<? } ?>
								</select>&nbsp;
								<? yearOption("syear",$syear); ?>
								~
								<select name="eday">
								<? for($i=1; $i < 32; $i++) { 
									if($i < 10) {
										$k = "0".$i;
									} else {$k=$i;}
									?>
								<option value="<?=$k?>" <?if($eday==$i) echo "selected";?>><?=$k?></option>
								<? } ?>

								</select>&nbsp;
								<select name="emonth">
								<? for($i=1; $i < 13; $i++) { 
									if($i < 10) {
										$k = "0".$i;
									} else {$k=$i;}
									?>
								<option value="<?=$k?>" <?if($emonth==$i) echo "selected";?>><?=$k?></option>
								<? } ?>
								</select>&nbsp;
								<? yearOption("eyear",$eyear); ?>
								</td>
							</tr>
							<tr><td colspan="4" background="images/bg_check02.gif" height="3"></td></tr>
							<tr class="ui-widget-header">
								<td height="30"  style="padding-left:5px">			Supplier
								</td>
								<td style="padding-left:5px">
								<? getOption("supplier",$supplier_id)?>
								</td>
								<td height="30"  style="padding-left:5px">			Project
								</td>
								<td style="padding-left:5px">
								<?php getSelectOption('project','',' project_name ',NULL," id='project_id' ",NULL,'300');?>
								</td>
							</tr>
							<tr><td colspan="4" background="images/bg_check02.gif" height="3"></td></tr>
							<tr class="ui-widget-header">
								<td height="30"  style="padding-left:5px">			Status
								</td>
								<td style="padding-left:5px" >
								<?php processOption("srch_orders_status",$srch_orders_status)?>
								&nbsp;		
								</td>
								<td height="30"  >
								<select name="srch_opt" id="srch_opt">
									<option value='delivery_date' <?php if($srch_opt == 'delivery_date' ) echo " selected "; ?> >Delivery date</option>
									<option value='material_description' <?php if($srch_opt == 'material_description' ) echo " selected "; ?>>Description</option>
									<option value='orders_number' <?php if($srch_opt == 'orders_number' ) echo " selected "; ?>>Order number</option>
									<option value='supervisor_info' <?php if($srch_opt == 'supervisor_info' ) echo " selected "; ?>>Supervisor info</option>
								</select>
								</td>
								<td style="padding-left:5px" >
								<input type="text" name='srch_opt_value' id='srch_opt_value' value='<?php echo "$srch_opt_value";?>'> 
								</td>
							</tr>
							
							<tr><td colspan="4" background="images/bg_check02.gif" height="3"></td></tr>
							<tr>
							<td colspan="4" align="right" height="30"><input type="button" value="Search" onclick="searchNow()"></td></tr>
						</table>
						</form>
						<br>
						<form name="orderfrm">
						<table border="0" cellpadding="0" cellspacing="1" width="1000" class='list_table'>
						<thead>
						<tr align="center" height="30">
							<th width="80">PO No.</th>
							<th width="65">Ordered Date</th>
							<th width="155">Ordered By</th>
							<th width="160">Supplier/<br/>Total Price</th>
							<th >Deliver To</th>
							<th width="110">Delievery Date</th>
							<th width="125">Status</th>			
							<th width="50">Order Sheet</th>
						</tr>
						</thead>
						<?php																
							// 페이지 계산 /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
							$page = $_REQUEST["page"];
							if(!$page)
								$page = 1;
						
							$limitPage = 10;
							$limitList = 15;
							$total = getRowCount2("SELECT COUNT(distinct orders_date) FROM orders o WHERE 1=1 and orders_number <>'' ". $s_Cond);
							//echo ceil(1.2);
							$totalPage = ceil($total/$limitList);
							$block = ceil($page/$limitPage);
							$start = ($page-1)*$limitList;

							$startPage = ($block-1)*$limitPage + 1;
							$endPage = $startPage + $limitPage - 1;
							if ($endPage > $totalPage ) $endPage = $totalPage; 
							// 페이지 계산 끝//////////

								$sql= "SELECT o.orders_number, o.user_id, ac.username, o.orders_status, o.project_id,o.supplier_id, ";
								$sql.= "o.orders_date, o.delivery_date, sum(o.material_price * o.orders_inventory) as amount, clear_date  ";
								$sql.="FROM orders o ";
								$sql.="INNER JOIN account ac ";
								$sql.="	ON o.user_id=ac.userid ";							
								$sql.="WHERE 1=1 and o.orders_number <>'' ". $s_Cond . " GROUP BY orders_number ORDER BY orders_number DESC, orders_date DESC LIMIT $start, $limitList";
						
							//echo $sql;
							$list_Records = array();
							$id_cnn = mysql_query($sql) or exit(mysql_error());
							while($id_rst = mysql_fetch_assoc($id_cnn)) {
								$list_Records = array_merge($list_Records, array($id_rst));
								//print_r($list_Records);
								//echo "<p>";
							}
							//echo count($list_Records);
												
							if(count($list_Records)) {
							for($i=0; $i<count($list_Records); $i++) {		
																
								$deliveryto = "";

								if ($list_Records[$i]["project_id"]) {
									$deliveryto = getName("project",$list_Records[$i]["project_id"]); 
								} else {
									$deliveryto = "Stock Order";
								}
								
								
						?>
						<tr align="center" onmouseover="javascript:this.style.color='#536499';this.style.backgroundColor='#dcebfe';"  onmouseout="javascript:this.style.color='#000000';this.style.backgroundColor='';" height="40" <?php	
						if($list_Records[$i]["orders_status"] == "COMPLETED" ) {echo "bgcolor='#C9E4FF'";} 
						else if($list_Records[$i]["orders_status"] == "HOLDING" ) {echo "bgcolor='#FFBE3C'";}  ?> >
							<td height="30" style="cursor:hand"><?if($Sync_alevel<="B2"){?><input type="checkbox" name="orders_number<?=$i?>" value="<?=$list_Records[$i]["orders_number"]?>"><? }?><a href="order_view.php?orders_number=<?=$list_Records[$i]["orders_number"]?>&srch_param=<?=urlencode($srch_param)?>"><?=$list_Records[$i]["orders_number"]?></a></td>
							<td class='font90'><a href="order_view.php?orders_number=<?=$list_Records[$i]["orders_number"]?>&srch_param=<?=urlencode($srch_param)?>"><?=getAUDate($list_Records[$i]["orders_date"])?></a></td>
							<td class='font90 left'><a href="order_view.php?orders_number=<?=$list_Records[$i]["orders_number"]?>&srch_param=<?=urlencode($srch_param)?>"><?=$list_Records[$i]["username"];?></a></td>
							<td class='font90'><a href="order_view.php?orders_number=<?=$list_Records[$i]["orders_number"]?>&srch_param=<?=urlencode($srch_param)?>"><?=getName("supplier",$list_Records[$i]["supplier_id"])?><br />
							<span class="quantity03">$<?=number_format($list_Records[$i]["amount"],2,".",",")?></span>
							</a></td>
							<td class='font90'><a href="order_view.php?orders_number=<?=$list_Records[$i]["orders_number"]?>&srch_param=<?=urlencode($srch_param)?>"><?=$deliveryto;?></a></td>
							<td class='font90'><a href="order_view.php?orders_number=<?=$list_Records[$i]["orders_number"]?>&srch_param=<?=urlencode($srch_param)?>"><?=$list_Records[$i]["delivery_date"]?></a></td>
							<td class='font90' id='<?php echo $list_Records[$i]['orders_number'];?>'><?php processOption("orders_status".$i,$list_Records[$i]["orders_status"],'115px'); ?></td>			
							<td><a href="javascript:goExcelPrint('<?=$list_Records[$i]["orders_number"]?>')"><img src="images/icon_excel.gif"></a>&nbsp;<a href="javascript:issueOrdersheet('<?=$list_Records[$i]["orders_number"]?>')"><img src="images/printButton.png"></a></td>
						</tr>			
						<?php
							}
							} else {
								echo "<tr><td colspan='8' height='40' align='center'>Nothing to display</td></tr>";
							}
						?>
						</table>
						<? if($Sync_alevel <= "B2") { ?>
						<table border="0" cellpadding="0" cellspacing="0" bordercolor="#c3c3c6" bordercolordark="white" width="1000">
						<tr><td width="100"><? if($Sync_alevel <= "B1") { ?><input type="button" value="DELETE" onclick="orderDelete()"><?}?></td><td></td><td align="right" height="40"><input type="button" value="Change status" onclick="changeStatus()"></td></tr>
						</table>
						<? } ?>
						<input type="hidden" name="itemcnt" value="<?=$i?>">
						<input type="hidden" name="act_flag">
						</form>
					</td>
				</tr>
				<tr><td align="center"><?php include_once "paging.php"?></td></tr>
				</table>
				<!-- CONTENTS END -------------------------------------------------------------------------------------------->
			</td>
			<td>
			</td>
		  </tr>
		  <tr><td colspan="2" height="50"></td></tr>
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
	<?php include_once "bottom.php"; ?>
	<!-- BOTTOM END -------------------------------------------------------------------------------------------->
	</td>
</tr>
</table>
<div id="dialog-form" title="Clear Date">
	<p class="validateTips">All form fields are required.</p>
	<form>
		<fieldset>
		<label for="n_date">Clear Date</label>
		<input type="text" name="n_clear_date" id="n_clear_date" ><br />
		</fieldset>
	</form>
</div>
</BODY>
</HTML>
<?php ob_flush(); ?>
