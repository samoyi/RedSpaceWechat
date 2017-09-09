<?php
	session_start();
	if (  !isset($_SESSION['valid']) || !($_SESSION['valid'] === true) ){
		header('location:../login.php');
	}

	$open_id = trim( $_POST["open_id"] );
	$message = trim( $_POST["message"] );

	require '../../configuration.php';
	require '../../publicFunctions.php';
	require '../../class/MessageManager.class.php';
	define("ACCESS_TOKEN", getAccessToken());

	$messageManager = new MessageManager();
	$orderDetail = array(
		'product_name'=>$message,
		'order_total_price'=>99,
		'order_create_time'=>time()
	);

	$result = $messageManager->sendTemplateMessage($orderDetail, $open_id);
	echo json_encode($result);
?>
