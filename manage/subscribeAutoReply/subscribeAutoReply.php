<?php

	if( $_POST['act'] === 'copy' )
	{
		if( copy('subscribeAutoPlayText.json', 'subscribeAutoPlayText_copy.json') ){
			exit('true');
		}
	}

	if( $_POST['act'] === 'set' )
	{
		$sNewJSON = stripslashes(urldecode($_POST['newJSON']));
		if( file_put_contents('subscribeAutoPlayText.json', $sNewJSON) ){
			exit('true');
		}
	}

	echo 'false';

?>
