<?php

class MaterialManager
{
	    
    public function addImage()
	{
		$type = "image";
		$filepath = dirname(dirname(__FILE__))."/image/33.png";
		$filedata = array("media"  => "@".$filepath);
		//$url = "http://file.api.weixin.qq.com/cgi-bin/media/upload?access_token=" . ACCESS_TOKEN . "&type=$type";
		// 应该是这个url？ https://api.weixin.qq.com/cgi-bin/material/add_material?access_token=ACCESS_TOKEN
		$url = "https://api.weixin.qq.com/cgi-bin/material/add_material?access_token=" . ACCESS_TOKEN . "&type=$type";

		$result = request_post($url, $filedata); 
		echo $result; 
		// id   wptdc2AEc7V_tFYzTD1EMbeiIBESEpEzAUJgCcG_A9o
		/*function request_post($url, $data = null)
		{
		    $curl = curl_init();
		    curl_setopt($curl, CURLOPT_URL, $url);
		    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
		    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
		    if (!empty($data)){
		        curl_setopt($curl, CURLOPT_POST, 1);
		        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
		    }
		    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		    $output = curl_exec($curl);
		    curl_close($curl);
		    return $output;
		}*/
		//似乎一直在上传还是在干什么，超过5分钟也不成功
	    /*$url = "https://api.weixin.qq.com/cgi-bin/material/add_material?access_token=" . ACCESS_TOKEN . "&type=image";
	    file_put_contents("err.txt", $url . "<br />", FILE_APPEND);
	    $ch1 = curl_init ();
	    $timeout = 5;
	    $real_path="{$_SERVER['DOCUMENT_ROOT']}{$file_info['filename']}";
	    //$real_path=str_replace("/", "\\", $real_path);
	    $data= array("media"=>"@{$real_path}",'form-data'=>$file_info);
	    curl_setopt ( $ch1, CURLOPT_URL, $url );
	    curl_setopt ( $ch1, CURLOPT_POST, 1 );
	    curl_setopt ( $ch1, CURLOPT_RETURNTRANSFER, 1 );
	    curl_setopt ( $ch1, CURLOPT_CONNECTTIMEOUT, $timeout );
	    curl_setopt ( $ch1, CURLOPT_SSL_VERIFYPEER, FALSE );
	    curl_setopt ( $ch1, CURLOPT_SSL_VERIFYHOST, false );
	    curl_setopt ( $ch1, CURLOPT_POSTFIELDS, $data );
	    $result = curl_exec ( $ch1 );
	    curl_close ( $ch1 );
	    if(curl_errno()==0)
	    {
	        $result=json_decode($result,true);
	    //var_dump($result);
	        return $result['media_id'];
	    }
	    else 
	    {
	        return false;
	    }*/




//上传图片
/*$type = "image";
$filepath = dirname(__FILE__)."\winter.jpg";
$filedata = array("media"  => "@".$filepath);
$url = "http://file.api.weixin.qq.com/cgi-bin/media/upload?access_token=$access_token&type=$type";
$result = https_request($url, $filedata); 
var_dump($result); 
function https_request($url, $data = null)
{
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
    if (!empty($data)){
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
    }
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $output = curl_exec($curl);
    curl_close($curl);
    return $output;
}
*/





	}
}


?>