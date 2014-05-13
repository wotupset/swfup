<?php
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
error_reporting(E_ALL & ~E_NOTICE); //所有錯誤中排除NOTICE提示
date_default_timezone_set("Asia/Taipei");//時區設定 Etc/GMT+8
$time=time();
$dir_mth="./_".date("ym",$time)."/"; //存放該月檔案
@mkdir($dir_mth, 0777); //建立資料夾 權限0777
@chmod($dir_mth, 0777); //權限0777
////
$chk_safemode_= NULL;
if(is_dir("./safemode=NO/") || is_dir("./safemode=YES/") ){//檢查是否有檢查過
	if(is_dir("./safemode=NO/")){$chk_safemode_=0;}
	if(is_dir("./safemode=YES/")){$chk_safemode_=1;}
	//echo "跳過";
}else{//沒檢查過
	$FFF="index.php";
	if(!is_file($dir_mth.$FFF)){
		@copy($FFF, $dir_mth.$FFF);//複製index檔案到目錄
	}
	$FFF="_fourm_self.php";
	if(!is_file($dir_mth.$FFF)){
		@copy($FFF, $dir_mth.$FFF);//複製index檔案到目錄
	}
	if(is_file($dir_mth."index.php")){//存在
		@rename("./safemode/", "./safemode=NO/"); //更名
		$chk_safemode_=0;//沒有安全模式
	}else{//還是不存在
		@rename("./safemode/", "./safemode=YES/"); //更名
		$chk_safemode_=1;//有安全模式
	}
}

	//
	$fn=$_FILES["Filedata"]['name'];
	$fn_a=substr($fn,0,strrpos($fn,".")); //主檔名
	$fn_a=strZHcut($fn_a);//主檔名
	$fn_b=strrpos($fn,".")+1-strlen($fn);
	$fn_b=substr($fn,$fn_b); //副檔名
	if($fn_b=="php"){exit;}//忽略上傳的php檔案(安全考量)
	////存放檔案
	//$date_now=date("ymdHis", $time);
	$date_now=date("d", $time)."v".date("His", $time);
	if($chk_safemode_){
		$dir_mth="./safemode/";
		@chmod($dir_mth, 0777); //權限0777
		$FFF=move_uploaded_file($_FILES["Filedata"]['tmp_name'], $dir_mth."_".$date_now."_".$fn_a.".".$fn_b);
	}else{
		if(!is_file($dir_mth."index.php")){
			$chk=@copy("index.php", $dir_mth."index.php");
		}
		$FFF=move_uploaded_file($_FILES["Filedata"]['tmp_name'], $dir_mth."_".$date_now."_".$fn_a.".".$fn_b);
	}
	////存放檔案/
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