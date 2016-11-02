<?php

	//$card_id = trim( $_POST["nickname"] );

	include('../configuration.php'); // 公众号配置文件
	include('../publicFunctions.php'); // 公共函数  TODO 这个文件依赖configuration.php
	include('../WechatPushed.php'); // 获取微信后台推送信息
	
	include('../class/UserManager.class.php');

	$UserManager = new UserManager();
	$aUserList = $UserManager->getOpenIDArray(); 
 
    print_r($aUserList) ; 
?>
