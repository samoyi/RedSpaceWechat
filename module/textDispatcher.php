<?php

	define("CONTENT_FROM_USER", $userMessage['userSentMessageContent']); // 用户发送的文本内容


	require PROJECT_ROOT . 'data/keywords.php';

	if( !empty($aCustomKeywords) &&  in_array(CONTENT_FROM_USER, $aCustomKeywords) )
	{
		require 'textHander/complicatedTextHander.php';
	}
	elseif( in_array(CONTENT_FROM_USER, $aKeywords) )
	{
		require 'textHander/basicTextHandler.php';
	}
	else
	{
		require 'textHander/noKeyWordsMatch.php';
	}

?>
