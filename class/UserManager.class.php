<?php

class UserManager
{

    //获得推送信息
    protected $userOpenID;
    protected $userNickname;
    protected $userSex;
    protected $userAge;
    protected $userCity;
    protected $userSentMessageType;
    protected $userSentMessageContent;

    function __get( $property )
    {
        if( 'nTotalUserNumber' === $property )
        {
            $oUserList = json_decode( $this->getUserList() );
            return $oUserList->total;
        }
    }

	// 获取临时二维码ticket
	protected function getTempQRCodeTicket($nExpireSeconds, $nSceneID )
	{
		$url = 'https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token=' . ACCESS_TOKEN;
		$data = '{
			"expire_seconds": ' .$nExpireSeconds. ',
			"action_name": "QR_SCENE",
			"action_info": {
								"scene": {"scene_id": ' .$nSceneID. '}
							}
		}';
		return json_decode(request_post($url, $data))->ticket;
	}


    // 获取关注者的openID数组，每次最多获得一万条
    protected function getUserList($sNextOpenID="")
    {
        $sNextOpenIDPara = $sNextOpenID ? "&next_openid=$sNextOpenID" : "";
        $url = 'https://api.weixin.qq.com/cgi-bin/user/get?access_token=' . ACCESS_TOKEN . $sNextOpenIDPara;
        return json_decode( $result = request_get($url) );
    }

	// 获取用户总数
	public function getUserAmount()
	{
		return $this->getUserList()->total;
	}


    // 用户交互时的用户基本信息记录进数据库
    public function noteUseBasicInfo()
    {
		$con = ACCESS_TOKEN . "\n" .
				USERID . "\n" .
				HOSTID . "\n" .
				CONTENT_FROM_USER . "\n" .
				MESSAGE_TYPE . "\n" .
				DB_TABLE . "\n" .
				DB_ADDRESS . "\n" .
				DB_USER . "\n" .
				DB_PASSWORD . "\n" .
				DB_NAME . "\n" .
				EVENT_TYPE;

		if( !class_exists("MySQLiController", false) )
		{
			require PROJECT_ROOT . 'class/MySQLiController.class.php';
		}
        $MySQLiController = new MySQLiController( $dbr );
        $type = defined('EVENT_TYPE') ? EVENT_TYPE : MESSAGE_TYPE;

		$where = 'openID="' . USERID . '"';

        $aRowInDB = $MySQLiController->getRow(DB_TABLE, $where);

		$userInfo = $this->getUserInfo(USERID);
		$bIsSubscribing = $userInfo->subscribe;
		$sNickname = $userInfo->nickname;
		$sSex = $userInfo->sex;
		$sCountry = $userInfo->country;
		$sProvince = $userInfo->province;
		$sCity = $userInfo->city;
		$sHeadImgUrl = $userInfo->headimgurl;

		$sAddress = $sCountry . $sProvince . $sCity;

        if( $aRowInDB->fetch_array( )) // 如果数据库中已经有该用户的数据行
        {
            $MySQLiController->updateData(
					DB_TABLE,
					array('type', 'modifyTime', 'nickname', 'sex', 'country', 'province', 'city', 'headimgurl', 'isSubscribing'),
					array($type, date("Y-m-d G:i:s"), $sNickname, $sSex, $sCountry, $sProvince, $sCity, $sHeadImgUrl, $bIsSubscribing),
					'openID="' . USERID . '"');
        }
        else
        {
            $aRow = array('0, "' . USERID . '", "' . $type . '", "' . date("Y-m-d G:i:s") . '"');
			$aCol = array('openID', 'type', 'modifyTime', 'nickname', 'isSubscribing', 'sex', 'headimgurl', 'city', 'province', 'country');
			$aValue = array(USERID, $type, date("Y-m-d G:i:s"), $sNickname, $bIsSubscribing, $sSex, $sHeadImgUrl, $sCity, $sProvince, $sCountry);

			$MySQLiController->insertRow(DB_TABLE, $aCol, $aValue);

        }
        $dbr->close();
    }


	// 下订单时订单详情中的用户信息记录进数据库
    public function noteUseOrderInfo($orderDetail)
    {
		$sReceiverProvince = $orderDetail["receiver_province"];
		$sReceiverCity = $orderDetail["receiver_city"];
		$sReceiverZone = $orderDetail["receiver_zone"];
		$sReceiverAddress = $orderDetail["receiver_address"];
		$sReceiverName = $orderDetail["receiver_name"];
		$sReceiverTel = $orderDetail["receiver_mobile"];

		if( !class_exists("MySQLiController", false) )
		{
			require PROJECT_ROOT . 'class/MySQLiController.class.php';
		}
        $MySQLiController = new MySQLiController( $dbr );
		$MySQLiController->updateData(
				DB_TABLE,
				array('type', 'modifyTime', 'receiver_name', 'tel', 'receiver_province', 'receiver_city', 'receiver_zone', 'receiver_address'),
				array('merchant_order', date("Y-m-d G:i:s"), $sReceiverName, $sReceiverTel, $sReceiverProvince, $sReceiverCity, $sReceiverZone, $sReceiverAddress),
				'openID="' . USERID . '"');
        $dbr->close();
    }

    // 将最近100个用户交互记录输出位表格
    public function echoRecentUserInteractionTable()
    {
        require PROJECT_ROOT . 'class/MySQLiController.class.php';
        $MySQLiController = new MySQLiController( $dbr );

        $result = $MySQLiController->getDataByRank(DB_TABLE, "modifyTime");
        $dbr->close();

        echo '<table border="1">
                <tr>
                    <th>昵称</th>
                    <th>OpenID</th>
                    <th>交互类型</th>
                    <th>最新交互时间</th>
                </tr>';
        for($i=0; $i<100; $i++)
        {
            $arr = $result->fetch_array();
            $nickname = $this->getUserInfo($arr["openID"])->nickname;
            echo '<tr><td>' . $nickname . '</td><td>' . $arr["openID"] . '</td><td>' . $arr["type"] . '</td><td>' . $arr["modifyTime"] . '</td></tr>';
        }
        echo '</table>';
    }



    public function getUserInfo($sOpenID)
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/user/info?access_token=' . ACCESS_TOKEN . '&openid=' . $sOpenID . '&lang=zh_CN';
        return json_decode( $result = request_get($url) );
    }

    // 批量获取用户信息
    public function getUserInfoBatch($aOpenID)
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/user/info/batchget?access_token=' . ACCESS_TOKEN;
        $aOpenIDChunk = array_chunk($aOpenID, 100); // 该接口一次最多查询100个

        $aUserInfoChunk = array();
        $aUserList = array();
        $aUserInfoList = array();
        foreach($aOpenIDChunk as $value)
        {
            foreach($value as $innerValue)
            {
                $aUserList[] = json_decode('{"openid": "' . $innerValue . '", "lang": "zh-CN"}');
            }
            $data = '{
                "user_list": ' . json_encode($aUserList) . '
            }';

            $result = json_decode(request_post($url, $data));
            $aUserInfoList = array_merge($aUserInfoList, $result->user_info_list);
            unset( $aUserList );
        }
        return $aUserInfoList;
    }

    // 过滤保留 getUserInfoBatch 函数返回的数组
    // 第二个参数是关联数组，一个数组项的键对应用户信息中的某个属性，只有该属性值和该键值完全相同才会保留该用户信息
    // 并且每一个数组项都要对应才行。
    // 例如传入 array("sex"=>2, "city"=>"Xinyang") 将从$aUserInfoArray中 挑选性别女且城市为Xinyang的予以保留
    public function filterUserInfoArray( $aUserInfoArray, $aFilterAssociativeArray )
    {
        foreach($aUserInfoArray as $key=>$value) // 循环每一个用户信息
        {
            foreach($aFilterAssociativeArray as $innerKey=>$innerValue) // 循环每一个过滤器数组项
            {
                if( $innerValue !== $value->$innerKey ) // 只要有一个过滤器的值不在当前用户信息中，就从用户信息数组中删除这一条
                {
                    unset($aUserInfoArray[$key]);
                }
            }
        }
        return $aUserInfoArray;
    }

    // 从 getUserInfoBatch 函数返回的数组提取某一项组成一个数组
    public function getUserInfoPropertyArray($aUserInfoArray, $sProperty)
    {
        $aPropertyArray = array();
        foreach($aUserInfoArray as $value)
        {
            $aPropertyArray[] = $value->$sProperty;
        }
        return $aPropertyArray;
    }


    public function getOpenIDArray()
    {
        $aOpenIDArray = array();
        $sNextOpenID = "";
        do
        {
            $aPartialUserList = $this->getUserList($sNextOpenID);
            $aPartialOpenID = $aPartialUserList->data->openid;
            if(!$aPartialOpenID) // 已经获取完了
            {
                break;
            }
            $aOpenIDArray = array_merge($aOpenIDArray, $aPartialOpenID);
            $sNextOpenID = $aPartialUserList->next_openid;
        }
        while( $aPartialUserList->count > 0);

        return $aOpenIDArray;
    }


	public function getTempQRCodeURL($nExpireSeconds, $nSceneID)
	{
		$ticket = $this->getTempQRCodeTicket($nExpireSeconds, $nSceneID);
		return 'https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=' . $ticket ;
	}

}


?>
