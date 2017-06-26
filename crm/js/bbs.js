var g_boardid = null;
var g_no = null;

function goDeleteComment(idx, boardid) {	
	var pass = document.getElementById("pass"+idx);
    var url = "bbs_comments_del.php?boardid="+encodeURIComponent(boardid)+"&idx="+ encodeURIComponent(idx)+"&pass="+encodeURIComponent(pass.value);
    httpRequest("GET",url,true,handleResponse);
}

function goAction() {	
	var f = document.hiddenForm;
	var no = f.no.value;
	var boardid = f.boardid.value;
	var act = f.act.value;
	g_boardid=boardid;
	g_no = no;
	var pass = document.getElementById("pass");
	//alert(act);
	//alert(idx);
	//alert(boardid);
	//alert(pass.value);
    var url = "bbs_action.php?act="+encodeURIComponent(act)+"&boardid="+encodeURIComponent(boardid)+"&no="+ encodeURIComponent(no)+"&pass="+encodeURIComponent(pass.value);
	//alert(url);
    httpRequest("GET",url,true,handleResponse);
}

function goReply() {	
	var f = document.hiddenForm;
	f.act.value="reply";
	f.action = "bbs_write.php";
	f.submit();
}


//event handler for XMLHttpRequest
function handleResponse(){
    try{
        if(request.readyState == 4){
            if(request.status == 200){
                var resp = request.responseText;
				//alert(resp);
                if(resp != null) {
					//alert("3")
                    var func = new Function("return "+resp);
					var objt = func();
					//alert(objt);
					switch (objt.act) {
					case "main_comment_delete" : 
						if(objt.result == "ok") {
							alert("Deleted!");
							window.location.reload();
						} else {
							alert("Incorrect password!");
							return;
						}
						break;
					case "board_comment_delete" : 
						if(objt.result == "ok") {
							alert("Deleted!");
							window.location.reload();
						} else {
							alert("Incorrect password!");
							return;
						}
						break;
					case "board_modify" : 
						if(objt.result == "ok") {
							window.location.replace("bbs_write.php?act=modify&boardid="+g_boardid+"&no="+g_no);
						} else {
							//alert(objt.sql);
							alert("Incorrect password!");
							return;
						}
						break;
					case "board_delete" :
						if(objt.result == "ok") {
							alert("Deleted!");
							window.location.replace("bbs_list.php?boardid="+g_boardid+"&no="+g_no);
						} else {
							alert("Incorrect password!");
							return;
						}
						break;			
					}                  
				}
            } else {
                //request.status is 503  if the application isn't available; 
                //500 if the application has a bug
                alert(
                        "A problem occurred with communicating between the XMLHttpRequest object and the server program.");
            }
        }//end outer if
    } catch (err)   {
        alert(err.name);
        alert("It does not appear that the server is available for this application. Please"+
              " try again very soon. \nError: "+err.message);

    }
}


function validate_required(field,alerttxt) {
	with (field) {
		if (value==null||value=="") {alert(alerttxt);return false}
		else {return true}
	}
}

function validate_checked(field,alerttxt) {
	with (field) {
		if (checked == false) {alert(alerttxt);return false}
		else {return true}
	}
}

function validate_email(field,alerttxt) {
	with (field) {
		apos=value.indexOf("@");
		dotpos=value.lastIndexOf(".");
		if (apos<1||dotpos-apos<2) {alert(alerttxt);return false}
		else {return true}
	}
}


function checkBoardForm(thisform) {
	with (thisform) {		
		if (validate_required(subject,"Title must be filled out!")==false) {subject.focus();return false}
		if (validate_required(name,"Name must be filled out!")==false) {name.focus();return false}
		//if (validate_required(pass,"Password must be filled out!")==false) {pass.focus();return false}		
		//if (validate_required(contents,"contents must be filled out!")==false) {contents.focus();return false}
		//if (validate_required(file_up_0,"file must be selected!")==false) {file_up_0.focus();return false}
	}

	document.getElementById("loading").style.display="block";
}


function checkUpdateBoardForm(thisform) {
	with (thisform) {		
		if (validate_required(subject,"Title must be filled out!")==false) {subject.focus();return false}
		if (validate_required(name,"Name must be filled out!")==false) {name.focus();return false}
		//if (validate_required(pass,"Password must be filled out!")==false) {pass.focus();return false}		
		//if (validate_required(contents,"contents must be filled out!")==false) {contents.focus();return false}		
	}

	document.getElementById("loading").style.display="block";
}

function checkCommentForm(thisform) {
	with (thisform) {		
		if (validate_required(name,"Subject must be filled out!")==false) {name.focus();return false}
		if (validate_required(pass,"Name must be filled out!")==false) {pass.focus();return false}
		//if (validate_required(contents,"contents must be filled out!")==false) {contents.focus();return false}
	}

	if(confirm("Press OK to confirm?")) {
		document.getElementById("loading").style.display="block";
		return true;
	} else {
		return false;
	}
}


function delComment(idx) {
	document.getElementById("del"+idx).style.display="none";
	document.getElementById("password"+idx).style.display="block";
}

function goPasswordCheck(act) {
	var f = document.hiddenForm;
	var no = f.no.value;
	var boardid = f.boardid.value;
	f.act.value = act;
	document.getElementById("pass_space").style.display = "block";
}

function img_popup(){
	var productid=document.getElementById("productid").value;
	var productname = document.getElementById("productname").value;
	var filename = document.getElementById("filename").value;
	var width = parseInt(document.getElementById("width").value);
	var height = parseInt(document.getElementById("height").value);
	//alert(productname);
	var x, y;		
	if(width > window.screen.Width -10) {
		width = 	window.screen.Width -10;
		x = 0;
	} else {
		x = Math.round((window.screen.Width - width) / 2);
	}
	if(height > window.screen.Height -70) {
		height = window.screen.Height -70;
		y = 0;
	} else {
		y =  Math.round((window.screen.Height - width) / 2);
	}
	window.open("product_img_popup.php?product_name="+productname+"&filename="+filename, "imgpop", "left="+x+",top="+y+",width="+width+",height="+height+",toolbar=no,menubar=no,status=no,scrollbars=no,resizable=no, location=no");
}
