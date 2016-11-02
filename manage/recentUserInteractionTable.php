<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
<title>最近100个用户交互记录</title>
<style>
table
{
	border-collapse: collapse;

}
td
{
	padding: 0.5em;
}
</style>
</head>
<body>

<?php
include('../configuration.php'); // 公众号配置文件
include('../publicFunctions.php'); // 公共函数  TODO 这个文件依赖configuration.php
include('../WechatPushed.php'); // 获取微信后台推送信息

require   PROJECT_ROOT . 'class/UserManager.class.php';
$UserManager = new UserManager();
$UserManager->echoRecentUserInteractionTable();

?>
</body>
</html>