<?php

	define("CONTENT_FROM_USER", $userMessage['userSentMessageContent']); // 用户发送的文本内容

	define(ON_DUTY_TIME, 9);
	define(OFF_DUTY_TIME, 17);
	define(OFF_DUTY_AUTOREPLY, file_get_contents("manage/offDutyAutoreplyText.json"));


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
	}
	else
	{
		// noKeyWordMatch($messageManager);
		require PROJECT_ROOT . 'data/noKeyWordsMatch.php';
	}

?>
