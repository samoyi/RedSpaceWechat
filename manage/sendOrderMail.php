<?php

	$receivers = array( // 接收人邮箱
		'funcaservice@163.com',
		'601610407@qq.com',
		'276739812@qq.com'
	);
	$mail_subject = '红房子微信小店新订单提醒';// 邮件标题	
	
	
	require PROJECT_ROOT . 'plugin/sendmail.php';	

	function generateMailContentTable($sendOrdermail_orderDetail)
	{
		$sOrderID = $sendOrdermail_orderDetail["order_id"];
		$sNickname = $sendOrdermail_orderDetail["buyer_nick"];
		$nTotalPrice = $sendOrdermail_orderDetail["order_total_price"];
		$sReceiverName = $sendOrdermail_orderDetail["receiver_name"];
		$sReceiverProvince = $sendOrdermail_orderDetail["receiver_province"];
		$sReceiverCity = $sendOrdermail_orderDetail["receiver_city"];
		$sReceiverZone = $sendOrdermail_orderDetail["receiver_zone"];
		$sReceiverAddress = $sendOrdermail_orderDetail["receiver_address"];
		$sReceiverTel = $sendOrdermail_orderDetail["receiver_mobile"];
		$sProductID = $sendOrdermail_orderDetail["product_id"];
		$sProductName = $sendOrdermail_orderDetail["product_name"];
		$sProductSku = $sendOrdermail_orderDetail["product_sku"];
		$nProductPrice = $sendOrdermail_orderDetail["product_price"];
		$nProductCount = $sendOrdermail_orderDetail["product_count"];

		$sContentTable = '
		<table border="1">
			<tr>
				<td>订单号</td><td>' .$sOrderID. '</td>
			</tr>
			<tr>
				<td>商品名称</td><td>' .$sProductName. '</td>
			</tr>
			<tr>
				<td>商品属性</td><td>' .str_replace(';', "<br />", $sProductSku). '</td>
			</tr>
			<tr>
				<td>购买数量</td><td>' .$nProductCount. '</td>
			</tr>
			<tr>
				<td>订单总金额</td><td>' .($nTotalPrice/100). ' 元</td>
			</tr>
			<tr>
				<td>用户昵称</td><td>' .$sNickname. '</td>
			</tr>
			<tr>
				<td>收货人名称</td><td>' .$sReceiverName. '</td>
			</tr>
			<tr>
				<td>收货人地址</td><td>' .$sReceiverProvince.$sReceiverCity.$sReceiverZone.$sReceiverAddress. '</td>
			</tr>
			<tr>
				<td>收货人电话</td><td>' .$sReceiverTel. '</td>
			</tr>
			<tr>
				<td>商品价格</td><td>' .($nProductPrice/100). ' 元</td>
			</tr>
			<tr>
				<td>商品ID</td><td>' .$sProductID. '</td>
			</tr>
		</table>';
		return $sContentTable;
	}

	$mail_message = generateMailContentTable($sendOrdermail_orderDetail);

	foreach($receivers as $value)
	{
		sendmail($value, $mail_subject, $mail_message);
	}


?>

