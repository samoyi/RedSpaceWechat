<pre><?php

/*
 * 微信后台将消息、事件等推送到该文件
 *
 */

include('configration.php'); // 公众号配置文件
include('publicFunctions.php'); // 公共函数  TODO 这个文件依赖configration.php
include('WechatPushed.php'); // 获取微信后台推送信息






/*include('class/ProductManager.class.php');
$productManager = new ProductManager();

$result = $productManager->modifyProduct("pkV_gjsTaeMWcNxzoVNWLXBRQlhM", 537074298);*/

include('class/UserManager.class.php');
$UserManager = new UserManager();
$result = $UserManager->getOpenIDArray();
$result = array_chunk($result, 500);

$result = $UserManager->getUserInfoBatch( $result[0] );



print_r( $result );



//print_r( $result );
//file_put_contents("err.txt", json_encode($result) );

?></pre>