<?php


	/*
	 * 如果检测到消息类型是文字，则加载该文件
	 *
	 * 首先检测用户输入文本是否在 $aComplicatedKeywords 数组中。如果在，则加载
	 *     complicatedTextHander.php 文件进行处理
	 *
	 * 如果用户的输入不在 $aComplicatedKeywords 数组中，检测是否在 $aBasicKeywords
	 *     数组中，如果在，则加载 basicTextHandler.php 文件进行处理
	 *
	 * 如果还没有找到匹配的关键词，则加载 noKeyWordsMatch.php 文件进行处理
	 *
	 *
	 * 只在 complicatedTextHander.php 或  basicTextHandler.php 添加了相应关键词的
	 *     的处理代码而不在该文件中将关键词添加到数组中，并不会触发期望的处理
	 */

	 $aComplicatedKeywords = array('刷新接口', '切换自动回复314', '测试');
	 $aBasicKeywords = array("wifi", "WIFI", "WiFi", "测试回复314", "微信订蛋糕", "营业时间", "投诉电话", "投诉");


?>
