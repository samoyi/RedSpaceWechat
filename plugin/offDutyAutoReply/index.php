<?php


define(ON_DUTY_TIME, 9);
define(OFF_DUTY_TIME, 17);
define(OFF_DUTY_AUTOREPLY, "您的留言已被记录，客服将在上午九点后回复您。\n\n红房子门店营业时间7:00~22:00\n微信小店24小时自助下单，当天17：00之后的订单在第二天10：00之后可提货\n投诉电话：18637627906");

// require 'class/MessageManager.class.php';
$messageManager = new MessageManager();

if( date('G')>(OFF_DUTY_TIME-1) || date('G')<ON_DUTY_TIME)//客服下班时间，自动回复客服已下班
{
	require PROJECT_ROOT . 'manage/manager.php';
	$manager = new Manager();
	// 查看客服是否开启了下班时间自动回复功能
	$autoReplyByTimeState = $manager->getAutoReplyByTimeState();

	if( 'on' === $autoReplyByTimeState )
	{
		define("CONTENT", OFF_DUTY_AUTOREPLY);
		$messageManager->responseMsg( 'text' );
	}
	else
	{
		$messageManager->responseMsg( 'null' );
	}
}
else
{
	$messageManager->responseMsg( 'null' );
}


?>
