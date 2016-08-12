﻿<?php

class CardMessager
{
    //获取卡券信息
    public function getBaseInfo($card_id) 
    {
        $postJson = '{"card_id": "' . $card_id . '"}';
        $result =  request_post('https://api.weixin.qq.com/card/get?access_token=' . ACCESS_TOKEN, $postJson) ;
        $resultorderObj = json_decode($result);
        $baseInfo = $resultorderObj->{'card'}->{'general_coupon'}->{'base_info'};
        return $baseInfo;
    }


    //发送卡券
    public function sendCard( $card_id )
    {
        $data = '{
                "touser":"' . USERID . '", 
                "msgtype":"wxcard",
                "wxcard":{ "card_id":"' . $card_id . '" }
                }';
        $url = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=" . ACCESS_TOKEN;
        $result = request_post($url, $data);    
        $messageManager = new MessageManager();
        $messageManager->responseMsg( 'null' );
    }

    //修改卡券数量
    //之前测试无效，返回的是空
    /*public function changeQuantity( $card_id, $increase_stock_value=0, $reduce_stock_value=0 )
    {
        $postJson = '{
                        "card_id": "' . $card_id . '",
                        "increase_stock_value": ' . $increase_stock_value . ',
                        "reduce_stock_value": ' . $reduce_stock_value . '
                    }';
        $url = 'https://api.weixin.qq.com/card/modifystock?access_token=' . ACCESS_TOKEN;
        $result =  request_post($ur, $postJson) ;
        return $result;
    }*/
}

?>