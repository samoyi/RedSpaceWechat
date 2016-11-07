<?php
	
	
	/*
	 * 如果检测到消息类型是文字，则加载该文件
	 *
	 * 更复杂的关键词处理能力，用户可以自写插件：例如只要输入信息中带有wifi这个词就回复wifi密码对于本框架并不会提供这个功能，所以需要用户自己写好逻辑并插入到关键词接收逻辑处。用户自定义处理方法的关键词，
	 * 在关键词接收逻辑处，会先检查需要使用用户自定义处理方法的关键词。例如一个气象查询插件，提供了“气温”“气象”“空气质量”
	 * 三个一组的关键词。在关键词接收逻辑处，会先看输入的关键词是否在这一组关键词里面，如果没有，才进入该文件继续搜索是否有
	 * 对应关键词。
	 *
	 */
	

	$keywords = array(
		"wifi" => array(
								"type"=>"sendTextMessage",
								"text"=>'您所在的门店wifi密码为：redspace'
							),
		"WIFI" => array(
								"type"=>"sendTextMessage",
								"text"=>'您所在的门店WIFI密码为：redspace'
							),
		"WiFi" => array(
								"type"=>"sendTextMessage",
								"text"=>'您所在的门店WiFi密码为：redspace'
							),
		"测试回复314" => array( // 第一项必须是type。
								"type"=>"sendArticalMessage",
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
		"微信订蛋糕" => array(
								"type"=>"sendArticalMessage",
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