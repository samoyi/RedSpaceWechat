<?php



class Manager

{

	public function getAutoReplyByTimeState()

	{

		$JSONObj = json_decode( file_get_contents('manage/switchAutoReply/autoReplyState.json'));

		return $JSONObj->autoReplyByTime;

	}

}





?>
