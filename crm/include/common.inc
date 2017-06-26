<?php
ob_start();
#Timezone
date_default_timezone_set('Australia/Brisbane');
session_start();


########## DATABASE
$DB_SERVER			= "localhost";			
$DB_LOGIN			= "sungoldt_web";			
$DB_PASSWORD			= "@sunny12";		
$DB_NAME			= "sungoldt_database";			

########## CONFIG
$company			= "Sun Gold Tiles";			// ��ȣ
$mastermail			= "admin@sungoldtile.com";		// ���� ���ڿ���
$salt = "MNEchoTiles1";

########## ���丮 ����.
$HOME_DIR			= "http://www.sungoldtile.com";		// �������� ���ͳ� Ȩ �ּ�
$ABS_DIR			= $_SERVER{'DOCUMENT_ROOT'}."";		// �������� ���� Ȩ ���

## ȸ��α��� ����
$Sync_id			= $_SESSION["USER_ID"];
$Sync_name			= $_SESSION["USER_NAME"];
$Sync_alevel		= $_SESSION["A_LEVEL"];
$Sync_olevel		= $_SESSION["O_LEVEL"];
$Sync_payment		= $_SESSION["PAYMENT_METHOD"];

## �Խ��� ����� ��� ���� ����
if ($Sync_alevel == "A1") {
	$Sync_level = 100;
	$login_txt = "Administrator";
} else if ($Sync_alevel == "B1") {
	$Sync_level = 50;
	$login_txt = "Manager";
} else if ($Sync_alevel == "B2") {
	$Sync_level = 20;
	$login_txt = "Staff";
} else if ($Sync_alevel == "C1") {
	$Sync_level = 0;
	$login_txt = "Tiler";
}

$array_level[0][0] = 0;		$array_level[1][0] = "Tiler";
$array_level[0][1] = 20;	$array_level[1][1] = "Staff";
$array_level[0][2] = 50;	$array_level[1][2] = "Manager";
$array_level[0][3] = 100;	$array_level[1][3] = "Administrator";


## ���Ͼ��ε� ����, �뷮
$info_file_count = 50;
$info_file_quata = 10;

## ���丮 ����.
$upload_dir	= $HOME_DIR."/pupload";	// ���ε� ���ͳ� ���
$upload_root	= $ABS_DIR."/pupload";	// ���ε� ���� ���

## ���� �ð�
// ȣ��<->�̱����� �ð���
//$now_dateano = date("Y-m-d", time() + (3600 * 15));
//$now_datetimeano = date("Y-m-d H:i:s", time() + (3600 * 15));
//$now_date = date("d-m-Y", time() + (3600 * 15));
//$now_datetime = date("d-m-Y H:i:s", time() + (3600 * 15));
//$now_time = date("H:i:s", time() + (3600 * 15));
//$tomorrow_date = date("Y-m-d", strtotime("$now_date + 1 day"));


//ȣ��
$now_dateano = date("Y-m-d");
$now_datetimeano = date("Y-m-d H:i:s");
$now_date = date("d-m-Y");
$now_datetime = date("d-m-Y H:i:s");
$now_time = date("H:i:s");
$tomorrow_date = date("Y-m-d", strtotime("$now_dateano + 1 day"));

## �޴� ������ ��Ű�� ������ ����
if($_REQUEST["lmenu"]!="") {	
	setcookie("leftmenu", $_REQUEST["lmenu"], 0, "/");
}
ob_flush();
?>