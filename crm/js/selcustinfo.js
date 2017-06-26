var request=null;

function getInfo(obj) {
    if (obj == null ) { return; }
	if (obj.value=="")	{
		var f = document.frm02;
		var f01= document.frm01;
		f.d_name.value="";
		f.d_phone.value="";
		f.d_fax.value="";
		f.d_abn.value="";
		f.d_mobile.value="";
		f.d_email.value="";
		f.d_addr.value="";
		f01.useok.checked=false;
		var hinfo = document.getElementById("hinfo");
		hinfo.style.display="none";
		return;
	}
    var url = "http://order.sushione.com/select_custinfo.php?userid="+ encodeURIComponent(obj.value);
    httpRequest("GET",url,true);
}

//event handler for XMLHttpRequest
function handleResponse(){
    try{
        if(request.readyState == 4){
            if(request.status == 200){
                var resp = request.responseText;
                if(resp != null) {
                    var func = new Function("return "+resp);
					var objt = func();
					var h_usename = document.getElementById("h_usename");
					var h_phone = document.getElementById("h_phone");
					var h_fax = document.getElementById("h_fax");
					var h_mobile = document.getElementById("h_mobile");
					var h_abn = document.getElementById("h_abn");
					var h_addr = document.getElementById("h_addr");
					var h_email = document.getElementById("h_email");
					//alert("abc");
					//alert(objt.username);
					h_usename.innerHTML=objt.username;
					h_phone.innerHTML=objt.phone;
					h_fax.innerHTML=objt.fax;
					h_mobile.innerHTML=objt.mobile;
					h_abn.innerHTML=objt.abn;
					h_email.innerHTML=objt.email;
					h_addr.innerHTML=objt.addr;
					//alert(objt.username);

					var f = document.orderform;
					f.puserid.value=objt.userid;
					f.pusername.value=objt.username;
					f.puser_level.value=objt.alevel;
					f.phone.value=objt.phone;
					f.ABN.value = objt.abn;
					f.fax.value=objt.fax;
					f.mobile.value=objt.mobile;
					f.email.value=objt.email;
					f.addr.value=objt.addr;
					f.payment_method.value=objt.paymentm;

					var hinfo = document.getElementById("hinfo");
					hinfo.style.display="block";
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

/* Initialize a Request object that is already constructed */
function initReq(reqType,url,bool){
    try{
        /* Specify the function that will handle the HTTP response */
        request.onreadystatechange=handleResponse;
        request.open(reqType,url,bool);
        request.send(null);
    } catch (errv) {
        alert(
                "The application cannot contact the server at the moment. "+
                "Please try again in a few seconds." );
    }
}
/* Wrapper function for constructing a Request object.
 Parameters:
  reqType: The HTTP request type such as GET or POST.
  url: The URL of the server program.
  asynch: Whether to send the request asynchronously or not. */
function httpRequest(reqType,url,asynch){
    //Mozilla-based browsers
    if(window.XMLHttpRequest){
        request = new XMLHttpRequest();
    } else if (window.ActiveXObject){
        request=new ActiveXObject("Msxml2.XMLHTTP");
        if (! request){
            request=new ActiveXObject("Microsoft.XMLHTTP");
        }
     }
    //the request could still be null if neither ActiveXObject
    //initializations succeeded
    if(request){
       initReq(reqType,url,asynch);
    }  else {
        alert("Your browser does not permit the use of all "+
        "of this application's features!");}
}
