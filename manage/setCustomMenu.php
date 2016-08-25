<?php

/*
 *  自定义菜单相关操作
 *
 *
 */
include('../configration.php'); // 公众号配置文件
include('../publicFunctions.php'); // 公共函数
include('../WechatPushed.php'); // 获取微信后台推送信息

include('../class/CustomMenu.class.php');
$customMenu = new CustomMenu();

//$customMenuData 为自定义菜单各按钮设置
/*$customMenuData =  '{
                   "button":[
                   {  
                        "name":"🎂蛋糕订购",
                         "sub_button":[
                         {    
                             "type":"view",
                             "name":"🎂在线订购",
                             "url":"http://mp.weixin.qq.com/bizmall/mallshelf?id=&t=mall/list&biz=MjM5NzA2OTIwMQ==&shelf_id=1&showwxpaytitle=1#wechat_redirect"
                          },
                          {
                             "type":"click",
                             "name":"蛋糕订购操作指南",
                             "key":"customMenuKey01"
                          },
                          {  
                                "type":"click",
                                "name":"我的订单",
                                "key":"customMenuKey02"
                          }]
                    },
                    {
                         "name":"自助服务",
                         "sub_button":[
                         {    
                             "type":"view",
                             "name":"附近门店",
                             "url":"http://red-space.cn/list/index.php"
                          },
                          {  
                                "type":"view",
                                "name":"在线招聘",
                                "url":"http://red-space.cn/recruitment/index.php"
                          },
                          {  
                                "type":"click",
                                "name":"人工客服",
                                "key":"customMenuKey12"
                          },
                          {  
                                "type":"view",
                                "name":"DIY报名",
                                "url":"http://red-space.cn/diy-mobile2016/index.php"
                            }]
                     },
                     {  
                        "name": "中秋福利",
                        "sub_button":[
                        {  
                            "type":"view",
                            "name":"中秋礼盒",
                            "url":"http://mp.weixin.qq.com/s?__biz=MjM5NzA2OTIwMQ==&mid=2650756246&idx=1&sn=1f51b20abd547937712ebd7b07213e36#rd"
                        },
                        {
                            "type":"click",
                            "name":"优惠券天天抢",
                            "key":"customMenuKey21"
                        },
                        {
                            "type":"click",
                            "name":"现烤月饼二送一",
                            "key":"customMenuKey22"
                        },
                        {
                            "type":"click",
                            "name":"月饼新品",
                            "key":"customMenuKey23"
                        }]
                    }]
               }';
*/

    $customMenuData = file_get_contents("JSONData/customMenu.json");
    echo $customMenu->createMenu( $customMenuData);   // 设置自定义菜单

?>