<pre><?php

/*
 * 微信后台将消息、事件等推送到该文件
 *
 */

include('configuration.php'); // 公众号配置文件
include('publicFunctions.php'); // 公共函数  TODO 这个文件依赖configuration.php
include('WechatPushed.php'); // 获取微信后台推送信息



// okV_gjqh3xvy2JmuNLoLIyKiBV_c


require PROJECT_ROOT . 'class/MySQLiController.class.php';
//$MySQLiController = new MySQLiController( $dbr );



$query = 'UPDATE Wechat_OpenID SET receiver_name="小红红",receiver_province="河南省",receiver_city="信阳市",receiver_zone="浉河区",receiver_address="申城大道，6月9号送达，蛋糕上请写:祝宝贝生日快乐！" WHERE openID="okV_gjrMpNfy6d5fJxqj7ph68MmU"';
$dbr->query( $query );
//$MySQLiController->changeColumnType(DB_TABLE, 'isSubscribing', 'INT UNSIGNED NOT NULL');

//关键词自动回复和事件推送回复
//该部分最后会跳出程序，下面这行应该是放在最后
include('module/messageAutoReply.php');




?></pre>