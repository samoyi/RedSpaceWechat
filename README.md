# 微信公众号后台系统（PHP）

## TODO:  
1. 回复文字链接之后还必须要回个空消息才不会报错(关注回复为什么不用)
2. 修改AppSecret
3. 如果自定义菜单数据中修改了click的按钮，则还需要在事件处理模块中进行修改。有什么可以只在一个地方修改的方法。
4. 应该添加监控功能，比如监控accesstoken过期和刷新情况
5. 数据和逻辑绑定太紧密。比如修改了自定义菜单的JSON数据还要去事件处理的地方该响应代码。
6. 检测access token并重发的那个函数能否直接写进前一行的发送函数中？或者给这个发送函数带一个参数，使这个函数可以自主选择是否重发，这样就不用写独立的两行了。
7. 逻辑框架要方便的插入新功能、新插件
8. 记录用户地址和收获地址是国家、省、市、地区、具体地址连在一起的，后期如果要统计某一项则无法进行


## 一些问题
1. 之前把记录用户的代码放在处理用户交互之前，大概是由于网络延迟导致少量用户没有在5s内接到响应从而出现“公众号无法服务”的情况。