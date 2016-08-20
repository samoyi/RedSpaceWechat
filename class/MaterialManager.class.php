<?php

class MaterialManager
{
	    
    function addImage($image_info)
	{
	    $url="https://api.weixin.qq.com/cgi-bin/material/add_material?access_token=" . ACCESS_TOKEN . "&type=image";
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
	    }
	}
}


?>