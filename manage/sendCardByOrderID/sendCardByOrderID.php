<?php
	session_start();
	if (  !isset($_SESSION['valid']) || !($_SESSION['valid'] === true) ){
		header('location:../login.php');
	}

	$order_id = trim( $_POST["order_id"] );
	$card_id = trim( $_POST["card_id"] );

	require '../../configuration.php';
	require '../../publicFunctions.php';
	define("ACCESS_TOKEN", getAccessToken());

	include('../../class/OrderManager.class.php');

	$OrderManager = new OrderManager();
	$buyer_openid = $OrderManager->getOPENIDbyORDERID($order_id);

	$data = '{
                "touser":"' . $buyer_openid . '",
                "msgtype":"wxcard",
                "wxcard":{ "card_id":"' . $card_id . '" }
                }';
    $url = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=" . ACCESS_TOKEN;
    echo $result = request_post($url, $data);
?>
