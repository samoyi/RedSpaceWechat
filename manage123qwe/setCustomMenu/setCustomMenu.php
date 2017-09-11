<?php

 require '../../configuration.php';
 require '../../publicFunctions.php';
 define("ACCESS_TOKEN", getAccessToken());

 $customMenuData = file_get_contents("customMenu.json");

if($_POST['act']==='copy'){
    if( copy('customMenu.json', 'customMenu_copy.json') )
    {
        exit('true');
    }
    else{
        exit('false');
    }
}
if($_POST['act']==='set'){
    $url =  'https://api.weixin.qq.com/cgi-bin/menu/create?access_token=' . ACCESS_TOKEN;
    $result = request_post($url, $customMenuData);
    $resultObj = json_decode($result);
    if($resultObj->errcode===0){
        exit('true');
    }
    else{
        exit($resultObj->errmsg);
    }
}





?>
