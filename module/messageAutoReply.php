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
                    define("CONTENT", '测试回复');
                    $messageManager->responseMsg( 'text' );
    				break;
    			}
    			case '微信订蛋糕' :
    			{
    				$title = '红房子微信订蛋糕指南';
    			    $des = '红房子蛋糕 美味空间新灵感';
    			    $imageUrl = 'https://mmbiz.qlogo.cn/mmbiz/fYETicIfkWsWicnYhDqAjfYl0QuCBl9esrEqPKQbtibM1MEPMWbHy9puVfVfZ2h8IQbunL7KicPicUs8qGicUQ74EmAg/0?wx_fmt=jpeg';
    			    $articalUrl = 'http://mp.weixin.qq.com/s?__biz=MjM5NzA2OTIwMQ==&mid=503272296&idx=1&sn=e27544828b2c12bbdbca9a95b88b150e#rd';
    				$messageManager->sendArticalMessage($title, $des, $imageUrl, $articalUrl);
    				break;
    			}
    			case '切换自动回复314' :
    			{
    				include('manage/manager.php');
    				$manager = new Manager();
    				$autoReplyByTimeState = $manager->getAutoReplyByTimeState();
    				$JSONObj = json_decode( file_get_contents('manage/manageConfigration.js') );
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
    				file_put_contents('manage/manageConfigration.js', json_encode($JSONObj) );
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