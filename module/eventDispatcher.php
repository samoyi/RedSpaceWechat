<?php

$postedEvent = $messageManager->getPostedEvent(); // 获得事件推送
define("EVENT_TYPE", $postedEvent['eventType']); // 推送的事件类型 Event
define("EVENT_KEY", $postedEvent['eventKey']); // 事件KEY
define("ORDERID", $postedEvent['orderID']); // 订单事件推送时的订单ID


// 根据事件类型，分发至响应的模块处理
switch( EVENT_TYPE )
{
    case 'subscribe':
    {
        require 'module/eventHandlers/subscribeHandler.php';
        break;
    }
    case 'CLICK' :
    {
        require 'module/eventHandlers/clickHandler.php';
        break;
    }
    case 'user_get_card' :
    {
        require 'module/eventHandlers/userGetCardHandler.php';
        break;
    }
    case 'merchant_order' :
    {
        require 'module/eventHandlers/merchantOrderHandler.php';
        break;
    }
	case 'SCAN':
	{
        require 'module/eventHandlers/scanHandler.php';
		break;
	}
    default :
    {
        $messageManager->responseMsg( 'null' );
        // 这里会发送空字符串，也就是不回复。但是要注意这之后不能再发送其他东西，包
        // 括修改自定义菜单之类的，否则发送的就不是空字符串，且也不是合理的回复格式，
        // 就会显示无法服务。
    }
}

?>
