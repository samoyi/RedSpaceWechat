<?php


/* 以下为数据区域 */
define(MESSAGE_fOR_GET_CARD_EVENT, '亲亲，领到优惠券请在“微信-我-卡包-我的票券”中查看和使用。红房子祝您国庆快乐！');
define(CARDID_SENT_AFTER_ORDER, 'pkV_gjkMiddaSVeMglxSb1oPU4nQ');

/* 以下为逻辑区域 */
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
         $messageManager->sendCSMessage($content);
         break;
    }
    case 'CLICK' :
    {
        $fetchedMsgKey = $postedEvent['eventKey'];// 设置自定义菜单时的key值
        switch($fetchedMsgKey)     
        {
            case 'customMenuKey10' :
            {
                $title = '红房子微信订蛋糕指南';
                $des = '红房子蛋糕 美味空间新灵感';
                $imageUrl = 'https://mmbiz.qlogo.cn/mmbiz/fYETicIfkWsWicnYhDqAjfYl0QuCBl9esrEqPKQbtibM1MEPMWbHy9puVfVfZ2h8IQbunL7KicPicUs8qGicUQ74EmAg/0?wx_fmt=jpeg';
                $articalUrl = 'http://mp.weixin.qq.com/s?__biz=MjM5NzA2OTIwMQ==&mid=503272296&idx=1&sn=e27544828b2c12bbdbca9a95b88b150e#rd';
                $messageManager->sendArticalMessage($title, $des, $imageUrl, $articalUrl);
                break; 
            }
            case 'customMenuKey11' :
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
                	$des .= $toomuchOrderCue . "\n";
                }    

                include('class/MaterialManager.class.php');
                $materialManager = new MaterialManager();
                $latestNews = $materialManager->getMaterials("news", 2);
                $latestNewsItem = $latestNews->item;
                $latestNewsContentItem = $latestNewsItem[0]->content->news_item;
                $latestNewsTitle = $latestNewsContentItem[0]->title;
                $latestNewsUrl = $latestNewsContentItem[0]->url;
                $newsTime = $latestNewsItem[0]->content->update_time;
                $ad = "-----------------------------------------\n\n⭐ 点击查看红房子更多资讯：\n⭐ [" . date("m月j日", $newsTime) . "] " . $latestNewsTitle;

                $des .= "\n\n" . $ad;
                $imageUrl = $latestNewsContentItem[0]->thumb_url;
                $messageManager->sendArticalMessage($title, $des, $imageUrl, $latestNewsUrl);
                break; 
            }
            case 'customMenuKey12' :
            {
                define("CONTENT", 'Hi，直接在公众号对话框输入问题，召唤人工客服（在线时间9:00-18:00)为您解答。也可直接拨打400-0376-558咨询！');
                $messageManager->responseMsg( 'text' );
                break; 
            }
        }
        break;
    }
    case 'user_get_card' :
    {
        $messageManager->sendCardReceivedMessage( MESSAGE_fOR_GET_CARD_EVENT );
        break;
    }
    case 'merchant_order' :
    {               
        include('class/OrderManager.class.php');
        $orderManager = new OrderManager();
        $orderDetail = $orderManager->getOrderDetail(ORDERID);
        $messageManager->sendTemplateMessage($orderDetail, '', ''); // 购买成功消息

        // 发卡券
        /* include('class/CardMessager.class.php');
        $cardMessager = new CardMessager();
        $cardMessager->sendCard( CARDID_SENT_AFTER_ORDER ); */

        break;	
    }
    default :
    {
        $messageManager->responseMsg( 'null' );
        //这里会发送空字符串，也就是不回复。但是要注意这之后不能再发送其他东西，包括修改自定义菜单之类的，否则发送的就不是空字符串，且也不是合理的回复格式，就会显示暂时无法服务。
    }
}

?>