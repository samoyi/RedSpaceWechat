<?php

switch( EVENT_TYPE )
{
    case 'subscribe':
    {
         $json = '{
                 "touser": "' . USERID . '",
                 "msgtype":"text",
                 "text":
                 {
                        "content":"' . '恭喜小红花找到组织啦！（点击蓝色文字直接进入）\n\n[蛋糕]<a href=\"http://dwz.cn/3DU6JS\">蛋糕在线订购，3小时速达</a>\n\n——中秋活动专场——\n\n🎁<a href=\"http://mp.weixin.qq.com/s?__biz=MjM5NzA2OTIwMQ==&mid=2650756161&idx=1&sn=ec56abc73358b5519e851f3cb0a7e848#rd\">月饼优惠券天天抢</a>\n\n🎁<a href=\"http://mp.weixin.qq.com/s?__biz=MjM5NzA2OTIwMQ==&mid=2650756161&idx=2&sn=4291427ec44aebc6b10cfcd39accf990#rd\">现烤月饼买二送一</a>' . '"
                 }
             }';
         $url = 'https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=' . ACCESS_TOKEN;
         $result = request_post($url, $json);
         ifRefreshAccessTokenAndRePost($result, 'https://api.weixin.qq.com/merchant/order/getbyfilter?access_token=', $json );

         //$messageManager->sendImage( '' );

         break;
    }
    case 'CLICK' :
    {
        $fetchedMsgKey = $postedEvent['eventKey'];// 设置自定义菜单时的key值
        switch($fetchedMsgKey)     
        {
            case 'customMenuKey01' :
            {
                $title = '红房子微信订蛋糕指南';
                $des = '红房子蛋糕 美味空间新灵感';
                $imageUrl = 'https://mmbiz.qlogo.cn/mmbiz/fYETicIfkWsWicnYhDqAjfYl0QuCBl9esrEqPKQbtibM1MEPMWbHy9puVfVfZ2h8IQbunL7KicPicUs8qGicUQ74EmAg/0?wx_fmt=jpeg';
                $articalUrl = 'http://mp.weixin.qq.com/s?__biz=MjM5NzA2OTIwMQ==&mid=503272296&idx=1&sn=e27544828b2c12bbdbca9a95b88b150e#rd';
                $messageManager->sendArticalMessage($title, $des, $imageUrl, $articalUrl);
                break; 
            }
            case 'customMenuKey02' :
            {
                //2015-3-1开始
                $data = '{
                        "begintime": 1425139200, 
                        "endtime": ' . time() . '
                        }';
                        
                $url = 'https://api.weixin.qq.com/merchant/order/getbyfilter?access_token=' . ACCESS_TOKEN;
                $return = request_post($url, $data);
                $orderObj = json_decode($return);
                $return  = ifRefreshAccessTokenAndRePost($return, 'https://api.weixin.qq.com/merchant/order/getbyfilter?access_token=', $data );
                $orderObj = json_decode($return);
                $orderArr = $orderObj->order_list;
                $title = "已成功付款的订单";
                $des = "";
                $orderStatus = "";
                $orderNum = 0;
                $toomuchOrderCue = "";
//okV_gjrMpNfy6d5fJxqj7ph68MmU
                foreach( $orderArr as $order)
                {
                    if( USERID === $order->buyer_openid )
                    {
                    	if( 20 === $orderNum++)//最多显示20个历史订单
                    	{
                    		$toomuchOrderCue = "只能查询最新的20个订单。";
                    		break;
                    	}
                		switch( $order->order_status )
                        {
                            case 2:
                                $orderStatus = '待发货';
                                break;
                            case 3:
                                $orderStatus = '已发货';
                                break;

                        }
                        //直接返回的sku格式如下   $蛋糕尺寸:$8寸（适合3-5人）;$送达日期:$7月13日;$送达时刻（请提前3小时预定）:$16点-17点;$配送方式（市区免费配送）:$配送到户

                        $productSku = str_replace("$", "\n", $order->product_sku);
                        $productSku = str_replace(";", "", $productSku);
                        $productSku = str_replace(":\n", "：", $productSku);
                        $des .= "时间：" . date('Y-m-d H:i', $order->order_create_time) . "\n名称：" . $order->product_name . $productSku . "\n总价：" . ($order->order_total_price)/100 . "元\n状态：" . $orderStatus .  "\n收货人：" . $order->receiver_name ." ". $order->receiver_mobile.  "\n收货地址：" .$order->receiver_address . "\n\n"; 	  
                    }
                }
                if( empty($des) )
                {
                	$des = "没有查询到你的订单记录。";
                }
                else
                {
                	$des .= "\n\n" . $toomuchOrderCue;
                }               
                $messageManager->sendArticalMessage($title, $des, '', '');
                break; 
            }
            case 'customMenuKey12' :
            {
                define("CONTENT", '亲，你好，请问有什么可以为您服务？您可直接在公众号中与客服联系（客服在线时间9:00—18:00）');
                $messageManager->responseMsg( 'text' );
                break; 
            }
            case 'customMenuKey20' :
            {
                $title = '三宝中秋献礼🎁优惠券🎁天天抢！';
                $des = '小票对暗号，微信摇一摇，中秋优惠券抢不停！';
                $imageUrl = 'https://mmbiz.qlogo.cn/mmbiz/fYETicIfkWsVrTO8MVbtZ2HafiauatAYXJVylv0lmKKpEg7N7Q79k4qhxvO9NS0fUl7UcrtkkNAnhhE0wNcpwv0g/0?wx_fmt=gif';
                $articalUrl = 'http://mp.weixin.qq.com/s?__biz=MjM5NzA2OTIwMQ==&mid=2650756161&idx=1&sn=ec56abc73358b5519e851f3cb0a7e848#rd'; 
                $messageManager->sendArticalMessage($title, $des, $imageUrl, $articalUrl);
                break; 
            }
            case 'customMenuKey21' :
            {
                $title = '现烤现卖二赠一！刚出炉的家味道';
                $des = '8月11日-8月31日，现烤月饼玉皇酥、鲜肉月饼、蛋黄肉松，买二送一！';
                $imageUrl = 'https://mmbiz.qlogo.cn/mmbiz/fYETicIfkWsViaJE34NyFfg8kJDYZib3UibHSRdlVQ9H5D6ZgFXQgibbQMm2YfyXEj5bDle08sXTINhhxBxptgsNmRg/0?wx_fmt=gif';
                $articalUrl = 'http://mp.weixin.qq.com/s?__biz=MjM5NzA2OTIwMQ==&mid=2650756161&idx=2&sn=4291427ec44aebc6b10cfcd39accf990#rd'; 
                $messageManager->sendArticalMessage($title, $des, $imageUrl, $articalUrl);
                break; 
            }
            case 'customMenuKey22' :
            {
                $title = '月饼奥运，有美味更有福利！';
                $des = "🎁小票对暗号，礼券天天抢\n🎁申情摇一摇，三宝来献礼\n\n中秋活动火热进行中\n\n领完优惠券，哪款月饼才是你心中的冠军呢？";
                $imageUrl = 'https://mmbiz.qlogo.cn/mmbiz/fYETicIfkWsWoicZh4TFdlBiaqSEEDQuiaS6HmvQmMyW9r8Je0g3ObzZdXsCMed50FJgqiaT5tFBvuEjAI1rrutghHA/0?wx_fmt=gif';
                $articalUrl = 'http://mp.weixin.qq.com/s?__biz=MjM5NzA2OTIwMQ==&mid=2650756181&idx=1&sn=5c728f9bde60f016f58e0cf8914dff92#rd'; 
                $messageManager->sendArticalMessage($title, $des, $imageUrl, $articalUrl);
                break; 
            }
        }
        break;
    }
    case 'user_get_card' :
    {
        $messageManager->sendCardReceivedMessage( '亲亲，领到优惠券请在“微信-我-卡包-我的票券”中查看和使用。红房子祝您中秋快乐！' );
        break;
    }
    case 'merchant_order' :
    {
        include('class/OrderManager.class.php');
        $orderManager = new OrderManager();
        $orderDetail = $orderManager->getOrderDetail(ORDERID);

        $messageManager->sendTemplateMessage($orderDetail); // 购买成功消息

        if( '【七夕纯秀】白色恋人 纯纯的刚刚好' === trim($orderDetail['product_name'])) //特定产品推卡券
        {
            $messageManager->sendCard('pkV_gjvwDR0FlDJkU1sqtAbdnduQ');
        }
        break;	
    }
    default :
    {
        $messageManager->responseMsg( 'null' );
        //这里会发送空字符串，也就是不回复。但是要注意这之后不能再发送其他东西，包括修改自定义菜单之类的，否则发送的就不是空字符串，且也不是合理的回复格式，就会显示暂时无法服务。
    }
}

?>