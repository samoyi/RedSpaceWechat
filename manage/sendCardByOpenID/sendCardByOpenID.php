<?php
	session_start();
	if (  !isset($_SESSION['valid']) || !($_SESSION['valid'] === true) ){
		header('location:../login.php');
	}

	$open_id = trim( $_POST["open_id"] );
	$card_id = trim( $_POST["card_id"] );

	require '../../configuration.php';
	require '../../publicFunctions.php';
	define("ACCESS_TOKEN", getAccessToken());

	include('../../class/CardMessager.class.php');
	$CardMessager = new CardMessager();
	$result = $CardMessager->sendCard($card_id, $open_id);
	echo json_encode($result);
?>
