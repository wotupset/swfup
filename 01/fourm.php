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
$sf=0;if(is_file("sf=1.txt")){$sf=1;}
//

if(!is_dir($dir_mth)){//找不到資料夾就找safemode
	$dir_mth="./safemode/";
	if(!is_dir($dir_mth)){die('[x]dir');}
}
if(!is_writeable(realpath($dir_mth))){die("目錄沒有寫入權限"); }
$url=$dir_mth;
$handle=opendir($url); 
$cc = 0;
$FFF_arr=array();
while(($file = readdir($handle))!==false) { 
	$chk=0;
	if(preg_match("/\.jpg$/i",$file)){$chk=1;}
	if(preg_match("/\.png$/i",$file)){$chk=1;}
	if(preg_match("/\.gif$/i",$file)){$chk=1;}
	if($chk==1){$FFF_arr[]=$file;}
	$cc = $cc + 1;
} 
closedir($handle); 
rsort($FFF_arr);//新的在上
$ct=count($FFF_arr);//攔截到的項目
////**********
//檢查是否支援 allow_url_fopen
echo $allow_url_fopen = ini_get('allow_url_fopen');


$ct2=ceil($ct/10);
echo "<a href='./".$phpself."'>01</a>";
echo "<a href='./".$phpself."?a'>02</a>";
echo "<pre>";
$cc=0;
foreach($FFF_arr as $k => $v ){
	$pic_src=$dir_mth.$v;
	if($sf==1){$pic_src="img_hot.php?".$dir_mth.$v;}
	if($cc>100){break;}
	switch($query_string){
		case 'a': //html
			if($cc == 0){
				echo "&lt;a href='".$phpdir."fourm2.php?".$ym."!".$ct2."'&gt;".$phphost."&lt;/a&gt; &lt;br/&gt;";
				echo "\n";
			}
			//貼圖語法
			echo $cc;
			echo "&lt;img src='".$phpdir.$pic_src."'&gt; &lt;br/&gt;";
		break;
		default: //預設bbcode
			if($cc == 0){
				echo "[url=".$phpdir."fourm2.php?".$ym."!".$ct2."]".$phphost."[/url]";
				echo "\n";
			}
			//貼圖語法
			echo $cc;
			echo "[img]".$phpdir.$pic_src."[/img]";
		break;
	}
	echo "\n";
	$cc=$cc+1;
}
echo "</pre>";
echo "<br/>\n";
?>