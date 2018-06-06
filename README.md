# Typecho-Plugin-WeiboSync
当发布或更新文章时,能够将文章的标题和链接同步至你的微博
![演示效果](https://i.loli.net/2018/06/06/5b17ea49aae24.png)
### 使用说明
1.下载之后，请把文件夹重新命名为`WeiboSync`后再启用插件，否则可能会出问题
2.文件夹内的`oAuth.php`可放在你站点的任意目录下
3.`oAuth.php`设计本来只是一次性设置的活，故没给集成到插件本体中，所以`oAuth.php`内的配置项要手动配置
4.使用之前**务必**先把插件设置内的两个选项填好了，否则提交文章的时候可能会白屏
### 使用步骤
1.把`Oauth.php`放在一个你比较喜欢的位置，**例如**我把他放在了`https://www.jimoe.cn/oAuth.php`那块
2.进入这个文件，填写一些必要的信息(在此之前，你要注册好你的微博开放平台，申请的类型为**微连接 - 网页应用**),`$client_id`和`$client_secret`填写你在微博开放平台获取到的这两个值(位于**应用信息->基本信息**)**client_secret务必要妥善保存，不要告诉其他人**。下面是一段填写**示例**
```php
<?php
//设置区开始
$client_id = '在你微博开放平台里自己对号入座去';
$client_secret = '在你微博开放平台里自己对号入座去';
$return_url = '如上文中第一步的例子 https://www.jimoe.cn/oAuth.php';
//设置区结束
```
3.去微博开放平台内的**基本信息->高级信息**,把授权回调页那俩给设置成`oAuth.php`所在的文件路径后加上`?return`,即`oAuth.php?return`，以第一步举的例子**为例**，完整的路径就应该是`https://www.jimoe.cn/oAuth.php?return`
4.访问你的`oAuth.php?login`，以第一步的例子**为例**，链接就应该是`https://www.jimoe.cn/oAuth.php?login`，点击进去之后里面有个蓝链，点进去输入自己微博的账号密码(这个页面即为微博的oAuth)，然后登录完之后微博的oAuth会返回一段json串，大概是这样的
```json
{"access_token":"手动马赛克","remind_in":"XXXX","expires_in":XXXXX,"uid":"XXXXXX","isRealName":"true"}
```
"手动马赛克"的那个部分(`access_token`)即为我们所需要的参数,这个参数同样也要**妥善保存**，不要告诉其他人。此处的`oAuth.php`不要删除，微博的access_token是有过期时间的，没过审核的话保鲜期为一天(测试用)，过审核的话保鲜期为30天。
5.将从Github下载下来的文件夹改名为`WeiboSync`(同"使用**说明**-1")，放入Typecho的plugins文件夹内，同时你可以选择性的把`oAuth.php`这个文件删掉(如果你在"使用**步骤**-1"时将这个文件转移到了其他的地方)
6.进入后台启用插件，填写好`Access_Key`(**即access_token**)，并自定义设置选项"微博大括号内内容"，两者**均为必填选项**，否则提交文章时会白屏(同"使用**说明**-4")
7.记得定时更新你的`Access_Key`(**即access_token**)，保鲜期一旦过了，插件本体就失去了对你微博账户的控制权，请使用`oAuth.php`重新生成新的access_token
### 常见BUG
1.返回的值和这里介绍的不一致&PHP报错
  *请确定你输入的值的正误*
2.昨天能提交，第二天天咋就趴窝了？
  *未审核应用请尽快去审核，未审核应用的token保鲜期为一天*
  *如果已经审核过了的话，请注意token的保鲜期为30天，请注意更新*
3.插件部分无法使用
  *请将下载下来的文件夹的名字删成`WeiboSync`后再去启用插件，且`WeiboSync`文件夹内必须有那个`Plugin.php`文件，`oAuth.php`可放入别处，看你具体爱好
4.等待补充
