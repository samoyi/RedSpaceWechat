<?php
/*
 * 购买指定商品赠送卡券
 *
 * 从数据库查询用户是否买过指定商品，如果有，发送对应的卡券
 *
 * 购买指定商品后会自动赠送某款卡券，但对于之前一段时间没有和公众号交互而直接从微信
 * 群发文章中进入购买的用户来说无法收到。需要之后用户回复关键词来领取自己购买的商品
 * 对应的卡券
 *
 * 例如一组水果蛋糕有赠券，分别是：买一个大苹果蛋糕赠一个小苹果蛋糕券，买一个大香蕉
 * 蛋糕赠一个小香蕉蛋糕券。一个人在购买后只需要回复设定的关键词，例如设为“水果蛋糕”，
 * 则他如果之前买了大苹果蛋糕的就会获得一个小苹果蛋糕券 ，如果买了大香蕉蛋糕的则获得
 * 一个小香蕉蛋糕券。
 *
 * 注意：
 *   1. 必须将卡券设定为一个用户只能领一次。这样即使他多次发送关键词并多次收到卡券推
 * 		送，也只能领一次。
 *   2. 基于第一条原因，如果用户买了两个同样的产品，他也实际领到一张券。所以这一功能
 *      一般适用于限购一款的情况。
 */

// 调用示例
// $aProductNameToCardID = array(
// 	'大苹果蛋糕'=>'小苹果蛋糕ID',
// 	'大香蕉蛋糕'=>'小香蕉蛋糕ID'
// );
// $sNoOrderTip = '没购买过大苹果蛋糕或大香蕉蛋糕';
// sendKeywordsGetPromotionalCard($aProductNameToCardID, $sNoOrderTip);


function sendKeywordsGetPromotionalCard($aProductNameToCardID, $sNoOrderTip)
{
	if( !class_exists("MySQLiController", false) )
	{
		require PROJECT_ROOT . 'plugin/MySQLiController.class.php';
	}
	$MySQLiController = new MySQLiController( $dbr );
	$tableName = 'Wechat_Order';
	$where = 'openid="' .USERID . '"';
	$result = $MySQLiController->getRow($tableName, $where );

	$orderNum = 0;
	$isSend = false;
	while( $row = $result->fetch_array() )
	{
		$orderNum++;
		$sProductNam = $row['product_name'];

		forEach($aProductNameToCardID as $key=>$value)
		{
			if( $sProductNam === $key )
			{
				if( !class_exists("CardManager", false) )
				{
					include('class/CardManager.class.php');
				}
				$cardManager = new CardManager();
				$cardManager->sendCard($value, USERID, 'manage' .MANAGE_DIR_RAND. '/sendCardResult.txt' );
				$isSend = true;
			}
		}
	}

	if( !class_exists("messageManager", false) )
	{
		include('class/messageManager.class.php');
	}
	$messageManager = new messageManager;
	if( $orderNum===0 )
	{
		 $messageManager->responseTextMsg('没有查询到您的购买记录');
	}
	elseif( $isSend )
	{
		$messageManager->responseNull();
	}
	else
	{
		$messageManager->responseTextMsg($sNoOrderTip);
	}
}

?>
