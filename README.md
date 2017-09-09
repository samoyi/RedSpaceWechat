# 微信公众号后台系统（PHP）


***
## 项目接入方法
### 第一步. 接入微信公众平台开发
1. 公众平台——开发——基本配置，设置IP白名单。否则不能获取access token
2. 参考文档的接入指南在公众平台官网填写相应设置，先不要提交接入
3. 将 `接入文件.php` 重命名为`index.php`并上传，使的上一步设置中的URL项指向重命名后
   的文件
4. 提交接入。如果上述各项配置正确，则接入成功。
5. 上述接入文件不再需要。在本项目设置好并上传后，本项目的`index.php`直接将其覆盖即可

### 第二步 填写 `configuration.php` 文件中的相关配置

### 第三步 上传项目
保证这里的`index.php`位置是接入时填写的URL所指向的



***
## 响应用户操作的流程
### 响应分发
1. 用户的任何操作，微信服务器都会推送到`index.php`文件
2. `index.php`会引入`messageDispatcher.php`，来根据消息类型将其分发到相应的module
3. 目前只响应用户文本消息和用户事件，其他类型的用户操作都统一使用默认处理方式。如果
   要响应其他类型的用户操作，则在`messageDispatcher.php`中添加新的分支，并增加相应
   的处理模块
4. 文本消息通过`messageDispatcher.php`分发至`module/textDispatcher.php`，事件操作
   分发至`module/eventDispatcher.php`,其他操作分发至`module/defaultHandler.php`

### 处理文本消息
1. 文本消息共分三类：
    * 基本关键词回复，由`module/textHander/basicTextHandler.php`处理。针对某个关键
      词可以便捷的设置回复文字消息或（和）图文消息，无法自定义更复杂的处理逻辑
    * 复杂关键词回复，由`module/textHander/complicatedTextHander.php`处理。针对某
      个关键词可以编写更复杂的处理逻辑
    * 没有匹配到关键词的文本消息的回复，由`module/textHander/noKeyWordsMatch.php`
      处理
2. 不管是基本关键词回复还是复杂关键词回复，只有在`module/textHander/keywords.php`
   的相应数组中添加了该关键词，才会引用相应的处理文件。通过该文件，可以在不删除某个
   关键词处理逻辑的前提下使其暂时不予处理。

### 处理事件操作  
在 `module/eventHandlers/`路径下，根据不同的事件类型设置其对应的处理文件，并通过
`module/eventDispatcher.php`进行分发



***
## 插件系统
所有基本功能以外的功能，都通过编写插件来实现
1. 每一个独立插件，都是`plugin`目录下一个独立的目录，目录名为插件名
2. 插件的入口文件必须是其目录下的`index.php`，这样可以方便统一的引用
3. 如果要使用该插件，必须要把其插件名（目录名）添加到`plugin\config.php`的数组里，
   且值设为`true`。如果设为`false`，则不会调用该插件。
4. 引用插件时，要通过全局的`requirePlugin()`并传入插件名（目录名）。调用后，就会引
   入该插件的`index.php`文件。虽然可以不通过该函数而直接引入插件文件，但这将导致无法
   统一管理插件的使用。



***
## 简单的管理功能
* 通过访问`manage/index.php`，可以进行一些简单的管理操作。
* 只有在输入密码登陆后才能进入该页面以及成功的执行里面的操作


***
## api
通过`api`目录下的文件向外提供接口



***
## TODO:  
* 应该添加监控功能，比如监控accesstoken过期和刷新情况
* 发送模板消息的方法和具体的模板解耦
* 整体整理的时候，一直使用的公众号做了迁移且一直没有重新启用微信小店，所以 `ProductManager.class.php` 和 `OrderManager.class.php `这两个类以及其他页微信小店
有关的功能无法测试。
* 完善api文件并添加调用日志
* manage 和 api 中是否有改用post但用了get的情况



***
## 其他
### 微信文档没有解释的错误码
* 40125 AppSecret错误



***
## 一些问题
