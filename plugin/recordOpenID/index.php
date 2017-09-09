<?php

	define('DB_TABLE', 'Wechat_OpenID');
	
	// 不是事件消息 或者 是事件消息但不是这三种事件
	if( (!defined('EVENT_TYPE')) || (EVENT_TYPE !== 'unsubscribe' && EVENT_TYPE !== 'merchant_order' && EVENT_TYPE !== 'TEMPLATESENDJOBFINISH') ) // 取消关注事件会发送空的数据，因此会清空原数据
	{
		function noteUseBasicInfo()
	    {

			if( !class_exists("MySQLiController", false) )
			{
				require PROJECT_ROOT . 'plugin/MySQLiController.class.php';
			}
	        $MySQLiController = new MySQLiController( $dbr );
	        $type = defined('EVENT_TYPE') ? EVENT_TYPE : MESSAGE_TYPE;

			$where = 'openID="' . USERID . '"';

	        $aRowInDB = $MySQLiController->getRow(DB_TABLE, $where);

			require PROJECT_ROOT . 'class/UserManager.class.php';
			$UserManager = new UserManager();
			$userInfo = $UserManager->getUserInfo(USERID);
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
		noteUseBasicInfo();
	}
	elseif( EVENT_TYPE === 'unsubscribe' ) // 取消关注的只修改是否关注的那一栏数据
	{
		require PROJECT_ROOT . 'plugin/MySQLiController.class.php';
	    $MySQLiController = new MySQLiController( $dbr );
		$MySQLiController->updateData(
						DB_TABLE,
						array('isSubscribing', 'modifyTime', 'type'),
						array(0, date("Y-m-d G:i:s"), 'unsubscribe'),
						'openID="' . USERID . '"');

	}

?>
