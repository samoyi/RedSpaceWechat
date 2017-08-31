<?php
/*
 *  自定义菜单相关操作
 *
 *
 */
include('../configuration.php'); // 公众号配置文件
include('../publicFunctions.php'); // 公共函数
include('../messageDispatcher.php'); // 获取微信后台推送信息

include('../class/CustomMenu.class.php');
$customMenu = new CustomMenu();

    $customMenuData = file_get_contents("JSONData/customMenu.json");
    echo $customMenu->createMenu( $customMenuData);   // 设置自定义菜单

?>
