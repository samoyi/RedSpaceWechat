﻿<?php

class CustomMenu
{
	//创建或重写自定义菜单。参数为菜单按钮设置
    public function createMenu( $customMenuData)
    {
        $url =  'https://api.weixin.qq.com/cgi-bin/menu/create?access_token=' . ACCESS_TOKEN;
        $result = request_post($url, json_encode($customMenuData, JSON_UNESCAPED_UNICODE) );
        return $result;
    }
}

?>
