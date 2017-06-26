var request=null;

function avaUserid(obj) {
	var f = document.frm1;
	if(f.userid.value == "") {
		alert("Please, Insert User ID");
		f.userid.focus();
		return;
	}
    var url = "avauserid.php?userid="+ encodeURIComponent(f.userid.value);
	
    httpRequest("GET",url,true);

}

//event handler for XMLHttpRequest
function handleResponse(){
    try{
        if(request.readyState == 4){
            if(request.status == 200){
                var resp = request.responseText;
                if(resp != null) {
					//alert(resp);
                    if(resp == "0") {
						alert("This User Id is available!");
					}else {
						alert("This User Id is not available!");
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
