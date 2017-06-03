<?php
	$open_id = trim( $_POST["open_id"] );
	$message = trim( $_POST["message"] );

	include('../configuration.php'); // 公众号配置文件
	include('../publicFunctions.php'); // 公共函数  TODO 这个文件依赖configuration.php
	include('../WechatPushed.php'); // 获取微信后台推送信息

	$messageManager = new MessageManager();
	$orderDetail = array(
		'product_name'=>$message,
		'order_total_price'=>99,
		'order_create_time'=>time()
	);

	$result = $messageManager->sendTemplateMessage($orderDetail, $open_id);	
	echo json_encode($result);
?>
