<?php
/*
 *  自定义菜单相关操作
 *
 *
 */




require '../../configuration.php';
require '../../publicFunctions.php';
define("ACCESS_TOKEN", getAccessToken());

include('../../class/CustomMenu.class.php');
$customMenu = new CustomMenu();

$customMenuData = file_get_contents("customMenu.json");

echo $customMenu->createMenu( json_decode($customMenuData) );   // 设置自定义菜单

?>
