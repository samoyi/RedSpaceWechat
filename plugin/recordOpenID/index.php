<?php

	// 不是事件消息 或者 是事件消息但不是这三种事件
	if( (!defined('EVENT_TYPE')) || (EVENT_TYPE !== 'unsubscribe' && EVENT_TYPE !== 'merchant_order' && EVENT_TYPE !== 'TEMPLATESENDJOBFINISH') ) // 取消关注事件会发送空的数据，因此会清空原数据
	{
		require PROJECT_ROOT . 'class/UserManager.class.php';
		$UserManager = new UserManager();
		$UserManager->noteUseBasicInfo();
	}
	elseif( EVENT_TYPE === 'unsubscribe' ) // 取消关注的只修改是否关注的那一栏数据
	{
		require PROJECT_ROOT . 'class/MySQLiController.class.php';
	    $MySQLiController = new MySQLiController( $dbr );
		$MySQLiController->updateData(
						DB_TABLE,
						array('isSubscribing', 'modifyTime', 'type'),
						array(0, date("Y-m-d G:i:s"), 'unsubscribe'),
						'openID="' . USERID . '"');

	}

?>
