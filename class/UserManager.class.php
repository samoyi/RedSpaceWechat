<?php

class UserManager
{

    // 直接获取用户总数
    function __get( $property )
    {
        if( 'nTotalUserNumber' === $property )
        {
            $oUserList = $this->getUserList();
            return $oUserList->total;
        }
    }


    // 获取用户列表
    /*
     * 调用微信【获取用户列表】接口
     * 每次最多获得一万条。
     * 当公众号关注者数量超过10000时，可通过填写$sNextOpenID的值从而多次拉取列表的
     * 方式来满足需求。具体而言，将上一次调用得到的返回中的next_openid值，作为下一次
     * 调用中的$sNextOpenID值。
     */
    public function getUserList($sNextOpenID="")
    {
        $sNextOpenIDPara = $sNextOpenID ? "&next_openid=$sNextOpenID" : "";
        $url = 'https://api.weixin.qq.com/cgi-bin/user/get?access_token=' . ACCESS_TOKEN . $sNextOpenIDPara;
        return json_decode(request_get($url));
    }


	// 获取用户总数
	public function getUserAmount()
	{
		return $this->getUserList()->total;
	}


    // 获取用户基本信息
    /*
     * 调用微信【获取用户基本信息】接口
     */
    public function getUserInfo($sOpenID)
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/user/info?access_token=' . ACCESS_TOKEN . '&openid=' . $sOpenID . '&lang=zh_CN';
        return json_decode( request_get($url) );
    }


    // 批量获取用户基本信息
    /*
     * 调用微信【批量获取用户基本信息】接口
     * 一次获取过多用户信息将会耗时过长导致微信报错
     */
    public function getUserInfoBatch($aOpenID)
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/user/info/batchget?access_token=' . ACCESS_TOKEN;
        $aOpenIDChunk = array_chunk($aOpenID, 100); // 该接口一次最多查询100个

        $aUserInfoChunk = array();
        $aUserList = array();
        $aUserInfoList = array();
        foreach($aOpenIDChunk as $value)
        {
            foreach($value as $innerValue)
            {
                $aUserList[] = json_decode('{"openid": "' . $innerValue . '", "lang": "zh-CN"}');
            }
            $data = '{
                "user_list": ' . json_encode($aUserList) . '
            }';

            $result = json_decode(request_post($url, $data));
            $aUserInfoList = array_merge($aUserInfoList, $result->user_info_list);
            unset( $aUserList );
        }
        return $aUserInfoList;
    }


    // 过滤 getUserInfoBatch 方法返回的数组
    /*
     * 第二个参数是关联数组，一个数组项的键对应用户信息中的某个属性，只有该属性值和
     *   该键值完全相同才会保留该用户信息。并且每一个数组项都要对应才行。
     * 例如传入 array("sex"=>2, "city"=>"Xinyang") 将从$aUserInfo中 挑选性别
     * 女且城市为Xinyang的予以保留
     */
    public function filterUserInfoArray( $aUserInfo, $aFilter )
    {
        foreach($aUserInfo as $key=>$value) // 循环每一个用户信息
        {
            foreach($aFilter as $innerKey=>$innerValue) // 循环每一个过滤器数组项
            {
                 // 只要有一个过滤器的值不在当前用户信息中，就从用户信息数组中删除这一条
                if( $innerValue !== $value->$innerKey )
                {
                    unset($aUserInfo[$key]);
                }
            }
        }
        return $aUserInfo;
    }


    // 从 getUserInfoBatch 方法返回的数组提取某一项组成一个数组
    public function getUserInfoPropertyArray($aUserInfo, $sProperty)
    {
        $aPropertyArray = array();
        foreach($aUserInfo as $value)
        {
            $aPropertyArray[] = $value->$sProperty;
        }
        return $aPropertyArray;
    }

}


?>
