<?php
/* 
 *  处理用户发送的消息
 *  最后如果推送的不是消息而是事件，转入事件处理
 *
 */


/* 以下为数据区域 */
	define(ON_DUTY_TIME, 9);
	define(OFF_DUTY_TIME, 18); 
	define(OFF_DUTY_AUTOREPLY, '您的留言已被标记，客服将在上午九点后回复您。'); 
// TODO 这里常量定义如果放在keywords文件中，非关键字消息会自动回复 'OFF_DUTY_AUTOREPLY'



// 记录用户交互记录
if( EVENT_TYPE !== 'unsubscribe' && EVENT_TYPE !== 'merchant_order' && EVENT_TYPE !== 'TEMPLATESENDJOBFINISH' ) // 取消关注事件会发送空的数据，因此会清空原数据
{
	require 'class/UserManager.class.php';
	$UserManager = new UserManager();
	$UserManager->noteUseBasicInfo();
}
elseif( EVENT_TYPE === 'unsubscribe' ) // 取消关注的只修改是否关注的那一栏数据
{
	require PROJECT_ROOT . 'class/MySQLiController.class.php';
    $MySQLiController = new MySQLiController( $dbr );
	$MySQLiController->updateData(
					DB_TABLE, 
					array('isSubscribing', 'modifyTime', 'type'), 
					array(0, date("Y-m-d G:i:s"), 'unsubscribe'), 
					'openID="' . USERID . '"');

}
/* require 'class/UserManager.class.php';
	$UserManager = new UserManager();
	$UserManager->noteUseBasicInfo(); */

/* 以下为逻辑区域 */
switch(MESSAGE_TYPE)
{   
    case "text":
    {   
		//$cardKeywordsID = json_decode(file_get_contents("manage/JSONData/cardKeywords.json"), true);
		
		require PROJECT_ROOT . 'data/keywords.php';
		
		if( !empty($aCustomKeywords) &&  in_array(CONTENT_FROM_USER, $aCustomKeywords) )
		{	
			require PROJECT_ROOT . 'data/customKeywordsHandler.php';
		}
		elseif( in_array(CONTENT_FROM_USER, $aKeywords) )
		{	
			$handlerData = $aKeywordHandler[CONTENT_FROM_USER];
			//$nReplyAmount = count($handlerData);
			//$bIsAutoReply = false; // 如果只发客服消息则最后会提示公众号无法服务，必须要发一个自动回复消息
			
			$nIndex = 0;// 第一遍发自动回复，之后如果再有就发客服消息
			file_put_contents("err.txt", json_encode($handlerData) . "\n\n", FILE_APPEND);
			foreach($handlerData as $key=>$value)
			{	
				// 如果key的最后一位是最为区分的数字，则删掉该数字
				$sLastChar = substr($key, -1);
				$key = is_numeric($sLastChar) ? strtok( $key, $sLastChar) : $key; 
				
				if( $nIndex++ === 0 )
				{	
					switch( $key )
					{
						case 'sendTextMessage':
						{	
							define("CONTENT", $value);
							$messageManager->responseMsg( 'text' );
							break;
						}
						case 'sendArticalMessage':
						{	
							$messageManager->sendArticalMessage($value);
							$bIsAutoReply = true;
							break;
						}
					}
				}
				else
				{	
					switch( $key )
					{
						case 'sendTextMessage':
						{	
							$messageManager->sendTextCSMessage(USERID, $value);
							break;
						}
						case 'sendArticalMessage':
						{	
							$messageManager->sendArticalCSMessage(USERID, $value);
							break;
						}
					}
				}
			}
			/* if( !$bIsAutoReply )
			{
				define("CONTENT", $value);
				$messageManager->responseMsg( 'null' ); 
			} */
		}
		else
		{
			noKeyWordMath($messageManager);
		}
		break;
    }
    case 'event':
    {   
        include('eventAutoReply.php');
        break;
    }
    default: // 既不是文字消息也不是事件推送
    {   
        if( date('G')>(OFF_DUTY_TIME-1) || date('G')<ON_DUTY_TIME)//客服下班时间，自动回复客服已下班
        {
            include('manage/manager.php');
            $manager = new Manager();
            // 查看客服是否开启了下班时间自动回复功能
            $autoReplyByTimeState = $manager->getAutoReplyByTimeState();
            if( 'on' === $autoReplyByTimeState )
            {
                define("CONTENT", OFF_DUTY_AUTOREPLY);
                $messageManager->responseMsg( 'text' );
            }
            else
            {
                $messageManager->responseMsg( 'null' );
            }
        }
        else
        {
            $messageManager->responseMsg( 'null' );
        }
    }
}

?>