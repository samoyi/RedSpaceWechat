<?php

    $arr = json_decode( file_get_contents('manage/JSONData/subscribeAutoPlayText.json'));
    $content = '';
    foreach( $arr as $value){
        $content .= $value;
    }
    $messageManager->sendCSMessage($content, false);

?>
