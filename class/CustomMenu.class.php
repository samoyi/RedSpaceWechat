<?php

class CustomMenu 
{
	//创建或重写自定义菜单。参数为菜单按钮设置
    public function createMenu( $customMenuData)
    {
        $url =  'https://api.weixin.qq.com/cgi-bin/menu/create?access_token=' . ACCESS_TOKEN;
        return request_post($url, $customMenuData);
    }

    //处理自定义菜单click事件。参数为当前按钮的key
    public function clickHandler( $fetchedMsgKey, $messageManager )
    {	
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
                //历史订单从2015-3-1开始
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
                define("CONTENT", '亲，你好，请问有什么可以为您服务？您可直接在公众号中与客服联系（客服在线时间9:00—21:00）');
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
                $articalUrl = 'http://mp.weixin.qq.com/s?__biz=MjM5NzA2OTIwMQ==&mid=2650756161&idx=1&sn=ec56abc73358b5519e851f3cb0a7e848#rd'; 
                $messageManager->sendArticalMessage($title, $des, $imageUrl, $articalUrl);
                break; 
            }
        }

    }
}

?>