<pre><?php

include('configration.php'); // 公众号配置文件
include('publicFunctions.php'); // 公共函数  TODO 这个文件依赖configration.php
include('WechatPushed.php'); // 获取微信后台推送信息



echo urldecode('%E6%B5%8B%E8%AF%95%E5%9B%9E%E5%A4%8D314');
require 'class/MySQLiController.class.php';
$MySQLiController = new MySQLiController( $dbr );


echo $dbr->real_escape_string('<h1></h1>');
/*$aRow = array('0, ' . 'USERID' . ', ' . 'CONTENT_FROM_USER');

$aValue = array('0, "li", "17", ""');
var_dump( $MySQLiController->insertRow(DB_TABLE, $aValue) );
$dbr->close();*/

?></pre>