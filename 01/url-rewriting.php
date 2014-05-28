<?php
date_default_timezone_set("Asia/Taipei");//時區設定 Etc/GMT+8
$time=time();
$ym=date("ym",$time);
$FFF=$_SERVER["REQUEST_URI"];
//echo $FFF;
$url = pathinfo($FFF);
//print_r($url);
$rewrite = $url["basename"];//原始地址
//echo $rewrite;
$patterns = array();
$patterns[0] = "/^1\.htm$/";
$patterns[1] = "/^(.+\.jpg)$/";
//
$replacements = array();
$replacements[0] = "XPButtonUploadText_61x22.png";
$replacements[1] = "./_".$ym."/$1";
$rewrite_o=$rewrite;
$rewrite = preg_replace($patterns, $replacements, $rewrite);//匹配后的地址
//echo $rewrite;
if($rewrite == $rewrite_o){
	header("HTTP/1.0 404 OK");
	echo $rewrite;
	exit;
}else{
	header("HTTP/1.0 200 OK");
	if(is_file($rewrite)){
		$FFF='';$chk_err=0;
		if(function_exists("mime_content_type")){
			$FFF=mime_content_type($rewrite);//檔案類型 方法一
		}else{
			if(function_exists("finfo_open")){
				$finfo = finfo_open(FILEINFO_MIME);
				$FFF=finfo_file($finfo, $rewrite);//檔案類型 方法二
				finfo_close($finfo);
			}else{
				$chk_err=1;//錯誤
			}
		}
		$filetype=$FFF;
		//$contents = file_get_contents($rewrite);//取得檔案內容
		//if($contents === false){die('x');}
		//echo $FFF;echo "\n";
		//Header("Content-type:".$filetype);//指定文件類型
		//echo $contents;
		header("Location: ".$rewrite);//拼接地址
	}else{
		echo $rewrite;
		echo "檔案不存在";
	}
	exit;
}
?>