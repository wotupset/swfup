<?php
$query_string=$_SERVER['QUERY_STRING'];
//
$yesno = is_file($query_string);//�T�{�ɮצs�b
if(!$yesno){die('x');}
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
*/
if(!$array){die('x2');}
//
$contents = file_get_contents($query_string);//���o�ɮפ��e
if(!$contents){die('x');}
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