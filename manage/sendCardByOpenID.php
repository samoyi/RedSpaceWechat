<?php

	$open_id = trim( $_POST["open_id"] );
	$card_id = trim( $_POST["card_id"] );

	include('../configuration.php'); // 公众号配置文件
	include('../publicFunctions.php'); // 公共函数  TODO 这个文件依赖configuration.php
	include('../WechatPushed.php'); // 获取微信后台推送信息
	
	include('../class/CardMessager.class.php');
	$CardMessager = new CardMessager();
	$result = $CardMessager->sendCardByOpenID($card_id, $open_id, 'sendCardResult.txt');
	echo json_encode($result);
?>
