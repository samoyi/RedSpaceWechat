<?php


    $fetchedMsgKey = $postedEvent['eventKey'];// 设置自定义菜单时的key值
    switch($fetchedMsgKey)
    {
        case 'customMenuKey10' :
        {
			$info = array(
				"title" => "红房子配送范围扩大，直击你的区域！",
				"des" => "红房子蛋糕 美味空间新灵感",
				"imageUrl" => "https://mmbiz.qlogo.cn/mmbiz/fYETicIfkWsWicnYhDqAjfYl0QuCBl9esrEqPKQbtibM1MEPMWbHy9puVfVfZ2h8IQbunL7KicPicUs8qGicUQ74EmAg/0?wx_fmt=jpeg",
				"articleUrl" => "http://mp.weixin.qq.com/s/NoV2O46KoxP0vt_1YCft4g"
			);
			$aArticleInfo = array( $info );
			$messageManager->sendArticalMessage($aArticleInfo);
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
            	$des .= $toomuchOrderCue . "\n";
            }

            include('class/MaterialManager.class.php');
            $materialManager = new MaterialManager();
            $latestNews = $materialManager->getMaterials("news", 1);
            $latestNewsItem = $latestNews->item;
            $latestNewsContentItem = $latestNewsItem[0]->content->news_item;
            $latestNewsTitle = $latestNewsContentItem[0]->title;
            $latestNewsUrl = $latestNewsContentItem[0]->url;
            $newsTime = $latestNewsItem[0]->content->update_time;
            $ad = "-----------------------------------------\n\n⭐ 点击查看红房子更多资讯：\n⭐ [" . date("m月j日", $newsTime) . "] " . $latestNewsTitle;

            $des .= "\n\n" . $ad;
            $imageUrl = $latestNewsContentItem[0]->thumb_url;
			$info = array(
				"title" => $title,
				"des" => $des,
				"imageUrl" => $imageUrl,
				"articleUrl" => $latestNewsUrl
			);
			$aArticleInfo = array( $info );
			$messageManager->sendArticalMessage($aArticleInfo);
            break;
        }
        case 'customMenuKey12' :
        {
            define("CONTENT", 'Hi，直接在公众号对话框输入问题，召唤人工客服（在线时间9:00-17:00)为您解答。也可直接拨打400-0376-558咨询。' . "\n\n"
			. '常见问题请点击蓝色文字直接查看' . "\n\n"
			. '<a href="https://mp.weixin.qq.com/s?__biz=MjM5NzA2OTIwMQ==&mid=503273325&idx=1&sn=9bf41fd1f8304c3bebf12ecdaa0194ec&chksm=3ed45f9309a3d68541e737f62f0320f06d27ac296eeaaeb617cb3c848a9b2dd43d334f54b00b&mpshare=1&scene=1&srcid=0213lxDHDc7Ujxd3nTjBByO3&pass_ticket=4ah1rIIN04hA7C9W0LPGoxwXgMG9bVTJXZ5S3nXg2pBSa1rhTadiTt2b6UWNmWoK#rd">1、配送范围</a>' . "\n\n"
			. '<a href="http://red-space.cn/list/index.php">2、门店电话</a>');
            $messageManager->responseMsg( 'text' );
            break;
        }
    }

?>
