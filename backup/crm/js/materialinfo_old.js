var request=null;
var _index=null;
function getInfo(obj,fromindex) {
	_index = fromindex;
	if (obj == null ) { return; }
	if (obj.value=="")	{
		var f = document.orderform;
		
		if (_index == 0)
		{
			f.material_id0.value="";
			f.material_description0.value="";
			f.unit_id0.value="";
			f.material_code_number0.value="";
			f.material_color0.value="";
			f.material_size0.value="";
			f.material_factory_number0.value="";
		}
		else if (_index==1)
		{
			f.material_id1.value="";
			f.material_description1.value="";
			f.unit_id1.value="";
			f.material_code_number1.value="";
			f.material_color1.value="";
			f.material_size1.value="";
			f.material_factory_number1.value="";
		}
		else if (_index==2)
		{
			f.material_id2.value="";
			f.material_description2.value="";
			f.unit_id2.value="";
			f.material_code_number2.value="";
			f.material_color2.value="";
			f.material_size2.value="";
			f.material_factory_number2.value="";
		}
		else if (_index==3)
		{
			f.material_id3.value="";
			f.material_description3.value="";
			f.unit_id3.value="";
			f.material_code_number3.value="";
			f.material_color3.value="";
			f.material_size3.value="";
			f.material_factory_number3.value="";
		}
		else if (_index==4)
		{
			f.material_id4.value="";
			f.material_description4.value="";
			f.unit_id4.value="";
			f.material_code_number4.value="";
			f.material_color4.value="";
			f.material_size4.value="";
			f.material_factory_number4.value="";
		}
		else if (_index==5)
		{
			f.material_id5.value="";
			f.material_description5.value="";
			f.unit_id5.value="";
			f.material_code_number5.value="";
			f.material_color5.value="";
			f.material_size5.value="";
			f.material_factory_number5.value="";
		}
		else if (_index==6)
		{
			f.material_id6.value="";
			f.material_description6.value="";
			f.unit_id6.value="";
			f.material_code_number6.value="";
			f.material_color6.value="";
			f.material_size6.value="";
			f.material_factory_number6.value="";
		}
		else if (_index==7)
		{
			f.material_id7.value="";
			f.material_description7.value="";
			f.unit_id7.value="";
			f.material_code_number7.value="";
			f.material_color7.value="";
			f.material_size7.value="";
			f.material_factory_number7.value="";
		}
		else if (_index==8)
		{
			f.material_id8.value="";
			f.material_description8.value="";
			f.unit_id8.value="";
			f.material_code_number8.value="";
			f.material_color8.value="";
			f.material_size8.value="";
			f.material_factory_number8.value="";
		}
		else if (_index==9)
		{
			f.material_id9.value="";
			f.material_description9.value="";
			f.unit_id9.value="";
			f.material_code_number9.value="";
			f.material_color9.value="";
			f.material_size9.value="";
			f.material_factory_number9.value="";
		}
					
		return false;
	}
	
    var url = "material_info.php?id="+ encodeURIComponent(obj.value)+"&index="+encodeURIComponent(_index);
   
	$('#processing').fadeIn(500);
	httpRequest("GET",url,true);
	$('#processing').fadeOut(800);
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
					if (_index == 0)
					{
						f.material_id0.value=objt.material_id0;
						f.material_description0.value=objt.material_name0;
						f.unit_id0.value=objt.unit_id0;
						f.material_code_number0.value=objt.material_code_number0;
						f.material_color0.value=objt.material_color0;
						f.material_size0.value=objt.material_size0;
						f.material_factory_number0.value=objt.material_factory_number0;
					}
					else if (_index==1)
					{
						f.material_id1.value=objt.material_id1;
						f.material_description1.value=objt.material_name1;
						f.unit_id1.value=objt.unit_id1;
						f.material_code_number1.value=objt.material_code_number1;
						f.material_color1.value=objt.material_color1;
						f.material_size1.value=objt.material_size1;
						f.material_factory_number1.value=objt.material_factory_number1;
					}
					else if (_index==2)
					{
						f.material_id2.value=objt.material_id2;
						f.material_description2.value=objt.material_name2;
						f.unit_id2.value=objt.unit_id2;
						f.material_code_number2.value=objt.material_code_number2;
						f.material_color2.value=objt.material_color2;
						f.material_size2.value=objt.material_size2;
						f.material_factory_number2.value=objt.material_factory_number2;
					}
					else if (_index==3)
					{
						f.material_id3.value=objt.material_id3;
						f.material_description3.value=objt.material_name3;
						f.unit_id3.value=objt.unit_id3;
						f.material_code_number3.value=objt.material_code_number3;
						f.material_color3.value=objt.material_color3;
						f.material_size3.value=objt.material_size3;
						f.material_factory_number3.value=objt.material_factory_number3;
					}
					else if (_index==4)
					{
						f.material_id4.value=objt.material_id4;
						f.material_description4.value=objt.material_name4;
						f.unit_id4.value=objt.unit_id4;
						f.material_code_number4.value=objt.material_code_number4;
						f.material_color4.value=objt.material_color4;
						f.material_size4.value=objt.material_size4;
						f.material_factory_number4.value=objt.material_factory_number4;
					}
					else if (_index==5)
					{
						f.material_id5.value=objt.material_id5;
						f.material_description5.value=objt.material_name5;
						f.unit_id5.value=objt.unit_id5;
						f.material_code_number5.value=objt.material_code_number5;
						f.material_color5.value=objt.material_color5;
						f.material_size5.value=objt.material_size5;
						f.material_factory_number5.value=objt.material_factory_number5;
					}
					else if (_index==6)
					{
						f.material_id6.value=objt.material_id6;
						f.material_description6.value=objt.material_name6;
						f.unit_id6.value=objt.unit_id6;
						f.material_code_number6.value=objt.material_code_number6;
						f.material_color6.value=objt.material_color6;
						f.material_size6.value=objt.material_size6;
						f.material_factory_number6.value=objt.material_factory_number6;
					}
					else if (_index==7)
					{
						f.material_id7.value=objt.material_id7;
						f.material_description7.value=objt.material_name7;
						f.unit_id7.value=objt.unit_id7;
						f.material_code_number7.value=objt.material_code_number7;
						f.material_color7.value=objt.material_color7;
						f.material_size7.value=objt.material_size7;
						f.material_factory_number7.value=objt.material_factory_number7;
					}
					else if (_index==8)
					{
						f.material_id8.value=objt.material_id8;
						f.material_description8.value=objt.material_name8;
						f.unit_id8.value=objt.unit_id8;
						f.material_code_number8.value=objt.material_code_number8;
						f.material_color8.value=objt.material_color8;
						f.material_size8.value=objt.material_size8;
						f.material_factory_number8.value=objt.material_factory_number8;
					}
					else if (_index==9)
					{
						f.material_id9.value=objt.material_id9;
						f.material_description9.value=objt.material_name9;
						f.unit_id9.value=objt.unit_id9;
						f.material_code_number9.value=objt.material_code_number9;
						f.material_color9.value=objt.material_color9;
						f.material_size9.value=objt.material_size9;
						f.material_factory_number9.value=objt.material_factory_number9;
					}
				}
            } else {
                //request.status is 503  if the application isn't available; 
                //500 if the application has a bug
                alert("A problem occurred with communicating between the XMLHttpRequest object and the server program.");
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
