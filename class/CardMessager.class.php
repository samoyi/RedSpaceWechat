<?php

class CardMessager
{
    //获取卡券信息
    public function getBaseInfo($card_id) 
    {
        $data = '{"card_id": "' . $card_id . '"}';
        $result =  request_post('https://api.weixin.qq.com/card/get?access_token=' . ACCESS_TOKEN, $data);
        ifRefreshAccessTokenAndRePost($result, 'https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=', $data ); 
        $resultorderObj = json_decode($result);
		$baseInfo = $resultorderObj->{'card'};
        return $baseInfo;
    }

    // 批量查询卡券ID列表
    /*
	 *	参数为查询的卡券数量,最多支持50.
	 */ 
    public function batchGetCard( $nBatchCount )
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
        return $aCardID = $resultorderObj->{'card_id_list'};
    }

    //自动回复卡券
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

    //根据OpenID发送卡券
    public function sendCardByOpenID( $card_id, $sOpenID )
    {
        $data = '{
                "touser":"' . $sOpenID  . '", 
                "msgtype":"wxcard",
                "wxcard":{ "card_id":"' . $card_id . '" }
                }';
        $url = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=" . ACCESS_TOKEN;
        $result = request_post($url, $data);   
        return $result;
    }
	
	// 获取用户已领取的卡券
	/* 
	 * 包括正常状态和未生效状态
	 * 不写第二个参数则是所有的卡券，第二个参数传入某款卡券ID，则只包含改款卡券的列表
	 * 在指定卡券ID的情况下，返回值得card_list是改款卡券的数组列表，如果为空数组则表示没有改款卡券
	 */
	public function getUserCardList($sOpenID, $sCardID="")
	{	
		$url = 'https://api.weixin.qq.com/card/user/getcardlist?access_token=' . ACCESS_TOKEN;
		if( isset($sCardID) )
		{
			$data = '{
			  "openid": "' . $sOpenID . '",
			  "card_id": "' . $sCardID . '"
			}';
		}
		else
		{
			$data = '{
			  "openid": "' . $sOpenID . '"
			}';
		}
		
		$result = request_post($url, $data);
		return json_decode($result);
	}

    //修改卡券数量
    // 如果要增加，则第二个参数写增加的个数，第三个参数不写或写0；如果要减少，则第二个参数写0，第三个写减少的个数
    // 如果要清空该卡券，第二个参数写0，第三个参数写“all”
    // 如果卡券类型不是general_coupon，则填写第四个参数。
    public function changeQuantity( $card_id, $increase_stock_value=0, $reduce_stock_value=0, $card_type='general_coupon')
    {
        if( 'all' === trim($reduce_stock_value) )
        {
            $reduce_stock_value = $this->getBaseInfo($card_id)->$card_type->base_info->sku->quantity;
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