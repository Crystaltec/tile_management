<?php

$name= $_REQUEST["name"];
$email= $_REQUEST["email"];
$subject= $_REQUEST["subject"];
$message= $_REQUEST["message"];

$admin = "sun@sungoldtile.com";

$message_all = $name."\n\n".$message;

mail($admin , $subject, $message_all , "From:" . $email);

	echo "<script>alert('Thank you for your message!');location.href='http://www.sungoldtile.com/';</script>";


?>