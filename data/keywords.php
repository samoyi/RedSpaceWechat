<?php
	
	
	/*
	 * 如果检测到消息类型是文字，则加载该文件
	 *
	 * 更复杂的关键词处理能力，用户可以自写插件：例如只要输入信息中带有wifi这个词就回复wifi密码对于本框架并不会提供这个功能，所以需要用户自己写好逻辑并插入到关键词接收逻辑处。用户自定义处理方法的关键词，
	 * 在关键词接收逻辑处，会先检查需要使用用户自定义处理方法的关键词。例如一个气象查询插件，提供了“气温”“气象”“空气质量”
	 * 三个一组的关键词。在关键词接收逻辑处，会先看输入的关键词是否在这一组关键词里面，如果没有，才进入该文件继续搜索是否有
	 * 对应关键词。因此也可以使用同名自定义关键词来覆盖基础关键词
	 *
	 * 发送图文消息是时的数组中必须按照 title des imageUrl articleUrl 的顺序
	 */
	
	


	// 以下为关键词区域 
	/* 
	 * $aKeywords是关键词列表，$aKeywordHandler是真正的关键词处理方法
	 * 只有出现在$aKeywords中的关键词才会进行处理。也就是说可以将暂时
	 * 不进行处理但以后还会处理的关键词在$aKeywordHandler中保留，但在
	 * $aKeywords中暂时删除
	 *
	 * 如果要发送多条相同类型的消息，则第一条以后的其他条的消息类型的
	 * 键名必须加一个不相同的数字后缀。例如要发送三个文字回复，则三个
	 * 键名可以为“sendTextMessage”， sendTextMessage“sendTextMessage2”
     * 和 “sendTextMessage6”。因为该数字只能是一位且不能重复，所以一次
	 * 回复中相同类型的回复数最多为11个。即不带后缀的一个和后缀从0到9
	 * 的10个
	 */
	$aKeywords = array("wifi", "WIFI", "WiFi", "测试回复314", "微信订蛋糕", "22", "营业时间", "投诉电话");
	
	$aKeywordHandler = array(
		"wifi" => array(
							"sendTextMessage"=>'您所在的门店wifi密码为：redspace'
						),
		"WIFI" => array(
							"sendTextMessage"=>'您所在的门店WIFI密码为：redspace'
						),
		"WiFi" => array(
							"sendTextMessage"=>'您所在的门店WiFi密码为：redspace'
						),
		"营业时间" => array(
							"sendTextMessage"=>'红房子门店营业时间7:00~22:00'
						),
		"投诉电话" => array(
							"sendTextMessage"=>'投诉电话：18637627906'
						),
		"测试回复314" => array( 
								"sendArticalMessage"=>array
								(
									array
									(
										"title" => "红房子微信订蛋糕指南1",
										"des" => "红房子蛋糕 美味空间新灵感",
										"imageUrl" => "https://mmbiz.qlogo.cn/mmbiz/fYETicIfkWsWicnYhDqAjfYl0QuCBl9esrEqPKQbtibM1MEPMWbHy9puVfVfZ2h8IQbunL7KicPicUs8qGicUQ74EmAg/0?wx_fmt=jpeg",
										"articleUrl" => "http://mp.weixin.qq.com/s?__biz=MjM5NzA2OTIwMQ==&mid=503272296&idx=1&sn=e27544828b2c12bbdbca9a95b88b150e#rd"
									),
									array
									(
										"title" => "红房子微信订蛋糕指南2",
										"des" => "红房子蛋糕 美味空间新灵感",
										"imageUrl" => "https://mmbiz.qlogo.cn/mmbiz/fYETicIfkWsWicnYhDqAjfYl0QuCBl9esrEqPKQbtibM1MEPMWbHy9puVfVfZ2h8IQbunL7KicPicUs8qGicUQ74EmAg/0?wx_fmt=jpeg",							
										"articleUrl" => "http://mp.weixin.qq.com/s?__biz=MjM5NzA2OTIwMQ==&mid=503272296&idx=1&sn=e27544828b2c12bbdbca9a95b88b150e#rd"
									),
									array
									(
										"title" => "红房子微信订蛋糕指南3",
										"des" => "红房子蛋糕 美味空间新灵感",
										"imageUrl" => "https://mmbiz.qlogo.cn/mmbiz/fYETicIfkWsWicnYhDqAjfYl0QuCBl9esrEqPKQbtibM1MEPMWbHy9puVfVfZ2h8IQbunL7KicPicUs8qGicUQ74EmAg/0?wx_fmt=jpeg",							
										"articleUrl" => "http://mp.weixin.qq.com/s?__biz=MjM5NzA2OTIwMQ==&mid=503272296&idx=1&sn=e27544828b2c12bbdbca9a95b88b150e#rd"
									)
								), 
								"sendTextMessage"=>'test',
								"sendArticalMessage1"=>array
								(
									array
									(
										"title" => "红房子微信订蛋糕指南",
										"des" => "红房子蛋糕 美味空间新灵感",
										"imageUrl" => "https://mmbiz.qlogo.cn/mmbiz/fYETicIfkWsWicnYhDqAjfYl0QuCBl9esrEqPKQbtibM1MEPMWbHy9puVfVfZ2h8IQbunL7KicPicUs8qGicUQ74EmAg/0?wx_fmt=jpeg",
										"articleUrl" => "http://mp.weixin.qq.com/s?__biz=MjM5NzA2OTIwMQ==&mid=503272296&idx=1&sn=e27544828b2c12bbdbca9a95b88b150e#rd"
									)
								)
							),
		"微信订蛋糕" => array(
								"sendArticalMessage"=>array
								(
									array
									(
										"title" => "红房子微信订蛋糕指南",
										"des" => "红房子蛋糕 美味空间新灵感",
										"imageUrl" => "https://mmbiz.qlogo.cn/mmbiz/fYETicIfkWsWicnYhDqAjfYl0QuCBl9esrEqPKQbtibM1MEPMWbHy9puVfVfZ2h8IQbunL7KicPicUs8qGicUQ74EmAg/0?wx_fmt=jpeg",
										"articleUrl" => "http://mp.weixin.qq.com/s?__biz=MjM5NzA2OTIwMQ==&mid=503272296&idx=1&sn=e27544828b2c12bbdbca9a95b88b150e#rd"
									)
								)
							),
		"22" => array(
								"sendArticalMessage"=>array
								(
									array
									(
										"title" => "你最爱的红房子，由你代言",
										"des" => "分享你和红房子的小故事，成为红房子代言人\n\n一等奖（1名）： 1000元VIP卡1张 + 红房子22周年庆代言人\n\n二等奖（3名）： 500元VIP卡1张 + 红房子22周年庆代言人\n\n三等奖（6名）： 300元VIP卡1张 + 红房子22周年庆代言人\n\n红粉奖（100名）： 牛乳蛋糕1个 + 22周年帆布袋1个",
										"imageUrl" => "https://mmbiz.qlogo.cn/mmbiz_png/fYETicIfkWsV49f0eAKzNkVS5icP7TNMPaywNOj5b1cGSrcT62TNibIKr6icv58hFdRdN2TiaZvIsJypF9OQ4MaJ18g/0?wx_fmt=png",
										"articleUrl" => "https://mp.weixin.qq.com/s?__biz=MjM5NzA2OTIwMQ==&mid=2650757173&idx=1&sn=a057adcd87f95e70ec9f4636c35f1916&chksm=bed450cb89a3d9ddbcb731b9501e80f7cc49b4cebed283aa8ea1aad09815ae8fa99c9fa4b906#rd"
									)
								)
							)
	);
	
	function noKeyWordMatch($messageManager)
	{	
		$luckyCode = trim(CONTENT_FROM_USER);
		if( is_numeric($luckyCode) && is_int((int)$luckyCode) )
		{	
			require "class/MySQLiController.class.php";
			$MySQLiController = new MySQLiController( $dbr );
			$where = 'code="' .$luckyCode. '"';
			$result = $MySQLiController->getRow('50draw_temp', $where);
			$row = $result->fetch_array();
			
			if( $row  )
			{
				if( $row['used']==='no' || $row['used']===USERID ){
					require "class/CardMessager.class.php";
					$CardMessager = new CardMessager;
					$CardMessager->sendCardByOpenID( 'pkV_gjm4Sc4gqPzLlXue4dqY3NzM', USERID);
					$MySQLiController->updateData('50draw_temp', array('used'), array(USERID), $where);
					$messageManager->responseMsg( 'null' );
				}
				else{
					define("CONTENT", '该兑奖码已使用');
					$messageManager->responseMsg( 'text' );
				}
			}
			else{
				$messageManager->responseMsg( 'null' );
			}	
			
			$dbr->close();
			
		}
		elseif( date('G')>(OFF_DUTY_TIME-1) || date('G')<ON_DUTY_TIME)//客服下班时间，自动回复客服已下班
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
					'切换自动回复314',
					'测试',
					'提货券',
					'罐子蛋糕',
					'蛋糕罐子'
				);
?>