<?php

class MaterialManager
{
	    
    public function addImageGetImedaID($imageName)
	{
		$type = "image";
		$filepath = dirname(dirname(__FILE__)) . "/material/" . $imageName;
		$filedata = array("media"  => "@".$filepath);
		$url = "https://api.weixin.qq.com/cgi-bin/material/add_material?access_token=" . ACCESS_TOKEN . "&type=$type";

		$result = request_post($url, $filedata); 
		return json_decode($result)->media_id; 
	}
}


?>