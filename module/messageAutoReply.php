<?php
/* 
 *  处理用户发送的消息
 *  最后如果推送的不是消息而是事件，转入事件处理
 *
 */


/* 以下为数据区域 */
define(MESSAGE_fOR_WIFI_KEYWORD,  '您所在的门店WIFI密码为：redspace');

define(ON_DUTY_TIME, 9);
define(OFF_DUTY_TIME, 18); 
define(OFF_DUTY_AUTOREPLY, '您的留言已被标记，客服将在上午九点后回复您。'); 




// 记录用户交互记录
require 'class/UserManager.class.php';
$UserManager = new UserManager();
$UserManager->noteUserInteraction();

/* 以下为逻辑区域 */
switch(MESSAGE_TYPE)
{   
    case "text":
    {   
		//$cardKeywordsID = json_decode(file_get_contents("manage/JSONData/cardKeywords.json"), true);
		
		require PROJECT_ROOT . 'data/keywords.php';

		if( $aCustomKeywords &&  in_array(CONTENT_FROM_USER, $aCustomKeywords) )
		{	
			require PROJECT_ROOT . 'data/customKeywordsHandler.php';
		}
		elseif( array_key_exists(CONTENT_FROM_USER, $keywords) )
		{	
			$handlerData = $keywords[CONTENT_FROM_USER];
			$handlerType = $handlerData['type'];
			
			switch( $handlerType )
			{
				case 'sendTextMessage':
				{	file_put_contents("err.txt", $handlerData['text']);
					define("CONTENT", $handlerData['text']);
					$messageManager->responseMsg( 'text' );
					break;
				}
				case 'sendArticalMessage':
				{
					$len = count($handlerData);
					$aArticleInfo = array();
					for($i=1; $i<$len; $i++)
					{
						$articleDate = $handlerData['article' . $i];
						$des = $articleDate['des'];
						$title = $articleDate['title'];
						$imageUrl = $articleDate['imageUrl'];
						$articalUrl = $articleDate['articalUrl'];
						$aArticleInfo[] = array($title, $des, $imageUrl, $articalUrl);
					}
					$messageManager->sendArticalMessage($aArticleInfo);
					break;
				}
			}
		}
		else
		{
			noKeyWordMath($messageManager);
			file_put_contents("err.txt", 'end');
		}
		break;
    }
    case 'event':
    {   
        include('eventAutoReply.php');
        break;
    }
    default: // 既不是文字消息也不是事件推送
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
    }
}

?>