<?php
	
	
	/*
	 * 如果检测到消息类型是事件，则加载该文件
	 *
	 *
	 */
	
	/* 以下为数据区域 */
	define(MESSAGE_fOR_GET_CARD_EVENT, '亲亲，领到优惠券请在“微信-我-卡包-我的票券”中查看和使用。');
	define(CARDID_SENT_AFTER_ORDER, 'pkV_gjkMiddaSVeMglxSb1oPU4nQ');


	/* 以下为关键词区域 */
	$events = array(
		"subscribe" => array( // 第一项必须是type。
								"type"=>"sendCSMessage",
								"article1"=>array(
									"title" => "红房子微信订蛋糕指南1",
									"des" => "红房子蛋糕 美味空间新灵感",
									"imageUrl" => "https://mmbiz.qlogo.cn/mmbiz/fYETicIfkWsWicnYhDqAjfYl0QuCBl9esrEqPKQbtibM1MEPMWbHy9puVfVfZ2h8IQbunL7KicPicUs8qGicUQ74EmAg/0?wx_fmt=jpeg",
									"articalUrl" => "http://mp.weixin.qq.com/s?__biz=MjM5NzA2OTIwMQ==&mid=503272296&idx=1&sn=e27544828b2c12bbdbca9a95b88b150e#rd"
								),
								"article2"=>array(
									"title" => "红房子微信订蛋糕指南2",
									"des" => "红房子蛋糕 美味空间新灵感",
									"imageUrl" => "https://mmbiz.qlogo.cn/mmbiz/fYETicIfkWsWicnYhDqAjfYl0QuCBl9esrEqPKQbtibM1MEPMWbHy9puVfVfZ2h8IQbunL7KicPicUs8qGicUQ74EmAg/0?wx_fmt=jpeg",							
									"articalUrl" => "http://mp.weixin.qq.com/s?__biz=MjM5NzA2OTIwMQ==&mid=503272296&idx=1&sn=e27544828b2c12bbdbca9a95b88b150e#rd"
								),
								"article3"=>array(
									"title" => "红房子微信订蛋糕指南3",
									"des" => "红房子蛋糕 美味空间新灵感",
									"imageUrl" => "https://mmbiz.qlogo.cn/mmbiz/fYETicIfkWsWicnYhDqAjfYl0QuCBl9esrEqPKQbtibM1MEPMWbHy9puVfVfZ2h8IQbunL7KicPicUs8qGicUQ74EmAg/0?wx_fmt=jpeg",							
									"articalUrl" => "http://mp.weixin.qq.com/s?__biz=MjM5NzA2OTIwMQ==&mid=503272296&idx=1&sn=e27544828b2c12bbdbca9a95b88b150e#rd"
								)
							),
		"CLICK" => array(
								"type"=>"sendArticalMessage",
								"article1"=>array(
									"title" => "红房子微信订蛋糕指南",
									"des" => "红房子蛋糕 美味空间新灵感",
									"imageUrl" => "https://mmbiz.qlogo.cn/mmbiz/fYETicIfkWsWicnYhDqAjfYl0QuCBl9esrEqPKQbtibM1MEPMWbHy9puVfVfZ2h8IQbunL7KicPicUs8qGicUQ74EmAg/0?wx_fmt=jpeg",
									"articalUrl" => "http://mp.weixin.qq.com/s?__biz=MjM5NzA2OTIwMQ==&mid=503272296&idx=1&sn=e27544828b2c12bbdbca9a95b88b150e#rd"
								)
							),
		"user_get_card" => array(
								"type"=>"sendCardReceivedMessage",
								"article1"=>array(
									"title" => "红房子微信订蛋糕指南",
									"des" => "红房子蛋糕 美味空间新灵感",
									"imageUrl" => "https://mmbiz.qlogo.cn/mmbiz/fYETicIfkWsWicnYhDqAjfYl0QuCBl9esrEqPKQbtibM1MEPMWbHy9puVfVfZ2h8IQbunL7KicPicUs8qGicUQ74EmAg/0?wx_fmt=jpeg",
									"articalUrl" => "http://mp.weixin.qq.com/s?__biz=MjM5NzA2OTIwMQ==&mid=503272296&idx=1&sn=e27544828b2c12bbdbca9a95b88b150e#rd"
								)
							),
		"merchant_order" => array(
								"type"=>"sendTemplateMessage",
								"article1"=>array(
									"title" => "红房子微信订蛋糕指南",
									"des" => "红房子蛋糕 美味空间新灵感",
									"imageUrl" => "https://mmbiz.qlogo.cn/mmbiz/fYETicIfkWsWicnYhDqAjfYl0QuCBl9esrEqPKQbtibM1MEPMWbHy9puVfVfZ2h8IQbunL7KicPicUs8qGicUQ74EmAg/0?wx_fmt=jpeg",
									"articalUrl" => "http://mp.weixin.qq.com/s?__biz=MjM5NzA2OTIwMQ==&mid=503272296&idx=1&sn=e27544828b2c12bbdbca9a95b88b150e#rd"
								)
							)
							
	);
	
	function noKeyWordMath($messageManager)
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
	
	$aCustomKeywords = array(
					'刷新接口',
					'切换自动回复314'
				);
?>