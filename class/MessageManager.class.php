<?php

class MessageManager
{

    //protected
    //获得推送信息
    //protected $userOpenID;


    //class wechatCallbackapiTest
    //{
        public function valid()
        {

            $echoStr = $_GET["echostr"];

            //valid signature , option

            if($this->checkSignature()){
                /*echo $echoStr;
                exit;*/
            }

        }

        public function responseMsg( $MsgType, $media_id="" )
        {
            //get post data, May be due to the different environments
            $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];


            //extract post data
            if (!empty($postStr) && ( 'null' !== $MsgType ))
            {
                /* libxml_disable_entity_loader is to prevent XML eXternal Entity Injection,
                   the best way is to check the validity of xml by yourself */
                libxml_disable_entity_loader(true);
                $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
                $fromUsername = $postObj->FromUserName;
                $toUsername = $postObj->ToUserName;
                $keyword = trim($postObj->Content);
                $time = time();
                if( 'text' === $MsgType )
                {
                    $textTpl = "<xml>
                                <ToUserName><![CDATA[" . USERID . "]]></ToUserName>
                                <FromUserName><![CDATA[" . HOSTID . "]]></FromUserName>
                                <CreateTime>12345678</CreateTime>
                                <MsgType><![CDATA[text]]></MsgType>
                                <Content><![CDATA[" .CONTENT. "]]></Content>
                            </xml>";
                }
                elseif( 'image' === $MsgType )//图片消息
                {
                	$textTpl = "<xml>
									<ToUserName><![CDATA[" . USERID . "]]></ToUserName>
                                	<FromUserName><![CDATA[" . HOSTID . "]]></FromUserName>
									<CreateTime>12345678</CreateTime>
									<MsgType><![CDATA[image]]></MsgType>
									<Image>
										<MediaId><![CDATA[" . $media_id . "]]></MediaId>
									</Image>
								</xml>";
                }
                elseif( 'news' === $MsgType )//图文消息
                {
                    $textTpl = "<xml>
                                <ToUserName><![CDATA[" . USERID . "]]></ToUserName>
                                <FromUserName><![CDATA[" . HOSTID . "]]></FromUserName>
                                <CreateTime>12345678</CreateTime>
                                <MsgType><![CDATA[news]]></MsgType>
                                <ArticleCount>1</ArticleCount>
                                <Articles>
                                <item>
                                <Title><![CDATA[" . NEWSTITLE . "]]></Title>
                                <Description><![CDATA[" . NEWSDESCRIPTION . "]]></Description>
                                <PicUrl><![CDATA[" . NEWSPICURL . "]]></PicUrl>
                                <Url><![CDATA[" . NEWSURL . "]]></Url>
                                </item>
                                </Articles>
                                </xml> ";
                }
				elseif( 'newsss' === $MsgType )//图文消息
				{
					$textTpl = "<xml>
								<ToUserName><![CDATA[" . USERID . "]]></ToUserName>
								<FromUserName><![CDATA[" . HOSTID . "]]></FromUserName>
								<CreateTime>12345678</CreateTime>
								<MsgType><![CDATA[news]]></MsgType>
								<ArticleCount>2</ArticleCount>
								<Articles>
								<item>
								<Title><![CDATA[" . NEWSTITLE . "]]></Title>
								<Description><![CDATA[" . NEWSDESCRIPTION . "]]></Description>
								<PicUrl><![CDATA[" . NEWSPICURL . "]]></PicUrl>
								<Url><![CDATA[" . NEWSURL . "]]></Url>
								</item>
								<item>
								<Title><![CDATA[" . NEWSTITLE . "2]]></Title>
								<Description><![CDATA[" . NEWSDESCRIPTION . "]]></Description>
								<PicUrl><![CDATA[" . NEWSPICURL . "]]></PicUrl>
								<Url><![CDATA[" . NEWSURL . "]]></Url>
								</item>
								</Articles>
								</xml> ";
				}


                $msgType = "text";
                $contentStr = "Welcome to wechat world!";
                $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                echo $resultStr;
                //    注释了下面一段，因为自定义菜单的click推送是没有keyword的，而且这个keyword好像也没什么用。else似乎也不会发生
                /*if(!empty( $keyword ))
                {
                    file_put_contents('err.txt', $keyword . $textTpl, FILE_APPEND  );
                    $msgType = "text";
                    $contentStr = "Welcome to wechat world!";

                    $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);

                    echo $resultStr;

                }
                else
                {
                    echo "Input something...";
                }*/
            }
            else
            {
                ob_clean();//微信的例子中没有这个，但没有这个就会报错。据说是之前有我没发现的输出内容，所以输出的就不是空字符串。
                echo '';
                exit;
            }
        }

        private function checkSignature()
        {
            // you must define TOKEN by yourself
            if (!defined("TOKEN")) {
                throw new Exception('TOKEN is not defined!');
            }

            $signature = $_GET["signature"];
            $timestamp = $_GET["timestamp"];
            $nonce = $_GET["nonce"];

            $token = TOKEN;
            $tmpArr = array($token, $timestamp, $nonce);
            // use SORT_STRING rule
            sort($tmpArr, SORT_STRING);
            $tmpStr = implode( $tmpArr );
            $tmpStr = sha1( $tmpStr );

            if( $tmpStr == $signature ){
                return true;
            }else{
                return false;
            }
        }
    //}


    //取得用户发送
    public function getUserMessage()
    {

        $fetchedMsg = $GLOBALS["HTTP_RAW_POST_DATA"];
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

        $fetchedMsg = $GLOBALS["HTTP_RAW_POST_DATA"];
        $fetchedMsgXML = simplexml_load_string($fetchedMsg, 'SimpleXMLElement', LIBXML_NOCDATA);

        $postedEvent = array('userOpenID'=> $fetchedMsgXML->FromUserName,
                            'hostID'=> $fetchedMsgXML->ToUserName,
                            'userSentMessageType'=> trim($fetchedMsgXML->MsgType),
                            'eventType'=> trim($fetchedMsgXML->Event),
                            'eventKey'=> trim($fetchedMsgXML->EventKey),
                            'orderID'=>$fetchedMsgXML->OrderId);
        return $postedEvent;
    }

    //发送文本消息
    public function sendTextMessage()//TODO 这个没用了？
    {
        $wechatObj->responseMsg( 'text' );
    }

    //发送图片
    public function sendImage( $media_id )
    {
    	$result = $this->responseMsg( 'image', $media_id);
    }

	// 发送图文消息。不超过十条
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

    // 发送客服消息
	/*
	 *	默认第二个参数为true，即发送完客服消息后再进行空回复，结束会话
	 *	如果之后还要发送其他消息，这个参数应该设为false
	 */
    public function sendCSMessage( $content, $bSendNull=true )
    {
        $json = '{
                    "touser": "' . USERID . '",
                    "msgtype":"text",
                    "text":
                    {
                           "content":"' . $content . '"
                    }
                }';
        $url = 'https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=' . ACCESS_TOKEN;
        $result = request_post($url, $json);
        ifRefreshAccessTokenAndRePost($result, 'https://api.weixin.qq.com/merchant/order/getbyfilter?access_token=', $json );
        if( $bSendNull )
        {
            $this->responseMsg( 'null' );
        }
    }

	// 发送文字客服消息
	public function sendTextCSMessage($sOpenID, $sContent)
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
		return $result = request_post($url, $data);
	}

	// 发送图片客服消息
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
		return $result = request_post($url, $data);
	}

	// 发送图文客服消息（最多8条）
	public function sendArticalCSMessage($sOpenID, $aArticleInfo)
	{	file_put_contents("err.txt", $sOpenID . "   ", FILE_APPEND);
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
		file_put_contents("err.txt", $nArticleAmount . "   ", FILE_APPEND);
		$data = '{
					"touser":"' . $sOpenID . '",
					"msgtype":"news",
					"news":{
						"articles": '. decodeUnicode(json_encode($aArticles)) .'
					}
				}';
		$url = 'https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=' . ACCESS_TOKEN;
		file_put_contents("err.txt", $data . "   ", FILE_APPEND);
		return $result = request_post($url, $data);
	}

	// 发送单条图文客服消息（根据素材ID）
	public function sendNewsCSMessage($sOpenID, $sMediaID)
	{
		$url = 'https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=' . ACCESS_TOKEN;
		$data = '{
					"touser":"' . $sOpenID . '",
					"msgtype":"mpnews",
					"mpnews":
					{
						 "card_id":"' . $sMediaID . '"
					}
				}';
		return $result = request_post($url, $data);
	}

	// 发送卡券客服消息
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
		return $result = request_post($url, $data);
	}

    //根据订单号发送文字客服消息
    public function sendCustomMessage( $order_id, $msg )
    {
        include('OrderManager.class.php');
        $orderManager = new OrderManager();
        $openid = $orderManager->getOPENIDbyORDERID( $order_id );

        $data = '{
            "touser" :"' . $openid . '",
            "msgtype":"text",
            "text":{"content":"' . $msg . '"}
        }';
        $url = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=" . ACCESS_TOKEN;
        $result = request_post($url, $data);
        exit( $result ) ;
    }


    //发送卡券领取消息
    public function sendCardReceivedMessage( $txt)
    {
        define("CONTENT", $txt);
        $this->responseMsg( 'text' );
    }

    //发送模板消息
    // 参数$orderDetail为订单详情数组
	public function sendTemplateMessage($orderDetail, $sOpenID, $ad="", $detailUrl="")
    {
        $template = array(
            'touser'        =>  $sOpenID,
            'template_id'   =>  "444pldIlaFSHxWzAS7eoG4K7cvGb0vIqm4XY0JBkv60",
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
        $result = request_post($url_post, json_encode($template));
        $result = ifRefreshAccessTokenAndRePost($result, 'https://api.weixin.qq.com/merchant/order/getbyfilter?access_token=', $template );
		return $result;
    }

    // 发送订阅消息
    public function sendSubscribeMessage($nScene, $sOpenID, $sTitle, $sMessage, $sFontColor, $sTemplateID, $sRedirectURL=''){
        $url_post = "https://api.weixin.qq.com/cgi-bin/message/template/subscribe?access_token=" . ACCESS_TOKEN;
        $data = array(
                    "touser"=> $sOpenID,
                    "template_id"=> $sTemplateID,
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
