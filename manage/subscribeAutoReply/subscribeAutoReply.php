<?php
//date_default_timezone_set("Asia/Hong_Kong");

	if( isset($_GET['command']) )
	{
		switch( $_GET['command'] )
		{
			case 'copySubscribeAutoReplyJSON':
			{
				if( copy('../JSONData/subscribeAutoPlayText.json', '../JSONData/subscribeAutoPlayText_copy.json') )
				{
					echo 'success';
				}
				break;
			}
		}
	}

	if( isset($_POST['newJSON']) )
	{
		$sNewJSON = stripslashes(urldecode($_POST['newJSON']));
		if( file_put_contents('../JSONData/subscribeAutoPlayText.json', $sNewJSON) )
		{
			echo 'success';
		}
	}




?>
