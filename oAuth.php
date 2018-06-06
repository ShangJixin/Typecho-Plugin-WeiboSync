<?php
//设置区开始
$client_id = '请去微博开放平台获取';
$client_secret = '请去微博开放平台获取';
$return_url = '你的应用回调地址';//例如:http(s)://yourdomain.com/任意路径/oAuth.php?return，回调地址要与微博开放平台保持一致，且放在正确的位置（oAuth.php这个文件可以不放在Typecho插件的目录下）
//设置区结束
if (isset($_GET['return']) && isset($_GET['code'])){
$api = 'https://api.weibo.com/oauth2/access_token?client_id='.$client_id.'&client_secret='.$client_secret.'&grant_type=authorization_code&code='.htmlspecialchars($_GET['code']).'&redirect_uri='.$return_url;
$urls = array();
$ch = curl_init();
$options =  array(
    CURLOPT_URL => $api,
    CURLOPT_POST => true,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POSTFIELDS => implode("\n", $urls),
);
curl_setopt_array($ch, $options);
$result = curl_exec($ch);
echo $result;
}elseif (isset($_GET['login'])){
?><html><head><meta name="viewport" content="width=device-width,minimum-scale=1,initial-scale=1" /><title>oAuth - WeiboSync</title></head><body bgcolor="white"><center><h1>oAuth - WeiboSync</h1></center><hr><center><a href="https://api.weibo.com/oauth2/authorize?client_id=<?php echo $client_id; ?>&redirect_uri=<?php echo $return_url; ?>&response_type=code" href="_blank">点击进行授权登录</a></center></body></html>
<?php 
}else{
echo 'Bad Request';
};
