<?php

/*
 * 微信后台将消息推送到该文件
 */

require 'configuration.php'; // 基本配置文件

require 'publicFunctions.php'; // 公共函数  TODO 这个文件依赖configuration.php

define("ACCESS_TOKEN", getAccessToken()); // 全局 access token

require 'messageDispatcher.php'; // 根据消息类型，将消息信息分发到相应模块


?>
