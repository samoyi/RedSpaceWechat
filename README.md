# RedSpaceWechat

修改自定义菜单和上传文件都放到管理里面


公共函数文件中直接使用了相对路径，导致如果在其他目录引用就会发生错误。
现在设置自定义菜单的文件只能放在wechat根目录。

使用以下方法改写公共函数文件

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


