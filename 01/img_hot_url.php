<?php
error_reporting(E_ALL & ~E_NOTICE); //�Ҧ����~���ư�NOTICE����
date_default_timezone_set("Asia/Taipei");//�ɰϳ]�w Etc/GMT+8
$time=time();
$ym=date("ym",$time);

////
$query_string=$_SERVER['QUERY_STRING'];
if(!$query_string){die('x000');}
//
$array=getimagesize($query_string);//�O�_���Ϥ�
//print_r($array);exit;
/*
    [0] => 1440
    [1] => 810
    [2] => 2
    [3] => width="1440" height="810"
    [bits] => 8
    [channels] => 3
    [mime] => image/jpeg
1=gif
2=jpg
3=png
*/
if(!$array[2]){die('x200');}
//
$contents = file_get_contents($query_string);//���o�ɮפ��e
if($content === FALSE){die('x300');}
//
$dir_mth="./_".$ym."/"; //
$date_now=date("d", $time)."v".date("His", $time);
$ext='';
switch($array[2]){
case '1':
	$ext="gif";
break;
case '2':
	$ext="jpg";
break;
case '3':
	$ext="png";
break;
default:
	die('x400');
break;

}
$yesno = file_put_contents($dir_mth."_".$date_now."_".$time.".".$ext , $contents );//�إ��ɮפ��e
if($yesno === FALSE){die('x400');}
//
/*
if(function_exists("mime_content_type")){
	$FFF=mime_content_type($query_string);
}else{
	$chk=0;$type='';
	if(preg_match("/\.jpg$/i",$query_string)){$chk=1;$type='image/jpeg';}
	if(preg_match("/\.png$/i",$query_string)){$chk=1;$type='image/png';}
	if(preg_match("/\.gif$/i",$query_string)){$chk=1;$type='image/gif';}
	if(!$chk){die('x');}
	//
	$FFF=$type;
}
*/
//
Header("Content-type:".$array['mime']);//���w�������
echo $contents;
exit;
?>