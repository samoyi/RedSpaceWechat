
<?php
	header('Content-Type: text/html; charset=UTF-8');//TODO 为什么不加这个会出现乱码汉字。并没有输出汉字的代码
	$order_id = '10295333824950396964';
	$msg = '亲，已接到您的订单了。';

	include('../publicFunctions.php'); 
	include('../configration.php');

	if( $_POST['textCustomMessage'] && $_POST['orderNumber'] )
	{	
		include('../class/MessageManager.class.php');
		$messageManager = new MessageManager();
		$messageManager->sendCustomMessage( $_POST['orderNumber'], $_POST['textCustomMessage'] );
	}
	
?>
