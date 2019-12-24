<?php
error_reporting(0);//加上error_reporting(0);就不会弹出警告了  
require_once("data/db_info.php");
$s = mysqli_connect($SERV,$USER,$PASS) or die("connect failed");
mysqli_select_db($s,$DBNM);
//<br/>是换行的意思，<hr/>被水平线分隔 另外这两种html的属于标签

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


    include_once 'weather.php';
    print <<<eot1
<HTML>
    <HEAD>
        <META http-equiv="Content-Type" content="text/html;charset = utf-8">
        <TITLE>心声社区留言版</TITLE>
    </HEAD>

    <BODY BGCOLOR = "peachpuff">
        <IMG SRC="pic/88.png" alt="hhh" width="537px"  height="101px">
        <BR>
        <FONT SIZE="7" COLOR="orangered">
        &emsp; &ensp;<strong>心声社区留言版</strong>
        </FONT>
        <BR>

        <FONT SIZE="2" COLOR="dimgray">
        【公告】12.21无法访问原因：运维人员去找女朋友去了[技术开发：zj]<BR>
        【公告】12.22无法访问原因：这坑爹的域名认证<BR>
        </FONT>
        <FONT SIZE="2" COLOR="red">
        【公告】请大家放心使用，欢迎点击浏览器右上(下)角，保存网页为标签<br>
        </FONT>
        <HR>

        <FONT size="5">
            (天气指南)<br>
        </FONT>  <FONT  SIZE="3" ><strong> $today_weather</strong>  </FONT> 
        
        <FONT  SIZE="2" >   &ensp;&ensp;&ensp; $tomorrow_weather<br>     </FONT>
        <FONT  SIZE="2" COLOR="midnightblue"> 
            
        「1」$data_index_0<br>
        「2」$data_index_1<br>
        「3」$data_index_2<br>
        「4」$data_index_3<br>
        「5」$data_index_4<br>        
        </FONT>

        <HR>
        <FONT SIZE="5">
            (主题列表) 
        </FONT>
        <FONT FONT SIZE="3">
           点击下面的主题进入
        </FONT>
        
        <BR>
eot1;
     $ip =   get_onlineip();
     

    $su_d = htmlspecialchars($_GET["su"]);
    if($su_d<>""){
        mysqli_query($s,"INSERT INTO theme_web1 (title,ttime,tip) VALUES ('$su_d',now(),'$ip')");
        //header("Location: comment.php?gu=$su_d");
    }
    print <<<eot2222
    <FONT size="3">
    <BR>
        <strong>【深度阅览与行业观点】</strong>
    </FONT>
    <BR>
eot2222;
//--------------------------------------------
    $re = mysqli_query($s,"SELECT * FROM theme_web1 where degree=1 order by ttime desc;");
    $i=1;
    while($result=mysqli_fetch_array($re))
    {
    $uuu = "[$i]  $result[1]";
    print <<<eot20
   
        <A HREF="web_c.php?gu=$result[0]">$uuu</A> 
        <BR>
        创建时间：$result[2]($result[0])<BR><BR>
        
eot20;
    $i++;
    }

    print <<<eot2222
    <FONT size="3">
        <strong>【灌水区－合理表达尊重观点】</strong>
    </FONT>
    <BR>
eot2222;

//--------------------------------------------
    $re = mysqli_query($s,"SELECT * FROM theme_web1 where degree=0 order by ttime desc;");
    $i=1;
    while($result=mysqli_fetch_array($re))
    {
    $uuu = "[$i]  $result[1]";
    print <<<eot21
   
        <A HREF="web_c.php?gu=$result[0]">$uuu</A> 
        <BR>
        创建时间：$result[2]($result[0])<BR><BR>
        
eot21;
    $i++;
    }
//--------------------------------------------


    mysqli_free_result($s);
    mysqli_close($s);
    $weekarray=array(日,一,二,三,四,五,六); //先定义一个数组 
    $xingqi = "星期".$weekarray[date("w")];
    $today_time0 = date("Y/m/d");
    $today_time = date("Y/m/d   ") . $xingqi;
  
    print <<<eot3
            <HR>
            <FONT size="5">
                (创建新的留言)
            </FONT>
            <BR>
            请在此处创建新的留言主题
            <BR>
            <FORM METHOD="GET" ACTION="web_top.php">
                新的留言主题的标题
                <INPUT TYPE='TEXT' NAME='su' SIZE='45'>
                <BR>
                <INPUT TYPE="submit" VALUE="创建">
                
            </FORM>
            <HR>
            <FONT SIZE="5">
                (留言搜索)
            </FONT>

            <A HREF="web_s.php">需要检索留言时请点击进入留言搜索界面</a>
            <HR>

            <FONT SIZE="5">
            (今日励志语录)<br> 
            </FONT>

            <FONT SIZE="3">
            $today_time
            </FONT>

            <FONT SIZE="3" COLOR="darkblue" >
            
            <strong>【不要生气要争气,不要看破要突破,不要嫉妒要欣赏,不要拖延要积极,不要心动要行动。】</strong>
            <br> <br> <br> 
            </FONT>

            

            <HR>
            
            -------------------------------<br>
            <FONT SIZE="2" COLOR="dimgray">
            &ensp; &ensp; &ensp; &ensp; 开发日志<br>            
            2019/12/15：开发起点<br>
            2019/12/19：首次上线<br>
            <FONT SIZE="2" COLOR="red">
            2019/12/20：添加天气指南（百度API提供服务）<br>
            2019/12/24：设置内容分区<br>
            </FONT>
            开发截止：$today_time0 <br>
            后续计划：增加内容分区优先级显示，密码登录，balabalabala......<br>
            当前允许用户：all<br>
            技术框架：ubuntu 16.04: apache2 + PHP + MySQL<br>
            ps:花了12块钱才买到的http域名 <BR>
            0酬金诚招：有兴趣的小伙伴参与运营维护<br>
            </FONT>
            <FONT SIZE="2" COLOR="red">
            当你有一天进不来这里的时候，那真是一个悲伤的故事......<br>
            那一定是我不在实验室维护服务器，或者没有足够多的精力来维护，或者我不再青春......<br>
            </FONT>
            -------------------------------<br>
            <br> 
            <IMG SRC="pic/lizhi3.png">

            </BODY>
            </HTML>
             
eot3;

?>


