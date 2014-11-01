<?php
header('Content-type: text/plain; charset=utf-8');
$phpdir="http://".$_SERVER["SERVER_NAME"]."".$_SERVER["PHP_SELF"]."";
$phpdir=substr($phpdir,0,strrpos($phpdir,"/")+1); //根目錄
error_reporting(E_ALL & ~E_NOTICE); //
$query_string=$_SERVER['QUERY_STRING'];
if($query_string != 'x'){die('x');}
//if(!preg_match('/^[\w]+$/', $query_string )){die('x');}
//**********
//echo "123";
//
$url="./";
$handle=opendir($url); 
$cc = 0;
$img_type=array('jpg','png','gif'); //類型 計數 檔名
$img_ct=0;
$total_size=0;
$log='filemap';
$FFF_m=10;
while(($file = readdir($handle))!==false) { 
	$cc = $cc + 1;
	$is_img=0;
	//echo $file."\n";
	if($file=="."||$file == ".."){
		//沒事
	}else{
		if(is_file($file)){//只處理檔案
			//$ext=substr($file,strrpos($file,".")+1); //副檔名
			$file_p=pathinfo($file);
			$ext=$file_p['extension'];
			foreach($img_type as $k => $v){
				if(strtolower($ext) == $v){
					$is_img=1;
					$img_ct=$img_ct+1;
					$file_s=filesize($file);
					//echo $file_s;
					$total_size=$total_size+$file_s; //只計算圖檔大小
				}
			}
		}
	}
	if($is_img ==1){
		$FFF_a=ceil(($img_ct-1)/$FFF_m); // 0+1 , 1+1,
		$FFF_b=ceil(($img_ct)/$FFF_m);
		$FFF_c.='思'.$img_ct.'墨'.$phpdir.$file."\n";
		//echo $FFF_a." ".$FFF_b."\n";
		if($FFF_a !=0 && $FFF_a != $FFF_b ){
			$log2=$log.$FFF_a.'.txt';
			echo $FFF_a."\n".$FFF_c."\n";
			file_put_contents($log2,$FFF_c);
			$FFF_c='';
		}
	}
	//echo $is_img;
	//echo "\n";
	//$tmp[$cc] = substr($file,0,strpos($file,"."));
} 
closedir($handle); 


if($img_ct % $FFF_m != 0){
	echo $FFF_a."\n".$FFF_c."\n";
	$log2=$log.$FFF_a.'.txt';
	file_put_contents($log2,$FFF_c);
	$FFF_c='';
}

//echo $total_size;
?>