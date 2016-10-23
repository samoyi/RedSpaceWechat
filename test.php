<pre><?php

include('configration.php'); // 公众号配置文件
include('publicFunctions.php'); // 公共函数  TODO 这个文件依赖configration.php
include('WechatPushed.php'); // 获取微信后台推送信息



echo urldecode('%E6%B5%8B%E8%AF%95%E5%9B%9E%E5%A4%8D314');
require 'class/MySQLiController.class.php';
$MySQLiController = new MySQLiController( $dbr );


//echo $dbr->real_escape_string('<h1></h1>');


$userid = 'okV_gjrMpNfy6d5fJxqj7ph68MmU';
$messagetype = 'ev11';
//取得符合WHERE条件的一个或多个row。该函数的返回值需要循环使用fetch_array来依次取值
$aRowInDB = $MySQLiController->getRow(DB_TABLE, 'openID="' . $userid . '"' );
if( $aRowInDB->fetch_array( )) // 如果数据库中已经有该用户的数据行
{
    $MySQLiController->updateData(DB_TABLE, array('event'), array($messagetype), 'openID="' . $userid . '"');
}
else
{
    $aRow = array('0, "' . $userid . '", "' . $messagetype . '", ""');
    $MySQLiController->insertRow(DB_TABLE, $aRow);
}
$dbr->close();

?></pre>