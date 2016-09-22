<?php

class CardMessager
{
    //获取卡券信息
    public function getBaseInfo($card_id, $card_type='general_coupon') 
    {
        $data = '{"card_id": "' . $card_id . '"}';
        $result =  request_post('https://api.weixin.qq.com/card/get?access_token=' . ACCESS_TOKEN, $data);
        ifRefreshAccessTokenAndRePost($result, 'https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=', $data ); 
        $resultorderObj = json_decode($result);
        $baseInfo = $resultorderObj->{'card'}->$card_type->{'base_info'};
        return $baseInfo;
    }

    // 批量查询卡券列表
    // 第一个参数为查询的卡券数量最多支持50.
    // 第二个参数如果为真，输出的不是id数组，而是卡券标题数组
    public function batchGetCard( $nBatchCount, $bDisplayCardName=false )
    {
        $nBatchCount = $nBatchCount>50 ? 50 : $nBatchCount;
        $data = '{
                    "offset": 0,
                    "count" : ' . $nBatchCount . ',
                    "status_list":  ["CARD_STATUS_VERIFY_OK", "CARD_STATUS_DISPATCH"]
                    }
                ';
        $result =  request_post('https://api.weixin.qq.com/card/batchget?access_token=' . ACCESS_TOKEN, $data);
        ifRefreshAccessTokenAndRePost($result, 'https://api.weixin.qq.com/card/batchget?access_token=', $data ); 
        $resultorderObj = json_decode($result);
        $aCardID = $resultorderObj->{'card_id_list'};

        if( $bDisplayCardName )
        {
            $aCardTitle = array();
            foreach($aCardID as $value)
            {
                $aCardTitle[] = $this->getBaseInfo($value)->title; 
            }
            return $aCardTitle;
        }
        else
        {
            return $aCardID;        
        }
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
        ifRefreshAccessTokenAndRePost($result, 'https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=', $data ); 
        $messageManager = new MessageManager();
        $messageManager->responseMsg( 'null' );
    }

    //关键词回复卡券，领完后再发送关键词回复文字消息
    //参数分别为卡券id，领完再发送关键词回复的提示消息文本和$messageManager实例对象
    public function getCardByKeyWords( $card_id, $outReplayText, $messageManager)
    {
        $remainQuantity = $this->getBaseInfo($card_id)->sku->quantity;
        if( $remainQuantity > 0 )
        {
            $this->sendCard($card_id);
        }    
        else
        {
            define("CONTENT", $outReplayText);
            $messageManager->responseMsg( 'text' );
        }              
    }
    //修改卡券数量
    // 如果要增加，则第二个参数写增加的个数，不写第三个参数；如果要减少，则第二个参数写0，第三个写减少的个数
    // 如果要清空该卡券，第二个参数写0，第三个参数写“all”
    // 如果卡券类型不是general_coupon，则填写第四个参数
    public function changeQuantity( $card_id, $increase_stock_value=0, $reduce_stock_value=0, $card_type='general_coupon')
    {
        if( 'all' === trim($reduce_stock_value) )
        {
            $reduce_stock_value = $this->getBaseInfo($card_id, $card_type)->sku->quantity;
        }
        $data = '{
                        "card_id": "' . $card_id . '",
                        "increase_stock_value": ' . $increase_stock_value . ',
                        "reduce_stock_value": ' . $reduce_stock_value . '
                    }';
        $url = 'https://api.weixin.qq.com/card/modifystock?access_token=' . ACCESS_TOKEN;
        $result =  request_post($url, $data) ;
        ifRefreshAccessTokenAndRePost($result, 'https://api.weixin.qq.com/card/modifystock?access_token=', $data ); 
        return $result;
    }
}

?>