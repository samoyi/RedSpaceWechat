<?php


	/*
	 * 该文件提供自定义的更复杂的关键词处理
	 *
	 *
	 *
	 */


	switch( CONTENT_FROM_USER )
	{
		case '刷新接口' :
		{
			refreshAccessToken();
			define("CONTENT", '刷新完成');
			$messageManager->responseMsg( 'text' );
			break;
		}
		case '切换自动回复314' :
		{
			include('manage/manager.php');
			$manager = new Manager();
			$autoReplyByTimeState = $manager->getAutoReplyByTimeState();
			$JSONObj = json_decode( file_get_contents('manage/autoReplyState.json') );
			if( 'on' === $autoReplyByTimeState )
			{
				$JSONObj->autoReplyByTime = 'off';
				define("CONTENT", '下班时段自动回复已关闭');
			}
			else
			{
				$JSONObj->autoReplyByTime = 'on';
				define("CONTENT", '下班时段自动回复已打开');
			}

			$messageManager->responseMsg( 'text' );
			break;
		}
		case '测试':
		{
			include('class/CardMessager.class.php');
			$CardMessager = new CardMessager;
			file_put_contents('err.txt',' $result');
			$result = $CardMessager->consumeCard( USERID, 'pkV_gjr_Qr4I_FdLKj_yuR01mk44' );
			file_put_contents('err.txt', $result);
			define("CONTENT", '测试5');
			$messageManager->responseMsg( 'text' );
			break;
		}
	}

?>
