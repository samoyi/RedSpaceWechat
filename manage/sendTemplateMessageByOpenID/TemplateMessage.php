<?php
	session_start();
	if (  !isset($_SESSION['valid']) || !($_SESSION['valid'] === true) ){
		header('location:../login.php');
	}

	$open_id = trim( $_POST["open_id"] );
	$message = trim( $_POST["message"] );

	require '../../configuration.php';
	require '../../publicFunctions.php';
	require '../../class/TemplateMessage.class.php';
	define("ACCESS_TOKEN", getAccessToken());

	$templateMessage = new TemplateMessage();
	$orderDetail = array(
		'product_name'=>$message,
		'product_name'=>$message,
		'order_id'=>'dingdanbianhao',
		'order_total_price'=>99,
		'order_create_time'=>time()
	);
	$result = $templateMessage->orderPaymentNotice($orderDetail, $open_id, '欢迎惠顾');
	echo json_encode($result);
?>
