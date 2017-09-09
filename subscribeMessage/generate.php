<meta charset="utf-8">
<?php

	/*
	 * 因为一个公众号可以有多种一次性订阅消息业务场景，比如提醒生日的，提醒忌日的。
     * 现在只有一个用于测试的test场景，根据其在sceneMap.json数组中的序号，scene为0。
     * 想要添加新的业务场景是，直接在这里调用subscribemsgCorfirmURL函数并传入一个
     * 场景名称，例如‘birthday’。如果这个场景和现有的没有重复，则会在sceneMap.json
     * 将该名称放在数组最后一项，并同时输入显示该场景的授权申请URL；如果参数中的场景
     * 已经存在，则会提醒并显示对应的URL。
     * 现在需要按照test的样子，在同级路径下新建一个birthday目录，里面要有
     * redirect.php这个个文件。用户进入上面的授权申请URL并同意授权后，会
     * 跳转到birthday/redirect.php，着这里可以进行相关操作，例如让用户输入需要提醒
     * 的生日日期，然后记录进数据库
     * 再通过 MessageManager.class.php 中的 sendSubscribeMessage 方法给用户发送订
     * 阅消息时，$sSceneName 参数必须要对应这里设置的 $sSceneName
    */

    require '../configuration.php';

    // subscribemsgCorfirmURL('test'); // 生成一个新场景

	function subscribemsgCorfirmURL($sSceneName){
        $aSceneMap = json_decode(file_get_contents('sceneMap.json'));
        $nScene = array_search($sSceneName, $aSceneMap, true);
        if( $nScene !== false ){
            echo '已有该名称的场景<br />';
        }
        else{
            $nScene = count($aSceneMap);
            $aSceneMap[] = $sSceneName;
            file_put_contents('sceneMap.json', json_encode($aSceneMap));
        }

        $curURL = (!empty($_SERVER['HTTPS']) ? "https" : "http") . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

        $url = 'https://mp.weixin.qq.com/mp/subscribemsg'
                .'?action=get_confirm'
                .'&appid=' .APPID
                .'&scene=' .$nScene
                .'&template_id=' .SUB_MSG_TEMPLATE_ID
                .'&redirect_url=' .urlencode(str_replace('generate.php', $sSceneName.'/redirect.php', $curURL))
                .'&reserved=test' // TODO 不知道这个要怎么填
                .'#wechat_redirect';
        echo $url;
    }



?>
