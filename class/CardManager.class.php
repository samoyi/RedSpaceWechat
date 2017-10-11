<?php

class CardManager
{

    // 查询相关 ————————————————————————————————————————————————————————————————

    //获取卡券详情
    /*
     * 调用微信【查看卡券详情】接口
     * 返回值为该接口返回数据的 card 属性值
     */
    public function getBaseInfo($card_id)
    {
        $data = '{"card_id": "' . $card_id . '"}';
        $result =  request_post('https://api.weixin.qq.com/card/get?access_token=' . ACCESS_TOKEN, $data);
        $resultorderObj = json_decode($result);
		$baseInfo = $resultorderObj->card;
        return $baseInfo;
    }


    // 返回用户一种卡券的所有具体卡券的状态组成的数组
    /*
     * 调用微信【查询Code接口】接口
     */
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


    // 批量查询卡券ID列表
    /*
     * 调用微信【批量查询卡券列表】接口
	 * 第一个参数为查询的卡券数量,最多支持50.
     * 第二个参数是一个数组，表示要查询哪些状态的卡券
     *     'CARD_STATUS_NOT_VERIFY'    待审核
     *     'CARD_STATUS_VERIFY_FAIL'   审核失败
     *     'CARD_STATUS_VERIFY_OK'     通过审核待投放
     *     'CARD_STATUS_DELETE'        已被商户删除
     *     'CARD_STATUS_DISPATCH'      已投放
     *   该参数默认只查询通过审核待投放和已投放两种状态的卡券
	 */
    public function batchGetCard( $nBatchCount, $aState = array('CARD_STATUS_VERIFY_OK', 'CARD_STATUS_DISPATCH') )
    {
        $nBatchCount = $nBatchCount>50 ? 50 : $nBatchCount;
        $data = '{
                    "offset": 0,
                    "count" : ' . $nBatchCount . ',
                    "status_list":  '. json_encode($aState) .'
                    }
                ';
        $result =  request_post('https://api.weixin.qq.com/card/batchget?access_token=' . ACCESS_TOKEN, $data);
        $resultorderObj = json_decode($result);
        return $aCardID = $resultorderObj->card_id_list;
    }


	// 获取用户已领取的卡券（包括领取后已经使用的）
	/*
     * 调用微信【获取用户已领取卡券接口】接口
	 * 包括正常状态和未生效状态
	 * 不写第二个参数则返回所有的卡券，第二个参数传入某款卡券ID，则只包含该款卡券的列表
	 */
	public function getUserCardList($sOpenID, $sCardID="")
	{
		$url = 'https://api.weixin.qq.com/card/user/getcardlist?access_token=' . ACCESS_TOKEN;
		if( empty($sCardID) )
		{
			$data = '{
			  "openid": "' . $sOpenID . '"
			}';
		}
		else
		{
			$data = '{
			  "openid": "' . $sOpenID . '",
			  "card_id": "' . $sCardID . '"
			}';
		}

		$result = request_post($url, $data);
		return json_decode($result);
	}



    // 修改相关 ————————————————————————————————————————————————————————————————

    // 修改卡券数量
    /*
     * 调用微信【修改库存接口】接口
     * 如果要增加，则第二个参数写增加的个数，第三个参数不写或写0；如果要减少，则第二个参数写0，第三个写减少的个数
     * 如果要清空该卡券，第二个参数写0，第三个参数写“all”
     * 如果卡券类型不是general_coupon，则填写第四个参数。
     * 卡券类型：https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1451025272
     */
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
        $result = request_post($url, $data) ;
        return json_decode($result);
    }



    // 发送相关 ————————————————————————————————————————————————————————————————

    // 发送卡券
    /*
     * 调用微信【客服接口-发消息】接口
     * 第三个可选参数可以传入一个文件地址，记录每次发送的结果以及卡券ID、openID和发送时间
     */
	public function sendCard( $card_id, $sOpenID, $sSendCardNoteUrl="")
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



    // 核销相关 ————————————————————————————————————————————————————————————————

    // 核销用户某一种卡券中的其中（如果有多张）一张
    /*
     * 调用微信【查询Code接口】接口
     * 如果返回false，则说明用户没有可核销的该类卡券
     * 如果是自定义code的卡券，则第4个参数传true
     */
    public function consumeCard($sOpenID, $card_id, $bIsCustomCode=false)
    {
        $url = 'https://api.weixin.qq.com/card/code/consume?access_token=' . ACCESS_TOKEN;
        $aData = array();
        if($bIsCustomCode){
            $aData["card_id"] = $card_id;
        }

        $codes = $this->queryCode( $sOpenID, $card_id );

        $result = false;
        for($i=0; $i<count($codes); $i++){
            if($codes[$i]->can_consume === true){
                $aData["code"] = $codes[$i]->card->code;
                $result = request_post($url, json_encode($aData));
                break;
            }
        }
        return $result;
    }



    // 会员卡相关 ——————————————————————————————————————————————————————————————

    // 更改会员卡信息（不是某张具体的会员卡）
    // FIXME 数据没有解耦
    /*
     * 调用微信【更改会员卡信息接口】接口
     * 如果返回false，则说明用户没有可核销的该类卡券
     * 如果是自定义code的卡券，则第4个参数传true
     */
    public function updateMemberCardInfo($card_id)
	{
		$url = 'https://api.weixin.qq.com/card/update?access_token=' . ACCESS_TOKEN;
		$data = '{
			"card_id":"' .$card_id. '",
			"member_card":
			{
               "base_info":
			   {
                   "service_phone": "4000328374",
					"custom_url_name": "会员",
					"custom_url": "http://www.xxx.com/member",
					"custom_url_sub_title": "会员中心",
					"promotion_url_name": "商城",
					"promotion_url": "http://www.xxx.com/mall"
               },
               "activate_url": "http://www.xxx.com/active"
			}
		}';
		/*
		// 错误47001  格式错误
		$data = '{
			"card_id":"pdUqKjt3SR-nR9Bz5VK3r2_pkRus",
			"member_card":
			{
               "base_info":
			   {

               },
			   "use_custom_code": true
			}
		}'; */
		return $result =  request_post($url, $data);
	}


    // 解码encrypt_code
    /*
     * 调用微信【解码code接口】接口
     */
    private function decryptCode($encrypt_code){
        $url = 'https://api.weixin.qq.com/card/code/decrypt?access_token=' . ACCESS_TOKEN;
        $data = '{ "encrypt_code":"' .$encrypt_code. '" }';
        $result = json_decode(request_post($url, $data));
        if( $result->errcode ){
            $return = array(
                'errcode'=> $result->errcode,
                'errmsg'=> $result->errmsg
            );
            return $return;
        }
        else{
            return $result->code;
        }
    }


    // 接口激活会员卡
	/*
     * 调用微信【接口激活】接口
	 * @para $sMembershipNumber: 用户显示的卡号，可以随便设定（试了一下中文都可以）
     *                           但不能设定真正的卡号，$sCode。
     * @$encrypt_code: 通过跳转接收到的code是经过加密的$encrypt_code，直接传入即可
	 * 在已激活的状态下再次激活也可以修改显示的会员卡号
	 */
	public function activate($sCardID, $encrypt_code, $sMembershipNumber)
	{
        $code = $this->decryptCode($encrypt_code);

		$url = 'https://api.weixin.qq.com/card/membercard/activate?access_token=' . ACCESS_TOKEN;
		$data = '{
			"membership_number": "' . $sMembershipNumber . '",
			"code": "' . $code . '",
			"card_id": "' . $sCardID . '"
		}';
		$result = request_post($url, $data);
		return json_decode($result);
	}
}

?>
