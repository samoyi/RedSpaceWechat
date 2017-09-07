<?php

    include('class/OrderManager.class.php');
    $orderManager = new OrderManager();
    $orderDetail = $orderManager->getOrderDetail(ORDERID);
    $messageManager->sendTemplateMessage($orderDetail, USERID, '', ''); // 购买成功消息


    // 插件：记记录订单信息 ------------------------------------------------------
    requirePlugin('recordOpenID');
    noteOrderInfo($orderDetail);


?>
