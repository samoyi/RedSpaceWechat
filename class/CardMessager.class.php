<?php

class CardMessager
{
    //获取卡券信息
    public function getBaseInfo($card_id)
    {
        $data = '{"card_id": "' . $card_id . '"}';
        $result =  request_post('https://api.weixin.qq.com/card/get?access_token=' . ACCESS_TOKEN, $data);
        //ifRefreshAccessTokenAndRePost($result, 'https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=', $data );
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
        //ifRefreshAccessTokenAndRePost($result, 'https://api.weixin.qq.com/card/batchget?access_token=', $data );
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
    }

    //根据OpenID发送卡券
    /*
     *  第三个可选参数可以传入一个文件地址，记录每次发送的结果以及卡券ID、openID和发送时间
     */
	public function sendCardByOpenID( $card_id, $sOpenID, $sSendCardNoteUrl="")
    {
        $data = '{
                "touser":"' . $sOpenID  . '",
                "msgtype":"wxcard",
                "wxcard":{ "card_id":"' . $card_id . '" }
                }';
        $url = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=" . ACCESS_TOKEN;
        $result = request_post($url, $data);
        $resultObj = json_decode($result);
        if( !empty($sSendCardNoteUrl) )
        {
			file_put_contents($sSendCardNoteUrl, $result .' '. $card_id .' '. $sOpenID .' '. date("Y-m-d G:i:s") . "\n", FILE_APPEND);
        }
        return $resultObj;
    }

	// 获取用户已领取的卡券（包括领取后已经使用的）
	/*
	 * 包括正常状态和未生效状态
	 * 不写第二个参数则是所有的卡券，第二个参数传入某款卡券ID，则只包含该款卡券的列表
	 * 在指定卡券ID的情况下，返回值得card_list是该款卡券的数组列表，如果为空数组则表示没有该款卡券
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


    // 修改卡券数量
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
        //ifRefreshAccessTokenAndRePost($result, 'https://api.weixin.qq.com/card/modifystock?access_token=', $data );
        return $result;
    }


    // 核销相关 ————————————————————————————————————————————————————————————————

    // 返回用户一种卡券的所有具体卡券厄状态组成的数组
    // 对用官网文档中的查询code接口
    public function queryCode( $sOpenID, $card_id )
    {
        // 获取指定用户的指定卡券
        $cards = $this->getUserCardList($sOpenID, $card_id)->card_list;

        // 根据code获得具体一个卡券的信息
        function getCardInfo($card_id, $code){
            $url = 'https://api.weixin.qq.com/card/code/get?access_token=' . ACCESS_TOKEN;
            $data = '{
                       "card_id" : "' .$card_id. '",
                       "code" : "' .$code. '",
                       "check_consume" : true
                    }';
            $result = request_post($url, $data);

            return json_decode($result);
        }

        $cardInfo = array();
        for($i=0; $i<count($cards); $i++){
            $cardInfo[] = getCardInfo($card_id, $cards[$i]->code);
        }

        return $cardInfo;
    }

    // 核销用户某一种卡券中的其中（如果有多张）一张
    // 对应官网文档中的核销code接口
    // 如果是自定义code的卡券，则第4个参数传true
    public function consumeCard($sOpenID, $card_id, $bIsCustomCode=false){
        $url = 'https://api.weixin.qq.com/card/code/consume?access_token=' . ACCESS_TOKEN;
        $aData = array();
        if($bIsCustomCode){
            $aData["card_id"] = $card_id;
        }

        $codes = $this->queryCode( $sOpenID, $card_id );

        $result = "no can consume card";
        for($i=0; $i<count($codes); $i++){
            if($codes[$i]->can_consume === true){
                $aData["code"] = $codes[$i]->card->code;
                file_put_contents('test.txt', json_encode($aData) . "\n\n", FILE_APPEND);
                $result = request_post($url, json_encode($aData));
                break;
            }
        }

        return $result;
    }
}

?>
