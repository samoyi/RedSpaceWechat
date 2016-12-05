<?php

class OrderManager
{
    //通过订单号查询openid
    public function getOPENIDbyORDERID($order_id) 
    {
        $postJson = '{"order_id": "' . $order_id . '"}';
        $resultorder =  request_post('https://api.weixin.qq.com/merchant/order/getbyid?access_token=' . ACCESS_TOKEN, $postJson) ;
        $resultorderObj = json_decode($resultorder);
        $buyer_openid = $resultorderObj->{'order'}->{'buyer_openid'};
        return $buyer_openid;
    }

    //获取订单详情
    public function getOrderDetail() 
    {		
        $info = array('orderId'=>ORDERID,'userId'=>USERID,'hostId'=>HOSTID);
        $data = json_encode( array('order_id' => ORDERID) );
        $url = "https://api.weixin.qq.com/merchant/order/getbyid?access_token=" . ACCESS_TOKEN;
        $res_get = request_post($url, $data);
        $res_arr = json_decode($res_get, true);
        $order_arr=$res_arr['order'];
        return $order_arr;
    }
	
	public function getOrderProductName($aOrderDetail)
	{
		return trim( $aOrderDetail["product_name"] );
	}
	
	// 下订单时订单详情记录进数据库

    public function noteOrderInfo($orderDetail)
    {
		file_put_contents("user0.txt", 111);
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
		file_put_contents("user111.txt", 111);
		require PROJECT_ROOT . 'class/MySQLiController.class.php';
		file_put_contents("user1.txt", 111);
        $MySQLiController = new MySQLiController( $dbr );
		file_put_contents("user2.txt", 111);
		$aCol = array('order_id', 'nickname', 'openid', 'total_price', 'receiver_name', 'receiver_province', 'receiver_city', 'receiver_zone', 'receiver_address', 'receiver_mobile', 'product_id', 'product_name', 'product_price', 'product_count', 'order_create_time');
		file_put_contents("user3.txt", 111);
		$aValue = array($sOrderID, $sNickname, USERID, $nTotalPrice, $sReceiverName, $sReceiverProvince, $sReceiverCity, $sReceiverZone, $sReceiverAddress, $sReceiverTel, $sProductID, $sProductName, $nProductPrice, $nProductCount, date("Y-m-d G:i:s"));
		file_put_contents("user4.txt", 111);
		$MySQLiController->insertRow('Wechat_Order', $aCol, $aValue);
		file_put_contents("user5.txt", 111);
        $dbr->close(); 
    }
	
}

?>