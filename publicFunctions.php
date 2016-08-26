<?php
/*
 * 公共函数
 *
 */

function refreshAccessToken()//刷新access_token
{   
    $url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=' . APPID . '&secret=' . APPSECRET;
        $jsoninfo = json_decode(httpGet($url), true);
        
        $new_access_token =  $jsoninfo["access_token"];

        $configrationJSON->last_access_token = $new_access_token;

        $configrationJSON->last_access_token_time = time();
        
        //file_put_contents('configration.js', json_encode($configrationJSON) ); 
        //将本次获得的access_token存入文件，并记录获得时间
        file_put_contents(PROJECT_ROOT . 'configration.js', json_encode($configrationJSON) );
        return $new_access_token;
}

function getAccessToken()//获取access_token
{   
    //$configrationJSON = json_decode( file_get_contents('configration.js') ); 
    $configrationJSON = json_decode( file_get_contents(PROJECT_ROOT . 'configration.js') ); 
    $last_access_token_time = $configrationJSON->last_access_token_time;//读取上次调用接口取得access_token的时间
    
    if( time()- $last_access_token_time <3600 )//如果没到保质期7200秒，直接返回旧的
    {   //之前出现过距离7200秒很远就不能用的情况，所以这里改成3600秒
        return $configrationJSON->last_access_token;   
    }
    else//如果马上或已经到了保质期，重新获取，然后记录本次获取的时间和access_token。
    {   
        return refreshAccessToken();
    }
}

// 检测是否ACCESS_TOKEN已过期，过期则刷新并重发请求
// 第一个参数是用户某个请求的返回值，第二个参数是该请求的地址不包含ACCESS_TOKEN的部分，第三个参数是该请求发送的数据
function ifRefreshAccessTokenAndRePost( $return, $urlWithoutACCESS_TOKEN, $data)
{
    $returnObj = json_decode($return);
    if( 40001 == $returnObj->errcode )// 如果返回值的错误代码是40001，代表AccessToken已失效，
    {
        $url = $urlWithoutACCESS_TOKEN . refreshAccessToken(); // 刷新AccessToken并重发请求
        return request_post($url, $data); // 返回请求结果
    } 
    else
    {
        return $return;
    }
}

function httpGet($url)//发送GET请求
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
function request_post($url, $data)//发送POST请求
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
?>