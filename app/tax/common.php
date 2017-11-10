<?php
/**
* 公共函数
*/
//uuid 用于案件的随机id生成
function create_uuid($prefix = "DS",$phone='15539728747',$id='12'){    //可以指定前缀
	$cc  = findNum(md5($phone.$prefix.$id));
	$one = substr($cc,0,4);
	$two = substr($cc,-4);
	return $prefix.str_pad(rand(0,999),3,"0",STR_PAD_LEFT).$one.$two;
}

//查找数字
function findNum($str=''){ 
    $str=trim($str); 
    if(empty($str)){return '';} 
    $result=''; 
    for($i=0;$i<strlen($str);$i++){ 
        if(is_numeric($str[$i])){ 
            $result.=$str[$i]; 
        } 
    } 
    return $result; 
} 
