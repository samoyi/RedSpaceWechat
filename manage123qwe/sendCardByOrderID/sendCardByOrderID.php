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

	include('../../class/CardMessager.class.php');
	$CardMessager = new CardMessager();
	$result = $CardMessager->sendCard($card_id, $buyer_openid);
	echo json_encode($result);

?>
