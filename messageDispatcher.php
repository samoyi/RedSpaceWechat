<?php

/*
 * 根据不同的消息类型，调用不同的模块来进行处理
 */


// 获取消息类型和各类型消息通用的消息信息 ----------------------------------------
require 'class/MessageManager.class.php';
$messageManager = new MessageManager();

$messageManager->valid(); // TODO 为什么接入成功就不能再调用了

$userMessage = $messageManager->getUserMessage(); // 获得消息
define("MESSAGE_TYPE", $userMessage['userSentMessageType']); // 用户发送的消息类型 MsgType

define("USERID", $userMessage['userOpenID']); // 用户openid
define("HOSTID", $userMessage['hostID']); // 公众号id




// 根据消息类型调用不同的模块 ---------------------------------------------------
switch(MESSAGE_TYPE)
{
    case "text":
    {
        require 'module/textDispatcher.php';
		break;
    }
    case 'event':
    {
        // 事件类型的消息还会分好几种子类型，eventDispatcher模块中会再细分
        require 'module/eventDispatcher.php';
        break;
    }
    default:
    {
        require 'module/defaultHandler.php';
    }
}



// 插件：记录OpenID -------------------------------------------------------------
requirePlugin('recordOpenID');

?>
