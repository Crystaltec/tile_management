var formObj = null;
var formObjTyp = "";
var request=null;
//input field's event handlers
window.onload=function(){
    var srch_usergroup = document.getElementById("srch_usergroup");
    if(srch_usergroup != null){
        srch_usergroup.onchange=function(){getSelectInfo(this);}; 
	}  
}

function generateList(obj){
    if (obj == null ) { return; }
    if(obj.checked) {
        formObj=obj;
        formObjTyp =formObj.tagName.toLowerCase();
        var url = "http://www.parkerriver.com/s/selectl?countryType="+
                  encodeURIComponent(obj.value);
        httpRequest("GET",url,true);
    }
}

function getSelectInfo(selectObj){
    if (selectObj == null || selectObj.value == "") {
		var dispSelect = document.getElementById("dispSelect");
        reset(dispSelect);
		return; 
	}
    formObj=selectObj;
    formObjTyp =formObj.tagName.toLowerCase();
    var optsArray = formObj.options;
    var selectedArray = new Array();
    var val = "";
    for(var i=0,j=0; i < optsArray.length; i++){
        if(optsArray[i].selected) {
            selectedArray[j]=optsArray[i].value;
            j++;
        }

    }
    for(var k = 0; k < selectedArray.length; k++){
        if(k !=selectedArray.length-1 ) { val +=selectedArray[k]+",";}
        else {val +=selectedArray[k]; }
    }
    var url = "http://order.sushione.com/select_custinfo03.php?objtype="+
              encodeURIComponent(formObjTyp)+"&val="+ encodeURIComponent(val);
	//alert(url);
    httpRequest("GET",url,true);
}
//event handler for XMLHttpRequest
function handleResponse(){
    try{
        if(request.readyState == 4){
            if(request.status == 200){
                if(formObjTyp.length > 0 && formObjTyp == "select")   {    //working with existing radio button
                    var resp =  request.responseXML;					
                    if (resp != null){
						//alert(resp.xml);
						var sel = document.createElement("select");
                        sel.setAttribute("name","srch_userid");
                        createOptions(sel,resp);
                        var dispSelect = document.getElementById("dispSelect");
                        reset(dispSelect);
                        dispSelect.appendChild(sel);
                    }
                }
            } else {
                //request.status is 503  if the application isn't available; 500 if the application has a bug
                alert(
                        "A problem occurred with communicating between the XMLHttpRequest object and the server program.");
            }
        }//end outer if
    } catch (err)   {
        alert("It does not appear that the server is available for this application. Please"+
              " try again very soon. \nError: "+err.message);

    }
}

function createOptions(sel,obj) {
    //_options is an array of strings that represent the values of
    //a select list, as in each option of the list. sel is the select object
	var root = obj.documentElement;
	var nds;
	//alert("1");
	if(root.hasChildNodes()) {
		nds = root.childNodes;
		//alert(nds.length);
		opt = document.createElement("option");
		opt.setAttribute("value","");
		opt.appendChild(document.createTextNode("All"));	
		sel.appendChild(opt);
		for(var i=0; i < nds.length; i++) {
			opt = document.createElement("option");
			opt.setAttribute("value",nds[i].childNodes.item(0).firstChild.nodeValue);
			opt.appendChild(document.createTextNode(nds[i].childNodes.item(1).firstChild.nodeValue));			
			sel.appendChild(opt);
		}
	}	 
}
//remove any existing children from an Element object
function reset(elObject){
    if(elObject != null && elObject.hasChildNodes()){
        for(var i = 0; i < elObject.childNodes.length; i++){
            elObject.removeChild(elObject.firstChild);
        }
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
