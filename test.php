
<?php

//获取当前的域名:  
echo $_SERVER['SERVER_NAME'];  
echo "<br />";

//获取来源网址,即点击来到本页的上页网址  
echo $_SERVER["HTTP_REFERER"];  
echo "<br />";

echo $_SERVER['REQUEST_URI'];//获取当前域名的后缀  
echo "<br />";

echo $_SERVER['HTTP_HOST'];//获取当前域名  
echo "<br />";

echo dirname(__FILE__);//获取当前文件的物理路径  
echo "<br />";

echo dirname(__FILE__)."/../";//获取当前文件的上一级物理路径  
?>