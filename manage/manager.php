<?php

	

class Manager

{

	public function getAutoReplyByTimeState()

	{

		$JSONObj = json_decode( file_get_contents('manage/manageConfiguration.json'));

		return $JSONObj->autoReplyByTime;

	}

}

	

	

?>

