<?php

/*
 * 微信后台将消息、事件等推送到该文件
 *
 */


include('../publicFunctions.php'); // 公共函数
include('../configuration.php'); // 公众号配置文件
include('../WechatPushed.php'); // 获取微信后台推送信息


include('../class/MaterialManager.class.php');    
$MaterialManager = new MaterialManager();  
//echo $MaterialManager->addImageGetImedaID("test1.jpg");



?>