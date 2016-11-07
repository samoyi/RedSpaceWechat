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
    	if( stristr(CONTENT_FROM_USER, 'wifi') )
    	{
    	    define("CONTENT", MESSAGE_fOR_WIFI_KEYWORD);
    	    $messageManager->responseMsg( 'text' );
    	}
    	else
    	{
            $cardKeywordsID = json_decode(file_get_contents("manage/JSONData/cardKeywords.json"), true);
            if( $cardKeywordsID[CONTENT_FROM_USER] )
            {
                include('class/CardMessager.class.php');
                $cardMessager = new CardMessager();
                $outReplayText = '亲亲，优惠券天天抢活动已经结束，请持续关注红房子微信公众平台，福利多多哦~';
                $cardMessager->getCardByKeyWords( $cardKeywordsID[CONTENT_FROM_USER], $outReplayText, $messageManager);
    	    	$messageManager->responseMsg( 'text' );
                break;
            }
    		switch( CONTENT_FROM_USER )
    		{
    			case '测试回复314' :
    			{    
					$CONTENT_FROM_USER = 'wifi';
                    /* define("CONTENT", '测试回复');
                    $messageManager->responseMsg( 'text' );  */
					require PROJECT_ROOT . 'data/keywords.php';
					file_put_contents("err.txt", PROJECT_ROOT . 'data/keywords.php', FILE_APPEND);
					if( array_key_exists($CONTENT_FROM_USER, $keywords) )
					{
						$handlerData = $keywords[$CONTENT_FROM_USER];
						$handlerType = $handlerData['type'];
						switch( $handlerType )
						{
							case 'sendTextMessage':
							{
								define("CONTENT", $handlerData['text']);
								$messageManager->responseMsg( 'text' );
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
    				break;
    			}
    			case '微信订蛋糕' :
    			{
    				$title = '红房子微信订蛋糕指南';
    			    $des = '红房子蛋糕 美味空间新灵感';
    			    $imageUrl = 'https://mmbiz.qlogo.cn/mmbiz/fYETicIfkWsWicnYhDqAjfYl0QuCBl9esrEqPKQbtibM1MEPMWbHy9puVfVfZ2h8IQbunL7KicPicUs8qGicUQ74EmAg/0?wx_fmt=jpeg';
    			    $articalUrl = 'http://mp.weixin.qq.com/s?__biz=MjM5NzA2OTIwMQ==&mid=503272296&idx=1&sn=e27544828b2c12bbdbca9a95b88b150e#rd';
					
					$aArticleInfo = array( array($title, $des, $imageUrl, $articalUrl) );
					$messageManager->sendArticalMessage($aArticleInfo);
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
    	break;
    }
    case 'event':
    {   
        include('eventAutoReply.php');
        break;
    }
    default:
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