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
	echo $messageManager->sendTextCSMessage($open_id, $message, false);
?>
