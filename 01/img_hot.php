<?php
$query_string=$_SERVER['QUERY_STRING'];
$chk=0;
$type='';
if(preg_match("/\.jpg$/i",$query_string,$match)){$chk=1;$type='jpg';}
if(preg_match("/\.png$/i",$query_string,$match)){$chk=1;$type='png';}
if(preg_match("/\.gif$/i",$query_string,$match)){$chk=1;$type='gif';}
//
$yesno = is_file($query_string);//�T�{�ɮצs�b
if(!$yesno){die('x');}
$contents = file_get_contents($query_string);//���o�ɮפ��e
if(!$contents){die('x');}
$FFF = mime_content_type($query_string);//�T�{�������
Header("Content-type:".$FFF);//���w�������
echo $contents;
?>