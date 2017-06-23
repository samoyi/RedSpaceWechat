<?php
/* 
 *  处理用户发送的消息
 *  最后如果推送的不是消息而是事件，转入事件处理
 *
 */


/* 以下为数据区域 */
	define(ON_DUTY_TIME, 9);
	define(OFF_DUTY_TIME, 17); 
	define(OFF_DUTY_AUTOREPLY, file_get_contents("manage/offDutyAutoreplyText.json")); 
// TODO 这里常量定义如果放在keywords文件中，非关键字消息会自动回复 'OFF_DUTY_AUTOREPLY'




// 将相关数据发送到记录用户信息的脚本
sendDateToNoteUserInfoScript();
function sendDateToNoteUserInfoScript()
{
	$sFinalUrl = "";
	$sScriptUrl = "http://red-space.cn/wechat/manage/nodeUserInfo.php";
	
	$sTokenArg = "token=" . ACCESS_TOKEN;
	$sUserOpenID = "userOpenID=" . USERID;
	$sHostIDArg = "hostID=" . HOSTID;
	$sUserSentMessageContentArg = "userSentMessageContent=" . CONTENT_FROM_USER;
	$sUserSentMessageTypeArg = "userSentMessageType=" . MESSAGE_TYPE;
	$sEventTypeArg = "eventType=" . EVENT_TYPE;
	
	$sFinalUrl = $sScriptUrl . "?" .  $sTokenArg . "&" . $sUserOpenID . "&" 
				. $sHostIDArg . "&" . $sUserSentMessageContentArg . "&" 
				. $sUserSentMessageTypeArg . "&" . $sEventTypeArg;
	
	httpGet($sFinalUrl);
}

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
			noKeyWordMatch($messageManager);
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