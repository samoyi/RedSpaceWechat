<?php
	
	
	/*
	 * 该文件提供自定义的更复杂的关键词处理
	 * 输入字符串保存在 KEYWORD 常量中
	 *
	 *
	 */
	
	if( stristr(KEYWORD, 'wifi') )
	{
		define("CONTENT", MESSAGE_fOR_WIFI_KEYWORD);
		$messageManager->responseMsg( 'text' );
	}
	else
	{
		$cardKeywordsID = json_decode(file_get_contents("manage/JSONData/cardKeywords.json"), true);
		if( $cardKeywordsID[KEYWORD] )
		{
			include('class/CardMessager.class.php');
			$cardMessager = new CardMessager();
			$outReplayText = '亲亲，优惠券天天抢活动已经结束，请持续关注红房子微信公众平台，福利多多哦~';
			$cardMessager->getCardByKeyWords( $cardKeywordsID[KEYWORD], $outReplayText, $messageManager);
			$messageManager->responseMsg( 'text' );
			break;
		}
		switch( KEYWORD )
		{
			case '测试回复314' :
			{    

				define("CONTENT", '测试回复');
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
				file_put_contents('manage/autoReplyState.json', json_encode($JSONObj) );
				break;
			}
			case '刷新接口' : 
			{    //曾出现过AccessToken很快过期的情况，管理员回复这个可以立刻刷新
				$newak = refreshAccessToken();
				define("CONTENT", '刷新完成');
				$messageManager->responseMsg( 'text' );
				break;
			}
			default: // 如果用户发送的不是已设定的关键词
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

				//这里会发送空字符串，也就是不回复。但是要注意这之后不能再发送其他东西，包括修改自定义菜单之类的，否则发送的就不是空字符串，且也不是合理的回复格式，就会显示暂时无法服务。
			}
		}
	}
	
	
?>