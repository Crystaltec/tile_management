<?

ob_start();
include "../include/common.inc";
include "../include/dbconn.inc";
include "../include/user_functions.inc";
include "include/board_config.inc";

if($_GET["cmd"]=="exec"){
	//zb1.0 admin 에서 빠지는 필드(삭제해도 되지만 복구할 경우를 대비해서 삭제하지 않음.)
	/*
	$Query = "ALTER TABLE zb DROP COLUMN admin_mail";		@mysql_query($Query);
	$Query = "ALTER TABLE zb DROP COLUMN boardpasswd";	@mysql_query($Query);
	$Query = "ALTER TABLE zb DROP COLUMN lang";				@mysql_query($Query);
	$Query = "ALTER TABLE zb DROP COLUMN title_img_use";		@mysql_query($Query);
	$Query = "ALTER TABLE zb DROP COLUMN board_align";		@mysql_query($Query);
	$Query = "ALTER TABLE zb DROP COLUMN subject_len";		@mysql_query($Query);
	$Query = "ALTER TABLE zb DROP COLUMN name_len";			@mysql_query($Query);
	$Query = "ALTER TABLE zb DROP COLUMN base_bg";			@mysql_query($Query);
	$Query = "ALTER TABLE zb DROP COLUMN base_fon";			@mysql_query($Query);
	$Query = "ALTER TABLE zb DROP COLUMN title_bg";			@mysql_query($Query);
	$Query = "ALTER TABLE zb DROP COLUMN title_fon";			@mysql_query($Query);
	$Query = "ALTER TABLE zb DROP COLUMN list_bg";				@mysql_query($Query);
	$Query = "ALTER TABLE zb DROP COLUMN list_fon";			@mysql_query($Query);
	$Query = "ALTER TABLE zb DROP COLUMN point_bg";			@mysql_query($Query);
	$Query = "ALTER TABLE zb DROP COLUMN point_fon";			@mysql_query($Query);
	*/

	//zb2.0 admin 에서 추가되는 필드
	$Query = "ALTER TABLE zb ADD COLUMN link_style varchar(255) default NULL AFTER line_img";					@mysql_query($Query);
	$Query = "ALTER TABLE zb ADD COLUMN category_use int(1) NOT NULL default '0' AFTER img_icon_size";	@mysql_query($Query);
	$Query = "ALTER TABLE zb ADD COLUMN category_wid char(3) default NULL AFTER category_use";				@mysql_query($Query);
	$Query = "ALTER TABLE zb ADD COLUMN name_title varchar(20) default NULL AFTER name_wid";					@mysql_query($Query);
	$Query = "ALTER TABLE zb ADD COLUMN download_use int(1) NOT NULL default '0' AFTER file_use";			@mysql_query($Query);
	$Query = "ALTER TABLE zb ADD COLUMN reply_use int(1) NOT NULL default '0' AFTER comment_use";			@mysql_query($Query);
	$Query = "ALTER TABLE zb ADD COLUMN link_use int(1) NOT NULL default '0' AFTER secret_use";				@mysql_query($Query);
	$Query = "ALTER TABLE zb ADD COLUMN html_use int(1) NOT NULL default '0' AFTER link_use";					@mysql_query($Query);
	$Query = "ALTER TABLE zb ADD COLUMN search_use int(1) NOT NULL default '0' AFTER hit_wid";				@mysql_query($Query);
	$Query = "ALTER TABLE zb ADD COLUMN col_count int(4) default NULL AFTER row_count";							@mysql_query($Query);
	$Query = "ALTER TABLE zb ADD COLUMN col_padding int(4) default NULL AFTER col_count";						@mysql_query($Query);
	$Query = "ALTER TABLE zb ADD COLUMN category text AFTER col_padding";											@mysql_query($Query);

	//초기버전(비밀글,링크 기능 없는 버전 사용자)
	//$Query = "ALTER TABLE zb ADD COLUMN secret_use int(1) NOT NULL default '0'";										@mysql_query($Query);
	//$Query = "ALTER TABLE zb ADD COLUMN link_use int(1) NOT NULL default '0'";											@mysql_query($Query);

	$Query  = "select boardid from zb";
	$cnn = mysql_query($Query) or exit(mysql_error());
	while($rst = mysql_fetch_assoc($cnn)) {	
		//zb2.0 보드별 추가되는 필드
		$Query = "ALTER TABLE ".$rst["boardid"]." ADD COLUMN category varchar(255) NOT NULL default '' AFTER boarder_passwd";
		@mysql_query($Query);
		//초기버전
		$Query = "ALTER TABLE ".$rst["boardid"]." ADD COLUMN secret_passwd varchar(255) NOT NULL default '' AFTER boarder_passwd";
		@mysql_query($Query);
		//업로드 디렉토리가 없으면 생성
		if(!is_dir($upload_root)) @mkdir($upload_root,0755);
		if(!is_dir($upload_root."/".$rst["boardid"])) @mkdir($upload_root."/".$rst["boardid"],0755);
		@system("mv ".$upload_root."/thumb_".$rst["boardid"]."_* ".$upload_root."/".$rst["boardid"]);
		@system("mv ".$upload_root."/".$rst["boardid"]."_* ".$upload_root."/".$rst["boardid"]);
	}
	echo "<script>alert('완료되었습니다.')</script>";
	echo "<meta http-equiv='Refresh' content='0; URL=zb1to2.php'>";
	exit;
}
?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html"; charset="euc-kr">
</head>

<body  LEFTMARGIN="0" TOPMARGIN="0" MARGINWIDTH="0" MARGINHEIGHT="0" style="font:9pt 굴림;">
<center>
<br><br><br>
zb2.0 관리모드와 기존에 등록되어있는 게시판들에 추가될 필드들을 등록합니다.<br>
2.0에서 사용되지 않는 필드들은 삭제해도 되지만 복구할 경우를 대비해서 삭제하지는 않습니다.<br>
업그레이드 완료후에 수동으로 지우거나, 지금 삭제하려면 파일을 에디터로 열어 주석을 제거하세요.<br>
<br>
"upload" 디렉토리의 파일들을 "upload/각게시판ID" 디렉토리로 이동합니다.<br>
"upload/각게시판ID" 디렉토리는 자동생성됩니다.<br>
<br>
본 파일을 한번 실행 한 후 "upload" 디렉토리를 제외한 나머지 파일들을 2.0파일로 대체하면 업그레이드가 완료됩니다.<br>
업그레이드 완료후에는 게시판 관리에서 새로운 환경에 맞게 각 게시판 설정을 다시해야합니다.<br>
<br>
<input type="button" value="ZB2.0 필드추가 및 업로드파일 이동" onClick="location.href='<?=$_SERVER['PHP_SELF']?>?cmd=exec';">
</center>
</body>
</html>
<? ob_flush(); ?>