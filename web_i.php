<?php

require_once("data/db_info.php");

$s = mysqli_connect($SERV,$USER,$PASS) or die("connect failed");
mysqli_select_db($s,$DBNM);

mysqli_query($s,"DELETE FROM theme_web1");
mysqli_query($s,"DELETE FROM comments_web1");

mysqli_query($s,"ALTER TABLE  theme_web1 AUTO_INCREMENT=0");
mysqli_query($s,"ALTER TABLE  comments_web1 AUTO_INCREMENT=0");

print "成功初始化了SQL技术留言版的相关表";

mysqli_close($s);

?>

