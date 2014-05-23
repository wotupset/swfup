<?php
$FFF=$_SERVER["REQUEST_URI"];
//echo $FFF;
$url = pathinfo($FFF);
//print_r($url);
$rewrite = $url["basename"];//原始地址
//echo $rewrite;
$patterns = array();
$patterns[0] = "/^1\.htm$/";
//
$replacements = array();
$replacements[0] = "XPButtonUploadText_61x22.png";
$rewrite_o=$rewrite;
$rewrite = preg_replace($patterns, $replacements, $rewrite);//匹配后的地址
//echo $rewrite;
if($rewrite == $rewrite_o){
	header("HTTP/1.0 404 OK");
	echo "404";
	exit;
}else{
	header("HTTP/1.0 200 OK");
	header("Location: ".$rewrite);//拼接地址
	exit;
}
?>