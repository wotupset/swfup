<?php
error_reporting(E_ALL & ~E_NOTICE); //�Ҧ����~���ư�NOTICE����
date_default_timezone_set("Asia/Taipei");//�ɰϳ]�w Etc/GMT+8
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
		$yesno=mkdir($dir_mth, 0777); //�إ߸�Ƨ� �v��0777
		chmod($dir_mth, 0777); //�v��0777
	}
	if(is_dir($dir_mth)){
		if(!is_file($dir_mth."index.php")){
			copy("index.php",$dir_mth."index.php");
		}
	}else{
		if(!$yesno){die('x!dir');}
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
	//
	$fn_b=strrpos($fn,".")+1-strlen($fn);
	$fn_b=substr($fn,$fn_b); //���ɦW
	////
	if(move_uploaded_file($_FILES['upl']['tmp_name'],   $dir_mth."_".$date_now."_".$fn_a.".".$fn_b)){
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