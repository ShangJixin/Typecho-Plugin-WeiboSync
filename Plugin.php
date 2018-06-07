<?php
if (!defined('__TYPECHO_ROOT_DIR__')) exit;
/**
 * 当发布文章时,能够将文章的标题和链接同步至你的微博。<a href="https://github.com/ShangJixin/Typecho-Plugin-WeiboSync">配置方法</a>。使用前请确定你的机器支持curl
 * 
 * @package WeiboSync
 * @author 尚寂新
 * @version 1.0.1
 * @link https://www.jimoe.cn
 */
class WeiboSync_Plugin implements Typecho_Plugin_Interface
{
    /**
     * 激活插件方法,如果激活失败,直接抛出异常
     * 
     * @access public
     * @return void
     * @throws Typecho_Plugin_Exception
     */
    public static function activate()
    {
		Typecho_Plugin::factory('Widget_Contents_Post_Edit')->finishPublish = array('WeiboSync_Plugin', 'justdoit');
		Typecho_Plugin::factory('Widget_Contents_Page_Edit')->finishPublish = array('WeiboSync_Plugin', 'justdoit');
    }
    
    /**
     * 禁用插件方法,如果禁用失败,直接抛出异常
     * 
     * @static
     * @access public
     * @return void
     * @throws Typecho_Plugin_Exception
     */
    public static function deactivate(){}
    
    /**
     * 获取插件配置面板
     * 
     * @access public
     * @param Typecho_Widget_Helper_Form $form 配置面板
     * @return void
     */
    public static function config(Typecho_Widget_Helper_Form $form)
    {
		$qianzhui = new Typecho_Widget_Helper_Form_Element_Text('qianzhui', null, '尚寂新の博客', _t('微博大括号内内容'), '【对应这里内容】博主发表了一篇文章《文章名》，文章链接:网页链接');
		$form->addInput($qianzhui);
		$accesskey = new Typecho_Widget_Helper_Form_Element_Text('accesskey', null, '请去获取', _t('Access_Key'), '请去获取');
		$form->addInput($accesskey);
    }
    /**
     * 个人用户的配置面板
     * 
     * @access public
     * @param Typecho_Widget_Helper_Form $form
     * @return void
     */
    public static function personalConfig(Typecho_Widget_Helper_Form $form){}
	
	
	
    /**
     * 插件实现方法
     * 
     * @access public
     * @return void
     */
	public static function justdoit($contents, $class)
    {
		//如果文章属性为隐藏或滞后发布改良版,加入创建时间和修改时间不一致则返回不执行
		if( 'publish' != $contents['visibility'] || $contents['created'] > $contents['modified']){
            return;
        }
		//必填项如果没填的话直接停止
		if( is_null(Typecho_Widget::widget('Widget_Options')->plugin('WeiboSync')->accesskey) || is_null(Typecho_Widget::widget('Widget_Options')->plugin('WeiboSync')->qianzhui)){
            return;
        }
		//开始准备数据准备发送
		$get_post_title = $contents['title'];
		$weibo_status = '【'.Typecho_Widget::widget('Widget_Options')->plugin('WeiboSync')->qianzhui.'】博主发表了一篇文章《'.strip_tags($get_post_title).'》，文章链接:'.$class->permalink;
		$usetxt = urlencode($weibo_status);
		$weibo_token = Typecho_Widget::widget('Widget_Options')->plugin('WeiboSync')->accesskey;
		$weibo_api = 'https://api.weibo.com/2/statuses/share.json?access_token='.$weibo_token.'&status='.$usetxt;
		$weibo_push = array();
		$weibo_ch = curl_init();
		$weibo_options =  array(
			CURLOPT_URL => $weibo_api,
			CURLOPT_POST => true,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_POSTFIELDS => implode("\n", $weibo_push),
		);
		curl_setopt_array($weibo_ch, $weibo_options);
		$weibo_result = curl_exec($weibo_ch);
    } 

}
