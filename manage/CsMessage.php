<?php
	$order_id = trim( $_POST["order_id"] );
	$message = trim( $_POST["message"] );

	include('../configration.php'); // 公众号配置文件
	include('../publicFunctions.php'); // 公共函数  TODO 这个文件依赖configration.php
	include('../WechatPushed.php'); // 获取微信后台推送信息

	$messageManager = new MessageManager();
	echo $messageManager->sendCustomMessage($order_id, $message);	
?>
