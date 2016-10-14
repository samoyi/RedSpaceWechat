<?php

class ProductManager
{
    // 获取指定状态的所有商品的 product_id 及对应 name 组成的数组
    public function queryProductIDs( $nStatus = 0)
    {   
        $url = 'https://api.weixin.qq.com/merchant/getbystatus?access_token=' . ACCESS_TOKEN;
        $data = '{
                    "status": ' . $nStatus . '
                }';
        $result = request_post($url, $data);
        $result = ifRefreshAccessTokenAndRePost( $return, 'https://api.weixin.qq.com/merchant/getbystatus?access_token=', $data);
        $aProducts = json_decode($result)->products_info; // 所有商品
        $aIDs = array();
        foreach( $aProducts as $value )
        {
            $aIDs[$value->product_id] = $value->product_base->name;
        }
        //file_put_contents("err.txt", json_encode($aIDs) ); 
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
file_put_contents("err.txt", json_encode($aProducts[2]) ); 
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


        file_put_contents("err.txt", json_encode($oldDeliveryInfo) ); 
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
    public function modifySkuInfo( $sProductID, $sID, $aVID )
    {
        $oldProduct_info = $this->queryProductBase( $sProductID )->product_info;
        $oldProductBase = $oldProduct_info->product_base;
        $oldSkuList = $oldProduct_info->sku_list;
        $oldAttrext = $oldProduct_info->attrext;
        $oldDeliveryInfo = $oldProduct_info->delivery_info;

        $oldSkuInfo = $oldProductBase->sku_info;

        foreach( $oldSkuInfo as $key=>$value )
        {
            if( $value->id === $sID )
            {
                $oldSkuInfo[$key]->vid = $aVID;
                break;
            }
        }

        $url = 'https://api.weixin.qq.com/merchant/update?access_token=' . ACCESS_TOKEN;
        $oldProduct_info = str_replace(" ", "", json_encode( $oldProduct_info )); // 删除所有空格，让decodeUnicode正确转换
        $data = decodeUnicode( $oldProduct_info );
        $result = request_post($url, $data);
        $result = ifRefreshAccessTokenAndRePost( $result, 'https://api.weixin.qq.com/merchant/update?access_token=', $data);

        function decodeUnicode($str)
        {
            return preg_replace_callback('/\\\\u([0-9a-f]{4})/i',
                create_function(
                    '$matches',
                    'return mb_convert_encoding(pack("H*", $matches[1]), "UTF-8", "UCS-2BE");'
                ),
                $str);
        }

        file_put_contents("err.txt", serialize($oldProduct_info) );
    }
}


?>