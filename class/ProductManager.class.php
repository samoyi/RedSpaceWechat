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
    public function modifyProduct( $sProductID )
    {
        $oldProduct_info = $this->queryProductBase( $sProductID )->product_info;
        $oldProductBase = $oldProduct_info->product_base;
        $oldSkuList = $oldProduct_info->sku_list;
        $oldAttrext = $oldProduct_info->attrext;
        $oldDeliveryInfo = $oldProduct_info->delivery_info;

        file_put_contents("err.txt", json_encode($oldDeliveryInfo) ); 
    }
}


?>