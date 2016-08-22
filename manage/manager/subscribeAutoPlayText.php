
<?php
	header('Content-Type: text/html; charset=UTF-8');
	$arr = json_decode( file_get_contents('../JSONData/subscribeAutoPlayText.json'));
    
    foreach( $arr as $value)
    {
        //echo '<input type="text" value="' . $value . '" /><br />';
        echo $value . "<br />";
    }
	
?>
