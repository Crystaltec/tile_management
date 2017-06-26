<?
ob_start();
include "../include/common.inc";
include "../include/user_functions.inc";
include "../include/dbconn.inc";

$Query="
CREATE TABLE zb (
  boardid varchar(30) NOT NULL default '',
  boardname varchar(30) NOT NULL default '',
  readlevel varchar(10) NOT NULL default '0',
  writelevel varchar(10) NOT NULL default '0',
  signdate datetime NOT NULL default '0000-00-00 00:00:00',
  file_quota varchar(10) default NULL,
  file_count varchar(10) default NULL,
  title_img varchar(255) default NULL,
  line_img varchar(255) default NULL,
  link_style varchar(255) default NULL,
  width_sum varchar(4) default NULL,
  no_use int(1) NOT NULL default '0',
  no_wid char(3) default NULL,
  img_icon_use int(1) NOT NULL default '0',
  img_icon_wid char(3) default NULL,
  img_icon_size char(3) default NULL,
  category_use int(1) NOT NULL default '0',
  category_wid char(3) default NULL,
  subject_use int(1) NOT NULL default '0',
  subject_wid char(3) default NULL,
  name_use int(1) NOT NULL default '0',
  name_wid char(3) default NULL,
  name_title varchar(20) default NULL,
  date_use int(1) NOT NULL default '0',
  date_wid char(3) default NULL,
  file_use int(1) NOT NULL default '0',
  download_use int(1) NOT NULL default '0',
  comment_use int(1) NOT NULL default '0',
  reply_use int(1) NOT NULL default '0',
  secret_use int(1) NOT NULL default '0',
  link_use int(4) default NULL,
  html_use int(4) default NULL,
  vote_use int(1) NOT NULL default '0',
  vote_wid char(3) default NULL,
  hit_use int(1) NOT NULL default '0',
  hit_wid char(3) default NULL,
  search_use int(1) NOT NULL default '0',
  row_count int(4) default NULL,
  col_count int(4) default NULL,
  col_padding int(4) default NULL,
  category text,
  head_note text,
  tail_note text,
  head_php text,
  tail_php text,
  PRIMARY KEY  (boardid)
) 
";
@mysql_query("set names utf8"); 
mysql_query($Query) or die(mysql_error());

?>
<meta http-equiv="Content-Type" content="text/html"; charset="utf-8">
<?
echo "<script>alert('Board Tables maked!')</script>";
ob_flush();
?>