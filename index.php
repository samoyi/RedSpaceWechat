<?php

/*
 * 微信后台将消息推送到该文件
 */
// 除了服务器配置以外，还要设置IP白名单。否则不能获取access token

require 'configuration.php'; // 基本配置文件

require 'publicFunctions.php'; // 公共函数  TODO 这个文件依赖configuration.php

define("ACCESS_TOKEN", getAccessToken()); // 全局 access token

// ln  o-0vY04FMoh-iVk29K9cqxHmAmjA


require 'messageDispatcher.php'; // 根据消息类型，将消息信息分发到相应模块


?>
