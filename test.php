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


require PROJECT_ROOT . 'class/MySQLiController.class.php';
$MySQLiController = new MySQLiController( $dbr );

/* $aCol = array('order_id', 'nickname', 'openid', 'total_price', 'receiver_name', 'receiver_province', 'receiver_city', 'receiver_zone', 'receiver_address', 'receiver_mobile', 'product_id', 'product_name', 'product_price', 'product_count', 'order_create_time');
$aValue = array("10295333824952998814","犬猫店長","okV_gjrMpNfy6d5fJxqj7ph68MmU","1","小红红","河南省","信阳市","浉河区","申城大道，6月9号送达，蛋糕上请写:祝宝贝生日快乐！","15891732790","pkV_gjs26PbwCE25wdMibY2U30U4","幸福迷你粽（绿）","1","1","2016-12-05 14:49:43");
		
print_r( $MySQLiController->insertRow('Wechat_Order', $aCol, $aValue) ); */

$aCol = array('order_id', 'nickname', 'openid', 'total_price', 'receiver_name', 'receiver_province', 'receiver_city', 'receiver_zone', 'receiver_address', 'receiver_mobile', 'product_id', 'product_name', 'product_price', 'product_count', 'order_create_time');
		$aValue = array("10295333824952998814","犬猫店長","okV_gjrMpNfy6d5fJxqj7ph68MmU","1","小红红","河南省","信阳市","浉河区","申城大道，6月9号送达，蛋糕上请写:祝宝贝生日快乐！","15891732790","pkV_gjs26PbwCE25wdMibY2U30U4","幸福迷你粽（绿）","1","1","2016-12-05 14:49:43");
		file_put_contents("user444.txt", 111);
		$MySQLiController->insertRow('Wechat_Order', $aCol, $aValue);
		
//$query = 'INSERT INTO Wechat_Order(order_id,nickname,openid,total_price,receiver_name,receiver_province,receiver_city,receiver_zone,receiver_address,receiver_mobile,product_id,product_name,product_price,product_count,order_create_time) VALUES ("10295333824952998814","犬猫店長","okV_gjrMpNfy6d5fJxqj7ph68MmU","1","小红红","河南省","信阳市","浉河区","申城大道，6月9号送达，蛋糕上请写:祝宝贝生日快乐！","15891732790","pkV_gjs26PbwCE25wdMibY2U30U4","幸福迷你粽（绿）","1","1","2016-12-05 14:49:43")';



?></pre>