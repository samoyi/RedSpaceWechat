<?php

/*
 * 微信后台将消息、事件等推送到该文件
 *
 */


include('publicFunctions.php'); // 公共函数
include('configration.php'); // 公众号配置文件
include('WechatPushed.php'); // 获取微信后台推送信息


include('class/MaterialManager.class.php');    
$MaterialManager = new MaterialManager();  
$image_info = array(
    'filename'=>'/images/1.png',  //国片相对于网站根目录的路径
    'content-type'=>'image/png',  //文件类型
    'filelength'=>'156023'         //图文大小
);                                  
$MaterialManager->addImage($image_info);



?>