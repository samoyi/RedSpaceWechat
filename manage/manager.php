<?php

	

class Manager

{

	public function getAutoReplyByTimeState()

	{

		$JSONObj = json_decode( file_get_contents('manageConfigration.js'));

		return $JSONObj->autoReplyByTime;

	}

}

	

	

?>

