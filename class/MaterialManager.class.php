<?php

class MaterialManager
{
	
	public function getMaterials($sType, $nCount=10, $nOffset=0)
	{	
		$url = 'https://api.weixin.qq.com/cgi-bin/material/batchget_material?access_token=' . ACCESS_TOKEN;
		$data = '{
				    "type": "news",
				    "offset":' . $nOffset . ',
				    "count":' . $nCount . '
				}';
// "type":' . $sType . ',
		$result = request_post($url, $data); 
		return json_decode($result);
	} 

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