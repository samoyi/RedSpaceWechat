<?php

    $arr = json_decode( file_get_contents('manage/subscribeAutoReply/subscribeAutoPlayText.json'));
    $content = '';
    foreach( $arr as $value){
        $content .= $value;
    }
    $messageManager->sendTextCSMessage(USERID, $content);

?>
