<?php
error_reporting(0);//加上error_reporting(0);就不会弹出警告了  
require_once("data/db_info.php");

$s = mysqli_connect($SERV,$USER,$PASS) or die("connect failed");
mysqli_select_db($s,$DBNM);

print <<<eot1
<HTML>
    <HEAD>
        <META http-equiv="Content-Type" content="text/html;charset = utf-8">
        <TITLE>技术留言板的搜索界面</TITLE>
    </HEAD>

    <BODY BGCOLOR = "peachpuff">
        <HR>
        <FONT SIZE="5">
            （以下为检索结果）
        </FONT>
        <BR>
eot1;


    $se_d = htmlspecialchars($_GET["se"]);
    if($se_d<>""){
        $str=<<<eot2
        SELECT comments_web1.cid,comments_web1.nam,comments_web1.mess,theme_web1.title,comments_web1.ctime
        FROM comments_web1
        JOIN theme_web1
        ON
        comments_web1.tid = theme_web1.tid
        WHERE comments_web1.mess  LIKE "%$se_d%"
eot2;



        $re=mysqli_query($s,$str);
        while($result=mysqli_fetch_array($re)){
            print "主  题: $result[3]<BR>";
            print "留言人: <U>$result[1] ($result[4])<BR>";
            print "$result[2]";
            print "<BR><BR>";
        }

    }


   
    mysqli_close($s);

    print <<<eot3
    <HR>
        输入留言中包含字符串！
    <BR>
        <FORM METHOD="GET" ACTION="web_s.php">
        检索字符串
        <INPUT TYPE="text" NAME ="se">
        <BR>
        <INPUT TYPE ="submit" VALUE="检索">
        </FORM>
        <BR>
        <A HREF="web_top.php">
        回到留言主题列表
        </A>
        </BODY>
        </HTML>
eot3;


?>
