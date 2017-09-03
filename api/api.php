<?php


include('../configuration.php'); // 公众号配置文件
include('../publicFunctions.php'); // 公共函数  TODO 这个文件依赖configuration.php
include('../messageDispatcher.php'); // 获取微信后台推送信息


if( $_GET["act"] === 'sendCard' && !empty($_GET['openid']) && !empty($_GET['cardid']) )
{
	require "../class/CardMessager.class.php";
	$CardMessager = new CardMessager();
	$result = $CardMessager->sendCard($_GET['cardid'], $_GET['openid']);
	echo json_encode($result );
}


if( $_GET["act"] === 'product_group' )
{
	require "../class/ProductManager.class.php";
	$ProductManager = new ProductManager();
	$result = $ProductManager->getProductGroupArray();
	echo json_encode( $result );
}

if( $_GET["act"] === 'product_group_info' &&  $_GET['product_group_id'] )
{
	require "../class/ProductManager.class.php";
	$ProductManager = new ProductManager();
	$result = $ProductManager->getGroupInfo( $_GET['product_group_id'] );
	echo json_encode( $result );
}


if( $_GET["act"] === 'historical_order' &&  $_GET['open_id'] )
{
	require "../class/ProductManager.class.php";
	$ProductManager = new ProductManager();
	$result = $ProductManager->historicalOrder(  $_GET['open_id'] );
	echo json_encode( $result );
}


if( $_GET["act"] === 'historical_order' &&  $_GET['open_id'] )
{
	require "../class/ProductManager.class.php";
	$ProductManager = new ProductManager();
	$result = $ProductManager->historicalOrder(  $_GET['open_id'] );
	echo json_encode( $result );
}

if( $_GET["act"] === 'user_card_status' && !empty($_GET['openid']) && !empty($_GET['cardid']) )
{
	require "../class/CardMessager.class.php";
	$CardMessager = new CardMessager();
	$result = $CardMessager->queryCode($_GET['openid'], $_GET['cardid']);
	echo json_encode($result );
}

if( $_POST["act"] === 'consume_card' && !empty($_POST['openid']) && !empty($_POST['cardid']) )
{
	require "../class/CardMessager.class.php";
	$CardMessager = new CardMessager();
	$result = $CardMessager->consumeCard($_GET['openid'], $_GET['cardid']);
	echo json_encode($result );
}

?>
