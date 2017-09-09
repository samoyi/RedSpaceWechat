<?php


define(ON_DUTY_TIME, 9);
define(OFF_DUTY_TIME, 17);
define(OFF_DUTY_AUTOREPLY, "您的留言已被记录，客服将在上午九点后回复您。\n\n红房子门店营业时间 7:00~22:00\n\n投诉电话：18637627906");

$messageManager = new MessageManager();

if( date('G')>(OFF_DUTY_TIME-1) || date('G')<ON_DUTY_TIME)//客服下班时间，自动回复客服已下班
{
	// 查看客服是否开启了下班时间自动回复功能
	$JSONObj = json_decode( file_get_contents(PROJECT_ROOT . 'manage/switchAutoReply/autoReplyState.json'));
	if( 'on' === $JSONObj->autoReplyByTime )
	{
		$messageManager->responseTextMsg(OFF_DUTY_AUTOREPLY);
	}
	else
	{
		$messageManager->responseNull();
	}
}
else
{
	$messageManager->responseNull();
}


?>
