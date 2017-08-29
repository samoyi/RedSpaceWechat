<?php

    define(ON_DUTY_TIME, 9);
    define(OFF_DUTY_TIME, 17);
    define(OFF_DUTY_AUTOREPLY, file_get_contents("manage/offDutyAutoreplyText.json"));

    // TODO 这里常量定义如果放在keywords文件中，非关键字消息会自动回复 'OFF_DUTY_AUTOREPLY'

    if( date('G')>(OFF_DUTY_TIME-1) || date('G')<ON_DUTY_TIME)//客服下班时间，自动回复客服已下班
    {
        include('manage/manager.php');
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
