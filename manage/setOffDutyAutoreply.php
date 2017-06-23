<?php
	
	if($_POST["off_duty_auto_reply_text"]){
		file_put_contents('offDutyAutoreplyText.json', $_POST["off_duty_auto_reply_text"]);
	}
	
?>