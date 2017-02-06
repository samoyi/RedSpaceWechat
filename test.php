<pre><?php

/*
 * 微信后台将消息、事件等推送到该文件
 *
 */

include('configuration.php'); // 公众号配置文件
include('publicFunctions.php'); // 公共函数  TODO 这个文件依赖configuration.php
include('WechatPushed.php'); // 获取微信后台推送信息




		
// 哪些openID没有领到购买后的指定卡券
// print_r( usersWithoutCard() );
function usersWithoutCard()
{
	// 获取数据库里购买了29和39蛋糕的订单数据行
	function getlCupCakeOrderRows()
	{
		require "class/MySQLiController.class.php";
		$MySQLiController = new MySQLiController( $dbr );
		$tableName = 'Wechat_Order';
		$where = 'id>498';
		$result = $MySQLiController->getRow($tableName, $where );	
		return $result;	
	}

	function ungottenCards($aOrderRows)
	{
		// 返回用户持有的该卡券组成的数组，如果没有则为空数组
		function getUserCardList($sOpenID, $sCardID="")
		{	
			$url = 'https://api.weixin.qq.com/card/user/getcardlist?access_token=' . ACCESS_TOKEN;
			if( isset($sCardID) )
			{
				$data = '{
				  "openid": "' . $sOpenID . '",
				  "card_id": "' . $sCardID . '"
				}';
			}
			else
			{
				$data = '{
				  "openid": "' . $sOpenID . '"
				}';
			}
			
			$aOrderRows = request_post($url, $data);
			return json_decode($aOrderRows)->card_list;
		}
	
		$num = 0;
		$aOpenIDwithoutCard = array();
		while($row = $aOrderRows->fetch_array())
		{
			$productName = $row['product_name'];
			$openID = $row['openid'];
			
			switch($productName)
			{
				case '【有罐芒果】52赫兹的罐子 双十二特惠':
				{
					if( !getUserCardList($openID, 'pkV_gjpK3MF0VEoKkXANEibvzuRI') )
					{
						$aOpenIDwithoutCard[] = 'pkV_gjpK3MF0VEoKkXANEibvzuRI===' . $openID;
						$num++;
					}
					break;
				}
				case '【有罐草莓】52赫兹的罐子 双十二特惠':
				{
					if( !getUserCardList($openID, 'pkV_gji0wWGr39lIeLXKkrsgauK0') )
					{
						$aOpenIDwithoutCard[] = 'pkV_gji0wWGr39lIeLXKkrsgauK0===' . $openID;
						$num++;
					}
					break;
				}
				case '【有罐提拉米苏】52赫兹的罐子 双十二特惠':
				{
					if( !getUserCardList($openID, 'pkV_gjkWQsJlozZTQzf7-Ct2gYzw') )
					{
						$aOpenIDwithoutCard[] = 'pkV_gjkWQsJlozZTQzf7-Ct2gYzw===' . $openID;
						$num++;
					}
					break;
				}
			}
		}
		return $aOpenIDwithoutCard;
	}
	
	$aOrderRows = getlCupCakeOrderRows();
	return ungottenCards($aOrderRows);	
}
  
$aOpenIDwithoutXmasCard = array(
    'pkV_gju1LzZYrUiYN5uczwJd9Mjo===okV_gjr2ZNRC5hTXt1kmYL5sRx18',
    'pkV_gjvp7GRymlDxhRoTjzYiJepk===okV_gjrcXMTCCQ7HVK6G2gaYnxjc',
    'pkV_gju1LzZYrUiYN5uczwJd9Mjo===okV_gjpJAnEA6PkPV4UepAzGPKwo',
    'pkV_gjijkV9i-a6c5d_fu2lUTs58===okV_gjuyaN4OtSIIpBjPJHqr9jq8',
    'pkV_gju1LzZYrUiYN5uczwJd9Mjo===okV_gjmebMSuktEEu8MgihTDIr-M',
    'pkV_gjijkV9i-a6c5d_fu2lUTs58===okV_gjphBQm7tMzL24Izv6Xlzlng',
    'pkV_gjvp7GRymlDxhRoTjzYiJepk===okV_gjsBVjCf-qkswJ-oaFrYWGYE'
);




// 给没有指定卡券的用户发指定卡券
/*
 * 参数是 usersWithoutCard 返回的数组
 */
sendCardToUserWithourCard($aOpenIDwithoutXmasCard); 
function sendCardToUserWithourCard($aOpenIDwithoutCard)
{
	$aSuccess = array();
	$aFail = array();
	include('class/CardMessager.class.php');
	$cardMessager = new CardMessager();
	foreach( $aOpenIDwithoutCard as $value )
	{
		$card_id = strtok($value, '===');
		$sOpenID = strtok('===');
		$result = $cardMessager->sendCardByOpenID( $card_id, $sOpenID);
		if( $nErr = $result->errcode )
		{
			$aFail[] = $card_id . '+++' . $sOpenID . '+++' . $nErr;
		}
		else
		{
			$aSuccess[] = $card_id . '+++' . $sOpenID;
		}
	}
	echo 'success_';
	print_r($aSuccess);
	echo 'fail_';
	print_r($aFail);
}

?></pre>