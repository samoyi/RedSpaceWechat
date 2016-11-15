<?php


define("ACCESS_TOKEN", $_GET['token']); // ACCESS_TOKEN
define("USERID", $_GET['userOpenID']); // 用户openid
define("HOSTID", $_GET['hostID']); // 公众号id
define("CONTENT_FROM_USER", $_GET['userSentMessageContent']); // 用户发送的内容
define("MESSAGE_TYPE", $_GET['userSentMessageType']); // 用户发送的消息类型 MsgType
define("EVENT_TYPE", $_GET['eventType']); // 推送的事件类型 Event
define("EVENT_TYPE", $_GET['eventType']); // 推送的事件类型 Event

require "../configuration.php";
require "../publicFunctions.php";

// 记录用户交互记录
if( EVENT_TYPE !== 'unsubscribe' && EVENT_TYPE !== 'merchant_order' && EVENT_TYPE !== 'TEMPLATESENDJOBFINISH' ) // 取消关注事件会发送空的数据，因此会清空原数据
{
	require '../class/UserManager.class.php';
	$UserManager = new UserManager();
	$UserManager->noteUseBasicInfo();
}
elseif( EVENT_TYPE === 'unsubscribe' ) // 取消关注的只修改是否关注的那一栏数据
{
	require PROJECT_ROOT . 'class/MySQLiController.class.php';
    $MySQLiController = new MySQLiController( $dbr );
	$MySQLiController->updateData(
					DB_TABLE, 
					array('isSubscribing', 'modifyTime', 'type'), 
					array(0, date("Y-m-d G:i:s"), 'unsubscribe'), 
					'openID="' . USERID . '"');

}

?>