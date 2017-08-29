<?php

    include('class/OrderManager.class.php');
    $orderManager = new OrderManager();
    $orderDetail = $orderManager->getOrderDetail(ORDERID);
    $messageManager->sendTemplateMessage($orderDetail, USERID, '', ''); // 购买成功消息

	// 记录订单信息
	$orderManager->noteOrderInfo($orderDetail);

	// 邮件提醒
	$sendOrdermail_orderDetail = $orderDetail;
	require "manage/sendOrderMail.php";
	/*
	 * TODO
	 * 本来想把 manage/sendOrderMail.php 中的代码封装为一个函数，并把 $orderDetail 作为参数传入，
	 * 但是这样做导致无法发送邮件
	 */

	// // 邮件提醒
	// require "plugin/sendmail.php";
	// $mail_to = '601610407@qq.com';//接收人邮箱
	// $mail_subject = '红房子微信小店新订单提醒';//邮件标题
	// $mail_message = '红房子微信小店新订单提醒';//邮件内容
	// sendmail($mail_to, $mail_subject, $mail_message);


?>
