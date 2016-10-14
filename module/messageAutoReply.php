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






                    $url = 'https://api.weixin.qq.com/merchant/update?access_token=' . ACCESS_TOKEN;
                    $data = '{
                                "product_id": "pkV_gjsTaeMWcNxzoVNWLXBRQlhM",
                                "product_base": {
                                    "category_id": [
                                        "537074298"
                                    ],
                                    "property": [
                                        {
                                            "id": "1075741879",
                                            "vid": "1079749967"
                                        },
                                        {
                                            "id": "1075754127",
                                            "vid": "1079795198"
                                        },
                                        {
                                            "id": "1075777334",
                                            "vid": "1079837440"
                                        }
                                    ],
                                    "name": "商品名",
                                    "sku_info": [
                                        {
                                            "id": "$发货日期",
                                            "vid": [
                                                "$昨天",
                                                "$前天"
                                            ]
                                        }
                                    ],
                                    "main_img": "http://mmbiz.qpic.cn/mmbiz/4whpV1VZl2iccsvYbHvnphkyGtnvjD3ulEKogfsiaua49pvLfUS8Ym0GSYjViaLic0FD3vN0V8PILcibEGb2fPfEOmw/0",
                                    "img": [
                                        "http://mmbiz.qpic.cn/mmbiz/4whpV1VZl2iccsvYbHvnphkyGtnvjD3ulEKogfsiaua49pvLfUS8Ym0GSYjViaLic0FD3vN0V8PILcibEGb2fPfEOmw/0"
                                    ],
                                    "detail": [
                                        {
                                            "img": "http://mmbiz.qpic.cn/mmbiz/4whpV1VZl2iccsvYbHvnphkyGtnvjD3ul1UcLcwxrFdwTKYhH9Q5YZoCfX4Ncx655ZK6ibnlibCCErbKQtReySaVA/0"
                                        }
                                    ],
                                    "buy_limit": 3
                                },
                                "sku_list": [
                                    {
                                        "sku_id": "$发货日期:$昨天;",
                                        "price": 100,
                                        "icon_url": "http:\/\/mmbiz.qpic.cn\/mmbiz\/4whpV1VZl2iccsvYbHvnphkyGtnvjD3ulEKogfsiaua49pvLfUS8Ym0GSYjViaLic0FD3vN0V8PILcibEGb2fPfEOmw\/0",
                                        "quantity": 11,
                                        "product_code": "",
                                        "ori_price": 0
                                    },
                                    {
                                        "sku_id": "$发货日期:$前天;",
                                        "price": 100,
                                        "icon_url": "http:\/\/mmbiz.qpic.cn\/mmbiz\/4whpV1VZl2iccsvYbHvnphkyGtnvjD3ulEKogfsiaua49pvLfUS8Ym0GSYjViaLic0FD3vN0V8PILcibEGb2fPfEOmw\/0",
                                        "quantity": 11,
                                        "product_code": "",
                                        "ori_price": 0
                                    }
                                ],
                                "attrext": {
                                    "location": {
                                        "country": "中国",
                                        "province": "广东省",
                                        "city": "广州市",
                                        "address": "T.I.T创意园"
                                    },
                                    "isPostFree": 0,
                                    "isHasReceipt": 1,
                                    "isUnderGuaranty": 0,
                                    "isSupportReplace": 0
                                },
                                "delivery_info": {
                                    "delivery_type": 0,
                                    "template_id": 0,
                                    "express": [
                                        {
                                            "id": 10000027,
                                            "price": 100
                                        },
                                        {
                                            "id": 10000028,
                                            "price": 100
                                        },
                                        {
                                            "id": 10000029,
                                            "price": 100
                                        }
                                    ]
                                }
                            }';
                    

                    /*$codeArray = array(
                        'UTF-8', 'ASCII',
                        'ISO-8859-1', 'ISO-8859-2', 'ISO-8859-3', 'ISO-8859-4', 'ISO-8859-5',
                        'ISO-8859-6', 'ISO-8859-7', 'ISO-8859-8', 'ISO-8859-9', 'ISO-8859-10',
                        'ISO-8859-13', 'ISO-8859-14', 'ISO-8859-15', 'ISO-8859-16',
                        'Windows-1251', 'Windows-1252', 'Windows-1254',
                        );
                    $encode = mb_detect_encoding($data, $codeArray); 
                    $data = mb_convert_encoding($data, 'UTF-8', array($encode) );
                    //$data = iconv($encode, 'UTF-8', $data);*/

                    $result = request_post($url, $data);
                    $result = ifRefreshAccessTokenAndRePost( $result, 'https://api.weixin.qq.com/merchant/update?access_token=', $data);
                    file_put_contents("err.txt", $result ); 


                    // 获取sku_info
                    /*include('class/ProductManager.class.php');
                    $productManager = new ProductManager();
                    $result = $productManager->querySkuInfoArray( "pkV_gjr-gERP2CBlt32uJO5KspS0" );
                    file_put_contents("err.txt", json_encode($result) ); */
                    
                    // 添加商品

                    /*include('class/ProductManager.class.php');
                    $productManager = new ProductManager();
                    file_put_contents("err.txt", $productManager->addProduct() );*/



        
        



























                    /*include('class/ProductManager.class.php');
                    $productManager = new ProductManager();
                    $productManager->queryProductIDs(0);

                    $aVID = array("$10月14日",
                                "$10月15日",
                                "$10月16日",
                                "$10月17日",
                                "$10月19日");
                    $productManager->modifySkuInfo();*/
                    

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