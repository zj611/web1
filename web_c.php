<?php
error_reporting(0);//加上error_reporting(0);就不会弹出警告了  
require_once("data/db_info.php");

$s = mysqli_connect($SERV,$USER,$PASS) or die("connect failed");
mysqli_select_db($s,$DBNM);


function get_onlineip() {
    static $realip;
	if (isset($_SERVER)){
		if (isset($_SERVER["HTTP_X_FORWARDED_FOR"])){
			$realip = $_SERVER["HTTP_X_FORWARDED_FOR"];
		} else if (isset($_SERVER["HTTP_CLIENT_IP"])) {
			$realip = $_SERVER["HTTP_CLIENT_IP"];
		} else {
			$realip = $_SERVER["REMOTE_ADDR"];
		}
	} else {
		if (getenv("HTTP_X_FORWARDED_FOR")){
			$realip = getenv("HTTP_X_FORWARDED_FOR");
		} else if (getenv("HTTP_CLIENT_IP")) {
			$realip = getenv("HTTP_CLIENT_IP");
		} else {
			$realip = getenv("REMOTE_ADDR");
		}
	}
	return $realip;
}

$gu_d = $_GET["gu"];
//$gu_d = "1";

if(preg_match("/[^0-9]/",$gu_d))//搜索 subject 与 pattern 给定的正则表达式的一个匹配
{
    print <<<eot1
    含有非法字符<BR>
    <A HREF="web_top.php"> 点击此处返回留言主题列表</A>
eot1;
}

elseif(preg_match("/[0-9]/",$gu_d))
{   
    $na_d = htmlspecialchars($_GET["na"]);
    $me_d = htmlspecialchars($_GET["me"]);

    // $na_d = "na";
    // $me_d = "me";

  
    $ip =   get_onlineip();
  

    $re = mysqli_query($s,"SELECT title FROM theme_web1 WHERE tid=$gu_d");
 
    $result = mysqli_fetch_array($re);

    $title_com="   ".$result[0];
    print <<<eot2
    <HTML>
        <HEAD>
            <META http-equiv="Content-Type" content="text/html;charset = utf-8">
            <TITLE>心声留言板 $title_com 主题</TITLE>
            </HEAD>
            <BODY BGCOLOR="peachpuff ">
                <FONT SIZE="7" COLOR="indigo">
                    $title_com 主题
                </FONT>
                <BR><BR>
                <FONT SIZE="5">
                $title_com 的所有留言
                </FONT>
                <BR>
eot2;


    if($na_d<>""){//<>不等于
    
        mysqli_query($s,"INSERT INTO comments_web1 (nam,mess,ctime,tid,cip) VALUES ('$na_d','$me_d',now(),$gu_d,'$ip')");

    }
    print "<HR>";

    $re=mysqli_query($s,"SELECT * FROM comments_web1 WHERE tid=$gu_d ORDER BY ctime");
    if (!$re) {
        printf("Error: %s\n", mysqli_error($s));
        exit();
       }

  

    
    $i=1;
    
    while($result=mysqli_fetch_array($re))
    {
        //echo "($i)<U>$result[1]</U>:$result[3] ip地址：$result[5]<br>";//style='color:#ff0000; 
        //echo "<p style='font-size:16px;'>ip地址：$result[5] </p>";
        //$text = nl2br($result[2]);
        print <<<eot00
        <FONT SIZE="3"  COLOR="dimgray">
        ($i)<U>$result[1]</U>:$result[3]($result[0]) ip地址：$result[5]<br>
        </FONT>

eot00;
        echo  nl2br($result[2]);
        //echo  "<p style='font-size:16px;'> $text</p>";
        echo "<BR><BR>";
            $i++;
    }
    mysqli_free_result($s);
    mysqli_close($s);

    print <<<eot3
        <HR>
        <FONT SIZE="5">
            请在此回复主题
        </FONT>
        <FONT SIZE="2">
        (必须输入姓名，否则无法提交。。实名制上网，哈哈)
        </FONT>
        <FORM METHOD="GET" ACTION="web_c.php">
            姓名 <INPUT TYPE="text" NAME="na"><BR>
            留言内容<BR>
            <TEXTAREA NAME="me" ROWS="10" COLS="90"></TEXTAREA>
            <BR>
            <INPUT TYPE="hidden" NAME="gu" VALUE = $gu_d>
            <INPUT TYPE="submit" VALUE="提交">
        </FORM>
        <A HREF="web_top.php">返回到留言主题列表</A><br><br>
        
        
        <IMG SRC="pic/lizhi8.png">
        
        <HR>
        </BODY>
        </HTML>
eot3;
} 
else{
    print "请选择一个留言主题。<BR>";
    print "<A HREF='web_top.php'>点击此处返回留言主题列表</A>";
}


