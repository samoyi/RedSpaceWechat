<?php


// 刷新 access token
function refreshAccessToken()
{
    // 调用微信接口请求 access token
    $url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=' . APPID . '&secret=' . APPSECRET;
    $arr = json_decode(HTTP_GET($url), true);
    $newAccessToken =  $arr["access_token"];

    // 读取之前保存的 access token 信息，并将其修改为最新的
    $oLastAccessTokenInfo = json_decode( file_get_contents(PROJECT_ROOT . 'access_token.json') );
    $oLastAccessTokenInfo->last_access_token = $newAccessToken;
    $oLastAccessTokenInfo->last_access_token_time = time();

    // 将最新的 access token 信息存入文件
    file_put_contents(PROJECT_ROOT . 'access_token.json', json_encode($oLastAccessTokenInfo) );

    return $newAccessToken;
}


// 获取access token 如果本地保存的没有过期则使用本地的，否则重新请求
function getAccessToken()
{
    $oLastAccessTokenInfo = json_decode( file_get_contents(PROJECT_ROOT . 'access_token.json') );

    $lastAccessTokenTime = $oLastAccessTokenInfo->last_access_token_time;
    $sLastAccessToken = $oLastAccessTokenInfo->last_access_token;

    if( (time()-$lastAccessTokenTime<3600) && !empty($sLastAccessToken)  )//如果没到保质期7200秒，直接返回旧的
    {   /*
		 * 之前出现过距离7200秒很远就不能用的情况，所以这里改成3600秒
		 * 之所以加第二个判断，是因为出现过一次返回的access token 是 NULL，导致在时间没到之前不会刷新为正确的。
		 */
        return $sLastAccessToken;
    }
    else // 如果马上或已经到了保质期，重新获取
    {
        return refreshAccessToken();
    }
}


// 根据POST请求结果检测是否出现了因为 access toke 已过期而导致的错误。如果有则重发请求
/*
 * 第一个参数是用户某个请求的返回值
 * 第二个参数是该请求URL不包含 access toke 的部分。如：
 *     https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=
 * 第三个参数是该请求发送的数据
 */
function __ifExpirationRefreshAccessTokenAndRePost( $result, $urlWithoutACCESS_TOKEN, $data)
{
    $resultObj = json_decode($result);
    if( 40001 == $resultObj->errcode ) // access token 过期
    {
        $url = $urlWithoutACCESS_TOKEN . refreshAccessToken();
        return request_post($url, $data);
    }
    else
    {
        return $result;
    }
}
// get版本
function __ifExpirationRefreshAccessTokenAndReGet( $result, $urlWithoutACCESS_TOKEN)
{
    $resultObj = json_decode($result);
    if( 40001 == $resultObj->errcode )
    {
        $url = $urlWithoutACCESS_TOKEN . refreshAccessToken();
        return request_get($url, $data);
    }
    else
    {
        return $result;
    }
}


// 附带验证 access token 的 GET 请求
function request_get($url)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    // curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $output = curl_exec($ch);
    curl_close($ch);

    $urlWithoutACCESS_TOKEN = strtok($url, "access_token=") . "access_token=";
    return $output = __ifExpirationRefreshAccessTokenAndReGet( $output, $urlWithoutACCESS_TOKEN);
}


// 标准 GET 请求
function HTTP_GET($url)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    // curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $output = curl_exec($ch);
    curl_close($ch);
    return $output;
}


// 附带验证 access token 的 POST 请求
function request_post($url, $data)
{
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $output = curl_exec( $curl );
    curl_close($curl);

    $urlWithoutACCESS_TOKEN = strtok($url, "access_token=") . "access_token=";
    return $output = __ifExpirationRefreshAccessTokenAndRePost( $output, $urlWithoutACCESS_TOKEN, $data);
}


// 标准 POST 请求
function HTTP_POST($url, $data)
{
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $output = curl_exec( $curl );
    curl_close($curl);
    return $output;
}


// 根据插件配置文件加载插件
// 只有在插件配置文件里设定为true的插件才能成功加载
function requirePlugin($pluginName){
    require PROJECT_ROOT . 'plugin/config.php';
    $err = array(
        'err'=> 0,
        'errMsg'=> ''
    );
    if( array_key_exists($pluginName, $pluginConfig) ){
        if( $pluginConfig[$pluginName] ){
            require PROJECT_ROOT . 'plugin/' . $pluginName . '/index.php';
        }
        else{
            $err[`err`] = 2;
            $err[`errMsg`] = 'This plugin is not available';
        }
    }
    else{
        $err[`err`] = 1;
        $err[`errMsg`] = 'No this plugin';
    }
    return $err;
}

?>
