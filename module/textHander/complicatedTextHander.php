<?php


	/*
	 * 该文件提供自定义的更复杂的关键词处理
	 *
	 */

	switch( CONTENT_FROM_USER )
	{
		case '刷新接口' :
		{
			refreshAccessToken();
			$messageManager->responseTextMsg('刷新完成');
			break;
		}
		case '切换自动回复314' :
		{
			$JSONObj = json_decode( file_get_contents('manage/switchAutoReply/autoReplyState.json') );
			if( 'on' === $JSONObj->autoReplyByTime )
			{
				$JSONObj->autoReplyByTime = 'off';
				define("CONTENT", '下班时段自动回复已关闭');
			}
			else
			{
				$JSONObj->autoReplyByTime = 'on';
				define("CONTENT", '下班时段自动回复已打开');
			}
			file_put_contents('manage/switchAutoReply/autoReplyState.json', json_encode($JSONObj));
			$messageManager->responseTextMsg(CONTENT);
			break;
		}
		case '测试':
		{
			break;
		}
	}

?>
