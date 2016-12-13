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
		    if( !class_exists("MySQLiController", false) )
            {
                require PROJECT_ROOT . 'class/MySQLiController.class.php';
            }
            $MySQLiController = new MySQLiController( $dbr );
            $tableName = 'Wechat_Order';
            $where = 'openid="' .USERID . '"';
            $result = $MySQLiController->getRow($tableName, $where );
            $row = $result->fetch_array();

            if( empty($row) )
            {
                 define("CONTENT", '没有查询到您的购买记录');
                 $messageManager->responseMsg( 'text' );
                 break;
            }
            $cakeNameFrag = mb_substr( $row['product_name'], 1, 4, 'UTF-8');
            include('class/CardMessager.class.php');
            $cardMessager = new CardMessager();
            switch($cakeNameFrag)
            {
               case '圣诞欢乐':
                {
                    $cardMessager->sendCardByOpenID( "pkV_gjvp7GRymlDxhRoTjzYiJepk", USERID, 'manage/sendCardResult.txt' );
                    $messageManager->responseMsg( 'null' );
                    break;
                }
                case '圣诞黑魔':
                {
                    $cardMessager->sendCardByOpenID( "pkV_gjijkV9i-a6c5d_fu2lUTs58", USERID, 'manage/sendCardResult.txt' );
                    $messageManager->responseMsg( 'null' );
                    break;
                }
                case '雪域圣诞':
                {
                    $cardMessager->sendCardByOpenID( "pkV_gju1LzZYrUiYN5uczwJd9Mjo", USERID, 'manage/sendCardResult.txt' );
                    $messageManager->responseMsg( 'null' );
                    break;
                }
                default:
                {
                    define("CONTENT", '没有查询到您的圣诞蛋糕购买记录');
                    $messageManager->responseMsg( 'text' );
                }
            }
            break;
		}
		case '罐子蛋糕':
        {
            if( !class_exists("MySQLiController", false) )
            {
                require PROJECT_ROOT . 'class/MySQLiController.class.php';
            }
            $MySQLiController = new MySQLiController( $dbr );
            $tableName = 'Wechat_Order';
            $where = 'openid="' .USERID . '"';
            $result = $MySQLiController->getRow($tableName, $where );
            $row = $result->fetch_array();

            if( empty($row) )
            {
                 define("CONTENT", '没有查询到您的购买记录');
                 $messageManager->responseMsg( 'text' );
                 break;
            }
            $cakeNameFrag = mb_substr( $row['product_name'], 1, 4, 'UTF-8');
            include('class/CardMessager.class.php');
            $cardMessager = new CardMessager();
            switch($cakeNameFrag)
            {
               case '有罐芒果':
               {
                   $cardMessager->sendCardByOpenID( "pkV_gjpK3MF0VEoKkXANEibvzuRI", USERID, 'manage/sendCardResult.txt' );
                   $messageManager->responseMsg( 'null' );
                   break;
               }
               case '有罐草莓':
               {
                   $cardMessager->sendCardByOpenID( "pkV_gji0wWGr39lIeLXKkrsgauK0", USERID, 'manage/sendCardResult.txt' );
                   $messageManager->responseMsg( 'null' );
                   break;
               }
               case '有罐提拉':
               {
                   $cardMessager->sendCardByOpenID( "pkV_gjkWQsJlozZTQzf7-Ct2gYzw", USERID, 'manage/sendCardResult.txt' );
                   $messageManager->responseMsg( 'null' );
                   break;
               }
                default:
                {
                    define("CONTENT", '没有查询到您的罐子蛋糕购买记录');
                    $messageManager->responseMsg( 'text' );
                }
            }
            break;
        }
	}

	
	
?>