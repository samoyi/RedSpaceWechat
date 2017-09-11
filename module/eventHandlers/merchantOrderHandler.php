<?php

    require 'class/OrderManager.class.php';
    $orderManager = new OrderManager();
    $orderDetail = $orderManager->getOrderDetail(ORDERID);

    require 'class/TemplateMessage.class.php';
    $templateMessage = new TemplateMessage();
    $templateMessage->orderPaymentNotice($orderDetail, USERID); // 购买成功消息


    // 插件：记记录订单信息 ------------------------------------------------------
    requirePlugin('noteOrderInfo');
    noteOrderInfo($orderDetail);


?>
