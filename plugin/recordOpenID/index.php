<?php

	$sFinalUrl = "";
	$sScriptUrl = "http://red-space.cn/wechat/manage/noteUserInfo.php";

	$sTokenArg = "token=" . ACCESS_TOKEN;
	$sUserOpenID = "userOpenID=" . USERID;
	$sHostIDArg = "hostID=" . HOSTID;
	$sUserSentMessageContentArg = "userSentMessageContent=" . CONTENT_FROM_USER;
	$sUserSentMessageTypeArg = "userSentMessageType=" . MESSAGE_TYPE;
	$sEventTypeArg = "eventType=" . EVENT_TYPE;

	$sFinalUrl = $sScriptUrl . "?" .  $sTokenArg . "&" . $sUserOpenID . "&"
				. $sHostIDArg . "&" . $sUserSentMessageContentArg . "&"
				. $sUserSentMessageTypeArg . "&" . $sEventTypeArg;
	$result = HTTP_GET($sFinalUrl);


?>
