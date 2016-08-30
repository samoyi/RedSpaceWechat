<?php

	include('../configration.php'); // 公众号配置文件
	include('../publicFunctions.php'); // 公共函数  TODO 这个文件依赖configration.php
	include('../WechatPushed.php'); // 获取微信后台推送信息

	$JSONObj = json_decode( file_get_contents('manageConfigration.js'));
	$autoReplyByTimeState = $JSONObj->autoReplyByTime;

	if( 'on' === $autoReplyByTimeState )
	{
		$JSONObj->autoReplyByTime = 'off';
		echo '下班时段自动回复已关闭';
	}
	else
	{
		$JSONObj->autoReplyByTime = 'on';
		echo '下班时段自动回复已打开';
	}
	file_put_contents('manageConfigration.js', json_encode($JSONObj));
?>