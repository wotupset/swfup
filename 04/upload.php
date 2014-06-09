<?php
error_reporting(E_ALL & ~E_NOTICE); //所有錯誤中排除NOTICE提示
date_default_timezone_set("Asia/Taipei");//時區設定 Etc/GMT+8
$time=time();
$ym=date("ym",$time);
$dir_mth="../01/_".$ym."/"; //
$date_now=date("d", $time)."v".date("His", $time);
////
if(!is_dir($dir_mth)){
	$yesno=mkdir($dir_mth, 0777); //建立資料夾 權限0777
	chmod($dir_mth, 0777); //權限0777
}
if(is_dir($dir_mth)){
	if(!is_file($dir_mth."index.php")){
		copy("index.php",$dir_mth."index.php");
	}
}else{
	if(!$yesno){die('x!dir');}
}

/////
//抓出上傳檔案的副檔名
$fn=$_FILES['file']['name'];
$fn_a=substr($fn,0,strrpos($fn,".")); //主檔名
//修飾
$fn_a=strZHcut($fn_a);//主檔名
$fn_a=preg_replace("/\]/","_",$fn_a);
$fn_a=preg_replace("/\[/","_",$fn_a);
$fn_a=preg_replace("/ /","_",$fn_a);
$fn_a=preg_replace("/\./","_",$fn_a);
$fn_a=preg_replace("/_+/","_",$fn_a);
//
$fn_b=strrpos($fn,".")+1-strlen($fn);
$fn_b=substr($fn,$fn_b); //副檔名
////
function strZHcut($str){ //將檔名中的中文去掉
	$len = strlen($str);
	for($i = 0; $i < $len; $i++){
		$char = $str{0};
		if(ord($char) > 127){
			$i++;
			if($i < $len){
				//$arr[] = substr($str, 0, 3);//取0~3字元的字串到陣列
				$arr[] = "_";//取0~3字元的字串到陣列
				$str = substr($str, 3); //取3字元之後的字串
			}
		}else{
			$arr[] = $char;
			$str = substr($str, 1);
		}
	}
	$str=join($arr); //array_reverse?
	return $str;
}
////
if($_SERVER['REQUEST_METHOD'] == "POST"){
	if(move_uploaded_file($_FILES['file']['tmp_name'],  $dir_mth."_".$date_now."_".$fn_a.".".$fn_b)){
		echo($_POST['index']);
	}
	exit;
}
?>