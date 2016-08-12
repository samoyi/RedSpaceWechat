﻿<?php
/* 
 *  处理用户发送的消息
 *  最后如果推送的不是消息而是事件，转入事件处理
 *
 */

switch(MESSAGE_TYPE)
{   
    case "text":
    {   
    	if( stristr(CONTENT_FROM_USER, 'wifi') )
    	{
    	    define("CONTENT", '您所在的门店WIFI密码为：redspace');
    	    $messageManager->responseMsg( 'text' );
    	}
    	else
    	{
    		switch( CONTENT_FROM_USER )
    		{
    			case '一元抢' :
    			{
    				define("CONTENT", '亲，你好~~！
    						一元抢活动已经结束

    						持续关注红房子微信公众平台,福利多多哦mo-亲亲');
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
    			case '314' :
    			{    //测试回复关键字发卡券
    				$messageManager->sendCard('pkV_gjm6W_tQ1GopWQtd3KSVaNoA');
    				break;
    			}
    			case '家料家味道' :
    			{
                    define("CONTENT", '今日对暗号福利已经领完！亲亲，明天早上7:00红房子小票暗号更新，新的暗号，新的福利等着你！（小票暗号每2天更新一次，请在当日购物小票最下方查看）');
                    $messageManager->responseMsg( 'text' );
                    break;
    			}
                /*case '845' :
                {
                    
                    include('class/CardMessager.class.php');
                    $CardMessager = new CardMessager();
                    $remainQuantity = $CardMessager->getBaseInfo("pkV_gjm6W_tQ1GopWQtd3KSVaNoA")->sku->quantity;
                    file_put_contents("err.txt", $remainQuantity);
                    if( $remainQuantity > 0 )
                    {
                       $messageManager->sendCard('pkV_gjm6W_tQ1GopWQtd3KSVaNoA');
                        break; 
                    }
                    define("CONTENT", '测试的领完了');
                    $messageManager->responseMsg( 'text' );
                    break;
                }*/
    			case '申情三宝，好吃有料' :
    			{
                    include('class/CardMessager.class.php');
                    $cardMessager = new CardMessager();
                    $remainQuantity = $cardMessager->getBaseInfo("pkV_gjiEHPU4oRTe9JjOPbU9L7mY")->sku->quantity;
                    if( $remainQuantity > 0 )
                    {
                        
                        $cardMessager->sendCard('pkV_gjiEHPU4oRTe9JjOPbU9L7mY');
                        break;
                    }                    
                    define("CONTENT", '申请三包领完了');
                    $messageManager->responseMsg( 'text' );
                    break;
    			}
    			case '帝王酥好好吃' :
    			{
    				$messageManager->sendCard('pkV_gjnv7cGQJD_Z3x2T9wf9xsNM');
    				break;
    			}
    			case '五仁月饼果仁多' :
    			{
    				$messageManager->sendCard('pkV_gjklcvQ9lEZtps78_r_hnGGw');
    				break;
    			}
    			case '蛋黄莲蓉，料胜一筹' :
    			{
    				$messageManager->sendCard('pkV_gjk_3BCNFIwTOO7kYRJRhKVw');
    				break;
    			}
    			case '三宝萌萌哒' :
    			{
    				$messageManager->sendCard('pkV_gjupHgxWpv1MfNxSeRmxn9Fg');
    				break;
    			}
    			case '红房子月饼好好吃' :
    			{
    				$messageManager->sendCard('pkV_gjs7im22p3QG5BzpWpJ1Er6Y');
    				break;
    			}
    			case '甜甜的红房子' :
    			{
    				$messageManager->sendCard('pkV_gjhx1Z3vGs8wsVSUrZ5EaUGI');
    				break;
    			}
    			case '料你难挡诱惑' :
    			{
    				$messageManager->sendCard('pkV_gjmVjPoPqYSfdITt_dOhdLaw');
    				break;
    			}
    			case '吃申情三宝，过有料中秋' :
    			{
    				$messageManager->sendCard('pkV_gjrbqqVizE3t9bKKIhraHA2s');
    				break;
    			}
    			default: // 如果用户发送的不是已设定的关键词
    			{
    				if( date('G')>17 || date('G')<9)//客服下班时间，自动回复客服已下班
    				{
    					include('manage/manager.php');
    					$manager = new Manager();
                        // 查看客服是否开启了下班时间自动回复功能
    					$autoReplyByTimeState = $manager->getAutoReplyByTimeState();
    					if( 'on' === $autoReplyByTimeState )
    					{
    						define("CONTENT", '您的留言已被标记，客服将在上午九点后回复您');
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
        if( date('G')>17 || date('G')<9)//客服下班时间，自动回复客服已下班
        {
            include('manage/manager.php');
            $manager = new Manager();
            // 查看客服是否开启了下班时间自动回复功能
            $autoReplyByTimeState = $manager->getAutoReplyByTimeState();
            if( 'on' === $autoReplyByTimeState )
            {
                define("CONTENT", '您的留言已被标记，客服将在上午九点后回复您');
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