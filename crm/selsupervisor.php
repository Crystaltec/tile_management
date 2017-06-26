<? 
$Query ="select userid, username, phone from account where userid='".$_REQUEST["selsupervisor"]."'"; 
$cnn = mysql_query($Query) or exit(mysql_error()); 
while($rst = mysql_fetch_assoc($cnn)) { 
    $selsupervisor = array_merge($selsupervisor, array($rst)); 
} 
?> 

var ObjB = document.getElementsByName('B')[0].options; 
for(var i=ObjB.length;i>=0;i--) ObjB[i] = null; 
ObjB[0] = new Option('--선택--',''); 
<?for($i=0;$i<count($selsupervisor);$i++){?> 
    //ObjB[ObjB.length] = new Option('<?=$selsupervisor[$i]["fieldB"]?>','<?=$selsupervisor[$i]["fieldB"]?>'); 
<?}?> 
