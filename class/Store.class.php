<pre><?php

class StoreManager
{

	public function queryStoreList()
	{
		$url = 'https://api.weixin.qq.com/cgi-bin/poi/getpoilist?access_token=' . ACCESS_TOKEN;
		$data = '{
					"begin":0,
					"limit":50
				}';

		$aBusinessList = array();
		$aBusinessListThisBatch = array();
		do
		{
			$aBusinessListThisBatch = json_decode(request_post($url, $data))->business_list;
			$aBusinessList = array_merge($aBusinessList, $aBusinessListThisBatch);
		}
		while( count($aBusinessListThisBatch) >= 50 );

		return $aBusinessList;
	}
}



?></pre>