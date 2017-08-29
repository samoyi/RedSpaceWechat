<?php


	/*
	 * 如果检测到消息类型是文字，则加载该文件

	 */

	if( CONTENT_FROM_USER==='jd' )
	{
		define("CONTENT", 'jdjdjd');
		$messageManager->responseMsg( 'text' );
	}
	else
	{
		requirePlugin('offDutyAutoReply');
	}

?>
