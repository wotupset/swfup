<?php
error_reporting(E_ALL & ~E_NOTICE); //�Ҧ����~���ư�NOTICE����
date_default_timezone_set("Asia/Taipei");//�ɰϳ]�w Etc/GMT+8
$time=time();
$ym=date("ym",$time);
$date_now=date("d", $time)."v".date("His", $time);
// A list of permitted file extensions
$allowed = array('png', 'jpg', 'gif');
////
//�ˬd�O�_���w���Ҧ�
$chk_safemode_= NULL;
if(is_dir("../01/safemode=NO/") || is_dir("../01/safemode=YES/") ){//�ˬd�O�_���ˬd�L
	if(is_dir("../01/safemode=NO/")){$chk_safemode_=0;}
	if(is_dir("../01/safemode=YES/")){$chk_safemode_=1;}
	//echo "���L";
}else{//�S�ˬd�L
	mkdir("../01/safemode=CHK/", 0777); //�إ߸�Ƨ� �v��0777
	copy("./index.php", "../01/safemode=CHK/index.php");//�ƻsindex�ɮר�ؿ�
	if(!is_dir("../01/safemode=CHK/")){die('�إ߸�Ƨ�����');}
	if(is_file("../01/safemode=CHK/index.php")){//�s�b
		rename("../01/safemode=CHK/", "../01/safemode=NO/"); //��W
		$chk_safemode_=0;//�S���w���Ҧ�
	}else{//�٬O���s�b
		rename("../01/safemode=CHK/", "../01/safemode=YES/"); //��W
		$chk_safemode_=1;//���w���Ҧ�
	}
}
////
//�s���ɮ�
if($chk_safemode_){//���w���Ҧ�
	$dir_mth="../safemode/";//
	chmod($dir_mth, 0777); //�v��0777
}else{//�L�w���Ҧ�
	$dir_mth="../01/_".$ym."/"; //
	if(!is_dir($dir_mth)){//�Y��Ƨ����s�b �h�إ�
		mkdir($dir_mth, 0777); //�إ߸�Ƨ� �v��0777
		chmod($dir_mth, 0777); //�v��0777
	}
	$FFF="index.php";
	if(!is_file($dir_mth.$FFF) && is_file($FFF) ){
		copy($FFF, $dir_mth.$FFF);//�ƻs�ɮר�ؿ�
	}
	$FFF="_fourm_self.php";
	if(!is_file($dir_mth.$FFF) && is_file($FFF) ){
		//copy($FFF, $dir_mth.$FFF);//�ƻs�ɮר�ؿ�
	}
}

////
/*
if(!is_dir($dir_mth)){
	mkdir($dir_mth, 0777); //�إ߸�Ƨ� �v��0777
	chmod($dir_mth, 0777); //�v��0777
}
if(is_dir($dir_mth)){
	if(!is_file($dir_mth."index.php")){
		copy("index.php",$dir_mth."index.php");
	}
}else{
	die('x!dir'.$dir_mth);
}
*/
////
if(isset($_FILES['upl']) && $_FILES['upl']['error'] == 0){
	$extension = pathinfo($_FILES['upl']['name'], PATHINFO_EXTENSION);
	if(!in_array(strtolower($extension), $allowed)){
		echo '{"status":"error"}';
		exit;
	}
	/////
	//��X�W���ɮת����ɦW
	$fn=$_FILES['upl']['name'];
	$fn_a=substr($fn,0,strrpos($fn,".")); //�D�ɦW
	//�׹�
	$fn_a=strZHcut($fn_a);//�D�ɦW
	$fn_a=preg_replace("/\]/","_",$fn_a);
	$fn_a=preg_replace("/\[/","_",$fn_a);
	$fn_a=preg_replace("/ /","_",$fn_a);
	$fn_a=preg_replace("/\./","_",$fn_a);
	$fn_a=preg_replace("/_+/","_",$fn_a);
	//
	$fn_b=strrpos($fn,".")+1-strlen($fn);
	$fn_b=substr($fn,$fn_b); //���ɦW
	//�׹�
	if($fn_b=="jpeg"){$fn_b="jpg";}
	////
	if(0){//0
	//�ư����ɮ�
	$ban=0;
	if($fn_b=="php"){$ban=1;}//�����ɮ�(�w���Ҷq)
	if($fn_b=="exe"){$ban=1;}//�����ɮ�(�w���Ҷq)
	//�u���\�Ϥ�
	$info_array=getimagesize($_FILES["upl"]['tmp_name']);if(floor($info_array[2]) == 0 ){$ban=1;}
	//���\���ɮפj�p�W��
	if($_FILES["upl"]['size'] > 10*1024*1024){$ban=1;}
	//�^�Ǧۭq�����~�T��
	if($ban){
		//header("Status: 405");
		//header("HTTP/1.0 405 Not Found");
		echo '{"status":"error"}';
		exit;
	}
	}//0
	////
	//���ʦX�檺�ɮר��Ƨ�
	$filename_new=$dir_mth."_".$date_now."_".$fn_a.".".$fn_b;
	if(move_uploaded_file($_FILES['upl']['tmp_name'], $filename_new)){
		echo '{"status":"success"}';
		exit;
	}
}

echo '{"status":"error"}';
exit;

////
function strZHcut($str){ //�N�ɦW��������h��
	$len = strlen($str);
	for($i = 0; $i < $len; $i++){
		$char = $str{0};
		if(ord($char) > 127){
			$i++;
			if($i < $len){
				//$arr[] = substr($str, 0, 3);//��0~3�r�����r���}�C
				$arr[] = "_";//��0~3�r�����r���}�C
				$str = substr($str, 3); //��3�r�����᪺�r��
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