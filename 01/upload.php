<?php
error_reporting(E_ALL & ~E_NOTICE); //所有錯誤中排除NOTICE提示
////
	// Work-around for setting up a session because Flash Player doesn't send the cookies
	if (isset($_POST["PHPSESSID"])) {
		session_id($_POST["PHPSESSID"]);
	}
	session_start();

	// The Demos don't save files

	if (!isset($_FILES["Filedata"]) || !is_uploaded_file($_FILES["Filedata"]["tmp_name"]) || $_FILES["Filedata"]["error"] != 0) {
		echo "There was a problem with the upload";
		exit(0);
	}
////

date_default_timezone_set("Asia/Taipei");//時區設定 Etc/GMT+8
$time=time();
$ym=date("ym",$time);
////
//檢查是否有安全模式
$chk_safemode_= NULL;
if(is_dir("./safemode=NO/") || is_dir("./safemode=YES/") ){//檢查是否有檢查過
	if(is_dir("./safemode=NO/")){$chk_safemode_=0;}
	if(is_dir("./safemode=YES/")){$chk_safemode_=1;}
	//echo "跳過";
}else{//沒檢查過
	mkdir("./safemode=CHK/", 0777); //建立資料夾 權限0777
	copy("./index.php", "./safemode=CHK/index.php");//複製index檔案到目錄
	if(is_file("./safemode=CHK/index.php")){//存在
		rename("./safemode=CHK/", "./safemode=NO/"); //更名
		$chk_safemode_=0;//沒有安全模式
	}else{//還是不存在
		rename("./safemode=CHK/", "./safemode=YES/"); //更名
		$chk_safemode_=1;//有安全模式
	}
}
////
//抓出上傳檔案的副檔名
$fn=$_FILES["Filedata"]['name'];
$fn_a=substr($fn,0,strrpos($fn,".")); //主檔名
//修飾
$fn_a=strZHcut($fn_a);//主檔名
$fn_a=preg_replace("/\]/","_",$fn_a);
$fn_a=preg_replace("/\[/","_",$fn_a);
$fn_a=preg_replace("/ /","_",$fn_a);
$fn_a=preg_replace("/\./","_",$fn_a);
$fn_a=preg_replace("/\./","_",$fn_a);
$fn_a=preg_replace("/_+/","_",$fn_a);
//
$fn_b=strrpos($fn,".")+1-strlen($fn);
$fn_b=substr($fn,$fn_b); //副檔名
//修飾
if($fn_b=="jpeg"){$fn_b="jpg";}
////
//排除的檔案
$ban=0;
if($fn_b=="php"){$ban=1;}//忽略檔案(安全考量)
if($fn_b=="exe"){$ban=1;}//忽略檔案(安全考量)
//只允許圖片
$info_array=getimagesize($_FILES["Filedata"]['tmp_name']);if(!$info_array[2]){$ban=1;}
if($_FILES["Filedata"]['size'] > 10*1024*1024){$ban=1;}//允許的檔案大小上限
if($ban){header("Status: 500");exit;}
////
//存放檔案
$date_now=date("d", $time)."v".date("His", $time);
if($chk_safemode_){//有安全模式
	$dir_mth="./safemode/";//
	chmod($dir_mth, 0777); //權限0777
	$filename_new=$dir_mth."_".$date_now."_".$fn_a.".".$fn_b;
	$FFF=move_uploaded_file($_FILES["Filedata"]['tmp_name'], $filename_new);
}else{//無安全模式
	$dir_mth="./_".$ym."/"; //
	if(!is_dir($dir_mth)){//若資料夾不存在 則建立
		mkdir($dir_mth, 0777); //建立資料夾 權限0777
		chmod($dir_mth, 0777); //權限0777
	}
	$FFF="index.php";
	if(!is_file($dir_mth.$FFF) && is_file($FFF) ){
		copy($FFF, $dir_mth.$FFF);//複製檔案到目錄
	}
	$FFF="_fourm_self.php";
	if(!is_file($dir_mth.$FFF) && is_file($FFF) ){
		//copy($FFF, $dir_mth.$FFF);//複製檔案到目錄
	}
	$filename_new=$dir_mth."_".$date_now."_".$fn_a.".".$fn_b;
	$FFF=move_uploaded_file($_FILES["Filedata"]['tmp_name'], $filename_new);
}

////
//結束
exit(0);
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
////
?>