<?
/*
 * 购买指定商品后会自动赠送某款卡券，但对于之前一段时间没有和公众号交互而
 * 直接从微信群发文章中进入购买的用户来说无法收到。需要之后用户回复关键词
 * 来领取自己购买的商品对应的卡券
 * 
 * 例如一组水果蛋糕有赠券，分别是：买一个大苹果蛋糕赠一个小苹果蛋糕券，买
 * 一个大香蕉蛋糕赠一个小香蕉蛋糕券。一个人在购买后，只需要回复设定的关键
 * 词，例如设为“水果蛋糕”，则他如果之前买了苹果的就会获得一个小苹果蛋糕券
 * ，如果买了香蕉的则获得一个小香蕉蛋糕券。
 *
 * 注意：
 *   1. 必须将卡券设定为一个用户只能领一次。所以即使他多次发送关键词并多
 * 		次收到卡券推送，但他也只能领一次。
 *   2. 基于第一条原因，如果用户买了两个同样的产品，它也实际领到一张券。所
 *      以这一功能一般适用于限购一款的情况。
 */

function sendKeywordsGetPromotionalCard($aProductNameToCardID, $sNoOrderTip)
{	
	if( !class_exists("MySQLiController", false) )
	{
		require PROJECT_ROOT . 'class/MySQLiController.class.php';
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
				if( !class_exists("CardMessager", false) )
				{
						include('class/CardMessager.class.php');
				}
				$cardMessager = new CardMessager();
				$cardMessager->sendCardByOpenID($value, USERID, 'manage/sendCardResult.txt' );
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
		 define("CONTENT", '没有查询到您的购买记录');
		 $messageManager->responseMsg( 'text' );
	}
	elseif( $isSend )
	{
		$messageManager->responseMsg( 'null' );
	}
	else
	{
		define("CONTENT", $sNoOrderTip);
		$messageManager->responseMsg( 'text' );
	}
}

?>