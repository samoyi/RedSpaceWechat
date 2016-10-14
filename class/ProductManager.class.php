<?php

class ProductManager
{
    // 获取指定状态的所有商品的 product_id 及对应 name 组成的数组
    // 参数 0 代表所有商品，1 代表已上架商品， 2 代表未上架商品
    public function queryProductIDs( $nStatus = 0)
    {   
        $url = 'https://api.weixin.qq.com/merchant/getbystatus?access_token=' . ACCESS_TOKEN;
        $data = '{
                    "status": ' . $nStatus . '
                }';
        $result = request_post($url, $data);
        $result = ifRefreshAccessTokenAndRePost( $result, 'https://api.weixin.qq.com/merchant/getbystatus?access_token=', $data);
        
        $aProducts = json_decode($result)->products_info; // 所有商品

        $aIDs = array();
        foreach( $aProducts as $value )
        {
            $aIDs[$value->product_id] = $value->product_base->name;
        }
        return $aIDs;
    }


    // 获得商品信息
    public function queryProductBase( $sProductID )
    {
        $url = 'https://api.weixin.qq.com/merchant/get?access_token=' . ACCESS_TOKEN;
        $data = '{
                    "product_id": "' . $sProductID . '"
                }';
        $result = request_post($url, $data);
        $result = ifRefreshAccessTokenAndRePost( $result, 'https://api.weixin.qq.com/merchant/get?access_token=', $data);
        return json_decode($result);

    }


    // 获取指定状态的所有商品
    public function queryProducts( $nStatus = 0)
    {   
        $url = 'https://api.weixin.qq.com/merchant/getbystatus?access_token=' . ACCESS_TOKEN;
        $data = '{
                    "status": ' . $nStatus . '
                }';
        $result = request_post($url, $data);
        $aProducts = json_decode($result)->products_info;

        $oneskuinfo = $aProducts[2]->product_base->sku_info; // index 为 1 的是日期设置
        //file_put_contents("err.txt", json_encode($oneskuinfo[1]->vid) ); 
        $result = ifRefreshAccessTokenAndRePost( $result, 'https://api.weixin.qq.com/merchant/getbystatus?access_token=', $data);

        return $result; 
    }


    

    // 获取商品 sku_info
    public function querySkuInfoArray( $sProductID )
    {
        return $this->queryProductBase( $sProductID )->product_info->product_base->sku_info;
    }


    // 修改商品 
    // 未完成。获得了POST数据中未修改的内容。如果要修改某一部分，把未修改的和修改的拼接为完整的POST数据
    public function modifyProduct( $sProductID )
    {
        $oldProduct_info = $this->queryProductBase( $sProductID )->product_info;
        $oldProductBase = $oldProduct_info->product_base;
        $oldSkuList = $oldProduct_info->sku_list;
        $oldAttrext = $oldProduct_info->attrext;
        $oldDeliveryInfo = $oldProduct_info->delivery_info;


        
    }

    // 修改某项sku_info.
    // 第二个参数是该项的id，第三个参数是该id对应的vid数组
    /* sku_info 格式如下

        [
            {
                "id": "$蛋糕尺寸",
                "vid": [
                    "$10寸（适合6-8人）",
                    "$8寸（适合3-5人）"
                ]
            },
            {
                "id": "$送达日期",
                "vid": [
                    "$10月14日",
                    "$10月15日",
                    "$10月16日",
                    "$10月17日",
                    "$10月18日"
                ]
            },
            {
                "id": "$送达时刻（请提前3小时预定）",
                "vid": [
                    "$10点-11点",
                    "$11点-12点",
                    "$12点-13点",
                    "$15点-16点",
                    "$16点-17点",
                    "$17点-18点",
                    "$18点-19点",
                    "$（其他时刻联系客服登记）"
                ]
            },
            {
                "id": "$配送方式（市区免费配送）",
                "vid": [
                    "$*（祝福语请备注在收货地址栏）",
                    "$配送到户",
                    "$门店自提",
                    "$（*自提门店请填写在地址栏）"
                ]
            }
        ]
    */
    //public function modifySkuInfo( $sProductID, $sID, $aVID )
    public function modifySkuInfo()
    {   
        $sProductID = 'pkV_gjsTaeMWcNxzoVNWLXBRQlhM';
        $oldProduct_info = $this->queryProductBase( $sProductID )->product_info;
        $oldProductBase = $oldProduct_info->product_base;
        $oldSkuList = $oldProduct_info->sku_list;
        $oldAttrext = $oldProduct_info->attrext;
        $oldDeliveryInfo = $oldProduct_info->delivery_info;

        $oldSkuInfo = $oldProductBase->sku_info;

        $newVID = array("\$大前天", "\$大后天");
        
        $oldSkuInfo[0]->vid = $newVID;
        $oldSkuList[0]->sku_id = "\$发货日期:\$大前天;";
        $oldSkuList[1]->sku_id = "\$发货日期:\$大后天;";

        $detailHTML = $oldProductBase->detail_html;
        $oldProductBase->detail_html = str_replace('"', '\'', $detailHTML );

        /*foreach( $oldSkuInfo as $key=>$value )
        {
            if( $value->id === $sID )
            {
                $oldSkuInfo[$key]->vid = $aVID;
                break;
            }
        }*/

        function decodeUnicode($str)
        {
            return preg_replace_callback('/\\\\u([0-9a-f]{4})/i',
                create_function(
                    '$matches',
                    'return mb_convert_encoding(pack("H*", $matches[1]), "UTF-8", "UCS-2BE");'
                ),
                $str);

        }

        $url = 'https://api.weixin.qq.com/merchant/update?access_token=' . ACCESS_TOKEN;
        $data = json_encode( $oldProduct_info );
        file_put_contents("err.txt", $data );
        $data = str_replace('\"', '"', $data);
        $data = decodeUnicode( $data );
        
        $data = str_replace('\\/', '/', $data);

        $url = 'https://api.weixin.qq.com/merchant/update?access_token=' . ACCESS_TOKEN;

        /*$codeArray = array(
            'UTF-8', 'ASCII',
            'ISO-8859-1', 'ISO-8859-2', 'ISO-8859-3', 'ISO-8859-4', 'ISO-8859-5',
            'ISO-8859-6', 'ISO-8859-7', 'ISO-8859-8', 'ISO-8859-9', 'ISO-8859-10',
            'ISO-8859-13', 'ISO-8859-14', 'ISO-8859-15', 'ISO-8859-16',
            'Windows-1251', 'Windows-1252', 'Windows-1254',
            );
        $encode = mb_detect_encoding($data, $codeArray); 
        $data = mb_convert_encoding($data, 'UTF-8', $encode);*/

        //$data = iconv("ISO-8859-1", "UTF-8", $data); 

        $result = request_post($url, $data);
        $result = ifRefreshAccessTokenAndRePost( $result, 'https://api.weixin.qq.com/merchant/update?access_token=', $data);
        return $result;
    }


    public function addProduct()
    {
        $url = 'https://api.weixin.qq.com/merchant/create?access_token=' . ACCESS_TOKEN;
        $data = '{
    "product_base": {
        "category_id": [
            "537074298"
        ],
        "property": [
            {
                "id": "1075741879",
                "vid": "1079749967"
            },
            {
                "id": "1075754127",
                "vid": "1079795198"
            },
            {
                "id": "1075777334",
                "vid": "1079837440"
            }
        ],
        "name": "test",
        "sku_info": [
            {
                "id": "1075741873",
                "vid": [
                    "1079742386",
                    "1079742363"
                ]
            },
            {
                "id": "$日期",
                "vid": [
                    "$今天",
                    "$明天"
                ]
            }
        ],
        "main_img": "http://mmbiz.qpic.cn/mmbiz/4whpV1VZl2iccsvYbHvnphkyGtnvjD3ulEKogfsiaua49pvLfUS8Ym0GSYjViaLic0FD3vN0V8PILcibEGb2fPfEOmw/0", 
        "img": [
            "http://mmbiz.qpic.cn/mmbiz/4whpV1VZl2iccsvYbHvnphkyGtnvjD3ulEKogfsiaua49pvLfUS8Ym0GSYjViaLic0FD3vN0V8PILcibEGb2fPfEOmw/0"
        ],
        "detail": [
            {
                "text": "test first"
            },
            {
                "img": "http://mmbiz.qpic.cn/mmbiz/4whpV1VZl2iccsvYbHvnphkyGtnvjD3ul1UcLcwxrFdwTKYhH9Q5YZoCfX4Ncx655ZK6ibnlibCCErbKQtReySaVA/0"
            },
            {
                "text": "test again"
            }
        ],
        "buy_limit": 10
    },
    "sku_list": [
        {
            "sku_id": "$日期:$今天;1075741873:1079742386",
            "price": 30,
            "icon_url": "http://mmbiz.qpic.cn/mmbiz/4whpV1VZl28bJj62XgfHPibY3ORKicN1oJ4CcoIr4BMbfA8LqyyjzOZzqrOGz3f5KWq1QGP3fo6TOTSYD3TBQjuw/0",
            "product_code": "testing",
            "ori_price": 9000000,
            "quantity": 800
        },
        {
            "sku_id": "$日期:$明天;1075741873:1079742363",
            "price": 30,
            "icon_url": "http://mmbiz.qpic.cn/mmbiz/4whpV1VZl28bJj62XgfHPibY3ORKicN1oJ4CcoIr4BMbfA8LqyyjzOZzqrOGz3f5KWq1QGP3fo6TOTSYD3TBQjuw/0",
            "product_code": "testingtesting",
            "ori_price": 9000000,
            "quantity": 800
        }
    ],
    "attrext": {
        "location": {
            "country": "中国",
            "province": "广东省",
            "city": "广州市",
            "address": "T.I.T创意园"
        },
        "isPostFree": 0,
        "isHasReceipt": 1,
        "isUnderGuaranty": 0,
        "isSupportReplace": 0
    },
    "delivery_info": {
        "delivery_type": 0,
        "template_id": 0, 
        "express": [
            {
                "id": 10000027, 
                "price": 100
            }, 
            {
                "id": 10000028, 
                "price": 100
            }, 
            {
                "id": 10000029, 
                "price": 100
            }
        ]
    }
}';


        $result = request_post($url, $data);
        $result = ifRefreshAccessTokenAndRePost( $result, 'https://api.weixin.qq.com/merchant/create?access_token=', $data);
        return $result;
    }


    // 临时测试
    // 直接手写date属性可以修改成功，但获取原data再修改为新的data之后修改出问题
    public fnction temp_modifyProduct()
    {
        $url = 'https://api.weixin.qq.com/merchant/update?access_token=' . ACCESS_TOKEN;
        $data = '{
                    "product_id": "pkV_gjsTaeMWcNxzoVNWLXBRQlhM",
                    "product_base": {
                        "category_id": [
                            "537074298"
                        ],
                        "property": [
                            {
                                "id": "1075741879",
                                "vid": "1079749967"
                            },
                            {
                                "id": "1075754127",
                                "vid": "1079795198"
                            },
                            {
                                "id": "1075777334",
                                "vid": "1079837440"
                            }
                        ],
                        "name": "商品名",
                        "sku_info": [
                            {
                                "id": "$发货日期",
                                "vid": [
                                    "$大前天",
                                    "$大后天"
                                ]
                            }
                        ],
                        "main_img": "http://mmbiz.qpic.cn/mmbiz/4whpV1VZl2iccsvYbHvnphkyGtnvjD3ulEKogfsiaua49pvLfUS8Ym0GSYjViaLic0FD3vN0V8PILcibEGb2fPfEOmw/0",
                        "img": [
                            "http://mmbiz.qpic.cn/mmbiz/4whpV1VZl2iccsvYbHvnphkyGtnvjD3ulEKogfsiaua49pvLfUS8Ym0GSYjViaLic0FD3vN0V8PILcibEGb2fPfEOmw/0"
                        ],
                        "detail": [
                            {
                                "img": "http://mmbiz.qpic.cn/mmbiz/4whpV1VZl2iccsvYbHvnphkyGtnvjD3ul1UcLcwxrFdwTKYhH9Q5YZoCfX4Ncx655ZK6ibnlibCCErbKQtReySaVA/0"
                            }
                        ],
                        "buy_limit": 3
                    },
                    "sku_list": [
                        {
                            "sku_id": "$发货日期:$大前天;",
                            "price": 200,
                            "icon_url": "http://mmbiz.qpic.cn/mmbiz/4whpV1VZl2iccsvYbHvnphkyGtnvjD3ulEKogfsiaua49pvLfUS8Ym0GSYjViaLic0FD3vN0V8PILcibEGb2fPfEOmw/0",
                            "quantity": 20,
                            "product_code": "",
                            "ori_price": 0
                        },
                        {
                            "sku_id": "$发货日期:$大后天;",
                            "price": 100,
                            "icon_url": "http://mmbiz.qpic.cn/mmbiz/4whpV1VZl2iccsvYbHvnphkyGtnvjD3ulEKogfsiaua49pvLfUS8Ym0GSYjViaLic0FD3vN0V8PILcibEGb2fPfEOmw/0",
                            "quantity": 20,
                            "product_code": "",
                            "ori_price": 0
                        }
                    ],
                    "attrext": {
                        "location": {
                            "country": "中国",
                            "province": "广东省",
                            "city": "广州市",
                            "address": "T.I.T创意园"
                        },
                        "isPostFree": 0,
                        "isHasReceipt": 1,
                        "isUnderGuaranty": 0,
                        "isSupportReplace": 0
                    },
                    "delivery_info": {
                        "delivery_type": 0,
                        "template_id": 0,
                        "express": [
                            {
                                "id": 10000027,
                                "price": 100
                            },
                            {
                                "id": 10000028,
                                "price": 100
                            },
                            {
                                "id": 10000029,
                                "price": 100
                            }
                        ]
                    }
                }';

        $result = request_post($url, $data);
        return $result = ifRefreshAccessTokenAndRePost( $result, 'https://api.weixin.qq.com/merchant/update?access_token=', $data);
    }
}


?>