<?php
ob_start();
include_once "include/logincheck.inc";
include_once "include/common.inc";
include_once "include/dbconn.inc";
include_once "include/myfunc.inc";
include_once "htmlinclude/head.php";
?>
<link rel="stylesheet" type="text/css" href="css/dataTable.css" />
<link rel="stylesheet" type="text/css" href="media/css/TableTools.css" />
<script type="text/javascript" language="javascript" src="js/jquery.dataTables.js"></script>
<script type="text/javascript" language="javascript" src="js/jquery.jeditable.js"></script>
<script type="text/javascript" charset="utf-8" src="media/js/ZeroClipboard.js"></script>
<script type="text/javascript" charset="utf-8" src="media/js/TableTools.js"></script>
<script language="Javascript">
$(function() {
	$("input:button, button").button();

	$("#based_date").datepicker($.datepicker.regional['en-GB']);
	$("#based_date").datepicker( "option", "firstDay", 1 );
	$("#based_date").datepicker();
	
	$("#project_name").autocomplete({
    	source: "autocomplete_project.php",
    	minLength: 2,
    	select: function(event,ui) {
    		$('#n_project_id').val(ui.item.id);
			$('#project_name').val(ui.item.value);
    	}
    });
    
    $(document).ready(function() {
		
		$("#table tbody span.delete_row").live('click', function(){        
			deleteFunc (this,oTable, "user_delete")    
		});
		
		oTable = $('#table').dataTable({
					"bSort": false,
					"bJQueryUI": true,
					"sPaginationType": "full_numbers",
					"bAutoWidth": true,
					"bProcessing": true,
					"bServerSide": true,
					"iDisplayLength": 50,
					"aLengthMenu": [[50, 100, -1], [50, 100, "all"]],
					"aaSorting": [[2,'asc']],
					"sAjaxSource": "project_eval_dtsource.php?status=<?php echo $_GET['status']?>",		
					
					"fnRowCallback": function( nRow, aData, iDisplayIndex ) {            
						/* Append the grade to the default row class name */            
						if ( aData[1] != "" )            
						{                
							$('td:eq(0)', nRow).html( aData[1] +'&nbsp;<span class=\"delete_row ui-icon ui-icon-close\" style=\"display:inline-block !important;cursor:pointer;\"></span>' );            
						}
						       
						return nRow;        
					},
					"fnDrawCallback": function () {
						$('#table tbody td.budget_fc').editable( 'project_eval_edit_ajax.php', {
							"callback": function( sValue, y ) {
								/* Redraw the table from the new data on the server */
								oTable.fnDraw();
							},
							"submitdata": function ( value, settings ) {
								return {
									"id": this.parentNode.getAttribute('id'),
									"column": oTable.fnGetPosition( this )[2],
									"column_name" : "budget_fc"
								};
							},

							"height": "30px"
						} );
						$('#table tbody td.v_budget_fc').editable( 'project_eval_edit_ajax.php', {
							"callback": function( sValue, y ) {
								/* Redraw the table from the new data on the server */
								oTable.fnDraw();
							},
							"submitdata": function ( value, settings ) {
								return {
									"id": this.parentNode.getAttribute('id'),
									"column": oTable.fnGetPosition( this )[2],
									"column_name" : "v_budget_fc"
								};
							},

							"height": "30px"
						} );
						$('#table tbody td.labour_t_c').editable( 'project_eval_edit_ajax.php', {
							"callback": function( sValue, y ) {
								/* Redraw the table from the new data on the server */
								oTable.fnDraw();
							},
							"submitdata": function ( value, settings ) {
								return {
									"id": this.parentNode.getAttribute('id'),
									"column": oTable.fnGetPosition( this )[2],
									"column_name" : "labour_t_c"
								};
							},

							"height": "30px"
						} );
						$('#table tbody td.labour_s_c').editable( 'project_eval_edit_ajax.php', {
							"callback": function( sValue, y ) {
								/* Redraw the table from the new data on the server */
								oTable.fnDraw();
							},
							"submitdata": function ( value, settings ) {
								return {
									"id": this.parentNode.getAttribute('id'),
									"column": oTable.fnGetPosition( this )[2],
									"column_name" : "labour_s_c"
								};
							},

							"height": "30px"
						} );
						$('#table tbody td.labour_w_c').editable( 'project_eval_edit_ajax.php', {
							"callback": function( sValue, y ) {
								/* Redraw the table from the new data on the server */
								oTable.fnDraw();
							},
							"submitdata": function ( value, settings ) {
								return {
									"id": this.parentNode.getAttribute('id'),
									"column": oTable.fnGetPosition( this )[2],
									"column_name" : "labour_w_c"
								};
							},

							"height": "30px"
						} );
						$('#table tbody td.last_month_invoiced').editable( 'project_eval_edit_ajax.php', {
							"callback": function( sValue, y ) {
								/* Redraw the table from the new data on the server */
								oTable.fnDraw();
							},
							"submitdata": function ( value, settings ) {
								return {
									"id": this.parentNode.getAttribute('id'),
									"column": oTable.fnGetPosition( this )[2],
									"column_name" : "last_month_invoiced"
								};
							},

							"height": "30px"
						} );
						$('#table tbody td.approx_fp_ca').editable( 'project_eval_edit_ajax.php', {
							"callback": function( sValue, y ) {
								/* Redraw the table from the new data on the server */
								oTable.fnDraw();
							},
							"submitdata": function ( value, settings ) {
								return {
									"id": this.parentNode.getAttribute('id'),
									"column": oTable.fnGetPosition( this )[2],
									"column_name" : "approx_fp_ca"
								};
							},

							"height": "30px"
						} );
						$('#table tbody td.material_tile_c').editable( 'project_eval_edit_ajax.php', {
							"callback": function( sValue, y ) {
								/* Redraw the table from the new data on the server */
								oTable.fnDraw();
							},
							"submitdata": function ( value, settings ) {
								return {
									"id": this.parentNode.getAttribute('id'),
									"column": oTable.fnGetPosition( this )[2],
									"column_name" : "material_tile_c"
								};
							},

							"height": "30px"
						} );
						$('#table tbody td.material_material_c').editable( 'project_eval_edit_ajax.php', {
							"callback": function( sValue, y ) {
								/* Redraw the table from the new data on the server */
								oTable.fnDraw();
							},
							"submitdata": function ( value, settings ) {
								return {
									"id": this.parentNode.getAttribute('id'),
									"column": oTable.fnGetPosition( this )[2],
									"column_name" : "material_material_c"
								};
							},

							"height": "30px"
						} );
						$('#table tbody td.material_subcontractor_c').editable( 'project_eval_edit_ajax.php', {
							"callback": function( sValue, y ) {
								/* Redraw the table from the new data on the server */
								oTable.fnDraw();
							},
							"submitdata": function ( value, settings ) {
								return {
									"id": this.parentNode.getAttribute('id'),
									"column": oTable.fnGetPosition( this )[2],
									"column_name" : "material_subcontractor_c"
								};
							},

							"height": "30px"
						} );
						$('#table tbody td.contract_fc').editable( 'project_eval_edit_ajax.php', {
							"callback": function( sValue, y ) {
								/* Redraw the table from the new data on the server */
								oTable.fnDraw();
							},
							"submitdata": function ( value, settings ) {
								return {
									"id": this.parentNode.getAttribute('id'),
									"column": oTable.fnGetPosition( this )[2],
									"column_name" : "contract_fc"
								};
							},

							"height": "30px"
						} );
						$('#table tbody td.contract_sc').editable( 'project_eval_edit_ajax.php', {
							"callback": function( sValue, y ) {
								/* Redraw the table from the new data on the server */
								oTable.fnDraw();
							},
							"submitdata": function ( value, settings ) {
								return {
									"id": this.parentNode.getAttribute('id'),
									"column": oTable.fnGetPosition( this )[2],
									"column_name" : "contract_sc"
								};
							},

							"height": "30px"
						} );
						$('#table tbody td.v_fc').editable( 'project_eval_edit_ajax.php', {
							"callback": function( sValue, y ) {
								/* Redraw the table from the new data on the server */
								oTable.fnDraw();
							},
							"submitdata": function ( value, settings ) {
								return {
									"id": this.parentNode.getAttribute('id'),
									"column": oTable.fnGetPosition( this )[2],
									"column_name" : "v_fc"
								};
							},

							"height": "30px"
						} );
						$('#table tbody td.v_sc').editable( 'project_eval_edit_ajax.php', {
							"callback": function( sValue, y ) {
								/* Redraw the table from the new data on the server */
								oTable.fnDraw();
							},
							"submitdata": function ( value, settings ) {
								return {
									"id": this.parentNode.getAttribute('id'),
									"column": oTable.fnGetPosition( this )[2],
									"column_name" : "v_sc"
								};
							},

							"height": "30px"
						} );
					},

					"aoColumns" :[{"bVisible": false},
					{"sClass":"name","bSortable": false,"sWidth":"270px"},
					{"sClass":"based_date","bSortable": false,"sWidth":"50px" },
					{"sClass":"budget_fc right","bSortable": false},
					{"sClass":"v_budget_fc right","bSortable": false},
					{"sClass":"adjusted_budget right","bSortable": false},
					{"sClass":"labour_t_c right","bSortable": false},
					{"sClass":"labour_s_c right","bSortable": false},
					{"sClass":"labour_w_c right","bSortable": false},
					{"sClass":"labour_c_progress right","bSortable": false},
					{"sClass":"last_month_invoiced right","bSortable": false},
					{"sClass":"approx_fp_ca right","bSortable": false},
					{"sClass":"last_month_invoiced_fc right","bSortable": false},
					{"sClass":"material_tile_c right","bSortable": false},
					{"sClass":"material_material_c right","bSortable": false},
					{"sClass":"material_subcontractor_c right","bSortable": false},
					{"sClass":"material_fc right","bSortable": false},
					{"sClass":"expected_profit right","bSortable": false},
					{"sClass":"profit_after_managing_fee right","bSortable": false},
					{"sClass":"budget_left right","bSortable": false},
					{"sClass":"contract_fc right","bSortable": false},
					{"sClass":"contract_sc right","bSortable": false},
					{"sClass":"v_fc right","bSortable": false},
					{"sClass":"v_sc right","bSortable": false},
					{"sClass":"adjusted_contract_fc right","bSortable": false},
					{"sClass":"adjusted_contract_sc right","bSortable": false}]
				});
				
		var oTableTools = new TableTools( oTable, {
					
					"sSwfPath": "media/swf/copy_cvs_xls_pdf.swf",
					"aButtons": [
							"copy",
							"csv",
							"xls",
							{
								"sExtends": "pdf",
								"sPdfOrientation": "landscape",
								"sPdfMessage": " "
							},
							"print"
						]
				} );
				
				$('#table').before( oTableTools.dom.container );		
	});	
	
	function fnGetSelected( oTableLocal ){    
		var aReturn = new Array();    
		var aTrs = oTableLocal.fnGetNodes();         
		for ( var i=0 ; i<aTrs.length ; i++ )    
		{        
			if ( $(aTrs[i]).hasClass('row_selected') )        
			{            
				aReturn.push( aTrs[i] );        
				}    
		}    
		//alert(aReturn[0]);    
		return aReturn;
	} 
	function deleteFunc (obj,dataTable, cp) {           
		// Show a confirm popup to to be sure that we want to delete that record        
		var r=confirm("Do you want to delete this item?");        
		if (r==true){                
			// get the row that contains the delete btn            
			row = obj.parentNode.parentNode.getAttribute('id');            
			// get the record id from the btn id "delete_1"             
			idAll = row.split("project_eval_id_");                             
			id=idAll[1];          
			//add class to the selected row  
 			$(row).addClass('row_selected');            
			// make the ajax call to delete the record from db            
			$.post("project_eval_del_ajax.php",{
				pid : id
			}, function(data){
				if(data == "SUCCESS") {
				oTable.fnDraw();
				//var anSelected = fnGetSelected( dataTable );                    
				//	dataTable.fnDeleteRow( anSelected[0] );      
				} else {
					alert('Failed!');
				}
			});
			
			
		}                
	}
	


	$( "#dialog:ui-dialog" ).dialog( "destroy" );
		
		var n_project_id = $( "#n_project_id" ), 
			based_date = $("#based_date"),
			allFields = $( [] ).add( n_project_id ).add( based_date),
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
				"Add": function() {
					var bValid = true;
					allFields.removeClass( "ui-state-error" );

					bValid = bValid && checkValue( n_project_id, "Project");
					bValid = bValid && checkValue( based_date, "Based Date");
				
										 
					if ( bValid ) {
						$('#processing').fadeIn(500);
						$.post("add_project_eval.php",{
							pid : n_project_id.val(),
							based_date : based_date.val()
							
						}, function(data){
							$('#processing').fadeOut(800);
							if(data == "SUCCESS") {
								oTable.fnDraw();
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

		$( ".new_entry" )
			.button()
			.click(function() {
				$( "#dialog-form" ).dialog( "open" );
		});
		
});

</script>
<style>
#table {font-size:10px;  }
#table_wrapper { width:1500px; overflow:auto; }
#dialog-form label,
#dialog-form select { float: left; margin-right: 10px; }
#dialog-form fieldset { border:0; }
.ui-dialog {width:560px !important; }
.ui-dialog .ui-state-error { padding: .3em; }
.ui-dialog-content {padding-left:0 !important;}
#dialog-form { height:115px !important; width:550px !important;}

#dialog-form input,
#dialog-form select { float:left; display:block !important; margin:0 0 5px 0 !important; }
#dialog-form label { width:150px; display:inline-block !important; clear:both;}
.validateTips { border: 1px solid transparent; margin:0.3em; padding: 0.3em; }
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
			<td style="padding-left:15px"><?php include_once "top.php"; ?></td>
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
						<tr><td width="252"><img src="images/icon_circle03.gif">&nbsp;<span style="height:21px">Project Evaluation</span></td></tr>
						<tr><td height="3"></td></tr>
						<tr><td background="images/bg_check.gif" height="3"></td></tr>
						<tr><td height="2"></td></tr>
					</table>
					</td>
				</tr>
				<tr>
					<td valign="top">
						<?php if ($_GET['status'] <> 'completed') { ?> 
						<input type="button" class="new_entry" value="New Entry">
						<?php } ?>
						<div id="table_wrapper" >
						<table border="0" cellpadding="0" cellspacing="0" id="table" style="width:2000px;" class="display">
						<thead>
							<tr>
								<th width="0" rowspan="2">id</th>
								<th rowspan="2" style="width:300px;">Site</th>
								<th rowspan="2">Based date</th>
								<th class="ui-state-default" colspan="3">Budget</th>
								<th class="ui-state-default" colspan="3">Labour Cost up to Date<sup>8</sup></th>
								<th rowspan="2">Labour Cost progress %(Labour/ Adjusted Budget)<sup>8/7</sup></th>
								<th rowspan="2">Last Month Invoiced (Supply &amp; Install)<sup>9</sup></th>
								<th rowspan="2">Approx. Fixing Portion from Contract Amount<sup>10</sup></th>
								<th rowspan="2">Last Month Invoiced (Fixing Only)<sup>9*10</sup></th>
								<th class="ui-state-default" colspan="3">Material up to Date</th>
								<th  rowspan="2">Material % from Fixing Cost<sup>11/12</sup></th>
								<th class="ui-state-default" colspan="3">P/L</th>
								<th class="ui-state-default" colspan="2">Contract Amount</th>
								<th class="ui-state-default" colspan="2">Variation</th>
								<th class="ui-state-default" colspan="2">Adjusted Contract Amount</th>
							</tr>
							<tr>
								<th  >Budget Fixing Cost<sup>5</sup></th>
								<th  >Variation Budget Fixing Cost<sup>6</sup></th>
								<th  >Adjusted Budget<sup>7</sup></th>
								<th >Tiling</th>
								<th >Silicone</th>
								<th >Water proofing</th>
								<th  >Tile<sup>11</sup></th>
								<th  >Material</th>
								<th  >Sub contractor</th>
								<th  >Expected Profit (Fixing Only) <sup>13=12-8-11</sup></th>
								<th  >Profit after Managing Fee <sup>14=13/12-0.2</sup></th>
								<th  >Budget Left <sup>7-8</sup></th>
								<th  >Contract Fixing Cost<sup>1</sup></th>
								<th  >Contract Supply Cost<sup>2</sup></th>
								<th  >Variation Fixing Cost<sup>3</sup></th>
								<th  >Variation Supply Cost<sup>4</sup></th>
								<th  >Adjusted Contract Fixing Cost<sup>1+3</sup></th>
								<th  >Adjusted Contract Supply Cost<sup>2+4</sup></th>
							</tr>
						</thead>
						<tfoot>
							<tr>
								<th width="0" rowspan="2">id</th>
								<th rowspan="2">Site</th>
								<th rowspan="2">Based date</th>
								<th class="ui-state-default" colspan="3">Budget</th>
								<th class="ui-state-default" colspan="3">Labour Cost up to Date<sup>8</sup></th>
								<th  rowspan="2">Labour Cost progress %(Labour/ Adjusted Budget)<sup>8/7</sup></th>
								<th  rowspan="2">Last Month Invoiced (Supply &amp; Install)<sup>9</sup></th>
								<th  rowspan="2">Approx. Fixing Portion from Contract Amount<sup>10</sup></th>
								<th  rowspan="2">Last Month Invoiced (Fixing Only)<sup>9*10</sup></th>
								<th class="ui-state-default" colspan="3">Material up to Date</th>
								<th  rowspan="2">Material % from Fixing Cost<sup>11/12</sup></th>
								<th class="ui-state-default" colspan="3">P/L</th>
								<th class="ui-state-default" colspan="2">Contract Amount</th>
								<th class="ui-state-default" colspan="2">Variation</th>
								<th class="ui-state-default" colspan="2">Adjusted Contract Amount</th>
							</tr>
							<tr>
								<th  >Budget Fixing Cost<sup>5</sup></th>
								<th  >Variation Budget Fixing Cost<sup>6</sup></th>
								<th  >Adjusted Budget<sup>7</sup></th>
								<th >Tiling</th>
								<th>Silicone</th>
								<th>Water proofing</th>
								<th  >Tile<sup>11</sup></th>
								<th  >Material</th>
								<th  >Sub contractor</th>
								<th  >Expected Profit (Fixing Only) <sup>13=12-8-11</sup></th>
								<th  >Profit after Managing Fee <sup>14=13/12-0.2</sup></th>
								<th  >Budget Left <sup>7-8</sup></th>
								<th  >Contract Fixing Cost<sup>1</sup></th>
								<th  >Contract Supply Cost<sup>2</sup></th>
								<th  >Variation Fixing Cost<sup>3</sup></th>
								<th  >Variation Supply Cost<sup>4</sup></th>
								<th  >Adjusted Contract Fixing Cost<sup>1+3</sup></th>
								<th  >Adjusted Contract Supply Cost<sup>2+4</sup></th>
							</tr>
						</tfoot>
						</table>
						</div>
						<?php if ($_GET['status'] <> 'completed') { ?> 
						<input type="button" class="new_entry" value="New Entry">
						<?php } ?>
					</td>
				</tr>
				</table>
				<!-- CONTENTS END -------------------------------------------------------------------------------------------->
			</td>
			<td>
			</td>
		  </tr>
		  <tr><td colspan="2" height="202"></td></tr>
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
<div id="dialog-form" title="New Entry">
	<p class="validateTips">All form fields are required.</p>
	<form>
		<fieldset>
		<label for="n_project">Project</label>
		<input type="text" id='project_name' name="project_name" class='text ui-widget-content ui-corner-all' style="background-color:transparent;width:350px;"  /><br />
		<input type="hidden" id="n_project_id" name="n_project_id" />
		<label for="n_date">Based Date</label>
		<input type="text" name="based_date" id="based_date" >
		</fieldset>
	</form>
</div>
</BODY>
</html>
<?php ob_flush();?>