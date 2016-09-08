# 微信公众号后台系统（PHP）


    press "i"
    write your merge message
    press "esc"
    write ":wq"
    then press enter


## TODO:  
1. 回复文字链接之后还必须要回个空消息才不会报错(关注回复为什么不用)
2. 本来发卡券使用a.class，后来把发卡券功能放到了b.class，但有一个地方仍然使用a.class。怎么避免？
3. 如果自定义菜单数据中修改了click的按钮，则还需要在事件处理模块中进行修改。有什么可以只在一个地方修改的方法。
4. 应该添加监控功能，比如监控accesstoken过期和刷新情况
5. 新关注用户回复消息也要使用MessageManager.class.php中的客服消息函数



删除有数据信息的历史版本中  


.gitignore似乎只在文件尚未存在时设置才行
http://stackoverflow.com/questions/37937984/git-refusing-to-merge-unrelated-histories