<?php

	$order_id = "10295333824951275856";
	$card_id = 'pkV_gjnWmAH6DZoyPgnLogui7H_A';

	include('../configration.php'); // 公众号配置文件
	include('../publicFunctions.php'); // 公共函数  TODO 这个文件依赖configration.php
	include('../WechatPushed.php'); // 获取微信后台推送信息
	
	include('../class/OrderManager.class.php');

	$OrderManager = new OrderManager();
	$buyer_openid = $OrderManager->getOPENIDbyORDERID($order_id); 

	$data = '{
                "touser":"' . $buyer_openid . '", 
                "msgtype":"wxcard",
                "wxcard":{ "card_id":"' . $card_id . '" }
                }';
    $url = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=" . ACCESS_TOKEN;
    $result = request_post($url, $data);   
    echo ifRefreshAccessTokenAndRePost($result, 'https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=', $data ); 
?>
