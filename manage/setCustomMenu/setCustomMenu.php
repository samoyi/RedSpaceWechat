<?php
/*
 *  自定义菜单相关操作
 *
 *
 */




require '../../configuration.php';
require '../../publicFunctions.php';
define("ACCESS_TOKEN", getAccessToken());

$customMenuData = file_get_contents("customMenu.json");

$url =  'https://api.weixin.qq.com/cgi-bin/menu/create?access_token=' . ACCESS_TOKEN;
echo $result = request_post($url, $customMenuData);

?>
