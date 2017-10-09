<?php

class StoreManager
{

    // 查询相关 ————————————————————————————————————————————————————————————————

    // 查询门店列表
    /*
     * 调用微信【获取门店信息列表】接口
     * 返回值为该接口返回数据的 card 属性值
     */
    public function getStoreList($nOffset, $nLimit=20)
    {
        $aData = array(
            'offset'=> $nOffset,
            'limit'=> $nLimit
        );
        $result =  request_post('https://api.weixin.qq.com/wxa/get_store_list?access_token=' . ACCESS_TOKEN, json_encode($aData));
        $resultorderObj = json_decode($result);
        if( $resultorderObj->errcode ){
            return array(
                $resultorderObj->errcode,
                $resultorderObj->errmsg
            );
        }
        else{
            return $resultorderObj->business_list;
        }
    }


}

?>
