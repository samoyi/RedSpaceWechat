<pre><?php

/*
 * 微信后台将消息、事件等推送到该文件
 *
 */

include('configuration.php'); // 公众号配置文件
include('publicFunctions.php'); // 公共函数  TODO 这个文件依赖configuration.php
include('WechatPushed.php'); // 获取微信后台推送信息


print_r( $result );

//关键词自动回复和事件推送回复
//该部分最后会跳出程序，下面这行应该是放在最后
//include('module/messageAutoReply.php');



?></pre>