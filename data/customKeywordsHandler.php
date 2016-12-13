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
			file_put_contents('manage/autoReplyState.json', json_encode($JSONObj) );
			break;
		}
		case '提货券':
		{
			require "plugin/sendKeywordsGetPromotionalCard.php";
			$aProductNameToCardID = array(
				'【圣诞欢乐颂】圣诞一起来尝鲜 原价49元 微信订购立减10元'=>'pkV_gjvp7GRymlDxhRoTjzYiJepk',
				'【圣诞黑魔法】圣诞一起来尝鲜 原价49元 微信订购立减10元'=>'pkV_gjijkV9i-a6c5d_fu2lUTs58',
				'【雪域圣诞】圣诞一起来尝鲜 原价49元 微信订购立减10元'=>'pkV_gju1LzZYrUiYN5uczwJd9Mjo'
			);
			$sNoOrderTip = '没有查询到您的圣诞蛋糕购买记录';
			sendKeywordsGetPromotionalCard($aProductNameToCardID, $sNoOrderTip);
            break;
		}
		case '罐子蛋糕':
		{
			require "plugin/sendKeywordsGetPromotionalCard.php";
			$aProductNameToCardID = array(
				'【有罐芒果】52赫兹的罐子 双十二特惠'=>'pkV_gjpK3MF0VEoKkXANEibvzuRI',
				'【有罐草莓】52赫兹的罐子 双十二特惠'=>'pkV_gji0wWGr39lIeLXKkrsgauK0',
				'【有罐提拉米苏】52赫兹的罐子 双十二特惠'=>'pkV_gjkWQsJlozZTQzf7-Ct2gYzw'
			);
			$sNoOrderTip = '没有查询到您的罐子蛋糕购买记录。';
			sendKeywordsGetPromotionalCard($aProductNameToCardID, $sNoOrderTip);
			break;
		}
	}

	
	
?>