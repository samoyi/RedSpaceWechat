<?php
	session_start();
	if (  !isset($_SESSION['valid']) || !($_SESSION['valid'] === true) ){
		header('location:../login.php');
	}

	$order_id = trim( $_POST["order_id"] );
	$message = trim( $_POST["message"] );

	require '../../configuration.php';
	require '../../publicFunctions.php';
	require '../../class/MessageManager.class.php';
	define("ACCESS_TOKEN", getAccessToken());

	$messageManager = new MessageManager();
	echo $messageManager->sendCustomMessage($order_id, $message);
?>
