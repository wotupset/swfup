<?php
error_reporting(E_ALL & ~E_NOTICE); //所有錯誤中排除NOTICE提示
date_default_timezone_set("Asia/Taipei");//時區設定 Etc/GMT+8
$time=time();
$ym=date("ym",$time);
$dir_mth="../01/_".$ym."/"; //
$date_now=date("d", $time)."v".date("His", $time);
// A list of permitted file extensions
$allowed = array('png', 'jpg', 'gif');

if(isset($_FILES['upl']) && $_FILES['upl']['error'] == 0){

	$extension = pathinfo($_FILES['upl']['name'], PATHINFO_EXTENSION);

	if(!in_array(strtolower($extension), $allowed)){
		echo '{"status":"error"}';
		exit;
	}
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
	$fn=$_FILES['upl']['name'];
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
	//修飾
	if($fn_b=="jpeg"){$fn_b="jpg";}
	////
	if(0){//0
	//排除的檔案
	$ban=0;
	if($fn_b=="php"){$ban=1;}//忽略檔案(安全考量)
	if($fn_b=="exe"){$ban=1;}//忽略檔案(安全考量)
	//只允許圖片
	$info_array=getimagesize($_FILES["upl"]['tmp_name']);if(floor($info_array[2]) == 0 ){$ban=1;}
	//允許的檔案大小上限
	if($_FILES["upl"]['size'] > 10*1024*1024){$ban=1;}
	//回傳自訂的錯誤訊息
	if($ban){
		//header("Status: 405");
		//header("HTTP/1.0 405 Not Found");
		echo '{"status":"error"}';
		exit;
	}
	}//0
	////
	//移動合格的檔案到資料夾
	if(move_uploaded_file($_FILES['upl']['tmp_name'],   $dir_mth."_".$date_now."_".$fn_a.".".$fn_b)){
		echo '{"status":"success"}';
		exit;
	}
}

echo '{"status":"error"}';
exit;

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
?>