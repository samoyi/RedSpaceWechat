<?php

/*
 * 获取微信后台推送的信息
 *
 */




//获得access_token
define("ACCESS_TOKEN", getAccessToken());


include('class/MessageManager.class.php');
$messageManager = new MessageManager();


$messageManager->valid(); // TODO 为什么接入成功就不能再调用了


$userMessage = $messageManager->getUserMessage(); // 获得消息内容
$postedEvent = $messageManager->getPostedEvent(); // 获得事件推送
define("USERID", $userMessage['userOpenID']); // 用户openid
define("HOSTID", $userMessage['hostID']); // 公众号id
define("CONTENT_FROM_USER", $userMessage['userSentMessageContent']); // 用户发送的内容
define("MESSAGE_TYPE", $userMessage['userSentMessageType']); // 用户发送的消息类型 MsgType
define("EVENT_TYPE", $postedEvent['eventType']); // 推送的事件类型 Event
define("EVENT_KEY", $postedEvent['eventKey']); // 事件KEY
define("ORDERID", $postedEvent['orderID']); // 订单事件推送时的订单ID


?>
