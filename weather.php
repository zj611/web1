<?php 
    header("Content-type:text/html;charset=utf-8");
    error_reporting(0);//加上error_reporting(0);就不会弹出警告了  

    $location = "北京";//地区
    //注意：现在百度新注册的用户已经不再提供天气服务了，这个ak码是网上找的，可靠性不能保证
    $ak = "3p49MVra6urFRGOT9s8UBWr2"; // 浏览器  bMTlMK4ESu0q5gXfsfXxI8fUjnVABrgA  //服务端 Fki1pSETF3GGcLRwluAULTKI8n87FWXG
    $weatherURL = "http://api.map.baidu.com/telematics/v3/weather?location=$location&output=json&ak=$ak";
    
    //$weatherURL = "http://api.map.baidu.com/reverse_geocoding/v3/?ak=$ak&output=json&coordtype=wgs84ll&location=31.225696,121.49884";//
    $ch = curl_init($weatherURL);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // 获取数据返回
    curl_setopt($ch, CURLOPT_BINARYTRANSFER, true); // 在启用 CURLOPT_RETURNTRANSFER 时候将获取数据返回
    $re = curl_exec($ch);
    curl_close($ch);
    $result = json_decode($re,true);
    //print_r($result);
 

    if(!empty($result)&&$result['error']==0)
    {
		$weather=$result['results'][0];
 
		//白天还是黑夜
		$now = date("H:i");
		$timeName = (strtotime($now) > strtotime('18:00')) ? "night" : "day";		
		//城市
        $data['city'] = $weather['currentCity'];
        
        
        // 今天的天气			
		//气温
		$tempArr = explode('：', $weather['weather_data'][0]['date'],2);
	    $data['temp'] = trim($tempArr[1],')'); 
	    //PM2.5
		$data['pm25'] = $weather['pm25'];
		//风向
		$data['wd']	  = $weather['weather_data'][0]['wind'];
		//天气状况：晴
		$data['weather'] = $weather['weather_data'][0]['weather'];
		//一天气温	
        $data['temp1']	= $weather['weather_data'][0]['temperature'];
        

        // 明天的天气			
		//气温
		$tempArr = explode('：', $weather['weather_data'][1]['date'],2);
	    $data_tomor['temp'] = trim($tempArr[1],')'); 
		//风向
		$data_tomor['wd']	  = $weather['weather_data'][1]['wind'];
		//天气状况：晴
		$data_tomor['weather'] = $weather['weather_data'][1]['weather'];
		//一天气温	
        $data_tomor['temp1']	= $weather['weather_data'][1]['temperature'];
        


		// 各种指数
        $data_index_0 = $weather['index'][0]['des'].$weather['index'][0]['tipt'].'：'.$weather['index'][0]['zs'];
        $data_index_1 = $weather['index'][1]['des'].$weather['index'][1]['tipt'].'：'.$weather['index'][1]['zs'];
        $data_index_2 = $weather['index'][2]['des'].$weather['index'][2]['tipt'].'：'.$weather['index'][2]['zs'];
        $data_index_3 = $weather['index'][3]['des'].$weather['index'][3]['tipt'].'：'.$weather['index'][3]['zs'];
        $data_index_4 = $weather['index'][4]['des'].$weather['index'][4]['tipt'].'：'.$weather['index'][4]['zs'];


        $weekarray=array(日,一,二,三,四,五,六); //先定义一个数组 
        $xingqi = "星期".$weekarray[date("w")];
        $today_time0 = date("Y/m/d");
        $today_time = date("Y/m/d   ").$now."&ensp;".$xingqi;

        $today_weather = $today_time."&ensp;".$data['city']."&ensp;".$data['weather']."&ensp;".$data['temp1']."&ensp;".$data['wd'];
        $tomorrow_weather = "明天天气预告： ".$data_tomor['weather']."&ensp;".$data_tomor['temp1']."&ensp;".$data_tomor['wd'];
      
        // echo $today_weather."<br>"; 
        // echo $tomorrow_weather."<br>"; 
      
        // echo $data_index_0."<br>";
        // echo $data_index_1."<br>";
        // echo $data_index_2."<br>";
        // echo $data_index_3."<br>";
        // echo $data_index_4."<br><br>";
   

 
	}else{
		echo("获取不到天气情况！");
	}

?>
    <!--    
{"error":0,"status":"success","date":"2019-12-20","results":
    [
        {"currentCity":"北京",
         "pm25":"33",
        "index":
            [{"des":"111","tipt":"穿衣指数","title":"穿衣","zs":"寒冷"},
            {"des":"适宜洗车","tipt":"洗车指数","title":"洗车","zs":"适宜"},
            {"des":"天冷免感冒","tipt":"感冒指数","title":"感冒","zs":"易发"},
            {"des":"户外运动时","tipt":"运动指数","title":"运动","zs":"较不宜"},
            {"des":"防晒护肤品","tipt":"紫外线强度指数","title":"紫外线强度","zs":"弱"}],
        "weatherdata":[{"date":"周五 12月20日 (实时：-1℃)",
                    "dayPictureUrl":"http.png",
                    "nightPictureUrl":"http://api.ma/qing.png",
                    "weather":"晴",
                    "wind":"西南风微风",
                    "temperature":"2 ~ -7℃"},

                {"date":"周六",
                "dayPictureUrl":"htduoyun.png",
                "nightPictureUrl":"httt/duoyun.png",
                "weather":"多云",
                "wind":"北风微风",
                "temperature":"4 ~ -6℃"},

                {"date":"周日",
                "dayPictureUrl":"http://ap/qing.png",
                "nightPictureUrl":"http://ht/qing.png",
                "weather":"晴",
                "wind":"北风微风",
                "temperature":"5 ~ -5℃"},
                
                {"date":"周一",
                "dayPictureUrl":"http://ay/duoyun.png",
                "nightPictureUrl":"http:night/yin.png",
                "weather":"多云转阴",
                "wind":"东南风微风",
                "temperature":"3 ~ -4℃"}]}]} --> 