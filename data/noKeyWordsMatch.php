<?php


	/*
	 * 如果检测到消息类型是文字，则加载该文件

	 */

	if( CONTENT_FROM_USER==='jd' )
	{
		define("CONTENT", 'jdjdjd');
		$messageManager->responseMsg( 'text' );
	}
	elseif( date('G')>(OFF_DUTY_TIME-1) || date('G')<ON_DUTY_TIME)//客服下班时间，自动回复客服已下班
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

?>
