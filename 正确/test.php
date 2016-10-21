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

$aFilterAssociativeArray = array("sex"=>2, "city"=>"Xinyang");
$result = $UserManager->filterUserInfoArray( $result, $aFilterAssociativeArray );
$result1 = $UserManager->getUserInfoPropertyArray($result, "headimgurl");
$result2 = $UserManager->getUserInfoPropertyArray($result, "nickname");
print_r( $result );
foreach($result1 as $key=>$value)
{
	echo '<img src="' . $value . '" /><br />' . $result2[$key] . '<br /><br />';
}




//print_r( $result );
//file_put_contents("err.txt", json_encode($result) );

?></pre>