<?php
error_reporting(E_ALL & ~E_NOTICE); //所有錯誤中排除NOTICE提示
date_default_timezone_set("Asia/Taipei");//時區設定 Etc/GMT+8
$time=time();
$ym=date("ym",$time);
$dir_mth="./_".$ym."/"; //存放該月檔案
$query_string=$_SERVER['QUERY_STRING'];
$phpself=basename($_SERVER["SCRIPT_FILENAME"]);//
$phplink="http://".$_SERVER["SERVER_NAME"].$_SERVER["SCRIPT_NAME"]."";
$phphost=$_SERVER["SERVER_NAME"];
$phpdir="http://".$_SERVER["SERVER_NAME"]."".$_SERVER["PHP_SELF"]."";
$phpdir=substr($phpdir,0,strrpos($phpdir,"/")+1); //根目錄
//**********
//不支援外連時的應對方法
$hp=0;if(is_file("hp=1.txt")){$hp=1;}//hotlink_protect
//
if(!is_writeable(realpath($dir_mth))){die("目錄沒有寫入權限"); }
$FFF_arr=array();
if(is_dir($dir_mth)){
	$cc = 0;
	$url=$dir_mth;
	$handle=opendir($url); 
	while(($file = readdir($handle))!==false) { 
		$chk=0;
		$ext=substr($file,strrpos($file,".")+1); //副檔名
		$ext=strtolower($ext);
		//$ext = pathinfo($file,PATHINFO_EXTENSION);//副檔名
		if(preg_match("/jpg/i",$ext)){$chk=1;}//只要圖
		if(preg_match("/png/i",$ext)){$chk=1;}//只要圖
		if(preg_match("/gif/i",$ext)){$chk=1;}//只要圖
		if($chk==1){
			$FFF_arr[0][$cc]=$file;
			$FFF_arr[1][$cc]=filectime($dir_mth.$file);
		}
		$cc = $cc + 1;
	} 
}else{
	$FFF_arr[0][0]='x';
	$FFF_arr[1][0]='x';
}

closedir($handle); 
//sort($FFF_arr);//排序 舊的在前
array_multisort(
$FFF_arr[1], SORT_DESC,SORT_NUMERIC,
$FFF_arr[0]
);
//
ob_start();
$ct=count($FFF_arr[0]);//攔截到的項目
////**********
//檢查是否支援 allow_url_fopen
echo $allow_url_fopen = ini_get('allow_url_fopen');


$ct2=ceil($ct/10);
echo "<a href='./'>目</a>"."\n";
echo "<a href='./".$phpself."'>返</a>"."\n";
echo "<a href='./".$phpself."?a'>01</a>"."\n";
echo "<pre>";
$cc=0;
foreach($FFF_arr[0] as $k => $v ){
	if($cc>200){break;}
	$album_link=$phpdir."fourm2.php?".$ym."!".$ct2;//相簿位置(絕對位置)
	$pic_src=$phpdir.$dir_mth.$v;//圖片位置(絕對位置)
	if($hp==1){$pic_src=$phpdir."img_hot.php?".$dir_mth.$v;}//反防盜連(絕對+相對位置)
	switch($query_string){
		case 'a': //html
			if($cc == 0){
				echo "&lt;a href='".$album_link."'&gt;".$phphost."&lt;/a&gt; &lt;br/&gt;";
				echo "\n";
			}
			//貼圖語法
			echo $cc;
			echo "&lt;img src='".$pic_src."'&gt; &lt;br/&gt;";
		break;
		default: //預設bbcode
			if($cc == 0){
				echo "[url=".$album_link."]".$phphost."[/url]";
				echo "\n";
			}
			//貼圖語法
			echo $cc;
			echo "[img]".$pic_src."[/img]";
		break;
	}
	echo "\n";
	$cc++;
}
echo "\n`\n";
echo "</pre>";
//echo "<br/>\n`\n";
$htmlbody=ob_get_clean();





echo htmlhead();
echo $htmlbody;
echo htmlend();

function htmlhead(){
$x=<<<EOT
<html><head>
<title>guten morgen</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<META http-equiv="Content-Script-Type" content="text/javascript">
<META http-equiv="Content-Style-Type" content="text/css">
<META HTTP-EQUIV="EXPIRES" CONTENT="Thu, 15 Jan 2009 05:12:01 GMT">
<META NAME="ROBOTS" CONTENT="INDEX,FOLLOW">
<STYLE TYPE="text/css">
</STYLE>
</head>
<body>
EOT;
$x="\n".$x."\n";
return $x;
}

function htmlend(){
$x=<<<EOT
</body></html>
EOT;
$x="\n".$x."\n";
return $x;
}

?>