<?php




require '../configuration.php'; // 公众号配置文件

// 验证IP
if( !in_array($_SERVER['REMOTE_ADDR'], json_decode(WHITE_API_IP)) ){ exit; }

require '../publicFunctions.php'; // 公共函数  TODO 这个文件依赖configuration.php
define("ACCESS_TOKEN", getAccessToken()); // 全局 access token





// 给某个用户发送某款卡券
if( $_POST["act"] === 'sendCard' && !empty($_POST['openid']) && !empty($_POST['cardid']) )
{
	require "../class/CardMessager.class.php";
	$CardMessager = new CardMessager();
	$result = $CardMessager->sendCard($_POST['cardid'], $_POST['openid']);
	echo json_encode($result );
}


// 获取微信小店商品分组
if( $_GET["act"] === 'product_group' )
{
	require "../class/ProductManager.class.php";
	$ProductManager = new ProductManager();
	$result = $ProductManager->getProductGroupArray();
	echo json_encode( $result );
}


// 根据微信小店商品分组ID查询该商品分组信息
if( $_GET["act"] === 'product_group_info' &&  $_GET['product_group_id'] )
{
	require "../class/ProductManager.class.php";
	$ProductManager = new ProductManager();
	$result = $ProductManager->getGroupInfo( $_GET['product_group_id'] );
	echo json_encode( $result );
}


// 查询某个用户的历史订单
if( $_GET["act"] === 'historical_order' &&  $_GET['open_id'] )
{
	require "../class/ProductManager.class.php";
	$ProductManager = new ProductManager();
	$result = $ProductManager->historicalOrder(  $_GET['open_id'] );
	echo json_encode( $result );
}


// 查询某个用户某款卡券的状态
if( $_GET["act"] === 'user_card_status' && !empty($_GET['openid']) && !empty($_GET['cardid']) )
{
	require "../class/CardMessager.class.php";
	$CardMessager = new CardMessager();
	$result = $CardMessager->queryCode($_GET['openid'], $_GET['cardid']);
	echo json_encode($result );
}


// 核销某个用户的某款卡券
if( $_POST["act"] === 'consume_card' && !empty($_POST['openid']) && !empty($_POST['cardid']) )
{
	require "../class/CardMessager.class.php";
	$CardMessager = new CardMessager();
	$result = $CardMessager->consumeCard($_GET['openid'], $_GET['cardid']);
	echo json_encode($result );
}

?>
