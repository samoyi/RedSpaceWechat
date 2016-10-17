<?php

class ProductManager
{
    // 获取商品分类
    // 一共三级。获取第一级传参 1， 获取某第一级的第二级传参该第一级ID， 获取某第二级的第三级传参该第二级ID
    public function getCategoryListArray($nCateID)
    {
        $url = 'https://api.weixin.qq.com/merchant/category/getsub?access_token=' . ACCESS_TOKEN;
        $data = '{
                    "cate_id": ' . $nCateID . '
                }';
        $result = request_post($url, $data);
        $result = ifRefreshAccessTokenAndRePost( $result, 'https://api.weixin.qq.com/merchant/category/getsub?access_token=', $data);
        return json_decode( $result )->cate_list;

        // 相机 537874913
        // 单反相机ID 537074298
    }

    // 获取指定分类的所有属性
    public function getPropertyListArray($nCateID)
    {   
        $url = 'https://api.weixin.qq.com/merchant/category/getproperty?access_token=' . ACCESS_TOKEN;
        $data = '{
                    "cate_id": ' . $nCateID . '
                }';
        $result = request_post($url, $data);
        $result = ifRefreshAccessTokenAndRePost( $result, 'https://api.weixin.qq.com/merchant/category/getproperty?access_token=', $data);
        return json_decode( $result )->properties;
    }

    // 获得$nCategoryID类别中$sProperty属性的property_value数组中每一项的id组成的关联数组，键为对应的name
    // 例如传参 (537074298, "特殊功能")，就是获得数码相机类别中特殊功能属性的所有属性值
    public function getPropertyValueIDArray( $nCategoryID, $sProperty )
    {
        $aPropertyList = $this->getPropertyListArray($nCategoryID); // 所有的属性
        // 循环所有的属性，找到第二个参数中的属性，并将保存了该属性的所有值的数组保存到 $aPropertyValue中
        $aPropertyValue = array();
        foreach($aPropertyList as $value)
        {
            if( $value->name === $sProperty)
            {
                $aPropertyValue = $value->property_value;
                break;
            }
        }
        // 循环该数组，提取每一项的id和name，以name为键id为值得数组
        $aPropertyNameArray = array();
        foreach($aPropertyValue as $value)
        {
            $aPropertyKeyArray[$value->name] = $value->id;
        }
        return $aPropertyKeyArray;
    }

    // 获得$nCategoryID类别中$sProperty属性的ID的关联数组，键为对应的name
    public function getPropertyIDArray( $nCategoryID )
    {
        $aPropertyList = $this->getPropertyListArray($nCategoryID); // 所有的属性
        // 循环所有的属性，找到第二个参数中的属性，并将保存了该属性的所有值的数组保存到 $aPropertyValue中
        $aPropertyID = array();
        foreach($aPropertyList as $value)
        {
            $aPropertyID[$value->name] = $value->id;
        }
        return $aPropertyID;
    }


    // 将某个商品product_info对象中的property属性中的中文id和vid变为对应的数字
    // 该方法不会直接修改商品，只会修改product_info对象，并返回product_info对象
    // 在修改商品信息的时候，要先获取该信息，但获取到的商品属性中的id和vid会变成中文。而修改提交时又需要是数字才行
    // 第二个参数是这些属性所属的类别的类别id
    public function propertyListNameToID( $sProductID, $nCategoryID )
    {
        $product_info = $this->queryProductInfo($sProductID);
        $aCategoryList = $product_info->product_base->property; // 该商品的property属性
        $aProperty = $this->getPropertyIDArray( $nCategoryID ); // 该商品的所有属性

        // 根据$aCategoryName，将当前商品
        foreach($aCategoryList as $value)
        {   
            $aPropertyValue = $this->getPropertyValueIDArray($nCategoryID, $value->id);
            $value->vid = $aPropertyValue[$value->vid];
            $value->id = $aProperty[$value->id];
        }
        return $product_info;
    }

    // 获取商品分组
    protected function getProductGroupArray()
    {
        $url = 'https://api.weixin.qq.com/merchant/group/getall?access_token=' . ACCESS_TOKEN;
        return json_decode(httpGet( $url ))->groups_detail;
    }

    // 获取货架
    protected function getShelfArray()
    {
        $url = 'https://api.weixin.qq.com/merchant/shelf/getall?access_token=' . ACCESS_TOKEN;
        return json_decode(httpGet( $url ))->shelves;
    }


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
    public function queryProductInfo( $sProductID )
    {
        $url = 'https://api.weixin.qq.com/merchant/get?access_token=' . ACCESS_TOKEN;
        $data = '{
                    "product_id": "' . $sProductID . '"
                }';
        $result = request_post($url, $data);
        $result = ifRefreshAccessTokenAndRePost( $result, 'https://api.weixin.qq.com/merchant/get?access_token=', $data);
        return json_decode($result)->product_info;
    }


    // 获取指定状态的所有商品
    public function queryProductArray( $nStatus = 0)
    {   
        $url = 'https://api.weixin.qq.com/merchant/getbystatus?access_token=' . ACCESS_TOKEN;
        $data = '{
                    "status": ' . $nStatus . '
                }';
        $result = request_post($url, $data);
        $result = ifRefreshAccessTokenAndRePost( $result, 'https://api.weixin.qq.com/merchant/getbystatus?access_token=', $data);
        $aProducts = json_decode($result)->products_info;    
        return $aProducts; 
    }


    
    // 获取商品 sku_info
    public function querySkuInfoArray( $sProductID )
    {
        return $this->queryProductInfo( $sProductID )->product_base->sku_info;
    }


    // 修改商品 
    // 未完成 TODO 。获得了POST数据中未修改的内容。如果要修改某一部分，把未修改的和修改的拼接为完整的POST数据
    // 修改时提交的数据中比queryProductInfo获得的数据中多出了一项status
    /* TODO 必须手动先给 价格&库存 每个大的行添加一张图片才行。如果没有，则获得的icon_url是空字符串，但提交的时候该属性又不允许是空字符串
    */
    public function modifyProduct( $sProductID, $nCategoryID )
    {
        //$oldProduct_info = $this->queryProductInfo( $sProductID );
        $oldProduct_info = $this->propertyListNameToID( $sProductID, $nCategoryID );

        // 手动修改库存。不知道库存为什么
        $aSkuList = $oldProduct_info->sku_list;
        foreach($aSkuList as $value)
        {
            $value->quantity = 25;
        }
        
        unset( $oldProduct_info->status );
        $oldProduct_info->product_base->name = 'ceshi6';
        
        $aProperty = $oldProduct_info->product_base->property;
        
        $data = json_encode( $oldProduct_info ); 
        $data = decodeUnicode($data);
        $data = str_replace('\\/', '/', $data);
        $data = str_replace('\"', '"', $data);

        $data = str_replace('大前天', '后天', $data);
        $data = str_replace('大后天', '明天', $data);
        file_put_contents("err.txt", $data . "\n\n\n\n", FILE_APPEND);
        file_put_contents("err.txt", json_encode($data) . "\n\n\n\n", FILE_APPEND);

        

        $url = 'https://api.weixin.qq.com/merchant/update?access_token=' . ACCESS_TOKEN;
        $result = request_post($url, $data);
        return $result = ifRefreshAccessTokenAndRePost($result, 'https://api.weixin.qq.com/merchant/update?access_token=', $data);

        /*$oldProductBase = $oldProduct_info->product_base;
        $oldSkuList = $oldProduct_info->sku_list;
        $oldAttrext = $oldProduct_info->attrext;
        $oldDeliveryInfo = $oldProduct_info->delivery_info; */
    }

    // 修改某项sku_info.
    // 第二个参数是该项的id，第三个参数是该id对应的vid数组
    
    //public function modifySkuInfo( $sProductID, $sID, $aVID ) TODO
    public function modifySkuInfo()
    {   
        $sProductID = 'pkV_gjsTaeMWcNxzoVNWLXBRQlhM';
        $oldProduct_info = $this->queryProductInfo( $sProductID );
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


    public function addProduct( $data )
    {
        $url = 'https://api.weixin.qq.com/merchant/create?access_token=' . ACCESS_TOKEN;
        
        $result = request_post($url, $data);
        $result = ifRefreshAccessTokenAndRePost( $result, 'https://api.weixin.qq.com/merchant/create?access_token=', $data);
        return $result;
    }


    // 临时测试
    // 直接手写date属性可以修改成功，但获取原data再修改为新的data之后修改出问题
    public function temp_modifyProduct($data)
    {
        $url = 'https://api.weixin.qq.com/merchant/update?access_token=' . ACCESS_TOKEN;
        $result = request_post($url, $data);
        return $result = ifRefreshAccessTokenAndRePost($result, 'https://api.weixin.qq.com/merchant/update?access_token=', $data);
    }
}


?>