<?php

switch( EVENT_TYPE )
{
    case 'subscribe':
    {
        $arr = json_decode( file_get_contents('manage/JSONData/subscribeAutoPlayText.json'));
        $content = '';
        foreach( $arr as $value)
        {
            $content .= $value;
        }

         $messageManager->sendCSMessage($content, false);
         $messageManager->sendImage( 'wptdc2AEc7V_tFYzTD1EMRDzTIFu6ioaAfqciBupoF0' );
        
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
            case 'customMenuKey21' :
            {
                $title = '快领月饼！红房子申情三宝中秋大献礼';
                $des = '中秋节和申情三宝一起玩着游戏吃着月饼，多重好礼领起来！';
                $imageUrl = 'https://mmbiz.qlogo.cn/mmbiz_gif/fYETicIfkWsW4glUo0L1ynSIY5dQ4Q9wIa5ibWf5V2Pht1xdmFOm5p1ibubkQGn5IRYLWpRzFXeJq8iafPf5TmImkw/0?wx_fmt=gif';
                $articalUrl = 'http://mp.weixin.qq.com/s?__biz=MjM5NzA2OTIwMQ==&mid=2650756317&idx=1&sn=c6d9bda1e309552e725f136e8002b5bc&chksm=bed45c2389a3d535540c43ce0ec72c9647fefb7aa371461f4cd7ef18f91b39f5ef13c9847b86#rd'; 
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
        /*if( '【帝王酥】单独装，原价15元/粒，6粒，立减20元' === trim($orderDetail['product_name'])) //特定产品推卡券
        {   
            include('class/CardMessager.class.php');
            $cardMessager = new CardMessager();
            file_put_contents("err.txt", "timetosendcard" . date('Y-m-d H:i', time()) . "\n", FILE_APPEND);
            $cardMessager->sendCard('pkV_gjnWmAH6DZoyPgnLogui7H_A');
        }*/
        break;	
    }
    default :
    {
        $messageManager->responseMsg( 'null' );
        //这里会发送空字符串，也就是不回复。但是要注意这之后不能再发送其他东西，包括修改自定义菜单之类的，否则发送的就不是空字符串，且也不是合理的回复格式，就会显示暂时无法服务。
    }
}

?>