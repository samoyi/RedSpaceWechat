<?php

	define("CONTENT_FROM_USER", $userMessage['userSentMessageContent']); // 用户发送的文本内容


	require 'textHander/keywords.php'; // 所有设定的关键词都在这个文件里

	if( !empty($aComplicatedKeywords) &&  in_array(CONTENT_FROM_USER, $aComplicatedKeywords) )
	{	// 如果用户输入的文字是复杂回复类型的关键词之一
		require 'textHander/complicatedTextHander.php';
	}
	elseif( in_array(CONTENT_FROM_USER, $aBasicKeywords) )
	{
		// 如果用户输入的文字是简单回复类型的关键词之一
		require 'textHander/basicTextHandler.php';
	}
	else
	{
		require 'textHander/noKeyWordsMatch.php';
	}

?>
