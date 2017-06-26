var request=null;

function getInfo(obj) {
    if (obj == null ) { return; }
	if (obj.value=="")	{
		var f = document.frm02;
		var f01= document.frm01;
		f.deliverto_project.value="";
		f.deliverto_name.value="";
		f.deliverto_phone_number.value="";
		f.deliverto_address.value="";
		f.deliverto_suburb.value="";
		f.deliverto_state.value="";
		f.deliverto_postcode.value="";
		
		return;
	}
    var url = "select_info.php?id="+ encodeURIComponent(obj.value);
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
				
					var f = document.orderform;
					f.deliverto_project.value=objt.deliverto_project;
					f.deliverto_name.value=objt.deliverto_name;
					f.deliverto_phone_number.value=objt.phone;
					f.deliverto_address.value=objt.address;
					f.deliverto_suburb.value=objt.suburb;
					f.deliverto_state.value=objt.state;
					f.deliverto_postcode.value=objt.postcode;
					
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
