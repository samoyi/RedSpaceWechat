<?php

	// 参数为  OrderManager 类 getOrderDetail 方法的返回值
	function noteOrderInfo($orderDetail)
	{
		$sOrderID = $orderDetail["order_id"];
		$sNickname = $orderDetail["buyer_nick"];
		$nTotalPrice = $orderDetail["order_total_price"];
		$sReceiverName = $orderDetail["receiver_name"];
		$sReceiverProvince = $orderDetail["receiver_province"];
		$sReceiverCity = $orderDetail["receiver_city"];
		$sReceiverZone = $orderDetail["receiver_zone"];
		$sReceiverAddress = $orderDetail["receiver_address"];
		$sReceiverTel = $orderDetail["receiver_mobile"];
		$sProductID = $orderDetail["product_id"];
		$sProductName = $orderDetail["product_name"];
		$nProductPrice = $orderDetail["product_price"];
		$nProductCount = $orderDetail["product_count"];
		if( !class_exists( 'MySQLiController', false) )
		{
			require PROJECT_ROOT . 'plugin/MySQLiController.class.php';
		}
		$MySQLiController = new MySQLiController( $dbr );
		$aCol = array('order_id', 'nickname', 'openid', 'total_price', 'receiver_name', 'receiver_province', 'receiver_city', 'receiver_zone', 'receiver_address', 'receiver_mobile', 'product_id', 'product_name', 'product_price', 'product_count', 'order_create_time');
		$aValue = array($sOrderID, $sNickname, USERID, $nTotalPrice, $sReceiverName, $sReceiverProvince, $sReceiverCity, $sReceiverZone, $sReceiverAddress, $sReceiverTel, $sProductID, $sProductName, $nProductPrice, $nProductCount, date("Y-m-d G:i:s"));
		if( !class_exists( 'MySQLiController', false) )
		{
			require PROJECT_ROOT . 'plugin/MySQLiController.class.php';
		}
		$MySQLiController = new MySQLiController( $dbr );
		$MySQLiController->insertRow('Wechat_Order', $aCol, $aValue);

		// 同时把订单信息中的用户信息记录到用户信息表中
		$MySQLiController->updateData(
				'Wechat_OpenID',
				array('type', 'modifyTime', 'receiver_name', 'tel', 'receiver_province', 'receiver_city', 'receiver_zone', 'receiver_address'),
				array('merchant_order', date("Y-m-d G:i:s"), $sReceiverName, $sReceiverTel, $sReceiverProvince, $sReceiverCity, $sReceiverZone, $sReceiverAddress),
				'openID="' . USERID . '"');


		$dbr->close();
	}


?>
