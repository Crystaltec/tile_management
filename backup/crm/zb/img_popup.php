<?
ob_start();
include "include/board_config.inc";
?>

<!doctype html public "-//w3c//dtd html 4.0 transitional//en">
<html>
<head>
<title><?=$_GET["img_name"]?></title>
<meta name="generator" content="editplus">
</head>
<body leftmargin=0 topmargin=0>

<script>
var posX = 0;  
var posY = 0;  
var posX2 = 0;  
var posY2 = 0;  

function action_MouseDown(evt) {  
	if(navigator.appName == "Microsoft Internet Explorer"){
		posX = event.x;  
		posY = event.y;
	}else{
		posX = evt.pageX;  
		posY = evt.pageY;
	} 
	document.onmousemove = scrollPage; 
	document.getElementById('vimg').style.cursor="move";
	if(navigator.appName != "Microsoft Internet Explorer") evt.preventDefault();
}  

function scrollPage(evt) {
	if(navigator.appName == "Microsoft Internet Explorer"){
		posX2 = event.x;  
		posY2 = event.y;
	}else{
		posX2 = evt.pageX;  
		posY2 = evt.pageY;
	}
	
	pX = posX - posX2; 
	pY = posY - posY2; 
	window.scrollBy(pX,pY);
	
	if(navigator.appName == "Microsoft Internet Explorer"){
		posX = event.x;  
		posY = event.y;
	}else{
		posX = evt.pageX;  
		posY = evt.pageY;
	} 

	if(navigator.appName == "Microsoft Internet Explorer") event.returnValue=false;
} 

function action_MouseUp() { 
	document.onmousemove=null;
	document.getElementById('vimg').style.cursor="default";
}

document.onmousedown = action_MouseDown;
document.onmouseup = action_MouseUp;
</script>

<img id="vimg" src="<?=$upload_dir."/".$_GET["boardid"]."/".$_GET["img_extra"]?>" style="cursor:default;filter:progid:DXImageTransform.Microsoft.BasicImage(rotation=<?=$_GET["rv"]?>);">
</body>
</html>
<? ob_flush(); ?>