<?php

	define("TOKEN", ""); // 只需要填写公众平台TOKEN


	$tmpArr = array(TOKEN, $_GET["timestamp"],  $_GET["nonce"]);
	sort($tmpArr, SORT_STRING);
	$tmpStr = implode( $tmpArr );
	$tmpStr = sha1( $tmpStr );

	if( $tmpStr === $_GET["signature"] ){
		echo $_GET["echostr"];
	}

?>
