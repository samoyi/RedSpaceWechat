<?php

class MaterialManager
{

	//获取卡券详情
    /*
     * 调用微信【获取素材列表】接口
     * $nCount参数为一次获取的素材数量，最多20个
     */
	public function getMaterials($sType, $nCount=10, $nOffset=0)
	{
		$nCount = $nCount>20 ? 20 : $nCount;
		$url = 'https://api.weixin.qq.com/cgi-bin/material/batchget_material?access_token=' . ACCESS_TOKEN;
		$data = '{
				    "type": "' . $sType . '",
				    "offset":' . $nOffset . ',
				    "count":' . $nCount . '
				}';
		$result = request_post($url, $data);
		return json_decode($result);
	}


	// 上传永久素材并获得ID
    /*
     * 调用微信【新增其他类型永久素材】接口
     */
    public function addMaterialAndGetMediaID($type, $filepath)
	{
		$filedata = array("media"  => "@".$filepath);
		$url = "https://api.weixin.qq.com/cgi-bin/material/add_material?access_token=" . ACCESS_TOKEN . "&type=$type";
		$result = request_post($url, $filedata);
		return json_decode($result)->media_id;
	}


}


?>
