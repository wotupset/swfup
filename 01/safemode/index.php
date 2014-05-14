<?php
date_default_timezone_set("Asia/Taipei");//時區設定 Etc/GMT+8
$time=time();
$ym=date("ym",$time);
$query_string=$_SERVER['QUERY_STRING'];
$phplink="http://".$_SERVER["SERVER_NAME"].$_SERVER["SCRIPT_NAME"]."";
$phpdir="http://".$_SERVER["SERVER_NAME"]."".$_SERVER["PHP_SELF"]."";
$phpdir=substr($phpdir,0,strrpos($phpdir,"/")+1); //根目錄
$phpself=basename($_SERVER["SCRIPT_FILENAME"]);//被執行的文件檔名
//**********
if(preg_match("/^([0-9]+)/",$query_string,$match)){
	$pg_n=$match[1];//分頁的頁數
}
unset($match);
if(!$pg_n){$pg_n=0;}//沒指定就是0

//**********
$dir_mth="./"; //掃描根目錄
$url=$dir_mth;
$handle=opendir($url); 
$cc = 0;
$FFF_arr=array();
while(($file = readdir($handle))!==false) { 
	$chk=0;
	if(preg_match("/\.jpg$/i",$file)){$chk=1;}//只要圖
	if(preg_match("/\.png$/i",$file)){$chk=1;}//只要圖
	if(preg_match("/\.gif$/i",$file)){$chk=1;}//只要圖
	if($chk==1){$FFF_arr[]=$file;}
	$cc = $cc + 1;
} 
closedir($handle); 
sort($FFF_arr);//排序 舊的在前
//**********
//列出左側分頁
$arr_count=count($FFF_arr);//計算數量
$pg_max=floor($arr_count/10);
if($arr_count%10 != 0){$pg_max=$pg_max+1;} //有餘數再加一
if($pg_n>$pg_max){$pg_n=$pg_max;}//指定頁數太大 就更換成max值

$cc=1;$pg_html='';$FFF='';
for($i=0;$i<$pg_max;$i++){
	if($i == $pg_n){$FFF="&nbsp;<span id='menu2_pi'>&#9619;&#9618;&#9617;</span>";}else{$FFF='';}
	$pg_html.="<a href='".$phpself."?".$i."'>".$i."</a>".$FFF;
	$pg_html.="<br/>\n";
	$cc=$cc+1;

}
//**********
//列出右側圖片
$cc=1;$pic='';
foreach($FFF_arr as $k => $v ){
	//if(){continue;}
	if( ($k>= ($pg_n)*10 ) && ($k< ($pg_n+1)*10 ) ){
		//$pic_src=$phpdir.$dir_mth.$v;
		$pic_src=$dir_mth.$v;
		//$pic_size=filesize($pic_src);
		$fn=$v;
		$fn_a=substr($fn,0,strrpos($fn,".")); //主檔名
		$fn_b=strrpos($fn,".")+1-strlen($fn);
		$fn_b=substr($fn,$fn_b); //副檔名
		$pic.= $k;
		if(strtolower($fn_b) == "gif"){$pic.="GIF";}
		$pic.= "<br/>\n";
		$pic.= "<a href='".$pic_src."' target='_blank'><img src='".$pic_src."'/></a>";
		$pic.= "<br/>\n";
	}
	$cc=$cc+1;
}

$htmlbody=<<<EOT
<div id="menu2" style="z-index:9;position: fixed; margin: 0px; padding: 0px; left: 0px; top: 0px; color: #cc0000; background-color: #ffffff; border-right: 1px black solid; overflow: auto; width: 125px;height:90%;">
	<div style="padding: 10px;">
		$pg_html
	</div>
</div>
<div id="menu3" style="z-index:8;position: fixed; margin-bottom: 0px; padding: 5px; width: 100%; left: 0px; bottom: 0px; color: #cc0000; background-color: #ffffee; border-top: 1px black solid; ">
	<div style="font-size: 12px;margin-bottom:5px;">
		<a href='index2.php'>資料夾模式</a> 分頁 $pg_n <br/>
	</div>
</div>
<div id="right_content" style="margin: auto auto 50px 160px;">
	$pic
</div>
EOT;

echo htmlhead();
echo $htmlbody;
echo htmlend();


function htmlhead(){
$x=<<<EOT
<html><head>
<title>$ymdhis</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<META http-equiv="Content-Script-Type" content="text/javascript">
<META http-equiv="Content-Style-Type" content="text/css">
<META HTTP-EQUIV="EXPIRES" CONTENT="Thu, 15 Jan 2009 05:12:01 GMT">
<META NAME="ROBOTS" CONTENT="INDEX,FOLLOW">
<STYLE TYPE="text/css">
body2 { font-family:"細明體",'MingLiU'; }
img {
height:auto; width:auto; 
min-width:20px; min-height:20px;
max-width:250px; max-height:250px;
border:1px solid blue;
}
</STYLE>
<script src="http://code.jquery.com/jquery-2.1.1.min.js" type="text/javascript"></script>
<script language="Javascript">

////
function reset() { 
	//var tmp1 = tmp2 = tmp3 = 0;alert(tmp2);
	//(tmp3.clientHeight - 200)
	//$("#menu2").height =  "400px"; //選單高度修正
	//document.getElementById("menu2").style.height = "200px";
	var tmp4 = $("#menu3").outerHeight(true);//下方選單高度// set to true, the margin (top and bottom) is also included.
	//alert(tmp4);
	var tmp3 = $(document.body)[0].clientHeight -tmp4;//計算左側選單底部要縮多少高度
	$("#menu2").height(tmp3);
	var tmp5 = tmp4 +20;//計算右側內容底部要墊多少高度
	$("#right_content").css("margin-bottom",tmp5+"px");
	////
}
////
$(document).ready(function(){
	reset();
	$(window).resize(function(){reset();});
	//window.onresize = reset();
	//$("#menu2").scrollTop(tmp);
	//$("#menu2").height()
////
	var tmp1 = $("#menu2").outerHeight(true);//左側選單高度 //height()
	var tmp2 = $("#menu2_pi").position().top - (tmp1/2); //計算左側選單捲軸要停留的位置
	//$("#menu2").animate({scrollTop: tmp2 },1000,"swing");//jq動畫方式移動捲軸
	$("#menu2").scrollTop(tmp2);
});
</script>
</head>
<body bgcolor="#FFFFEE" text="#800000" link="#0000EE" vlink="#0000ee">

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