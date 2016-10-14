<?php

/*
 * 微信后台将消息、事件等推送到该文件
 *
 */

include('configration.php'); // 公众号配置文件
include('publicFunctions.php'); // 公共函数  TODO 这个文件依赖configration.php
include('WechatPushed.php'); // 获取微信后台推送信息


// $url = 'https://api.weixin.qq.com/merchant/update?access_token=' . ACCESS_TOKEN;
// $data = '{
//             "product_id": "pkV_gjsTaeMWcNxzoVNWLXBRQlhM",
//             "product_base": {
//                 "category_id": [
//                     "537074298"
//                 ],
//                 "property": [
//                     {
//                         "id": "1075741879",
//                         "vid": "1079749967"
//                     },
//                     {
//                         "id": "1075754127",
//                         "vid": "1079795198"
//                     },
//                     {
//                         "id": "1075777334",
//                         "vid": "1079837440"
//                     }
//                 ],
//                 "name": "商品名",
//                 "sku_info": [
//                     {
//                         "id": "$发货日期",
//                         "vid": [
//                             "$大前天",
//                     		"$大后天"
//                         ]
//                     }
//                 ],
//                 "main_img": "http://mmbiz.qpic.cn/mmbiz/4whpV1VZl2iccsvYbHvnphkyGtnvjD3ulEKogfsiaua49pvLfUS8Ym0GSYjViaLic0FD3vN0V8PILcibEGb2fPfEOmw/0",
//                 "img": [
//                     "http://mmbiz.qpic.cn/mmbiz/4whpV1VZl2iccsvYbHvnphkyGtnvjD3ulEKogfsiaua49pvLfUS8Ym0GSYjViaLic0FD3vN0V8PILcibEGb2fPfEOmw/0"
//                 ],
//                 "detail": [
//                     {
//                         "img": "http://mmbiz.qpic.cn/mmbiz/4whpV1VZl2iccsvYbHvnphkyGtnvjD3ul1UcLcwxrFdwTKYhH9Q5YZoCfX4Ncx655ZK6ibnlibCCErbKQtReySaVA/0"
//                     }
//                 ],
//                 "buy_limit": 3
//             },
//             "sku_list": [
//                 {
// 		            "sku_id": "$发货日期:$大前天;",
// 		            "price": 200,
// 		            "icon_url": "http://mmbiz.qpic.cn/mmbiz/4whpV1VZl2iccsvYbHvnphkyGtnvjD3ulEKogfsiaua49pvLfUS8Ym0GSYjViaLic0FD3vN0V8PILcibEGb2fPfEOmw/0",
// 		            "quantity": 20,
// 		            "product_code": "",
// 		            "ori_price": 0
// 		        },
// 		        {
// 		            "sku_id": "$发货日期:$大后天;",
// 		            "price": 100,
// 		            "icon_url": "http://mmbiz.qpic.cn/mmbiz/4whpV1VZl2iccsvYbHvnphkyGtnvjD3ulEKogfsiaua49pvLfUS8Ym0GSYjViaLic0FD3vN0V8PILcibEGb2fPfEOmw/0",
// 		            "quantity": 20,
// 		            "product_code": "",
// 		            "ori_price": 0
// 		        }
//             ],
//             "attrext": {
//                 "location": {
//                     "country": "中国",
//                     "province": "广东省",
//                     "city": "广州市",
//                     "address": "T.I.T创意园"
//                 },
//                 "isPostFree": 0,
//                 "isHasReceipt": 1,
//                 "isUnderGuaranty": 0,
//                 "isSupportReplace": 0
//             },
//             "delivery_info": {
//                 "delivery_type": 0,
//                 "template_id": 0,
//                 "express": [
//                     {
//                         "id": 10000027,
//                         "price": 100
//                     },
//                     {
//                         "id": 10000028,
//                         "price": 100
//                     },
//                     {
//                         "id": 10000029,
//                         "price": 100
//                     }
//                 ]
//             }
//         }';


// // $result = request_post($url, $data);
// // $result = ifRefreshAccessTokenAndRePost( $result, 'https://api.weixin.qq.com/merchant/update?access_token=', $data);
// // echo $result; 


// // 获取sku_info
// include('class/ProductManager.class.php');
// $productManager = new ProductManager();
// $result = $productManager->modifySkuInfo();
// echo $result;

include('class/ProductManager.class.php');
$productManager = new ProductManager();
$nAllProductNum = count( $productManager->queryProductIDs() );
$nOnProductNum = count( $productManager->queryProductIDs(1) );
$nOffProductNum = count( $productManager->queryProductIDs(2) );
echo $nAllProductNum;
echo ' ';
echo $nOnProductNum;
echo ' ';
echo $nOffProductNum;

?>