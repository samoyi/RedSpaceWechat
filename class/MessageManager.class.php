<?php

class MessageManager
{

    //取得用户发送
    public function getUserMessage()
    {
        $fetchedMsg = file_get_contents('php://input');
        libxml_disable_entity_loader(true); // prevent XXE attack
        $fetchedMsgXML = simplexml_load_string($fetchedMsg, 'SimpleXMLElement', LIBXML_NOCDATA);

        $userMessage = array('userOpenID'=> $fetchedMsgXML->FromUserName,
                            'hostID'=> $fetchedMsgXML->ToUserName,
                            'userSentMessageType'=> trim($fetchedMsgXML->MsgType),
                            'userSentMessageContent'=> trim($fetchedMsgXML->Content));
        return $userMessage;
    }


    //取得事件推送
    public function getPostedEvent()
    {
        $fetchedMsg = file_get_contents('php://input');
        libxml_disable_entity_loader(true); // prevent XXE attack
        $fetchedMsgXML = simplexml_load_string($fetchedMsg, 'SimpleXMLElement', LIBXML_NOCDATA);

        $postedEvent = array('userOpenID'=> $fetchedMsgXML->FromUserName,
                            'hostID'=> $fetchedMsgXML->ToUserName,
                            'userSentMessageType'=> trim($fetchedMsgXML->MsgType),
                            'eventType'=> trim($fetchedMsgXML->Event),
                            'eventKey'=> trim($fetchedMsgXML->EventKey),
                            'orderID'=>$fetchedMsgXML->OrderId);
        return $postedEvent;
    }


    // 结束会话，不回复内容
    public function responseNull(){
        ob_clean();//微信的例子中没有这个，但没有这个就会报错。据说是之前有我没发现的输出内容，所以输出的就不是空字符串。
        echo '';
        exit;
    }


    // 回复文本消息
    public function responseTextMsg($sContent)
    {
        $msg = "<xml>
                    <ToUserName><![CDATA[" . USERID . "]]></ToUserName>
                    <FromUserName><![CDATA[" . HOSTID . "]]></FromUserName>
                    <CreateTime>" .time(). "</CreateTime>
                    <MsgType><![CDATA[text]]></MsgType>
                    <Content><![CDATA[" .$sContent. "]]></Content>
                </xml>";
        echo $msg;
    }

    // 回复图片消息
    public function sendImage( $media_id )
    {
        $msg = "<xml>
                        <ToUserName><![CDATA[" . USERID . "]]></ToUserName>
                        <FromUserName><![CDATA[" . HOSTID . "]]></FromUserName>
                        <CreateTime>" .time(). "</CreateTime>
                        <MsgType><![CDATA[image]]></MsgType>
                        <Image>
                            <MediaId><![CDATA[" . $media_id . "]]></MediaId>
                        </Image>
                    </xml>";
        echo $msg;
    }

	// 回复图文消息
    /*
     * 一次不超过十条
     * $aArticleInfo是一个二维数组。每个数组项是一个图文消息的信息，为一个4项关联
     *   数组，如下示例
     *      array(
     *          "title"=>"图文消息标题",
     *         "des"=>"图文消息描述",
     *         "articleUrl"=>"", // 图文消息链接
     *         "imageUrl"=>"" // 图文消息缩略图
     *      )
     */
    public function sendArticalMessage($aArticleInfo)
    {
		$nArticleAmount = count( $aArticleInfo );

		$textTplFront = "<xml>
							<ToUserName><![CDATA[" . USERID . "]]></ToUserName>
							<FromUserName><![CDATA[" . HOSTID . "]]></FromUserName>
							<CreateTime>" . time() . "</CreateTime>
							<MsgType><![CDATA[news]]></MsgType>
							<ArticleCount>" . $nArticleAmount . "</ArticleCount>
							<Articles>";
		$textTplBehind = "</Articles>
					</xml> ";
		foreach( $aArticleInfo as $item )
		{
			$textTplFront .= "<item>
								<Title><![CDATA[" . $item["title"] . "]]></Title>
								<Description><![CDATA[" . $item["des"] . "]]></Description>
								<PicUrl><![CDATA[" . $item["imageUrl"] . "]]></PicUrl>
								<Url><![CDATA[" . $item["articleUrl"] . "]]></Url>
							</item>";
		}

		echo $textTpl = $textTplFront . $textTplBehind;
    }


	// 客服消息发送文字
    /*
     * 调用微信【客服接口-发消息】接口
	 * 默认第三个参数为true，即发送完客服消息后再进行空回复，结束会话
	 * 如果之后还要发送其他消息，这个参数应该设为false
	 */
	public function sendTextCSMessage($sOpenID, $sContent, $bSendNull=true)
	{
		$url = 'https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=' . ACCESS_TOKEN;
		$data = '{
					"touser":"' . $sOpenID . '",
					"msgtype":"text",
					"text":
					{
						 "content":"' . $sContent . '"
					}
				}';
		$result = request_post($url, $data);
        if( $bSendNull )
        {
            $this->responseNull();
        }
        return $result;
	}


	// 客服消息发送图片
    /*
     * 调用微信【客服接口-发消息】接口
	 */
	public function sendImageCSMessage($sOpenID, $sMediaID)
	{
		$url = 'https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=' . ACCESS_TOKEN;
		$data = '{
					"touser":"' . $sOpenID . '",
					"msgtype":"image",
					"image":
					{
						 "media_id":"' . $sMediaID . '"
					}
				}';
        return request_post($url, $data);
	}


	// 客服消息发送图文
    /*
     * 调用微信【客服接口-发消息】接口
     * 一次最多8条
	 * 参数参考 sendArticalMessage
	 */
	public function sendArticalCSMessage($sOpenID, $aArticleInfo)
	{
		$nArticleAmount = count( $aArticleInfo );
		$aArticles = array();
		foreach( $aArticleInfo as $item )
		{
			$aArticles[] = array(
					"title"=>$item["title"],
					"description"=>$item["des"],
					"url"=>$item["articleUrl"],
					"picurl"=>$item["imageUrl"]
			);
		}
		$data = '{
					"touser":"' . $sOpenID . '",
					"msgtype":"news",
					"news":{
						"articles": '. json_encode($aArticles, JSON_UNESCAPED_UNICODE) .'
					}
				}';
		$url = 'https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=' . ACCESS_TOKEN;
		return request_post($url, $data);
	}


	// 客服消息发送卡券
    /*
     * 调用微信【客服接口-发消息】接口
	 */
	public function sendCardCSMessage($sOpenID, $sCardID)
	{
		$url = 'https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=' . ACCESS_TOKEN;
		$data = '{
					"touser":"' . $sOpenID . '",
					"msgtype":"wxcard",
					"wxcard":
					{
						 "card_id":"' . $sCardID . '"
					}
				}';
		return request_post($url, $data);
	}


    //根据订单号发送文字客服消息
    public function sendCustomMessage( $order_id, $msg )
    {
        require PROJECT_ROOT . 'class/OrderManager.class.php';
        $orderManager = new OrderManager();
        $openid = $orderManager->getOPENIDbyORDERID( $order_id );

        $result = $this->sendTextCSMessage($sOpenID, $sContent, false);
        exit( $result ) ;
    }


    //发送模板消息
    /*
     * 调用微信【发送模板消息】接口
     * 参数$orderDetail为订单详情数组
	 */
	public function sendTemplateMessage($orderDetail, $sOpenID, $ad="", $detailUrl="")
    {
        file_put_contents('template.txt', $orderDetail['product_name']);
        $template = array(
            'touser'        =>  $sOpenID,
            'template_id'   =>  "_-MiirGkaabk-yTkax4igwEy_UmgPFBK0WNB2XTOohw",
            'url'           =>  $detailUrl,
            'data'          =>  array(
                'first'     =>  array('value'   =>("订单详情可点击下方 帮助中心-订单查询\n咨询电话：0376-6506386")),
                'product'   =>  array('value'   =>($orderDetail['product_name']), 'color'=> '#ea386c'),
                'price'     =>  array('value'   =>("￥".$orderDetail['order_total_price']/100), 'color'=> '#ea386c'),
                'time'      =>  array('value'   =>(date("Y-m-d H:i:s",$orderDetail['order_create_time'])), 'color'=> '#ea386c'),
                'remark'    =>  array('value'   =>(" \n$ad"), 'color'=> '#565656')
            )
        );
        $url_post = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=" . ACCESS_TOKEN;
        return request_post($url_post, json_encode($template));
    }


    // 发送订阅消息
    /*
     * 调用微信【一次性订阅消息】接口
     * $sSceneName 为通过 subscribeMessage\generate.php 生成新场景时使用的场景名
     * 如果返回false，则说明之前没有设定该名为$sSceneName的场景
	 */
    public function sendSubscribeMessage($sSceneName, $sOpenID, $sTitle, $sMessage, $sFontColor, $sRedirectURL=''){
        $url_post = "https://api.weixin.qq.com/cgi-bin/message/template/subscribe?access_token=" . ACCESS_TOKEN;
        $aSceneMap = json_decode(file_get_contents(PROJECT_ROOT.'subscribeMessage/sceneMap.json'));
        $nScene = array_search($sSceneName, $aSceneMap, true);
        if( $nScene===false ){
            return false;
        }
        $data = array(
                    "touser"=> $sOpenID,
                    "template_id"=> SUB_MSG_TEMPLATE_ID,
                    "url"=> $sRedirectURL,
                    "scene"=> $nScene,
                    "title"=> $sTitle,
                    "data"=> array(
                        "content"=> array(
                            "value"=> $sMessage,
                            "color"=> $sFontColor
                        )
                    )
                );
        $result = request_post($url_post, json_encode($data));
        return json_decode($result);
    }
}


?>
