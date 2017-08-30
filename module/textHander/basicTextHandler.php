<?php

    // 设定每个关键词相应的回复 -------------------------------------------------

    /*
     * 设定规则：
     * 1. $aKeywordHandler 数组中，每一个 key 对应一个待回复的关键词
     * 2. 该 key 的值是一个数组，每一个数组项表示一个相应的回复方法，如果有多个数组
     *    项，则表示该关键词会触发多条回复
     * 3. 表示回复方法的数组中，key 为 sendTextMessage 的项表示回复文字，
     *    sendTextMessage 的 value 为文字内容
     * 4. 表示回复方法的数组中，key 为 sendArticalMessage 的项表示回复图文消息，
     *    sendArticalMessage 的 value 为还是一个数组。该数组如果只有一项，表示只回
     *    复单条图文；如果有多项，表示以多图文消息的形式回复
     * 5. 回复图文消息时，每一条消息的 key 必须按照 title des imageUrl articleUrl
     *    的顺序
     * 6. 如果要回复多条相同类型的消息，则第一条以后的其他条的消息类型的键名必须加一
     *    个不相同的数字后缀。例如要发送三个文字回复，则三个键名可以为
     *    “sendTextMessage”、“sendTextMessage2”和 “sendTextMessage6”。因为该数字
     *    只能是一位且不能重复，所以一次回复中相同类型的回复数最多为11个。即不带后缀
     *    的一个和后缀从0到9的10个
     */

    //
    $aKeywordHandler = array(
        "wifi" => array(
                            "sendTextMessage"=>'您所在的门店wifi密码为：redspace'
                        ),
        "WIFI" => array(
                            "sendTextMessage"=>'您所在的门店WIFI密码为：redspace'
                        ),
        "WiFi" => array(
                            "sendTextMessage"=>'您所在的门店WiFi密码为：redspace'
                        ),
        "营业时间" => array(
                            "sendTextMessage"=>'红房子门店营业时间7:00~22:00'
                        ),
        "投诉电话" => array(
                            "sendTextMessage"=>'投诉电话：18637627906'
                        ),
        "投诉" => array(
                            "sendTextMessage"=>'投诉电话：18637627906'
                        ),
        "测试回复314" => array(
                                "sendArticalMessage"=>array
                                (
                                    array
                                    (
                                        "title" => "红房子微信订蛋糕指南1",
                                        "des" => "红房子蛋糕 美味空间新灵感",
                                        "imageUrl" => "https://mmbiz.qlogo.cn/mmbiz/fYETicIfkWsWicnYhDqAjfYl0QuCBl9esrEqPKQbtibM1MEPMWbHy9puVfVfZ2h8IQbunL7KicPicUs8qGicUQ74EmAg/0?wx_fmt=jpeg",
                                        "articleUrl" => "http://mp.weixin.qq.com/s?__biz=MjM5NzA2OTIwMQ==&mid=503272296&idx=1&sn=e27544828b2c12bbdbca9a95b88b150e#rd"
                                    ),
                                    array
                                    (
                                        "title" => "红房子微信订蛋糕指南2",
                                        "des" => "红房子蛋糕 美味空间新灵感",
                                        "imageUrl" => "https://mmbiz.qlogo.cn/mmbiz/fYETicIfkWsWicnYhDqAjfYl0QuCBl9esrEqPKQbtibM1MEPMWbHy9puVfVfZ2h8IQbunL7KicPicUs8qGicUQ74EmAg/0?wx_fmt=jpeg",
                                        "articleUrl" => "http://mp.weixin.qq.com/s?__biz=MjM5NzA2OTIwMQ==&mid=503272296&idx=1&sn=e27544828b2c12bbdbca9a95b88b150e#rd"
                                    ),
                                    array
                                    (
                                        "title" => "红房子微信订蛋糕指南3",
                                        "des" => "红房子蛋糕 美味空间新灵感",
                                        "imageUrl" => "https://mmbiz.qlogo.cn/mmbiz/fYETicIfkWsWicnYhDqAjfYl0QuCBl9esrEqPKQbtibM1MEPMWbHy9puVfVfZ2h8IQbunL7KicPicUs8qGicUQ74EmAg/0?wx_fmt=jpeg",
                                        "articleUrl" => "http://mp.weixin.qq.com/s?__biz=MjM5NzA2OTIwMQ==&mid=503272296&idx=1&sn=e27544828b2c12bbdbca9a95b88b150e#rd"
                                    )
                                ),
                                "sendTextMessage"=>'test',
                                "sendArticalMessage1"=>array
                                (
                                    array
                                    (
                                        "title" => "红房子微信订蛋糕指南",
                                        "des" => "红房子蛋糕 美味空间新灵感",
                                        "imageUrl" => "https://mmbiz.qlogo.cn/mmbiz/fYETicIfkWsWicnYhDqAjfYl0QuCBl9esrEqPKQbtibM1MEPMWbHy9puVfVfZ2h8IQbunL7KicPicUs8qGicUQ74EmAg/0?wx_fmt=jpeg",
                                        "articleUrl" => "http://mp.weixin.qq.com/s?__biz=MjM5NzA2OTIwMQ==&mid=503272296&idx=1&sn=e27544828b2c12bbdbca9a95b88b150e#rd"
                                    )
                                )
                            ),
        "微信订蛋糕" => array(
                                "sendArticalMessage"=>array
                                (
                                    array
                                    (
                                        "title" => "红房子微信订蛋糕指南",
                                        "des" => "红房子蛋糕 美味空间新灵感",
                                        "imageUrl" => "https://mmbiz.qlogo.cn/mmbiz/fYETicIfkWsWicnYhDqAjfYl0QuCBl9esrEqPKQbtibM1MEPMWbHy9puVfVfZ2h8IQbunL7KicPicUs8qGicUQ74EmAg/0?wx_fmt=jpeg",
                                        "articleUrl" => "http://mp.weixin.qq.com/s?__biz=MjM5NzA2OTIwMQ==&mid=503272296&idx=1&sn=e27544828b2c12bbdbca9a95b88b150e#rd"
                                    )
                                )
                            )
    );






    // 以下为纯逻辑区域，一般情况下不需要修改 ------------------------------------

    $handledData = $aKeywordHandler[CONTENT_FROM_USER];

    // 因为微信自动回复接口一次只能回复一条消息。所以如果要回复多条，则第一条使用自
    // 动回复接口，之后的使用客服消息接口
    $nIndex = 0;
    foreach($handledData as $key=>$value)
    {
        // 如果key的最后一位是作为区分的数字，则删掉该数字
        $sLastChar = substr($key, -1);
        $key = is_numeric($sLastChar) ? strtok( $key, $sLastChar) : $key;

        if( $nIndex++ === 0 )
        {
            switch( $key )
            {
                case 'sendTextMessage':
                {
                    define("CONTENT", $value);
                    $messageManager->responseMsg( 'text' );
                    break;
                }
                case 'sendArticalMessage':
                {
                    $messageManager->sendArticalMessage($value);
                    $bIsAutoReply = true;
                    break;
                }
            }
        }
        else
        {
            switch( $key )
            {
                case 'sendTextMessage':
                {
                    $messageManager->sendTextCSMessage(USERID, $value);
                    break;
                }
                case 'sendArticalMessage':
                {
                    $messageManager->sendArticalCSMessage(USERID, $value);
                    break;
                }
            }
        }
    }

?>
